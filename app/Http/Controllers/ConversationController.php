<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConversationController extends Controller
{
    public function index(Request $request): View
    {
        $conversations = Conversation::query()
            ->where('user_id', $request->user()->id)
            ->with('messages')
            ->orderByDesc('updated_at')
            ->get();

        return view('riwayat', compact('conversations'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            'mood' => ['nullable', 'integer', 'between:1,5'],
            'topic' => [
                'nullable',
                'in:kuliah,pertemanan,keluarga,asmara,finansial,lainnya',
            ],
            'lang' => ['required', 'in:id,en'],
        ]);

        $conversation = Conversation::create([
            'user_id' => $request->user()->id,
            'ticket' => 'NWS-' . Str::upper(Str::random(6)),
            'title' => 'Obrolan baru',
            'mood' => $data['mood'] ?? null,
            'topic' => $data['topic'] ?? null,
            'lang' => $data['lang'],
            'status' => 'active',
        ]);

        $conversation->messages()->create([
            'role' => 'user',
            'content' => $data['message'],
        ]);

        return response()->json([
            'id' => $conversation->id,
            'url' => route('obrolan', ['id' => $conversation->id]),
        ], 201);
    }

    public function show(Request $request, string $id): View
    {
        $conversation = $this->findOwnedConversation($request, $id);
        $conversation->load('messages');

        return view('obrolan', compact('conversation'));
    }

    public function updateTitle(Request $request, string $id): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:1', 'max:80'],
        ]);

        $conversation = $this->findOwnedConversation($request, $id);
        $conversation->update([
            'title' => trim($data['title']),
        ]);

        return back()->with('status', 'Judul percakapan berhasil diubah.');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        $conversation = $this->findOwnedConversation($request, $id);
        $conversation->delete();

        // Pesan terkait ikut terhapus karena cascadeOnDelete di migration.
        return redirect()
            ->route('riwayat')
            ->with('status', 'Percakapan berhasil dihapus.');
    }

    private function findOwnedConversation(
        Request $request,
        string $id
    ): Conversation {
        return Conversation::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);
    }
}