<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Guest - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'partials/admin_nav.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="<?= site_url('admin/guests') ?>" class="text-orange-600 hover:text-orange-700">
                <i class="fas fa-arrow-left mr-2"></i>Back to Guests
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Guest Info Card -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-center mb-4">
                        <?php 
                        $avatarUrl = ($guest && $guest['avatar_filename'])
                            ? base_url('public/uploads/avatars/' . $guest['avatar_filename'])
                            : 'https://via.placeholder.com/150/cccccc/888888?text=' . strtoupper(substr($guest['first_name'], 0, 1));
                        ?>
                        <img src="<?= $avatarUrl ?>" alt="Avatar" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <?= html_escape($guest['first_name'] . ' ' . $guest['last_name']) ?>
                        </h2>
                        <p class="text-gray-600"><?= html_escape($guest['email']) ?></p>
                        <span class="mt-2 inline-block px-3 py-1 text-sm font-semibold rounded-full <?= $guest['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' ?>">
                            <?= ucfirst($guest['role']) ?>
                        </span>
                    </div>

                    <div class="border-t pt-4 space-y-3">
                        <div>
                            <label class="text-gray-600 text-sm">Phone Number</label>
                            <p class="text-gray-900 font-medium"><?= html_escape($guest['phone_number'] ?? 'N/A') ?></p>
                        </div>
                        <div>
                            <label class="text-gray-600 text-sm">Member Since</label>
                            <p class="text-gray-900 font-medium">
                                <?= isset($guest['created_at']) ? date('F d, Y', strtotime($guest['created_at'])) : 'N/A' ?>
                            </p>
                        </div>
                        <div>
                            <label class="text-gray-600 text-sm">Total Spent</label>
                            <p class="text-gray-900 font-bold text-xl">₱<?= number_format($total_spent, 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking History -->
            <div class="md:col-span-2">
                <!-- Room Bookings -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-bed mr-2 text-orange-600"></i>Room Bookings
                    </h3>
                    
                    <?php if (!empty($room_bookings)): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Check-out</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($room_bookings as $booking): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm">
                                                <?= html_escape($booking['room_type_name']) ?><br>
                                                <span class="text-gray-500 text-xs">Room <?= html_escape($booking['room_number']) ?></span>
                                            </td>
                                            <td class="px-4 py-3 text-sm"><?= date('M d, Y', strtotime($booking['check_in_date'])) ?></td>
                                            <td class="px-4 py-3 text-sm"><?= date('M d, Y', strtotime($booking['check_out_date'])) ?></td>
                                            <td class="px-4 py-3 text-sm font-semibold">₱<?= number_format($booking['total_price'], 2) ?></td>
                                            <td class="px-4 py-3">
                                                <?php
                                                $status_colors = [
                                                    'confirmed' => 'bg-green-100 text-green-800',
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                    'completed' => 'bg-blue-100 text-blue-800'
                                                ];
                                                $color = $status_colors[$booking['status']] ?? 'bg-gray-100 text-gray-800';
                                                ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $color ?>">
                                                    <?= ucfirst($booking['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">No room bookings yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Tour Bookings -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-map-marked-alt mr-2 text-orange-600"></i>Tour Bookings
                    </h3>
                    
                    <?php if (!empty($tour_bookings)): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tour</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Guests</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($tour_bookings as $booking): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm"><?= html_escape($booking['tour_name']) ?></td>
                                            <td class="px-4 py-3 text-sm"><?= date('M d, Y', strtotime($booking['booking_date'])) ?></td>
                                            <td class="px-4 py-3 text-sm"><?= $booking['number_of_pax'] ?> pax</td>
                                            <td class="px-4 py-3 text-sm font-semibold">₱<?= number_format($booking['total_price'], 2) ?></td>
                                            <td class="px-4 py-3">
                                                <?php
                                                $status_colors = [
                                                    'confirmed' => 'bg-green-100 text-green-800',
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                    'completed' => 'bg-blue-100 text-blue-800'
                                                ];
                                                $color = $status_colors[$booking['status']] ?? 'bg-gray-100 text-gray-800';
                                                ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $color ?>">
                                                    <?= ucfirst($booking['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">No tour bookings yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
