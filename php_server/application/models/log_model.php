<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Log_model extends CLASS_Model {

    function __construct() {
        parent::__construct();
        $this->load->dbforge();
    }
    
    function basic($user_id, $class_id, $item_id, $type) {
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_log';
        
        $data = array(
            'user_id' => $user_id,
            'type' => $type,
            'item_id' => $item_id,
        );
        return $this->db->insert($table, $data);
    }

    function prog($user_id, $class_id, $prog_id) {
        // var_dump($prog_id); die();
        
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_log';
        
        $data = array(
            'user_id' => $user_id,
            'item_id' => $prog_id,
            'type' => 'prog'
        );
        return $this->db->insert($table, $data);
    }
    
    function blog($user_id, $class_id, $blog_id) {
        // var_dump($prog_id); die();
        
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_log';
        
        $data = array(
            'user_id' => $user_id,
            'item_id' => $blog_id,
            'type' => 'blog'
        );
        return $this->db->insert($table, $data);
    }

    function session($user_id, $class_id, $session_id) {
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_log';
        
        $data = array(
            'user_id' => $user_id,
            'item_id' => $session_id,
            'type' => 'session'
        );
        return $this->db->insert($table, $data);
    }

    function submit($user_id, $class_id, $prog_id) {
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_log';
        
        $data = array(
            'user_id' => $user_id,
            'item_id' => $prog_id,
            'type' => 'submit'
        );
        return $this->db->insert($table, $data);
    }

    function login($user_id) {
        $data = array(
            'user_id' => $user_id,
        );
        return $this->db->insert('log_login', $data);
    }
    
    // ================================
    function user_login(){
        
    }
    
    function user_submits(){
        
    }
    
    function user_session(){
        
    }
    
    function user_prog(){
        
    }

}
