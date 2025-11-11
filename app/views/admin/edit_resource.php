<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Resource</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Resource</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Update the details for this resource.</p>
                <div class="mt-5">
                    <form action="<?= site_url('admin/resources/update') ?>" method="post">
                        <input type="hidden" name="id" value="<?= html_escape($resource['id']); ?>">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Resource Name</label>
                                <input type="text" name="name" id="name" value="<?= html_escape($resource['name']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Resource Type</label>
                                <select id="type" name="type" required class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-orange-500 focus:outline-none focus:ring-orange-500 sm:text-sm">
                                    <option value="Guide" <?= ($resource['type'] == 'Guide') ? 'selected' : ''; ?>>Guide</option>
                                    <option value="Vehicle" <?= ($resource['type'] == 'Vehicle') ? 'selected' : ''; ?>>Vehicle</option>
                                    <option value="Boat" <?= ($resource['type'] == 'Boat') ? 'selected' : ''; ?>>Boat</option>
                                    <option value="Equipment" <?= ($resource['type'] == 'Equipment') ? 'selected' : ''; ?>>Equipment</option>
                                    <option value="Other" <?= ($resource['type'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                             <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity (Optional)</label>
                                <input type="number" name="capacity" id="capacity" value="<?= html_escape($resource['capacity'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">Leave blank if capacity is not applicable.</p>
                            </div>

                            <div class="relative flex items-start">
                                <div class="flex h-5 items-center">
                                     <input id="is_available" name="is_available" type="checkbox" value="1" <?= $resource['is_available'] ? 'checked' : ''; ?> class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_available" class="font-medium text-gray-700">Is Currently Available?</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="<?= site_url('admin/resources') ?>" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-orange-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-orange-700">Update Resource</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>