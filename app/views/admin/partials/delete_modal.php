<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mx-auto">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mt-4" id="deleteModalTitle">Confirm Delete</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 text-center" id="deleteModalMessage">
                    Are you sure you want to delete this item? This action cannot be undone.
                </p>
            </div>
            <div class="flex gap-4 px-4 py-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <button onclick="confirmDelete()" class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let pendingDelete = null;

    function showDeleteModal(type, id, customMessage) {
        pendingDelete = { type, id };
        
        // Customize message based on type
        const messages = {
            'booking': 'Are you sure you want to delete this booking? This action cannot be undone.',
            'tour': 'Are you sure you want to delete this tour? This action cannot be undone.',
            'room': 'Are you sure you want to delete this room? This action cannot be undone.',
            'room-type': 'Are you sure you want to delete this room type? This will also delete all associated rooms.',
            'resource': 'Are you sure you want to delete this resource? This action cannot be undone.',
            'guest': 'Are you sure you want to delete this guest? This action cannot be undone.'
        };
        
        document.getElementById('deleteModalMessage').textContent = customMessage || messages[type] || 'Are you sure you want to delete this item?';
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        pendingDelete = null;
    }

    function confirmDelete() {
        if (!pendingDelete) return;
        
        const { type, id } = pendingDelete;
        const urls = {
            'booking': '<?= site_url("admin/bookings/delete/") ?>',
            'tour': '<?= site_url("admin/delete/") ?>',
            'room': '<?= site_url("admin/rooms/delete/") ?>',
            'room-type': '<?= site_url("admin/room-types/delete/") ?>',
            'resource': '<?= site_url("admin/resources/delete/") ?>',
            'guest': '<?= site_url("admin/guests/delete/") ?>'
        };
        
        window.location.href = urls[type] + id;
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
</script>
