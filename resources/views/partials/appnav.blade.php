{{-- Navigasi untuk halaman setelah masuk. Pakai: @include('partials.appnav', ['active' => 'beranda']) --}}
<nav class="appnav" aria-label="Navigasi">
  <a href="{{ route('beranda') }}" data-i18n="nav_home" @if(($active ?? '') === 'beranda') aria-current="page" @endif></a>
  <a href="{{ route('riwayat') }}" data-i18n="nav_history" @if(($active ?? '') === 'riwayat') aria-current="page" @endif></a>
  {{-- Berhasil diganti jadi pemicu form POST logout --}}
  <a class="spacer" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-i18n="nav_logout"></a>
</nav>