<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->user_model->is_student();
        $this->load->model('quiz_model');
    }

    function index() {
        $data['main_content'] = 'quiz_student/home';
        $this->load->view('includes/template', $data);
    }

    
}