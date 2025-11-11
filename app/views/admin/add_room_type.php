<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Room Type</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Room Type</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Define a new category of room for your resort.</p>

                <?php if ($this->session->flashdata('upload_error')): ?>
                    <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Upload Error:</strong>
                        <span class="block sm:inline"><?= $this->session->flashdata('upload_error'); ?></span>
                    </div>
                <?php endif; ?>

                <div class="mt-5">
                    <form action="<?= site_url('admin/room-types/save') ?>" method="post" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Type Name</label>
                                <input type="text" name="name" id="name" placeholder="e.g., Deluxe King" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"></textarea>
                            </div>
                            <div>
                                <label for="base_price" class="block text-sm font-medium text-gray-700">Base Price (PHP)</label>
                                <input type="number" step="0.01" name="base_price" id="base_price" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity (Max persons)</label>
                                <input type="number" name="capacity" id="capacity" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                             <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                                <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/gif" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                <p class="mt-1 text-xs text-gray-500">Optional. Upload an image for this room type.</p>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="<?= site_url('admin/room-types') ?>" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-orange-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-orange-700">Save Room Type</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>