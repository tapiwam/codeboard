<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Roster extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->user_model->is_admin();
        $this->load->model('admin_model');
        $this->load->model('roster_model');
    }

    function index($class_id = null) {
        $seg = $this->uri->segment(3);
        //var_dump($seg); die();


        if (isset($class_id)) {
            redirect('class_admin/roster/cls/' . $class_id);
        } else if (!isset($seg) || $seg == "") {
            $data['classinfo'] = $this->admin_model->load_class_info($class_id);
            $data['classes'] = $this->admin_model->classes();
            $data['main_content'] = 'roster/home';
            $this->load->view('includes/template', $data);
        } else if (isset($seg) && $seg != 'index') {
            redirect('class_admin/roster/cls/' . $this->uri->segment(3));
        }
    }

    function cls($class_id) {
        $data['class_id'] = $class_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['students'] = $this->roster_model->get_students($class_id);

        $data['main_content'] = 'roster/roster';
        $this->load->view('includes/template', $data);
    }

}