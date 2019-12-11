<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="myOnlineMRF" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">Manpower Requisition Detail</legend>
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Search  View Manpower Requisition Filter </legend>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Department </label>
									<div class="col-sm-8">
										<select ng-model="searchDepartment" name="searchDepartment" id="searchDepartment" class="form-control input-sm" ng-change="designationFetch()"> 
											<option value="" >Select</option>
											<option ng-repeat="searchDepartment in getDepartments" ng-selected="{{ searchDepartment.selected == true }}" selected=""  value="{{ searchDepartment.dept_id }}">{{ searchDepartment.dept_name }}</option> 
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Name </label>
									<div class="col-sm-8">
										<input type="text" ng-model="searchName" name="searchName"  id="searchName" class="form-control input-sm"> 
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Designation </label>
									<div class="col-sm-8">
										<select ng-model="searchDesignation" name="searchDesignation" id="searchDesignation" class="form-control input-sm"> 
											<option value="" >Select</option>
											<option ng-repeat="searchDesignation in getDesignations" ng-selected="{{ searchDesignation.selected == true }}" selected=""  value="{{ searchDesignation.desg_id }}">{{ searchDesignation.desg_name }}</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Emp Code </label>
									<div class="col-sm-8">
										<input type="text" ng-model="searchEmpCode" name="searchEmpCode"  id="searchEmpCode" class="form-control input-sm"> 
									</div>
								</div>
							</div>
							 <div class="col-md-2">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search" ng-click="advanceFilter();" /> 
							 </div>
						</div>
					<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead>
									<tr class="info"> 
										<th width="20%">Name</th>
										<th width="15%">Employee Code</th>			                
										<th width="20%">Position for</th> 
                                        <th width="10%">MRF Date</th>
                                        <th width="10%">DH Status</th> 
                                        <th width="10%">MRF Status</th>
                                        <th width="15%">Action</th>
									</tr>
								</thead>
								<tbody id="filterData">
									<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit"> 
										<td>{{data.full_name}}</td>
										<td>{{data.loginhandle}}</td>
										<td><a id="showDesignations" data-idss="{{data.mid}}" >{{data.desg_name}}</a></td>
										<td>{{data.mrf_apply_date}}</td>
										<td ng-if="data.dh_status == '1'">Approved</td>
										<td ng-if="data.dh_status == '2'">Reject</td> 
										<td ng-if="data.dh_status == '0'">Pending</td>
										<td ng-if="data.mrf_status == '1'">Open</td>
										<td ng-if="data.mrf_status == '2'">Rejected</td> 
										<td ng-if="data.mrf_status == '0'">Closed</td> 
                                        <td align="center" ng-if="data.dh_status == '0'">
											<a class="link" href="<?php echo base_url(); ?>hr_help_desk/online_mrf?id={{data.login_id}}&mid={{data.mid}}"  title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" alt="Edit" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
											<a href="<?php echo base_url(); ?>hr_help_desk/online_mrf_detail_all?id={{data.login_id}}&mid={{data.mid}}&action=approve" title="Approve" ><img alt="Delete" src="<?php echo base_url(); ?>assets/images/icon/approve.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
											<a href="<?php echo base_url(); ?>hr_help_desk/online_mrf_detail_all?id={{data.login_id}}&mid={{data.mid}}&action=reject" title="Reject" ><img alt="Delete" src="<?php echo base_url(); ?>assets/images/icon/reject.png" /></a>
										</td>
										<td ng-if="data.dh_status == '1'">Approved</td>
										<td ng-if="data.dh_status == '2'">Reject</td> 
									</tr> 
									<tr ng-show="filteredItems == 0"><td colspan="7" align="center">No records found</td></tr>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
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
	<!-- Modal -->
	<div class="modal fade" id="showDesignationsss" tabindex="-1" role="dialog" data-toggle="modal" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Manpower Requisition Form</h4>
				</div>
				<div class="modal-body">
					<table width="100%">
						<div id="departmentss"></div>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
.pkdheading {
	background: #000000b0;
    color: #fff;
    padding: 20px;
    text-align: center;
    height: 31px;
    line-height: 29px;
    font-size: 15px;
}
.pkdheading1 {
	background: #000000c7;
    color: #fff;
    text-align: center;
    height: 20px;
    line-height: 20px;
    font-size: 12px;
}
#departmentss td {
	padding:5px;
}
</style>

<script>
	
	var base_url = '<?= base_url(); ?>';
	$(document).on('click','#showDesignations',function(){  
		var mid = $(this).data('idss');
		var str = "";
		$.ajax({
			type:'POST',
			url:base_url+'en/hr/getDepartments_details',
			data:{mid:mid},
			success:function(data){
				console.log(data);
				data = JSON.parse(data);
				str += '<tr><td width="40%">Department</td><td width="60%">'+data[0].dept_name+'</td></tr>';
				str += '<tr><td>Position</td><td>'+data[0].desg_name+'</td></tr>';
				str += '<tr><td>Location</td><td>'+data[0].branch_name+'</td></tr>';
				str += '<tr><td>Reason for Recuritment</td><td>'+data[0].reason_recruitment+'</td></tr>';
				str += '<tr><td>No of Vacancies</td><td>'+data[0].no_vacancies+'</td></tr>';
				str += '<tr><td>Justification</td><td>'+data[0].justification+'</td></tr>';
				str += '<tr><td>Job Description</td><td>'+data[0].job_description+'</td></tr>';
				str += '<tr class="pkdheading"><td colspan="2"> Job Description</td></tr>';
				str += '<tr class="pkdheading1"><td colspan="2"> Essential</td></tr>';
				str += '<tr><td><b>Qualification</b></td><td>'+data[0].essential_qualification+'</td></tr>';
				str += '<tr><td><b>Length of Experience</b></td><td>'+data[0].essential_length_experience+'</td></tr>';
				str += '<tr><td><b>Kind of Experience</b></td><td>'+data[0].essential_kind_experience+'</td></tr>';
				str += '<tr><td><b>Any Other</b></td><td>'+data[0].essential_other+'</td></tr>';
				str += '<tr class="pkdheading1"><td colspan="2"> Desirable </td></tr>';
				str += '<tr><td><b>Qualification</b></td><td>'+data[0].desirable_qualification+'</td></tr>';
				str += '<tr><td><b>Length of Experience</b></td><td>'+data[0].desirable_length_experience+'</td></tr>';
				str += '<tr><td><b>Kind of Experience</b></td><td>'+data[0].essential_kind_experience+'</td></tr>';
				str += '<tr><td><b>Any Other</b></td><td>'+data[0].desirable_other+'</td></tr>';
				str += '<tr><td><b>MRF Apply Date </b></td><td>'+data[0].mrf_apply_date+'</td></tr>';
				str += '<tr><td><b>Time Period within which this requirement need to be fulfilled.</b></td><td>'+data[0].time_period+'</td></tr>';
				str += '<tr class="pkdheading1"><td colspan="2">Reason for Approved/Rejected</td></tr>';
				str += '<tr><td><b>Comments</b></td><td>'+data[0].dh_desc+'</td></tr>';
				str += '<tr><td><b>Dept. Head Approved/Rejected Date</b></td><td>'+data[0].dh_desc+'</td></tr>';
				$('#departmentss').html(str);
				console.log(str);
				$('#showDesignationsss').modal('toggle');
			}
		});
	});
</script>

