@extends('layouts.nawasena')

@section('title', 'Obrolan · Nawasena')
@section('titleKey', 'title_chat')
@section('brandHref', route('beranda'))

@section('nav')
  @include('partials.appnav', ['active' => 'chat'])
@endsection

@section('content')
  <section class="chat-card" aria-label="Chat">
    <div class="chat-head">
      <div class="chat-avatar" aria-hidden="true">🌿</div>

      <div>
        <div class="chat-title">
          <span>{{ $conversation->title }}</span>
          <span class="tag">AI</span>
        </div>

        <div class="chat-sub" data-i18n="chat_role"></div>

        <details>
          <summary class="btn btn-outline btn-sm"
                   data-i18n="btn_edit_title">
            Ubah judul
          </summary>

          <form method="POST"
                action="{{ route('conversations.title', ['id' => $conversation->id]) }}">
            @csrf
            @method('PATCH')

            <label for="chat-title"
                   data-i18n="label_conversation_title">
              Judul percakapan
            </label>

            <input id="chat-title"
                   type="text"
                   name="title"
                   value="{{ $conversation->title }}"
                   maxlength="80"
                   required>

            <button class="btn btn-outline btn-sm"
                    type="submit"
                    data-i18n="btn_save_title">
              Simpan judul
            </button>
          </form>
        </details>
      </div>

      <div class="chat-meta">
        <span class="chat-ticket">{{ $conversation->ticket }}</span>
        <a class="btn btn-outline btn-sm"
           href="{{ route('riwayat') }}"
           data-i18n="chat_back"></a>
      </div>
    </div>

    <div class="chat-note" data-i18n="chat_note"></div>

    <div class="chat-body"
         id="chatBody"
         data-mode="saved"
         data-conversation-id="{{ $conversation->id }}"
         aria-live="polite">
      @forelse ($conversation->messages as $message)
        <div class="msg {{ $message->role === 'user' ? 'msg-user' : 'msg-bot' }}">
          <div class="bubble">{{ $message->content }}</div>
          <span class="msg-time">{{ $message->created_at->format('H:i') }}</span>
        </div>
      @empty
        <div class="msg msg-bot">
          <div class="bubble" data-i18n="bot_greeting"></div>
        </div>
      @endforelse
    </div>

    <form class="composer" id="chatForm">
      <label class="sr-only" for="chatInput" data-i18n="ph_chat"></label>

      <textarea id="chatInput"
                rows="1"
                maxlength="2000"
                data-i18n-placeholder="ph_chat"></textarea>

      <button type="submit"
              class="btn btn-solid"
              id="chatSend"
              data-i18n="btn_chat_send"></button>
    </form>
  </section>
@endsection

@section('after')
  @include('partials.crisis')
@endsection