<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Home extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->model('Tour_model');
        $this->call->model('Room_type_model');
        $this->call->model('Room_model');
        $this->call->model('Guest_model');
        $this->call->model('Booking_model');
        $this->call->model('Tour_booking_model');
        
        // Auto-cleanup expired bookings
        $this->auto_cleanup_expired_bookings();
    }
    
    /**
     * Automatically process expired bookings on every page load
     * Marks bookings as completed and frees up rooms when check-out date has passed
     */
    private function auto_cleanup_expired_bookings() {
        $now = date('Y-m-d H:i:s');
        
        // Update expired bookings to completed (considering both date and time)
        $this->db->raw(
            "UPDATE bookings 
             SET status = 'completed' 
             WHERE status = 'confirmed' 
             AND CONCAT(check_out_date, ' ', check_out_time) < :now",
            ['now' => $now]
        );
        
        // Free up rooms that no longer have active bookings
        $this->db->raw(
            "UPDATE rooms r
             SET r.status = 'available'
             WHERE r.status = 'occupied'
             AND NOT EXISTS (
                 SELECT 1 FROM bookings b
                 WHERE b.room_id = r.id
                 AND b.status = 'confirmed'
             )"
        );
    }

    private function get_room_types_with_availability($search = null, $sort = null) {
        
        // Ensure room statuses are in sync with bookings.
        // This handles cases where a booking row was deleted directly in phpMyAdmin
        // and the corresponding room row was left as 'occupied'.
        // We only mark a room available when there are no active bookings for it
        // (i.e., no bookings with status other than 'cancelled' or 'completed').
        $this->db->raw(
            "UPDATE `rooms` r
             SET r.status = 'available'
             WHERE NOT EXISTS (
                 SELECT 1 FROM `bookings` b
                 WHERE b.room_id = r.id
                 AND b.status NOT IN ('cancelled','completed')
             )"
        );

        // Start the query for room types
        $this->db->table('room_types');

        // If a search term is provided, filter by name OR description
        if (!empty($search)) {
            $this->db->like('name', '%' . $search . '%')
                     ->or_like('description', '%' . $search . '%');
        }

        // Apply sorting
        switch ($sort) {
            case 'price_asc':
                $this->db->order_by('base_price', 'ASC');
                break;
            case 'price_desc':
                $this->db->order_by('base_price', 'DESC');
                break;
            case 'name_asc':
                $this->db->order_by('name', 'ASC');
                break;
            default:
                // Default sorting (e.g., by ID or name)
                $this->db->order_by('id', 'ASC');
        }

        // Get the (potentially filtered and sorted) room types
        $room_types = $this->db->get_all();

        // Get the availability counts (this part is unchanged)
        $counts_query = $this->db->raw(
            "SELECT room_type_id, COUNT(id) as available_count 
             FROM rooms 
             WHERE status = 'available' 
             GROUP BY room_type_id"
        );
        $counts_result = $counts_query->fetchAll(PDO::FETCH_KEY_PAIR);
        
        foreach ($room_types as &$type) {
            $type['available_rooms_count'] = $counts_result[$type['id']] ?? 0;
        }
        return $room_types;
    }

	public function index() {
        $data['featured_room_types'] = array_slice($this->get_room_types_with_availability(), 0, 3);
        $data['featured_tours'] = $this->Tour_model->limit(3);
		$this->call->view('public/home', $data);
	}
    
    public function rooms() {
        // MODIFIED: Get search and sort parameters from the URL
        $search = $this->io->get('search'); // Get ?search=...
        $sort = $this->io->get('sort');     // Get ?sort=...
        
        // Pass both parameters to our updated function
        $data['room_types'] = $this->get_room_types_with_availability($search, $sort);
        
        // Pass the parameters to the view
        $data['search_term'] = $search; 
        $data['sort_term'] = $sort; 

        $this->call->view('public/rooms', $data);
    }

    public function contact() {
        $this->call->view('public/contact');
    }

    public function book($room_type_id) {
        // --- ADDED LOGIN CHECK ---
        if (!$this->session->has_userdata('user_id')) {
            $this->session->set_flashdata('error', 'You must be logged in to book a room.');
            // Store the intended URL
            $this->session->set_userdata('redirect_url', '/book/room/' . $room_type_id); 
            redirect('/login');
            return; // Stop further execution
        }
        // --- END OF CHECK ---

        // Find the specific room type by its ID
        $data['room_type'] = $this->Room_type_model->find($room_type_id);

        // Get check-in and check-out dates from query parameters (optional)
        $check_in = $this->io->get('checkin');
        $check_out = $this->io->get('checkout');
        
        // Get all rooms of this type with booking information
        $sql = "SELECT r.*, 
                       b.check_in_date, b.check_in_time, 
                       b.check_out_date, b.check_out_time,
                       CASE 
                           WHEN b.id IS NULL THEN 1
                           ELSE 0
                       END as is_available
                FROM rooms r
                LEFT JOIN bookings b ON r.id = b.room_id 
                    AND b.status = 'confirmed'
                    AND CONCAT(b.check_out_date, ' ', b.check_out_time) > NOW()
                WHERE r.room_type_id = ?
                ORDER BY r.room_number";
        
        $all_rooms = $this->db->raw($sql, [$room_type_id])->fetchAll(PDO::FETCH_ASSOC);
        
        // If dates are provided, check which rooms have conflicts
        if ($check_in && $check_out) {
            $check_in_datetime = $check_in . ' 14:00:00';
            $check_out_datetime = $check_out . ' 12:00:00';
            
            foreach ($all_rooms as &$room) {
                if ($room['check_in_date']) {
                    $existing_checkin = $room['check_in_date'] . ' ' . $room['check_in_time'];
                    $existing_checkout = $room['check_out_date'] . ' ' . $room['check_out_time'];
                    
                    // Check if there's an overlap
                    if ($existing_checkin < $check_out_datetime && $existing_checkout > $check_in_datetime) {
                        $room['has_conflict'] = true;
                        $room['available_from'] = date('M j, Y \a\t g:i A', strtotime($existing_checkout));
                    } else {
                        $room['has_conflict'] = false;
                    }
                } else {
                    $room['has_conflict'] = false;
                }
            }
            
            $data['available_rooms'] = $all_rooms;
            $data['selected_check_in'] = $check_in;
            $data['selected_check_out'] = $check_out;
        } else {
            // No dates selected, show all rooms with their booking status
            foreach ($all_rooms as &$room) {
                if ($room['check_in_date']) {
                    $room['has_conflict'] = true; // Can't book without dates
                    $room['available_from'] = date('M j, Y \a\t g:i A', strtotime($room['check_out_date'] . ' ' . $room['check_out_time']));
                } else {
                    $room['has_conflict'] = false;
                }
            }
            $data['available_rooms'] = $all_rooms;
            $data['selected_check_in'] = null;
            $data['selected_check_out'] = null;
        }
        
        // --- Fetch Guest Data (we know user is logged in) ---
        $guest_id = $this->session->userdata('user_id');
        $data['guest'] = $this->Guest_model->find($guest_id);
        
        $this->call->view('public/book', $data);
    }

    public function process_booking() {
        $room_type_id = $this->io->post('room_type_id');
        $room_id = $this->io->post('room_id');
        $check_in = $this->io->post('checkin');
        $check_out = $this->io->post('checkout');
        $check_in_time = $this->io->post('checkin_time') ?: '14:00:00';
        $check_out_time = $this->io->post('checkout_time') ?: '12:00:00';
        
        // --- CHECK FOR BOOKING CONFLICTS ---
        if ($this->Booking_model->has_conflict($room_id, $check_in, $check_out)) {
            // Get conflict details for better error message
            $conflicts = $this->Booking_model->get_conflicts($room_id, $check_in, $check_out);
            $conflict_dates = '';
            if (!empty($conflicts)) {
                $first_conflict = $conflicts[0];
                $conflict_dates = ' (Already booked from ' . 
                    date('M j, Y', strtotime($first_conflict['check_in_date'])) . ' to ' . 
                    date('M j, Y', strtotime($first_conflict['check_out_date'])) . ')';
            }
            
            $this->session->set_flashdata('error', 
                'This room is not available for the selected dates.' . $conflict_dates . ' Please choose different dates or another room.');
            redirect('/book/room/' . $room_type_id);
            return;
        }
        // --- END CONFLICT CHECK ---
        
        $room_type = $this->Room_type_model->find($room_type_id);
        $price = $room_type['base_price'];
        $checkin_date = new DateTime($check_in);
        $checkout_date = new DateTime($check_out);
        $days = $checkout_date->diff($checkin_date)->format("%a");
        $total_price = ($days > 0 ? $days : 1) * $price;

        $booking_details = [
            'guest_data' => [
                'first_name' => $this->io->post('first_name'),
                'last_name' => $this->io->post('last_name'),
                'email' => $this->io->post('email'),
                'phone_number' => $this->io->post('phone')
            ],
            'room_type_data' => $room_type,
            'booking_data' => [
                'room_id' => $room_id,
                'room_type_id' => $room_type_id,
                'check_in_date' => $check_in,
                'check_out_date' => $check_out,
                'check_in_time' => $check_in_time,
                'check_out_time' => $check_out_time,
                'total_price' => $total_price,
                'days' => ($days > 0 ? $days : 1)
            ]
        ];
        
        $this->session->set_userdata('pending_booking', $booking_details);
        redirect('/booking/confirm');
    }

    public function confirm() {
        $data['booking'] = $this->session->userdata('pending_booking');
        if (!$data['booking']) {
            redirect('/');
        }
        $this->call->view('public/booking_confirmation', $data);
    }

    
    public function booking_success() {
        $this->call->view('public/booking_success');
    }

    public function tours() {
        // MODIFIED: Get search and sort parameters from the URL
        $search = $this->io->get('search'); // Get ?search=...
        $sort = $this->io->get('sort');     // Get ?sort=...

        // Start building the query
        $this->db->table('tours');

        // If a search term is provided, filter by name OR description
        if (!empty($search)) {
            $this->db->like('name', '%' . $search . '%')
                     ->or_like('description', '%' . $search . '%');
        }

        // Apply sorting
        switch ($sort) {
            case 'price_asc':
                $this->db->order_by('price', 'ASC');
                break;
            case 'price_desc':
                $this->db->order_by('price', 'DESC');
                break;
            case 'name_asc':
                $this->db->order_by('name', 'ASC');
                break;
            default:
                // Default sorting (e.g., by ID or name)
                $this->db->order_by('id', 'ASC');
        }

        // Get the (potentially filtered and sorted) tours
        $data['tours'] = $this->db->get_all();
        
        // Pass the parameters to the view
        $data['search_term'] = $search;
        $data['sort_term'] = $sort; 

        $this->call->view('public/tours', $data);
    }


    // Show details and booking form for a specific tour
    public function book_tour($tour_id) {
        // --- ADDED LOGIN CHECK ---
        if (!$this->session->has_userdata('user_id')) {
            $this->session->set_flashdata('error', 'You must be logged in to book a tour.');
            // Store the intended URL
            $this->session->set_userdata('redirect_url', '/book/tour/' . $tour_id); 
            redirect('/login');
            return; // Stop further execution
        }
        // --- END OF CHECK ---

        $data['tour'] = $this->Tour_model->find($tour_id);
        if (!$data['tour']) {
            redirect('/tours'); // Redirect if tour doesn't exist
        }
        
        // --- Fetch Guest Data (we know user is logged in) ---
        $data['guest'] = null;
        $guest_id = $this->session->userdata('user_id');
        $data['guest'] = $this->Guest_model->find($guest_id);

        $this->call->view('public/book_tour', $data);
    }

    // Process the initial tour booking form submission
    public function process_tour_booking() {
        // 1. Get tour and booking details
        $tour_id = $this->io->post('tour_id');
        $booking_date = $this->io->post('booking_date');
        $num_pax = $this->io->post('num_pax');

        $tour = $this->Tour_model->find($tour_id);
        if (!$tour) {
            redirect('/tours'); // Tour not found
        }
        
        // --- CHECK FOR TOUR CAPACITY CONFLICTS ---
        $availability = $this->Tour_booking_model->check_availability($tour_id, $booking_date, $num_pax);
        
        if ($availability['has_conflict']) {
            $error_msg = 'Sorry, this tour does not have enough available slots for the selected date.';
            if (isset($availability['available_slots']) && $availability['available_slots'] > 0) {
                $error_msg .= ' Only ' . $availability['available_slots'] . ' slot(s) remaining out of ' . 
                    $availability['max_capacity'] . ' total capacity.';
            } else {
                $error_msg .= ' The tour is fully booked for this date.';
            }
            
            $this->session->set_flashdata('error', $error_msg);
            redirect('/book/tour/' . $tour_id);
            return;
        }
        // --- END CONFLICT CHECK ---
        
        $total_price = $num_pax * $tour['price'];

        // 2. Store tour booking details in session
        $tour_booking_details = [
            'guest_data' => [
                'first_name' => $this->io->post('first_name'),
                'last_name' => $this->io->post('last_name'),
                'email' => $this->io->post('email'),
                'phone_number' => $this->io->post('phone')
            ],
            'tour_data' => $tour,
            'booking_data' => [
                'tour_id' => $tour_id,
                'booking_date' => $booking_date,
                'number_of_pax' => $num_pax,
                'total_price' => $total_price
            ]
        ];

        $this->session->set_userdata('pending_tour_booking', $tour_booking_details);

        // 3. Redirect to tour confirmation page
        redirect('/booking/confirm-tour');
    }

    // Show the tour booking confirmation page
    public function confirm_tour_booking() {
        $data['tour_booking'] = $this->session->userdata('pending_tour_booking');
        if (!$data['tour_booking']) {
            redirect('/tours');
        }
        $this->call->view('public/booking_confirmation_tour', $data);
    }


    public function tour_detail($tour_id) {
        // Find the specific tour by its ID
        $data['tour'] = $this->Tour_model->find($tour_id);

        // Redirect to the main tours page if the tour wasn't found
        if (!$data['tour']) {
            redirect('/tours');
        }
        
        // Load the detail view
        $this->call->view('public/tour_detail', $data);
    }

    public function my_profile() {
        // Redirect to login if not logged in
        if (!$this->session->has_userdata('user_id')) {
            redirect('/login');
        }

        $guest_id = $this->session->userdata('user_id');

        // Fetch guest's profile information
        $data['guest'] = $this->Guest_model->find($guest_id);

        // Fetch both types of bookings for this guest
        $data['room_bookings'] = $this->Booking_model->get_bookings_by_guest($guest_id);
        $data['tour_bookings'] = $this->Tour_booking_model->get_tour_bookings_by_guest($guest_id);

        // If guest data is not found, log them out as a precaution
        if (!$data['guest']) {
            $this->session->unset_userdata('user_id');
            $this->session->unset_userdata('user_name');
            redirect('/login');
        }

        $this->call->view('public/my_profile', $data);
    }

    public function my_bookings() {
        // This page is now consolidated into the profile page
        redirect('/my-profile');
    }

    // Show the form to change/upload avatar
    public function change_avatar() {
        // Redirect if not logged in
        if (!$this->session->has_userdata('user_id')) {
            redirect('/login');
        }
        $this->call->view('public/change_avatar');
    }

    // Process the avatar upload
    public function process_avatar_upload() {
        if (!$this->session->has_userdata('user_id')) {
            redirect('/login');
        }

        $guest_id = $this->session->userdata('user_id');

        // Configure upload settings
        $this->upload->set_dir('public/uploads/avatars/'); // Specific folder for avatars
        $this->upload->allowed_extensions(array('jpg', 'jpeg', 'png', 'gif'));
        $this->upload->max_size(2); // Limit to 2MB
        $this->upload->encrypt_name(); // Encrypt filename for uniqueness

        // Attempt the upload
        if (isset($_FILES['avatar']) && $this->upload->do_upload($_FILES['avatar'])) {
            $new_filename = $this->upload->get_filename();

            // TODO: Delete the old avatar file if it exists
            $current_guest = $this->Guest_model->find($guest_id);
            if ($current_guest && $current_guest['avatar_filename']) {
                 $old_file_path = ROOT_DIR . 'public/uploads/avatars/' . $current_guest['avatar_filename'];
                 if (file_exists($old_file_path)) {
                     unlink($old_file_path);
                 }
            }

            // Update the guest record in the database
            $this->Guest_model->update($guest_id, ['avatar_filename' => $new_filename]);

            // Redirect back to profile page
            redirect('/my-profile');

        } else {
            // Handle upload error
            $errors = $this->upload->get_errors();
            $this->session->set_flashdata('upload_error', implode(', ', $errors));
            redirect('/change-avatar'); // Redirect back to the upload form
        }
    }

    // Show the form for the user to edit their profile
    public function edit_profile() {
        // Redirect if not logged in
        if (!$this->session->has_userdata('user_id')) {
            redirect('/login');
        }

        $guest_id = $this->session->userdata('user_id');
        $data['guest'] = $this->Guest_model->find($guest_id);

        if (!$data['guest']) { // Safety check
            redirect('/login');
        }

        $this->call->view('public/edit_profile', $data);
    }

    // Process the profile update form submission
    public function update_profile() {
        if (!$this->session->has_userdata('user_id')) {
            redirect('/login');
        }

        $guest_id = $this->session->userdata('user_id');

        // Prepare data for update
        $bind = [
            'first_name' => $this->io->post('first_name'),
            'last_name' => $this->io->post('last_name'),
            'email' => $this->io->post('email'),
            'phone_number' => $this->io->post('phone_number')
        ];

        // Update the guest record
        if ($this->Guest_model->update($guest_id, $bind)) {
            // Update session name if first name changed
            if ($bind['first_name'] !== $this->session->userdata('user_name')) {
                $this->session->set_userdata('user_name', $bind['first_name']);
            }
            // Set success message (optional)
            $this->session->set_flashdata('profile_success', 'Profile updated successfully!');
        } else {
             // Set error message (optional)
             $this->session->set_flashdata('profile_error', 'Failed to update profile.');
        }

        // Redirect back to the profile page
        redirect('/my-profile');
    }

    /**
     * Cancel a room booking
     */
    public function cancel_room_booking($booking_id) {
        // Redirect to login if not logged in
        if (!$this->session->has_userdata('user_id')) {
            redirect('/login');
        }

        $guest_id = $this->session->userdata('user_id');

        // Get booking details to verify ownership
        $booking = $this->Booking_model->find($booking_id);

        if (!$booking) {
            $this->session->set_flashdata('error', 'Booking not found.');
            redirect('/my-profile');
            return;
        }

        // Verify that this booking belongs to the logged-in user
        if ($booking['guest_id'] != $guest_id) {
            $this->session->set_flashdata('error', 'You do not have permission to cancel this booking.');
            redirect('/my-profile');
            return;
        }

        // Check if booking is already cancelled
        if ($booking['status'] === 'cancelled') {
            $this->session->set_flashdata('error', 'This booking is already cancelled.');
            redirect('/my-profile');
            return;
        }

        // Check if booking can be cancelled (only confirmed bookings can be cancelled)
        if ($booking['status'] !== 'confirmed') {
            $this->session->set_flashdata('error', 'Only confirmed bookings can be cancelled.');
            redirect('/my-profile');
            return;
        }

        // Update booking status to cancelled
        $updated = $this->Booking_model->update($booking_id, ['status' => 'cancelled']);

        if ($updated) {
            // Update room status back to available
            $this->Room_model->update($booking['room_id'], ['status' => 'available']);

            $this->session->set_flashdata('success', 'Room booking cancelled successfully.');
            error_log("Room booking #{$booking_id} cancelled by guest #{$guest_id}");
        } else {
            $this->session->set_flashdata('error', 'Failed to cancel booking. Please try again.');
        }

        redirect('/my-profile');
    }

    /**
     * Cancel a tour booking
     */
    public function cancel_tour_booking($booking_id) {
        // Redirect to login if not logged in
        if (!$this->session->has_userdata('user_id')) {
            redirect('/login');
        }

        $guest_id = $this->session->userdata('user_id');

        // Get booking details using the table directly since we need tour_id
        $booking = $this->db->table('tour_bookings')
            ->where('id', $booking_id)
            ->get();

        if (!$booking) {
            $this->session->set_flashdata('error', 'Tour booking not found.');
            redirect('/my-profile');
            return;
        }

        // Verify that this booking belongs to the logged-in user
        if ($booking['guest_id'] != $guest_id) {
            $this->session->set_flashdata('error', 'You do not have permission to cancel this booking.');
            redirect('/my-profile');
            return;
        }

        // Check if booking is already cancelled
        if ($booking['status'] === 'cancelled') {
            $this->session->set_flashdata('error', 'This tour booking is already cancelled.');
            redirect('/my-profile');
            return;
        }

        // Check if booking can be cancelled (only confirmed bookings can be cancelled)
        if ($booking['status'] !== 'confirmed') {
            $this->session->set_flashdata('error', 'Only confirmed bookings can be cancelled.');
            redirect('/my-profile');
            return;
        }

        // Update booking status to cancelled
        $updated = $this->db->table('tour_bookings')
            ->where('id', $booking_id)
            ->update(['status' => 'cancelled']);

        if ($updated) {
            $this->session->set_flashdata('success', 'Tour booking cancelled successfully.');
            error_log("Tour booking #{$booking_id} cancelled by guest #{$guest_id}");
        } else {
            $this->session->set_flashdata('error', 'Failed to cancel tour booking. Please try again.');
        }

        redirect('/my-profile');
    }
    
    /**
     * Help Center page
     */
    public function help_center() {
        $this->call->view('public/help_center');
    }
    
    /**
     * FAQ page
     */
    public function faq() {
        $this->call->view('public/faq');
    }
    
    /**
     * Privacy Policy page
     */
    public function privacy_policy() {
        $this->call->view('public/privacy_policy');
    }
    
    /**
     * Terms of Service page
     */
    public function terms_of_service() {
        $this->call->view('public/terms_of_service');
    }
}
?>