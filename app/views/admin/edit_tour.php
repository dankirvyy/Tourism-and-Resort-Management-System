<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Tour</title>
    <link href="<?= site_url('public/css/output.css') ?>" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Tour Package</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Update the details for this tour.</p>

                <?php if ($this->session->flashdata('upload_error')): ?>
                    <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Upload Error:</strong>
                        <span class="block sm:inline"><?= $this->session->flashdata('upload_error'); ?></span>
                    </div>
                <?php endif; ?>

                <div class="mt-5">
                    <form action="<?= site_url('admin/update_tour') ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= html_escape($tour['id']); ?>">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Tour Name</label>
                                <input type="text" name="name" id="name" value="<?= html_escape($tour['name']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"><?= html_escape($tour['description']); ?></textarea>
                            </div>
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Price (PHP)</label>
                                <input type="number" step="0.01" name="price" id="price" value="<?= html_escape($tour['price']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700">Duration</label>
                                <input type="text" name="duration" id="duration" value="<?= html_escape($tour['duration']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude (Optional)</label>
                                    <input type="text" name="latitude" id="latitude" value="<?= html_escape($tour['latitude'] ?? ''); ?>" placeholder="e.g., 13.4007" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude (Optional)</label>
                                    <input type="text" name="longitude" id="longitude" value="<?= html_escape($tour['longitude'] ?? ''); ?>" placeholder="e.g., 121.2250" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                                <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/gif" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                <?php if (isset($tour['image_filename']) && $tour['image_filename']): ?>
                                    <p class="mt-2 text-xs text-gray-500">Current Image:</p>
                                    <img src="<?= base_url('public/uploads/images/' . $tour['image_filename']) ?>" alt="Current Image" class="mt-1 h-20 w-auto rounded">
                                <?php endif; ?>
                                <p class="mt-1 text-xs text-gray-500">Leave blank to keep existing image.</p>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="<?= site_url('admin/tours') ?>" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-orange-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-orange-700">Update Tour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>