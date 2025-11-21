<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Rooms - Visit Mindoro</title>
    <link href="<?= site_url('public/css/output.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
                
                <button id="viewMapBtn" class="mt-4 inline-flex items-center gap-2 rounded-md bg-orange-600 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-orange-700">
                    <i class="fas fa-map-marked-alt"></i>
                    View Locations on Map
                </button>
            </div>
            
            <div class="mt-8 max-w-2xl mx-auto">
                <form action="<?= site_url('rooms') ?>" method="GET" class="sm:flex sm:items-center sm:gap-4">
                    
                    <input type="search" name="search"
                           value="<?= html_escape($search_term ?? '') ?>"
                           class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6"
                           placeholder="Search for room types...">
                    
                    <select name="location" class="mt-2 sm:mt-0 block w-full sm:w-auto rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
                        <option value="">All Locations</option>
                        <?php if (!empty($locations)): ?>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?= html_escape($loc['location']) ?>" <?= ($location_term ?? '') === $loc['location'] ? 'selected' : '' ?>>
                                    <?= html_escape($loc['location']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    
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
                                <?php if (!empty($room_type['location'])): ?>
                                    <p class="mt-1 text-sm text-orange-600 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <?= html_escape($room_type['location']) ?>
                                    </p>
                                <?php endif; ?>
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
    
    <!-- Map Modal -->
    <div id="mapModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-900">Room Locations in Mindoro</h3>
                <button id="closeMapBtn" class="text-gray-400 hover:text-gray-600 text-3xl font-bold">&times;</button>
            </div>
            <div id="map" class="w-full h-96 rounded-lg shadow-inner"></div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                <?php foreach($room_types as $room_type): ?>
                    <?php if (!empty($room_type['latitude']) && !empty($room_type['longitude'])): ?>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer room-location-item" 
                             data-lat="<?= $room_type['latitude'] ?>" 
                             data-lng="<?= $room_type['longitude'] ?>"
                             data-name="<?= html_escape($room_type['name']) ?>">
                            <i class="fas fa-map-marker-alt text-orange-600 text-xl"></i>
                            <div>
                                <p class="font-semibold text-gray-900"><?= html_escape($room_type['name']) ?></p>
                                <p class="text-sm text-gray-600"><?= html_escape($room_type['location']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
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
            
            // Map functionality
            const mapModal = document.getElementById('mapModal');
            const viewMapBtn = document.getElementById('viewMapBtn');
            const closeMapBtn = document.getElementById('closeMapBtn');
            let map = null;
            let markers = [];
            
            // Room locations data
            const roomLocations = [
                <?php foreach($room_types as $room_type): ?>
                    <?php if (!empty($room_type['latitude']) && !empty($room_type['longitude'])): ?>
                        {
                            name: <?= json_encode($room_type['name']) ?>,
                            location: <?= json_encode($room_type['location']) ?>,
                            lat: <?= $room_type['latitude'] ?>,
                            lng: <?= $room_type['longitude'] ?>,
                            price: <?= $room_type['base_price'] ?>,
                            capacity: <?= $room_type['capacity'] ?>,
                            bookUrl: <?= json_encode(site_url('book/room/' . $room_type['id'])) ?>,
                            available: <?= $room_type['available_rooms_count'] > 0 ? 'true' : 'false' ?>
                        },
                    <?php endif; ?>
                <?php endforeach; ?>
            ];
            
            function initMap() {
                if (map !== null) return; // Already initialized
                
                // Center on Mindoro
                map = L.map('map').setView([13.0, 121.2], 9);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                
                // Add markers for each room location
                roomLocations.forEach(function(room) {
                    const markerColor = room.available ? 'green' : 'red';
                    const availability = room.available ? 'Available' : 'Fully Booked';
                    
                    const marker = L.marker([room.lat, room.lng]).addTo(map);
                    
                    const popupContent = `
                        <div style="min-width: 200px;">
                            <h4 style="font-weight: bold; font-size: 16px; margin-bottom: 5px;">${room.name}</h4>
                            <p style="color: #ea580c; font-size: 14px; margin-bottom: 5px;">
                                <i class="fas fa-map-marker-alt"></i> ${room.location}
                            </p>
                            <p style="font-size: 14px; margin-bottom: 5px;">
                                <strong>₱${room.price.toLocaleString('en-PH', {minimumFractionDigits: 2})}</strong> / night
                            </p>
                            <p style="font-size: 14px; margin-bottom: 5px;">Sleeps ${room.capacity}</p>
                            <p style="font-size: 14px; margin-bottom: 10px; color: ${room.available ? '#16a34a' : '#dc2626'};">
                                <strong>${availability}</strong>
                            </p>
                            ${room.available ? `<a href="${room.bookUrl}" style="display: inline-block; background-color: #ea580c; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; text-align: center;">Book Now</a>` : ''}
                        </div>
                    `;
                    
                    marker.bindPopup(popupContent);
                    markers.push({marker: marker, room: room});
                });
                
                // Fit bounds to show all markers
                if (roomLocations.length > 0) {
                    const bounds = L.latLngBounds(roomLocations.map(r => [r.lat, r.lng]));
                    map.fitBounds(bounds, {padding: [50, 50]});
                }
            }
            
            // Open map modal
            viewMapBtn.addEventListener('click', function() {
                mapModal.classList.remove('hidden');
                setTimeout(function() {
                    initMap();
                    map.invalidateSize(); // Fix map rendering issues
                }, 100);
            });
            
            // Close map modal
            closeMapBtn.addEventListener('click', function() {
                mapModal.classList.add('hidden');
            });
            
            // Close modal when clicking outside
            mapModal.addEventListener('click', function(e) {
                if (e.target === mapModal) {
                    mapModal.classList.add('hidden');
                }
            });
            
            // Click on room location item to pan to marker
            document.querySelectorAll('.room-location-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    const lat = parseFloat(this.dataset.lat);
                    const lng = parseFloat(this.dataset.lng);
                    const name = this.dataset.name;
                    
                    if (map) {
                        map.setView([lat, lng], 13);
                        
                        // Find and open the corresponding marker popup
                        markers.forEach(function(m) {
                            if (m.room.name === name) {
                                m.marker.openPopup();
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>