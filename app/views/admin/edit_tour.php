<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Tour</title>
    <link href="<?= site_url('public/css/output.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-orange-600"></i> Pin Tour Location on Map (Optional)
                                </label>
                                <p class="text-xs text-gray-500 mb-2">Click on the map to set the tour location. The coordinates will be saved automatically.</p>
                                <div id="map" class="w-full h-80 rounded-lg border-2 border-gray-300 shadow-sm"></div>
                                <input type="hidden" name="latitude" id="latitude" value="<?= html_escape($tour['latitude'] ?? ''); ?>">
                                <input type="hidden" name="longitude" id="longitude" value="<?= html_escape($tour['longitude'] ?? ''); ?>">
                                <p id="coordinates-display" class="mt-2 text-sm text-gray-600"></p>
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
    
    <script>
        // Get existing coordinates
        const existingLat = document.getElementById('latitude').value;
        const existingLng = document.getElementById('longitude').value;
        
        // Initialize map centered on existing location or Mindoro
        const initialLat = existingLat ? parseFloat(existingLat) : 13.0;
        const initialLng = existingLng ? parseFloat(existingLng) : 121.2;
        const initialZoom = existingLat ? 13 : 9;
        
        const map = L.map('map').setView([initialLat, initialLng], initialZoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        let marker = null;
        
        // Add existing marker if coordinates exist
        if (existingLat && existingLng) {
            marker = L.marker([existingLat, existingLng]).addTo(map)
                .bindPopup('Current Tour Location<br>Lat: ' + existingLat + '<br>Lng: ' + existingLng);
            document.getElementById('coordinates-display').innerHTML = 
                '<i class="fas fa-check-circle text-green-600"></i> Current location: <strong>' + existingLat + ', ' + existingLng + '</strong>';
        } else {
            document.getElementById('coordinates-display').innerHTML = 'Click on the map to select coordinates';
        }
        
        // Handle map clicks
        map.on('click', function(e) {
            const lat = e.latlng.lat.toFixed(8);
            const lng = e.latlng.lng.toFixed(8);
            
            // Update hidden inputs
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            
            // Update display
            document.getElementById('coordinates-display').innerHTML = 
                '<i class="fas fa-check-circle text-green-600"></i> Location pinned: <strong>' + lat + ', ' + lng + '</strong>';
            
            // Remove existing marker if any
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Add new marker
            marker = L.marker([lat, lng]).addTo(map)
                .bindPopup('Selected Tour Location<br>Lat: ' + lat + '<br>Lng: ' + lng)
                .openPopup();
        });
    </script>
</body>
</html>