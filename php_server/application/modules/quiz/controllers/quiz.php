<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Quiz extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->load->model('quiz_model');
    }

    function index() {
        $data['main_content'] = 'home';
        $this->load->view('includes/template', $data);
    }

}