<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Invoice_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'invoices';
    }

    /**
     * Find an invoice by its related room booking ID
     */
    public function find_by_booking_id($booking_id) {
        return $this->db->table($this->table)
            ->where('booking_id', $booking_id)
            ->get();
    }

    /**
     * Recalculates the total amount of an invoice by summing its items
     */
    public function recalculate_total($invoice_id) {
        $sql = "SELECT SUM(total_price) as total FROM invoice_items WHERE invoice_id = ?";
        $result = $this->db->raw($sql, [$invoice_id])->fetch();
        
        $total = $result['total'] ?? 0.00;

        // Update the main invoice record with the new total
        $this->update($invoice_id, ['total_amount' => $total]);
        
        return $total;
    }
}
?>