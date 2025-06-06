<?php
// app/Http/Controllers/ChatController.php
namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getMessages()
    {
        return Message::latest()->limit(50)->get();
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'sender' => $request->sender,
            'message' => $request->message
        ]);

        broadcast(new MessageSent($request->sender, $request->message))->toOthers();

        return response()->json(['status' => 'Message sent']);
    }
}
