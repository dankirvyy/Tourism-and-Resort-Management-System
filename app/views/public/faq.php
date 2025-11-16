<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAQs - Visit Mindoro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <a href="<?= site_url('/') ?>" class="flex items-center gap-3 text-3xl font-bold text-orange-600">
                        <img class="h-10 w-auto" src="<?= site_url('public/uploads/images/logo.png') ?>" alt="Visit Mindoro Logo">
                        <span>Visit Mindoro</span>
                    </a>
                </div>
                <div class="hidden md:flex md:items-center md:space-x-4">
                    <a href="<?= site_url('/') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                    <a href="<?= site_url('rooms') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Rooms</a>
                    <a href="<?= site_url('tours') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Tours</a>
                    <a href="<?= site_url('contact') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                    <div class="border-l border-gray-300 h-6"></div>
                    <?php if ($this->session->has_userdata('user_id')): ?>
                        <div class="ml-4 flex items-center space-x-4">
                            <a href="<?= site_url('my-profile') ?>" class="flex items-center text-sm font-medium text-gray-700 hover:text-orange-600">
                                Welcome, <?= $this->session->userdata('user_name'); ?>!
                            </a>
                            <a href="<?= site_url('logout') ?>" class="text-gray-500 hover:text-gray-900 text-sm font-medium">Logout</a>
                        </div>
                    <?php else: ?>
                        <div class="ml-4 flex items-center">
                            <a href="<?= site_url('login') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                            <a href="<?= site_url('signup') ?>" class="ml-2 inline-flex items-center justify-center rounded-md border border-transparent bg-orange-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-orange-700">Sign Up</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <div class="bg-gradient-to-r from-orange-600 to-orange-700 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold text-white">Frequently Asked Questions</h1>
            <p class="mt-4 text-xl text-orange-100">Find answers to common questions</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="space-y-4">
            <!-- Booking Questions -->
            <div class="bg-white rounded-lg shadow-md">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">How do I make a booking?</span>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                </button>
                <div class="faq-answer hidden px-6 pb-4 text-gray-600">
                    <p>To make a booking, browse our available rooms or tours, select your preferred dates, fill in your guest information, and proceed to payment. You can pay via GCash or PayPal. Once payment is confirmed, you'll receive a confirmation email with your booking details.</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">Can I modify or cancel my booking?</span>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                </button>
                <div class="faq-answer hidden px-6 pb-4 text-gray-600">
                    <p>Yes! You can cancel your booking up to 48 hours before check-in for a full refund. Cancellations within 48 hours are subject to a 50% fee. To modify your booking dates, please contact us directly through the contact form or call our support team.</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">Do I need to create an account to book?</span>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                </button>
                <div class="faq-answer hidden px-6 pb-4 text-gray-600">
                    <p>Yes, you need to create a free account to make bookings. This allows you to manage your reservations, view booking history, and receive updates about your trips all in one place.</p>
                </div>
            </div>

            <!-- Payment Questions -->
            <div class="bg-white rounded-lg shadow-md">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">What payment methods do you accept?</span>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                </button>
                <div class="faq-answer hidden px-6 pb-4 text-gray-600">
                    <p>We accept GCash and PayPal payments. You can choose to pay the full amount upfront or make a 50% downpayment and pay the remaining balance upon arrival at the property.</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">Is my payment information secure?</span>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                </button>
                <div class="faq-answer hidden px-6 pb-4 text-gray-600">
                    <p>Absolutely! We use secure payment gateways (GCash and PayPal) that encrypt your payment information. We never store your credit card or payment details on our servers.</p>
                </div>
            </div>

            <!-- Check-in/out Questions -->
            <div class="bg-white rounded-lg shadow-md">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">What are the check-in and check-out times?</span>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                </button>
                <div class="faq-answer hidden px-6 pb-4 text-gray-600">
                    <p>Standard check-in time is 2:00 PM and check-out time is 12:00 PM (noon). You can select different times during booking if needed. Early check-in or late check-out may be available upon request, subject to availability.</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">What do I need to bring for check-in?</span>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                </button>
                <div class="faq-answer hidden px-6 pb-4 text-gray-600">
                    <p>Please bring a valid government-issued ID and your booking confirmation (email or printed). If you made a downpayment, be prepared to pay the remaining balance at check-in.</p>
                </div>
            </div>

            <!-- Room/Tour Questions -->
            <div class="bg-white rounded-lg shadow-md">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">Are meals included in the room rate?</span>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                </button>
                <div class="faq-answer hidden px-6 pb-4 text-gray-600">
                    <p>It depends on the room type. Some packages include breakfast, while others are room-only. Check the room description for details about included amenities and meals.</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">Can I book tours separately?</span>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                </button>
                <div class="faq-answer hidden px-6 pb-4 text-gray-600">
                    <p>Yes! You can book tours independently without booking a room. Visit our Tours page to see available activities and make reservations.</p>
                </div>
            </div>

            <!-- General Questions -->
            <div class="bg-white rounded-lg shadow-md">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900">How do I contact customer support?</span>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                </button>
                <div class="faq-answer hidden px-6 pb-4 text-gray-600">
                    <p>You can reach us through our <a href="<?= site_url('contact') ?>" class="text-orange-600 hover:text-orange-700 font-semibold">contact form</a>, call us at +63 123 456 7890, or visit our Help Center. We're available Monday to Saturday, 9:00 AM - 6:00 PM.</p>
                </div>
            </div>
        </div>

        <div class="mt-12 bg-orange-50 border-l-4 border-orange-600 p-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-question-circle text-orange-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Didn't find your answer?</h3>
                    <p class="mt-2 text-gray-700">Feel free to <a href="<?= site_url('contact') ?>" class="text-orange-600 hover:text-orange-700 font-semibold">contact us</a> and our support team will be happy to help!</p>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-800 py-12 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Visit Mindoro</h3>
                <p class="text-sm">Your trusted partner for unforgettable adventures and serene stays in the beautiful island of Mindoro.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Navigation</h3>
                <ul class="space-y-2">
                    <li><a href="<?= site_url('/') ?>" class="text-sm hover:text-orange-400">Home</a></li>
                    <li><a href="<?= site_url('rooms') ?>" class="text-sm hover:text-orange-400">Rooms</a></li>
                    <li><a href="<?= site_url('tours') ?>" class="text-sm hover:text-orange-400">Tours</a></li>
                    <li><a href="<?= site_url('contact') ?>" class="text-sm hover:text-orange-400">Contact Us</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Support</h3>
                <ul class="space-y-2">
                    <li><a href="<?= site_url('help-center') ?>" class="text-sm hover:text-orange-400">Help Center</a></li>
                    <li><a href="<?= site_url('faq') ?>" class="text-sm hover:text-orange-400">FAQs</a></li>
                    <li><a href="<?= site_url('privacy-policy') ?>" class="text-sm hover:text-orange-400">Privacy Policy</a></li>
                    <li><a href="<?= site_url('terms-of-service') ?>" class="text-sm hover:text-orange-400">Terms of Service</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Connect</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 hover:text-orange-400"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="text-gray-300 hover:text-orange-400"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-gray-300 hover:text-orange-400"><i class="fab fa-instagram fa-lg"></i></a>
                </div>
            </div>
        </div>
    </section>
    <footer class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-base text-gray-400">&copy; <?= date('Y') ?> Visit Mindoro. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function toggleFaq(button) {
            const answer = button.nextElementSibling;
            const icon = button.querySelector('i');
            
            answer.classList.toggle('hidden');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        }
    </script>
</body>
</html>
