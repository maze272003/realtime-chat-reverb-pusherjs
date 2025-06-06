import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher', // This refers to the Pusher *protocol*, not Pusher.com service
    key: import.meta.env.VITE_REVERB_APP_KEY, // <--- IMPORTANT: Using VITE_REVERB_APP_KEY
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    cluster: 'mt1', // Or import.meta.env.VITE_REVERB_CLUSTER if you defined it
});
