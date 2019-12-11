<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_id = "";
$get_id_data = "";
if (isset($_GET['id']))
{
	$get_id = "?id=".$_GET['id'];
	$get_id_data = $_GET['id'];
}
?>
<div class="section main-section">
    <div class="container">
        <div class="form-content page-content">
            <div class="mt mb">
                <div class="col-md-3 center-xs">
                    <div class="form-content page-content">
                        <?php $this->load->view('hr/left_sidebar');?>
                    </div>
                </div>
                <div class="col-md-9 center-xs">
				<?php $this->load->view('hr/top_view');?>
				
					<?php  if(($get_id_data != "") && (($this->session->userdata('user_type') == 'ADMINISTRATOR') ||($this->session->userdata('user_type') == 'HRM'))){
						  if($empInfo[0]['user_status'] == 1){?>
							<a data-id="<? echo $get_id_data; ?>" id="markInactive"  data-toggle="modal" data-target="#myModal_markInactive" class="btn btn-danger pull-right btn-sm" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Mark Inactive</a> 
							<a data-id="<? echo $get_id_data; ?>" id="setLwd"  data-toggle="modal" data-target="#myModal_setLwd" class="btn btn-info pull-right btn-sm" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Set LWD</a> 
							<a data-id="<? echo $get_id_data; ?>" id="setDor"  data-toggle="modal" data-target="#myModal_setDor" class="btn btn-info pull-right btn-sm" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Set DOR</a> 
					<?php }   ?>
				
                    
                    <a href="<?= base_url('en/hr/profile_update_emp'.$get_id);?>" class="btn btn-info pull-right btn-sm" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a> 
					<?php }  ?>
                    <legend class="pkheader">General</legend>
                    <div class="row well">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-condensed">
                                <tbody>
                                    <tr>
                                        <td width="20%"><strong>Name:</strong></td>
                                        <td width="30%"><?php echo $empInfo[0]['full_name'];?></td>
                                        <td width="50%" colspan="2" rowspan="6" align="center">
                                            <?php 
												if($empInfo[0]['user_photo_name'] != '')
												{
													echo '<img src="'.base_url().'assets/upload/profile/'.$empInfo[0]['user_photo_name'].'" alt="" width="130" height="150" class="form_img" />';
												}
												else
												{
													echo '<img src="'.base_url().'assets/images/no-image.jpg" alt="" width="130" height="150" class="form_img" />';
												}
												if($empInfo[0]['user_sign_name'] != '')
												{
													echo '<br/><img src="'.base_url().'assets/upload/profile/'.$empInfo[0]['user_sign_name'].'" alt="" width="225" height="40" class="form_img" />';
												}
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Employee Code:</strong></td>
                                        <td><?php echo $empInfo[0]['loginhandle']?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>DOB:</strong></td>
                                        <td><?php echo date("d-m-Y", strtotime($empInfo[0]['dob']))?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Age:</strong></td>
                                        <td>
                                            <?php 
                                                echo $age .' Months,'.$year. ' Years';
                                                ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Highest Qualif:</strong></td>
                                        <td><?php echo $empInfo[0]['course_name'].' ('.$empInfo[0]['specialization_name'].')';?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Location of H. Qualif:</strong></td>
                                        <td><?php
                                            if($empInfo[0]['loc_highest_qual'] != '' && $empInfo[0]['loc_highest_qual'] != '0') echo $empInfo[0]['loc_highest_qual_name'];
                                            	?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Personal Email:</strong></td>
                                        <td><?php echo $empInfo[0]['per_email']?></td>
                                        <td><strong>Official Mobile No:</strong></td>
                                        <td><?php echo $empInfo[0]['official_mobile']?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Gender:</strong></td>
                                        <td><?php echo $gender?></td>
                                        <td><strong>Marital Status:</strong></td>
                                        <td><?php echo $mStatus?></td>
                                    </tr>
                                    <tr>
                                        <!--<td><strong>Em. Contact No:</strong></td>
                                        <td><?php
                                            if($empInfo[0]['phone1'] != ''){
                                            	echo $empInfo[0]['phone1'];
                                            }else{
                                            	echo '--N/A--';
                                            } ?>                                          
                                        </td>-->
                                        <td><strong>Blood Group:</strong></td>
                                        <td><?php echo $empInfo[0]['blood_group'];?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Show Mobile No:</strong></td>
                                        <td colspan="3"><?php
                                            echo ($empInfo[0]['isShowOnSearch'] == 'Y')?'Show':'Do not Show';
                                            echo '</strong> My Mobile No on Polosoft Staff Directory.';
                                            ?>                                          
                                        </td>
                                    </tr>
                                    <tr class="info">
                                        <td colspan="4" class="form_title">Address</td>
                                    </tr>
                                    <tr>
                                        <td valign="top"><strong>Permanent Address:</strong></td>
                                        <td><?php echo $empInfo[0]['address1'].'<br/>'.$empInfo[0]['city_district1'].'<br/>'.$empInfo[0]['state_name1'].' '.$empInfo[0]['pin_zip1'].'<br/>'.$empInfo[0]['country_name1'];?></td>
                                        <td valign="top"><strong>Correspondence Address:</strong></td>
                                        <td><?php echo $empInfo[0]['address2'].'<br/>'.$empInfo[0]['city_district2'].'<br/>'.$empInfo[0]['state_name2'].' '.$empInfo[0]['pin_zip2'].'<br/>'.$empInfo[0]['country_name2'];?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Permanent Landline No:</strong></td>
                                        <td> <?php echo $empInfo[0]['phone1']; ?></td>
                                        <td><strong> Correspondence Landline No:</strong></td>
                                        <td> <?php echo $empInfo[0]['phone2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Permanent Mobile No:</strong></td>
                                        <td><?php echo $empInfo[0]['mobile1']; ?></td>
                                        <td> <strong>Correspondence Mobile No:</strong></td>
                                        <td><strong> <?php echo $empInfo[0]['mobile']; ?></strong></td>
                                    </tr>
                                    <tr class="info">
                                        <td colspan="4" class="form_title">Identification Number(s)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Passport No:</strong></td>
                                        <td><?php echo $empInfo[0]['passport_no']?></td>
                                        <td><strong>PAN Card No:</strong></td>
                                        <td><?php echo $empInfo[0]['pan_card_no']?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Voter ID:</strong></td>
                                        <td><?php echo $empInfo[0]['voter_id']?></td>
                                        <td><strong>Driving License:</strong></td>
                                        <td><?php echo $empInfo[0]['drl_no']?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Aadhar Card No:</strong></td>
                                        <td><?php echo $empInfo[0]['adharcard_no']?></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
	
	
	<!-- Modal -->
	<div class="modal fade" id="myModal_markInactive" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Mark Inactive</h4>
				</div>
				<div class="modal-body">
					<div class="successMsg" id="messageSuccess"></div>
					<div class="srlst">
						<h5><strong>Emp Status Type</strong><span class="red">*</span> :</h5>
						<select id="emp_status_type" name="emp_status_type" class="required form_ui form-control" style="width:300px;">
							<option value="Normal" <?php if($empInfo[0]['emp_status_type'] == 'Normal') echo 'selected="selected"';?>>Normal</option> 
							<option value="Resigned" <?php if($empInfo[0]['emp_status_type'] == 'Resigned') echo 'selected="selected"';?>>Resigned</option>
							<option value="Retired" <?php if($empInfo[0]['emp_status_type'] == 'Retired') echo 'selected="selected"';?>>Retired</option>
							<option value="Terminated" <?php if($empInfo[0]['emp_status_type'] == 'Terminated') echo 'selected="selected"';?>>Terminated</option>
							<option value="Transferred" <?php if($empInfo[0]['emp_status_type'] == 'Transferred') echo 'selected="selected"';?>>Transferred</option>
							<option value="Death" <?php if($empInfo[0]['emp_status_type'] == 'Death') echo 'selected="selected"';?>>Death</option>
						</select>
						<h5><strong>FnF Status</strong><span class="red">*</span> :</h5>
						<select id="FnF_status" name="FnF_status" class="required form_ui form-control" style="width:300px;">
							<option value="Pending" <?php if($empInfo[0]['FnF_status'] == 'P') echo 'selected="selected"';?>>Pending</option>
							<option value="Cleared" <?php if($empInfo[0]['FnF_status'] == 'C') echo 'selected="selected"';?>>Cleared</option>
						</select>
						<h5><strong>Remark of HR</strong><span class="red">*</span> :</h5>
						<textarea id="HR_remark" name="HR_remark"  class="required form_ui form-control" style=""><?php echo $empInfo[0]['HR_remark']?></textarea>
						<input type="hidden" id="login_id" name="login_id" value="<?php echo $get_id_data; ?>">
					</div>
					<br/>
					<div><span class="red">*</span>  Marked fields are mandatory</div>
				</div>
				<div class="modal-footer srlst">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="submit" name="markInactiveBtn" id="markInactiveBtn" value="markInactiveBtn" class="btn btn-primary" >Inactive</button>
				</div>
			</div>
		</div>
	</div>
<script>
$(document).on('click','#markInactive',function(){
	$('#messageSuccess').html('');
});
	
	
$(document).on('click','#markInactiveBtn',function(){
	  var emp_status_type = $('#myModal_markInactive #emp_status_type').val();
	  var FnF_status = $('#myModal_markInactive #FnF_status').val();
	  var HR_remark = $('#myModal_markInactive #HR_remark').val();
	  var login_id = $('#myModal_markInactive #login_id').val();
	  $('#messageSuccess').html('');
	if(emp_status_type == ""){
		$('#emp_status_type').attr('style', 'border-color: #f00000;');
	}
	if(FnF_status == ""){
		$('#FnF_status').attr('style', 'border-color: #f00000;');
	}
	if(HR_remark == ""){
		$('#HR_remark').attr('style', 'border-color: #f00000;');
	}
	if(emp_status_type !="" && FnF_status !="" && HR_remark !=""){
		$.ajax({
			type:'POST',
			data:{emp_status_type:emp_status_type, FnF_status:FnF_status, HR_remark:HR_remark, login_id:login_id},
			url:site_url+'en/hr/general_readonly_mark_inactive?id='+login_id,
			success:function(data){
			   $('#messageSuccess').html('<h4>Submtted Successfully</h4>');
			   setTimeout(function(){ location.reload(); }, 3000);
			}
		});
	}
 });
</script>
	
	<!-- Modal -->
	<div class="modal fade" id="myModal_setLwd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Set LWD (Last Working Date)</h4>
				</div>
				<div class="modal-body">
					<div class="successMsg" id="messageSuccess1"></div>
					<div class="srlst">
						<h5><strong>LWD</strong><span class="red">*</span>:</h5>
						<input type="text" class="required form_ui form-control datepickerShow" id="lwd_date" name="lwd_date" value="<?php if($empInfo[0]['lwd_date'] != '0000-00-00') echo date('d-m-Y', strtotime($empInfo[0]['lwd_date']));?>" style="width:200px;" />
						<input type="hidden" id="set_lwd_login_id" name="login_id" value="<?php echo $get_id_data; ?>">
					</div>
					<br/>
					<div><span class="red">*</span>  Marked fields are mandatory</div>
				</div>
				<div class="modal-footer srlst">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="submit" name="setLwdBtn" id="setLwdBtn" value="setLwdBtn" class="btn btn-primary" >Set LWD</button>
				</div>
			</div>
		</div>
	</div>
<script>
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
});
$(document).on('click','#setLwd',function(){
	$('#messageSuccess1').html('');
});
	
	
$(document).on('click','#setLwdBtn',function(){
	  var lwd_date = $('#myModal_setLwd #lwd_date').val();
	  var login_id = $('#myModal_setLwd #set_lwd_login_id').val();
	  $('#messageSuccess1').html('');
	if(lwd_date == ""){
		$('#lwd_date').attr('style', 'border-color: #f00000;');
	}
	if(lwd_date !=""){
		$.ajax({
			type:'POST',
			data:{lwd_date:lwd_date, login_id:login_id},
			url:site_url+'en/hr/general_readonly_set_lwd?id='+login_id,
			success:function(data){
			   $('#messageSuccess1').html('<h4>Submtted Successfully</h4>');
			   setTimeout(function(){ location.reload(); }, 3000);
			}
		});
	}
 });
