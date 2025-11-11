<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Room</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Add a physical room and assign it a type.</p>
                <div class="mt-5">
                    <form action="<?= site_url('admin/rooms/save') ?>" method="post">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="room_number" class="block text-sm font-medium text-gray-700">Room Number / Name</label>
                                <input type="text" name="room_number" id="room_number" placeholder="e.g., Room 101 or Villa A" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="room_type_id" class="block text-sm font-medium text-gray-700">Room Type</label>
                                <select id="room_type_id" name="room_type_id" required class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-orange-500 focus:outline-none focus:ring-orange-500 sm:text-sm">
                                    <option disabled selected>Select a room type...</option>
                                    <?php foreach ($room_types as $type): ?>
                                        <option value="<?= $type['id']; ?>"><?= html_escape($type['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                             <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-orange-500 focus:outline-none focus:ring-orange-500 sm:text-sm">
                                    <option value="available">Available</option>
                                    <option value="occupied">Occupied</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="<?= site_url('admin/rooms') ?>" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-orange-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-orange-700">Save Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>