<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Invoice_item_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'invoice_items';
    }

    /**
     * Get all line items for a specific invoice ID
     */
    public function get_items_for_invoice($invoice_id) {
        return $this->db->table($this->table)
            ->where('invoice_id', $invoice_id)
            ->order_by('id', 'ASC')
            ->get_all();
    }
}
?>