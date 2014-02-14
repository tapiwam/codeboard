<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tc extends CLASS_Controller {

    private $debug;
    
    function __construct() {
        parent::__construct();
        $this->load->model('tc_model');
        $this->load->model('admin_model');
        $this->user_model->is_logged_in();
        $this->user_model->is_admin();
        $this->debug = false;
    }

    function index() {
        $data['class_id'] = $this->uri->segment(3);
        $data['session_id'] = $this->uri->segment(4);
        $data['main_content'] = 'tc/tc_1';
        $this->load->view('includes/template', $data);
    }

    function basicinfo($class_id, $session_id) {
        if($this->debug == true){
            echo '<pre>' . var_dump($this->input->post(), true). '</pre><hr />';
            // die();
        }
        
        $this->form_validation->set_rules('prog_name', 'Assignment Name', 'trim|required');
        $this->form_validation->set_rules('num_tc', 'Number of Test Cases', 'trim|required|numeric');

        $this->form_validation->set_rules('num_source', 'Number of Source Files', 'trim|required|numeric');
        $this->form_validation->set_rules('num_input', 'Number of Input Files', 'trim|required|numeric');
        $this->form_validation->set_rules('num_output', 'Number of Output Files', 'trim|required|numeric');
        
        $this->form_validation->set_rules('c_points', 'Compiliation points', 'trim|required|numeric');
        $this->form_validation->set_rules('s_points', 'Sumbission points', 'trim|required|numeric');
        $this->form_validation->set_rules('d_points', 'Documentation points', 'trim|required|numeric');
        $this->form_validation->set_rules('e_points', 'Execution Points', 'trim|required|numeric');
        $this->form_validation->set_rules('late', 'Late deduction points', 'trim|required|numeric');

        if ($this->form_validation->run() == FALSE) {
            //redirect('admin/create_assignment_option/'.$this->uri->segment(3).'/'.$this->uri->segment(4));
            $this->index();
        } else {
            $check = $this->tc_model->basicinfo($class_id, $session_id);

            if ($check != true) {
                $data['error'] = "Sorry there was an issue creating the assignment!";
            }
            $prog_name = $this->input->post('prog_name');
            $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);

            $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
            $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);

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

    function filedetails($class_id, $session_id, $prog_name) {
        
        if($this->debug == true){
            echo '<pre>' . var_dump($this->input->post(), true). '</pre><hr />';
            // die();
        }
        
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $info = $this->admin_model->load_basicinfo($class_id, $session_id, $prog_name);

        // data validation
        for ($i = 1; $i <= ($info[0]->num_addition); $i++)
            $this->form_validation->set_rules("add$i", "File $i name", 'trim|required');

        if ($this->form_validation->run() == false) {
            $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
            $data['main_content'] = 'tc/tc_2';
            $this->load->view('includes/template', $data);
        } else {
            $check = $this->tc_model->filedetails($class_id, $session_id, $prog_name);
            $data['info'] = $info;
            $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);

            if ($check != true) {
                $data['error'] = "Sorry there was an issue creating the assignment!";
                $data['main_content'] = 'tc/tc_2';
                $this->load->view('includes/template', $data);
            } else {
                $this->tc_model->update_stage($class_id, $prog_id[0]->id, 3);
                $data['main_content'] = 'tc/tc_3';
            }
            $this->load->view('includes/template', $data);
        }
    }

    function filecontent_single($class_id, $session_id, $prog_name) {
        
        global $mypost;

        // grab file contents form single part files and store along with file names
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $fileinfo = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);
        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $data['fileinfo'] = $fileinfo;

        if (isset($fileinfo)) {
            foreach ($fileinfo as $file) {
                if ($file->multi_part == 0 || $file->stream_type == "source")
                    $this->form_validation->set_rules($file->id, "File, " . $file->file_name . ',', 'required');
            }

            if ($this->form_validation->run() == false) {
                $data['main_content'] = 'tc/tc_3';
                $this->load->view('includes/template', $data);
            } else {
                foreach ($fileinfo as $file) {
                    if ($file->multi_part == 0 || $file->stream_type == "source") {
                        $content =  $mypost[$file->id];
                        
                        if($this->debug){ 
                            echo "Unfiltered:<br />";
                            var_dump($content); 
                            
                            $content = filter_var($content, FILTER_SANITIZE_STRING);
                            echo "<hr />Filtered:<br />";
                            var_dump($content);
                            // die();
                        }
                        
                        
                        $check = $this->tc_model->update_file_content($class_id, $file->id, $content);
                        if($this->debug){  
                            echo "Update check for file " . $file->id . "<br />";
                            var_dump($check); 
                            // die();
                        }
                    }
                }

                $status = $this->tc_model->generate($class_id, $session_id, $prog_name);
               
                if ($status == 1) {
                    // Update stage - completed this phase
                    $prog_id = $this->admin_model->pidFromPname($data['info'][0]->class_id, $data['info'][0]->session_id, $data['info'][0]->prog_name);
                    $this->tc_model->update_stage($class_id, $prog_id[0]->id, 4);

                    // load next phase

                    $data['main_content'] = 'tc/tc_4';
                    $this->load->view('includes/template', $data);
                } else {
                    $this->stage3($class_id, $session_id, $prog_name, $status);
                }
            }
        }
    }

    function filecontent_multi($class_id, $session_id, $prog_name) 
    {
        // grab file contents for each testcase and save along with filenames
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);

        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);

        $num_tc = $data['info'][0]->num_tc;  // echo '<pre>'; print_r($fileinfo); echo '</pre>'; echo '<hr />';
        $input = $data['info'][0]->input;

        if ($num_tc > 0 && $input == 1) {
            // =========== FORM VALIDATION
            for ($i = 1; $i <= $num_tc; $i++) {
                if (isset($data['fileinfo'])) {
                    foreach ($data['fileinfo'] as $file) {
                        // print_r($file); echo '<br />';
                        if ($file->multi_part == 1 && $file->stream_type == 'input' && $file->file_name != 'cin' && $file->meta == $i) {
                            $this->form_validation->set_rules($file->id . '_' . $i , "file, " . $file->id . " for test case " . $file->meta, 'trim|required');
                        }
                    }
                }
            }

            if ($data['info'][0]->input > 0) {
                for ($i = 1; $i <= $data['info'][0]->num_tc; $i++) {
                    $this->form_validation->set_rules("cin_$i", "CIN for $i", 'trim|required');
                }
            }

            // ========== END FROM VALIDATION

            if ($this->form_validation->run() == false) {
                //echo 'failed validation<br />';
                //echo validation_errors('<p class="error">');
                //die();
                $data['main_content'] = 'tc/tc_4';
                $this->load->view('includes/template', $data);
            } else {
                // ======== GRAB DATA
                for ($i = 1; $i <= $num_tc; $i++) {
                    // Grab any cin data if any
                    if (trim($this->input->post("cin_$i")) != "") {
                        $cin = $this->input->post("cin_$i");
                        $check = $this->tc_model->update_cin_content($class_id, $data['info'][0]->id, $i, $cin);
                    }

                    //var_dump($this->input->post()); die();
                    
                    // Grab any other data
                    foreach ($data['fileinfo'] as $file) {
                        if ($file->multi_part == 1 && $file->stream_type == 'input' && $file->file_name != 'cin' && $file->meta == $i) {
                            //echo 'updating '. $file->id . '<br />';
                            $content = $this->input->post($file->id . '_' . $i);
                            
                            //echo "looking for ". $file->id . '_' . $i. '<hr />'; var_dump($content); 
                            
                            $check = $this->tc_model->update_testcase_content($class_id, $session_id, $prog_name, $file->id, $i, $content);
                            // $check = $this->tc_model->update_cin_content($class_id, $data['info'][0]->id, $i, $cin);
                            // var_dump($check); echo '<hr />';
                        }
                    }
                }

                // ======= END GRABBING DATA
                //$status = $this->tc_model->generate($class_id, $session_id, $prog_name);
                
                // This part run the program and gets the required result
                $this->tc_model->tc_input($class_id, $session_id, $prog_name);

                // ======= UPDATE STAGE AND MOVE ON
                $this->tc_model->update_stage($class_id, $data['info'][0]->id, 5);
                $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id); // reload info
                $data['main_content'] = 'tc/tc_5';
                $this->load->view('includes/template', $data);
            }
        } else {
            $this->tc_model->update_stage($class_id, $data['info'][0]->id, 5);
            $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id); // reload info
            $data['main_content'] = 'tc/tc_5';
            $this->load->view('includes/template', $data);
        }
    }

    function output($class_id, $session_id, $prog_name) {
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        // Accept edited output from tc_5 and save to db
        $this->tc_model->output_final($class_id, $session_id, $prog_name);

        // Update stage
        $this->tc_model->update_stage($class_id, $data['info'][0]->id, 6);

        // Load panel assignment for publishing

        $data['main_content'] = 'tc/tc_6';
        $this->load->view('includes/template', $data);
    }

    function description($class_id, $session_id, $prog_name) {
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $this->form_validation->set_rules('d', 'Description', 'trim|required');

        if ($this->form_validation->run() == false) {
            $data['main_content'] = 'tc/tc_6';
            $this->load->view('includes/template', $data);
        } else {
            // update program description
            $content = $this->input->post('d');

            //$content = htmlspecialchars($content); 
            //$content = str_replace('<html>', '', $content); 
            //$content = str_replace('</html>', '', $content);
            //$content = str_replace('<body>', '', $content); $content = str_replace('</body>', '', $content);
            //$content = preg_replace('<head>', '', $content); $content = preg_replace('</head>', '', $content);
            //$content = trim($content);
            //var_dump($content); die();

            $status = $this->tc_model->update_description($class_id, $session_id, $prog_name, $content);

            if ($status == true) {
                // display
                $this->tc_model->update_stage($class_id, $data['info'][0]->id, 7);
                $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
                $data['main_content'] = 'tc/assignment';
                $this->load->view('includes/template', $data);
            } else {
                $data['main_content'] = 'tc/tc_6';
                $this->load->view('includes/template', $data);
            }
        }
    }

    function publish($class_id, $session_id, $prog_name) {
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;

        // Save all the testcase data into a tcf and an exp file and update DB
        $c1 = $this->tc_model->compile_tc($class_id, $session_id, $prog_name);

        // generate grader and save to repo
        $c2 = $this->tc_model->update_gradebook($class_id, $session_id, $prog_name);

        // set to published
        $c3 = $this->tc_model->update_publish_status($class_id, $session_id, $prog_name, 1);

        //open next panel without edit options
        $this->published($class_id, $session_id, $prog_name);
    }

    function published($class_id, $session_id, $prog_name) {
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['main_content'] = 'tc/complete';
        $this->load->view('includes/template', $data);
    }
    
    function view_published($class_id, $session_id, $prog_name) {
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['main_content'] = 'tc/complete_v';
        $this->load->view('includes/template', $data);
    }
    
    

    function edit($class_id, $session_id, $prog_name) {
        // Open review page
        $this->stage5($class_id, $session_id, $prog_name);
    }

    function stage1($class_id, $session_id, $prog_name=null) {

        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        if ($prog_name != "" || $prog_name!=null) {
            $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
            $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        } else {
            $data['info'] = "";
        }

        $data['main_content'] = 'tc/tc_1';
        $this->load->view('includes/template', $data);
    }

    function stage2($class_id, $session_id, $prog_name) {
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);

        $data['main_content'] = 'tc/tc_2';
        $this->load->view('includes/template', $data);
    }

    function stage3($class_id, $session_id, $prog_name, $err = NULL) {
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);

        if ($err != NULL) {
            $data['error'] = $err;
        }

        $data['main_content'] = 'tc/tc_3';
        $this->load->view('includes/template', $data);
    }

    function stage4($class_id, $session_id, $prog_name) {
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);

        $data['main_content'] = 'tc/tc_4';
        $this->load->view('includes/template', $data);
    }

    function stage5($class_id, $session_id, $prog_name) {
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);

        $data['main_content'] = 'tc/tc_5';
        $this->load->view('includes/template', $data);
    }

    function stage6($class_id, $session_id, $prog_name) {
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);

        $data['main_content'] = 'tc/tc_6';
        $this->load->view('includes/template', $data);
    }

    function review($class_id, $session_id, $prog_name) {
        $prog_id = $this->admin_model->pidFromPname($class_id, $session_id, $prog_name);
        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $data['fileinfo'] = $this->tc_model->load_filedetails($class_id, $prog_id[0]->id);
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;

        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['main_content'] = 'tc/assignment';
        $this->load->view('includes/template', $data);
    }

}

