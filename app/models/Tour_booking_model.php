<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Tour_booking_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'tour_bookings';
    }

    // Get all tour bookings with guest and tour names
    public function get_all_tour_bookings() {
        return $this->db->table($this->table)
            ->select('
                tour_bookings.id,
                tour_bookings.booking_date,
                tour_bookings.number_of_pax,
                tour_bookings.total_price,
                tour_bookings.status,
                guests.first_name,
                guests.last_name,
                tours.name as tour_name
            ')
            ->left_join('guests', 'tour_bookings.guest_id = guests.id')
            ->left_join('tours', 'tour_bookings.tour_id = tours.id')
            ->order_by('tour_bookings.booking_date', 'DESC')
            ->get_all();
    }

    // Get tour bookings specifically for a given guest ID
    public function get_tour_bookings_by_guest($guest_id) {
        return $this->db->table($this->table)
            ->select('
                tour_bookings.id,
                tour_bookings.booking_date,
                tour_bookings.number_of_pax,
                tour_bookings.total_price,
                tour_bookings.status,
                tours.name as tour_name
            ')
            ->left_join('tours', 'tour_bookings.tour_id = tours.id')
            ->where('tour_bookings.guest_id', $guest_id) // Filter by guest ID
            ->order_by('tour_bookings.booking_date', 'DESC')
            ->get_all();
    }

    /**
     * Get a single tour booking by its ID
     *
     * @param int $id
     * @return array
     */
    public function get_tour_booking_by_id($id) {
         return $this->db->table($this->table)
            ->select('
                tour_bookings.id, 
                tour_bookings.booking_date, 
                tour_bookings.number_of_pax, 
                tour_bookings.total_price, 
                tour_bookings.status, 
                guests.first_name, 
                guests.last_name, 
                tours.name as tour_name
            ')
            ->left_join('guests', 'tour_bookings.guest_id = guests.id')
            ->left_join('tours', 'tour_bookings.tour_id = tours.id')
            ->where('tour_bookings.id', $id) // Filter by the specific booking ID
            ->get(); // Get a single record
    }

    /**
     * Check if a tour has too many bookings for a specific date
     * This prevents overbooking based on tour capacity
     * 
     * @param int $tour_id The tour to check
     * @param string $booking_date The date to check (Y-m-d format)
     * @param int $requested_pax Number of people requesting to book
     * @param int|null $exclude_booking_id Optional booking ID to exclude (for updates)
     * @return array ['has_conflict' => bool, 'available_slots' => int, 'total_booked' => int]
     */
    public function check_availability($tour_id, $booking_date, $requested_pax, $exclude_booking_id = null) {
        // Get the tour to check max capacity
        $tour = $this->db->table('tours')
            ->where('id', $tour_id)
            ->get();
        
        if (!$tour) {
            return ['has_conflict' => true, 'available_slots' => 0, 'total_booked' => 0, 'error' => 'Tour not found'];
        }
        
        $max_capacity = isset($tour['max_capacity']) ? (int)$tour['max_capacity'] : 0;
        
        // If tour has no capacity limit, no conflict
        if ($max_capacity <= 0) {
            return ['has_conflict' => false, 'available_slots' => 999, 'total_booked' => 0];
        }
        
        // Get total pax already booked for this tour on this date
        $query = $this->db->table($this->table)
            ->select('SUM(number_of_pax) as total_pax')
            ->where('tour_id', $tour_id)
            ->where('booking_date', $booking_date)
            ->where('status', 'confirmed');
        
        // Exclude a specific booking if updating
        if ($exclude_booking_id !== null) {
            $query->where('id !=', $exclude_booking_id);
        }
        
        $result = $query->get();
        $total_booked = isset($result['total_pax']) ? (int)$result['total_pax'] : 0;
        $available_slots = $max_capacity - $total_booked;
        
        // Check if requested pax exceeds available slots
        $has_conflict = ($requested_pax > $available_slots);
        
        return [
            'has_conflict' => $has_conflict,
            'available_slots' => $available_slots,
            'total_booked' => $total_booked,
            'max_capacity' => $max_capacity
        ];
    }

    /**
     * Get all bookings for a specific tour on a specific date
     * 
     * @param int $tour_id The tour ID
     * @param string $booking_date The date (Y-m-d format)
     * @return array Array of bookings
     */
    public function get_bookings_by_tour_and_date($tour_id, $booking_date) {
        return $this->db->table($this->table)
            ->select('
                tour_bookings.id,
                tour_bookings.number_of_pax,
                tour_bookings.status,
                guests.first_name,
                guests.last_name,
                guests.email
            ')
            ->left_join('guests', 'tour_bookings.guest_id = guests.id')
            ->where('tour_id', $tour_id)
            ->where('booking_date', $booking_date)
            ->where('status', 'confirmed')
            ->get_all();
    }
}
?>