@extends('layouts.app')

@section('title', __('Verifikasi Sertifikat') . ' - ISOC Indonesia')

@section('content')
<section class="bg-gradient-to-br from-navy via-blue-dark to-blue text-white py-10">
    <div class="max-w-2xl mx-auto px-4 text-center">
        <span class="material-symbols-outlined text-4xl text-blue-200 mb-2">verified</span>
        <h1 class="text-2xl md:text-3xl font-bold">{{ __('Verifikasi Sertifikat') }}</h1>
        <p class="text-blue-200 text-sm mt-2">{{ __('Halaman ini memverifikasi keaslian sertifikat ISOC Indonesia Jakarta Chapter') }}</p>
    </div>
</section>

<section class="max-w-2xl mx-auto px-4 py-10">
    @if($registration && $registration->attended_at)
    {{-- Valid Certificate --}}
    <div class="bg-white border border-green-200 rounded-2xl overflow-hidden shadow-sm">
        {{-- Status Header --}}
        <div class="bg-green-50 border-b border-green-200 px-6 py-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
            </div>
            <div>
                <p class="font-bold text-green-800">{{ __('Sertifikat Valid') }}</p>
                <p class="text-green-600 text-xs">{{ __('Sertifikat ini diterbitkan secara resmi oleh ISOC Indonesia Chapter') }}</p>
            </div>
        </div>

        {{-- Details --}}
        <div class="px-6 py-5 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-grey-400 text-xs uppercase tracking-wide mb-0.5">{{ __('Nama Peserta') }}</p>
                    <p class="font-bold text-navy text-lg">{{ $registration->name }}</p>
                </div>
                <div>
                    <p class="text-grey-400 text-xs uppercase tracking-wide mb-0.5">{{ __('Kode Sertifikat') }}</p>
                    <p class="font-mono font-bold text-blue">{{ $registration->registration_code }}</p>
                </div>
            </div>

            <hr class="border-grey-100">

            <div>
                <p class="text-grey-400 text-xs uppercase tracking-wide mb-0.5">{{ __('Nama Kegiatan') }}</p>
                <p class="font-semibold text-navy">{{ $registration->event->title ?? '-' }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @if($registration->event?->date)
                <div>
                    <p class="text-grey-400 text-xs uppercase tracking-wide mb-0.5">{{ __('Tanggal Kegiatan') }}</p>
                    <p class="font-semibold text-navy flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-blue text-base">calendar_month</span>
                        {{ $registration->event->date->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                @endif
                @if($registration->event?->location)
                <div>
                    <p class="text-grey-400 text-xs uppercase tracking-wide mb-0.5">{{ __('Lokasi') }}</p>
                    <p class="font-semibold text-navy flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-blue text-base">location_on</span>
                        {{ $registration->event->location }}
                    </p>
                </div>
                @endif
            </div>

            <hr class="border-grey-100">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-grey-400 text-xs uppercase tracking-wide mb-0.5">{{ __('Tanggal Hadir') }}</p>
                    <p class="font-semibold text-green-700 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-green-500 text-base">how_to_reg</span>
                        {{ $registration->attended_at->translatedFormat('l, d F Y - H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-grey-400 text-xs uppercase tracking-wide mb-0.5">{{ __('Status Pendaftaran') }}</p>
                    <p class="font-semibold text-navy capitalize">{{ $registration->status }}</p>
                </div>
            </div>

            @if($registration->organization)
            <div>
                <p class="text-grey-400 text-xs uppercase tracking-wide mb-0.5">{{ __('Organisasi') }}</p>
                <p class="font-semibold text-navy">{{ $registration->organization }}</p>
            </div>
            @endif
        </div>
    </div>

    @elseif($registration && !$registration->attended_at)
    {{-- Registration exists but not attended --}}
    <div class="bg-white border border-yellow-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="bg-yellow-50 border-b border-yellow-200 px-6 py-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-yellow-600">warning</span>
            </div>
            <div>
                <p class="font-bold text-yellow-800">{{ __('Sertifikat Belum Diterbitkan') }}</p>
                <p class="text-yellow-600 text-xs">{{ __('Peserta terdaftar namun belum menghadiri kegiatan.') }}</p>
            </div>
        </div>
        <div class="px-6 py-5">
            <p class="text-grey-600 text-sm">{{ __('Kode') }}: <span class="font-mono font-bold text-navy">{{ $code }}</span></p>
        </div>
    </div>

    @else
    {{-- Not found --}}
    <div class="bg-white border border-red-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="bg-red-50 border-b border-red-200 px-6 py-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-red-600">cancel</span>
            </div>
            <div>
                <p class="font-bold text-red-800">{{ __('Sertifikat Tidak Ditemukan') }}</p>
                <p class="text-red-600 text-xs">{{ __('Kode yang Anda masukkan tidak terdaftar dalam sistem kami.') }}</p>
            </div>
        </div>
        <div class="px-6 py-5">
            <p class="text-grey-600 text-sm">{{ __('Kode dicari') }}: <span class="font-mono font-bold text-navy">{{ $code }}</span></p>
        </div>
    </div>
    @endif

    <div class="text-center mt-6">
        <a href="{{ route('home') }}" class="text-blue hover:text-navy text-sm font-semibold inline-flex items-center gap-1 transition-colors">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            {{ __('Kembali ke Beranda') }}
        </a>
    </div>
</section>
@endsection
