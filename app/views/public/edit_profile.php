<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Visit Mindoro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md sticky top-0 z-50">
       <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
           <div class="flex items-center justify-between h-16">
               <div class="flex-shrink-0"><a href="<?= site_url('/') ?>" class="text-2xl font-bold text-orange-600">Visit Mindoro</a></div>
               <div class="hidden md:flex md:items-center md:space-x-4">
                   <a href="<?= site_url('/') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                   <a href="<?= site_url('rooms') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Rooms</a>
                   <a href="<?= site_url('tours') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Tours</a>
                   <a href="<?= site_url('contact') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                   <div class="border-l border-gray-300 h-6"></div>
                   <?php if ($this->session->has_userdata('user_id')):
                       $guest_info = $this->Guest_model->find($this->session->userdata('user_id'));
                       $avatarUrl = ($guest_info && $guest_info['avatar_filename']) ? base_url('public/uploads/avatars/' . $guest_info['avatar_filename']) : 'https://via.placeholder.com/32/cccccc/888888?text=U';
                   ?>
                       <div class="ml-4 flex items-center space-x-4"><a href="<?= site_url('my-profile') ?>" class="flex items-center text-sm font-semibold text-orange-600"><img class="h-8 w-8 rounded-full mr-2" src="<?= $avatarUrl ?>" alt="User Avatar">Welcome, <?= $this->session->userdata('user_name'); ?>!</a><a href="<?= site_url('logout') ?>" class="text-gray-500 hover:text-gray-900 text-sm font-medium">Logout</a></div>
                   <?php else: ?>
                        <div class="ml-4 flex items-center"><a href="<?= site_url('login') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Login</a><a href="<?= site_url('signup') ?>" class="ml-2 inline-flex items-center justify-center rounded-md border border-transparent bg-orange-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-orange-700">Sign Up</a></div>
                   <?php endif; ?>
               </div>
               <div class="md:hidden flex items-center"><button id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500" aria-expanded="false"><span class="sr-only">Open main menu</span><svg class="block h-6 w-6" x-description="Heroicon name: outline/menu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path></svg><svg class="hidden h-6 w-6" x-description="Heroicon name: outline/x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></button></div>
           </div>
       </nav>
       <div class="md:hidden hidden" id="mobile-menu"><div class="px-2 pt-2 pb-3 space-y-1 sm:px-3"><a href="<?= site_url('/') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Home</a><a href="<?= site_url('rooms') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Rooms</a><a href="<?= site_url('tours') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Tours</a><a href="<?= site_url('contact') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Contact</a></div><div class="pt-4 pb-3 border-t border-gray-200"><div class="px-2 space-y-1"><?php if ($this->session->has_userdata('user_id')): ?><a href="<?= site_url('my-profile') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">My Profile</a><a href="<?= site_url('logout') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Logout</a><?php else: ?><a href="<?= site_url('login') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Login</a><a href="<?= site_url('signup') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Sign Up</a><?php endif; ?></div></div></div>
    </header>

    <div class="max-w-xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Your Profile</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Update your personal information.</p>

                <div class="mt-5">
                    <form action="<?= site_url('profile/update') ?>" method="post">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="first_name" id="first_name" value="<?= html_escape($guest['first_name']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="last_name" id="last_name" value="<?= html_escape($guest['last_name']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" name="email" id="email" value="<?= html_escape($guest['email']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" name="phone_number" id="phone_number" value="<?= html_escape($guest['phone_number']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="<?= site_url('my-profile') ?>" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-orange-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-orange-700">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white mt-12"><div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8"><p class="text-center text-base text-gray-400">&copy; <?= date('Y') ?> Visit Mindoro. All rights reserved.</p></div></footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () { /* ... Hamburger script ... */ });
    </script>
</body>
</html>