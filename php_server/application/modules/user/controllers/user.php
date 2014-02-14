<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model("log_model");
    }

    // ==========================================
    // Login & Logout 
    // ==========================================

    function index($error = null) {
        if ($this->user_model->login_check())
            redirect('site/members');

        if (isset($error)) {
            $data['error'] = $error;
        }
        $data['main_content'] = 'login_form';
        $this->load->view('includes/template', $data);
    }

    function login($error = null) {
        if ($this->user_model->login_check())
            redirect('site/members');

        if (isset($error)) {
            $data['error'] = $error;
        }
        $data['main_content'] = 'login_form';
        $this->load->view('includes/template', $data);
    }

    function logout() {
        $this->session->sess_destroy();
        redirect('site');
    }

    // ==========================================
    // Signup accounts
    // ==========================================

    function signup() {
        if ($this->user_model->login_check())
            redirect('site/members');
        $data['main_content'] = 'signup_form';
        $this->load->view('includes/template', $data);
    }

    function signup_admin() {
        if ($this->user_model->login_check())
            redirect('site/members');
        $data['main_content'] = 'signup_form_admin';
        $this->load->view('includes/template', $data);
    }

    function signup_ta() {
        $data['main_content'] = 'signup_form_ta';
        $this->load->view('includes/template', $data);
    }

    // ==========================================
    // Validation checkers
    // ==========================================

    function validate_credentials() {
        $query = $this->user_model->validate();

        // If users credentials validated
        if ($query) {
            $q = $this->user_model->is_active();
            
            //var_dump($q); die();
            
            if ($q) {
                $data = array(
                    'username' => $this->input->post('username'),
                    'is_logged_in' => true
                );

                $this->session->set_userdata($data);  
                $this->user_model->user_data();  // Set other session variables
                //$this->user_model->log_time($this->session->userdata('id')); // Log what time they logged in
                $this->log_model->login($this->session->userdata('id')); // Log what time they logged in
                
                redirect('site/members');
            } else {
                $error = "Sorry looks like you account isn't active yet. Please contact the site administrator to activate it.";
                $this->index($error);
            }
        } else {
            $error = "Sorry the username/password combination is wrong, please try again.";
            $this->index($error);
        }
    }

    function check_password() {
        $response = array();
        $pass = md5($this->input->post('password'));

        echo "<script>alert($pass)</script>";
        if ($this->user_model->validate_password($pass) != true) {
            echo '<span class="badge badge-warning">Password does not match.</span>';
        } else {
            echo '<span class="badge badge-success">Password ok.</span>';
        }

        return $response;
    }

    // ==========================================
    // Creation
    // ==========================================

    function create_student() {
        if ($this->user_model->login_check())
            redirect('site/members');
        $this->load->library('form_validation');

        // fieldname, error msg, rules

        $this->form_validation->set_rules('first_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');
        
        $this->form_validation->set_rules('student_id', 'Student ID', 'trim|required|numeric|min_length[5]|max_length[12]');
        $this->form_validation->set_rules('student_id2', 'Student ID Confirmation', 'trim|required|matches[student_id]');

        if ($this->form_validation->run() == FALSE) {
            $data['main_content'] = 'signup_form';
            $this->load->view('includes/template', $data);
        } else {
            if ($this->user_model->create_member(0)) {
                $data['main_content'] = 'signup_successful';
                $this->load->view('includes/template', $data);
            } else {
                $this->signup();
            }
        }
    }

    function create_ta() {
        if ($this->user_model->login_check())
            redirect('site/members');
        $this->load->library('form_validation');

        // fieldname, error msg, rules

        $this->form_validation->set_rules('first_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $data['main_content'] = 'signup_form';
            $this->load->view('includes/template', $data);
        } else {
            if ($this->user_model->create_member(1)) {
                $data['main_content'] = 'signup_successful';
                $this->load->view('includes/template', $data);
            } else {
                $this->signup();
            }
        }
    }

    function create_instructor() {
        if ($this->user_model->login_check())
            redirect('site/members');
        $this->load->library('form_validation');

        // fieldname, error msg, rules

        $this->form_validation->set_rules('first_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $data['main_content'] = 'signup_form';
            $this->load->view('includes/template', $data);
        } else {
            if ($this->user_model->create_member(2)) {
                $data['main_content'] = 'signup_successful';
                $this->load->view('includes/template', $data);
            } else {
                $this->signup();
            }
        }
    }

    function create_sa() {
        if ($this->user_model->login_check())
            redirect('site/members');
        $this->load->library('form_validation');

        // fieldname, error msg, rules

        $this->form_validation->set_rules('first_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $data['main_content'] = 'signup_form';
            $this->load->view('includes/template', $data);
        } else {
            if ($this->user_model->create_member(9)) {
                $data['main_content'] = 'signup_successful';
                $this->load->view('includes/template', $data);
            } else {
                $this->signup();
            }
        }
    }

    // ==========================================
    // Settings
    // ==========================================

    function settings_update() {
        if (!$this->user_model->login_check())
            redirect('site');

        $p = $this->input->get_post('submit'); // die();
            //var_dump($p); die();
        
        if (strpos($p, 'password')) {
            $this->update_password();
        } else if (strpos($p, 'email')) {
            $this->update_email();
        } else if (strpos($p, 'username')) {
            $this->update_username();
        } else if (strpos($p, 'sid')) {
            $this->update_sid();
        }
        
    }

    function update_password() {
        $this->form_validation->set_rules('old_pass', 'Old password', 'trim|required');
        $this->form_validation->set_rules('new_pass', 'New password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('new_pass1', 'Repeated password', 'trim|required|matches[new_pass]');

        if ($this->form_validation->run() == false) {
            $data['main_content'] = 'settings';
            $this->load->view('includes/template', $data);
        } else {
            $this->user_model->update_password();
            $data['success'] = "Password successfully updated";
            $data['main_content'] = 'settings';
            $this->load->view('includes/template', $data);
        }
    }

    function update_email() {
        // var_dump($this->input->post()); die();
        
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
        $this->form_validation->set_rules('pass_email', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $data['main_content'] = 'settings';
            $this->load->view('includes/template', $data);
        } else {
            if($this->user_model->update_email()){
                $data['success'] = "Email successfully updated";
            } else {
                $data['fail'] = "Email already in use!";
            }
            $data['main_content'] = 'settings';
            $this->load->view('includes/template', $data);
        }
    }
    
    function update_username() {
        $this->form_validation->set_rules('username', 'username', 'trim|required|min_length[4]|max_length[15]');
        $this->form_validation->set_rules('pass_uname', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $data['main_content'] = 'settings';
            $this->load->view('includes/template', $data);
        } else {
            if( $this->user_model->update_username() ){
                $data['success'] = "Username successfully updated";
            } else {
                $data['fail'] = "Username already taken";
            }
            
            $data['main_content'] = 'settings';
            $this->load->view('includes/template', $data);
        }
    }
    
    function update_sid() {
        $this->form_validation->set_rules('student_id', 'Student ID', 'trim|required|min_length[4]|max_length[15]');
        $this->form_validation->set_rules('pass_sid', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $data['main_content'] = 'settings';
            $this->load->view('includes/template', $data);
        } else {
            if( $this->user_model->update_sid() ){
                $data['success'] = "Student ID successfully updated";
            } else {
                $data['fail'] = "Student ID already taken";
            }
            $data['main_content'] = 'settings';
            $this->load->view('includes/template', $data);
        }
    }

}

