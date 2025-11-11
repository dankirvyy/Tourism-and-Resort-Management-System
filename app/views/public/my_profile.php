<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Visit Mindoro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
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

                    <?php if ($this->session->has_userdata('user_id')):
                        // Fetch avatar details if user is logged in
                        $guest_info = $this->Guest_model->find($this->session->userdata('user_id')); // Fetch guest info
                        $avatarUrl = ($guest_info && $guest_info['avatar_filename'])
                            ? base_url('public/uploads/avatars/' . $guest_info['avatar_filename'])
                            : 'https://via.placeholder.com/32/cccccc/888888?text=U'; // Placeholder if no avatar
                    ?>
                        <div class="ml-4 flex items-center space-x-4">
                             <a href="<?= site_url('my-profile') ?>" class="flex items-center text-sm font-semibold text-orange-600"> <img class="h-8 w-8 rounded-full mr-2 object-cover" src="<?= $avatarUrl ?>" alt="User Avatar">
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

                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" x-description="Heroicon name: outline/menu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path></svg>
                        <svg class="hidden h-6 w-6" x-description="Heroicon name: outline/x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </nav>

        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="<?= site_url('/') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="<?= site_url('rooms') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Rooms</a>
                <a href="<?= site_url('tours') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Tours</a>
                <a href="<?= site_url('contact') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Contact</a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="px-2 space-y-1">
                     <?php if ($this->session->has_userdata('user_id')): ?>
                        <a href="<?= site_url('my-profile') ?>" class="text-gray-700 bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">My Profile</a> <a href="<?= site_url('logout') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Logout</a>
                    <?php else: ?>
                        <a href="<?= site_url('login') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Login</a>
                        <a href="<?= site_url('signup') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">My Profile</h1>

         <?php if ($this->session->flashdata('profile_success')): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= $this->session->flashdata('profile_success'); ?></span>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('profile_error')): ?>
             <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= $this->session->flashdata('profile_error'); ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow sm:rounded-lg overflow-hidden mb-8">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Personal Information</h2>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Your contact details.</p>
                </div>
                <a href="<?= site_url('edit-profile') ?>" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50">Edit Profile</a>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="flex items-center space-x-4">
                    <?php
                        $avatarUrl = $guest['avatar_filename']
                            ? base_url('public/uploads/avatars/' . $guest['avatar_filename'])
                            : 'https://via.placeholder.com/100/cccccc/888888?text=No+Avatar'; // Placeholder
                    ?>
                    <img class="h-24 w-24 rounded-full object-cover" src="<?= $avatarUrl ?>" alt="Profile Picture">

                    <dl class="flex-1 sm:divide-y sm:divide-gray-200">
                        <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Full name</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= html_escape($guest['first_name'] . ' ' . $guest['last_name']) ?></dd>
                        </div>
                        <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Email address</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= html_escape($guest['email']) ?></dd>
                        </div>
                        <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Phone number</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= html_escape($guest['phone_number']) ?></dd>
                        </div>
                         <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                             <dt class="text-sm font-medium text-gray-500">Avatar</dt>
                             <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                 <a href="<?= site_url('change-avatar') ?>" class="text-orange-600 hover:text-orange-800">Change Avatar</a>
                             </dd>
                         </div>
                    </dl>
                </div>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-6">My Reservations</h2>

        <div class="bg-white shadow sm:rounded-lg overflow-hidden mb-8">
             <div class="px-4 py-5 sm:px-6"><h3 class="text-lg font-medium text-gray-900">Room Bookings</h3></div>
             <div class="border-t border-gray-200">
                <?php if (!empty($room_bookings)): ?>
                    <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in / Check-out</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th></tr></thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($room_bookings as $booking): ?>
                                <tr><td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= html_escape($booking['room_number'] . ' (' . $booking['room_type_name'] . ')'); ?></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= html_escape($booking['check_in_date']); ?> to <?= html_escape($booking['check_out_date']); ?></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= ucfirst(html_escape($booking['status'])); ?></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱<?= number_format($booking['total_price'], 2); ?></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500 py-6 px-6">You have no room bookings.</p>
                <?php endif; ?>
            </div>
        </div>

         <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6"><h3 class="text-lg font-medium text-gray-900">Tour Bookings</h3></div>
            <div class="border-t border-gray-200">
                <?php if (!empty($tour_bookings)): ?>
                    <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                         <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tour</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guests</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th></tr></thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($tour_bookings as $booking): ?>
                                <tr><td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= html_escape($booking['tour_name']); ?></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= html_escape($booking['booking_date']); ?></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= html_escape($booking['number_of_pax']); ?></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= ucfirst(html_escape($booking['status'])); ?></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱<?= number_format($booking['total_price'], 2); ?></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500 py-6 px-6">You have no tour bookings.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <section class="bg-gray-800 py-12 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Visit Mindoro</h3>
                <p class="text-sm">
                    Your trusted partner for unforgettable adventures and serene stays in the beautiful island of Mindoro.
                </p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Navigation</h3>
                <ul class="space-y-2">
                    <li><a href="<?= site_url('/') ?>" class="text-sm hover:text-orange-400">Home</a></li>
                    <li><a href="<?= site_url('rooms') ?>" class="text-sm hover:text-orange-400">Rooms</a></li>
                    <li><a href="<?= site_url('tours') ?>" class="text-sm hover:text-orange-400">Tours</a></li>
                    <li><a href="<?= site_url('contact') ?>" class="text-sm hover:text-orange-400">Contact Us</a></li>
                    <?php if ($this->session->has_userdata('user_id')): ?>
                        <li><a href="<?= site_url('my-profile') ?>" class="text-sm hover:text-orange-400">My Profile</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Support</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-sm hover:text-orange-400">Help Center</a></li>
                    <li><a href="#" class="text-sm hover:text-orange-400">FAQs</a></li>
                    <li><a href="#" class="text-sm hover:text-orange-400">Privacy Policy</a></li>
                    <li><a href="#" class="text-sm hover:text-orange-400">Terms of Service</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Connect</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 hover:text-orange-400"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="text-gray-300 hover:text-orange-400"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-gray-300 hover:text-orange-400"><i class="fab fa-instagram fa-lg"></i></a>
                </div>
                <p class="mt-4 text-sm">
                    Subscribe to our newsletter for updates!
                </p>
                <form class="mt-2">
                    <input type="email" placeholder="Your email" class="w-full px-3 py-2 rounded-md bg-gray-700 border border-gray-600 text-white text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <button type="submit" class="mt-2 w-full bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium py-2 rounded-md">Subscribe</button>
                </form>
            </div>
        </div>
    </section>
    <footer class="bg-white mt-12"><div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8"><p class="text-center text-base text-gray-400">&copy; <?= date('Y') ?> Visit Mindoro. All rights reserved.</p></div></footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const hamburgerIcon = mobileMenuButton.querySelector('svg.block');
            const closeIcon = mobileMenuButton.querySelector('svg.hidden');

            mobileMenuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
                hamburgerIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
                const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true' || false;
                 mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
            });
        });
    </script>
</body>
</html>