<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Grader_m extends CLASS_Model {

    private $debug;

    function __construct() {
        parent::__construct();
        $this->load->model('class_admin/tc_model');
        $this->load->model('class_admin/admin_model');
        $this->load->model('tables_model');
        $this->load->model('class_admin/gradebk_model');
        $this->user_model->is_logged_in();
        // $this->load->library('mongo_db');

        $this->debug = false;
    }

    function run($class_id, $session_id, $prog_name, $student) {

        global $mypost;
        $this->check_results_tables($class_id);

        // Grab all proprietry data
        $classinfo = $this->admin_model->load_class_info($class_id);
        $prog = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);
        $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;
        $uid = $this->session->userdata('id');
        $fileinfo = $this->tc_model->load_filedetails($class_id, $pid);
        $sessioninfo = $this->admin_model->load_session_info($class_id, $session_id);

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
        if ($this->debug) {
            echo "Current Dir: $current <br />";
        }

        chdir($location);
        if ($this->debug) {
            echo "Changed Dir to:" . getcwd() . " <hr />";
        }

        //====================================================
        // grab student files from post 

        $comp = "";  // string with source compile list
        $infiles = array();
        $outfiles = array();
        $code_files = array();
        $code_lines = 0;

        // Run through files, tag and compile data
        foreach ($fileinfo as $file) {
            // save items to student location
            if ($file->admin_file == 0 && $file->stream_type != "output" && $file->multi_part == 0) {
                //$content = $this->input->post($file->id);
                
                // Build a mini-object contating the file source code. Will be put in report
                $content = $mypost[$file->id];
                $cx1 = new stdClass();
                $cx1->name = $file->file_name;
                $cx1->code = htmlentities($content) ; // nl2br($content);
                $code_files[] = $cx1;

                write_file($file->file_name, $content);

                // Save to db
                $user_id = $this->session->userdata('id');
                $this->update_student_file($class_id, $user_id, $file->prog_id, $file->file_name, $content);
                $code_lines += $this->count_code_lines($content);
                
                $version = $this->get_version($class_id, $user_id, $pid);
            }

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

        if ($this->debug) {
            echo "Program type to compile: " . $prog[0]->type . "<br />";  /* die(); */
        }
        if ($prog[0]->type == "cpp") {
            $go = "g++ -Wall $comp -o  " . $prog[0]->prog_name . ' 2>&1';
        } else if ($prog[0]->type == "c") {
            $go = "gcc -Wall $comp -o  " . $prog[0]->prog_name . ' 2>&1';
        } else if ($prog[0]->type == "java") {
            $go = "javac -verbose -classpath " . $prog[0]->prog_name . ".jar $comp 2>&1";
        }

        // compile here
        if ($this->debug) {
            echo "compiling: $go <hr />";
        }
        exec($go, $out, $exitstat);

        if ($prog[0]->type == "python") {
            $exitstat = 0;
        }

        //var_dump($infiles); die();

        $e = implode("\n", $out);
        $err = "*******************\n  COMPILE ERROR\n*******************\n$e\n";

        if ($exitstat != 0) {
            $err1 = htmlentities($err);
            $s_result[] = "<pre>$err1</pre>";
            $error[] = $err;
            if ($this->debug) {
                echo "Failed to compile with exit status: $exitstat <hr />";
            }
        } else {
            // =====================================
            // give the student the compilation points
            // =====================================
            $c_total = $c_points;

            // =====================================
            // run testcases
            // =====================================
            if ($this->debug) {
                echo "Starting running testcases<br />";
            }
            for ($i = 1; $i <= $prog[0]->num_tc; $i++) {

                if ($this->debug) {
                    echo "<hr />Testcase $i<br />";
                }

                $atf = "";

                // rename input files to the right name
                if ($this->debug) {
                    echo "Renaming input files for run:<br />";
                    var_dump($infiles);
                    echo "<br />";
                }

                foreach ($infiles as $item) {
                    if ($this->debug) {
                        echo "checking ${item} <br />";
                    }
                    if (is_file($item)) {
                        unlink($item);
                        copy("${item}_$i", $item);
                        if ($this->debug) {
                            echo "overwrite ${item}_$i to $item<br />";
                        }
                    } else {
                        copy("${item}_$i", $item);
                        if ($this->debug) {
                            echo "copied ${item}_$i to $item<br />";
                        }
                    }
                }
                if ($this->debug) {
                    echo "Done renaming files <br />===============<br />"; /* die(); */
                }


                // build string to run
                $out = array();
                $go = "timeout 1 ./" . $prog[0]->prog_name . " < cin_$i  2>&1";
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
                    $this->update_atf($class_id, $prog_id[0]->id, $atfname, $uid, $atf);

                    // Grab test cases from testcases db
                    $e = $this->get_expf($class_id, $prog_id[0]->id, $i);
                    // echo '<pre>'; print_r($e);  echo '</pre><hr />'; die();

                    $s_result[] = "<h3>Testcase $i</h3>";

                    $expected = $e[0]->expf;
                    $expected = explode("\n", $expected);

                    // =======================================
                    // write output to screen
                    $s_result[] = '<div class="row">'; // open row

                    $s_result[] = '<div class="col-md-6">'; // open span6
                    $s_result[] = '<div class="panel panel-info">'; // open panel
                    $s_result[] = '<div class="panel-heading">'; // open panel heading
                    $s_result[] = '<h4>Expected Output</h4>';
                    $s_result[] = '</div>'; // close panel heading
                    $s_result[] = '<table class="table table-striped table-bordered table-hover">'; // open table
                    foreach ($expected as $line)
                        if (trim($line) != "")
                            $s_result[] = "<tr><td>$line</td></tr>";
                    $s_result[] = '</table>'; // close table
                    $s_result[] = '</div>'; // close panel
                    $s_result[] = '</div>'; // close span6 

                    $actual = $atf;
                    $actual = explode("\n", $actual);

                    $s_result[] = '<div class="col-md-6">'; // open new span
                    $s_result[] = '<div class="panel panel-info">'; // open panel
                    $s_result[] = '<div class="panel-heading">'; // open panel heading
                    $s_result[] = '<h4>Actual Output</h4>';
                    $s_result[] = '</div>'; // close panel heading
                    $s_result[] = '<table class="table table-striped table-bordered table-hover">'; // open table
                    foreach ($actual as $line)
                        if (trim($line) != "")
                            $s_result[] = "<tr><td>$line</td></tr>";
                    $s_result[] = '</table>'; // close table
                    $s_result[] = '</div>'; // close panel
                    $s_result[] = '</div>'; // close span

                    $s_result[] = '</div>'; // close row
                    //echo '<pre>'; print_r($expected);  echo '</pre><hr />';
                    //echo '<pre>'; print_r($actual);  echo '</pre><hr />';
                    // =======================================
                    // compare and score

                    $s_result[] = '<div class="row">'; // open row
                    $s_result[] = '<div class="panel panel-info">'; // open panel
                    $s_result[] = '<div class="panel-heading">'; // open panel heading
                    $s_result[] = "<h4>Results for test case $i</h4>";
                    $s_result[] = '</div>'; // close panel heading

                    $s_result[] = '<table class="table table-striped table-bordered table-hover">';

                    foreach ($expected as $item) {
                        $item = trim($item);
                        if ($item != "") {
                            //echo "Looking for::  $item<br />";
                            $s_total++;
                            if ($this->result_contains($actual, $item)) {
                                $s_correct++;
                                $r = "<tr class=\"text-success\"><td>Found: $item</td></tr>";
                                $s_result[] = $r;
                            } else {
                                $r = "<tr class=\"text-danger\"><td>Not Found: $item</td></tr>";
                                $s_result[] = $r;
                            }
                        }
                    }

                    $s_result[] = '</table>'; // close table
                    $s_result[] = '</div>'; // close panel
                    $s_result[] = '</div>'; // close row

                    $s_result[] = "<hr />";
                }
            }
        }

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

        //print_r($prog); echo '<hr />';
        //echo $prog[0]->file_name . '<hr />'; 

        list( $d_correct, $d_total, $d_result ) = $this->check_doc($source);

        //echo $source. '<hr />' ;
        //echo '<pre>' . print_r ( $this->check_doc( $source ), TRUE ) . '</pre>';
        // echo '<pre>' . print_r ( $d_result, TRUE ) . '</pre>';
        //echo  '<hr />';
        //die();
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
        if (time() <= strtotime($sessioninfo[0]->end))
            $late = 0;

        $score = (int) $escore + (int) $dscore + (int) $sscore + (int) $cscore - (int) $late;

        // =======================================
        // append to results array
        $t = $prog[0]->e_points + $prog[0]->d_points + $prog[0]->s_points + $prog[0]->c_points;

        $indicator = 'class="success"';
        if ($score / $t < 0.6) {
            $indicator = 'class="warning"';
        } else if ($score / $t < 0.8) {
            $indicator = 'class="active"';
        } else {
            $indicator = 'class="success"';
        }

        $t_result[] = '<div class="panel panel-info">'; // open panel
        $t_result[] = '<div class="panel-heading">'; // open panel heading
        $t_result[] = "<h3>Final Score Breakdown : {$prog[0]->prog_name} </h3>";
        $t_result[] = "</div>";  // close panel heading
        $t_result[] = '<table class="table table-bordered table-striped table-hover">';

        if ($prog[0]->c_points > 0) {
            $t_result[] = "<tr><td><strong>Successful Compile</strong></td>  <td></td> </tr>";
            $t_result[] = "<tr><td>Compile points</td>  <td>$c_total</td> </tr>";
        }

        if ($prog[0]->e_points > 0) {
            $t_result[] = "<tr>  <td><strong>Execution</strong></td>  <td></td> </tr>";

            $t_result[] = "<tr> <td>Possible execution points</td>  <td>$e_points</td> </tr>";
            $t_result[] = "<tr> <td>Total items checked</td>  <td>$s_total</td> </tr>";
            $t_result[] = "<tr> <td>Total items correct</td>  <td>$s_correct</td> </tr>";
            $t_result[] = "<tr> <td>Execution Score</td>  <td>$escore</td> </tr>";
        }

        if ($prog[0]->d_points > 0) {
            $t_result[] = "<tr> <td><strong>Documentation</strong></td>  <td></td> </tr>";

            $t_result[] = "<tr> <td>Possible documentation points</td>  <td>$d_points</td> </tr>";
            $t_result[] = "<tr> <td>Total items checked</td>  <td>$d_total</td> </tr>";
            $t_result[] = "<tr> <td>Total items correct</td>  <td>$d_correct</td> </tr>";
            $t_result[] = "<tr> <td>Documentation Score</td>  <td>$dscore</td> </tr>";
        }

        if ($prog[0]->s_points > 0) {
            $t_result[] = "<tr>  <td><strong>Submitted assignment</strong></td>  <td></td> </tr>";
            $t_result[] = "<tr> <td>Submission</td>  <td>$sscore</td> </tr>";
        }

        if ($late > 0) {
            $t_result[] = "<tr>  <td><strong>Late Deduction</strong></td>  <td></td> </tr>";
            $t_result[] = "<tr> <td>Deducted</td>  <td> -  $late</td> </tr>";
        }

        $t_result[] = "<tr> <td><strong>Final</strong></td>  <td></td> </tr>";

        $t_result[] = "<tr $indicator> <td>Final Score</td>  <td>$score / $t</td> </tr>";
        $t_result[] = "</table>";
        $t_result[] = "</div><hr />"; // close panel
        
        // append source code to each report
        
        $c_result = array();
        $c_result[] = "<div>";
        $c_result[] = "<h3>Source Code</h3>";
        foreach ($code_files as $k=>$v){
            $c_result[] = "<h4>{$v->name}</h4>";
            $c_result[] = "<pre class='well'>"; // open panel
            $c_result[] = "<p>". $v->code."</p>";
            $c_result[] = "</pre>"; // close panel
        }
        $c_result[] = "</div>";
                
        // =======================================
        // Compile Reports

        $r1[] = implode("\n", $t_result);
        $r1[] = implode("\n", $s_result);
        
        if ($prog[0]->d_points > 0) {
            $r1[] = implode("\n", $d_result);
        }
        
        $r1[] = implode("\n", $c_result);
        
        $report = implode("\n", $r1);   // main report

        $d_report = implode("\n", $d_result); // documentation report
        $e_report = implode("\n", $s_result);  // execution report

        $this->update_reports($class_id, $prog_id[0]->id, $this->session->userdata('id'), $e_report, $d_report, $report);
        $this->update_scores($class_id, $pid, $this->session->userdata('id'), $cscore, $escore, $dscore, $sscore, $late, $code_lines);

        $log_data = array(
            "prog_id" => $prog_id[0]->id,
            "user_id" => $this->session->userdata('id'),
            "score" => $escore + $dscore + $sscore + $cscore,
            "c_points" => $cscore,
            "e_points" => $escore,
            "d_points" => $dscore,
            "s_points" => $sscore,
            "late" => $late,
            "e_report" => $e_report,
            "d_report" => $d_report,
            "report" => $report,
            "submits" => $version,
            "lines" => $code_lines,
            "hash" => "",
        );

        $this->update_submit_log($class_id, $log_data);
        $version = $version + 1;


        //var_dump($student); die();
        $sid = $this->user_model->id_from_username($student);
        //var_dump($sid);
        $this->consolidate_student($class_id, $sid);

        chdir($current);
        if ($this->debug) {
            echo "<hr />Changed  back to Dir to:" . getcwd() . " <hr />";
            die();
        }

        if (empty($error)) {
            $final = array(true, $report);
            return $final;
        } else {
            $final = array(false, $error);
            return $final;
        }
    }

    // ================================================================================================


    function update_scores($class_id, $prog_id, $user_id, $c_score, $e_score, $d_score, $s_score, $late, $lines) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_results';
        $gtable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_gradebook';
        // $stable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_log_submits';

        $data['score'] = $e_score + $d_score + $s_score + $c_score;
        $data['c_points'] = $c_score;
        $data['e_points'] = $e_score;
        $data['d_points'] = $d_score;
        $data['s_points'] = $s_score;
        $data['late'] = $late;
        $data['prog_id'] = $prog_id;
        $data['user_id'] = $user_id;
        $data['lines'] = $lines;

        $d['score'] = $c_score + $e_score + $d_score + $s_score - $late;

        // update gradebook table
        $old_score = 0;
        $r = $this->db->where('user_id', $user_id)->select('a_' . $prog_id)->get($gtable)->row();

        $n = 'a_' . $prog_id;
        if (!empty($r) && isset($r)) {
            $old_score = (int) $r->$n;
        }

        //if ($d['score'] > $old_score) {
        $q = $this->db->where('user_id', $user_id)
                ->set($n, $d['score'])
                ->update($gtable);
        //}
        // Insert into submit log
        //$this->db->insert($stable, $data);
        // update results table
        if ($this->db->where('user_id', $user_id)->where('prog_id', $prog_id)->count_all_results($table) > 0) {
            $s = $this->db->select('score')->where('user_id', $user_id)->where('prog_id', $prog_id)->get($table)->result();
            $s = (int) $s[0]->score;

            //if ($s < $data['score']) {
            $this->db->where('user_id', $user_id)->where('prog_id', $prog_id);
            return $this->db->update($table, $data);
            //}
        } else {
            return $this->db->insert($table, $data);
        }
    }

    function update_reports($class_id, $prog_id, $user_id, $e_report, $d_report, $report) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_results';
        // $stable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_log_submits';

        $data['e_report'] = $e_report;
        $data['d_report'] = $d_report;
        $data['report'] = $report;
        $data['prog_id'] = $prog_id;
        $data['user_id'] = $user_id;

        // update log submit
        //$this->db->where('user_id', $user_id)->where('prog_id', $prog_id)->update($stable, $data);

        if ($this->db->where('user_id', $user_id)->where('prog_id', $prog_id)->count_all_results($table) > 0) {
            $this->db->where('user_id', $user_id)->where('prog_id', $prog_id);
            return $this->db->update($table, $data);
        } else {
            return $this->db->insert($table, $data);
        }
    }

    function update_submit_log($class_id, $data) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_log_submits';
        //echo "Table: $table<hr>";
        //var_dump($data); die();

        $this->db->insert($table, $data);
    }

    function update_atf($class_id, $prog_id, $name, $uid, $content) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $atable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_sr';
        //echo $table . '<br />'; //die();

        $data['user_id'] = trim($this->session->userdata('id'));
        $data['prog_id'] = $prog_id;
        $data['file'] = mysql_real_escape_string($content);
        $data['name'] = $name;

        //echo '<pre>'; print_r($data);  echo '</pre><hr />';
        $cr = array(
            'prog_id' => $prog_id,
            'user_id' => $uid,
        );

        $v = $this->db->where($cr)->count_all_results($atable);

        if ($v > 0) {
            // get current version and update
            $ver = $this->db->select_max('version')->where($cr)->get($atable)->result();
            $version = $ver[0]->version;

            if ($version > 3) {
                $this->db->where('version <=', $version - 3)
                        ->where($cr)
                        ->where('prog_id', $prog_id)
                        ->delete($atable);
            }

            $data['version'] = $version + 1;
            return $this->db->insert($atable, $data);
        } else {
            return $this->db->insert($atable, $data);
        }
    }

    function get_atf($class_id, $prog_id, $name, $uid, $version = NULL) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_student_results';

        $cr = array(
            'prog_id' => $prog_id,
            'user_id' => $uid,
        );

        if ($version == NULL) {
            $q = $this->db->select_max('version')
                            ->where($cr)
                            ->get($table)->result();
            foreach ($q as $row)
                $data[] = $row;
        } else {
            $q = $this->db->where('version', $version)
                            ->where($cr)
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
        
        $this->check_student_table($class_id); // JUst make sure that the lines column is in there

        $cr = array(
            'user_id' => $user_id,
            'prog_id' => $prog_id,
            'file_name' => $file_name,
        );

        $data['user_id'] = $user_id;
        $data['prog_id'] = $prog_id;
        $data['file_name'] = $file_name;
        $data['content'] = $content;
        $data['lines'] = $this->count_code_lines($content);

        $v = $this->db->where($cr)->count_all_results($table);

        // This part updates the item or inserts if it doesn't exist
        if ($v > 0) {
            // get the current version
            $v = $this->db->where($cr)->order_by('version', 'DESC')->limit(1)->get($table)->result();

            // update the version
            $data['version'] = $v[0]->version + 1;

            // update item
            $this->db->where($cr)->update($table, $data);
        } else {
            // insert item
            $this->db->insert($table, $data);
        }
    }

    // Check to see that student directory is correct and in place
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

    // =======================================
    // check documentation here
    // =======================================
    function check_doc($source) { // source file as string
        $d_correct = 0;
        $d_total = 0;
        $d_result = array();

        $source = explode('\n', $source);

        // Items to check for
        $items = array(
            "(c)" . date('Y') . ", " . $this->session->userdata('username'),
            'File name: ',
            'Due date: ',
            'Purpose: ',
            'Author: ',
        );

        // ===
        $d_result[] = '<h3>Documentation</h3>';
        $d_result[] = '<div class="row">';
        $d_result[] = '<div class="col-md-6">';
        $d_result[] = '<div class="panel panel-info">';
        $d_result[] = '<div class="panel-heading">';
        $d_result[] = '<h4>Expected Documentation</h4>';
        $d_result[] = '</div>';
        $d_result[] = '<table class="table table-striped table-bordered table-hover">';
        foreach ($items as $line)
            if (trim($line) != "")
                $d_result[] = "<tr><td>$line</td></tr>";
        $d_result[] = '</table>';
        $d_result[] = '</div>';
        $d_result[] = '</div>';


        // compare source code and score
        $d_result[] = '<div class="col-md-6">';
        $d_result[] = '<div class="panel panel-info">';
        $d_result[] = '<div class="panel-heading">';
        $d_result[] = "<h4>Results for documentation</h4>";
        $d_result[] = '</div>';
        $d_result[] = '<table class="table table-striped table-bordered table-hover">';

        //print_r($d_result); die();
        foreach ($items as $item) {
            $item = trim($item);
            if ($item != "") {
                //echo "Looking for::  $item<br />";
                $d_total++;
                if ($this->result_contains($source, $item)) {
                    $d_correct++;
                    $r = "<tr class=\"text-success\"><td>Found: $item</td></tr>";
                    $d_result[] = $r;
                } else {
                    $r = "<tr class=\"text-danger\"><td>Not Found: $item</td></tr>";
                    $d_result[] = $r;
                }
            }
        }
        $d_result[] = '</table>';
        $d_result[] = '</div>';
        $d_result[] = '</div>';

        // close the row
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
        //$p = array();
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

    function get_version($class_id, $user_id, $prog_id) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_student_files';

        $q = $this->db->where('user_id', $user_id)
                ->where('prog_id', $prog_id)
                ->get($table)
                ->row();

        if (isset($q->version)) {
            return $q->version;
        } else {
            return 1;
        }
    }

    // ==========================================

    function reports($class_id, $user_id, $prog_id, $limit = 1) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_log_submits';

        $q = $this->db->where('prog_id', $prog_id)
                        ->where('user_id', $user_id)
                        ->order_by('time', 'DESC')
                        ->limit($limit)
                        ->get($table)->result();
        //$possible =
        
        return  $q;
    }

    function count_code_lines($code) {
        $pattern = "\n";
        $lines = explode($pattern, $code);
        // echo "Initial lines: ". count($lines) . "<br />";

        foreach ($lines as $k => $v) {
            $line = trim($v);
                // echo "Going through line $k: " . $v . "<br />";

            // Remove all empty lines
            if ($line == "" || empty($line)) {
                // echo "line $k empty --> removing<br />";
                unset($lines[$k]);
            }

            // Remove commented lines
            $pattern = "//";
                // echo "strpos value --->> " . strpos($line, $pattern) . "<br>";
            
            if (strpos($line, $pattern) === false ) {
                // echo "No comment in line<br />";
            } else {
                if( strpos($line, $pattern) == 0 ){
                    //echo "comment line $k --> removing<br />";
                    unset($lines[$k]);
                }
            }
        }

        return count($lines);
    }

    function check_student_table($class_id) {
        $this->load->dbforge();
        
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_student_files';

        // echo $table . "<hr />";
        
        if (!$this->db->field_exists('lines', $table)) {
            $fields = array(
                'lines' => array(
                    'type' => 'INT',
                    'constraint' => 5,
                    'default' => 0,
                )
            );
            
            $this->dbforge->add_column($table, $fields);
        } else {
            // echo 'lines - already exists';
        }
    }
    
    function check_results_tables($class_id) {
        $this->load->dbforge();
        
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_results';
        $ltable = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_log_submits';

        // echo $table . "<hr />";
        
        if (!$this->db->field_exists('lines', $table)) {
            $fields = array(
                'lines' => array(
                    'type' => 'INT',
                    'constraint' => 5,
                    'default' => 0,
                )
            );
            
            $this->dbforge->add_column($table, $fields);
        } 
        
        if (!$this->db->field_exists('lines', $ltable)) {
            $fields = array(
                'lines' => array(
                    'type' => 'INT',
                    'constraint' => 5,
                    'default' => 0,
                )
            );
            
            $this->dbforge->add_column($ltable, $fields);
        } 
    }

}

