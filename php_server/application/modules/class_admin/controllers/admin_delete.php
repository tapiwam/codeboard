<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_delete extends CLASS_Controller {

	function __construct()
	{ 
		parent::__construct();
		$this->user_model->is_admin();
		$this->load->model('admin_model');
		$this->load->model('admin_create_model');
		$this->load->model('student_model');
	}
	
	
	function delete_session()
	{
		// delete from programs table and sessions table
		$classinfo = $this->admin_model->load_class_info($classid);
		$stbl = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_sessions';
		$ptbl = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';
		
		$this->db->where('session_id', $sessionid);
		if( $this->db->delete($ptbl) ){
			$this->db->where('session_id', $sessionid);
			return $this->db->delete($stbl);
		}
	}
	
	function delete_assignment($classid, $sessionid, $prog_id)
	{
		$classinfo = $this->admin_model->load_class_info($classid);
		$tbl = $classinfo[0]->term . '_' . $classinfo[0]->class_name . '_programs';
		
		$this->db->where('class_id', $classid);
		$this->db->where('session_id', $sessionid);
		$this->db->where('id', $prog_id);
		$status = $this->db->delete($tbl);
		return $status;
	}
	
	
}