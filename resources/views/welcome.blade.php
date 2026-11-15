<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nawasena - Ruang Curhat Mahasiswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] text-[#1b1b18] min-h-screen flex flex-col items-center justify-center p-6">
    <main class="w-full max-w-2xl text-center bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-[#19140015]">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 text-2xl mb-6 shadow-inner">
            🌿
        </div>
        <h1 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">Nawasena</h1>
        <p class="text-gray-600 text-base md:text-lg mb-8 leading-relaxed">
            Ruang curhat buat mahasiswa bareng Nawa, teman ngobrol AI yang siap mendengarkan kapan pun kamu butuh.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3 bg-[#1b1b18] text-white rounded-lg font-medium hover:bg-black transition-all shadow-sm">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3 border border-gray-300 rounded-lg font-medium hover:border-black transition-all">
                Daftar Akun
            </a>
        </div>
    </main>
    <footer class="mt-8 text-xs text-gray-400">
        &copy; {{ date('Y') }} Nawasena. All rights reserved.
    </footer>
</body>
</html>
