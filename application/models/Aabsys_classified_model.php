<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Aabsys_classified_model extends CI_Model { 
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	
	
	public function get_my_classified($user_id) 
	{
		$this->db->select('classified.*, internal_user.name_first, internal_user.name_last, internal_user.mobile');		
		$this->db->from('classified');		
		$this->db->join('internal_user','internal_user.login_id = classified.posted_by');		
		$this->db->where('n_disp_flag','1');		
		$this->db->where('classified.posted_by',$user_id);		
		$this->db->order_by('classified.ix_classified', 'DESC');		
		$query = $this->db->get();		
		$result = $query->result(); 
		return $result; 
	}
	
	public function insert_my_classified($classifiedHeader, $classifiedDesc, $file, $user_id)
	{
		$data = array(
			'classified_header' => $classifiedHeader,
			'classified_body' => $classifiedDesc,
			'classified_file' => $file,
			'posted_by' => $user_id,
			'date' => date('Y-m-d H:i:s')
		);
		$this->db->insert('classified', $data);	
	}
	
	
}