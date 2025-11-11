<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tour Booking - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-8 pb-4 border-b border-gray-300">
            <div class="flex justify-between items-center">
                 <a href="<?= site_url('admin/tour-bookings') ?>" class="text-sm font-medium text-gray-500 hover:text-orange-600 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Tour Bookings
                </a>
                <a href="<?= site_url('admin/logout') ?>" class="text-sm font-semibold text-red-600 hover:text-red-800">Logout</a>
            </div>
             <h1 class="mt-4 text-3xl font-bold text-gray-800">Manage Tour Booking</h1>
        </div>
        <div class="bg-white shadow sm:rounded-lg overflow-hidden mb-8">
            <div class="px-4 py-5 sm:px-6">
                <h2 class="text-xl font-semibold text-gray-900">Booking #<?= html_escape($booking['id']) ?> Details</h2>
                <p class="mt-1 text-sm text-gray-500">Summary of the tour reservation.</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-3">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Guest</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= html_escape($booking['first_name'] . ' ' . $booking['last_name']) ?></dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Tour</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= html_escape($booking['tour_name']) ?></dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Tour Date</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= html_escape($booking['booking_date']) ?></dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Number of Guests</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= html_escape($booking['number_of_pax']) ?></dd>
                    </div>
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= ucfirst(html_escape($booking['status'])) ?></dd>
                    </div>
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Total Price</dt>
                        <dd class="mt-1 text-sm text-gray-900">₱<?= number_format($booking['total_price'], 2) ?></dd>
                    </div>
                </dl>
            </div>
        </div>
        
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6">
                <h2 class="text-xl font-semibold text-gray-900">Assigned Resources</h2>
                <p class="mt-1 text-sm text-gray-500">Manage guides, vehicles, or boats for this tour.</p>
            </div>
            
            <div class="border-t border-gray-200">
                <ul role="list" class="divide-y divide-gray-200">
                    <?php if (!empty($assigned_resources)): ?>
                        <?php foreach ($assigned_resources as $resource): ?>
                            <li class="px-4 py-4 sm:px-6 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900"><?= html_escape($resource['name']) ?></p>
                                    <p class="text-sm text-gray-500"><?= html_escape($resource['type']) ?></p>
                                </div>
                                <a href="<?= site_url('admin/tour-booking/unassign/' . $resource['schedule_id'] . '/' . $booking['id']) ?>" class="text-sm font-medium text-red-600 hover:text-red-800">
                                    Un-assign
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="px-4 py-4 sm:px-6">
                            <p class="text-sm text-gray-500">No resources assigned to this booking yet.</p>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="bg-gray-50 px-4 py-5 sm:px-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Assign a New Resource</h3>
                <form action="<?= site_url('admin/tour-booking/assign-resource') ?>" method="POST" class="mt-4 sm:flex sm:items-center">
                    <input type="hidden" name="tour_booking_id" value="<?= $booking['id'] ?>">
                    
                    <input type="hidden" name="start_time" value="<?= $booking['booking_date'] ?> 08:00:00">
                    <input type="hidden" name="end_time" value="<?= $booking['booking_date'] ?> 17:00:00">
                    
                    <div class="w-full sm:max-w-xs">
                        <label for="resource_id" class="sr-only">Resource</label>
                        <select id="resource_id" name="resource_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            <option value="" disabled selected>Select an available resource...</option>
                            <?php foreach ($all_available_resources as $resource): ?>
                                <option value="<?= $resource['id'] ?>"><?= html_escape($resource['name']) ?> (<?= html_escape($resource['type']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="mt-3 inline-flex w-full items-center justify-center rounded-md border border-transparent bg-orange-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-orange-700 sm:mt-0 sm:ml-3 sm:w-auto">
                        Assign
                    </button>
                </form>
            </div>
        </div>

    </div>
</body>
</html>