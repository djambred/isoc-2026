<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Section;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function show(Event $event)
    {
        if (!$event->canRegister()) {
            return redirect()->route('events')->with('error', __('Pendaftaran untuk event ini tidak tersedia.'));
        }

        $registrationCount = $event->confirmedRegistrations()->count();
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();

        $this->generateCaptcha();

        return view('pages.event-register', compact('event', 'registrationCount', 'settings'));
    }

    public function refreshCaptcha()
    {
        $this->generateCaptcha();

        return response()->json([
            'num1' => session('captcha_num1'),
            'num2' => session('captcha_num2'),
        ]);
    }

    public function store(Request $request, Event $event)
    {
        if (!$event->canRegister()) {
            return redirect()->route('events')->with('error', __('Pendaftaran untuk event ini tidak tersedia.'));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'organization' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'motivation' => 'nullable|string|max:1000',
            'captcha_answer' => 'required|integer',
        ]);

        $expected = session('captcha_num1', 0) + session('captcha_num2', 0);
        if ((int) $request->captcha_answer !== $expected) {
            $this->generateCaptcha();
            return back()->withInput()->withErrors(['captcha_answer' => __('Jawaban captcha salah.')]);
        }

        // Check duplicate email for same event
        $exists = EventRegistration::where('event_id', $event->id)
            ->where('email', $validated['email'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['email' => __('Email ini sudah terdaftar untuk event ini.')]);
        }

        $registration = EventRegistration::create([
            ...\Illuminate\Support\Arr::except($validated, ['captcha_answer']),
            'event_id' => $event->id,
        ]);

        return redirect()->route('event.register.success', [
            'event' => $event->id,
            'code' => $registration->registration_code,
        ]);
    }

    public function success(Event $event, Request $request)
    {
        $code = $request->query('code');
        $registration = EventRegistration::where('registration_code', $code)
            ->where('event_id', $event->id)
            ->firstOrFail();

        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();

        return view('pages.event-register-success', compact('event', 'registration', 'settings'));
    }

    private function generateCaptcha(): void
    {
        session([
            'captcha_num1' => random_int(1, 10),
            'captcha_num2' => random_int(1, 10),
        ]);
    }
}
