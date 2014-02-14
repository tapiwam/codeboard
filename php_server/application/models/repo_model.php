<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repo_model extends CI_Model {
	
	private $root;
	private $files;
	
	function __construct()
	{
		parent::__construct();
		$this->root =  base_url();
		$this->files = $this->root.'files/';
	}
	
	function test(){
		exec('pwd', $out);
		echo '<pre>';
		print_r($out);
		echo '</pre>';
		
		echo 'exit status: '. $exitstat . '<br />';
		echo '<pre>';print_r($out);echo '</pre>';
	}
	
		// ===========================================
	// INITIALIZE REPO
	// ===========================================
	function initialize($term, $classname){
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
	
	// ===========================================
	// SINGLE COPY
	// ===========================================
	
	function scopy($term, $classname, $filename){
		// SUBMITcopy repo Filename 
		$shell =  "bin/Scopy $term $classname $filename  2>&1" ;
		exec( $shell, $out, $exitstat );
		if ($exitstat == 0)
			return true;
		
	}
	
	function pcopy($term, $classname, $filename){
		$shell =  "bin/Pcopy $term $classname $filename  2>&1" ;
		exec( $shell, $out, $exitstat );
		if ($exitstat == 0)
			return true;
		
	}
	
	// ===========================================
	// MULTI COPY -- good
	// ===========================================
	
	function smcopy($term, $classname, $pattern1 , $pattern2=NULL ){
		// Rmcopy repo pattern1 [pattern2] [file_tail] [root_dir]
		$shell =   "bin/Smcopy $term $classname $pattern1 $pattern2  2>&1";
		exec( $shell, $out, $exitstat );
		
		if($exitstat == 0)
		return true;
	}
	
	function pmcopy($term, $classname, $pattern1, $pattern2=NULL){
		// Rmcopy repo pattern1 [pattern2] [file_tail] [root_dir]
		$shell =   "bin/Pmcopy $term $classname $pattern1 $pattern2 2>&1";
		echo "looking for: *$pattern1*$pattern2* <hr />";
		exec( $shell, $out, $exitstat );
		
		echo 'exit status: '. $exitstat . '<br />'; echo '<pre>';print_r($out);echo '</pre>';// die();
		
		if($exitstat == 0)
		return true;
	}
	
	// ===========================================
	// POST -- good
	// ===========================================
	
	function spost($term, $classname, $filename){
		if( ! is_dir("files/$term/$classname/submits") ) $this->initialize($term, $classname);

		// Rpost repo filename [location]
		$shell =   "bin/Spost $term $classname $filename 2>&1";
		
		exec( $shell, $out, $exitstat );
		if($exitstat == 0)
			return true;
	}
	
	function ppost($term, $classname, $filename){
		if( ! is_dir("files/$term/$classname/public") ) $this->initialize($term, $classname);
			
		// Rpost repo filename [location]
		$shell =   "bin/Ppost $term $classname $filename 2>&1";
		exec( $shell, $out, $exitstat );
	}
	
	// ===========================================
	// SUBMIT
	// ===========================================
	
	function submit($term, $assignid, $filename, $usr){
		if( ! is_dir("files/$term") ) $this->initialize($term);
		
		// FSsubmit repo assignid filename usr [file_dir_loaction]
		$shell = "bin/Rsubmit $term $assignid $filename $usr ./files 2>&1";
		exec( $shell, $out, $exitstat );
		if($exitstat == 0)
		return true;
	}

	// ===========================================
	// CLEAN
	// ===========================================
	
	function sclean($term, $classname, $filename){
		// Rclean repo filename
		$shell = "bin/Sclean $term $classname $filename 2>&1";
		exec( $shell, $out, $exitstat );
		
		if($exitstat == 0)
		return true;
	}
	
	function pclean($term, $classname, $filename){
		// Rclean repo filename
		$shell = "bin/Pclean $term $classname $filename 2>&1";
		exec( $shell, $out, $exitstat );
		if($exitstat == 0)
		return true;
	}
	
	function delete_repo($term, $classname){
		 $dir = "files/$term/$classname";

		 if(is_dir($dir) )
		 {
		 	$this->deleteDir($dir);
			if( !is_dir($dir) )
				return true;
		 }
	}
	
	static function deleteDir($dirPath) {
	    if (! is_dir($dirPath)) {
	        throw new InvalidArgumentException("$dirPath must be a directory");
	    }
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {
	        if (is_dir($file)) {
	            self::deleteDir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    rmdir($dirPath);
	}
}

//echo 'exit status: '. $exitstat . '<br />'; echo '<pre>';print_r($out);echo '</pre>'; die();


