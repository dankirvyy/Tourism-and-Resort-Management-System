<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Rooms - Visit Mindoro</title>
    <link href="<?= site_url('public/css/output.css') ?>" rel="stylesheet">
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
                <a href="<?= site_url('rooms') ?>" class="font-semibold text-orange-600 px-3 py-2 rounded-md text-sm">Rooms</a> <a href="<?= site_url('tours') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Tours</a>
                <a href="<?= site_url('contact') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                <div class="border-l border-gray-300 h-6"></div>

                <?php if ($this->session->has_userdata('user_id')):
                    $guest_info = $this->Guest_model->find($this->session->userdata('user_id')); 
                    $avatarUrl = ($guest_info && $guest_info['avatar_filename'])
                        ? base_url('public/uploads/avatars/' . $guest_info['avatar_filename'])
                        : 'https://via.placeholder.com/32/cccccc/888888?text=U'; 
                ?>
                    <div class="ml-4 flex items-center space-x-4">
                         <a href="<?= site_url('my-profile') ?>" class="flex items-center text-sm font-medium text-gray-700 hover:text-orange-600">
                            <img class="h-8 w-8 rounded-full mr-2 object-cover" src="<?= $avatarUrl ?>" alt="User Avatar">
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
            <a href="<?= site_url('rooms') ?>" class="text-gray-700 bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">Rooms</a> <a href="<?= site_url('tours') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Tours</a>
            <a href="<?= site_url('contact') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Contact</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-2 space-y-1">
                 <?php if ($this->session->has_userdata('user_id')): ?>
                    <a href="<?= site_url('my-profile') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">My Profile</a>
                    <a href="<?= site_url('logout') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Logout</a>
                <?php else: ?>
                    <a href="<?= site_url('login') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Login</a>
                    <a href="<?= site_url('signup') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Our Accommodations</h2>
                <p class="mt-4 text-lg text-gray-500">Find the perfect room for your stay. We offer a variety of options to suit every need and budget.</p>
            </div>
            
            <div class="mt-8 max-w-2xl mx-auto">
                <form action="<?= site_url('rooms') ?>" method="GET" class="sm:flex sm:items-center sm:gap-4">
                    
                    <input type="search" name="search"
                           value="<?= html_escape($search_term ?? '') ?>"
                           class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6"
                           placeholder="Search for room types...">
                    
                    <select name="sort" class="mt-2 sm:mt-0 block w-full sm:w-auto rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
                        <option value="default" <?= !isset($sort_term) ? 'selected' : '' ?>>Sort by... (Default)</option>
                        <option value="price_asc" <?= ($sort_term ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_desc" <?= ($sort_term ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="name_asc" <?= ($sort_term ?? '') === 'name_asc' ? 'selected' : '' ?>>Name: A to Z</option>
                    </select>
                    
                    <button type="submit" class="mt-2 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-md bg-orange-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500">
                        Search
                    </button>
                </form>
            </div>
            <div class="mt-12 grid gap-10 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <?php if (empty($room_types)): ?>
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center text-gray-500">
                        <p class="text-xl">No rooms found<?php if (!empty($search_term)): ?> matching "<?= html_escape($search_term) ?>"<?php endif; ?>.</p>
                        <a href="<?= site_url('rooms') ?>" class="mt-2 text-orange-600 hover:text-orange-500">Clear search</a>
                    </div>
                <?php else: ?>
                    <?php foreach($room_types as $room_type): ?>
                    <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                        <div class="flex-shrink-0">
                             <?php
                                $imageUrl = $room_type['image_filename']
                                    ? base_url('public/uploads/images/' . $room_type['image_filename'])
                                    : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=60'; // Default placeholder
                             ?>
                            <img class="h-56 w-full object-cover" src="<?= $imageUrl ?>" alt="<?= html_escape($room_type['name']) ?>">
                        </div>
                        <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900"><?= html_escape($room_type['name']) ?></h3>
                                <p class="mt-3 text-base text-gray-500"><?= html_escape($room_type['description']) ?></p>
                            </div>
                            <div class="mt-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-lg font-medium text-gray-900">₱<?= number_format($room_type['base_price'], 2) ?> <span class="text-sm text-gray-500">/ night</span></p>
                                    <p class="text-sm text-gray-500">Sleeps <?= html_escape($room_type['capacity']) ?></p>
                                </div>
                                <?php if ($room_type['available_rooms_count'] > 0): ?>
                                    <a href="<?= site_url('book/room/' . $room_type['id']) ?>" class="mt-4 block w-full text-center rounded-md border border-transparent bg-orange-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-orange-700">Book Now</a>
                                <?php else: ?>
                                    <span class="mt-4 block w-full text-center rounded-md border border-gray-300 bg-gray-200 px-4 py-2 text-base font-medium text-gray-500 cursor-not-allowed">Fully Booked</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
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
    <footer class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-base text-gray-400">&copy; <?= date('Y') ?> Visit Mindoro. All rights reserved.</p>
        </div>
    </footer>

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
            });
        });
    </script>
</body>
</html>