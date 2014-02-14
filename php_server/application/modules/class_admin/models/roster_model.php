<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Roster_model extends CLASS_Model {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->user_model->is_admin();
    }

    function get_students($class_id) {
        // all registered students
        $ids = $this->db->where('class_id', $class_id)
                ->get('registration')
                ->result();

        $d = array();
        foreach ($ids as $stud) {
            $stud->name = $this->user_model->name_from_id($stud->user_id);
            $stud->username = $this->user_model->username_from_id($stud->user_id);
            $stud->email = $this->user_model->email_from_id($stud->user_id);
            $stud->student_id = $this->user_model->student_id_from_id($stud->user_id);
            $d[] = $stud;
        }
        return $d;
    }

}