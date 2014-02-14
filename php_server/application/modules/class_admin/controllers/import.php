<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import extends CLASS_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_admin();
        $this->load->model('admin_model');
        $this->load->model('import_model');
        $this->load->model('blog_model');
    }
    
    // select a lab to import into
    function index($class_id, $session_id){
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['classes'] = $this->admin_model->all_classes($this->session->userdata('id'));
        
        $data['main_content'] = 'import/home';
        $this->load->view('includes/template', $data);
    }
    
    // ================================================
    
    //display available sessions/labs
    function cls($class_id, $session_id, $external_class_id){
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        
        $data['external_class_id'] = $external_class_id;
        $data['external_classinfo'] = $this->admin_model->load_class_info($external_class_id);
        $data['external_sessions'] = $this->admin_model->load_all_sessions($external_class_id);
        
        $data['main_content'] = 'import/cls';
        $this->load->view('includes/template', $data);
    }
    
    // show all labs along with their assignments
    function session($class_id, $session_id, $external_class_id, $external_session_id){
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        
        $data['external_class_id'] = $external_class_id;
        $data['external_session_id'] = $external_session_id;
        $data['external_classinfo'] = $this->admin_model->load_class_info($external_class_id);
        $data['external_sessions'] = $this->admin_model->load_all_sessions($external_class_id);
        
        $data['external_sessioninfo'] = $this->admin_model->load_session_info($external_class_id, $external_session_id);
        $data['assignments'] = $this->admin_model->load_session_assignments($external_class_id, $external_session_id);
        $data['blogs'] = $this->blog_model->get_all($external_class_id, $external_session_id);
        
        $data['main_content'] = 'import/session';
        $this->load->view('includes/template', $data);
    }
    
    // ================================================
    // Show assignments for external class
        // function assignments($class_id, $session_id, $external_class_id, $external_session_id){ }
    
    // show blogs for external class
        // function blogs($class_id, $session_id, $external_class_id, $external_session_id){ }
    
    // ================================================
    
    // import into the lab
    function import_assign($class_id, $session_id, $external_class_id, $external_session_id, $external_prog_id){        
        if( $this->import_model->import_assign($class_id, $session_id, $external_class_id, $external_session_id, $external_prog_id) ){
            // worked redirect back to session home
                //die();
            redirect("class_admin/sessions/$class_id/$session_id");
        } else {
            // error
            echo 'failed to import'; die();
        }
        
    }
    
    // import into the lab
    function import_blog($class_id, $session_id, $external_class_id, $external_session_id, $external_blog_id){
        if( $this->import_model->import_blog($class_id, $session_id, $external_class_id, $external_session_id, $external_blog_id) ){
            // worked redirect back to session home
            redirect("class_admin/sessions/$class_id/$session_id");
        } else {
            // error
            echo 'failed to import'; die();
        }
    }
    
    function import_lab($class_id, $session_id, $external_class_id, $external_session_id)
    {
        $this->import_model->import_lab($class_id, $session_id, $external_class_id, $external_session_id);
        redirect("class_admin/sessions/$class_id/$session_id");
    }
    
}

?>
