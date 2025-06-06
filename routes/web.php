<?php

use Illuminate\Support\Facades\Route;
use App\Events\ChatMessageSent;
use Illuminate\Http\Request;
use App\Models\Message; // Import the Message model

Route::get('/', function () {
    // Fetch and pass existing messages to the view
    $messages = Message::latest()->limit(50)->get(); // Get the latest 50 messages for backread
    return view('chat', compact('messages'));
});

Route::post('/send-message', function (Request $request) {
    // Add validation for better practice
    $request->validate([
        'message' => 'required|string|max:1000',
        'sender' => 'nullable|string|max:50',
    ]);

    $messageContent = $request->input('message');
    $senderName = $request->input('sender', 'Anonymous');

    // 1. Save the message to the database
    $message = Message::create([
        'sender_name' => $senderName,
        'message' => $messageContent,
    ]);

    // 2. Broadcast the event (now the message includes sender and content)
    // We can directly pass the created Message model instance if we want,
    // but for simplicity and consistency with previous event, we'll extract the data.
    broadcast(new ChatMessageSent($message->message, $message->sender_name))->toOthers();

    return response()->json(['status' => 'Message sent!']);
});
