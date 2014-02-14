<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// This class deals with the concept behind creating components needed for the app

class Activate_deactivate_model extends CLASS_Model {
	
	function __contruct()
	{
		parent::__contruct();
		
		$this->user_model->is_logged_in();
		$this->user_model->is_admin();
	}
	
	// ======================================
	
	function activate_class($class_id)
	{
		return $this->db->where('id', $class_id)->set('active', '1')->update('classes');
	}
		
	function activate_session($class_id, $session_id)
	{
		$classinfo = $this->load_class_info($class_id);
		$table = $classinfo[0]->term.'_'. $classinfo[0]->class_name.'_sessions';
		return $this->db->where('id', $session_id)->set('active', '1')->update($table);
	}
	
	function activate_assignment($class_id, $session_id, $prog_name)
	{
		$classinfo = $this->load_class_info($class_id);
		$table = $classinfo[0]->term.'_'. $classinfo[0]->class_name.'_programs';
		$p = $this->pidFromPname($class_id, $session_id, $prog_name);
		$pid = $p[0]->id;
		return $this->db->where('id', $pid)->set('active', '1')->update($table);
	}
	
	// ======================================
	
	function deactivate_class($class_id)
	{
		return $this->db->where('id', $class_id)->set('active', '0')->update('classes');
	}
	
	function deactivate_session($class_id, $session_id)
	{
		$classinfo = $this->load_class_info($class_id);
		$table = $classinfo[0]->term.'_'. $classinfo[0]->class_name.'_sessions';
		return $this->db->where('id', $session_id)->set('active', '0')->update($table);
	}
	
	function deactivate_assignment($class_id, $session_id, $prog_name)
	{
		$classinfo = $this->load_class_info($class_id);
		$table = $classinfo[0]->term.'_'. $classinfo[0]->class_name.'_programs';
		$p = $this->pidFromPname($class_id, $session_id, $prog_name);
		$pid = $p[0]->id;
		return $this->db->where('id', $pid)->set('active', '0')->update($table);
	}
	
}