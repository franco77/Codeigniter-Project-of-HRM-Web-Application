<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_id = "";
if (isset($_GET['id']))
{
	$get_id = "?id=".$_GET['id'];
}
echo '<input type="hidden" id="getURLIdVal" name="getURLIdVal" class="btn btn-sm btn-info" value="'.$get_id.'" />';
?>
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('accounts/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
				<?php $this->load->view('accounts/top_view');?>
					<?php //if($this->session->userdata('user_type') != 'EMP' ){ ?>
						<a href="<?= base_url('en/accounts_admin/salary_profile_readonly_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;View</a> 
					<?php //} ?>
					<legend class="pkheader">Salary</legend>
					<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed"> 
								<tbody>
									<tr class="info">
										<td valign="top" colspan="2" style="text-align:right;"> <strong>Salary Calculation Type <span class="red">*</span></strong> :</td>
										<td valign="top" colspan="2">
											<select id="calculation_type" name="calculation_type" class="required form-control" style="width:160px;">
											<option value="A" <?php if($empInfo[0]['calculation_type'] == "A"){echo "selected='selected'";}?>>Automatic</option>
											<option value="M" <?php if($empInfo[0]['calculation_type'] == "M"){echo "selected='selected'";}?>>Manual</option>                                                   
										 </select>
										</td>
									</tr>
									<tr>
										<td valign="top" width="20%"> <strong>Gross  </strong>(Monthly)<span class="red">*</span> : </td>
										<td valign="top" width="20%"> 
											<input type="text" id="txtgross_salary" name="txtgross_salary" value="<?php echo $empInfo[0]['gross_salary'];?>" class="required number form-control" style="width:150px; margin-bottom: 10px;" /></td>                                        
										<td valign="top" width="20%"><strong>Basic  </strong>(Upto 15,000):</td>
										<td valign="top" width="20%"> <input type="text" id="txtfixed_basic" name="txtfixed_basic" value="<?php echo $empInfo[0]['fixed_basic']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>
									
									</tr>
									<tr>
										<td valign="top"> <strong>Basic</strong> <span id="basic_p">(% of Gross)</span><span class="red">*</span> : </td>
										<td valign="top">
											<input type="text" id="txtbasic" name="txtbasic" value="<?php echo $empInfo[0]['basic']?>" class="required number form-control" style="width:150px; margin-bottom: 10px;" /></td>
										<td valign="top"> <strong>HRA</strong> <span id="hra_p">(% of Basic)</span> : <span class="red">*</span></td>
										<td valign="top">
											<input type="text" id="txthra" name="txthra" value="<?php echo $empInfo[0]['hra']?>" class="required number form-control" style="width:150px; margin-bottom: 10px;" /></td>
									</tr>
									<tr>
										<td valign="top"> <strong>Conveyance Allowance</strong><span class="red">*</span> </td>
										<td valign="top">
											<input type="text" id="txtconveyance_allowance" name="txtconveyance_allowance" value="<?php echo $empInfo[0]['conveyance_allowance'] ?>" class="required number form-control" style="width:150px; margin-bottom: 10px;" /></td>  
										<td valign="top"> <strong>Reimbursement</strong> :</td>
										<td valign="top">
											<input type="text" id="reimbursement" name="reimbursement" value="<?php echo $empInfo[0]['reimbursement']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>
									 </tr>
									<tr>    
								  
									<tr class="info"><td colspan="4" class="form_title" style="text-align:center; text-transform:uppercase;"><strong>Information for Salary Slip</strong></td></tr>
									<tr>
										<td valign="top"><strong>PF No.<span class="red">*</span> :</strong></td>
										<td valign="top"><input type="text" id="txtpf_no" name="txtpf_no" value="<?php echo $empInfo[0]['pf_no']?>" class="required form-control" style="width:150px; margin-bottom: 10px;" />
									<td valign="top"><strong>ESI / Mediclaim No<span class="red">*</span> :</strong></td>
										<td valign="top"><input type="text" id="txtmediclaim_no" name="txtmediclaim_no" value="<?php echo $empInfo[0]['mediclaim_no']?>" class="required form-control" style="width:150px; margin-bottom: 10px;" /></td>
									</tr>
									<tr>
										<td valign="top"> <strong>Bank:</strong></td>
										<td valign="top">
											<select id="selBank" name="selBank" class="form-control" style="width:160px;">
												<option value="">Select</option>
												<?php 
												for($l=0; $l < count($bankInfo); $l++) 
												{?>
													<option value="<?php echo $bankInfo[$l]['bank_id']; ?>" <?php if($empInfo[0]['bank_id'] == $bankInfo[$l]['bank_id']){echo "selected='selected'";}?> ><?php echo $bankInfo[$l]['bank_name']; ?></option>	
												<?php } ?>
											</select>
										</td>
									<td valign="top"><strong>Bank A/C No:</strong></td>
										<td valign="top"><input type="text" id="txtbank_no" name="txtbank_no" value="<?php echo $empInfo[0]['bank_no']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>
									</tr>
									<tr>
										<td valign="top"> <strong>Payment Mode:</strong></td>
										<td valign="top">
											<select id="payment_mode" name="payment_mode" class="required form-control" style="width:160px;">
												<option value="Bank Transfer" <?php if($empInfo[0]['payment_mode'] == "Bank Transfer"){echo "selected='selected'";}?>>Bank Transfer</option>
												<option value="Cheque" <?php if($empInfo[0]['payment_mode'] == "Cheque"){echo "selected='selected'";}?>>Cheque</option>
												<option value="Cash" <?php if($empInfo[0]['payment_mode'] == "Cash"){echo "selected='selected'";}?>>Cash</option>
											 </select>
										</td>
										<td valign="top"><strong>IFS Code:</strong></td>
										<td valign="top"><input type="text" id="txtbank_ifsc_code" name="txtbank_ifsc_code" value="<?php echo $empInfo[0]['ifsc_code']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>
									</tr>  
								</tbody>
							</table>
							<div class="row submtSec"  style="margin-bottom: 20px;">
								<div class="msg-sec"></div>
								<div class="col-md-3 pull-right">
									<span class="pull-right">
										<input type="button" id="btnUpdateSal" name="btnUpdateSal" class="btn btn-sm btn-info" value="Update" onclick="UpdateEmpSal(this)" />
									</span>
								</div>
							</div>
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<td colspan="4" class="form_title"  style="text-align:center; text-transform:uppercase;"><strong>Increment Information</strong></td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td valign="top" width="20%"> <strong>Incremented CTC<span class="red">*</span></strong>:</td>
										<td valign="top" width="20%">
											<input type="text" id="txtincreament" name="txtincreament" value="" class="form-control" style="width:150px; margin-bottom: 10px;" /></td>
										<td valign="top" width="20%"> <strong>Date<span class="red">*</span> :</strong></td>
										<td valign="top" width="20%">
											<input type="text" id="txtyear" name="txtyear" value="" class="form-control datepickerShow" style="width:150px; margin-bottom: 10px;" /></td>
									</tr>
								</tbody>
							</table>
							<div class="row submtSec"  style="margin-bottom: 20px;">
								<div class="msg-sec"></div>
								<div class="col-md-3 pull-right">
									<span class="pull-right">
										<input type="button" id="btnAddIncExp" name="btnAddIncExp" class="btn btn-sm btn-info" value="Add" onclick="AddIncExp(this)" />
									</span>
								 <div class="clear"></div>
								 </div>
							</div>
							<?php 
                                $j=0;
                             if(count($increRows) > 0)
                             {
                              for($i=0; $i< count($increRows); $i++)
                              {
								$j++;  ?>
							<div class="form">
								<div class="form1">
									<table class="table table-striped table-bordered table-condensed">
										<tr>
									<td colspan="4" class="form_title" style="text-align:center; text-transform:uppercase;"><strong>Increment Information <?php echo $j; ?></strong></td>
										</tr>
									 <tr>
										<td valign="top" width="20%"> <strong>Incremented CTC<span class="red">*</span> </strong>:</td>
										<td valign="top" width="20%">
											<input type="text" id="txtincreament_<?php echo $increRows[$i]['increament_info_id']; ?>" name="txtincreament_<?php echo $increRows[$i]['increament_info_id']; ?>" value="<?php echo $increRows[$i]['increament']; ?>" class="form-control" style="width:150px; margin-bottom: 10px;" /></td>
										<td valign="top" width="20%"> <strong>Date<span class="red">*</span> :</strong></td>
										<td valign="top" width="20%">
											<input type="text" id="txtyear_<?php echo $increRows[$i]['increament_info_id']; ?>" name="txtyear_<?php echo $increRows[$i]['increament_info_id']; ?>" value="<?php echo date("d-m-Y",strtotime($increRows[$i]['year'])); ?>" class="form-control" style="width:150px; margin-bottom: 10px;" /></td>
									</tr>
									</table>
									 <div class="row submtSec"  style="margin-bottom: 20px;">
										<div class="msg-sec"></div>
										<div class="col-md-3 pull-right">
											<span class="pull-right">
												<input type="button" id="btnUpdateIncExp_<?php echo $increRows[$i]['increament_info_id']; ?>" name="btnUpdateIncExp_<?php echo $increRows[$i]['increament_info_id']; ?>" class="btn btn-sm btn-info" value="Update" onclick="UpdateInc(this,'update', <?php echo $increRows[$i]['increament_info_id']; ?>)" />
												<input type="button" id="btnUpdateIncExp_<?php echo $increRows[$i]['increament_info_id']; ?>" name="btnUpdateIncExp_<?php echo $increRows[$i]['increament_info_id']; ?>" class="btn btn-sm btn-danger" value="Delete" onclick="UpdateInc(this,'delete', <?php echo $increRows[$i]['increament_info_id']; ?>)" />
											</span>
											<div class="clear"></div>
										</div>
									</div>
								</div>
							</div>
							  
								<?php } }
							else {?>
							<div class="form">
								<div class="form1">
									<table width="100%">
												<tr>
													<td align="center"><strong>No increment information added till now.</strong></td>
												</tr>
									</table>
								
									</div>
								</div>       
							 <?php }?>
						</div>
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>