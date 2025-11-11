<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Resource_schedule_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'resource_schedules';
    }

    /**
     * Get all resources assigned to a specific tour booking ID
     *
     * @param int $tour_booking_id
     * @return array
     */
    public function get_assigned_resources($tour_booking_id) {
        return $this->db->table($this->table)
            ->select('resource_schedules.id as schedule_id, resources.name, resources.type')
            ->left_join('resources', 'resource_schedules.resource_id = resources.id')
            ->where('resource_schedules.tour_booking_id', $tour_booking_id)
            ->get_all();
    }
}
?>