<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student_model extends CI_Model {

    function __contruct() {
        parent::__construct();
        $this->load->model('class_admin/admin_model');
        $this->load->model('grader_model');
    }

    function load_classes() {
        $user_id = $this->session->userdata('id');
        //echo $user_id; die();

        $data = array();
        $this->db->where('user_id', $user_id);
        $q = $this->db->get('registration');

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $class_id = $row->class_id;

                // ====================
                // Use the id to get the class data
                $this->db->where('id', $class_id);
                $d = $this->db->get('classes');
                if ($d->num_rows() > 0) {
                    foreach ($d->result() as $r) {
                        $data[] = $r;
                    }
                }
                // ===================
            }
            return $data;
        }
    }

    function available_classes() {
        $q = $this->db->get('classes');
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function class_list() {
        //$this->db->select('term, class_name, section, instructor');
        $q = $this->db->get('classes');
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function register() {
        $classinfo = $this->admin_model->load_class_info($this->input->post('class_id'));
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_gradebook';

        $data = array(
            'user_id' => $this->session->userdata('id'),
            'class_id' => $this->input->post('class_id')
        );

        // Check the passcode then check to see if not already registered
        $passcode = $this->input->post('passcode');
        $this->db->where('id', $data['class_id']);
        $this->db->where('passcode', $passcode);
        $this->db->from('classes');

        if ($this->db->count_all_results() > 0) {
            // Check if not already registered
            $this->db->where('user_id', $data['user_id']);
            $this->db->where('class_id', $data['class_id']);
            $this->db->from('registration');

            if ($this->db->count_all_results() == 0) {
                $q = $this->db->insert('registration', $data);

                // add them to gradebook
                $d = array('user_id' => $this->session->userdata('id'));
                if ($this->db->where('user_id', $this->session->userdata('id'))->count_all_results($table) == 0) {
                    $q1 = $this->db->insert($table, $d);
                }

                return $q;
                //echo $q; die();
            }
        } else {
            return FALSE;
        }
    }

    // =================================================================================
    // =================================================================================
    // =================================================================================

    function announce() {
        $q = $this->db->get('announce', 5, 0);

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function recent() {
        $q = $this->db->get('announce', 5, 0);

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    // =================================================================================
    // =================================================================================
    // =================================================================================

    function submit_file($class_id, $session_id, $prog_name, $student) {
        $prog_id = $this->tc_model->pidFromPname($class_id, $session_id, $prog_name);
        $pid = $prog_id[0]->id;

        $classinfo = $this->admin_model->load_class_info($class_id);
        $prog = $this->admin_model->load_assignment($class_id, $session_id, $pid);
        $fileinfo = $this->tc_model->load_filedetails($class_id, $pid);

        $comp = "";

        foreach ($fileinfo as $file) {
            // echo '<pre>'; print_r($file); echo '</pre><hr />'; 
            if ($file->admin_file == 0 && $file->stream_type != "output" && $file->multi_part == 0) {
                if ($file->stream_type == "source") {
                    $comp .= $file->file_name . " ";
                }

                $f = $this->input->post($file->id);
                // echo $file->file_name . '<hr />' . $f . '<hr />';

                $path = 'files/' . $classinfo[0]->term . '/' . $classinfo[0]->class_name . '/students/' . $student . '/' . $file->file_name;
                //echo $path . '<hr />';

                if (write_file($path, $f))
                    echo 'file written<br />';
            }
        }

        //echo $comp . '<br />';

        $progdir = 'files/' . $classinfo[0]->term . '/' . $classinfo[0]->class_name . '/students/' . $student;
        $go = "g++ $comp -Wall -ansi -o  " . $prog[0]->prog_name . ' 2>&1';
        $current = getcwd();
        chdir($progdir);
        exec($go, $out, $exitstat);
        chdir($current);

        $e = implode('\n', $out);
        $err = "*******************\n  COMPILE ERROR\n*******************\n$e\n";

        // echo '<pre>'; print_r($err) ; echo '</pre>'; die();

        if ($exitstat == 0)
            return true;
        else {
            return $err;
        }
    }

    function load_student_files($class_id, $user_id, $prog_id) {
        $classinfo = $this->admin_model->load_class_info($class_id);
        $table = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_student_files';

        // return all student submitted files for the given pid
        return $this->db->where('user_id', $user_id)
                        ->where('prog_id', $prog_id)
                        ->order_by('version', 'desc')
                        ->get($table)->result();
    }

    // ========================================================

    function load_session_assignments($class_id, $session_id) {
        //$data = array();
        // ====================
        // Use the id to get the class data
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];

        // find tyhe correct table
        $p_table = $classinfo->term . '_' . $classinfo->class_name . '_programs';

        // grab from DB
        $this->db->where('session_id', $session_id)
                ->where('published', 1)
                ->where('active', 1);
        $d = $this->db->get($p_table);
        if ($d->num_rows() > 0) {
            foreach ($d->result() as $r) {
                $data[] = $r;
            }
        }

        //print_r($data); die();
        if (isset($data)) {
            return $data;
        }
    }
}

