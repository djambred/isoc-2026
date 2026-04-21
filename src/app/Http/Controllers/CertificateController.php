<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function verify(string $code)
    {
        $registration = EventRegistration::with('event')
            ->where('registration_code', $code)
            ->first();

        return view('pages.verify-certificate', compact('registration', 'code'));
    }

    public function download(Request $request, EventRegistration $registration)
    {
        $email = $request->session()->get('participant_email');

        if ($registration->email !== $email) {
            abort(403);
        }

        if (! $registration->attended_at) {
            abort(404, __('Anda belum tercatat hadir di event ini.'));
        }

        $registration->load('event');
        $event = $registration->event;

        if (! $event) {
            abort(404);
        }

        $pdf = $this->generatePdf($registration, $event);

        return $pdf->download('Certificate-' . $registration->registration_code . '.pdf');
    }

    public function preview(Request $request, EventRegistration $registration)
    {
        $email = $request->session()->get('participant_email');

        if ($registration->email !== $email) {
            abort(403);
        }

        if (! $registration->attended_at) {
            abort(404);
        }

        $registration->load('event');
        $event = $registration->event;

        if (! $event) {
            abort(404);
        }

        $pdf = $this->generatePdf($registration, $event);

        return $pdf->stream('Certificate-' . $registration->registration_code . '.pdf');
    }

    public function adminPreview(EventRegistration $registration)
    {
        if (! $registration->attended_at) {
            abort(404, 'Participant has not attended this event.');
        }

        $registration->load('event');
        $event = $registration->event;

        if (! $event) {
            abort(404);
        }

        $pdf = $this->generatePdf($registration, $event);

        return $pdf->stream('Certificate-' . $registration->registration_code . '.pdf');
    }

    private function generatePdf(EventRegistration $registration, $event)
    {
        if ($event->certificate_template) {
            $renderedHtml = $this->renderCustomTemplate($event->certificate_template, $registration, $event);
            $pdf = Pdf::loadView('certificates.custom', compact('renderedHtml'));
        } else {
            $pdf = Pdf::loadView('certificates.default', compact('registration', 'event'));
        }

        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('defaultFont', 'Helvetica');
        $pdf->setOption('dpi', 150);

        return $pdf;
    }

    private function renderCustomTemplate(string $template, EventRegistration $registration, $event): string
    {
        $placeholders = [
            '{{participant_name}}' => e($registration->name),
            '{{participant_email}}' => e($registration->email),
            '{{participant_organization}}' => e($registration->organization ?? ''),
            '{{participant_position}}' => e($registration->position ?? ''),
            '{{registration_code}}' => e($registration->registration_code),
            '{{event_title}}' => e($event->title),
            '{{event_date}}' => $event->date ? $event->date->format('F d, Y') : '',
            '{{event_date_id}}' => $event->date ? $event->date->translatedFormat('d F Y') : '',
            '{{event_location}}' => e($event->location ?? ''),
            '{{event_time}}' => e($event->time_info ?? ''),
            '{{event_category}}' => e($event->category ?? ''),
            '{{attended_at}}' => $registration->attended_at ? $registration->attended_at->format('F d, Y') : '',
            '{{current_year}}' => date('Y'),
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $template);
    }
}
