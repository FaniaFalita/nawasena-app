@extends('layouts.nawasena')

@section('title', 'Masuk · Nawasena')
@section('titleKey', 'title_login')

@section('content')
  <section class="card auth">
    <h2 data-i18n="login_title"></h2>
    <p class="sub" data-i18n="login_sub"></p>

    {{-- TODO BE: ganti ke form POST beneran (method POST + @csrf) --}}
    
<form id="laravelAuthForm" method="POST" action="{{ route('login.process') }}">
    @csrf

    <div class="field">
        <label for="email" data-i18n="label_email"></label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            autocomplete="email"
            required
        />
        @error('email')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="password" data-i18n="label_password"></label>
        <input
            type="password"
            id="password"
            name="password"
            autocomplete="current-password"
            required
        />
        @error('password')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" id="submitBtn" class="btn-primary" data-i18n="btn_login">Masuk</button>
</form>

    <p class="auth-alt">
      <span data-i18n="login_alt"></span>
      <a href="{{ route('register') }}" data-i18n="login_alt_link"></a>
    </p>
  </section>
@endsection