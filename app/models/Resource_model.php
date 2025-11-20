<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Resource_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'resources';
    }

    /**
     * Decrease available quantity when a resource is assigned
     */
    public function decrease_quantity($resource_id, $amount = 1) {
        $resource = $this->find($resource_id);
        if ($resource && $resource['available_quantity'] >= $amount) {
            $new_quantity = $resource['available_quantity'] - $amount;
            $this->update($resource_id, [
                'available_quantity' => $new_quantity,
                'is_available' => ($new_quantity > 0) ? 1 : 0
            ]);
            return true;
        }
        return false;
    }

    /**
     * Increase available quantity when a resource is unassigned
     */
    public function increase_quantity($resource_id, $amount = 1) {
        $resource = $this->find($resource_id);
        if ($resource) {
            $new_quantity = min($resource['available_quantity'] + $amount, $resource['quantity']);
            $this->update($resource_id, [
                'available_quantity' => $new_quantity,
                'is_available' => 1
            ]);
            return true;
        }
        return false;
    }

    /**
     * Get available resources (available_quantity > 0)
     */
    public function get_available_resources() {
        return $this->db->table($this->table)
            ->where('available_quantity', '>', 0)
            ->get_all();
    }
}
?>