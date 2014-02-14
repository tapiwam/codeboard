+
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tc_manage extends CLASS_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('tc_model');
        $this->load->model('tc_m_model');
        $this->load->model('admin_model');
        $this->user_model->is_logged_in();
        $this->user_model->is_admin();
    }

    // ================================================
    // change information for assignment
    // ================================================

    function points($class_id, $session_id, $prog_name) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['prog_name'] = $prog_name;

        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);

        $data['main_content'] = 'tc_manage/points';
        $this->load->view('includes/template', $data);
    }

    function update_points($class_id, $session_id, $prog_name) {
        $this->form_validation->set_rules('c_points', 'compile points', 'trim|numeric|required');
        $this->form_validation->set_rules('s_points', 'submission points', 'trim|numeric|required');
        $this->form_validation->set_rules('d_points', 'documentation points', 'trim|numeric|required');
        $this->form_validation->set_rules('e_points', 'execution points', 'trim|numeric|required');
        $this->form_validation->set_rules('late', 'late deduction points', 'trim|numeric|required');

        if ($this->form_validation->run() == FALSE) {
            $data['class_id'] = $class_id;
            $data['session_id'] = $session_id;
            $data['prog_name'] = $prog_name;

            $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
            $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

            $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);

            $data['main_content'] = 'tc_manage/points';
            $this->load->view('includes/template', $data);
        } else {
            $q = $this->tc_m_model->update_points($class_id, $session_id, $prog_name);
            redirect("class_admin/manage/assignments/$class_id/$session_id");
        }
    }

    function description($class_id, $session_id, $prog_name) {
        $data['class_id'] = $class_id;
        $data['session_id'] = $session_id;
        $data['prog_name'] = $prog_name;

        $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
        $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

        $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);

        $data['main_content'] = 'tc_manage/description';
        $this->load->view('includes/template', $data);
    }

    function update_description($class_id, $session_id, $prog_name) {
        $this->form_validation->set_rules('description', 'program description', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['class_id'] = $class_id;
            $data['session_id'] = $session_id;
            $data['prog_name'] = $prog_name;

            $data['classinfo'] = $this->admin_model->load_class_info($data['class_id']);
            $data['sessioninfo'] = $this->admin_model->load_session_info($data['class_id'], $data['session_id']);

            $data['info'] = $this->tc_model->load_basicinfo($class_id, $session_id, $prog_name);

            $data['main_content'] = 'tc_manage/description';
            $this->load->view('includes/template', $data);
        } else {
            $q = $this->tc_m_model->update_description($class_id, $session_id, $prog_name);
            redirect("class_admin/manage/assignments/$class_id/$session_id");
        }
    }

}

