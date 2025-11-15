<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Booking Management</title>
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
             <h1 class="mt-4 text-3xl font-bold text-gray-800">Manage Bookings</h1>
        </div>
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
             <div class="px-4 py-5 sm:p-6">
                 <div class="sm:flex sm:items-center sm:justify-between mb-6">
                      <p class="text-sm text-gray-600">A list of all room reservations in the system.</p>
                     <div class="mt-3 sm:mt-0 sm:ml-4">
                         <a href="<?= site_url('admin/bookings/add') ?>" class="inline-flex items-center justify-center rounded-md border border-transparent bg-orange-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-orange-700">
                             Add New Booking
                        </a>
                    </div>
                </div>

                 <div class="flex flex-col">
                     <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                         <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                             <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                 <table class="min-w-full divide-y divide-gray-300">
                                     <thead class="bg-gray-50">
                                         <tr>
                                             <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Guest Name</th>
                                             <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Room</th>
                                             <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Check-in</th>
                                             <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Check-out</th>
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
                                         <?php foreach ($bookings as $booking): ?>
                                             <tr>
                                                 <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"><?= html_escape($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                                 <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"><?= html_escape($booking['room_number'] . ' (' . $booking['room_type_name'] . ')'); ?></td>
                                                 <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"><?= html_escape($booking['check_in_date']); ?></td>
                                                 <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"><?= html_escape($booking['check_out_date']); ?></td>
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
                                                     <!-- Quick Status Update Dropdown -->
                                                     <select onchange="if(this.value && confirm('Update booking status?')) { fetch('<?= site_url('admin/booking/update-status/' . $booking['id']) ?>/' + this.value, {method: 'POST'}).then(() => location.reload()); }" class="inline-block mr-2 text-xs border rounded px-2 py-1">
                                                         <option value="">Quick Status</option>
                                                         <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'disabled' : '' ?>>Confirmed</option>
                                                         <option value="completed" <?= $booking['status'] === 'completed' ? 'disabled' : '' ?>>Completed</option>
                                                         <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'disabled' : '' ?>>Cancelled</option>
                                                     </select>
                                                     <a href="<?= site_url('admin/invoice/view/' . $booking['id']) ?>" class="text-orange-600 hover:text-orange-900">Invoice</a>
                                                     <a href="<?= site_url('admin/bookings/delete/' . $booking['id']) ?>" class="text-red-600 hover:text-red-900 ml-4" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
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
</body>
</html>