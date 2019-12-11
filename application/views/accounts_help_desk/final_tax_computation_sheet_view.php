<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('accounts_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<form action="<?= base_url('accounts_help_desk/final_tax_computation_sheet')?>" method="POST" class="form-horizontal"  id="formSubmit" >
					<span  class="pull-right " role="button" style="margin-right: 10px; margin-top: 4px;" > <span style="color:#fff;">FY of : </span> 
						<select  name="searchYear" id="searchYear" class="input-sm" onchange="this.form.submit()" > 
							<?php
							  $yr=date("Y");
							  for ($j=$yr;$j>=2017;$j--){
							  if ($j == $fyear){
							 ?>
							  <option value="<?php echo $j;?>" selected ><?php echo $j.'-'.($j+1);?></option>
							 <?php }else{?>
							 <option value="<?php echo $j;?>" ><?php echo $j.'-'.($j+1);?></option>
							 <?php }
							 }?> 
						</select>
					</span>
					</form>
					<legend class="pkheader">Final Tax Computation Sheet</legend>
					<div class="row well">
						<div class="table-responsive">
							 <table class="table table-striped table-bordered table-condensed">
								<div class="embed-responsive embed-responsive-4by3">
								  <iframe class="embed-responsive-item" src="<?php echo base_url('accounts_help_desk/final_tax_computation_sheet_result?fyear='.$fyear);?>"></iframe>
								</div>
							</table>
						</div>
						<!-- /.table-responsive -->
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>
