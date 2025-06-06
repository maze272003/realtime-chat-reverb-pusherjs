<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Reverb Chat</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* ... your CSS styles ... */
    </style>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div id="app">
        <div class="container">
            <h1>Laravel Reverb Chat</h1>
            <div id="chat-container">
                @foreach ($messages as $message)
                    <div class="message">
                        <strong>{{ $message->sender }}</strong>:
                        <span>{{ $message->content }}</span>
                        <span class="timestamp">{{ $message->created_at->format('Y-m-d H:i:s') }}</span>
                    </div>
                @endforeach
            </div>

            <form id="message-form" method="POST" action="{{ route('chat.send') }}">
                @csrf
                <input type="text" name="sender" id="sender-input" placeholder="Your name" required>
                <input type="text" name="content" placeholder="Type your message here..." required>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>