<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Front Desk Dashboard - Visit Mindoro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <img class="h-10 w-auto" src="<?= site_url('public/uploads/images/logo.png') ?>" alt="Visit Mindoro Logo">
                        <div>
                            <h1 class="text-2xl font-bold text-orange-600">Visit Mindoro</h1>
                            <p class="text-sm text-gray-500">Front Desk Dashboard</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-700">Welcome, <?= $this->session->userdata('front_desk_user_name') ?>!</span>
                        <a href="<?= site_url('frontdesk/logout') ?>" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>
            
            <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Pending Assignments</p>
                            <p class="text-2xl font-bold text-gray-900"><?= count($unassigned_bookings) ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-door-open text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Today's Check-ins</p>
                            <p class="text-2xl font-bold text-gray-900"><?= count($todays_checkins) ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-door-closed text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Today's Check-outs</p>
                            <p class="text-2xl font-bold text-gray-900"><?= count($todays_checkouts) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Room Assignments -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-bed text-orange-600 mr-2"></i>
                        Pending Room Assignments
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($unassigned_bookings)): ?>
                                <?php foreach ($unassigned_bookings as $booking): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= html_escape($booking['first_name'] . ' ' . $booking['last_name']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500"><?= html_escape($booking['email']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= html_escape($booking['room_type_name']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M j, Y', strtotime($booking['check_in_date'])) ?><br>
                                            <span class="text-xs"><?= date('g:i A', strtotime($booking['check_in_time'])) ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M j, Y', strtotime($booking['check_out_date'])) ?><br>
                                            <span class="text-xs"><?= date('g:i A', strtotime($booking['check_out_time'])) ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ₱<?= number_format($booking['total_price'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="<?= site_url('frontdesk/assign/' . $booking['id']) ?>" 
                                               class="inline-flex items-center px-3 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                                                <i class="fas fa-key mr-2"></i> Assign Room
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-check-circle text-4xl text-green-500 mb-2"></i>
                                        <p>All bookings have been assigned!</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Today's Check-ins and Check-outs -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Check-ins -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">
                            <i class="fas fa-calendar-check text-green-600 mr-2"></i>
                            Today's Check-ins
                        </h2>
                    </div>
                    <div class="p-6">
                        <?php if (!empty($todays_checkins)): ?>
                            <div class="space-y-4">
                                <?php foreach ($todays_checkins as $checkin): ?>
                                    <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded">
                                        <div class="flex justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900">
                                                    <?= html_escape($checkin['first_name'] . ' ' . $checkin['last_name']) ?>
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <?= $checkin['room_number'] ? 'Room ' . html_escape($checkin['room_number']) : 'Not Assigned' ?> - 
                                                    <?= html_escape($checkin['room_type_name']) ?>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">
                                                    <?= date('g:i A', strtotime($checkin['check_in_time'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-center text-gray-500 py-4">No check-ins today</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Check-outs -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">
                            <i class="fas fa-calendar-times text-blue-600 mr-2"></i>
                            Today's Check-outs
                        </h2>
                    </div>
                    <div class="p-6">
                        <?php if (!empty($todays_checkouts)): ?>
                            <div class="space-y-4">
                                <?php foreach ($todays_checkouts as $checkout): ?>
                                    <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded">
                                        <div class="flex justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900">
                                                    <?= html_escape($checkout['first_name'] . ' ' . $checkout['last_name']) ?>
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    Room <?= html_escape($checkout['room_number']) ?> - 
                                                    <?= html_escape($checkout['room_type_name']) ?>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">
                                                    <?= date('g:i A', strtotime($checkout['check_out_time'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-center text-gray-500 py-4">No check-outs today</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
