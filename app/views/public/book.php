<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book a Room - Visit Mindoro</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path></svg>
                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900"><?= html_escape($room_type['name']) ?></h2>
                    <p class="mt-4 text-lg text-gray-500"><?= html_escape($room_type['description']) ?></p>
                    <div class="mt-6">
                        <?php 
                            $imageUrl = $room_type['image_filename'] 
                                ? base_url('public/uploads/images/' . $room_type['image_filename']) 
                                : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=60'; // Default placeholder
                        ?>
                        <img class="rounded-lg shadow-lg object-cover w-full h-80" src="<?= $imageUrl ?>" alt="<?= html_escape($room_type['name']) ?>">
                    </div>
                    <div class="mt-6 flex justify-between items-center">
                         <p class="text-2xl font-bold text-gray-900">₱<?= number_format($room_type['base_price'], 2) ?> <span class="text-lg font-medium text-gray-500">/ night</span></p>
                         <p class="text-md text-gray-600">Sleeps up to <?= html_escape($room_type['capacity']) ?> guests</p>
                    </div>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-gray-900">Reserve Your Stay</h3>
                    <form action="<?= site_url('book/process') ?>" method="POST" class="mt-6 space-y-6">
                        <input type="hidden" name="room_type_id" value="<?= $room_type['id'] ?>">

                        <div>
                            <label for="room_id" class="block text-sm font-medium text-gray-700">Select an Available Room</label>
                            <select id="room_id" name="room_id" required class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-orange-500 focus:outline-none focus:ring-orange-500 sm:text-sm">
                                <?php if (!empty($available_rooms)): ?>
                                    <option value="" disabled selected>Choose a room...</option>
                                    <?php foreach ($available_rooms as $room): ?>
                                        <option value="<?= $room['id']; ?>"><?= html_escape($room['room_number']); ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled selected>No rooms of this type are available</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="checkin" class="block text-sm font-medium text-gray-700">Check-in Date</label>
                                <input type="date" name="checkin" id="checkin" value="<?= isset($selected_check_in) ? html_escape($selected_check_in) : '' ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="checkout" class="block text-sm font-medium text-gray-700">Check-out Date</label>
                                <input type="date" name="checkout" id="checkout" value="<?= isset($selected_check_out) ? html_escape($selected_check_out) : '' ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                        </div>
                        <hr class="border-gray-200"/>
                        <h4 class="text-lg font-medium text-gray-900">Your Details</h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="first_name" id="first_name" value="<?= isset($guest['first_name']) ? html_escape($guest['first_name']) : '' ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="last_name" id="last_name" value="<?= isset($guest['last_name']) ? html_escape($guest['last_name']) : '' ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="<?= isset($guest['email']) ? html_escape($guest['email']) : '' ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="<?= isset($guest['phone_number']) ? html_escape($guest['phone_number']) : '' ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                        </div>
                        <div>
                            <button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:bg-gray-400 disabled:cursor-not-allowed" <?= empty($available_rooms) ? 'disabled' : '' ?>>
                                Submit Reservation
                            </button>
                        </div>
                    </form>
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
</body>
</html>