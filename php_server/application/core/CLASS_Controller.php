<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CLASS_Controller extends CI_Controller {
		
	function __construct(){
		parent::__construct();
		$this->user_model->is_logged_in();
		
		$this->data['errors'] = array();
		$this->data['site_name'] = config_item('site_name');
	}
	

}