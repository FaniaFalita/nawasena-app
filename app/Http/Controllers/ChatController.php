<?php

namespace App\Http\Controllers;

use App\Services\N8nChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\Message;
use App\Models\Conversation;

class ChatController extends Controller
{
    public function reply(Request $request, N8nChatService $chat): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:2000'],
            'lang' => ['required', 'in:id,en'],
            'history' => ['sometimes', 'array', 'max:12'],
            'history.*.role' => ['required', 'in:user,assistant'],
            'history.*.content' => ['required', 'string', 'max:2000'],
        ]);

        // 1. Deteksi krisis lokal di Laravel (seperti kode aslimu)
        $kataKrisis = [
            'bunuh diri', 'mau mati', 'ingin mati', 'pengen mati', 'akhiri hidup',
            'menyakiti diri', 'nyakitin diri', 'self harm', 'suicide', 'kill myself', 'end my life',
        ];
        $teks = mb_strtolower($data['message']);
        
        foreach ($kataKrisis as $kata) {
            if (str_contains($teks, $kata)) {
                $reply = $data['lang'] === 'en'
                    ? "I'm really glad you told me. You matter, and you don't have to face this alone. Please reach out to someone you trust right now, or call the Indonesian Ministry of Health mental health hotline at 119 ext. 8. I'm here with you too. Can you tell me what's going on?"
                    : 'Makasih udah cerita, aku peduli sama kamu dan kamu nggak sendirian. Tolong hubungi orang terdekat sekarang, atau Hotline Kesehatan Mental Kemenkes di 119 ekstensi 8. Aku tetap di sini nemenin kamu. Boleh cerita apa yang lagi kamu rasain?';

                return response()->json(['reply' => $reply]);
            }
        }

        // 2. Jika aman dari krisis, teruskan langsung ke OpenRouter (tanpa n8n)
        try {
            $userMessage = $data['message'];
            $locale = $data['lang'];
            $history = $data['history'] ?? [];

            $systemPrompt = $locale === 'en'
                ? 'You are Nawa, a warm, empathetic, and supportive AI companion for mental well-being in Nawasena. Respond naturally in English.'
                : 'Kamu adalah Nawa, teman ngobrol AI yang hangat, empatik, dan suportif untuk kesehatan mental di Nawasena. Jawablah dengan bahasa Indonesia yang ramah dan menenangkan.';

            // Susun riwayat percakapan agar AI nyambung dengan konteks sebelumnya
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
            ];

            foreach ($history as $turn) {
                $messages[] = [
                    'role' => $turn['role'],
                    'content' => $turn['content'],
                ];
            }

            // Pastikan pesan terbaru pengguna masuk di urutan terakhir
            if (empty($history) || end($history)['content'] !== $userMessage) {
                $messages[] = ['role' => 'user', 'content' => $userMessage];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                'HTTP-Referer' => config('app.url'),
                'X-Title' => 'Nawasena',
            ])->timeout(30)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'openai/gpt-4o-mini',
                'messages' => $messages,
            ]);

            if (!$response->successful()) {
                throw new \RuntimeException('OpenRouter API error: ' . $response->body());
            }

            $reply = trim((string) $response->json('choices.0.message.content'));

            if ($reply === '') {
                throw new \RuntimeException('Empty response from OpenRouter');
            }

        } catch (Throwable $exception) {
            Log::warning('Nawasena chat failed.', [
                'exception_type' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return response()->json(['error' => 'chat_unavailable'], 502);
        }

        return response()->json(['reply' => $reply]);
    }

    /**
     * Menerima kiriman balasan obrolan normal dari n8n (Jalur False)
     */
    public function handleAiCallback(Request $request): JsonResponse
    {
        $request->validate([
            'conversation_id' => ['required'],
            'message_text' => ['required', 'string']
        ]);

        // Simpan pesan bot ke database
        Message::create([
            'conversation_id' => $request->conversation_id,
            'sender' => 'ai', 
            'message' => $request->message_text,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Pesan AI berhasil disimpan']);
    }

    /**
     * Menerima notifikasi darurat krisis dari n8n (Jalur True)
     */
    public function handleCrisisTrigger(Request $request): JsonResponse
    {
        $request->validate([
            'conversation_id' => ['required']
        ]);

        // Kirim pesan otomatis sistem berisi nomor bantuan
        Message::create([
            'conversation_id' => $request->conversation_id,
            'sender' => 'system', 
            'message' => '⚠️ SISTEM: Kami mendeteksi Anda sedang dalam kondisi darurat. Harap hubungi Hotline Kesehatan Mental Kemenkes di 119 ekstensi 8, atau hubungi konselor manusia kami segera.',
        ]);

        return response()->json(['status' => 'crisis_handled']);
    }
}