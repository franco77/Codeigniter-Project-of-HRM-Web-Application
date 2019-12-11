<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Expenses_reimbrusement_model extends CI_Model {

	/**
	 * __construct function. 
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	} 
	public function get_reimbrusement() 
	{
		$sql = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name, i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' ORDER BY login_id DESC";
		$qry_result = $this->db->query($sql);
		$result = $qry_result->result_array();
		return $result; 
	} 
	public function get_reimbrusement_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
	{
		$cond = " i.login_id != '10010'";
		
		if($searchDepartment !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."department = '".$searchDepartment."'";
		}
		if($searchName !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."full_name LIKE '%".$searchName."%'";
		}
		if($searchDesignation  !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."loginhandle = '".$searchEmpCode."'";
		}
		
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name, i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE $cond ORDER BY login_id DESC");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_emp_gratuity() 
	{
		$encypt = $this->config->item('masterKey');
		$sql = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.join_date, i.father_name, i.dob, AES_DECRYPT(s.gross_salary, '".$encypt."') AS gross_salary,AES_DECRYPT(s.basic, '".$encypt."') AS basic,s.calculation_type FROM `internal_user` i LEFT JOIN `salary_info` s ON s.login_id = i.login_id WHERE i.login_id != '10010' AND user_status='1'";
		$qry_result = $this->db->query($sql);
		$result = $qry_result->result_array();
		 
		$results = array();
		$date = date('Y-m-d');
		for($i=0; $i<count($result); $i++){ 
			$date1 = strtotime($result[$i]['join_date']);
			$date2 = strtotime(date('Y-m-d'));
			$months = 0;
			while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2){
				$months++;
			} 
			if($months >= 60){
				$gross= $result[$i]['gross_salary'];
				if($result[$i]['calculation_type'] == 'A'){ //For Automatic Salary Calculation
					$basic_percent = $result[$i]['basic'];
					$basic=round($gross*($basic_percent/100));   
				}
				else{
					$basic = $result[$i]['basic'];                                                    
				}
				$gratuity = ceil(($basic/26)*15*($months/12)); 
				$data = array(
					'login_id' => $result[$i]['login_id'],
					'email' => $result[$i]['email'],
					'name' => $result[$i]['name'],
					'loginhandle' => $result[$i]['loginhandle'],
					'join_date' => $result[$i]['join_date'],
					'father_name' => $result[$i]['father_name'],
					'dob' => $result[$i]['dob'],
					'gross_salary' => $result[$i]['gross_salary'],
					'basic' => $result[$i]['basic'],
					'calculation_type' => $result[$i]['calculation_type'],
					'months' => $months,
					'years' => number_format((float)($months/12), 2, '.', ''),
					'gratuity' => $gratuity
				);
				array_push($results, $data);
			}
		} 
		return $results; 
	} 
	 
	/**
	 * get_user function. 
	*/
	public function get_emp_bonus() 
	{
		$yy = date("Y");
		$s_year=$yy;
        $e_year=$yy+1;
		$cond = " AND ((salary_month >= 4 AND salary_year=$s_year) OR (salary_month <= 3 AND salary_year=$e_year))";
		$eDate = $e_year.'-03-01';
		$sDate = $e_year.'-03-31';
		$encypt = $this->config->item('masterKey');
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, CONCAT(name_first, ' ', name_last) AS name, i.designation, i.join_date,i.user_status, m.*, u.* 
		FROM `internal_user` i 
		RIGHT JOIN `midyear_review` m ON m.login_id=i.login_id 
		LEFT JOIN `user_desg` u ON u.desg_id=i.designation 
		WHERE m.login_id != '10010' AND join_date <= '$eDate' AND user_status=2 GROUP BY i.login_id ORDER BY i.login_id "); 
		$result = $sql->result_array(); 
		
		$sqls = $this->db->query("Select miscellaneous_value from miscellaneous_mater where miscellaneous_id='1'"); 
		$res = $sqls->result_array(); 
		$bonus_basic =$res[0]['miscellaneous_value'];
		$total_bonus =$monthly_bonus =0;
		$results = array(); 
		for($i =0; $i<count($result); $i++){
			
			$sqlss = $this->db->query("SELECT AES_DECRYPT(basic, '".$encypt."') AS basic, working_days, paid_days from `salary_sheet` WHERE login_id = '".$result[$i]['login_id']."' $cond"); 
			$ress = $sqlss->result_array(); 
			for($j=0; $j<count($ress); $j++){
				$basic = $ress[$j]['basic'];    
				if($basic <= 21000){
					if($basic < $bonus_basic){
						$bonus_basic= $bonus_basic;
					}
					else{
						$bonus_basic= $bonus_basic;
					}
				}else{
					$bonus_basic =0;
				}
				$monthly_bonus=($bonus_basic/ $ress[$j]['working_days'])*$ress[$j]['paid_days'];
				$total_bonus = $total_bonus + $monthly_bonus;
			}
			if($total_bonus > 84000)
			{
				$bonus = $bonus_basic;
			}
			else
			{
				$bonus=ceil($total_bonus*(8.33/100));
			}
			
			$data = array(
				'login_id' => $result[$i]['login_id'],
				'loginhandle' => $result[$i]['loginhandle'],
				'name' => $result[$i]['name'],
				'designation' => $result[$i]['designation'],
				'join_date' => $result[$i]['join_date'],
				'user_status' => $result[$i]['user_status'],
				'bonus' => $bonus
			);
			array_push($results,$data);
		} 
		return $results; 
	} 	
	
	public function get_emp_bonus_search($searchYear, $searchName)
	{
		//echo $searchYear.' '.$searchName;
		$yy = date("Y");
		$s_year=$yy;
        $e_year=$yy+1;
		if($searchYear !=""){
			$d_year=explode('-',$searchYear);  
			$s_year=$d_year[0];
			$e_year=$d_year[1];
		}
		$cond = " AND ((salary_month >= 4 AND salary_year=$s_year) OR (salary_month <= 3 AND salary_year=$e_year))";
		$eDate = $e_year.'-03-01';
		$sDate = $e_year.'-03-31';
		$encypt = $this->config->item('masterKey');
		if($searchName !=""){
			$sql = $this->db->query("SELECT i.login_id, i.loginhandle, CONCAT(name_first, ' ', name_last) AS name, i.designation, i.join_date,i.user_status
				FROM `internal_user` i 
				RIGHT JOIN `midyear_review` m ON m.login_id=i.login_id 
				LEFT JOIN `user_desg` u ON u.desg_id=i.designation 
				WHERE i.login_id != '10010' AND i.join_date <= '$eDate' AND i.user_status=2 AND i.full_name LIKE '%".$searchName."%' GROUP BY i.login_id ORDER BY i.login_id"); 
			$result = $sql->result_array(); 
		}
		else{
			$sql = $this->db->query("SELECT i.login_id, i.loginhandle, CONCAT(name_first, ' ', name_last) AS name, i.designation, i.join_date,i.user_status
			FROM `internal_user` i 
			RIGHT JOIN `midyear_review` m ON m.login_id=i.login_id 
			LEFT JOIN `user_desg` u ON u.desg_id=i.designation 
			WHERE i.login_id != '10010' AND i.join_date <= '$eDate' AND i.user_status=2 GROUP BY i.login_id ORDER BY i.login_id"); 
			$result = $sql->result_array(); 
		}
		
		$sqls = $this->db->query("Select miscellaneous_value from miscellaneous_mater where miscellaneous_id='1'"); 
		$res = $sqls->result_array(); 
		$bonus_basic =$res[0]['miscellaneous_value'];
		$total_bonus =$monthly_bonus =0;
		$results = array(); 
		for($i =0; $i<count($result); $i++){
			
			$sqlss = $this->db->query("SELECT AES_DECRYPT(basic, '".$encypt."') AS basic, working_days, paid_days from `salary_sheet` WHERE login_id = '".$result[$i]['login_id']."' $cond"); 
			$ress = $sqlss->result_array(); 
			for($j=0; $j<count($ress); $j++){
				$basic = $ress[$j]['basic'];    
				if($basic <= 21000){
					if($basic < $bonus_basic){
						$bonus_basic= $bonus_basic;
					}
					else{
						$bonus_basic= $bonus_basic;
					}
				}else{
					$bonus_basic =0;
				}
				$monthly_bonus=($bonus_basic/ $ress[$j]['working_days'])*$ress[$j]['paid_days'];
				$total_bonus = $total_bonus + $monthly_bonus;
			}
			if($total_bonus > 84000)
			{
				$bonus = $bonus_basic;
			}
			else
			{
				$bonus=ceil($total_bonus*(8.33/100));
			}
			//echo $total_bonus;
			//print_r($ress);exit;
			$data = array(
				'login_id' => $result[$i]['login_id'],
				'loginhandle' => $result[$i]['loginhandle'],
				'name' => $result[$i]['name'],
				'designation' => $result[$i]['designation'],
				'join_date' => $result[$i]['join_date'],
				'user_status' => $result[$i]['user_status'],
				'bonus' => $bonus
			);
			array_push($results,$data);
		} 
		return $results; 
	} 	
}
