@extends('layouts.app')

@section('title', __('Portal Peserta') . ' - ' . ($registration->event?->title ?? __('Detail Event')))

@section('content')
{{-- Compact Hero --}}
<section class="bg-gradient-to-br from-navy via-blue-dark to-blue text-white py-6">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="min-w-0">
                <a href="{{ route('participant.dashboard') }}" class="inline-flex items-center gap-1 text-blue-200 hover:text-white text-xs mb-1.5 transition-colors">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    {{ __('Dashboard') }}
                </a>
                <h1 class="text-xl md:text-2xl font-bold truncate">{{ $registration->event?->title ?? __('Event Dihapus') }}</h1>
                @if($registration->event?->date)
                <div class="flex flex-wrap items-center gap-3 text-blue-200 text-xs mt-1.5">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">calendar_month</span>
                        {{ $registration->event->date->translatedFormat('l, d F Y') }}
                    </span>
                    @if($registration->event->time_info)
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">schedule</span>
                        {{ $registration->event->time_info }}
                    </span>
                    @endif
                    @if($registration->event->location)
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">location_on</span>
                        {{ $registration->event->location }}
                    </span>
                    @endif
                </div>
                @endif
            </div>
            {{-- Status Badges --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                @if($registration->status === 'confirmed')
                <span class="inline-flex items-center gap-1 bg-green-500/20 text-green-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-sm">check_circle</span>{{ __('Dikonfirmasi') }}
                </span>
                @elseif($registration->status === 'pending')
                <span class="inline-flex items-center gap-1 bg-yellow-500/20 text-yellow-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-sm">schedule</span>{{ __('Pending') }}
                </span>
                @elseif($registration->status === 'cancelled')
                <span class="inline-flex items-center gap-1 bg-red-500/20 text-red-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-sm">cancel</span>{{ __('Dibatalkan') }}
                </span>
                @endif

                @if($registration->attended_at)
                <span class="inline-flex items-center gap-1 bg-blue-500/20 text-blue-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-sm">how_to_reg</span>{{ __('Hadir') }}
                </span>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 py-6">
    <div class="grid md:grid-cols-12 gap-5">

        {{-- Left Column: Registration Info --}}
        <div class="md:col-span-5">
            <div class="bg-white border border-grey-100 rounded-2xl p-5 h-full">
                <h2 class="font-bold text-navy flex items-center gap-2 text-sm mb-3">
                    <span class="material-symbols-outlined text-blue text-lg">badge</span>
                    {{ __('Informasi Pendaftaran') }}
                </h2>
                <div class="space-y-2.5">
                    <div class="flex items-baseline gap-2">
                        <span class="text-grey-400 text-xs w-20 flex-shrink-0">{{ __('Nama') }}</span>
                        <span class="font-semibold text-navy text-sm">{{ $registration->name }}</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-grey-400 text-xs w-20 flex-shrink-0">{{ __('Email') }}</span>
                        <span class="font-semibold text-navy text-sm truncate">{{ $registration->email }}</span>
                    </div>
                    @if($registration->phone)
                    <div class="flex items-baseline gap-2">
                        <span class="text-grey-400 text-xs w-20 flex-shrink-0">{{ __('Telepon') }}</span>
                        <span class="font-semibold text-navy text-sm">{{ $registration->phone }}</span>
                    </div>
                    @endif
                    @if($registration->organization)
                    <div class="flex items-baseline gap-2">
                        <span class="text-grey-400 text-xs w-20 flex-shrink-0">{{ __('Organisasi') }}</span>
                        <span class="font-semibold text-navy text-sm">{{ $registration->organization }}</span>
                    </div>
                    @endif
                    @if($registration->position)
                    <div class="flex items-baseline gap-2">
                        <span class="text-grey-400 text-xs w-20 flex-shrink-0">{{ __('Jabatan') }}</span>
                        <span class="font-semibold text-navy text-sm">{{ $registration->position }}</span>
                    </div>
                    @endif
                    <hr class="border-grey-100">
                    <div class="flex items-baseline gap-2">
                        <span class="text-grey-400 text-xs w-20 flex-shrink-0">{{ __('Kode') }}</span>
                        <span class="font-mono font-bold text-blue text-sm">{{ $registration->registration_code }}</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-grey-400 text-xs w-20 flex-shrink-0">{{ __('Daftar') }}</span>
                        <span class="font-semibold text-navy text-sm">{{ $registration->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @if($registration->attended_at)
                    <div class="flex items-baseline gap-2">
                        <span class="text-grey-400 text-xs w-20 flex-shrink-0">{{ __('Hadir') }}</span>
                        <span class="font-semibold text-green-600 text-sm">{{ $registration->attended_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @endif
                </div>

                {{-- Event Description (collapsed) --}}
                @if($registration->event?->description)
                <div x-data="{ open: false }" class="mt-4 pt-3 border-t border-grey-100">
                    <button @click="open = !open" class="flex items-center gap-1.5 text-grey-500 text-xs hover:text-navy transition-colors w-full text-left">
                        <span class="material-symbols-outlined text-sm transition-transform" :class="open && 'rotate-90'">chevron_right</span>
                        {{ __('Tentang Event') }}
                    </button>
                    <p x-show="open" x-collapse class="text-grey-600 text-xs leading-relaxed mt-2">{{ $registration->event->description }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Right Column: Actions --}}
        <div class="md:col-span-7">

            {{-- Attendance Verified --}}
            @if($registration->attended_at)
            <div class="grid sm:grid-cols-2 gap-4">
                {{-- Verified Banner --}}
                <div class="bg-green-50 border border-green-200 rounded-2xl p-5 flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-green-500 text-2xl">verified</span>
                    </div>
                    <div>
                        <p class="text-green-700 font-bold text-sm">{{ __('Kehadiran Terverifikasi') }}</p>
                        <p class="text-green-600 text-xs mt-0.5">{{ $registration->attended_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Certificate Card --}}
                <div class="bg-purple-50 border border-purple-200 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-purple-600 text-xl">workspace_premium</span>
                        </div>
                        <div>
                            <p class="font-bold text-purple-800 text-sm">{{ __('Sertifikat Tersedia') }}</p>
                            <p class="text-purple-600 text-xs">{{ __('Unduh atau lihat sertifikat Anda') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('participant.certificate', $registration) }}" class="flex-1 flex items-center justify-center gap-1.5 bg-purple-600 hover:bg-purple-700 text-white py-2.5 rounded-xl font-semibold text-xs transition-colors">
                            <span class="material-symbols-outlined text-base">download</span>
                            {{ __('Download') }}
                        </a>
                        <a href="{{ route('participant.certificate.preview', $registration) }}" target="_blank" class="flex-1 flex items-center justify-center gap-1.5 border-2 border-purple-600 text-purple-600 hover:bg-purple-100 py-2 rounded-xl font-semibold text-xs transition-colors">
                            <span class="material-symbols-outlined text-base">visibility</span>
                            {{ __('Preview') }}
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Attendance Section (Not yet attended) --}}
            @if($registration->status === 'confirmed' && !$registration->attended_at)
            <div class="space-y-4">
                {{-- Info banner --}}
                <div class="bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3 flex items-center gap-3">
                    <span class="material-symbols-outlined text-amber-500 text-lg">info</span>
                    <p class="text-amber-800 text-xs">{{ __('Saat event berlangsung, verifikasi kehadiran Anda menggunakan QR code atau kode kehadiran.') }}</p>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    {{-- QR Code --}}
                    <div class="bg-white border border-grey-100 rounded-2xl p-5 text-center">
                        <h3 class="font-bold text-navy flex items-center justify-center gap-1.5 text-sm mb-1">
                            <span class="material-symbols-outlined text-blue text-lg">qr_code_2</span>
                            {{ __('QR Check-in') }}
                        </h3>
                        <p class="text-grey-400 text-xs mb-3">{{ __('Tunjukkan ke panitia') }}</p>
                        <div id="qrcode" class="inline-block bg-white p-2 rounded-xl border border-grey-100"></div>
                        <p class="text-navy font-bold mt-2 font-mono text-sm">{{ $registration->registration_code }}</p>
                    </div>

                    {{-- Verify with Code --}}
                    <div class="bg-white border border-grey-100 rounded-2xl p-5 flex flex-col">
                        <h3 class="font-bold text-navy flex items-center gap-1.5 text-sm mb-1">
                            <span class="material-symbols-outlined text-teal text-lg">verified</span>
                            {{ __('Verifikasi Manual') }}
                        </h3>
                        <p class="text-grey-500 text-xs mb-3">{{ __('Masukkan kode yang diumumkan panitia') }}</p>

                        @if(session('attendance_error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-xl mb-3 flex items-center gap-1.5 text-xs">
                            <span class="material-symbols-outlined text-sm">error</span>
                            {{ session('attendance_error') }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('participant.verify-attendance', $registration) }}" class="flex flex-col gap-2.5 mt-auto">
                            @csrf
                            <input type="text" name="attendance_code" required
                                class="w-full px-3 py-2.5 border border-grey-200 rounded-xl text-sm focus:ring-2 focus:ring-teal focus:border-teal font-mono uppercase"
                                placeholder="{{ __('Kode kehadiran') }}">
                            <button type="submit" class="bg-teal hover:bg-teal-dark text-white w-full py-2.5 rounded-xl font-semibold text-sm transition-colors flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-base">verified</span>
                                {{ __('Verifikasi') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            {{-- If pending or cancelled, show info --}}
            @if($registration->status === 'pending')
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-yellow-500 text-2xl">hourglass_top</span>
                </div>
                <div>
                    <p class="font-bold text-yellow-800 text-sm">{{ __('Menunggu Konfirmasi') }}</p>
                    <p class="text-yellow-700 text-xs mt-0.5">{{ __('Pendaftaran Anda sedang ditinjau oleh panitia. Anda akan mendapatkan notifikasi setelah dikonfirmasi.') }}</p>
                </div>
            </div>
            @endif

            @if($registration->status === 'cancelled')
            <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-red-500 text-2xl">block</span>
                </div>
                <div>
                    <p class="font-bold text-red-800 text-sm">{{ __('Pendaftaran Dibatalkan') }}</p>
                    <p class="text-red-700 text-xs mt-0.5">{{ __('Pendaftaran Anda untuk event ini telah dibatalkan. Hubungi panitia untuk informasi lebih lanjut.') }}</p>
                </div>
            </div>
            @endif

        </div>
    </div>
</section>
@endsection

@push('scripts')
@if($registration->status === 'confirmed' && !$registration->attended_at)
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ url('/admin/verify-attendance/' . $registration->registration_code) }}",
        width: 150,
        height: 150,
        colorDark: "#0f172a",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
</script>
@endif
@endpush
