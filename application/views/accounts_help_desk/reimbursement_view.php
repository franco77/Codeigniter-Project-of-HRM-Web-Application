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
					<legend class="pkheader">Reimbursement</legend>
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
