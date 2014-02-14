<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blog extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->user_model->is_admin();
        $this->load->model('blog_model');
        $this->load->model('class_admin/blog_model');
    }

    function index($class_id = null) {
        if (isset($class_id)) {
            redirect('blog/cls/' . $class_id);
        } else {
            $data['classinfo'] = $this->admin_model->load_class_info($class_id);
            $data['classes'] = $this->admin_model->classes();
            $data['main_content'] = 'home';
            $this->load->view('includes/template', $data);
        }
    }

    function cls($class_id, $msg=null) {
        $data['class_id'] = $class_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        if ($msg != null){ $data['msg'] = $msg; }
        
        // get current blogs given the class id
        $data['blogs'] = $this->blog_model->load_class_blogs($class_id);
        
        $data['main_content'] = 'cls';
        $this->load->view('includes/template', $data);
    }
    
    function update_blog($class_id, $blog_id){
       if ( $this->blog_model->update_blog($class_id, $blog_id) ){
            $msg = "The post was successfully updated!";
            $this->cls($class_id, $msg);
            die();
        } else {
            $msg = "Sorry, an error occured. The post was not updated!";
            $this->cls($class_id, $msg);
            die();
        }
    }
    
    function insert_blog($class_id){
        if ( $this->blog_model->update_blog($class_id) ){
            $msg = "The post was successfully inserted!";
            $this->cls($class_id, $msg);
            die();
        } else {
            $msg = "Sorry, an error occured. The post was not inserted!";
            $this->cls($class_id, $msg);
            die();
        }
    }
    
    function delete_blog($class_id, $blog_id){
        if ( $this->blog_model->delete_blog($class_id, $blog_id) ){
            $msg = "The post was successfully deleted!";
            $this->cls($class_id, $msg);
            die();
        } else {
            $msg = "Sorry, an error occured. The post was not deleted!";
            $this->cls($class_id, $msg);
            die();
        }
    }
    
    function item($class_id, $blog_id){
        $data['class_id'] = $class_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        
        $data['blog'] = $this->blog_model->load_blog($class_id, $blog_id);
        $data['main_content'] = 'item';
        $this->load->view('includes/template', $data);
    }
}