<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gradebk extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        //$this->user_model->is_admin();
        $this->load->model('gradebk_model');
        $this->load->model('admin_model');
        $this->load->model('tc_model');
    }

    function index($class_id = null) {
        $seg = $this->uri->segment(3);
        //echo $seg; die();

        if (isset($class_id)) {
            redirect('class_admin/gradebk/grades/' . $class_id);
        } else if (!isset($seg) || $seg == "") {
            $data['classes'] = $this->admin_model->classes();
            $data['main_content'] = 'gradebk/home';
            $this->load->view('includes/template', $data);
        } else if (isset($seg) && $seg != 'index') {
            redirect('class_admin/gradebk/grades/' . $this->uri->segment(3));
        }
    }

    function grades($class_id) {

        // check if any filters have been applied
        $order = null;
        $o = $this->input->post('order');

        if ($o != false) {
            $order = $o;
        }

        // Get information for form
        $data['class_id'] = $class_id;
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['programs'] = $this->gradebk_model->get_programs($class_id);
        $data['students'] = $this->gradebk_model->get_scores($class_id, $order);
        $data['possible'] = $this->gradebk_model->get_possible_points($class_id);

        $data['main_content'] = 'gradebk/gradebook';
        $this->load->view('includes/template', $data);
    }

    function student_grades($class_id, $student_id) {
        if ($this->gradebk_model->valid_student($class_id, $student_id)) {
            $data['class_id'] = $class_id;
            $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);

            $data['possible'] = $this->gradebk_model->get_possible_points($class_id);
            $data['stud'] = $this->admin_model->load_student_info($student_id);
            $data['stud_avg'] = $this->gradebk_model->get_student_avg($class_id, $student_id);

            // pull program info for each item and add it to the same array
            $grades = $this->admin_model->load_student_grades($class_id, $student_id);
            foreach ($grades as $item) {
                $item->prog_info = $this->admin_model->pull_prog($class_id, $item->prog_id);
            }
            $data['grades'] = $grades;

            // append program info to programs
            $p = $this->gradebk_model->get_programs($class_id);
            $programs = array();

            foreach ($p as $item) {
                $x = explode('_', $item); // extract prog_id
                $y = new stdClass(); // template
                $y->prog_info = $this->tc_model->load_prog_info($class_id, $x[1]); // pull data
                $y->prog_info = $y->prog_info[0];
                $y->prog_info->session = $this->tc_model->load_session_info($class_id, $y->prog_info->session_id);
                $y->prog_info->session = $y->prog_info->session[0];

                // var_dump($y->prog_info->session); die();

                $y->prog_info->possible = $y->prog_info->c_points +
                        $y->prog_info->s_points +
                        $y->prog_info->d_points +
                        $y->prog_info->e_points;
                $y->prog_info->deadline = date("F j, Y", strtotime($y->prog_info->session->late)); //." - ".date("H:i", strtotime($y->prog_info->session->late)) ;

                if ($y->prog_info->graded == 1) {
                    $y->prog_info->graded = "yes";
                } else {
                    $y->prog_info->graded = "no";
                }

                $programs[] = $y; // replace with real data
            }

            //var_dump($programs); die();
            $data['programs'] = & $programs;

            $data['main_content'] = 'gradebk/student_grades';
            $this->load->view('includes/template', $data);
        } else {
            //echo "Bad URL"; die();
            redirect($this->session->userdata('refered_from'));
        }
    }

    function student_report($user_id, $class_id, $session_id, $prog_name) {
        if ($this->gradebk_model->valid_student($class_id, $user_id)) {
            $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
            $pid = $prog_id[0]->id;

            $data['class_id'] = $class_id;
            $data['session_id'] = $session_id; // id for 
            $data['prog_name'] = $prog_name;
            $data['prog_id'] = $pid;

            // Query database for all infor in that session/assignment
            $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
            $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);
            $data['prog'] = $this->admin_model->load_assignment($data['class_id'], $data['session_id'], $pid);
            $data['info'] = $this->admin_model->load_assignment($class_id, $session_id, $pid);

            $data['report'] = $this->admin_model->load_student_result($class_id, $user_id, $pid);
            $data['stud'] = $this->admin_model->load_student_info($user_id);

            $data['main_content'] = 'gradebk/prog_report';
            $this->load->view('includes/template', $data);
        } else {
            redirect("class_admin/gradebk/student_grades/$class_id/$user_id");
        }
    }
    
    function student_prog_analytics($user_id, $class_id, $session_id, $prog_name) {
        if ($this->gradebk_model->valid_student($class_id, $user_id)) {
            $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
            $pid = $prog_id[0]->id;

            $data['class_id'] = $class_id;
            $data['session_id'] = $session_id; // id for 
            $data['prog_name'] = $prog_name;
            $data['prog_id'] = $pid;

            // Query database for all infor in that session/assignment
            $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
            $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);
            $data['prog'] = $this->admin_model->load_assignment($data['class_id'], $data['session_id'], $pid);
            $data['info'] = $this->admin_model->load_assignment($class_id, $session_id, $pid);

            $data['report'] = $this->admin_model->load_student_result($class_id, $user_id, $pid);
            $data['stud'] = $this->admin_model->load_student_info($user_id);
            
            //var_dump($data['stud']); die();
            $data['name'] = $data['stud']->first_name." ".$data['stud']->last_name;
            $data['uid'] = $user_id;

            $data['main_content'] = 'analysis/analytics_prog';
            $this->load->view('includes/template', $data);
        } else {
            redirect("class_admin/gradebk/student_grades/$class_id/$user_id");
        }
    }

    // =============================================

    function grade_scale($class_id) {
        // get data and send to display

        $data['class_id'] = $class_id;
        $data['programs'] = $this->gradebk_model->get_grade_scale($class_id);
        $data['main_content'] = 'gradebk/grade_scale';
        $this->load->view('includes/template', $data);
    }

    function edit_scale($class_id) {
        // get data and send to display

        $data['class_id'] = $class_id;
        $data['programs'] = $this->gradebk_model->get_grade_scale($class_id);
        $data['main_content'] = 'gradebk/edit_scale';
        $this->load->view('includes/template', $data);
    }

    function update_scale($class_id) {
        $stat = $this->gradebk_model->update_grade_scale($class_id);

        if ($stat) {
            redirect("gradebk/grade_scale/$class_id");
        } else {
            $data['error'] = "Ooops.. one of the entiries was NOT updated properly!";
            $data['class_id'] = $class_id;
            $data['programs'] = $this->gradebk_model->get_grade_scale($class_id);
            $data['main_content'] = "gradebk/grade_scale/$class_id";
            $this->load->view('includes/template', $data);
        }
    }

    // ===============================================
}
