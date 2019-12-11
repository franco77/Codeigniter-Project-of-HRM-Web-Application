<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="empattendancesummary" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<a  class="btn btn-primary pull-right " role="button" style="margin-right: 10px; margin-top: 4px;" target="_blank" ng-click="exportAttendance()"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Export Attendance</a>
					
					<a  class="btn btn-primary pull-right " role="button" style="margin-right: 10px; margin-top: 4px;" target="_blank" ng-click="exportToExcel()"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Export To Excel</a>
					<legend class="pkheader">Employee Attendance Summary<small>( For both Daily/Monthly Data)</small></legend>
					<div class="row well">
							<div class="row pkdsearch">
								<div class="col-md-2">Month:
									<select ng-model="searchMonth" name="dd_month" class="form-control input-sm">
										<?php
										$dd_month = date('m');
										foreach($monthArray AS $key => $val){
											if($dd_month == $key){
												echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
											}else{
												echo '<option value="'.$key.'">'.$val.'</option>';
											}
										}
										?>
									</select>
								</div>
								<div class="col-md-2">year:
									<select ng-model="searchYear" name="dd_year" class="form-control input-sm">
										<?php
										$yr=date("Y");
										for ($j=$yr;$j>=2011;$j--){
										if ($j == $yr){
										?>
										<option value="<?php echo($j)?>" selected><?php echo($j)?></option>
										<?php }else{?>
										<option value="<?php echo($j)?>"><?php echo($j)?></option>
										<?php }
										}?>
									</select>
								</div>
								<div class="col-md-3">Emp Code:
									<input type="text" placeholder="Search" name="emp_code"  ng-model="emp_code"class="form-control input-sm" required />
								</div> 
								<div class="col-md-3">
									<input type="submit" id="btnView" name="btnView" value="View" ng-click="advanceFilter();" class="btn btn-primary pull-right"/> 
								</div>
							</div>
					
					
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
									<th><div class="iCompassTip" title="Serial Number">Sl. No.</div></th>
									<th><div class="iCompassTip" title="Employee Code">Employee Code</div></th>
									<th><div class="iCompassTip" title="Date of Joining">Date of Joining</div></th>
									<th><div class="iCompassTip" title="Attended days">Attend. Days</div></th>
									<th><div class="iCompassTip" title="Regularize Days">Reg. Days</div></th>
									<th><div class="iCompassTip" title="Planned Leave">PL</div></th>
									<th><div class="iCompassTip" title="Sick Leave">SL</div></th>
									<th><div class="iCompassTip" title="Absent days">Absent Days</div></th>
									<th><div class="iCompassTip" title="Pay days">Pay Days</div></th>
									<th><div class="iCompassTip" title="Available Planned Leave">Av. PL</div></th>
									<th><div class="iCompassTip" title="Available Sick Leave">Av. SL</div></th>
									<th><div class="iCompassTip" title="Current Planned Leave">Cur. PL</div></th>
									<th><div class="iCompassTip" title="Current Sick Leave">Cur. SL</div></th>
									<th><div class="iCompassTip" title="Attendance Allowance">Attendance Pay</div></th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getempAttendance | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.join_date}}</td>
										<td>{{data.attndDays}}</td>
										<td>{{data.regDays}}</td>
										<td>{{data.PLDays}}</td>
										<td>{{data.SLDays}}</td>
										<td>{{data.absentdays}}</td>
										<td>{{data.payDays}}</td>
										<td>{{data.avlPL}}</td>
										<td>{{data.avlSL}}</td>
										<td>{{data.curPL}}</td>
										<td>{{data.curSL}}</td>
										<td>{{data.attndPay}}</td>			
									</tr>
								</tbody>
							</table>
							<div class="row "> 
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
								<div class="col-md-6">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total Attendance Summary </h5>
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