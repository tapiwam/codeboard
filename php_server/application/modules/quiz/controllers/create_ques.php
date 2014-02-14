<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Create_ques extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->user_model->is_admin();
        $this->load->model('ques_create_model');
        $this->load->model('class_admin/admin_model');
    }

    // load all the users classes and list them for the user to pick
    function index() {
        $data['classes'] = $this->admin_model->classes();
        $data['main_content'] = 'quiz_admin/home';
        $this->load->view('includes/template', $data);
    }

    function add($class_id, $session_id = null, $quiz_id = null) {
        $data['class_id'] = $class_id;
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        
        $data['main_content'] = 'quiz_admin/create/add1';
        $this->load->view('includes/template', $data);
    }

    function add_eval($class_id) {
        $data['class_id'] = $class_id;
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);

        // form validate 
        $this->form_validation->set_rules('title', 'question title', 'trim|required');
        $this->form_validation->set_rules('type', 'question type', 'required');
        $this->form_validation->set_rules('points', 'question points', 'trim|numeric|required');
        $this->form_validation->set_rules('terms', 'question search terms', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['main_content'] = 'quiz_admin/create/add1';
            $this->load->view('includes/template', $data);
        } else {
            $title = $this->input->post('title');
            $type = $this->input->post('type');
            $points = $this->input->post('points');
            $terms = $this->input->post('terms');
            
            list($s, $id) = $this->ques_create_model->create_one_ques($class_id, $title, $type, $points, $terms);
            if ($s) {
                // set the question id
                $data['ques_id'] = $id;
                
                // determin next page
                if ($type == "program") {
                    echo "program"; die();
                    $data['main_content'] = 'quiz_admin/create/add_prog1';
                } else {
                    $data['main_content'] = 'quiz_admin/create/add_mcq1';
                }
            } else {
                $data['error'] = "The question could not be created.";
                $data['main_content'] = 'quiz_admin/create/add1';
            }
            
            // load page
            $this->load->view('includes/template', $data);
        }
    }
    
    function add_mcq($class_id, $ques_id){
        $ques = $this->input->post('ques');
        $o = array(); // answer object
        $a = array(); // correct object
        
        $x = $this->input->post();
        
        // extract options and answer indicators into 2 objects 
        foreach($x as $key=>$item){
            $c = explode('_', $key);
            if($c[0] == 'option' ){
                $o[ $c[1] ]= $item;
            }
            
            if($c[0] == 'correct' ){
                $a[ $c[1] ] = $item;
            }
        }

        // merge these objects into 1 object
        $opt = array();
        
        foreach($o as $key=>$val){
            $c = new stdClass();
            $c->ans = $val;
            foreach($a as $key1=>$val1){
                if($key1 == $key){
                    $c->correct = $val1;
                    break;
                }
            }
            $opt[] = $c;
        }
        
        // Insert question 
        $this->ques_create_model->update_ques($class_id, $ques_id, $ques);
        
        // Insert Options and ansers
        $this->ques_create_model->insert_options($class_id, $ques_id, $opt);
        
        
        $data['x'] = $opt ;
        
        $data['main_content'] = 'quiz_admin/create/temp';
        $this->load->view('includes/template', $data);
    }

}

?>
