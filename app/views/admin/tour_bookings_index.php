<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Tour Bookings</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-8 pb-4 border-b border-gray-300">
            <div class="flex justify-between items-center">
                 <a href="<?= site_url('admin/dashboard') ?>" class="text-sm font-medium text-gray-500 hover:text-orange-600 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
                </a>
                <a href="<?= site_url('admin/logout') ?>" class="inline-flex items-center gap-x-2 rounded-md bg-red-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    <svg class="-ml-0.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 015.25 2h5.5A2.25 2.25 0 0113 4.25v2a.75.75 0 01-1.5 0v-2a.75.75 0 00-.75-.75h-5.5a.75.75 0 00-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 00.75-.75v-2a.75.75 0 011.5 0v2A2.25 2.25 0 0110.75 18h-5.5A2.25 2.25 0 013 15.75V4.25z" clip-rule="evenodd" /><path fill-rule="evenodd" d="M6 10a.75.75 0 01.75-.75h9.546l-1.048-1.047a.75.75 0 111.06-1.06l2.5 2.5a.75.75 0 010 1.06l-2.5 2.5a.75.75 0 11-1.06-1.06L16.296 10.75H6.75A.75.75 0 016 10z" clip-rule="evenodd" /></svg>
                    Logout
                </a>
            </div>
             <h1 class="mt-4 text-3xl font-bold text-gray-800">Manage Tour Bookings</h1>
        </div>
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
             <div class="px-4 py-5 sm:p-6">
                 <div class="sm:flex sm:items-center sm:justify-between mb-6">
                    <p class="text-sm text-gray-600">A list of all tour reservations made by guests.</p>
                    </div>

                 <div class="flex flex-col">
                     <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Guest Name</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tour Name</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Booking Date</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Pax</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Price</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Amount Paid</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Balance Due</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Payment Status</th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <?php foreach ($tour_bookings as $booking): ?>
                                            <tr>
                                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"><?= html_escape($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"><?= html_escape($booking['tour_name']); ?></td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"><?= html_escape($booking['booking_date']); ?></td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"><?= html_escape($booking['number_of_pax']); ?></td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                     <?php 
                                                     $status_colors = [
                                                         'pending' => 'bg-yellow-100 text-yellow-800',
                                                         'confirmed' => 'bg-green-100 text-green-800',
                                                         'cancelled' => 'bg-red-100 text-red-800',
                                                         'completed' => 'bg-blue-100 text-blue-800'
                                                     ];
                                                     $color = $status_colors[$booking['status']] ?? 'bg-gray-100 text-gray-800';
                                                     ?>
                                                     <span class="px-2 py-1 rounded-full text-xs font-medium <?= $color ?>">
                                                         <?= ucfirst(html_escape($booking['status'])); ?>
                                                     </span>
                                                 </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱<?= number_format($booking['total_price'], 2); ?></td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-medium">₱<?= number_format($booking['amount_paid'] ?? $booking['total_price'], 2); ?></td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    <?php 
                                                    $balance = $booking['balance_due'] ?? 0;
                                                    echo $balance > 0 ? '₱' . number_format($balance, 2) : '—';
                                                    ?>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                    <?php 
                                                    $payment_status = $booking['payment_status'] ?? 'paid';
                                                    $payment_colors = [
                                                        'paid' => 'bg-green-100 text-green-800',
                                                        'partial' => 'bg-orange-100 text-orange-800',
                                                        'unpaid' => 'bg-red-100 text-red-800'
                                                    ];
                                                    $payment_color = $payment_colors[$payment_status] ?? 'bg-gray-100 text-gray-800';
                                                    ?>
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium <?= $payment_color ?>">
                                                        <?= ucfirst(html_escape($payment_status)); ?>
                                                    </span>
                                                </td>
                                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                    <?php if ($booking['status'] !== 'cancelled'): ?>
                                                        <!-- Quick Status Update Dropdown -->
                                                        <select onchange="showStatusModal(<?= $booking['id'] ?>, this.value, 'tour')" class="inline-block mr-2 text-xs border rounded px-2 py-1">
                                                            <option value="">Quick Status</option>
                                                            <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'disabled' : '' ?>>Confirmed</option>
                                                            <option value="completed" <?= $booking['status'] === 'completed' ? 'disabled' : '' ?>>Completed</option>
                                                            <option value="cancelled">Cancelled</option>
                                                        </select>
                                                        <a href="<?= site_url('admin/tour-booking/manage/' . $booking['id']) ?>" class="text-orange-600 hover:text-orange-900">
                                                            Manage Resources
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-gray-400 text-xs">Cancelled</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 mx-auto">
                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Update Tour Booking Status</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 text-center">
                        Are you sure you want to update this tour booking status to <span id="statusText" class="font-semibold"></span>?
                    </p>
                </div>
                <div class="flex gap-4 px-4 py-3">
                    <button onclick="closeStatusModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                    <button onclick="confirmStatusUpdate()" class="flex-1 px-4 py-2 bg-orange-600 text-white text-base font-medium rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let pendingStatusUpdate = null;

        function showStatusModal(bookingId, status, type) {
            if (!status) return;
            
            pendingStatusUpdate = { bookingId, status, type };
            document.getElementById('statusText').textContent = status.toUpperCase();
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
            // Reset the dropdown
            document.querySelectorAll('select').forEach(select => select.value = '');
            pendingStatusUpdate = null;
        }

        function confirmStatusUpdate() {
            if (!pendingStatusUpdate) return;
            
            const { bookingId, status, type } = pendingStatusUpdate;
            const url = '<?= site_url('admin/tour-booking/update-status/') ?>' + bookingId + '/' + status;
            
            fetch(url, { method: 'POST' })
                .then(() => {
                    closeStatusModal();
                    location.reload();
                });
        }

        // Close modal when clicking outside
        document.getElementById('statusModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeStatusModal();
        });
    </script>
</body>
</html>