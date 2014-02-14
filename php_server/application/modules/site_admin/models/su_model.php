<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SU_model extends CI_Model {

	function __contruct()
	{
		parent::__contruct();
		$this->load->dbforge();

	}
	
	function classes()
	{
		return $this->db->get('classes')->result();
	}
	
		function delete_class($class_id) 
	{
		echo 'Deleting class<hr />';
		$this->load->dbforge();
		
		//$this->user_model->is_site_admin();
		
		$this->load->model('admin_model');
		$classinfo = $this->admin_model->load_class_info($class_id);
		
		// Start delete all the tables
		$term = $classinfo[0]->term;
		$class_name = $classinfo[0]->class_name;
		
		// delete dir and files
			$path = "files/$term/$class_name";
			$this->deleteDir($path);
		
		// TABLES
			
			$table = "${term}_${class_name}_sessions";
			$status = $this->dbforge->drop_table($table);
			echo "<hr />Deleteing sessions table: $status";
			
			$table = "${term}_${class_name}_announcements";
			$status = $this->dbforge->drop_table($table);
			echo "<hr />Deleteing announce table: $status";

			$table = "${term}_${class_name}_gradebook";
			$status = $this->dbforge->drop_table($table);
			echo "<hr />Deleteing gradebook table: $status";

			$table = "${term}_${class_name}_programs";
			$status = $this->dbforge->drop_table($table);
			echo "<hr />Deleteing programs table: $status";

			$table = "${term}_${class_name}_files";
			$status = $this->dbforge->drop_table($table);
			echo "<hr />Deleteing files table: $status";

			$table = "${term}_${class_name}_student_files";
			$status = $this->dbforge->drop_table($table);
			echo "<hr />Deleteing stud files table: $status";

			$table = "${term}_${class_name}_testcases";
			$status = $this->dbforge->drop_table($table);
			echo "<hr />Deleteing testcases table: $status";
		

			$table = "${term}_${class_name}_results";
			$status = $this->dbforge->drop_table($table);
			echo "<hr />Deleteing results table: $status";
		
			$table = "${term}_${class_name}_sr";
			$status = $this->dbforge->drop_table($table);
			echo "<hr />Deleteing sr table: $status";
			
		// from register table
		$this->db->where('class_id', $class_id);
		$status = $this->db->delete('registration');
		echo "<hr />Deleteing registrations entry: $status";
		
		// from classes table
		$this->db->where('id', $class_id);
		$status = $this->db->delete('classes');
		echo "<hr />Deleteing class entry: $status";
	}
}