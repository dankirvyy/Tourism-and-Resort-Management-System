<nav class="bg-white shadow-lg mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex space-x-8">
                <a href="<?= site_url('admin/dashboard') ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium <?= (isset($active_page) && $active_page === 'dashboard') ? 'border-b-2 border-orange-500 text-gray-900' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="<?= site_url('admin/tours') ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium <?= (isset($active_page) && $active_page === 'tours') ? 'border-b-2 border-orange-500 text-gray-900' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    <i class="fas fa-map-signs mr-2"></i>Tours
                </a>
                <a href="<?= site_url('admin/rooms') ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium <?= (isset($active_page) && $active_page === 'rooms') ? 'border-b-2 border-orange-500 text-gray-900' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    <i class="fas fa-hotel mr-2"></i>Rooms
                </a>
                <a href="<?= site_url('admin/bookings') ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium <?= (isset($active_page) && $active_page === 'bookings') ? 'border-b-2 border-orange-500 text-gray-900' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    <i class="fas fa-calendar-check mr-2"></i>Bookings
                </a>
                <a href="<?= site_url('admin/tour-bookings') ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium <?= (isset($active_page) && $active_page === 'tour_bookings') ? 'border-b-2 border-orange-500 text-gray-900' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    <i class="fas fa-ticket mr-2"></i>Tour Bookings
                </a>
                <a href="<?= site_url('admin/guests') ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium <?= (isset($active_page) && $active_page === 'guests') ? 'border-b-2 border-orange-500 text-gray-900' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    <i class="fas fa-users mr-2"></i>Guests
                </a>
                <a href="<?= site_url('admin/reports') ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium <?= (isset($active_page) && $active_page === 'reports') ? 'border-b-2 border-orange-500 text-gray-900' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    <i class="fas fa-chart-line mr-2"></i>Reports
                </a>
            </div>
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-4">
                    <?= $this->session->userdata('admin_user_name') ?>
                </span>
                <a href="<?= site_url('admin/logout') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>
    </div>
</nav>
