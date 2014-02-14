<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller{
	
	// ============================================================================
	// Extra Functions to validate certain information can be found in user_model
	// ============================================================================
	
	function index()
	{
		$data['main_content'] = 'site';
		$this->load->view('includes/template', $data);
	}	
	
	
	function members()
	{
		// Check if a user is a student, class admin or site admin and send them to the correct place
		$this->user_model->user_data();
		
		// debug
		//echo $this->session->userdata('level');  die;
		
		if ( $this->session->userdata('level') == 0 ){
			// Student
			redirect('student');
		} 
		elseif ( $this->session->userdata('level') == 1 ) {
			// Class Admin
			redirect('ta');
		} 
		elseif ( $this->session->userdata('level') == 2 ) {
			// Class Admin
			redirect('class_admin');
		} 
		elseif ( $this->session->userdata('level') == 3 ) {
			// Super User
			redirect('site_admin');
		}
		else {
			$this->index();
		}
	}
	
	function contact()
	{
		$data['main_content'] = 'contact';
		$this->load->view('includes/template', $data);
	}	
	
	function profile()
	{
		$data['main_content'] = 'user/profile';
		$this->load->view('includes/template', $data);
	}
	
	function settings()
	{
		$data['main_content'] = 'user/settings';
		$this->load->view('includes/template', $data);
	}
	
	function register()
	{
		$data['main_content'] = 'user/register';
		$this->load->view('includes/template', $data);
	}
	
}