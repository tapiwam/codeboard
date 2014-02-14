<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_create extends CLASS_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_admin();
        $this->load->model('admin_model');
        $this->load->model('admin_create_model');
        // $this->load->model('student_model');
    }

    function create_class() {
        // Form validate
        $this->form_validation->set_rules('class_name', 'Class Name', 'trim|required');
        $this->form_validation->set_rules('section', 'Section Number', 'trim');
        $this->form_validation->set_rules('passcode', 'Passcode', 'trim|required');

        // Load admin model and call respective function
        if ($this->form_validation->run() == FALSE) {
            redirect('class_admin/create_class_option');
        } else {
            $status = $this->admin_create_model->create_class();

            // load success page
            $data['main_content'] = 'admin/success_class';
            $this->load->view('includes/template', $data);
        }
    }

    function create_session() {
        //$this->form_validation->set_rules('class_name', 'Class Name', 'trim|required');
        //$this->form_validation->set_rules('term', 'Term', 'trim|required');

        $this->form_validation->set_rules('class_id', 'Class Name', 'trim|required');
        $this->form_validation->set_rules('session_name', 'Session Name', 'trim|required');

        $this->form_validation->set_rules('start', 'start date', 'trim|required');
        $this->form_validation->set_rules('end', 'submission date', 'trim|required');
        $this->form_validation->set_rules('late', 'late submission date', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['class_id'] = $this->input->post('class_id');
            $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);

            $data['main_content'] = 'admin/create_session_1';
            $this->load->view('includes/template', $data);
        } else {
            $status = $this->admin_create_model->create_session();
            $data['class_id'] = $this->input->post('class_id');
            $data['main_content'] = 'admin/success_session';
            $this->load->view('includes/template', $data);
        }
    }
    
    function create_session_api() {
        $this->form_validation->set_rules('class_id', 'Class Name', 'trim|required');
        $this->form_validation->set_rules('session_name', 'Session Name', 'trim|required');

        $this->form_validation->set_rules('start', 'start date', 'trim|required');
        $this->form_validation->set_rules('end', 'submission date', 'trim|required');
        $this->form_validation->set_rules('late', 'late submission date', 'trim|required');

        $d = new stdClass();
        if ($this->form_validation->run() == false) {
            $d->error = 1;
            $d->result = "Failed to validate submitted data!";
        } else {
            $status = $this->admin_create_model->create_session();
            
            if ($status !== false) {
                $d->success = 1;
                $d->result = "Submitted";
                $d->session_id = $status;
            } else {
                $d->error = 1;
                $d->result = "Failed to change dates and times";
            }
        }
        echo json_encode($d);
    }

    /*
    function create_assignment() {
        $this->form_validation->set_rules('class_id', 'Class ID', 'trim|required');
        $this->form_validation->set_rules('session_id', 'session_id', 'trim|required');
        $this->form_validation->set_rules('num_tc', 'Number of test cases', 'trim|required|numeric');
        $this->form_validation->set_rules('prog_name', 'Program Name', 'trim|required');
        $this->form_validation->set_rules('prog_key', 'Program Key', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            redirect('class_admin/create_assignment_option');
        }

        // try save details, check to see if key compiles 
        $status = $this->admin_create_model->create_assignment();


        // ******************************************************************
        // WHAT HAPPENS FROM HERE??????????????
        // move to testcases panel
        $data['classinfo'] = $this->admin_model->load_class_info($this->input->post('class_id'));
        $data['sessioninfo'] = $this->admin_model->load_session_info($this->input->post('class_id'), $this->input->post('session_id'));
        print_r($data['sessioninfo']);
        die();

        $data['prog']->prog_name = $this->input->post('prog_name');
        $data['prog']->prog_key = $this->input->post('prog_key');
        $data['prog']->num_tc = $this->input->post('num_tc');

        $data['main_content'] = 'admin/panel_assignment';
        $this->load->view('includes/template', $data);
    }

    function create_tc() {
        $data['class_id'] = $this->uri->segment(3);
        $data['session_id'] = $this->uri->segment(4);
        $data['assign_id'] = $this->uri->segment(5);

        // load class data
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        print_r($data);

        // load session data
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);
        print_r($data);

        // load assignment data
        $data['prog'] = $this->admin_model->load_assignment($data['class_id'], $data['session_id'], $data['assign_id']);

        for ($i = 0; $i < $data['prog'][0]->num_tc; $i++) {
            $this->form_validation->set_rules('input_' . $i, 'Input ' . $i, 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            redirect('class_admin/create_tc_option');
        }

        // compile and run each tc first to check if input is goog
        // exec("checktc.sh <<prog_name>> <<input>> <<output>>", $script_out, $exitout);
        // $status_tc = $?;
        // $tmp = fopen("../files/temp/$usr/input", 'r+');
        // for ($i=0 ; $i<$data['prog'][0]->num_tc ; $i++) {
        // 	output[$i] = stream_get_contents($tmp); 
        // 	If all goes well insert -> insert into DB

        $i = 1;
        $output = "";
        //print_r($data['prog']); die();
        $num = $data['prog'][0]->num_tc;
        $status = $this->admin_model->insert_tc_input($data['prog'], $num, $i, $output);


        // fclose($tmp);
        // send output data so user can tweak it
        //$data['output'] = $output;
        //$data['tc'] = $this->admin_model->load_tc_info();
        // send output to next screen
        $data['main_content'] = 'admin/panel_tc_output';
        $this->load->view('includes/template', $data);
    }
    
    */

}
