<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>  

<!--********** Tooltip ****************-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/frontend/timesheet/jquery-1.9.1.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/frontend/timesheet/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/dist/frontend/timesheet/jquery-ui.css">
<!--********** /Tooltip ****************-->
<script language="javascript" type="text/javascript">
var j191 = jQuery.noConflict();
  j191(function () {
      j191(document).tooltip({
          content: function () {
              return $(this).prop('title');
          }
      });
  });
</script>

<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content ">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content" style="min-height:210px;">
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
					<legend class="pkheader" style="margin-top:20px;">Color Info</legend>
					<div class="well"> 
						<ul style="list-style-type: none; margin-left: -40px;">
							<li><img src="<?= base_url('assets/images/attendence/color1.jpg')?>" alt="" align="left" /> &nbsp;Days Attended</li>
							<li><img src="<?= base_url('assets/images/attendence/color2.jpg')?>" alt="" align="left" /> &nbsp;Absent Days</li>
							<li><img src="<?= base_url('assets/images/attendence/color4.jpg')?>" alt="" align="left" /> &nbsp;Regularized Days</li> 
							<li><img src="<?= base_url('assets/images/attendence/color3.jpg')?>" alt="" align="left" /> &nbsp;Days on Leave</li>
							<li><img src="<?= base_url('assets/images/attendence/color21.png')?>" alt="" align="left" /> &nbsp;Days on Maternity Leave</li>
							<li><img src="<?= base_url('assets/images/attendence/color5.jpg')?>" alt="" align="left" /> &nbsp;First Half Present</li>
							<li><img src="<?= base_url('assets/images/attendence/color6.jpg')?>" alt="" align="left" /> &nbsp;Second Half Present</li>
							<li><img src="<?= base_url('assets/images/attendence/color7.jpg')?>" alt="" align="left" /> &nbsp;First Half Absent</li>
							<li><img src="<?= base_url('assets/images/attendence/color8.jpg')?>" alt="" align="left" /> &nbsp;Second Half Absent</li>
							<li><img src="<?= base_url('assets/images/attendence/color9.jpg')?>" alt="" align="left" /> &nbsp;First Half Leave</li>
							<li><img src="<?= base_url('assets/images/attendence/color10.jpg')?>" alt="" align="left" /> &nbsp;Second Half Leave</li> 
							<li><img src="<?= base_url('assets/images/attendence/color11.jpg')?>" alt="" align="left" /> &nbsp;Holidays</li>
							<li><img src="<?= base_url('assets/images/attendence/color12.jpg')?>" alt="" align="left" /> &nbsp;Sorrow Feedback</li>
							<li><img src="<?= base_url('assets/images/attendence/color13.jpg')?>" alt="" align="left" /> &nbsp;Performer of the Day</li>
							<li><img src="<?= base_url('assets/images/attendence/color14.jpg')?>" alt="" align="left" /> &nbsp;Fullon attended Month</li>
						</ul>
					</div>
					
					
					<?php 
					 //Attendance Punch out
					//if($this->session->userdata('empCode') != 'administrator' && $this->session->userdata('showAttendanceBoxOutTime')=='YES'){
					if($this->session->userdata('empCode') != 'administrator' && $this->session->userdata('showAttendanceBoxOutTime')=='YES' && $this->session->userdata('remote_access')=='Y'){
						?>
					<script language="javascript" type="text/javascript">
					var site_url = '<?php echo base_url(); ?>';
					function punchAttendance(dis){
						$.ajax({
							type: "POST",
							url: site_url+'timesheet/punch_attendance_out',
							data: $("#feedbackForm").serialize(), // serializes the form's elements.
							success: function(data)
							{
								$('#myAttendance #punchAttendanceMSG').html('<h4>Attendance punched successfully</h4>');
								$('#myAttendance #punchoutSec').html('');
						   }
						});
					}
					</script>
					<legend class="pkheader">Attendance Punch Out</legend>
					<div class="well" id="myAttendance"> 
						<div class="successMsg" id="punchAttendanceMSG" style="text-align:center;"></div>
						<div id="punchoutSec">
							<h4 class="modal-title">Do you want to punch out time for the day?</h4>
							<div class="row">
								<button type="button" id="glowingtabs3" class="btn btn-info pull-right btn-sm" name="atnBTN" onclick="punchAttendance(this)" >Yes</button>
							</div>
						</div>
					</div>
					<?php } ?>
					
				</div>
				 
				<div class="col-md-9"> 
					<legend class="pkheader">Time Sheet Yearly View</legend>
					<div class="row well sm-over"> 
						<div class="col-md-1" style="padding-left: 0px;">
							<form id="viewAttendance" name="viewAttendance" method="POST" action="">
								<select id="dd_year" name="dd_year" class="form-control" onchange="document.viewAttendance.submit();" style="width:63px; padding: 0 4px;" >
									<?php
									$year = date("Y");
									if($this->input->post('dd_year') != ''){
										$year = $this->input->post('dd_year');
									}
									$yr=date("Y");
									for($j=$yr;$j>=2011;$j--){
									if ($j == $year){
									?>
									<option value="<?php echo $j;?>" selected><?php echo $j;?></option>
									<?php }else{?>
									<option value="<?php echo $j;?>"><?php echo $j;?></option>
									<?php }
									}?> 
								</select>
							</form>
						</div>
						<div id="floating" class="col-md-11" style="padding-left: 0px; padding-right:0px;z-index: 99;">
							<table class="table table-bordered table-fixed months">
								<thead> 
									<tr> 
										<?php echo $monthHeader;?>
									</tr>
								</thead>
							</table>
						</div>  
						<table class="table table-bordered table-striped table-fixed"> 
							<tbody class="timesheet">
								<?php 
									for($d=0; $d<37; $d++)
									{  
										echo '<tr>';
										echo $calRow[$d];
										echo '</tr>'; 
									} 
								?>
							</tbody>
						</table>
					</div>
				</div>
				<!--<div class="col-md-3"> 
					<h4>Color Info :</h4>
					<ul>
						<li><img src="<?= base_url('assets/images/attendence/color1.jpg')?>" alt="" align="left" /> Days Attended</li>
						<li><img src="<?= base_url('assets/images/attendence/color2.jpg')?>" alt="" align="left" /> Absent Days</li>
						<li><img src="<?= base_url('assets/images/attendence/color4.jpg')?>" alt="" align="left" /> Regularized Days</li> 
						<li><img src="<?= base_url('assets/images/attendence/color3.jpg')?>" alt="" align="left" /> Days on Leave</li>
						<li><img src="<?= base_url('assets/images/attendence/color5.jpg')?>" alt="" align="left" /> First Half Present</li>
						<li><img src="<?= base_url('assets/images/attendence/color6.jpg')?>" alt="" align="left" /> Second Half Present</li>
						<li><img src="<?= base_url('assets/images/attendence/color7.jpg')?>" alt="" align="left" /> First Half Absent</li>
						<li><img src="<?= base_url('assets/images/attendence/color8.jpg')?>" alt="" align="left" /> Second Half Absent</li>
						<li><img src="<?= base_url('assets/images/attendence/color9.jpg')?>" alt="" align="left" /> First Half Leave</li>
						<li><img src="<?= base_url('assets/images/attendence/color10.jpg')?>" alt="" align="left" /> Second Half Leave</li> 
						<li><img src="<?= base_url('assets/images/attendence/color11.jpg')?>" alt="" align="left" /> Holidays</li>
						<li><img src="<?= base_url('assets/images/attendence/color12.jpg')?>" alt="" align="left" /> Sorrow Feedback</li>
						<li><img src="<?= base_url('assets/images/attendence/color13.jpg')?>" alt="" align="left" /> Performer of the Day</li>
						<li><img src="<?= base_url('assets/images/attendence/color14.jpg')?>" alt="" align="left" /> Fullon attended Month</li>
					</ul> 
				</div>--> 
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>