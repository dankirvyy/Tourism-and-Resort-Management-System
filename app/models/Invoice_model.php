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
     * Get all invoices with guest and booking information
     */
    public function get_all_invoices() {
        return $this->db->raw("
            SELECT 
                i.*,
                g.first_name,
                g.last_name,
                g.email,
                b.check_in_date,
                b.check_out_date,
                b.status as booking_status,
                rt.name as room_type_name,
                r.room_number,
                tb.booking_date as tour_date,
                tb.number_of_pax,
                t.name as tour_name,
                CASE 
                    WHEN i.booking_id IS NOT NULL THEN 'room'
                    WHEN i.tour_booking_id IS NOT NULL THEN 'tour'
                    ELSE 'unknown'
                END as booking_type
            FROM invoices i
            LEFT JOIN bookings b ON i.booking_id = b.id
            LEFT JOIN tour_bookings tb ON i.tour_booking_id = tb.id
            LEFT JOIN guests g ON COALESCE(b.guest_id, tb.guest_id) = g.id
            LEFT JOIN rooms r ON b.room_id = r.id
            LEFT JOIN room_types rt ON r.room_type_id = rt.id
            LEFT JOIN tours t ON tb.tour_id = t.id
            ORDER BY i.created_at DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get invoice with full details (guest, booking, items)
     */
    public function get_invoice_details($invoice_id) {
        $invoice = $this->db->raw("
            SELECT 
                i.*,
                g.first_name,
                g.last_name,
                g.email,
                g.phone_number,
                g.address,
                b.check_in_date,
                b.check_out_date,
                b.status as booking_status,
                b.total_price as booking_total,
                b.amount_paid,
                b.balance_due,
                b.payment_status,
                rt.name as room_type_name,
                r.room_number,
                tb.booking_date as tour_date,
                tb.number_of_pax,
                tb.total_price as tour_total,
                tb.amount_paid as tour_amount_paid,
                tb.balance_due as tour_balance_due,
                tb.payment_status as tour_payment_status,
                t.name as tour_name,
                CASE 
                    WHEN i.booking_id IS NOT NULL THEN 'room'
                    WHEN i.tour_booking_id IS NOT NULL THEN 'tour'
                    ELSE 'unknown'
                END as booking_type
            FROM invoices i
            LEFT JOIN bookings b ON i.booking_id = b.id
            LEFT JOIN tour_bookings tb ON i.tour_booking_id = tb.id
            LEFT JOIN guests g ON COALESCE(b.guest_id, tb.guest_id) = g.id
            LEFT JOIN rooms r ON b.room_id = r.id
            LEFT JOIN room_types rt ON r.room_type_id = rt.id
            LEFT JOIN tours t ON tb.tour_id = t.id
            WHERE i.id = ?
        ", [$invoice_id])->fetch(PDO::FETCH_ASSOC);

        if ($invoice) {
            // Get all invoice items
            $invoice['items'] = $this->db->table('invoice_items')
                ->where('invoice_id', $invoice_id)
                ->get_all();
        }

        return $invoice;
    }

    /**
     * Get invoices for a specific guest
     */
    public function get_guest_invoices($guest_id) {
        return $this->db->raw("
            SELECT 
                i.*,
                b.check_in_date,
                b.check_out_date,
                rt.name as room_type_name,
                r.room_number
            FROM invoices i
            LEFT JOIN bookings b ON i.booking_id = b.id
            LEFT JOIN rooms r ON b.room_id = r.id
            LEFT JOIN room_types rt ON r.room_type_id = rt.id
            WHERE b.guest_id = ?
            ORDER BY i.created_at DESC
        ", [$guest_id])->fetchAll(PDO::FETCH_ASSOC);
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

    /**
     * Mark invoice as paid
     */
    public function mark_as_paid($invoice_id) {
        return $this->update($invoice_id, ['status' => 'paid']);
    }

    /**
     * Mark invoice as unpaid
     */
    public function mark_as_unpaid($invoice_id) {
        return $this->update($invoice_id, ['status' => 'unpaid']);
    }

    /**
     * Get invoice statistics
     */
    public function get_invoice_stats() {
        $stats = [];
        
        // Total invoices
        $stats['total_invoices'] = $this->db->table($this->table)->count();
        
        // Paid invoices
        $stats['paid_invoices'] = $this->db->table($this->table)
            ->where('status', 'paid')
            ->count();
        
        // Unpaid invoices
        $stats['unpaid_invoices'] = $this->db->table($this->table)
            ->where('status', 'unpaid')
            ->count();
        
        // Total revenue (paid invoices)
        $paid_total = $this->db->raw("
            SELECT SUM(total_amount) as total 
            FROM invoices 
            WHERE status = 'paid'
        ")->fetch(PDO::FETCH_ASSOC);
        $stats['total_revenue'] = $paid_total['total'] ?? 0;
        
        // Outstanding amount (unpaid invoices)
        $unpaid_total = $this->db->raw("
            SELECT SUM(total_amount) as total 
            FROM invoices 
            WHERE status = 'unpaid'
        ")->fetch(PDO::FETCH_ASSOC);
        $stats['outstanding_amount'] = $unpaid_total['total'] ?? 0;
        
        return $stats;
    }

    /**
     * Generate invoice number (format: INV-YYYYMMDD-XXX)
     */
    public function generate_invoice_number() {
        $date = date('Ymd');
        $last_invoice = $this->db->table($this->table)
            ->like('id', $date)
            ->order_by('id', 'DESC')
            ->get();
        
        $sequence = 1;
        if ($last_invoice) {
            // Extract sequence number from last invoice
            $sequence = intval(substr($last_invoice['id'], -3)) + 1;
        }
        
        return 'INV-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
?>