<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class FrontDesk extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->call->model('Booking_model');
        $this->call->model('Room_model');
        $this->call->model('Room_type_model');
        $this->call->model('Guest_model');
        
        // Check if user is logged in and has front_desk role
        if (!$this->session->has_userdata('front_desk_user_id')) {
            redirect('/login');
        }
    }
    
    public function dashboard() {
        // Get pending room assignments (bookings without room_id)
        $data['unassigned_bookings'] = $this->db->table('bookings')
            ->select('bookings.*, guests.first_name, guests.last_name, guests.email, guests.phone_number, room_types.name as room_type_name')
            ->left_join('guests', 'bookings.guest_id = guests.id')
            ->left_join('room_types', 'bookings.room_type_id = room_types.id')
            ->where_null('bookings.room_id')
            ->where('bookings.status', 'pending')
            ->order_by('bookings.check_in_date', 'ASC')
            ->get_all();
        
        // Get today's check-ins
        $today = date('Y-m-d');
        $data['todays_checkins'] = $this->db->table('bookings')
            ->select('bookings.*, guests.first_name, guests.last_name, rooms.room_number, room_types.name as room_type_name')
            ->left_join('guests', 'bookings.guest_id = guests.id')
            ->left_join('rooms', 'bookings.room_id = rooms.id')
            ->left_join('room_types', 'room_types.id = COALESCE(bookings.room_type_id, rooms.room_type_id)')
            ->where('bookings.check_in_date', $today)
            ->where('bookings.status', 'confirmed')
            ->order_by('bookings.check_in_time', 'ASC')
            ->get_all();
        
        // Get today's check-outs
        $data['todays_checkouts'] = $this->db->table('bookings')
            ->select('bookings.*, guests.first_name, guests.last_name, rooms.room_number, room_types.name as room_type_name')
            ->left_join('guests', 'bookings.guest_id = guests.id')
            ->left_join('rooms', 'bookings.room_id = rooms.id')
            ->left_join('room_types', 'room_types.id = COALESCE(bookings.room_type_id, rooms.room_type_id)')
            ->where('bookings.check_out_date', $today)
            ->where('bookings.status', 'confirmed')
            ->order_by('bookings.check_out_time', 'ASC')
            ->get_all();
        
        $this->call->view('frontdesk/dashboard', $data);
    }
    
    public function assign_room($booking_id) {
        $booking = $this->Booking_model->find($booking_id);
        
        if (!$booking || $booking['room_id']) {
            $this->session->set_flashdata('error', 'Booking not found or room already assigned.');
            redirect('/frontdesk/dashboard');
            return;
        }
        
        // Get available rooms for this room type and date range
        $data['booking'] = $booking;
        $data['guest'] = $this->Guest_model->find($booking['guest_id']);
        $data['room_type'] = $this->Room_type_model->find($booking['room_type_id']);
        
        // Get all rooms of this type
        $all_rooms = $this->db->table('rooms')
            ->where('room_type_id', $booking['room_type_id'])
            ->where('status', 'available')
            ->get_all();
        
        // Filter out rooms that have conflicts
        $available_rooms = [];
        foreach ($all_rooms as $room) {
            $has_conflict = $this->Booking_model->has_conflict(
                $room['id'],
                $booking['check_in_date'],
                $booking['check_out_date']
            );
            
            if (!$has_conflict) {
                $available_rooms[] = $room;
            }
        }
        
        $data['available_rooms'] = $available_rooms;
        
        $this->call->view('frontdesk/assign_room', $data);
    }
    
    public function process_assignment() {
        $booking_id = $this->io->post('booking_id');
        $room_id = $this->io->post('room_id');
        
        $booking = $this->Booking_model->find($booking_id);
        
        if (!$booking || $booking['room_id']) {
            $this->session->set_flashdata('error', 'Invalid booking or room already assigned.');
            redirect('/frontdesk/dashboard');
            return;
        }
        
        // Final conflict check
        if ($this->Booking_model->has_conflict($room_id, $booking['check_in_date'], $booking['check_out_date'])) {
            $this->session->set_flashdata('error', 'Selected room is not available for these dates.');
            redirect('/frontdesk/assign/' . $booking_id);
            return;
        }
        
        // Update booking with room assignment and confirm status
        $front_desk_user_id = $this->session->userdata('front_desk_user_id');
        $this->Booking_model->update($booking_id, [
            'room_id' => $room_id,
            'assigned_by' => $front_desk_user_id,
            'assigned_at' => date('Y-m-d H:i:s'),
            'status' => 'confirmed'
        ]);
        
        // Update room status
        $this->Room_model->update($room_id, ['status' => 'occupied']);
        
        $this->session->set_flashdata('success', 'Room successfully assigned to guest!');
        redirect('/frontdesk/dashboard');
    }
    
    public function logout() {
        $this->session->unset_userdata('front_desk_user_id');
        $this->session->unset_userdata('front_desk_user_name');
        $this->session->unset_userdata('front_desk_user_role');
        redirect('/login');
    }
}
?>
