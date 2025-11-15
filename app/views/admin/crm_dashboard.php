<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Dashboard - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'partials/admin_nav.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <i class="fas fa-users-cog mr-2 text-orange-600"></i>Guest Relationship Management (CRM)
        </h1>

        <!-- CRM Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Guests</p>
                        <p class="text-3xl font-bold mt-1"><?= number_format($stats['total_guests'] ?? 0) ?></p>
                    </div>
                    <div class="bg-blue-400 rounded-full p-3">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">VIP Guests</p>
                        <p class="text-3xl font-bold mt-1"><?= number_format($stats['vip_count'] ?? 0) ?></p>
                    </div>
                    <div class="bg-purple-400 rounded-full p-3">
                        <i class="fas fa-crown text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total Revenue</p>
                        <p class="text-3xl font-bold mt-1">₱<?= number_format($stats['total_revenue'] ?? 0, 0) ?></p>
                    </div>
                    <div class="bg-green-400 rounded-full p-3">
                        <i class="fas fa-dollar-sign text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Marketing Subscribers</p>
                        <p class="text-3xl font-bold mt-1"><?= number_format($stats['marketing_subscribers'] ?? 0) ?></p>
                    </div>
                    <div class="bg-orange-400 rounded-full p-3">
                        <i class="fas fa-envelope text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Tabs -->
        <div class="bg-white rounded-lg shadow-lg mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button onclick="showTab('vip')" id="tab-vip" class="tab-btn group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-center text-sm font-medium hover:bg-gray-50 border-b-2 border-orange-500">
                        <span class="text-orange-600"><i class="fas fa-crown mr-2"></i>VIP Guests</span>
                    </button>
                    <button onclick="showTab('regular')" id="tab-regular" class="tab-btn group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 border-b-2 border-transparent">
                        <span><i class="fas fa-star mr-2"></i>Regular Guests</span>
                    </button>
                    <button onclick="showTab('inactive')" id="tab-inactive" class="tab-btn group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 border-b-2 border-transparent">
                        <span><i class="fas fa-clock mr-2"></i>Inactive Guests</span>
                    </button>
                    <button onclick="showTab('birthday')" id="tab-birthday" class="tab-btn group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 border-b-2 border-transparent">
                        <span><i class="fas fa-birthday-cake mr-2"></i>Birthdays This Month</span>
                    </button>
                </nav>
            </div>

            <!-- VIP Guests Tab -->
            <div id="content-vip" class="tab-content p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">VIP Guests (₱50,000+ Spent)</h3>
                <?php if (!empty($vip_guests)): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visits</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Visit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($vip_guests as $guest): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <i class="fas fa-crown text-yellow-500 mr-2"></i>
                                                <span class="font-medium text-gray-900"><?= html_escape($guest['first_name'] . ' ' . $guest['last_name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?= html_escape($guest['email']) ?></td>
                                        <td class="px-6 py-4 text-sm font-bold text-green-600">₱<?= number_format($guest['total_revenue'] ?? 0, 2) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?= $guest['total_visits'] ?? 0 ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?= $guest['last_visit_date'] ? date('M d, Y', strtotime($guest['last_visit_date'])) : 'Never' ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="<?= site_url('admin/guest/view/' . $guest['id']) ?>" class="text-orange-600 hover:text-orange-900">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-8">No VIP guests yet.</p>
                <?php endif; ?>
            </div>

            <!-- Regular Guests Tab -->
            <div id="content-regular" class="tab-content p-6 hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Regular Guests (3+ Visits)</h3>
                <?php if (!empty($regular_guests)): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visits</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Visit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($regular_guests as $guest): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?= html_escape($guest['first_name'] . ' ' . $guest['last_name']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?= html_escape($guest['email']) ?></td>
                                        <td class="px-6 py-4 text-sm font-semibold text-green-600">₱<?= number_format($guest['total_revenue'] ?? 0, 2) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?= $guest['total_visits'] ?? 0 ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?= $guest['last_visit_date'] ? date('M d, Y', strtotime($guest['last_visit_date'])) : 'Never' ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="<?= site_url('admin/guest/view/' . $guest['id']) ?>" class="text-orange-600 hover:text-orange-900">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-8">No regular guests yet.</p>
                <?php endif; ?>
            </div>

            <!-- Inactive Guests Tab -->
            <div id="content-inactive" class="tab-content p-6 hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Inactive Guests (90+ Days)</h3>
                <p class="text-sm text-gray-600 mb-4">These guests haven't visited in over 90 days. Consider sending them a re-engagement offer!</p>
                <?php if (!empty($inactive_guests)): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Visit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Inactive</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lifetime Value</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($inactive_guests as $guest): ?>
                                    <?php 
                                    $last_visit = strtotime($guest['last_visit_date']);
                                    $days_inactive = floor((time() - $last_visit) / (60 * 60 * 24));
                                    ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?= html_escape($guest['first_name'] . ' ' . $guest['last_name']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?= html_escape($guest['email']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?= date('M d, Y', $last_visit) ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $days_inactive > 180 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                                <?= $days_inactive ?> days
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">₱<?= number_format($guest['total_revenue'] ?? 0, 2) ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="<?= site_url('admin/guest/view/' . $guest['id']) ?>" class="text-orange-600 hover:text-orange-900 mr-3">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                            <a href="mailto:<?= html_escape($guest['email']) ?>" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-envelope mr-1"></i>Email
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-8">No inactive guests found.</p>
                <?php endif; ?>
            </div>

            <!-- Birthday Guests Tab -->
            <div id="content-birthday" class="tab-content p-6 hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Birthdays This Month 🎂</h3>
                <p class="text-sm text-gray-600 mb-4">Send these guests a special birthday offer to show you care!</p>
                <?php if (!empty($birthday_guests)): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Birthday</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($birthday_guests as $guest): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <i class="fas fa-birthday-cake text-pink-500 mr-2"></i>
                                                <span class="font-medium text-gray-900"><?= html_escape($guest['first_name'] . ' ' . $guest['last_name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?= html_escape($guest['email']) ?></td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?= date('F d', strtotime($guest['birthday'])) ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $guest['guest_type'] === 'vip' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' ?>">
                                                <?= ucfirst($guest['guest_type']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="mailto:<?= html_escape($guest['email']) ?>" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-envelope mr-1"></i>Send Greeting
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-8">No birthdays this month.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="<?= site_url('admin/guests') ?>" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">View All Guests</h3>
                        <p class="text-sm text-gray-600">Browse complete guest list</p>
                    </div>
                </div>
            </a>

            <a href="<?= site_url('admin/crm/export-marketing-list') ?>" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <i class="fas fa-download text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Export Marketing List</h3>
                        <p class="text-sm text-gray-600">Download subscriber emails</p>
                    </div>
                </div>
            </a>

            <a href="<?= site_url('admin/bookings') ?>" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center">
                    <div class="bg-orange-100 rounded-full p-3 mr-4">
                        <i class="fas fa-calendar-alt text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Manage Bookings</h3>
                        <p class="text-sm text-gray-600">View all reservations</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active state from all tabs
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-orange-500', 'text-orange-600');
                btn.classList.add('border-transparent', 'text-gray-500');
                btn.querySelector('span').classList.remove('text-orange-600');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Add active state to clicked tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.add('border-orange-500');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.querySelector('span').classList.add('text-orange-600');
        }
    </script>
</body>
</html>
