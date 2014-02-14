<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->user_model->is_admin();
        $this->load->model('quiz_model');
        $this->load->model('class_admin/admin_model');
    }

    // load all the users classes and list them for the user to pick
    function index() {
        $data['classes'] = $this->admin_model->classes();
        $data['main_content'] = 'quiz_admin/home';
        $this->load->view('includes/template', $data);
    }

    // load up all sessions for the class and list them for user
    function cls($class_id, $msg = null) {
        $this->quiz_model->check_tables($class_id);

        $data['class_id'] = $class_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['sessions'] = $this->admin_model->load_all_sessions($class_id);

        if ($msg != null) {
            $data['msg'] = $msg;
        }

        $data['main_content'] = 'quiz_admin/cls';
        $this->load->view('includes/template', $data);
    }

    // load all quizzes for session and display them
    function session($class_id, $session_id, $error = null) {

        if ($error != null) {
            $data['error'] = $error;
        }

        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        // load class and session data
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $quizzes = $this->quiz_model->get_all($class_id, $session_id);

        foreach ($quizzes as $quiz) {
            $quiz->num_ques = $this->quiz_model->num_ques($class_id, $quiz->id);
        }
        $data['quizzes'] = & $quizzes;

        $data['main_content'] = 'quiz_admin/session';
        $this->load->view('includes/template', $data);
    }

    // open and view what's inside of that container-> add, edit, delete
    function panel($class_id, $session_id, $quiz_id) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['quiz_id'] = $quiz_id;

        // load class and data
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);
        $data['quiz'] = $this->quiz_model->get_quiz($class_id, $session_id, $quiz_id);

        $data['main_content'] = 'quiz_admin/panel';
        $this->load->view('includes/template', $data);
    }

    // wizard for creating a quiz container
    function create($class_id, $session_id, $error = null) {

        if ($error != null) {
            $data['error'] = $error;
        }

        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        // load class and session data
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['quizzes'] = $this->quiz_model->get_all($class_id, $session_id);

        $data['main_content'] = 'quiz_admin/create_quiz';
        $this->load->view('includes/template', $data);
    }

    // action to create the quiz session
    function make($class_id, $session_id) {
        $this->form_validation->set_rules('title', 'title', 'trim|required');
        $this->form_validation->set_rules('type', 'quiz type', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim');

        // Load admin model and call respective function
        if ($this->form_validation->run() == FALSE) {
            $data['class_id'] = $class_id;
            $data['session_id'] = $session_id;
            $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
            $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);
            $data['quizzes'] = $this->quiz_model->get_all($class_id, $session_id);
            $data['main_content'] = 'quiz_admin/create_quiz';
            $this->load->view('includes/template', $data);
        } else {
            $title = $this->input->post('title');
            $type = $this->input->post('type');
            $description = $this->input->post('description');
            if ($this->quiz_model->create_quiz($class_id, $session_id, $title, $type, $description)) {
                $error = "Sorry the quiz could not be created!";
                $this->session($class_id, $session_id, $error);
            } else {
                $this->session($class_id, $session_id);
            }
        }
    }

    // Import from the current question list
    // some JQuery functionality to filterout results
    function import($class_id, $session_id, $quiz_id, $filter = null) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['main_content'] = 'quiz_admin/import';
        $this->load->view('includes/template', $data);
    }

    // Load view with question and at least 2 responses. Radio buttons for correct ans
    function add($class_id, $session_id, $quiz_id) {
        redirect("quiz/create_ques/add/$class_id/$session_id/$quiz_id");
    }

    // delete question
    function delete($class_id, $session_id, $quiz_id, $qid) {
        
    }

    // edit a question
    function edit($class_id, $session_id, $quiz_id, $qid) {
        
    }

}