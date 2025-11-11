<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Admin extends Controller {

    public function __construct() {
        parent::__construct();

        // Check if an ADMIN is logged in
        if (!$this->session->has_userdata('admin_user_id')) { 
            redirect('/login');
        }

        $this->call->model('Tour_model');
        $this->call->model('Room_type_model');
        $this->call->model('Room_model');
        $this->call->model('Booking_model'); 
        $this->call->model('Guest_model');
        $this->call->model('Resource_model');
        $this->call->model('Tour_booking_model');
        $this->call->model('Resource_schedule_model');
        $this->call->model('Invoice_model'); 
        $this->call->model('Invoice_item_model');
    }

    // ---------------------------------------------------
    // GUEST/USER MANAGEMENT (NEW)
    // ---------------------------------------------------

    /**
     * Show list of all registered guests/users
     */
    public function guests() {
        // Get search parameter
        $search = $this->io->get('search');
        
        if ($search) {
            $data['guests'] = $this->db->table('guests')
                ->like('first_name', '%' . $search . '%')
                ->or_like('last_name', '%' . $search . '%')
                ->or_like('email', '%' . $search . '%')
                ->order_by('created_at', 'DESC')
                ->get_all();
        } else {
            $data['guests'] = $this->db->table('guests')
                ->order_by('created_at', 'DESC')
                ->get_all();
        }
        
        $data['search_term'] = $search;
        $data['active_page'] = 'guests';
        $this->call->view('admin/guests_index', $data);
    }

    /**
     * View detailed information about a specific guest
     */
    public function view_guest($id) {
        $data['guest'] = $this->Guest_model->find($id);
        
        if (!$data['guest']) {
            redirect('/admin/guests');
        }
        
        // Get all bookings for this guest
        $data['room_bookings'] = $this->Booking_model->get_bookings_by_guest($id);
        $data['tour_bookings'] = $this->Tour_booking_model->get_tour_bookings_by_guest($id);
        
        // Calculate total spent
        $total_room = $this->db->table('bookings')
            ->where('guest_id', $id)
            ->where('status', 'confirmed')
            ->select('SUM(total_price) as total')
            ->get();
        
        $total_tour = $this->db->table('tour_bookings')
            ->where('guest_id', $id)
            ->where('status', 'confirmed')
            ->select('SUM(total_price) as total')
            ->get();
        
        $data['total_spent'] = ($total_room['total'] ?? 0) + ($total_tour['total'] ?? 0);
        
        $this->call->view('admin/view_guest', $data);
    }

    /**
     * Delete a guest (with confirmation)
     */
    public function delete_guest($id) {
        // Check if guest has any bookings
        $has_bookings = $this->db->table('bookings')
            ->where('guest_id', $id)
            ->count() > 0;
        
        $has_tour_bookings = $this->db->table('tour_bookings')
            ->where('guest_id', $id)
            ->count() > 0;
        
        if ($has_bookings || $has_tour_bookings) {
            $this->session->set_flashdata('error', 'Cannot delete guest with existing bookings. Cancel bookings first.');
        } else {
            $this->Guest_model->delete($id);
            $this->session->set_flashdata('success', 'Guest deleted successfully.');
        }
        
        redirect('/admin/guests');
    }

    // ---------------------------------------------------
    // REPORTS & ANALYTICS (NEW)
    // ---------------------------------------------------

    /**
     * Revenue and analytics reports
     */
    public function reports() {
        // Get date range from query params or default to last 30 days
        $start_date = $this->io->get('start_date') ?: date('Y-m-01'); // First day of current month
        $end_date = $this->io->get('end_date') ?: date('Y-m-d'); // Today
        
        // Room booking revenue
        $room_revenue = $this->db->raw(
            "SELECT SUM(total_price) as total 
             FROM bookings 
             WHERE status IN ('confirmed', 'completed') 
             AND check_in_date BETWEEN ? AND ?",
            [$start_date, $end_date]
        )->fetch(PDO::FETCH_ASSOC);
        
        // Tour booking revenue
        $tour_revenue = $this->db->raw(
            "SELECT SUM(total_price) as total 
             FROM tour_bookings 
             WHERE status IN ('confirmed', 'completed') 
             AND booking_date BETWEEN ? AND ?",
            [$start_date, $end_date]
        )->fetch(PDO::FETCH_ASSOC);
        
        // Booking statistics
        $booking_stats = $this->db->raw(
            "SELECT 
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
             FROM bookings 
             WHERE check_in_date BETWEEN ? AND ?",
            [$start_date, $end_date]
        )->fetch(PDO::FETCH_ASSOC);
        
        // Most popular room types
        $popular_rooms = $this->db->raw(
            "SELECT rt.name, COUNT(b.id) as booking_count, SUM(b.total_price) as revenue
             FROM bookings b
             JOIN rooms r ON b.room_id = r.id
             JOIN room_types rt ON r.room_type_id = rt.id
             WHERE b.status IN ('confirmed', 'completed')
             AND b.check_in_date BETWEEN ? AND ?
             GROUP BY rt.id
             ORDER BY booking_count DESC
             LIMIT 5",
            [$start_date, $end_date]
        )->fetchAll(PDO::FETCH_ASSOC);
        
        // Most popular tours
        $popular_tours = $this->db->raw(
            "SELECT t.name, COUNT(tb.id) as booking_count, SUM(tb.total_price) as revenue
             FROM tour_bookings tb
             JOIN tours t ON tb.tour_id = t.id
             WHERE tb.status IN ('confirmed', 'completed')
             AND tb.booking_date BETWEEN ? AND ?
             GROUP BY t.id
             ORDER BY booking_count DESC
             LIMIT 5",
            [$start_date, $end_date]
        )->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'room_revenue' => $room_revenue['total'] ?? 0,
            'tour_revenue' => $tour_revenue['total'] ?? 0,
            'total_revenue' => ($room_revenue['total'] ?? 0) + ($tour_revenue['total'] ?? 0),
            'booking_stats' => $booking_stats,
            'popular_rooms' => $popular_rooms,
            'popular_tours' => $popular_tours,
            'active_page' => 'reports'
        ];
        
        $this->call->view('admin/reports', $data);
    }

    /**
     * Export bookings to CSV
     */
    public function export_bookings() {
        $start_date = $this->io->get('start_date');
        $end_date = $this->io->get('end_date');
        
        $query = "SELECT 
                    b.id, 
                    CONCAT(g.first_name, ' ', g.last_name) as guest_name,
                    g.email,
                    rt.name as room_type,
                    r.room_number,
                    b.check_in_date,
                    b.check_out_date,
                    b.total_price,
                    b.status,
                    b.created_at
                  FROM bookings b
                  JOIN guests g ON b.guest_id = g.id
                  JOIN rooms r ON b.room_id = r.id
                  JOIN room_types rt ON r.room_type_id = rt.id";
        
        $params = [];
        if ($start_date && $end_date) {
            $query .= " WHERE b.check_in_date BETWEEN ? AND ?";
            $params = [$start_date, $end_date];
        }
        
        $query .= " ORDER BY b.created_at DESC";
        
        $bookings = $this->db->raw($query, $params)->fetchAll(PDO::FETCH_ASSOC);
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="bookings_export_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, ['ID', 'Guest Name', 'Email', 'Room Type', 'Room Number', 'Check-in', 'Check-out', 'Total Price', 'Status', 'Created At']);
        
        // Add data rows
        foreach ($bookings as $booking) {
            fputcsv($output, $booking);
        }
        
        fclose($output);
        exit;
    }

    // ---------------------------------------------------
    // BOOKING QUICK ACTIONS (NEW)
    // ---------------------------------------------------

    /**
     * Quick status update for bookings
     */
    public function update_booking_status($id, $status) {
        $valid_statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        
        if (!in_array($status, $valid_statuses)) {
            redirect('/admin/bookings');
            return;
        }
        
        $booking = $this->Booking_model->find($id);
        
        if ($booking) {
            $this->Booking_model->update($id, ['status' => $status]);
            
            // If cancelling, free up the room
            if ($status === 'cancelled') {
                $this->Room_model->update($booking['room_id'], ['status' => 'available']);
            }
            
            $this->session->set_flashdata('success', 'Booking status updated to ' . $status);
        }
        
        redirect('/admin/bookings');
    }

    /**
     * Quick status update for tour bookings
     */
    public function update_tour_booking_status($id, $status) {
        $valid_statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        
        if (!in_array($status, $valid_statuses)) {
            redirect('/admin/tour-bookings');
            return;
        }
        
        $this->Tour_booking_model->update($id, ['status' => $status]);
        $this->session->set_flashdata('success', 'Tour booking status updated to ' . $status);
        
        redirect('/admin/tour-bookings');
    }

    // This function shows the main dashboard overview
    public function dashboard() {
        // --- 1. OVERVIEW CARD COUNTS (Your existing code) ---
        $data['tour_count'] = $this->db->table('tours')->count();
        $data['room_count'] = $this->db->table('rooms')->count();
        $data['active_booking_count'] = $this->db->table('bookings')->where_not_in('status', ['completed', 'cancelled'])->count();
        $data['resource_count'] = $this->db->table('resources')->where('is_available', 1)->count();
        
        // --- 2. DOUGHNUT CHART DATA (Booking Types) ---
        // Get total confirmed/completed room bookings
        $total_room_bookings = $this->db->table('bookings')
                                        ->where_not_in('status', ['cancelled', 'pending'])
                                        ->count();
        
        // Get total confirmed/completed tour bookings
        $total_tour_bookings = $this->db->table('tour_bookings')
                                        ->where_not_in('status', ['cancelled', 'pending'])
                                        ->count();

        // Pass this data to the view
        $data['doughnut_chart_labels'] = json_encode(['Room Bookings', 'Tour Bookings']);
        $data['doughnut_chart_data'] = json_encode([$total_room_bookings, $total_tour_bookings]);
        
        // (We re-use this for the overview card)
        $data['tour_booking_count'] = $total_tour_bookings;


        // --- 3. LINE CHART DATA (Revenue for last 7 days) ---
        // This query is for MySQL. It gets the sum of total_price for confirmed/completed
        // room bookings for each of the last 7 days.
        $revenue_query = $this->db->raw(
            "SELECT 
                DATE(check_in_date) AS booking_date, 
                SUM(total_price) AS daily_total
            FROM 
                bookings
            WHERE 
                status IN ('confirmed', 'completed') AND
                check_in_date >= CURDATE() - INTERVAL 7 DAY
            GROUP BY 
                DATE(check_in_date)
            ORDER BY 
                booking_date ASC"
        );
        
        $revenue_data = $revenue_query->fetchAll(PDO::FETCH_ASSOC);

        // We must format this data for Chart.js
        $line_chart_labels = [];
        $line_chart_dataset = [];
        
        // Create a 7-day date range
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $line_chart_labels[] = date('M d', strtotime($date));
            
            // Check if we have revenue data for this date
            $found = false;
            foreach ($revenue_data as $row) {
                if ($row['booking_date'] == $date) {
                    $line_chart_dataset[] = $row['daily_total'];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $line_chart_dataset[] = 0; // Add 0 if no revenue for this day
            }
        }
        
        // Pass this data to the view
        $data['line_chart_labels'] = json_encode($line_chart_labels);
        $data['line_chart_data'] = json_encode($line_chart_dataset);
        
        // --- 4. Pass all data to the view ---
        $this->call->view('admin/dashboard', $data);
    }

    // Default method: This will show the list of all tours
    public function index() {
        $data['tours'] = $this->Tour_model->all();
        $this->call->view('admin/index', $data);
    }

    // ---------------------------------------------------
    // Tour MANAGEMENT
    // ---------------------------------------------------

    // Show the form to add a new tour
    public function add_tour() {
        $this->call->view('admin/add_tour');
    }

    // Process the form submission and save the new tour
    public function save_tour() {
        $bind = array(
            "name" => $this->io->post('name'),
            "description" => $this->io->post('description'),
            "price" => $this->io->post('price'),
            "duration" => $this->io->post('duration'),
            "latitude" => $this->io->post('latitude'),   // ADD THIS
            "longitude" => $this->io->post('longitude') // ADD THIS
        );

        // Handle Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $this->upload->set_dir('public/uploads/images/'); // Set upload directory
            $this->upload->allowed_extensions(array('jpg', 'jpeg', 'png', 'gif')); // Allowed types
            
            if ($this->upload->do_upload($_FILES['image'])) { // Use $_FILES directly
                $bind['image_filename'] = $this->upload->get_filename(); // Save the filename
            } else {
                // Handle upload error (e.g., set flashdata, redirect back)
                $errors = $this->upload->get_errors();
                 $this->session->set_flashdata('upload_error', implode(', ', $errors));
                 redirect('/admin/add_tour'); // Redirect back to form on error
                 return; // Stop execution
            }
        }

        $this->Tour_model->insert($bind);
        redirect('/admin/tours');
    }

    // Delete a tour
    public function delete_tour($id) {
        // Find the tour by its ID and delete it
        $this->Tour_model->delete($id);
        // Redirect back to the main admin page
        redirect('/admin/tours');
    }

    // Show the form to edit an existing tour
    public function edit_tour($id) {
        // Find the specific tour by its ID
        $data['tour'] = $this->Tour_model->find($id);
        // Load the edit view and pass the tour data to it
        $this->call->view('admin/edit_tour', $data);
    }

    // Process the form submission to update a tour
    public function update_tour() {
        $id = $this->io->post('id');
        $bind = array(
            "name" => $this->io->post('name'),
            "description" => $this->io->post('description'),
            "price" => $this->io->post('price'),
            "duration" => $this->io->post('duration'),
            "latitude" => $this->io->post('latitude'),   // ADD THIS
            "longitude" => $this->io->post('longitude') // ADD THIS
        );

        // Handle Image Upload (Optional Update)
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $this->upload->set_dir('public/uploads/images/');
            $this->upload->allowed_extensions(array('jpg', 'jpeg', 'png', 'gif'));
            
            if ($this->upload->do_upload($_FILES['image'])) {
                 // TODO: Optionally delete the old image file here
                $bind['image_filename'] = $this->upload->get_filename();
            } else {
                 $errors = $this->upload->get_errors();
                 $this->session->set_flashdata('upload_error', implode(', ', $errors));
                 redirect('/admin/edit_tour/' . $id);
                 return;
            }
        }

        $this->Tour_model->update($id, $bind);
        redirect('/admin/tours');
    }

    // ---------------------------------------------------
    // ROOM TYPE MANAGEMENT
    // ---------------------------------------------------

    // Show list of room types
    public function room_types() {
        $data['room_types'] = $this->Room_type_model->all();
        $this->call->view('admin/room_types_index', $data);
    }

    // Show form to add a new room type
    public function add_room_type() {
        $this->call->view('admin/add_room_type');
    }

    // Save a new room type
    public function save_room_type() {
        $bind = [
            'name' => $this->io->post('name'),
            'description' => $this->io->post('description'),
            'base_price' => $this->io->post('base_price'),
            'capacity' => $this->io->post('capacity')
        ];

        // Handle Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $this->upload->set_dir('public/uploads/images/');
            $this->upload->allowed_extensions(array('jpg', 'jpeg', 'png', 'gif'));
            
            if ($this->upload->do_upload($_FILES['image'])) {
                $bind['image_filename'] = $this->upload->get_filename();
            } else {
                 $errors = $this->upload->get_errors();
                 $this->session->set_flashdata('upload_error', implode(', ', $errors));
                 redirect('/admin/room-types/add');
                 return;
            }
        }

        $this->Room_type_model->insert($bind);
        redirect('/admin/room-types');
    }

    // Show form to edit a room type
    public function edit_room_type($id) {
        $data['room_type'] = $this->Room_type_model->find($id);
        $this->call->view('admin/edit_room_type', $data);
    }

    // Update an existing room type
    public function update_room_type() {
        $id = $this->io->post('id');
        $bind = [
            'name' => $this->io->post('name'),
            'description' => $this->io->post('description'),
            'base_price' => $this->io->post('base_price'),
            'capacity' => $this->io->post('capacity')
        ];

        // Handle Image Upload (Optional Update)
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $this->upload->set_dir('public/uploads/images/');
            $this->upload->allowed_extensions(array('jpg', 'jpeg', 'png', 'gif'));
            
            if ($this->upload->do_upload($_FILES['image'])) {
                // TODO: Optionally delete the old image file here
                $bind['image_filename'] = $this->upload->get_filename();
            } else {
                 $errors = $this->upload->get_errors();
                 $this->session->set_flashdata('upload_error', implode(', ', $errors));
                 redirect('/admin/room-types/edit/' . $id);
                 return;
            }
        }

        $this->Room_type_model->update($id, $bind);
        redirect('/admin/room-types');
    }

    // Delete a room type
    public function delete_room_type($id) {
        $this->Room_type_model->delete($id);
        redirect('/admin/room-types');
    }

    // ---------------------------------------------------
    // ROOM MANAGEMENT
    // ---------------------------------------------------

    // Show list of all physical rooms
    public function rooms() {
        $data['rooms'] = $this->Room_model->get_all_rooms_with_types();
        $this->call->view('admin/rooms_index', $data);
    }

    // Show form to add a new room
    public function add_room() {
        // We need all room types to populate a dropdown
        $data['room_types'] = $this->Room_type_model->all();
        $this->call->view('admin/add_room', $data);
    }

    // Save a new room
    public function save_room() {
        $bind = [
            'room_number' => $this->io->post('room_number'),
            'room_type_id' => $this->io->post('room_type_id'),
            'status' => $this->io->post('status')
        ];
        $this->Room_model->insert($bind);
        redirect('/admin/rooms');
    }

    // Show form to edit a room
    public function edit_room($id) {
        // Get the specific room's data
        $data['room'] = $this->Room_model->find($id);
        // Get all room types for the dropdown
        $data['room_types'] = $this->Room_type_model->all();
        $this->call->view('admin/edit_room', $data);
    }

    // Update an existing room
    public function update_room() {
        $id = $this->io->post('id');
        $bind = [
            'room_number' => $this->io->post('room_number'),
            'room_type_id' => $this->io->post('room_type_id'),
            'status' => $this->io->post('status')
        ];
        $this->Room_model->update($id, $bind);
        redirect('/admin/rooms');
    }

    // Delete a room
    public function delete_room($id) {
        $this->Room_model->delete($id);
        redirect('/admin/rooms');
    }

    // ---------------------------------------------------
    // BOOKING MANAGEMENT
    // ---------------------------------------------------

    // Show the main booking dashboard
    public function bookings() {
        $data['bookings'] = $this->Booking_model->get_all_bookings();
        $this->call->view('admin/bookings_index', $data);
    }

    // Show the form to add a new booking
    public function add_booking() {
        // We need lists of guests and available rooms for dropdowns
        $data['guests'] = $this->Guest_model->all();
        $data['rooms'] = $this->Room_model->get_all_rooms_with_types(); // Assuming this returns all rooms
        $this->call->view('admin/add_booking', $data);
    }

    // Save a new booking
    public function save_booking() {
        $bind = [
            'guest_id' => $this->io->post('guest_id'),
            'room_id' => $this->io->post('room_id'),
            'check_in_date' => $this->io->post('check_in_date'),
            'check_out_date' => $this->io->post('check_out_date'),
            'total_price' => $this->io->post('total_price'),
            'status' => $this->io->post('status')
        ];
        $this->Booking_model->insert($bind);
        redirect('/admin/bookings');
    }

    // Show form to edit a booking
    public function edit_booking($id) {
        // We need the specific booking, plus all guests and rooms for dropdowns
        $data['booking'] = $this->Booking_model->find($id);
        $data['guests'] = $this->Guest_model->all();
        $data['rooms'] = $this->Room_model->get_all_rooms_with_types();
        $this->call->view('admin/edit_booking', $data);
    }

    // Update an existing booking
    public function update_booking() {
        $id = $this->io->post('id');
        $bind = [
            'guest_id' => $this->io->post('guest_id'),
            'room_id' => $this->io->post('room_id'),
            'check_in_date' => $this->io->post('check_in_date'),
            'check_out_date' => $this->io->post('check_out_date'),
            'total_price' => $this->io->post('total_price'),
            'status' => $this->io->post('status')
        ];
        $this->Booking_model->update($id, $bind);
        redirect('/admin/bookings');
    }

    // Delete a booking and free up the room
    public function delete_booking($id) {
        // First, find the booking to get the associated room_id
        $booking = $this->Booking_model->find($id);

        if ($booking) {
            $room_id = $booking['room_id'];

            // Before marking the room available, ensure there are no other bookings
            // that still reference this room (defensive for concurrent bookings).
            $otherBookings = $this->db->table('bookings')
                                     ->where('room_id', $room_id)
                                     ->where('id !=', $id)
                                     ->count();

            if ($otherBookings == 0) {
                // Safe to mark as available
                $this->Room_model->update($room_id, ['status' => 'available']);
            }

            // Finally, delete the booking itself
            $this->Booking_model->delete($id);
        }

        // Redirect back to the booking list
        redirect('/admin/bookings');
    }
    // ---------------------------------------------------
    // RESOURCE MANAGEMENT
    // ---------------------------------------------------

    // Show list of all resources
    public function resources() {
        $data['resources'] = $this->Resource_model->all();
        $this->call->view('admin/resources_index', $data);
    }

    // Show form to add a new resource
    public function add_resource() {
        $this->call->view('admin/add_resource');
    }

    // Save a new resource
    public function save_resource() {
        $bind = [
            'name' => $this->io->post('name'),
            'type' => $this->io->post('type'),
            'capacity' => $this->io->post('capacity', FILTER_VALIDATE_INT) ?: null, // Allow null capacity
            'is_available' => $this->io->post('is_available') ? 1 : 0
        ];
        $this->Resource_model->insert($bind);
        redirect('/admin/resources');
    }

    // Show form to edit a resource
    public function edit_resource($id) {
        $data['resource'] = $this->Resource_model->find($id);
        $this->call->view('admin/edit_resource', $data);
    }

    // Update an existing resource
    public function update_resource() {
        $id = $this->io->post('id');
        $bind = [
            'name' => $this->io->post('name'),
            'type' => $this->io->post('type'),
            'capacity' => $this->io->post('capacity', FILTER_VALIDATE_INT) ?: null,
            'is_available' => $this->io->post('is_available') ? 1 : 0
        ];
        $this->Resource_model->update($id, $bind);
        redirect('/admin/resources');
    }

    // Delete a resource
    public function delete_resource($id) {
        // We should also check if the resource is scheduled before deleting,
        // but for now, we'll just delete it.
        $this->Resource_model->delete($id);
        redirect('/admin/resources');
    }

    // ---------------------------------------------------
    // TOUR BOOKING MANAGEMENT
    // ---------------------------------------------------

    public function tour_bookings() {
        $data['tour_bookings'] = $this->Tour_booking_model->get_all_tour_bookings();
        $this->call->view('admin/tour_bookings_index', $data);
    }

    /**
     * Show the page for managing a single tour booking (assigning resources)
     */
    public function manage_tour_booking($id) {
        // 1. Get the details of the tour booking itself
        $data['booking'] = $this->Tour_booking_model->get_tour_booking_by_id($id);
        
        if (!$data['booking']) {
            redirect('/admin/tour-bookings'); // Booking not found
        }

        // 2. Get resources that are *already* assigned to this booking
        $data['assigned_resources'] = $this->Resource_schedule_model->get_assigned_resources($id);

        // 3. Get all *available* resources that can be assigned
        // We'll filter this more later, but for now, let's get all of them
        $data['all_available_resources'] = $this->Resource_model->filter(['is_available' => 1])->get_all();
        
        $this->call->view('admin/manage_tour_booking', $data);
    }

    /**
     * Handles the form submission to assign a resource to a tour booking
     */
    public function assign_resource() {
        // Get data from the form
        $tour_booking_id = $this->io->post('tour_booking_id');
        $resource_id = $this->io->post('resource_id');
        $start_time = $this->io->post('start_time');
        $end_time = $this->io->post('end_time');

        // Simple validation
        if (empty($tour_booking_id) || empty($resource_id) || empty($start_time) || empty($end_time)) {
             // Handle error - for now, just redirect back
             redirect('/admin/tour-booking/manage/'."$tour_booking_id");
             return;
        }

        $bind = [
            'resource_id' => $resource_id,
            'tour_booking_id' => $tour_booking_id,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];

        // Insert into the resource_schedules table using our model
        $this->Resource_schedule_model->insert($bind);

        // Redirect back to the manage page for that booking
        redirect('/admin/tour-booking/manage/' . $tour_booking_id);
    }

    /**
     * Un-assigns a resource from a tour booking
     */
    public function unassign_resource($schedule_id, $tour_booking_id) {
        // Delete the schedule entry by its unique ID
        $this->Resource_schedule_model->delete($schedule_id);

        // Redirect back to the manage page for that booking
        redirect('/admin/tour-booking/manage/' . $tour_booking_id);
    }

    // ---------------------------------------------------
    // INVOICE MANAGEMENT
    // ---------------------------------------------------

    /**
     * View/Generate an invoice for a specific room booking
     */
    public function invoice($booking_id) {
        // 1. Get the core booking details
        $booking = $this->Booking_model->get_booking_details($booking_id);
        if (!$booking) {
            redirect('/admin/bookings'); // Booking not found
        }
        $guest_id = $booking['guest_id'];

        // 2. Find or Create the Invoice
        $invoice = $this->Invoice_model->find_by_booking_id($booking_id);

        if (!$invoice) {
            // It doesn't exist, so let's create it
            $new_invoice_data = [
                'booking_id' => $booking_id,
                'issue_date' => date('Y-m-d'),
                'due_date' => date('Y-m-d'),
                'total_amount' => 0, // Will be calculated next
                'status' => 'unpaid'
            ];
            $invoice_id = $this->Invoice_model->insert($new_invoice_data);
            $invoice = $this->Invoice_model->find($invoice_id); // Reload new invoice data

            // 3. Add the Room Booking as the first line item
            $room_item_data = [
                'invoice_id' => $invoice_id,
                'description' => $booking['room_type_name'] . ' (' . $booking['room_number'] . ') - ' . $booking['check_in_date'] . ' to ' . $booking['check_out_date'],
                'quantity' => 1,
                'unit_price' => $booking['room_total'],
                'total_price' => $booking['room_total']
            ];
            $this->Invoice_item_model->insert($room_item_data);

            // 4. (OPTIONAL) Find and add any 'pending' tour bookings for this guest
            $guest_tours = $this->db->table('tour_bookings')
                                ->where('guest_id', $guest_id)
                                ->where('status', 'pending')
                                ->get_all();

            foreach ($guest_tours as $tour_booking) {
                $tour = $this->Tour_model->find($tour_booking['tour_id']);
                $tour_item_data = [
                    'invoice_id' => $invoice_id,
                    'description' => 'Tour: ' . $tour['name'] . ' (' . $tour_booking['booking_date'] . ') - ' . $tour_booking['number_of_pax'] . ' pax',
                    'quantity' => 1,
                    'unit_price' => $tour_booking['total_price'],
                    'total_price' => $tour_booking['total_price']
                ];
                $this->Invoice_item_model->insert($tour_item_data);
                // Mark tour booking as 'confirmed' since it's now on the bill
                $this->Tour_booking_model->update($tour_booking['id'], ['status' => 'confirmed']);
            }
        }

        // 5. Get all items and recalculate the total
        $data['invoice_id'] = $invoice['id'];
        $data['items'] = $this->Invoice_item_model->get_items_for_invoice($invoice['id']);
        $data['total_amount'] = $this->Invoice_model->recalculate_total($invoice['id']);
        $data['booking'] = $booking; // Pass booking info
        $data['invoice'] = $this->Invoice_model->find($invoice['id']); // Pass updated invoice info

        $this->call->view('admin/view_invoice', $data);
    }
}
?>