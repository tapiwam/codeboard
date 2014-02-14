<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad_model extends CLASS_Model {
	
	function __contruct(){
		parent::__contruct();
	}
	
	function classes()
	{
		// return class list for given instructor
		$this->db->where('instructor', $this->session->userdata('username'));
		$q = $this->db->get('classes');
		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
            {
            	$data[] = $row;
			}
			return $data;
		}
	}
	
}