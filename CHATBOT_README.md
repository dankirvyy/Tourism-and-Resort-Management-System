# Tourism Chatbot Feature

## Overview
A smart, interactive chatbot assistant for the Visit Mindoro Tourism & Resort Management System. The chatbot helps visitors get quick answers about rooms, tours, bookings, and general information.

## Features

### 🤖 Smart Responses
The chatbot can answer questions about:
- Room types and accommodations
- Tour packages and activities  
- Pricing and payment options
- Booking procedures
- Contact information
- Location and directions
- Amenities and facilities
- Check-in/check-out times
- Cancellation policies
- Best time to visit
- Weather information

### 🎨 User Interface
- Modern, elegant design with gradient accents
- Floating chat button (bottom-right corner)
- Smooth animations and transitions
- Mobile-responsive design
- Quick action buttons for common queries
- Typing indicators for better UX

### ⚡ Quick Actions
Pre-defined buttons for instant access to:
- 🛏️ Room Options
- 🗺️ Tour Packages  
- 📞 Contact Info

## Installation

The chatbot has been added to your homepage. To add it to other pages:

### Add to Any Page

1. **Include the CSS** in the `<head>` section:
```php
<link href="<?= site_url('public/css/chatbot.css') ?>" rel="stylesheet">
```

2. **Include the JavaScript** before closing `</body>` tag:
```php
<script src="<?= site_url('public/js/chatbot.js') ?>"></script>
```

### Files Created
- `/public/js/chatbot.js` - Main chatbot functionality
- `/public/css/chatbot.css` - Chatbot styling
- `/CHATBOT_README.md` - This documentation

## Customization

### Modify Responses
Edit `/public/js/chatbot.js` and find the `getBotResponse()` method. You can:
- Add new question patterns
- Modify existing responses
- Change URLs and links
- Update pricing information

Example:
```javascript
if (lowerMessage.includes('your_keyword')) {
    return `Your custom response here`;
}
```

### Styling
Modify `/public/css/chatbot.css` to change:
- Colors and gradients
- Button sizes and positions
- Font styles
- Animation speeds

### Quick Actions
Edit the `handleQuickAction()` method in `/public/js/chatbot.js` to add new quick action buttons.

## Usage Examples

### For Visitors
1. Click the chat button (bottom-right)
2. Type a question or use quick action buttons
3. Get instant answers with helpful links
4. Continue conversation naturally

### Common Queries
- "What rooms do you have?"
- "How much are the tours?"
- "How do I book a room?"
- "What's your contact number?"
- "When is the best time to visit?"
- "What payment methods do you accept?"

## Technical Details

### Technologies Used
- Vanilla JavaScript (ES6+)
- CSS3 with animations
- No external libraries required
- Fully client-side processing

### Browser Support
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers

### Performance
- Lightweight (~15KB total)
- Instant responses
- No server calls for basic queries
- Smooth animations (60fps)

## Future Enhancements

Potential additions:
- [ ] Integration with AI (OpenAI GPT, etc.)
- [ ] Save conversation history
- [ ] Multi-language support
- [ ] Voice input/output
- [ ] Live agent handoff
- [ ] Analytics tracking
- [ ] Personalized recommendations based on browsing

## Troubleshooting

### Chatbot doesn't appear
1. Check that CSS and JS files are loaded
2. Verify file paths are correct
3. Check browser console for errors

### Responses not working
1. Check `getBotResponse()` method syntax
2. Ensure keywords match user queries
3. Test in browser console

### Styling issues
1. Verify CSS file is loaded
2. Check for conflicting styles
3. Clear browser cache

## Support

For issues or questions:
- Email: dankirvymanongsong@gmail.com
- Check browser console for errors
- Review code comments in source files

---

**Created for Visit Mindoro Tourism & Resort Management System**
