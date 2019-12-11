<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="leaveprovision" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<a  class="btn btn-primary pull-right btn-sm" role="button" style="margin-right: 10px; margin-top: 4px;" target="_blank" ng-click="exportToExcel()"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Export To Excel</a>
					<legend class="pkheader">Track Leave Provision of Employees</legend> 
					<div class="row well"> 
						<div class="row pkdsearch">
							<div class="col-md-3">Leave Provision FY:
								<select ng-model="searchYear" name="year" class="form-control input-sm">
									 <?php
										  $yr=date("Y");
										  for ($j=$yr;$j>=2014;$j--){?>
										 <option value="<?php echo $j.'-'.($j+1);?>" <?php if($this->input->post('year') == $j.'-'.($j+1) || $this->input->post('year') == $j.'-'.($j+1)) echo "selected";?>><?php echo $j.'-'.($j+1);?></option>
										 <?php }
										 ?> 
								</select>
							</div> 
							<div class="col-md-3">Name:
								<input type="text" ng-model="searchName" name="name" value="<?php echo(stripslashes(htmlspecialchars($this->input->post('name'))))?>" placeholder="Enter Name" class="form-control input-sm" />
							</div> <br>
							<div class="col-md-3">
								<input type="submit" id="searchRegularize" name="searchRegularize" class="btn btn-primary pull-right" value="Find" ng-click="advanceFilter();" /> 
							</div>
							<div class="col-md-3">
								<h5>Filtered {{ filtered.length }} of {{ totalItems}} total late coming info </h5>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<th width="25%">Name<a ng-click="sort_by('full_name');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="25%" nowrap='nowrap'>Employee Code<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="25%">DOJ<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>                                                             
										<th width="25%">Tot Leave as on 1/1/<span>{{yearrrr}}</span><a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="25%">Jan<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="25%">Feb<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="25%">Mar<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="25%">This Yr. Tot Leave<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="25%">Leave Availed This Yr<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="25%">Cumu. as on <?php //echo '31/3/'.$e_year;?><a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="25%">Gross<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="25%">Leave Amt<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (leaveprovision | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td><a ng-href="<?php echo base_url(); ?>en/hr/general_readonly?id={{data.login_id}}" ng-bind="data.name;" ></a> </td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.doj}}</td> 
										<td>{{data.tot_leave}}</td>
										<td>0</td>
										<td>{{data.feb}}</td>
										<td>{{data.mar}}</td>
										<td>{{data.tot_leave_yr}}</td>
										<td>{{data.leave_availed}}</td>
										<td>{{data.cumu}}</td>
										<td>{{data.gross}}</td>
										<td>{{data.leave_amt}}</td>
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="12" align="center">No records found</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-12" ng-show="filteredItems == 0">
							<div class="col-md-12">
								<h4>No records found</h4>
							</div>
						</div>
					</div>
					<div ng-show="filteredItems > 0">    
						<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-sm" max-size="entryLimit" boundary-link-numbers="true" rotate="false" previous-text="&laquo;" next-text="&raquo;"></div> 
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>