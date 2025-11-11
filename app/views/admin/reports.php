<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <?php include 'partials/admin_nav.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Reports & Analytics</h1>

        <!-- Date Range Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form action="<?= site_url('admin/reports') ?>" method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="<?= html_escape($start_date) ?>" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="<?= html_escape($end_date) ?>" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500">
                </div>
                <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700">
                    <i class="fas fa-filter mr-2"></i>Apply Filter
                </button>
                <a href="<?= site_url('admin/export-bookings?start_date=' . $start_date . '&end_date=' . $end_date) ?>" 
                   class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
            </form>
        </div>

        <!-- Revenue Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Room Revenue</p>
                        <p class="text-3xl font-bold text-gray-900">₱<?= number_format($room_revenue, 2) ?></p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-bed text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tour Revenue</p>
                        <p class="text-3xl font-bold text-gray-900">₱<?= number_format($tour_revenue, 2) ?></p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-map-marked-alt text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-3xl font-bold text-orange-600">₱<?= number_format($total_revenue, 2) ?></p>
                    </div>
                    <div class="bg-orange-100 rounded-full p-3">
                        <i class="fas fa-dollar-sign text-orange-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Statistics -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Booking Statistics</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-900"><?= $booking_stats['total_bookings'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Total Bookings</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-2xl font-bold text-green-600"><?= $booking_stats['confirmed'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Confirmed</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-2xl font-bold text-blue-600"><?= $booking_stats['completed'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Completed</p>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <p class="text-2xl font-bold text-red-600"><?= $booking_stats['cancelled'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Cancelled</p>
                </div>
            </div>
        </div>

        <!-- Popular Items -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Popular Room Types -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Most Popular Room Types</h2>
                <?php if (!empty($popular_rooms)): ?>
                    <div class="space-y-3">
                        <?php foreach ($popular_rooms as $room): ?>
                            <div class="flex items-center justify-between border-b pb-2">
                                <div>
                                    <p class="font-semibold text-gray-900"><?= html_escape($room['name']) ?></p>
                                    <p class="text-sm text-gray-500"><?= $room['booking_count'] ?> bookings</p>
                                </div>
                                <p class="font-bold text-orange-600">₱<?= number_format($room['revenue'], 2) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-4">No data available for this period.</p>
                <?php endif; ?>
            </div>

            <!-- Popular Tours -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Most Popular Tours</h2>
                <?php if (!empty($popular_tours)): ?>
                    <div class="space-y-3">
                        <?php foreach ($popular_tours as $tour): ?>
                            <div class="flex items-center justify-between border-b pb-2">
                                <div>
                                    <p class="font-semibold text-gray-900"><?= html_escape($tour['name']) ?></p>
                                    <p class="text-sm text-gray-500"><?= $tour['booking_count'] ?> bookings</p>
                                </div>
                                <p class="font-bold text-orange-600">₱<?= number_format($tour['revenue'], 2) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-4">No data available for this period.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
