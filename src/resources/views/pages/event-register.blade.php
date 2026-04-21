@extends('layouts.app')

@section('title', __('Daftar Event') . ' - ' . $event->title)

@section('content')
{{-- Hero --}}
<section class="bg-navy relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 lg:py-20">
        <div class="max-w-3xl">
            <a href="{{ route('events') }}" class="inline-flex items-center gap-1 text-white/60 hover:text-white text-sm mb-4 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span> {{ __('Kembali ke Events') }}
            </a>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-[1.1] mb-4">{{ $event->title }}</h1>
            <div class="flex flex-wrap gap-4 text-sm text-white/70">
                @if($event->date)
                <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-blue-light text-lg">calendar_today</span> {{ $event->date->translatedFormat('d F Y') }}</span>
                @endif
                @if($event->time_info)
                <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-blue-light text-lg">schedule</span> {{ $event->time_info }}</span>
                @endif
                @if($event->location)
                <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-blue-light text-lg">location_on</span> {{ $event->location }}</span>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Registration Form --}}
<section class="py-16 lg:py-24">
    <div class="max-w-3xl mx-auto px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-grey-100 overflow-hidden">
            {{-- Header --}}
            <div class="bg-grey-50 px-8 py-6 border-b border-grey-100">
                <h2 class="text-xl font-bold text-navy">{{ __('Formulir Pendaftaran') }}</h2>
                <p class="text-grey-600 text-sm mt-1">{{ __('Isi data berikut untuk mendaftar event ini.') }}</p>
                @if($event->max_participants)
                <div class="mt-3 flex items-center gap-2">
                    <div class="flex-1 bg-grey-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-teal h-full rounded-full transition-all" style="width: {{ min(100, ($registrationCount / $event->max_participants) * 100) }}%"></div>
                    </div>
                    <span class="text-xs font-medium text-grey-500">{{ $registrationCount }}/{{ $event->max_participants }} {{ __('peserta') }}</span>
                </div>
                @endif
            </div>

            {{-- Form --}}
            <form action="{{ route('event.register.store', $event) }}" method="POST" class="p-8 space-y-6">
                @csrf

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-semibold text-navy mb-2" for="name">{{ __('Nama Lengkap') }} <span class="text-red-500">*</span></label>
                    <input class="w-full px-4 py-3 rounded-xl border border-grey-200 focus:border-blue focus:ring-2 focus:ring-blue/20 outline-none transition text-sm" id="name" name="name" required type="text" value="{{ old('name') }}" placeholder="{{ __('Masukkan nama lengkap Anda') }}"/>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-navy mb-2" for="email">{{ __('Email') }} <span class="text-red-500">*</span></label>
                    <input class="w-full px-4 py-3 rounded-xl border border-grey-200 focus:border-blue focus:ring-2 focus:ring-blue/20 outline-none transition text-sm" id="email" name="email" required type="email" value="{{ old('email') }}" placeholder="{{ __('contoh@email.com') }}"/>
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-semibold text-navy mb-2" for="phone">{{ __('Nomor Telepon') }}</label>
                    <input class="w-full px-4 py-3 rounded-xl border border-grey-200 focus:border-blue focus:ring-2 focus:ring-blue/20 outline-none transition text-sm" id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="{{ __('08xxxxxxxxxx') }}"/>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Organization --}}
                    <div>
                        <label class="block text-sm font-semibold text-navy mb-2" for="organization">{{ __('Organisasi / Institusi') }}</label>
                        <input class="w-full px-4 py-3 rounded-xl border border-grey-200 focus:border-blue focus:ring-2 focus:ring-blue/20 outline-none transition text-sm" id="organization" name="organization" type="text" value="{{ old('organization') }}" placeholder="{{ __('Nama organisasi') }}"/>
                    </div>

                    {{-- Position --}}
                    <div>
                        <label class="block text-sm font-semibold text-navy mb-2" for="position">{{ __('Jabatan') }}</label>
                        <input class="w-full px-4 py-3 rounded-xl border border-grey-200 focus:border-blue focus:ring-2 focus:ring-blue/20 outline-none transition text-sm" id="position" name="position" type="text" value="{{ old('position') }}" placeholder="{{ __('Jabatan Anda') }}"/>
                    </div>
                </div>

                {{-- Motivation --}}
                <div>
                    <label class="block text-sm font-semibold text-navy mb-2" for="motivation">{{ __('Motivasi Mengikuti Event') }}</label>
                    <textarea class="w-full px-4 py-3 rounded-xl border border-grey-200 focus:border-blue focus:ring-2 focus:ring-blue/20 outline-none transition text-sm" id="motivation" name="motivation" rows="4" placeholder="{{ __('Ceritakan motivasi Anda mengikuti event ini...') }}">{{ old('motivation') }}</textarea>
                </div>

                {{-- Simple Math Captcha --}}
                <div x-data="{ num1: {{ session('captcha_num1', 0) }}, num2: {{ session('captcha_num2', 0) }}, loading: false }">
                    <label class="block text-sm font-semibold text-navy mb-2">{{ __('Verifikasi Keamanan') }}</label>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 bg-grey-50 border border-grey-200 rounded-xl px-4 py-3 select-none">
                            <span class="font-bold text-navy text-lg" x-text="num1"></span>
                            <span class="text-grey-500 text-lg">+</span>
                            <span class="font-bold text-navy text-lg" x-text="num2"></span>
                            <span class="text-grey-500 text-lg">=</span>
                        </div>
                        <input class="w-24 px-4 py-3 rounded-xl border border-grey-200 focus:border-blue focus:ring-2 focus:ring-blue/20 outline-none transition text-sm text-center font-bold @error('captcha_answer') border-red-400 @enderror" id="captcha_answer" name="captcha_answer" type="number" required placeholder="?" value="{{ old('captcha_answer') }}">
                        <button type="button" @click="loading = true; fetch('{{ route('captcha.refresh') }}').then(r => r.json()).then(d => { num1 = d.num1; num2 = d.num2; loading = false; document.getElementById('captcha_answer').value = ''; })" class="flex items-center justify-center w-11 h-11 rounded-xl border border-grey-200 hover:bg-grey-50 text-grey-500 hover:text-blue transition-colors" title="{{ __('Refresh Captcha') }}">
                            <span class="material-symbols-outlined text-xl" :class="loading && 'animate-spin'">refresh</span>
                        </button>
                    </div>
                    @error('captcha_answer')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button class="w-full bg-blue hover:bg-blue-dark text-white py-3.5 px-8 rounded-xl font-semibold text-sm transition-colors flex items-center justify-center gap-2" type="submit">
                        <span class="material-symbols-outlined text-lg">how_to_reg</span>
                        {{ __('Daftar Sekarang') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Event Details Sidebar --}}
        <div class="mt-8 bg-grey-50 rounded-2xl p-8">
            <h3 class="font-bold text-navy mb-4">{{ __('Detail Event') }}</h3>
            <div class="space-y-3 text-sm text-grey-600">
                @if($event->date)
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue text-lg mt-0.5">calendar_today</span>
                    <div>
                        <p class="font-medium text-navy">{{ __('Tanggal') }}</p>
                        <p>{{ $event->date->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                @endif
                @if($event->time_info)
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue text-lg mt-0.5">schedule</span>
                    <div>
                        <p class="font-medium text-navy">{{ __('Waktu') }}</p>
                        <p>{{ $event->time_info }}</p>
                    </div>
                </div>
                @endif
                @if($event->location)
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue text-lg mt-0.5">location_on</span>
                    <div>
                        <p class="font-medium text-navy">{{ __('Lokasi') }}</p>
                        <p>{{ $event->location }}</p>
                    </div>
                </div>
                @endif
                @if($event->location_type)
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue text-lg mt-0.5">{{ $event->location_type === 'online' ? 'videocam' : ($event->location_type === 'hybrid' ? 'devices' : 'apartment') }}</span>
                    <div>
                        <p class="font-medium text-navy">{{ __('Tipe') }}</p>
                        <p class="capitalize">{{ $event->location_type }}</p>
                    </div>
                </div>
                @endif
                @if($event->description)
                <div class="pt-3 border-t border-grey-200">
                    <p class="font-medium text-navy mb-2">{{ __('Deskripsi') }}</p>
                    <p class="leading-relaxed">{{ $event->description }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
