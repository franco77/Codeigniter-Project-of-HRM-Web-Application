<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
class Admin_settings_model extends CI_Model { 
	public function __construct() {
		
		parent::__construct();
		$this->load->database(); 
	}
	
	public function get_all_active_employee(){
		$empSQL = $this->db->query("SELECT login_id,full_name From internal_user WHERE login_id != 10010 AND user_type = 'EMP' AND user_status = 1");
		$empSQLRes = $empSQL->result_array();
		return $empSQLRes;
	}
	
	public function get_all_employee(){
		$empSQL = $this->db->query("SELECT login_id,loginhandle,full_name From internal_user WHERE login_id != 10010 AND user_type = 'EMP' AND user_status = '1' ");
		$empSQLRes = $empSQL->result_array();
		return $empSQLRes;
	}
	
	public function submit_fall_of_fame($empID,$title,$description,$login_id,$image,$month,$year,$order){
		$this->db->query("INSERT INTO hall_of_fame(user_id,title,description,create_date,image,month,year,h_order,login_id) VALUES('".$empID."','".$title."','".$description."','".date('Y-m-d')."','".$image."','".$month."','".$year."','".$order."','".$login_id."')");
	}
	
	public function get_all_hall_of_fame($last_month,$last_year){
		$fameSQL = $this->db->query('SELECT hall_of_fame.id,internal_user.full_name,internal_user.loginhandle,title,description,month,year,status FROM hall_of_fame INNER JOIN internal_user ON hall_of_fame.user_id = internal_user.login_id WHERE month = '.$last_month.' AND year = '.$last_year.' ORDER by hall_of_fame.h_order ASC ');
		$fameRes = $fameSQL->result_array();
		return $fameRes;
	}
	
	public function get_all_hall_of_fame_id($id){
		$fameSQL = $this->db->query('SELECT hall_of_fame.id,internal_user.full_name,internal_user.loginhandle,title,description,month,year,status,image,hall_of_fame.user_id,h_order FROM hall_of_fame INNER JOIN internal_user ON hall_of_fame.user_id = internal_user.login_id WHERE id ='.$id);
		$fameRes = $fameSQL->result_array();
		return $fameRes;
	}
	
	public function update_fall_of_fame_img($filename,$id){
		$this->db->query('UPDATE `hall_of_fame` SET `image`="'.$filename.'" WHERE `id` = '.$id );
	}
	
	public function update_fall_of_fame($empID,$title,$description,$login_id,$month,$year,$order,$id){
		$this->db->query('UPDATE `hall_of_fame` SET `user_id`='.$empID.',`title`="'.$title.'",`description`="'.$description.'",`login_id`= '.$login_id.',`month`='.$month.',`year`= '.$year.',`h_order`= '.$order.' WHERE `id` = '.$id );
	}
	
	public function get_all_hall_of_fame_search($year,$month){
		if($year != "" && $month != ""){
			$cond = "WHERE month = '$month' AND year = '$year'";
		} else if($year != ""){
			$cond = "WHERE year = '$year'";
		} else if($month != ""){
			$cond = "WHERE month = '$month'";
		} else if($year == "" && $month == ""){
			$cond = "";
		}
		$fameSQL = $this->db->query('SELECT hall_of_fame.id,internal_user.full_name,internal_user.loginhandle,title,description,month,year,status FROM hall_of_fame INNER JOIN internal_user ON hall_of_fame.user_id = internal_user.login_id '.$cond);
		$fameRes = $fameSQL->result_array();
		return $fameRes;
	}
}	

