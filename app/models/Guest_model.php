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
}
?>