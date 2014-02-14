<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assign_model extends CLASS_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('admin_model');
        $this->user_model->is_logged_in();
    }
    
    // ===========================
    // Upload basic info 
    //  -> name, points, type
    // ===========================
    function step1(){
        // Put information in programs table
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        $data['prog_name'] = $this->input->post('prog_name');
        $data['type'] = $this->input->post('type');
        $data['num_tc'] = $this->input->post('num_tc');

        $data['num_source'] = $this->input->post('num_source');
        $data['num_input'] = $this->input->post('num_input');
        $data['num_output'] = $this->input->post('num_output');
        $data['num_addition'] = $data['num_source'] + $data['num_input'] + $data['num_output'];

        $data['late'] = $this->input->post('late');
        $data['c_points'] = $this->input->post('c_points');
        $data['s_points'] = $this->input->post('s_points');
        $data['d_points'] = $this->input->post('d_points');
        $data['e_points'] = $this->input->post('e_points');
        $data['input'] = $this->input->post('cin');
        $data['output'] = $this->input->post('cout');

        $classinfo = $this->load_class_info($data['class_id']);

        //var_dump($classinfo); die();
        // other files

        $this->db->where('class_id', $data['class_id']);
        $this->db->where('session_id', $data['session_id']);
        $this->db->where('prog_name', $data['prog_name']);

        $count = $this->db->count_all_results($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs');

        if ($count > 0) {
            $this->db->where('class_id', $data['class_id']);
            $this->db->where('session_id', $data['session_id']);
            $this->db->where('prog_name', $data['prog_name']);
            $status = $this->db->update($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs', $data);
        } else {
            $status = $this->db->insert($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs', $data);
        }

        if (isset($status))
            return $status;
    }
    
    // ===========================
    // get file names
    // ===========================
    function step2(){
        
    }
    
    // ===========================
    // get SOURCE items
    // ===========================
    function step3(){
        
    }
    
    // ===========================
    // get INPUT items
    // ===========================
    function step4(){
        
    }
    
    // ===========================
    // Evaluate OUTPUT items
    // ===========================
    function step5(){
        
    }
    
    // ===========================
    // PROG Description
    // ===========================
    function step6(){
        
    }
    
    // ===================================================================
    
    // ===========================
    // HELPER FUNCTIONS
    // ===========================
    
    
}
?>
