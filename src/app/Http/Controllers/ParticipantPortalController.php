<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ParticipantPortalController extends Controller
{
    public function loginForm()
    {
        if (session()->has('participant_email')) {
            return redirect()->route('participant.dashboard');
        }

        $this->generateCaptcha();

        return view('pages.participant.login');
    }

    public function refreshCaptcha()
    {
        $this->generateCaptcha();

        return response()->json([
            'num1' => session('captcha_num1'),
            'num2' => session('captcha_num2'),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'registration_code' => 'nullable|string',
            'password' => 'nullable|string',
            'captcha_answer' => 'required|integer',
        ]);

        $expected = session('captcha_num1', 0) + session('captcha_num2', 0);
        if ((int) $request->captcha_answer !== $expected) {
            $this->generateCaptcha();
            return back()->withErrors([
                'captcha_answer' => __('Jawaban captcha salah.'),
            ])->withInput();
        }

        // Try password login first if password is provided
        if ($request->filled('password')) {
            $registration = EventRegistration::where('email', $request->email)
                ->whereNotNull('password')
                ->get()
                ->first(fn ($r) => Hash::check($request->password, $r->password));

            if (! $registration) {
                $this->generateCaptcha();
                return back()->withErrors([
                    'credentials' => __('Email atau password tidak valid.'),
                ])->withInput();
            }

            $request->session()->put('participant_email', $registration->email);
            $request->session()->put('participant_name', $registration->name);
            $request->session()->put('participant_password_set', true);

            return redirect()->route('participant.dashboard');
        }

        // First-time login with registration code
        if (! $request->filled('registration_code')) {
            $this->generateCaptcha();
            return back()->withErrors([
                'credentials' => __('Masukkan kode registrasi atau password.'),
            ])->withInput();
        }

        $registration = EventRegistration::where('email', $request->email)
            ->where('registration_code', $request->registration_code)
            ->first();

        if (! $registration) {
            $this->generateCaptcha();
            return back()->withErrors([
                'credentials' => __('Email atau kode registrasi tidak valid.'),
            ])->withInput();
        }

        $request->session()->put('participant_email', $registration->email);
        $request->session()->put('participant_name', $registration->name);

        // If password not yet set, redirect to set password
        if (! $registration->password) {
            $request->session()->put('participant_password_set', false);
            return redirect()->route('participant.set-password');
        }

        $request->session()->put('participant_password_set', true);
        return redirect()->route('participant.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['participant_email', 'participant_name', 'participant_password_set']);

        return redirect()->route('participant.login');
    }

    public function setPasswordForm(Request $request)
    {
        if (! $request->session()->has('participant_email')) {
            return redirect()->route('participant.login');
        }

        if ($request->session()->get('participant_password_set')) {
            return redirect()->route('participant.dashboard');
        }

        return view('pages.participant.set-password');
    }

    public function setPassword(Request $request)
    {
        if (! $request->session()->has('participant_email')) {
            return redirect()->route('participant.login');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $email = $request->session()->get('participant_email');

        EventRegistration::where('email', $email)
            ->whereNull('password')
            ->update(['password' => Hash::make($request->password)]);

        $request->session()->put('participant_password_set', true);

        return redirect()->route('participant.dashboard');
    }

    public function dashboard(Request $request)
    {
        $email = $request->session()->get('participant_email');

        $registrations = EventRegistration::with('event')
            ->where('email', $email)
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'total' => $registrations->count(),
            'confirmed' => $registrations->where('status', 'confirmed')->count(),
            'attended' => $registrations->whereNotNull('attended_at')->count(),
            'certificates' => $registrations->whereNotNull('attended_at')->count(),
        ];

        return view('pages.participant.dashboard', compact('registrations', 'stats'));
    }

    public function eventDetail(Request $request, EventRegistration $registration)
    {
        $email = $request->session()->get('participant_email');

        if ($registration->email !== $email) {
            abort(403);
        }

        $registration->load('event');

        return view('pages.participant.event-detail', compact('registration'));
    }

    public function downloadCertificate(Request $request, EventRegistration $registration)
    {
        $email = $request->session()->get('participant_email');

        if ($registration->email !== $email) {
            abort(403);
        }

        if (! $registration->certificate_path || ! Storage::disk('public')->exists($registration->certificate_path)) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $registration->certificate_path,
            'Certificate-' . $registration->registration_code . '.pdf'
        );
    }

    public function verifyAttendance(Request $request, EventRegistration $registration)
    {
        $email = $request->session()->get('participant_email');

        if ($registration->email !== $email) {
            abort(403);
        }

        if ($registration->attended_at) {
            return back()->with('attendance_error', __('Kehadiran sudah terverifikasi sebelumnya.'));
        }

        $request->validate([
            'attendance_code' => 'required|string',
        ]);

        $event = $registration->event;

        if (! $event || ! $event->attendance_code) {
            return back()->with('attendance_error', __('Verifikasi kehadiran belum tersedia untuk event ini.'));
        }

        if (strtoupper(trim($request->attendance_code)) !== strtoupper(trim($event->attendance_code))) {
            return back()->with('attendance_error', __('Kode kehadiran salah. Silakan coba lagi.'));
        }

        $registration->update(['attended_at' => now()]);

        return back()->with('attendance_success', __('Kehadiran berhasil diverifikasi!'));
    }

    private function generateCaptcha(): void
    {
        session([
            'captcha_num1' => random_int(1, 10),
            'captcha_num2' => random_int(1, 10),
        ]);
    }
}
