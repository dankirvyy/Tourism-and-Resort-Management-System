# Chatbot Deployment Instructions

## Files to Upload to cPanel

You need to upload these 3 new files to your server:

### 1. JavaScript File
**Local:** `public/js/chatbot.js`
**Server:** `public_html/public/js/chatbot.js`

### 2. CSS File
**Local:** `public/css/chatbot.css`  
**Server:** `public_html/public/css/chatbot.css`

### 3. Updated Home Page
**Local:** `app/views/public/home.php`
**Server:** `public_html/app/views/public/home.php`

## Upload Steps

### Method 1: Using cPanel File Manager

1. Log into cPanel
2. Open File Manager
3. Navigate to `public_html/public/js/`
4. Click "Upload" and upload `chatbot.js`
5. Navigate to `public_html/public/css/`
6. Click "Upload" and upload `chatbot.css`
7. Navigate to `public_html/app/views/public/`
8. Click on `home.php` → Edit
9. Replace the content with your updated `home.php`

### Method 2: Using FTP

1. Connect to your FTP
2. Upload `chatbot.js` to `/public_html/public/js/`
3. Upload `chatbot.css` to `/public_html/public/css/`
4. Upload updated `home.php` to `/public_html/app/views/public/`

## After Upload

1. Visit your website: http://visitmindoro.xyz
2. You should see a purple chat button in the bottom-right corner
3. Click it to open the chatbot
4. Try asking questions like:
   - "What rooms do you have?"
   - "How much are the tours?"
   - "How do I book?"

## Adding to Other Pages

To add the chatbot to other pages (rooms, tours, etc.):

1. Open the page file in cPanel File Manager
2. Add this in the `<head>` section:
```php
<link href="<?= site_url('public/css/chatbot.css') ?>" rel="stylesheet">
```

3. Add this before closing `</body>` tag:
```php
<script src="<?= site_url('public/js/chatbot.js') ?>"></script>
```

## Pages to Add Chatbot To

Recommended pages:
- ✅ home.php (already done)
- rooms.php
- tours.php
- book.php
- book_tour.php
- contact.php
- my_profile.php

## Testing Checklist

- [ ] Chat button appears on homepage
- [ ] Button opens chat window when clicked
- [ ] Quick action buttons work
- [ ] Can type and send messages
- [ ] Bot responds to questions
- [ ] Links in responses work
- [ ] Close button works
- [ ] Works on mobile devices

## Troubleshooting

**If chatbot doesn't appear:**
1. Check browser console for errors (F12)
2. Verify files are uploaded to correct paths
3. Clear browser cache (Ctrl+F5)
4. Check file permissions (should be 644)

**If styles look wrong:**
1. Verify `chatbot.css` is loaded
2. Check for conflicting CSS
3. Clear browser cache

## Need Help?

Check the CHATBOT_README.md file for full documentation and customization options.
