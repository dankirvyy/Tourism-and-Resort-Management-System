<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Terms of Service - Visit Mindoro</title>
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
            <h1 class="text-4xl font-extrabold text-white">Terms of Service</h1>
            <p class="mt-4 text-xl text-orange-100">Last updated: November 16, 2025</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-md p-8 space-y-8">
            
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Acceptance of Terms</h2>
                <div class="space-y-3 text-gray-700">
                    <p>By accessing and using Visit Mindoro's booking platform, you accept and agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our services.</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Booking and Reservations</h2>
                <div class="space-y-3 text-gray-700">
                    <p><strong>2.1 Account Creation:</strong> You must create an account to make bookings. You are responsible for maintaining the confidentiality of your account credentials.</p>
                    <p><strong>2.2 Accuracy of Information:</strong> You agree to provide accurate and complete information when making reservations.</p>
                    <p><strong>2.3 Booking Confirmation:</strong> A booking is confirmed only after payment is successfully processed and you receive a confirmation email.</p>
                    <p><strong>2.4 Availability:</strong> All bookings are subject to availability. We reserve the right to refuse or cancel bookings at our discretion.</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Payment Terms</h2>
                <div class="space-y-3 text-gray-700">
                    <p><strong>3.1 Payment Methods:</strong> We accept GCash and PayPal payments.</p>
                    <p><strong>3.2 Payment Options:</strong></p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Full payment at time of booking</li>
                        <li>50% downpayment with balance due upon check-in</li>
                    </ul>
                    <p><strong>3.3 Pricing:</strong> All prices are in Philippine Pesos (PHP) unless otherwise stated. Prices are subject to change without notice.</p>
                    <p><strong>3.4 Failed Payments:</strong> If payment fails, your reservation will not be confirmed.</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Cancellation and Refund Policy</h2>
                <div class="space-y-3 text-gray-700">
                    <p><strong>4.1 Cancellation by Guest:</strong></p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Free cancellation up to 48 hours before check-in time</li>
                        <li>Cancellations within 48 hours: 50% cancellation fee applies</li>
                        <li>No-shows: No refund</li>
                    </ul>
                    <p><strong>4.2 Cancellation by Visit Mindoro:</strong> If we cancel your reservation, you will receive a full refund.</p>
                    <p><strong>4.3 Refund Processing:</strong> Refunds will be processed within 7-14 business days to the original payment method.</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Check-in and Check-out</h2>
                <div class="space-y-3 text-gray-700">
                    <p><strong>5.1 Standard Times:</strong></p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Check-in: 2:00 PM</li>
                        <li>Check-out: 12:00 PM (noon)</li>
                    </ul>
                    <p><strong>5.2 Early Check-in/Late Check-out:</strong> Subject to availability and may incur additional charges.</p>
                    <p><strong>5.3 Required Documents:</strong> Valid government-issued ID required at check-in.</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Guest Responsibilities</h2>
                <div class="space-y-3 text-gray-700">
                    <p>Guests are responsible for:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Respecting property rules and regulations</li>
                        <li>Any damage caused to the property during their stay</li>
                        <li>Maintaining reasonable noise levels</li>
                        <li>Not engaging in illegal activities</li>
                        <li>Respecting other guests and staff</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Limitation of Liability</h2>
                <div class="space-y-3 text-gray-700">
                    <p>Visit Mindoro is not liable for:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Loss or damage to personal belongings</li>
                        <li>Injuries sustained on the property (except due to our negligence)</li>
                        <li>Service interruptions due to circumstances beyond our control</li>
                        <li>Third-party services (tours, activities) booked through our platform</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Privacy</h2>
                <div class="space-y-3 text-gray-700">
                    <p>Your use of our services is also governed by our <a href="<?= site_url('privacy-policy') ?>" class="text-orange-600 hover:text-orange-700 font-semibold">Privacy Policy</a>. Please review it to understand how we collect and use your information.</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Modifications to Terms</h2>
                <div class="space-y-3 text-gray-700">
                    <p>We reserve the right to modify these Terms of Service at any time. Changes will be effective immediately upon posting. Continued use of our services after changes constitutes acceptance of the modified terms.</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Governing Law</h2>
                <div class="space-y-3 text-gray-700">
                    <p>These Terms of Service are governed by the laws of the Republic of the Philippines. Any disputes shall be resolved in the courts of Oriental Mindoro, Philippines.</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Contact Information</h2>
                <div class="space-y-3 text-gray-700">
                    <p>For questions about these Terms of Service:</p>
                    <div class="bg-gray-50 p-4 rounded-lg mt-3">
                        <p><strong>Email:</strong> support@visitmindoro.com</p>
                        <p><strong>Phone:</strong> +63 123 456 7890</p>
                        <p><strong>Address:</strong> Puerto Galera, Oriental Mindoro, Philippines</p>
                    </div>
                </div>
            </section>

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
</body>
</html>
