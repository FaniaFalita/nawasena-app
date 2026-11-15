@extends('layouts.nawasena')

@section('title', 'Daftar · Nawasena')
@section('titleKey', 'title_register')

@section('content')
  <section class="card auth">
    <h2 data-i18n="register_title"></h2>
    <p class="sub" data-i18n="register_sub"></p>

    {{-- TODO BE: ganti ke form POST beneran (method POST + @csrf) --}}
    
    <form id="authForm" method="POST" action="{{ route('register.process') }}">
        @csrf

        <div class="field">
            <label for="nickname" data-i18n="label_nickname"></label>
            <input
                type="text"
                id="nickname"
                name="nickname"
                maxlength="40"
                value="{{ old('nickname') }}"
                autocomplete="nickname"
                required
            />
            @error('nickname')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

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
                autocomplete="new-password"
                required
            />
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password2" data-i18n="label_password2"></label>
            <input
                type="password"
                id="password2"
                name="password_confirmation"
                autocomplete="new-password"
                required
            />
        </div>

        <button type="submit" class="btn-primary" data-i18n="btn_register"></button>
    </form>

    <p class="auth-alt">
      <span data-i18n="register_alt"></span>
      <a href="{{ route('login') }}" data-i18n="register_alt_link"></a>
    </p>
  </section>
@endsection