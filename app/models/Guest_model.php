<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Guest_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'guests';
    }

    public function find_by_email($email) {
        return $this->db->table($this->table)->where('email', $email)->get();
    }

    /**
     * Get guest with CRM metrics
     */
    public function get_guest_with_metrics($guest_id) {
        return $this->db->table($this->table)
            ->where('id', $guest_id)
            ->get();
    }

    /**
     * Get all guests with enhanced CRM data
     */
    public function get_all_with_metrics() {
        return $this->db->table($this->table)
            ->select('*')
            ->order_by('total_revenue', 'DESC')
            ->get_all();
    }

    /**
     * Update guest metrics (total visits, revenue, last visit)
     */
    public function update_guest_metrics($guest_id) {
        // Calculate from bookings
        $booking_stats = $this->db->raw("
            SELECT 
                COUNT(*) as visit_count,
                MAX(check_out_date) as last_visit,
                SUM(total_price) as revenue
            FROM bookings
            WHERE guest_id = ? AND status = 'completed'
        ", [$guest_id])->fetch(PDO::FETCH_ASSOC);

        // Calculate from tour bookings
        $tour_stats = $this->db->raw("
            SELECT 
                COUNT(*) as tour_count,
                MAX(booking_date) as last_tour,
                SUM(total_price) as tour_revenue
            FROM tour_bookings
            WHERE guest_id = ? AND status = 'completed'
        ", [$guest_id])->fetch(PDO::FETCH_ASSOC);

        $total_visits = ($booking_stats['visit_count'] ?? 0) + ($tour_stats['tour_count'] ?? 0);
        $total_revenue = ($booking_stats['revenue'] ?? 0) + ($tour_stats['tour_revenue'] ?? 0);
        $last_visit = max($booking_stats['last_visit'] ?? '2000-01-01', $tour_stats['last_tour'] ?? '2000-01-01');

        // Auto-classify guest type
        $guest_type = 'new';
        if ($total_revenue >= 50000) {
            $guest_type = 'vip';
        } elseif ($total_visits >= 3) {
            $guest_type = 'regular';
        }

        // Update the guest record
        $this->db->table($this->table)
            ->where('id', $guest_id)
            ->update([
                'total_visits' => $total_visits,
                'total_revenue' => $total_revenue,
                'last_visit_date' => $last_visit,
                'guest_type' => $guest_type,
                'loyalty_points' => floor($total_revenue / 100)
            ]);
    }

    /**
     * Get guests by type for segmentation
     */
    public function get_by_type($type) {
        return $this->db->table($this->table)
            ->where('guest_type', $type)
            ->order_by('total_revenue', 'DESC')
            ->get_all();
    }

    /**
     * Get VIP guests (high spenders)
     */
    public function get_vip_guests() {
        return $this->get_by_type('vip');
    }

    /**
     * Get guests who haven't visited in X days (for re-engagement)
     */
    public function get_inactive_guests($days = 90) {
        return $this->db->raw("
            SELECT * FROM guests
            WHERE last_visit_date IS NOT NULL
            AND last_visit_date < DATE_SUB(CURDATE(), INTERVAL ? DAY)
            AND role = 'user'
            ORDER BY total_revenue DESC
        ", [$days])->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get guests with birthdays this month (for marketing)
     */
    public function get_birthday_guests() {
        return $this->db->raw("
            SELECT * FROM guests
            WHERE MONTH(birthday) = MONTH(CURDATE())
            AND role = 'user'
            ORDER BY DAY(birthday)
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get guests who opted in for marketing
     */
    public function get_marketing_subscribers() {
        return $this->db->table($this->table)
            ->where('marketing_consent', 1)
            ->where('role', 'user')
            ->get_all();
    }

    /**
     * Search guests by name, email, or tags
     */
    public function search_crm($query) {
        return $this->db->table($this->table)
            ->like('first_name', '%' . $query . '%')
            ->or_like('last_name', '%' . $query . '%')
            ->or_like('email', '%' . $query . '%')
            ->or_like('tags', '%' . $query . '%')
            ->get_all();
    }

    /**
     * Add communication record
     */
    public function log_communication($guest_id, $type, $subject, $message, $sent_by) {
        $this->db->table('guest_communications')->insert([
            'guest_id' => $guest_id,
            'communication_type' => $type,
            'subject' => $subject,
            'message' => $message,
            'sent_by' => $sent_by,
            'sent_at' => date('Y-m-d H:i:s')
        ]);

        // Update last contacted
        $this->db->table($this->table)
            ->where('id', $guest_id)
            ->update(['last_contacted_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get communication history for a guest
     */
    public function get_communications($guest_id) {
        return $this->db->table('guest_communications')
            ->where('guest_id', $guest_id)
            ->order_by('sent_at', 'DESC')
            ->get_all();
    }

    /**
     * Get guest statistics for dashboard
     */
    public function get_crm_stats() {
        $stats = [];
        
        $stats['total_guests'] = $this->db->table($this->table)->where('role', 'user')->count();
        $stats['vip_count'] = $this->db->table($this->table)->where('guest_type', 'vip')->count();
        $stats['regular_count'] = $this->db->table($this->table)->where('guest_type', 'regular')->count();
        $stats['new_count'] = $this->db->table($this->table)->where('guest_type', 'new')->count();
        $stats['marketing_subscribers'] = $this->db->table($this->table)->where('marketing_consent', 1)->count();
        
        $revenue_result = $this->db->raw("SELECT SUM(total_revenue) as total FROM guests")->fetch(PDO::FETCH_ASSOC);
        $stats['total_revenue'] = $revenue_result['total'] ?? 0;
        
        return $stats;
    }
}
?>