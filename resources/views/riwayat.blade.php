@extends('layouts.nawasena')

@section('title', 'Riwayat · Nawasena')
@section('titleKey', 'title_history')
@section('brandHref', route('beranda'))

@section('nav')
  @include('partials.appnav', ['active' => 'riwayat'])
@endsection

@section('content')
  <div class="page-head">
    <div>
      <h2 data-i18n="history_title"></h2>
      <p data-i18n="history_sub"></p>
    </div>

    <a class="btn btn-solid btn-sm"
       href="{{ route('beranda') }}"
       data-i18n="btn_new"></a>
  </div>

  @if (session('status'))
    <p role="status">{{ session('status') }}</p>
  @endif

  <div class="convo-list">
    @forelse ($conversations as $conversation)
      @php
        $moodFaces = [
          1 => '😞',
          2 => '😕',
          3 => '😐',
          4 => '🙂',
          5 => '😄',
        ];

        $mood = $moodFaces[$conversation->mood ?? 3] ?? '🌿';
        $lastMessage = $conversation->messages->last();
      @endphp

      <div class="convo">
        <span class="convo-mood" aria-hidden="true">{{ $mood }}</span>

        <div class="convo-body">
          <a class="convo-open"
             href="{{ route('obrolan', ['id' => $conversation->id]) }}">
            <span class="convo-top">
              <strong class="convo-ticket">{{ $conversation->title }}</strong>

              <span class="tag">{{ $conversation->ticket }}</span>

              @if ($conversation->topic)
                <span class="tag"
                      data-i18n="topic_{{ $conversation->topic }}"></span>
              @endif

              <span class="tag status status-{{ $conversation->status }}"
                    data-i18n="status_{{ $conversation->status }}"></span>

              <span class="convo-date">
                {{ $conversation->updated_at->format('d M Y') }}
              </span>
            </span>

            <span class="convo-snippet">
              {{ $lastMessage
                  ? \Illuminate\Support\Str::limit($lastMessage->content, 120)
                  : '' }}
            </span>
          </a>

                    <details class="convo-menu">
            <summary class="convo-menu-toggle" aria-label="Buka pengaturan percakapan">
              <span class="toggle-icon">▼</span>
            </summary>

            <div class="convo-menu-content">
              <form class="form-rename" method="POST"
                    action="{{ route('conversations.title', ['id' => $conversation->id]) }}">
                @csrf
                @method('PATCH')

                <div class="input-group">
                  <label for="title-{{ $conversation->id }}" data-i18n="label_conversation_title" class="sr-only">
                    Judul percakapan
                  </label>
                  <input id="title-{{ $conversation->id }}"
                         type="text"
                         name="title"
                         value="{{ $conversation->title }}"
                         maxlength="80"
                         required>
                  <button class="btn btn-outline btn-sm" type="submit" data-i18n="btn_save_title">
                    Simpan judul
                  </button>
                </div>
              </form>

              <form class="form-delete" method="POST"
                    action="{{ route('conversations.destroy', ['id' => $conversation->id]) }}"
                    onsubmit="return confirm(document.documentElement.lang === 'en'
                      ? 'Delete this conversation and its messages?'
                      : 'Yakin hapus percakapan ini beserta semua pesannya?')">
                @csrf
                @method('DELETE')

                <button class="btn btn-danger-outline btn-sm" type="submit">
                  <span data-i18n="btn_delete_conversation">Hapus percakapan</span>
                </button>
              </form>
            </div>
          </details>
        </div>
      </div>
    @empty
      <p data-i18n="history_empty">Belum ada percakapan tersimpan.</p>
    @endforelse
  </div>
@endsection