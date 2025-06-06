import './bootstrap';

const chatContainer = document.getElementById('chat-container');
const messageForm = document.getElementById('message-form');
const senderInput = document.getElementById('sender-input');
const messageInput = document.getElementById('message-input');

// Function to add a message to the chat container
function addMessageToChat(message, sender) {
    const messageElement = document.createElement('div');
    messageElement.classList.add('message');
    messageElement.innerHTML = `<strong>${sender}:</strong> <span>${message}</span>`;
    // Changed from prepend to append
    chatContainer.append(messageElement);
    // Scroll to bottom after adding new message
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

// Listen for messages on the 'public-chat' channel (for messages from others)
window.Echo.channel('public-chat')
    .listen('.message-sent', (e) => {
        console.log('Received message from others:', e);
        addMessageToChat(e.message, e.sender);
    });

// Handle sending messages
messageForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const message = messageInput.value.trim();
    const sender = senderInput.value.trim() || 'Anonymous';

    if (message) {
        // Optimistically add the message for the current sender
        addMessageToChat(message, sender + ' (You)');

        try {
            const response = await fetch('/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message, sender: sender })
            });

            if (!response.ok) {
                console.error('Failed to send message:', response.statusText);
                // Optionally handle UI rollback for failed sends
            }
        } catch (error) {
            console.error('Error sending message:', error);
        } finally {
            messageInput.value = '';
        }
    }
});

// Scroll to the bottom of the chat container when the page loads
// This ensures that new messages are always visible at the bottom.
chatContainer.scrollTop = chatContainer.scrollHeight;

console.log('Laravel Echo initialized and listening for public-chat events.');
