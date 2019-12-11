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
					<legend class="pkheader">Employee Suggestion <small>(Employee Suggestion)</small></legend>
					<div class="row well">
						<form> 
							<div class="form-group"> 
								<textarea id="editor1" class="form-control ckeditor" name="editor1" rows="10"></textarea> 
							</div>
							<div class="form-group"> 
								<button id="signup" name="signup" class="btn btn-info pull-right">Submit</button> 
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