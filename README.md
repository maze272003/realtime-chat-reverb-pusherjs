# Laravel 10 Reverb Live Chat (Guest Mode)

This is a real-time live chat application built with Laravel 10, Reverb for WebSocket broadcasting, and Blade/Vite for the frontend. It allows multiple guests to chat without requiring user authentication.

## Getting Started

Follow these steps to set up and run the chat application on your local machine.

### Prerequisites

Before you begin, ensure you have the following installed:

* **PHP:** >= 8.1
* **Composer:** Latest version
* **Node.js:** LTS version (e.g., 18.x or 20.x)
* **NPM (Node Package Manager):** Comes with Node.js
* **Laravel:** (Not strictly required to be globally installed, as we'll use `composer create-project`)

### Installation & Setup

1.  **Create a new Laravel 10 Project:**
    Open your terminal and run:
    ```bash
    composer create-project laravel/laravel chat-app "10.*"
    cd chat-app
    ```

2.  **Install Reverb:**
    For Laravel 10, Reverb is installed via Composer first, then initialized:
    ```bash
    composer require laravel/reverb
    php artisan reverb:install
    ```
    * During `php artisan reverb:install`, you will be prompted to install frontend dependencies (`laravel-echo` and `pusher-js`). **Type `yes` and press Enter.**

3.  **Run Database Migrations (Optional but Recommended):**
    While this specific chat doesn't use a database for messages, it's good practice for any Laravel project:
    ```bash
    php artisan migrate
    ```

4.  **Verify `.env` Configuration:**
    Open your `.env` file and ensure the following Reverb and VITE variables are correctly set. The `reverb:install` command should have handled this, but double-check.
    ```dotenv
    # ... other .env variables

    BROADCAST_CONNECTION=reverb

    REVERB_APP_ID=your_generated_id
    REVERB_APP_KEY=your_generated_key
    REVERB_APP_SECRET=your_generated_secret
    REVERB_HOST="localhost" # Or "0.0.0.0"
    REVERB_PORT=8080
    REVERB_SCHEME=http
    REVERB_CLUSTER=mt1 # Added for Pusher JS compatibility

    VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
    VITE_REVERB_HOST="${REVERB_HOST}"
    VITE_REVERB_PORT="${REVERB_PORT}"
    VITE_REVERB_SCHEME="${REVERB_SCHEME}"
    VITE_REVERB_CLUSTER="${REVERB_CLUSTER}" # Make sure this is present
    ```
    * **Note:** If you have `PUSHER_APP_*` variables, ensure `BROADCAST_CONNECTION` is `reverb` and your `resources/js/bootstrap.js` uses the `VITE_REVERB_*` variables.

5.  **Clean and Install Frontend Dependencies (if issues persist):**
    If you encountered `Failed to resolve import "laravel-echo"` errors, a clean re-installation of npm dependencies is often necessary:
    ```bash
    # In the project root (chat-app)
    rm -rf node_modules # On Windows: rmdir /s /q node_modules
    del package-lock.json # On Windows: del package-lock.json
    npm cache clean --force
    npm install
    ```
    * **Windows Users:** Use `rmdir /s /q node_modules` instead of `rm -rf node_modules`.

### Core Development Files & Logic

Here's a summary of the important files and the logic implemented:

* **`app/Events/ChatMessageSent.php`**: Defines the event that will be broadcast.
    * Implements `ShouldBroadcast`.
    * `broadcastOn()`: Uses `new Channel('public-chat')` for guest access.
    * `broadcastAs()`: Returns `'message-sent'`.
* **`routes/web.php`**:
    * Defines a `GET /` route to load the chat view.
    * Defines a `POST /send-message` route that receives messages and broadcasts the `ChatMessageSent` event using `broadcast(new ChatMessageSent(...))->toOthers()`. The `toOthers()` method ensures the sender does not receive their own broadcast message.
* **`resources/views/chat.blade.php`**:
    * The main chat interface.
    * Includes `<meta name="csrf-token" content="{{ csrf_token() }}">` in the `<head>` for AJAX requests.
    * Includes `@vite(['resources/js/app.js'])` to load compiled JavaScript.
* **`resources/js/bootstrap.js`**:
    * Configures Laravel Echo to connect to the Reverb server.
    * Ensures `cluster: 'mt1'` (or `VITE_REVERB_CLUSTER`) is provided for Pusher JS compatibility.
    * Uses `VITE_REVERB_APP_KEY`, `VITE_REVERB_HOST`, `VITE_REVERB_PORT`, `VITE_REVERB_SCHEME`, and `VITE_REVERB_CLUSTER` for Reverb connection.
* **`resources/js/app.js`**:
    * Listens to the `public-chat` channel for `.message-sent` events.
    * Uses an "optimistic UI update": when a user sends a message, it's immediately displayed in their chat with `(You)` appended to the sender's name. This prevents the sender from waiting for their own message to be broadcast back from Reverb.
    * Handles sending messages via a `fetch` POST request to `/send-message`.

## Running the Application

You need to run three separate commands in three separate terminal windows:

1.  **Start the Laravel Development Server:**
    ```bash
    php artisan serve
    ```
    (This will typically run on `http://127.0.0.1:8000`)

2.  **Start the Reverb WebSocket Server:**
    ```bash
    php artisan reverb:start
    ```
    (This will typically listen on `ws://0.0.0.0:8080`)

3.  **Start the Vite Development Server (Frontend):**
    ```bash
    npm run dev
    ```
    (This will serve your frontend assets, typically on `http://localhost:5173`)

### Accessing the Chat

Once all three commands are running without errors, open your web browser and navigate to: