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
						<?php $this->load->view('myprofile/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<?php if($this->session->userdata('user_type') != 'EMP' &&  (isset($_GET['id']))){ ?>
						<a href="<?= base_url('my_account/salary_profile_update_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a> 
					<?php } ?>
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
										<td valign="top" width="20%"> <?php //echo $empInfo[0]['gross_salary']?></td>                                        
										<td valign="top" width="20%"><strong>Basic  </strong>(Upto 15,000):</td>
										<td valign="top" width="20%"> <?php //echo $empInfo[0]['fixed_basic']?></td>
									
									</tr>
									<tr>
										<td valign="top"> <strong>Basic</strong> <span id="basic_p">(% of Gross)</span> :</td>
										<td valign="top"><?php //echo $empInfo[0]['basic']?></td>
									<td valign="top"> <strong>HRA</strong> <span id="hra_p">(% of Basic)</span> :</td>
										<td valign="top"><?php //echo $empInfo[0]['hra']?></td>
									</tr>
									<tr>
										<td valign="top"> <strong>Conveyance Allowance</strong> :</td>
										<td valign="top"><?php //echo $empInfo[0]['conveyance_allowance']?></td>  
										<td valign="top"> <strong>Reimbursement</strong> :</td>
										<td valign="top"><?php //echo $empInfo[0]['reimbursement']?></td>
									 </tr>
									<tr>    
								  
									<tr class="info"><td colspan="4" class="form_title" style="text-align:center; text-transform:uppercase;"><strong>Information for Salary Slip</strong></td></tr>
									<tr>
										<td valign="top"><strong>PF No.:</strong></td>
										<td valign="top"><?php echo $empInfo[0]['pf_no']?>  </td>
										<td valign="top"> <strong>Bank:</strong></td>
										<td valign="top"><?php echo $empInfo[0]['bank_name']?></td>
									</tr>
									<tr>
										<td valign="top"><strong>UAN No:</strong></td>
										<td valign="top"><?php echo $empInfo[0]['uan_no']?></td>
										<td valign="top"><strong>Bank A/C No:</strong></td>
										<td valign="top"><?php echo $empInfo[0]['bank_no']?></td>
									</tr>
									<tr>
										<td valign="top"><strong>ESI / Mediclaim No:</strong></td>
										<td valign="top"><?php echo $empInfo[0]['mediclaim_no']?></td>
										<td valign="top"><strong>IFS Code:</strong></td>
										<td valign="top"><?php echo $empInfo[0]['ifsc_code']?></td>
									</tr>  
									<tr>
										<td valign="top"> <strong>Payment Mode:</strong></td>
										<td valign="top"><?php echo $empInfo[0]['payment_mode']?></td>
									</tr>  
								</tbody>
							</table>
							<?php 
                                $j=0;
                             if(count($increRows) > 0)
                             {
                              for($i=0; $i< count($increRows); $i++)
                              {
								$j++;  ?>
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<td colspan="4" class="form_title"  style="text-align:center; text-transform:uppercase;"><strong>Increment Information <?php echo $j; ?></strong></td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td width="20%" valign="top"><strong>Incremented CTC:</strong></td>
										<td width="20%" valign="top"><strong><?php echo $increRows[$i]['increament']; ?></strong></td> 
										<td width="20%" valign="top"><strong>Date:</strong></td>
										<td width="20%" valign="top"><strong><?php echo date("d-m-Y",strtotime($increRows[$i]['year'])); ?></strong></td> 
									</tr>
								</tbody>
							</table>
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