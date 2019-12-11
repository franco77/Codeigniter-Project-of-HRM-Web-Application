<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="officialholidays" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('resources/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<a href="<?= base_url('resources/general_resources');?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;">&nbsp;Back</a> 
					<legend class="pkheader">Official Holidays<small>(All official Holidays)</small></legend>
					<div class="row well"> 
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Track Official Holidays </legend>
							 <div class="col-md-7">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-6 col-form-label">Choose Year </label>
									<div class="col-sm-6">
										<select ng-model="searchYear" name="searchYear"  id="searchYear" class="form-control input-sm">
										  <?php
                                          $yr=date("Y");
                                          for ($j=$yr;$j>=2014;$j--){
                                          if ($j == $yr){
                                         ?>
                                          <option value="<?php echo $j;?>" ng-selected="{{searchYear == '<?php echo $j;?>'}}" ><?php echo $j;?></option>
                                         <?php }else{?>
                                         <option value="<?php echo $j;?>" ng-selected="{{searchYear == '<?php echo $j;?>'}}" ><?php echo $j;?></option>
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
							<table class="table table-striped table-hover table-bordered">
								<thead>
									<tr class="info">
										<th>#</th> 
										<th>Event Name&nbsp;<a ng-click="sort_by('s_event_name');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Event Date&nbsp;<a ng-click="sort_by('addressLine1');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Branch&nbsp;<a ng-click="sort_by('branch');"><i class="glyphicon glyphicon-sort"></i></a></th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.s_event_name}}</td>
										<td>{{data.dt_event_date | date: 'fullDate'}}</td>
										<td>{{data.branch == '0' ? 'Normal' : data.branch_name}}</td> 
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="4" align="center">No records found</td> 
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
								<!--<div class="col-md-3">Search:
									<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
								</div> -->
								<div class="col-md-5">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total update records</h5>
								</div>
							</div> 
						</div>
					</div>
					<div class="row">
						<div ng-show="filteredItems > 0">    
							<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-sm" max-size="entryLimit" boundary-link-numbers="true" rotate="false" previous-text="&laquo;" next-text="&raquo;"></div> 
						</div>
						<!-- /.col-lg-4 (nested) -->
						<div class="col-lg-8">
							<div id="morris-bar-chart"></div>
						</div>  
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>