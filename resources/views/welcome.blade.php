@extends('layouts.nawasena')

@section('title', 'Nawasena · Ruang Curhat Mahasiswa')
@section('titleKey', 'title_landing')

@section('content')
  <section class="hero">
    <span class="hero-badge" data-i18n="hero_badge"></span>
    <h2 data-i18n-html="hero_title"></h2>
    <p data-i18n="hero_text"></p>
    <div class="hero-cta">
      <a class="btn btn-solid" href="{{ route('register') }}" data-i18n="cta_start"></a>
      <a class="btn btn-outline" href="{{ route('login') }}" data-i18n="cta_login"></a>
    </div>
  </section>

  <section class="features" aria-label="Fitur">
    <article class="feature">
      <div class="feature-icon" aria-hidden="true">🔒</div>
      <h3 data-i18n="f1_title"></h3>
      <p data-i18n="f1_text"></p>
    </article>
    <article class="feature">
      <div class="feature-icon" aria-hidden="true">💬</div>
      <h3 data-i18n="f2_title"></h3>
      <p data-i18n="f2_text"></p>
    </article>
    <article class="feature">
      <div class="feature-icon" aria-hidden="true">🗂️</div>
      <h3 data-i18n="f3_title"></h3>
      <p data-i18n="f3_text"></p>
    </article>
  </section>
@endsection
