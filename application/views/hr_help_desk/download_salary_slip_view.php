<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Download Salary Slip <small>(Please select a month & Year)</small></legend>
					<div class="row well"> 
						<?php //echo $msg;?>
						<form id="frmPhoneAdd" name="frmPhoneAdd" method="POST" action="">
							<div class="box-body pad"> 
								<div class="form-group">
									<label class="col-md-6 control-label" for="name">Download Salary Slip for the month of</label>  
									<div class="col-md-2"> 
										<select id="selMonth" name="selMonth" class="form-control" id="sel1">
											<option value="">Select</option>
											<option value="1" <?php if($this->input->post('selMonth') == '1') echo 'selected="selected"';?>>January</option>
											<option value="2" <?php if($this->input->post('selMonth') == '2') echo 'selected="selected"';?>>February</option>
											<option value="3" <?php if($this->input->post('selMonth') == '3') echo 'selected="selected"';?>>March</option>
											<option value="4" <?php if($this->input->post('selMonth') == '4') echo 'selected="selected"';?>>April</option>
											<option value="5" <?php if($this->input->post('selMonth') == '5') echo 'selected="selected"';?>>May</option>
											<option value="6" <?php if($this->input->post('selMonth') == '6') echo 'selected="selected"';?>>June</option>
											<option value="7" <?php if($this->input->post('selMonth') == '7') echo 'selected="selected"';?>>July</option>
											<option value="8" <?php if($this->input->post('selMonth') == '8') echo 'selected="selected"';?>>August</option>
											<option value="9" <?php if($this->input->post('selMonth') == '9') echo 'selected="selected"';?>>September</option>
											<option value="10" <?php if($this->input->post('selMonth') == '10') echo 'selected="selected"';?>>October</option>
											<option value="11" <?php if($this->input->post('selMonth') == '11') echo 'selected="selected"';?>>November</option>
											<option value="12" <?php if($this->input->post('selMonth') == '12') echo 'selected="selected"';?>>December</option> 
										</select>									  
									</div>
									<div class="col-md-2"> 
										<select id="selYear" name="selYear" class="form-control" id="sel1">
											<option value="">Select</option>
											<?php for($i=2014; $i<=(date('Y'));$i++){ ?>
											<option value="<?php echo $i;?>" <?php if($this->input->post('selYear') == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
											<?php } ?> 
										</select>									  
									</div>
								</div> 
								<div class="form-group">
									<label class="col-md-12 control-label red" ><h4><?php echo $msg; ?></h4></label>  
								</div>
							</div>
							<div class="form-group">
								<input type="submit" id="mailSalarySlip" name="mailSalarySlip" class="btn btn-info pull-right" value="Submit" /> 
							</div>
						</form> 
					</div>
					
					
					<div class="row well"  style="line-height: 20px; overflow-x:auto;"> 
						<section id="print_id" style="width: 678px; display: block; margin: 0 auto;">
							<?php if($slip !="")
							{ ?>
								<section>
									<?php echo $slip; ?>
								</section>
								<br>
									
									<p style="text-align: center; font-size: 10px; color: #000; cursor: pointer;"><img id="btnprint" alt="Print" onclick="printDiv('print_id');" src="<?php echo base_url(); ?>assets/images/printer_icon.png"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <img id="btnprint" alt="Print" onclick="downloadSalarySlip(this);" src="<?php echo base_url(); ?>assets/images/icon/downloads.png" style="width:25px;"></p>

							<?php } else{ ?>
								  <p style="text-align: center; font-size: 10px; color: #000;">Salary slip not found!</p>
							 <?php } ?>
						</section>
					</div> 
					
					
					
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div>
</div>
<script type="text/javascript">
/* var $k=jQuery.noConflict();
$k(function(){	    
        <?php if($successMsg){?>
        $k("#successMessage").html("Salary Slip Mailed successfully.").slideDown().delay(4000).slideUp();
        <?php } ?>
	$k('#frmPhoneAdd').validate({
		errorPlacement: function(error, element) {
			error.appendTo( element.attr('id'));
		}
	});
}); */
</script>

<script>
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;
	 
	 var ButtonControl = document.getElementById("btnprint");
     ButtonControl.style.visibility = "hidden";

     window.print();

     document.body.innerHTML = originalContents;
}
</script>

<script>
function downloadSalarySlip(dis) {
    
	var selMonth = $('#selMonth').val();
	var selYear = $('#selYear').val();
    var url = site_url+'hr_help_desk/download_salary_slip_doenload?m='+selMonth+'&y='+selYear;
    window.open(url, '_blank');
}
</script>