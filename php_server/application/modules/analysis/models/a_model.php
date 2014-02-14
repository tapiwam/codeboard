<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class A_model extends CLASS_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('class_admin/admin_model');
        $this->load->model('class_admin/gradebk_model');
        $this->user_model->is_logged_in();
    }

    // ==============================
    // Assignment Specific Data
    // ==============================
    
    // Individual avarage for an assignemnt
    function avg_assign_ind($user_id, $class_id, $session_id, $prog_name) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_log_submits';
    }
    
    // Class average for an assignment (look at final scores only)
    function avg_assign_class($class_id, $session_id, $prog_name) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_log_submits';
    }
    
    //average for an attempts for the individual
    function avg_attempts_ind($class_id, $session_id, $prog_name) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_log_submits';
    }
    
    //average for an attempts for the class
    function avg_attempts_class($class_id, $session_id, $prog_name) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_log_submits';
    }
    
    // All grades for an assignment
    function grades_assign_ind($user_id, $class_id, $session_id, $prog_name) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_log_submits';
        $possible = 1;
        
        $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        $avg = $this->prog_avg($class_id, $session_id, $prog_name);
        
        $criteria = array('prog_id'=>$pid, 'user_id'=>$user_id, );
        
        $r = $this->db->select('time ,submits, score, c_points, e_points, d_points, s_points, late')
                    ->where($criteria)
                    ->get($table)
                    ->result();
        
        $possible = $this->get_assign_points($class_id, $session_id, $prog_name);
        // echo "Possible: $possible<br />";
        foreach($r as $rep){
            $rep->class_avg = $avg;
            $rep->avg = strval(round( ($rep->score/$possible)*100 , 2) );
            // echo "Score: {$rep->score} Avg: {$rep->avg}<hr />";
        }
        return $r;
    }
    
    // find elapsed time
    
    // return log activity for 
    
    // Get possible points for that assignment
    function get_assign_points( $class_id, $session_id, $prog_name ) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_programs';

        $r = $this->db->select('e_points, s_points, d_points, c_points')
                ->where('session_id', $session_id)
                ->where('prog_name', $prog_name)
                ->get($table)->row();
        return $r->e_points + $r->s_points + $r->d_points + $r->c_points;
    }
    
    // find the standard deviation
    function prog_sd( $class_id, $session_id, $prog_name ){
        return sqrt( $this->prog_variance($class_id, $session_id, $prog_name) ) ; 
    }
    
    // find the variance
    function prog_variance( $class_id, $session_id, $prog_name ){
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_results';
        
        $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        
        $scores = $this->db->select('score')
                            ->where('prog_id', $pid)
                            ->get($table)->result();
        
        $ss = 0; // (x minus the mean squared)
        $mean = $this->prog_avg($class_id, $session_id, $prog_name);
        $num = $this->prog_size($class_id, $session_id, $prog_name);
        
        foreach($scores as $s){
            $ss += pow( ($s->score - $mean), 2);
        }
        return $ss / $num;
    }


    // find average given the number of people that took the assignment
    function prog_avg( $class_id, $session_id, $prog_name ){
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_results';
        
        $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        
        $scores = $this->db->select('score')
                            ->where('prog_id', $pid)
                            ->get($table)->result();
        $total = 0;
        foreach ($scores as $s){
            $total += $s->score;
        }
        
        return $total / $this->prog_size($class_id, $session_id, $prog_name);
    }
    
    // find the number of people that took the assignment
    function prog_size( $class_id, $session_id, $prog_name ){
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_results';
        
        $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        
        return $this->db->where('prog_id', $pid)
                    ->from($table)
                    ->count_all_results();
    }
    
    // COde growth
    function lines_assign_ind($user_id, $class_id, $session_id, $prog_name){
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];
        $table = $classinfo->term . '_' . $classinfo->class_name . '_log_submits';
        
        $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        
        $lines = $this->db->select('submits, time, lines')
                    ->where('prog_id', $pid)
                    ->where('user_id', $user_id)
                    ->get($table)
                    ->result();
            //var_dump($lines); die();
        
        return $lines;
    }
}