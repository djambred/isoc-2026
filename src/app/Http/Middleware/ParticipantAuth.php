<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParticipantAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('participant_email')) {
            return redirect()->route('participant.login');
        }

        // Force password creation if not yet set
        if (! $request->session()->get('participant_password_set') && ! $request->routeIs('participant.set-password*')) {
            return redirect()->route('participant.set-password');
        }

        return $next($request);
    }
}
