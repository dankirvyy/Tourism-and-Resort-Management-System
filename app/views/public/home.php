<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visit Mindoro: Tourism & Booking Management System</title>
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
                <a href="<?= site_url('/') ?>" class="font-semibold text-orange-600 px-3 py-2 rounded-md text-sm">Home</a>
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
            <a href="<?= site_url('/') ?>" class="text-gray-700 bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">Home</a>
            <a href="<?= site_url('rooms') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Rooms</a>
            <a href="<?= site_url('tours') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Tours</a>
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

    <div class="relative bg-gray-800 min-h-screen">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1506929562872-bb421503ef21?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Mindoro Beach">
            <div class="absolute inset-0 bg-gray-900 bg-opacity-40" aria-hidden="true"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center min-h-screen">
            <div class="py-32 md:py-40 lg:py-48">
                <h1 class="text-5xl font-extrabold tracking-tight text-white sm:text-6xl lg:text-7xl xl:text-8xl">Discover the Beauty of Mindoro</h1>
                <p class="mt-6 text-xl sm:text-2xl text-gray-100 max-w-3xl">Your gateway to unforgettable beaches, stunning waterfalls, and vibrant local culture. Book your adventure today!</p>
                
                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <a href="<?= site_url('rooms') ?>" class="inline-flex items-center justify-center rounded-md border border-transparent bg-orange-600 px-8 py-4 text-lg font-medium text-white shadow-lg hover:bg-orange-700 transition-all transform hover:scale-105">Book Your Stay</a>
                    <a href="<?= site_url('tours') ?>" class="inline-flex items-center justify-center rounded-md border-2 border-white bg-white bg-opacity-10 px-8 py-4 text-lg font-medium text-white backdrop-blur-sm backdrop-filter hover:bg-opacity-20 transition-all">Explore Tours</a>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-orange-600 text-white">
                            <i class="fas fa-sun fa-lg"></i>
                        </div>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Stunning Beaches</h3>
                    <p class="mt-2 text-base text-gray-500">Discover the pristine white sand beaches and crystal-clear waters of Mindoro.</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-orange-600 text-white">
                            <i class="fas fa-hiking fa-lg"></i>
                        </div>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Amazing Adventures</h3>
                    <p class="mt-2 text-base text-gray-500">From majestic waterfalls to thrilling dive spots, your next adventure awaits.</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-orange-600 text-white">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Easy Booking</h3>
                    <p class="mt-2 text-base text-gray-500">Secure your room or tour in just a few clicks with our simple booking system.</p>
                </div>
            </div>
        </div>
    </section>
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Our Accommodations</h2>
                <p class="mt-4 text-lg text-gray-500">Comfortable and relaxing rooms for every traveler.</p>
            </div>
            <div class="mt-10 grid gap-10 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach($featured_room_types as $room_type): ?>
                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">
                         <?php
                            $imageUrl = isset($room_type['image_filename']) && $room_type['image_filename']
                                ? base_url('public/uploads/images/' . $room_type['image_filename'])
                                : 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&q=60'; // Default placeholder
                        ?>
                        <img class="h-48 w-full object-cover" src="<?= $imageUrl ?>" alt="<?= html_escape($room_type['name']) ?>">
                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-xl font-semibold text-gray-900"><?= html_escape($room_type['name']) ?></p>
                            <p class="mt-3 text-base text-gray-500"><?= html_escape($room_type['description']) ?></p>
                        </div>
                        <div class="mt-6 flex items-center justify-between">
                            <p class="text-lg font-medium text-gray-900">₱<?= number_format($room_type['base_price'], 2) ?> / night</p>
                            <?php if ($room_type['available_rooms_count'] > 0): ?>
                                <a href="<?= site_url('book/room/' . $room_type['id']) ?>" class="text-orange-600 hover:text-orange-800 font-semibold">Book Now &rarr;</a>
                            <?php else: ?>
                                <span class="text-red-500 font-semibold">Fully Booked</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        The Jewel of the Philippines
                    </h2>
                    <p class="mt-4 text-lg text-gray-500">
                        Mindoro is an island of incredible diversity, from the stunning coral reefs of the Apo Reef Natural Park to the rugged mountains and the rich, ancestral culture of the Mangyan tribes.
                    </p>
                    <p class="mt-4 text-lg text-gray-500">
                        Whether you're here to dive in Puerto Galera, one of the world's most beautiful bays, or to relax on the secluded beaches of the west coast, Mindoro offers an unforgettable escape.
                    </p>
                </div>
                <div class="mt-10 lg:mt-0">
                    <img class="rounded-lg shadow-xl object-cover w-full h-80" 
                         src="<?= base_url('public/uploads/images/Tamaraw-Falls-1.jpg') ?>" 
                         alt="Tamaraw Falls in Mindoro">
                </div>
            </div>
        </div>
    </section>
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Explore Mindoro's Wonders</h2>
                <p class="mt-4 text-lg text-gray-500">Guided tours to the best spots on the island.</p>
            </div>
            <div class="mt-10 grid gap-10 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach($featured_tours as $tour): ?>
                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">
                         <?php
                            $imageUrl = isset($tour['image_filename']) && $tour['image_filename'] // Check if key exists AND is not empty/null
                                ? base_url('public/uploads/images/' . $tour['image_filename'])
                                : 'https://images.unsplash.com/photo-1523999955322-74afa39a5712?auto=format&fit=crop&w=800&q=60'; // Default placeholder
                        ?>
                        <img class="h-48 w-full object-cover" src="<?= $imageUrl ?>" alt="<?= html_escape($tour['name']) ?>">
                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-xl font-semibold text-gray-900"><?= html_escape($tour['name']) ?></p>
                            <p class="mt-3 text-base text-gray-500"><?= html_escape($tour['description']) ?></p>
                        </div>
                        <div class="mt-6 flex items-center justify-between">
                             <p class="text-lg font-medium text-gray-900">₱<?= number_format($tour['price'], 2) ?></p>
                             <a href="<?= site_url('tour/' . $tour['id']) ?>" class="text-orange-600 hover:text-orange-800 font-semibold">Learn More &rarr;</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">What Our Guests Say</h2>
                <p class="mt-4 text-lg text-gray-500">We're proud to have created unforgettable memories.</p>
            </div>
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg">
                    <div class="flex items-center mb-4">
                        <img class="h-12 w-12 rounded-full" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?fit=facearea&auto=format&q=80" alt="Testimonial user">
                        <div class="ml-4">
                            <p class="font-semibold text-gray-900">Alex R.</p>
                            <p class="text-sm text-gray-500">Visited June 2024</p>
                        </div>
                    </div>
                    <p class="text-base text-gray-600">"An amazing experience! The tour guides were so knowledgeable, and the room was beyond our expectations. We will be back!"</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg">
                    <div class="flex items-center mb-4">
                        <img class="h-12 w-12 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?fit=facearea&auto=format&q=80" alt="Testimonial user">
                        <div class="ml-4">
                            <p class="font-semibold text-gray-900">Maria S.</p>
                            <p class="text-sm text-gray-500">Visited May 2024</p>
                        </div>
                    </div>
                    <p class="text-base text-gray-600">"Booking was so simple. The 'My Profile' page made it easy to see all my reservations in one place. Highly recommend!"</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg">
                     <div class="flex items-center mb-4">
                        <img class="h-12 w-12 rounded-full" src="https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?fit=facearea&auto=format&q=80" alt="Testimonial user">
                        <div class="ml-4">
                            <p class="font-semibold text-gray-900">Chris J.</p>
                            <p class="text-sm text-gray-500">Visited May 2024</p>
                        </div>
                    </div>
                    <p class="text-base text-gray-600">"Mindoro is beautiful. The "Tamaraw Falls" tour was the highlight of our trip. Thank you, Visit Mindoro, for all the help."</p>
                </div>
            </div>
        </div>
    </section>
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