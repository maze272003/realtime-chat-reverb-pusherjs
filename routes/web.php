<?php

use Illuminate\Support\Facades\Route;
use App\Events\ChatMessageSent;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('chat');
});

Route::post('/send-message', function (Request $request) {
    $message = $request->input('message');
    $sender = $request->input('sender', 'Anonymous');

    if ($message) {
        // Ito ang crucial na line: toOthers()
        broadcast(new ChatMessageSent($message, $sender))->toOthers();
        return response()->json(['status' => 'Message sent!']);
    }
    return response()->json(['status' => 'No message provided.'], 400);
});