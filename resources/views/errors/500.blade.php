@extends('layouts.guest')

@section('title', 'Terjadi Kesalahan Server')

@section('content')
<div class="text-center">
    <div class="mb-6 relative">
        <div class="w-32 h-32 bg-red-100 rounded-full flex items-center justify-center mx-auto animate-pulse-slow">
            <i class="ti ti-server-off text-6xl text-red-600"></i>
        </div>
        <div class="absolute top-0 right-1/3 w-8 h-8 bg-red-400 rounded-full blur-md animate-float" style="animation-delay: 1s"></div>
        <div class="absolute bottom-0 left-1/3 w-6 h-6 bg-orange-400 rounded-full blur-md animate-float" style="animation-delay: 2s"></div>
    </div>

    <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">500</h1>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">Terjadi Kesalahan Server</h2>

    <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
        Maaf, terjadi kesalahan internal pada server kami. Silakan coba beberapa saat lagi atau hubungi administrator.
    </p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('dashboard') }}" class="btn-primary inline-flex items-center gap-2">
            <i class="ti ti-home"></i>
            Dashboard
        </a>
        <button onclick="window.location.reload()" class="btn-ghost inline-flex items-center gap-2">
            <i class="ti ti-refresh"></i>
            Muat Ulang
        </button>
    </div>
</div>
@endsection