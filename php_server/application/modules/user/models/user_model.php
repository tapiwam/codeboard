<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model {

    function validate() {
        // Get the username and password from post
        $this->db->where('username', $this->input->post('username'));
        $this->db->where('password', md5($this->input->post('password')));

        // Try get a record using the credentials
        $q = $this->db->get('users');

        // If I get anything then I'm good
        if ($q->num_rows == 1) {
            return true;
        }
    }

    function is_active() {
        $this->db->where('username', $this->input->post('username'));
        $this->db->where('password', md5($this->input->post('password')));

        // Try get a record using the credentials
        $q = $this->db->get('users');

        // If I get anything then I'm good
        if ($q->num_rows == 1) {
            $q1 = $q->row();
            if ($q1->active == 1) {
                // echo "true"; die();
                return true;
            }
            // echo "false"; die();
        }
    }

    function validate_password($pass) {
        // Get the username and password from post
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('password', $pass);

        // Try get a record using the credentials
        $q = $this->db->get('users');

        // If I get anything then I'm good
        if ($q->num_rows == 1) {
            return true;
        }
    }

    // ============================================

    function create_member($level = null) {
        // Grab all the validated post info
        $new_member_insert_data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password')),
            'student_id' => $this->input->post('student_id')
        );

        if ($level != null) {
            $new_member_insert_data['level'] = $level;
            $new_member_insert_data['active'] = 0;
        }

        // Insert into the database
        $insert = $this->db->insert('users', $new_member_insert_data);

        // Check to see if everything went OK
        return $insert;
    }

    // ============================================

    function is_logged_in() {
        // Get the session logged in status
        $is_logged_in = $this->session->userdata('is_logged_in');

        // Check and see if logged in. SEND THEN TO THE HOME SCREEN OTHERWISE
        if (!isset($is_logged_in) || $is_logged_in != true) {
            redirect('user/login');
            die();
        }
    }

    function login_check() {
        // Get the session logged in status
        $is_logged_in = $this->session->userdata('is_logged_in');

        // Check and see if logged in or not. return tru/false
        if (!isset($is_logged_in) || $is_logged_in != true) {
            return false;
        } else {
            return true;
        }
    }

    // ============================================

    function user_data() {
        // Check if logged in
        $this->login_check();

        // Query database and get only relvant data pertaining to user that we could use
        $username = $this->session->userdata('username');

        // $this->db->select('id, first_name, last_name, level, email');
        $this->db->where('username', $username);
        $q = $this->db->get('users');

        // Store data within the session
        if ($q->num_rows() == 1) {
            $d = $q->result();
            $data = array(
                'id' => $d[0]->id,
                'first_name' => $d[0]->first_name,
                'last_name' => $d[0]->last_name,
                'level' => $d[0]->level,
                'email' => $d[0]->email,
                'p' => $d[0]->password,
                'student_id' => $d[0]->student_id,
            );

            // Debug
            //echo '<pre>';
            //print_r($data);
            //echo '</pre><br><br>';

            $this->session->set_userdata($data);

            // echo "<br><br>session email-> ".$this->session->userdata('email'); die();
            

            return true;
        } else {
            return false;
        }
        // return true of false on the status
    }

    // ============================================
    function site_admin_check() {
        // Get security level from session
        $level = $this->session->userdata('level');

        // Check if admin and return true of false
        if (!isset($level) || $level != 3) {
            return false;
        } else {
            return true;
        }
    }

    function admin_check() {
        // Get security level from session
        $level = $this->session->userdata('level');

        // Check if admin and return true of false
        if (!isset($level) || $level != 2) {
            return false;
        } else {
            return true;
        }
    }

    function ta_check() {
        // Get security level from session
        $level = $this->session->userdata('level');

        // Check if admin and return true of false
        if (!isset($level) || $level != 1) {
            return false;
        } else {
            return true;
        }
    }

    function student_check() {
        // Get security level from session
        $level = $this->session->userdata('level');

        // Check if student and return true or false
        if (!isset($level) || $level != 0) {
            return false;
        } else {
            return true;
        }
    }

    // ==========================================

    function is_site_admin() {
        // Get the session logged in status
        $level = $this->session->userdata('level');

        // Check and see if logged in. SEND THEN TO THE HOME SCREEN OTHERWISE
        if ($level != 3) {
            redirect($this->index);
            die();
        }
    }

    function is_admin() {
        // Get the session logged in status
        $level = $this->session->userdata('level');

        // Check and see if logged in. SEND THEN TO THE HOME SCREEN OTHERWISE
        if ($level < 1) {
            redirect($this->index);
            die();
        }
    }

    function is_student() {
        // Get the session logged in status
        $level = $this->session->userdata('level');

        // Check and see if logged in. SEND THEN TO THE HOME SCREEN OTHERWISE
        if ($level != 0) {
            redirect($this->index);
            die();
        }
    }

    // ============================================

    function email_exists() {
        // Get email address from post
        $email = $this->input->post('email');

        // Query database to see if email exists
        $this->db->where('email', $email);
        $q = $this->db->get('users');

        // return true of false
        if ($q->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    function username_exists() {
        // Get username from post
        $username = $this->input->post('username');

        // Query database to see if username exists
        $this->db->where('username', $username);
        $q = $this->db->get('users');

        // return true of false
        if ($q->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    // ============================================

    function update_password() {
        //Pull up user_id from session data
        $id = $this->session->userdata('id');

        // Get information from post
        $pass = md5($this->input->post('new_pass'));

        // DB update
        return $this->db->where('id', $id)->set('password', $pass)->update('users');
    }

    function update_email() {
        //Pull up user_id from session data
        $id = $this->session->userdata('id');

        // Get information from post
        $pass = $this->input->post('email');
        
        // check username not taken
        $count = $this->db->where('email', $pass)->count_all_results('users');
        if($count > 0){ return false; }

        // DB update
        if ($this->db->where('id', $id)->set('email', $pass)->update('users')) {
            $data['email'] = $this->input->post('email');
            $this->session->set_userdata($data);
            return true;
        }
    }
    
    function update_sid() {
        //Pull up user_id from session data
        $id = $this->session->userdata('id');

        // Get information from post
        $sid = $this->input->post('student_id');
        
        // check username not taken
        $count = $this->db->where('student_id', $sid)->count_all_results('users');
        if($count > 0){ return false; }

        // DB update
        if ($this->db->where('id', $id)->set('student_id', $sid)->update('users')) {
            $data['student_id'] = $this->input->post('student_id');
            $this->session->set_userdata($data);
            return true;
        }
    }
    
    function update_username() {
        //Pull up user_id from session data
        $id = $this->session->userdata('id');

        // Get information from post
        $uname = $this->input->post('username');
        
        // check username not taken
        $count = $this->db->where('username', $uname)->count_all_results('users');
        if($count > 0){ return false; }
        
        // DB update
        if ($this->db->where('id', $id)->set('username', $uname)->update('users')) {
            $data['username'] = $this->input->post('username');
            $this->session->set_userdata($data);
            return true;
        }
    }

    // ============================================

    function username_from_id($id) {
        $q = $this->db->select('username')
                ->where('id', $id)
                ->get('users')
                ->row();

        if (count($q))
            return $q->username;
    }

    function id_from_username($name) {
        $q = $this->db->select('id')
                ->where('username', $name)
                ->get('users')
                ->row();

        if (count($q))
            return $q->id;
    }

    function name_from_id($id) {
        $q = $this->db->select('first_name, last_name')
                ->where('id', $id)
                ->get('users')
                ->result();

        $name = $q[0]->first_name . ' ' . $q[0]->last_name;
        return $name;
    }
    
    function lastname_from_id($id) {
        $q = $this->db->select('last_name')
                ->where('id', $id)
                ->get('users')
                ->result();

        $name = $q[0]->last_name;
        return $name;
    }
    
    function firstname_from_id($id) {
        $q = $this->db->select('first_name')
                ->where('id', $id)
                ->get('users')
                ->result();

        $name = $q[0]->first_name;
        return $name;
    }

    function email_from_id($id) {
        $q = $this->db->select('email')
                ->where('id', $id)
                ->get('users')
                ->result();

        $email = $q[0]->email;
        return $email;
    }

    function student_id_from_id($id) {
        $q = $this->db->select('student_id')
                ->where('id', $id)
                ->get('users')
                ->result();

        $sid = $q[0]->student_id;
        return $sid;
    }

    // -----------------------------------------------
    
    function log_time($id) {
        $data = array(
          user_id => $id,
        );
        
        return $this->db->insert('log_login', $data);
    }
}
