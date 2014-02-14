<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Grader_m extends CLASS_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('class_admin/tc_model');
        $this->load->model('class_admin/admin_model');
        $this->load->model('tables_model');
        $this->load->model('class_admin/gradebk_model');
        $this->user_model->is_logged_in();
         $this->load->library('mongo_db');
    }

    function run($class_id, $session_id, $prog_name, $student) {
        
        
        // Grab all proprietry data
        $classinfo = $this->admin_model->load_class_info($class_id);
        $prog = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        $fileinfo = $this->tc_model->load_filedetails($class_id, $pid);
        $sessioninfo = $this->admin_model->load_session_info($class_id, $session_id);

        
        //var_dump($prog); echo '<hr />';
        //var_dump($fileinfo); echo '<hr />';
        //var_dump($sessioninfo); echo '<hr />';
        //die();
        
        // Variables 
        $term = $classinfo[0]->term;
        $classname = $classinfo[0]->class_name;
        $e_points = $prog[0]->e_points;
        $d_points = $prog[0]->d_points;
        $s_points = $prog[0]->s_points;
        $c_points = $prog[0]->c_points;
        $late = $prog[0]->late;
        $num_tc = $prog[0]->num_tc;
        $location = "files/$term/$classname/students/$student/$class_id/$session_id";
        $error = array();

        // Variables for score
        $s_correct = 0;
        $s_total = 0;
        $sscore = 0;
        $d_correct = 0;
        $d_total = 0;
        $dscore = 0;
        $e_correct = 0;
        $e_total = 0;
        $escore = 0;
        $c_total = 0;
        $cscore = 0;

        $header = array(); // header for report
        $s_result = array(); // execution report
        $d_result = array(); // documentation report
        $t_result = array(); // totals report
        // check if student directory exists
        $this->check_dir($class_id, $session_id, $student);

        $current = getcwd();
        chdir($location);
        
            //echo getcwd(); die();

        //====================================================
        // grab student files from post 

        $comp = "";  // string with source compile list
        $infiles = array();
        $outfiles = array();

        // Run through files, tag and compile data
        foreach ($fileinfo as $file) {
            // save items to student location
            if ($file->admin_file == 0 && $file->stream_type != "output" && $file->multi_part == 0) {
                $content = $this->input->post($file->id);
                write_file($file->file_name, $content);

                // Save to db n mongo
                $user_id = $this->session->userdata('id');
                $this->update_student_file($class_id, $user_id, $file->prog_id, $file->file_name, $content);
                // ================================
                    $version = $this->get_version($class_id, $user_id, $pid);
                    $mongo_data = array(
                        'prog_id' => $file->prog_id,
                        'username' => $this->session->userdata('username'),
                        'user_id' => $user_id,
                        'file_name' => $file->file_name,
                        'content' => $content,
                        'version' => $version,
                    );
                    $collection = $classinfo[0]->term. '_' . $classinfo[0]->class_name . '_students' ;
                    $this->mongo_db->insert($collection, $mongo_data);
                // ================================
            }
            
            // die();

            // write any other files that may be needed
            if ($file->admin_file == 1 && $file->stream_type != "output" && $file->multi_part == 0) {
                $content = $file->file_content;
                write_file($file->file_name, $content);
            }

            // get the file list to compile
            if ($file->stream_type == "source")
                $comp .= $file->file_name . " ";

            // get multi-part files for testcases
            if ($file->stream_type != "output" && $file->multi_part == 1) {
                $infiles[] = $file->file_name;
                write_file($file->file_name . '_' . $file->meta, $file->file_content);
            }

            if ($file->stream_type == "output" && $file->file_name != "cin")
                $outfiles[] = $file->file_name;
        }

        $infiles = array_unique($infiles);
        $outfiles = array_unique($outfiles);

        // =====================================
        // compile student files
        // =====================================
        $go = "g++ $comp -Wall -ansi -o  " . $prog[0]->prog_name . ' 2>&1';
        exec($go, $out, $exitstat);

        $e = implode("\n", $out);
        $err = "*******************\n  COMPILE ERROR\n*******************\n$e\n";

        if ($exitstat != 0) {
            $s_result[] = $err;
            $error[] = $err;
            //echo $exitstat . '<hr />';
        } else {
            // =====================================
            // give the student the compilation points
            // =====================================
            $c_total = $c_points;

            // =====================================
            // run testcases
            // =====================================
            for ($i = 1; $i <= $prog[0]->num_tc; $i++) {
                $atf = "";

                // rename input files to the right name
                foreach ($infiles as $item) {
                    if (is_file($item)) {
                        unlink($item);
                        copy("${item}_$i", $item);
                    }
                }

                // build string to run
                //echo exec('pwd'). '<hr />';
                $out = array();
                $go = "./" . $prog[0]->prog_name . " < cin_$i  2>&1";
                set_time_limit(2);
                exec($go, $out, $exitstat);

                //print_r($out); die();

                if ($exitstat != 0) {
                    $e = implode('\n', $out);
                    $err = "*******************\n ERROR\n*******************\n$e\n";
                    // echo "Exit status $exitstat <br />"; echo '<pre>; print_r($err); echo '</pre>'; die();
                    $error[] = $err;
                } else {
                    // tag all output with cout and add to atf
                    foreach ($out as $line) {
                        $line = trim($line);
                        if ($line != "") {
                            $tg = "cout::$line";
                            $atf .= $tg . "\n";
                        }
                    }

                    // rename any outfiles so the aren't overwriteen later
                    //print_r($outfiles); echo '<hr>'; //die();

                    foreach ($outfiles as $item) {
                        if ($item != 'cout') {

                            copy($item, "${item}_$i"); // copy file just in case
                            $f = read_file($item);
                            $f1 = explode("\n", $f);

                            foreach ($f1 as $line) {
                                $line = trim($line);
                                if ($line != "") {
                                    $tg = "$item::$line";
                                    $atf .= $tg . "\n";
                                }
                            }
                        }
                    }

                    // save atf file to db / file
                    write_file($prog[0]->prog_name . ":aft$i", $atf);
                    $atfname = $prog[0]->prog_name . ":aft$i";
                    $this->update_atf($class_id, $prog_id[0]->id, $atfname, $atf);

                    // Grab test cases from testcases db
                        echo "class id: $class_id<br />Prog ID: ". $prog_id[0]->id . "<br />TC Num: $i<hr />";
                        //die();
                        
                    $e = $this->get_expf($class_id, $prog_id[0]->id, $i);
                    
                        echo '<pre>'; print_r($e);  echo '</pre><hr />'; //die();

                    $s_result[] = "<h2>Testcase $i</h2>";

                    $expected = $e[0]->expf;
                    $expected = explode("\n", $expected);
                        
                    echo '<pre>expected: '; print_r($expected);  echo '</pre><hr />'; //die();
                    
                    // =======================================
                    // write output to screen
                    $s_result[] = '<div class="row-fluid">';

                    $s_result[] = '<div class="span6 well">';
                    $s_result[] = '<h4>Expected Output</h4>';
                    $s_result[] = '<ul class="nav nav-list">';
                    foreach ($expected as $line)
                        if (trim($line) != "")
                            $s_result[] = "<li>$line</li><hr />";
                    $s_result[] = '</ul>';
                    $s_result[] = '</div>';

                    $actual = $atf;
                    $actual = explode("\n", $actual);

                    $s_result[] = '<div class="span6 well">';
                    $s_result[] = '<h4>Actual Output</h4>';
                    $s_result[] = '<ul class="nav nav-list">';
                    foreach ($actual as $line)
                        if (trim($line) != "")
                            $s_result[] = "<li>$line</li><hr />";
                    $s_result[] = '</ul>';
                    $s_result[] = '</div>';

                    $s_result[] = '</div>';

                    //echo '<pre>'; print_r($expected);  echo '</pre><hr />';
                    //echo '<pre>'; print_r($actual);  echo '</pre><hr />';
                    // =======================================
                    // compare and score
                    $s_result[] = "<h4>Results for test case $i</h4>";
                    $s_result[] = '<ul class="nav nav-list">';

                    foreach ($expected as $item) {
                        $item = trim($item);
                        if ($item != "") {
                            //echo "Looking for::  $item<br />";
                            $s_total++;
                            if ($this->result_contains($actual, $item)) {
                                $s_correct++;
                                $r = "<li  class=\"alert alert-success\">Found: $item<li>";
                                $s_result[] = $r;
                            } else {
                                $r = "<li class=\"alert alert-error\">Not Found: $item<li>";
                                $s_result[] = $r;
                            }
                        }
                    }

                    $s_result[] = '</ul>';
                    $s_result[] = "<hr />";
                }
            }
        }
        
        // die();

        $s_files = array();
        foreach ($fileinfo as $file) {
            if ($file->stream_type == "source" && $file->admin_file == 0) {
                $s_files[] = $file->file_name;
            }
        }
        $s_files = array_unique($s_files);
        $source = "";
        foreach ($s_files as $file) {
            $source .= read_file($file);
        }
        
            var_dump($s_files, TRUE); // die();

            //print_r($prog); echo '<hr />';
            //echo $prog[0]->file_name . '<hr />'; 

        list( $d_correct, $d_total, $d_result ) = $this->check_doc($source);

        echo $source. '<hr />' ;
        echo '<pre>' . print_r ( $this->check_doc( $source ), TRUE ) . '</pre>';
        echo '<pre>' . print_r ( $d_result, TRUE ) . '</pre>';
        echo  '<hr />';
        // die();
        
        // Calculate score
        if ($s_total != 0) {
            $escore = round(($s_correct / $s_total) * $e_points);
        }
        if ($d_total != 0) {
            $dscore = round(($d_correct / $d_total) * $d_points);
        }
        $cscore = $c_total;
        $sscore = $s_points;

        // Check if the assignments is late  
        if (time() <= strtotime($sessioninfo[0]->end) )
            $late = 0;
        
        // var_dump($late); echo '<hr />';

        $score = (int) $escore + (int) $dscore + (int) $sscore + (int) $cscore - (int) $late;
        
        echo "Final score: " ; var_dump($score); // die();

        // =======================================
        // append to results array
        $t = $prog[0]->e_points + $prog[0]->d_points + $prog[0]->s_points + $prog[0]->c_points;

        $indicator = 'class="alert alert-success"';
        if ($score / $t < 0.6) {
            $indicator = 'class="alert alert-error"';
        } else if ($score / $t < 0.8) {
            $indicator = 'class="alert alert-block"';
        } else {
            $indicator = 'class="alert alert-success"';
        }

        $t_result[] = "<h2>Final Scores</h2>";
        $t_result[] = '<table class="table table-bordered table-striped table-hover">';

        $t_result[] = "<tr>  <td><strong>Successful Compile</strong></td>  <td></td> </tr>";
        $t_result[] = "<tr> <td>Compile points</td>  <td>$c_total</td> </tr>";

        $t_result[] = "<tr>  <td><strong>Execution</strong></td>  <td></td> </tr>";

        $t_result[] = "<tr> <td>Possible execution points</td>  <td>$e_points</td> </tr>";
        $t_result[] = "<tr> <td>Total items checked</td>  <td>$s_total</td> </tr>";
        $t_result[] = "<tr> <td>Total items correct</td>  <td>$s_correct</td> </tr>";
        $t_result[] = "<tr> <td>Execution Score</td>  <td>$escore</td> </tr>";

        $t_result[] = "<tr> <td><strong>Documentation</strong></td>  <td></td> </tr>";

        $t_result[] = "<tr> <td>Possible documentation points</td>  <td>$d_points</td> </tr>";
        $t_result[] = "<tr> <td>Total items checked</td>  <td>$d_total</td> </tr>";
        $t_result[] = "<tr> <td>Total items correct</td>  <td>$d_correct</td> </tr>";
        $t_result[] = "<tr> <td>Documentation Score</td>  <td>$dscore</td> </tr>";

        $t_result[] = "<tr>  <td><strong>Submitted assignment</strong></td>  <td></td> </tr>";
        $t_result[] = "<tr> <td>Submission</td>  <td>$sscore</td> </tr>";

        if ($late > 0) {
            $t_result[] = "<tr>  <td><strong>Late Deduction</strong></td>  <td></td> </tr>";
            $t_result[] = "<tr> <td>Deducted</td>  <td> -  $late</td> </tr>";
        }

        $t_result[] = "<tr> <td><strong>Final</strong></td>  <td></td> </tr>";

        $t_result[] = "<tr $indicator> <td>Final Score</td>  <td>$score / $t</td> </tr>";
        $t_result[] = "</table><hr />";
        
        // =======================================
        // Compile Reports

        $r1[] = implode("\n", $t_result);
        $r1[] = implode("\n", $s_result);
        $r1[] = implode("\n", $d_result);
        $report = implode("\n", $r1);   // main report

        $d_report = implode("\n", $d_result); // documentation report
        $e_report = implode("\n", $s_result);  // execution report
        echo 'Finished compiling reports<br />';
        
        
        $this->update_reports($class_id, $prog_id[0]->id, $this->session->userdata('id'), $e_report, $d_report, $report);
        echo 'Finished updating reports<br />';
        
        $this->update_scores($class_id, $pid, $this->session->userdata('id'), $cscore, $escore, $dscore, $sscore, $late);
        echo 'Finished updating scores<br />';
        
        $sid = $this->user_model->id_from_username($student);
        $this->consolidate_student($class_id, $sid);
        echo 'Finished cosolidating student scores<br />';
        die();
        
        chdir($current);

        if (empty($error)) {
            $final = array(true, $report);
            return $final;
        } else {
            $final = array(false, $error);
            return $final;
        }
    }

    // ================================================================================================


    function update_scores($class_id, $prog_id, $user_id, $c_score, $e_score, $d_score, $s_score, $late) {
        echo '<hr />In update scores<br />';
        
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_results';
        $gtable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_gradebook';

        $data['score'] = $e_score + $d_score + $s_score;
        $data['c_points'] = $e_score;
        $data['e_points'] = $e_score;
        $data['d_points'] = $d_score;
        $data['s_points'] = $s_score;
        $data['late'] = $late;
        $data['prog_id'] = $prog_id;
        $data['user_id'] = $user_id;

        $d['score'] = $c_score + $e_score + $d_score + $s_score - $late;
        echo 'Score to save: '. $d['score'] . '<br />';
        
        // update gradebook table
        $old_score = 0;
        $r = $this->db->where('user_id', $user_id)->select('a_' . $prog_id)->get($gtable)->row();
        
        var_dump($r); echo '<br />';
        
        $n = 'a_' . $prog_id;
        if (!empty($r) && isset($r)) {
            $old_score = (int) $r->$n;
        }
        echo 'OLd score: '. $d['score'] . '<br />';

        
        if ($d['score'] > $old_score) {
            echo 'Trying to update score - line 442 g_m<br />';
            $q = $this->db->where('user_id', $user_id)
                    ->set($n, $d['score'])
                    ->update($gtable);
            echo 'Updated score<br />';
        }
        

        // update results table
        if ($this->db->where('user_id', $user_id)->where('prog_id', $prog_id)->count_all_results($table) > 0) {
            $s = $this->db->select('score')->where('user_id', $user_id)->where('prog_id', $prog_id)->get($table)->result();
            $s = (int) $s[0]->score;

            if ($s < $data['score']) {
                echo 'Trying to update results table - line 456 g_m<br />';
                $this->db->where('user_id', $user_id)->where('prog_id', $prog_id);
                return $this->db->update($table, $data);
            }
        } else {
            echo 'Trying to insert into results table - line 461 g_m<br />';
            return $this->db->insert($table, $data);
        }
    }

    function update_reports($class_id, $prog_id, $user_id, $e_report, $d_report, $report) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_results';

        $data['e_report'] = $e_report;
        $data['d_report'] = $d_report;
        $data['report'] = $report;
        $data['prog_id'] = $prog_id;
        $data['user_id'] = $user_id;

        if ($this->db->where('user_id', $user_id)->where('prog_id', $prog_id)->count_all_results($table) > 0) {
            $this->db->where('user_id', $user_id)->where('prog_id', $prog_id);
            return $this->db->update($table, $data);
        } else {
            return $this->db->insert($table, $data);
        }
    }

    function update_atf($class_id, $prog_id, $name, $content) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $atable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_sr';
        //echo $table . '<br />'; //die();

        $data['user_id'] = trim($this->session->userdata('id'));
        $data['prog_id'] = $prog_id;
        $data['file'] = mysql_real_escape_string($content);
        $data['name'] = $name;

        //echo '<pre>'; print_r($data);  echo '</pre><hr />';

        $v = $this->db->where('name', $name)->count_all_results($atable);
        //echo $v; //die();

        if ($v > 0) {
            // get current version and update
            $ver = $this->db->select_max('version')->where('name', $name)->get($atable)->result();
            $version = $ver[0]->version;

            if ($version > 3) {
                $this->db->where('version <=', $version - 3)
                        ->where('name', $name)
                        ->where('prog_id', $prog_id)
                        ->delete($atable);
            }

            $data['version'] = $version + 1;
            return $this->db->insert($atable, $data);
        } else {
            return $this->db->insert($atable, $data);
        }
    }

    function get_atf($class_id, $prog_id, $name, $version = NULL) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_student_results';

        if ($version == NULL) {
            $q = $this->db->select_max('version')
                            ->where('name', $name)
                            ->where('prog_id', $prog_id)
                            ->get($table)->result();
            foreach ($q as $row)
                $data[] = $row;
        } else {
            $q = $this->db->where('version', $version)
                            ->where('name', $name)
                            ->where('prog_id', $prog_id)
                            ->get($table)->result();
            foreach ($q as $row)
                $data[] = $row;
        }

        if (isset($data))
            return $data;
    }

    function get_expf($class_id, $prog_id, $tc_num, $version = NULL) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_testcases';
        //echo $table; die();

        if ($version == NULL) {
            $q = $this->db->where('tc_num', $tc_num)
                            ->where('prog_id', $prog_id)
                            ->order_by('version', 'DESC')
                            ->limit(1)
                            ->get($table)->result();
            foreach ($q as $row)
                $data[] = $row;
        } else {
            $q = $this->db->where('version', $version)
                            ->where('tc_num', $tc_num)
                            ->where('prog_id', $prog_id)
                            ->get($table)->result();
            foreach ($q as $row)
                $data[] = $row;
        }

        if (isset($data))
            return $data;
    }

    function result_contains($arry, $item) {
        $check = false;

        foreach ($arry as $line) {

            similar_text($line, $item, $percent);
            //echo "Comparing $line to $item <br /> percentage: $percent<hr />";
            if ($percent >= 95) {
                $check = true;
                break;
            }
        }

        if ($check == false) {
            foreach ($arry as $line) {
                $temp1 = str_replace(" ", "", $line);
                $temp2 = str_replace(" ", "", $item);
                similar_text($temp1, $temp2, $percent);
                if ($percent >= 95) {
                    $check = true;
                    break;
                }
            }
        }

        if ($check == false) {
            foreach ($arry as $line) {
                if (strpos($line, $item)) {
                    $check = true;
                    break;
                };
            }
        }

        return $check;
    }

    function update_student_file($class_id, $user_id, $prog_id, $file_name, $content) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_student_files';

        $data['user_id'] = $user_id;
        $data['prog_id'] = $prog_id;
        $data['file_name'] = $file_name;
        $data['content'] = $content;

        $this->db->where('prog_id', $prog_id)->where('file_name', $file_name)->where('user_id', $user_id);

        if ($this->db->count_all_results($table) > 0) {
            // get the current version
            $v = $this->db->where('prog_id', $prog_id)
                            ->where('file_name', $file_name)
                            ->order_by('version', 'DESC')
                            ->limit(1)
                            ->get($table)->result();

            $data['version'] = $v[0]->version + 1;

            if ($data['version'] > 1) {
                $this->db->where('version <=', $data['version'] - 1)
                        ->where('file_name', $file_name)
                        ->where('prog_id', $prog_id)
                        ->delete($table);
            }

            $this->db->where('prog_id', $prog_id)->where('file_name', $file_name)
                    ->where('user_id', $user_id)
                    ->insert($table, $data);
        } else {
            $this->db->insert($table, $data);
        }
    }

    function check_dir($class_id, $session_id, $student) {
        $classinfo = $this->admin_model->load_class_info($class_id);

        $term = $classinfo[0]->term;
        $classname = $classinfo[0]->class_name;

        // check if student directory exists
        if (!is_dir("files/$term/$classname/students/$student/$class_id")) {
            if (!is_dir("files/$term/$classname")) {
                $old = umask(0);
                mkdir("files/$term/$classname", 0777);
                umask($old);
            }

            if (!is_dir("files/$term/$classname/students")) {
                $old = umask(0);
                mkdir("files/$term/$classname/students", 0777);
                umask($old);
            }

            if (!is_dir("files/$term/$classname/students/$student")) {
                $old = umask(0);
                mkdir("files/$term/$classname/students/$student", 0777);
                umask($old);
            }

            if (!is_dir("files/$term/$classname/students/$student/$class_id")) {
                $old = umask(0);
                mkdir("files/$term/$classname/students/$student/$class_id", 0777);
                umask($old);
            }
        }

        if (!is_dir("files/$term/$classname/students/$student/$class_id/$session_id")) {
            $old = umask(0);
            mkdir("files/$term/$classname/students/$student/$class_id/$session_id", 0777);
            umask($old);
        }
    }

    function check_doc($source) { // source file as string
        // =======================================
        // check documentation here
        // =======================================

        $d_correct = 0;
        $d_total = 0;
        $d_result = array();

        $source = explode('\n', $source);
        $items = array(
            '(c)2013, ' . $this->session->userdata('username'),
            'File name: ',
            'Due date: ',
            'Purpose: ',
            'Author: ',
        );


        // ===
        $d_result[] = '<h2>Documentation</h2>';
        $d_result[] = '<div class="row-fluid">';
        $d_result[] = '<div class="span6 well">';
        $d_result[] = '<h4>Expected Documentation</h4>';
        $d_result[] = '<ul class="nav nav-list">';
        foreach ($items as $line)
            if (trim($line) != "")
                $d_result[] = "<li>$line</li><hr />";
        $d_result[] = '</ul>';
        $d_result[] = '</div>';

        // compare source code and score
        $d_result[] = '<div class="span6 well">';
        $d_result[] = "<h4>Results for documentation</h4>";
        $d_result[] = '<ul class="nav nav-list">';

        //print_r($d_result); die();
        foreach ($items as $item) {
            $item = trim($item);
            if ($item != "") {
                //echo "Looking for::  $item<br />";
                $d_total++;
                if ($this->result_contains($source, $item)) {
                    $d_correct++;
                    $r = "<li  class=\"alert alert-success\">Found: $item<li>";
                    $d_result[] = $r;
                } else {
                    $r = "<li class=\"alert alert-error\">Not Found: $item<li>";
                    $d_result[] = $r;
                }
            }
        }
        $d_result[] = '</ul>';
        $d_result[] = '</div>';
        $d_result[] = '</div>';
        $d_result[] = "<hr />";

        //print_r($d_result); die();

        return array($d_correct, $d_total, $d_result);
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
        $possible = $this->gradebk_model->get_possible_points($class_id);

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

    function get_version($class_id, $user_id, $prog_id){
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_student_files';
        
        $q = $this->db->where('user_id', $user_id)
                ->where('prog_id', $prog_id)
                ->get($table)
                ->row();
   
        if( isset($q->version)){ return $q->version; } else { return 1; }
    }
}

