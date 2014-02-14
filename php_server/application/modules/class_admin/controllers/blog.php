<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blog extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('blog_model');
        $this->load->model('admin_model');
        $this->user_model->is_admin();
    }

    function index($class_id, $session_id, $error = null) {
        if ($error != null) {
            $data['error'] = $error;
        }

        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        // load class data
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);

        // load session data
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['blogs'] = $this->blog_model->get_all($class_id, $session_id);

        //$data['main_content'] = 'admin/create_assignment';
        $data['main_content'] = 'blog/home';
        $this->load->view('includes/template', $data);
    }

    function create_option($class_id, $session_id) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        // load class data
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);

        // load session data
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['blogs'] = $this->blog_model->get_all($class_id, $session_id);

        //$data['main_content'] = 'admin/create_assignment';
        $data['main_content'] = 'blog/create';
        $this->load->view('includes/template', $data);
    }

    function create($class_id, $session_id) {
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('content', 'blog content', 'trim|required');

        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);
        $data['blogs'] = $this->blog_model->get_all($class_id, $session_id);

        if ($this->form_validation->run() == FALSE) {
            $data['main_content'] = 'blog/home';
            $this->load->view('includes/template', $data);
        } else {
            $d['title'] = $this->input->post('title');
            $d['content'] = $this->input->post('content');
            $d['created'] = date('Y-m-d H:m:s');
            $d['updated'] = date('Y-m-d H:m:s');
            $d['session_id'] = $session_id;
            $d['author'] = $this->session->userdata('username');
            $d['description'] = $this->input->post('description');

            if ($this->blog_model->create($class_id, $d)) {
                $this->index($class_id, $session_id);
            } else {
                $data['blogs'] = $this->blog_model->get_all($class_id, $session_id);
                $error = "Sorry, something went wrong. The blog post was not created.";
                $this->index($class_id, $session_id, $error);
            }
        }
    }
    
    function view($class_id, $session_id, $blog_id){
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        // load class data
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);

        // load session data
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['blog_item'] = $this->blog_model->get_one($class_id, $blog_id);
        
        //$data['main_content'] = 'admin/create_assignment';
        $data['main_content'] = 'blog/view';
        $this->load->view('includes/template', $data);
    }
    
    function v($class_id, $session_id, $blog_id){
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        // load class data
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);

        // load session data
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['blog_item'] = $this->blog_model->get_one($class_id, $blog_id);
        
        //$data['main_content'] = 'admin/create_assignment';
        $data['main_content'] = 'blog/view_v';
        $this->load->view('includes/template', $data);
    }
    
    function edit($class_id, $session_id, $blog_id){
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        // load class data
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);

        // load session data
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['blog_item'] = $this->blog_model->get_one($class_id, $blog_id);

        //$data['main_content'] = 'admin/create_assignment';
        $data['main_content'] = 'blog/create';
        $this->load->view('includes/template', $data);
    }
    
    function update($class_id, $session_id, $blog_id) {
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('content', 'blog content', 'trim|required');

        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        if ($this->form_validation->run() == FALSE) {
            $data['blogs'] = $this->blog_model->get_all($class_id, $session_id);
            $data['main_content'] = 'blog/home';
            $this->load->view('includes/template', $data);
        } else {
            $d['title'] = $this->input->post('title');
            $d['content'] = $this->input->post('content');
            $d['updated'] = date('Y-m-d H:m:s');
            $d['description'] = $this->input->post('description');
            
            if ($this->blog_model->update($class_id, $blog_id, $d)) {
                $this->index($class_id, $session_id);
            } else {
                $data['blogs'] = $this->blog_model->get_all($class_id, $session_id);
                $error = "Sorry, something went wrong. The blog post was not updated.";
                $this->index($class_id, $session_id, $error);
            }
        }
    }

    function delete($class_id, $session_id, $blog_id) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        if ($this->blog_model->delete($class_id, $blog_id)) {
            $data['blogs'] = $this->blog_model->get_all($class_id, $session_id);
            $data['main_content'] = 'blog/home';
            $this->load->view('includes/template', $data);
        } else {
            $data['error'] = "Sorry, something went wrong. The blog post was not deleted.";
            $data['blogs'] = $this->blog_model->get_all($class_id, $session_id);
            $data['main_content'] = 'blog/home';
            $this->load->view('includes/template', $data);
        }
    }

}