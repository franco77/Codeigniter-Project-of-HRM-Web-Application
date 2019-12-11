<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="empGratuity" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('accounts/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<a href="<?= base_url('accounts_admin/emp_gratuity_export');?>" class="btn btn-primary pull-right " role="button" style="margin-right: 10px; margin-top: 4px;" target="_blank"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Export To Excel</a>
					<legend class="pkheader">Employee Gratuity Details ({{ totalItems}})</legend>
					<div class="row well">
						<div class="row pkdsearch"> 
							<div class="col-md-2">PageSize:
								<select ng-model="entryLimit" class="form-control input-sm">
									<option ng-selected="entryLimit">10</option>
									<option>20</option>
									<option>30</option>
									<option>40</option>
									<option>50</option> 
								</select>
							</div>
							<div class="col-md-3">Search:
								<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
							</div> 
							<div class="col-md-3">
								<h5>Filtered {{ filtered.length }} of {{ totalItems}} Total Mid-year Appraisal Report </h5>
							</div>
						</div>
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<th>#</th>
										<th>Name</th>
										<th>DOJ</th>
										<th>DOB</th>
										<th>Father's Name</th>
										<th>Employee Code</th>
										<th>Amount</th> 
										<th>Years</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getempGratuity | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.name}} </td>
										<td>{{data.join_date}}</td>
										<td>{{data.dob}}</td> 
										<td>{{data.father_name}}</td> 
										<td>{{data.loginhandle}}</td> 
										<td>{{data.gratuity}}</td> 
										<td>{{data.years}}</td> 
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
					<div class="row">  
						<div ng-show="filteredItems > 0">    
							<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-sm" max-size="entryLimit" boundary-link-numbers="true" rotate="false" previous-text="&laquo;" next-text="&raquo;"></div> 
						</div>
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>