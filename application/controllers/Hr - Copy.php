<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Hr extends MY_Controller 
{ 
	var $data = array('visit_type' => '','pageTitle' => '','file' =>''); 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Hr_model'); 
	}
	//Start employee manament
	
	public function profile_list()
	{
		if($this->input->post_get('dd_dept') !='')
		{
			$cond ="AND dept_id='".$this->input->post_get('dd_dept')."'";
		}
		else
		{
			$cond ="";
		} 
		$this->mViewData['dept'] = $this->Hr_model->get_department(); 
		//var_dump($mysql_dept);exit;
		$this->mViewData['desg'] = $this->Hr_model->get_desg($cond);
		//var_dump($mysql_desg);exit;
		//$this->mViewData['db_listbox'] = $this->Hr_model->db_listbox($mysql_dept,$mysql_dept,'');
		$this->mViewData['activemp'] = $this->Hr_model->get_active_employee();
		$mysql_qry = $this->Hr_model->get_active_employee();
		//var_dump($this->mViewData['activemp']);exit;
		if($this->input->post('searchEmployee') == 'Find')
		{
			if($this->input->post('dd_dept') != '') $mysql_qry .= " AND i.department = '".$this->input->post('dd_dept')."' ";
			if($this->input->post('dd_desg') != '') $mysql_qry .= " AND i.designation = '".$this->input->post('dd_desg')."' ";
			if($this->input->post('name') <> "") $mysql_qry .= " AND  (i.full_name like '%".$this->input->post('name')."%') ";
			if($this->input->post('emp_code') <> "") $mysql_qry .= " AND  i.loginhandle = '".$this->input->post('emp_code')."' ";
		}
		elseif($this->input->get('page') !='')
		{
			if($this->input->post_get('dd_dept') != '') $mysql_qry .= " AND i.department = '".$this->input->post_get('dd_dept')."' ";
			if($this->input->post_get('dd_desg') != '') $mysql_qry .= " AND i.designation = '".$this->input->post_get('dd_desg')."' ";
			if($this->input->post_get('name') <> "") $mysql_qry .= " AND  (i.full_name like '%".$this->input->post_get('name')."%') ";
			if($this->input->post_get('emp_code') <> "") $mysql_qry .= " AND  i.loginhandle = '".$this->input->post_get('emp_code')."' ";
		}
		$this->render('hr/employee_management/profile_list_view', 'full_width',$this->mViewData);
	}
	public function inactive_profile_list()
	{
		$this->mViewData['dept'] = $this->Hr_model->get_department(); 
		//var_dump($mysql_dept);exit;
		if($this->input->post_get('dd_dept') !='')
		{
			$cond ="AND dept_id='".$this->input->post_get('dd_dept')."'";
		}
		else
		{
			$cond ="";
		} 
		$this->mViewData['desg'] = $this->Hr_model->get_desg($cond);
		$this->mViewData['inactiveEmp'] = $this->Hr_model->get_inactive_employee();
		$inactive_qry = $this->Hr_model->get_active_employee();
		if($this->input->post('searchEmployee') == 'Find')
		{
			if($this->input->post('dd_dept') != '') $inactive_qry .= " AND i.department = '".$this->input->post('dd_dept')."' ";
			if($this->input->post('dd_desg') != '') $inactive_qry .= " AND i.designation = '".$this->input->post('dd_desg')."' ";
			if($this->input->post('name') <> "") $inactive_qry .= " AND  (i.full_name like '%".$this->input->post('name')."%') ";
			if($this->input->post('emp_code') <> "") $inactive_qry .= " AND  i.loginhandle = '".$this->input->post('emp_code')."' ";
		}
		elseif($this->input->get('page') !='')
		{
			if($this->input->post_get('dd_dept') != '') $inactive_qry .= " AND i.department = '".$this->input->post_get('dd_dept')."' ";
			if($this->input->post_get('dd_desg') != '') $inactive_qry .= " AND i.designation = '".$this->input->post_get('dd_desg')."' ";
			if($this->input->post_get('name') <> "") $inactive_qry .= " AND  (i.full_name like '%".$this->input->post_get('name')."%') ";
			if($this->input->post_get('emp_code') <> "") $inactive_qry .= " AND  i.loginhandle = '".$this->input->post_get('emp_code')."' ";
		}
		$this->render('hr/employee_management/inactive_profile_list_view', 'full_width',$this->mViewData);
	}
	public function  add_employee()
	{
		//define model
		$this->mViewData['country'] = $this->Hr_model->get_country();
		$this->mViewData['state'] = $this->Hr_model->get_state();
		$this->mViewData['grade'] = $this->Hr_model->get_grade();
		$this->mViewData['level'] = $this->Hr_model->get_level();
		$this->mViewData['qualification'] = $this->Hr_model->get_qualification();
		$this->mViewData['sourcehire'] = $this->Hr_model->get_source_of_hire();
		$this->mViewData['location'] = $this->Hr_model->get_company_location_branch(); 
		$this->mViewData['department'] = $this->Hr_model->get_department();  
		$this->mViewData['designation'] = $this->Hr_model->get_designation_status_one();
		
		//end model
		if($this->input->post('btnCreateNewEmployeeCode') == 'Create')
		{
			$doj = date("Y-m-d", strtotime($this->input->post('txtdoj')));
			$this->mViewData['employeeID'] = $this->generateEmployeeCode($doj, $this->input->post('ddlTypeEmp'));
		}

		if($this->input->post('btnCreateNewEmployee') == 'Yes')
		{
			$doj = date("Y-m-d", strtotime($this->input->post('txtdoj')));

			$dConfOrCeDateMonth = "";	
			$dConfOrCeDate = "";
			if($this->input->post('ddlTypeEmp') == 'F')
			{
				$dConfOrCeDateMonth = $this->input->post('due_conf');	
				$dConfOrCeDate = strtotime ( '+'.$dConfOrCeDateMonth.' month' , strtotime ( $doj ) ) ;
			}
			else
			{
				$dConfOrCeDateMonth = $this->input->post('contract_EndDate');	
				$dConfOrCeDate = strtotime ( '+'.$dConfOrCeDateMonth.' month' , strtotime ( $doj ) ) ;
			}
			$dConfOrCeDate = date("Y-m-d", $dConfOrCeDate);

			$employeeID = $this->generateEmployeeCode($doj, $this->input->post('ddlTypeEmp'));

			$dob = date("Y-m-d", strtotime($this->input->post('txtdob')));
			$dobWithCY = date("m-d", strtotime($dob));

			// Get Last Salary Sheet ID
			$salSheetIDSql = "SELECT `sal_sheet_sl_no` FROM `internal_user` WHERE `emp_type` = '".$this->input->post('ddlTypeEmp')."' ORDER BY `sal_sheet_sl_no` DESC LIMIT 1";
			$salSheetIDRes = $this->db->query($salSheetIDSql);
			$salSheetIDInfo = $salSheetIDRes->result_array();
			$newSalSheetID = $salSheetIDInfo['sal_sheet_sl_no'] + 1;

			$i=0;
			do
			{
				$nameAbbr = substr($this->input->post('txtFirstName'),0,1) . substr($this->input->post('txtFirstName'),$i,1). substr($this->input->post('txtLastName'),0,1);//nameAbbr should be 3 chars
				$nameAbbr = strtoupper($nameAbbr);
				$i++;
			}
			while($this->checkUniqueNameAbbr($nameAbbr));

			// Insert Into internal_user
			$insIUQry="INSERT INTO `internal_user` (`per_email`,`loginhandle`,`name_first`,`name_middle`,`name_last`,
				`full_name`,`name_abbr`,`gender`,`marital_status`,`join_date`,`address1`,
				`city_district1`,`state_region1`,`pin_zip1`,`country1`,`phone1`,
				`mobile`,`branch`,`designation`,`department`,`grade`,`level`,`dob`,
				`highest_qual`,`loc_highest_qual`,`dob_with_current_year`,`blood_group`,`reporting_to`,
				`source_hire`,`isAttndAllowance`,`isPerfomAllowance`,`sal_sheet_sl_no`,`due_conform`,`employee_conform`,`emp_type`,`leaveCreditedDate`)
			VALUES
				('".$this->input->post('txtPEmailID')."', '".$employeeID."', '".$this->input->post('txtFirstName')."', '".$this->input->post('txtMiddleName')."', '".$this->input->post('txtLastName')."',
				'".$this->input->post('txtFullName')."','".$nameAbbr."', '".$this->input->post('rdGender')."', '".$this->input->post('rdMStatus')."', '".$doj."', '".$this->input->post('perAddr')."',
				'".$this->input->post('perDist')."', '".$this->input->post('perState')."', '".$this->input->post('perPin')."', '".$this->input->post('perCountry')."', '".$this->input->post('txtEContNo')."', 
				'".$this->input->post('txtContNo')."', '".$this->input->post('branch')."', '".$this->input->post('designation')."', '".$this->input->post('department')."', '".$this->input->post('grade')."', '".$this->input->post('level')."', '".$dob."', 
				'".$this->input->post('selHgstEdu')."', '".$this->input->post('perLoc')."', '".$dobWithCY."', '".$this->input->post('txtBGroup')."', '".$this->input->post('reporting')."',
				'".$this->input->post('ddlSrcHire')."','".$this->input->post('attndEligb')."', '".$this->input->post('perofmEligb')."', '".$newSalSheetID."' ,'".$dConfOrCeDateMonth."', '".$dConfOrCeDate."', '".$this->input->post('ddlTypeEmp')."', '".$doj."' )";
			$this->db->query($insIUQry);
			$newLoginID = $this->db->insert_id();;

			// Insert Into compass_user
			$insCUQry = "INSERT INTO `compass_user` (`login_id`, `email`, `name`, `name_abbr`, `ref_id`)
				VALUES
				('".$employeeID."', '".$this->input->post('txtPEmailID')."', '".$this->input->post('txtFullName')."', '".$nameAbbr."', '".$newLoginID."')";
			$this->db->query($insCUQry);
			if($this->input->post('ddlTypeEmp') == 'F'){          
			$insLeaves = "INSERT INTO `leave_carry_ forward` (user_id, year, ob_pl, ob_sl) values ('".$newLoginID."', '".date("Y")."', 0, '1')";
			$this->db->query($insLeaves);
			}
			employeeCreated($this->session->userdata('user_id'), $newLoginID);

			header("location:profile_readonly_emp.php?id=".$newLoginID);
			exit();
		}

		/*if($perCountry == '')
		{
			$perCountry = 99;
		}
		if($perState == '')
		{
			$perState = 20;
		}

		$seldept = 6;
		if($_REQUEST["department"] > 0)
		{
			$seldept = $_REQUEST["department"];
		}*/
		
		
		$this->render('hr/employee_management/add_employee_view', 'full_width',$this->mViewData);
	}
	function generateEmployeeCode($joiningDate,$ddlEmpType)
	{
		// Calculate Financial Year
		$joiningMonth = date("m", strtotime($joiningDate));
		$joiningYear = date("Y", strtotime($joiningDate));
		$joiningyear = date("y", strtotime($joiningDate));

		if($joiningDate < '2012-03-31'){
			$startYear = '2011-08-03';
			$endYear = '2012-03-31';
			$yearCode = '1112';
			
		}
		else
		{
			if($joiningMonth > 3)
			{
				$startYear = $joiningYear.'-04-01';
				$endYear = ($joiningYear + 1).'-03-31';
				
				$plusJoiningyear = $joiningyear + 1;
				$plusJoiningyear = ($plusJoiningyear < 10)?'0'.$plusJoiningyear:$plusJoiningyear;
				$yearCode = $joiningyear.$plusJoiningyear;
			}
			else
			{
				$startYear = ($joiningYear - 1).'-04-01';
				$endYear = $joiningYear.'-03-31';
				
				$minusJoiningyear = $joiningyear - 1;
				$minusJoiningyear = ($minusJoiningyear < 10)?'0'.$minusJoiningyear:$minusJoiningyear;
				$yearCode = $minusJoiningyear.$joiningyear;
			}
		}

		$empQuery = "SELECT `login_id` FROM `internal_user` WHERE `join_date` BETWEEN '$startYear' AND '$endYear' AND `emp_type` = '$ddlEmpType'";
		$empRes = $this->db->query($empQuery);
		$empNum = $empRes->row();
		$newEmpNo = $empNum + 1;

		if($newEmpNo < 10)
		{
			$newEmpNo = '0'.$newEmpNo;
		}

		if($ddlEmpType == 'F')
			$empCode = 'AITPL-'.$yearCode.$newEmpNo;
		elseif($ddlEmpType == 'C')
			$empCode = 'AITPL-C'.$yearCode.$newEmpNo;
			else
			$empCode = 'AITPL-I'.$yearCode.$newEmpNo;

		return $empCode;
	}

	/**
	* Check For Name Abbr
	*
	*
	* @param	string
	* @return	bool
	*/

	function checkUniqueNameAbbr($abbrText)
	{
		$nameQuery = "SELECT `login_id` FROM `internal_user` WHERE `name_abbr` = '$abbrText' LIMIT 1";
		$nameRes = $this->db->query($nameQuery);
		$nameNum = $nameRes->row();
		if($nameNum == 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	//pending work
	public function reset_emp_pwd()
	{
		if($this->input->post('btnResetEmpPwd') == 'Reset')
		{
			$upPwdQry = "UPDATE `internal_user` SET `password` = 'a63e4320272e0a129ff45dcc97519ec6' WHERE `login_id` = '".$this->input->post('empLoginID')."'";
			$this->input->post($upPwdQry);
			$showPwdReset = TRUE;

			// Mail to Employee
			$this->resetPasswordSuccess($this->input->post('empName'), $this->input->post('empEmail'));

			//$_SESSION['showPwdReset'] = TRUE;
			//$_SESSION['pwdResetEmpName'] = $this->input->post('empName');

			header("Location:reset_emp_pwd.php");
			exit();
		}
		$this->render('hr/employee_management/reset_emp_pwd_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/reset_password_script');
	}
	public function show_emp_name()
	{
		$result = $this->Hr_model->show_emp_name(); 
		$this->json_response($result);
	}
	public function emp_vintage_list()
	{
		$this->mViewData['dd_vintage_type'] = $this->input->post('dd_vintage_type');
		$this->mViewData['vType'] = '3';
		if($this->input->post('searchVintageEmployee') == 'Find')
		{
			$this->mViewData['vType'] = $this->input->post('dd_vintage_type');
		} 
		$toDay = date("Y-m-d");
		if($this->mViewData['vType'] == '3')
		{
			$fromDate = date("Y-m-d", strtotime("-90 day"));
			$toDate = date("Y-m-d", strtotime("-70 day"));
		}
		elseif($this->mViewData['vType'] == '6')
		{
			$fromDate = date("Y-m-d", strtotime("-190 day"));
			$toDate = date("Y-m-d", strtotime("-170 day"));
		}
		elseif($this->mViewData['vType'] == '12')
		{
			$fromDate = date("Y-m-d", strtotime("-1 year -10 days"));
			$toDate = date("Y-m-d", strtotime("-1 year +10 days"));
		}
		elseif($this->mViewData['vType'] == '24')
		{
			$fromDate = date("Y-m-d", strtotime("-2 year -10 days"));
			$toDate = date("Y-m-d", strtotime("-2 year +10 days"));
		}
		elseif($this->mViewData['vType'] == '36')
		{
			$fromDate = date("Y-m-d", strtotime("-3 year -10 days"));
			$toDate = date("Y-m-d", strtotime("-3 year +10 days"));
		}
		elseif($this->mViewData['vType'] == '60')
		{
			$fromDate = date("Y-m-d", strtotime("-5 year -10 days"));
			$toDate = date("Y-m-d", strtotime("-5 year +10 days"));
		}
		else
		{
			$fromDate = $toDay;
			$toDate = date("Y-m-d", strtotime(" +20 days"));
		} 
		// Get Active Employees
		if($this->mViewData['vType'] == 'C')
		{
			$this->mViewData['num_rows'] = $this->Hr_model->get_emp_vintage_c($fromDate,$toDate);
		}
		else
		{
			$this->mViewData['num_rows'] = $this->Hr_model->get_emp_vintage($fromDate,$toDate);
		}  
		$this->render('hr/employee_management/emp_vintage_list_view', 'full_width',$this->mViewData);
	}
	public function emp_details_import()
	{
		$this->render('hr/employee_management/emp_details_import_view', 'full_width',$this->mViewData);
	}
	public function emp_report()
	{
		//define model 
		$this->mViewData['state'] = $this->Hr_model->get_state();
		$this->mViewData['grade'] = $this->Hr_model->get_grade();
		$this->mViewData['level'] = $this->Hr_model->get_level();
		$this->mViewData['qualification'] = $this->Hr_model->get_qualification();
		$this->mViewData['sourcehire'] = $this->Hr_model->get_source_of_hire();
		$this->mViewData['location'] = $this->Hr_model->get_company_location_branch(); 
		$this->mViewData['department'] = $this->Hr_model->get_department();  
		$this->mViewData['designation'] = $this->Hr_model->get_designation_status_one();
		$this->mViewData['reporting'] = $this->Hr_model->reporting();
		$this->mViewData['reviewing'] = $this->Hr_model->reviewing();
		$this->mViewData['bank'] = $this->Hr_model->bank();
		$this->mViewData['graduation'] = $this->Hr_model->graduation_level_courses();
		$this->mViewData['specialization_grade'] = $this->Hr_model->specialization_grade();
		$this->mViewData['specialization_professional'] = $this->Hr_model->specialization_professional();
		$this->mViewData['board_university'] = $this->Hr_model->board_university();
		$this->mViewData['professional_qualification'] = $this->Hr_model->professional_qualification();
		$this->mViewData['specialization_professional'] = $this->Hr_model->specialization_professional();
		$this->mViewData['separation'] = $this->Hr_model->get_separation_master();
		$this->mViewData['hod'] = $this->Hr_model->hod();
		$this->mViewData['location_of_highest_qualification'] = $this->Hr_model->location_of_highest_qualification();
		//end model
		if($this->input->post('exportEmployee') == "Generate")
		{
			$encypt = 'HHGSH362sgHHG';
			$this->load->library('PHPExcel');
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel(); 
			// Set document properties
			$objPHPExcel->getProperties()->setCreator("AABSyS IT Pvt Ltd")
				->setLastModifiedBy("Rangaballava swain")
				->setTitle("Online HR Master")
				->setSubject("Online HR Master")
				->setDescription("Online HR Master.")
				->setKeywords("Online HR Master")
				->setCategory("Online HR Master Export");

			//setting the values of the headers and data of the excel file 
			$header = array();
			$header = array("Sl No");
			$noOfColumnsSelected = 0;
			$selCols = "";
			$cond = "";
			$loginhandle = $this->input->post('loginhandle');
			if(isset($loginhandle)){
				array_push($header,"Employee Code");
				$selCols .= ", i.loginhandle";
				$noOfColumnsSelected++;
			}
			$full_name = $this->input->post('full_name');
			if(isset($full_name)){
				array_push($header, "Name");
				$selCols .= ", i.full_name";
				$noOfColumnsSelected++;
			}
			$father_name = $this->input->post('father_name');  
			if(isset($father_name)){
				array_push($header, "Fathers Name");
				$selCols .= ", f.fathers_name";
				$noOfColumnsSelected++;
			}
			$mother_name = $this->input->post('mother_name');
			if(isset($mother_name)){
				array_push($header, "Mothers Name");
				$selCols .= ", f.mother_name";
				$noOfColumnsSelected++;
			}
			$gender = $this->input->post('gender');
			if(isset($gender)){
				array_push($header,"Gender");
				$selCols .= ", i.gender";
				$noOfColumnsSelected++;
				if($this->input->post("selGender") != ""){
					$cond .= " AND i.gender = '" . $this->input->post("selGender") . "'";
				}
			}
			$dob = $this->input->post('dob');
			if(isset($dob)){
				array_push($header, "Emp D.O.B");
				$selCols .= ", i.dob";
				$noOfColumnsSelected++;
				if($this->input->post("dobFrom") != ""){
					$cond .= " AND i.dob >= '" . date("Y-m-d", strtotime($this->input->post("dobFrom"))) . "'";
				}
				if($this->input->post("dobTo") != ""){
					$cond .= " AND i.dob <= '" .date("Y-m-d", strtotime($this->input->post("dobTo"))) . "'";
				}
			}
			$doj = $this->input->post('doj');
			if(isset($doj)){
				array_push($header,"DOJ");
				$selCols .= ", i.join_date";
				$noOfColumnsSelected++;
				if($this->input->post("dojFrom") != ""){
					$cond .= " AND i.join_date >= '" . date("Y-m-d", strtotime($this->input->post("dojFrom"))) . "'";
				}
				if($this->input->post("dojTo") != ""){
					$cond .= " AND i.join_date <= '" .date("Y-m-d", strtotime($this->input->post("dojTo"))) . "'";
				}
			}
			$doc = $this->input->post('doc');
			if(isset($doc)){
				array_push($header,"DOC");
				$selCols .= ", i.employee_conform";
				$noOfColumnsSelected++;
				if($this->input->post("docFrom") != ""){
					$cond .= " AND i.employee_conform >= '" . date("Y-m-d", strtotime($this->input->post("docFrom"))) . "'";
				}
				if($this->input->post("docTo") != ""){
					$cond .= " AND i.employee_conform <= '" . date("Y-m-d", strtotime($this->input->post("docTo"))) . "'";
				}
			}
			$grade = $this->input->post('grade');
			if(isset($grade)){
				array_push($header,"Grade");
				$selCols .= ", g.grade_name";
				$noOfColumnsSelected++;
				if($this->input->post("hdnGrade") != ""){
					$cond .= " AND i.grade IN (" . $this->input->post("hdnGrade") . ")";
				}
			}
			$level = $this->input->post('level');
			if(isset($level)){
				array_push($header,"Level");
				$selCols .= ", l.level_name";
				$noOfColumnsSelected++;
				if($this->input->post("hdnLevel") != ""){
					$cond .= " AND i.level IN (" . $this->input->post("hdnLevel") . ")";
				}
			}
			$dept_name = $this->input->post('dept_name');
			if(isset($dept_name)){
				array_push($header,"Department");
				$selCols .= ", d.dept_name";
				$noOfColumnsSelected++;
				if($this->input->post("hdnDept") != ""){
					$cond .= " AND i.department IN (" . $this->input->post("hdnDept") . ")";
				}
			}
			$desg_name = $this->input->post('desg_name');
			if(isset($desg_name)){
				array_push($header, "Designation");
				$selCols .= ", u.desg_name, i.designation";
				$noOfColumnsSelected++;
				if($this->input->post("hdnDesg") != ""){
					$cond .= " AND i.designation IN (" . $this->input->post("hdnDesg") . ")";
				}
			}
			$loc = $this->input->post('loc');
			if(isset($loc)){
				array_push($header, "Location");
				$selCols .= ", b.branch_name";
				if($this->input->post("hdnLoc") != ""){
					$cond .= " AND i.branch IN (" . $this->input->post("hdnLoc") . ")";
				}
			}
			$reporting = $this->input->post('reporting');
			if(isset($reporting)){
				array_push($header,  "Reporting Officer");
				$selCols .= ", r.full_name AS reporting";
				$noOfColumnsSelected++;
				if($this->input->post("hdnReporting") != ""){
					$cond .= " AND i.reporting_to IN (" . $this->input->post("hdnReporting") . ")";
				}
			}
			$rev_officer = $this->input->post('rev_officer');
			if(isset($rev_officer)){
				array_push($header, "Reviewing Officer");
				$selCols .= ", rev.full_name AS reviewing";
				$noOfColumnsSelected++;
				if($this->input->post("hdnReviewing") != ""){
					$cond .= " AND rev.login_id IN (" . $this->input->post("hdnReviewing") . ")";
				}
			}
			$hod = $this->input->post('hod');
			if(isset($hod)){
				array_push($header,"HOD Name");
				$selCols .= ", h.full_name AS hod";
				$noOfColumnsSelected++;
				if($this->input->post("hdnHOD") != ""){
					$cond .= " AND d.dept_head IN (" . $this->input->post("hdnHOD") . ")";
				}
			}
			$exp_aabsys = $this->input->post('exp_aabsys');
			if(isset($exp_aabsys)){
				array_push($header, "Experience in Polosoft");
				$selCols .= ", i.join_date";
				$noOfColumnsSelected++;
			}
			$exp_others = $this->input->post('exp_others');
			if(isset($exp_others)){
				array_push($header, "Experience Prior to Polosoft");
				$selCols .= ", ie.exp_others";
				$noOfColumnsSelected++;
				if($this->input->post("expOFrom") != ""){
					$cond .= " AND ie.exp_others >= '" . $this->input->post("expOFrom") . "'";
				}
				if($this->input->post("expOTo") != ""){
					$cond .= " AND ie.exp_others <= '" . $this->input->post("expOTo") . "'";
				}
			}
			$exp_total = $this->input->post('exp_total');
			if(isset($exp_total)){
				array_push($header,  "Total Exp.");
				$noOfColumnsSelected++;
			}
			$age = $this->input->post('age');
			if(isset($age)){
				array_push($header,"Age");
				$selCols .= ", i.dob";
				$noOfColumnsSelected++;
			}
			$basic = $this->input->post('basic');
			if(isset($basic)){
				array_push($header,"Basic");
				$selCols .= ", AES_DECRYPT(sal.basic, '".$encypt."') AS basic";
				$noOfColumnsSelected++;
				if($this->input->post("basicFrom") != ""){
					$cond .= " AND AES_DECRYPT(sal.basic, '".$encypt."') >= " . $this->input->post("basicFrom");
				}
				if($this->input->post("basicTo") != ""){
					$cond .= " AND AES_DECRYPT(sal.basic, '".$encypt."') <= " . $this->input->post("basicTo");
				}
			}
			$hra = $this->input->post('hra');
			if(isset($hra)){
				array_push($header,"HRA");
				$selCols .= ", AES_DECRYPT(sal.hra, '".$encypt."') AS hra";
				$noOfColumnsSelected++;
			}
			$conv = $this->input->post('conv');
			if(isset($conv)){
				array_push($header,  "Conv.");
				$selCols .= ", AES_DECRYPT(sal.conveyance_allowance, '".$encypt."') AS conveyance_allowance";
				$noOfColumnsSelected++;
			}
			$gross_salary = $this->input->post('gross_salary');
			if(isset($gross_salary)){
				array_push($header, "Gross Salary");
				$selCols .= ", AES_DECRYPT(sal.gross_salary, '".$encypt."') AS gross_salary";
				$noOfColumnsSelected++;
				if($this->input->post("gSalFrom") != ""){
					$cond .= " AND AES_DECRYPT(sal.gross_salary, '".$encypt."') >= " . $this->input->post("gSalFrom");
				}
				if($this->input->post("gSalTo") != ""){
					$cond .= " AND AES_DECRYPT(sal.gross_salary, '".$encypt."') <= " . $this->input->post("gSalTo");
				}
			}
			$official_mobile = $this->input->post('official_mobile');
			if(isset($official_mobile)){
				array_push($header,"Official Mobile No");
				$selCols .= ", ie.official_mobile";
				$noOfColumnsSelected++;
			}
			$email = $this->input->post('email');
			if(isset($email)){
				array_push($header,  "Official Email Id"); 
				$selCols .= ", i.email";
				$noOfColumnsSelected++;
			}
			$prev_comp1 = $this->input->post('prev_comp1');
			if(isset($prev_comp1)){
				array_push($header, "Previous Company 1");
				$noOfColumnsSelected++;
			}
			$prev_deg1 = $this->input->post('prev_deg1');
			if(isset($prev_deg1)){
				array_push($header, "Previous Designation 1");
				$noOfColumnsSelected++;
			}
			$no_exp1 = $this->input->post('no_exp1');
			if(isset($no_exp1)){
				array_push($header, "No. of Years of Experience 1");
				$noOfColumnsSelected++;
			}
			$prev_comp2 = $this->input->post('prev_comp2');
			if(isset($prev_comp2)){
				array_push($header, "Previous Company 2");
				$noOfColumnsSelected++;
			}
			$prev_deg2 = $this->input->post('prev_deg2');
			if(isset($prev_deg2)){
				array_push($header, "Previous Designation 2");
				$noOfColumnsSelected++;
			}
			$no_exp2 = $this->input->post('no_exp2');
			if(isset($no_exp2)){
				array_push($header, "No. of Years of Experience 2");
				$noOfColumnsSelected++;
			}
			$prev_comp3 = $this->input->post('prev_comp3');
			if(isset($prev_comp3)){
				array_push($header, "Previous Company 3");
				$noOfColumnsSelected++;
			}
			$prev_deg3 = $this->input->post('prev_deg3');
			if(isset($prev_deg3)){
				array_push($header, "Previous Designation 3");
				$noOfColumnsSelected++;
			}
			$no_exp3 = $this->input->post('no_exp3');
			if(isset($no_exp3)){
				array_push($header, "No. of Years of Experience 3");
				$noOfColumnsSelected++;
			}
			$graduation = $this->input->post('graduation');
			if(isset($graduation)){
				array_push($header,"Graduation");
				$noOfColumnsSelected++;
			}
			$specializationGrad = $this->input->post('specializationGrad');
			if(isset($specializationGrad)){
				array_push($header, "Specialization(Grad)");
				$noOfColumnsSelected++;
			}
			$grad_passing_year = $this->input->post('grad_passing_year');
			if(isset($grad_passing_year)){
				array_push($header,"Year Of Passing(G)");
				$noOfColumnsSelected++;
			}
			$grad_percentage = $this->input->post('grad_percentage');
			if(isset($grad_percentage)){
				array_push($header, "%age(G)");
				$noOfColumnsSelected++;
			}
			$grad_board = $this->input->post('grad_board');
			if(isset($grad_board)){
				array_push($header,"Board/University(G)");
				$noOfColumnsSelected++;
			}
			$edu_catG = $this->input->post('edu_catG');
			if(isset($edu_catG)){
				array_push($header,"Category of Education(G)");
				$noOfColumnsSelected++;
			}
			$professional = $this->input->post('professional');
			if(isset($professional)){
				array_push($header,"Professional Qualification");
				$noOfColumnsSelected++;
			}
			$specializationProf = $this->input->post('specializationProf');
			if(isset($specializationProf)){
				array_push($header, "Specialization(Prof)");
				$noOfColumnsSelected++;
			}
			$prof_passing_year = $this->input->post('prof_passing_year');
			if(isset($prof_passing_year)){
				array_push($header,"Year Of Passing(P)");
				$noOfColumnsSelected++;
			}
			$prof_percentage = $this->input->post('prof_percentage');
			if(isset($prof_percentage)){
				array_push($header,  "%age(P)"); 
				$noOfColumnsSelected++;
			}
			$prof_board = $this->input->post('prof_board');
			if(isset($prof_board )){
				array_push($header, "Board/University(P)");
				$noOfColumnsSelected++;
			}
			$edu_catP = $this->input->post('edu_catP');
			if(isset($edu_catP)){
				array_push($header,"Category of Education(P)");
				$noOfColumnsSelected++;
			}
			$State = $this->input->post('State');
			if(isset($State)){
				array_push($header,"Native State");
				$selCols .= ", s.state_name as State";
				$noOfColumnsSelected++;
				if($this->input->post("hdnState") != ""){
					$cond .= " AND i.state_region1 IN (" . $this->input->post("hdnState") . ")";
				}
			}
			$spouse_name = $this->input->post('spouse_name');
			if(isset($spouse_name)){
				array_push($header,"Spouse Name");
				$selCols .= ", f.spouse_name";
				$noOfColumnsSelected++;
			}
			$spouse_dob = $this->input->post('spouse_dob');
			if(isset($spouse_dob)){
				array_push($header, "Spouse DOB");
				$selCols .= ", f.spouse_dob";
				$noOfColumnsSelected++;
			}
			$anniversary_date = $this->input->post('anniversary_date');
			if(isset($anniversary_date)){
				array_push($header,"Anniversary Date");
				$selCols .= ", f.anniversary_date";
				$noOfColumnsSelected++;
				if($this->input->post("hdnAnniversaryDate") != ""){
					$cond .= " AND DATE_FORMAT(f.anniversary_date, '%m') IN ('" . str_replace(",", "','", $this->input->post("hdnAnniversaryDate")) . "')";
				}
			}
			$child1 = $this->input->post('child1');
			if(isset($child1)){
				array_push($header,"Child1");
				$noOfColumnsSelected++;
			}
			$child_dob1 = $this->input->post('child_dob1');
			if(isset($child_dob1)){
				array_push($header, "Child1 DOB");
				$noOfColumnsSelected++;
			}
			$child2 = $this->input->post('child2');
			if(isset($child2)){
				array_push($header, "Child2");
				$noOfColumnsSelected++;
			}
			$child_dob2 = $this->input->post('child_dob2');
			if(isset($child_dob2)){
				array_push($header,"Child2 DOB");
				$noOfColumnsSelected++;
			}
			$child3 = $this->input->post('child3');
			if(isset($child3)){
				array_push($header,"Child3");
				$noOfColumnsSelected++;
			}
			$child_dob3 = $this->input->post('child_dob3');
			if(isset($child_dob3)){
				array_push($header,"Child3 DOB");
				$noOfColumnsSelected++;
			}
			$per_add = $this->input->post('per_add');
			if(isset($per_add)){
				array_push($header,  "Permanent Address");
				$selCols .= ", i.address1";
				$noOfColumnsSelected++;
			}
			$corr_add = $this->input->post('corr_add');
			if(isset($corr_add)){
				array_push($header, "Correspondence Address");
				$selCols .= ", i.address2";
				$noOfColumnsSelected++;
			}
			$phone1 = $this->input->post('phone1');
			if(isset($phone1)){
				array_push($header, "Contact No. Permanent Address (Landline)");
				$selCols .= ", i.phone1";
				$noOfColumnsSelected++;
			}
			$mobile1 = $this->input->post('mobile1');
			if(isset($mobile1)){
				array_push($header,"Contact No. Permanent  Address (Mobile)");
				$selCols .= ", i.mobile1";
				$noOfColumnsSelected++;
			}
			$phone2 = $this->input->post('phone2');
			if(isset($phone2)){
				array_push($header,"Contact No. Correspondence Address (Landline)");
				$selCols .= ", i.phone2";
				$noOfColumnsSelected++;
			}
			$mobile = $this->input->post('mobile');
			if(isset($mobile)){
				array_push($header,"Contact No. Correspondence Address (Mobile)"); 
				$selCols .= ", i.mobile";
				$noOfColumnsSelected++;
			}
			$per_email = $this->input->post('per_email');  
			if(isset($per_email)){
				array_push($header,"Personal EMail ID");
				$selCols .= ", i.per_email";
				$noOfColumnsSelected++;
			}
			$pan_card_no = $this->input->post('pan_card_no');
			if(isset($pan_card_no)){
				array_push($header,"PAN Card No.");
				$selCols .= ", i.pan_card_no";
				$noOfColumnsSelected++;
			}
			$drl_no = $this->input->post('drl_no');
			if(isset($drl_no)){
				array_push($header,"Driving License No.");
				$selCols .= ", i.drl_no";
				$noOfColumnsSelected++;
			}
			$voter_id = $this->input->post('voter_id');
			if(isset($voter_id)){
				array_push($header,"Voter ID Card No.");
				$selCols .= ", i.voter_id";
				$noOfColumnsSelected++;
			}
			$adharcard_no = $this->input->post('adharcard_no');
			if(isset($adharcard_no)){
				array_push($header, "Aadhar Card No.");
				$selCols .= ", ie.adharcard_no";
				$noOfColumnsSelected++;
			}
			$passport_no = $this->input->post('passport_no');
			if(isset($passport_no)){
				array_push($header,"Passport No.");
				$selCols .= ", i.passport_no";
				$noOfColumnsSelected++;
			}
			$mediclaim_no = $this->input->post('mediclaim_no');
			if(isset($mediclaim_no)){
				array_push($header, "Mediclaim/ESI No.");
				$selCols .= ", sal.mediclaim_no";
				$noOfColumnsSelected++;
			}
			$blood_group = $this->input->post('blood_group');
			if(isset($blood_group)){
				array_push($header, "Blood Group");
				$selCols .= ", i.blood_group";
				$noOfColumnsSelected++;
				if($this->input->post("hdnBGroup") != ""){
					$cond .= " AND i.blood_group IN ('" . str_replace(",", "','", $this->input->post("hdnBGroup")) . "')";
				}
			}
			$bank = $this->input->post('bank');
			if(isset($bank)){
				array_push($header, "Bank Name");
				$selCols .= ", ba.bank_name";
				$noOfColumnsSelected++;
				if($this->input->post("hdnBank") != ""){
					$cond .= " AND sal.bank IN (" . $this->input->post("hdnBank") . ")";
				}
			}
			$bank_no = $this->input->post('bank_no');
			if(isset($bank_no)){
				array_push($header,"Bank Account No.");
				$selCols .= ", sal.bank_no";
				$noOfColumnsSelected++;
			}
			$pf_no = $this->input->post('pf_no');
			if(isset($pf_no)){
				array_push($header,"PF No.");
				$selCols .= ", sal.pf_no";
				$noOfColumnsSelected++;
			}
			$offer_letter_issued = $this->input->post('offer_letter_issued');
			if(isset($offer_letter_issued)){
				array_push($header,"Offer Letter Issued Status");
				$selCols .= ", ie.offer_letter_issued";
				$noOfColumnsSelected++;
			}
			$appoint_letter_issued = $this->input->post('appoint_letter_issued');
			if(isset($appoint_letter_issued)){
				array_push($header,"Appointment Letter Issued Status");
				$selCols .= ", ie.appoint_letter_issued";
				$noOfColumnsSelected++;
			}
			$conf_letter_issued = $this->input->post('conf_letter_issued');
			if(isset($conf_letter_issued)){
				array_push($header,"Confirmation Letter Issued Status");
				$selCols .= ", ie.conf_letter_issued";
				$noOfColumnsSelected++;
			}
			$increment = $this->input->post('increment');
			 if(isset($increment)){
				 array_push($header,"Last Increment");
				$noOfColumnsSelected++;
			 }
			 $dop = $this->input->post('dop');
			 if(isset($dop)){
				 array_push($header, "Date of Promotion");
				 $selCols .= ", ie.last_promotion";
				 $noOfColumnsSelected++;
				 if($this->input->post("dopFrom") != ""){
					$cond .= " AND ie.last_promotion >= '" . date("Y-m-d", strtotime($this->input->post("dopFrom"))) . "'";
				 }
				 if($this->input->post("dopTo") != ""){
					$cond .= " AND ie.last_promotion <= '" .date("Y-m-d", strtotime($this->input->post("dopTo"))) . "'";
				 }
			 }
			 $miscunduct_issue = $this->input->post('miscunduct_issue');
			if(isset($miscunduct_issue)){
				array_push($header,"Misconduct/Integrity Issues Details");
				$selCols .= ", ie.miscunduct_issue";
				$noOfColumnsSelected++;
			}
			$DOR = $this->input->post('DOR');
			if(isset($DOR)){
				array_push($header,"Resignation Date");
				$selCols .= ", i.resign_date";
				$noOfColumnsSelected++;
				if($this->input->post("dorFrom") != ""){
					$cond .= " AND i.resign_date >= '" . date("Y-m-d", strtotime($this->input->post("dorFrom"))) . "'";
				 }
				 if($this->input->post("dorTo") != ""){
					$cond .= " AND i.resign_date <= '" .date("Y-m-d", strtotime($this->input->post("dorTo"))) . "'";
				 }
			}
			$LWD = $this->input->post('LWD');
			if(isset($LWD)){
				array_push($header,"Full & Final Date");
				$selCols .= ", i.lwd_date";
				$noOfColumnsSelected++;
			}
			$ff_date = $this->input->post('ff_date');
			if(isset($ff_date)){
				 array_push($header, "Date Of F&F");
				 $selCols .= ", ie.ff_date";
				$noOfColumnsSelected++;
			}
			$ff_amount = $this->input->post('ff_amount');
			if(isset($ff_amount)){
				array_push($header,"Amount Of F&F");
				$selCols .= ", ie.ff_amount";
				$noOfColumnsSelected++;
			}
			$ff_handed_date = $this->input->post('ff_handed_date');
			if(isset($ff_handed_date)){
				array_push($header,"F&F Amount Handed Over Date");
				$selCols .= ", ie.ff_handed_date";
				$noOfColumnsSelected++;
			}
			$hrRemark = $this->input->post('hrRemark');
			if(isset($hrRemark)){
				array_push($header,"Remarks");
				$selCols .= ", i.HR_remark";
				$noOfColumnsSelected++;
			}
			$source_hire = $this->input->post('source_hire');
			if(isset($source_hire)){
				array_push($header, "Source of Hire");
				$selCols .= ", sh.source_hire_name";
				$noOfColumnsSelected++;
				if($this->input->post("hdnHire") != ""){
					$cond .= " AND i.source_hire IN (" . $this->input->post("hdnHire") . ")";
				}
			}
			$marital_status = $this->input->post('marital_status');
			if(isset($marital_status)){
				array_push($header, "Marital Status");
				$selCols .= ", i.marital_status";
				$noOfColumnsSelected++;
				if($this->input->post("selMarital_status") != ""){
					$cond .= " AND i.marital_status = '" . $this->input->post("selMarital_status") . "'";
				 }
			}
			$highest_qual = $this->input->post('highest_qual');
			if(isset($highest_qual)){
				array_push($header,"Highest Qualification");
				$selCols .= ", i.highest_qual, hq.course_name";
				$noOfColumnsSelected++;
				if($this->input->post("hdnHQ") != ""){
					$cond .= " AND i.highest_qual IN (" . $this->input->post("hdnHQ") . ")";
				 }
			}
			$loc_highest_qualActual = $this->input->post('loc_highest_qualActual');
			if(isset($loc_highest_qualActual)){
				array_push($header,"Location of Highest Qualification");
				$selCols .= ", sl.state_name AS loc_highest_qualActual";
				$noOfColumnsSelected++;
				if($this->input->post("hdnLocHQ") != ""){
					$cond .= " AND i.loc_highest_qual IN (" . $this->input->post("hdnLocHQ") . ")";
				 }
			}
			$confirm_status = $this->input->post('confirm_status');
			if(isset($confirm_status)){
				array_push($header,"Confirmation Status");
				$selCols .= ", i.confirm_status";
				$noOfColumnsSelected++;
				if($this->input->post("selConfStatus") != ""){
					$cond .= " AND i.confirm_status = " . $this->input->post("selConfStatus");
				}
			}
			$skype = $this->input->post('skype');
			if(isset($skype)){
				array_push($header,"Skype ID");
				$selCols .= ", i.skype";
				$noOfColumnsSelected++;
			}
			$resignReson = $this->input->post('resignReson');
			if(isset($resignReson)){
				array_push($header, "Reason of Separation");
				$selCols .= ", sp.separation_name";
				$noOfColumnsSelected++;
				if($this->input->post("hdnReaSep") != ""){
					$cond .= " AND i.resign_reason IN (" . $this->input->post("hdnReaSep") . ")";
				}
			}
			$FnF_status = $this->input->post('FnF_status');
			if(isset($FnF_status)){
				array_push($header, "FnF Status");
				$selCols .= ", i.FnF_status";
				$noOfColumnsSelected++;
				if($this->input->post("selFnFStatus") != ""){
					$cond .= " AND i.FnF_status = " . $this->input->post("selFnFStatus");
				}
			}
			$emp_type = $this->input->post('emp_type');
			if(isset($emp_type)){
				array_push($header,"Employee Type");
				$selCols .= ", i.emp_type";
				$noOfColumnsSelected++;
				if($this->input->post("hdnEmpType") != ""){
					$cond .= " AND i.emp_type IN ('" . str_replace(",", "','", $this->input->post("hdnEmpType")) . "')";
				}
			}
			$emp_status_type = $this->input->post('emp_status_type');
			if(isset($emp_status_type)){
				array_push($header,"Employee Status Type");
				$selCols .= ", i.emp_status_type";
				$noOfColumnsSelected++;
				if($this->input->post("hdnEmpStatusType") != ""){
					$cond .= " AND i.emp_status_type IN ('" . str_replace(",", "','", $this->input->post("hdnEmpStatusType")) . "')";
				}
			}
			$user_status = $this->input->post('user_status');
			if(isset($user_status)){
				array_push($header,"Active/Inactive");
				$selCols .= ", i.user_status";
				$noOfColumnsSelected++;
				if($this->input->post("selEmpStatus") != ""){
					$cond .= " AND i.user_status = '" . $this->input->post("selEmpStatus") . "'";
				 }
			}
			$actual_skill = $this->input->post('actual_skill');
			if(isset($actual_skill)){
				array_push($header, "Actual Skill");
				$selCols .= ", ie.skills";
				$noOfColumnsSelected++;
			}
			$required_skill = $this->input->post('required_skill');
			if(isset($required_skill)){
				array_push($header, "Required Skill");
				$selCols .= ", i.designation";
				$noOfColumnsSelected++;
			}
			$actual_exp = $this->input->post('actual_exp');
			if(isset($actual_exp)){
				array_push($header, "Actual Exp.");
				$selCols .= ", i.designation, i.join_date";
				$noOfColumnsSelected++;
			}
			$required_exp = $this->input->post('required_exp');
			if(isset($required_exp)){
				array_push($header, "Required Exp.");
				$selCols .= ", i.designation, i.join_date";
				$noOfColumnsSelected++;
			}
			$actual_edu = $this->input->post('actual_edu');
			if(isset($actual_edu)){
				array_push($header, "Actual Edu.");
				$selCols .= ", i.designation";
				$noOfColumnsSelected++;
			}
			$required_edu = $this->input->post('required_edu');
			if(isset($required_edu)){
				array_push($header, "Required Edu.");
				$selCols .= ", i.designation";
				$noOfColumnsSelected++;
			}
			

			foreach($header AS $i => $head){
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
			}
			
			if($noOfColumnsSelected == 2)
			{ 
				if(isset($dept_name) && isset($hod))
				{
					$empDetailsQry = "SELECT d.dept_id".$selCols." FROM `department` d LEFT JOIN `internal_user` AS h ON h.login_id = d.login_id WHERE 1" . $cond;
				}
			}
			
			$empDetailsInfo_one = $this->Hr_model->empDetails($selCols,$cond);
			
			//$empDetailsRes = $this->db->query($empDetailsQry);
			//$empDetailsInfo = $empDetailsRes->result_array();
			var_dump($empDetailsInfo_one);
			//return $empDetailsInfo;
			$empDetailsNum = count($empDetailsInfo_one);

			//Employee details array
			$empSummaryArray = array();
			if($empDetailsNum >0)
			{
				$i = $ai = 0;
				foreach($empDetailsInfo_one as $empDetailsInfo)
				{
					$i++;
					$processEmpSummaryArray = true;

					$DOJ = date("d-m-Y", strtotime($empDetailsInfo['join_date']));

					if($empDetailsInfo['join_date'] != "")
					{
						$month_diff = $this->getDifferenceFromNow($empDetailsInfo['join_date'], 6);					
					}       
				   
					
					if($this->input->post("expAABSySFrom") != "" && $processEmpSummaryArray){
						if($month_diff < $this->input->post("expAABSySFrom")){
							$processEmpSummaryArray = false;
						}
					}
					if($this->input->post("expAABSySTo") != "" && $processEmpSummaryArray){
						if($month_diff > $this->input->post("expAABSySTo")){
							$processEmpSummaryArray = false;
						}
					}
					
					$totalExp = $month_diff + $empDetailsInfo['exp_others'];
					if($this->input->post("expFrom") != "" && $processEmpSummaryArray){
						if($totalExp < $this->input->post("expFrom")){
							$processEmpSummaryArray = false;
						}
					}
					if($this->input->post("expTo") != "" && $processEmpSummaryArray){
						if($totalExp > $this->input->post("expTo")){
							$processEmpSummaryArray = false;
						}
					}

					if($empDetailsInfo['dob'] != "0000-00-00")
					{
						$DOB = date("d-m-Y", strtotime($empDetailsInfo['dob']));
						$age = $this->getDifferenceFromNow($empDetailsInfo['dob'], 6);
					}
					else
					{
						$DOB = $age = "";
					}
					
					if($this->input->post("ageFrom") != "" && $processEmpSummaryArray){
						if($age < $this->input->post("ageFrom")){
							$processEmpSummaryArray = false;
						}
					}
					if($this->input->post("ageTo") != "" && $processEmpSummaryArray){
						if($age > $this->input->post("ageTo")){
							$processEmpSummaryArray = false;
						}
					}
				   
					if($empDetailsInfo['employee_conform'] != "0000-00-00" && $empDetailsInfo['employee_conform'] != NULL)
					{
						$CONFORM = date("d-m-Y", strtotime($empDetailsInfo['employee_conform']));
					}
					else
					{
						$CONFORM =" ";
					}

					if($empDetailsInfo['resign_date'] != "0000-00-00" && $empDetailsInfo['resign_date'] != NULL)
					{
							$DOR = date("d-m-Y", strtotime($empDetailsInfo['resign_date']));
					}
					else
					{
							$DOR ="";
					}
					if($empDetailsInfo['lwd_date'] != "0000-00-00" && $empDetailsInfo['lwd_date'] != NULL)
					{
							$LWD = date("d-m-Y", strtotime($empDetailsInfo['lwd_date']));
					}
					else
					{
							$LWD ="";
					}
					$emp_type = "";
					if($empDetailsInfo['emp_type'] == "C"){
						$emp_type = "Contractual";
					}elseif($empDetailsInfo['emp_type'] == "I"){
						$emp_type = "Interns";
					}else{
						$emp_type = "Full Time";
					}
					
					$emp_status_type = $empDetailsInfo['emp_status_type'];
					
					$status="Inactive";
					if($empDetailsInfo['user_status'] == 1)
					{
							$status="Active";
					}
					$gender ="Male";
					if($empDetailsInfo['gender'] == 'F')
					{
							$gender ="Female";
					}
					$marital_status ="Single";
					if($empDetailsInfo['marital_status'] == 'M')
					{
							$marital_status ="Married";
					}
					$permanentAddress= str_replace(array(" ",'%26','%3A','+','%2F','%29','%28','%2C','x','<br>',"\r\n","\n","\r")," ",$empDetailsInfo['address1']." ".$empDetailsInfo['city_district1'].",".$empDetailsInfo['State'].",".$empDetailsInfo['perCountry']." ".$empDetailsInfo['pin_zip1'] );
					$currentAdress = str_replace(array(" ",'%26','%3A','+','%2F','%29','%28','%2C','x','<br>',"\r\n","\n","\r")," ",$empDetailsInfo['address2']." ".$empDetailsInfo['city_district2'].",".$empDetailsInfo['CurrentState'].",".$empDetailsInfo['country_name']." ".$empDetailsInfo['pin_zip2'] );
					$hrRemark = str_replace(array(" ",'+','%26','%3A','%2F','%29','%28','%2C','x',"\r\n","\n","\r")," ",$empDetailsInfo['HR_remark']);

					$spouse_dob = $anniversary_date = $ff_date = $ff_handed_date = $dop = "";
					if($empDetailsInfo['spouse_dob'] != "" && $empDetailsInfo['spouse_dob'] != "0000-00-00")
						$spouse_dob = date("d-m-Y", strtotime($empDetailsInfo['spouse_dob']));

					if($empDetailsInfo['anniversary_date'] != "" && $empDetailsInfo['anniversary_date'] != "0000-00-00")
						$anniversary_date = date("d-m-Y", strtotime($empDetailsInfo['anniversary_date']));

					if($empDetailsInfo['ff_date'] != "" && $empDetailsInfo['ff_date'] != "0000-00-00")
						$ff_date = date("d-m-Y", strtotime($empDetailsInfo['ff_date']));

					if($empDetailsInfo['ff_handed_date'] != "" && $empDetailsInfo['ff_handed_date'] != "0000-00-00")
						$ff_handed_date = date("d-m-Y", strtotime($empDetailsInfo['ff_handed_date']));
					
					if($empDetailsInfo['last_promotion'] != "" && $empDetailsInfo['last_promotion'] != "0000-00-00")
						$dop = date("d-m-Y", strtotime($empDetailsInfo['last_promotion']));
					
					if(isset($prev_comp1) || isset($prev_deg1) || isset($no_exp1) ||
							isset($prev_comp2) || isset($prev_deg2) || isset($no_exp2) ||
							isset($prev_comp3) || isset($prev_deg3) || isset(no_exp3)))
					{
						//Get Experience Information
						$experience = array();
						$expSQL = "SELECT comp_name, designation, experince FROM exp_info WHERE login_id = ".$empDetailsInfo['login_id']." LIMIT 3";
						$expRES = $this->db->query($expSQL);
						$expINFO = $expRES->result_array();
						$expNUM = count($expRES);
						if($expNUM > 0){
							$ec = 0;
							while($expINFO){
								$experience[$ec++] = array($expINFO[0]["comp_name"], $expINFO[0]["designation"], $expINFO[0]["experince"]);
							}
						}
					}
					
					if(isset($graduation) || isset($actual_edu) || isset($required_edu) || isset($grad_passing_year) || isset($grad_percentage) || isset($grad_board) && $processEmpSummaryArray)
					{
						//Get Graduation Details
						$graduation = array();
						$eduCond = "";
						if($this->input->post("hdnGraduation") != ""){
							$eduCond .= " AND c.course_id IN (".$this->input->post("hdnGraduation").")";
						}
						 if($this->input->post("selSpecializationGrad") != ""){
							$eduCond .= " AND spl.specialization_id  = '".$this->input->post("selSpecializationGrad")."'";
						}
						if($this->input->post("gYOPFrom") != ""){
							$eduCond .= " AND e.passing_year >= " . $this->input->post("gYOPFrom");
						}
						if($this->input->post("gYOPTo") != ""){
							$eduCond .= " AND e.passing_year <= " . $this->input->post("gYOPTo");
						}
						if($this->input->post("hdngBorU") != ""){
							$eduCond .= " AND b.board_university_id IN (".$this->input->post("hdngBorU").")";
						}
						if($this->input->post("selEduCategoryG") != ""){
							$eduCond .= " AND c.course_category  = '".$this->input->post("selEduCategoryG")."'";
						}
						if($this->input->post("grad_perFrom") != ""){
							$eduCond .= " AND e.percentage >= " . $this->input->post("grad_perFrom");
						}
						if($this->input->post("grad_perTo") != ""){
							$eduCond .= " AND e.percentage <= " . $this->input->post("grad_perTo");
						}
						
						$educationSQL = "SELECT c.course_name, spl.specialization_name, e.passing_year, e.percentage, c.course_type, c.course_category, b.board_university_name
								FROM education_info AS e INNER JOIN course_info AS c ON e.course_id = c.course_id 
								INNER JOIN board_university_master AS b ON b.board_university_id = e.board_id 
								LEFT JOIN `specialization_master` spl ON spl.specialization_id = e.specialization_id 
								WHERE e.login_id = ".$empDetailsInfo['login_id']." AND c.course_type = 'Graduation' ".$eduCond." ORDER BY e.education_id DESC LIMIT 1";
						$educationRES = $this->db->query($educationSQL);
						$educationINFO = $educationRES->result_array();
						$educationNUM = count($educationRES);
						if($educationNUM > 0)
						{ 
							$graduation[] = $educationINFO[0]["course_name"];
							$graduation[] = $educationINFO[0]["passing_year"];
							$graduation[] = $educationINFO[0]["percentage"];
							$graduation[] = $educationINFO[0]["board_university_name"];
							$graduation[] = $educationINFO[0]["course_category"];
							$graduation[] = $educationINFO[0]["specialization_name"];
						}
						if($eduCond != "" && $educationNUM == 0){
							$processEmpSummaryArray = false;
						}
					}
					
					if((isset($professional) || isset($actual_edu) || isset($required_edu) || isset($prof_passing_year) || isset($prof_percentage) || isset($prof_board)) && $processEmpSummaryArray)
					{
						//Get Professional Details
						$professional = array();
						$eduCond = "";
						if($this->input->post("hdnProfessional") != "")
						{
							$eduCond .= " AND c.course_id IN (".$this->input->post("hdnProfessional").")";
						}
						if($this->input->post("selSpecializationProf") != "")
						{
							$eduCond .= " AND spl.specialization_id  = '".$this->input->post("selSpecializationProf")."'";
						}
						if($this->input->post("pYOPFrom") != "")
						{
							$eduCond .= " AND e.passing_year >= " . $this->input->post("pYOPFrom");
						}
						if($this->input->post("pYOPTo") != "")
						{
							$eduCond .= " AND e.passing_year <= " . $this->input->post("pYOPTo");
						}                    
						
						if($this->input->post("hdnpBorU") != "")
						{
							$eduCond .= " AND b.board_university_id IN (".$this->input->post("hdnpBorU").")";
						}
						if($this->input->post("selEduCategoryP") != "")
						{
							$eduCond .= " AND c.course_category  = '".$this->input->post("selEduCategoryP")."'";
						}
						if($this->input->post("prof_perFrom") != ""){
							$eduCond .= " AND e.percentage >= " . $this->input->post("prof_perFrom");
						}
						if($this->input->post("prof_perTo") != "")
						{
							$eduCond .= " AND e.percentage <= " . $this->input->post("prof_perTo");
						}
						$educationSQL = "SELECT c.course_name, e.passing_year, spl.specialization_name, e.percentage, c.course_type, c.course_category, b.board_university_name
								FROM education_info AS e INNER JOIN course_info AS c ON e.course_id = c.course_id 
								INNER JOIN board_university_master AS b ON b.board_university_id = e.board_id 
								LEFT JOIN `specialization_master` spl ON spl.specialization_id = e.specialization_id 
								WHERE e.login_id = ".$empDetailsInfo['login_id']." AND c.course_type = 'Professional' ".$eduCond." ORDER BY e.education_id DESC LIMIT 1";
						//exit;
						$educationRES = $this->db->query($educationSQL);
						$educationINFO = $educationRES->result_array();
						$educationNUM = count($educationRES);
						if($educationNUM > 0)
						{ 
							$professional[] = $educationINFO[0]["course_name"];
							$professional[] = $educationINFO[0]["passing_year"];
							$professional[] = $educationINFO[0]["percentage"];
							$professional[] = $educationINFO[0]["board_university_name"];
							$professional[] = $educationINFO[0]["course_category"];
							$professional[] = $educationINFO[0]["specialization_name"];
						}
						if($eduCond != "" && $educationNUM == 0)
						{
						   $processEmpSummaryArray = false;
						}
					}
					
					if(isset($child1) || isset($child_dob1) ||
							isset($child2) || isset($child_dob2) ||
							isset($child3) || isset($child_dob3))
					{
						//Get Child Information
						$child = array();
						$childSQL = "SELECT child_name, child_dob FROM child_info WHERE login_id = ".$empDetailsInfo['login_id']." LIMIT 3";
						$childRES = $this->db->query($childSQL);
						$childINFO = $childRES->result_array();
						$childNUM = count($childRES);
						if($childNUM > 0){
							$cc = 0;
							while($childINFO){
								$childDOB = "";
								if($childINFO[0]['child_dob'] != "" && $childINFO[0]['child_dob'] != "0000-00-00")
									$childDOB = date("d-m-Y", strtotime($childINFO[0]['child_dob']));

								$child[$cc++] = array($childINFO[0]["child_name"], $childDOB);
							}
						}
					}
					
					if(isset($increment))
					{
						//Get Last Increment
						$increment = 0;
						$incrementSQL = "SELECT increament FROM emp_increament_info WHERE login_id = ".$empDetailsInfo['login_id']." LIMIT 1";
						$incrementRES = $this->db->query($incrementSQL);
						$incrementINFO = $incrementRES->result_array();
						$incrementNUM = count($incrementRES);
						if($incrementNUM == 1)
						{  
							$increment = $incrementINFO[0]["increament"];
						}
					}
					
					if($processEmpSummaryArray)
					{ 
						//Employee summary array
						$empSummary = array();
						array_push($empSummary,$i);
						if(isset($loginhandle))
							array_push($empSummary,$empDetailsInfo['loginhandle']);
						if(isset($full_name))
							array_push($empSummary, $empDetailsInfo['full_name']);
						if(isset($father_name))
							array_push($empSummary, $empDetailsInfo['fathers_name']);
						if(isset($mother_name))
							array_push($empSummary, $empDetailsInfo['mother_name']);
						if(isset($gender))
							array_push($empSummary, $gender); 
						if(isset($dob))
							array_push($empSummary, $DOB); 
						if(isset($doj))
							array_push($empSummary,$DOJ);
						if(isset($doc))
							array_push($empSummary,$CONFORM);
						if(isset($grade))
							array_push($empSummary,  $empDetailsInfo['grade_name']);
						if(isset($level))
							array_push($empSummary,  $empDetailsInfo['level_name']);
						if(isset($dept_name))
							array_push($empSummary,  $empDetailsInfo['dept_name']);
						if(isset($desg_name))
							array_push($empSummary, $empDetailsInfo['desg_name']);
						if(isset($loc))
							array_push($empSummary, $empDetailsInfo['branch_name']);
						if(isset($reporting))
							array_push($empSummary, $empDetailsInfo['reporting']);
						if(isset($rev_officer))				  
							array_push($empSummary, $empDetailsInfo['reviewing']);
						if(isset($hod))
							array_push($empSummary,$empDetailsInfo['hod']); 
						if(isset($exp_aabsys))
							array_push($empSummary, round(($month_diff/12),1));
						if(isset($exp_others))
							array_push($empSummary, round(($empDetailsInfo['exp_others']/12),1));
						if(isset($exp_total))
							array_push($empSummary, $totalExp);
						if(isset($age))
							array_push($empSummary,round(($age/12),1));
						if(isset($basic))
							array_push($empSummary, $empDetailsInfo['basic']);
						if(isset($hra))
							array_push($empSummary, $empDetailsInfo['hra']);
						if(isset($conv))
							array_push($empSummary, $empDetailsInfo['conveyance_allowance']); 
						if(isset($gross_salary))
							array_push($empSummary, $empDetailsInfo['gross_salary']); 
						if(isset($official_mobile))
							array_push($empSummary, $empDetailsInfo['official_mobile']);
						if(isset($email))
							array_push($empSummary, $empDetailsInfo['email']); 
						if(isset($prev_comp1))
							array_push($empSummary,$experience[0][0]);
						if(isset($prev_deg1))
							array_push($empSummary, $experience[0][1]); 
						if(isset($no_exp1))
							array_push($empSummary, $experience[0][2]); 
						if(isset($prev_comp2))
							array_push($empSummary,$experience[1][0]);
						if(isset($prev_deg2))
							array_push($empSummary,$experience[1][1]); 
						if(isset($no_exp2))
							array_push($empSummary,$experience[1][2]); 
						if(isset($prev_comp3))
							array_push($empSummary,$experience[2][0]);
						if(isset($prev_deg3))
							array_push($empSummary,$experience[2][1]); 
						if(isset($no_exp3))
							array_push($empSummary,$experience[2][2]); 
						if(isset($graduation))
							array_push($empSummary, $graduation[0]); 
						if(isset($specializationGrad))
							array_push($empSummary, $graduation[5]);
						if(isset($grad_passing_year))
							array_push($empSummary, $graduation[1]);
						if(isset($grad_percentage))
							array_push($empSummary, $graduation[2]); 
						if(isset($grad_board))
							array_push($empSummary, $graduation[3]);
						if(isset($edu_catG))
							array_push($empSummary, $graduation[4]); 
						if(isset($professional))
							array_push($empSummary, $professional[0]); 
						if(isset($specializationProf))
							array_push($empSummary, $professional[5]);
						if(isset($prof_passing_year))
							array_push($empSummary, $professional[1]);
						if(isset($prof_percentage))
							array_push($empSummary, $professional[2]); 
						if(isset($prof_board))
							array_push($empSummary, $professional[3]);
						if(isset($edu_catP))
							array_push($empSummary, $professional[4]);
						if(isset($State))
							array_push($empSummary, $empDetailsInfo['State']);                       

						if(isset($spouse_name))
							array_push($empSummary, $empDetailsInfo['spouse_name']);
						if(isset($spouse_dob))
							array_push($empSummary, $spouse_dob);
						if(isset($anniversary_date))
							array_push($empSummary, $anniversary_date);
						if(isset($child1))
							array_push($empSummary, $child[0][0]);
						if(isset($child_dob1))
							array_push($empSummary,  $child[0][1]);
						if(isset($child2))
							array_push($empSummary, $child[1][0]);
						if(isset($child_dob2))
							array_push($empSummary,  $child[1][1]);
						if(isset($child3))
							array_push($empSummary, $child[2][0]);
						if(isset($child_dob3))
							array_push($empSummary,  $child[2][1]);
						if(isset($per_add))
							array_push($empSummary,  $permanentAddress);
						if(isset($corr_add))
							array_push($empSummary,  $currentAdress);
						if(isset($phone1))
							array_push($empSummary,  $empDetailsInfo['phone1']);
						if(isset($mobile1))
							array_push($empSummary, $empDetailsInfo['mobile1']);	
						if(isset($phone2))
							array_push($empSummary,  $empDetailsInfo['phone2']);
						if(isset($mobile))
							array_push($empSummary, $empDetailsInfo['mobile']); 
						if(isset($per_email))
							array_push($empSummary, $empDetailsInfo['per_email']);
						if(isset($pan_card_no))
							array_push($empSummary, $empDetailsInfo['pan_card_no']);
						if(isset($drl_no))
							array_push($empSummary, $empDetailsInfo['drl_no']);
						if(isset($voter_id))
							array_push($empSummary, $empDetailsInfo['voter_id']);
						if(isset($adharcard_no))
							array_push($empSummary, $empDetailsInfo['adharcard_no']);
						if(isset($passport_no))
							array_push($empSummary, $empDetailsInfo['passport_no']);
						if(isset($mediclaim_no))
							array_push($empSummary, $empDetailsInfo['mediclaim_no']);
						if(isset($blood_group))
							array_push($empSummary, $empDetailsInfo['blood_group']);
						if(isset($bank))
							array_push($empSummary, $empDetailsInfo['bank_name']);
						if(isset($bank_no))
							array_push($empSummary, $empDetailsInfo['bank_no']);
						if(isset($pf_no))
							array_push($empSummary, $empDetailsInfo['pf_no']);
						 
						if(isset($offer_letter_issued))
							array_push($empSummary, $empDetailsInfo['offer_letter_issued']);
						if(isset($appoint_letter_issued))
							array_push($empSummary, $empDetailsInfo['appoint_letter_issued']);
						if(isset($conf_letter_issued))
							array_push($empSummary, $empDetailsInfo['conf_letter_issued']);
						if(isset($increment))
							array_push($empSummary,$increment);
						if(isset($dop))
							array_push($empSummary, $dop);
						if(isset($miscunduct_issue))
							array_push($empSummary, $empDetailsInfo['miscunduct_issue']);
						if(isset($DOR))
							array_push($empSummary, $DOR);
						if(isset($LWD))
							array_push($empSummary, $LWD);
						if(isset($ff_date))
							array_push($empSummary, $ff_date);
						if(isset($ff_amount))
							array_push($empSummary, $empDetailsInfo['ff_amount']);
						if(isset($ff_handed_date))
							array_push($empSummary, $ff_handed_date);
						if(isset($hrRemark))
							array_push($empSummary,$hrRemark);
						if(isset($source_hire))
							array_push($empSummary, $empDetailsInfo['source_hire_name']);
						if(isset($marital_status))
							array_push($empSummary, $marital_status);
						if(isset($highest_qual))
							array_push($empSummary, $empDetailsInfo['course_name']);
						if(isset($loc_highest_qualActual))
							array_push($empSummary, $empDetailsInfo['loc_highest_qualActual']);
						if(isset($confirm_status))
							array_push($empSummary, $empDetailsInfo['confirm_status']);
						if(isset($skype))
							array_push($empSummary, $empDetailsInfo['skype']);
						if(isset($resignReson))
							array_push($empSummary, $empDetailsInfo['separation_name']);                    
						if(isset($FnF_status))
							array_push($empSummary, $empDetailsInfo['FnF_status']);
						if(isset($emp_type))
							array_push($empSummary,$emp_type);
						if(isset($emp_status_type))
							array_push($empSummary,$emp_status_type);
						if(isset($user_status))
							array_push($empSummary,$status);
						
						
						if(isset($actual_skill))
						{
							$actuallSkillList = "";                       
								/* query for skills */
								$skillSQL = "SELECT skill_name FROM skills_master WHERE skill_id IN (".$empDetailsInfo["skill_id"].")";
								$skillRES = $this->db->query($skillSQL);
								$skillINFO = $skillRES->result_array();
								while($skillINFO)
								{
									$actuallSkillList .= $skillINFO[0]['skill_name'].', ';
								}  
								array_push($empSummary, $actuallSkillList);    
						}
						if(isset($required_skill)){
							$reqSkillList='';
							$reqSkillSQL = "SELECT s.skill_name FROM minimum_requirement AS r INNER JOIN skills_master AS s ON s.skill_id = r.requirement_type_id WHERE r.designation_id = '".$empDetailsInfo["designation"]."' AND r.requirement_type = 'S'";
							$reqSkillRES = $this->db->query($reqSkillSQL);
							$reqSkillINFO = $reqSkillRES->result_array();
							while($reqSkillINFO)
							{
								$reqSkillList .= $reqSkillINFO[0]["skill_name"].', '; 
							}
							array_push($empSummary, $reqSkillList);
						}
						if(isset($actual_exp))
						{
							array_push($empSummary,$totalExp);
						}
						if(isset($required_exp)){
							$reqExperience = "Not Defined";
							$reqExpSQL = "SELECT e.experience_name FROM minimum_requirement AS r INNER JOIN experience_master AS e ON e.experience_id = r.requirement_type_id WHERE r.designation_id = ".$empDetailsInfo['designation']." AND r.requirement_type = 'W' LIMIT 1";
							$reqExpRES = $this->db->query($reqExpSQL);                        
							$reqExpINFO = $reqExpRES->result_array();
							$reqExperience = $reqExpINFO[0]["experience_name"]; 
							array_push($empSummary,$reqExperience);
						}
						
						if(isset($actual_edu))
						{
							array_push($empSummary, ($graduation[0]!='')?$graduation[0]:$professional[0]);                           
						}
						if(isset($required_edu))
						{
							$reqEduINFO='';
							$reqEduSQL = "SELECT c.course_name FROM minimum_requirement AS r INNER JOIN course_info AS c ON c.course_id = r.requirement_type_id WHERE r.designation_id = '".$empDetailsInfo["designation"]."' AND r.requirement_type = 'E'";
							$reqEduRES = $this->db->query($reqEduSQL);
							$reqEduINFO = $reqEduRES->result_array();
							$reqEduINFO =  $reqEduINFO[0]["course_name"] ; 
							array_push($empSummary,$reqEduINFO);                          
						}
						$empSummaryArray[$ai++] = $empSummary;
					} 
				}
			} 
			$totalKS = count($empSummaryArray);
			$row = 2;
			for($i=0; $i< $totalKS; $i++)
			{
				foreach($empSummaryArray[$i] AS $col => $empInfo)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}
			 
			$filename = "employee_report_".date("dmYHis").".xls"; 
			
			// Rename worksheet
			$objPHPExcel->getDefaultStyle()->applyFromArray(array(
					'borders' => array(
						'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
					)
				));
		 
			$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setTitle('Employee Report ' . date("M, Y"));


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=\"$filename\""); 
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		} 
		$this->render('hr/employee_management/emp_report_view', 'full_width',$this->mViewData);
	}
	public function getNameFromNumber($num) 
	{
		$numeric = $num % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval($num / 26);
		if ($num2 > 0)
		{
			return $this->getNameFromNumber($num2 - 1) . $letter;
		} 
		else
		{
			return $letter;
		}
	}
	public function getDifferenceFromNow($date, $format = 0)
	{
		$secondsDifference = time() - strtotime($date) ;
		switch($format){
			// Difference in Seconds
			case 1: 
				return floor($secondsDifference);
			// Difference in Minutes
			case 2: 
				return floor($secondsDifference/60);
			// Difference in Hours    
			case 3:
				return floor($secondsDifference/60/60);
			// Difference in Days    
			case 4:
				return floor($secondsDifference/60/60/24);
			// Difference in Weeks    
			case 5:
				return floor($secondsDifference/60/60/24/7);
			// Difference in Months    
			case 6:            
				 // Assume YYYY-mm-dd - as is common MYSQL format
				$splitStart = explode('-', $date);
				$splitEnd = explode('-',date('Y-m-d') );

				if (is_array($splitStart) && is_array($splitEnd)) {
					$startYear = $splitStart[0];
					$startMonth = $splitStart[1];
					$endYear = $splitEnd[0];
					$endMonth = $splitEnd[1];

					$difYears = $endYear - $startYear;
					$difMonth = $endMonth - $startMonth;

					if (0 == $difYears && 0 == $difMonth) { // month and year are same
						return 0;
					}
					else if (0 == $difYears && $difMonth > 0) { // same year, dif months
						return $difMonth;
					}
					else if (1 == $difYears) {
						$startToEnd = 12 - $startMonth; // months remaining in start year(13 to include final month
						return ($startToEnd + $endMonth); // above + end month date
					}
					else if ($difYears > 1) {
						$startToEnd = 12 - $startMonth; // months remaining in start year 
						$yearsRemaing = $difYears - 1;  // minus the years of the start and the end year
						$remainingMonths = 12 * $yearsRemaing; // tally up remaining months
						$totalMonths = $startToEnd + $remainingMonths + $endMonth; // Monthsleft + full years in between + months of last year
						return $totalMonths;
					}
				}
				else {
					return false;
				}
				//return floor($secondsDifference/60/60/24/7/4);
			// Difference in Years
			default:
				return floor($secondsDifference/365/60/60/24);
		}      
	}
	public function resume_format()
	{
		$this->render('hr/employee_management/resume_format_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/resume_format_script');
	}
	public function get_active_emp_resume()
	{
		$result = $this->Hr_model->get_active_emp_resume(); 
		$this->json_response($result); 
	}
	//end employee managemnt
	
	//Start Attendance entry
	
	public function biometric_data_upload()
	{  
		$this->render('hr/biometric_data_upload_view', 'full_width',$this->mViewData);
	} 
	public function lwh_report()
	{  
		$this->render('hr/lwh_report_view', 'full_width',$this->mViewData);
	} 
	public function emp_attendance_summary()
	{  
		$this->render('hr/emp_attendance_summary_view', 'full_width',$this->mViewData);
	}
	
	//End Attendance entry
	
	//Start Payroll managment
	
	public function allowance_deduction_list()
	{  
		$this->render('hr/allowance_deduction_list_view', 'full_width',$this->mViewData);
	} 
	public function generate_salary()
	{  
		$this->render('hr/generate_salary_view', 'full_width',$this->mViewData);
	} 
	public function salary_sheet()
	{  
		$this->render('hr/salary_sheet_view', 'full_width',$this->mViewData);
	} 
	public function mail_salary_slip()
	{  
		$this->render('hr/mail_salary_slip_view', 'full_width',$this->mViewData);
	} 
	public function payroll_report()
	{  
		$this->render('hr/payroll_report_view', 'full_width',$this->mViewData);
	} 
	public function increment_report()
	{  
		$this->render('hr/increment_report_view', 'full_width',$this->mViewData);
	} 
	public function epf_report()
	{  
		$this->render('hr/epf_report_view', 'full_width',$this->mViewData);
	} 
	public function esi_report()
	{  
		$this->render('hr/esi_report_view', 'full_width',$this->mViewData);
	} 
	public function graph_profile_list()
	{  
		$this->render('hr/graph_profile_list_view', 'full_width',$this->mViewData);
	}  
	public function general_resources()
	{  
		$this->render('resources/general_resources_view', 'full_width',$this->mViewData);
		$this->load->view('script/resources/general_resources_script');
	}
	//End Payroll managment 
	/*Start Ajax with angularjs function*/
	public function get_phone_directory()
	{
		$result = $this->Resource_model->get_all_phone_no(); 
		$this->json_response($result); 
	}
	/*End */
	public function json_response($return)
	{
		ob_start("ob_gzhandler");
		header("Content-type: application/json");
		echo json_encode($return);
		ob_end_flush(); 
	}
}
