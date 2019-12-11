<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Timesheet extends MY_Controller
{
    var $data = array('visit_type' => '', 'pageTitle' => '', 'file' => '', 'reporting_manager' => '', 'calRow' => '', 'monthHeader' => '', 'weekHeader' => '', 'monthBody' => '');
    public function __construct()
    {
        parent::__construct();
		ini_set('max_execution_time', 600); // 10 mins
		/******************* Remote Restriction Control ************************/
		$ipAddr=array();
		for($p=1; $p<255;$p++){
			$ip='172.61.24.'.$p;
			array_push($ipAddr,$ip);
			$ip='172.61.25.'.$p;
			array_push($ipAddr,$ip);
		}
		array_push($ipAddr, '203.129.198.75');
		if(!in_array($_SERVER['REMOTE_ADDR'], $ipAddr) && $_SERVER['REMOTE_ADDR'] != '::1' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1' && $_SERVER['REMOTE_ADDR'] != '111.93.166.70' && $_SERVER['REMOTE_ADDR'] != '220.225.24.37' && $_SERVER['REMOTE_ADDR'] != '123.63.170.114'){
			$remoteIcompassQRY = "SELECT `login_id`,`remote_access` FROM `internal_user` WHERE `remote_access` = 'Y' AND `login_id`='".$this->session->userdata('user_id')."'";
			$remoteIcompassRES = $this->db->query($remoteIcompassQRY);
			$remoteIcompassNUM = $remoteIcompassRES->result_array();
			if(count($remoteIcompassNUM) < 1){
				session_destroy();
				redirect(site_url('login/unauthorized'),'refresh');
				exit();
			}
		}
		if($_SERVER['REMOTE_ADDR'] == '203.129.198.75'){
			$remoteIcompassQRY = "SELECT `login_id`,`remote_access` FROM `internal_user` WHERE `remote_access` = 'Y' AND `login_id`='".$this->session->userdata('user_id')."'";
			$remoteIcompassRES = $this->db->query($remoteIcompassQRY);
			$remoteIcompassNUM = $remoteIcompassRES->result_array();
			if(count($remoteIcompassNUM) < 1){
				session_destroy();
				redirect(site_url('login/unauthorized'),'refresh');
				exit();
			}
		}
		/******************* END/ Remote Restriction Control ************************/
		
        $this->load->model('timesheet_model');
        $this->load->model('ams_model');
		//$this->timesheet_model->get();
        //$this->session->userdata('user_id');
        //$this->data['get_emp_type'] =$this->timesheet_model->get_employee_type();
        //var_dump($this->data['get_emp_type']);exit;
		$this->load->library('session');
		$this->load->model('event_model');
		if(empty($this->session->userdata['user_id']))
		{
			redirect(site_url('login'),'refresh');
		}
		
		// create the data object
		$mViewData = new stdClass();
		//Today Event count 
		$eventToday = $this->event_model->get_news_and_events_today();
		$this->mViewData['countEventToday'] = count($eventToday);
		$this->mViewData['countRegularizationPending'] = $this->event_model->get_my_regularies_pending_count();
		$this->mViewData['countLeavePending'] = $this->event_model->get_my_leave_request_count();
		$this->mViewData['countRegularizationPendingRM'] = $this->event_model->get_all_regularization_request_rm_count();
		$this->mViewData['countLeavePendingRM'] = $this->event_model->get_all_leave_request_rm_count();
    }
	
	
    public function index()
    {
		$uid=$this->session->userdata('user_id');
		$this->mViewData['pageTitle'] = 'Time Sheet';
        $year      = date("Y");
        if($this->input->post('dd_year') != '')
		{
            $year = $this->input->post('dd_year');
        }
        $monthNames = array(
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        );
        $dayNames   = array(
            'Sun',
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri',
            'Sat'
        );
		
        // Get Employee Type
        $empTypeQry = "SELECT `emp_type`,join_date FROM  `internal_user` WHERE `login_id` = '" . $this->session->userdata('user_id') . "'";
        $empTypeRes = $this->db->query($empTypeQry)->result_array();
        //var_dump($empTypeRes);exit;
		
        $empTypeNum = count($empTypeRes);
        if ($empTypeNum > 0)
		{
            foreach ($empTypeRes as $empTypeInfo)
			{
                $Type = $empTypeInfo['emp_type'];
                //var_dump($Type);exit;
                $joindate = date("Y-m-d", strtotime($empTypeInfo['join_date']));
				//echo $joindate;exit;
            }
        }
        // Get All Declared Holidays
        $declaredHolidayArray[] = '';
        $holidaySql             = "SELECT `dt_event_date`, `s_event_name` FROM `declared_leave` WHERE (`branch`='0' OR `branch`='" . $this->session->userdata('branch') . "') AND `f_disp_flag` = 1 AND DATE_FORMAT(`dt_event_date`, '%Y') = $year";
        $holidayRes             = $this->db->query($holidaySql)->result_array();
        //var_dump($holidayRes);exit;
        $contdate               = 1;
        foreach ($holidayRes as $holidayInfo)
		{
            $keyname = $holidayInfo['s_event_name'];
            $contdate++;
            if(($keyname == 'Sunday') OR ($keyname == 'First Saturday') OR ($keyname == 'Third Saturday'))
			{
                $keyname = $holidayInfo['s_event_name'] . $contdate;
            }
            $declaredHolidayArray[$keyname] = $holidayInfo['dt_event_date'];
            //var_dump($declaredHolidayArray[$keyname]);exit;
        }
        // Get All Present, Leave & Regularize days Info
        $attendanceArray[] = $attendanceStatusArray[] = $attendanceInArray[] = $attendanceOutArray[] = $attendanceReasonArray[] = $attendanceLeaveTypeArray[] = '';
        // $attendanceArray = array();
        // $attendanceStatusArray = array();
        // $attendanceInArray = array();
        // $attendanceOutArray = array();
        // $attendanceReasonArray = array();
        $attendanceSql     = "SELECT `date`, `att_status`, `in_time`, `out_time`, `reason` , `leave_type` FROM `attendance_new` WHERE `login_id` = '" . $this->session->userdata('user_id') . "' AND DATE_FORMAT(`date`, '%Y') = $year";
        $attendanceRes     = $this->db->query($attendanceSql)->result_array();
        //var_dump($attendanceInfo);exit;
        //$attendanceRes = mysql_query($attendanceSql);
        // while($attendanceInfo)
        // {
        // $attendanceArray[] = $attendanceInfo['date'];
        // $attendanceStatusArray[] = $attendanceInfo['att_status'];
        // $attendanceInArray[] = $attendanceInfo['in_time'];
        // $attendanceOutArray[] = $attendanceInfo['out_time'];
        // $attendanceReasonArray[] = $attendanceInfo['reason'];
        // }
        foreach ($attendanceRes as $attendanceInfo)
		{
            $attendanceArray[]       = $attendanceInfo['date'];
            $attendanceStatusArray[] = $attendanceInfo['att_status'];
            $attendanceLeaveTypeArray[] = $attendanceInfo['leave_type'];
            $attendanceInArray[]     = $attendanceInfo['in_time'];
            $attendanceOutArray[]    = $attendanceInfo['out_time'];
            $attendanceReasonArray[] = $attendanceInfo['reason'];
        }
        for($k = 0; $k < 13; $k++)
		{
            $present = $leave = $absent = $holiday = 0;
            $date    = 0;
            if ($k != 0)
			{
                $days     = cal_days_in_month(CAL_GREGORIAN, $k, $year);
                $firstDay = date("N", strtotime($k . '/01/' . $year));
                $firstDay = ($firstDay == 7) ? 0 : $firstDay;
            }
            for($d = 0; $d < 37; $d++)
			{
                if ($k == 0)
				{
                    if (($d % 7 == 0))
					{
                        $dayClass = 'sunday';
                        //$calrow_result = $calRow[$d];
                        $this->mViewData['calRow'][$d] = '<td style="width:8.1%"><div class="sunday">' . $dayNames[$d % 7] . '</div></td>';
                    }
					elseif(($d % 6 == 0) OR ($d % 20 == 0))
					{
                        $dayClass = 'saturday';
                        $this->mViewData['calRow'][$d] = '<td><div class="sunday">' . $dayNames[$d % 7] . '</div></td>';
                    }
					else
					{
                        $dayClass = 'otherday';
                        $this->mViewData['calRow'][$d] = '<td><div class="' . $dayClass . '">' . $dayNames[$d % 7] . '</div></td>';
                    }
                }
				else
				{
                    if ($d >= $firstDay && $date < $days)
					{
                        $date++;
                        $kool = date("Y-m-d", strtotime($date . '-' . $k . '-' . $year));
                        if ($holidayFor = array_search($kool, $declaredHolidayArray))
						{
                            if (strpos($holidayFor, "Sunday") !== false)
							{
                                $holidayFor = "Sunday";
                            }
							elseif (strpos($holidayFor, "First Saturday") !== false)
							{
                                $holidayFor = "First Saturday";
                            }
							elseif (strpos($holidayFor, "Third Saturday") !== false)
							{
                                $holidayFor = "Third Saturday";
                            }
                            $holiday++;
                            $this->mViewData['calRow'][$d] .= '<td><div class=" holidays"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '</span><br/> ' . $holidayFor . '"><div class="black_dates" class="top">' . $date . '</div></div></td>';
                        }
                        /*
                        else if(date("N", strtotime($k.'/'.$date.'/'.$year)) == 7){
                        $holiday++;
                        $calRow[$d] .= '
                        <td>
                        <div class="iCompassTip holidays" title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span> <br/> Sunday">
                        <div class="black_dates">'.$date.'</div>
                        </div>
                        </td>
                        ';
                        }
                        else if( ( $kool >= NEW_SATURDAY_LEAVE) && (date("N", strtotime($k.'/'.$date.'/'.$year)) == 6) &&(($d==6) || ($d==20)) ){
                        if($d==6){$type = 'First';}else{$type = 'Third';}
                        $holiday++;
                        $calRow[$d] .= '
                        <td>
                        <div class="iCompassTip holidays" title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span> <br/> '.$type.' Saturday">
                        <div class="black_dates">'.$date.'</div>
                        </div>
                        </td>
                        ';
                        }*/
                        else{
                            if((NEW_ATTENDANCE_START <= $kool) && ($joindate <= $kool))
							{
										$mysqldate_format = strtotime($year . '-' . $k . '-' . $date);
										$sql="SELECT * FROM biomatrics_daily_workduration WHERE login_id = ? AND attend_date = ?";
										
										$qr1=$this->db->query($sql,array($uid,$mysqldate_format))->result_array();
										 
										 $div_class = "attend_day";
										 $txt_data = "Present";
										 
										 if(count($qr1)>0)
										 {
												$firs_punch_in = $qr1[0]->first_punch_in;
												$last_punch_out =  $qr1[0]->last_punch_out;
												$tot_office_duration = $qr1[0]->tot_office_duration;
												$tot_attend_duration = $qr1[0]->tot_attend_duration;
												$attendance_status_user = $qr1[0]->attendance_status;											
													
												if($attendance_status_user=='A')
												{
													$div_class="absent_day";
													$txt_data = "Absent";
												
												}
											
										 }
								
                                if($attVic = array_search($kool, $attendanceArray))
								{
                                    if($attendanceStatusArray[$attVic] == 'P')
									{
                                        $present++;
                                        $outTime = '';
                                        $inTime  = date("g:i:s A", strtotime($attendanceInArray[$attVic]));
                                        if($attendanceOutArray[$attVic] != '00:00:00')
										{
                                            $outTime = date("g:i:s A", strtotime($attendanceOutArray[$attVic]));
                                        }
                                        
										//$this->mViewData['calRow'][$d] .= '<td><div class=" attend_day"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '</span><br/> Present (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
										
										
										
										
										$this->mViewData['calRow'][$d] .= '<td><div class=" '.$div_class.'"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '</span><br/> '.$txt_data.' (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
									elseif($attendanceStatusArray[$attVic] == 'W')
									{
                                        $absent++;
                                        $outTime = '';
                                        $inTime  = date("g:i:s A", strtotime($attendanceInArray[$attVic]));
                                        if ($attendanceOutArray[$attVic] != '00:00:00')
										{
                                            $outTime = date("g:i:s A", strtotime($attendanceOutArray[$attVic]));
                                        }
                                        $this->mViewData['calRow'][$d] .= '<td><div class=" lessworking_day" title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '</span><br/> LW Hours (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
									elseif($attendanceStatusArray[$attVic] == 'H')
									{
                                        // get Which Half
                                        $whichHalf = $this->getWhichHalfLeave($this->session->userdata('user_id'), date("Y-m-d", strtotime($date . '-' . $k . '-' . $year)));
                                        $leave = $leave + 0.5;
                                        $hType = 'L';
                                        if(date("Y-m-d") >= $kool)
										{
                                            if($attendanceInArray[$attVic] != '00:00:00')
											{
                                                $present = $present + 0.5;
                                                $hType   = 'P';
                                            }
											else
											{
                                                $absent = $absent + 0.5;
                                                $hType  = 'A';
                                            }
                                        }
                                        if($whichHalf == 'F')
										{
                                            if($hType == 'P')
											{
                                                $bgClass = 'half_present_1';
                                            }
											elseif($hType == 'A')
											{
                                                $bgClass = 'half_absent_1';
                                            }
											else
											{
                                                $bgClass = 'half_leave_1';
                                            }
                                        }
										else
										{
                                            if($hType == 'P')
											{
                                                $bgClass = 'half_present_2';
                                            }
											elseif($hType == 'A')
											{
                                                $bgClass = 'half_absent_2';
                                            }
											else
											{
                                                $bgClass = 'half_leave_2';
                                            }
                                        }
                                        $outTime = '';
                                        $inTime  = '';
                                        if($attendanceInArray[$attVic] != '00:00:00')
										{
                                            $inTime = date("g:i:s A", strtotime($attendanceInArray[$attVic]));
                                        }
                                        if($attendanceOutArray[$attVic] != '00:00:00')
										{
                                            $outTime = date("g:i:s A", strtotime($attendanceOutArray[$attVic]));
                                        }
                                        $this->mViewData['calRow'][$d] .= '<td><div class=" ' . $bgClass . '"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . ' </span><br/>' . $attendanceReasonArray[$attVic] . ' (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
									elseif($attendanceStatusArray[$attVic] == 'L')
									{
                                        $leave++;
										if($attendanceLeaveTypeArray[$attVic] == "M")
										{
											$this->mViewData['calRow'][$d] .= '<td><div class=" leave_day_ml"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '</span><br/> ' . $attendanceReasonArray[$attVic] . '"><div class="white_dates">' . $date . '</div></div></td>';
										}else{
											$this->mViewData['calRow'][$d] .= '<td><div class=" leave_day"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '</span><br/> ' . $attendanceReasonArray[$attVic] . '"><div class="white_dates">' . $date . '</div></div></td>';
										}
                                        
                                    }
									elseif($attendanceStatusArray[$attVic] == 'R')
									{
										$outTime = '';
                                        $inTime  = '';
                                        if($attendanceInArray[$attVic] != '00:00:00')
										{
                                            $inTime = date("g:i:s A", strtotime($attendanceInArray[$attVic]));
                                        }
                                        if($attendanceOutArray[$attVic] != '00:00:00')
										{
                                            $outTime = date("g:i:s A", strtotime($attendanceOutArray[$attVic]));
                                        }
										
                                        $present++;
                                        $this->mViewData['calRow'][$d] .= '<td><div class=" regularize_day"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . ' </span><br/>'. ' (' . $inTime . ' - ' . $outTime . ') <br/>' . $attendanceReasonArray[$attVic] . '"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
                                }
								else
								{
                                    if(date("Y-m-d") < $kool)
									{
                                        $this->mViewData['calRow'][$d] .= '<td><div class="black_dates">' . $date . '</div></td>';
                                    }
									else
									{
                                        $absent++;
                                        $this->mViewData['calRow'][$d] .= '<td><div class="absent_day" title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '</span><br/> Absent"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
                                }
                            }
							else
							{
                                $this->mViewData['calRow'][$d] .= '<td><div class="black_dates">' . $date . '</div></td>';
                            }
                        }
                    }
					else
					{
                        $this->mViewData['calRow'][$d] .= '<td>&nbsp;</td>';
                    }
                }
            }
            if($k != 0)
			{
                $v                 = $k - 1;
                $presentArray[$v]  = $present;
                $absentArray[$v]   = $absent;
                $leaveArray[$v]    = $leave;
                $holidayArray[$v]  = $holiday;
                $totaldayArray[$v] = $days;
            }
        }
        $this->mViewData['monthHeader'] = '';
        for($k = 0; $k < 12; $k++)
		{
            $monthName = $monthNames[$k];
            $payDays   = $presentArray[$k] + $holidayArray[$k];
            if($totaldayArray[$k] == $payDays)
			{
                $this->mViewData['monthHeader'] .= ' <th>
					<div class=" img"
					title="Present - ' . $presentArray[$k] . ' <br/> Leave - ' . $leaveArray[$k] . ' <br/> Absent - ' . $absentArray[$k] . ' <br/> Holiday - ' . $holidayArray[$k] . '">' . $monthName . '</div></th>';
            }
			else
			{ 
                $this->mViewData['monthHeader'] .= '<th><div class="" 
														title="Present&nbsp;- '.$presentArray[$k].' <br/> Leave &nbsp;&nbsp; - ' . $leaveArray[$k] . ' <br/> Absent &nbsp; - ' . $absentArray[$k] . ' <br/> Holiday - ' . $holidayArray[$k] . '">' . $monthName . '</div></th>';
            }
        }
        $ckhSql = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '" . $this->session->userdata('user_id') . "' AND `date` = '" . date("Y-m-d") . "'";
        $chkRes = $this->db->query($ckhSql);
        $chkNum = count($chkRes->result_array());
		
        // Get Reporting Manager
        $repMgrSql  = "SELECT i.department, i.reporting_to, u.full_name FROM `internal_user` i LEFT JOIN `internal_user` u ON i.reporting_to = u.login_id  WHERE i.login_id = '" . $this->session->userdata('user_id') . "'";
        $repMgrInfo = $this->db->query($repMgrSql)->result_array();
        //$repMgrRes = $this->db->query($repMgrSql)->result_array();
        //$repMgrInfo = mysql_fetch_assoc($repMgrRes);
		
		$user_id = $this->session->userdata('user_id');
		$chkDuplicateSQL = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '".$user_id."' AND `date` = '".date("Y-m-d")."' AND `out_time` = '00:00:00' LIMIT 1";
        $chkDuplicateRES = $this->db->query($chkDuplicateSQL);
        $chkDuplicateRow = $chkDuplicateRES->result_array();
        if(count($chkDuplicateRow) > 0){
            $this->session->set_userdata('showAttendanceBoxOutTime', 'YES');
        }
		else{
			$this->session->set_userdata('showAttendanceBoxOutTime', 'NO');
		}
		
		
        //Template view
        $this->render('timesheet/attendance_view', 'full_width', $this->mViewData); 
		$this->load->view('script/timesheet/timesheet_custom_js');
    }

	public function punch_attendance_out()
	{
		
		$user_id = $this->session->userdata('user_id');
		$chkDuplicateSQL = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '".$user_id."' AND `date` = '".date("Y-m-d")."' LIMIT 1";
        $chkDuplicateRES = $this->db->query($chkDuplicateSQL);
        $chkDuplicateRow = $chkDuplicateRES->result_array();
        if(count($chkDuplicateRow) > 0){
           $this->db->query("UPDATE `attendance_new` SET `out_time` = '".date("H:i:s")."' WHERE `login_id` = '".$user_id."' AND `date` = '".date("Y-m-d")."'");
            $this->session->set_userdata('showAttendanceBoxOutTime', 'NO');
        }
		else{
			$this->session->set_userdata('showAttendanceBoxOutTime', 'YES');
		}
		
	} 
    public function attendance()
    {
        $this->mViewData['pageTitle'] = 'Attendance';
        $year                    = date("Y");
        if ($this->input->post('dd_year') != '') {
            $year = $this->input->post('dd_year');
        }
        $monthNames = array(
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        );
        $dayNames   = array(
            'Sun',
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri',
            'Sat'
        );
        // Get Employee Type
        $empTypeQry = "SELECT `emp_type`,join_date FROM  `internal_user` WHERE `login_id` = '" . $this->session->userdata('user_id') . "'";
        $empTypeRes = $this->db->query($empTypeQry)->result_array();
        //var_dump($empTypeRes);exit;
        $empTypeNum = count($empTypeRes);
        if ($empTypeNum > 0) {
            foreach ($empTypeRes as $empTypeInfo) {
                $Type     = $empTypeInfo['emp_type'];
                //var_dump($Type);exit;
                $joindate = date("Y-m-d", strtotime($empTypeInfo['join_date']));
            }
        }
        // Get All Declared Holidays
        $declaredHolidayArray[] = '';
        $holidaySql             = "SELECT `dt_event_date`, `s_event_name` FROM `declared_leave` WHERE (`branch`='0' OR `branch`='" . $this->session->userdata('branch') . "') AND `f_disp_flag` = 1 AND DATE_FORMAT(`dt_event_date`, '%Y') = $year";
        $holidayRes             = $this->db->query($holidaySql)->result_array();
        //var_dump($holidayRes);exit;
        $contdate               = 1;
        foreach ($holidayRes as $holidayInfo) {
            $keyname = $holidayInfo['s_event_name'];
            $contdate++;
            if (($keyname == 'Sunday') OR ($keyname == 'First Saturday') OR ($keyname == 'Third Saturday')) {
                $keyname = $holidayInfo['s_event_name'] . $contdate;
            }
            $declaredHolidayArray[$keyname] = $holidayInfo['dt_event_date'];
            //var_dump($declaredHolidayArray[$keyname]);exit;
        }
        // Get All Present, Leave & Regularize days Info
        $attendanceArray[] = $attendanceStatusArray[] = $attendanceInArray[] = $attendanceOutArray[] = $attendanceReasonArray[] = '';
        // $attendanceArray = array();
        // $attendanceStatusArray = array();
        // $attendanceInArray = array();
        // $attendanceOutArray = array();
        // $attendanceReasonArray = array();
        $attendanceSql     = "SELECT `date`, `att_status`, `in_time`, `out_time`, `reason` FROM `attendance_new` WHERE `login_id` = '" . $this->session->userdata('user_id') . "' AND DATE_FORMAT(`date`, '%Y') = $year";
        $attendanceRes     = $this->db->query($attendanceSql)->result_array();
        //var_dump($attendanceInfo);exit;
        //$attendanceRes = mysql_query($attendanceSql);
        // while($attendanceInfo)
        // {
        // $attendanceArray[] = $attendanceInfo['date'];
        // $attendanceStatusArray[] = $attendanceInfo['att_status'];
        // $attendanceInArray[] = $attendanceInfo['in_time'];
        // $attendanceOutArray[] = $attendanceInfo['out_time'];
        // $attendanceReasonArray[] = $attendanceInfo['reason'];
        // }
        foreach ($attendanceRes as $attendanceInfo) {
            $attendanceArray[]       = $attendanceInfo['date'];
            $attendanceStatusArray[] = $attendanceInfo['att_status'];
            $attendanceInArray[]     = $attendanceInfo['in_time'];
            $attendanceOutArray[]    = $attendanceInfo['out_time'];
            $attendanceReasonArray[] = $attendanceInfo['reason'];
        }
        for ($k = 0; $k < 13; $k++) {
            $present = $leave = $absent = $holiday = 0;
            $date    = 0;
            if ($k != 0) {
                $days     = cal_days_in_month(CAL_GREGORIAN, $k, $year);
                $firstDay = date("N", strtotime($k . '/01/' . $year));
                $firstDay = ($firstDay == 7) ? 0 : $firstDay;
            }
            for($d = 0; $d < 37; $d++)
			{
                if($k == 0)
				{
                    if(($d % 7 == 0))
					{
                        $dayClass = 'sunday';
                        //$calrow_result = $calRow[$d];
                        $this->mViewData['calRow'][$d] = '<td><div class="sunday">' . $dayNames[$d % 7] . '</div></td>';
                    }
					elseif(($d % 6 == 0) OR ($d % 20 == 0))
					{
                        $dayClass = 'saturday';
                        $this->mViewData['calRow'][$d] = '<td><div class="sunday">' . $dayNames[$d % 7] . '</div></td>';
                    }
					else
					{
                        $dayClass = 'otherday';
                        $this->mViewData['calRow'][$d] = '<td><div class="' . $dayClass . '">' . $dayNames[$d % 7] . '</div></td>';
                    }
                }
				else
				{
                    if($d >= $firstDay && $date < $days)
					{
                        $date++;
                        $kool = date("Y-m-d", strtotime($date . '-' . $k . '-' . $year));
                        if($holidayFor = array_search($kool, $declaredHolidayArray))
						{
                            if(strpos($holidayFor, "Sunday") !== false)
							{
                                $holidayFor = "Sunday";
                            }
							elseif(strpos($holidayFor, "First Saturday") !== false)
							{
                                $holidayFor = "First Saturday";
                            }
							elseif (strpos($holidayFor, "Third Saturday") !== false)
							{
                                $holidayFor = "Third Saturday";
                            }
                            $holiday++;
                            $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip holidays" data-toggle="tooltip" title="' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . ' - ' . $holidayFor . '"><div class="black_dates" class="top">' . $date . '</div></div></td>';
                        }
                        /*
                        else if(date("N", strtotime($k.'/'.$date.'/'.$year)) == 7){
                        $holiday++;
                        $calRow[$d] .= '
                        <td>
                        <div class="iCompassTip holidays" title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span> <br/> Sunday">
                        <div class="black_dates">'.$date.'</div>
                        </div>
                        </td>
                        ';
                        }
                        else if( ( $kool >= NEW_SATURDAY_LEAVE) && (date("N", strtotime($k.'/'.$date.'/'.$year)) == 6) &&(($d==6) || ($d==20)) ){
                        if($d==6){$type = 'First';}else{$type = 'Third';}
                        $holiday++;
                        $calRow[$d] .= '
                        <td>
                        <div class="iCompassTip holidays" title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span> <br/> '.$type.' Saturday">
                        <div class="black_dates">'.$date.'</div>
                        </div>
                        </td>
                        ';
                        }*/
                        else {
                            if((NEW_ATTENDANCE_START <= $kool) && ($joindate <= $kool))
							{
                                if($attVic = array_search($kool, $attendanceArray))
								{
                                    if($attendanceStatusArray[$attVic] == 'P')
									{
                                        $present++;
                                        $outTime = '';
                                        $inTime  = date("g:i A", strtotime($attendanceInArray[$attVic]));
                                        if($attendanceOutArray[$attVic] != '00:00:00')
										{
                                            $outTime = date("g:i A", strtotime($attendanceOutArray[$attVic]));
                                        }
                                        $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip attend_day" data-toggle="tooltip" title="' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '- Present (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
									elseif($attendanceStatusArray[$attVic] == 'W')
									{
                                        $absent++;
                                        $outTime = '';
                                        $inTime  = date("g:i A", strtotime($attendanceInArray[$attVic]));
                                        if($attendanceOutArray[$attVic] != '00:00:00')
										{
                                            $outTime = date("g:i A", strtotime($attendanceOutArray[$attVic]));
                                        }
                                        $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip lessworking_day" data-toggle="tooltip" title="' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '- LW Hours (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
									elseif($attendanceStatusArray[$attVic] == 'H')
									{
                                        // get Which Half
                                        $whichHalf = $this->getWhichHalfLeave($this->session->userdata('user_id'), date("Y-m-d", strtotime($date . '-' . $k . '-' . $year)));
                                        $leave = $leave + 0.5;
                                        $hType = 'L';
                                        if(date("Y-m-d") >= $kool)
										{
                                            if($attendanceInArray[$attVic] != '00:00:00')
											{
                                                $present = $present + 0.5;
                                                $hType   = 'P';
                                            }
											else
											{
                                                $absent = $absent + 0.5;
                                                $hType  = 'A';
                                            }
                                        }
                                        if($whichHalf == 'F')
										{
                                            if($hType == 'P')
											{
                                                $bgClass = 'half_present_1';
                                            }
											elseif($hType == 'A')
											{
                                                $bgClass = 'half_absent_1';
                                            }
											else
											{
                                                $bgClass = 'half_leave_1';
                                            }
                                        }
										else
										{
                                            if($hType == 'P')
											{
                                                $bgClass = 'half_present_2';
                                            }
											elseif($hType == 'A')
											{
                                                $bgClass = 'half_absent_2';
                                            }
											else
											{
                                                $bgClass = 'half_leave_2';
                                            }
                                        }
                                        $outTime = '';
                                        $inTime  = '';
                                        if($attendanceInArray[$attVic] != '00:00:00')
										{
                                            $inTime = date("g:i A", strtotime($attendanceInArray[$attVic]));
                                        }
                                        if($attendanceOutArray[$attVic] != '00:00:00')
										{
                                            $outTime = date("g:i A", strtotime($attendanceOutArray[$attVic]));
                                        }
                                        $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip ' . $bgClass . '" data-toggle="tooltip" title="' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '-' . $attendanceReasonArray[$attVic] . ' (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
									elseif($attendanceStatusArray[$attVic] == 'L')
									{
                                        $leave++;
                                        $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip leave_day" data-toggle="tooltip" title="' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '- ' . $attendanceReasonArray[$attVic] . '"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
									elseif($attendanceStatusArray[$attVic] == 'R')
									{
                                        $present++;
                                        $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip regularize_day" data-toggle="tooltip" title="' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '-' . $attendanceReasonArray[$attVic] . '"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
                                }
								else
								{
                                    if(date("Y-m-d") < $kool)
									{
                                        $this->mViewData['calRow'][$d] .= '<td><div class="black_dates">' . $date . '</div></td>';
                                    }
									else
									{
                                        $absent++;
                                        $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip absent_day" data-toggle="tooltip" title="' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '- Absent"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
                                }
                            }
							else{
                                $this->mViewData['calRow'][$d] .= '<td><div class="black_dates">' . $date . '</div></td>';
                            }
                        }
                    }
					else{
                        $this->mViewData['calRow'][$d] .= '<td>&nbsp;</td>';
                    }
                }
            }
            if ($k != 0)
			{
                $v                 = $k - 1;
                $presentArray[$v]  = $present;
                $absentArray[$v]   = $absent;
                $leaveArray[$v]    = $leave;
                $holidayArray[$v]  = $holiday;
                $totaldayArray[$v] = $days;
            }
        }
        $this->mViewData['monthHeader'] = '';
        for ($k = 0; $k < 12; $k++)
		{
            $monthName = $monthNames[$k];
            $payDays   = $presentArray[$k] + $holidayArray[$k];
            if ($totaldayArray[$k] == $payDays)
			{
                $this->mViewData['monthHeader'] .= '<th><div class="iCompassTip img" data-toggle="tooltip" title="Present - ' . $presentArray[$k] . '&nbsp;&nbsp;&nbsp; Leave - ' . $leaveArray[$k] . '&nbsp;&nbsp;&nbsp; Absent - ' . $absentArray[$k] . ' &nbsp;&nbsp;&nbsp; Holiday - ' . $holidayArray[$k] . '">' . $monthName . '</div></th>';
            }
			else
			{
                $this->mViewData['monthHeader'] .= '<th><div class="iCompassTip" data-toggle="tooltip" title="Present - ' . $presentArray[$k] . ' &nbsp;&nbsp;&nbsp; Leave - ' . $leaveArray[$k] . '&nbsp;&nbsp;&nbsp; Absent - ' . $absentArray[$k] . '&nbsp;&nbsp;&nbsp; Holiday - ' . $holidayArray[$k] . '">' . $monthName . '</div></th>';
            }
        }
        $ckhSql = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '" . $this->session->userdata('user_id') . "' AND `date` = '" . date("Y-m-d") . "'";
        $chkRes = $this->db->query($ckhSql);
        $chkNum = count($chkRes->result_array());
        // Get Reporting Manager
        $repMgrSql  = "SELECT i.department, i.reporting_to, u.full_name FROM `internal_user` i LEFT JOIN `internal_user` u ON i.reporting_to = u.login_id  WHERE i.login_id = '" . $this->session->userdata('user_id') . "'";
        $repMgrInfo = $this->db->query($repMgrSql)->result_array();
        //$repMgrRes = $this->db->query($repMgrSql)->result_array();
        //$repMgrInfo = mysql_fetch_assoc($repMgrRes);
        //Template view
		$this->render('timesheet/attendance_view', 'full_width', $this->mViewData);
		$this->load->view('script/timesheet/timesheet_custom_js');
       
    }
    public function getWhichHalfLeave($userID, $date)
    {
        $half      = 'F';
        $halfSQL   = "SELECT `application_id`, `leavefromhalfday`, `leavetohalfday`, `leave_from`, `leave_to` FROM `leave_application` WHERE status='A' AND `user_id` = '" . $userID . "' AND (`leave_from` = '$date' OR `leave_to` = '$date')";
        $half_info = $this->db->query($halfSQL, array($userID,$date))->result_array();
        $half_num  = count($half_info);
        if($half_num > 0)
		{
            if($half_info[0]['leavefromhalfday'] == 'Y' && $half_info[0]['leave_from'] == $date)
			{
                $half = 'F';
            }
			else if($half_info[0]['leavetohalfday'] == 'Y' && $half_info[0]['leave_to'] == $date)
			{
                $half = 'S';
            }
        }
        return $half;
    }
	
	
    public function monthly()
    {
        $this->mViewData['pageTitle'] = 'monthly';
        $year = date("Y");
        $month = date("m");
        $yMonth = date("Y-m");
        $monthNames = array(
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        );
        $dayNames                = array(
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri',
            'Sat',
            'Sun'
        );
        //Get Employee Type
        $empTypeQry              = "SELECT `emp_type` FROM  `internal_user` WHERE `login_id` = '" . $this->session->userdata('user_id') . "'";
        $empTyperes              = $this->db->query($empTypeQry)->result_array();
        //var_dump($empTyperes);
        $empTypeNum = count($empTyperes);
        if($empTypeNum > 0)
		{
            foreach ($empTyperes as $empTypeInfo)
			{
                $Type = $empTypeInfo['emp_type'];
            }
        }
        // Get All Declared Holidays
        $declared_holiday_array[] = '';
        $holidaySql               = "SELECT `dt_event_date`, `s_event_name` FROM `declared_leave` WHERE `f_disp_flag` = 1 AND DATE_FORMAT(`dt_event_date`, '%Y-%m') = '$yMonth'";
        $holiday_res = $this->db->query($holidaySql)->result_array();
        //var_dump($holidayRes);
        $contdate                 = 1;
        foreach($holiday_res as $holiday_info)
		{
            $keyname = $holiday_info['s_event_name'];
            $contdate++;
            if(($keyname == 'Sunday') OR ($keyname == 'First Saturday') OR ($keyname == 'Third Saturday'))
			{
                $keyname = $holiday_info['s_event_name'] . $contdate;
            }
            $declared_holiday_array[$keyname] = $holiday_info['dt_event_date'];
        }
        // Get All Present, Leave & Regularize days Info
        $attendanceArray[] = $attendanceStatusArray[] = $attendanceInArray[] = $attendanceOutArray[] = $attendanceReasonArray[] = $attendanceLeaveTypeArray[] = '';
        $attendanceSql     = "SELECT `date`, `att_status`, `in_time`, `out_time`, `reason`, `leave_type` FROM `attendance_new` WHERE `login_id` = '" . $this->session->userdata('user_id') . "' AND DATE_FORMAT(`date`, '%Y-%m') = '$yMonth'";
        $attendanceRes     = $this->db->query($attendanceSql)->result_array();
        //var_dump($attendanceRes);
        foreach($attendanceRes as $attendanceInfo)
		{
            $attendanceArray[]       = $attendanceInfo['date'];
            $attendanceStatusArray[] = $attendanceInfo['att_status'];
			$attendanceLeaveTypeArray[] = $attendanceInfo['leave_type'];
            $attendanceInArray[]     = $attendanceInfo['in_time'];
            $attendanceOutArray[]    = $attendanceInfo['out_time'];
            $attendanceReasonArray[] = $attendanceInfo['reason'];
        }
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $firstDay = date("N", strtotime($month . '/01/' . $year));
        if($month == 1)
		{
            $monthPrev = 12;
            $yearPrev  = $year - 1;
        }
		else
		{
            $monthPrev = $month - 1;
            $yearPrev  = $year;
        }
        $daysPrev = cal_days_in_month(CAL_GREGORIAN, $monthPrev, $yearPrev);
        $date     = 0;
        $present  = $leave = $absent = $holiday = 0;
		$this->mViewData['monthBody'] = "";
        for($j = 1; $j < 43; $j++)
		{
            if($j % 7 == 1)
			{
                $this->mViewData['monthBody'] .= '<tr>';
            }
            $tdClass = 'workday';
            
            if($j%7==0){
            $tdClass = 'holidays';
            }
            if(($j%6 == 0) OR ($j%20 == 0))
            {
            $tdClass ='holidays';
            }
            if ($j < $firstDay)
			{
                $this->mViewData['monthBody'] .= '<td>&nbsp;</td>';
            } 
			elseif ($j >= $firstDay && $date < $days)
			{
                $date++;
                $kool = date("Y-m-d", strtotime($date . '-' . $month . '-' . $year));
                if($holiday_for = array_search($kool, $declared_holiday_array))
				{
                    $holiday++;
                    if(strpos($holiday_for, "Sunday") !== false)
					{
                        $holiday_for = "Sunday";
                    }
					elseif(strpos($holiday_for, "First Saturday") !== false)
					{
                        $holiday_for = "First Saturday";
                    }
					elseif(strpos($holiday_for, "Third Saturday") !== false)
					{
                        $holiday_for = "Third Saturday";
                    }
                    $this->mViewData['monthBody'] .= '<td><div class="iCompassTip holidays" title="<span>' . date("jS M, Y", strtotime($date . '-' . $month . '-' . $year)) . ' </span><br/> ' . $holiday_for . '"><div class="black_dates">' . $date . '</div></div></td>';
                }
                /*
                elseif(date("N", strtotime($month.'/'.$date.'/'.$year)) == 7)
                {
                $holiday++;
                $monthBody .= '
                <td>
                <div class="iCompassTip holidays" title="<span>'.date("jS M, Y", strtotime($date.'-'.$month.'-'.$year)).'</span> <br/> Sunday">
                <div class="black_dates">'.$date.'</div>
                </div>
                </td>
                ';
                }elseif( ( $kool >= NEW_SATURDAY_LEAVE) && (date("N", strtotime($month.'/'.$date.'/'.$year)) == 6) &&(($j==6) || ($j==20)))
                {
                if($j==6){$type = 'First';}else{$type = 'Third';}
                $holiday++;
                $this->mViewData['monthBody'] .= '
                <td>
                <div class="iCompassTip holidays" title="<span>'.date("jS M, Y", strtotime($date.'-'.$month.'-'.$year)).'</span> <br/> '.$type.'Saturday">
                <div class="black_dates">'.$date.'</div>
                </div>
                </td>
                ';
                }*/
                else
				{
                    if($attVic = array_search($kool, $attendanceArray))
					{
                        if ($attendanceStatusArray[$attVic] == 'P')
						{
                            $present++;
                            $outTime = '';
                            $inTime  = date("g:i:s A", strtotime($attendanceInArray[$attVic]));
                            if ($attendanceOutArray[$attVic] != '00:00:00')
							{
                                $outTime = date("g:i:s A", strtotime($attendanceOutArray[$attVic]));
                            }
                            $this->mViewData['monthBody'] .= '<td><div class="iCompassTip attend_day"  title="<span>' . date("jS M, Y", strtotime($kool)) . ' </span><br/> Present (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
                        }
						elseif
						($attendanceStatusArray[$attVic] == 'W')
						{
                            $present++;
                            $outTime = '';
                            $inTime  = date("g:i:s A", strtotime($attendanceInArray[$attVic]));
                            if ($attendanceOutArray[$attVic] != '00:00:00')
							{
                                $outTime = date("g:i:s A", strtotime($attendanceOutArray[$attVic]));
                            }
                            $this->mViewData['monthBody'] .= '<td><div class="iCompassTip lessworking_day"  title="<span>' . date("jS M, Y", strtotime($kool)) . '</span><br/> LW Hours (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
                        }
						elseif($attendanceStatusArray[$attVic] == 'H')
						{
                            // get Which Half
                            $whichHalf = $this->getWhichHalfLeave($this->session->userdata('user_id'), date("Y-m-d", strtotime($kool)));
                            $leave     = $leave + 0.5;
                            $hType     = 'L';
                            if(date("Y-m-d") >= $kool)
							{
                                if ($attendanceInArray[$attVic] != '00:00:00')
								{
                                    $present = $present + 0.5;
                                    $hType   = 'P';
                                }
								else
								{
                                    $absent = $absent + 0.5;
                                    $hType  = 'A';
                                }
                            }
                            if($whichHalf == 'F')
							{
                                if($hType == 'P')
								{
                                    $bgClass = 'half_present_1';
                                }
								elseif($hType == 'A')
								{
                                    $bgClass = 'half_absent_1';
                                }
								else
								{
                                    $bgClass = 'half_leave_1';
                                }
                            }
							else
							{
                                if($hType == 'P')
								{
                                    $bgClass = 'half_present_2';
                                }
								elseif ($hType == 'A')
								{
                                    $bgClass = 'half_absent_2';
                                }
								else
								{
                                    $bgClass = 'half_leave_2';
                                }
                            }
                            $outTime = '';
                            $inTime  = '';
                            if ($attendanceInArray[$attVic] != '00:00:00')
							{
                                $inTime = date("g:i:s A", strtotime($attendanceInArray[$attVic]));
                            }
                            if ($attendanceOutArray[$attVic] != '00:00:00')
							{
                                $outTime = date("g:i:s A", strtotime($attendanceOutArray[$attVic]));
                            }
                            $this->mViewData['monthBody'] .= '<td><div class="iCompassTip ' . $bgClass . '"  title="<span>' . date("jS M, Y", strtotime($kool)) . ' </span><br/> ' . $attendanceReasonArray[$attVic] . ' (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
                        }
						elseif($attendanceStatusArray[$attVic] == 'L')
						{
							$leave++;
							if($attendanceLeaveTypeArray[$attVic] == 'M')
							{								
								$this->mViewData['monthBody'] .= '<td><div class="iCompassTip leave_day_ml"  title="<span>' . date("jS M, Y", strtotime($kool)) . ' </span><br/> ' . $attendanceReasonArray[$attVic] . '"><div class="white_dates">' . $date . '</div></div></td>';
							}
							else{								
								$this->mViewData['monthBody'] .= '<td><div class="iCompassTip leave_day"  title="<span>' . date("jS M, Y", strtotime($kool)) . ' </span><br/> ' . $attendanceReasonArray[$attVic] . '"><div class="white_dates">' . $date . '</div></div></td>';
							}                         
                        }
						elseif($attendanceStatusArray[$attVic] == 'R')
						{
                            $present++;
                            $this->mViewData['monthBody'] .= '<td><div class="iCompassTip regularize_day"  title="<span>' . date("jS M, Y", strtotime($kool)) . ' </span><br/> ' . $attendanceReasonArray[$attVic] . '"><div class="white_dates">' . $date . '</div></div></td>';
                        }
                    }
					else
					{
                        if(date("Y-m-d") < $kool)
						{
                            $this->mViewData['monthBody'] .= '<td><div class="black_dates">' . $date . '</div></td>';
                        }
						else
						{
                            $absent++;
                            $this->mViewData['monthBody'] .= '<td><div class="iCompassTip absent_day"  title="<span>' . date("jS M, Y", strtotime($kool)) . ' </span><br/> Absent"><div class="white_dates">' . $date . '</div></div></td>';
                        }
                    }
                }
            }
			else
			{
                $this->mViewData['monthBody'] .= '<td>&nbsp;</td>';
            }
            if($j % 7 == 0)
			{
                $this->mViewData['monthBody'] .= '</tr>';
            }
        }
        $this->mViewData['weekHeader'] = '<tr>';
        for($d = 0; $d < 7; $d++)
		{
            $dayClass = ($d % 7 == 6) ? 'sunday' : 'otherday';
            $this->mViewData['weekHeader'] .= '<td><div class="' . $dayClass . '">' . $dayNames[$d] . '</div></td>';
        }
        $this->mViewData['weekHeader'] .= '</tr>';
        $ckhSql = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '" . $this->session->userdata('user_id') . "' AND `date` = '" . date("Y-m-d") . "'";
        $chkRes = $this->db->query($ckhSql);
        $chkNum = count($chkRes->result_array());
		
        // Get Reporting Manager
        $repMgrSql = "SELECT i.department, i.reporting_to, u.full_name FROM `internal_user` i LEFT JOIN `internal_user` u ON i.reporting_to = u.login_id  WHERE i.login_id = '" . $this->session->userdata('user_id') . "'";
        $repMgrInfo = $this->db->query($repMgrSql)->result_array();
        //$repMgrInfo = mysql_fetch_assoc($repMgrRes);
		
        //Template view
		$this->render('timesheet/monthly_view', 'full_width', $this->mViewData); 
    }
	
	
    public function yearly()
    {
        $this->mViewData['pageTitle'] = 'Time Sheet';
        $year      = date("Y");
        if($this->input->post('dd_year') != '')
		{
            $year = $this->input->post('dd_year');
        }
        $monthNames = array(
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        );
        $dayNames   = array(
            'Sun',
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri',
            'Sat'
        );
		
        // Get Employee Type
        $empTypeQry = "SELECT `emp_type`,join_date FROM  `internal_user` WHERE `login_id` = '" . $this->session->userdata('user_id') . "'";
        $empTypeRes = $this->db->query($empTypeQry)->result_array();
        //var_dump($empTypeRes);exit;
		
        $empTypeNum = count($empTypeRes);
        if ($empTypeNum > 0)
		{
            foreach ($empTypeRes as $empTypeInfo)
			{
                $Type = $empTypeInfo['emp_type'];
                //var_dump($Type);exit;
                $joindate = date("Y-m-d", strtotime($empTypeInfo['join_date']));
				//echo $joindate;exit;
            }
        }
        // Get All Declared Holidays
        $declaredHolidayArray[] = '';
        $holidaySql             = "SELECT `dt_event_date`, `s_event_name` FROM `declared_leave` WHERE (`branch`='0' OR `branch`='" . $this->session->userdata('branch') . "') AND `f_disp_flag` = 1 AND DATE_FORMAT(`dt_event_date`, '%Y') = $year";
        $holidayRes             = $this->db->query($holidaySql)->result_array();
        //var_dump($holidayRes);exit;
        $contdate               = 1;
        foreach ($holidayRes as $holidayInfo)
		{
            $keyname = $holidayInfo['s_event_name'];
            $contdate++;
            if(($keyname == 'Sunday') OR ($keyname == 'First Saturday') OR ($keyname == 'Third Saturday'))
			{
                $keyname = $holidayInfo['s_event_name'] . $contdate;
            }
            $declaredHolidayArray[$keyname] = $holidayInfo['dt_event_date'];
            //var_dump($declaredHolidayArray[$keyname]);exit;
        }
        // Get All Present, Leave & Regularize days Info
        $attendanceArray[] = $attendanceStatusArray[] = $attendanceInArray[] = $attendanceOutArray[] = $attendanceLeaveTypeArray[] =$attendanceReasonArray[] = '';
        // $attendanceArray = array();
        // $attendanceStatusArray = array();
        // $attendanceInArray = array();
        // $attendanceOutArray = array();
        // $attendanceReasonArray = array();
        $attendanceSql     = "SELECT `date`, `att_status`, `in_time`, `out_time`, `reason`, `leave_type` FROM `attendance_new` WHERE `login_id` = '" . $this->session->userdata('user_id') . "' AND DATE_FORMAT(`date`, '%Y') = $year";
        $attendanceRes     = $this->db->query($attendanceSql)->result_array();
        //var_dump($attendanceInfo);exit;
        //$attendanceRes = mysql_query($attendanceSql);
        // while($attendanceInfo)
        // {
        // $attendanceArray[] = $attendanceInfo['date'];
        // $attendanceStatusArray[] = $attendanceInfo['att_status'];
        // $attendanceInArray[] = $attendanceInfo['in_time'];
        // $attendanceOutArray[] = $attendanceInfo['out_time'];
        // $attendanceReasonArray[] = $attendanceInfo['reason'];
        // }
        foreach ($attendanceRes as $attendanceInfo)
		{
            $attendanceArray[]       = $attendanceInfo['date'];
            $attendanceStatusArray[] = $attendanceInfo['att_status'];
			$attendanceLeaveTypeArray[] = $attendanceInfo['leave_type'];
            $attendanceInArray[]     = $attendanceInfo['in_time'];
            $attendanceOutArray[]    = $attendanceInfo['out_time'];
            $attendanceReasonArray[] = $attendanceInfo['reason'];
        }
        for($k = 0; $k < 13; $k++)
		{
            $present = $leave = $absent = $holiday = 0;
            $date    = 0;
            if ($k != 0)
			{
                $days     = cal_days_in_month(CAL_GREGORIAN, $k, $year);
                $firstDay = date("N", strtotime($k . '/01/' . $year));
                $firstDay = ($firstDay == 7) ? 0 : $firstDay;
            }
            for($d = 0; $d < 37; $d++)
			{
                if ($k == 0)
				{
                    if (($d % 7 == 0))
					{
                        $dayClass = 'sunday';
                        //$calrow_result = $calRow[$d];
                        $this->mViewData['calRow'][$d] = '<td style="width:8.1%"><div class="sunday">' . $dayNames[$d % 7] . '</div></td>';
                    }
					elseif(($d % 6 == 0) OR ($d % 20 == 0))
					{
                        $dayClass = 'saturday';
                        $this->mViewData['calRow'][$d] = '<td><div class="sunday">' . $dayNames[$d % 7] . '</div></td>';
                    }
					else
					{
                        $dayClass = 'otherday';
                        $this->mViewData['calRow'][$d] = '<td><div class="' . $dayClass . '">' . $dayNames[$d % 7] . '</div></td>';
                    }
                }
				else
				{
                    if ($d >= $firstDay && $date < $days)
					{
                        $date++;
                        $kool = date("Y-m-d", strtotime($date . '-' . $k . '-' . $year));
                        if ($holidayFor = array_search($kool, $declaredHolidayArray))
						{
                            if (strpos($holidayFor, "Sunday") !== false)
							{
                                $holidayFor = "Sunday";
                            }
							elseif (strpos($holidayFor, "First Saturday") !== false)
							{
                                $holidayFor = "First Saturday";
                            }
							elseif (strpos($holidayFor, "Third Saturday") !== false)
							{
                                $holidayFor = "Third Saturday";
                            }
                            $holiday++;
                            $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip holidays"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . ' </span><br/> ' . $holidayFor . '"><div class="black_dates" class="top">' . $date . '</div></div></td>';
                        }
                        /*
                        else if(date("N", strtotime($k.'/'.$date.'/'.$year)) == 7){
                        $holiday++;
                        $calRow[$d] .= '
                        <td>
                        <div class="iCompassTip holidays" title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span> <br/> Sunday">
                        <div class="black_dates">'.$date.'</div>
                        </div>
                        </td>
                        ';
                        }
                        else if( ( $kool >= NEW_SATURDAY_LEAVE) && (date("N", strtotime($k.'/'.$date.'/'.$year)) == 6) &&(($d==6) || ($d==20)) ){
                        if($d==6){$type = 'First';}else{$type = 'Third';}
                        $holiday++;
                        $calRow[$d] .= '
                        <td>
                        <div class="iCompassTip holidays" title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span> <br/> '.$type.' Saturday">
                        <div class="black_dates">'.$date.'</div>
                        </div>
                        </td>
                        ';
                        }*/
                        else{
                            if((NEW_ATTENDANCE_START <= $kool) && ($joindate <= $kool))
							{
                                if($attVic = array_search($kool, $attendanceArray))
								{
                                    if($attendanceStatusArray[$attVic] == 'P')
									{
                                        $present++;
                                        $outTime = '';
                                        $inTime  = date("g:i:s A", strtotime($attendanceInArray[$attVic]));
                                        if($attendanceOutArray[$attVic] != '00:00:00')
										{
                                            $outTime = date("g:i:s A", strtotime($attendanceOutArray[$attVic]));
                                        }
                                        $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip attend_day"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '</span><br/> Present (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
									elseif($attendanceStatusArray[$attVic] == 'W')
									{
                                        $absent++;
                                        $outTime = '';
                                        $inTime  = date("g:i:s A", strtotime($attendanceInArray[$attVic]));
                                        if ($attendanceOutArray[$attVic] != '00:00:00')
										{
                                            $outTime = date("g:i:s A", strtotime($attendanceOutArray[$attVic]));
                                        }
                                        $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip lessworking_day"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '</span><br/> LW Hours (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
									elseif($attendanceStatusArray[$attVic] == 'H')
									{
                                        // get Which Half
                                        $whichHalf = $this->getWhichHalfLeave($this->session->userdata('user_id'), date("Y-m-d", strtotime($date . '-' . $k . '-' . $year)));
                                        $leave = $leave + 0.5;
                                        $hType = 'L';
                                        if(date("Y-m-d") >= $kool)
										{
                                            if($attendanceInArray[$attVic] != '00:00:00')
											{
                                                $present = $present + 0.5;
                                                $hType   = 'P';
                                            }
											else
											{
                                                $absent = $absent + 0.5;
                                                $hType  = 'A';
                                            }
                                        }
                                        if($whichHalf == 'F')
										{
                                            if($hType == 'P')
											{
                                                $bgClass = 'half_present_1';
                                            }
											elseif($hType == 'A')
											{
                                                $bgClass = 'half_absent_1';
                                            }
											else
											{
                                                $bgClass = 'half_leave_1';
                                            }
                                        }
										else
										{
                                            if($hType == 'P')
											{
                                                $bgClass = 'half_present_2';
                                            }
											elseif($hType == 'A')
											{
                                                $bgClass = 'half_absent_2';
                                            }
											else
											{
                                                $bgClass = 'half_leave_2';
                                            }
                                        }
                                        $outTime = '';
                                        $inTime  = '';
                                        if($attendanceInArray[$attVic] != '00:00:00')
										{
                                            $inTime = date("g:i:s A", strtotime($attendanceInArray[$attVic]));
                                        }
                                        if($attendanceOutArray[$attVic] != '00:00:00')
										{
                                            $outTime = date("g:i:s A", strtotime($attendanceOutArray[$attVic]));
                                        }
                                        $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip ' . $bgClass . '"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . ' </span><br/> ' . $attendanceReasonArray[$attVic] . ' (' . $inTime . ' - ' . $outTime . ')"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
									elseif($attendanceStatusArray[$attVic] == 'L')
									{
                                        $leave++;
										if($attendanceLeaveTypeArray[$attVic] == "M")
										{
										    $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip leave_day_ml"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '</span><br/> ' . $attendanceReasonArray[$attVic] . '"><div class="white_dates">' . $date . '</div></div></td>';		
										}
										else{
											$this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip leave_day"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '</span><br/> ' . $attendanceReasonArray[$attVic] . '"><div class="white_dates">' . $date . '</div></div></td>';
										}
 
                                    }
									elseif($attendanceStatusArray[$attVic] == 'R')
									{
                                        $present++;
                                        $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip regularize_day"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . ' </span><br/> ' . $attendanceReasonArray[$attVic] . '"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
                                }
								else
								{
                                    if(date("Y-m-d") < $kool)
									{
                                        $this->mViewData['calRow'][$d] .= '<td><div class="black_dates">' . $date . '</div></td>';
                                    }
									else
									{
                                        $absent++;
                                        $this->mViewData['calRow'][$d] .= '<td><div class="iCompassTip absent_day"  title="<span>' . date("jS M, Y", strtotime($date . '-' . $k . '-' . $year)) . '</span><br/> Absent"><div class="white_dates">' . $date . '</div></div></td>';
                                    }
                                }
                            }
							else
							{
                                $this->mViewData['calRow'][$d] .= '<td><div class="black_dates">' . $date . '</div></td>';
                            }
                        }
                    }
					else
					{
                        $this->mViewData['calRow'][$d] .= '<td>&nbsp;</td>';
                    }
                }
            }
            if($k != 0)
			{
                $v                 = $k - 1;
                $presentArray[$v]  = $present;
                $absentArray[$v]   = $absent;
                $leaveArray[$v]    = $leave;
                $holidayArray[$v]  = $holiday;
                $totaldayArray[$v] = $days;
            }
        }
        $this->mViewData['monthHeader'] = '';
        for($k = 0; $k < 12; $k++)
		{
            $monthName = $monthNames[$k];
            $payDays   = $presentArray[$k] + $holidayArray[$k];
            if($totaldayArray[$k] == $payDays)
			{
                $this->mViewData['monthHeader'] .= ' <th><div class="iCompassTip img"  title="Present - ' . $presentArray[$k] . '<br/> Leave - ' . $leaveArray[$k] . '<br/> Absent - ' . $absentArray[$k] . '<br/> Holiday - ' . $holidayArray[$k] . '">' . $monthName . '</div></th>';
            }
			else
			{
                $this->mViewData['monthHeader'] .= '<th><div class="iCompassTip"  title="Present - ' . $presentArray[$k] . '<br/>
														Leave - ' . $leaveArray[$k] . '<br/>
														Absent &nbsp; - ' . $absentArray[$k] . '<br/>
														Holiday - ' . $holidayArray[$k] . '">' . $monthName . '</div></th>';
            }
        }
        $ckhSql = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '" . $this->session->userdata('user_id') . "' AND `date` = '" . date("Y-m-d") . "'";
        $chkRes = $this->db->query($ckhSql);
        $chkNum = count($chkRes->result_array());
		
        // Get Reporting Manager
        $repMgrSql  = "SELECT i.department, i.reporting_to, u.full_name FROM `internal_user` i LEFT JOIN `internal_user` u ON i.reporting_to = u.login_id  WHERE i.login_id = '" . $this->session->userdata('user_id') . "'";
        $repMgrInfo = $this->db->query($repMgrSql)->result_array();
        //$repMgrRes = $this->db->query($repMgrSql)->result_array();
        //$repMgrInfo = mysql_fetch_assoc($repMgrRes);
        //Template view
        $this->render('timesheet/attendance_view', 'full_width', $this->mViewData);
		$this->load->view('script/timesheet/timesheet_custom_js');
    }
	
	
    //Start regularise
    public function apply_for_regularise()
    {
        $this->mViewData['pageTitle'] = 'Apply for regularise';
		$loginID = $this->session->userdata('user_id');
        $this->mViewData['reporting_manager'] = $this->timesheet_model->get_reporting_manager();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('from_date', 'from date', 'required');
        $this->form_validation->set_rules('to_date', 'to date', 'required');
        $this->form_validation->set_rules('txtReason', 'reason', 'trim|required');
		
        if($this->input->post('btnApplyLeave') == 'Apply'){
			if($this->form_validation->run($this) === FALSE)
			{
				$this->render('timesheet/apply_for_regularise_view', 'full_width', $this->mViewData);
				$this->load->view('script/timesheet/datepicker_js');
			}
			else
			{
			$reporting_to = $this->input->post('reportingTo');
			$from_date    = date("Y-m-d", strtotime($this->input->post('from_date')));
			$to_date      = date("Y-m-d", strtotime($this->input->post('to_date')));
			$reason       = $this->input->post('txtReason');
			
			$chkSql = "SELECT leave_type FROM `attendance_request` WHERE user_id = '".$loginID."' AND DATE(from_date) = '".$from_date."' AND (status ='A' OR status='P')";
			$chkRes = $this->db->query($chkSql);
			$chkInfo = $chkRes->result_array();
			//echo count($chkInfo);exit;
			if(count($chkInfo) == 0){
			if($this->timesheet_model->insert_apply_for_regularise($reporting_to, $from_date, $to_date, $reason))
			{ 
		
			$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			
			$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
		
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Attendance Regularization Request For Approval - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('timesheet/regularise_request');
			
			$message=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear {$repInfo[0]['full_name']},</p>                                 
                 <p>{$empInfo[0]['full_name']} has applied for Regularization Attendance. </p>  
				 <p>Regularization reason - <strong>{$reason}</strong> </p>
				<p>Regularization from - <strong>{$from_date}</strong> to <strong>{$to_date}</strong>.</p>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to Approve / Reject</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				$to =$repInfo[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
				$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				
				mail($to, $subject, $message, $headers); 
				redirect('/timesheet/my_regularise_application', 'refresh');	
				}
				else
				{
					$mViewData->error = 'There was a problem creating your new account. Please try again.';
					//template view
					$this->render('timesheet/apply_for_regularise_view', 'full_width', $this->mViewData);
					$this->load->view('script/timesheet/datepicker_js');
				}
			}
			}
		}
		else{
			$this->render('timesheet/apply_for_regularise_view', 'full_width', $this->mViewData);
			$this->load->view('script/timesheet/apply_for_regularization_js');
		}
    } 
	
	
    public function apply_for_regularise_check()
    {
		$loginID = $this->session->userdata('user_id');
		$branch = $this->session->userdata('branch');
		$reporting_to = $this->input->post('reportingTo');
		$from_date    = date("Y-m-d", strtotime($this->input->post('from_date')));
		$to_date      = date("Y-m-d", strtotime($this->input->post('to_date')));
		$reason       = $this->input->post('txtReason');
		
		$chkSql = "SELECT leave_type FROM `attendance_request` WHERE user_id = '".$loginID."' AND DATE(from_date) = '".$from_date."' AND (status ='A' OR status='P')";
		$chkRes = $this->db->query($chkSql);
		$chkInfo = $chkRes->result_array();
		$days=0;
		$data = array( 'status'=> 0, 'nodays'=>$days);
		//echo count($chkInfo);exit;
		if(count($chkInfo) == 0){
			$dateRangeArr = $this->date_range($from_date, $to_date);
			
			foreach($dateRangeArr AS $dt){
				$chkHoliDaySql = $this->db->query("SELECT `ix_declared_leave` FROM `declared_leave` WHERE (`branch`='0' OR `branch`='".$branch."') AND `f_disp_flag` = 1 AND `dt_event_date` = '".$dt."'   AND (`branch` = '0' OR `branch` = '".$this->session->userdata('branch')."')");
				$chkHoliDayRes = $chkHoliDaySql->result_array();
				$chkHoliDayNum = COUNT($chkHoliDayRes);
				if($chkHoliDayNum < 1){
					if(date('N', strtotime($dt)) != 7){
						$chkAttPSql = $this->db->query("SELECT `attendance_id`, `att_status` FROM `attendance_new` WHERE `login_id` = '".$loginID."' AND `date` = '$dt' LIMIT 1");
						$chkAttPRes = $chkAttPSql->result_array();
						
						$chkAttPNum = COUNT($chkAttPRes);
						if($chkAttPNum == 0){
							$days++;
						}
						else{
							if($chkAttPRes[0]['att_status'] == 'H'){
								$days++;
							} else if($chkAttPRes[0]['att_status'] == 'W' || $chkAttPRes[0]['att_status'] == 'P'){
								$days++;
							}
						}
					}
				}
			}
			$data = array( 'status'=> 1, 'nodays'=>$days);
		}
		else{
			$data = array( 'status'=> 0, 'nodays'=>$days);
		}
		echo json_encode($data);
    }
	
    public function apply_for_regularise_submit()
    {
		$loginID = $this->session->userdata('user_id');
		$reporting_to = $this->input->post('reportingTo');
		$from_date    = date("Y-m-d", strtotime($this->input->post('from_date')));
		$to_date      = date("Y-m-d", strtotime($this->input->post('to_date')));
		$reason       = $this->input->post('txtReason');
		$no_of_days       = $this->input->post('no_of_days');
		$reason_date       = $this->input->post('reason_date');
		$reason_time       = $this->input->post('reason_time');
		
		$chkSql = "SELECT leave_type FROM `attendance_request` WHERE user_id = '".$loginID."' AND DATE(from_date) = '".$from_date."' AND (status ='A' OR status='P')";
		$chkRes = $this->db->query($chkSql);
		$chkInfo = $chkRes->result_array();
		//echo count($chkInfo);exit;
		if(count($chkInfo) == 0){
			if($this->timesheet_model->insert_apply_for_regularise($reporting_to, $from_date, $to_date, $reason, $no_of_days, $reason_date, $reason_time))
			{ 
		
			$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			
			$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
		
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Attendance Regularization Request For Approval - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('timesheet/regularise_request');
			
			$message=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear {$repInfo[0]['full_name']},</p>                                 
                 <p>{$empInfo[0]['full_name']} has applied for Regularization Attendance. </p>  
				 <p>Regularization reason - <strong>{$reason}</strong> </p>
				<p>Regularization from - <strong>{$from_date}</strong> to <strong>{$to_date}</strong>.</p>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to Approve / Reject</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				$to =$repInfo[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
				$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				
				mail($to, $subject, $message, $headers); 
				//redirect('/timesheet/my_regularise_application', 'refresh');	
				echo 1;
			}
			else
			{
				$mViewData->error = 'There was a problem creating your new account. Please try again.';
				echo 2;
			}
		}
		else{
			echo 3;
		}
    }
	
	public function update_regularise_request_approved()
    {
		$user_id = $this->session->userdata('user_id');
		$branch = $this->session->userdata('branch');
		$application_id = $this->input->post('application_id');
        $chkRPSQL = $this->db->query("SELECT * FROM `attendance_request` WHERE `attd_req_id` = '".$application_id."' AND `status` = 'P' LIMIT 1");
		$getReqInfo = $chkRPSQL->result_array();
		$chkRPNUM = COUNT($getReqInfo);
		if($chkRPNUM > 0){
			/* $getReqSql = $this->db->query("SELECT * FROM `attendance_request` WHERE `attd_req_id` = '".$application_id."' AND `status` = 'A' LIMIT 1");
			$getReqInfo = $getReqSql->result_array(); */
			$dateRangeArr = date_range($getReqInfo[0]['from_date'], $getReqInfo[0]['to_date']);
			$reason = $getReqInfo[0]['reason'];
			$reason = str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($reason ) );
			
			$from_date = $getReqInfo[0]['from_date'];
			$to_date = $getReqInfo[0]['to_date'];
			foreach($dateRangeArr AS $dt){
				$chkHoliDaySql = $this->db->query("SELECT `ix_declared_leave` FROM `declared_leave` WHERE (`branch`='0' OR `branch`='".$branch."') AND `f_disp_flag` = 1 AND `dt_event_date` = '".$dt."'   AND (`branch` = '0' OR `branch` = '".$this->session->userdata('branch')."') ");
				$chkHoliDayRes = $chkHoliDaySql->result_array();
				$chkHoliDayNum = COUNT($chkHoliDayRes);
				if($chkHoliDayNum < 1){
					if(date('N', strtotime($dt)) != 7){
						$chkAttPSql = $this->db->query("SELECT `attendance_id`, `att_status` FROM `attendance_new` WHERE `login_id` = '".$getReqInfo[0]['user_id']."' AND `date` = '$dt' LIMIT 1");
						$chkAttPRes = $chkAttPSql->result_array();
						
						$chkAttPNum = COUNT($chkAttPRes);
						if($chkAttPNum == 0){
							$login_id = $getReqInfo[0]['user_id'];
							$date = $dt;
							$att_status = 'R';
							$in_time = '00:00:00';
							$insAttSql = $this->timesheet_model->insert_attendance_on_regularize($login_id, $date, $att_status, $in_time, $reason);
							//$insAttSql = $this->db->query("INSERT INTO attendance_new (`login_id`, `date`, `att_status`, `in_time`, `reason`) VALUES ('".$getReqInfo[0]['user_id']."', '".$dt."', 'R', '00:00:00', '".$reason."')");
						}
						else{
							if($chkAttPRes[0]['att_status'] == 'H'){
								/* $attendance_id = $chkAttPRes[0]['attendance_id'];
								$in_time = '00:00:00';
								$sQryUpdate = $this->timesheet_model->update_attendance_on_regularize_H($in_time, $attendance_id); */
								//$sQryUpdate = $this->db->query("UPDATE attendance_new SET `in_time` = '00:00:00' WHERE `attendance_id` = '".$chkAttPRes[0]['attendance_id']."'");
							} else if($chkAttPRes[0]['att_status'] == 'W' || $chkAttPRes[0]['att_status'] == 'P'){
								$attendance_id = $chkAttPRes[0]['attendance_id'];
								$att_status = 'R';
								$rQryUpdate = $this->timesheet_model->update_attendance_on_regularize_p($att_status, $reason, $attendance_id);
								//$rQryUpdate = $this->db->query("UPDATE attendance_new SET `att_status` = 'R', reason='".$reason."' WHERE `attendance_id` = '".$chkAttPRes[0]['attendance_id']."'");
							}
						}
					}
				}
			}
			$appQry = "UPDATE `attendance_request` SET `status` = 'A', `act_date` = '".date("Y-m-d H:i:s")."' WHERE `attd_req_id` = '".$application_id."' AND `rm_id` = '".$user_id."' LIMIT 1";
			$appQryR = $this->db->query($appQry);
			
			
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$getReqInfo[0]['user_id']."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$getReqInfo[0]['user_id']."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array();
		
		$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Your Regularization has been Approved';
			$site_base_url=base_url('timesheet/my_regularise_application');
			
			$message=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear {$empInfo[0]['full_name']},</p>
				 <p>Your Regularization has been Approved . </p> 				 
                 <p>Reason - <strong>{$reason}</strong>. <br/><p/>
				 <p>From - <strong>{$from_date}</strong> to <strong>{$to_date}</strong>.<p/>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to View</a><br /><br /></p>
                 <p> In case of any Query, Please contact to Reporting Manager.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				$to = $empInfo[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$repInfo[0]['full_name'].'. <'.$repInfo[0]['email'].'>' . "\r\n";  
				$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				mail($to, $subject, $message, $headers);
		}
		
    }
	
    public function update_regularise_request_rejected()
    {
		$application_id = $this->input->post('application_id');
		$reject_reason = $this->input->post('reject_reason');
        $result = $this->timesheet_model->update_regularise_request_rejected($application_id, $reject_reason);
        echo json_encode($result);
    }
	
	//mail function for allpy regularise
	public function sendEmail()
	{
		$this->arr_global['email_data'] = $this->global_model->getRecentOrder($order_id);
		$this->load->library('email');
		$this->email->from('orders@mysarvodaya.com', 'Sarvodaya');
		$this->email->to('orders@mysarvodaya.com');
		$this->email->subject('Sarvodaya Order :: '.$order_id);
		$message = $this->load->view('email/email_view.php',$this->arr_global,TRUE);
		$this->email->message($message);	
		$this->email->send();
	}
	
	//validation regularise date
	public function checkdate()
	{
		if(null !== $this->input->post('from_date'))
		{
			$date = date("Y-m-d", strtotime($this->input->post('from_date')));
		}
		elseif(null !== $this->input->post('to_date'))
		{
			$date = date("Y-m-d", strtotime($this->input->post('to_date')));
		} 
		 
		$chkDateSql = "SELECT attd_req_id FROM `attendance_request` WHERE `status` != 'R' AND `user_id` = '".$this->session->userdata('user_id')."' AND '".$date."' BETWEEN `from_date` AND `to_date`";
		$chkDateRes = $this->db->query($chkDateSql);
		$chkDateNum = count($chkDateRes->result_array());
		if($chkDateNum > 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		} 
	}
    public function my_regularise_application()
    {
        $this->mViewData['pageTitle'] = 'My regularise application';

        //Template view
		$this->render('timesheet/my_regularise_application_view', 'full_width', $this->mViewData); 
        $this->load->view('script/timesheet/my_regularise_application_script');
    }
    /*Start Ajax with angularjs function*/
    public function get_my_regularise_application()
    {
        $result = $this->timesheet_model->get_my_regularies();
        echo json_encode($result);
    }
    public function get_my_regularise_application_search()
    {
		$searchMonth = $this->input->post('searchMonth');
		$searchYear = $this->input->post('searchYear');
        $result = $this->timesheet_model->get_my_regularise_application_search($searchMonth, $searchYear);
        echo json_encode($result);
    }
    /*End */
    public function regularise_request()
    {
        $this->mViewData['pageTitle'] = 'Regularise request';
		
		if($this->input->post('rejRegReq') == 'Reject')
		{
			// Reject User Request
			$rejQry = "UPDATE `attendance_request` SET `status` = 'R', `rej_reason` = '".$this->input->post('txtReason')."', `act_date` = '".date("Y-m-d H:i:s")."' WHERE `attd_req_id` = '".$_REQUEST['reqID']."' AND `rm_id` = '".$_SESSION['user_id']."' LIMIT 1";
			@mysql_query($rejQry);

			// Mail To Employee For Rejection of Attendance Regularization Request
			attendanceRegularizeReject($_REQUEST['reqID']);

			header("location:attendance_reg_request.php");
			exit();
		} 
        //Template view 
		$this->render('timesheet/regularise_request_view', 'full_width', $this->mViewData);
		$this->load->view('script/timesheet/regularize_attendance_request_script');
    }
	/*Start Ajax with angularjs function*/
    public function get_regularize_attendance_request()
    {
        $result = $this->timesheet_model->get_regularize_attendance_request();
        echo json_encode($result);
    }
    public function get_regularize_attendance_request_search()
    {
		$searchMonth = $this->input->post('searchMonth');
		$searchYear = $this->input->post('searchYear');
		$searchStatus = $this->input->post('searchStatus');
        $result = $this->timesheet_model->get_regularize_attendance_request_search($searchMonth, $searchYear, $searchStatus);
        echo json_encode($result);
    }
    /*End */
    //end regularise
	
	
    // Start leave management 
    public function apply_for_leave()
    {
		$loginID = $this->session->userdata('user_id');
        $this->mViewData['pageTitle']    = 'Apply for Leave';
        $this->mViewData['detail_leave'] = $this->timesheet_model->get_leave_balance();
        $res                        = $this->mViewData['detail_leave'];
        $result                     = count($res);
        for ($i = 0; $i < $result; $i++) {
            $emp_type = $res[$i]['emp_type'];
        }
        if($emp_type == 'F')
		{
            $maxPL               = $this->getMaxLeave($_SESSION['user_id'], 'P');
            $maxSL               = $this->getMaxLeave($_SESSION['user_id'], 'S');
            $maxLeave            = $maxPL + $maxSL;
            $leaveINFO           = $this->getLeaveTaken($_SESSION['user_id'], date("m"), date("Y"), 'A');
            $this->mViewData['avlPL'] = $maxPL - $leaveINFO['ob_pl'];
            $this->mViewData['avlSL'] = $maxSL - $leaveINFO['ob_sl'];
            $totAvlleave         = $maxLeave > $leaveINFO['ob_pl'] + $leaveINFO['ob_sl'];
        }
		else
		{
            $maxPL         = 0;
            $maxSL         = 0;
            $maxLeave      = 0;
            $leaveINFO     = 0;
            $avlPL         = 0;
            $avlSL         = 0;
            $year          = date("Y");
            $contLeaveSql  = "SELECT i.`join_date`, f.`ob_pl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '" . $year . "' WHERE i.`login_id` = '" . $this->session->userdata('user_id') . "'";
            $contLeaveRes  = $this->db->query($contLeaveSql);
            $contLeaveInfo = $contLeaveRes->result_array();
            $maxPL         = $contLeaveInfo[0]['ob_pl'];
            $leaveINFO     = $this->getLeaveTaken($this->session->userdata('user_id'), date("m"), date("Y"), 'A');
            $avlPL         = $maxPL - $leaveINFO['ob_pl'];
            $totAvlleave   = $maxPL > $leaveINFO['ob_pl'];
			$this->mViewData['avlPL'] = $avlPL;
            $this->mViewData['avlSL'] = $avlSL;
        }
		$submitMSG = "";
		/* if($this->input->post('leaveApply') == 'Apply'){
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('leave_type', 'Leave type', 'trim|required');
			$this->form_validation->set_rules('from_date', 'Leave from', 'trim|required');
			$this->form_validation->set_rules('to_date', 'Leave to', 'required');
			$this->form_validation->set_rules('txtReason', 'Reason', 'required');
			$this->form_validation->set_rules('txtDetails', 'Contact details', 'trim|required');
			if ($this->form_validation->run() === false)
			{
				$this->render('timesheet/apply_for_leave_view', 'full_width', $this->mViewData);
				$this->load->view('script/timesheet/datepicker_js');
			} 
			else 
			{
				$leave_type       = $this->input->post('leave_type');
				$leave_from       = date("Y-m-d", strtotime($this->input->post('from_date')));
				$leavefromhalfday = $this->input->post('halfday1');
				$leave_to         = date("Y-m-d", strtotime($this->input->post('to_date')));
				$leavetohalfday   = $this->input->post('halfday2');
				$absence_reason   = $this->input->post('txtReason');
				$reportingTo      = $this->input->post('reportingTo');
				$contact_details  = $this->input->post('txtDetails');
				
				if ($emp_type == 'C') 
				{
					$leave_type = 'P';
				}
				
				$chkSql = "SELECT leave_type FROM `leave_application` WHERE user_id = '".$loginID."' AND DATE(leave_from) = '".$leave_from."' AND (status ='A' AND status='P')";
				$chkRes = $this->db->query($chkSql);
				$chkInfo = $chkRes->result_array();
				//echo count($chkInfo);exit;
				if(count($chkInfo) == 0){
				if($this->timesheet_model->insert_apply_for_leave($leave_type, $leave_from, $leavefromhalfday, $leave_to, $leavetohalfday, $absence_reason, $reportingTo, $contact_details)){
					
					
				$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
				$repRes = $this->db->query($repSql);
				$repInfo = $repRes->result_array();
				
				$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
				$empRes = $this->db->query($empSql);
				$empInfo = $empRes->result_array();
				
				$leave_type_name = $leave_type;
				if($leave_type == 'P'){
					$leave_type_name = 'PL';
				}
				else if($leave_type == 'S'){
					$leave_type_name = 'SL';
				}
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Leave Request For Approval - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('timesheet/leave_request');
			
			$message=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear {$repInfo[0]['full_name']},</p>
				 <p>{$empInfo[0]['full_name']} has applied for Leave . </p> 				 
                 <p>Leave Reason - <strong>{$absence_reason}</strong>. <br/><p/>
				 <p>Leave from - <strong>{$leave_from}</strong> to <strong>{$leave_to}</strong>.<p/>
				 <p>Leave Type - <strong>{$leave_type_name}</strong>. <p/>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to Approve / Reject</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				$to = $repInfo[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
				$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				mail($to, $subject, $message, $headers);
					//$this->leaveRequest($this->session->userdata('user_id'), $leave_from, $leave_to, $absence_reason, $leave_type);
					redirect('timesheet/my_leave_application');
				}
				else 
				{
					$mViewData->error = 'There was a problem creating your new account. Please try again.';
					$this->render('timesheet/apply_for_leave_view', 'full_width', $this->mViewData);
					$this->load->view('script/timesheet/datepicker_js');
				}
			}
			else{
				$submitMSG = "Already applied in Date:".$this->input->post('from_date')."";
			}
			}
		} */
		$this->mViewData['submitMSG'] = $submitMSG;
		$this->render('timesheet/apply_for_leave_view', 'full_width', $this->mViewData);
		$this->load->view('script/timesheet/datepicker_js');
		$this->load->view('script/timesheet/apply_for_leave_js');
    }
	
	
	
    public function apply_for_leave_check()
    {
		$loginID = $this->session->userdata('user_id');
		$branch = $this->session->userdata('branch');
		$days=0;
		$data = array( 'status'=> 0, 'nodays'=>$days);
		
		$detail_leave = $this->timesheet_model->get_leave_balance();
        $res = $detail_leave; //print_r($res);
        $result = count($res);
        for ($i = 0; $i < $result; $i++) {
            $emp_type = $res[$i]['emp_type'];
        }
		$leave_type       = $this->input->post('leave_type');
		$leave_from       = date("Y-m-d", strtotime($this->input->post('from_date')));
		$halfday1 = $this->input->post('halfday1');
		$leave_to         = date("Y-m-d", strtotime($this->input->post('to_date')));
		$halfday2   = $this->input->post('halfday2');
		$absence_reason   = $this->input->post('txtReason');
		$reportingTo      = $this->input->post('reportingTo');
		$contact_details  = $this->input->post('txtDetails');
		
		if ($emp_type == 'C')
		{
			$leave_type = 'P';
		}
		
		$chkSql = "SELECT leave_type FROM `leave_application` WHERE user_id = '".$loginID."' AND DATE(leave_from) = '".$leave_from."' AND (status ='A' OR status='P')";
		$chkRes = $this->db->query($chkSql);
		$chkInfo = $chkRes->result_array();
		//echo count($chkInfo);exit;
		if(count($chkInfo) == 0){
			$dateRangeArr = $this->date_range($leave_from, $leave_to);
			
			$cnt_date = count($dateRangeArr);
			$k=0;
			foreach($dateRangeArr AS $dt){
				$k++;
				$chkHoliDaySql =  $this->db->query("SELECT `ix_declared_leave` FROM `declared_leave` WHERE `f_disp_flag` = 1 AND `dt_event_date` = '$dt' AND (`branch` = '0' OR `branch` = '$branch') "); 
				$chkHoliDayRes = $chkHoliDaySql->result_array();
				$chkHoliDayNum = COUNT($chkHoliDayRes);
				if($chkHoliDayNum < 1){
					if($k ==1 && $halfday1 == 'Y'){
						$days = (float)$days + 0.5;
					}
					else if($k ==$cnt_date && $halfday2 == 'Y'){
						$days = (float)$days + 0.5;
					}
					/* if($halfday1 == 'Y' || $halfday2 == 'Y'){
						
						if($halfday1 == 'Y'){
							
						}
						if($halfday2 == 'Y'){
							$days = (float)$days + 0.5;
						}
					} */
					else{
						$days = (float)$days + 1;
					}
					//echo $days;
				}
			}
			
			
			$data = array( 'status'=> 1, 'nodays'=>$days);
		}
		else{
			$data = array( 'status'=> 0, 'nodays'=>$days);
		}
		echo json_encode($data);
    }
	
	
	
    public function apply_for_leave_submit()
    {
		$loginID = $this->session->userdata('user_id');
		$this->mViewData['detail_leave'] = $this->timesheet_model->get_leave_balance();
        $res                        = $this->mViewData['detail_leave'];
        $result                     = count($res);
        for ($i = 0; $i < $result; $i++) {
            $emp_type = $res[$i]['emp_type'];
        }
		$leave_type       = $this->input->post('leave_type');
		$leave_from       = date("Y-m-d", strtotime($this->input->post('from_date')));
		$leavefromhalfday = $this->input->post('halfday1');
		$leave_to         = date("Y-m-d", strtotime($this->input->post('to_date')));
		$leavetohalfday   = $this->input->post('halfday2');
		$absence_reason   = $this->input->post('txtReason');
		$reportingTo      = $this->input->post('reportingTo');
		$contact_details  = $this->input->post('txtDetails');
		$no_of_days  = $this->input->post('no_of_days');
		
		if ($emp_type == 'C')
		{
			$leave_type = 'P';
		}
		
		$chkSql = "SELECT leave_type FROM `leave_application` WHERE user_id = '".$loginID."' AND DATE(leave_from) = '".$leave_from."' AND (status ='A' OR status='P')";
		$chkRes = $this->db->query($chkSql);
		$chkInfo = $chkRes->result_array();
		//echo count($chkInfo);exit;
		if(count($chkInfo) == 0){
			if($this->timesheet_model->insert_apply_for_leave($leave_type, $leave_from, $leavefromhalfday, $leave_to, $leavetohalfday, $absence_reason, $reportingTo, $contact_details, $no_of_days)){
				
				
			$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			
			$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			
			$leave_type_name = $leave_type;
			if($leave_type == 'P'){
				$leave_type_name = 'PL';
			}
			else if($leave_type == 'S'){
				$leave_type_name = 'SL';
			}else if($leave_type == 'M'){
				$leave_type_name = 'ML';
				$absence_reason = "Apply For ML";
			}
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Leave Request For Approval - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('timesheet/leave_request');
			
			$message=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear {$repInfo[0]['full_name']},</p>
				 <p>{$empInfo[0]['full_name']} has applied for Leave . </p> 				 
                 <p>Leave Reason - <strong>{$absence_reason}</strong>. <br/><p/>
				 <p>Leave from - <strong>{$leave_from}</strong> to <strong>{$leave_to}</strong>.<p/>
				 <p>Leave Type - <strong>{$leave_type_name}</strong>. <p/>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to Approve / Reject</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

			$to = $repInfo[0]['email'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $message, $headers);
				//$this->leaveRequest($this->session->userdata('user_id'), $leave_from, $leave_to, $absence_reason, $leave_type);
				//redirect('timesheet/my_leave_application');
				echo 2;
			}
			else 
			{
				echo 1;
			}
		}
		else{
			echo 0;
		}
		
    }
	
	
    // Send mail to reporting manager for requesting Leave
    function leaveRequest($user_id, $leaveFrom, $leaveTo, $leaveReason, $leaveRequest)
    {
        global $logoText, $footer, $fromAABSySiCompass;
        $userSql  = "SELECT i.email AS myEmail, i.full_name AS myName, i.loginhandle AS myEmpCode, r.email AS rmEmail, r.full_name AS rmName FROM `internal_user` i JOIN `internal_user` r ON i.reporting_to = r.login_id WHERE i.login_id = '" . $user_id . "'";
        $userRes  = $this->db->query($userSql);
        $userInfo = $userRes->result_array();
        $myName   = $userInfo[0]['myName'];
        $myEmail  = $userInfo[0]['myEmail'];
        $rmName   = $userInfo[0]['rmName'];
        $rmEmail   = $userInfo[0]['rmEmail'];
        $to       = $rmEmail;
        $lFrom    = date("d-m-y", strtotime($leaveFrom));
        $lTo      = date("d-m-y", strtotime($leaveTo));
        if ($leaveRequest == 'P') {
            $leave = "PL";
        } else {
            $leave = "SL";
        }
        // subject
        $subject = 'Leave Request For Approval - ' . $userInfo[0]['myEmpCode'] . ' (' . $myName . ')';
        $message = '
			<html>
				<head>
					<title>POLOHRM Leave Request For Approval</title>
				</head>
				<body>
					<div id="icompass" style="width:615px;margin:0 auto;">
						';
					$message .= $logoText;
					$message .= '
						<div>
							<span style="color:#FF6600;font-size:14px;font-weight:bold;line-height:20px">Dear Sir/Madam,</span>
							<br />
							<p>Please click on the following link to act on the "Leave Request" of <strong>' . $myName . '</strong>.
								<br /><br/>
								Leave Reason - <strong>' . $leaveReason . '</strong>. <br/><br/>
								Leave from - <strong>' . $lFrom . '</strong> to - <strong>' . $lTo . '</strong>.<br/><br/>
								Leave Type - <strong>' . $leave . '</strong>. <br/><br/>
								<br />
								<a href="' . SITE_BASE_URL . '/script/leave_app_req.php" style="text-decoration:none">Click Here for Approve / Reject</a><br /><br />
							</p>
						</div>
						';
					$message .= $fromAABSySiCompass;
					$message .= $footer;
					$message .= '
					</div>
				</body>
			</html>
			';
        // To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "X-Priority: 1 (Highest)\n"; 
        $headers .= "X-MSMail-Priority: High\n"; 
        $headers .= "Importance: High\n";
		$headers .= 'From: ' . $myName . ' <' . $myEmail . '>' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $mail = mail($to, $subject, $message, $headers);
    }
    public function getMaxLeave($userID, $type = 'A', $year = '')
    {
        if ($year == '') {
            $year = date("Y");
        }
        $joinDtSql = "SELECT i.`join_date`, f.`ob_pl`, f.`ob_sl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '" . $year . "' WHERE i.`login_id` = '" . $userID . "'";
        //$joinDtRes = $this->db->query($joinDtSql);
        $result    = $this->db->query($joinDtSql)->result_array();
		//echo $joinDtSql;
        //var_dump($result);exit;
        //$joinInfo = $this->result_array($joinDtRes);
        $joinDate  = date("d", strtotime($result[0]['join_date']));
        $joinMonth = date("m", strtotime($result[0]['join_date']));
        $joinYear  = date("Y", strtotime($result[0]['join_date']));
        if ($year <= 2013) {
            if ($type == 'P') {
                $maxLeave          = 22;
                $carryForwardLeave = $result[0]['ob_pl'];
            } elseif ($type == 'S') {
                $maxLeave          = 8;
                $carryForwardLeave = $result[0]['ob_sl'];
            } else {
                $maxLeave          = 30;
                $carryForwardLeave = $result[0]['ob_pl'] + $result[0]['ob_sl'];
            }
            if ($year > $joinYear) {
                $maxLeaveForThisYear = $maxLeave + $carryForwardLeave;
            } else {
                if ($joinDate <= 15) {
                    $remainingMonth = 12 - ($joinMonth - 1);
                } else {
                    $remainingMonth = 12 - $joinMonth;
                }
                $maxLeaveForThisYear = ceil(($maxLeave / 12) * $remainingMonth);
            }
        } else {
            if ($type == 'P') {
                $maxLeaveForThisYear = $result[0]['ob_pl'];
            } elseif ($type == 'S') {
                $maxLeaveForThisYear = $result[0]['ob_sl'];
            } else {
                $maxLeaveForThisYear = $result[0]['ob_pl'] + $result[0]['ob_sl'];
            }
        }
		if($maxLeaveForThisYear ==""){
			$maxLeaveForThisYear = 0;
		}
        return $maxLeaveForThisYear;
    }
    public function getLeaveTaken($userID, $month, $year, $type = 'C')
    {
        if ($type == 'C') {
            $leaveSQL = "SELECT SUM(`ob_pl`) as ob_pl, SUM(`ob_sl`) AS ob_sl FROM `leave_info` WHERE `login_id` = '$userID' AND `month` = '$month' AND `year` = '$year'";
        } else if ($type == 'A') {
            $leaveSQL = "SELECT SUM(`ob_pl`) AS ob_pl, SUM(`ob_sl`) AS ob_sl FROM `leave_info` WHERE `login_id` = '$userID' AND `month` <= '$month' AND `year` = '$year'";
        }
        $leaveRES = $this->db->query($leaveSQL)->result_array();
        $leaveNUM = count($leaveRES);
        if ($leaveNUM > 0) {
            $leaveINFO = Array(
                    "ob_pl" => $leaveRES[0]['ob_pl'],
                    "ob_sl" => $leaveRES[0]['ob_sl']
                );
            if ($leaveINFO['ob_pl'] == "") {
                $leaveINFO = Array(
                    "ob_pl" => 0,
                    "ob_sl" => 0
                );
            }
        } else {
            $leaveINFO = Array(
                "ob_pl" => 0,
                "ob_sl" => 0
            );
        }
        return $leaveINFO;
    }
	
    public function my_leave_application()
    {
        $this->mViewData['pageTitle'] = 'My Leave Application';
		if($this->input->post('can_submit') == 'cancel_leave'){
			$status = $this->input->post('status');
			if($status == 'P'){
				$newStatus = 'W';
				$rejQry = "UPDATE `leave_application` SET `status` = '".$newStatus."', `w_c_reason` = '".$this->input->post('reason')."', `w_c_dt` = '".date("Y-m-d H:i:s")."' WHERE `application_id` = '".$this->input->post('application_id')."' AND `user_id` = '".$this->session->userdata('user_id')."' LIMIT 1";
				$rejRes = $this->db->query($rejQry);
			} else if($status == 'A'){
				$newStatus = 'CA';
			
			$reason = $this->input->post('reason');
			$application_id = $this->input->post('application_id');
			$user_id = $this->session->userdata('user_id');
			$date = date("Y-m-d H:i:s");
			$chkCPSQL = $this->db->query("SELECT `application_id` FROM `leave_application` WHERE `application_id` = '".$application_id."' AND `status` = 'A' LIMIT 1");
			$chkCPRES = $chkCPSQL->result_array();
			$chkCPNUM = COUNT($chkCPRES);
			
			if($chkCPNUM == 1){
				$appQry = $this->db->query("UPDATE `leave_application` SET `status` = 'CA', `w_c_dt` = '".$date."', `w_c_reason` = '".$this->input->post('reason')."' WHERE `application_id` = '".$application_id."'");
				$leaveCount = 0;
			
				$getReqSql = $this->db->query("SELECT * FROM `leave_application` WHERE `application_id` = '".$application_id."' AND `status` = 'CA' LIMIT 1");
				$getReqInfo = $getReqSql->result_array();
				$dateRangeArr = date_range($getReqInfo[0]['leave_from'], $getReqInfo[0]['leave_to']);
				foreach($dateRangeArr AS $dt){
					$chkHoliDaySql = $this->db->query("SELECT `ix_declared_leave` FROM `declared_leave` WHERE (`branch`='0' OR `branch`='".$_SESSION['branch']."') AND `f_disp_flag` = 1 AND `dt_event_date` = '".$dt."'   AND (`branch` = '0' OR `branch` = '".$this->session->userdata('branch')."')");
					$chkHoliDayRes = $chkHoliDaySql->result_array();
					$chkHoliDayNum = COUNT($chkHoliDayRes);
					if($chkHoliDayNum < 1){
						if(date('N', strtotime($dt)) != 7){
							$addleave = 1;
							if($getReqInfo[0]['leavefromhalfday'] == 'Y' && $getReqInfo[0]['leave_from'] == $dt){
								$addleave = 0.5;
							}
							if($getReqInfo[0]['leavetohalfday'] == 'Y' && $getReqInfo[0]['leave_to'] == $dt){
								$addleave = 0.5;
							}
							$delAttSql = $this->db->query("DELETE FROM `attendance_new` WHERE `login_id` = '".$getReqInfo[0]['user_id']."' AND `date` = '".$dt."' LIMIT 1");
							$m = date("m", strtotime($dt));
							$y = date("Y", strtotime($dt));
							$chkleaveSql = $this->db->query("SELECT `leave_id`, `ob_pl`, `ob_sl` FROM `leave_info` WHERE `login_id` = '".$getReqInfo[0]['user_id']."' AND `month` = '".$m."' AND `year` = '".$y."' LIMIT 1");
							$chkleaveInfo = $chkleaveSql->result_array();
							$pl = $chkleaveInfo[0]['ob_pl'];
							$sl = $chkleaveInfo[0]['ob_sl'];
							if($getReqInfo[0]['leave_type'] == 'P'){
								$pl = $pl - $addleave;
							} elseif($getReqInfo[0]['leave_type'] == 'S'){
								$sl = $sl - $addleave;
							}
							$upLeaveSql = $this->db->query("UPDATE `leave_info` SET `ob_pl` = '".$pl."', `ob_sl` = '".$sl."' WHERE `login_id` = '".$getReqInfo[0]['user_id']."' AND `month` = '".$m."' AND `year` = '".$y."' LIMIT 1");
						}
					}
				}
		//mail
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$user_id."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array();
		
		$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Your Leave has been Cancel Approved';
			$site_base_url=base_url('timesheet/leave_request');
			
			$message=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear {$repInfo[0]['full_name']},</p>
				 <p>Leave of {$empInfo[0]['full_name']} has been Cancel Approved . </p> 
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to View</a><br /><br /></p>
                 <p> In case of any Query, Please contact to Reporting Manager.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				$to = $repInfo[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$repInfo[0]['full_name'].'. <'.$repInfo[0]['email'].'>' . "\r\n";  
				$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				mail($to, $subject, $message, $headers);
				
			}
			}
		}
        
		$this->render('timesheet/my_leave_application_view', 'full_width', $this->mViewData);
		$this->load->view('script/timesheet/my_leave_application_script');
    }
	/*Start Ajax with angularjs function*/
    public function get_my_leave_application()
    {
        $result = $this->timesheet_model->get_my_leave_application();
        echo json_encode($result);
    }
    /*End */
	/*Start Ajax with angularjs function*/
    public function get_my_leave_application_search()
    {
		$searchMonth = $this->input->post('searchMonth');
		$searchYear = $this->input->post('searchYear');
        $result = $this->timesheet_model->get_my_leave_application_search($searchMonth, $searchYear);
        echo json_encode($result);
    }
    /*End */
    public function leave_app_cancel()
    {
        // load form helper and validation library
        $this->load->helper('form');
        $this->load->library('form_validation');
        // set validation rules
        $this->form_validation->set_rules('txtReason', 'Reason', 'trim|required');
        if ($this->form_validation->run() === false) {
            echo validation_errors();
        } else {
            $txtReason = $this->input->post('txtReason');
            $appid     = $this->input->post('appID');
            // Withdraw Leave Request
            $rejQry    = "UPDATE `leave_application` SET `status` = 'W', `w_c_reason` = '" . $txtReason . "', `w_c_dt` = '" . date("Y-m-d H:i:s") . "' WHERE `application_id` = '" . $appid . "' AND `user_id` = '" . $this->session->userdata('user_id') . "' LIMIT 1";
            echo $this->db->query($rejQry);
            //Mail To Reporting manager For Withdraw of Leave Request
            //leaveWithdraw($_REQUEST['appID']);
            redirect('timesheet/my_leave_application');
        }
    }
	
    public function leave_app_withdraw()
    {
    }
	
    public function leave_request()
    {
        $this->mViewData['pageTitle'] = 'Leave request';
        //Template view
		$this->render('timesheet/leave_request_view', 'full_width', $this->mViewData);
		$this->load->view('script/timesheet/leave_request_script');
    }
	
	/*Start Ajax with angularjs function*/
    public function get_my_leave_request()
    {
        $result = $this->timesheet_model->get_my_leave_request();
        echo json_encode($result);
    }
    public function get_my_leave_request_search()
    {
		$searchMonth = $this->input->post('searchMonth');
		$searchYear = $this->input->post('searchYear');
		$searchStatus = $this->input->post('searchStatus');
        $result = $this->timesheet_model->get_my_leave_request_search($searchMonth, $searchYear, $searchStatus);
        echo json_encode($result);
    }
	
	
    public function update_leave_request_approved()
    {
		$application_id = $this->input->post('application_id');
		
		
		$check_leave = $this->timesheet_model->check_leave_request_status_employee($application_id);
		if(count($check_leave)>0){
			//echo count($check_leave); exit;
			$check_val = $this->check_leave_availability_employee($application_id);
			//exit;
			if($check_val == 1){
				$result = $this->timesheet_model->update_leave_request_approved($application_id);
				$this->leaveApproveProcess($application_id);
			   
				$getLeaveQry = $this->db->query('SELECT leave_type,leave_from,leave_to,absence_reason,user_id FROM leave_application WHERE application_id = "'.$application_id.'" AND status = "A"');
				$getLeaveInfo = $getLeaveQry->result_array();
				$absence_reason = $getLeaveInfo[0]['absence_reason'];
				$leave_from = $getLeaveInfo[0]['leave_from'];
				$leave_to = $getLeaveInfo[0]['leave_to'];
				if( $getLeaveInfo[0]['leave_type'] == 'P'){
					$leave_type_name = 'PL';
				} else if( $getLeaveInfo[0]['leave_type'] == 'S'){
					$leave_type_name = 'SL';
				}else if( $getLeaveInfo[0]['leave_type'] == 'M'){
					$leave_type_name = 'ML';
				}
				
				$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$getLeaveInfo[0]['user_id']."'";
				$empRes = $this->db->query($empSql);
				$empInfo = $empRes->result_array();
				
				$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$getLeaveInfo[0]['user_id']."')";
				$repRes = $this->db->query($repSql);
				$repInfo = $repRes->result_array();
				
				$logo_URL = base_url('assets/images/logo.gif');
				$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
				<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
				</div>';
					
				$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
				<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
				<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
					&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
				</div>';
				
				$subject = 'Your Leave has been Approved';
				$site_base_url=base_url('timesheet/my_leave_application');
				
			$message=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear {$empInfo[0]['full_name']},</p>
				 <p>Your leave has been Approved . </p> 				 
                 <p>Leave Reason - <strong>{$absence_reason}</strong>. <br/><p/>
				 <p>Leave from - <strong>{$leave_from}</strong> to <strong>{$leave_to}</strong>.<p/>
				 <p>Leave Type - <strong>{$leave_type_name}</strong>. <p/>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to View</a><br /><br /></p>
                 <p> In case of any Query, Please contact to Reporting Manager.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				$to = $empInfo[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$repInfo[0]['full_name'].'. <'.$repInfo[0]['email'].'>' . "\r\n";  
				$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				mail($to, $subject, $message, $headers);
				$response= 1;
			}
			else{
				$response= 0;
			}
		}
		else{
			$response= 2;
		}
		
				echo json_encode($response);
    }
	
	public function check_leave_availability_employee($application_id)
    {
		$getLeaveQry = $this->db->query('SELECT * FROM leave_application WHERE application_id = "'.$application_id.'" ');
		$getLeaveInfo = $getLeaveQry->result_array();
        $loginID = $getLeaveInfo[0]['user_id'];
        $halfday1 = $getLeaveInfo[0]['leavefromhalfday'];
        $halfday2 = $getLeaveInfo[0]['leavetohalfday'];
        $leave_type = $getLeaveInfo[0]['leave_type'];
        $from_date = date('Y-m-d', strtotime($getLeaveInfo[0]['leave_from']));
        $to_date = date('Y-m-d', strtotime($getLeaveInfo[0]['leave_to']));
		
		$res = $this->timesheet_model->get_leave_balance_details($loginID);
        $result = count($res);
        for ($i = 0; $i < $result; $i++) {
            $emp_type = $res[$i]['emp_type'];
        }
        if($emp_type == 'F')
		{
            $maxPL               = $this->getMaxLeave($loginID, 'P');
            $maxSL               = $this->getMaxLeave($loginID, 'S');
            //$maxLeave            = $maxPL + $maxSL;
            $leaveINFO           = $this->getLeaveTaken($loginID, date("m"), date("Y"), 'A');
            $avlPL = $maxPL - $leaveINFO['ob_pl'];
            $avlSL = $maxSL - $leaveINFO['ob_sl'];
            //$totAvlleave         = $maxLeave > $leaveINFO['ob_pl'] + $leaveINFO['ob_sl'];
        }
		else
		{
            $maxPL         = 0;
            $maxSL         = 0;
            //$maxLeave      = 0;
            //$leaveINFO     = 0;
            $avlPL         = 0;
            $avlSL         = 0;
            $year          = date("Y");
            $contLeaveSql  = "SELECT i.`join_date`, f.`ob_pl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '" . $year . "' WHERE i.`login_id` = '" . $loginID . "'";
            $contLeaveRes  = $this->db->query($contLeaveSql);
            $contLeaveInfo = $contLeaveRes->result_array();
            $maxPL         = $contLeaveInfo[0]['ob_pl'];
            $leaveINFO     = $this->getLeaveTaken($loginID, date("m"), date("Y"), 'A');
            $avlPL         = $maxPL - $leaveINFO['ob_pl'];
            //$totAvlleave   = $maxPL > $leaveINFO['ob_pl'];
			$avlPL = $avlPL;
            $avlSL = $avlSL;
        }
		
		$leave_apply_days =0;
		$dateRangeArr = date_range($from_date, $to_date);
		foreach($dateRangeArr AS $dt){
			$chkHoliDaySql =  $this->db->query("SELECT `ix_declared_leave` FROM `declared_leave` WHERE `f_disp_flag` = 1 AND `dt_event_date` = '$dt'   AND (`branch` = '0' OR `branch` = '".$this->session->userdata('branch')."')");
			$chkHoliDayRes = $chkHoliDaySql->result_array();
			$chkHoliDayNum = COUNT($chkHoliDayRes);
			if($chkHoliDayNum < 1){
				if($halfday1 == 'Y' || $halfday2 == 'Y'){
					if($halfday1 == 'Y'){
						$leave_apply_days = (float)$leave_apply_days + 0.5;
					}
					if($halfday2 == 'Y'){
						$leave_apply_days = (float)$leave_apply_days + 0.5;
					}
				}
				else{
					$leave_apply_days = (float)$leave_apply_days + 1;
				}
			}
		}
		if($leave_type == 'P'){
			if((float)$leave_apply_days <= (float)$avlPL){
				$response = 1;
			}
			else {
				$response = 0;
			}
		}
		else if($leave_type == 'S'){
			if((float)$leave_apply_days <= (float)$avlSL){
				$response = 1;
			}
			else {
				$response = 0;
			}
		}
		else if($leave_type == 'M'){
			$response = 1;
		}
		//echo ' al:'.$leave_apply_days.' avS:'.$avlSL;
		return $response;
    }
	
	
    public function update_leave_request_rejected()
    {
		$application_id = $this->input->post('application_id');
		$reject_reason = $this->input->post('reject_reason');
        $result = $this->timesheet_model->update_leave_request_rejected($application_id, $reject_reason);
        echo json_encode($result);
    }
    /*End */
	
	
    public function my_leave_status()
    {
        $this->mViewData['pageTitle'] = 'My Leave Status';
        $leaveUserID = 0;
        if ($this->input->post('reqEmpid') == null)
		{
            $subheader   = 'Leave Management';
            $nested_link = 'My Leave Status';
            $title       = 'Leave Management';
            $leaveUserID = $this->session->userdata('user_id');
        } 
		else 
		{
            $subheader   = 'View Members';
            $fullName    = $this->getValue("internal_user", "full_name", "login_id=" . $this->input->post('reqEmpid'));
            $leaveUserID = $this->input->post('reqEmpid');
        }
        $userTypeSql  = "SELECT i.emp_type FROM `internal_user` i WHERE i.`login_id` = '$leaveUserID'";
        $userTypeInfo = $this->db->query($userTypeSql)->result_array();
        if ($userTypeInfo[0]['emp_type'] == 'F')
		{
            $this->mViewData['leaveINFO'] = $this->getLeaveTaken($leaveUserID, date("m"), date("Y"), 'A');
            $this->mViewData['tot_pl']    = $this->getMaxLeave($leaveUserID, 'P');
            $this->mViewData['tot_sl']    = $this->getMaxLeave($leaveUserID, 'S');
        }
		elseif ($userTypeInfo[0]['emp_type'] == 'C')
		{
            $year                    = date("Y");
            $contLeaveSql            = $this->db->query("SELECT i.`join_date`, f.`ob_pl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '" . $year . "' WHERE i.`login_id` = '$leaveUserID'");
            $contLeaveInfo           = $contLeaveSql->result_array();
            $this->mViewData['tot_pl']    = $contLeaveInfo[0]['ob_pl'];
            $this->mViewData['tot_sl']    = 0;
            $this->mViewData['leaveINFO'] = $this->getLeaveTaken($leaveUserID, date("m"), date("Y"), 'A');
        }
        //Template view
		$this->render('timesheet/my_leave_status_view', 'full_width', $this->mViewData);
    }
	
    
	
	
	
    public function chek_leave_availability_count()
    {
        $leaveUserID = $this->session->userdata('user_id');
        $halfday1 = $this->input->post('halfday1');
        $halfday2 = $this->input->post('halfday2');
        $leave_type = $this->input->post('leave_type');
        $avlSL = $this->input->post('avlSL');
        $avlPL = $this->input->post('avlPL');
        $from_date = date('Y-m-d', strtotime($this->input->post('from_date')));
        $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
		
		$leave_apply_days =0;
		$dateRangeArr = date_range($from_date, $to_date);
		foreach($dateRangeArr AS $dt){
			$chkHoliDaySql =  $this->db->query("SELECT `ix_declared_leave` FROM `declared_leave` WHERE `f_disp_flag` = 1 AND `dt_event_date` = '$dt'   AND (`branch` = '0' OR `branch` = '".$this->session->userdata('branch')."')");
			$chkHoliDayRes = $chkHoliDaySql->result_array();
			$chkHoliDayNum = COUNT($chkHoliDayRes);
			if($chkHoliDayNum < 1){
				if($halfday1 == 'Y' || $halfday2 == 'Y'){
					if($halfday1 == 'Y'){
						$leave_apply_days = (float)$leave_apply_days + 0.5;
					}
					if($halfday2 == 'Y'){
						$leave_apply_days = (float)$leave_apply_days + 0.5;
					}
				}
				else{
					$leave_apply_days = (float)$leave_apply_days + 1;
				}
			}
		}
		if($leave_type == 'P'){
			if((float)$leave_apply_days <= (float)$avlPL){
				echo 1;
			}
			else {
				echo 0;
			}
		}
		else if($leave_type == 'S'){
			if((float)$leave_apply_days <= (float)$avlSL){
				echo 1;
			}
			else {
				echo 0;
			}
		}
		//echo ' al:'.$leave_apply_days.' avS:'.$avlSL;
		
    }
	
    public function chek_leave_availability()
    {
        $leaveUserID = $this->session->userdata('user_id');
        $userTypeSql  = "SELECT i.emp_type FROM `internal_user` i WHERE i.`login_id` = '$leaveUserID'";
        $userTypeInfo = $this->db->query($userTypeSql)->result_array();
        if ($userTypeInfo[0]['emp_type'] == 'F')
		{
            $leaveINFO = $this->getLeaveTaken($leaveUserID, date("m"), date("Y"), 'A');
			$tot_pl    = $this->getMaxLeave($leaveUserID, 'P');
            $tot_sl    = $this->getMaxLeave($leaveUserID, 'S');
        }
		else if ($userTypeInfo[0]['emp_type'] == 'C')
		{
            $leaveINFO = $this->getLeaveTaken($leaveUserID, date("m"), date("Y"), 'A');
			$year                    = date("Y");
            $contLeaveSql            = $this->db->query("SELECT i.`join_date`, f.`ob_pl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '" . $year . "' WHERE i.`login_id` = '$leaveUserID'");
            $contLeaveInfo           = $contLeaveSql->result_array();
			$tot_pl    = 0;
			if(count($contLeaveInfo)>0){
				$tot_pl    = $contLeaveInfo[0]['ob_pl'];
			}
            $tot_sl    = 0;
        }
		else if ($userTypeInfo[0]['emp_type'] == 'I')
		{
            $leaveINFO = $this->getLeaveTaken($leaveUserID, date("m"), date("Y"), 'A');
			$year                    = date("Y");
            $contLeaveSql            = $this->db->query("SELECT i.`join_date`, f.`ob_pl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '" . $year . "' WHERE i.`login_id` = '$leaveUserID'");
            $contLeaveInfo           = $contLeaveSql->result_array();
			$tot_pl    = 0;
			if(count($contLeaveInfo)>0){
				$tot_pl    = $contLeaveInfo[0]['ob_pl'];
			}
            $tot_sl    = 0;
        }
		
        if($this->input->post('leave_type') == 'P'){
			$ob_pl = $leaveINFO['ob_pl'];
			$bal_pl = $tot_pl - $ob_pl; 
			if($bal_pl > 0){
				echo 1;
			}
			else{
				echo 0;
			}
		}
        else{
			$ob_sl = $leaveINFO['ob_sl'];
			$bal_sl = $tot_sl - $ob_sl;
			if($bal_sl > 0){
				echo 1;
			}
			else{
				echo 0;
			}
		}
    }
	
    public function chek_leave_availability_direct()
    {
        $leaveUserID = $this->input->post('login_id');
        $userTypeSql  = "SELECT i.emp_type FROM `internal_user` i WHERE i.`login_id` = '$leaveUserID'";
        $userTypeInfo = $this->db->query($userTypeSql)->result_array();
        if ($userTypeInfo[0]['emp_type'] == 'F')
		{
            $leaveINFO = $this->getLeaveTaken($leaveUserID, date("m"), date("Y"), 'A');
			$tot_pl    = $this->getMaxLeave($leaveUserID, 'P');
            $tot_sl    = $this->getMaxLeave($leaveUserID, 'S');
        }
		else if ($userTypeInfo[0]['emp_type'] == 'C')
		{
            $leaveINFO = $this->getLeaveTaken($leaveUserID, date("m"), date("Y"), 'A');
			$year                    = date("Y");
            $contLeaveSql            = $this->db->query("SELECT i.`join_date`, f.`ob_pl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '" . $year . "' WHERE i.`login_id` = '$leaveUserID'");
            $contLeaveInfo           = $contLeaveSql->result_array();
			$tot_pl    = 0;
			if(count($contLeaveInfo)>0){
				$tot_pl    = $contLeaveInfo[0]['ob_pl'];
			}
            $tot_sl    = 0;
        }
		else if ($userTypeInfo[0]['emp_type'] == 'I')
		{
            $leaveINFO = $this->getLeaveTaken($leaveUserID, date("m"), date("Y"), 'A');
			$year                    = date("Y");
            $contLeaveSql            = $this->db->query("SELECT i.`join_date`, f.`ob_pl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '" . $year . "' WHERE i.`login_id` = '$leaveUserID'");
            $contLeaveInfo           = $contLeaveSql->result_array();
			$tot_pl    = 0;
			if(count($contLeaveInfo)>0){
				$tot_pl    = $contLeaveInfo[0]['ob_pl'];
			}
            $tot_sl    = 0;
        }
		
        if($this->input->post('leave_type') == 'P'){
			$ob_pl = $leaveINFO['ob_pl'];
			$bal_pl = $tot_pl - $ob_pl; 
			if($bal_pl > 0){
				echo 1;
			}
			else{
				echo 0;
			}
		}
        else{
			$ob_sl = $leaveINFO['ob_sl'];
			$bal_sl = $tot_sl - $ob_sl;
			if($bal_sl > 0){
				echo 1;
			}
			else{
				echo 0;
			}
		}
    }
    //end leave management
	public function leave_status()
	{
		$this->mViewData['pageTitle'] = 'leave status of Employee';
		$leaveUserID = $this->session->userdata('user_id');
		if(isset($_GET['reqEmpid']))
		{
			$leaveUserID = $_GET['reqEmpid'];
		}
		$leaveUserID = $this->input->get('reqEmpid');
		$userTypeSql = "SELECT i.emp_type FROM `internal_user` i WHERE i.`login_id` = '$leaveUserID'";
		$userTypeRes = $this->db->query($userTypeSql); 
		$this->mViewData['userTypeInfo'] = $this->db->query($userTypeSql)->result_array();
		$userTypeInfo = $this->db->query($userTypeSql)->result_array();
		//var_dump($userTypeInfo);
		
		if($userTypeInfo[0]['emp_type'] == 'F')
		{
			/* $maxPL               = $this->getMaxLeave($_SESSION['user_id'], 'P');
            $maxSL               = $this->getMaxLeave($_SESSION['user_id'], 'S');
            $maxLeave            = $maxPL + $maxSL;
            $leaveINFO           = $this->getLeaveTaken($_SESSION['user_id'], date("m"), date("Y"), 'A');
            $this->mViewData['avlPL'] = $maxPL - $leaveINFO['ob_pl'];
            //echo $avlPL;exit;
            $this->mViewData['avlSL'] = $maxSL - $leaveINFO['ob_sl'];
            $totAvlleave         = $maxLeave > $leaveINFO['ob_pl'] + $leaveINFO['ob_sl']; */
			
			$leaveINFO = $this->getLeaveTaken($leaveUserID, date("m"), date("Y"), 'A');
            $tot_pl    = $this->getMaxLeave($leaveUserID, 'P');
            $tot_sl    = $this->getMaxLeave($leaveUserID, 'S');
		}
		else if($userTypeInfo[0]['emp_type']  == 'C')
		{
			$year                    = date("Y");
            $contLeaveSql            = "SELECT i.`join_date`, f.`ob_pl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '" . $year . "' WHERE i.`login_id` = '$leaveUserID'";
            $contLeaveInfo           = $this->db->query($contLeaveSql)->result_array();
            $tot_pl    = $contLeaveInfo[0]['ob_pl'];
            $tot_sl    = 0;
            $leaveINFO = $this->getLeaveTaken($leaveUserID, date("m"), date("Y"), 'A');
		}
		else if($userTypeInfo[0]['emp_type']  == 'CO')
		{
			$leaveINFO = $this->getLeaveTaken($leaveUserID, date("m"), date("Y"), 'A');
            $tot_pl    = $this->getMaxLeave($leaveUserID, 'P');
            $tot_sl    = $this->getMaxLeave($leaveUserID, 'S');
		} 
		$this->mViewData['leaveINFO'] = $leaveINFO;
		$this->mViewData['tot_pl'] = $tot_pl;
		$this->mViewData['tot_sl'] = $tot_sl;

		$ob_pl = $leaveINFO['ob_pl'];
		$this->mViewData['ob_pl'] = $leaveINFO['ob_pl'];
		$bal_pl = $tot_pl - $ob_pl;
		$this->mViewData['bal_pl'] = $tot_pl - $ob_pl;

		$ob_sl = $leaveINFO['ob_sl'];
		$this->mViewData['ob_sl'] = $leaveINFO['ob_sl'];
		$bal_sl = $tot_sl - $ob_sl;
		$this->mViewData['bal_sl'] = $bal_sl;

		$this->mViewData['tot_leave'] = $tot_pl + $tot_sl;
		$this->mViewData['tot_leave_taken'] = $ob_pl + $ob_sl;
		$this->mViewData['tot_leave_bal'] = $bal_pl + $bal_sl;
		//Template view
		$this->render('timesheet/leave_status_view', 'full_width', $this->mViewData);
	}
    public function view_members()
    {
        $this->mViewData['pageTitle'] = 'View Members';
		//$mysql_qry = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.user_status = '1' AND i.login_id != '10010' AND reporting_to = '".$this->session->userdata('user_id')."'";
		$mysql_qry = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.user_status = '1' AND i.login_id != '10010' AND reporting_to = '".$this->session->userdata('user_id')."'";
		if($this->session->userdata('user_type') == 'ADMINISTRATOR' || $this->session->userdata('user_type') == 'HR'){
			$mysql_qry = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.user_status = '1' AND i.login_id != '10010' ";
		}
		$result = $this->db->query($mysql_qry);
		$this->mViewData['result_arr'] = $result->result_array();
		$this->mViewData['totNoofRec'] = count($this->mViewData['result_arr']);
		//var_dump($totNoofRec);
		
        //Template view
		$this->render('timesheet/view_members_view', 'full_width', $this->mViewData); 
    }

	public function change_reporting()
	{
		$this->mViewData['pageTitle'] = 'Change Reporting Person';
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['user_id']))
		{
			$user_id = $_GET['user_id'];
		}
		// Get Reporting Manager
		$repMgrSql = "SELECT i.department, i.reporting_to, u.full_name, u.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` u ON i.reporting_to = u.login_id  WHERE i.login_id = '".$user_id."'";
		$repMgrRes = $this->db->query($repMgrSql);
		$this->mViewData['repMgrInfo'] = $repMgrRes->result_array();
		
		$this->mViewData['getReportingManager'] = $this->getReportingManager();
		//var_dump($this->mViewData['getReportingManager'] );
		//Template view
		$this->render('timesheet/change_reporting_view', 'full_width', $this->mViewData); 
		$this->load->view('script/timesheet/change_reporting_js'); 
	}

	public function update_reporting_manager_user()
	{
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['user_id']))
		{
			$user_id = $_GET['user_id'];
		}
		// Update Reporting Manager
		$repMgrSql = "UPDATE `internal_user` SET `reporting_to` = '".$this->input->post('reporting')."' WHERE `login_id` = '".$user_id."'";
		$repMgrRes = $this->db->query($repMgrSql);
		echo 1;
	}
	public function getReportingManager()
	{
		$reportingQrySelect = "SELECT i.login_id, CONCAT(i.full_name, ' (', i.loginhandle, ')') AS dispName FROM `internal_user` i WHERE i.login_id != '10010' AND i.user_status = '1' AND i.user_role < '5' ORDER BY i.full_name ASC";
		$reportingResSelect = $this->db->query($reportingQrySelect);
		$reportingInfoSelect = $reportingResSelect->result_array();
		$reportingArray = '';
		
		$reportingArray = substr($reportingArray, 1);

		return $reportingInfoSelect;
	}
    public function direct_regularise()
    {
        $this->mViewData['pageTitle'] = 'Direct regularise';
		$this->mViewData['emoInfo'] = $this->event_model->get_active_employee();
        //Template view 
		$this->render('timesheet/direct_regularise_view', 'full_width', $this->mViewData);
		$this->load->view('script/timesheet/direct_regularize_js');
    }
    public function directly_apply_for_regulaization_submit()
    {
		$fromDate = date("Y-m-d", strtotime($this->input->post('from_date')));
		$toDate = date("Y-m-d", strtotime($this->input->post('to_date')));
		$regInsSql = "INSERT INTO `attendance_request` (`user_id`, `rm_id`, `from_date`, `to_date`, `type`, `reason`, `status`, `act_date`) VALUES('".$this->input->post('employee')."', '".$this->input->post('reporting')."', '".$fromDate."', '".$toDate."', 'R', '".$this->input->post('txtReason')."', 'A', '".date("Y-m-d H:i:s")."')";
		$this->db->query($regInsSql);
		
		$appID = $this->db->insert_id();
		
		$dateRangeArr = date_range($fromDate, $toDate);
		$empSQL = "SELECT `login_id`,`loginhandle`, `full_name`,join_date, branch FROM `internal_user` WHERE `login_id` = '".$this->input->post('employee')."'";
		$empRES = $this->db->query($empSQL); 
		$empINFO = $empRES->result_array();
		foreach($dateRangeArr AS $dt)
		{
			// Check For all Day whether that day is holiday or not
			$chkHoliDaySql = "SELECT `ix_declared_leave` FROM `declared_leave` WHERE (`branch`='0' OR `branch`='".$empINFO[0]['branch']."') AND `f_disp_flag` = 1 AND `dt_event_date` = '$dt'   AND (`branch` = '0' OR `branch` = '".$this->session->userdata('branch')."')";
			$chkHoliDayRes = $this->db->query($chkHoliDaySql);
			$chkHoliDayNum = count($chkHoliDayRes->result_array());
			if($chkHoliDayNum < 1){
				// Check For Sunday
				if(date('N', strtotime($dt)) != 7){
					// Check that day is already present or not in attendance_new table
					$chkAttPSql = "SELECT `attendance_id`, `att_status` FROM `attendance_new` WHERE `login_id` = '".$this->input->post('employee')."' AND `date` = '$dt' LIMIT 1";
					$chkAttPRes = $this->db->query($chkAttPSql);
					$chkAttPInfo = $chkAttPRes->result_array();
					$chkAttPNum = count($chkAttPRes->result_array());
					if($chkAttPNum == 0)
					{
						// Insert Into  attendance_new As Regularize Present
						$insAttSql = "INSERT INTO attendance_new (`login_id`, `date`, `att_status`, `in_time`, `reason`) VALUES ('".$this->input->post('employee')."', '$dt', 'R', '".date("H:i:s")."', '".$this->input->post('txtReason')."')";
						$this->db->query($insAttSql);
					}
					else
					{
						
						if($chkAttPInfo[0]['att_status'] == 'H')
						{
							$sQryUpdate = "UPDATE attendance_new SET `in_time` = '".date("H:i:s")."' WHERE `attendance_id` = '".$chkAttPInfo[0]['attendance_id']."'";
							$this->db->query($sQryUpdate);
						}
						if($chkAttPInfo[0]['att_status'] == 'W'){
							$rQryUpdate = "UPDATE attendance_new SET `att_status` = 'R', reason='".$this->input->post('txtReason')."' WHERE `attendance_id` = '".$chkAttPInfo[0]['attendance_id']."'";
							$this->db->query($rQryUpdate);
						}
					}
					echo 0;
				}
				else{
					echo 1;
				}
			}
			else{
				echo 1;
			}
		}
		
		//Mail To Employee for Direct Regularisation And CC to reporting manager
		//$this->directRegularisationApproval($appID, $_SESSION['user_id']);
			
    }
    public function get_repoting_manager_for_emp()
    {
       $login_id = $this->input->post('login_id');
		$result = $this->event_model->get_repoting_manager_for_emp($login_id); 
		echo json_encode($result, true); 
    }
    public function direct_apply_for_leave()
    {
        $this->mViewData['pageTitle'] = 'direct apply for leave';

		
        //Template view 
		$this->render('timesheet/direct_apply_for_leave_view', 'full_width', $this->mViewData);
        $this->load->view('script/timesheet/datepicker_js');
    }
	public function employee_timesheet()
	{
		$this->mViewData['pageTitle'] = 'Employee Timesheet';
		$this->mViewData['viewMessage'] = TRUE;
		$this->mViewData['message'] = "Please enter Employee code to view employee's attendance year calender";
		$year = date("Y");
		$action = base_url('timesheet/employee_timesheet');
		$this->mViewData['dd_year'] = $year;
		$emp_code = "";
		$empINFO = array();
		if(isset($_GET['reqEmpid'])){
			//$empSQL = "SELECT iu.`login_id`, iu.`loginhandle`, iu.`full_name`,iu.join_date, iu.branch FROM `internal_user` as iu WHERE iu.`login_id` = '".$_GET['reqEmpid']."' ORDER BY iu.login_id DESC";
			$empSQL = "SELECT iu.`login_id`, iu.`loginhandle`, iu.`full_name`,iu.join_date, iu.branch, iul.join_date as join_date_log 
				FROM `internal_user` as iu 
				left join internal_user_change_log as iul ON iul.login_id=iu.login_id  
				WHERE iu.`login_id` = '".$_GET['reqEmpid']."' ORDER BY iul.log_id ASC LIMIT 1 ";
			$empRES = $this->db->query($empSQL); 
			$empINFO = $empRES->result_array();
		}
		
		
		if($this->input->post('btnView') == 'View'){
			//if(($this->input->post('emp_code') != ''))
			//{
				if($this->input->post('dd_year') > 0)
				{
					$year = $this->input->post('dd_year');
				}
				//$empSQL = "SELECT `login_id`, `loginhandle`, `full_name`,join_date, branch FROM `internal_user` WHERE `loginhandle` = '".$this->input->post('emp_code')."'  ORDER BY login_id DESC";
				$empSQL = "SELECT iu.`login_id`, iu.`loginhandle`, iu.`full_name`,iu.join_date, iu.branch, iul.join_date as join_date_log 
				FROM `internal_user` as iu 
				left join internal_user_change_log as iul ON iul.login_id=iu.login_id  
				WHERE iu.`loginhandle` = '".$this->input->post('emp_code')."' ORDER BY iul.log_id ASC LIMIT 1 ";
				$empRES = $this->db->query($empSQL); 
				$empINFO = $empRES->result_array();
			//}
		}
		//print_r($empINFO);
		$empNUM = count($empINFO);
		if($empNUM > 0)
		{
			$emp_code = $empINFO[0]['loginhandle'];
			$this->mViewData['viewMessage'] = FALSE; 
			$login_id = $empINFO[0]['login_id'];
			$this->mViewData['empName'] = $empINFO[0]['full_name'];
			$joindate =date("Y-m-d",strtotime($empINFO[0]['join_date'])) ; 
			if($empINFO[0]['join_date_log'] !=""){
				$joindate =date("Y-m-d",strtotime($empINFO[0]['join_date_log'])) ; 
			}
			
			$monthNames = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
			$dayNames = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
			
			// Get All Declared Holidays
			$declaredHolidayArray[] = '';
			$holidaySql = "SELECT `dt_event_date`, `s_event_name` FROM `declared_leave` WHERE (`branch`='0' OR `branch`='".$empINFO[0]['branch']."') AND `f_disp_flag` = 1 AND DATE_FORMAT(`dt_event_date`, '%Y') = $year";
			$holidayRes = $this->db->query($holidaySql);
			$holidayInfo = $holidayRes->result_array();
			//var_dump($holidayInfo_arr); 
			$contdate=1;
			for($i=0;$i < count($holidayInfo);$i++)
			{
				$keyname = $holidayInfo[$i]['s_event_name'];
				$contdate++;
				if(($keyname == 'Sunday') OR ($keyname == 'First Saturday') OR ($keyname == 'Third Saturday') )
				{
					$keyname = $holidayInfo[$i]['s_event_name'].$contdate;
				}
				$declaredHolidayArray[$keyname] = $holidayInfo[$i]['dt_event_date'];
			}
				
			
			
			// Get All Present, Leave & Regularize days Info
			$attendanceArray[] = $attendanceStatusArray[] = $attendanceInArray[] = $attendanceOutArray[] = $attendanceReasonArray[] = '';
			$attendanceSql = "SELECT `date`, `att_status`, `in_time`, `out_time`, `reason` FROM `attendance_new` WHERE `login_id` = '".$login_id."' AND DATE_FORMAT(`date`, '%Y') = $year"; 
			//echo $attendanceSql;
			$attendanceRes = $this->db->query($attendanceSql);
			$attendanceInfo = $attendanceRes->result_array();
			for($j=0;$j < count($attendanceInfo);$j++)
			{
				$attendanceArray[] = $attendanceInfo[$j]['date'];
				$attendanceStatusArray[] = $attendanceInfo[$j]['att_status'];
				$attendanceInArray[] = $attendanceInfo[$j]['in_time'];
				$attendanceOutArray[] = $attendanceInfo[$j]['out_time'];
				$attendanceReasonArray[] = $attendanceInfo[$j]['reason'];
			} 
			for($k = 0; $k<13; $k++)
			{
				$present = $leave = $absent = $holiday = 0;
				$date = 0;
				if($k != 0)
				{
					$days = cal_days_in_month(CAL_GREGORIAN,$k,$year);
					$firstDay = date("N", strtotime($k.'/01/'.$year));
					$firstDay = ($firstDay == 7)?0:$firstDay;
				}
				for($d = 0; $d<37; $d++)
				{
					if($k == 0)
					{ 
						if(($d%7 == 0))
						{
							$dayClass ='sunday';
							$this->mViewData['calRow'][$d] = '<td  style="width:8.1%;"><div class="sunday">'.$dayNames[$d%7].'</div></td>';
						}
						elseif(($d%6 == 0) OR ($d%20 == 0))
						{ 
							$dayClass ='saturday';
							$this->mViewData['calRow'][$d] = '<td><div class="sunday">'.$dayNames[$d%7].'</div></td>';
						} 
						else
						{ 
							$dayClass ='otherday';
							$this->mViewData['calRow'][$d] = '<td><div class="'.$dayClass.'">'.$dayNames[$d%7].'</div></td>';
						}
					}
					else
					{
						if($d >= $firstDay && $date < $days)
						{
							$date++;
							$kool = date("Y-m-d", strtotime($date.'-'.$k.'-'.$year));
							if($holidayFor = array_search($kool, $declaredHolidayArray))
							{
								if (strpos($holidayFor, "Sunday") !== false)
								{
									$holidayFor="Sunday";
								}
								elseif(strpos($holidayFor, "First Saturday") !== false)
								{
									$holidayFor="First Saturday";
								}
								elseif(strpos($holidayFor, "Third Saturday") !== false)
								{
									$holidayFor="Third Saturday";
								}
								$holiday++;
								$this->mViewData['calRow'][$d] .= '<td><div class=" holidays"  title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span><br/> '.$holidayFor.'"><div class="black_dates">'.$date.'</div></div></td>';
							}  
							else
							{
								if((NEW_ATTENDANCE_START <= $kool) && ($joindate <=$kool))
								{
									if($attVic = array_search($kool, $attendanceArray))
									{
										if($attendanceStatusArray[$attVic] == 'P')
										{
											$present++;
											$outTime = '';
											$inTime = date("g:i A", strtotime($attendanceInArray[$attVic]));
											if($attendanceOutArray[$attVic] != '00:00:00'){
												$outTime = date("g:i A", strtotime($attendanceOutArray[$attVic]));
											}
											$this->mViewData['calRow'][$d] .= '<td><div class=" attend_day"  title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span><br/> Present ('.$inTime.' - '.$outTime.')"><div class="white_dates">'.$date.'</div></div></td>';
										}
										elseif($attendanceStatusArray[$attVic] == 'W')
										{
											$present++;
											$outTime = '';
											$inTime = date("g:i A", strtotime($attendanceInArray[$attVic]));
											if($attendanceOutArray[$attVic] != '00:00:00')
											{
												$outTime = date("g:i A", strtotime($attendanceOutArray[$attVic]));
											}
											$this->mViewData['calRow'][$d] .= '<td><div class=" lessworking_day"  title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span><br/> LW Hours ('.$inTime.' - '.$outTime.')"><div class="white_dates">'.$date.'</div></div></td>';
										}
										elseif($attendanceStatusArray[$attVic] == 'H')
										{
											// get Which Half
											$whichHalf = $this->getWhichHalfLeave($login_id, date("Y-m-d", strtotime($date.'-'.$k.'-'.$year)));
											$leave = $leave + 0.5;
											$hType = 'L';
											if(date("Y-m-d") >= $kool)
											{
												if($attendanceInArray[$attVic] != '00:00:00')
												{
													$present = $present + 0.5;
													$hType = 'P';
												}
												else
												{
													$absent = $absent + 0.5;
													$hType = 'A';
												}
											}
											if($whichHalf == 'F')
											{
												if($hType == 'P')
												{
													$bgClass = 'half_present_1';
												}
												elseif($hType == 'A')
												{
													$bgClass = 'half_absent_1';
												}
												else
												{
													$bgClass = 'half_leave_1';
												}
											}
											else
											{
												if($hType == 'P')
												{
													$bgClass = 'half_present_2';
												}
												elseif($hType == 'A')
												{
													$bgClass = 'half_absent_2';
												}
												else
												{
													$bgClass = 'half_leave_2';
												}
											}
											$outTime = '';
											$inTime = '';
											if($attendanceInArray[$attVic] != '00:00:00')
											{
												$inTime = date("g:i A", strtotime($attendanceInArray[$attVic]));
											}
											if($attendanceOutArray[$attVic] != '00:00:00')
											{
												$outTime = date("g:i A", strtotime($attendanceOutArray[$attVic]));
											}
											$this->mViewData['calRow'][$d] .= '<td><div class=" '.$bgClass.'"  title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span><br/> '.$attendanceReasonArray[$attVic].' ('.$inTime.' - '.$outTime.')"><div class="white_dates">'.$date.'</div></div></td>';
										}
										elseif($attendanceStatusArray[$attVic] == 'L')
										{
											$leave++;
											$this->mViewData['calRow'][$d] .= '<td><div class=" leave_day" title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span><br/> '.$attendanceReasonArray[$attVic].'"><div class="white_dates">'.$date.'</div></div></td>';
										}
										elseif($attendanceStatusArray[$attVic] == 'R')
										{
											$outTime = '';
											$inTime = '';
											if($attendanceInArray[$attVic] != '00:00:00')
											{
												$inTime = date("g:i A", strtotime($attendanceInArray[$attVic]));
											}
											if($attendanceOutArray[$attVic] != '00:00:00')
											{
												$outTime = date("g:i A", strtotime($attendanceOutArray[$attVic]));
											}
											$present++;
											$this->mViewData['calRow'][$d] .= '<td><div class=" regularize_day" title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span><br/> '. ' (' . $inTime . ' - ' . $outTime . ') <br/>' .$attendanceReasonArray[$attVic].'"><div class="white_dates">'.$date.'</div></div></td>';
										}
									}
									else
									{
										if(date("Y-m-d") < $kool)
										{
											$this->mViewData['calRow'][$d] .= '<td><div class="black_dates">'.$date.'</div></td>';
										}
										else
										{
											$absent++;
											$this->mViewData['calRow'][$d] .= '<td><div class=" absent_day" title="<span>'.date("jS M, Y", strtotime($date.'-'.$k.'-'.$year)).'</span><br/> Absent"><div class="white_dates">'.$date.'</div></div></td>';
										}
									}
								}
								else
								{
									$this->mViewData['calRow'][$d] .= '<td><div class="black_dates">'.$date.'</div></td>';
								}
							}
						}
						else
						{
							$this->mViewData['calRow'][$d] .= '<td>&nbsp;</td>';
						}
					}
				}
				if($k != 0)
				{
					$v = $k - 1;
					$presentArray[$v] = $present;
					$absentArray[$v] = $absent;
					$leaveArray[$v] = $leave;
					$holidayArray[$v] = $holiday;
					$totaldayArray[$v] = $days;
				}
			}
			
			$this->mViewData['monthHeader'] = '';
			for($k = 0; $k < 12; $k++)
			{
				$monthName = $monthNames[$k];
				$payDays   = $presentArray[$k] + $holidayArray[$k];
				if($totaldayArray[$k] == $payDays)
				{
					$this->mViewData['monthHeader'] .= ' <th>
						<div class=" img"
						title="Present - ' . $presentArray[$k] . ' <br/> Leave - ' . $leaveArray[$k] . ' <br/> Absent - ' . $absentArray[$k] . ' <br/> Holiday - ' . $holidayArray[$k] . '">' . $monthName . '</div></th>';
				}
				else
				{ 
					$this->mViewData['monthHeader'] .= '<th><div class="" 
															title="Present&nbsp;- '.$presentArray[$k].' <br/> Leave &nbsp;&nbsp; - ' . $leaveArray[$k] . ' <br/> Absent &nbsp; - ' . $absentArray[$k] . ' <br/> Holiday - ' . $holidayArray[$k] . '">' . $monthName . '</div></th>';
				}
			}
		}
		else
		{
			$this->mViewData['message'] = 'There is no employee exists against the emplooyee code.';
		}
		
		$this->mViewData['dd_year'] = $year;
		$this->mViewData['emp_code'] = $emp_code;
        //Template view 
		$this->render('timesheet/employee_timesheet_view', 'full_width', $this->mViewData);
		
	}
	public function leave_app_mgt_emp()
	{
		$this->mViewData['pageTitle'] = 'Employee Timesheet';
		/* if($this->input->post('submitStatus') == 'ok')
		{
			$leaveFrom = date("Y-m-d", strtotime($this->input->post('from_date')));
			$leaveTo = date("Y-m-d", strtotime($this->input->post('to_date')));
			$insQry = "INSERT INTO `leave_application` (`user_id`, `rp_mgr_id`, `leave_type`, `leave_from`, `leavefromhalfday`, `leave_to`, `leavetohalfday`, `absence_reason`, `contact_details`, `status`, `action_dt`) 
					   VALUES 
					   ('".$this->input->post('employee')."', '".$this->input->post('reporting')."', '".$this->input->post('leave_type')."', '".$leaveFrom."', '".$this->input->post('halfday1')."', '".$leaveTo."', '".$this->input->post('halfday2')."', '".$this->input->post('txtReason')."', '".$this->input->post('txtDetails')."', 'A', '".date("Y-m-d H:i:s")."')";
			$this->db->query($insQry);
			
			$appID = $this->db->insert_id();
			
			// Process Leave Request (mark those dates as leave and deduct leave balance)
			$this->leaveApproveProcess($appID);
			
			// Mail to Employee & CC to reporting
			$this->directLeaveApproval($appID, $this->session->userdata('user_id'));
				
			// Redirect to this page with success message
			header("location:leave_app_mgt_emp?akns=success");
			exit();
		} */
		
		$this->mViewData['emoInfo'] = $this->event_model->get_active_employee();
		//Template view 
		$this->render('timesheet/leave_app_mgt_emp_view', 'full_width', $this->mViewData);
        $this->load->view('script/timesheet/direct_leave_apply_js');
	}
	
	
    public function get_direct_leave_emp_details()
    {
		$loginID = $this->input->post('login_id');
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, r.login_id as reporting_login_id, r.loginhandle as reporting_loginhandle, r.full_name as reporting_full_name FROM `internal_user` i
			INNER JOIN `internal_user` r ON i.reporting_to = r.login_id
			WHERE i.login_id != '10010' AND i.user_status = '1' AND i.`login_id` = '" . $loginID . "'");
		$empinfo = $sql->result_array();
        $res = $this->timesheet_model->get_leave_balance_details($loginID);
        $result = count($res);
        for ($i = 0; $i < $result; $i++) {
            $emp_type = $res[$i]['emp_type'];
        }
        if($emp_type == 'F')
		{
            $maxPL               = $this->getMaxLeave($loginID, 'P');
            $maxSL               = $this->getMaxLeave($loginID, 'S');
            $maxLeave            = $maxPL + $maxSL;
            $leaveINFO           = $this->getLeaveTaken($loginID, date("m"), date("Y"), 'A');
            $avlPL = $maxPL - $leaveINFO['ob_pl'];
            $avlSL = $maxSL - $leaveINFO['ob_sl'];
            $totAvlleave         = $maxLeave > $leaveINFO['ob_pl'] + $leaveINFO['ob_sl'];
        }
		else
		{
            $maxPL         = 0;
            $maxSL         = 0;
            $maxLeave      = 0;
            $leaveINFO     = 0;
            $avlPL         = 0;
            $avlSL         = 0;
            $year          = date("Y");
            $contLeaveSql  = "SELECT i.`join_date`, f.`ob_pl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '" . $year . "' WHERE i.`login_id` = '" . $loginID . "'";
            $contLeaveRes  = $this->db->query($contLeaveSql);
            $contLeaveInfo = $contLeaveRes->result_array();
            $maxPL         = $contLeaveInfo[0]['ob_pl'];
            $leaveINFO     = $this->getLeaveTaken($loginID, date("m"), date("Y"), 'A');
            $avlPL         = $maxPL - $leaveINFO['ob_pl'];
            $totAvlleave   = $maxPL > $leaveINFO['ob_pl'];
			$avlPL = $avlPL;
            $avlSL = $avlSL;
        }
		
		$submitMSG = "";
		$retData = array(
			'login_id' => $empinfo[0]['login_id'],
			'full_name' => $empinfo[0]['full_name'],
			'loginhandle' => $empinfo[0]['loginhandle'],
			'reporting_login_id' => $empinfo[0]['reporting_login_id'],
			'reporting_full_name' => $empinfo[0]['reporting_full_name'],
			'reporting_loginhandle' => $empinfo[0]['reporting_loginhandle'],
			'avlPL' => $avlPL,
			'avlSL' => $avlSL,
		);
		echo json_encode($retData, TRUE);
    }
	
	
    public function direct_apply_for_leave_submit()
    {
		$loginID = $this->input->post('login_id');
		$leaveFrom = date("Y-m-d", strtotime($this->input->post('from_date')));
		$leaveTo = date("Y-m-d", strtotime($this->input->post('to_date')));
		$insQry = "INSERT INTO `leave_application` (`user_id`, `rp_mgr_id`, `leave_type`, `leave_from`, `leavefromhalfday`, `leave_to`, `leavetohalfday`, `absence_reason`, `contact_details`, `status`, `action_dt`) 
				   VALUES 
				   ('".$loginID."', '".$this->input->post('reporting')."', '".$this->input->post('leave_type')."', '".$leaveFrom."', '".$this->input->post('halfday1')."', '".$leaveTo."', '".$this->input->post('halfday2')."', '".$this->input->post('txtReason')."', '".$this->input->post('txtDetails')."', 'A', '".date("Y-m-d H:i:s")."')";
		$this->db->query($insQry);
		
		$appID = $this->db->insert_id();
		
		// Process Leave Request (mark those dates as leave and deduct leave balance)
		$this->leaveApproveProcess($appID);
		
		// Mail to Employee & CC to reporting
		$this->directLeaveApproval($appID, $loginID);
		echo 2;
    }
	
	
	function directLeaveApproval($appID, $userID)
	{
		

		$userSql = "SELECT l.application_id, l.leave_from, l.leavefromhalfday, l.leave_to, l.leavetohalfday, i.email AS empEmail, i.full_name AS empName, u.email AS rmEmail, u.full_name AS rmName, u.loginhandle AS rmEmpCode FROM `leave_application` l JOIN `internal_user` i ON l.user_id = i.login_id JOIN `internal_user` u ON l.rp_mgr_id = u.login_id  WHERE l.application_id = '$appID'";
		$userRes = $this->db->query($userSql);
		$userInfo = $userRes->result_array();
		
		$hrSql = "SELECT `email`, `full_name` FROM `internal_user` WHERE `login_id` = '$userID'";
		$hrRes = $this->db->query($hrSql);
		$hrInfo = $hrRes->result_array();
		
		$leaveRangeText = '';
		if($userInfo[0]['leavefromhalfday'] == 'Y'){
			$leaveRangeText .= 'Second Half of ';
		}
		$leaveRangeText .= date("jS F, Y", strtotime($userInfo[0]['leave_from']));
		$leaveRangeText .= ' to ';
		if($userInfo[0]['leavetohalfday'] == 'Y'){
			$leaveRangeText .= 'First Half of ';
		}
		$leaveRangeText .= date("jS F, Y", strtotime($userInfo[0]['leave_to']));

		$rmName = $userInfo[0]['rmName'];
		$rmEmail = $userInfo[0]['rmEmail'];

		$empName = $userInfo[0]['empName'];
		$to = $empName.'<'.$userInfo[0]['empEmail'].'>';
		// subject
		$subject = 'Direct Leave Approved';
		$message =$messageEmp ='';
			$site_base_url=base_url('timesheet/my_leave_application');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
                <img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.base_url().'assets/images/logo.gif" />
            </div>';
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
		$message = '
		<html>
		<head>
		  <title>POLOHRM Direct leave application Approved</title>
		</head>
		<body>
		<div id="icompass" style="width:615px;margin:0 auto;">';
		$message .=$logoText;
		$message .= '<div>
					<span style="color:#FF6600;font-size:14px;font-weight:bold;line-height:20px">Dear '.$empName.',</span>
					<br />
					<p>Your direct leave request from '.$leaveRangeText.' has been approved as per the request of your reporting manager <strong>'.$rmName.'</strong>. Please click on the below link to to see the details.
					<br /><br/>
					<a href="'.$site_base_url.'" style="text-decoration:none">Click Here for view details</a><br /><br /></p>
				</div>';
				
		//$message .=$fromAABSySiCompass;
		$message .=$footer;
		$message .= '
		</div>
		</body>
		</html>
		';

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";  
		$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		
		if(mail($to, $subject, $message, $headers)){
			//appendToSuccessMailLog($to,"Mail Sent by HR People To respective Employee  & reporting manager For Direct Leave Approval");
		}
	}
	
	public function leaveApproveProcess($applicationID)
	{
		$getReqSql = "SELECT * FROM `leave_application` WHERE `application_id` = '".$applicationID."' AND `status` = 'A' LIMIT 1";
		$getReqRes = $this->db->query($getReqSql);
		$getReqInfo = $getReqRes->result_array();
		$userID = $getReqInfo[0]['user_id'];
		$leaveType = $getReqInfo[0]['leave_type'];
		
		$dateRangeArr = date_range($getReqInfo[0]['leave_from'], $getReqInfo[0]['leave_to']);
		foreach($dateRangeArr AS $dt){
			$chkHoliDaySql =  $this->db->query("SELECT `ix_declared_leave` FROM `declared_leave` WHERE `f_disp_flag` = 1 AND `dt_event_date` = '$dt'  AND (`branch` = '0' OR `branch` = '".$this->session->userdata('branch')."')");
			$chkHoliDayRes = $chkHoliDaySql->result_array();
			$chkHoliDayNum = COUNT($chkHoliDayRes);
			if($chkHoliDayNum < 1){
				
				$status = 'L';
				$leaveDeduct = 1;
				
				if($getReqInfo[0]['leavefromhalfday'] == 'Y' && $getReqInfo[0]['leave_from'] == $dt){
					$status = 'H';
					$leaveDeduct = 0.5;
				}
				if($getReqInfo[0]['leavetohalfday'] == 'Y' && $getReqInfo[0]['leave_to'] == $dt){
					$status = 'H';
					$leaveDeduct = 0.5;
				}
				
				$chkAttPSql = $this->db->query("SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '".$userID."' AND `date` = '$dt' LIMIT 1");
				$chkAttPRes = $chkAttPSql->result_array();
				$chkAttPNum = COUNT($chkAttPRes);
				$reason = $getReqInfo[0]['absence_reason'];
				//$reason = $reason.replace(/["']/g, "");
				//$reason = str_replace('"', '\"', $reason);
				//$reason = str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($reason ) );
				$inTime = '00:00:00';
				if($chkAttPNum < 1){
					if($status != 'H'){
						$inTime = date("H:i:s");
					}
					if($leaveType != "M")
					{
						$this->updateLeave($dt, $userID, $leaveType, $leaveDeduct);	
					}					
					
					$login_id = $userID;
					$date = $dt;
					$att_status = $status;
					$in_time = $inTime;
					$leave_type = $leaveType;
					$insAttSql = $this->timesheet_model->insert_attendance_on_leaver_apply($login_id, $date, $att_status, $in_time, $reason, $leave_type);
								
					//$insAttSql = $this->db->query("INSERT INTO `attendance_new` (`login_id`, `date`, `att_status`, `in_time`, `reason`, `leave_type`) VALUES ('".$userID."', '$dt', '$status', '".$inTime."', '".$reason."', '".$leaveType."')");
					$ret=1;
				}else{
					if($leaveType != "M")
					{
						$this->updateLeave($dt, $userID, $leaveType, $leaveDeduct);	
					}
					$login_id = $userID;
					$date = $dt;
					$att_status = $status;
					$in_time = $inTime;
					$leave_type = $leaveType;
					$upAttSql = $this->timesheet_model->update_attendance_on_leaver_apply($att_status, $reason, $leave_type, $login_id, $date);
					
					///$upAttSql = $this->db->query("UPDATE `attendance_new` SET `att_status` = '$status', `reason` = '".$reason."', `leave_type` = '".$getReqInfo[0]['leave_type']."' WHERE `login_id` = '".$userID."' AND `date` = '".$dt."' LIMIT 1");
					$ret=1;
				}
			}
		}
			return $ret;
	}
	
	
	public function updateLeave($dt, $userID, $leaveType, $leaveDeduct)
	{
		$m = date("m", strtotime($dt));
		$y = date("Y", strtotime($dt));
		$userTypeSql=$this->db->query("SELECT i.emp_type FROM `internal_user` i WHERE i.`login_id` = '".$userID."'");
		$userTypeInfo = $userTypeSql->result_array();
		$chkleaveSql = $this->db->query("SELECT `leave_id`, `ob_pl`, `ob_sl` FROM `leave_info` WHERE `login_id` = '".$userID."' AND `month` = '$m' AND `year` = '$y' LIMIT 1");
		$chkleaveRes = $chkleaveSql->result_array();
		$chkleaveNum = COUNT($chkleaveRes);
		if($chkleaveNum < 1){
			$pl = $sl = 0;
			if($leaveType == 'P'){
				$pl = $leaveDeduct;
			}elseif($leaveType == 'S'){
				$sl = $leaveDeduct;
			}
			$insLeaveSql = "INSERT INTO `leave_info` (`login_id`, `month`, `year`, `ob_pl`, `ob_sl`) VALUES ('".$userID."', '".$m."', '".$y."', '".$pl."', '".$sl."')";
			$this->db->query($insLeaveSql);
			
		}else{
			$pl = $chkleaveRes[0]['ob_pl'];
			$sl = $chkleaveRes[0]['ob_sl'];
			if($leaveType == 'P'){
				$pl = $pl + $leaveDeduct;
			}elseif($leaveType == 'S'){
				$sl = $sl + $leaveDeduct;
			}
			$upLeaveSql = "UPDATE `leave_info` SET `ob_pl` = '".$pl."', `ob_sl` = '".$sl."' WHERE `login_id` = '".$userID."' AND `month` = '".$m."' AND `year` = '".$y."' LIMIT 1";
			$this->db->query($upLeaveSql);
		}
	}
	public function getValue($table_name,$field_name,$where_cond='1',$debug='')
	{
		$value = '';
		$mysql = 'SELECT '.$field_name.' FROM '.$table_name.' WHERE '.$where_cond;
		if($debug == 1)
		{
			echo($mysql);
		} 	
		$result = $this->db->query($mysql);
		$rec = count($result->result_array());
		if($rec > 0)
		{
		$row = $result_array();
		$value = stripslashes($row[0]);
		}
		return $value;
	}
	/**
	 * Get Dates between two dates.
	 *
	 *
	 * @param	date
	 * @param	date
	 * @return	array
	 */
	public function date_range($sd,$ed)
	{
		$tmp = array();
		$sdu = strtotime($sd);
		$edu = strtotime($ed);
		while ($sdu <= $edu) {
			$tmp[] = date('Y-m-d',$sdu);
			$sdu = strtotime('+1 day',$sdu);
		}
		return $tmp;
	}
    public function json_response($return)
    {
        ob_start("ob_gzhandler");
        header("Content-type: application/json");
        echo json_encode($return);
        ob_end_flush();
    }
	
	/*********************  AMS  *************************/
	
	public function allot_machine()
	{
		$this->mViewData['pageTitle'] = 'AMS Allot Machine';
		$emp_code = $this->session->userdata('empCode');
		if($this->session->userdata('user_type') == 'ADMINISTRATOR'){
			$emp_code ="";
		}
		$this->mViewData['machines'] = $this->ams_model->getOwnershipWithAllotMachine($emp_code);
		$this->mViewData['emp'] = $this->ams_model->get_active_employee();
		//Template view 
		$this->render('timesheet/allot_machine_view', 'full_width', $this->mViewData);
        $this->load->view('script/timesheet/allot_machine_view_js');
	}
	
	public function release_machine_rm()
	{
		$emp_code = $this->session->userdata('empCode');
		$ix_machine = $this->input->post('ix_machine');
		$ownership = $this->input->post('ownership');
		$result = $this->ams_model->releaseMachineRM($ix_machine, $ownership);
		echo 1;
	}
	
	public function delete_employee_from_machine_morning_rm()
	{
		$emp_code = $this->session->userdata('empCode');
		$ix_machine = $this->input->post('ix_machine');
		$employee = $this->input->post('employee');
		$result = $this->ams_model->deleteEMployeeFromMachineMorningRM($ix_machine, $employee);
		echo 1;
	}
	
	public function delete_employee_from_machine_general_rm()
	{
		$emp_code = $this->session->userdata('empCode');
		$ix_machine = $this->input->post('ix_machine');
		$employee = $this->input->post('employee');
		$result = $this->ams_model->deleteEMployeeFromMachineGeneralRM($ix_machine, $employee);
		echo 1;
	}
	
	public function delete_employee_from_machine_evening_rm()
	{
		$emp_code = $this->session->userdata('empCode');
		$ix_machine = $this->input->post('ix_machine');
		$employee = $this->input->post('employee');
		$result = $this->ams_model->deleteEMployeeFromMachineEveningRM($ix_machine, $employee);
		echo 1;
	}
	
	public function add_employee_to_machine_rm()
	{
		$emp_code = $this->session->userdata('empCode');
		$product_id = $this->input->post('product_id');
		$shift_type = $this->input->post('shift_type');
		$source_id = $this->input->post('source_id');
		$result = $this->ams_model->addEMployeeToMachineRM($emp_code, $product_id, $shift_type, $source_id);
		echo 1;
	}
	
	
	/*********************  END\ AMS  *************************/
}