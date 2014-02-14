<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Class_admin extends CLASS_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->user_model->is_admin();
        $this->load->model('admin_model');
        //$this->load->model('student_model');
    }

    function index() {
        $user_id = $this->session->userdata('id');
        $data['classes'] = $this->admin_model->all_classes($user_id);

        $data['main_content'] = 'admin/home';
        $this->load->view('includes/template', $data);
    }

    // ===================================
    // Opens up the panel related to the class selected
    // ===================================

    function classes($class_id) {
        // ===================================
        // Opens up the panel related to the class selected
        // ===================================
        // $class_id = $this->uri->segment(3);

        // Load the related data to that class
        $data['sessions'] = $this->admin_model->load_all_sessions($class_id);
        //$data['$assignments'] = $this->admin_model->load_all_assignments($class_id);

        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['class_id'] = $class_id;

        // Load the window
        $data['main_content'] = 'admin/panel_class';
        $this->load->view('includes/template', $data);
    }

    function sessions() {
        // grab url info
        $data['class_id'] = $this->uri->segment(3);
        $data['session_id'] = $this->uri->segment(4);

        // load class data
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);

        // load session info
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        // Load the related data to that class
        $data['assignments'] = $this->admin_model->load_session_assignments($data['class_id'], $data['session_id']);

        // Load blog material
        $this->load->model('blog_model');
        $data['blogs'] = $this->blog_model->get_all($data['class_id'], $data['session_id']);

        //var_dump($data); //die();
        // Return ALL assignments for the given session 
        // Load the window
        $data['main_content'] = 'admin/panel_session';
        $this->load->view('includes/template', $data);
    }

    function assignments($class_id, $session_id, $assign_id) {
        // Return ALL assignments 
        // Go some of the major details. 
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id; // id for 
        $data['assign_id'] = $assign_id;

        // Query database for all infor in that session/assignment
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);
        
        $data['prog'] = $this->admin_model->load_assignment($data['class_id'], $data['session_id'], $data['assign_id']);
        
        
        if ($data['prog'][0]->published == 0) {
            // Addition: pull up the stage from the programs table (done already) then decide on the appropriate link based on stage
            $stage = $data['prog'][0]->stage;

            if ($stage == 1) {
                redirect('class_admin/tc/stage1/' . $data['class_id'] . '/' . $data['session_id'] . '/' . $data['prog'][0]->prog_name);
            } else if ($stage == 2) {
                redirect('class_admin/tc/stage2/' . $data['class_id'] . '/' . $data['session_id'] . '/' . $data['prog'][0]->prog_name);
            } else if ($stage == 3) {
                redirect('class_admin/tc/stage3/' . $data['class_id'] . '/' . $data['session_id'] . '/' . $data['prog'][0]->prog_name);
            } else if ($stage == 4) {
                redirect('class_admin/tc/stage4/' . $data['class_id'] . '/' . $data['session_id'] . '/' . $data['prog'][0]->prog_name);
            } else if ($stage == 5) {
                redirect('class_admin/tc/stage5/' . $data['class_id'] . '/' . $data['session_id'] . '/' . $data['prog'][0]->prog_name);
            } else if ($stage == 6) {
                redirect('class_admin/tc/stage6/' . $data['class_id'] . '/' . $data['session_id'] . '/' . $data['prog'][0]->prog_name);
            } else {
                redirect('class_admin/tc/review/' . $data['class_id'] . '/' . $data['session_id'] . '/' . $data['prog'][0]->prog_name);
            }
        } else {
            redirect('class_admin/tc/published/' . $data['class_id'] . '/' . $data['session_id'] . '/' . $data['prog'][0]->prog_name);
        }
    }

    // ===================================
    // Opens up the create panels
    // ===================================
    function create_class_option() {
        $data['main_content'] = 'admin/create_class';
        $this->load->view('includes/template', $data);
    }

    function create_session_option() {
        // First get information on the class_name and term
        $data['class_id'] = $this->uri->segment(3);
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);

        // print_r($data); die();

        $data['main_content'] = 'admin/create_session';
        $this->load->view('includes/template', $data);
    }

    function create_assignment_option() {

        $data['class_id'] = $this->uri->segment(3);
        $data['session_id'] = $this->uri->segment(4);

        // load class data
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);

        // load session data
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        //$data['main_content'] = 'admin/create_assignment';
        $data['main_content'] = 'tc/tc_1';
        $this->load->view('includes/template', $data);
    }

    // ===================================
    // Gradebook
    // ===================================
    function gradebook($class_id) {
        redirect("class_admin/gradebk/index/$class_id");
    }
    
    // ===================================

}