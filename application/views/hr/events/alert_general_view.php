<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>  

<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">General ALert <small>(Write General Alert Here.)</small></legend>
					<div class="row well"> 
						<form id="frmSalaryUpdate" name="frmSalaryUpdate" method="POST" action="<?= base_url('en/hr/alert_general');?>" enctype="multipart/form-data">
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
							<div class="form-group row"> 
								<label for="staticEmail" class="col-sm-2 col-form-label">Message : <span class="red">*</span></label>
								<div class="col-sm-10">
									<textarea id="txtEditor" class="form-control ckeditor" name="txtMessage" rows="10" cols="120" required=""><?php if(count($rowAllEmp)>0){ echo $rowAllEmp[0]['message']; } ?></textarea> 
								</div>
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