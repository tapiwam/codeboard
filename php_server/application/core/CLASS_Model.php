<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CLASS_Model extends CI_Model {

    function __construct() {
        parent::__construct();
        //$this->load->model("log_model");
    }

    function load_class_info($id) {
        // echo "in class info <br />";
        //$this->db->select('id, term, class_name, instructor');
        $this->db->where('id', $id);
        $q = $this->db->get('classes');

        foreach ($q->result() as $row) {
            $data[] = $row;
        }

        //var_dump($data); echo '<hr />';
        if (isset($data))
            return $data;
    }

    function load_session_info($class_id, $session_id) {

        //echo '<hr />';
        //echo $class_id .'/'. $session_id;

        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];
        
        //var_dump($class_id); die();

        $p_table = $classinfo->term . '_' . $classinfo->class_name . '_sessions';
        $this->db->where('id', $session_id);
        $q = $this->db->get($p_table);

        foreach ($q->result() as $row)
            $data[] = $row;

        if (isset($data))
            return $data;
    }

    function load_all_sessions($class_id) {
        // Returns all necessary data related to a session for that class
        // ====================
        // Use the id to get the class data
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];

        // find tyhe correct table
        $p_table = $classinfo->term . '_' . $classinfo->class_name . '_sessions';

        // get all sessions from that table
        $d = $this->db->get($p_table);
        if ($d->num_rows() > 0) {
            foreach ($d->result() as $r) {
                $data[] = $r;
            }
        }

        //print_r($data); die();
        if (isset($data))
            return $data;
    }

    function load_session_assignments($class_id, $session_id) {
        //$data = array();
        // ====================
        // Use the id to get the class data
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];

        // find tyhe correct table
        $p_table = $classinfo->term . '_' . $classinfo->class_name . '_programs';

        // grab from DB
        $this->db->where('session_id', $session_id);
        $d = $this->db->get($p_table);
        if ($d->num_rows() > 0) {
            foreach ($d->result() as $r) {
                $data[] = $r;
            }
        }

        //print_r($data); die();
        if (isset($data)) {
            return $data;
        }
    }

    
    function load_assignment($class_id, $session_id, $assign_id) {
        // Use the id to get the class data
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];

        // find the correct table
        $p_table = $classinfo->term . '_' . $classinfo->class_name . '_programs';

        // grab from DB
        $this->db->where('id', $assign_id);
        $d = $this->db->get($p_table)->result();
        $data = $this->object_to_array($d);

        // print_r($data); die();
        if (isset($data)) {
            return $data;
        }
    }

    // load basic info for program
    function load_basicinfo($class_id, $session_id, $prog_name) {
        $classinfo = $this->load_class_info($class_id);
        //echo 'grabbed class info';

        $this->db->where('class_id', $class_id);
        $this->db->where('session_id', $session_id);
        $this->db->where('prog_name', $prog_name);
        $q = $this->db->get($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs');

        foreach ($q->result() as $row) {
            $data[] = $row;
        }

        if (isset($data))
            return $data;
    }

    function object_to_array($data) {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $value;
            }
            return $result;
        }
        return $data;
    }

    
    function assign_from_id($class_id, $assign_id) {
        $classinfo = $this->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';

        $q = $this->db->select('prog_name')
                ->where('id', $assign_id)
                ->get($table)
                ->result();

        // echo "Class ID: $class_id ::: Assign ID: $assign_id :: -->>>". print_r($q, TRUE); die();

        if (isset($q->prog_name))
            return $q->prog_name;
    }

    function sessionname_from_sessionid($class_id, $session_id) {
        $classinfo = $this->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_sessions';

        $q = $this->db->select('session_name')
                ->where('id', $session_id)
                ->get($table)
                ->row();

        if (isset($q))
            return $q->session_name;
    }

    function pidFromPname($class_id, $session_id, $prog_name) {
        //echo '<hr />In PID function<br/>';

        $classinfo = $this->load_class_info($class_id);
        //var_dump($classinfo);
        $this->db->select('id');
        $this->db->where('class_id', $class_id);
        $this->db->where('session_id', $session_id);
        $this->db->where('prog_name', $prog_name);
        $q = $this->db->get($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs', 1)->result();

        //echo 'exiting PID<hr />';
        return $q;
    }

    function snameFromSid($class_id, $session_id) {
        $classinfo = $this->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_sessions';
        
        $d = $this->db->where('id', $session_id)->get($table)->row();
        return $d->session_name;
    }

    function load_prog_info($class_id, $prog_id) {
        $classinfo = $this->load_class_info($class_id);
        //echo 'grabbed class info';

        $this->db->where('class_id', $class_id);
        $this->db->where('id', $prog_id);
        $q = $this->db->get($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs');

        foreach ($q->result() as $row) {
            $row->session_name = $this->snameFromSid($class_id, $row->session_id);
            $data[] = $row;
        }

        if (isset($data))
            return $data;
    }

}