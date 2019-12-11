<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('myprofile/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">Salary</legend>
					<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed"> 
								<tbody>
									<tr class="info">
										<td valign="top" colspan="4" style="text-align:center; text-transform:uppercase;"> <strong>Salary Calculation Type : <?php if($empInfo[0]['calculation_type'] == "A") echo "Automatic"; else echo "Manual";  ?></strong></td>
										
									</tr>
									<tr>
										<td valign="top" width="20%"> <strong>Gross  </strong>(Monthly):</td>
										<td valign="top" width="30%"> <?php echo $empInfo[0]['gross_salary']?></td>                                        
										<td valign="top" width="20%"><strong>Basic  </strong>(Upto 15,000):</td>
										<td valign="top" width="30%"> <?php echo $empInfo[0]['fixed_basic']?></td>
									
									</tr>
									<tr>
										<td valign="top"> <strong>Basic</strong> <span id="basic_p">(% of Gross)</span> :</td>
										<td valign="top"><?php echo $empInfo[0]['basic']?></td>
										<td valign="top"> <strong>HRA</strong> <span id="hra_p">(% of Basic)</span> :</td>
										<td valign="top"><?php echo $empInfo[0]['hra']?></td>
									</tr>
									<tr>
										<td valign="top"> <strong>Conveyance Allowance</strong> :</td>
										<td valign="top"><?php echo $empInfo[0]['conveyance_allowance']?></td>  
										<td valign="top"> <strong>Reimbursement</strong> :</td>
										<td valign="top"><?php echo $empInfo[0]['reimbursement']?></td>
									 </tr>
									<tr>    
								  
									<tr class="info"><td colspan="4" class="form_title" style="text-align:center; text-transform:uppercase;"><strong>Information for Salary Slip</strong></td></tr>
									<tr>
										<td width="20%" valign="top"><strong>PF No.:</strong></td>
										<td width="30%" valign="top"><?php echo $empInfo[0]['pf_no']?>  </td>
										<td width="20%" valign="top"><strong>ESI / Mediclaim No:</strong></td>
										<td width="30%" valign="top"><?php echo $empInfo[0]['mediclaim_no']?></td>
									</tr>
									<tr>
										<td valign="top"> <strong>Bank:</strong></td>
										<td valign="top"><?php echo $empInfo[0]['bank_name']?></td>
									<td valign="top"><strong>Bank A/C No:</strong></td>
										<td valign="top"><?php echo $empInfo[0]['bank_no']?></td>
									</tr>
									<tr>
										<td valign="top"> <strong>Payment Mode:</strong></td>
										<td valign="top"><?php echo $empInfo[0]['payment_mode']?></td>
										<td valign="top"><strong>IFS Code:</strong></td>
										<td valign="top"><?php echo $empInfo[0]['ifsc_code']?></td>
									</tr>  
								</tbody>
							</table>
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<td colspan="4" class="form_title"  style="text-align:center; text-transform:uppercase;"><strong>Increment Information</strong></td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td width="20%" valign="top"><strong>Incremented CTC:</strong></td>
										<td width="20%" valign="top"><strong>Incremented CTC:</strong></td> 
									</tr>
									<tr>
										<td width="20%" valign="top"><strong>Date:</strong></td> 
										<td width="20%" valign="top"><strong>Date:</strong></td> 
									</tr>
									<tr>
										<td colspan="4" class=""><strong>No increament information added till now.</strong></td>
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
</div>