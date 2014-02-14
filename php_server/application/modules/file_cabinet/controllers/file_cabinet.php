<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class File_cabinet extends MX_Controller {

    function __contruct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->load->helper('download');
    }

    function index() {
        echo $_SERVER['SERVER_NAME'];
        $this->load->model('ad_model');
        $data['classes'] = $this->ad_model->classes();
        $data['main_content'] = 'home';
        $this->load->view('includes/template', $data);
    }

    function cls($class_id, $msg = null) {
        $this->load->model('ad_model');
        $classinfo = $this->ad_model->load_class_info($class_id);
        $t = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_uploads';

        $this->load->model('files_model');
        if (isset($msg)) {
            $data['msg'] = $msg;
        }
        $data['classinfo'] = $this->ad_model->load_class_info($class_id);
        $data['class_id'] = $class_id;
        $data['files'] = $this->files_model->list_files($t);
        $data['main_content'] = 'class_files';
        $this->load->view('includes/template', $data);
    }

    /*
      function upload_option($class_id)
      {
      $this->load->model('ad_model');
      $classinfo = $this->ad_model->load_class_info($class_id);
      $t = $classinfo[0]->term.'_'.$classinfo[0]->class_name.'_uploads';
      $data['class_id'] = $class_id;
      $data['main_content'] = 'upload';
      $this->load->view('includes/_layout_modal', $data);
      }
     * 
     */

    // ====================================
    // Ajax calls

    function upload($class_id) {
        $this->load->model('files_model');
        $this->load->model('ad_model');
        $classinfo = $this->ad_model->load_class_info($class_id);
        $t = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_uploads';

        if ($this->files_model->upload_file($t)) {
            $msg = $this->upload->display_errors();
        } else {
            $msg = "File successfully uploaded";
        }

        $this->cls($class_id, $msg);
    }

    function download($class_id, $name) {
        $this->load->model('files_model');
        $this->load->model('ad_model');

        $classinfo = $this->ad_model->load_class_info($class_id);
        $t = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_uploads';
        // echo $t . '<hr />';
        $this->files_model->download_file($t, $name);
        $this->cls($class_id);
    }

    function delete_file($class_id, $name) {
        $this->load->model('files_model');
        $this->load->model('ad_model');
        $classinfo = $this->ad_model->load_class_info($class_id);
        $t = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_uploads';

        $this->files_model->delete_file($t, $name);
        $this->cls($class_id);
    }

}

