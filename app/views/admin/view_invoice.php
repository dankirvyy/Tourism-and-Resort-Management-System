<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= html_escape($invoice['id']) ?> - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
            .print-container {
                padding: 0;
                margin: 0;
                box-shadow: none;
                border: none;
            }
            .print-body {
                background-color: #ffffff;
            }
        }
    </style>
</head>
<body class="bg-gray-100 print-body">
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8 print-container">
        <div class="mb-8 pb-4 border-b border-gray-300 no-print">
            <div class="flex justify-between items-center">
                 <a href="<?= site_url('admin/bookings') ?>" class="text-sm font-medium text-gray-500 hover:text-orange-600 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Room Bookings
                </a>
                <div class="flex items-center gap-x-4">
                     <button onclick="window.print()" class="inline-flex items-center gap-x-2 rounded-md bg-gray-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                        <svg class="-ml-0.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6 13.828M6 13.828l-1.12 3.136a1.12 1.12 0 001.07 1.498l1.07-1.498M6 13.828l1.12-3.136m0 0a1.12 1.12 0 011.498-1.07l3.136 1.12m-4.634 0l1.12 3.136m6.72-3.136a1.12 1.12 0 00-1.07-1.498L18 13.828m0 0l1.12 3.136a1.12 1.12 0 01-1.498 1.07l-3.136-1.12m4.634 0l-1.12 3.136" /></svg>
                        Print Invoice
                    </button>
                    <a href="<?= site_url('admin/logout') ?>" class="text-sm font-semibold text-red-600 hover:text-red-800">Logout</a>
                </div>
            </div>
        </div>
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-orange-600">Visit Mindoro</h1>
                        <p class="text-sm text-gray-500">Invoice / Guest Folio</p>
                    </div>
                    <div class="text-right">
                        <h2 class="text-2xl font-bold text-gray-900">INVOICE</h2>
                        <p class="text-sm text-gray-500">#<?= html_escape($invoice['id']) ?></p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Billed To:</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            <?= html_escape($invoice['first_name'] . ' ' . $invoice['last_name']) ?><br>
                            <?= html_escape($invoice['email']) ?>
                        </p>
                    </div>
                    <div class="text-left md:text-right">
                         <h3 class="text-lg font-semibold text-gray-900">Invoice Details:</h3>
                         <p class="mt-2 text-sm text-gray-600">
                            <strong>Issue Date:</strong> <?= date('F j, Y', strtotime($invoice['issue_date'])) ?><br>
                            <strong>Status:</strong> <span class="font-medium text-lg <?= $invoice['status'] == 'paid' ? 'text-green-600' : 'text-red-600' ?>"><?= strtoupper(html_escape($invoice['status'])) ?></span>
                         </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Description</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Qty</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Unit Price</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <?php if (!empty($invoice['items'])): ?>
                                        <?php foreach ($invoice['items'] as $item): ?>
                                            <tr>
                                                <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"><?= html_escape($item['description']); ?></td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center"><?= html_escape($item['quantity']); ?></td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱<?= number_format($item['unit_price'], 2); ?></td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱<?= number_format($item['total_price'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="py-4 text-center text-gray-500">No items found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-4 py-5 sm:p-6 bg-gray-50 flex justify-end">
                <div class="w-full max-w-xs">
                     <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Subtotal</dt>
                            <dd class="text-sm font-medium text-gray-900">₱<?= number_format($invoice['total_amount'], 2); ?></dd>
                        </div>
                         <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Taxes (0%)</dt>
                            <dd class="text-sm font-medium text-gray-900">₱0.00</dd>
                        </div>
                         <div class="flex justify-between border-t border-gray-200 pt-4">
                            <dt class="text-lg font-bold text-gray-900">Total Amount</dt>
                            <dd class="text-lg font-bold text-orange-600">₱<?= number_format($invoice['total_amount'], 2); ?></dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            </div>
        </div>
</body>
</html>