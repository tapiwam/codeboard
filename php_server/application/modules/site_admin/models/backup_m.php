<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Backup_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('mongo_db');
    }
    
}

?>
