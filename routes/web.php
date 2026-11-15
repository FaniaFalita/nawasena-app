<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\CurhatController;

// Halaman publik / Landing Page
Route::view('/', 'welcome')->name('landing');

// Rute khusus untuk user yang BELUM login (Guest)
Route::middleware('guest')->group(function () {
    // Tampilan Form Login & Register dimasukkan ke sini agar terproteksi secara benar
    Route::view('/masuk', 'auth.login')->name('login');
    Route::view('/daftar', 'auth.register')->name('register');

    // Proses kirim data Login dan Register
    Route::post('/masuk', [AuthController::class, 'login'])->name('login.process');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.process');
});

// Proses Logout (Wajib via POST + harus sudah login)
Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Halaman yang memerlukan login / Proteksi Auth
Route::middleware('auth')->group(function () {

    Route::view('/beranda', 'beranda')->name('beranda');

    Route::get('/riwayat', [ConversationController::class, 'index'])->name('riwayat');

    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');

    Route::get('/obrolan/{id}', [ConversationController::class, 'show'])->whereNumber('id')->name('obrolan');

    Route::patch('/conversations/{id}/title', [ConversationController::class, 'updateTitle'])->whereNumber('id')->name('conversations.title');

    Route::delete('/conversations/{id}', [ConversationController::class, 'destroy'])->whereNumber('id')->name('conversations.destroy');

    Route::post('/api/chat/reply', [ChatController::class, 'reply'])->name('chat.reply');

    Route::post('/curhat', [CurhatController::class, 'store'])->name('curhat.store');
});

Route::get('/test-n8n', function () {
    $response = Http::post('http://localhost:5678/webhook-test/nawasena-ai-chat', [
        'message' => 'Halo Nawa, ini tes curhatan dari mahasiswa.',
        'user_email' => 'mahasiswa@nawasena.test'
    ]);

    return 'Data berhasil dikirim ke n8n! Cek layar n8n kamu sekarang.';
});

// Endpoint untuk menerima balasan AI normal (Jalur False)
Route::post('/v1/chat/callback', [ChatController::class, 'handleAiCallback']);

// Endpoint untuk menerima sinyal krisis darurat (Jalur True)
Route::post('/v1/chat/crisis-trigger', [ChatController::class, 'handleCrisisTrigger']);
