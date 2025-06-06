import './bootstrap';

const chatContainer = document.getElementById('chat-container');
const messageForm = document.getElementById('message-form');
const senderInput = document.getElementById('sender-input');
const messageInput = document.getElementById('message-input');
const typingIndicator = document.getElementById('typing-indicator');

// Load initial messages when page loads
async function loadInitialMessages() {
    try {
        const response = await fetch('/get-messages');
        if (response.ok) {
            const messages = await response.json();
            messages.reverse().forEach(msg => {
                const isCurrentUser = msg.sender === (senderInput.value.trim() || 'Anonymous');
                addMessageToChat(msg.message, isCurrentUser ? 'You' : msg.sender, isCurrentUser);
            });
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    } catch (error) {
        console.error('Error loading initial messages:', error);
    }
}

// Function to add a message to the chat container
function addMessageToChat(message, sender, isCurrentUser = false) {
    const messageElement = document.createElement('div');
    messageElement.classList.add('message');
    messageElement.classList.add(isCurrentUser ? 'message-user' : 'message-other');

    const time = getCurrentTime();

    messageElement.innerHTML = `
        <div class="message-sender">${sender}</div>
        <div class="message-text">${message}</div>
        <div class="message-time">${time}</div>
    `;

    chatContainer.append(messageElement);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

// Get current time in HH:MM format
function getCurrentTime() {
    const now = new Date();
    return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

// Show typing indicator
function showTypingIndicator(sender) {
    const currentSender = senderInput.value.trim() || 'Anonymous';
    if (sender !== currentSender) {
        typingIndicator.textContent = `${sender} is typing...`;
        typingIndicator.classList.add('active');

        clearTimeout(window.typingTimeout);
        window.typingTimeout = setTimeout(() => {
            typingIndicator.classList.remove('active');
        }, 3000);
    }
}

// Initialize Echo listeners
function initializeEchoListeners() {
    window.Echo.channel('public-chat')
        .listen('.message-sent', (e) => {
            const currentSender = senderInput.value.trim() || 'Anonymous';
            if (e.sender !== currentSender) {
                addMessageToChat(e.message, e.sender, false);
            }
        })
        .listenForWhisper('typing', (e) => {
            showTypingIndicator(e.sender);
        });
}

// Handle typing events
messageInput.addEventListener('input', () => {
    const sender = senderInput.value.trim() || 'Anonymous';
    window.Echo.private('public-chat')
        .whisper('typing', {
            sender: sender
        });
});

// Handle sending messages
messageForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const message = messageInput.value.trim();
    const sender = senderInput.value.trim() || 'Anonymous';

    if (message) {
        // Optimistically add the message for the current sender
        addMessageToChat(message, 'You', true);

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
            }
        } catch (error) {
            console.error('Error sending message:', error);
        } finally {
            messageInput.value = '';
            typingIndicator.classList.remove('active');
        }
    }
});

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    loadInitialMessages();
    initializeEchoListeners();
    messageInput.focus();
});
