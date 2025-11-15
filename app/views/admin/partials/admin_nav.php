<nav class="bg-white shadow-lg mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-4">
                <a href="<?= site_url('admin/dashboard') ?>" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md <?= (isset($active_page) && $active_page === 'dashboard') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                
                <!-- Dropdown for Manage -->
                <div class="relative">
                    <button onclick="toggleDropdown('manageDropdown')" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-cog mr-2"></i>Manage
                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>
                    <div id="manageDropdown" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                        <a href="<?= site_url('admin/tours') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-map-signs mr-2"></i>Tours
                        </a>
                        <a href="<?= site_url('admin/rooms') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-hotel mr-2"></i>Rooms
                        </a>
                        <a href="<?= site_url('admin/resources') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-tools mr-2"></i>Resources
                        </a>
                        <a href="<?= site_url('admin/guests') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-users mr-2"></i>Guests
                        </a>
                    </div>
                </div>
                
                <!-- Dropdown for Bookings -->
                <div class="relative">
                    <button onclick="toggleDropdown('bookingsDropdown')" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-calendar-check mr-2"></i>Bookings
                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>
                    <div id="bookingsDropdown" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                        <a href="<?= site_url('admin/bookings') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-bed mr-2"></i>Room Bookings
                        </a>
                        <a href="<?= site_url('admin/tour-bookings') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-ticket mr-2"></i>Tour Bookings
                        </a>
                        <a href="<?= site_url('admin/resources/calendar') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-calendar-alt mr-2"></i>Resource Calendar
                        </a>
                    </div>
                </div>
                
                <a href="<?= site_url('admin/crm/dashboard') ?>" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md <?= (isset($active_page) && $active_page === 'crm') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i class="fas fa-users-cog mr-2"></i>CRM
                </a>
                
                <a href="<?= site_url('admin/invoices') ?>" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md <?= (isset($active_page) && $active_page === 'invoices') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>Invoices
                </a>
                
                <a href="<?= site_url('admin/reports') ?>" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md <?= (isset($active_page) && $active_page === 'reports') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i class="fas fa-chart-line mr-2"></i>Reports
                </a>
            </div>
            
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-700">
                    <i class="fas fa-user-circle mr-1"></i><?= $this->session->userdata('admin_user_name') ?>
                </span>
                <a href="<?= site_url('admin/logout') ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleDropdown(dropdownId) {
        // Close all other dropdowns first
        const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');
        allDropdowns.forEach(dropdown => {
            if (dropdown.id !== dropdownId) {
                dropdown.classList.add('hidden');
            }
        });
        
        // Toggle the clicked dropdown
        const dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle('hidden');
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const isDropdownButton = event.target.closest('button[onclick^="toggleDropdown"]');
        const isDropdownMenu = event.target.closest('[id$="Dropdown"]');
        
        if (!isDropdownButton && !isDropdownMenu) {
            const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');
            allDropdowns.forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
</script>
