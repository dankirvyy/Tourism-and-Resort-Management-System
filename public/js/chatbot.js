/**
 * Tourism Chatbot Widget
 * Simple, elegant chatbot for visitor assistance
 */

class TourismChatbot {
    constructor() {
        this.isOpen = false;
        this.messages = [];
        this.init();
    }

    init() {
        this.createChatbotHTML();
        this.attachEventListeners();
        this.addWelcomeMessage();
    }

    createChatbotHTML() {
        const chatbotHTML = `
            <!-- Chatbot Button -->
            <button id="chatbot-button" class="chatbot-button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </button>

            <!-- Chatbot Window -->
            <div id="chatbot-window" class="chatbot-window hidden">
                <div class="chatbot-header">
                    <div class="flex items-center">
                        <div class="chatbot-avatar">🏝️</div>
                        <div>
                            <h3 class="font-bold">Visit Mindoro Assistant</h3>
                            <p class="text-xs">How can I help you today?</p>
                        </div>
                    </div>
                    <button id="chatbot-close" class="chatbot-close-btn">✕</button>
                </div>
                
                <div id="chatbot-messages" class="chatbot-messages"></div>
                
                <div class="chatbot-quick-actions">
                    <button class="quick-action-btn" data-action="rooms">🛏️ Room Options</button>
                    <button class="quick-action-btn" data-action="tours">🗺️ Tour Packages</button>
                    <button class="quick-action-btn" data-action="contact">📞 Contact Info</button>
                </div>
                
                <div class="chatbot-input-area">
                    <input type="text" id="chatbot-input" placeholder="Type your message..." />
                    <button id="chatbot-send">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
    }

    attachEventListeners() {
        const button = document.getElementById('chatbot-button');
        const closeBtn = document.getElementById('chatbot-close');
        const sendBtn = document.getElementById('chatbot-send');
        const input = document.getElementById('chatbot-input');
        const quickActions = document.querySelectorAll('.quick-action-btn');

        button.addEventListener('click', () => this.toggleChat());
        closeBtn.addEventListener('click', () => this.toggleChat());
        sendBtn.addEventListener('click', () => this.sendMessage());
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.sendMessage();
        });

        quickActions.forEach(btn => {
            btn.addEventListener('click', () => {
                const action = btn.dataset.action;
                this.handleQuickAction(action);
            });
        });
    }

    toggleChat() {
        this.isOpen = !this.isOpen;
        const window = document.getElementById('chatbot-window');
        const button = document.getElementById('chatbot-button');
        
        if (this.isOpen) {
            window.classList.remove('hidden');
            button.style.display = 'none';
        } else {
            window.classList.add('hidden');
            button.style.display = 'flex';
        }
    }

    addWelcomeMessage() {
        const welcomeMsg = `👋 Welcome to Visit Mindoro! I'm here to help you plan your perfect getaway. 

I can assist you with:
• Room accommodations
• Tour packages
• Booking information
• Contact details

How can I help you today?`;
        
        this.addMessage(welcomeMsg, 'bot');
    }

    addMessage(text, sender = 'user') {
        const messagesContainer = document.getElementById('chatbot-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message ${sender}-message`;
        
        // Convert URLs to links
        const linkedText = this.linkify(text);
        messageDiv.innerHTML = linkedText.replace(/\n/g, '<br>');
        
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        this.messages.push({ text, sender, timestamp: new Date() });
    }

    linkify(text) {
        const urlRegex = /(https?:\/\/[^\s]+)/g;
        return text.replace(urlRegex, '<a href="$1" target="_blank" class="text-blue-600 underline">$1</a>');
    }

    async sendMessage() {
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();
        
        if (!message) return;
        
        this.addMessage(message, 'user');
        input.value = '';
        
        // Show typing indicator
        this.showTyping();
        
        try {
            const response = await this.getBotResponse(message);
            this.hideTyping();
            this.addMessage(response, 'bot');
        } catch (error) {
            this.hideTyping();
            this.addMessage('Sorry, I encountered an error. Please try again.', 'bot');
        }
    }

