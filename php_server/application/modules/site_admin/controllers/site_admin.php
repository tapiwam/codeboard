<?php

class Site_admin extends MX_Controller{
	
	function __construct()
	{
		parent::__construct();
		$this->user_model->is_logged_in();
		$this->user_model->is_site_admin();
		$this->load->model('su_model');
		$this->load->model('admin_delete_model');
	}
	
	
	function index()
	{
		$data['main_content'] = 'home';
		$this->load->view('includes/template', $data);
	}
	
	function classes()
	{
		$data['classes'] = $this->su_model->classes();
		
		$data['main_content'] = 'dashboard';
		$this->load->view('includes/template', $data);
	}
	
	
	// ===================================
	// Delete
	// ===================================
	
	function delete_class($class_id)
	{
		// echo $class_id;
		
		$this->su_model->delete_class($class_id);
		redirect('site_admin');
	}
	

}
