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
				
					<legend class="pkheader">Add Extra Hours</legend>
					<div class="row well">
						<div class="table-responsive" ng-show="filteredItems > 0">
							<img src = "<?= base_url('assets/images/website_comingsoon.png');?>" alt = "final declaration form " />
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
<script type="text/javascript">
var $k=jQuery.noConflict();
$k(function(){	    
        <?php if($successMsg){?>
        $k("#successMessage").html("Salary Slip Mailed successfully.").slideDown().delay(4000).slideUp();
        <?php } ?>
	$k('#frmPhoneAdd').validate({
		errorPlacement: function(error, element) {
			error.appendTo( element.attr('id'));
		}
	});
});
</script>