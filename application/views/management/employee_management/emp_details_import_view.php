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
					<legend class="pkheader">Import From Excel</legend>
					<div class="row well"> 
						<div class="form-inline">
						  <label class="mr-sm-2" for="inlineFormCustomSelect">Excel File</label>
						  <input type="file" class="form-control" id="inputEmail3" placeholder="Email">

						  <button type="submit" class="btn btn-info">Import</button>
						</div> 
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>