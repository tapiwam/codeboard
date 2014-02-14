<?php
 
class User_model extends CI_Model {
 
    function __construct() {
        parent::__construct();
    }
 
    function getUser($username) {
    	echo 'loading mongo_db...<br />';
        $this->load->library('mongo_db');
 		
		echo 'uploading to users...<br />';
        $user = $this->mongo_db->get_where('users', array('username' => $username));
        return $user;
    }
 
    function createUser($username, $password) {
    	echo "Inside create user<hr>";
		//die();
		
        echo 'loading mongo_db...<br />';
        $this->load->library('mongo_db');
        
		echo 'uploading to users...<br />';
        $user = array('username' => $username,
            'password' => $password);
 
        $this->mongo_db->insert('users', $user);
    }
 
}
 
?>