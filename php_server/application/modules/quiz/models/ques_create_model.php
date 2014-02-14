<?php

class Ques_create_model extends CLASS_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('class_admin/admin_model');
        $this->user_model->is_logged_in();
    }

    function create_one_ques($class_id, $title, $type, $points, $terms=null){
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_ques';
        
        $data = array(
            'title' => $title,
            'type' => $type,
            'points' => $points,
            'terms' => $terms,
            'active' => 0,
        );
        
        $s = $this->db->set($data)->insert($table);
        $id = $this->db->insert_id();
        return array($s, $id);
    }
    
    function update_ques($class_id, $ques_id, $ques) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_ques';
        
        $data = array( 'question' => $ques );
        
        return $this->db->where('id', $ques_id)->set($data)->update($table);
    }
    
    function insert_options($class_id, $ques_id, $opt){
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_ques_choices';
        
        foreach($opt as $item){
            $count = $this->db->where('id', $ques_id)->where('ans', $item->ans)->count_all_results($table);
            if( $count > 0 ){
                $data['correct'] = $item->correct;
                $this->db->where('id', $ques_id)->set($data)->update($table);
            } else {
                $data['ans'] = $item->ans;
                $data['correct'] = $item->correct;
                $this->db->set($data)->insert($table);
            }
        }
        
    }
    

}

?>
