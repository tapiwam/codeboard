<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_model extends CLASS_Model {

    function __contruct() {
        $this->load->dbforge();
    }

    // ==========================================================================

    function classes() {
        // return class list for given instructor
        $this->db->where('instructor', $this->session->userdata('username'));
        $q = $this->db->get('classes');

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        
//        $this->db->where('user_id', $this->session->userdata('id'));
//        $q = $this->db->get('class_instructors')->result();
//        foreach($q as $item){
//            
//        }
        
    }
    
    // grab all classes that the instructor is registered for
    function all_classes($user_id){
        $q = $this->db->where('user_id', $user_id)
                ->get('class_instructors')
                ->result();
        
        foreach ($q as $c){
            $cls = $this->db->where('id', $c->class_id)->get('classes')->row();
            $data[] = $cls;
        }
        
        if ( isset($data) && !empty($data) ){ return $data; }
    }
    
    // does instruct have access or not???
    function class_access($class_id){
        $user_id = $this->session->userdata('id');
        $q = $this->db->where('class_id', $class_id)
                ->count_all_results('class_instructors');
        
        if($q > 0){ return true; }
    }
    
    // security feature to enforce weather instructor has access
    function check_class_access($class_id){
        if(!$this->check_access($class_id) ){
            redirect('class_admin');
        }
    }
    
    //=======================================
    // load all instuctors
    function load_instructors()
    {
       return $this->db->where('level', '2')
                ->get('users') ->result();
    }
    
    function load_instructor_info($user_id){
        
    }
    
    function add_instructor($class_id, $ins){
        $q = $this->load_student_info($ins); // technically grabs user data
        $data = array(
            'class_id' => $class_id,
            'user_id' => $q->id,
            'username' => $q->username
        );
        
        //var_dump($data); echo '</hr>';
        
        if ( $this->db->where('user_id', $ins)->where('class_id', $class_id)
                ->count_all_results('class_instructors') == 0 ){
            
            return $this->db->insert('class_instructors', $data);
        }
        
    }
    
    //=======================================
    
    function load_testcases($class_id, $session_id, $assign_id) {
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];

        // find tyhe correct table
        $p_table = $classinfo->term . '_' . $classinfo->class_name . '_testcases';

        // grab from DB
        $this->db->where('assign_id', $assign_id);
        // select the latest version
        $this->db->select_max('version');  // might have to group data
        // 
        $q = $this->db->get($p_table);
        foreach ($q->result() as $i) {
            $data[] = $i;
        }
        return $data;
    }

    function insert_tc_input($proginfo, $size, $tc_num, $raw) {
        // compile and run first 
        $data['class_id'] = $proginfo[0]->class_id;
        $data['session_id'] = $proginfo[0]->session_id;
        $data['assign_id'] = $proginfo[0]->id;
        $classinfo = $this->admin_model->load_class_info($data['class_id']);

        $p_table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_testcases';

        // loop through all the test cases and save these to the DB
        for ($i = 0; $i < $size; $i++) {
            // get the current version and increment the index of
            $version = $this->db->select('version')
                            ->where('assign_id', $data['assign_id'])
                            ->where('tc_num', $tc_num)
                            ->get($p_table)->row();

            echo "<hr />version::   ";
            print_r($version); //die();

            if (isset($version) && $version->version > 0) {
                $data['version'] = $version->version + 1;
            } else {
                $data['version'] = 1;
            }

            // set the other variales
            $data['tc_input'] = $this->input->post('input_' . $i);
            $data['tc_num'] = $i;

            // insert into DB
            $this->db->insert($p_table, $data);
        }

        // could insert into repository here
    }

    function session_dates($class_id, $session_id) {
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];

        // find the correct table
        $table = $classinfo->term . '_' . $classinfo->class_name . '_sessions';

        return $this->db->where('id', $session_id)
                        ->get($table)->row();
    }

    function set_session_dates($class_id, $session_id) {
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];

        // find the correct table
        $table = $classinfo->term . '_' . $classinfo->class_name . '_sessions';
        
        //var_dump($this->input->post('start')); 
        
        $date = new DateTime();
        $date->setTimestamp($this->input->post('start'));
        $data['start'] = $date->format('Y-m-d H:i:s');
        
        $date->setTimestamp($this->input->post('end'));
        $data['end'] = $date->format('Y-m-d H:i:s');
        
        $date->setTimestamp($this->input->post('late'));
        $data['late'] = $date->format('Y-m-d H:i:s');
        
        //var_dump($data); die();

        return $this->db->where('id', $session_id)
                        ->set($data)->update($table);
    }
    
    // ==========================================

    function load_student_result($class_id, $user_id, $prog_id) {

        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];

        // find the correct table
        $table = $classinfo->term . '_' . $classinfo->class_name . '_results';

        $q = $this->db->select('report')
                ->where('user_id', $user_id)
                ->where('prog_id', $prog_id)
                ->get($table)
                ->row();

        if( isset( $q->report ) ){ return $q->report; }
    }

    function load_student_grades($class_id, $user_id) {
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];

        // find the correct table
        $table = $classinfo->term . '_' . $classinfo->class_name . '_results';
        
        return $this->db->where('user_id', $user_id)
                ->get($table)
                ->result();
    }

    function load_student_info($user_id) {
        return $this->db->where('id', $user_id)->get('users')->row();
    }
    
    function load_student_files($user_id, $class_id, $prog_id){
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_student_files';
        
        $q1 = $this->db->where('user_id', $user_id)->where('prog_id', $prog_id)
                ->get($table)
                ->result();
        
        // echo '<pre>'; var_dump($q1); echo '</pre>'; die();
        
        return $q1;
    }
    
    // ==============================================
    
    function pull_prog($class_id, $prog_id){
        $data = new stdClass();
        
        $classinfo = $this->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $ptable = $classinfo->term . '_' . $classinfo->class_name . '_programs';
        $rtable = $classinfo->term . '_' . $classinfo->class_name . '_results';
        $stable = $classinfo->term . '_' . $classinfo->class_name . '_sessions';
        
        $q = $this->db->where('id', $prog_id)
                ->get($ptable)->row();
        $data->id = $q->id;
        $data->prog_name = $q->prog_name;
        $data->possible = $q->s_points +$q->d_points + $q->e_points + $q->c_points;
        $data->published = $this->yes_no($q->published);
        $data->graded = $this->yes_no($q->graded);
        
        $qs = $this->db->where('id', $q->session_id)
                ->get($stable)
                ->row();
        
        $data->session_id = $qs->id;
        $data->session_name = $qs->session_name;
        $data->start = date("D F j, Y", strtotime($qs->start));
        $data->end = date("D F j, Y", strtotime($qs->end));
        $data->late = date("D F j, Y", strtotime($qs->late));
        
        return $data;
    }
    
    function yes_no($item){
        if($item){
            return "yes";
        } else {
            return "no";
        }
    }
    
    function date_msg($date){
        $now = date("Y-m-d H:i:s");
        if( date("Y-m-d", strtotime($now)) == date("Y-m-d", strtotime($date)) )
        {
            $msg = "Today";
        } 
        else if( date("Y-m-d", strtotime($date)) == date("Y-m-d", strtotime($now)) )
        {
            
        }
    }
    
    

}