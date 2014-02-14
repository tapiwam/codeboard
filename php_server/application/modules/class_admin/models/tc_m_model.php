<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tc_m_model extends CLASS_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('admin_model');
        $this->user_model->is_logged_in();
    }

    function update_points($class_id, $session_id, $prog_name){
        $classinfo = $this->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';
        
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        
        $data = array(
            'c_points' => $this->input->post('c_points'),
            's_points' => $this->input->post('s_points'),
            'd_points' => $this->input->post('d_points'),
            'e_points' => $this->input->post('e_points'),
            'late' => $this->input->post('late'),
        );
        
        return $this->db->where('id', $pid)->set($data)->update($table);
    }
    
    function update_description($class_id, $session_id, $prog_name){
        $classinfo = $this->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';
        
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        
        $data = array(
            'description' => $this->input->post('description'),
        );
        
        return $this->db->where('id', $pid)->set($data)->update($table);
    }

}

