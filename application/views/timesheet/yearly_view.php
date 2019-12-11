<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h4 class="section-title">
				<span class="c-dark">Time Sheet</span>
			</h4>
		</div>
	</div>
</div>
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
					<div class="well">
						<h4>Color Info :</h4>
						<ul style="list-style-type: none; margin-left: -40px;">
							<li><img src="<?= base_url('assets/images/attendence/color1.jpg')?>" alt="" align="left" /> &nbsp;Days Attended</li>
							<li><img src="<?= base_url('assets/images/attendence/color2.jpg')?>" alt="" align="left" /> &nbsp;Absent Days</li>
							<li><img src="<?= base_url('assets/images/attendence/color4.jpg')?>" alt="" align="left" /> &nbsp;Regularized Days</li> 
							<li><img src="<?= base_url('assets/images/attendence/color3.jpg')?>" alt="" align="left" /> &nbsp;Days on Leave</li>
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
				<div class="col-md-9"> 
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th colspan="13" > 
									<form id="viewAttendance" name="viewAttendance" method="POST" action="">
										<select id="dd_year" name="dd_year" class="form-control pull-right" onchange="document.viewAttendance.submit();" style="width:75px;" >
											<?php
												$yr=date("Y");
												for ($j=$yr;$j>=2011;$j--)
												{
													if ($j == $year)
													{?>
													<option value="<?php echo($j)?>" selected><?php echo($j)?></option>
													<?php }else{?>
													<option value="<?php echo($j)?>"><?php echo($j)?></option>
											<?php }
											}?>
											</select>
									</form>
								</th>
							</tr>
							<tr>
								<th></th>
								<?php echo $monthHeader;?>
							</tr>
						</thead>
						<tbody>
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