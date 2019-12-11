<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 

<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h4 class="section-title">
				<span class="c-dark"><?php echo $this->uri->segment(2);?></span>
			</h4>
		</div>
	</div>
</div> 
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
					<div class="form-content page-content">
						<div class="box-header">
							<h3 class="box-title">Payroll Help 
								<small>Payroll Center</small>
							</h3> 
							<div class="box-body pad">
								<form id="profileForm" method="post" class="form-horizontal">
									<textarea name="bio" rows="10" cols="80" class="form-control"></textarea>
								</form>
							</div>
							<div class="form-group"> 
								<button id="signup" name="signup" class="btn btn-primary pull-right">Submit</button> 
							</div> 
						</div>
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div>
</div>