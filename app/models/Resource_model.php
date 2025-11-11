<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Resource_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'resources';
    }
}
?>