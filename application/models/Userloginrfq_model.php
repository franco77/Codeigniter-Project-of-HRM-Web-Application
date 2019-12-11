<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userloginrfq_model extends CI_Model {
	
	 public function __construct()
        {
			parent::__construct();
                $this->load->database();
        }
	
	public function rfq_login($username, $password)
	{
		$this->db->select('internal_user.login_id,internal_user.loginhandle,internal_user.email,internal_user.full_name,rfq_users.user_role_id,rfq_role.role_name');
		$this->db->from('internal_user');
		$this->db->join('rfq_users','rfq_users.login_id = internal_user.login_id');
		$this->db->join('rfq_role','rfq_role.id = rfq_users.user_role_id');
		$this->db->where('internal_user.loginhandle',$username);
		$this->db->where('internal_user.password', md5(trim($password)));
		$this->db->where('internal_user.user_status', '1');
		//$this->db->where('user_role_id',1);
		//$this->db->order('');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}

	public function rfq_list()
	{
		$this->db->select('internal_user.login_id,internal_user.loginhandle as emp_code,internal_user.email,internal_user.full_name,rfq_users.user_role_id, DATE_FORMAT(rfq_users.user_created_at,"%d-%m-%Y") as user_created_at,rfq_role.role_name');
		$this->db->from('internal_user');
		$this->db->join('rfq_users','rfq_users.login_id = internal_user.login_id');
		$this->db->join('rfq_role','rfq_role.id = rfq_users.user_role_id');
		$this->db->where('rfq_users.user_role_id !=', '1');
		$this->db->where('internal_user.user_status', '1');
		$query = $this->db->get();
		return $query->result();
	}

	public function rfqusers()
	{
		$this->db->select('internal_user.login_id,internal_user.loginhandle as emp_code,internal_user.email,internal_user.full_name,rfq_users.user_role_id, DATE_FORMAT(rfq_users.user_created_at,"%d-%m-%Y") as user_created_at,rfq_role.role_name');
		$this->db->from('rfq_users');
		$this->db->join('internal_user','rfq_users.login_id = internal_user.login_id');
		$this->db->join('rfq_role','rfq_role.id = rfq_users.user_role_id');
		$this->db->where('rfq_users.user_role_id !=', '1');
		$this->db->where('internal_user.user_status', '1');
		$query = $this->db->get();
		return $query->result();
	}

	public function employeelist()
	{
		$this->db->select('internal_user.login_id,internal_user.loginhandle as emp_code,internal_user.email,internal_user.full_name');
		$this->db->from('internal_user');
		//$this->db->join('rfq_users', 'rfq_users.user_role_id = internal_user.user_role');
		$this->db->where('internal_user.user_status', '1');
		$this->db->where('internal_user.login_id !=10010');
		$this->db->where('internal_user.user_role=2 OR internal_user.user_role=3 OR internal_user.user_role=4 OR internal_user.user_role=5');
		$this->db->order_by('internal_user.full_name', 'asc');
		$query = $this->db->get();
		return $query->result();
	}
	

	public function insert_data($role, $login_id)
	{
		$array = array(
			'login_id' => $login_id,
			'user_role_id' => $role,
			'user_created_at' => date('Y-m-d'),
			'user_created_by' => 1
		);
		$this->db->insert('rfq_users', $array);
		return $this->db->insert_id();
	}
	

	public function deleterecords($id)
	{
		$this->db->where('login_id',$id);
		$this->db->delete('rfq_users');
		return true;
	}

	public function getrecords($id)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('user_id',$id);
		$query = $this->db->get();
		return $query->result();
	}
	
	
	public function get_projectrecord()
	{
		$this->db->select('rfq_nature_of_project.id, rfq_nature_of_project.name itemName');
		$this->db->from('rfq_nature_of_project');
		$query = $this->db->get();
		return $query->result();
	}	

	public function roles()
	{
		$this->db->select('rfq_role.*');
		$this->db->from('rfq_role');
		$query = $this->db->get();
		return $query->result();
	}
	
	
	public function save_vendor_records($keywords, $projectname, $filename, $attachfile, $instruction, $user, $pjct_type, $country, $client, $currency, $project_name, $vendor_name, $vendor_type, $scope_of_work, $total_cost, $currency_code, $gst_inclusive, $vendorfilename)
	{
		
		$current_date = date('Y-m-d');
		for($i=0;$i<count($projectname);$i++)
		{
			$file[$i] = $projectname[$i]->itemName; 
		}
		$myfilename = implode(',', $file);
		$serialize = serialize($projectname);
		$serial_file = serialize($filename);
		//$vendor_file = serialize($vendorfilename);
		$vendor_file = "";
		if(count($vendorfilename)>0){
			$vendor_file = $vendorfilename[0];
		}
		$array = array(
			'filename' => $serial_file,
			'projectname' => $project_name,
			'attach_filename' => $attachfile,
			'keywords' => $keywords,
			'project_id' => $serialize,
			'project_name' => $myfilename,
			'project_type' => $pjct_type,
			'text_instruction' => $instruction,
			'client_name' => $client,
			'territory' => $country,
			'currency' => $currency,	
			'add_estimation_submit' => $user,	
			'add_estimation_approved' => $user,	
			'total_estimate' => $total_cost,	
			'estimate_file' => $vendor_file,	
			'request_type' => 'Vendor',	
			'vendor_name' => $vendor_name,	
			'vendor_type' => $vendor_type,	
			'created_by' => $user,
			'created_at' => $current_date	
		);
		
		$this->db->insert('rfq_request_estimate', $array);
		$lunch_req_id =  $this->db->insert_id();
		
		$datas = array(
			'lunch_req_id' => $lunch_req_id,
			'scope_of_work' => $scope_of_work,
			'total_cost' => $total_cost,
			'currency_code' => $currency_code,
			'gst_inclusive' => $gst_inclusive,
			'vendor_file' => $vendor_file	
		);
		
		$this->db->insert('rfq_vendor_estimate', $datas);
		
		
		
		return $lunch_req_id;
	}
	
	
	public function save_records($keywords, $projectname, $filename, $attachfile, $instruction, $user, $pjct_type, $country, $client, $currency, $project_name, $date="")
	{
		if($date != "")
		{
			$current_date = $date;
		}
		else{
			$current_date = date('Y-m-d');
		}
		for($i=0;$i<count($projectname);$i++)
		{
			$file[$i] = $projectname[$i]->itemName; 
		}
		$myfilename = implode(',', $file);
		$serialize = serialize($projectname);
		$serial_file = serialize($filename);
		$array = array(
			'filename' => $serial_file,
			'projectname' => $project_name,
			'attach_filename' => $attachfile,
			'keywords' => $keywords,
			'project_id' => $serialize,
			'project_name' => $myfilename,
			'project_type' => $pjct_type,
			'text_instruction' => $instruction,
			'client_name' => $client,
			'territory' => $country,
			'currency' => $currency,	
			'created_by' => $user,
			'created_at' => $current_date	
		);
		
		$this->db->insert('rfq_request_estimate', $array);
		return $this->db->insert_id();
	}
	
	
	
	
	public function get_records($id, $roll_id)
	{
		$this->db->select('rfq_request_estimate.*, rfq_request_estimate.territory as country, internal_user.full_name');
		$this->db->from('rfq_request_estimate');
		$this->db->join('internal_user','internal_user.login_id = rfq_request_estimate.created_by');
		if($roll_id != "3" && $roll_id != "2" && $roll_id != "6")
		{
			$this->db->where('created_by', $id);	
		}
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		//$this->db->join('rfq_nature_of_project','rfq_nature_of_project.id = rfq_request_estimate.project_id','left');
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
	
	
	public function delete_records($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('rfq_request_estimate');
		return true;
	}
	
	
	public function edit_records($id)
	{
		$this->db->select('rfq_request_estimate.*, rfq_lunch_req_mail.mail_to, rfq_lunch_req_mail.expected_date');
		$this->db->from('rfq_request_estimate');
		$this->db->join('rfq_lunch_req_mail','rfq_lunch_req_mail.lunch_req_id = rfq_request_estimate.id','left');
		$this->db->where('rfq_request_estimate.id',$id);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function edit_request_estimate($keywords, $projectname, $filename, $attachfile, $id, $instruction, $user, $type, $client, $country, $currency, $project_name)
	{
		for($i=0;$i<count($projectname);$i++)
		{
			$file[$i] = $projectname[$i]->itemName; 
		}
		$myfilename = implode(',', $file);
		$serialize = serialize($projectname);
		$array = array(
			'projectname' => $project_name,
			'client_name' => $client,
			'territory' => $country,
			'currency' => $currency,
			'keywords' => $keywords,
			'project_id' => $serialize,
			'updated_by' => $user,
			'text_instruction' => $instruction,
			'updated_at' => date('Y-m-d')	
		);
		$array1 = array(
			'attach_filename' => $attachfile
		);
		$array2 = array(
			'filename' => serialize($filename)			
		);
		
		$this->db->where('id', $id);
		if($attachfile != "")
		{
			$this->db->update('rfq_request_estimate', $array1);	
		}
		if(!empty($filename))
		{
			$this->db->update('rfq_request_estimate', $array2);
		}
		if(!empty($type))
		{
			$array3 = array(
				'project_type' => $type[0]->id
			);
			$this->db->update('rfq_request_estimate', $array3);
		}
		$this->db->update('rfq_request_estimate', $array);
		return true;
	}
	
	public function send_mail($subject, $expcted_date, $message, $to, $cc, $bcc, $id, $user)
	{
		$to_serialize = serialize($to);
		$cc_serialize = serialize($cc);
		$bcc_serialize = serialize($bcc);
		$data = array(
			'lunch_req_id' => $id,
			'mail_to' => $to_serialize,
			'mail_cc' => $cc_serialize,
			'mail_bcc' => $bcc_serialize,
			'expected_date' => $expcted_date,
			'subject' => $subject,
			'message' => $message,
			'message_type' => 'lunch request',
			'created_at' => date('Y-m-d'),
			'created_by' => $user
		);
		$this->db->insert('rfq_lunch_req_mail', $data);
		$insert_id = $this->db->insert_id();
		//$mail_to = explode(',', $to);
		for($i=0; $i<count($to);$i++)
		{
			$array = array(
				'lunch_id' => $id,
				'mail_id' => $insert_id,
				'login_id' => $to[$i]->login_id,
				'created_at' => date('Y-m-d'),
				'created_by' => 1
			);
			$this->db->insert('rfq_lunch_req_member', $array);
		}
		
		for($i=0; $i<count($cc);$i++)
		{
			$array1 = array(
				'lunch_id' => $id,
				'mail_id' => $insert_id,
				'login_id' => $cc[$i]->login_id,
				'created_at' => date('Y-m-d'),
				'created_by' => 1
			);
			$this->db->insert('rfq_lunch_req_member', $array1);
		}
		
		for($i=0; $i<count($bcc);$i++)
		{
			$array2 = array(
				'lunch_id' => $id,
				'mail_id' => $insert_id,
				'login_id' => $bcc[$i]->login_id,
				'created_at' => date('Y-m-d'),
				'created_by' => 1
			);
			$this->db->insert('rfq_lunch_req_member', $array2);
		}
		
		return true;
	}
	
	public function getEstimate($id)
	{
		$this->db->select('rfq_request_estimate.*, internal_user.full_name');
		$this->db->from('rfq_request_estimate');
		//$this->db->join('rfq_lunch_req_member','rfq_lunch_req_member.lunch_id = rfq_request_estimate.id');
		//$this->db->where('rfq_lunch_req_member.login_id', $id);
		$this->db->join('internal_user','internal_user.login_id = rfq_request_estimate.created_by');
		$this->db->where('rfq_request_estimate.add_estimation_submit !=', null);
		$this->db->where('rfq_request_estimate.add_estimation_approved', null);
		$this->db->group_by('rfq_request_estimate.id');
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function save_esimated($id, $name, $unit, $rate, $totalrate, $user, $type, $formid, $delete_id, $skill, $no_unit = "", $hours_unit = "", $duration_type = "", $duration = "", $unit_type="")
	{
		if($formid != "")
		{
			$condition = array(
				'id' => $formid
			);	
		}
		
		if($type == "effort")
		{
			if(!empty($delete_id))
			{
				$this->db->where_in('id', $delete_id);
				$this->db->delete('rfq_effort_estimate');	
			}
			
			if($formid != "")
			{
				$array = array(
					'lunch_req_id' => $id,
					'effort_name' => $name,
					'skill_level' => $skill,					
					"no_of_unit" => $no_unit,
					"hours_per_unit" => $hours_unit,
					'no_of_hour' => $unit,
					'rate_per_hour' => $rate,
					'total_rate' => $totalrate,
					'unit_type' => $unit_type,
					'updated_at' => date("Y-m-d h:i:s"),
					'updated_by' => $user
				); 
					
				$this->db->update('rfq_effort_estimate', $array, $condition);
			}
			else{
				$array = array(
					'lunch_req_id' => $id,
					'effort_name' => $name,
					'skill_level' => $skill,
					"no_of_unit" => $no_unit,
					"hours_per_unit" => $hours_unit,
					'no_of_hour' => $unit,
					'rate_per_hour' => $rate,
					'total_rate' => $totalrate,
					'unit_type' => $unit_type,
					'created_at' => date('Y-m-d'),
					'created_by' => $user
				);	
				$this->db->insert('rfq_effort_estimate', $array);	
			}				
		}
		else if($type == "hardware")
		{
			if(!empty($delete_id))
			{
				$this->db->where_in('id', $delete_id);
				$this->db->delete('rfq_hardware_estimate');	
			}
			if($formid != "")
			{
				$array = array(
					'lunch_req_id' => $id,
					'hardware_name' => $name,
					'no_of_count' => $unit,
					'unit_cost' => $rate,
					'total_rate' => $totalrate,
					'updated_at' => date("Y-m-d h:i:s"),
					'updated_by' => $user
				);
					
				$this->db->update('rfq_hardware_estimate', $array, $condition);
			}
			else{
				$array = array(
					'lunch_req_id' => $id,
					'hardware_name' => $name,
					'no_of_count' => $unit,
					'unit_cost' => $rate,
					'total_rate' => $totalrate,
					'created_at' => date('Y-m-d'),
					'created_by' => $user
				);			
				$this->db->insert('rfq_hardware_estimate', $array);		
			}
			
		}
		else if($type == "manpower")
		{
			if(!empty($delete_id))
			{
				$this->db->where_in('id', $delete_id);
				$this->db->delete('rfq_manpower_estimate');	
			}
			if($formid != "")
			{
				$array = array(
					'lunch_req_id' => $id,
					'manpower_name' => $name,
					'duration_type' => $duration_type,
					'duration' => $duration,
					'no_of_people' => $unit,
					'allowance' => $rate,
					'total_rate' => $totalrate,
					'updated_at' => date("Y-m-d h:i:s"),
					'updated_by' => $user
				);
					
				$this->db->update('rfq_manpower_estimate', $array, $condition);
			}
			else{
				$array = array(
					'lunch_req_id' => $id,
					'manpower_name' => $name,
					'duration_type' => $duration_type,
					'duration' => $duration,
					'no_of_people' => $unit,
					'allowance' => $rate,
					'total_rate' => $totalrate,
					'created_at' => date('Y-m-d'),
					'created_by' => $user
				);			
				$this->db->insert('rfq_manpower_estimate', $array);		
			}
	
		}
		else if($type == "software")
		{
			if(!empty($delete_id))
			{
				$this->db->where_in('id', $delete_id);
				$this->db->delete('rfq_software_estimate');	
			}
			if($formid != "")
			{
				$array = array(
					'lunch_req_id' => $id,
					'software_name' => $name,
					'no_of_lincenses' => $unit,
					'unitcost' => $rate,
					'total_rate' => $totalrate,
					'updated_at' => date("Y-m-d h:i:s"),
					'updated_by' => $user
				);
					
				$this->db->update('rfq_software_estimate', $array, $condition);
			}
			else
			{
				$array = array(
					'lunch_req_id' => $id,
					'software_name' => $name,
					'no_of_lincenses' => $unit,
					'unitcost' => $rate,
					'total_rate' => $totalrate,
					'created_at' => date('Y-m-d'),
					'created_by' => $user
				);			
				$this->db->insert('rfq_software_estimate', $array);		
			}

		}
		else if($type == "outstation")
		{
			if(!empty($delete_id))
			{
				$this->db->where_in('id', $delete_id);
				$this->db->delete('rfq_outstation_estimate');	
			}
			if($formid != "")
			{
				$array = array(
					'lunch_req_id' => $id,
					'item_name' => $name,
					'no_of_resource' => $unit,
					'cost_per_person' => $rate,
					'total_rate' => $totalrate,
					'updated_at' => date("Y-m-d h:i:s"),
					'updated_by' => $user
				);
					
				$this->db->update('rfq_outstation_estimate', $array, $condition);
			}
			else
			{
				$array = array(
					'lunch_req_id' => $id,
					'item_name' => $name,
					'no_of_resource' => $unit,
					'cost_per_person' => $rate,
					'total_rate' => $totalrate,
					'created_at' => date('Y-m-d'),
					'created_by' => $user
				);			
				$this->db->insert('rfq_outstation_estimate', $array);		
			}
		}
		return true;
	}
	
	
	public function get_estimate_effort($id)
	{
		$this->db->select('*');
		$this->db->from('rfq_effort_estimate');
		$this->db->where('lunch_req_id', $id);
		$this->db->order_by('id');
		$query = $this->db->get();
		return $query->result();
	}
	
	
	public function get_vendor_estimate($id)
	{
		$this->db->select('*');
		$this->db->from('rfq_vendor_estimate');
		$this->db->where('lunch_req_id', $id);
		$this->db->order_by('vendor_id');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_estimate_hardware($id)
	{
		$this->db->select('*');
		$this->db->from('rfq_hardware_estimate');
		$this->db->where('lunch_req_id', $id);
		$this->db->order_by('id');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_estimate_manpower($id)
	{
		$this->db->select('*');
		$this->db->from('rfq_manpower_estimate');
		$this->db->where('lunch_req_id', $id);
		$this->db->order_by('id');
		$query = $this->db->get();
		return $query->result();
	}
	
	
	public function get_estimate_software($id)
	{
		$this->db->select('*');
		$this->db->from('rfq_software_estimate');
		$this->db->where('lunch_req_id', $id);
		$this->db->order_by('id');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_estimate_outstation($id)
	{
		$this->db->select('*');
		$this->db->from('rfq_outstation_estimate');
		$this->db->where('lunch_req_id', $id);
		$this->db->order_by('id');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function send_mail_estimate($id, $logid, $file_name)
	{
		$condition = array(
			'id' => $id
		);
		
		$array= array(
			'add_estimation_submit' => $logid,
			'updated_by' => $logid,
			'updated_at' => date("Y-m-d h:i:s")
		);
		
		if($file_name != "")
		{
			$new_file = implode(',', $file_name); //print_r($new_file); exit;
			$array1= array(
				'estimate_file' => $new_file,
				'updated_by' => $logid,
				'updated_at' => date("Y-m-d h:i:s")
			);
			$this->db->update('rfq_request_estimate', $array1, $condition);
		}
		
		/*$condition = array(
			'id' => $id
		);*/
		
		$this->db->update('rfq_request_estimate', $array, $condition);
		return true;
	}
	
	public function sendapprove_mail_estimate($id, $logid, $rate, $total, $file)
	{
		
		$condition = array(
			'id' => $id
		);
		if(!empty($file))
		{
			for($i=0; $i<count($file); $i++)
			{
				$array_file = array(
					'estimate_file' => $file[$i]
				);
				$this->db->update('rfq_request_estimate', $array_file, $condition);
			}			
		}
		$array= array(
			'add_estimation_approved' => $logid,
			'total_rate' => $rate,
			'total_estimate' => $total,
			'updated_by' => $logid,
			'updated_at' => date("Y-m-d h:i:s")
		);
		
		$this->db->update('rfq_request_estimate', $array, $condition);
		return true;
	}
	
	public function get_role()
	{
		$this->db->select('internal_user.email, internal_user.full_name, rfq_role.role_name');
		$this->db->from('internal_user');
		$this->db->join('rfq_users', 'rfq_users.login_id = internal_user.login_id', 'left');
		$this->db->join('rfq_role','rfq_role.id = rfq_users.user_role_id', 'left');
		$this->db->where('rfq_role.id',6);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_approverole($id)
	{
		$this->db->select('internal_user.email, internal_user.full_name, rfq_role.role_name');
		$this->db->from('internal_user');
		$this->db->join('rfq_users', 'rfq_users.login_id = internal_user.login_id', 'left');
		$this->db->join('rfq_role','rfq_role.id = rfq_users.user_role_id', 'left');
		$this->db->where('internal_user.login_id',$id);
		$query = $this->db->get();
		return $query->result();	
	}

	public function get_management_record()
	{
		$this->db->select('internal_user.email, internal_user.full_name, rfq_role.role_name');
		$this->db->from('internal_user');
		$this->db->join('rfq_users', 'rfq_users.login_id = internal_user.login_id', 'left');
		$this->db->join('rfq_role','rfq_role.id = rfq_users.user_role_id', 'left');
		$this->db->where('rfq_role.id',2);
		$this->db->where('rfq_users.login_id = 10594');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_record_sales()
	{
		$array = array('3','4');
		$this->db->select('internal_user.email, internal_user.full_name, rfq_role.role_name');
		$this->db->from('internal_user');
		$this->db->join('rfq_users', 'rfq_users.login_id = internal_user.login_id', 'left');
		$this->db->join('rfq_role','rfq_role.id = rfq_users.user_role_id', 'left');
		$this->db->where_in('rfq_role.id',$array);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_message($id, $logid)
	{
		//$this->db->select('rfq_request_estimate.*, rfq_lunch_req_mail.expected_date, rfq_lunch_req_mail.subject');
		$this->db->select('rfq_request_estimate.*');
		$this->db->from('rfq_request_estimate');
		//$this->db->join('rfq_lunch_req_mail','rfq_lunch_req_mail.lunch_req_id = rfq_request_estimate.id', 'left');
		//$this->db->join('rfq_lunch_req_member','rfq_lunch_req_member.mail_id = rfq_lunch_req_mail.id', 'left');
		$this->db->where('rfq_request_estimate.id', $id);
		//$this->db->where('rfq_lunch_req_member.login_id', $logid);
		$this->db->group_by('rfq_request_estimate.id');
		$query = $this->db->get(); //echo $this->db->last_query();
		return $query->result();
	}
	
	public function getrfqEstimate($id)
	{
		$this->db->select('*');
		$this->db->from('rfq_request_estimate');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function getuser($loginid)
	{
		$this->db->select('*');
		$this->db->from('internal_user');
		$this->db->where('login_id', $loginid);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function save_mail_user($email, $subject, $exp_date, $message, $id, $loginid, $estimate)
	{
		$this->db->select('login_id');
		$this->db->from('internal_user');
		$this->db->where('email', $email);
		$query = $this->db->get();
		$result = $query->result();
		if($estimate == "approve")
		{
			$array = array(
				'lunch_req_id' => $id,
				'mail_to' => serialize($email),
				'mail_cc' => serialize('saurav.mohapatra@polosoftech.com'),
				'mail_bcc' => serialize('saurav.mohapatra@polosoftech.com'),
				'subject' => $subject,
				'expected_date' => $exp_date,
				'subject' => $subject,
				'message' => $message,
				'message_type' => 'approve estimation',
				'created_at' => date('Y-m-d'),
				'created_by' => $loginid
			);	
		}
		else{
			$array = array(
				'lunch_req_id' => $id,
				'mail_to' => serialize($email),
				'mail_cc' => serialize('saurav.mohapatra@polosoftech.com'),
				'mail_bcc' => serialize('saurav.mohapatra@polosoftech.com'),
				'subject' => $subject,
				'expected_date' => $exp_date,
				'subject' => $subject,
				'message' => $message,
				'message_type' => 'add estimation',
				'created_at' => date('Y-m-d'),
				'created_by' => $loginid
			);	
		}
		
		$this->db->insert('rfq_lunch_req_mail', $array);
		$insert_id = $this->db->insert_id();
		
		$array1 = array(
			'lunch_id' => $id,
			'mail_id' => $insert_id,
			'login_id' => $result[0]->login_id,
			'created_at' => date('Y-m-d'),
			'created_by' => $loginid
		);
		$this->db->insert('rfq_lunch_req_member', $array1);
		
	}
	
	public function getFinancial($id)
	{
		$this->db->select('rfq_request_estimate.*, internal_user.full_name');
		$this->db->from('rfq_request_estimate');
		$this->db->join('rfq_lunch_req_member','rfq_lunch_req_member.lunch_id = rfq_request_estimate.id','left');
		$this->db->join('internal_user','internal_user.login_id = rfq_request_estimate.created_by');
		$this->db->where('rfq_request_estimate.created_by', $id);
		$this->db->where('rfq_request_estimate.add_estimation_approved !=', null);
		$this->db->where('rfq_request_estimate.prepare_financial_proposal', null);
		$this->db->group_by('rfq_request_estimate.id');
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function save_finance($record, $myrecord, $type, $user, $total, $estimate_id, $gstnid, $totalamt, $filename)
	{
		if($type == "domestic" || $type == "domestic tender")
		{
			for($i=0; $i<count($myrecord); $i++)
			{
				if($myrecord[$i]->id != "")
				{
					$cond = array(
						'id' => $myrecord[$i]->id
					);	
					$data = array(
						'estimate_id' => $estimate_id,
						'project_type' => 1,
						//'name' => $myrecord[$i]->name,
						'total_estimate' => $total,
						'dollar_rate' => $myrecord[$i]->rate,
						'amount' => $myrecord[$i]->total_cost,
						'currency' => $myrecord[$i]->currency,
						'updated_by' => $user,
						'updated_at' => date('Y-m-d h:i:s')
					);
					$this->db->update('rfq_financial_proposal', $data, $cond);		
				}
				else
				{					
					$data = array(
						'estimate_id' => $estimate_id,
						'project_type' => 1,
						//'name' => $myrecord[$i]->name,
						'total_estimate' => $total,
						'dollar_rate' => $myrecord[$i]->rate,
						'amount' => $myrecord[$i]->total_cost,
						'currency' => $myrecord[$i]->currency,
						'created_by' => $user,
						'created_at' => date('Y-m-d')
					);
					$this->db->insert('rfq_financial_proposal', $data);		
				}
			}
			
			if($gstnid != "" || $gstnid != 0)
			{
				$cond = array(
					'id' => $gstnid
				);
				$data = array(
					'estimate_id' => $estimate_id,
					'project_type' => 1,
					'name' => $record[0]->name,
					'total_estimate' => $total,
					'rate_percent' => $record[0]->rate,
					'amount' => $record[0]->totalrate,
					'updated_by' => $user,
					'updated_at' => date('Y-m-d h:i:s')
				);
				$this->db->update('rfq_financial_proposal', $data, $cond);				
			}
			else{
				$data = array(
					'estimate_id' => $estimate_id,
					'project_type' => 1,
					'name' => $record[0]->name,
					'total_estimate' => $total,
					'rate_percent' => $record[0]->rate,
					'amount' => $record[0]->totalrate,
					'created_by' => $user,
					'created_at' => date('Y-m-d')
				);
				$this->db->insert('rfq_financial_proposal', $data);
			}
			
			
		}
		else if($type == "international" || $type == "international tender")
		{
			for($i=0; $i<count($record); $i++)
			{
				if($record[$i]->id != "")
				{
					$cond = array(
						'id' => $record[$i]->id
					);
					$data = array(
						'estimate_id' => $estimate_id,
						'project_type' => 2,
						//'name' => $record[$i]->name,
						'total_estimate' => $total,
						'dollar_rate' => $record[$i]->rate,
						//'exchange_rate' => $record[$i]->exrate,
						'amount' => $record[$i]->total_cost,
						'currency' => $record[$i]->currency,
						'updated_by' => $user,
						'updated_at' => date('Y-m-d h:i:s')
					);
					$this->db->update('rfq_financial_proposal', $data, $cond);
				}
				else{
					$data = array(
						'estimate_id' => $estimate_id,
						'project_type' => 2,
						//'name' => $record[$i]->name,
						'total_estimate' => $total,
						'dollar_rate' => $record[$i]->rate,
						//'exchange_rate' => $record[$i]->exrate,
						'amount' => $record[$i]->total_cost,
						'currency' => $record[$i]->currency,
						'created_by' => $user,
						'created_at' => date('Y-m-d')
					);
					$this->db->insert('rfq_financial_proposal', $data);
				}	
			}
		}
		$condition = array(
			'id' => $estimate_id
		);
		if(!empty($filename)){
			for($i=0; $i<count($filename); $i++)
			{				
				$array_data = array(
					'financial_file' => $filename[$i],
					'total_estimate' => $totalamt,
					'updated_by' => $user,
					'updated_at' => date("Y-m-d h:i:s")
				);
				$this->db->update('rfq_request_estimate', $array_data, $condition);
			}
		}
		else{
			$array_data = array(
				'total_estimate' => $totalamt,
				'updated_by' => $user,
				'updated_at' => date("Y-m-d h:i:s")
			);
			$this->db->update('rfq_request_estimate', $array_data, $condition);
		}
		
		return true;
	}
	
	public function get_FPrecords($id)
	{
		$this->db->select('*');
		$this->db->from('rfq_financial_proposal');
		$this->db->where('estimate_id',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function sendfinance_mail_estimate($id, $user_id, $totalamt)
	{
		$condition = array(
			'id' => $id
		);
		$data = array(
			'total_estimate' => $totalamt,
			'prepare_financial_proposal' => $user_id,
			'updated_by' => $user_id,
			'updated_at' => date('Y-m-d h:i:s')
		);

		$this->db->update('rfq_request_estimate', $data, $condition);
	}
	
	public function approvefinance_mail_estimate($id, $user_id, $totalamt)
	{
		$condition = array(
			'id' => $id
		);
		$data = array(
			'total_estimate' => $totalamt,
			'approve_financial_proposal' => $user_id,
			'updated_by' => $user_id,
			'updated_at' => date('Y-m-d h:i:s')
		);

		$this->db->update('rfq_request_estimate', $data, $condition);		
	}
	
	public function get_cuurencies()
	{
		$this->db->select('*');
		$this->db->from('currencies');
		$this->db->order_by('currencies.country');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_rfqestimate_record($exp_date)
	{
		$this->db->select("rfq_lunch_req_mail.*, rfq_request_estimate.*, internal_user.email, internal_user.full_name");
		$this->db->from('rfq_lunch_req_mail');
		$this->db->join('rfq_request_estimate','rfq_request_estimate.id = rfq_lunch_req_mail.lunch_req_id');
		$this->db->join('internal_user','internal_user.login_id = rfq_request_estimate.created_by');
		$this->db->where('rfq_lunch_req_mail.expected_date', $exp_date);
		$this->db->where('rfq_request_estimate.add_estimation_approved', null);
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		$query = $this->db->get(); //echo $this->db->last_query();
		return $query->result();
	}
	
	public function get_rfq_estimate_approve()
	{
		$this->db->select('rfq_request_estimate.*');
		$this->db->from('rfq_request_estimate');
		$this->db->join('rfq_lunch_req_member','rfq_lunch_req_member.lunch_id = rfq_request_estimate.id','left');
		//$this->db->where('rfq_lunch_req_member.login_id', $id);
		$this->db->where('rfq_request_estimate.add_estimation_approved !=', null);
		$this->db->where('rfq_request_estimate.prepare_financial_proposal !=', null);
		$this->db->group_by('rfq_request_estimate.id');
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		$query = $this->db->get(); //echo $this->db->last_query();
		return $query->result();
	}
	
	public function save_estimate_proposal($name, $file1, $user, $id, $date="")
	{
		if($date != "")
		{
			$current_date = $date;
		}else{
			$current_date = date('Y-m-d');
		}
		for($i=0; $i<count($file1); $i++)
		{
			$array = array(
				'estimate_id' => $id,
				'client_name' => $name,
				'proposal_doc' => $file1[$i],
				'created_at' => $current_date,
				'created_by' => $user
			);
			
			$this->db->insert('rfq_upload_proposal', $array);
		}
		
		/* for($i=0; $i<count($file2); $i++)
		{
			$array = array(
				'estimate_id' => $id,
				'client_name' => $name,
				'mail_doc' => $file2[$i],
				'created_at' => $current_date,
				'created_by' => $user
			);
			
			$this->db->insert('rfq_upload_proposal', $array);
		} */
		
		$data = array(
			'uploaded_proposal' => $user,
			'updated_at' => date('Y-m-d h:i:s', strtotime($current_date)),
			'updated_by' => $user
		);
		$cond = array(
			'id' => $id
		);
		$this->db->update('rfq_request_estimate', $data, $cond);
		return true;
	}
	
	public function get_proposal($id)
	{
		$this->db->select('rfq_request_estimate.*');
		$this->db->from('rfq_request_estimate');
		$this->db->where('rfq_request_estimate.id',$id);
		$query = $this->db->get();
		$result = $query->result();
		$data = array();
		for($i=0; $i<count($result); $i++){
			$this->db->select('rfq_upload_proposal.*,internal_user.full_name');
			$this->db->from('rfq_upload_proposal');
			$this->db->join('internal_user', 'internal_user.login_id = rfq_upload_proposal.created_by', 'left');
			$this->db->where('rfq_upload_proposal.estimate_id',$id);
			$query1 = $this->db->get();
			$result1 = $query1->result_array();
			$data[] = array(
				'id'=> $result[$i]->id,
				'projectname'=> $result[$i]->projectname,
				'project_name'=> $result[$i]->project_name,
				'project_type'=> $result[$i]->project_type,
				'client_name'=> $result[$i]->client_name,
				'created_at'=> $result[$i]->created_at,
				'uploaded_file'=> $result1
			);
		}
		return $data;
		
	}
	
	public function get_projectrecords_list()
	{
		$this->db->select('*');
		$this->db->from('rfq_request_estimate');
		$this->db->order_by('rfq_request_estimate.created_at','desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function search_keyword($keywords="", $client_name="", $project="")
	{
		$this->db->select('`rfq_request_estimate`.*, `internal_user`.`full_name` , `rfq_request_estimate`.`territory` as `countryname`, rfq_upload_proposal.proposal_doc, rfq_upload_proposal.mail_doc, 
		(SELECT sum(rfq_effort_estimate.`total_rate`) from rfq_effort_estimate WHERE `rfq_effort_estimate`.`lunch_req_id` = `rfq_request_estimate`.`id`) as sumeffort, 
        (SELECT sum(rfq_hardware_estimate.`total_rate`) from rfq_hardware_estimate WHERE `rfq_hardware_estimate`.`lunch_req_id` = `rfq_request_estimate`.`id`) as sumhardwrcost, 
        (SELECT sum(rfq_software_estimate.`total_rate`) from rfq_software_estimate WHERE `rfq_software_estimate`.`lunch_req_id` = `rfq_request_estimate`.`id`) as sumsoftwrcost, 
        (SELECT sum(rfq_outstation_estimate.`total_rate`) from rfq_outstation_estimate WHERE `rfq_outstation_estimate`.`lunch_req_id` = `rfq_request_estimate`.`id`) as sumoutscost, 
        (SELECT sum(rfq_manpower_estimate.`total_rate`) from rfq_manpower_estimate WHERE `rfq_manpower_estimate`.`lunch_req_id` = `rfq_request_estimate`.`id`) as sumhrcost, 
        (SELECT sum(rfq_financial_proposal.`total_estimate`) from rfq_financial_proposal WHERE `rfq_financial_proposal`.`estimate_id` = `rfq_request_estimate`.`id`) as totalfp');
		$this->db->from('rfq_request_estimate');
		$this->db->join('internal_user', 'internal_user.login_id = rfq_request_estimate.created_by','left');
		//$this->db->join('currencies', 'rfq_request_estimate.territory = currencies.id','left');
		$this->db->join('rfq_upload_proposal','rfq_upload_proposal.estimate_id = `rfq_request_estimate`.id', 'left');
		if($keywords != "")
		{
			$this->db->like('rfq_request_estimate.keywords', $keywords);	
		}
		if($project != "" && $project != "all")
		{
			$this->db->like("rfq_request_estimate.project_name",$project);	
		}
		if($client_name != "" && $client_name != "all")
		{
			$this->db->where("rfq_request_estimate.client_name = '$client_name'");	
		}
		$this->db->group_by('rfq_request_estimate.id');
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		//$this->db->or_like($array);
		$query = $this->db->get(); //echo $this->db->last_query();
		return $query->result();
		
		/*$query = $this->db->query("SELECT `rfq_request_estimate`.*, `internal_user`.`full_name` as `createdname`, `currencies`.`country` as `countryname`, rfq_upload_proposal.*, 
		(SELECT sum(rfq_effort_estimate.`total_rate`) from rfq_effort_estimate WHERE `rfq_effort_estimate`.`lunch_req_id` = `rfq_request_estimate`.`id`) as sumeffort, 
        (SELECT sum(rfq_hardware_estimate.`total_rate`) from rfq_hardware_estimate WHERE `rfq_hardware_estimate`.`lunch_req_id` = `rfq_request_estimate`.`id`) as sumhardwrcost, 
        (SELECT sum(rfq_software_estimate.`total_rate`) from rfq_software_estimate WHERE `rfq_software_estimate`.`lunch_req_id` = `rfq_request_estimate`.`id`) as sumsoftwrcost, 
        (SELECT sum(rfq_outstation_estimate.`total_rate`) from rfq_outstation_estimate WHERE `rfq_outstation_estimate`.`lunch_req_id` = `rfq_request_estimate`.`id`) as sumoutscost, 
        (SELECT sum(rfq_manpower_estimate.`total_rate`) from rfq_manpower_estimate WHERE `rfq_manpower_estimate`.`lunch_req_id` = `rfq_request_estimate`.`id`) as sumhrcost, 
        (SELECT sum(rfq_financial_proposal.`total_estimate`) from rfq_financial_proposal WHERE `rfq_financial_proposal`.`estimate_id` = `rfq_request_estimate`.`id`) as totalfp
        FROM `rfq_request_estimate` 
		LEFT JOIN `internal_user` ON `internal_user`.`login_id` = `rfq_request_estimate`.`created_by` 
		LEFT JOIN `currencies` ON `rfq_request_estimate`.`territory` = `currencies`.`id` 
        LEFT JOIN rfq_upload_proposal on rfq_upload_proposal.estimate_id = `rfq_request_estimate`.id
		WHERE `rfq_request_estimate`.`project_name` LIKE '%".$project."%' ESCAPE '!' && `rfq_request_estimate`.`keywords` LIKE '%".$keywords."%' ESCAPE '!' && `rfq_request_estimate`.`client_name` LIKE '%".$client_name."%' ESCAPE '!' GROUP BY `rfq_request_estimate`.`id`");
		$result = $query->result();
		return $result;	*/
	}
	
	public function get_projectrecords()
	{
		$this->db->select('rfq_request_estimate.*, internal_user.full_name');
		$this->db->from('rfq_request_estimate');
		$this->db->join('internal_user', 'internal_user.login_id = rfq_request_estimate.created_by', 'left');
		//$this->db->group_by('project_name');
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_clientrecords()
	{
		$this->db->select('*');
		$this->db->from('rfq_request_estimate');
		$this->db->group_by('client_name');
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	//2018.09.11
	
	
	
	public function sendlre_mail($subject, $expcted_date, $message, $to, $id, $user, $date="")
	{
		if($date != "")
		{
			$current_date = $date;
		}
		else{
			$current_date = date('Y-m-d');
		}
		$to_serialize = serialize($to);
		$data = array(
			'lunch_req_id' => $id,
			'mail_to' => $to_serialize,
			'expected_date' => $expcted_date,
			'subject' => $subject,
			'message' => $message,
			'message_type' => 'lunch request',
			'created_at' => $current_date,
			'created_by' => $user
		);
		$this->db->insert('rfq_lunch_req_mail', $data);
		$insert_id = $this->db->insert_id();
		
		for($i=0; $i<count($to);$i++)
		{
			$array = array(
				'lunch_id' => $id,
				'mail_id' => $insert_id,
				'login_id' => $to[$i]->login_id,
				'created_at' => $current_date,
				'created_by' => 1
			);
			$this->db->insert('rfq_lunch_req_member', $array);
		}
		
		return true;
	}
	
	public function sendlre_mail_edit($subject, $expcted_date, $message, $to, $id, $user)
	{
		$to_serialize = serialize($to);
		$data = array(
			'mail_to' => $to_serialize,
			'expected_date' => $expcted_date,
			'subject' => $subject,
			'message' => $message,
			'message_type' => 'lunch request',
			'updated_at' => date('Y-m-d h:i:s'),
			'updated_by' => $user
		);
		
		$condition = array(
			'lunch_req_id' => $id
		);
		$this->db->update('rfq_lunch_req_mail', $data, $condition);
		//$insert_id = $this->db->insert_id();
		
		$this->db->select('*');
		$this->db->from('rfq_lunch_req_mail');
		$this->db->where('lunch_req_id', $id);
		$query = $this->db->get();
		$res = $query->result();
		//print_r($res);
		
		$array = array(
			'mail_id' => $insert_id,
			'login_id' => $to,
			'updated_at' => date('Y-m-d h:i:s'),
			'updated_by' => $user
		);
		
		$cond = array(
			'lunch_id' => $id,
			'mail_id' => $res[0]->id
		);
		$this->db->update('rfq_lunch_req_member', $array, $cond);
		
		return true;
	}
	
	public function get_records_name($logid)
	{
		$this->db->select('*');
		$this->db->from('internal_user');
		$this->db->where('login_id',$logid);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_clients()
	{
		$this->db1 = $this->load->database('amsdb', TRUE); 
		//Get My Assets
		$my_asset_sql = "SELECT client.*, countries.country_name, currencies.code  
		FROM `client` left join countries on countries.id = client.s_country left join currencies on currencies.country = countries.country_name
		WHERE client.n_disp_flag = '1' ORDER BY client.s_client_name ASC";
		$result = $this->db1->query($my_asset_sql);
		$this->db = $this->load->database('default', TRUE); 
		return $result->result_array(); 
	}

	
	public function get_state()
	{
		$this->db1 = $this->load->database('amsdb', TRUE); 
		//Get My Assets
		$my_asset_sql = "SELECT * 
		FROM `state` 
		WHERE state_status = '1' ORDER BY state_name ASC";
		$result = $this->db1->query($my_asset_sql);
		$this->db = $this->load->database('default', TRUE); 
		return $result->result_array();
	}
	
	public function get_country()
	{
		$this->db1 = $this->load->database('amsdb', TRUE); 
		//Get My Assets
		$my_asset_sql = "SELECT * 
		FROM `countries` 
		ORDER BY country_name ASC";
		$result = $this->db1->query($my_asset_sql);
		$this->db = $this->load->database('default', TRUE); 
		return $result->result_array();
	}
	
	public function save_client($cname, $ctype, $email, $fax, $mobile, $phone, $contactperson, $description, $address, $country, $mtype, $gstno, $scode, $state, $stype)
	{
		$this->db1 = $this->load->database('amsdb', TRUE); 
		
		if($ctype ='1'){
			$client_type = 'Export';
			$stype = 'Inter';
			$state = '0';
		}
		else if($ctype ='2'){
			$client_type = 'Domestic';
		}
		$array = array(
			's_client_name' => $cname,
			's_client_type' => $client_type,
			's_client_email' => $email,
			's_client_fax' => $fax,
			's_client_mobile' => $mobile,
			's_client_phone' => $phone,
			's_client_contact' => $contactperson,
			's_client_desc' => $description,
			's_client_address' => $address,
			's_msa_type' => $mtype,
			's_gst_no' => $gstno,
			's_state_type' => $stype,
			's_state' => $state,
			's_state_code' => $scode,
			's_country' => $country
		);
		
		$this->db1->insert('client', $array);
		$last_id = $this->db1->insert_id();
		$this->db = $this->load->database('default', TRUE); 
		return $last_id;
	}
	
	public function get_chart_record()
	{
		$query = $this->db->query("SELECT rfq_nature_of_project.name, COUNT(rfq_request_estimate.id) cnt FROM `rfq_nature_of_project` left OUTER JOIN rfq_request_estimate on rfq_request_estimate.project_name = rfq_nature_of_project.name GROUP by rfq_nature_of_project.name");
		$result = $query->result();	
		
		return $result;
	}
	
	public function get_chartrecord_client()
	{
		$query = $this->db->query("SELECT rfq_request_estimate.client_name, COUNT(rfq_request_estimate.id) cnt FROM rfq_request_estimate GROUP by rfq_request_estimate.client_name");
		$result = $query->result();	
		
		return $result;
	}
	
	
	public function get_proposal_upload($id, $roll_id)
	{
		$where = "rfq_request_estimate.approve_financial_proposal is  NOT NULL and rfq_request_estimate.uploaded_proposal is NULL";

		$this->db->select('rfq_request_estimate.*, currencies.country, currencies.code, internal_user.full_name');
		$this->db->from('rfq_request_estimate');
		$this->db->join('internal_user','internal_user.login_id = rfq_request_estimate.created_by');
		$this->db->join('currencies', 'currencies.id = rfq_request_estimate.territory','left');
		if($roll_id != "3" && $roll_id != "2")
		{
			$this->db->where('created_by', $id);	
		}
		$this->db->where($where);
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_rfq_estimate_approve_all()
	{
		$this->db->select('rfq_request_estimate.*, internal_user.full_name');
		$this->db->from('rfq_request_estimate');
		$this->db->join('rfq_lunch_req_member','rfq_lunch_req_member.lunch_id = rfq_request_estimate.id','left');
		$this->db->join('internal_user','internal_user.login_id = rfq_request_estimate.created_by');
		$this->db->where('rfq_request_estimate.prepare_financial_proposal !=', null);
		$this->db->where('rfq_request_estimate.approve_financial_proposal', null);
		$this->db->group_by('rfq_request_estimate.id');
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		$query = $this->db->get(); //echo $this->db->last_query();
		return $query->result();
	}
	
	public function getEstimate_records($id)
	{
		$this->db->select('rfq_request_estimate.*, internal_user.full_name');
		$this->db->from('rfq_request_estimate');
		$this->db->join('rfq_lunch_req_member','rfq_lunch_req_member.lunch_id = rfq_request_estimate.id');
		$this->db->join('internal_user','internal_user.login_id = rfq_request_estimate.created_by');
		$this->db->where('rfq_lunch_req_member.login_id', $id);
		$this->db->where('rfq_request_estimate.add_estimation_submit', null);
		$this->db->where('rfq_request_estimate.add_estimation_approved', null);
		$this->db->group_by('rfq_request_estimate.id');
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	// Technical Users  (10.01.2019)
	public function rfqusers_technical()
	{
		$this->db->select('internal_user.login_id,internal_user.loginhandle as emp_code,internal_user.email,internal_user.full_name,rfq_users.user_role_id, DATE_FORMAT(rfq_users.user_created_at,"%d-%m-%Y") as user_created_at,rfq_role.role_name');
		$this->db->from('rfq_users');
		$this->db->join('internal_user','rfq_users.login_id = internal_user.login_id');
		$this->db->join('rfq_role','rfq_role.id = rfq_users.user_role_id');
		$this->db->where('rfq_users.user_role_id !=', '1');
		$this->db->where_in('rfq_users.user_role_id', array('5','6'));
		$this->db->where('internal_user.user_status', '1');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_all_uploaded_request()
	{
		$this->db->select('rfq_request_estimate.*');
		$this->db->from('rfq_request_estimate');
		$this->db->join('rfq_lunch_req_member','rfq_lunch_req_member.lunch_id = rfq_request_estimate.id','left');
		$this->db->where('rfq_request_estimate.upload_request', 1);
		$this->db->group_by('rfq_request_estimate.id');
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function insert_lunch_request($sheet_record)
	{
		$project = array();
		$to = array();
		$this->db->select('*');
		$this->db->from('rfq_request_estimate');
		$this->db->where('rfq_request_estimate.upload_request',1);
		$myquery = $this->db->get();
		$res_rfq = $myquery->result();
		
		for($i=0; $i<count($res_rfq); $i++)
		{
			$array1 = array(
				'upload_request' => 0
			);
			$condition = array(
				'id' => $res_rfq[$i]->id
			);
			$this->db->update('rfq_request_estimate', $array1, $condition);
		}
		
		for($i=1; $i<count($sheet_record); $i++)
		{
			if($sheet_record[0][0] == "Project Name")
			{
				if(!empty($sheet_record[$i][1]))
				{
					$projectname = $sheet_record[$i][0];	
				}
			}
			if($sheet_record[0][1] == "Nature Of Project")
			{
				if(!empty($sheet_record[$i][1]))
				{
					$nature_of_project = $sheet_record[$i][1];
					$record = explode(',', $sheet_record[$i][1]);	
					for($j=0; $j<count($record); $j++)
					{
						 $this->db->select('id');
						 $this->db->from('rfq_nature_of_project');
						 $this->db->where('name', ltrim($record[$j]));
						 $query = $this->db->get();
						 $result = $query->result();
						 if(!empty($result))
						 {
							$array[] = array(
								'id' => $result[0]->id,
								'itemName' => ltrim($record[$j])
							 );	
						 }
					}
					
					$serialize_project = serialize($array);
				}
			}
			
			if($sheet_record[0][3] == "Project Type")
			{
				$project_type = '';
				if(!empty($sheet_record[$i][3]))
				{
					if($sheet_record[$i][3] == "Domestic")
					{
						$project_type = 1;
					}else if($sheet_record[$i][3] == "International")
					{
						$project_type = 2;
					}else if($sheet_record[$i][3] == "Domestic Tender")
					{
						$project_type = 3;
					}else if($sheet_record[$i][3] == "International Tender")
					{
						$project_type = 4;
					}
					
				}
			}
			if($sheet_record[0][4] == "Name of Client")
			{
				if(!empty($sheet_record[$i][4]))
				{
					$clint_name = $sheet_record[$i][4];
				}
			}
			
			if($sheet_record[0][5] == "Client Geography")
			{
				if(!empty($sheet_record[$i][5]))
				{
					$territory = $sheet_record[$i][5];
				}
			}
			
			if($sheet_record[0][6] == "Currency")
			{
				if(!empty($sheet_record[$i][6]))
				{
					$currency = $sheet_record[$i][6];
				}
			}
			
			if($sheet_record[0][7] == "Responsible To Add Estimate")
			{
				if(!empty($sheet_record[$i][7]))
				{
					$to_mail = $sheet_record[$i][7];
					//$to_serialize = serialize($to);
					$to_data = explode(',', $to_mail);
					for($k=0; $k<count($to_data); $k++)
					{
						$this->db->select('*');
						$this->db->from('internal_user');
						$this->db->where('email', ltrim($to_data[$k]));
						$queries = $this->db->get();
						$res_to = $queries->result();
						
						if(count($res_to) > 0)
						{
							$to[] = array(
								'login_id' => $res_to[0]->login_id,
								'email' => ltrim($to_data[$k])
							);
						}
					}
					$to_serialize = serialize($to);
				}
			} 
			if($sheet_record[0][8] == "Estimated Date")
			{
				if(!empty($sheet_record[$i][8]))
				{
					$estimated_date = $sheet_record[$i][8];
				}
			}
			
			if($sheet_record[0][9] == "Email Content")
			{
				if(!empty($sheet_record[$i][9]))
				{
					$email_content = $sheet_record[$i][9];
				}
			}
			
			if($sheet_record[0][10] == "Assign Keywords")
			{
				if(!empty($sheet_record[$i][10]))
				{
					$key_words = $sheet_record[$i][10];
				}
			}
			
			if($sheet_record[0][11] == "Craeted By (EMP CODE)")
			{
				if(!empty($sheet_record[$i][11]))
				{
					$created_by = $sheet_record[$i][11];
					
					$this->db->select('*');
					$this->db->from('internal_user');
					$this->db->where('loginhandle', ltrim($created_by));
					$my_query = $this->db->get();
					$res_user = $my_query->result();
					if(count($res_user) > 0)
					{
						$created_by = $res_user[0]->login_id;
					}
					else{
						$created_by = 1;
					}
				}
			}
			
			$array = array(
				'filename' => '',
				'projectname' => $projectname,
				'keywords' => $key_words,
				'project_id' => $serialize_project,
				'project_name' => $nature_of_project,
				'project_type' => $project_type,
				'text_instruction' => $email_content,
				'client_name' => $clint_name,
				'territory' => $territory,
				'currency' => $currency,
				'upload_request' => 1,
				'created_by' => $created_by,
				'created_at' => date('Y-m-d')	
			);
			
			$this->db->insert('rfq_request_estimate', $array);
			$last_id = $this->db->insert_id();
			
			$data = array(
				'lunch_req_id' => $last_id,
				'mail_to' => $to_serialize,
				'expected_date' => $estimated_date,
				'subject' => $nature_of_project,
				'message_type' => 'lunch request',
				'created_at' => date('Y-m-d'),
				'created_by' => $created_by
			);
			$this->db->insert('rfq_lunch_req_mail', $data);
			$insert_id = $this->db->insert_id();
			
			for($l=0; $l<count($to);$l++)
			{
				$array = array(
					'lunch_id' => $last_id,
					'mail_id' => $insert_id,
					'login_id' => $to[$l]['login_id'],
					'created_at' => date('Y-m-d'),
					'created_by' => $created_by
				);
				$this->db->insert('rfq_lunch_req_member', $array);
			}
			
			unset($to);
			unset($array);
			
		}
		
	}
	
	public function get_records_estimate($id)
	{
		$this->db->select('rfq_request_estimate.*, internal_user.full_name');
		$this->db->from('rfq_request_estimate');
		$this->db->join('rfq_lunch_req_member','rfq_lunch_req_member.lunch_id = rfq_request_estimate.id');
		$this->db->join('internal_user','internal_user.login_id = rfq_request_estimate.created_by');
		$this->db->where('rfq_lunch_req_member.login_id', $id);
		$this->db->where('rfq_request_estimate.add_estimation_submit !=', null);
		$this->db->where('rfq_request_estimate.add_estimation_approved', null);
		$this->db->group_by('rfq_request_estimate.id');
		$this->db->order_by('rfq_request_estimate.created_at', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function save_esimated_records($id, $name, $unit, $rate, $totalrate, $user, $type, $formid, $skill, $no_unit = "", $hours_unit = "", $duration_type = "", $duration = "", $date = "", $unit_type="")
	{
		if($date != "")
		{
			$current_date = $date;
		}else{
			$current_date = date('Y-m-d');
		}
		
		if($type == "effort")
		{
			$array = array(
				'lunch_req_id' => $id,
				'effort_name' => $name,
				'skill_level' => $skill,
				"no_of_unit" => $no_unit,
				"hours_per_unit" => $hours_unit,
				'no_of_hour' => $unit,
				'rate_per_hour' => $rate,
				'total_rate' => $totalrate,
				'unit_type' => $unit_type,
				'created_at' => $current_date,
				'created_by' => $user
			);	
			$this->db->insert('rfq_effort_estimate', $array);				
		}
		else if($type == "hardware")
		{
			$array = array(
				'lunch_req_id' => $id,
				'hardware_name' => $name,
				'no_of_count' => $unit,
				'unit_cost' => $rate,
				'total_rate' => $totalrate,
				'created_at' => $current_date,
				'created_by' => $user
			);			
			$this->db->insert('rfq_hardware_estimate', $array);					
		}
		else if($type == "manpower")
		{
			$array = array(
				'lunch_req_id' => $id,
				'manpower_name' => $name,
				'duration_type' => $duration_type,
				'duration' => $duration,
				'no_of_people' => $unit,
				'allowance' => $rate,
				'total_rate' => $totalrate,
				'created_at' => $current_date,
				'created_by' => $user
			);			
			$this->db->insert('rfq_manpower_estimate', $array);			
		}
		else if($type == "software")
		{
			$array = array(
				'lunch_req_id' => $id,
				'software_name' => $name,
				'no_of_lincenses' => $unit,
				'unitcost' => $rate,
				'total_rate' => $totalrate,
				'created_at' => $current_date,
				'created_by' => $user
			);			
			$this->db->insert('rfq_software_estimate', $array);		
		}
		else if($type == "outstation")
		{
			$array = array(
				'lunch_req_id' => $id,
				'item_name' => $name,
				'no_of_resource' => $unit,
				'cost_per_person' => $rate,
				'total_rate' => $totalrate,
				'created_at' => $current_date,
				'created_by' => $user
			);			
			$this->db->insert('rfq_outstation_estimate', $array);		
		}

		return true;
	}
	
	public function save_finance_proposal($record, $myrecord="", $type, $user, $total, $estimate_id, $totalamt, $estimate_submit, $finance_submit, $estimate_file, $addsubmit_by, $approve_by, $date="", $finance_file)
	{
		if($date != "")
		{
			$current_date = $date;
		}else{
			$current_date = date('Y-m-d');
		}
		if($type == "domestic" || $type == "domestic tender")
		{
			if($myrecord != "")
			{
				for($i=0; $i<count($myrecord); $i++)
				{					
					$data = array(
						'estimate_id' => $estimate_id,
						'project_type' => 1,
						'name' => $myrecord[$i]->name,
						'total_estimate' => $total,
						'rate_percent' => $myrecord[$i]->rate,
						'amount' => $myrecord[$i]->totalrate,
						'created_by' => $user,
						'created_at' => $current_date
					);
					$this->db->insert('rfq_financial_proposal', $data);	
				}	
			}			
			
			$data = array(
				'estimate_id' => $estimate_id,
				'project_type' => 1,
				'name' => $record[0]->name,
				'total_estimate' => $total,
				'rate_percent' => $record[0]->rate,
				'amount' => $record[0]->totalrate,
				'created_by' => $user,
				'created_at' => $current_date
			);
			$this->db->insert('rfq_financial_proposal', $data);
			
			
		}
		else if($type == "international" || $type == "international tender")
		{
			for($i=0; $i<count($record); $i++)
			{
				$data = array(
					'estimate_id' => $estimate_id,
					'project_type' => 2,
					'name' => $record[$i]->name,
					'total_estimate' => $total,
					'dollar_rate' => $record[$i]->rate,
					'exchange_rate' => $record[$i]->exrate,
					'amount' => $record[$i]->totalrate,
					'created_by' => $user,
					'created_at' => $current_date
				);
				$this->db->insert('rfq_financial_proposal', $data);	
			}
		}
		
		
		$condition = array(
			'id' => $estimate_id
		);
		$array_data = array(
			'add_estimation_submit' => $addsubmit_by,
			'add_estimation_approved' => $approve_by,
			'prepare_financial_proposal' => $user,
			'approve_financial_proposal' => $finance_submit,
			'estimate_file' => 	$estimate_file,
			'financial_file' => $finance_file,
			'total_estimate' => $totalamt,
			'updated_by' => $user,
			'updated_at' => date("Y-m-d h:i:s", strtotime($current_date))
		);
		$this->db->update('rfq_request_estimate', $array_data, $condition);
		return true;
	}
	
	public function get_estimated_employee($role)
	{
		$this->db->select('internal_user.login_id,internal_user.loginhandle as emp_code,internal_user.email,internal_user.full_name');
		$this->db->from('internal_user');
		$this->db->join('rfq_users', 'rfq_users.login_id = internal_user.login_id');
		$this->db->where('internal_user.user_status', '1');
		//$this->db->where('internal_user.login_id !=10010');
		if($role == 5){
			$this->db->where('rfq_users.user_role_id = 5 or rfq_users.user_role_id = 6');
		}
		else{	
			$this->db->where('rfq_users.user_role_id', $role);
		}
		$this->db->order_by('internal_user.full_name', 'asc');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_records_client($id)
	{
		$this->db1 = $this->load->database('amsdb', TRUE); 
		//Get My Assets
		$my_asset_sql = "SELECT * 
		FROM `client` 
		WHERE n_disp_flag = '1' and ix_client='".$id."'";
		$result = $this->db1->query($my_asset_sql);
		$this->db = $this->load->database('default', TRUE); 
		return $result->result_array(); 
	}
	
	public function edit_client($cname, $ctype, $email, $fax, $mobile, $phone, $contactperson, $description, $address, $country, $mtype, $gstno, $scode, $state, $stype, $id)
	{
		$this->db1 = $this->load->database('amsdb', TRUE); 
		$array = array(
			's_client_name' => $cname,
			's_client_type' => $ctype,
			's_client_email' => $email,
			's_client_fax' => $fax,
			's_client_mobile' => $mobile,
			's_client_phone' => $phone,
			's_client_contact' => $contactperson,
			's_client_desc' => $description,
			's_client_address' => $address,
			's_msa_type' => $mtype,
			's_gst_no' => $gstno,
			's_state_type' => $stype,
			's_state' => $state,
			's_state_code' => $scode,
			's_country' => $country
		);
		
		$condition = array(
			'ix_client' => $id
		);
		
		$this->db1->update('client', $array, $condition);
		$last_id = $this->db1->insert_id();
		$this->db = $this->load->database('default', TRUE); 
		return $last_id;
	}
	
	public function get_record_estimate_sale($login_id)
	{
		$this->db->select('internal_user.email, internal_user.full_name, rfq_role.role_name');
		$this->db->from('internal_user');
		$this->db->join('rfq_users', 'rfq_users.login_id = internal_user.login_id', 'left');
		$this->db->join('rfq_role','rfq_role.id = rfq_users.user_role_id', 'left');
		$this->db->where('internal_user.login_id',$login_id);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_launch_request_details($id)
	{
		$this->db->select('rfq_request_estimate.*, internal_user.full_name,rfq_lunch_req_mail.expected_date');
		$this->db->from('rfq_request_estimate');
		$this->db->join('rfq_lunch_req_member','rfq_lunch_req_member.lunch_id = rfq_request_estimate.id','left');
		$this->db->join('rfq_lunch_req_mail','rfq_lunch_req_mail.lunch_req_id = rfq_request_estimate.id','left');
		$this->db->join('internal_user','internal_user.login_id = rfq_request_estimate.created_by');
		$this->db->where('rfq_request_estimate.id', $id);
		$this->db->group_by('rfq_request_estimate.id');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_financial_proposaldetails($id)
	{
		$this->db->select('rfq_financial_proposal.*, rfq_effort_estimate.hours_per_unit, rfq_effort_estimate.unit_type, rfq_effort_estimate.no_of_unit');
		$this->db->from('rfq_financial_proposal');
		$this->db->join('rfq_effort_estimate', 'rfq_effort_estimate.lunch_req_id = rfq_financial_proposal.estimate_id', 'left');
		$this->db->where('rfq_financial_proposal.estimate_id', $id);
		$this->db->where('rfq_effort_estimate.hours_per_unit !=', 0.00);
		$this->db->group_by('rfq_financial_proposal.id');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_pending_estimate($role, $id)
	{
		$cond = "";
		if($role == 4)
		{
			$cond = "and created_by=".$id;
		}
		$query = $this->db->query('SELECT COUNT(*) as sumcount FROM `rfq_request_estimate` WHERE `uploaded_proposal` IS NULL '.$cond);
		return $query->result();
	}
	
	public function get_pending_estimated_record($role, $id)
	{
		$cond = "";
		if($role == 4)
		{
			$cond = "and created_by=".$id;
		}
		$query = $this->db->query('SELECT rfq_request_estimate.*, internal_user.full_name FROM `rfq_request_estimate` left join internal_user on rfq_request_estimate.created_by = internal_user.login_id WHERE `uploaded_proposal` IS NULL '.$cond.' group by id');
		return $query->result();
	}
	
	public function get_current_estimated_record($role, $id)
	{
		$cond = "";
		if($role == 4)
		{
			$cond = "and created_by=".$id;
		}
		
		$query = $this->db->query('SELECT count(*) as sumcount FROM `rfq_request_estimate` WHERE rfq_request_estimate.`created_at`="'.date('Y-m-d').'" '.$cond.' ');
		return $query->result();
	}
	
	public function get_current_estimate_list($role, $id)
	{
		$cond = "";
		if($role == 4)
		{
			$cond = "and created_by=".$id;
		}
		
		$query = $this->db->query('SELECT rfq_request_estimate.*, internal_user.full_name  FROM `rfq_request_estimate` left join internal_user on rfq_request_estimate.created_by = internal_user.login_id WHERE rfq_request_estimate.`created_at`="'.date('Y-m-d').'" '.$cond.' ');
		return $query->result();
	}
	
	
	public function get_project_list()
	{
		$query = $this->db->query("select * from rfq_nature_of_project order by id desc");
		return $query->result_array();
	}
	
	public function add_project_record($name, $login_id)
	{
		$data = array(
			'name' => $name,
			'created_at' => date('Y-m-d'),
			'created_by' => $login_id
		);
		$this->db->insert('rfq_nature_of_project', $data);
		return $this->db->insert_id();
	}
	
	public function get_nop_records($id)
	{
		$query = $this->db->query("select * from rfq_nature_of_project where id=".$id);
		return $query->result_array();
	}
	
	public function update_nop_records($id, $name)
	{
		$data = array(
			'name' => $name
		);
		$condition = array(
			'id' => $id
		);
		$this->db->where('id', $id);
		$this->db->update('rfq_nature_of_project', $data);
		return $this->db->affected_rows();
	}
	
	/* New Section */
	
	public function get_effort_records($id)
	{
		$query = $this->db->query("select * from rfq_effort_estimate where lunch_req_id =".$id);
		return $query->result_array();
	}
	
	
}
