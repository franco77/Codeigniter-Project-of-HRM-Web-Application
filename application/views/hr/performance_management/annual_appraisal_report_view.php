<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="AnnualAppraisalReport" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<a class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;" ng-click="exportToExcel()"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Export To Excel</a>
					<legend class="pkheader">Annual Appraisal Report ({{ totalItems}}) </legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Track Status of Annual Appraisal Report </legend>
							 <div class="col-md-7">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-6 col-form-label">Annual Appraisal Year of </label>
									<div class="col-sm-6">
										<select ng-model="searchYear" name="searchYear"  id="searchYear" class="form-control input-sm">
										  <?php
                                          $yr=date("Y")-1;
                                          for ($j=$yr;$j>=2014;$j--){
                                          if ($j == $yr){
                                         ?>
                                          <option value="<?php echo $j.'-'.($j+1);?>" ng-selected="{{searchYear == '<?php echo $j.'-'.($j+1);?>'}}" ><?php echo $j.'-'.($j+1);?></option>
                                         <?php }else{?>
                                         <option value="<?php echo $j.'-'.($j+1);?>" ng-selected="{{searchYear == '<?php echo $j.'-'.($j+1);?>'}}" ><?php echo $j.'-'.($j+1);?></option>
                                         <?php }
                                         }?>
										</select> 
										<input type="hidden" ng-model="defaultSerachYear" value="<?php echo $yr.'-'.($yr+1);?>" />
									</div>
								</div>
							 </div>
							 <div class="col-md-3"></div>
							 <div class="col-md-2">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search" ng-click="advanceFilter();" /> 
							 </div>
						</div>
						<div class="table-responsive" >
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<th width="3%">#</th>
										<th width="15%">Name</th>
										<th width="12%">Emp. Code</th>
										<th width="15%">Designation</th>
										<th width="12%">Sec. A Score</th>
										<th width="12%">Sec. B Score</th>
										<th width="31%">Emp. Development</th>  
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getAnnualAppraisalReport | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td><a ng-href="<?php echo base_url(); ?>en/hr/general_readonly?id={{data.login_id}}" ng-bind="data.name;" ></a>  </td>
										<td>{{data.loginhandle}}</a> </td>
										<td>{{data.desg_name}}</td>
										<td>{{data.total_score_a | number : 2 }}</td> 
										<td>{{data.total_score_b  | number : 2 }}</td> 
										<td>{{data.action_plans}}</td>  
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="7" align="center">No records found</td> 
									</tr>
								</tbody>
							</table>
							<div class="row"> 
								<div class="col-md-2">
									<select ng-model="entryLimit" class="form-control input-sm">
										<option value="10" ng-selected="{{entryLimit == 10}}">10</option>
										<option value="20" ng-selected="{{entryLimit == 20}}">20</option>
										<option value="30" ng-selected="{{entryLimit == 30}}">30</option>
										<option value="40" ng-selected="{{entryLimit == 40}}">40</option>
										<option value="50" ng-selected="{{entryLimit == 50}}">50</option>
										<option value="100" ng-selected="{{entryLimit == 100}}">100</option>
										<option value="200" ng-selected="{{entryLimit == 200}}">200</option>
										<option value="300" ng-selected="{{entryLimit == 300}}">300</option> 
										<option value="400" ng-selected="{{entryLimit == 400}}">400</option> 
										<option value="500" ng-selected="{{entryLimit == 500}}">500</option> 
									</select>
								</div>
								<!-- <div class="col-md-3">Search:
									<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
								</div> -->
								<div class="col-md-6">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total resumes </h5>
								</div>
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