<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api extends CLASS_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->user_model->is_student();
        $this->load->model('class_admin/admin_model');
        $this->load->model('student_model');
        $this->load->model('blog_model');
        $this->load->model('class_admin/tc_model');
        $this->load->model('log_model');
        $this->load->model('grader_m');
    }

    // list all student's classes
    function classes($class_id) {
        // Load the related data to that class
        $data = new stdClass();
        $data->success = 1;
        $data->sessions = $this->admin_model->load_all_sessions($class_id);
        $data->class_id = $class_id;
        $data->classinfo = $this->admin_model->load_class_info($class_id);

        echo json_encode($data);
    }

    // list all sessions and relevant information
    function sessions($class_id, $session_id) {
        // grab url info
        $data = new stdClass();
        $data->success = 1;
        
        $data->class_id = $class_id;
        $data->session_id = $session_id;

        // load class data
        $data->classinfo = $this->admin_model->load_class_info($class_id);

        // load session info
        $data->sessioninfo = $this->admin_model->load_session_info($class_id, $session_id);

        // Load the related data to that class
        $data->assignments = $this->student_model->load_session_assignments($class_id, $session_id);

        // Load the related blog to that session
        $data->blogs = $this->blog_model->get_all($class_id, $session_id);

        echo json_encode($data);
    }

    // show details about the assignment like scores etc
    function assignment($class_id, $session_id, $prog_name) {
        // Return ALL assignments 
        // Go some of the major details. 
        $data = new stdClass();
        $data->success = 1;
        
        $data->class_id = $class_id;
        $data->session_id = $session_id;
        
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        $data->prog_id = $pid;

        // Query database for all infor in that session/assignment
        $data->classinfo = $this->admin_model->load_class_info($class_id);
        $data->sessioninfo = $this->admin_model->load_session_info($class_id, $session_id);
        $data->prog = $this->admin_model->load_assignment($class_id, $session_id, $pid);

        // data for files
        $data->files = $this->tc_model->load_filedetails($class_id, $pid);

        echo json_encode($data);
    }

    // the window availbe so the student can take the assignment
    function prog($class_id, $session_id, $prog_name) {
        $data = new stdClass();
        $data->success = 1;

        // Check to see if prelim tables are present
        $this->load->model('tables_model');
        $this->tables_model->check_time_log($class_id);
        $this->tables_model->check_submit_log($class_id);
        
        $data->class_id = $class_id;
        $data->session_id = $session_id;
        $data->prog_name = $prog_name;
        
        $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        $data->prog_id = $pid;
        
        // Query database for all info in that session/assignment
        $data->classinfo = $this->admin_model->load_class_info($class_id);
            if(is_array($data->classinfo)){ $data->classinfo = $data->classinfo[0]; } // convert into object
        $data->sessioninfo = $this->admin_model->load_session_info($class_id, $session_id);
            if(is_array($data->sessioninfo)){ $data->sessioninfo = $data->sessioninfo[0]; } // convert into object
        $data->prog_info = $this->admin_model->load_assignment($class_id, $session_id, $pid);
            if(is_array($data->prog_info)){ $data->prog_info = $data->prog_info[0]; } // convert into object
            
        // $data->prog_info->description = htmlentities($data->prog_info->description);
        
        $user_id = $this->session->userdata('id');
        
        // data for files
        $stud = $this->admin_model->load_student_files($user_id, $class_id, $pid);
        $files = $this->tc_model->load_filedetails($class_id, $pid);
        
        foreach ($files as $key=>$file){
            if($file->admin_file == 1){
                unset($files[$key]);
            } else {
                // Load any previous student assignment
                $check = false;
                foreach($stud as $k1=>$f1){
                    if($f1->file_name == $file->file_name){
                        // set the file data to students previous work
                        $file->file_content = $f1->content;
                        $check = true;
                        break;
                    }
                }
                
                if(!$check){ // If no change was made then send blank template
                    $file->file_content = "";
                }
            }
        }
        $data->files = $files;

        // Get the last 3 reports
        $data->results = $this->grader_m->reports($class_id, $user_id, $pid, 4);
        // var_dump($data->reports); die();
        
        /*
        if (isset($stud[0]->file_name) ) {
            if(is_array($stud)){
                $data->student_data = $stud[0]; // I don't want the array part.. I just want the raw object
            } else {
                $data->student_data = $stud;
            }
        }
         */

        // Log it
        $this->log_model->prog($this->session->userdata('id'), $class_id, $pid);

        echo json_encode($data);
    }

    // view the program description
    function view_prog($class_id, $session_id, $prog_name) {
        $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;

        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id; // id for 
        $data['prog_name'] = $prog_name;
        $data['prog_id'] = $pid;
        $data['info'] = $this->admin_model->load_assignment($class_id, $session_id, $pid);

        // Query database for all infor in that session/assignment
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);
        $data['prog'] = $this->admin_model->load_assignment($data['class_id'], $data['session_id'], $pid);

        // data for files
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $pid);

        //echo '<pre>';print_r($data);echo '</pre>'; die();
        // Load any previous student assignment
        $data['student'] = $this->student_model->load_student_files($class_id, $this->session->userdata('id'), $pid);

        // Log it
        $this->log_model->basic($this->session->userdata('id'), $class_id, $pid, "view_prog");

        $data['main_content'] = 'stud/prog_v';
        $this->load->view('includes/template', $data);
    }
    
    function prog_results($class_id, $session_id, $prog_name) {
        $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        
        $results = $this->grader_m->load_results($class_id, $pid);
    }

    // submit a program for grading
    function submit($class_id, $session_id, $prog_name) {
        $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;

        $d1 = new stdClass();
        
        // Log it
        $this->log_model->submit($this->session->userdata('id'), $class_id, $pid);

        // submit the rest
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id; // id for 
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['assign_id'] = $prog_name;

        $data['classinfo'] = $this->admin_model->load_class_info($class_id);
        $data['info'] = $this->admin_model->load_assignment($class_id, $session_id, $pid);
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $pid);

        //var_dump($data['sessioninfo']); die();

        if (strtotime($data['sessioninfo'][0]->late) > time()) {

            $student = $this->session->userdata('username');
            $term = $data['classinfo'][0]->term;
            $classname = $data['classinfo'][0]->class_name;

            // make sure the directory is there
            $this->check_student_dir($term, $classname, $student);

            // data validation
            $files = array();
            foreach ($data['fileinfo'] as $file) {
                if ($file->admin_file == 0 && $file->stream_type != "output" && $file->multi_part == 0) {
                    $this->form_validation->set_rules($file->id, $file->file_name, 'trim|required');
                    // $xc = $this->input->post($file->id); echo $xc."<br/>"; echo "Looking for: " . $file->file_name . '<br />'; if(!empty($xc)){ echo 'file found!<br />'; }
                }
            }
            
            // die();

            // Run testcases
            if ($this->form_validation->run() == false) {
                $d1->error = 1;
                //$d1->response = "Something is up with the file data sent";
                $d1->response = validation_errors();
            } else {
                $this->load->model('grader_m');
                list ( $status, $x ) = $this->grader_m->run($class_id, $session_id, $prog_name, $student);

                if ($status == false) {
                    $d1->error = 2;
                    $d1->response = $x;
                } else {
                    $d1->success = 1;
                    $d1->results = $this->grader_m->reports($class_id, $this->session->userdata('id'), $pid, 5);
                    $d1->respose = "Successfully graded assignment. Check under results!";
                }
            }
        } else {
            $d1->error = 3;
            $d1->respose = "Late submission! Contact your instructor!";
        }
        $d1->prog = $this->admin_model->load_assignment($data['class_id'], $data['session_id'], $pid);
        echo json_encode($d1);
    }

    // show a report for the specified program
    function report($class_id, $session_id, $prog_name) {
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

        $user_id = $this->session->userdata('id');
        $data['report'] = $this->admin_model->load_student_result($class_id, $user_id, $pid);
        $data['stud'] = $this->admin_model->load_student_info($user_id);

        $data['main_content'] = 'stud/prog_report';
        $this->load->view('includes/template', $data);
    }

    // show grades for the given class
    function grades($class_id) {
        $this->load->model("class_admin/gradebk_model");
        $data['class_id'] = $class_id;
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $student_id = $this->session->userdata('id');

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
            
            // echo "<hr /><h2>Extracting from class: {$class_id} prog_id: {$x[1]}</h2>";
            
            if ( is_array($y->prog_info) ) {
                $y->prog_info = $y->prog_info[0];
            }
            
            // var_dump($y->prog_info); echo '<br />----------<br />';

            $y->prog_info->session = $this->tc_model->load_session_info($class_id, $y->prog_info->session_id);

            if (is_array($y->prog_info->session)) {
                $y->prog_info->session = $y->prog_info->session[0];
            }

            // var_dump($y->prog_info->session); echo '<hr />'; 
            
            if (!empty($y->prog_info->e_points) || !empty($y->prog_info->s_points)) {
                $y->prog_info->possible = $y->prog_info->c_points +
                        $y->prog_info->s_points +
                        $y->prog_info->d_points +
                        $y->prog_info->e_points;
            }
            $y->prog_info->deadline = date("F j, Y", strtotime($y->prog_info->session->late)); //." - ".date("H:i", strtotime($y->prog_info->session->late)) ;

            if ($y->prog_info->graded == 1) {
                $y->prog_info->graded = "yes";
            } else {
                $y->prog_info->graded = "no";
            }

            $programs[] = $y; // replace with real data
        }
        
        //var_dump($programs); //die();
        
        $data['programs'] = & $programs;

        $data['main_content'] = 'stud/grades';
        $this->load->view('includes/template', $data);
    }

    // list all the student's available reports based on the class id
    function reports($class_id) {
        
    }

    // Get posted blog item from the db and display it to the student
    function post($class_id, $session_id, $blog_id) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        // load class data
        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);

        // load session data
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['blog_item'] = $this->blog_model->get_one($class_id, $blog_id);
        
        // Log it
        $this->log_model->blog($this->session->userdata('id'), $class_id, $blog_id);

        //$data['main_content'] = 'admin/create_assignment';
        $data['main_content'] = 'blog/view';
        $this->load->view('includes/template', $data);
    }

    function check_student_dir($term, $classname, $student) {
        if (!is_dir("files")) {
            $oldmask = umask(0);
            mkdir("files", 0777);
            umask($oldmask);
        }

        if (!is_dir("files/$term")) {
            $oldmask = umask(0);
            mkdir("files/$term", 0777);
            umask($oldmask);
        }

        if (!is_dir("files/$term/$classname")) {
            $oldmask = umask(0);
            mkdir("files/$term/$classname", 0777);
            umask($oldmask);
        }

        if (!is_dir("files/$term/$classname/students")) {
            $oldmask = umask(0);
            mkdir("files/$term/$classname/students", 0777);
            umask($oldmask);
        }

        if (!is_dir("files/$term/$classname/students/$student")) {
            $oldmask = umask(0);
            mkdir("files/$term/$classname/students/$student", 0777);
            umask($oldmask);
        }
    }

    // =====================================================================

    function class_register($error = NULL) {
        if ($error != NULL) {
            $data['error'] = $error;
        }
        $data['classes'] = $this->student_model->class_list();
        $data['main_content'] = 'stud/class_register';
        $this->load->view('includes/template', $data);
    }

    function register() {

        if ($this->student_model->register() == FALSE) {
            $e = "You have not yet been registered for the class. Please make sure your passcode is correct.";
            $this->class_register($e);
        } else {
            $this->index();
        }
    }

    //========================================

    function files($class_id) {
        $this->load->model('file_cabinet/files_model');

        $classinfo = $this->admin_model->load_class_info($class_id);
        $t = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_uploads';
        $data['classinfo'] = $classinfo;
        $data['class_id'] = $class_id;
        $data['files'] = $this->files_model->list_files($t);

        $data['main_content'] = 'files/class_files';
        $this->load->view('includes/template', $data);
    }

    function download_code($class_id, $session_id, $prog_name) {
        $user_id = $this->session->userdata("id");
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;

        $files = $this->student_model->load_student_files($class_id, $user_id, $pid);

        if (empty($files) || $files == null) {
            redirect("student/sessions/$class_id/$session_id");
        }

        foreach ($files as $file) {
            $name = $this->session->userdata("username") . "_" . $file->file_name;
            $content = $file->content;

            $this->load->helper('download');
            force_download($name, $content);
        }
    }

}
