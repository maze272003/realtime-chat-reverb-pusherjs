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
    chatContainer.prepend(messageElement);
}

// Listen for messages on the 'public-chat' channel (for messages from others)
window.Echo.channel('public-chat')
    .listen('.message-sent', (e) => {
        console.log('Received message from others:', e);
        // Only add if it's NOT from the current user (to prevent duplicates if toOthers fails for some reason)
        // Or simply trust toOthers() and just add whatever comes here.
        // For simplicity, we just add it, relying on toOthers() to filter the sender's own message.
        addMessageToChat(e.message, e.sender);
    });

// Handle sending messages
messageForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const message = messageInput.value.trim();
    const sender = senderInput.value.trim() || 'Anonymous'; // Get sender, default to Anonymous

    if (message) {
        // *** DITO ANG PAGBABAGO: Optimistically add the message to the chat for the current sender ***
        addMessageToChat(message, sender + ' (You)'); // Idagdag ang "(You)" para malaman na sarili niyang message

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
                // Optionally: If sending failed, you might want to remove the optimistically added message
                // or change its style to indicate failure.
            }
        } catch (error) {
            console.error('Error sending message:', error);
            // Handle network errors
        } finally {
            messageInput.value = ''; // Clear input after sending
        }
    }
});

console.log('Laravel Echo initialized and listening for public-chat events.');