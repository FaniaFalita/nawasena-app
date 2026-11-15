<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nChatService
{
    public function sendMessage(string $message, string $locale = 'id')
    {
        $webhookUrl = config('services.n8n.webhook_url');

        try {
            $response = Http::timeout(30)->post($webhookUrl, [
                'message' => $message,
                'locale'  => $locale,
            ]);

            if ($response->successful()) {
                // Mengambil key 'reply' dari JSON yang dikirimkan node 'Respond to Webhook' di n8n
                return $response->json('reply') ?? 'Maaf, Nawa tidak mendapatkan respons yang sesuai.';
            }

            Log::error('N8n Webhook Error: ' . $response->body());
            return 'Maaf, terjadi masalah saat menghubungkan ke Nawa AI.';
        } catch (\Exception $e) {
            Log::error('N8n Connection Exception: ' . $e->getMessage());
            return 'Maaf, Nawa sedang tidak dapat dihubungi saat ini.';
        }
    }
}