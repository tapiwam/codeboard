<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Initialize_model extends CLASS_Model {
	
	private $root;
	private $files;
	
	function __construct()
	{
		parent::__construct();
		$this->root =  base_url();
		$this->files = $this->root.'files/';
	}
	
	function init_files($term, $classname){
		$oldmask = umask(0);
		
		if(!is_dir('files'))
			mkdir('files', 0777);
			
		if (!is_dir("files/$term"))
			mkdir("files/$term", 0777);
		
		if (!is_dir("files/$term/$classname"))
			mkdir("files/$term/$classname", 0777);
		
		if (!is_dir("files/$term/$classname/tmp"))
			mkdir("files/$term/$classname/tmp", 0777);
		
		if (!is_dir("files/$term/$classname/admin"))
			mkdir("files/$term/$classname/admin", 0777);
		
		if (!is_dir("files/$term/$classname/students"))
			mkdir("files/$term/$classname/students", 0777);
		
		if (!is_dir("files/$term/$classname/submits"))
			mkdir("files/$term/$classname/submits", 0777);
		
		if (!is_dir("files/$term/$classname/submits/RCS"))
			mkdir("files/$term/$classname/submits/RCS", 0777);
		
		if (!is_dir("files/$term/$classname/public"))
			mkdir("files/$term/$classname/public", 0777);
		
		if (!is_dir("files/$term/$classname/public/RCS"))
			mkdir("files/$term/$classname/public/RCS", 0777);
		
		//===========================================================
		
		if (!is_dir("files/$term/$classname/uploads"))
			mkdir("files/$term/$classname/uploads", 0777);
		
		umask($oldmask);
		
		if(is_dir("files/$term/$classname")) {
			//echo 'files/'.$term.'/'.$classname . ' created<br />'; 
			return true;
		}
		else {
			//echo 'NOT created<br />';
			return false;
		}
	}

}