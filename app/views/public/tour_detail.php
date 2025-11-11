<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= html_escape($tour['name']) ?> - Visit Mindoro</title>
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
                <a href="<?= site_url('tours') ?>" class="font-semibold text-orange-600 px-3 py-2 rounded-md text-sm">Tours</a>
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
                            <img class="h-8 w-8 rounded-full mr-2" src="<?= $avatarUrl ?>" alt="User Avatar">
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
            <a href="<?= site_url('tours') ?>" class="text-sm font-medium text-gray-500 hover:text-orange-600 flex items-center mb-6">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Tours
            </a>

            <div class="lg:grid lg:grid-cols-3 lg:gap-12">
                <div class="lg:col-span-2">
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden shadow-lg">
                      <?php 
                        $imageUrl = $tour['image_filename'] 
                            ? base_url('public/uploads/images/' . $tour['image_filename']) 
                            : 'https://images.unsplash.com/photo-1523999955322-74afa39a5712?auto=format&fit=crop&w=800&q=60'; // Default placeholder
                    ?>
                     <img src="<?= $imageUrl ?>" alt="<?= html_escape($tour['name']) ?>" class="w-full h-full object-center object-cover">
                    </div>
                    
                    <?php if (!empty($tour['latitude']) && !empty($tour['longitude'])): ?>
                    <div class="mt-10">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Tour Location</h2>
                        <div class="h-96 w-full rounded-lg shadow-md overflow-hidden">
                            <div id="map"></div>
                        </div>
                    </div>
                    <?php endif; ?>
                 </div>

                 <div class="mt-10 lg:mt-0 lg:col-span-1">
                     <h1 class="text-3xl font-extrabold tracking-tight text-gray-900"><?= html_escape($tour['name']) ?></h1>

                     <div class="mt-3">
                         <h2 class="sr-only">Tour information</h2>
                         <p class="text-3xl text-gray-900">₱<?= number_format($tour['price'], 2) ?> <span class="text-xl text-gray-500 font-normal">/ person</span></p>
                     </div>

                     <div class="mt-6">
                         <h3 class="sr-only">Description</h3>
                         <div class="text-base text-gray-700 space-y-6">
                             <p><?= nl2br(html_escape($tour['description'])) ?></p>
                         </div>
                     </div>
                     
                     <div class="mt-6">
                        <p class="text-sm text-gray-500"><span class="font-medium text-gray-700">Duration:</span> <?= html_escape($tour['duration']) ?></p>
                     </div>

                     <div class="mt-10 flex">
                         <a href="<?= site_url('book/tour/' . $tour['id']) ?>" class="flex-1 bg-orange-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-orange-500">
                             Book This Tour
                         </a>
                     </div>
                 </div>
             </div>
        </div>
    </div>
    
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
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <?php if (!empty($tour['latitude']) && !empty($tour['longitude'])): ?>
    <script>
        // Get coordinates from PHP
        const tourLat = <?= json_encode($tour['latitude']); ?>;
        const tourLng = <?= json_encode($tour['longitude']); ?>;
        const tourName = <?= json_encode($tour['name']); ?>;
        
        // Initialize the map
        const map = L.map('map').setView([tourLat, tourLng], 13); // 13 is the zoom level

        // Add the OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Add a marker for the tour location
        const marker = L.marker([tourLat, tourLng]).addTo(map);
        
        // Add a popup to the marker
        marker.bindPopup(`<b>${tourName}</b>`).openPopup();
    </script>
    <?php endif; ?>

</body>
</html>