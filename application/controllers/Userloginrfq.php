<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userloginrfq extends MY_Controller {

	 public function __construct()
	{
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$this->load->model('userloginrfq_model');
		$this->load->helper('url_helper');
		//echo "hi";exit;
	}
	
	public function login()
	{
		$array = array();
		$data = json_decode(file_get_contents("php://input"));
		if($data != "" || $data != null)
		{
			
			$result = $this->userloginrfq_model->rfq_login($data->username, $data->password);
			if(count($result) > 0)
			{
				/* $txt_pwds = stripslashes($res[0]['password']);
				if($txt_pwds != md5(trim($data->password))){
					$array['error'] = 'Invalid Detail';
				}else{ */
				
					$array['id'] = $result[0]->login_id;
					$array['login_id'] = $result[0]->login_id;
					$array['name'] = $result[0]->full_name;
					$array['email'] = $result[0]->email;
					$array['emp_code'] = $result[0]->loginhandle;
					$array['roll_id'] = $result[0]->user_role_id;
					$array['roll_name'] = $result[0]->role_name;
				//}
			}
			else{
				$array['error'] = 'Invalid Detail';
			}
			print_r(json_encode($array));
		}
		else
		{
			$array['error'] = "Error in data";
			print_r(json_encode($array));
		}
	}
	
	public function user_lists()
	{
		$array = array();
		$result = $this->userloginrfq_model->rfq_list();
		print_r(json_encode($result));
	}
	
	public function rfqusers()
	{
		$array = array();
		$result = $this->userloginrfq_model->rfqusers();
		print_r(json_encode($result));
	}
	
	public function employeelist()
	{
		$array = array();
		$result = $this->userloginrfq_model->employeelist();
		print_r(json_encode($result));
	}
	
	
	public function adduser()
	{
		$data = json_decode(file_get_contents("php://input"));
		if(!empty($data))
		{
			$result = $this->userloginrfq_model->insert_data($data->role,$data->employee);
		}
		echo $result;
	}
	
	public function deleteuser()
	{
		$id = $_GET['id'];
		if($id != "")
		{
			$res = $this->userloginrfq_model->deleterecords($id);
			echo $res;
		}
		else
		{
			echo false;
		}
	}
	
	public function roles()
	{
		$array = array();
		$result = $this->userloginrfq_model->roles();
		print_r(json_encode($result));
	}
	
	public function lunch_request()
	{
		//print_r($_FILES);exit;
		$datas = array( 'status' => 0 , 'fileName' => '');
		if(($_FILES['file']['name']) !=""){
			$path = $_FILES['file']['name'];
			$filename = time().'-project-files.'.pathinfo($path, PATHINFO_EXTENSION); 
			$config['upload_path'] = APPPATH.'../assets/upload/rfq/';
			$config['allowed_types'] = 'pdf|doc|docx|jpg|png|txt|gif|jpeg|xls|xlsx|dwg|zip';
			$config['file_name'] = $filename;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if($this->upload->do_upload('file')){
				$fileData = $this->upload->data();
				$datas = array( 'status' => 1 , 'fileName' => $filename);
			}
			else{
				$error = array('error' => $this->upload->display_errors());
				//print_r($error);
				$datas = array( 'status' => 0 , 'fileName' => '');
			}
		}
		print_r( json_encode($datas));
	}
	
	
	public function upload_multiple_file()
	{
		$datas = array();
		$file_count = count($_FILES['file']['name']);
		$datas['datas']['counts'] = $file_count;
		for($i=0; $i<$file_count; $i++)
		{
			if(($_FILES['file']['name'][$i]) !=""){
				$_FILES['files']['name']     = $_FILES['file']['name'][$i];
				$_FILES['files']['type']     = $_FILES['file']['type'][$i];
				$_FILES['files']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
				$_FILES['files']['error']     = $_FILES['file']['error'][$i];
				$_FILES['files']['size']     = $_FILES['file']['size'][$i];
							
								
				$path = $_FILES['file']['name'][$i];
				$filename = time().'-project-specification.'.pathinfo($path, PATHINFO_EXTENSION); 
				$config['upload_path'] = APPPATH.'../assets/upload/rfq/';
				$config['allowed_types'] = 'pdf|doc|docx|jpg|png|txt|gif|jpeg|xls|xlsx|dwg|zip';
				$config['file_name'] = $filename;
			
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if($this->upload->do_upload('files')){
					$fileData = $this->upload->data();
					$datas[$i] = array( 'status' => 1 , 'fileName' => $filename);
				}
				else{
					$error = array('error' => $this->upload->display_errors());
					$datas[$i] = array( 'status' => 0 , 'fileName' => '', 'error'=>$error);
				}
			}
		}
		print_r(json_encode($datas));
	}
	
	
	public function upload_vendor_file_multiple()
	{
		$datas = array();
		$file_count = count($_FILES['file']['name']);
		$datas['datas']['counts'] = $file_count;
		for($i=0; $i<$file_count; $i++)
		{
			if(($_FILES['file']['name'][$i]) !=""){
				$_FILES['files']['name']     = $_FILES['file']['name'][$i];
				$_FILES['files']['type']     = $_FILES['file']['type'][$i];
				$_FILES['files']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
				$_FILES['files']['error']     = $_FILES['file']['error'][$i];
				$_FILES['files']['size']     = $_FILES['file']['size'][$i];
							
								
				$path = $_FILES['file']['name'][$i];
				$filename = time().'-vendor-file.'.pathinfo($path, PATHINFO_EXTENSION); 
				$config['upload_path'] = APPPATH.'../assets/upload/rfq/';
				$config['allowed_types'] = 'pdf|doc|docx|jpg|png|txt|gif|jpeg|xls|xlsx|dwg|zip';
				$config['file_name'] = $filename;
			
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if($this->upload->do_upload('files')){
					$fileData = $this->upload->data();
					$datas[$i] = array( 'status' => 1 , 'fileName' => $filename);
				}
				else{
					$error = array('error' => $this->upload->display_errors());
					$datas[$i] = array( 'status' => 0 , 'fileName' => '', 'error'=>$error);
				}
			}
		}
		print_r(json_encode($datas));
	}
	
	
	public function projectlist()
	{
		$result = $this->userloginrfq_model->get_projectrecord();
		echo json_encode($result);
	}
	
	
	public function save_request_estimate()
	{
		$result = array();
		$data = json_decode(file_get_contents("php://input"));
		$res = array(); $log_id = "";
		for($i=0; $i< count($data->to);$i++)
		{
			$log_id = $data->to[$i]->login_id;
		}

		$records = $this->userloginrfq_model->get_records_name($log_id);
		if(!empty($data) && !empty($records))
		{
			$res = $this->userloginrfq_model->save_records($data->keywords, $data->projectname, $data->filename, $data->attachfile, $data->textediter, $data->user, $data->type, $data->country, $data->client, $data->currency, $data->project_name);
			for($i=0;$i<count($data->projectname);$i++)
			{
				$file[$i] = $data->projectname[$i]->itemName; 
			}
			$myfilename = implode(',', $file);	

		}
		
		if($res > 0 && !empty($records))
		{
			$logo_URL = base_url().'assets/images/logo.gif';
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';

			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
			<a href="http://www.linkedin.com/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
			<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
			</div>';

			$subject = 'Add Estimate';
			$site_base_url= $this->config->item('rfqUrl').'/#/estimate-sheet';

			$from = 'hr@polosoftech.com';
			$fromName = 'POLOHRM RFQ Team';
			$file_array = array(); //echo "count".count($data->filename); print_r($data->filename);
			for($i=0; $i<count($data->filename); $i++)
			{
				$filename = $data->filename[$i];
				$path = APPPATH.'../assets/upload/rfq';
				$file = $path . "/" . $filename;
				array_push($file_array, $file);
			}
			//print_r($data->to);

			//email body content
			$htmlContent= "{$logoText} <div style=\"width:100%; font-family: verdana; font-size: 13px;\">
			<div style=\"width:900px; margin: 0 auto; background: #fff;\">
			<div style=\"width:650px; float: left; min-height: 190px;\">
			<div style=\"padding: 7px 7px 14px 10px;\">
			<p>Hi ".$records[0]->name_first.", </p>
			<p>Added estimation request to for ".$myfilename.".</p> 
			<p style=\"color:#f00; font-style: italic; padding-bottom: 30px;\">Note: <strong> Add estimation should be submit on or before ".$data->expected_date."</strong>.</p>
			<p>Please <a href=\"{$site_base_url}\" style=\"text-decoration:none\"> click here to open the RFQMS </a>and respond<br /><br /></p>
			<p> In case of any Query, Please contact to Sales Department.</p> 
			<p>{$footer}</p>
			</div> 
			</div> 
			</div> 
			</div>";



			//preparing attachment
			if(!empty($file_array) > 0){
				
				if(is_file($file)){
					//header for sender info
					$headers = "From: $fromName"." <".$from.">";
					$headers .= "\nCc: lalit.tyagi@polosoftech.com";

					//boundary 
					$semi_rand = md5(time()); 
					$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

					//headers for attachment 
					$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
	
					//multipart boundary 
					$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
					"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";
					
					$message .= "--{$mime_boundary}\n";
					
					for($x=0;$x<count($file_array);$x++){
						$fp =    @fopen($file_array[$x],"rb");
						$datas =  @fread($fp,filesize($file_array[$x]));

						@fclose($fp);
						$datas = chunk_split(base64_encode($datas));
						$message .= "Content-Type: application/octet-stream; name=\"".basename($file_array[$x])."\"\n" . 
						"Content-Description: ".basename($file_array[$x])."\n" .
						"Content-Disposition: attachment;\n" . " filename=\"".basename($file_array[$x])."\"; size=".filesize($file_array[$x]).";\n" . 
						"Content-Transfer-Encoding: base64\n\n" . $datas . "\n\n";
						$message .= "--{$mime_boundary}--";
					}
					
					$returnpath = "-f" . $from;

					//send email
					for($i=0; $i<count($data->to); $i++)
					{
						$mail = @mail($data->to[$i]->email, $subject, $message, $headers, $returnpath);						
					}
				}
				else{
					$headers = 'MIME-Version: 1.0' . "\r\n";

					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= "From: $fromName"." <".$from.">" . "\r\n";
					$headers .= 'Cc: hr@polosoftech.com' . "\r\n";	
					//$headers .= 'Cc: ' . "\r\n";	
					$headers .= 'Reply-To:No reply <no-reply@polosoftech.com>' . "\r\n";
					$headers .= 'X-Mailer: PHP/' . phpversion();

					for($i=0; $i<count($data->to); $i++)
					{	
						mail($data->to[$i]->email, $subject, $htmlContent, $headers);
					}
				}
			}
			else
			{
				$headers = 'MIME-Version: 1.0' . "\r\n";

				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= "From: $fromName"." <".$from.">" . "\r\n";
				$headers .= 'Cc: hr@polosoftech.com' . "\r\n";	
				//$headers .= 'Cc: ' . "\r\n";	
				$headers .= 'Reply-To:No reply <no-reply@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();

				for($i=0; $i<count($data->to); $i++)
				{
					mail($data->to[$i]->email, $subject, $htmlContent, $headers);
					
				}
			}

			$this->userloginrfq_model->sendlre_mail($myfilename, $data->expected_date, $htmlContent, $data->to, $res, $data->user);

			$result['res'] = 'success';	
		}
		else
		{
			$result['res'] = 'error';
		}
		echo json_encode($result);
	}
	
	
	public function save_vendor_request_estimate()
	{
		$result = array();
		$data = json_decode(file_get_contents("php://input"));
		$res = array(); $log_id = "";
		/* for($i=0; $i< count($data->to);$i++)
		{
			$log_id = $data->to[$i]->login_id;
		}

		$records = $this->userloginrfq_model->get_records_name($log_id); */
		//if(!empty($data) && !empty($records))   
		if(!empty($data))
		{
			$res = $this->userloginrfq_model->save_vendor_records($data->keywords, $data->projectname, $data->filename, $data->attachfile, $data->textediter, $data->user, $data->type, $data->country, $data->client, $data->currency, $data->project_name, $data->vendor_name, $data->vendor_type, $data->scope_of_work, $data->total_cost, $data->currency_code, $data->gst_inclusive, $data->vendorfilename);
			for($i=0;$i<count($data->projectname);$i++)
			{
				$file[$i] = $data->projectname[$i]->itemName; 
			}
			$myfilename = implode(',', $file);	

		}
		
		//if($res > 0 && !empty($records))
		if($res > 0 )
		{
			/* $logo_URL = base_url().'assets/images/logo.gif';
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';

			$footer = '<a href="http://www.facebook.com/AABSyS" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
			<a href="http://www.linkedin.com/company/336775" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
			<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
			</div>';

			$subject = 'Add Financial Proposal';
			$site_base_url= $this->config->item('rfqUrl').'/#/financial-proposal-list';

			$from = 'support@polosoftech.com';
			$fromName = 'AABSyS RFQ Team';
			$file_array = array(); //echo "count".count($data->filename); print_r($data->filename);
			for($i=0; $i<count($data->filename); $i++)
			{
				$filename = $data->filename[$i];
				$path = APPPATH.'../assets/upload/rfq';
				$file = $path . "/" . $filename;
				array_push($file_array, $file);
			}
			//print_r($data->to);

			//email body content
			$htmlContent= "{$logoText} <div style=\"width:100%; font-family: verdana; font-size: 13px;\">
			<div style=\"width:900px; margin: 0 auto; background: #fff;\">
			<div style=\"width:650px; float: left; min-height: 190px;\">
			<div style=\"padding: 7px 7px 14px 10px;\">
			<p>Hi ".$records[0]->name_first.", </p>
			<p>Added financial proposal to for ".$myfilename.".</p>
			<p>Please <a href=\"{$site_base_url}\" style=\"text-decoration:none\"> click here to open the RFQMS </a>and respond<br /><br /></p>
			<p> In case of any Query, Please contact to Sales Department.</p> 
			<p>{$footer}</p>
			</div> 
			</div> 
			</div> 
			</div>";



			//preparing attachment
			if(!empty($file_array) > 0){
				
				if(is_file($file)){
					//header for sender info
					$headers = "From: $fromName"." <".$from.">";
					$headers .= "\nCc: shyam.mohapatra@polosoftech.com";

					//boundary 
					$semi_rand = md5(time()); 
					$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

					//headers for attachment 
					$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
	
					//multipart boundary 
					$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
					"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";
					
					$message .= "--{$mime_boundary}\n";
					
					for($x=0;$x<count($file_array);$x++){
						$fp =    @fopen($file_array[$x],"rb");
						$datas =  @fread($fp,filesize($file_array[$x]));

						@fclose($fp);
						$datas = chunk_split(base64_encode($datas));
						$message .= "Content-Type: application/octet-stream; name=\"".basename($file_array[$x])."\"\n" . 
						"Content-Description: ".basename($file_array[$x])."\n" .
						"Content-Disposition: attachment;\n" . " filename=\"".basename($file_array[$x])."\"; size=".filesize($file_array[$x]).";\n" . 
						"Content-Transfer-Encoding: base64\n\n" . $datas . "\n\n";
						$message .= "--{$mime_boundary}--";
					}
					
					$returnpath = "-f" . $from;

					//send email
					for($i=0; $i<count($data->to); $i++)
					{
						$mail = @mail($data->to[$i]->email, $subject, $message, $headers, $returnpath);						
					}
				}
				else{
					$headers = 'MIME-Version: 1.0' . "\r\n";

					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= "From: $fromName"." <".$from.">" . "\r\n";
					$headers .= 'Cc: shyam.mohapatra@polosoftech.com' . "\r\n";	
					//$headers .= 'Cc: ' . "\r\n";	
					$headers .= 'Reply-To:No reply <no-reply@polosoftech.com>' . "\r\n";
					$headers .= 'X-Mailer: PHP/' . phpversion();

					for($i=0; $i<count($data->to); $i++)
					{	
						mail($data->to[$i]->email, $subject, $htmlContent, $headers);
					}
				}
			}
			else
			{
				$headers = 'MIME-Version: 1.0' . "\r\n";

				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= "From: $fromName"." <".$from.">" . "\r\n";
				$headers .= 'Cc: shyam.mohapatra@polosoftech.com' . "\r\n";	
				//$headers .= 'Cc: ' . "\r\n";	
				$headers .= 'Reply-To:No reply <no-reply@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();

				for($i=0; $i<count($data->to); $i++)
				{
					mail($data->to[$i]->email, $subject, $htmlContent, $headers);
					
				}
			}

			$this->userloginrfq_model->sendlre_mail($myfilename, $data->expected_date, $htmlContent, $data->to, $res, $data->user); */

			$result['res'] = 'success';	
		}
		else
		{
			$result['res'] = 'error';
		}
		echo json_encode($result);
	}
	
	
	
	public function test_email_launch_request()
	{
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';

		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="http://www.linkedin.com/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
		&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';

		$subject = "Test RFQ Mail";
		$site_base_url= $this->config->item('rfqUrl');

		$from = 'hr@polosoftech.com';
		$fromName = 'POLOHRM Team';
		$to = 'hr@polosoftech.com';
		//$filename = '1541741509-project-specification.pdf';
		$filename = '';
		$path = APPPATH.'../assets/upload/rfq';
		$file = $path . "/" . $filename;

		//email body content
		$htmlContent= "{$logoText} <div style=\"width:100%; font-family: verdana; font-size: 13px;\">
		<div style=\"width:900px; margin: 0 auto; background: #fff;\">
		<div style=\"width:650px; float: left; min-height: 190px;\">
		<div style=\"padding: 7px 7px 14px 10px;\">
		<p>Hi Manoja, </p>
		<p>Added estimation request to for RFQ.</p> 
		<p style=\"color:#f00; font-style: italic; padding-bottom: 30px;\">Note: <strong> Add estimation should be submit on or before 2018.11.12</strong>.</p>
		<p>You have received a Request for Estimate. Please click here to open the <a href=\"{$site_base_url}\" style=\"text-decoration:none\">RFQMS </a>and respond<br /><br /></p>
		<p> In case of any Query, Please contact to Sales Department.</p> 
		<p>{$footer}</p>
		</div> 
		</div> 
		</div> 
		</div>";
		$file_array = array();
		$file_name = array('1541741509-project-specification.pdf', '1541741509-project-specification.pdf');
		for($i=0; $i<count($file_name); $i++)
		{
			$filename = $file_name[$i];
			$path = APPPATH.'../assets/upload/rfq';
			$file = $path . "/" . $filename;
			array_push($file_array, $file);
		}

		//preparing attachment
		if(!empty($file_array) > 0){
			if(is_file($file)){
				

				//header for sender info
				$headers = "From: $fromName"." <".$from.">";
				/*$headers .= "\nCc: manoja.soft@gmail.com";*/

				//boundary 
				$semi_rand = md5(time()); 
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

				//headers for attachment 
				$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

				//multipart boundary 
				$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
				"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";
				
				$message .= "--{$mime_boundary}\n";
				
				
				/* $fp =    @fopen($file,"rb");
				$data =  @fread($fp,filesize($file));

				@fclose($fp);
				$data = chunk_split(base64_encode($data));
				$message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" . 
				"Content-Description: ".basename($file)."\n" .
				"Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" . 
				"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
				$message .= "--{$mime_boundary}--"; */
				
				
				for($x=0;$x<count($file_array);$x++){
					$fp =    @fopen($file_array[$x],"rb");
					$data =  @fread($fp,filesize($file_array[$x]));

					@fclose($fp);
					$data = chunk_split(base64_encode($data));
					$message .= "Content-Type: application/octet-stream; name=\"".basename($file_array[$x])."\"\n" . 
					"Content-Description: ".basename($file_array[$x])."\n" .
					"Content-Disposition: attachment;\n" . " filename=\"".basename($file_array[$x])."\"; size=".filesize($file_array[$x]).";\n" . 
					"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
					$message .= "--{$mime_boundary}--";
				}
				
				
				$returnpath = "-f" . $from;

				//send email
				$mail = @mail($to, $subject, $message, $headers, $returnpath); 

				//email sending status
				echo $mail?"<h1>Mail sent.</h1>":"<h1>Mail sending failed.</h1>";
				
				//@mail($to, $subject, $message, $headers, $returnpath);
			}
			else{
				$headers = 'MIME-Version: 1.0' . "\r\n";

				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= "From: $fromName"." <".$from.">" . "\r\n";
				$headers .= 'Cc: hr@polosoftech.com' . "\r\n";	
				$headers .= 'Reply-To:No reply <no-reply@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();

				
				mail($to, $subject, $htmlContent, $headers);
			}
		}
		else
		{
			$headers = 'MIME-Version: 1.0' . "\r\n";

			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: $fromName"." <".$from.">" . "\r\n";
			$headers .= 'Cc: hr@polosoftech.com' . "\r\n";	
			$headers .= 'Reply-To:No reply <no-reply@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();

			
			mail($to, $subject, $htmlContent, $headers);	
		}	
				
		$result['res'] = 'success';	
		
		echo json_encode($result);
	}
	
	public function sendmail()
	{
		$to = 'hr@polosoftech.com';

		//sender
		$from = 'hr@polosoftech.com';
		$fromName = 'POLOHRM RFQ Team';

		//email subject
		$subject = 'PHP Email with Attachment by CodexWorld'; 

		//attachment file path
		//$file = "codexworld.pdf";
		$filename = '1541739848-project-specification.png';
		$path = APPPATH.'../assets/upload/rfq';
		$file = $path . "/" . $filename;

		//email body content
		$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = "Add estimate request";
			$site_base_url= $this->config->item('rfqUrl');
			$htmlContent= "{$logoText} <div style=\"width:100%; font-family: verdana; font-size: 13px;\">
            <div style=\"width:900px; margin: 0 auto; background: #fff;\">
             <div style=\"width:650px; float: left; min-height: 190px;\">
                 <div style=\"padding: 7px 7px 14px 10px;\">
						<p>Hi Manoja, </p>
						<p>Added estimation request to for Launch Request.</p> 
						<p style=\"color:#f00; font-style: italic; padding-bottom: 30px;\">Note: <strong> Add estimation should be submit on or before 05-11-2018</strong>.</p>
						<p>You have received a Request for Estimate. Please click here to open the <a href=\"{$site_base_url}\" style=\"text-decoration:none\">RFQMS </a>and respond<br /><br /></p>
						 <p> In case of any Query, Please contact to Sales Department.</p>                                 
						 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>";
		
		
		
		
		/* $htmlContent = '<h1>PHP Email with Attachment by CodexWorld</h1>
			<p>This email has sent from PHP script with attachment.</p>'; */

		//header for sender info
		$headers = "From: $fromName"." <".$from.">";
		/*$headers .= "\nCc: manoja.soft@gmail.com";*/

		//boundary 
		$semi_rand = md5(time()); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

		//headers for attachment 
		$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

		//multipart boundary 
		$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
		"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n"; 

		//preparing attachment
		if(!empty($file) > 0){
			if(is_file($file)){
				$message .= "--{$mime_boundary}\n";
				$fp =    @fopen($file,"rb");
				$data =  @fread($fp,filesize($file));

				@fclose($fp);
				$data = chunk_split(base64_encode($data));
				$message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" . 
				"Content-Description: ".basename($file)."\n" .
				"Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" . 
				"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
			}
		}
		$message .= "--{$mime_boundary}--";
		$returnpath = "-f" . $from;

		//send email
		$mail = @mail($to, $subject, $message, $headers, $returnpath); 

		//email sending status
		echo $mail?"<h1>Mail sent.</h1>":"<h1>Mail sending failed.</h1>";

	}
	
	
	public function get_request_estimate()
	{
		$data = json_decode(file_get_contents('php://input'));
		$array = array();
		$result = $this->userloginrfq_model->get_records($data->id, $data->user_id);
		if(!empty($result))
		{
			for($i=0; $i < count($result); $i++)
			{
				$datas[$i] = unserialize($result[$i]->project_id);
				$files[$i] = unserialize($result[$i]->filename);
			}
			$array = array(
				'res' => $result,
				'data' => $datas,
				'files' => $files
			);	
		}
		else{
			$array = array(
				'res' => 'error',
				'data' => 0,
				'files' => 0
			);
		}
		echo json_encode($array);
	}
	
	
	public function delete_request_estimate()
	{
		$id = $_GET['id'];
		$res = $this->userloginrfq_model->delete_records($id);
		echo $res;
	}
	
	public function edit_request_estimate()
	{
		//$id = $_GET['id'];
		$array1 = array();
		$data = json_decode(file_get_contents("php://input"));
		$result = $this->userloginrfq_model->edit_records($data->id);
		$datas = unserialize($result[0]->project_id);
		$mailto = unserialize($result[0]->mail_to);
		
		$files = unserialize($result[0]->filename);
		switch($result[0]->project_type){
			case 1 : 
			$res = array(
				'id' => 1,
				'name' => 'Domestic'
			);
			break;
			case 2:
			$res = array(
				'id' => 2,
				'name' => 'International'
			);
			break;
			case 3:
			$res = array(
				'id' => 3,
				'name' => 'Domestic Tender'
			);
			break;
			case 4:
			$res = array(
				'id' => 4,
				'name' => 'International Tender'
			);
			break;
		}
		array_push($array1, $res);
		
		$array = array(
			'res' => $result,
			'data' => $datas,
			'type' => $array1,
			'to' => $mailto,
			'files' => $files
		);
		echo json_encode($array);
	}
	
	
	
	public function request_estimate_edit()
	{
		$data = json_decode(file_get_contents("php://input"));
		if(!empty($data))
		{
			$res = $this->userloginrfq_model->edit_request_estimate($data->keywords, $data->projectname, $data->filename, $data->attachfile, $data->id, $data->textediter, $data->user, $data->type, $data->client, $data->country, $data->currency, $data->project_name);
			for($i=0;$i<count($data->projectname);$i++)
			{
				$file[$i] = $data->projectname[$i]->itemName; 
			}
			$myfilename = implode(',', $file);
		}
		//$records = $this->userloginrfq_model->get_records_name($data->to);
		/*if($data->id != 0 && !empty($records))
		{
			$site_base_url=base_url();
			$logo_URL = base_url('assets/imgs/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/AABSyS" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/336775" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = $myfilename;
			//$site_base_url=base_url('timesheet/regularise_request');
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
						<p>Hi {$records[0]->name_first}, </p>
						<p>Added estimation request to for {$myfilename}.</p> 
						<p style="color:#f00; font-style: italic; padding-bottom: 30px;">Note: <strong> Add estimation should be submit on or before {$data->expected_date}</strong>.</p>
						<p>You have received a Request for Estimate. Please click here to open the <a href="{$site_base_url}" style="text-decoration:none">RFQMS </a>and respond<br /><br /></p>
						 <p> In case of any Query, Please contact to Sales Department.</p>                                 
						 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				$cc_header = "";	
				$headers = "";
				$this->userloginrfq_model->sendlre_mail_edit($myfilename, $data->expected_date, $message, $data->to, $data->id, $data->user);
				
				$bcc_mail[] = 'shyam.mohapatra@polosoftech.com';
				//$headers .= 'Reply-To:No reply  <no-reply@polosoftech.com>' . "\r\n";
				//$headers .= 'X-Mailer: PHP/' . phpversion();
				
				// 13.11.208
				
				    $filename = $data->filename;
					$path = APPPATH.'../assets/upload/rfq/';
					$file = $path . "/" . $filename;

					$content = file_get_contents($file);
					$content = chunk_split(base64_encode($content));

					// a random hash will be necessary to send mixed content
					$separator = md5(time());

					// carriage return type (RFC)
					$eol = "\r\n";

					// main header (multipart mandatory)
					//$headers = "From: name <test@test.com>" . $eol;
					//$headers .= "MIME-Version: 1.0" . $eol;
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Cc: '."shyam.mohapatra@polosoftech.com". "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
					$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
					$headers .= "This is a MIME encoded message." . $eol;

					// message
					$body = "--" . $separator . $eol;
					$body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
					$body .= "Content-Transfer-Encoding: 8bit" . $eol;
					$body .= $message . $eol;

					// attachment
					$body .= "--" . $separator . $eol;
					$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
					$body .= "Content-Transfer-Encoding: base64" . $eol;
					$body .= "Content-Disposition: attachment" . $eol;
					$body .= $content . $eol;
					$body .= "--" . $separator . "--";
					
				for($i=0; $i<count($records); $i++)
				{
					//mail($records[$i]->email, $subject, $message, $headers);	
					mail($records[$i]->email, $subject, $body, $headers);	
				}
		}	*/
		if($res > 0)
		{
			$result['res'] = 'success';	
		}
		else
		{
			$result['res'] = 'error';
		}
		echo json_encode($result);
	}
	
	
	public function mymail_send()
	{
		$data = json_decode(file_get_contents("php://input"));
		if(!empty($data))
		{
			$res = $this->userloginrfq_model->send_mail($data->subject, $data->exp_date, $data->message, $data->to, $data->cc, $data->bcc, $data->id, $data->user);
		}
		
			$logo_URL = base_url('assets/imgs/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = $data->subject;
			$site_base_url= $this->config->item('rfqUrl');
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
						 {$data->message}
						<p style="color:#f00; font-style: italic; padding-bottom: 30px;">Note: <strong> Add estimation should be submit on or before {$data->exp_date}</strong>.</p>
						<p>You have received a Request for Estimate. Please click here to open the <a href="{$site_base_url}" style="text-decoration:none">RFQMS </a>and respond<br /><br /></p>
						 <p> In case of any Query, Please contact to HR Department.</p>                                 
						 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				//$to =$repInfo[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$data->from_name.'. <'.$data->from_mail.'>' . "\r\n";
				$cc_header = "";	
				for($i=0; $i<count($data->cc);$i++)
				{
					$cc_mail[] = $data->cc[$i]->email;
				}
				for($i=0; $i<count($data->bcc);$i++)
				{
					$bcc_mail[] = $data->bcc[$i]->email;
				}
				$bcc_mail[] = 'hr@polosoftech.com';
				$cc_header = implode(',',$cc_mail);
				$bcc_header = implode(',',$bcc_mail);
				$headers .= 'Cc: '.$cc_header . "\r\n";
				$headers .= 'Bcc: '.$bcc_header . "\r\n";				
				$headers .= 'Reply-To:No reply  <no-reply@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				for($i=0; $i<count($data->to);$i++)
				{
					mail($data->to[$i]->email, $subject, $message, $headers);	
				}//echo $message; exit;
		if($res == 1)
		{
			$result['res'] = 'success';	
		}
		else
		{
			$result['res'] = 'error';
		}
		echo json_encode($result);
	}
	
	public function getEstimate()
	{
		$request = json_decode(file_get_contents("php://input"));
		$array = array();
		//$id = $_GET['id'];
		$data = $this->userloginrfq_model->getEstimate($request->id);
		$files = array();
		for($i=0; $i<count($data);$i++)
		{
			$files[$i] = unserialize($data[$i]->filename);
		}
		$array['data'] = $data;
		$array['files'] = $files;
		echo json_encode($array);
	}
	
	public function saveEstimate()
	{
		$data = json_decode(file_get_contents("php://input"));
		$array = array();
		
		
		if($data->type == "effort")
		{
			for($i=0; $i<count($data->form);$i++)
			{
				$res = $this->userloginrfq_model->save_esimated($data->id, $data->form[$i]->effort, $data->form[$i]->hour, '', '', $data->loginid, 'effort', $data->form[$i]->id, $data->ids, $data->form[$i]->skill, $data->form[$i]->no_units, $data->form[$i]->hours_units, "", "", $data->form[$i]->units_type);	
			}	
		}
		else if($data->type == "hardware")
		{
			for($i=0; $i<count($data->form);$i++)
			{
				$res = $this->userloginrfq_model->save_esimated($data->id, $data->form[$i]->hardware, $data->form[$i]->counts, $data->form[$i]->unitcost, $data->form[$i]->totalcost, $data->loginid, 'hardware', $data->form[$i]->id, $data->ids, "", "", "", "", "", "");	
			}
		}
		
		else if($data->type == "manpower")
		{
			for($i=0; $i<count($data->form);$i++)
			{
				$res = $this->userloginrfq_model->save_esimated($data->id, $data->form[$i]->name, $data->form[$i]->counts, $data->form[$i]->allowance, $data->form[$i]->totalcost, $data->loginid, 'manpower', $data->form[$i]->id, $data->ids, "", "", "", $data->duration_type, $data->form[$i]->duration, "");	
			}
		}
		else if($data->type == "software")
		{
			for($i=0; $i<count($data->form);$i++)
			{
				$res = $this->userloginrfq_model->save_esimated($data->id, $data->form[$i]->softwarename, $data->form[$i]->lincenses, $data->form[$i]->unitcost, $data->form[$i]->totalcost, $data->loginid, 'software', $data->form[$i]->id, $data->ids, "", "", "", "", "", "");	
			}
		}
		else if($data->type == "outstation")
		{
			for($i=0; $i<count($data->form);$i++)
			{
				$res = $this->userloginrfq_model->save_esimated($data->id, $data->form[$i]->name, $data->form[$i]->counts, $data->form[$i]->allowance, $data->form[$i]->totalcost, $data->loginid, 'outstation', $data->form[$i]->id, $data->ids, "", "", "", "", "", "");	
			}
		}
		
		if($res == 1)
		{
			$result['res'] = 'successfully saved';	
		}
		else
		{
			$result['res'] = 'error';
		}
		echo json_encode($result);
	}
	
	public function getEffortestimate()
	{
		$id = $_GET['id'];
		$res = $this->userloginrfq_model->get_estimate_effort($id);
		$result['res'] = $res;
		echo json_encode($result);
	}
	
	public function gethardwareEstimate()
	{
		$id = $_GET['id'];
		$res = $this->userloginrfq_model->get_estimate_hardware($id);
		$result['res'] = $res;
		echo json_encode($result);
	}
	
	public function getmanpowerEstimate()
	{
		$id = $_GET['id'];
		$res = $this->userloginrfq_model->get_estimate_manpower($id);
		$result['res'] = $res;
		echo json_encode($result);
	}
	
	public function getsoftwareEstimate()
	{
		$id = $_GET['id'];
		$res = $this->userloginrfq_model->get_estimate_software($id);
		$result['res'] = $res;
		echo json_encode($result);
	}
	
	public function sendEstimate_mail()
	{
		$data = json_decode(file_get_contents("php://input"));
		$id = $data->id;
		$logid = $data->logid;
		
		
		$res = $this->userloginrfq_model->send_mail_estimate($data->id, $data->logid,$data->files);
		
		$getrole = $this->userloginrfq_model->get_role();
		
		$getuser = $this->userloginrfq_model->getuser($logid);
		
		$getmessage = $this->userloginrfq_model->get_message($id, $logid);
		//print_r($getmessage);
		
		for($i=0; $i<count($getrole); $i++)
		{
			$logo_URL = base_url().'assets/images/logo.gif';
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Edit/Approve Estimate';
			$site_base_url= $this->config->item('rfqUrl').'/#/estimate-sheet';
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
						 <p>Dear Sir,</p>
						 <p>Add Estimation is submitted by techniacl team successfully, Kindly Edit/Approve Estimate.</p>
						<p> Please <a href="{$site_base_url}" style="text-decoration:none"> click here to open the RFQMS </a>and respond<br /><br /></p>
						 <p> In case of any Query, Please contact to Administrator.</p>                                 
						 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

			$this->userloginrfq_model->save_mail_user($getrole[$i]->email, $getmessage[0]->projectname, '', $message, $id, $logid, 'add');
			//$to =$repInfo[0]['email'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: POLOSOFT RFQ Team <hr@polosoftech.com>' . "\r\n";
			$cc_header = "";	
			//$cc_mail[] = $data->cc[$i]->email;
			
			//$bcc_mail[] = $data->bcc[$i]->email;
			//$cc_header = implode(',',$cc_mail);
			//$bcc_header = implode(',',$bcc_mail);
			$headers .= 'Cc: '.'hr@polosoftech.com'. "\r\n";
			//$headers .= 'Bcc: '."shyam.mohapatra@polosoftech.com". "\r\n";				
			$headers .= 'Reply-To:No reply  <no-reply@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			if(mail($getrole[$i]->email, $subject, $message, $headers)){
				//$res="success";
			}
			else
			{
				//$res="error";
			}
		}		
		echo json_encode($res);
					
	}
	
	public function getrfqstatus()
	{
		$id = $_GET['id'];
		$res = $this->userloginrfq_model->getrfqEstimate($id);
		echo json_encode($res);
	}
	
	
	public function approveEstimate_mail()
	{
		$data = json_decode(file_get_contents("php://input"));
		$id = $data->id;
		$logid = $data->logid;
		$res = $this->userloginrfq_model->sendapprove_mail_estimate($id, $logid, $data->rate, $data->totalsum, $data->myfile);
		
		$getrole = $this->userloginrfq_model->get_approverole($data->created_id);
		
		$getuser = $this->userloginrfq_model->getuser($logid);
		
		$getmessage = $this->userloginrfq_model->get_message($id, $logid);
			
			$logo_URL = base_url().'assets/imgs/logo.gif';
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Add Financial Proposal';
			$site_base_url= $this->config->item('rfqUrl').'/#/financial-proposal-list';
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
						 <p>Estimate has been added to your request. Please add your Financial Proposal., Kindly Add Financial Proposal.</p>
						<p>Please <a href="{$site_base_url}" style="text-decoration:none"> click here to open the RFQMS </a>and respond<br /><br /></p>
						 <p> In case of any Query, Please contact to Administrator.</p>                                 
						 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;
		//echo $message; exit;
		$this->userloginrfq_model->save_mail_user($getrole[0]->email, $getmessage[0]->projectname, '', $message, $id, $logid, 'approve');
		$to =$getrole[0]->email;
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: POLOSOFT RFQ Team <hr@polosoftech.com>' . "\r\n";
		$cc_header = "";	
		//$cc_mail[] = $data->cc[$i]->email;
		
		//$bcc_mail[] = $data->bcc[$i]->email;
		//$cc_header = implode(',',$cc_mail);
		//$bcc_header = implode(',',$bcc_mail);
		$headers .= 'Cc: '."hr@polosoftech.com". "\r\n";
		//$headers .= 'Bcc: '."shyam.mohapatra@polosoftech.com". "\r\n";				
		$headers .= 'Reply-To:No reply  <no-reply@polosoftech.com>' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		mail($to, $subject, $message, $headers);

		echo json_encode("success");
	}
	
	/*05-10-2018  - New Changes*/
	public function getoutstationEstimate()
	{
		$id = $_GET['id'];
		$res = $this->userloginrfq_model->get_estimate_outstation($id);
		$result['res'] = $res;
		echo json_encode($result);
	}	
	
	public function getFinancial()
	{
		$data = json_decode(file_get_contents("php://input"));
		$result = $this->userloginrfq_model->getFinancial($data->id);
		$mydata['res'] = $result;
		$files = array();
		for($i=0; $i<count($result); $i++)
		{
			$files[$i] = unserialize($result[$i]->filename);
		}
		$mydata['files'] = $files;
		echo json_encode($mydata);
	}
	
	public function getcurrency()
	{
		$array = array();
		//$key = $_GET['key']; 
		//$data = file_get_contents("http://data.fixer.io/api/fluctuation?access_key=70f90a2d765c2f295b29a343f14301ac&base = INR&symbols = USD,AUD,CAD,PLN,MXN");
		$data = json_decode(file_get_contents("http://data.fixer.io/api/latest?access_key=70f90a2d765c2f295b29a343f14301ac"));
		if($data->rates->USD)
		{
			$array[] = array(
				"name" => "USD",
				"price" => $data->rates->USD
			);
		}
		if($data->rates->GBP)
		{
			$array[] = array(
				"name" => "GBP",
				"price" => $data->rates->GBP
			);
		}
		if($data->rates->AUD)
		{
			$array[] = array(
				"name" => "GBP",
				"price" => $data->rates->GBP
			);
		}
		if($data->rates->CAD)
		{
			$array[] = array(
				"name" => "CAD",
				"price" => $data->rates->CAD
			);
		}
		if($data->rates->EUR)
		{
			$array[] = array(
				"name" => "EUR",
				"price" => $data->rates->EUR
			);
		}
		echo json_encode($array);
	}
	
	public function FinancialProposal()
	{
		$data = json_decode(file_get_contents("php://input"));
		if($data->type == "1"){
			//$result = $this->userloginrfq_model->save_finance($data->domestic_points, $data->domestic_sellingpoint, "domestic", $data->user, $data->total, $data->estimate_id, $data->gid, $data->totalamt, $data->filename);
			$result = $this->userloginrfq_model->save_finance($data->domestic_points, $data->international, "domestic", $data->user, $data->total, $data->estimate_id, $data->gid, $data->totalamt, $data->filename);
		}
		else if($data->type == "2"){
			$result = $this->userloginrfq_model->save_finance($data->international, "", "international", $data->user, $data->total, $data->estimate_id, "", $data->totalamt, $data->filename);
		}
		else if($data->type == "3"){
			//$result = $this->userloginrfq_model->save_finance($data->domestic_points, $data->domestic_sellingpoint, "domestic tender", $data->user, $data->total, $data->estimate_id, $data->gid, $data->totalamt, $data->filename);
			$result = $this->userloginrfq_model->save_finance($data->domestic_points, $data->international, "domestic tender", $data->user, $data->total, $data->estimate_id, $data->gid, $data->totalamt, $data->filename);
		}
		else{
			$result = $this->userloginrfq_model->save_finance($data->international, "", "international tender", $data->user, $data->total, $data->estimate_id, "", $data->totalamt, $data->filename);
		}
		echo json_encode($res['res'] = true);
	}
	
	
	
	public function FinancialProposalsubmit()
	{
		$data = json_decode(file_get_contents("php://input"));
		if($data->type == "1"){
			//$result = $this->userloginrfq_model->save_finance($data->domestic_points, $data->domestic_sellingpoint, "domestic", $data->user, $data->total, $data->estimate_id, $data->gid, $data->totalamt, $data->filename);
			$result = $this->userloginrfq_model->save_finance($data->domestic_points, $data->international, "domestic", $data->user, $data->total, $data->estimate_id, $data->gid, $data->totalamt, $data->filename);
		}
		else if($data->type == "2"){
			$result = $this->userloginrfq_model->save_finance($data->international, "", "international", $data->user, $data->total, $data->estimate_id, "", $data->totalamt, $data->filename);
		}
		else if($data->type == "3"){
			//$result = $this->userloginrfq_model->save_finance($data->domestic_points, $data->domestic_sellingpoint, "domestic tender", $data->user, $data->total, $data->estimate_id, $data->gid, $data->totalamt, $data->filename);
			$result = $this->userloginrfq_model->save_finance($data->domestic_points, $data->international, "domestic tender", $data->user, $data->total, $data->estimate_id, $data->gid, $data->totalamt, $data->filename);
		}
		else{
			$result = $this->userloginrfq_model->save_finance($data->international, "", "international tender", $data->user, $data->total, $data->estimate_id, "", $data->totalamt, $data->filename);
		}


		$res = $this->userloginrfq_model->sendfinance_mail_estimate($data->estimate_id, $data->user, $data->totalamt);
		
		$getrole = $this->userloginrfq_model->get_management_record();
		
		$getuser = $this->userloginrfq_model->getuser($data->user);
		
		$getmessage = $this->userloginrfq_model->get_message($data->estimate_id, $data->user);
		
		for($i=0; $i<count($getrole);$i++){
			
			$logo_URL = base_url().'assets/imgs/logo.gif';
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			
			$subject = 'Approve Financial Proposal';
			$site_base_url= $this->config->item('rfqUrl').'/#/approve-estimation';
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
						 <p>Dear Sir,</p>
						 <p>A Financial Proposal has been submitted for your approval.</p>
						<p>Please <a href="{$site_base_url}" style="text-decoration:none"> click here to open the RFQMS </a>and respond<br /><br /></p>                                
						 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

			
			//echo $message; exit;
			//$this->userloginrfq_model->save_mail_user($getrole[$i]->email, $getmessage[0]->subject, $getmessage[0]->expected_date, $message, $id, $logid, 'approve');
			$to =$getrole[$i]->email;
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: POLOSOFT RFQ Team <hr@polosoftech.com>' . "\r\n";
			$cc_header = "";	
			//$cc_mail[] = $data->cc[$i]->email;
			
			//$bcc_mail[] = $data->bcc[$i]->email;
			//$cc_header = implode(',',$cc_mail);
			//$bcc_header = implode(',',$bcc_mail);
			$headers .= 'Cc: '."hr@polosoftech.com". "\r\n";				
			$headers .= 'Reply-To:No reply  <no-reply@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $message, $headers);

		}
		echo json_encode($res['res'] = true);
	}
	
	
	public function getallApprove_proposal()
	{
		$data = $this->userloginrfq_model->get_rfq_estimate_approve_all();
		$mydata['res'] = $data;
		for($i=0; $i<count($data); $i++)
		{
			$files[$i] = unserialize($data[$i]->filename);
		}
		$mydata['files'] = $files;
		echo json_encode($mydata);
	}
	
	
	public function approve_fp_submit()
	{
		$data = json_decode(file_get_contents("php://input"));
		if($data->type == "1"){
			$result = $this->userloginrfq_model->save_finance($data->domestic_points, $data->domestic_sellingpoint, "domestic", $data->user, $data->total, $data->estimate_id, $data->gid, $data->totalamt, $data->filename);
		}else{
			$result = $this->userloginrfq_model->save_finance($data->international, "", "international", $data->user, $data->total, $data->estimate_id, "", $data->totalamt, $data->filename);
		}


		$res = $this->userloginrfq_model->approvefinance_mail_estimate($data->estimate_id, $data->user, $data->totalamt);
		
		$getrole = $this->userloginrfq_model->get_record_estimate_sale($data->created_by);
		
		$getuser = $this->userloginrfq_model->getuser($data->user);
		
		$getmessage = $this->userloginrfq_model->get_message($data->estimate_id, $data->user);
		
		for($i=0; $i<count($getrole);$i++){
			
			$logo_URL = base_url().'assets/imgs/logo.gif';
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Upload Final Proposal';
			$site_base_url= $this->config->item('rfqUrl').'/#/proposals';
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
						 <p>Your Financial Proposal is approved.</p>
						<p>Please <a href="{$site_base_url}" style="text-decoration:none"> click here to open the RFQMS </a>and respond<br /><br /></p>
						 <p> In case of any Query, Please contact to Administrator.</p>                                 
						 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

//echo $message; exit;
				//$this->userloginrfq_model->save_mail_user($getrole[$i]->email, $getmessage[0]->subject, $getmessage[0]->expected_date, $message, $id, $logid, 'approve');
				$to =$getrole[$i]->email;
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: POLOSOFT RFQ Team <hr@polosoftech.com>' . "\r\n";
				$cc_header = "";	
				//$cc_mail[] = $data->cc[$i]->email;
				
				//$bcc_mail[] = $data->bcc[$i]->email;
				//$cc_header = implode(',',$cc_mail);
				//$bcc_header = implode(',',$bcc_mail);
				$headers .= 'Cc: '."hr@polosoftech.com". "\r\n";
				//$headers .= 'Bcc: '."shyam.mohapatra@polosoftech.com". "\r\n";				
				$headers .= 'Reply-To:No reply  <no-reply@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				mail($to, $subject, $message, $headers);

		}
		echo json_encode($res['res'] = true);
	}
	
	public function getFPrecord()
	{
		$array = array();
		$array1 = array();
		$id = $_GET['id'];
		$type = 1;
		$result = $this->userloginrfq_model->get_FPrecords($id);
		if(count($result) > 0)
		{
			if($result[0]->project_type == 1 || $result[0]->project_type == 3)
			{		
				for($i=0; $i<count($result); $i++)
				{
					if($result[$i]->name == "GST")
					{
						array_push($array1, $result[$i]);
					}
					else
					{
						array_push($array, $result[$i]);
					}
				}	
			}
			else
			{
				$type = $result[0]->project_type;
				for($i=0; $i<count($result); $i++)
				{
					array_push($array, $result[$i]);
				}
			}
		
			$data = array(
				'error' => false,
				'type' => $type,	
				'res' => $array1,
				'res1' => $array
			);			
		}
		else
		{
			$data = array(
				'error' => true
			);
		}
		echo json_encode($data);
	}
	
	public function getCurrency_record()
	{
		$data = $this->userloginrfq_model->get_cuurencies();
		echo json_encode($data);
	}
	
	/*Cron Function To Email Estimate if Not Approved*/
	
	public function cron_mail_approve_estimate()
	{
		//$exp_date = date('Y-m-d', strtotime('+1 day'));
		$exp_date = date('Y-m-d', strtotime('+1 day'));
		$data = $this->userloginrfq_model->get_rfqestimate_record("2018-10-13");
		$array_to = array(); $array_cc = array(); $array_bcc = array();
		for($i=0; $i<count($data);$i++){
			$tomail = unserialize($data[$i]->mail_to);
			$ccmail = unserialize($data[$i]->mail_cc);
			$bccmail = unserialize($data[$i]->mail_bcc);
			for($j=0; $j<count($tomail); $j++)
			{
				array_push($array_to, $tomail[$j]->email);
			}
			for($h=0; $h<count($ccmail); $h++)
			{
				array_push($array_cc, $ccmail[$h]->email);
			}
			for($j=0; $j<count($bccmail); $j++)
			{
				array_push($array_bcc, $bccmail[$j]->email);
			}
			$mailto = implode(",",$array_to);
			$mailcc = implode(",",$array_cc);
			$mailbcc = implode(",",$array_bcc);
			$logo_URL = base_url('assets/imsges/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = $data[$i]->subject;
			$site_base_url= $this->config->item('rfqUrl');
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
						 {$data[$i]->text_instruction}
						 <p>Add Estimation is submitted by {$data[$i]->full_name}</p>
						<p style="color:#f00; font-style: italic; padding-bottom: 30px;">Note: <strong> Your Add estimate expected date is {$data[$i]->expected_date}</strong>. Please submit estimate before expected date.</p>
						<p>You have received a Request for Estimate. Please click here to open the <a href="{$site_base_url}" style="text-decoration:none">RFQMS </a>and respond<br /><br /></p>
						 <p> In case of any Query, Please contact to HR Department.</p>                                 
						 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;
//echo $message; exit;
				//$this->userloginrfq_model->save_mail_user($data[$i]->email, $data[0]->subject, $data[$i]->expected_date, $message, $id, $logid, 'approve');
				//$to =$getrole[$i]->email;
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$getuser[$i]->full_name.'. <'.$getuser[$i]->email.'>' . "\r\n";
				$cc_header = "";	
				//$cc_mail[] = $data->cc[$i]->email;
				
				//$bcc_mail[] = $data->bcc[$i]->email;
				//$cc_header = implode(',',$cc_mail);
				//$bcc_header = implode(',',$bcc_mail);
				$headers .= 'Cc: '.$mailcc. "\r\n";
				$headers .= 'Bcc: '.$mailbcc. "\r\n";				
				$headers .= 'Reply-To:No reply  <no-reply@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				mail($mailto, $subject, $message, $headers);
				
				unset($array_to); 
				unset($array_cc);
				unset($array_bcc);

		}
		print_r($data);
	}
	
	public function getApprove_proposal()
	{
		$data = $this->userloginrfq_model->get_rfq_estimate_approve();
		$mydata['res'] = $data;
		for($i=0; $i<count($data); $i++)
		{
			$files[$i] = unserialize($data[$i]->filename);
		}
		$mydata['files'] = $files;
		echo json_encode($mydata);
	}
	
	public function upload_proposal()
	{
		$data = json_decode(file_get_contents("php://input"));
		$res = $this->userloginrfq_model->save_estimate_proposal($data->name, $data->file1, $data->user, $data->est_id);
		
		echo json_encode($res);	
	}
	
	public function get_upload_proposal($id)
	{
		$data = $this->userloginrfq_model->get_proposal($id);
		echo json_encode($data);
	}
	
	public function get_estimate_sheet_all_client()
	{
		$data = $this->userloginrfq_model->get_projectrecords();
		$array ['res'] = $data;
		echo json_encode($array);
	}
	
	public function search_request()
	{
		$array = array();
		$data = json_decode(file_get_contents("php://input"));
		$record = $this->userloginrfq_model->search_keyword($data->keys, $data->name, $data->project);
		/*for($i=0; $i<count($record); $i++)
		{
			$sum = $record[$i]->sumhrcost + $record[$i]->sumoutscost + $record[$i]->sumsoftwrcost + $record[$i]->sumhardwrcost + $record[$i]->sumeffort;
			
			//array_push($array, $sum);
			array_push($array, $record[$i]);
		}
		
		$myrecord[0] = $record;
		$myrecord[1] = $array;*/
		
		for($i=0; $i<count($record); $i++)
		{
			if($record[$i]->filename != "")
			{
				$files[$i] = unserialize($record[$i]->filename);	
			}else{
				$files[$i] = "";
			}
			
		}
		$array['record'] = $record;
		$array['file'] = $files;
		
		echo json_encode($array);
	}
	
	public function get_estimate_sheet()
	{
		$data = $this->userloginrfq_model->get_projectrecords();
		$array ['res'] = $data;
		echo json_encode($array);
	}
	
	public function get_estimate_client()
	{
		$data = $this->userloginrfq_model->get_clientrecords();
		$array ['res'] = $data;
		echo json_encode($array);
	}
	
	public function clientlist()
	{
		$data = $this->userloginrfq_model->get_clients();
		echo json_encode($data);
	}
	
	public function get_state()
	{
		$data = $this->userloginrfq_model->get_state();
		echo json_encode($data);
	}
	
	public function add_client()
	{
		$data = json_decode(file_get_contents("php://input"));
		
		$rec = $this->userloginrfq_model->save_client($data->cname, $data->ctype, $data->email, $data->fax, $data->mobile, $data->phone, $data->contactperson, $data->description, $data->address, $data->country, $data->mtype, $data->gstno, $data->scode, $data->state, $data->stype);
		
		if($rec > 0)
		{
			echo true;
		}
		else
		{
			echo false;
		}
	}
	
	public function get_chart()
	{
		$data = $this->userloginrfq_model->get_chart_record();
		echo json_encode($data);
	}
	
	public function get_chart_client()
	{
		$data = $this->userloginrfq_model->get_chartrecord_client();
		echo json_encode($data);
	}
	
	public function get_country()
	{
		$data = $this->userloginrfq_model->get_country();
		echo json_encode($data);
	}
	
	public function get_rfq_proposal()
	{
		//$id = $_GET['id'];
		//$data = $this->userloginrfq_model->get_proposal_upload($id);
		
		$data = json_decode(file_get_contents('php://input'));
		$array = array();
		$result = $this->userloginrfq_model->get_proposal_upload($data->id, $data->user_id);
		if(!empty($result))
		{
			for($i=0; $i < count($result); $i++)
			{
				$datas[$i] = unserialize($result[$i]->project_id);
				$files[$i] = unserialize($result[$i]->filename);
			}
			$array = array(
				'res' => $result,
				'data' => $datas,
				'files' => $files,
				'count' => count($result)
			);	
		}
		else{
			$array = array(
				'res' => 'error',
				'data' => 0,
				'files' => 0,
				'count' => count($result)
			);
		}
		echo json_encode($array);
	}
	
	public function getEstimate_records()
	{
		$request = json_decode(file_get_contents("php://input"));
		$array = array();
		$data = $this->userloginrfq_model->getEstimate_records($request->id);
		$files = array();
		for($i=0; $i<count($data);$i++)
		{
			$files[$i] = unserialize($data[$i]->filename);
		}
		$array['data'] = $data;
		$array['files'] = $files;
		echo json_encode($array);
	}
	
	// 10.01.2019 Technical Users List
	public function rfqusers_technical()
	{
		$array = array();
		$result = $this->userloginrfq_model->rfqusers_technical();
		print_r(json_encode($result));
	}
	
	public function get_all_upload_request()
	{
		$data = $this->userloginrfq_model->get_all_uploaded_request();
		echo json_encode($data);
	}
	
	public function upload_request()
	{
		//print_r($_FILES);
		$array = array();
		if($_FILES['file']['name'] != ''){
			$fileName = 'Lunch_report_'.time('His').''.basename( $_FILES['file']['name']);
			$target_path = './upload/'. $fileName;
			if ( move_uploaded_file($_FILES['file']['tmp_name'], $target_path)){
				//
				$this->load->library('PHPExcel'); 
				$inputFileName = $target_path;

				$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					//$worksheetTitle = $worksheet->getTitle();
					$sheetData = $worksheet->toArray();					
					$data = $this->userloginrfq_model->insert_lunch_request($sheetData);
				}	
			} 
		}
		$response = array( 'status' => 1);
		echo json_encode($response);
	}
	
	public function get_estimate_records()
	{
		$request = json_decode(file_get_contents("php://input"));
		$array = array();
		$data = $this->userloginrfq_model->get_records_estimate($request->id);
		$files = array();
		for($i=0; $i<count($data);$i++)
		{
			$files[$i] = unserialize($data[$i]->filename);
		}
		$array['data'] = $data;
		$array['files'] = $files;
		echo json_encode($array);
	}
	
	public function save_single_request()
	{
		$request = json_decode(file_get_contents("php://input"));
		$file = array();
		/** Launch Request Form**/
		$result = array();
		$res = array(); $log_id = "";
		for($i=0; $i< count($request->to);$i++)
		{
			$log_id = $request->to[$i]->login_id;
		}

		$records = $this->userloginrfq_model->get_records_name($log_id);
		if(!empty($request) && !empty($records))
		{
			$res = $this->userloginrfq_model->save_records($request->keywords, $request->projectname, $request->filename, $request->attachfile, $request->textediter, $request->launch_submit_by, $request->type, $request->country, $request->client, $request->currency, $request->project_name, $request->launch_date);
			for($i=0;$i<count($request->projectname);$i++)
			{
				$file[$i] = $request->projectname[$i]->itemName;  
			}
			$myfilename = implode(',', $file);

		}
		
		$this->userloginrfq_model->sendlre_mail($myfilename, $request->expected_date, '', $request->to, $res, $request->launch_submit_by, $request->launch_date);
		
		
		/* Estimate record save */
		
		for($i=0; $i<count($request->form1);$i++)
		{
			$result = $this->userloginrfq_model->save_esimated_records($res, $request->form1[$i]->effort, $request->form1[$i]->hour, $request->form1[$i]->rate, $request->form1[$i]->totalrate, $request->estimate_submit_by, 'effort', $request->form1[$i]->id, $request->form1[$i]->skill, $request->form1[$i]->no_units, $request->form1[$i]->hours_units, "", "", $request->launch_date, $request->form1[$i]->units_type);	
		}	
		for($i=0; $i<count($request->form2);$i++)
		{
			$result = $this->userloginrfq_model->save_esimated_records($res, $request->form2[$i]->hardware, $request->form2[$i]->counts, $request->form2[$i]->unitcost, $request->form2[$i]->totalcost, $request->estimate_submit_by, 'hardware', $request->form2[$i]->id, "", "", "", "", "", $request->launch_date, "");	
		}
		for($i=0; $i<count($request->form3);$i++)
		{
			$result = $this->userloginrfq_model->save_esimated_records($res, $request->form3[$i]->name, $request->form3[$i]->counts, $request->form3[$i]->allowance, $request->form3[$i]->totalcost, $request->estimate_submit_by, 'manpower', $request->form3[$i]->id, "", "", "", $request->duration_type, $request->form3[$i]->duration, $request->launch_date, "");	
		}
		for($i=0; $i<count($request->form4);$i++)
		{
			$result = $this->userloginrfq_model->save_esimated_records($res, $request->form4[$i]->softwarename, $request->form4[$i]->lincenses, $request->form4[$i]->unitcost, $request->form4[$i]->totalcost, $request->estimate_submit_by, 'software', $request->form4[$i]->id, "", "", "", "", "", $request->launch_date, "");	
		}
		for($i=0; $i<count($request->form5);$i++)
		{
			$result = $this->userloginrfq_model->save_esimated_records($res, $request->form5[$i]->name, $request->form5[$i]->counts, $request->form5[$i]->allowance, $request->form5[$i]->totalcost, $request->estimate_submit_by, 'outstation', $request->form5[$i]->id, "", "", "", "", "", $request->launch_date, "");	
		}
		
		/** Financial Proposal Submit **/
		
		if($request->type == "1"){
			
			$result_fp = $this->userloginrfq_model->save_finance_proposal($request->form7, '', "domestic", $request->launch_submit_by, $request->estimate, $res, $request->total_price, $request->estimate_submit_by, $request->finance_approve_by, $request->finance_file, $request->estimate_submit_by, $request->estimate_approve_by, $request->launch_date, $request->finance_proposal_file);
		}
		else if($request->type == "2"){
			$result_fp = $this->userloginrfq_model->save_finance_proposal($request->form8, "", "international", $request->launch_submit_by, $request->estimate, $res, $request->total_price, $request->estimate_submit_by, $request->finance_approve_by, $request->finance_file, $request->estimate_submit_by, $request->estimate_approve_by, $request->launch_date, $request->finance_proposal_file);
		}
		else if($request->type == "3"){
			$result_fp = $this->userloginrfq_model->save_finance_proposal($request->form7, $request->form6, "domestic tender", $request->launch_submit_by, $request->estimate, $res, $request->total_price, $request->estimate_submit_by, $request->finance_approve_by,$request->finance_file, $request->estimate_submit_by, $request->estimate_approve_by, $request->launch_date, $request->finance_proposal_file);
		}
		else{
			$result_fp = $this->userloginrfq_model->save_finance_proposal($request->form8, "", "international tender", $request->launch_submit_by, $request->estimate, $res, $request->total_price, $request->estimate_submit_by, $request->finance_approve_by, $request->finance_file, $request->estimate_submit_by, $request->estimate_approve_by, $request->launch_date, $request->finance_proposal_file);
		}
		
		$res = $this->userloginrfq_model->save_estimate_proposal($request->client, $request->upload1, $request->launch_submit_by, $res, $request->launch_date);
		
		echo true;
	}
	
	public function get_employee_estimate()
	{
		$request = json_decode(file_get_contents("php://input"));
		$data = $this->userloginrfq_model->get_estimated_employee($request->role);
		echo json_encode($data);
	}
	
	public function get_client_record($id)
	{
		$data = $this->userloginrfq_model->get_records_client($id);
		echo json_encode($data);
	}
	
	public function save_client_data()
	{
		$request = json_decode(file_get_contents("php://input"));
		//$data = $this->userloginrfq_model->save_records_client();
		
		$rec = $this->userloginrfq_model->edit_client($request->cname, $request->ctype, $request->email, $request->fax, $request->mobile, $request->phone, $request->contactperson, $request->description, $request->address, $request->country, $request->mtype, $request->gstno, $request->scode, $request->state, $request->stype, $request->id);
		
		echo true;
	}
	
	public function view_estimate_details($id)
	{
		$launch_request = $this->userloginrfq_model->get_launch_request_details($id);
		$effort_estimate = $this->userloginrfq_model->get_estimate_effort($id);
		$hardware_estimate = $this->userloginrfq_model->get_estimate_hardware($id);
		$manpower = $this->userloginrfq_model->get_estimate_manpower($id);
		$software = $this->userloginrfq_model->get_estimate_software($id);
		$outstation = $this->userloginrfq_model->get_estimate_outstation($id);
		$financial = $this->userloginrfq_model->get_financial_proposaldetails($id);
		$upload = $this->userloginrfq_model->get_proposal($id);
		$launch = array();
		$request_type = 'Normal';
		for($i=0; $i<count($launch_request); $i++){
			$request_type = $launch_request[$i]->request_type;
			$launch[] = array(
				'id'=>$launch_request[$i]->id,
				'filename'=>unserialize($launch_request[$i]->filename),
				'projectname'=>$launch_request[$i]->projectname,
				'attach_filename'=>$launch_request[$i]->attach_filename,
				'keywords'=>$launch_request[$i]->keywords,
				'project_id'=>unserialize($launch_request[$i]->project_id),
				'project_name'=>$launch_request[$i]->project_name,
				'project_type'=>$launch_request[$i]->project_type,
				'text_instruction'=>$launch_request[$i]->text_instruction,
				'client_name'=>$launch_request[$i]->client_name,
				'territory'=>$launch_request[$i]->territory,
				'currency'=>$launch_request[$i]->currency,
				'add_estimation_submit'=>$launch_request[$i]->add_estimation_submit,
				'add_estimation_approved'=>$launch_request[$i]->add_estimation_approved,
				'prepare_financial_proposal'=>$launch_request[$i]->prepare_financial_proposal,
				'approve_financial_proposal'=>$launch_request[$i]->approve_financial_proposal,
				'uploaded_proposal'=>$launch_request[$i]->uploaded_proposal,
				'total_rate'=>$launch_request[$i]->total_rate,
				'total_estimate'=>$launch_request[$i]->total_estimate,
				'upload_request'=>$launch_request[$i]->upload_request,
				'estimate_file'=>$launch_request[$i]->estimate_file,
				'financial_file'=>$launch_request[$i]->financial_file,
				'created_by'=>$launch_request[$i]->created_by,
				'full_name'=>$launch_request[$i]->full_name,
				'expected_date'=>$launch_request[$i]->expected_date,
				'request_type'=>$launch_request[$i]->request_type,
				'vendor_name'=>$launch_request[$i]->vendor_name,
				'vendor_type'=>$launch_request[$i]->vendor_type,
				'created_at'=>$launch_request[$i]->created_at
			);
		}
		
		$vendor = array();
		if($request_type == 'Vendor'){
			$vendor = $this->userloginrfq_model->get_vendor_estimate($id);
		}
		$array['launch'] = $launch;
		$array['effort'] = $effort_estimate;
		$array['hardware'] = $hardware_estimate;
		$array['manpower'] = $manpower;
		$array['software'] = $software;
		$array['outstation'] = $outstation;
		$array['financial'] = $financial;
		$array['upload'] = $upload;
		$array['vendor'] = $vendor;
		echo json_encode($array);
	}
	
	public function get_pending_estimate($role, $id)
	{
		$record = $this->userloginrfq_model->get_pending_estimate($role, $id);
		
		echo json_encode($record);
	}
	
	public function get_pending_estimate_record($role, $id)
	{
		$record = $this->userloginrfq_model->get_pending_estimated_record($role, $id);
		
		echo json_encode($record);
	}
	
	
	public function get_current_estimate_record()
	{
		$request = json_decode(file_get_contents("php://input"));
		$data['sum_record'] = $this->userloginrfq_model->get_current_estimated_record($request->role, $request->login_id);
		$data['record'] = $this->userloginrfq_model->get_current_estimate_list($request->role, $request->login_id);		
		echo json_encode($data);
	}
	
	
	public function get_project_record()
	{
		$data['record'] = $this->userloginrfq_model->get_project_list();
		echo json_encode($data);
	}
	
	public function add_project_record()
	{
		$data = json_decode(file_get_contents("php://input"));
		
		$record = $this->userloginrfq_model->add_project_record($data->name, $data->login_id);
		echo json_encode($record);
	}
	
	public function edit_nop_project_record($id)
	{
		$record = $this->userloginrfq_model->get_nop_records($id);
		echo json_encode($record);
	}
	
	public function update_nop_records()
	{
		$data = json_decode(file_get_contents("php://input"));
		$record = $this->userloginrfq_model->update_nop_records($data->id, $data->name);
		echo json_encode($record);
	}
	
	/* FP Effort table records */
	public function get_fp_records($id)
	{
		$data['res'] = $this->userloginrfq_model->get_effort_records($id);
		echo json_encode($data);
	}
	

}