    showTyping() {
        const messagesContainer = document.getElementById('chatbot-messages');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chatbot-message bot-message typing-indicator';
        typingDiv.id = 'typing-indicator';
        typingDiv.innerHTML = '<span></span><span></span><span></span>';
        messagesContainer.appendChild(typingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    hideTyping() {
        const typing = document.getElementById('typing-indicator');
        if (typing) typing.remove();
    }

    async getBotResponse(message) {
        const lowerMessage = message.toLowerCase();
        
        // Room-related queries
        if (lowerMessage.includes('room') || lowerMessage.includes('accommodation') || lowerMessage.includes('stay')) {
            return `🛏️ We offer various room types including:\n\n• Standard Rooms\n• Deluxe Rooms\n• Suites\n• Family Rooms\n\nAll rooms feature modern amenities and beautiful views. Would you like to view our available rooms?\n\nView Rooms: ${window.location.origin}/rooms`;
        }
        
        // Tour-related queries
        if (lowerMessage.includes('tour') || lowerMessage.includes('activity') || lowerMessage.includes('explore')) {
            return `🗺️ Mindoro offers amazing tours including:\n\n• Beach & Island Tours\n• Mountain Trekking\n• Cultural Experiences\n• Water Sports Adventures\n\nEach tour includes a guide and refreshments. Check out our tour packages:\n\nView Tours: ${window.location.origin}/tours`;
        }
        
        // Booking queries
        if (lowerMessage.includes('book') || lowerMessage.includes('reservation') || lowerMessage.includes('reserve')) {
            return `📅 To make a booking:\n\n1. Browse our rooms or tours\n2. Select your preferred dates\n3. Fill in your details\n4. Complete payment\n\nYou must be logged in to book. Need help with a specific booking?`;
        }
        
        // Price queries
        if (lowerMessage.includes('price') || lowerMessage.includes('cost') || lowerMessage.includes('rate') || lowerMessage.includes('how much')) {
            return `💰 Our pricing varies by season and room type:\n\n• Standard Rooms: Starting at ₱2,500/night\n• Tours: Starting at ₱800/person\n\nWould you like to see specific pricing?\n\nView Rooms: ${window.location.origin}/rooms\nView Tours: ${window.location.origin}/tours`;
        }
        
        // Contact queries
        if (lowerMessage.includes('contact') || lowerMessage.includes('phone') || lowerMessage.includes('email') || lowerMessage.includes('call')) {
            return `📞 Contact Information:\n\n📧 Email: info@visitmindoro.xyz\n📱 Phone: +63 123 456 7890\n📍 Location: Mindoro, Philippines\n\nOr use our contact form:\n${window.location.origin}/contact`;
        }
        
        // Location/directions queries
        if (lowerMessage.includes('where') || lowerMessage.includes('location') || lowerMessage.includes('address') || lowerMessage.includes('direction')) {
            return `📍 We're located in the beautiful island of Mindoro, Philippines!\n\n🗺️ Getting here:\n• By air: Puerto Galera Airport\n• By ferry: From Batangas Port\n• By car: RORO ferry service available\n\nNeed specific directions to a property? Let me know which one!`;
        }
        
        // Amenities queries
        if (lowerMessage.includes('amenities') || lowerMessage.includes('facilities') || lowerMessage.includes('feature')) {
            return `✨ Our amenities include:\n\n• Swimming Pool\n• Restaurant & Bar\n• Free WiFi\n• Air Conditioning\n• Parking\n• Beach Access\n• Tour Desk\n\nWould you like to know about room-specific amenities?`;
        }
        
        // Payment queries
        if (lowerMessage.includes('payment') || lowerMessage.includes('pay') || lowerMessage.includes('gcash') || lowerMessage.includes('paypal')) {
            return `💳 We accept:\n\n• GCash\n• PayPal\n• Cash on arrival\n\nYou can choose to:\n✓ Pay full amount online\n✓ Pay 50% down payment + balance on arrival\n\nAll online payments are secure and encrypted.`;
        }
        
        // Cancellation queries
        if (lowerMessage.includes('cancel') || lowerMessage.includes('refund') || lowerMessage.includes('change')) {
            return `🔄 Cancellation Policy:\n\n• Free cancellation up to 48 hours before check-in\n• 50% refund for cancellations within 48 hours\n• No refund for no-shows\n\nTo cancel a booking, log in to your profile and select "Cancel Booking".\n\nNeed to modify an existing booking?`;
        }
        
        // Check-in/Check-out
        if (lowerMessage.includes('check in') || lowerMessage.includes('check out') || lowerMessage.includes('arrival') || lowerMessage.includes('departure')) {
            return `⏰ Check-in & Check-out:\n\n• Check-in: 2:00 PM\n• Check-out: 12:00 PM (noon)\n\nEarly check-in and late check-out may be available upon request, subject to availability.`;
        }
        
        // Weather/Best time to visit
        if (lowerMessage.includes('weather') || lowerMessage.includes('season') || lowerMessage.includes('when to visit') || lowerMessage.includes('best time')) {
            return `☀️ Best Time to Visit:\n\n• Dry Season: November - May (Best!)\n• Rainy Season: June - October\n\nPeak season is December-February with perfect beach weather. May-June offers great deals with fewer crowds!\n\nWould you like to check available dates?`;
        }
        
        // FAQ/Help
        if (lowerMessage.includes('help') || lowerMessage.includes('faq') || lowerMessage.includes('question')) {
            return `❓ I can help you with:\n\n• Room information & booking\n• Tour packages & activities\n• Pricing & payment options\n• Location & directions\n• Amenities & facilities\n• Cancellation policies\n• Contact information\n\nWhat would you like to know?`;
        }
        
        // Greetings
        if (lowerMessage.includes('hello') || lowerMessage.includes('hi') || lowerMessage.includes('hey')) {
            return `Hello! 👋 Welcome to Visit Mindoro!\n\nI'm here to help you plan your perfect island getaway. What would you like to know about?`;
        }
        
        // Thanks
        if (lowerMessage.includes('thank') || lowerMessage.includes('thanks')) {
            return `You're welcome! 😊 Is there anything else I can help you with? Feel free to ask about rooms, tours, or anything else!`;
        }
        
        // Default response
        return `I'd be happy to help! I can assist you with:\n\n• 🛏️ Room accommodations\n• 🗺️ Tour packages\n• 💰 Pricing information\n• 📞 Contact details\n• 📅 Booking process\n\nCould you please be more specific about what you'd like to know?`;
    }

    handleQuickAction(action) {
        switch(action) {
            case 'rooms':
                this.addMessage('Show me available rooms', 'user');
                this.showTyping();
                setTimeout(() => {
                    this.hideTyping();
                    this.getBotResponse('rooms').then(response => {
                        this.addMessage(response, 'bot');
                    });
                }, 800);
                break;
            case 'tours':
                this.addMessage('What tours are available?', 'user');
                this.showTyping();
                setTimeout(() => {
                    this.hideTyping();
                    this.getBotResponse('tours').then(response => {
                        this.addMessage(response, 'bot');
                    });
                }, 800);
                break;
            case 'contact':
                this.addMessage('How can I contact you?', 'user');
                this.showTyping();
                setTimeout(() => {
                    this.hideTyping();
                    this.getBotResponse('contact').then(response => {
                        this.addMessage(response, 'bot');
                    });
                }, 800);
                break;
        }
    }
}

// Initialize chatbot when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new TourismChatbot();
    });
} else {
    new TourismChatbot();
}
