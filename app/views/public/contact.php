<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Visit Mindoro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        /* This ensures the map tiles load correctly */
        .leaflet-container {
            height: 100%;
            width: 100%;
        }
    </style>
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
                <a href="<?= site_url('contact') ?>" class="font-semibold text-orange-600 px-3 py-2 rounded-md text-sm">Contact</a>
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
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path></svg>
                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    </nav>

    <div class="md:hidden hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="<?= site_url('/') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Home</a>
            <a href="<?= site_url('rooms') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Rooms</a>
            <a href="<?= site_url('tours') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Tours</a>
            <a href="<?= site_url('contact') ?>" class="text-gray-700 bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">Contact</a>
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

    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Contact Us</h2>
                <p class="mt-4 text-lg text-gray-500">Have questions? We'd love to hear from you.</p>
            </div>
            
            <div class="mt-12 lg:grid lg:grid-cols-2 lg:gap-16">
                <div class="flex flex-col">
                    <h3 class="text-2xl font-bold text-gray-900">Our Office</h3>
                    <p class="mt-3 text-base text-gray-500">Find us at our main office in Calapan City.</p>
                    <div class="mt-6 space-y-4 text-base text-gray-700">
                        <p class="flex items-center">
                            <i class="fas fa-map-marker-alt text-orange-600 w-6"></i>
                            <span class="ml-3">J.P. Rizal St, Calapan City, Oriental Mindoro</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-phone text-orange-600 w-6"></i>
                            <span class="ml-3">(+63) 123-456-7890</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-envelope text-orange-600 w-6"></i>
                            <span class="ml-3">info@visitmindoro.com</span>
                        </p>
                    </div>
                    
                    <div class="mt-6 h-96 w-full rounded-lg shadow-md overflow-hidden">
                        <div id="map"></div>
                    </div>
                </div>

                <div class="mt-12 lg:mt-0">
                    <h3 class="text-2xl font-bold text-gray-900">Send Us a Message</h3>
                    <form action="#" method="POST" class="mt-6 space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                            <div class="mt-1">
                                <textarea id="message" name="message" rows="4" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6"></textarea>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
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
            </div>
        </div>
    </section>
    <footer class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-base text-gray-400">&copy; <?= date('Y') ?> Visit Mindoro. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Mobile menu script
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
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Set map coordinates for Calapan City, Oriental Mindoro
        const mapCoords = [13.4146, 121.1812];
        
        // Initialize the map and set its view
        const map = L.map('map').setView(mapCoords, 14); 

        // Add the OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Add a marker for the office location
        const marker = L.marker(mapCoords).addTo(map);
        
        // Add a popup to the marker
        marker.bindPopup("<b>Visit Mindoro Office</b><br>Calapan City").openPopup();
    </script>
    
</body>
</html>