<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Reverb Chat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --bg-color: #f9fafb;
            --card-color: #ffffff;
            --text-color: #1f2937;
            --text-light: #6b7280;
            --border-color: #e5e7eb;
            --user-message: #e0e7ff;
            --other-message: #f3f4f6;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: var(--primary-color);
        }

        .chat-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: var(--card-color);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        #chat-container {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 12px;
            line-height: 1.4;
            position: relative;
            word-wrap: break-word;
        }

        .message-user {
            align-self: flex-end;
            background-color: var(--user-message);
            border-bottom-right-radius: 4px;
            color: var(--text-color);
        }

        .message-other {
            align-self: flex-start;
            background-color: var(--other-message);
            border-bottom-left-radius: 4px;
        }

        .message-sender {
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 4px;
            color: var(--primary-color);
        }

        .message-text {
            font-size: 0.95rem;
        }

        .message-time {
            font-size: 0.75rem;
            color: var(--text-light);
            text-align: right;
            margin-top: 4px;
        }

        .message-form-container {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            background-color: var(--card-color);
        }

        #message-form {
            display: flex;
            gap: 10px;
        }

        #sender-input {
            flex: 0 0 120px;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }

        #message-input {
            flex: 1;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        #message-form button {
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        /* Add all other CSS styles from previous example */

    </style>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div class="container">
        <header>
            <h1>Laravel Reverb Live Chat</h1>
        </header>

        <div class="chat-wrapper">
            <div id="chat-container"></div>

            <div class="typing-indicator" id="typing-indicator"></div>

            <div class="message-form-container">
                <form id="message-form">
                    <input type="text" id="sender-input" placeholder="Your name" />
                    <input type="text" id="message-input" placeholder="Type your message..." required />
                    <button type="submit">Send</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
