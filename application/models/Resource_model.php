<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Resource_model extends CI_Model { 
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	public function get_aabsys_info() 
	{ 	 
		$sql = $this->db->query("SELECT resource_link.*, DATE_FORMAT(resource_link.dttime, '%d/%m/%Y %r') as dttimes FROM resource_link"); 
		$result = $sql->result_array();
		return $result; 
	} 
	public function get_guidelines() 
	{ 	 
		$sql = $this->db->query("SELECT * FROM resource_topic"); 
		$result = $sql->result_array();
		return $result; 
	}
	public function get_staff_format_rules() 
	{ 	 
		$sql = $this->db->query("SELECT resource_doc.*, DATE_FORMAT(resource_doc.dttime, '%d/%m/%Y %r') as dttimes FROM  `resource_doc` where topic_id='1' AND flag='1' order by dttime desc"); 
		$result = $sql->result_array();
		return $result; 
	}
	public function get_resource($choose_year) 
	{ 	 
		//$sql = $this->db->query("SELECT d.*,c.* FROM declared_leave d LEFT JOIN company_branch c ON c.branch_id=d.branch WHERE d.dt_event_date LIKE '$choose_year%' AND d.leave_type='D'  ORDER BY dt_event_date");
		$cond = " AND DATE_FORMAT(dt_event_date,'%Y')=$choose_year";
		$sql = $this->db->query("SELECT d.*,c.* FROM declared_leave d LEFT JOIN company_branch c ON c.branch_id=d.branch WHERE d.leave_type='D' $cond  ORDER BY dt_event_date DESC"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	public function get_all_photo_gallery() 
	{ 	 
		$sql = $this->db->query("SELECT a.*, p.photo_id, p.photo_name FROM `album_title` a LEFT JOIN photo_gallery p ON p.album_id = a.album_id AND p.IsAlbumCover = 'Y' WHERE a.status = 'A' ORDER BY a.album_id DESC"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	public function get_all_phone_no() 
	{ 	 
		$sql = $this->db->query("SELECT c.name AS name, c.tel_no_with_ext AS phone FROM `company_telephone_directory` c INNER JOIN `internal_user` i ON c.id = i.company_telephone_id WHERE c.`n_disp_flag` = '1'
		   UNION
		   SELECT CONCAT(i.name_first, ' ', i.name_last) AS name, i.mobile  FROM `internal_user` i WHERE i.user_status = '1' AND i.isShowOnSearch = 'Y'
		   ORDER BY name"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	public function get_cricket_team($choose_year) 
	{ 	 
		$sql = $this->db->query("SELECT c.id, c.position, i.loginhandle, i.full_name, i.user_photo_name FROM `cricket_team` c INNER JOIN `internal_user` i ON i.login_id = c.user_id WHERE c.year = $choose_year AND c.n_disp_flag = 1 ORDER BY n_order"); 
		$result = $sql->result_array(); 
		return $result; 
	}
}
