<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request, ChatService $chatService)
    {
        $data = $request->validate([
            'channel_id' => ['required', 'exists:channels,id'],
            'body' => ['required', 'string'],
        ]);

        $channel = Channel::findOrFail($data['channel_id']);

        $message = $chatService->sendMessage(Auth::user(), $channel, $data['body']);

        return response()->json($message->load('user'), 201);
    }
}
