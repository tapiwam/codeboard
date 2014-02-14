<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ta extends CI_Controller{

	function __construct()
	{
		parent::__construct();
		$this->user_model->is_logged_in();
		$this->user_model->is_admin();
		$this->load->model('admin_model');
		$this->load->model('student_model');
	}
	
	function index()
	{
		echo "TA PAGE"; die();
		
		$data['classes'] = $this->ta_model->classes();
		$data['main_content'] = 'admin/home';
		$this->load->view('includes/template', $data);
	}
	
}