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
					<legend class="pkheader">Payroll Help <small>(Payroll Center)</small></legend>
					<div class="row well"> 
						<form id="frmSalaryUpdate" name="frmSalaryUpdate" method="POST" action="<?= base_url('hr_help_desk/payroll_help');?>" enctype="multipart/form-data">
							<?php if($scsmsg !="") : ?>
									<div class="col-md-12">
										<div class="alert alert-success" role="alert">
											<?= $scsmsg ?>
										</div>
									</div>
								<?php endif; ?>
								<?php if(validation_errors()) : ?>
									<div class="col-md-12">
										<div class="alert alert-danger" role="alert">
											<?= validation_errors() ?>
										</div>
									</div>
								<?php endif; ?>
								<?php if(isset($error)) : ?>
									<div class="col-md-12">
										<div class="alert alert-danger" role="alert">
											<?= $error ?>
										</div>
									</div>
						<?php endif; ?>
							<div class="form-group"> 
								<textarea id="txtEditor" class="form-control ckeditor" name="txtMessage" rows="10" cols="120" required="" ></textarea> 
							</div>
							<div class="form-group">
								<input type="submit" id="btnAddMessage" name="btnAddMessage" class="btn btn-info pull-right" value="SUBMIT" /> 
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

<script src="https://cdn.ckeditor.com/4.9.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace( 'description',
    {
        toolbar : 'Basic', /* this does the magic */
        uiColor : '#9AB8F3'
    });
</script>