</script>
	
	
	<!-- Modal -->
	<div class="modal fade" id="myModal_setDor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Set DOR (date of Resign)</h4>
				</div>
				<div class="modal-body">
					<div class="successMsg" id="messageSuccess2"></div>
					<div class="srlst">
						<h5>DOR<span class ="red">*</span></h5>
						<input type="text" class="required form_ui form-control datepickerShow" id="resign_date" name="resign_date" value="<?php if($empInfo[0]['resign_date'] != '0000-00-00') echo date('d-m-Y', strtotime($empInfo[0]['resign_date']));?>" style="width:300px;" />
						<h5>Reason of Separation<span class ="red">*</span></h5>
						 <select id="selReaSep" name="selReaSep" class="required form_ui form-control" style="width:300px;">
							<option value="">Select</option>
							<?php
								$res = count($separation);
								for($i=0;$i<$res;$i++)
								{?> 
									<option value="<?php echo $separation[$i]['separation_id'];?>" <?php if($empInfo[0]['resign_reason'] == $separation[$i]['separation_id']) echo 'selected="selected"';?> ><?php echo $separation[$i]['separation_name'];?></option>
								<?php }
							?> 
						</select>
						<input type="hidden" id="set_dor_login_id" name="login_id" value="<?php echo $get_id_data; ?>">
					</div>
					<br/>
					<div><span class="red">*</span>  Marked fields are mandatory</div>
				</div>
				<div class="modal-footer srlst">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="submit" name="setDorBtn" id="setDorBtn" value="setDorBtn" class="btn btn-primary" >Set DOR</button>
				</div>
			</div>
		</div>
	</div>
	
	
<script>
$(document).on('click','#setDor',function(){
	$('#messageSuccess2').html('');
});
	
	
$(document).on('click','#setDorBtn',function(){
	var resign_date = $('#myModal_setDor #resign_date').val();
	var selReaSep = $('#myModal_setDor #selReaSep').val();
	var login_id = $('#myModal_setDor #set_dor_login_id').val();
	$('#messageSuccess1').html('');
	if(resign_date == ""){
		$('#resign_date').attr('style', 'border-color: #f00000;');
	}
	if(selReaSep == ""){
		$('#selReaSep').attr('style', 'border-color: #f00000;');
	}
	if(resign_date !="" && selReaSep !=""){
		$.ajax({
			type:'POST',
			data:{resign_date:resign_date, selReaSep:selReaSep, login_id:login_id},
			url:site_url+'en/hr/general_readonly_set_dor?id='+login_id,
			success:function(data){
			   $('#messageSuccess2').html('<h4>Submtted Successfully</h4>');
			   setTimeout(function(){ location.reload(); }, 3000);
			}
		});
	}
 });
</script>
</div>