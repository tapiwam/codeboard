<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gradebk_model extends CLASS_Model {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->load->model('admin_model');
    }

    function get_scores($class_id, $order_by=null) {
        $this->update_avg($class_id);
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_gradebook';

        // Get list of availbale grades
        $f = $this->db->list_fields($table);
        $fields = array();
        $p = array();

        // get the assignment fields
        foreach ($f as $i) {
            if (!( $i == 'id' || $i == 'user_id' || $i == 'total' || $i == 'avg' || $i == 'grade')) {
                $fields[] = $i;    // echo $this->admin_model->assign_from_id($class_id ,$i).'<br />';
            }
        }

        // Get student records for that class
        $q = $this->db->order_by('avg', 'desc')->get($table)->result();
        
        // start compiling student records
        // run through all the students and put in the correct format
        $students = array();
        foreach ($q as $stud) {    //echo '<hr>STUDENT -->>>' . print_r($stud, TRUE) . '<hr />';
            $obj = new stdClass();
            $s['id'] = $stud->user_id;
            $s['name'] = $this->user_model->name_from_id($stud->user_id);
            $s['uname'] = $this->user_model->username_from_id($stud->user_id);
            $s['lname'] = $this->user_model->lastname_from_id($stud->user_id);
            $s['total'] = $stud->total;
            $s['avg'] = $stud->avg;
            $s['grade'] = $stud->grade;

            // compile scores accoring to posted stuff
            $scores = array();
            foreach ($fields as $i) {   //echo 'FIELD: '. $i . '<br />';
                $scores[] = $stud->$i;
            }

            $s['scores'] = $scores;

            // convert array into object
            foreach ($s as $key => $value) {
                $obj->$key = $value;
            }

            // add record to students
            $students[] = $obj;
        }
        
        // order by the given order if not avg by default
        if( isset($order_by) && !empty($order_by)){
            //var_dump($students); die();
            if($order_by == 'name'){usort($students, function($a, $b){return strcmp($a->name, $b->name);});}
            
            if($order_by == 'uname'){usort($students, function($a, $b){return strcmp($a->uname, $b->uname);});}
            
            if($order_by == 'lname'){usort($students, function($a, $b){return strcmp($a->lname, $b->lname);});}
            
        }
        
        return $students;
    }
    
    function get_student_avg($class_id, $user_id){
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_gradebook';

        // Get student records for that class
        return $this->db->select('total, avg, grade')
                ->where('user_id', $user_id)->get($table)->row();
    }
    

    function get_programs($class_id) {
        $classinfo = $this->load_class_info($class_id);
        //echo "$class_id<hr />";
        //var_dump($classinfo); die();

        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_gradebook';
        $fields = array();

        // Get list of availbale grades
        $f = $this->db->list_fields($table);

        foreach ($f as $i) {
            if (!( $i == 'id' || $i == 'user_id' || $i == 'total' || $i == 'avg' || $i == 'grade' || $i == 'prog_id')) {
                $fields[] = $i;
            }
        }
        return $fields;
    }

    function get_student_grades($class_id, $student_id) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_gradebook';

        $this->db->where('user_id', $student_id);
        return $this->db->get($table)->row();
    }

    // =====================================

    function get_grade_scale($class_id) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';

        $q = $this->db->select('id, session_id, prog_name, active, graded')
                ->get($table)
                ->result();

        $data = array();
        foreach ($q as $prog) {
            $prog->session_name = $this->admin_model->sessionname_from_sessionid($class_id, $prog->session_id);
            $data[] = $prog;
        }

        return $data;
    }

    function update_grade_scale($class_id) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';

        $items = $this->get_grade_scale($class_id);

        $check = true;
        foreach ($items as $item) {
            $c = $this->input->post($item->id);

            $x = $this->db->where('id', $item->id)
                    ->set('graded', $c)
                    ->update($table);
            $check = $check && $x;
        }

        return $x;
    }

    // =====================================

    function update_avg($class_id) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_gradebook';
        $pt = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';

        $q = $this->db->get($table)->result();

        // Get list of availbale grades
        $f = $this->db->list_fields($table);
        $fields = array();
        $p = array();

        // Get possible points
        $possible = $this->get_possible_points($class_id);

        // get the assignment fields
        foreach ($f as $i) {
            if (!( $i == 'id' || $i == 'user_id' || $i == 'total' || $i == 'avg' || $i == 'grade' )) {
                $fields[] = $i;
            }
        }

        $students = array();
        foreach ($q as $stud) {
            $this->consolidate_student($class_id, $stud->user_id);
        }
    }

    function get_possible_points($class_id) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';
        $possible = 0;

        $qr = $this->db->select('s_points, e_points, d_points, c_points')
                        ->where('active !=', 0)
                        ->where('graded', 1)
                        ->get($table)->result();

        foreach ($qr as $q) {
            $score = $q->s_points + $q->e_points + $q->d_points + $q->c_points;
            $possible += $score;
        }

        // echo "Possible Points: $possible '<hr />";
        return $possible;
    }

    function consolidate_student($class_id, $user_id) {

        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_gradebook';

        // Get list of availbale grades
        $f = $this->db->list_fields($table);
        $fields = array();
        $p = array();
        foreach ($f as $i) {
            if (!( $i == 'id' || $i == 'user_id' || $i == 'total' || $i == 'avg' || $i == 'grade')) {
                $fields[] = $i;    // echo $this->admin_model->assign_from_id($class_id ,$i).'<br />';
            }
        }

        // Get possible points
        $possible = $this->get_possible_points($class_id);

        // Get student record
        $stud = $this->db->where('user_id', $user_id)->get($table)->row();

        if (isset($stud)) {
            // compile total
            $total = 0;   // student's total points
            $scores = array();
            foreach ($fields as $i) {
                $total += (int) $stud->$i;
                // echo "Total now -> $total <br />";
            }

            // find avg
            if ($possible > 0) {
                $avg = round($total / $possible * 100);
            } else {
                $avg = 0;
            }

            // determin grade
            if ($avg < 60) {
                $grade = 'F';
            } else if ($avg < 70) {
                $grade = 'D';
            } else if ($avg < 80) {
                $grade = 'C';
            } else if ($avg < 90) {
                $grade = 'B';
            } else {
                $grade = 'A';
            }

            // update the students table entry
            $data['total'] = $total;
            $data['avg'] = $avg;
            $data['grade'] = $grade;

            return $this->db->where('id', $stud->id)
                            ->update($table, $data);
        }
    }

    function valid_student($class_id, $user_id) {
        
        $q = $this->db->where('user_id', $user_id)
                ->where('class_id', $class_id)
                ->count_all_results('registration');
        
        if($q > 0){ return true; } else { return false; }
    }

}

