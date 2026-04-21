@extends('layouts.app')

@section('title', __('Portal Peserta') . ' - Login')

@section('content')
<section class="min-h-screen bg-gradient-to-br from-navy via-blue-dark to-blue flex items-center justify-center py-20 px-4">
    <div class="w-full max-w-md" x-data="{ mode: 'code' }">
        {{-- Logo & Title --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur rounded-2xl mb-4">
                <span class="material-symbols-outlined text-white text-3xl">person</span>
            </div>
            <h1 class="text-2xl font-bold text-white">{{ __('Portal Peserta') }}</h1>
            <p class="text-blue-200 mt-2 text-sm">{{ __('Masuk dengan email dan kode registrasi atau password Anda') }}</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            @if($errors->has('credentials'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-lg">error</span>
                {{ $errors->first('credentials') }}
            </div>
            @endif

            <form method="POST" action="{{ route('participant.login.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-navy mb-1.5">{{ __('Email') }}</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-grey-400 text-xl">mail</span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full pl-10 pr-4 py-3 border border-grey-200 rounded-xl text-sm focus:ring-2 focus:ring-blue focus:border-blue transition-colors @error('email') border-red-400 @enderror"
                            placeholder="{{ __('Masukkan email Anda') }}">
                    </div>
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mode Toggle --}}
                <div class="flex rounded-xl border border-grey-200 overflow-hidden text-sm">
                    <button type="button" @click="mode = 'code'" :class="mode === 'code' ? 'bg-blue text-white' : 'bg-white text-grey-500 hover:bg-grey-50'" class="flex-1 py-2.5 font-semibold transition-colors flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-base">confirmation_number</span>
                        {{ __('Kode Registrasi') }}
                    </button>
                    <button type="button" @click="mode = 'password'" :class="mode === 'password' ? 'bg-blue text-white' : 'bg-white text-grey-500 hover:bg-grey-50'" class="flex-1 py-2.5 font-semibold transition-colors flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-base">lock</span>
                        {{ __('Password') }}
                    </button>
                </div>

                {{-- Registration Code Field --}}
                <div x-show="mode === 'code'" x-transition>
                    <label for="registration_code" class="block text-sm font-semibold text-navy mb-1.5">{{ __('Kode Registrasi') }}</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-grey-400 text-xl">confirmation_number</span>
                        <input type="text" id="registration_code" name="registration_code" value="{{ old('registration_code') }}"
                            class="w-full pl-10 pr-4 py-3 border border-grey-200 rounded-xl text-sm focus:ring-2 focus:ring-blue focus:border-blue transition-colors font-mono uppercase @error('registration_code') border-red-400 @enderror"
                            placeholder="REG-XXXXXXXX">
                    </div>
                    @error('registration_code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div x-show="mode === 'password'" x-transition>
                    <label for="password" class="block text-sm font-semibold text-navy mb-1.5">{{ __('Password') }}</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-grey-400 text-xl">lock</span>
                        <input type="password" id="password" name="password"
                            class="w-full pl-10 pr-4 py-3 border border-grey-200 rounded-xl text-sm focus:ring-2 focus:ring-blue focus:border-blue transition-colors @error('password') border-red-400 @enderror"
                            placeholder="{{ __('Masukkan password Anda') }}">
                    </div>
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Simple Math Captcha --}}
                <div x-data="{ num1: {{ session('captcha_num1', 0) }}, num2: {{ session('captcha_num2', 0) }}, loading: false }">
                    <label class="block text-sm font-semibold text-navy mb-1.5">{{ __('Verifikasi Keamanan') }}</label>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 bg-grey-50 border border-grey-200 rounded-xl px-4 py-3 select-none">
                            <span class="font-bold text-navy text-lg" x-text="num1"></span>
                            <span class="text-grey-500 text-lg">+</span>
                            <span class="font-bold text-navy text-lg" x-text="num2"></span>
                            <span class="text-grey-500 text-lg">=</span>
                        </div>
                        <input class="w-24 px-4 py-3 rounded-xl border border-grey-200 focus:border-blue focus:ring-2 focus:ring-blue/20 outline-none transition text-sm text-center font-bold @error('captcha_answer') border-red-400 @enderror" id="captcha_answer" name="captcha_answer" type="number" required placeholder="?" value="{{ old('captcha_answer') }}">
                        <button type="button" @click="loading = true; fetch('{{ route('captcha.refresh.portal') }}').then(r => r.json()).then(d => { num1 = d.num1; num2 = d.num2; loading = false; document.getElementById('captcha_answer').value = ''; })" class="flex items-center justify-center w-11 h-11 rounded-xl border border-grey-200 hover:bg-grey-50 text-grey-500 hover:text-blue transition-colors" title="{{ __('Refresh Captcha') }}">
                            <span class="material-symbols-outlined text-xl" :class="loading && 'animate-spin'">refresh</span>
                        </button>
                    </div>
                    @error('captcha_answer')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-blue hover:bg-blue-dark text-white py-3 rounded-xl font-semibold text-sm transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">login</span>
                    {{ __('Masuk') }}
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-grey-100">
                <p class="text-grey-500 text-xs text-center">{{ __('Login pertama kali gunakan kode registrasi. Setelah membuat password, gunakan password untuk login berikutnya.') }}</p>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('events') }}" class="text-white/70 hover:text-white text-sm flex items-center justify-center gap-1 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                {{ __('Kembali ke Events') }}
            </a>
        </div>
    </div>
</section>
@endsection
