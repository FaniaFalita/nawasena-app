@extends('nawasena')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[80vh] text-center px-4">
    <h1 class="text-4xl font-extrabold mb-4">Selamat Datang di Nawasena</h1>
    <p class="text-gray-600 dark:text-gray-300 mb-8 max-w-md">
        Ruang curhat buat mahasiswa bareng Nawa, teman ngobrol AI yang siap mendengarkan.
    </p>

    <div class="flex items-center gap-4">
        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-black text-white dark:bg-white dark:text-black rounded-sm font-medium">
            Masuk
        </a>
        <a href="{{ route('register') }}" class="px-6 py-2.5 border border-black dark:border-white rounded-sm font-medium">
            Daftar
        </a>
    </div>
</div>
@endsection
