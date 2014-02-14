<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// This class deals with the concept behind creating components needed for the app

class Admin_delete_model extends CI_Model {
	
	function __contruct()
	{
		parent::__contruct();
		
		$this->user_model->is_logged_in();
		$this->user_model->is_admin();
		
		$this->load->model('tables_model');
		
		
	}
	
	// ======================================
	
	function delete_session($class_id, $session_id)
	{
		$classinfo = $this->admin_model->load_class_info($class_id);
		$class_name 	= $classinfo[0]->class_name;
		$term 			= $classinfo[0]->term;
	}
	
	// Updated Look at testcases contoller to handle this
	function delete_assignment($class_id, $prog_id)
	{
		$classinfo = $this->admin_model->load_class_info($class_id);
		$class_name 	= $classinfo[0]->class_name;
		$term 			= $classinfo[0]->term;
	}
	
	private static function deleteDir($path) {
	    if (!is_dir($path)) {
	        throw new InvalidArgumentException("$path is not a directory");
	    }
	    if (substr($path, strlen($path) - 1, 1) != '/') {
	        $path .= '/';
	    }
	    $dotfiles = glob($path . '.*', GLOB_MARK);
	    $files = glob($path . '*', GLOB_MARK);
	    $files = array_merge($files, $dotfiles);
	    foreach ($files as $file) {
	        if (basename($file) == '.' || basename($file) == '..') {
	            continue;
	        } else if (is_dir($file)) {
	            self::deleteDir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    rmdir($path);
	}
}