<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Resource</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Resource</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Add a new guide, vehicle, boat, or other resource.</p>
                <div class="mt-5">
                    <form action="<?= site_url('admin/resources/save') ?>" method="post">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Resource Name</label>
                                <input type="text" name="name" id="name" placeholder="e.g., John Doe (Guide) or Tour Van Alpha" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Resource Type</label>
                                <select id="type" name="type" required class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-orange-500 focus:outline-none focus:ring-orange-500 sm:text-sm">
                                    <option disabled selected>Select type...</option>
                                    <option value="Guide">Guide</option>
                                    <option value="Vehicle">Vehicle</option>
                                    <option value="Boat">Boat</option>
                                    <option value="Equipment">Equipment</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                             <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity (Optional)</label>
                                <input type="number" name="capacity" id="capacity" placeholder="e.g., number of seats/people" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">Leave blank if capacity is not applicable (e.g., for a guide).</p>
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">For named resources (e.g., "Juan - Guide"), use 1. For countable items (e.g., "Tour Van"), enter total number available.</p>
                            </div>

                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                                <div class="flex">
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            <strong>How quantity works:</strong><br>
                                            • <strong>Named resources (Guides):</strong> Set quantity to 1. When assigned, they become unavailable.<br>
                                            • <strong>Countable resources (Vehicles, Boats):</strong> Set quantity to total number (e.g., 5 cars). They become unavailable when quantity reaches 0.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="<?= site_url('admin/resources') ?>" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-orange-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-orange-700">Save Resource</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>