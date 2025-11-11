<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Room_type_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'room_types';
    }
}
?>