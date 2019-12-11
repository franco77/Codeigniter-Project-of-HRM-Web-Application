<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="allEmployee" ng-init="init('<?php echo base_url() ?>')">
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Generate Salary(<small>Please select a month & year.</small>)</legend> 
					<div class="row well"> 
						<form id="frmPhoneAdd" name="frmPhoneAdd" method="POST" action="">
							<div class="box-body pad"> 
								<div class="form-group">
									<label class="col-md-4 control-label" for="name">Generate Salary For the Month of </label>  
									<div class="col-md-2"> 
										<select id="selMonth" name="selMonth" class="form-control" id="sel1">
											<option value="">Select</option>
											<option value="01" <?php if($this->input->post('selMonth') == '01') echo 'selected="selected"';?>>January</option>
											<option value="02" <?php if($this->input->post('selMonth') == '02') echo 'selected="selected"';?>>February</option>
											<option value="03" <?php if($this->input->post('selMonth') == '03') echo 'selected="selected"';?>>March</option>
											<option value="04" <?php if($this->input->post('selMonth') == '04') echo 'selected="selected"';?>>April</option>
											<option value="05" <?php if($this->input->post('selMonth') == '05') echo 'selected="selected"';?>>May</option>
											<option value="06" <?php if($this->input->post('selMonth') == '06') echo 'selected="selected"';?>>June</option>
											<option value="07" <?php if($this->input->post('selMonth') == '07') echo 'selected="selected"';?>>July</option>
											<option value="08" <?php if($this->input->post('selMonth') == '08') echo 'selected="selected"';?>>August</option>
											<option value="09" <?php if($this->input->post('selMonth') == '09') echo 'selected="selected"';?>>September</option>
											<option value="10" <?php if($this->input->post('selMonth') == '10') echo 'selected="selected"';?>>October</option>
											<option value="11" <?php if($this->input->post('selMonth') == '11') echo 'selected="selected"';?>>November</option>
											<option value="12" <?php if($this->input->post('selMonth') == '12') echo 'selected="selected"';?>>December</option> 
										</select>									  
									</div>
									<div class="col-md-2"> 
										<select id="selYear" name="selYear" class="form-control" id="sel1">
											<option value="">Select</option>
											<?php for($i=2014; $i<=(date('Y'));$i++){ ?>
											<option value="<?php echo $i;?>" <?php if($this->input->post('selYear') == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
											<?php } ?> 
										</select>									  
									</div>
									<div class="col-md-2">
										<input type="submit" id="btnAddPhone" name="btnAddPhone" class="btn btn-info pull-right" value="GENERATE" <?php if($this->config->item('generateSalaryFreeze') == 'YES'){ echo "disabled"; } ?> /> 
									</div>
									<div class="col-md-12">
										<?php if($successMsg !=""){ ?>
										<div class="alert alert-success" role="alert"> <?php echo $successMsg; ?> </div>
										<?php } ?>
									</div>
                                    
                                    
                                    <!------ Employee  data display start------>
                    <div class="clear-fix"> </div>  
                    <div class="form-group row"></div>              
                    <div class="col-md-12">					
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<th>#</th>
										<th>Name<a ng-click="sort_by('name');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Employee Code<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Email<a ng-click="sort_by('email');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Designation<a ng-click="sort_by('desg_name');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Status</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getprofilelist | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td><a ng-href="<?php echo base_url(); ?>en/hr/salary_slip?id={{data.login_id}}" ng-bind="data.name;" ></a> </td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.email}}</td> 
										<td>{{data.desg_name}}</td>
										<td ng-if="data.user_status == 1"><span class="label label-success">Active</span></td> 
										<td ng-if="data.user_status != 1"><span class="label label-danger">Inactive</span></td> 
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="6" align="center">No records found</td> 
									</tr>
								</tbody>
							</table>
						
							<div class="row "> 
								<div class="col-md-2">
									<select ng-model="entryLimit" class="form-control input-sm">
										<option>10</option>
										<option>20</option>
										<option>30</option>
										<option>40</option>
										<option>50</option> 
									</select>
								</div>
								<!--<div class="col-md-3">Search:
									<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
								</div> -->
								<div class="col-md-5">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total resumes </h5>
								</div>
							</div>
						</div>
						
					</div> 
					<div ng-show="filteredItems > 0">    
						<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-sm" max-size="entryLimit" boundary-link-numbers="true" rotate="false" previous-text="&laquo;" next-text="&raquo;"></div> 
					
				</div>
                                    
                                    <!----- Employee data display end----------->
                                    
								</div> 
							</div> 
						</form>   
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
 

