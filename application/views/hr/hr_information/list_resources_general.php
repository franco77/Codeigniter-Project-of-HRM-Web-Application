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
				<div class="col-md-9" ng-app="myApp"> 
					<a  class="btn btn-primary pull-right btn-sm" role="button" data-toggle="modal" data-target="#addHoliday" style="margin-right: 10px; margin-top: 4px;" target="_blank"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Documents</a>
					
					<div class="modal fade" id="addHoliday" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">×</button>
									<h4 class="modal-title" id="exampleModalLabel">Add Resource Document</h4>
								</div>
								<form id="add_holiday" name="addPHND" method="POST" action="<?php echo base_url(); ?>en/hr/resource_submit" class="ng-pristine ng-valid"  enctype="multipart/form-data">
									<div class="modal-body">
										<div class="successMsg piMSG" id="messageSuccess4"></div>
										<div class="srlst">
											<div class="row">
												<label for="staticEmail" class="col-sm-4 col-form-label">Topic</label>
												<div class="col-sm-5">
													<select name="topic_id" class="form-control">
														<option value="1"> Staff Formats & Rules</option>
														<option value="2"> polohrm Info</option>
														<option value="3"> Guidelines & SOPs</option>
														<option value="4"> Holiday List </option>
													</select>
												</div>
											</div>
											<br>
											<div class="row">
												<label for="staticEmail" class="col-sm-4 col-form-label">Document Upload</label>
												<div class="col-sm-5">
													<input type="file" class="form-control" name="file" id="file" required>
												</div>
											</div>
											<br>
											<div class="row">
												<label for="staticEmail" class="col-sm-4 col-form-label">Document Title</label>
												<div class="col-sm-5">
													<input type="text" class="required form_ui form-control datepickerShow" id="doc_title" name="doc_title" value="" required />
												</div>
											</div>			
										</div>
									</div>
									<div class="modal-footer ">
										<button type="submit" data-dismiss="modal" class="btn btn-secondry">Close</button>
										<input type="submit" name="addholidaySubmit" class="btn btn-primary srlst" value="SUBMIT">
									</div>
								</form>
							</div>
						</div>
					</div>
					
					
					<legend class="pkheader">General Resources</legend>
					<div class="row well"> 
						<div class="panel with-nav-tabs panel-default" ng-controller="getAbbsysInfo" ng-init="init('<?php echo base_url() ?>')">  
							<div class="panel-body">
								<div class="tab-content">
									<div class="tab-pane fade in active" id="tab1default">
										<div id="successMsg"></div>
										<table class="table table-striped table-hover table-bordered">
											<thead>
												<tr  class="info">
													<th width="20%">#</th>
													<th width="40%"> Polohrm Info </th> 
													<th width="30%">Date</th>
													<th width="10%"></th>
												</tr>
											</thead>
											<tbody> 
												<tr ng-repeat="data in aabsysInfo">
													<td>{{$index+1}}</td>
													<td><a ng-href="{{data.link_url}}" target="_blank">&nbsp;&nbsp;{{data.link_title}}</a></td>
													<td>{{data.dttimes}} GMT</td> 
													<td><a ng-click="delete_Aabsys_Info(data.link_id)"><img src="<?php echo base_url('assets/images/icon/delete.gif'); ?>"></a></td>
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
													<th width="30%">Date</th>
													<th width="10%"></th>
												</tr>
											</thead>
											<tbody> 
												<tr>
													<td>1</td>
													<td><img src="<?php echo base_url('assets/images/ico-pdf.png')?>" alt="" align="left" />&nbsp;&nbsp;<a href="#" target="_blank">Polohrm User Guide</a></td>
													<td>09/12/2019 06.40 PM GMT</td> 
													<td></td>
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
													<th  width="30%">Date</th>
													<th width="10%"></th>
												</tr>
											</thead>
											<tbody> 
												<tr>
													<td>1</td>
													<td><a href="<?php echo base_url(); ?>en/resources/official_holidays">&nbsp;&nbsp;This Year Holidays List</a></td>
													<td>09/12/2019 06:42:10 PM GMT</td> 
													<td></td>
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
													<th width="30%">Date</th>
													<th width="10%"></th>
												</tr>
											</thead>
											<tbody> 
												<tr ng-repeat="data in rules">
													<td>{{$index+1}}</td>
													<td><a href="<?php echo base_url();?>assets/share/docs/rd_{{data.doc_id}}_{{data.doc_name}}" download target="_blank"><img src="<?php echo base_url('assets/images/ico-pdf.png')?>" alt="" align="left" />&nbsp;&nbsp;{{data.doc_title}}</a></td>
													<td>{{data.dttimes}} GMT</td> 
													<td><a ng-click="delete_StaffRule(data.doc_id)"><img src="<?php echo base_url('assets/images/icon/delete.gif'); ?>"></a></td>
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