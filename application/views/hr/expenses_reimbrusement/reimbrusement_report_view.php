<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<link rel="Stylesheet" href="<?php echo base_url(); ?>assets/dist/frontend/pqselect.min.css" />        

<script src="<?php echo base_url(); ?>assets/dist/frontend/pqselect.min.js"></script>

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
					<legend class="pkheader">Reimbursement Report</legend>
					<div class="row well"> 
						<form id="frmReport" name="frmReport" action="" method="POST" > 
							<div class="row">
								<div class="col-md-5">
									<input type="checkbox" id="chooseall" name="chooseall" value="" /><label for="chooseall" style="font-size: 11px;">&nbsp;&nbsp;Choose All</label>
								</div>
								<div class="col-md-2 pull-right">
									<input type="hidden" name="hdnMonth" id="hdnMonth" value="" />
									<select id="selMonth" name="selMonth" class="required form-control form-control" style="width:100px;"> 
										<option value="01" <?php if($this->input->post('selMonth') == '01') echo 'selected="selected"';?>>January</option>
										<option value="02" <?php if($this->input->post('selMonth') == '02') echo 'selected="selected"';?>>February</option>
										<option value="03" <?php if($this->input->post('selMonth') == '03') echo 'selected="selected"';?>>March</option>
										<option value="04" <?php if($this->input->post('selMonth') == '04') echo 'selected="selected"';?>>April</option>
										<option value="05" <?php if($this->input->post('selMonth') == '05') echo 'selected="selected"';?>>May</option>
										<option value="06" <?php if($this->input->post('selMonth') == '06') echo 'selected="selected"';?>>June</option>
										<option value="07" <?php if($this->input->post('selMonth') == '07') echo 'selected="selected"';?>>July</option>
										<option value="08" <?php if($this->input->post('selMonth') == '08') echo 'selected="selected"';?>>August</option>
										<option value="09" <?php if($this->input->post('selMonth') == '09') echo 'selected="selected"';?>>September</option>
										<option value="10" <?php if($this->input->post('selMonth') == '10') echo 'selected="selected"';?>>October</option>
										<option value="11" <?php if($this->input->post('selMonth') == '11') echo 'selected="selected"';?>>November</option>
										<option value="12" <?php if($this->input->post('selMonth') == '12') echo 'selected="selected"';?>>December</option>
									</select>
								</div>
								<div class="col-md-2 pull-right">
									<input type="hidden" name="hdnYear" id="hdnYear" value="" />
									<select id="selYear" name="selYear" class="required form-control form-control" style="width:100px; margin-left:10px;"> 
										<?php for($i=2014; $i<=(date('Y'));$i++){ ?>
										<option value="<?php echo $i;?>" <?php if($this->input->post('selYear') == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
										<?php } ?> 
									</select>
								</div>
							</div> 
							<div style="clear:both;"></div>
							<table width="100%" cellspacing="0" cellpadding="6" border="0" class="search_box"> 
								<tr>
									<td><label for="loginhandle">Emp. Code</label></td><td><input type="checkbox" class="chkColumns" id="loginhandle" name="loginhandle" value="" /></td>
									<td><label for="full_name">Name</label></td><td><input type="checkbox" class="chkColumns" class="chk" id="full_name" name="full_name" /></td>
									<td><label for="father_name">Father's Name</label></td><td><input type="checkbox" class="chkColumns" id="father_name" name="father_name" /></td>
									<td><label for="dept_name">Department</label></td><td><input type="checkbox" class="chkColumns" id="dept_name" name="dept_name" /></td>
									<td><label for="desg_name">Designation</label></td><td><input type="checkbox" class="chkColumns" id="desg_name" name="desg_name" /></td>
								</tr>
								<tr class="search_odd">
									<td><label for="doj">DOJ</label></td><td><input type="checkbox" class="chkColumns" id="doj" name="doj" /></td>
									<td><label for="mobile_official">Mobile(Official)</label></td><td><input type="checkbox" class="chkColumns" id="mobile_official" name="mobile_official" /></td>
									<td><label for="mobile_landline">Mobile(Landline)</label></td><td><input type="checkbox" class="chkColumns" id="mobile_landline" name="mobile_landline" /></td>
									<td><label for="fuel">Fuel</label></td><td><input type="checkbox" class="chkColumns" id="fuel" name="fuel" /></td>
									<td><label for="vehicle_maintenance">Vehicle Maintenance</label></td><td><input type="checkbox" class="chkColumns" id="vehicle_maintenance" name="vehicle_maintenance" /></td>
								</tr>
								<tr>
									<td><label for="entertainment">Entertainment</label></td><td><input type="checkbox" class="chkColumns" id="entertainment" name="entertainment" value="" /></td>
									<td><label for="books_periodical">Books & Periodical</label></td><td><input type="checkbox" class="chkColumns" class="chk" id="books_periodical" name="book_periodical" /></td>
									<td><label for="lta">LTA</label></td><td><input type="checkbox" class="chkColumns" id="lta" name="lta" /></td>
									<td><label for="mediclaim">Mediclaim</label></td><td><input type="checkbox" class="chkColumns" id="mediclaim" name="mediclaim" /></td>
									<td><label for="driver_salary">Driver's Salary</label></td><td><input type="checkbox" class="chkColumns" id="driver_salary" name="driver_salary" /></td>
								</tr>
								<tr class="search_odd">
									<td><label for="lunch">Lunch</label></td><td><input type="checkbox" class="chkColumns" id="lunch" name="lunch" /></td>
								</tr>
								<tr><td colspan="10">&nbsp;</td></tr>
							</table>
							<h4>Filter Data From Selected Columns</h4>
							<div class="multiSelectSearchHolder">
								<div id="dojBox" class="filterItem">
									<h5 class="reportpk">Date of Joining</h5> 
									<div class="form-row">
										<div class="form-group col-md-6" style="padding:2px;">
											<input type="text" id="dojFrom" name="dojFrom" value="" readonly placeholder="From" class="form-control" />
										</div>
										<div class="form-group col-md-6" style="padding:2px;">
											<input type="text" id="dojTo" name="dojTo" value="" readonly placeholder="To" class="form-control" />
										</div> 
									</div> 
								</div>
								<div id="dept_nameBox" class="filterItem">
									<h5 class="reportpk">Department</h5>                                  
									<div class="form-row">
										<div class="form-group col-md-6" style="padding:2px;">
											<input type="hidden" name="hdnDept" id="hdnDept" value="" />
											<select id="selDepartment" name="selDepartment[]" class="form-control selDepartment" multiple=multiple>
											<?php 
												$department_result = count($department);
												for($i=0; $i<$department_result; $i++)
												{?>
													<option value="<?php echo $department[$i]['dept_id'];?>"><?php echo $department[$i]['dept_name'];?></option>
												<?php }
											?>
											</select>
										</div>
									</div> 
								</div> 
								<div id="desg_nameBox" class="filterItem">
									<h5 class="reportpk">Designation</h5>
									<div class="form-row">
										<div class="form-group col-md-6" style="padding:2px;">                                         
											<select id="selDesignation" name="selDesignation[]"  class="form-control selDesignation" multiple=multiple>
											<?php 
												$designation_result = count($designation);
												for($i=0; $i<$designation_result; $i++)
												{?>
													<option value="<?php echo $designation[$i]['desg_id'];?>"><?php echo $designation[$i]['desg_name'];?></option>
												<?php }
											?>
											</select>                                        
										</div>
									</div> 
								</div>
								<input type="hidden" name="hdnDesg" id="hdnDesg" value="" /> 
							</div>
							<div class="clear"></div>
							<div class="row">
								<div class="col-md-12">
								   <center>
										<input type="submit" name="exportEmployee" value="Generate" class="btn btn-md btn-info"/>
								   <center>
								</div>
							</div> 
							<div class="clear"></div> 
						</form> 
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>
