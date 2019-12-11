<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="roomBookingList" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">Online Conference Room Booking</legend>
						<div class="row pkdsearch">
							<legend class="form_title col-md-12">View / Search</legend>
							
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticMonth" class="col-sm-4 col-form-label">Month </label>
									<div class="col-sm-8">
										<select id="searchMonth" name="searchMonth" ng-model="searchMonth" class="required form-control form-control " > 
											<option value="01" >January</option>
											<option value="02" >February</option>
											<option value="03" >March</option>
											<option value="04" >April</option>
											<option value="05" >May</option>
											<option value="06" >June</option>
											<option value="07" >July</option>
											<option value="08" >August</option>
											<option value="09" >September</option>
											<option value="10" >October</option>
											<option value="11" >November</option>
											<option value="12" >December</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Year </label>
									<div class="col-sm-8">
										<select id="searchYear" name="searchYear" ng-model="searchYear"  class="search_UI form-control " >
											<?php
											$yr=date("Y");
											for ($j=$yr;$j>=2011;$j--){ ?>
												<option value="<?php echo($j)?>"><?php echo($j)?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							 <div class="col-md-2">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search" ng-click="advanceFilter();" /> 
							 </div>
						</div>
					<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info"> 
										<th width="20%">Name</th> 
										<th width="20%">EMP Code</th> 
										<th width="20%">Room Name</th>
										<th width="25%">Book Date & Time</th>
										<th nowrap  width="15%">Action</th> 
									</tr>
								</thead>
								<tbody id="filterData">
									<tr ng-repeat="data in filtered = (getprofilelist | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td><a  ng-href="<?php echo base_url(); ?>en/hr/general_readonly?id={{data.login_id}}" ng-bind="data.full_name;"  ></a></td>
										<td>{{data.loginhandle}}</td> 
										<td>{{data.room_name}}</td> 
										<td>{{data.book_date}} {{data.book_time}}</td>
										<td ng-if="data.status == 'P'" align="center">
											<div class="iCompassTip" >
												<img  class="leaveApprove" src="<?php echo base_url(); ?>assets/images/icon/approve.png" alt="" title="Approve" ng-click="statusApporved(data.id);" style="cursor: pointer;" /> &nbsp;&nbsp;&nbsp;
												<img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/reject.png" alt="" title="Reject" ng-click="statusRejected(data.id);" style="cursor: pointer;"  />
											</div>
										</td>
										<td ng-if="data.status == 'A'">Approved</td>
										<td ng-if="data.status == 'R'">Rejected</td>
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="5" align="center">No records found</td> 
									</tr>
								</tbody>
							</table>
							<div class="row" ng-show="filteredItems > 0"> 
								<div class="col-md-2">
									<select ng-model="entryLimit" class="form-control input-sm">
										<option ng-selected="entryLimit">10</option>
										<option>20</option>
										<option>30</option>
										<option>40</option>
										<option>50</option> 
									</select>
								</div>
								<div class="col-md-3">
									<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
								</div> 
								<div class="col-md-5">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total employees </h5>
								</div>
							</div>
						</div>
						<!-- /.table-responsive -->
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



