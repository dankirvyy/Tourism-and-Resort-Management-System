<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Room - Visit Mindoro</title>
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
                            <p class="text-sm text-gray-500">Assign Room to Guest</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="<?= site_url('frontdesk/dashboard') ?>" class="text-gray-700 hover:text-orange-600">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                        </a>
                        <a href="<?= site_url('frontdesk/logout') ?>" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Booking Information -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 bg-orange-600 text-white rounded-t-lg">
                    <h2 class="text-xl font-bold">
                        <i class="fas fa-user mr-2"></i>
                        Booking Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Guest Name</label>
                            <p class="text-lg font-semibold text-gray-900">
                                <?= html_escape($guest['first_name'] . ' ' . $guest['last_name']) ?>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact</label>
                            <p class="text-gray-900"><?= html_escape($guest['email']) ?></p>
                            <p class="text-gray-900"><?= html_escape($guest['phone_number']) ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                            <p class="text-lg font-semibold text-gray-900">
                                <?= html_escape($room_type['name']) ?>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Price</label>
                            <p class="text-lg font-semibold text-green-600">
                                ₱<?= number_format($booking['total_price'], 2) ?>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-in</label>
                            <p class="text-gray-900">
                                <?= date('F j, Y', strtotime($booking['check_in_date'])) ?><br>
                                <span class="text-sm text-gray-600"><?= date('g:i A', strtotime($booking['check_in_time'])) ?></span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-out</label>
                            <p class="text-gray-900">
                                <?= date('F j, Y', strtotime($booking['check_out_date'])) ?><br>
                                <span class="text-sm text-gray-600"><?= date('g:i A', strtotime($booking['check_out_time'])) ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Room Assignment Form -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 bg-orange-600 text-white">
                    <h2 class="text-xl font-bold">
                        <i class="fas fa-key mr-2"></i>
                        Select Available Room
                    </h2>
                </div>
                <div class="p-6">
                    <?php if (!empty($available_rooms)): ?>
                        <form action="<?= site_url('frontdesk/process-assignment') ?>" method="POST" id="assignForm">
                            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Available Rooms <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php foreach ($available_rooms as $room): ?>
                                        <label class="relative flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-orange-500 hover:bg-orange-50 transition">
                                            <input type="radio" 
                                                   name="room_id" 
                                                   value="<?= $room['id'] ?>" 
                                                   required
                                                   class="h-5 w-5 text-orange-600 focus:ring-orange-500">
                                            <div class="ml-3">
                                                <p class="text-lg font-semibold text-gray-900">
                                                    Room <?= html_escape($room['room_number']) ?>
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                                    <?= ucfirst($room['status']) ?>
                                                </p>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            Only rooms that are available for the guest's check-in and check-out dates are shown.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end gap-4">
                                <a href="<?= site_url('frontdesk/dashboard') ?>" 
                                   class="inline-flex items-center px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                    <i class="fas fa-times mr-2"></i> Cancel
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                                    <i class="fas fa-check mr-2"></i> Assign Room
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-circle text-6xl text-yellow-500 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Available Rooms</h3>
                            <p class="text-gray-600 mb-6">
                                There are no available rooms of type "<?= html_escape($booking['room_type_name']) ?>" 
                                for the selected dates.
                            </p>
                            <a href="<?= site_url('frontdesk/dashboard') ?>" 
                               class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add visual feedback when selecting a room
        document.querySelectorAll('input[name="room_id"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[name="room_id"]').forEach(r => {
                    r.parentElement.classList.remove('border-orange-500', 'bg-orange-50');
                    r.parentElement.classList.add('border-gray-300');
                });
                this.parentElement.classList.remove('border-gray-300');
                this.parentElement.classList.add('border-orange-500', 'bg-orange-50');
            });
        });

        // Confirm before assignment
        document.getElementById('assignForm')?.addEventListener('submit', function(e) {
            const selectedRoom = document.querySelector('input[name="room_id"]:checked');
            if (selectedRoom) {
                const roomNumber = selectedRoom.parentElement.querySelector('.text-lg').textContent.trim();
                if (!confirm(`Are you sure you want to assign ${roomNumber} to this guest?`)) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>
