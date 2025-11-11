<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="<?= site_url('public/css/output.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-10 pb-4 border-b border-gray-300">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
                <p class="mt-1 text-md text-gray-500">Welcome back, <span class="font-semibold text-orange-600"><?= $this->session->userdata('admin_user_name'); ?></span>!</p>
            </div>
            <a href="<?= site_url('admin/logout') ?>" class="inline-flex items-center gap-x-2 rounded-md bg-red-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                <i class="fa-solid fa-right-from-bracket -ml-0.5 h-5 w-5"></i>
                Logout
            </a>
        </div>

        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4 mb-12">

            <a href="<?= site_url('admin/tours') ?>" class="group block p-6 bg-white rounded-xl shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-center bg-orange-100 rounded-full w-12 h-12">
                    <i class="fa-solid fa-map-signs w-7 h-7 text-orange-600"></i>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-800 group-hover:text-orange-600">Manage Tours</h3>
                <p class="mt-1 text-sm text-gray-500">Add & edit packages.</p>
            </a>

            <a href="<?= site_url('admin/room-types') ?>" class="group block p-6 bg-white rounded-xl shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-center bg-blue-100 rounded-full w-12 h-12">
                    <i class="fa-solid fa-tags w-7 h-7 text-blue-600"></i>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-800 group-hover:text-blue-600">Manage Room Types</h3>
                <p class="mt-1 text-sm text-gray-500">Define categories.</p>
            </a>

            <a href="<?= site_url('admin/rooms') ?>" class="group block p-6 bg-white rounded-xl shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                 <div class="flex items-center justify-center bg-green-100 rounded-full w-12 h-12">
                    <i class="fa-solid fa-hotel w-7 h-7 text-green-600"></i>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-800 group-hover:text-green-600">Manage Rooms</h3>
                <p class="mt-1 text-sm text-gray-500">Manage physical rooms.</p>
            </a>
            
            <a href="<?= site_url('admin/bookings') ?>" class="group block p-6 bg-white rounded-xl shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-center bg-purple-100 rounded-full w-12 h-12">
                     <i class="fa-solid fa-calendar-check w-7 h-7 text-purple-600"></i>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-800 group-hover:text-purple-600">Manage Bookings</h3>
                <p class="mt-1 text-sm text-gray-500">View reservations.</p>
            </a>

            <a href="<?= site_url('admin/resources') ?>" class="group block p-6 bg-white rounded-xl shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-center bg-teal-100 rounded-full w-12 h-12">
                    <i class="fa-solid fa-user-group w-7 h-7 text-teal-600"></i>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-800 group-hover:text-teal-600">Manage Resources</h3>
                <p class="mt-1 text-sm text-gray-500">Guides, vehicles, etc.</p>
            </a>

            <a href="<?= site_url('admin/tour-bookings') ?>" class="group block p-6 bg-white rounded-xl shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-center bg-yellow-100 rounded-full w-12 h-12">
                    <i class="fa-solid fa-ticket w-7 h-7 text-yellow-600"></i>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-800 group-hover:text-yellow-600">Tour Bookings</h3>
                <p class="mt-1 text-sm text-gray-500">View tour reservations.</p>
            </a>

            <a href="<?= site_url('admin/guests') ?>" class="group block p-6 bg-white rounded-xl shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-center bg-indigo-100 rounded-full w-12 h-12">
                    <i class="fa-solid fa-users w-7 h-7 text-indigo-600"></i>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-800 group-hover:text-indigo-600">Manage Guests</h3>
                <p class="mt-1 text-sm text-gray-500">View & manage users.</p>
            </a>

            <a href="<?= site_url('admin/reports') ?>" class="group block p-6 bg-white rounded-xl shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-center bg-pink-100 rounded-full w-12 h-12">
                    <i class="fa-solid fa-chart-line w-7 h-7 text-pink-600"></i>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-800 group-hover:text-pink-600">Reports</h3>
                <p class="mt-1 text-sm text-gray-500">Analytics & exports.</p>
            </a>
        </div>

        <h2 class="text-xl font-semibold text-gray-700 mt-12 mb-6">Analytics</h2>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900">Revenue (Last 7 Days)</h3>
                    <div class="mt-4 h-80">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900">Confirmed Booking Types</h3>
                    <div class="mt-4 h-80">
                        <canvas id="bookingsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="text-xl font-semibold text-gray-700 mt-12 mb-6">Overview</h2>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Tours</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900"><?= $tour_count ?? 0 ?></dd>
                    </dl>
                </div>
            </div>
             <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Rooms</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900"><?= $room_count ?? 0 ?></dd>
                    </dl>
                </div>
            </div>
             <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                     <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Active Room Bookings</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900"><?= $active_booking_count ?? 0 ?></dd>
                    </dl>
                </div>
            </div>
             <div class="bg-white overflow-hidden shadow rounded-lg">
                 <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Available Resources</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900"><?= $resource_count ?? 0 ?></dd>
                    </dl>
                </div>
            </div>
             <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Confirmed Tour Bookings</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900"><?= $tour_booking_count ?? 0 ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- 1. Revenue Line Chart ---
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueLabels = <?= $line_chart_labels; ?>;
            const revenueData = <?= $line_chart_data; ?>;

            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Revenue (PHP)',
                        data: revenueData,
                        borderColor: 'rgb(234, 88, 12)', // Orange color
                        backgroundColor: 'rgba(234, 88, 12, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // --- 2. Bookings Doughnut Chart ---
            const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
            const bookingsLabels = <?= $doughnut_chart_labels; ?>;
            const bookingsData = <?= $doughnut_chart_data; ?>;

            new Chart(bookingsCtx, {
                type: 'doughnut',
                data: {
                    labels: bookingsLabels,
                    datasets: [{
                        label: 'Total Bookings',
                        data: bookingsData,
                        backgroundColor: [
                            'rgb(59, 130, 246)',  // Blue (for Rooms)
                            'rgb(234, 88, 12)'   // Orange (for Tours)
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
            
        });
    </script>
</body>
</html>