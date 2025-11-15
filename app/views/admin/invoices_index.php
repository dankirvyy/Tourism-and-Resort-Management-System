<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Invoices & Billing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'partials/admin_nav.php'; ?>

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-8 pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-file-invoice-dollar mr-2 text-orange-600"></i>Invoices & Billing
            </h1>
            <p class="text-gray-600 mt-2">Manage all guest invoices and track payments</p>
        </div>

        <!-- Invoice Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Invoices</p>
                        <p class="text-3xl font-bold mt-1"><?= number_format($stats['total_invoices'] ?? 0) ?></p>
                    </div>
                    <div class="bg-blue-400 rounded-full p-3">
                        <i class="fas fa-file-invoice text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Paid</p>
                        <p class="text-3xl font-bold mt-1"><?= number_format($stats['paid_invoices'] ?? 0) ?></p>
                    </div>
                    <div class="bg-green-400 rounded-full p-3">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Unpaid</p>
                        <p class="text-3xl font-bold mt-1"><?= number_format($stats['unpaid_invoices'] ?? 0) ?></p>
                    </div>
                    <div class="bg-red-400 rounded-full p-3">
                        <i class="fas fa-exclamation-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Revenue</p>
                        <p class="text-2xl font-bold mt-1">₱<?= number_format($stats['total_revenue'] ?? 0, 0) ?></p>
                    </div>
                    <div class="bg-purple-400 rounded-full p-3">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Outstanding</p>
                        <p class="text-2xl font-bold mt-1">₱<?= number_format($stats['outstanding_amount'] ?? 0, 0) ?></p>
                    </div>
                    <div class="bg-orange-400 rounded-full p-3">
                        <i class="fas fa-hourglass-half text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- Invoices Table -->
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex flex-col">
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Invoice ID</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Guest</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Room</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Issue Date</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Due Date</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Amount</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <?php if (!empty($invoices)): ?>
                                            <?php foreach ($invoices as $invoice): ?>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                        #<?= html_escape($invoice['id']); ?>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        <?= html_escape($invoice['first_name'] . ' ' . $invoice['last_name']); ?>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        <?= html_escape($invoice['room_number'] ?? 'N/A') . ' (' . html_escape($invoice['room_type_name'] ?? 'N/A') . ')'; ?>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        <?= date('M d, Y', strtotime($invoice['issue_date'])); ?>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        <?= date('M d, Y', strtotime($invoice['due_date'])); ?>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-gray-900">
                                                        ₱<?= number_format($invoice['total_amount'], 2); ?>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                        <?php 
                                                        $status_colors = [
                                                            'paid' => 'bg-green-100 text-green-800',
                                                            'unpaid' => 'bg-red-100 text-red-800',
                                                            'partial' => 'bg-yellow-100 text-yellow-800',
                                                            'overdue' => 'bg-orange-100 text-orange-800'
                                                        ];
                                                        $color = $status_colors[$invoice['status']] ?? 'bg-gray-100 text-gray-800';
                                                        ?>
                                                        <span class="px-2 py-1 rounded-full text-xs font-medium <?= $color ?>">
                                                            <?= ucfirst(html_escape($invoice['status'])); ?>
                                                        </span>
                                                    </td>
                                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <a href="<?= site_url('admin/invoice/view/' . $invoice['id']) ?>" 
                                                           class="text-orange-600 hover:text-orange-900 mr-3">
                                                            <i class="fas fa-eye mr-1"></i>View
                                                        </a>
                                                        <a href="<?= site_url('admin/invoice/download/' . $invoice['id']) ?>" 
                                                           class="text-blue-600 hover:text-blue-900 mr-3">
                                                            <i class="fas fa-download mr-1"></i>PDF
                                                        </a>
                                                        <?php if ($invoice['status'] === 'unpaid'): ?>
                                                            <a href="<?= site_url('admin/invoice/mark-paid/' . $invoice['id']) ?>" 
                                                               class="text-green-600 hover:text-green-900"
                                                               onclick="return confirm('Mark this invoice as paid?')">
                                                                <i class="fas fa-check mr-1"></i>Mark Paid
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                                    No invoices found.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
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
