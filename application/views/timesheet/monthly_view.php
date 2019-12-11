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
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-6">
					<legend class="pkheader">Time Sheet Monthly View</legend>
					<div class="well">
						<table class="table table-bordered table-striped">
							<thead class="timesheet">
								<tr style="background: #f5ffff; text-transform: uppercase; font-size:15px;">
								  <th colspan="7" >
									<center>
									    <span><?php echo date("F - Y"); ?></span>
								    </center>
								  </th>
								</tr>
								<?php echo $weekHeader; ?> 
							</thead>
							<tbody class="timesheet">
								<?php echo $monthBody; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-3">
					<legend class="pkheader">Color Info</legend>
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
				</div> 
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>