<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Reverb Chat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Make sure this is present --}}

    <style>
        body { font-family: sans-serif; margin: 20px; }
        #chat-container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 15px;
            height: 400px;
            overflow-y: scroll;
            display: flex;
            flex-direction: column; /* Changed to column for initial load from oldest to newest */
            justify-content: flex-end; /* Keeps messages at the bottom */
        }
        .message {
            margin-bottom: 10px;
            padding: 8px 12px;
            border-radius: 5px;
            background-color: #f0f0f0;
            word-wrap: break-word; /* To prevent overflow for long messages */
        }
        .message strong { color: #333; }
        .message span { color: #666; font-size: 0.9em; }
        #message-form {
            max-width: 600px;
            margin: 15px auto 0;
            display: flex;
            flex-wrap: wrap; /* Allows inputs to wrap on smaller screens */
        }
        #sender-input, #message-input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px; /* Spacing between inputs */
            box-sizing: border-box;
        }
        #sender-input {
            flex: 0 0 100%; /* Take full width on top */
        }
        #message-input {
            flex-grow: 1; /* Takes remaining space */
            margin-right: 10px;
        }
        #message-form button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            flex-shrink: 0; /* Prevents button from shrinking */
        }
        #message-form button:hover {
            background-color: #0056b3;
        }
    </style>

</head>
<body>
    <h1>Laravel Reverb Live Chat (Guest Mode)</h1>

    <div id="chat-container">
        {{-- Loop through existing messages for backreading --}}
        @foreach ($messages as $message)
            <div class="message">
                <strong>{{ $message->sender_name }}:</strong> <span>{{ $message->message }}</span>
            </div>
        @endforeach
    </div>

    <form id="message-form">
        <input type="text" id="sender-input" placeholder="Your Name (optional)" />
        <input type="text" id="message-input" placeholder="Type your message..." required />
        <button type="submit">Send</button>
    </form>
       @vite(['resources/js/app.js'])
</body>
</html>
