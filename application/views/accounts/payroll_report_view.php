<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('accounts/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">Payroll Report(Salary Sheet)</legend> 
					<div class="row well"> 
					
						<form id="frmReport" name="frmReport" action="" method="POST" >
						
							<div class="pad_10">
								<div class="search_box">
									<div class="search_box_top">
										<div class="search_box_btm">
											<div class="pad_10">
												<div class="row">
												   <div class="col-md-5">
														<input type="checkbox" id="chooseall" name="chooseall" value="" /><label for="chooseall" style="font-size: 11px;">&nbsp;&nbsp;Choose All</label>
												   </div>
													<div class="col-md-2 pull-right">
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
														<select id="selYear" name="selYear" class="required form-control form-control" style="width:100px; margin-left:10px;"> 
															<?php for($i=2014; $i<=(date('Y'));$i++){ ?>
															<option value="<?php echo $i;?>" <?php if($this->input->post('selYear') == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
															<?php } ?> 
														</select>
													</div>
												</div>
												
												<table width="100%" cellspacing="0" cellpadding="6" border="0"> 
													<tr>
														<td><label for="loginhandle" title="Employee Code">Emp. Code</label></td><td><input type="checkbox" class="chkColumns" id="loginhandle" name="loginhandle" value="" /></td>
														<td><label for="full_name">Name</label></td><td><input type="checkbox" class="chkColumns" class="chk" id="full_name" name="full_name" /></td>
														<td><label for="basic">Father's Name</label></td><td><input type="checkbox" class="chkColumns" id="father_name" name="father_name" /></td>
														<td><label for="dept_name">Department</label></td><td><input type="checkbox" class="chkColumns" id="dept_name" name="dept_name" /></td>
														<td><label for="desg_name">Designation</label></td><td><input type="checkbox" class="chkColumns" id="desg_name" name="desg_name" /></td>
													</tr>
													<tr class="search_odd">
														<td><label for="doj" title="Date of Joining">DOJ</label></td><td><input type="checkbox" class="chkColumns" id="doj" name="doj" /></td>
														<td><label for="ctc">CTC</label></td><td><input type="checkbox" class="chkColumns" id="ctc" name="ctc" /></td>
														<td><label for="gross_salary">Gross Salary</label></td><td><input type="checkbox" class="chkColumns" id="gross_salary" name="gross_salary" /></td>
														<td><label for="basic">Basic</label></td><td><input type="checkbox" class="chkColumns" id="basic" name="basic" /></td>
														<td><label for="hra">HRA</label></td><td><input type="checkbox" class="chkColumns" id="hra" name="hra" /></td>

													</tr>
													<tr>
														<td><label for="conv">Conv.Allow.</label></td><td><input type="checkbox" class="chkColumns" id="conv" name="conv" /></td>
														<td><label for="medical" title="Medical Allowance">Medical Allow.</label></td><td><input type="checkbox" class="chkColumns" id="medical" name="medical" /></td>
														<td><label for="special_allownce" title="Special Allowance">Sp. Allowance</label></td><td><input type="checkbox" class="chkColumns" id="special_allownce" name="special_allownce" /></td>
														<td><label for="city_allownce">City Allowance</label></td><td><input type="checkbox" class="chkColumns" id="city_allownce" name="city_allownce" /></td>
														<td><label for="personal_allownce" title="Personal Allownce">Pers. Allowance</label></td><td><input type="checkbox" class="chkColumns" id="personal_allownce" name="personal_allownce" /></td>
													</tr>
													<tr class="search_odd">
														<td><label for="food_allowance">Food Allowance</label></td><td><input type="checkbox" class="chkColumns" id="food_allowance" name="food_allowance" /></td>
														<td><label for="referal_bonus">Buddy Referal</label></td><td><input type="checkbox" class="chkColumns" id="referal_bonus" name="referal_bonus" /></td>
														<td><label for="relocation_allowance">Relocation Allowance</label></td><td><input type="checkbox" class="chkColumns" id="relocation_allowance" name="relocation_allowance" /></td>
														<td><label for="arrear">Arrear</label></td><td><input type="checkbox" class="chkColumns" id="arrear" name="arrear" /></td>
														<td><label for="performance_incentive">Performance Incentive</label></td><td><input type="checkbox" class="chkColumns" id="performance_incentive" name="performance_incentive" /></td>
													</tr>
													<tr>
														<td><label for="attendance_incentive">Attendance Incentive</label></td><td><input type="checkbox" class="chkColumns" id="attendance_incentive" name="attendance_incentive" /></td>
														<td><label for="leave_encashment" title="Leave Encashment">Leave Encash.</label></td><td><input type="checkbox" class="chkColumns" id="leave_encashment" name="leave_encashment" /></td>
														<td><label for="pf_no">PF No.</label></td><td><input type="checkbox" class="chkColumns" id="pf_no" name="pf_no" /></td>
														<td><label for="pf">PF (12%)</label></td><td><input type="checkbox" class="chkColumns" id="pf" name="pf" /></td>
														<td><label for="pf">PF (13.61%)</label></td><td><input type="checkbox" class="chkColumns" id="employer_pf" name="employer_pf" /></td>
													</tr>
													<tr class="search_odd">
														<td><label for="mediclaim_no" title="Mediclaim Card No.">Medi/ESI. No.</label></td><td><input type="checkbox" class="chkColumns" id="mediclaim_no" name="mediclaim_no" /></td>
														<td><label for="esic">Esi(1.75%)</label></td><td><input type="checkbox" class="chkColumns" id="esi" name="esi" /></td>
														<td><label for="esic">Esi(4.75%)</label></td><td><input type="checkbox" class="chkColumns" id="employer_esi" name="employer_esi" /></td>
														<td><label for="gross">Gross</label></td><td><input type="checkbox" class="chkColumns" id="gross" name="gross" /></td>
														<td><label for="total_deduction">Total Deduction</label></td><td><input type="checkbox" class="chkColumns" id="total_deduction" name="total_deduction" /></td>
													</tr>
													<tr>
														<td><label for="net_salary">Net Salary</label></td><td><input type="checkbox" class="chkColumns" id="net_salary" name="net_salary" /></td>
														<td><label for="bank">Bank Name</label></td><td><input type="checkbox" class="chkColumns" id="bank" name="bank" /></td>
														<td><label for="bank_no">Bank Account No.</label></td><td><input type="checkbox" class="chkColumns" id="bank_no" name="bank_no" /></td>
														<td><label for="loan">Loan</label></td><td><input type="checkbox" class="chkColumns" id="loan" name="loan" /></td>
														<td><label for="advance">Advance</label></td><td><input type="checkbox" class="chkColumns" id="advance" name="advance" /></td>
													</tr> 
													<tr class="search_odd">
														<td><label for="recovery">Recovery</label></td><td><input type="checkbox" class="chkColumns" id="recovery" name="recovery" /></td>
														<td><label for="donation">Donation</label></td><td><input type="checkbox" class="chkColumns" id="donation" name="donation" /></td>
														<td><label for="pt">PT</label></td><td><input type="checkbox" class="chkColumns" id="pt" name="pt" /></td>
														<td><label for="other_deduction">Other Deduction</label></td><td><input type="checkbox" class="chkColumns" id="other_deduction" name="other_deduction" /></td>
														<td><label for="payment_mode">Payment Mode</label></td><td><input type="checkbox" class="chkColumns" id="payment_mode" name="payment_mode" /></td>
													</tr>
													<tr>
														<td><label for="income_tax">Income Tax</label></td><td><input type="checkbox" class="chkColumns" id="income_tax" name="income_tax" /></td>
														<td><label for="emp_type">Employee Type</label></td><td><input type="checkbox" class="chkColumns" id="emp_type" name="emp_type" /></td>
														<td><label for="user_status">Active/Inactive</label></td><td><input type="checkbox" class="chkColumns" id="user_status" name="user_status" /></td>
														<td><label for="reimbursement">Reimbursement</label></td><td><input type="checkbox" class="chkColumns" id="reimbursement" name="reimbursement" /></td>
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
																<select id="hdnDept" name="hdnDept[]" class="selectpicker" multiple>
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
												
													<div id="payment_modeBox" class="filterItem">
													<h5 class="reportpk">Mode of Payment</h5>                                    
														<div class="form-row">
															<div class="form-group col-md-6" style="padding:2px;">
																<select id="hdnPaymentMode" name="hdnPaymentMode[]" class="selectpicker" multiple>
																	<option value="Bank Transfer">Bank Transfer</option>
																	<option value="Cheque">Cheque</option>
																	<option value="Cash">Cash</option>
																</select>
															</div>
														</div>
													</div>
													<div id="emp_typeBox" class="filterItem">
														<h5 class="reportpk">Employee Type</h5>
														<div class="form-row">
															<div class="form-group col-md-6" style="padding:2px;">
																<select id="hdnEmployeeType" name="hdnEmployeeType[]" class="selectpicker" multiple>
																<option value="F">Full Time</option>
																<option value="C">Contratual</option>
																<option value="I">Intern</option>
																</select>
															</div>
														</div>
													</div> 
													<div id="desg_nameBox" class="filterItem">
														<h5 class="reportpk">Designation</h5>
														<div class="form-row">
															<div class="form-group col-md-6" style="padding:2px;"> 
															<select id="hdnDesg" name="hdnDesg[]" class="selectpicker" multiple>
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
													
													<div id="ctcBox" class="filterItem">
													<h5 class="reportpk">CTC</h5> 
														<div class="form-row">
															<div class="form-group col-md-6" style="padding:2px;">
																<input type="text" id="ctcFrom" name="ctcFrom" value="" placeholder="From" class="form-control" />
																
															</div>
															<div class="form-group col-md-6" style="padding:2px;">
																<input type="text" id="ctcTo" name="ctcTo" value="" placeholder="To" class="form-control" />
															</div>
														</div>
													</div>

													<div id="gross_salaryBox" class="filterItem">
													<h5 class="reportpk">Gross Salary</h5>
														<div class="form-row">
															<div class="form-group col-md-6" style="padding:2px;">
																<input type="text" id="gSalFrom" name="gSalFrom" value="" placeholder="From" class="form-control" />
															</div>
															<div class="form-group col-md-6" style="padding:2px;">
																<input type="text" id="gSalTo" name="gSalTo" value="" placeholder="To" class="form-control" />
															</div>
														</div>
													</div>                               
													<div id="basicBox" class="filterItem">
													<h5 class="reportpk">Bank</h5>
														<div class="form-row">
															<div class="form-group col-md-6" style="padding:2px;">
																<input type="text" id="basicFrom" name="basicFrom" value="" placeholder="From" class="form-control" />
															</div>
															<div class="form-group col-md-6" style="padding:2px;">	
																<input type="text" id="basicTo" name="basicTo" value="" placeholder="To" class="form-control" />
															</div>
														</div>
													</div>


													<div id="bankBox" class="filterItem">
													<h5 class="reportpk">Bank</h5>
														<div class="form-row">
															<div class="form-group col-md-6" style="padding:2px;">
																<select id="hdnBank" name="hdnBank[]"class="selectpicker" multiple>
																<?php 
																	$bank_result = count($bank);
																	for($i=0; $i<$bank_result; $i++)
																	{?>
																		<option value="<?php echo $bank[$i]['bank_id'];?>"><?php echo $bank[$i]['bank_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
													</div>                               
													<div id="user_statusBox" class="filterItem">
													<h5 class="reportpk">Employee Status</h5>
														<div class="form-row">
															<div class="form-group col-md-6" style="padding:2px;">
																<select id="hdnEmpStatus" name="hdnEmpStatus[]" class="selectpicker" multiple>    s<option value="1" <?php if($this->input->post('selEmpStatus') == '1') echo 'selected="selected"';?>>Active</option>
																	<option value="2" <?php if($this->input->post('selEmpStatus') == '2') echo 'selected="selected"';?>>Inactive</option>
																</select>
															</div>
														</div>
													</div>
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
											</div>
										</div>
									</div>
								</div>
							</div>
						</form> 
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>
<script>
$(document).ready(function(){
	$('#dojFrom').datepicker({
        dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            maxDate: '-1D',
			yearRange: "-100:+0",
		onSelect: function(selected) {
			$("#dojTo").datepicker("option","minDate", selected)
        }
	});
	$('#dojTo').datepicker({
        dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            maxDate: '-1D',
			yearRange: "-100:+0",
		onSelect: function(selected) {
           $("#dojFrom").datepicker("option","maxDate", selected)
        }
	});
});
</script>