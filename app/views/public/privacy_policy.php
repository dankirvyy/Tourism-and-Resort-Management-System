<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Privacy Policy - Visit Mindoro</title>
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
            <h1 class="text-4xl font-extrabold text-white">Privacy Policy</h1>
            <p class="mt-4 text-xl text-orange-100">Last updated: November 16, 2025</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-md p-8 space-y-8">
            
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Information We Collect</h2>
                <div class="space-y-3 text-gray-700">
                    <p><strong>Personal Information:</strong> When you create an account or make a booking, we collect your name, email address, phone number, and payment information.</p>
                    <p><strong>Booking Information:</strong> Details about your reservations including dates, room preferences, and special requests.</p>
                    <p><strong>Usage Data:</strong> Information about how you use our website, including pages visited, time spent, and actions taken.</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">2. How We Use Your Information</h2>
                <div class="space-y-3 text-gray-700">
                    <p>We use your information to:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Process and manage your bookings</li>
                        <li>Send booking confirmations and updates</li>
                        <li>Provide customer support</li>
                        <li>Improve our services and website experience</li>
                        <li>Send promotional offers (with your consent)</li>
                        <li>Comply with legal obligations</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Data Security</h2>
                <div class="space-y-3 text-gray-700">
                    <p>We implement industry-standard security measures to protect your personal information. This includes:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Encrypted data transmission (SSL/TLS)</li>
                        <li>Secure payment processing through trusted gateways (GCash, PayPal)</li>
                        <li>Regular security audits and updates</li>
                        <li>Restricted access to personal data</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Information Sharing</h2>
                <div class="space-y-3 text-gray-700">
                    <p>We do not sell your personal information. We may share your data with:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li><strong>Service Providers:</strong> Payment processors, email services, and hosting providers</li>
                        <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
                        <li><strong>Business Partners:</strong> Tour operators and activity providers (only for booked services)</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Cookies and Tracking</h2>
                <div class="space-y-3 text-gray-700">
                    <p>We use cookies and similar technologies to enhance your browsing experience, analyze site traffic, and personalize content. You can control cookie settings through your browser preferences.</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Your Rights</h2>
                <div class="space-y-3 text-gray-700">
                    <p>You have the right to:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Access your personal information</li>
                        <li>Correct inaccurate data</li>
                        <li>Request deletion of your data</li>
                        <li>Opt-out of marketing communications</li>
                        <li>Object to data processing</li>
                    </ul>
                    <p class="mt-3">To exercise these rights, please contact us at privacy@visitmindoro.com</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Children's Privacy</h2>
                <div class="space-y-3 text-gray-700">
                    <p>Our services are not directed to individuals under 18. We do not knowingly collect personal information from children. If you believe we have collected data from a child, please contact us immediately.</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Changes to This Policy</h2>
                <div class="space-y-3 text-gray-700">
                    <p>We may update this Privacy Policy periodically. We will notify you of significant changes via email or through a notice on our website. Continued use of our services after changes constitutes acceptance of the updated policy.</p>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Contact Us</h2>
                <div class="space-y-3 text-gray-700">
                    <p>If you have questions about this Privacy Policy or how we handle your data:</p>
                    <div class="bg-gray-50 p-4 rounded-lg mt-3">
                        <p><strong>Email:</strong> privacy@visitmindoro.com</p>
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
