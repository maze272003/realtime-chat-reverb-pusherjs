<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Reverb Chat</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            flex-direction: column-reverse; /* New messages at bottom */
        }
        .message {
            margin-bottom: 10px;
            padding: 8px 12px;
            border-radius: 5px;
            background-color: #f0f0f0;
        }
        .message strong { color: #333; }
        .message span { color: #666; font-size: 0.9em; }
        #message-form {
            max-width: 600px;
            margin: 15px auto 0;
            display: flex;
        }
        #message-form input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        #message-form button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #message-form button:hover {
            background-color: #0056b3;
        }
        #sender-input {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
    @vite(['resources/js/app.js']) {{-- This loads your JavaScript --}}
</head>
<body>
    <h1>Laravel Reverb Live Chat (Guest Mode)</h1>

    <div id="chat-container"></div>

    <form id="message-form">
        <input type="text" id="sender-input" placeholder="Your Name (optional)" />
        <input type="text" id="message-input" placeholder="Type your message..." required />
        <button type="submit">Send</button>
    </form>

    <script>
         
    </script>
</body>
</html>