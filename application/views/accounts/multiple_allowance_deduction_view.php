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
					<legend class="pkheader">Multiple Allowance/Deduction<small>(AABSyS Resources)</small></legend>
					<div class="well">  
						
							<div class="row">
							
								<section>
									<div  class="col-md-12">
										<div class="form-group" style="height: 55px;">
											<div class="col-md-12"> <h4><?php echo $empDetails[0]['full_name'].' ( '.$empDetails[0]['loginhandle'] .' )'; ?></h4></div>
											<form  method="POST" action="<?= base_url('accounts_admin/multiple_allowance'.$get_id)?>">
												<div class="col-md-4">
													<label for="inputCity">&nbsp;Month</label>
													<select id="searchmonth" name="searchmonth"  class="form-control" onchange="javascript:document.getElementById('month').value = this.value">
													<?php if($month == "") $month = "";?>
														<option value="" <?php if($month == '') echo 'selected disabled'; ?>>Select</option>
														<option value='1' <?php if($month == '1') echo 'selected'; ?>>Janaury</option>
														<option value='2' <?php if($month == '2') echo 'selected'; ?>>February</option>
														<option value='3' <?php if($month == '3') echo 'selected'; ?>>March</option>
														<option value='4' <?php if($month == '4') echo 'selected'; ?>>April</option>
														<option value='5' <?php if($month == '5') echo 'selected'; ?>>May</option>
														<option value='6' <?php if($month == '6') echo 'selected'; ?>>June</option>
														<option value='7' <?php if($month == '7') echo 'selected'; ?>>July</option>
														<option value='8' <?php if($month == '8') echo 'selected'; ?>>August</option>
														<option value='9' <?php if($month == '9') echo 'selected'; ?>>September</option>
														<option value='10' <?php if($month == '10') echo 'selected'; ?>>October</option>
														<option value='11' <?php if($month == '11') echo 'selected'; ?>>November</option>
														<option value='12' <?php if($month =='12') echo 'selected'; ?>>December</option>
													</select>
												</div>
												<div class="col-md-4" style="padding: 2px;">
													<label for="inputCity">Year</label>
													<select id="searchYear" name="searchYear"  class="form-control" onchange="javascript:document.getElementById('year').value = this.value">
													<?php if($year == "") $year = "";?>
															<option value="" <?php if($year == "") echo 'selected disabled'; ?>>Select</option>
															<?php for($i=date('Y')-1; $i<=(date('Y')+10);$i++){ ?>
															<option value="<?php echo $i;?>"<?php if($year == $i) echo 'selected'; ?>><?php echo $i;?></option>
															<?php } ?>
													</select>
												</div>
												<div class="col-md-4">
													<input type="submit" id="btnFind" name="btnFind" class="search_sbmt pull-right btn btn-primary" value="FIND" onclick="copyData()" style="margin-top:25px"/>
												 </div>
											</form>	 
										</div>
									</div>
						<form id="addAllowance" method="POST">
									<div  class="col-md-12">
									<h4 class="pkhead2">Performance</h4>
										<div class="col-md-6"> 
											<div class="form-group">
												<label for="inputCity">Performance(in %)</label>
													<input type="text" id="performance_incentive" name="performance_incentive"  <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['performance_incentive']?>" <?php } ?>class="form-control">
											</div>
										</div>
										<div class="col-md-6"> 	
											<div class="form-group">
												<label for="inputCity">Income Tax</label>
												<input type="text" id="txtincometax" name="txtincometax"  <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['income_tax']?>" <?php } ?>  class="form-control">
											</div>
										</div>	
									</div>	
									<div  class="col-md-12">
										<h4 class="pkhead2">Mutiple Allowance</h4>
										<div class="col-md-6"> 
											<div class="form-group">
												<label for="inputCity">Buddy Referal Bonus</label>
													<input type="text" id="txtreferal_bonus" name="txtreferal_bonus"  <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['referal_bonus']?>" <?php } ?> class="form-control">
											</div>
											<div class="form-group">
												<label for="inputCity">Arrear(in Amount)</label>
													<input type="text" id="txtarrear" name="txtarrear"  <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['arrear']?>" <?php } ?> class="form-control">
											</div>
											<div class="form-group">
												<label for="inputCity">Food Allowance</label>
													<input type="text" id="txtfood_allowance" name="txtfood_allowance"  <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['food_allowance']?>" <?php } ?> class="form-control">
											</div>
											<div class="form-group">
												<label for="inputCity">Relocation Allowance</label>
													<input type="text" id="txtrelocation_allowance" name="txtrelocation_allowance"  <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['relocation_allowance']?>" <?php } ?> class="form-control">
											</div>
										</div>	
										<div class="col-md-6"> 
											<div class="form-group">
												<label for="inputCity">Leave Encashment</label>
													<input type="text" id="txtleave_encashment" name="txtleave_encashment"  <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['leave_encashment']?>" <?php } ?> class="form-control">
											</div>
											<div class="form-group">
												<label for="inputCity">City Allowance</label>
													<input type="text" id="txtcity_allowance" name="txtcity_allowance"   <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['city_allowance']?>" <?php } ?> class="form-control">
											</div>
											<div class="form-group">
												<label for="inputCity">Personal Allowance</label>
													<input type="text" id="txtpersonal_allowance" name="txtpersonal_allowance"  <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['personal_allowance']?>" <?php } ?> class="form-control">
											</div>
											<div class="form-group">
												<label for="inputCity">Other Income(Incentives)</label>
													<input type="text" id="txtincentive" name="txtincentive"  <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['incentive']?>" <?php } ?> class="form-control">
											</div>
										</div>
									</div>
									<div  class="col-md-12">
										<h4 class="pkhead2">Multiple Deduction</h4>
										<div class="col-md-6"> 
											<div class="form-group">
												<label for="inputCity">Donation</label>
													<input type="text" id="txtdonation" name="txtdonation"  <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['donation']?>" <?php } ?> class="form-control">
											</div>
											<div class="form-group">
												<label for="inputCity">Other Deduction</label>
												<input type="text" id="txtother_deduction" name="txtother_deduction"  <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['other_deduction']?>" <?php } ?> class="form-control">
											</div>
										</div>
										<div class="col-md-6"> 
											<div class="form-group">
												<label for="inputCity">Recovery</label>
													<input type="text" id="txtrecovery" name="txtrecovery"  <?php if(COUNT($empInfo) != 0) { ?> value="<?= $empInfo[0]['recovery']?>" <?php } ?> class="form-control">
											</div>
										</div>		
									</div>
									<input type="hidden" value="<?= $_GET['empid'] ?>" name="empid" required>
									<input type="hidden" id="month" name="month" required>
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
            url: site_url+'accounts_admin/addMultiple_allowance',
            data: frm.serialize(),
            success: function (data) {
                console.log(data);
				$('#piMSG').html('<h6>Allowance/Deduction submitted Successfully</h6>');
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
	$('#month').val($('#searchmonth').val());
		$('#year').val($('#searchYear').val());
});

function copyData(){
	$('#month').val($('#searchmonth').val());
	$('#year').val($('#searchYear').val());
}


</script>