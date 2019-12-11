<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h4 class="section-title">
				<span class="c-dark">Events</span>
			</h4>
		</div>
	</div>
</div> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('news_and_events/left_sidebar');?>
					</div>
				</div>
				 <div class="col-md-9">
					<div class="form-content page-content">
						<div class="panel-default"> 
							<!-- /.panel-heading -->
							<div class="panel-body">
								<h3 class="box-title">Official Holidays<small>(All official Holidays)</small></h3> 
								<div class="well"> 
									<div class="row">
										<div class="col-md-2">PageSize:
											<select ng-model="entryLimit" class="form-control input-sm">
												<option>10</option>
												<option>20</option>
												<option>30</option>
												<option>40</option>
												<option>50</option>
												<option>70</option>
												<option>90</option>
												<option>120</option>
												<option>150</option>
											</select>
										</div>
										<div class="col-md-3">Search:
											<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
										</div> 
										<div class="col-md-2">Choose Year
											<select id="choose_year" name="choose_year" class="form-control input-sm" onChange="javascript:document.forms[0].submit();">
											<?php 
												$cYear = date("Y"); 
												for($y = 2009; $y <= $cYear; $y++){
													$rg_year[$y] = $y;
												}
												foreach($rg_year as $key => $val){
													$checkselect = ($choose_year == $key) ? 'selected' : '';
													echo('<option value="'.$key.'" '.$checkselect.'>'.$val.'</option>');
												}
											?>  
											</select>
										</div>
										<div class="col-md-3">
											<h5>Filtered {{ filtered.length }} of {{ totalItems}} total update records</h5>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="table-responsive" ng-show="filteredItems > 0">
											<table class="table table-striped table-bordered table-condensed">
												<thead>
													<tr> 
														<th>#</th>
														<th>Event Name</th>
														<th>Event Date</th>
														<th>Branch</th> 
													</tr>
												</thead>
												<tbody>
													<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
														<td>{{$index+1}}</td>
														<td>{{data.s_event_name}}</td>
														<td>{{data.dt_event_date | date: 'fullDate'}}</td>
														<td>{{data.branch == '0' ? 'Normal' : data.branch_name}}</td> 
													</tr>
												</tbody>
											</table>
										</div>
										<!-- /.table-responsive -->
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12" ng-show="filteredItems == 0">
										<div class="col-lg-12">
										<h4>Not found</h4>
										</div> 
									</div>
									<div class="col-lg-12" ng-show="filteredItems > 0">    
										<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div> 
									</div>
									<!-- /.col-lg-4 (nested) -->
									<div class="col-lg-8">
										<div id="morris-bar-chart"></div>
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