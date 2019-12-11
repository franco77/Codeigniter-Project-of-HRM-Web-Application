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
				<div class="col-md-9"> 
					<legend class="pkheader">Increment Report(<small>Please select a year.</small>)</legend>
					<div class="row well">
						<form id="frmPhoneAdd" name="frmPhoneAdd" method="POST" action="">
							<div class="box-body pad"> 
								<div class="form-group">
									<label class="col-md-4 control-label" for="name">Increment Report For the Year of   </label>  
									<div class="col-md-2"> 
										<select id="selYear" name="selYear" class="form-control" id="sel1">
											<option value="">Select</option>
											<?php for($i=2014; $i<=(date('Y'));$i++){ ?>
											<option value="<?php echo $i;?>" <?php if($this->input->post('selYear') == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
											<?php } ?> 
										</select>									  
									</div>
									<div class="col-md-2">
										<input type="submit" id="incrementExport" name="incrementExport" class="btn btn-info pull-right" value="GENERATE" /> 
									</div>
								</div> 
							</div> 
						</form>   
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
 

