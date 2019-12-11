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
				<div class="col-md-9 center-xs">
					<legend class="pkheader">HR Policies</legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"></span>HR Policies</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="directorMessage" name="directorMessage" method="POST">
										<div class="col-md-12">
											<div class="form-group row">
												<div class="col-sm-12">
													<textarea name="editor1" ><?php if(COUNT($checkDataRes) > 0){ echo $checkDataRes[0]['policies']; }?></textarea>
												</div>
											</div>
										</div>
										 <div class="col-md-12 add_btn">
											<input type="submit" id="btn_add" name="btn_update" class="search_sbmt pull-right btn btn-primary" value="Submit"/>
										 </div>
										 <div class="col-md-12 successMsg" id="piMSG" style="text-align:center;"></div>
										
										</div>
									</form>
								</div>
							</div>
						</div>
						
					</div> 
					
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
<script src="https://cdn.ckeditor.com/4.9.1/standard/ckeditor.js"></script>	
<script type="text/javascript">
    var frm = $('#directorMessage');
	var site_url = "<?= base_url(); ?>";
	frm.submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: frm.attr('method'),
            url: site_url+'en/hr/add_hr_policies',
            data: {data:CKEDITOR.instances.editor1.getData()},
            success: function (data) {
                console.log(data);
				$('#piMSG').html('HR Policies submitted Successfully');	
					setTimeout(function(){ $('#piMSG').html(''); }, 3000);
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
            },
        });
    });

	CKEDITOR.replace( 'editor1' );
</script>