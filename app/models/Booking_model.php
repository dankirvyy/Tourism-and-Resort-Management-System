<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Booking_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'bookings';
    }

    // Get all bookings with guest, room, and room type information
    public function get_all_bookings() {
        return $this->db->table($this->table)
            ->select('
                bookings.id,
                bookings.check_in_date,
                bookings.check_out_date,
                bookings.total_price,
                bookings.status,
                bookings.amount_paid,
                bookings.balance_due,
                bookings.payment_status,
                guests.first_name,
                guests.last_name,
                rooms.room_number,
                room_types.name as room_type_name
            ')
            ->left_join('guests', 'bookings.guest_id = guests.id')
            ->left_join('rooms', 'bookings.room_id = rooms.id')
            ->left_join('room_types', 'rooms.room_type_id = room_types.id')
            ->order_by('bookings.check_in_date', 'DESC')
            ->get_all();
    }

    // Get bookings specifically for a given guest ID
    public function get_bookings_by_guest($guest_id) {
         return $this->db->table($this->table)
            ->select('
                bookings.id,
                bookings.check_in_date,
                bookings.check_out_date,
                bookings.total_price,
                bookings.status,
                rooms.room_number,
                room_types.name as room_type_name
            ')
            ->left_join('rooms', 'bookings.room_id = rooms.id')
            ->left_join('room_types', 'rooms.room_type_id = room_types.id')
            ->where('bookings.guest_id', $guest_id) // Filter by guest ID
            ->order_by('bookings.check_in_date', 'DESC')
            ->get_all();
    }

    /**
     * Get all details for a single booking
     */
    public function get_booking_details($booking_id) {
        return $this->db->table($this->table)
            ->select('
                bookings.id, 
                bookings.guest_id, 
                bookings.check_in_date, 
                bookings.check_out_date, 
                bookings.total_price as room_total, 
                bookings.status, 
                guests.first_name, 
                guests.last_name, 
                guests.email,
                rooms.room_number, 
                room_types.name as room_type_name
            ')
            ->left_join('guests', 'bookings.guest_id = guests.id')
            ->left_join('rooms', 'bookings.room_id = rooms.id')
            ->left_join('room_types', 'rooms.room_type_id = room_types.id')
            ->where('bookings.id', $booking_id)
            ->get();
    }

    /**
     * Check if a room has conflicting bookings
     * 
     * @param int $room_id The room to check
     * @param string $check_in_date Check-in date (Y-m-d format)
     * @param string $check_out_date Check-out date (Y-m-d format)
     * @param int|null $exclude_booking_id Optional booking ID to exclude (for updates)
     * @return bool True if there's a conflict, false if room is available
     */
    public function has_conflict($room_id, $check_in_date, $check_out_date, $exclude_booking_id = null) {
        // Check for overlapping bookings
        // Two date ranges overlap if: start1 < end2 AND start2 < end1
        // We only check for confirmed bookings (not cancelled or completed)
        
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE room_id = ? 
                AND status = ? 
                AND check_in_date < ? 
                AND check_out_date > ?";
        
        $params = [$room_id, 'confirmed', $check_out_date, $check_in_date];
        
        // Exclude a specific booking if updating
        if ($exclude_booking_id !== null) {
            $sql .= " AND id != ?";
            $params[] = $exclude_booking_id;
        }
        
        // Debug logging (optional - comment out in production)
        error_log("Booking Conflict Check - Room: {$room_id}, Check-in: {$check_in_date}, Check-out: {$check_out_date}");
        
        $result = $this->db->raw($sql, $params)->fetch(PDO::FETCH_ASSOC);
        
        $has_conflict = isset($result['count']) && $result['count'] > 0;
        
        // Debug logging (optional - comment out in production)
        error_log("Conflict Result: " . ($has_conflict ? 'YES - BLOCKED' : 'NO - ALLOWED'));
        
        return $has_conflict;
    }

    /**
     * Get all conflicting bookings for a room and date range
     * 
     * @param int $room_id The room to check
     * @param string $check_in_date Check-in date (Y-m-d format)
     * @param string $check_out_date Check-out date (Y-m-d format)
     * @return array Array of conflicting bookings
     */
    public function get_conflicts($room_id, $check_in_date, $check_out_date) {
        $sql = "SELECT bookings.id, bookings.check_in_date, bookings.check_out_date, 
                       bookings.status, guests.first_name, guests.last_name, guests.email
                FROM {$this->table} 
                LEFT JOIN guests ON bookings.guest_id = guests.id
                WHERE room_id = ? 
                AND status = ? 
                AND check_in_date < ? 
                AND check_out_date > ?";
        
        $params = [$room_id, 'confirmed', $check_out_date, $check_in_date];
        
        return $this->db->raw($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>