<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CurhatController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'    => ['nullable', 'string', 'max:40'],
            'mood'    => ['nullable', 'integer', 'between:1,5'],
            'topic'   => ['nullable', 'in:kuliah,pertemanan,keluarga,asmara,finansial,lainnya'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            'lang'    => ['nullable', 'in:id,en'],
            'sent_at' => ['nullable', 'date'],
        ]);

        $ticket = 'NWS-' . Str::upper(Str::random(6));

        // TODO (tahap BE): simpan ke database (model Curhat) pakai $data + $ticket
        // TODO (tahap n8n): kirim webhook ke n8n setelah tersimpan

        return response()->json(['ticket' => $ticket], 201);
    }
}