<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tc_model extends CLASS_Model {

    private $debug;

    function __construct() {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('repo_model');
        $this->user_model->is_logged_in();

        $this->debug = false;
    }

    // Save basic info
    function basicinfo($class_id, $session_id) {
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

    function filedetails($class_id, $session_id, $prog_name) {
        $classinfo = $this->load_class_info($class_id);
        $info = $this->load_basicinfo($class_id, $session_id, $prog_name);
        $info = $info[0];

        // Side note: If a file already exist keep it. If not save new ones and delete all other obsolete files

        $this->remove_changed_files($class_id, $session_id, $prog_name);
        $it = (int) $info->num_addition; // - (int) $info->num_output;

        if ($this->debug == true) {
            var_dump($info); 
            //var_dump($classinfo); echo '<hr />'; 
            echo "Running through posted files:<br />";
            echo "Num of files: " . $it . "<br />";
            // echo "killing process<br />";die();
        }

        // Run through and update

        for ($i = 1; $i <= $it; $i++) {

            if ($this->debug == true) {
                echo 'dealing with ' . $this->input->post('add' . $i) . '<hr />';
            }

            $data['file_name'] = $this->input->post('add' . $i);
            $data['prog_id'] = $info->id;

            //echo "Admin $i: ".  $this->input->post('admin'.$i) . '<hr />';
            $data['admin_file'] = $this->input->post('admin' . $i);

            //echo "Multi $i: ". $this->input->post('multi'.$i) .'<hr />';		
            $data['multi_part'] = $this->input->post('multi' . $i);

            $data['stream_type'] = $this->input->post('stream_type' . $i);

            if ($this->debug == true) {
                echo 'Data so far: <br /><pre>' . var_dump($data, true) . '</pre><hr />';
            }

            if ($data['multi_part'] == 0) {
                $this->db->where('prog_id', $data['prog_id']);
                $this->db->where('file_name', $data['file_name']);
                $count = $this->db->count_all_results($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files');

                if ($count > 0) {
                    $this->db->where('prog_id', $data['prog_id']);
                    $this->db->where('file_name', $data['file_name']);
                    $status[] = $this->db->update($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files', $data);

                    if ($this->debug == true) {
                        echo '(existing file)update status for ' . $this->input->post('add' . $i) . ": $status<hr />";
                    }
                } else {
                    $status[] = $this->db->insert($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files', $data);

                    if ($this->debug == true) {
                        //$fxt = $this->input->post('add' . $i);
                        echo '(new file)insert status:<br />';
                        var_dump($status);
                        echo "<hr />";
                    }
                }
            } else {
                // deal wil mutli files by adding a file separetley for each testcase
                // either delete any old files that deviate away from the ones available

                for ($tc_num = 1; $tc_num <= $info->num_tc; $tc_num++) {
                    $data['meta'] = $tc_num;
                    
                    if ($this->debug == true) { echo "Processing Testcase Number: $tc_num <hr />"; }
                    
                    $this->db->where('prog_id', $data['prog_id']);
                    $this->db->where('file_name', $data['file_name']);
                    $this->db->where('meta', $tc_num);
                    $count = $this->db->count_all_results($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files');
                    if ($count > 0) {
                        $this->db->where('prog_id', $data['prog_id']);
                        $this->db->where('file_name', $data['file_name']);

                        $status[] = $this->db->update($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files', $data);
                    } else {
                        $status[] = $this->db->insert($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files', $data);
                    }
                }
            }
            
            if ($this->debug == true) { echo 'finished file '. $i . ' <hr />'; }
        }

        if ($this->debug == true) {
            echo 'exiting filedetails in tc_model<hr />';
            // die();
        }

        // if any files were updated just checck if ALL of them upload OK
        $check = true;
        foreach ($status as $c) {
            $check = ( $c && $check );
        }
        if (isset($status))
            return $check;
    }

    function load_filedetails($class_id, $prog_id) {
        $classinfo = $this->load_class_info($class_id);

        // grab just the file details
        $this->db->where('prog_id', $prog_id);
        $q = $this->db->get($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files');
        foreach ($q->result() as $row) {
            $data[] = $row;
        }
        if (isset($data))
            return $data;
    }

    function update_file_content($class_id, $file_id, $content, $meta = null) {
        //echo 'Updating file content<br />';
        //echo "<hr />Class ID: $class_id<br />Files ID: $file_id<br />"; var_dump($content); echo '<hr />';

        if (isset($file_id)) {
            if (isset($meta) && $meta != null) {
                $data['meta'] = $meta;
            }
            $classinfo = $this->load_class_info($class_id);
            $data['file_content'] = $content;
            $this->db->where('id', $file_id);
            $status = $this->db->update($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files', $data);
        }
    }

    function get_file_id($class_id, $file_name, $tc_num = NULL) {
        $classinfo = $this->load_class_info($class_id);
        $ftable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files';

        $this->db->where('file_name', $file_name);
        if ($tc_num != NULL)
            $this->db->where('meta', $tc_num);

        $q = $this->db->get($ftable)->result();
        foreach ($q as $item)
            $data[] = $item;

        if (isset($data))
            return $data;
    }

    function update_testcase_content($class_id, $session_id, $prog_name, $file_id, $tc_num, $content) {
        if($this->debug){
        echo "class id: $class_id<br />session is: $session_id<br />file id: $file_id<br />test case num: $tc_num<br />content: $content<br />";
        }
        
        if (isset($file_id) && isset($tc_num)) {
            $classinfo = $this->load_class_info($class_id);
            $data['file_content'] = $content;
            $this->db->where('id', $file_id);
            $this->db->where('meta', $tc_num);

            $ftable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files';

            $stat1 = $this->db->update($ftable, $data);
            // echo $stat1 . '<br />'; var_dump( $this->db->last_query() ); die();

            $fn = $this->db->select('file_name')->where('id', $file_id)->get($ftable)->result();
            $filename = $fn[0]->file_name;

            //var_dump($classinfo); //die();
            // Write cin file
            if (!is_dir("files")) {
                $old = umask(0);
                mkdir("files");
                umask($old);
            }

            if (!is_dir("files/" . $classinfo[0]->term)) {
                $old = umask(0);
                mkdir("files/" . $classinfo[0]->term);
                umask($old);
            }

            if (!is_dir("files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name)) {
                $old = umask(0);
                mkdir("files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name);
                umask($old);
            }

            if (!is_dir("files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name . "/admin")) {
                $old = umask(0);
                mkdir("files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name . "/admin");
                umask($old);
            }

            // echo "<hr />Session ID: $session_id <hr />";
            if (!is_dir("files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name . "/admin/$session_id")) {
                $old = umask(0);
                mkdir("files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name . "/admin/$session_id");
                umask($old);
            }

            if (!is_dir("files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name . "/admin/$session_id/$prog_name")) {
                $old = umask(0);
                mkdir("files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name . "/admin/$session_id/$prog_name");
                umask($old);
            }

            $path = "files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name . "/admin/$session_id/$prog_name";

            $current = getcwd();
            chdir($path);
            $stat2 = write_file("${filename}_$tc_num", $content);
            chdir($current);

            return ($stat1 && $stat2);
        }
    }

    function update_cin_content($class_id, $prog_id, $tc_num, $content) {
        $classinfo = $this->load_class_info($class_id);
        $ftable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files';
        $ptable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';

        // get details from programs table
        $op = $this->db->select('id')->where('id', $prog_id)->get($ptable)->result();

        $data['prog_id'] = $op[0]->id;
        $data['file_name'] = 'cin';
        $data['file_content'] = $content;
        $data['admin_file'] = 1;
        $data['multi_part'] = 1;
        $data['stream_type'] = 'input';
        $data['meta'] = $tc_num;

        // get session 
        $ptable = $classinfo[0]->term . "_" . $classinfo[0]->class_name . "_programs";
        $s = $this->db->select('session_id, prog_name')->where('id', $prog_id)->get($ptable)->result();
        $prog_name = $s[0]->prog_name;
        $session_id = $s[0]->session_id;

        // Write cin file
        if (!is_dir("files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name . "/admin/$session_id")) {
            $old = umask(0);
            mkdir("files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name . "/admin/$session_id");
            umask($old);
        }

        if (!is_dir("files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name . "/admin/$session_id/$prog_name")) {
            $old = umask(0);
            mkdir("files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name . "/admin/$session_id/$prog_name");
            umask($old);
        }
        $path = "files/" . $classinfo[0]->term . "/" . $classinfo[0]->class_name . "/admin/$session_id/$prog_name";

        $current = getcwd();
        chdir($path);
        write_file("cin_$tc_num", $content);
        chdir($current);

        // insert necessary details into files table
        $this->db->where('prog_id', $prog_id);
        $this->db->where('file_name', 'cin');
        $this->db->where('meta', $tc_num);
        if ($this->db->count_all_results($ftable) != 0) {
            $this->db->where('prog_id', $prog_id);
            $this->db->where('file_name', 'cin');
            $this->db->where('meta', $tc_num);
            $this->db->update($ftable, $data);
        } else {
            $this->db->insert($ftable, $data);
        }
    }

    function update_cout_content($class_id, $prog_id, $tc_num, $content) {
        $classinfo = $this->load_class_info($class_id);
        $ftable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files';
        $ptable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';

        // get details from programs table
        $op = $this->db->select('id')->where('id', $prog_id)->get($ptable)->result();

        $data['prog_id'] = $op[0]->id;
        $data['file_name'] = 'cout';
        $data['file_content'] = $content;
        $data['admin_file'] = 1;
        $data['multi_part'] = 1;
        $data['stream_type'] = 'output';
        $data['meta'] = $tc_num;

        // insert necessary details into files table
        $this->db->where('prog_id', $prog_id);
        $this->db->where('file_name', 'cout');
        $this->db->where('meta', $tc_num);
        if ($this->db->count_all_results($ftable) != 0) {
            $this->db->where('prog_id', $prog_id);
            $this->db->where('file_name', 'cout');
            $this->db->where('meta', $tc_num);
            $this->db->update($ftable, $data);
        } else {
            $this->db->insert($ftable, $data);
        }
    }

    function remove_changed_files($class_id, $session_id, $prog_name) {
        if ($this->debug == true) {
            echo "<hr />Trying to remove obsolete files for $prog_name in session $session_id<br />";
        }
        $prog_id = $this->pidFromPname($class_id, $session_id, $prog_name);
        $prog_id = $prog_id[0]->id;

        $classinfo = $this->load_class_info($class_id);
        $info = $this->load_basicinfo($class_id, $session_id, $prog_name);
        $info = $info[0];


        // get the files from the table
        if ($this->debug == true) {
            echo "getting filenames from files table for $prog_name<br />";
        }

        $this->db->select('file_name');
        $this->db->where('prog_id', $prog_id);
        $q = $this->db->get($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files')->result();
        $files = array();
        foreach ($q as $row) {
            $files[] = $row->file_name;
        }

        if ($this->debug == true) {
            echo "<hr />Files:<br /><pre>" . var_dump($files, true) . "</pre><hr />";
        }

        // ==================================================== good
        if ($this->debug == true) {
            echo "removing valid files from the delete-list<br />";
        }

        foreach ($this->input->post(NULL, TRUE) as $val) {
            if (($key = array_search($val, $files)) != false) {
                unset($files[$key]);
            }
        }

        if ($this->debug == true) {
            echo "removing obsolete files from the files table<br />";
        }
        foreach ($files as $item) {
            $this->remove_file_from_files($class_id, $prog_id, $item);
        }

        if ($this->debug == true) {
            echo "finished removing files for $prog_name in session $session_id<hr />";
        }
    }

    function array_contains($arry, $item) {
        foreach ($arry as $i) {
            if ($i == $item) {
                return true;
            }
        }
        return false;
    }

    function remove_file_from_files($class_id, $prog_id, $file) {
        $classinfo = $this->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files';

        $this->db->where('prog_id', $prog_id);
        $this->db->where('file_name', $file);
        $status = $this->db->delete($table);

        if ($this->debug == true) {
            echo "remove - $file - from the $table ==> status: $status<br />";
        }
        return $status;
    }

    function update_stage($class_id, $prog_id, $stage) {
        $classinfo = $this->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';

        $status = $this->db->where('id', $prog_id)->set('stage', $stage)->update($table);
        return $status;
    }

    function current_stage($class_id, $prog_id) {
        $classinfo = $this->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';

        $q = $this->db->select('stage')->where('id', $prog_id)->get($table)->result();
        if ($q) {
            return $q[0]->stage;
        }
    }

    function generate($class_id, $session_id, $prog_name) {
        $prog = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $prog_id = $this->pidFromPname($class_id, $session_id, $prog_name);
        // load session info here
        $class = $this->load_class_info($class_id);
        $class = $class[0];

        $pid = $prog_id[0]->id;
        $fileinfo = $this->tc_model->load_filedetails($class_id, $pid);

        $pre = 'files/' . $class->term . '/' . $class->class_name . '/admin/';

        //echo getcwd(); echo '<br />';

        if (!is_dir('files')) {
            $old = umask(0);
            mkdir('files', 0777);
            umask($old);
        }

        if (!is_dir('files/' . $class->term)) {
            $old = umask(0);
            mkdir('files/' . $class->term, 0777);
            umask($old);
        }

        if (!is_dir('files/' . $class->term . '/' . $class->class_name)) {
            $old = umask(0);
            mkdir('files/' . $class->term . '/' . $class->class_name, 0777);
            umask($old);
        }

        if (!is_dir($pre)) {
            $old = umask(0);
            mkdir($pre, 0777);
            umask($old);
        }

        $sessiondir = $pre . $prog[0]->session_id;  // Save all the files to the session
        if (!is_dir($sessiondir)) {
            $old = umask(0);
            mkdir($sessiondir, 0777);
            umask($old);
        }

        $progdir = $sessiondir . '/' . $prog[0]->prog_name;
        if (!is_dir($progdir)) {
            mkdir($progdir, 0777);
        }

        // COPY files over
        $comp = "";

        foreach ($fileinfo as $key => $file) {

            if (is_file($file->file_name)) {
                unlink($file->file_name);
            }

            if ($file->stream_type == 'source') {
                write_file($progdir . '/' . $file->file_name, $file->file_content);

                // put a string together that will be used for compilation
                $comp .= $file->file_name . ' ';
            }

            if ($file->multi_part == 1 && $file->stream_type != 'source') {
                if ($file->stream_type == 'input') {
                    write_file($progdir . '/' . $file->file_name . '_' . $file->meta, $file->file_content);
                }
            }

            if ($file->multi_part == 0 && $file->stream_type != 'source') {
                if ($file->stream_type == 'input') {
                    write_file($progdir . '/' . $file->file_name, $file->file_content);
                }
            }
        }

        // compile here
        if($prog[0]->type == "cpp" )
        {
            $go = "g++ -Wall $comp -o  " . $prog[0]->prog_name . ' 2>&1';
        } 
        else if($prog[0]->type == "c" )
        {
            $go = "gcc -Wall $comp -o  " . $prog[0]->prog_name . ' 2>&1';
        }
        
        $current = getcwd();
        chdir($progdir);
        exec($go, $out, $exitstat);
        chdir($current);

        $e = implode('\n', $out);
        $err = "*******************\n  COMPILE ERROR\n*******************\n $e \n";

        if ($exitstat == 0)
            return true;
        else {
            return $err;
        }
    }

    function tc_input($class_id, $session_id, $prog_name) {
        $prog = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $prog_id = $this->pidFromPname($class_id, $session_id, $prog_name);
        $class = $this->load_class_info($class_id);
        $class = $class[0];

        $pid = $prog_id[0]->id;
        $fileinfo = $this->tc_model->load_filedetails($class_id, $pid);
        $info = $this->load_basicinfo($class_id, $session_id, $prog_name);

        $pre = 'files/' . $class->term . '/' . $class->class_name . '/admin/';
        $sessiondir = $pre . $prog[0]->session_id;
        $progdir = $sessiondir . '/' . $prog[0]->prog_name . '/';

        // run testcases and get propriatery output
        //-----if( is_dir($progdir))
        //-----	echo $progdir . '<br />';

        for ($i = 1; $i <= $prog[0]->num_tc; $i++) {
            // check if any cout is required.. If so look for it and upload it
            // rename input file to original name so it can be used within the prog
            if ($info[0]->input == 1) {
                // rename each file
                foreach ($fileinfo as $file) {
                    if ($file->stream_type == "input" && $file->multi_part == 1 && $file->meta == $i && $file->file_name != "cin") {
                        if(is_file("$progdir" . $file->file_name)) { unlink("$progdir" . $file->file_name); }
                        copy("$progdir/" . $file->file_name . '_' . $file->meta, "$progdir/" . $file->file_name);
                    }
                }
            }

            // build the execution string
            $out = array();
            $go = "./" . $prog[0]->prog_name . " < cin_" . $i . " > out_" . $i . " 2>&1";

            // run the script
            $current = getcwd();
            chdir($progdir);
            exec($go, $out, $exitstat);
            chdir($current);

            // Check status
            if ($exitstat != 0) {
                $compile_err = implode('\n', $out);
                $error[] = "Sorry there was a problem executing testcase " . $i . '\n\n' . $compile_err . '\n';
                // echo '<pre>'; print_r($error); echo '</pre><hr />';
            } else {

                if ($prog[0]->output == 1) {
                    $output = read_file($progdir . 'out_' . $i);
                    //echo $output . '<hr />'; 
                    $this->update_cout_content($class_id, $pid, $i, $output);
                }

                // find all the output files and see which ones are multi, output, (excluding cout ) and save
                foreach ($fileinfo as $file) {
                    if ($file->multi_part == 1 && $file->stream_type == 'output' && $file->file_name != 'cout' && $file->meta == $i) {
                        // Update that file in the db
                        $content = read_file($progdir . $file->file_name);
                        //var_dump($content); die();
                        // this must have a meta tag attached for the testcase
                        if ($this->debug) {
                            echo "Updating output file: " . $file->file_name . " for testcase $i <br />";
                        }
                        $this->update_file_content($class_id, $file->id, $content, $i);
                    }
                }

                if ($this->debug) {
                    echo "Updated output files in DB";
                } //die(); }
            }
        }
        //die();
    }

    function output_final($class_id, $session_id, $prog_name) {
        $classinfo = $this->load_class_info($class_id);
        $info = $this->load_basicinfo($class_id, $session_id, $prog_name);
        $info = $info[0];

        $prog_id = $this->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        $fileinfo = $this->tc_model->load_filedetails($class_id, $pid);

        // get all output for the given testcases
        if ($info->output == 1) {
            // update db for any cout
            for ($i = 1; $i <= $info->num_tc; $i++) {
                $output = $this->input->post('output_' . $i);
                //echo 'output_'.$i.'<br>'.$output.'<hr />';
                $this->update_cout_content($class_id, $pid, $i, $output);
            }
        }

        // Any other output files
        foreach ($fileinfo as $file) {
            if ($file->multi_part == 1 && $file->stream_type == 'output' && $file->file_name != 'cout') {
                $file_id = $file->id;
                for ($i = 1; $i <= $info->num_tc; $i++) {
                    if ($file->meta == $i) {
                        if ($this->debug == true) {
                            echo $file->id . '_' . $i . '<br>' . $output . '<hr />';
                            echo "CHECK FOR:<br />class id: $class_id<br />session is: $session_id<br />file id: $file_id<br />test case num: $i<br />content: $output<hr />";
                        }
                        $output = $this->input->post($file->id . '_' . $i);
                        $this->update_testcase_content($class_id, $session_id, $prog_name, $file_id, $i, $output);
                    }
                }
            }
        }
    }

    function load_cout_content($class_id, $session_id, $prog_name) {
        $classinfo = $this->load_class_info($class_id);
        $prog_id = $this->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;

        // grab just the file details
        $this->db->where('prog_id', $pid);
        $this->db->where('file_name', 'cout');
        $q = $this->db->get($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files');
        foreach ($q->result() as $row) {
            $data[] = $row;
        }
        if (isset($data))
            return $data;
    }

    function load_cin_content($class_id, $session_id, $prog_name) {
        $classinfo = $this->load_class_info($class_id);
        $prog_id = $this->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;

        // grab just the file details$this->load->model('repo_model');
        $this->db->where('prog_id', $pid);
        $this->db->where('file_name', 'cin');
        $q = $this->db->get($classinfo[0]->term . '_' . $classinfo[0]->class_name . '_files');
        foreach ($q->result() as $row) {
            $data[] = $row;
        }
        if (isset($data))
            return $data;
    }

    function compile_tc($class_id, $session_id, $prog_name) {
        $classinfo = $this->load_class_info($class_id);
        $prog = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $prog_id = $this->pidFromPname($class_id, $session_id, $prog_name);

        $pid = $prog_id[0]->id;
        $fileinfo = $this->tc_model->load_filedetails($class_id, $pid);
        $check = true;

        $tc_db = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_testcases';

        //echo '<pre>'; print_r($prog); echo '<hr />'; print_r($class); echo '</pre>';
        // file location
        $pre = 'files/' . $classinfo[0]->term . '/' . $classinfo[0]->class_name . '/admin/';
        $sessiondir = $pre . $prog[0]->session_id;
        $progdir = $sessiondir . '/' . $prog[0]->prog_name . '/';

        // run through all the files and construct tcf and expf files
        for ($i = 1; $i <= $prog[0]->num_tc; $i++) {
            $tcf_file = $prog[0]->prog_name . ":tcf$i";
            $expf_file = $prog[0]->prog_name . ":expf$i";

            $tcf = "";
            $expf = "";

            // Run through files, tag and compile data
            foreach ($fileinfo as $file) {
                if ($file->stream_type == "input" && $file->meta == $i) {
                    // split filecontent into string and implode with tags
                    $output = explode("\n", $file->file_content);
                    $final = "";
                    foreach ($output as $token) {
                        trim($token);
                        if ($token != "") {
                            $line = $file->file_name . '::' . $token;
                            $final .= $line . "\n";
                        }
                    }
                    $tcf .= $final;
                }

                if ($file->stream_type == "output" && $file->meta == $i) {
                    // split filecontent into string and implode with tags
                    $output = explode("\n", $file->file_content);
                    $final = "";
                    foreach ($output as $token) {
                        trim($token);
                        if ($token != "") {
                            $line = $file->file_name . '::' . $token;
                            $final .= $line . "\n";
                        }
                    }
                    $expf .= $final;
                }
            }
            //echo '<pre>' . htmlentities($tcf) . '</pre><hr />';echo '<pre>' . htmlentities($expf) . '</pre><br />';
            // save each tc to repository
            /*
              $stat1 = write_file($progdir . $tcf_file , $tcf);
              $stat2 = write_file($progdir . $expf_file, $expf);
              $check = ($check && $stat1 && $stat2 );
              $stat1 = $this->repo_model->ppost($classinfo[0]->term , $classinfo[0]->class_name, $progdir . $tcf_file);
              $stat2 = $this->repo_model->ppost($classinfo[0]->term , $classinfo[0]->class_name, $progdir . $expf_file);
              $check = ($check && $stat1 && $stat2 );
             */

            // Save TC to DB
            $tc_data = array();

            $tc_data['tcf'] = $tcf;
            $tc_data['expf'] = $expf;
            $tc_data['tc_num'] = $i;
            $tc_data['tc_name'] = $prog[0]->prog_name;
            $tc_data['prog_id'] = $file->prog_id;

            $this->db->where('prog_id', $file->prog_id);
            $this->db->where('tc_num', $i);
            if ($this->db->count_all_results($tc_db) > 0) {
                // Deactivate prvious testcases
                $this->db->where('prog_id', $file->prog_id)->where('tc_num', $i)->set('active', 0);
                $this->db->update($tc_db);

                // get the current version 
                $this->db->select_max('version')->where('prog_id', $file->prog_id)->where('tc_num', $i);
                $q = $this->db->get($tc_db)->result();
                $version = $q[0]->version;

                // If version greater than 5 cut out earlier versions
                if ($version > 5) {
                    $cut = $version - 5;
                    $this->db->where('prog_id', $file->prog_id)->where('tc_num', $i)->where('version <=', $cut);
                    $this->db->delete($tc_db);
                }

                // aditional info
                $tc_data['version'] = $version + 1;
                $tc_data['active'] = 1;

                // insert a new version
                $check1[$i] = $this->db->insert($tc_db, $tc_data);
            } else {
                $check2[$i] = $this->db->insert($tc_db, $tc_data);
            }
        }
        return $check;
    }

    function update_publish_status($class_id, $session_id, $prog_name, $status) {
        $prog_id = $this->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;

        $classinfo = $this->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';

        $status = $this->db->where('id', $pid)->set('published', $status)->update($table);
        return $status;
    }

    function update_description($class_id, $session_id, $prog_name, $content) {
        $prog_id = $this->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;

        $classinfo = $this->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';

        $status = $this->db->where('id', $pid)->set('description', $content)->update($table);
        return $status;
    }

    function update_gradebook($class_id, $session_id, $prog_name) {
        $this->load->dbforge();
        $classinfo = $this->load_class_info($class_id);
        $prog_id = $this->pidFromPname($class_id, $session_id, $prog_name);

        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_gradebook';
        $name = "a_" . $prog_id[0]->id;

        if (!$this->db->field_exists($name, $table)) {
            $fields = array($name => array('type' => 'INT'));
            $this->dbforge->add_column($table, $fields);
        }
    }

}

