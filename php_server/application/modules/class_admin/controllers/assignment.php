<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assignment extends CLASS_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('assign_model');
        $this->load->model('admin_model');
        $this->user_model->is_logged_in();
        $this->user_model->is_admin();
    }

    function index($class_id, $session_id) {
        $this->step1($class_id, $session_id);
    }
    
    // ===========================
    // form for BASIC info
    // ===========================
    function step1($class_id, $session_id) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['main_content'] = 'assign/step_1';
        $this->load->view('includes/template', $data);
    }
    
    function step1_eval($class_id, $session_id){
        $this->form_validation->set_rules('prog_name', 'Assignment Name', 'trim|required');
        $this->form_validation->set_rules('num_tc', 'Number of Test Cases', 'trim|required|numeric');

        $this->form_validation->set_rules('num_source', 'Number of Source Files', 'trim|required|numeric');
        $this->form_validation->set_rules('num_input', 'Number of Input Files', 'trim|required|numeric');
        $this->form_validation->set_rules('num_output', 'Number of Output Files', 'trim|required|numeric');
        //$this->form_validation->set_rules('num_addition', 'Number of Adittional Files', 'trim|required|numeric');

        $this->form_validation->set_rules('c_points', 'Compiliation points', 'trim|required|numeric');
        $this->form_validation->set_rules('s_points', 'Sumbission points', 'trim|required|numeric');
        $this->form_validation->set_rules('d_points', 'Documentation points', 'trim|required|numeric');
        $this->form_validation->set_rules('e_points', 'Execution Points', 'trim|required|numeric');
        $this->form_validation->set_rules('late', 'Late deduction points', 'trim|required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $data['class_id'] = $class_id;
            $data['session_id'] = $session_id;
            $data['main_content'] = 'assign/step_1';
            $this->load->view('includes/template', $data);
        } else {
            $check = $this->assign_model->step1($class_id, $session_id);

            if ($check != true) {
                $data['error'] = "Sorry there was an issue creating the assignment!";
            }
            $prog_name = $this->input->post('prog_name');
            $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);

            $data['info'] = $this->admin_model->load_basicinfo($class_id, $session_id, $prog_name);
            $data['fileinfo'] = $this->admin_model->load_filedetails($class_id, $prog_id[0]->id);

            // check if there are any additional files. If not move to stage 3
            if ($data['info'][0]->num_addition == 0) {
                $data['main_content'] = 'tc/tc_3';
            } else {
                $this->tc_model->update_stage($class_id, $prog_id[0]->id, 2);
                $data['main_content'] = 'tc/tc_2';  //echo $prog_id[0]->id .'<br /><pre>'; print_r($data); echo '</pre>'; die();
            }
            $this->load->view('includes/template', $data);
        }
    }
    
    // ===========================
    // form for file names
    // ===========================
    function step2(){
        
    }
    
    function step2_eval(){
        
    }
    
    // ===========================
    // form for SOURCE
    // ===========================
    function step3(){
        
    }
    
    function step3_eval(){
        
    }
    
    // ===========================
    // form for INPUT
    // ===========================
    function step4(){
        
    }
    
    function step4_eval(){
        
    }
    
    // ===========================
    // form for OUTPUT
    // ===========================
    function step5(){
        
    }
    
    function step5_eval(){
        
    }
    
    // ===========================
    // form for Decription
    // ===========================
    function step6(){
        
    }
    
    function step6_eval(){
        
    }
    
}
?>
