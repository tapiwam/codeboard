<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends CI_Controller{
	
	function index()
	{
		$data['main_content'] = 'forum/home';
		$this->load->view('includes/template', $data);
	}
}
	