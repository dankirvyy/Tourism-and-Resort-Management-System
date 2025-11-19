<!-- Mark as Paid Confirmation Modal -->
<div id="markPaidModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mx-auto">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Mark Invoice as Paid</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 text-center">
                    Are you sure you want to mark this invoice as paid?
                </p>
            </div>
            <div class="flex gap-4 px-4 py-3">
                <button onclick="closeMarkPaidModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <button onclick="confirmMarkPaid()" class="flex-1 px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Mark as Paid
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let pendingInvoiceId = null;

    function showMarkPaidModal(invoiceId) {
        pendingInvoiceId = invoiceId;
        document.getElementById('markPaidModal').classList.remove('hidden');
    }

    function closeMarkPaidModal() {
        document.getElementById('markPaidModal').classList.add('hidden');
        pendingInvoiceId = null;
    }

    function confirmMarkPaid() {
        if (!pendingInvoiceId) return;
        window.location.href = '<?= site_url("admin/invoices/mark-paid/") ?>' + pendingInvoiceId;
    }

    // Close modal when clicking outside
    document.getElementById('markPaidModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeMarkPaidModal();
    });
</script>
