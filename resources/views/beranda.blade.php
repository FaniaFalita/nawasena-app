@extends('layouts.nawasena')

@section('title', 'Beranda · Nawasena')
@section('titleKey', 'title_home')
@section('brandHref', route('beranda'))

@section('nav')
  @include('partials.appnav', ['active' => 'beranda'])
@endsection

@section('content')
  <section class="welcome" aria-labelledby="welcome-text">
    <p id="welcome-text" data-i18n-html="home_welcome"></p>
  </section>

  <section class="card" id="formCard">
    {{-- TODO BE: bikin conversation baru lewat Laravel, lalu redirect ke /obrolan/{id} --}}
    <form id="curhatForm" data-next="{{ route('obrolan', ['id' => 'baru']) }}" novalidate>
      <fieldset class="field">
        <legend data-i18n="label_mood"></legend>
        <div class="moods" id="moods">
          <label class="mood"><input type="radio" name="mood" value="1" /><span class="mood-face">😞</span><span class="mood-text" data-i18n="mood_1"></span></label>
          <label class="mood"><input type="radio" name="mood" value="2" /><span class="mood-face">😕</span><span class="mood-text" data-i18n="mood_2"></span></label>
          <label class="mood"><input type="radio" name="mood" value="3" /><span class="mood-face">😐</span><span class="mood-text" data-i18n="mood_3"></span></label>
          <label class="mood"><input type="radio" name="mood" value="4" /><span class="mood-face">🙂</span><span class="mood-text" data-i18n="mood_4"></span></label>
          <label class="mood"><input type="radio" name="mood" value="5" /><span class="mood-face">😄</span><span class="mood-text" data-i18n="mood_5"></span></label>
        </div>
      </fieldset>

      <fieldset class="field">
        <legend data-i18n="label_topic"></legend>
        <div class="chips" id="topics">
          <label class="chip"><input type="radio" name="topic" value="kuliah" /><span data-i18n="topic_kuliah"></span></label>
          <label class="chip"><input type="radio" name="topic" value="pertemanan" /><span data-i18n="topic_pertemanan"></span></label>
          <label class="chip"><input type="radio" name="topic" value="keluarga" /><span data-i18n="topic_keluarga"></span></label>
          <label class="chip"><input type="radio" name="topic" value="asmara" /><span data-i18n="topic_asmara"></span></label>
          <label class="chip"><input type="radio" name="topic" value="finansial" /><span data-i18n="topic_finansial"></span></label>
          <label class="chip"><input type="radio" name="topic" value="lainnya" /><span data-i18n="topic_lainnya"></span></label>
        </div>
      </fieldset>

      <div class="field">
        <label for="message" data-i18n="label_message"></label>
        <textarea id="message" name="message" rows="6" maxlength="2000" required
                  data-i18n-placeholder="ph_message"></textarea>
        <div class="meta-row">
          <span class="error" id="msgError" role="alert" aria-live="assertive"></span>
          <span class="counter"><span id="count">0</span>/2000</span>
        </div>
      </div>

      <div class="field">
        <label class="check">
          <input type="checkbox" id="anonymous" name="anonymous" />
          <span>
            <strong data-i18n="check_anon"></strong><br />
            <small data-i18n="check_anon_hint"></small>
          </span>
        </label>
      </div>

      <button type="submit" class="btn-primary" id="submitBtn" data-i18n="btn_start"></button>

      <p class="privacy" data-i18n="privacy"></p>
    </form>
  </section>

  <!-- Form POST Logout Tersembunyi (Tambahan Baru) -->
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
  </form>
@endsection

@section('after')
  @include('partials.crisis')
@endsection