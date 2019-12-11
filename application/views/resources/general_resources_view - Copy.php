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
													<th>#</th>
													<th> AABSyS Info </th> 
													<th>Date</th>
												</tr>
											</thead>
											<tbody> 
												<tr ng-repeat="data in aabsysInfo">
													<td>{{$index+1}}</td>
													<td><a ng-href="{{data.link_url}}" target="_blank"><img src="<?php echo base_url('assets/images/file.gif')?>" alt="" align="left" />&nbsp;&nbsp;{{data.link_title}}</a></td>
													<td>{{data.dttime}}</td> 
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
						<div class="panel with-nav-tabs panel-default" ng-controller="getGuideLine" ng-init="init('<?php echo base_url() ?>')">  
							<div class="panel-body">
								<div class="tab-content">
									<div class="tab-pane fade in active" id="tab2default" ng-show="filteredItems > 0">
										<table class="table table-striped table-hover table-bordered">
											<thead>
												<tr  class="info">
													<th>#</th>
													<th> Guidelines & SOPs</th> 
													<th>Date</th>
												</tr>
											</thead>
											<tbody> 
												<tr ng-repeat="data in guideline">
													<td>{{$index+1}}</td>
													<td><img src="<?php echo base_url('assets/images/file.gif')?>" alt="" align="left" />&nbsp;&nbsp;{{data.topic_title}}</td>
													<td>{{data.dttime}}</td> 
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
													<th>#</th>
													<th>Holiday List</th> 
													<th>Date</th>
												</tr>
											</thead>
											<tbody> 
												<tr>
													<td>1</td>
													<td><a href="http://localhost/ci_icompass/en/resources/official_holidays"><img src="<?php echo base_url('assets/images/file.gif')?>" alt="" align="left" />&nbsp;&nbsp;This Year Holidays List</a></td>
													<td>01/01/2018</td> 
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
													<th>#</th>
													<th>Staff Formats & Rules</th> 
													<th>Date</th>
												</tr>
											</thead>
											<tbody> 
												<tr ng-repeat="data in rules">
													<td>{{$index+1}}</td>
													<td><a ng-href="{{data.doc_path}}" target="_blank" download="{{data.doc_title}}"><img src="<?php echo base_url('assets/images/ico-pdf.png')?>" alt="" align="left" />&nbsp;&nbsp;{{data.doc_title}}</a></td>
													<td>{{data.dttime}}</td> 
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