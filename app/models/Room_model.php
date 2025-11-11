<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Room_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'rooms';
    }

    // Get all rooms and join with room_types to get the type name
    public function get_all_rooms_with_types() {
        return $this->db->table($this->table)
                    ->select('rooms.id, rooms.room_number, rooms.status, room_types.name as type_name')
                    ->left_join('room_types', 'rooms.room_type_id = room_types.id')
                    ->get_all();
    }
}
?>