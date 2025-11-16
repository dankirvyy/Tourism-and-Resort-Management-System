<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resource Scheduling Calendar - Admin</title>
    <link href="<?= site_url('public/css/output.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Lexend', sans-serif;
        }
        
        .calendar-wrapper {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .calendar-nav button {
            background: #f3f4f6;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            margin: 0 4px;
        }
        
        .calendar-nav button:hover {
            background: #e5e7eb;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #e5e7eb;
            border: 1px solid #e5e7eb;
        }
        
        .calendar-day-header {
            background: #f9fafb;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            color: #6b7280;
        }
        
        .calendar-day {
            background: white;
            min-height: 100px;
            padding: 8px;
            position: relative;
        }
        
        .calendar-day.other-month {
            background: #f9fafb;
            opacity: 0.5;
        }
        
        .calendar-day.today {
            background: #fef3c7;
        }
        
        .day-number {
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }
        
        .event {
            background: #10b981;
            color: white;
            padding: 4px 8px;
            margin: 2px 0;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .event:hover {
            opacity: 0.8;
        }
        
        .event.guide { background: #3b82f6; }
        .event.vehicle { background: #10b981; }
        .event.boat { background: #06b6d4; }
        .event.equipment { background: #8b5cf6; }
        .event.other { background: #6b7280; }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            padding: 24px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-gray-100 font-['Lexend']">
    <?php include 'partials/admin_nav.php'; ?>
    
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-1">Resource Scheduling</h1>
            <p class="text-lg text-gray-500">View and manage resource availability and assignments</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label for="resourceTypeFilter" class="block text-sm font-medium text-gray-700 mb-2">Resource Type</label>
                    <select id="resourceTypeFilter" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 text-gray-900 bg-white px-3 py-2">
                        <option value="">All Resources</option>
                        <option value="Guide">Guides</option>
                        <option value="Vehicle">Vehicles</option>
                        <option value="Boat">Boats</option>
                        <option value="Equipment">Equipment</option>
                    </select>
                </div>
                <div>
                    <label for="monthFilter" class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                    <select id="monthFilter" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 text-gray-900 bg-white px-3 py-2">
                        <?php
                        $currentMonth = date('m');
                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                        foreach ($months as $index => $month):
                            $selected = ($index + 1) == $currentMonth ? 'selected' : '';
                        ?>
                            <option value="<?= $index + 1 ?>" <?= $selected ?>><?= $month ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="yearFilter" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select id="yearFilter" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 text-gray-900 bg-white px-3 py-2">
                        <?php
                        $currentYear = date('Y');
                        for ($y = $currentYear - 1; $y <= $currentYear + 2; $y++):
                            $selected = $y == $currentYear ? 'selected' : '';
                        ?>
                            <option value="<?= $y ?>" <?= $selected ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button onclick="applyFilters()" style="background-color: #111827; color: #ffffff; font-size: 16px; font-weight: 500;" class="w-full px-4 py-2.5 rounded-lg hover:bg-gray-700 transition-all duration-300 shadow-md">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Available Resources</p>
                        <p class="text-3xl font-semibold tracking-tight text-gray-900"><?= $stats['available'] ?? 0 ?></p>
                    </div>
                    <div class="bg-green-500/10 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Resources</p>
                        <p class="text-3xl font-semibold tracking-tight text-gray-900"><?= $stats['total'] ?? 0 ?></p>
                    </div>
                    <div class="bg-blue-500/10 p-3 rounded-full">
                        <i class="fas fa-layer-group text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Scheduled This Month</p>
                        <p class="text-3xl font-semibold tracking-tight text-gray-900"><?= $stats['scheduled_month'] ?? 0 ?></p>
                    </div>
                    <div class="bg-yellow-500/10 p-3 rounded-full">
                        <i class="fas fa-calendar-alt text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Utilization Rate</p>
                        <p class="text-3xl font-semibold tracking-tight text-gray-900"><?= round($stats['utilization'] ?? 0, 1) ?>%</p>
                    </div>
                    <div class="bg-orange-500/10 p-3 rounded-full">
                        <i class="fas fa-chart-pie text-orange-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="calendar-wrapper">
            <div class="calendar-header">
                <h2 class="text-xl font-semibold text-gray-900" id="calendarTitle">
                    <?php
                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    $current_month_name = $months[((int)date('m')) - 1];
                    $current_year_name = date('Y');
                    echo $current_month_name . ' ' . $current_year_name;
                    ?>
                </h2>
                <div class="calendar-nav">
                    <button onclick="changeMonth(-1)"><i class="fas fa-chevron-left"></i> Prev</button>
                    <button onclick="goToToday()">Today</button>
                    <button onclick="changeMonth(1)">Next <i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            
            <div class="p-6">
                <div class="calendar-grid">
                    <div class="calendar-day-header">Sun</div>
                    <div class="calendar-day-header">Mon</div>
                    <div class="calendar-day-header">Tue</div>
                    <div class="calendar-day-header">Wed</div>
                    <div class="calendar-day-header">Thu</div>
                    <div class="calendar-day-header">Fri</div>
                    <div class="calendar-day-header">Sat</div>
                    
                    <?php foreach ($calendar_days as $day): ?>
                        <div class="calendar-day <?= $day['is_today'] ? 'today' : '' ?> <?= $day['is_other_month'] ? 'other-month' : '' ?>">
                            <div class="day-number"><?= $day['day'] ?></div>
                            <div class="events">
                                <?php if (!empty($day['schedules'])): ?>
                                    <?php foreach ($day['schedules'] as $schedule): ?>
                                        <div class="event <?= strtolower($schedule['resource_type']) ?>" 
                                             onclick='showEventDetails(<?= json_encode($schedule) ?>)'>
                                            <i class="fas fa-<?= $schedule['icon'] ?>"></i>
                                            <span><?= htmlspecialchars($schedule['resource_name']) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">All Resources</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Resource</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Capacity</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Next Booking</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Utilization</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php if (!empty($resources)): ?>
                            <?php foreach ($resources as $resource): ?>
                                <tr class="hover:bg-orange-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($resource['name']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            <?= htmlspecialchars($resource['type']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <?= $resource['capacity'] ?? 'N/A' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($resource['is_available']): ?>
                                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 flex items-center w-fit">
                                                <i class="fas fa-check-circle mr-1.5"></i>Available
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 flex items-center w-fit">
                                                <i class="fas fa-times-circle mr-1.5"></i>Unavailable
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <?= $resource['next_booking'] ?? 'No upcoming bookings' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-3">
                                                <div class="bg-orange-600 h-2.5 rounded-full" style="width: <?= $resource['utilization'] ?? 0 ?>%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-600"><?= round($resource['utilization'] ?? 0) ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No resources found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div id="eventModal" class="modal" onclick="closeModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Schedule Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>
            
            <div id="eventDetails" class="space-y-4">
            </div>
            
            <div class="mt-6">
                <button onclick="closeModal()" class="w-full px-4 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentMonth = <?= (int)date('m') ?>;
        let currentYear = <?= (int)date('Y') ?>;
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        function updateCalendarTitle() {
            document.getElementById('calendarTitle').textContent = monthNames[currentMonth - 1] + ' ' + currentYear;
        }
        
        function changeMonth(delta) {
            currentMonth += delta;
            if (currentMonth > 12) {
                currentMonth = 1;
                currentYear++;
            } else if (currentMonth < 1) {
                currentMonth = 12;
                currentYear--;
            }
            
            // Update dropdowns to match
            document.getElementById('monthFilter').value = currentMonth;
            document.getElementById('yearFilter').value = currentYear;
            
            updateCalendarTitle();
            applyFilters();
        }
        
        function goToToday() {
            currentMonth = <?= (int)date('m') ?>;
            currentYear = <?= (int)date('Y') ?>;
            
            // Update dropdowns to match
            document.getElementById('monthFilter').value = currentMonth;
            document.getElementById('yearFilter').value = currentYear;
            
            updateCalendarTitle();
            applyFilters();
        }
        
        function applyFilters() {
            const type = document.getElementById('resourceTypeFilter').value;
            const month = document.getElementById('monthFilter').value || currentMonth;
            const year = document.getElementById('yearFilter').value || currentYear;
            window.location.href = `<?= site_url('admin/resources/calendar') ?>?type=${type}&month=${month}&year=${year}`;
        }
        
        function showEventDetails(event) {
            const modal = document.getElementById('eventModal');
            const details = document.getElementById('eventDetails');
            
            details.innerHTML = `
                <div class="space-y-5">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Resource</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-${event.icon} mr-2.5 text-orange-600" style="width: 20px;"></i>
                            ${event.resource_name}
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tour</p>
                            <p class="mt-1 font-medium text-gray-800">${event.tour_name}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Guest</p>
                            <p class="mt-1 font-medium text-gray-800">${event.guest_name}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-sm font-medium text-gray-500">Date & Time</p>
                        <p class="mt-1 font-medium text-gray-800">${event.booking_date}</p>
                        <p class="mt-1 text-sm text-gray-600">${event.start_time} - ${event.end_time}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <p class="mt-1 font-medium text-gray-800 capitalize">${event.status}</p>
                    </div>
                </div>
            `;
            
            modal.classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('eventModal').classList.remove('active');
        }
    </script>
</body>
</html>