<?php

class Quiz_model extends CLASS_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('class_admin/admin_model');
        $this->user_model->is_logged_in();
    }

    function get_all($class_id, $session_id) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_quiz';
        
        $cr = array('session_id' => $session_id);
        return $this->db->where($cr)->get($table)->result();
    }
    
    function get_quiz($class_id, $session_id, $quiz_id) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $t1 = $classinfo->term . '_' . $classinfo->class_name . '_quiz_ques';
        
        // get all questions from question table
        $ques = $this->db->where('quiz_id', $quiz_id)->get($t1)->result();
        
        // get build question objects from question numbers
        foreach( $ques as $q ){
            $d = $this->get_mcq_question($class_id, $q->ques_id);
            $data1[] = $q;
            echo '<pre>' . var_dump($q, true) . '</pre><hr />';
        }
        
    }
    
    function get_mcq_question($class_id, $ques_id){
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $t1 = $classinfo->term . '_' . $classinfo->class_name . '_ques';
        $t2 = $classinfo->term . '_' . $classinfo->class_name . '_ques_choices';
        
        $data = new stdClass();
        
        // load question info
        $q = $this->db->where('id', $ques_id)->get($t1)->row();
        $data->id = $q->id;
        $data->question = $q->question;
        
        // get all answers and add to array
        $ans = array();
        $q1 = $this->db->where('ques_id', $ques_id)->get($t2)->result();
        foreach ($q1 as $q1x){
            $a->ans = $q1x->ans;
            $a->correct = $q1x->correct;
            $ans[] = $a;
        }
        
        $data->answers = $ans;
        
        return data;
    }
    
    function num_ques($class_id, $quiz_id){
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_quiz_ques';
        
        return $this->db->where('quiz_id', $quiz_id)->count_all_results($table);
    }

    function create_quiz($class_id, $session_id, $title, $type, $description){
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_quiz';
        
        $data = array(
            'class_id'=> $class_id,
            'session_id'=> $session_id,
            'title' => $title,
            'type' => $type,
            'description' => $description,
        );
        
        $cr1 = array('session_id' => $session_id, 'title' => $title);
        $v1 = $this->db->where($cr1)->count_all_results($table);
        if($v1 > 0){
            return false;
        } else {
            return $this->db->set($data)->insert($table);
        }
    }
    
    function check_tables($class_id){
        
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $t1 = $classinfo->term . '_' . $classinfo->class_name . '_quiz';
        $t2 = $classinfo->term . '_' . $classinfo->class_name . '_quiz_ques';
        $t3 = $classinfo->term . '_' . $classinfo->class_name . '_quiz_answers';
        $t4 = $classinfo->term . '_' . $classinfo->class_name . '_quiz_mcq';
        $t5 = $classinfo->term . '_' . $classinfo->class_name . '_ques';
        $t6 = $classinfo->term . '_' . $classinfo->class_name . '_ques_prog';
        
        if( !$this->db->table_exists( $t1 ) || 
                !$this->db->table_exists( $t2 ) || 
                !$this->db->table_exists( $t3 ) ||
                !$this->db->table_exists( $t4 ) ||
                !$this->db->table_exists( $t5 ) ||
                !$this->db->table_exists( $t6 ) )
        {
            $this->load->model('q_tables_model');
            $this->q_tables_model->init($classinfo->term, $classinfo->class_name);
        } 
    }

}

?>
