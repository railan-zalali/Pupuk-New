@extends('layouts.guest')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="text-center">
    <div class="mb-6 relative">
        <div class="w-32 h-32 bg-emerald-100 rounded-full flex items-center justify-center mx-auto animate-pulse-slow">
            <i class="ti ti-error-404 text-6xl text-emerald-600"></i>
        </div>
        <div class="absolute top-0 right-1/3 w-8 h-8 bg-yellow-400 rounded-full blur-md animate-float" style="animation-delay: 1s"></div>
        <div class="absolute bottom-0 left-1/3 w-6 h-6 bg-blue-400 rounded-full blur-md animate-float" style="animation-delay: 2s"></div>
    </div>

    <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">404</h1>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">Halaman Tidak Ditemukan</h2>

    <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
        Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman tersebut telah dipindahkan atau dihapus.
    </p>

    <a href="{{ route('dashboard') }}" class="btn-primary inline-flex items-center gap-2">
        <i class="ti ti-home"></i>
        Kembali ke Dashboard
    </a>
</div>
@endsection