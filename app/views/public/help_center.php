<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Help Center - Visit Mindoro</title>
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
            <h1 class="text-4xl font-extrabold text-white">Help Center</h1>
            <p class="mt-4 text-xl text-orange-100">How can we help you today?</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <a href="<?= site_url('faq') ?>" class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 text-orange-600 mb-4">
                    <i class="fas fa-question-circle fa-lg"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">FAQs</h3>
                <p class="text-gray-600">Find answers to commonly asked questions about bookings, payments, and more.</p>
            </a>

            <a href="<?= site_url('contact') ?>" class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 text-blue-600 mb-4">
                    <i class="fas fa-envelope fa-lg"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Contact Support</h3>
                <p class="text-gray-600">Can't find what you're looking for? Send us a message and we'll help you out.</p>
            </a>

            <a href="<?= site_url('my-profile') ?>" class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100 text-green-600 mb-4">
                    <i class="fas fa-calendar-check fa-lg"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">My Bookings</h3>
                <p class="text-gray-600">View and manage your current and past reservations.</p>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Popular Help Topics</h2>
            
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-book text-orange-600 mr-3"></i>
                        How to Make a Booking
                    </h3>
                    <p class="text-gray-600 ml-8">Browse available rooms or tours, select your dates, fill in your details, and complete payment. You'll receive a confirmation email immediately.</p>
                </div>

                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-credit-card text-orange-600 mr-3"></i>
                        Payment Methods
                    </h3>
                    <p class="text-gray-600 ml-8">We accept GCash and PayPal. You can pay the full amount or make a 50% downpayment and pay the balance upon arrival.</p>
                </div>

                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-ban text-orange-600 mr-3"></i>
                        Cancellation Policy
                    </h3>
                    <p class="text-gray-600 ml-8">Free cancellation up to 48 hours before check-in. Cancellations made within 48 hours are subject to a 50% cancellation fee.</p>
                </div>

                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-clock text-orange-600 mr-3"></i>
                        Check-in & Check-out Times
                    </h3>
                    <p class="text-gray-600 ml-8">Standard check-in is at 2:00 PM and check-out is at 12:00 PM. Early check-in or late check-out may be available upon request.</p>
                </div>

                <div class="pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-edit text-orange-600 mr-3"></i>
                        Modifying Your Reservation
                    </h3>
                    <p class="text-gray-600 ml-8">To modify your booking, please contact us directly. Changes are subject to availability and may incur additional charges.</p>
                </div>
            </div>
        </div>

        <div class="mt-12 bg-orange-50 border-l-4 border-orange-600 p-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-orange-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Still need help?</h3>
                    <p class="mt-2 text-gray-700">Our support team is available Monday to Saturday, 9:00 AM - 6:00 PM. You can reach us via the <a href="<?= site_url('contact') ?>" class="text-orange-600 hover:text-orange-700 font-semibold">contact form</a> or call us at +63 123 456 7890.</p>
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
</body>
</html>
