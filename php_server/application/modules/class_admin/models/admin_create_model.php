<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// This class deals with the concept behind creating components needed for the app

class Admin_create_model extends CI_Model {

    function __contruct() {
        $this->load->model('tables_model');
        $this->load->model('admin_model');
    }

    function create_class() {
        $this->load->model('tables_model');

        // insert class information into classes table
        $data = array(
            'class_name' => $this->input->post('class_name'),
            'passcode' => $this->input->post('passcode'),
            'term' => $this->input->post('term'),
            'section' => $this->input->post('section'),
            'lang' => $this->input->post('lang'),
            'instructor' => $this->session->userdata('username')
        );

        $data['class_name_id'] = $data['term'] . '-' . $data['class_name'] . '-' . $data['section'] . '-' . $data['instructor'];
        $insert = $this->db->insert('classes', $data);
        
        // add user to instructors list
        $class_id = $this->db->insert_id();
        $data1 = array(
            'class_id' => $class_id,
            'user_id' => $this->session->userdata('id'),
            'username' => $this->session->userdata('username'),
            'owner' => $this->session->userdata('id'),
        );
        $insert1 = $this->db->insert('class_instructors', $data1);

        // Start creating all the tables
        $term = $data['term'];
        $class_name = $data['class_name'];

        // Rinitialize here to setup the file directories - good
        $this->load->model('initialize_model');
        $this->initialize_model->init_files($term, $class_name);

        // Create session table - good
        $this->tables_model->sessions_tbl($term, $class_name);

        // cretae announcements table - good
        $this->tables_model->announce_tbl($term, $class_name);

        // Create tables for calender
        $this->tables_model->calendar_tbl($term, $class_name);

        // Create tables for gradebook
        $this->tables_model->gradebk_tbl($term, $class_name);

        // Create programs table -- good
        $this->tables_model->programs_tbl($term, $class_name);

        // Create files table
        $this->tables_model->files_tbl($term, $class_name);

        // Create student files table
        $this->tables_model->student_files_tbl($term, $class_name);

        // create tables for test cases - good
        $this->tables_model->testcases_tbl($term, $class_name);

        // create scores table for that sessions results - good
        $this->tables_model->results_tbl($term, $class_name);

        // students results	
        $this->tables_model->student_results_tbl($term, $class_name);

        // blogs	
        $this->tables_model->lab_blogs_tbl($term, $class_name);
        
        // log tables
        $this->tables_model->submit_log($term, $class_name);
        $this->tables_model->time_log($term, $class_name);
    }

    function create_session() {
        $this->load->dbforge();
        $class_id = $this->input->post('class_id');
        $classinfo = $this->admin_model->load_class_info($class_id);

        $class_name = $classinfo[0]->class_name;
        $term = $classinfo[0]->term;
        $session_name = $this->input->post('session_name');
        
        // $start = $this->input->post('start');
        // $end = $this->input->post('end');
        // $late = $this->input->post('late');
        
        $data = array( 'session_name' => $session_name );
        
        $date = new DateTime();
        $date->setTimestamp($this->input->post('start'));
        $data['start'] = $date->format('Y-m-d H:i:s');
        
        $date->setTimestamp($this->input->post('end'));
        $data['end'] = $date->format('Y-m-d H:i:s');
        
        $date->setTimestamp($this->input->post('late'));
        $data['late'] = $date->format('Y-m-d H:i:s');
        
        //var_dump($data); die();

        //** print_r($data); echo ' '.$term.' '.$class_name; //die();  // term and class name not being passed through
        // record in the sessions table so it can be loaded
        $p_table = "${term}_${class_name}_sessions";
        $insert = $this->db->insert($p_table, $data);
        if($insert){
            return $this->db->insert_id();
        } else {
            return $insert;
        }
    }

    // Updated Look at testcases contoller to handle this
    function create_assignment() {
        /*
          $data = array(
          'class_id'		=> $this->input->post('class_id'),
          'session_id'	=> $this->input->post('session_id'),
          'prog_name' 	=> $this->input->post('prog_name'),
          'prog_key' 		=> $this->input->post('prog_key'),
          'num_tc'		=> $this->input->post('num_tc'),
          );

          //$data['assign_key_id'] = $data['session_name']. '_' . $data['prog_name'];

          // insert data into classes atable e.g. key e.t.c
          $classinfo = $this->admin_model->load_class_info($data['class_id']);
          $p_table = $classinfo[0]->term.'_'.$classinfo[0]->class_name.'_programs' ;

          echo $p_table. '<br />';
          print_r($data); echo '<br>'; //die();

          $insert = $this->db->insert($p_table, $data);
          return $insert;
         * 
         */
    }

}