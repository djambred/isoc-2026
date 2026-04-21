@extends('layouts.app')

@section('title', __('Portal Peserta') . ' - Dashboard')

@section('content')
{{-- Hero --}}
<section class="bg-gradient-to-br from-navy via-blue-dark to-blue text-white py-16">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-blue-200 text-sm mb-1">{{ __('Selamat datang') }},</p>
                <h1 class="text-2xl md:text-3xl font-bold">{{ session('participant_name') }}</h1>
                <p class="text-blue-200 text-sm mt-1">{{ session('participant_email') }}</p>
            </div>
            <form method="POST" action="{{ route('participant.logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 backdrop-blur text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-lg">logout</span>
                    {{ __('Keluar') }}
                </button>
            </form>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
            <div class="bg-white/10 backdrop-blur rounded-xl p-4 text-center">
                <span class="material-symbols-outlined text-3xl text-blue-200">event</span>
                <p class="text-2xl font-bold mt-1">{{ $stats['total'] }}</p>
                <p class="text-blue-200 text-xs">{{ __('Total Event') }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4 text-center">
                <span class="material-symbols-outlined text-3xl text-green-300">check_circle</span>
                <p class="text-2xl font-bold mt-1">{{ $stats['confirmed'] }}</p>
                <p class="text-blue-200 text-xs">{{ __('Dikonfirmasi') }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4 text-center">
                <span class="material-symbols-outlined text-3xl text-yellow-300">how_to_reg</span>
                <p class="text-2xl font-bold mt-1">{{ $stats['attended'] }}</p>
                <p class="text-blue-200 text-xs">{{ __('Hadir') }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4 text-center">
                <span class="material-symbols-outlined text-3xl text-purple-300">workspace_premium</span>
                <p class="text-2xl font-bold mt-1">{{ $stats['certificates'] }}</p>
                <p class="text-blue-200 text-xs">{{ __('Sertifikat') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- Event List --}}
<section class="max-w-6xl mx-auto px-4 py-12">
    <h2 class="text-xl font-bold text-navy mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined">list_alt</span>
        {{ __('Kegiatan Saya') }}
    </h2>

    @if($registrations->isEmpty())
    <div class="text-center py-16 bg-grey-50 rounded-2xl">
        <span class="material-symbols-outlined text-5xl text-grey-300">event_busy</span>
        <p class="text-grey-500 mt-3">{{ __('Anda belum terdaftar di event apapun.') }}</p>
        <a href="{{ route('events') }}" class="inline-flex items-center gap-2 mt-4 text-blue font-semibold text-sm hover:underline">
            <span class="material-symbols-outlined text-lg">arrow_forward</span>
            {{ __('Lihat Event') }}
        </a>
    </div>
    @else
    <div class="space-y-4">
        @foreach($registrations as $reg)
        <a href="{{ route('participant.event', $reg) }}" class="block bg-white border border-grey-100 rounded-2xl p-5 hover:shadow-lg hover:border-blue/20 transition-all group">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                {{-- Date Badge --}}
                <div class="flex-shrink-0 w-16 h-16 bg-blue/5 rounded-xl flex flex-col items-center justify-center">
                    @if($reg->event && $reg->event->date)
                    <span class="text-blue font-bold text-lg leading-none">{{ $reg->event->date->format('d') }}</span>
                    <span class="text-blue/70 text-xs uppercase">{{ $reg->event->date->translatedFormat('M Y') }}</span>
                    @else
                    <span class="material-symbols-outlined text-blue text-2xl">event</span>
                    @endif
                </div>

                {{-- Event Info --}}
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-navy group-hover:text-blue transition-colors truncate">
                        {{ $reg->event?->title ?? __('Event Dihapus') }}
                    </h3>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-grey-500 text-xs mt-1">
                        @if($reg->event?->location)
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            {{ $reg->event->location }}
                        </span>
                        @endif
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">confirmation_number</span>
                            <span class="font-mono">{{ $reg->registration_code }}</span>
                        </span>
                    </div>
                </div>

                {{-- Status Badges --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    {{-- Registration Status --}}
                    @if($reg->status === 'confirmed')
                    <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        {{ __('Dikonfirmasi') }}
                    </span>
                    @elseif($reg->status === 'pending')
                    <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                        <span class="material-symbols-outlined text-sm">schedule</span>
                        {{ __('Menunggu') }}
                    </span>
                    @elseif($reg->status === 'cancelled')
                    <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                        <span class="material-symbols-outlined text-sm">cancel</span>
                        {{ __('Dibatalkan') }}
                    </span>
                    @endif

                    {{-- Attendance --}}
                    @if($reg->attended_at)
                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                        <span class="material-symbols-outlined text-sm">how_to_reg</span>
                        {{ __('Hadir') }}
                    </span>
                    @endif

                    {{-- Certificate --}}
                    @if($reg->attended_at)
                    <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                        <span class="material-symbols-outlined text-sm">workspace_premium</span>
                        {{ __('Sertifikat') }}
                    </span>
                    @endif

                    <span class="material-symbols-outlined text-grey-300 group-hover:text-blue transition-colors">chevron_right</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</section>
@endsection
