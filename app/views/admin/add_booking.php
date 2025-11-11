<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Create New Booking</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Manually enter a reservation for a guest.</p>
                <div class="mt-5">
                    <form action="<?= site_url('admin/bookings/save') ?>" method="post">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="guest_id" class="block text-sm font-medium text-gray-700">Guest</label>
                                <select id="guest_id" name="guest_id" required class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-orange-500 focus:outline-none focus:ring-orange-500 sm:text-sm">
                                    <option disabled selected>Select an existing guest...</option>
                                    <?php foreach ($guests as $guest): ?>
                                        <option value="<?= $guest['id']; ?>"><?= html_escape($guest['first_name'] . ' ' . $guest['last_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="room_id" class="block text-sm font-medium text-gray-700">Room</label>
                                <select id="room_id" name="room_id" required class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-orange-500 focus:outline-none focus:ring-orange-500 sm:text-sm">
                                    <option disabled selected>Select a room...</option>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?= $room['id']; ?>"><?= html_escape($room['room_number'] . ' (' . $room['type_name'] . ')'); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="check_in_date" class="block text-sm font-medium text-gray-700">Check-in Date</label>
                                    <input type="date" name="check_in_date" id="check_in_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="check_out_date" class="block text-sm font-medium text-gray-700">Check-out Date</label>
                                    <input type="date" name="check_out_date" id="check_out_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                </div>
                            </div>
                            
                             <div>
                                <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price (PHP)</label>
                                <input type="number" step="0.01" name="total_price" id="total_price" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>

                             <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Booking Status</label>
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-orange-500 focus:outline-none focus:ring-orange-500 sm:text-sm">
                                    <option value="confirmed">Confirmed</option>
                                    <option value="pending">Pending</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="<?= site_url('admin/bookings') ?>" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-orange-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-orange-700">Save Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>