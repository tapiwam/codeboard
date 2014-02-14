<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage extends CLASS_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_admin();
        $this->load->model('admin_model');
    }

    // ===================================================

    function index($error = null) {
        // get all classses and display a menu to select a class
        if (isset($error) && $error != "") {
            $data['error'] = $error;
        }

        $data['main_content'] = 'manage/home';
        $this->load->view('includes/template', $data);
    }

    // ===================================================

    function manage_class($class_id) {
        //grad relevant class data and load appropriate  
        $data['class_id'] = $class_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);

        $data['main_content'] = 'manage/m_class';
        $this->load->view('includes/template', $data);
    }

    function manage_session($class_id, $session_id) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['sessioninfo'] = $this->admin_model->load_session_info($class_id, $session_id);

        $data['main_content'] = 'manage/m_session';
        $this->load->view('includes/template', $data);
    }

    function manage_assignment($class_id, $session_id, $prog_name) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['prog_name'] = $prog_name;
        
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['sessioninfo'] = $this->admin_model->load_session_info($class_id, $session_id);
        $assign_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $assign_id = $assign_id[0]->id;
        $data['prog'] = load_assignment($class_id, $session_id, $assign_id);

        $data['main_content'] = 'manage/m_assignment';
        $this->load->view('includes/template', $data);
    }

    // ===================================================

    function classes() {
        $data['classes'] = $this->admin_model->classes();
        $data['main_content'] = 'manage/classes';
        $this->load->view('includes/template', $data);
    }

    function sessions($class_id) {
        $data['class_id'] = $class_id;
        $data['prog_name'] = $prog_name;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['sessions'] = $this->admin_model->load_all_sessions($class_id);
        $data['main_content'] = 'manage/sessions';
        $this->load->view('includes/template', $data);
    }

    function assignments($class_id, $session_id) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['sessioninfo'] = $this->admin_model->load_session_info($class_id, $session_id);
        $data['assignments'] = $this->admin_model->load_session_assignments($class_id, $session_id);
        $data['main_content'] = 'manage/assignments';
        $this->load->view('includes/template', $data);
    }

    // ===================================================

    function session_dates($class_id, $session_id) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['sessioninfo'] = $this->admin_model->load_session_info($class_id, $session_id);
        $data['main_content'] = 'manage/session_dates';
        $this->load->view('includes/template', $data);
    }

    function set_lab_time($class_id, $session_id) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['sessioninfo'] = $this->admin_model->load_session_info($class_id, $session_id);

        $this->form_validation->set_rules('start', 'start date', 'trim|required');
        $this->form_validation->set_rules('end', 'due date', 'trim|required');
        $this->form_validation->set_rules('late', 'late deadline', 'trim|required');

        if ($this->form_validation->run() == false) {
            // just reload the same page with errors
            $data['main_content'] = 'manage/session_dates';
            $this->load->view('includes/template', $data);
        } else {
            if ($this->admin_model->set_session_dates($class_id, $session_id) != false) {
                redirect("class_admin/sessions/$class_id/$session_id");
            } else {
                $data['error'] = "Failed to change dates and times";
                $data['main_content'] = 'manage/fail';
                $this->load->view('includes/template', $data);
            }
        }
    }
    
    function set_lab_time_api($class_id, $session_id) {
        $d = new stdClass();
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['sessioninfo'] = $this->admin_model->load_session_info($class_id, $session_id);

        $this->form_validation->set_rules('start', 'start date', 'trim|required');
        $this->form_validation->set_rules('end', 'due date', 'trim|required');
        $this->form_validation->set_rules('late', 'late deadline', 'trim|required');

        if ($this->form_validation->run() == false) {
            $d->error = 1;
            $d->result = "Failed to validate submitted data!";
        } else {
            if ($this->admin_model->set_session_dates($class_id, $session_id) != false) {
                $d->success = 1;
                $d->result = "Submitted";
            } else {
                $d->error = 1;
                $d->result = "Failed to change dates and times";
            }
        }
        echo json_encode($d);
    }

    // ===================================================
    // Functionality starts here
    // ===================================================

    function deactivate_class($class_id) {
        $this->load->model('activate_deactivate_model');
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        if ($this->activate_deactivate_model->deactivate_class($class_id)) {
            $data['success'] = "Successfully deactivated class";
            $data['main_content'] = 'manage/success';
            $this->load->view('includes/template', $data);
        } else {
            $data['error'] = "Sorry the class was not deactivated. It could already be 'offline'.";
            $data['main_content'] = 'manage/fail';
            $this->load->view('includes/template', $data);
        }
    }

    function deactivate_session($class_id, $session_id) {
        $this->load->model('activate_deactivate_model');
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['sessioninfo'] = $this->admin_model->load_session_info($class_id, $session_id);
        if ($this->activate_deactivate_model->deactivate_session($class_id, $session_id)) {
            //$data['success'] = "Successfully deactivated session";
            //$data['main_content'] = 'manage/success';
            //$this->load->view('includes/template', $data);
            redirect("class_admin/sessions/$class_id/$session_id");
        } else {
            $data['error'] = "Sorry the class was not deactivated. It could already be 'offline'.";
            $data['main_content'] = 'manage/fail_deactivate';
            $this->load->view('includes/template', $data);
        }
    }

    function deactivate_assignments($class_id, $session_id, $prog_name) {
        $this->load->model('activate_deactivate_model');
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['sessioninfo'] = $this->admin_model->load_session_info($class_id, $session_id);
        $assign_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $assign_id = $assign_id[0]->id;
        $data['prog'] = load_assignment($class_id, $session_id, $assign_id);
        if ($this->activate_deactivate_model->deactivate_session($class_id, $session_id)) {
            //$data['success'] = "Successfully deactivated session";
            //$data['main_content'] = 'manage/success';
            //$this->load->view('includes/template', $data);
            redirect("class_admin/assignments/$class_id/$session_id/$prog_name");
        } else {
            $data['error'] = "Sorry the class was not deactivated. It could already be 'offline'.";
            $data['main_content'] = 'manage/fail';
            $this->load->view('includes/template', $data);
        }
    }
    
    function delete_assignment($class_id, $session_id, $prog_name) {
        $this->load->model('admin_delete_model');
        $this->admin_delete_model->delete_assignment($class_id, $session_id, $prog_name);
        redirect("class_admin/manage/assignments/$class_id/$session_id");
    }

    // ===================================================

    function activate_class($class_id) {
        $this->load->model('activate_deactivate_model');
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        if ($this->activate_deactivate_model->activate_class($class_id)) {
            $data['success'] = "Successfully activated class";
            $data['main_content'] = 'manage/success';
            $this->load->view('includes/template', $data);
        } else {
            $data['error'] = "Sorry the class was not activated. It could already be 'online'.";
            $data['main_content'] = 'manage/fail';
            $this->load->view('includes/template', $data);
        }
    }

    function activate_session($class_id, $session_id) {
        $this->load->model('activate_deactivate_model');
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['sessioninfo'] = $this->admin_model->load_session_info($class_id, $session_id);
        if ($this->activate_deactivate_model->activate_session($class_id, $session_id)) {
            //$data['success'] = "Successfully activated session";
            //$data['main_content'] = 'manage/success';
            //$this->load->view('includes/template', $data);
            redirect("class_admin/sessions/$class_id/$session_id");
        } else {
            $data['error'] = "Sorry the class was not activated. It could already be 'online'.";
            $data['main_content'] = 'manage/fail';
            $this->load->view('includes/template', $data);
        }
    }

    function activate_assignments($class_id, $session_id, $progname) {
        $this->load->model('activate_deactivate_model');
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['sessioninfo'] = $this->admin_model->load_session_info($class_id, $session_id);
        $assign_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $assign_id = $assign_id[0]->id;
        $data['prog'] = load_assignment($class_id, $session_id, $assign_id);
        if ($this->activate_deactivate_model->activate_session($class_id, $session_id)) {
            $data['success'] = "Successfully activated assignment";
            $data['main_content'] = 'manage/success';
            $this->load->view('includes/template', $data);
        } else {
            $data['error'] = "Sorry the class was not activated. It could already be 'online'.";
            $data['main_content'] = 'manage/fail';
            $this->load->view('includes/template', $data);
        }
    }

    // =====================================

    function add_instructor_option($class_id) {
        $data['class_id'] = $class_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['instructors'] = $this->admin_model->load_instructors();
        $data['main_content'] = 'manage/add_ins';
        $this->load->view('includes/template', $data);
    }

    function add_instructor($class_id) {
        $ins = $this->input->post('ins');
        $data['class_id'] = $class_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);

        if (!empty($ins)) {
            if( $this->admin_model->add_instructor($class_id, $ins) ) {
                $data['main_content'] = "manage/success_add_ins";
                $this->load->view('includes/template', $data);
            } else {
                $data['main_content'] = "manage/fail_add_ins";
                $this->load->view('includes/template', $data);
            }
        }
    }
    
    function remove_instructor($class_id) {
        
    }
    
    
    
    

}

