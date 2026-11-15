<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Nawasena')</title>
  <meta name="description" content="Ruang curhat buat mahasiswa bareng Nawa, teman ngobrol AI." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  {{-- Pasang tema tersimpan sebelum halaman digambar, biar nggak "kedip" pas pindah halaman --}}
  <script>
    try {
      var savedTheme = localStorage.getItem('nawasena-theme');
      if (savedTheme) document.documentElement.setAttribute('data-theme', savedTheme);
    } catch (e) {}
  </script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-title-key="@yield('titleKey', 'title_landing')">
  <div class="bg-blob blob-1" aria-hidden="true"></div>
  <div class="bg-blob blob-2" aria-hidden="true"></div>

  <main class="wrap">
    <!-- HEADER -->
    <header class="topbar">
      <a class="brand" href="@yield('brandHref', url('/'))">
        <span class="brand-icon" aria-hidden="true">🌿</span>
        <h1>Nawasena</h1>
      </a>

      <div class="controls">
        <div class="seg" role="group" aria-label="Theme" data-i18n-aria="aria_theme">
          <button type="button" class="seg-btn" data-theme-btn="light" aria-pressed="true"><span aria-hidden="true">🌞</span> Light</button>
          <button type="button" class="seg-btn" data-theme-btn="dark" aria-pressed="false"><span aria-hidden="true">🌙</span> Dark</button>
          <button type="button" class="seg-btn" data-theme-btn="pastel" aria-pressed="false"><span aria-hidden="true">🌸</span> Pastel</button>
        </div>
        <span class="divider" aria-hidden="true"></span>
        <div class="lang" role="group" aria-label="Language" data-i18n-aria="aria_lang">
          <button type="button" class="lang-btn" data-lang-btn="id" aria-pressed="true">ID</button>
          <button type="button" class="lang-btn" data-lang-btn="en" aria-pressed="false">EN</button>
        </div>
      </div>
    </header>

    @yield('nav')

    @yield('content')

    @yield('after')

    <footer class="footer">
      <small data-i18n="footer"></small>
    </footer>
  </main>
</body>
</html>