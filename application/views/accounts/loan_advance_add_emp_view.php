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
					<legend class="pkheader">Loan and Advance</legend>
					<div class="well">  
						
							<div class="row">
							
								<section>
									<div  class="col-md-12">
										<div class="form-group" style="height: 55px;">
											<div class="col-md-12"> <h4><?php echo $empDetails[0]['full_name'].' ( '.$empDetails[0]['loginhandle'] .' )'; ?></h4></div>
											<form  method="POST" action="<?= base_url('accounts_admin/loan_advance_loan'.$get_id)?>">
												<div class="col-md-4">
													<label for="inputCity">&nbsp;Month</label>
													<select  required id="searchmonth" name="searchmonth"  class="form-control" onchange="javascript:document.getElementById('month').value = this.value" >
													<?php if($month == "") $month = "";?>
														<option value="" <?php if($month == '') echo 'selected disabled'; ?>>Select</option>
														<option value='1' <?php if($month == '1') echo 'selected'; ?>>January</option>
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
													<select required id="searchYear" name="searchYear"  class="form-control" onchange="javascript:document.getElementById('year').value = this.value" >
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
								<form id="addLoan_Advance" method="POST">
									<div  class="col-md-12">
									<h4 class="pkhead2">Define Loan & Advance </h4>
										<div class="col-md-6"> 
											<div class="form-group">
												<label for="inputCity">Loan(Monthly)</label>
													<input type="text" id="txtloan" name="txtloan" <?php if(COUNT($empInfo) > "") { ?> value="<?=$empInfo[0]['loan'] ?>" <?php } ?> class="form-control">
											</div>
										</div>
										<div class="col-md-6"> 	
											<div class="form-group">
												<label for="inputCity">Advance(Monthly)</label>
												<input type="text" id="txtadvance" <?php if(COUNT($empInfo) > "") { ?> value="<?=$empInfo[0]['advance'] ?>" <?php } ?> name="txtadvance"  class="form-control">
											</div>
										</div>	
									</div>	
									<input type="hidden" value="<?= $_GET['empid'] ?>" name="empid">
									<input type="hidden" id="month" name="month">
									<input type="hidden" id="year" name="year">
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
   var frm = $('#addLoan_Advance');
	var site_url = "<?= base_url(); ?>";
    frm.submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: frm.attr('method'),
            url: site_url+'accounts_admin/addLoan_Advance',
            data: frm.serialize(),
            success: function (data) {
                console.log(data);
				$('#piMSG').html('<h6>Loan/Advance submitted Successfully</h6>');
				//setTimeout(function(){ location.reload(); }, 2000);	
				setTimeout(function(){ $('#piMSG').html('');location.reload(); }, 3000);
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