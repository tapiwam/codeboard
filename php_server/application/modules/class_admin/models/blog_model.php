<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blog_model extends CLASS_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('admin_model');
        $this->user_model->is_logged_in();
    }

    function get_all($class_id, $session_id) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_blogs';

        $this->db->where('session_id', $session_id);
        $data = $this->db->get($table)->result();
        return $data;
    }

    function get_one($class_id, $blog_id){
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_blogs';

        $this->db->where('id', $blog_id);
        $data = $this->db->get($table)->row();
        return $data;
    }
    
    function update($class_id, $blog_id, $data) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_blogs';

        return $this->db->where('id', $blog_id)
                        ->set($data)
                        ->update($table);
    }

    function delete($class_id, $blog_id) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_blogs';

        return $this->db->where('id', $blog_id)
                        ->delete($table);
    }

    function create($class_id, $data) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_blogs';

        return $this->db->set($data)
                        ->insert($table);
    }

}