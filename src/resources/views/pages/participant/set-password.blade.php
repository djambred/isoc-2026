@extends('layouts.app')

@section('title', __('Portal Peserta') . ' - ' . __('Buat Password'))

@section('content')
<section class="min-h-screen bg-gradient-to-br from-navy via-blue-dark to-blue flex items-center justify-center py-20 px-4">
    <div class="w-full max-w-md">
        {{-- Title --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur rounded-2xl mb-4">
                <span class="material-symbols-outlined text-white text-3xl">lock</span>
            </div>
            <h1 class="text-2xl font-bold text-white">{{ __('Buat Password') }}</h1>
            <p class="text-blue-200 mt-2 text-sm">{{ __('Untuk keamanan akun Anda, silakan buat password baru.') }}</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            {{-- User info --}}
            <div class="bg-blue/5 rounded-xl p-4 mb-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue/10 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue">person</span>
                </div>
                <div>
                    <p class="font-semibold text-navy text-sm">{{ session('participant_name') }}</p>
                    <p class="text-grey-500 text-xs">{{ session('participant_email') }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('participant.set-password.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="password" class="block text-sm font-semibold text-navy mb-1.5">{{ __('Password Baru') }}</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-grey-400 text-xl">lock</span>
                        <input type="password" id="password" name="password" required minlength="6"
                            class="w-full pl-10 pr-4 py-3 border border-grey-200 rounded-xl text-sm focus:ring-2 focus:ring-blue focus:border-blue transition-colors @error('password') border-red-400 @enderror"
                            placeholder="{{ __('Minimal 6 karakter') }}">
                    </div>
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-navy mb-1.5">{{ __('Konfirmasi Password') }}</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-grey-400 text-xl">lock_reset</span>
                        <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6"
                            class="w-full pl-10 pr-4 py-3 border border-grey-200 rounded-xl text-sm focus:ring-2 focus:ring-blue focus:border-blue transition-colors"
                            placeholder="{{ __('Ulangi password') }}">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue hover:bg-blue-dark text-white py-3 rounded-xl font-semibold text-sm transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span>
                    {{ __('Simpan Password') }}
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
