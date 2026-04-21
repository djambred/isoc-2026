@extends('layouts.app')

@section('title', __('Verifikasi Kehadiran'))

@section('content')
<section class="min-h-screen bg-gradient-to-br from-navy via-blue-dark to-blue flex items-center justify-center py-20 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            @if($registration->attended_at)
                {{-- Already Attended --}}
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-blue text-3xl">info</span>
                </div>
                <h1 class="text-xl font-bold text-navy mb-2">{{ __('Sudah Terverifikasi') }}</h1>
                <p class="text-grey-500 text-sm mb-6">{{ __('Peserta ini sudah diverifikasi kehadirannya.') }}</p>
                <div class="bg-grey-50 rounded-xl p-4 text-left space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-grey-400">{{ __('Nama') }}</span>
                        <span class="font-semibold text-navy">{{ $registration->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-grey-400">{{ __('Kode') }}</span>
                        <span class="font-mono font-bold text-blue">{{ $registration->registration_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-grey-400">{{ __('Event') }}</span>
                        <span class="font-semibold text-navy">{{ $registration->event?->title }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-grey-400">{{ __('Hadir') }}</span>
                        <span class="font-semibold text-green-600">{{ $registration->attended_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
            @else
                {{-- Confirm Attendance --}}
                <div class="w-16 h-16 bg-teal/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-teal text-3xl">how_to_reg</span>
                </div>
                <h1 class="text-xl font-bold text-navy mb-2">{{ __('Verifikasi Kehadiran') }}</h1>
                <p class="text-grey-500 text-sm mb-6">{{ __('Konfirmasi kehadiran peserta berikut?') }}</p>
                <div class="bg-grey-50 rounded-xl p-4 text-left space-y-2 text-sm mb-6">
                    <div class="flex justify-between">
                        <span class="text-grey-400">{{ __('Nama') }}</span>
                        <span class="font-semibold text-navy">{{ $registration->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-grey-400">{{ __('Email') }}</span>
                        <span class="font-semibold text-navy">{{ $registration->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-grey-400">{{ __('Kode') }}</span>
                        <span class="font-mono font-bold text-blue">{{ $registration->registration_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-grey-400">{{ __('Event') }}</span>
                        <span class="font-semibold text-navy">{{ $registration->event?->title }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-grey-400">{{ __('Status') }}</span>
                        <span class="font-semibold {{ $registration->status === 'confirmed' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $registration->status === 'confirmed' ? __('Dikonfirmasi') : ucfirst($registration->status) }}
                        </span>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.verify-attendance.store', $registration->registration_code) }}">
                    @csrf
                    <button type="submit" class="w-full bg-teal hover:bg-teal-dark text-white py-3 rounded-xl font-semibold text-sm transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">verified</span>
                        {{ __('Konfirmasi Kehadiran') }}
                    </button>
                </form>
            @endif

            <a href="{{ url('/admin') }}" class="mt-4 inline-flex items-center gap-1 text-grey-400 hover:text-navy text-sm transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                {{ __('Kembali ke Admin') }}
            </a>
        </div>
    </div>
</section>
@endsection
