<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Guests - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'partials/admin_nav.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Guest Management</h1>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <form action="<?= site_url('admin/guests') ?>" method="GET" class="flex gap-2">
                <input 
                    type="text" 
                    name="search" 
                    value="<?= html_escape($search_term ?? '') ?>" 
                    placeholder="Search by name or email..." 
                    class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500"
                >
                <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <?php if ($search_term): ?>
                    <a href="<?= site_url('admin/guests') ?>" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                        Clear
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Guests Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($guests)): ?>
                        <?php foreach ($guests as $guest): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $guest['id'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= html_escape($guest['first_name'] . ' ' . $guest['last_name']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= html_escape($guest['email']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= html_escape($guest['phone_number'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $guest['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' ?>">
                                        <?= ucfirst($guest['role']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= isset($guest['created_at']) ? date('M d, Y', strtotime($guest['created_at'])) : 'N/A' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="<?= site_url('admin/guest/view/' . $guest['id']) ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <?php if ($guest['role'] !== 'admin'): ?>
                                        <a href="<?= site_url('admin/guest/delete/' . $guest['id']) ?>" 
                                           class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('Are you sure you want to delete this guest?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No guests found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-gray-600">
            Total Guests: <strong><?= count($guests) ?></strong>
        </div>
    </div>
</body>
</html>
