<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function show(string $code)
    {
        $registration = EventRegistration::with('event')
            ->where('registration_code', $code)
            ->firstOrFail();

        return view('pages.admin.verify-attendance', compact('registration'));
    }

    public function store(Request $request, string $code)
    {
        $registration = EventRegistration::where('registration_code', $code)->firstOrFail();

        if (! $registration->attended_at) {
            $registration->update(['attended_at' => now()]);
        }

        return redirect()->route('admin.verify-attendance', $code);
    }
}
