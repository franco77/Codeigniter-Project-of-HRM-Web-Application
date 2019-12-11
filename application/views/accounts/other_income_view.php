<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$get_id = "";
$get_id_data = "";
if (isset($_GET['empid']))
{
	$get_id = "?empid=".$_GET['empid'];
	$get_id_data = $_GET['empid'];
}
echo '<input type="hidden" id="getURLIdVal" name="getURLIdVal" class="btn btn-sm btn-info" value="'.$get_id.'" />';
?>
<style>
.pknoborder tr td {
	border:none;
}
.pkhead2 {
	background: #e9e9e9;
    padding: 4px;
    font-size: 15px;
    color: #000;
    font-weight: bold;
    border-bottom: 1px solid #344470;
	margin-bottom:20px;
}
</style> 
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
					<legend class="pkheader">Other Income<small>(Polohrm Resources)</small></legend>
					<div class=" well">  
						
							<div class="row">
							
								<section>
									<div  class="col-md-12">
										<div class="form-group" style="height: 55px;">
											<div class="col-md-12"> <h4><?php echo $empDetails[0]['full_name'].' ( '.$empDetails[0]['loginhandle'] .' )'; ?></h4></div>
											<form  method="POST" name="findData" class="form-horizontal" action="<?= base_url('en/accounts_admin/other_income'.$get_id)?>">
												<div class="col-md-4" style="padding: 2px;">
													<label for="inputCity">For the Financial Year</label>
													<?php $j=0; ?>
													<select id="searchYear" name="searchYear"  class="form-control" onchange="javascript:document.getElementById('year').value = this.value">
													<?php if($year == ""){ $year = ""; }?>
														<option value="" <?php if($year == ""){ echo 'selected disabled';} ?>>Select FY</option>
														<?php
														$yr=date("Y")-1;
														for ($j=$yr;$j>=2014;$j--){
															 if ($j == $year){
														?>
															<option value="<?php echo $j;?>" selected><?php echo $j.'-'.($j+1);?></option>
														<?php }else{?>
															<option value="<?php echo $j;?>"><?php echo $j.'-'.($j+1);?></option>
														<?php }
															}?>
													</select>
												</div>
												<div class="col-md-4">
													<input type="submit" id="btnFind" name="btnFind" class="search_sbmt pull-right btn btn-primary" value="FIND" onclick="copyData()" style="margin-top:25px"/>
												 </div>
											</form>	 
										</div>
									</div>
						
						<form id="addAllowance" method="POST" class="form-horizontal">
									<div class="table-responsive1">
								<table class="table table-striped table-bordered">
									<thead>
										<tr class="info">
											  <th colspan="1" class="form_title"><strong>Nature Of Payment</strong></th>
											<th colspan="1" class="form_title"><strong>Payment Date</strong></th>
											<th colspan="1" class="form_title"><strong>Amount Paid</strong></th>
										</tr>
									</thead>
									<tbody>
                                    <tr>
                                        <td valign="top"> <strong>Project Allowance </strong></td>
                                        <td valign="top">
                                            <input type="text" id="date1" name="project_allowance_date" value="<?php if(count($empInfo)>0){ if($empInfo[0]['project_allowance_date'] != "0000-00-00"){ echo date('m/d/Y', strtotime($empInfo[0]['project_allowance_date'])); }} ?>" class="number form-control" style="width:200px; " />
                                        </td>
                                        <td valign="top">
                                            <input type="text" id="project_allowance_amount" name="project_allowance_amount" value="<?php if(count($empInfo)>0) echo $empInfo[0]['project_allowance_amount']?>" class="number form-control" style="width:200px; " />
                                        </td> 
                                    </tr>
									<tr>
                                        <td valign="top"> <strong>Statutory Bonus</strong></td>
                                        <td valign="top">
                                            <input type="text" id="statutory_bonus_date" name="statutory_bonus_date" value="<?php if(count($empInfo)>0){ if($empInfo[0]['statutory_bonus_date'] != "0000-00-00"){ echo date('m/d/Y', strtotime($empInfo[0]['statutory_bonus_date'])); }} ?>"  class="number form-control" style="width:200px; " />
                                        </td>
                                        <td valign="top">
                                            <input type="text" id="statutory_bonus_amount" name="statutory_bonus_amount" value="<?php if(count($empInfo)>0) echo $empInfo[0]['statutory_bonus_amount']?>" class="number form-control" style="width:200px; " />
                                        </td> 
                                    </tr>
									<tr>
                                        <td valign="top"> <strong>Performance Bonus </strong></td>
                                        <td valign="top">
                                            <input type="text" id="performance_bonus_date" name="performance_bonus_date" value="<?php if(count($empInfo)>0){ if($empInfo[0]['performance_bonus_date'] != "0000-00-00"){ echo date('m/d/Y', strtotime($empInfo[0]['performance_bonus_date'])); }} ?>" class="number form-control" style="width:200px; " />
                                        </td>
                                        <td valign="top">
                                            <input type="text" id="performance_bonus_amount" name="performance_bonus_amount" value="<?php if(count($empInfo)>0) echo $empInfo[0]['performance_bonus_amount']?>" class="number form-control" style="width:200px; " />
                                        </td> 
                                    </tr>
									<tr>
                                        <td valign="top"> <strong>Incentive Payment </strong></td>
                                        <td valign="top">
                                            <input type="text" id="incentive_payment_date" name="incentive_payment_date" value="<?php if(count($empInfo)>0){ if($empInfo[0]['incentive_payment_date'] != "0000-00-00"){ echo date('m/d/Y', strtotime($empInfo[0]['incentive_payment_date'])); }} ?>" class="number form-control" style="width:200px; " />
                                        </td>
                                        <td valign="top">
                                            <input type="text" id="incentive_payment_amount" name="incentive_payment_amount" value="<?php if(count($empInfo)>0) echo $empInfo[0]['incentive_payment_amount']?>" class="number form-control" style="width:200px; " />
                                        </td> 
                                    </tr>
									<tr>
                                        <td valign="top"> <strong>Leave Incashment </strong></td>
                                        <td valign="top">
                                            <input type="text" id="leave_incashment_date" name="leave_incashment_date" value="<?php if(count($empInfo)>0){ if($empInfo[0]['leave_incashment_date'] != "0000-00-00"){ echo date('m/d/Y', strtotime($empInfo[0]['leave_incashment_date'])); }} ?>" class="number form-control" style="width:200px; " />
                                        </td>
                                        <td valign="top">
                                            <input type="text" id="leave_incashment_amount" name="leave_incashment_amount" value="<?php if(count($empInfo)>0) echo $empInfo[0]['leave_incashment_amount']?>" class="number form-control" style="width:200px; " />
                                        </td> 
                                    </tr>
									<tr>
                                        <td valign="top"> <strong>Other Payment </strong></td>
                                        <td valign="top">
                                            <input type="text" id="other_payment_date" name="other_payment_date" value="<?php if(count($empInfo)>0){ if($empInfo[0]['other_payment_date'] != "0000-00-00"){ echo date('m/d/Y', strtotime($empInfo[0]['other_payment_date'])); }} ?>" class="number form-control" style="width:200px; " />
                                        </td>
                                        <td valign="top">
                                            <input type="text" id="other_payment_amount" name="other_payment_amount" value="<?php if(count($empInfo)>0) echo $empInfo[0]['other_payment_amount']?>" class="number form-control" style="width:200px; " />
                                        </td> 
                                    </tr> 
									</tbody>
                                </table>
                                </div>
									<input type="hidden" value="<?= $_GET['empid'] ?>" name="empid" required>
									<input type="hidden" id="year" name="year" required>
								</section>
							</div> 
							<div class="row">
								<div class="col-md-9 successMsg" id="piMSG" style="text-align:center;"></div>
								<input type="submit" id="btnallowance" name="btnallowance" class="btn btn-info pull-right" value="SUBMIT" /> 
							</div>
							<div class="clearfix"></div>
						</form>
					</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>
<script>
 var site_url = '<?php echo base_url(); ?>';
   var frm = $('#addAllowance');
	var site_url = "<?= base_url(); ?>";
    frm.submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: frm.attr('method'),
            url: site_url+'accounts_admin/addOtherIncome',
            data: frm.serialize(),
            success: function (data) {
                console.log(data);
				$('#piMSG').html('<h6>Other Income submitted Successfully</h6>');
				setTimeout(function(){ location.reload(); }, 2000);	
				setTimeout(function(){ $('#piMSG').html(''); }, 3000);
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
            },
        });
    });
	
$(document).ready(function(){
		$('#year').val($('#searchYear').val());
});

function copyData(){
	$('#year').val($('#searchYear').val());
}


</script>

<script>
$( document ).ready(function() {
    $('#date1, #statutory_bonus_date, #performance_bonus_date, #incentive_payment_date, #leave_incashment_date, #other_payment_date').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: "m/d/yy"
}); 
}); 
</script>