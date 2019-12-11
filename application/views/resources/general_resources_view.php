<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>  
<div class="section main-section">
    <div class="container">
         
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('resources/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9" ng-app="myApp"> 
					<legend class="pkheader">General Resources</legend>
					<div class="row well"> 
						<div class="panel with-nav-tabs panel-default" ng-controller="getAbbsysInfo" ng-init="init('<?php echo base_url() ?>')">  
							<div class="panel-body">
								<div class="tab-content">
									<div class="tab-pane fade in active" id="tab1default">
										<table class="table table-striped table-hover table-bordered">
											<thead>
												<tr  class="info">
													<th width="20%">#</th>
													<th width="40%"> Polosoft Info </th> 
													<th width="40%">Date</th>
												</tr>
											</thead>
											<tbody> 
												<tr ng-repeat="data in aabsysInfo">
													<td>{{$index+1}}</td>
													<td><a ng-href="{{data.link_url}}" target="_blank"><img src="<?php echo base_url('assets/images/file.gif')?>" alt="" align="left" />&nbsp;&nbsp;{{data.link_title}}</a></td>
													<td>{{data.dttimes}} GMT</td> 
												</tr>
											</tbody>
										</table>
										<div class="row">
											<div class="col-lg-12" ng-show="filteredItems == 0">
												<div class="col-lg-12">
												<h4>Not found</h4>
												</div> 
											</div> 
										</div> 
									</div>
								</div>
							</div>
						</div>
						<div class="panel with-nav-tabs panel-default">  
							<div class="panel-body">
								<div class="tab-content">
									<div class="tab-pane fade in active" id="tab2default">
										<table class="table table-striped table-hover table-bordered">
											<thead>
												<tr  class="info">
													<th width="20%">#</th>
													<th width="40%"> Guidelines & SOPs</th> 
													<th width="40%">Date</th>
												</tr>
											</thead>
											<tbody> 
												<tr>
													<td>1</td>
													<td><img src="<?php echo base_url('assets/images/file.gif')?>" alt="" align="left" />&nbsp;&nbsp;<a href="#" target="_blank">Polohrm User Guide</a></td>
													<td>05/12/2019 05.00 PM GMT</td> 
												</tr>
											</tbody>
										</table>
										<div class="row">
											<div class="col-lg-12" ng-show="filteredItems == 0">
												<div class="col-lg-12">
												<h4>Not found</h4>
												</div> 
											</div> 
										</div> 
									</div>
								</div>
							</div>
						</div>
						<div class="panel with-nav-tabs panel-default">  
							<div class="panel-body">
								<div class="tab-content">
									<div class="tab-pane fade in active" id="tab2default">
										<table class="table table-striped table-hover table-bordered">
											<thead>
												<tr  class="info">
													<th width="20%">#</th>
													<th  width="40%">Holiday List</th> 
													<th  width="40%">Date</th>
												</tr>
											</thead>
											<tbody> 
												<tr>
													<td>1</td>
													<td><a href="<?php echo base_url(); ?>en/resources/official_holidays"><img src="<?php echo base_url('assets/images/file.gif')?>" alt="" align="left" />&nbsp;&nbsp;This Year Holidays List</a></td>
													<td>01/01/2019 10:00 AM GMT</td> 
												</tr>
											</tbody>
										</table> 
									</div>
								</div>
							</div>
						</div>
						<div class="panel with-nav-tabs panel-default" ng-controller="getStaffRule" ng-init="init('<?php echo base_url() ?>')">  
							<div class="panel-body">
								<div class="tab-content">
									<div class="tab-pane fade in active" id="tab2default" ng-show="filteredItems > 0">
										<table class="table table-striped table-hover table-bordered">
											<thead>
												<tr  class="info">
													<th width="20%">#</th>
													<th width="40%">Staff Formats & Rules</th> 
													<th width="40%">Date</th>
												</tr>
											</thead>
											<tbody> 
												<tr ng-repeat="data in rules">
													<td>{{$index+1}}</td>
													<td><a href="<?php echo base_url();?>assets/share/docs/rd_{{data.doc_id}}_{{data.doc_name}}" download ><img src="<?php echo base_url('assets/images/ico-pdf.png')?>" alt="" align="left" />&nbsp;&nbsp;{{data.doc_title}}</a></td>
													<td>{{data.dttimes}} GMT</td> 
												</tr>
											</tbody>
										</table>
										<div class="row">
											<div class="col-lg-12" ng-show="filteredItems == 0">
												<div class="col-lg-12">
												<h4>Not found</h4>
												</div> 
											</div> 
										</div> 
									</div> 
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
</div>