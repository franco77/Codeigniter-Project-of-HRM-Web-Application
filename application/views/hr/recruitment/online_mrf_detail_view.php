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
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="shortlistedList" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">Manpower Requisition Detail</legend>
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Manpower Requisition Filter </legend>
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
									<label for="staticEmail"  class="col-sm-4 col-form-label">Name </label>
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
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">MRF Status </label>
									<div class="col-sm-8">
										<select type="text" ng-model="mrf_status" name="mrf_status"  id="mrf_status" class="form-control input-sm"> 
											<option value="1">Open</option>
											<option value="0">Close</option>
											<option value="2" >Hold</option>
											
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									&nbsp;
								</div>
							</div>
							 <div class="col-md-2">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search"ng-click="advanceFilter();" /> 
							 </div>
						</div>
					<div class="row well" id="listpayroll">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info"> 
										<th width="15%">Name</th>
										<th width="15%">Employee Code</th>			                
										<th width="20%">Position for</th> 
                                        <th width="10%">No of Position</th>
                                        <th width="15%">MRF Date</th>
                                        <th width="15%">Action</th>
									</tr>
								</thead>
								<tbody id="filterData">
										<tr ng-repeat="data in filtered = (getprofilelist | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
											<td>{{data.name}}</td> 
											<td>{{data.loginhandle}}</td> 
											<td><a id="showDesignations" data-idss="{{data.mid}}" >{{data.desg_name}}</a></td> 
											<td>{{data.no_vacancies}}</td> 
											<td>{{data.mrf_apply_date}}</td> 
                                            <td ng-if="data.mrf_status == 1"><a data-ids="{{data.mid}}" id="mrf_status_btn">Click to Close/Hold</a></td>
                                            <!--<td ng-if="data.mrf_status == 0">MRF Closed</td>-->
											<td ng-if="data.mrf_status == 0"><a id="showMrfStatus" data-idss="{{data.mid}}" >MRF Closed</a></td> 
                                            <!--<td ng-if="data.mrf_status != '1' && data.mrf_status != '0'">MRF Hold</td> -->
											<td ng-if="data.mrf_status != '1' && data.mrf_status != '0'"><a id="showMrfStatus" data-idss="{{data.mid}}" >MRF Hold</a></td>
										</tr> 
										<tr ng-show="filteredItems == 0">
											<td colspan="6" align="center">No records found</td> 
										</tr>
								</tbody>
							</table>
							<div class="row"> 
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
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-toggle="modal" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">MRF Closed Detail</h4>
				</div>
				<form method="POST" id="mrf_close">
				<div class="modal-body">
					<div id="successmsg" class="successMsg"></div>
					<div id="hideSubmit">
						<div class="row">
							<div class="form-group row">
								<label for="staticEmail" class="col-sm-4 col-form-label">MRF Status <span class ="red">*</span></label>
								<div class="col-sm-5">
									<select type="text" required name="mrf_status"  id="mrf_status" class="form-control input-sm">
										<option value="0">Close</option>
										<option value="2" >Hold</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group row">
								<label for="staticEmail" class="col-sm-4 col-form-label">MRF Action Date<span class ="red">*</span> </label>
								<div class="col-sm-5">
									<input type="text" required name="mrf_action_date" class="form-control datepickerShow">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group row">
								<label for="staticEmail" class="col-sm-4 col-form-label">HR Description<span class ="red">*</span></label>
								<div class="col-sm-5">
									<textarea type="text" required name="hr_description" rows="4" class="form-control"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group row">
								<label for="staticEmail" class="col-sm-4 col-form-label">No of Resume Recieved<span class ="red">*</span></label>
								<div class="col-sm-5">
									<input type="text" required name="no_of_resume" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group row">
								<label for="staticEmail" class="col-sm-4 col-form-label">No of Candidate cleared HR Round<span class ="red">*</span></label>
								<div class="col-sm-5">
									<input type="text" required name="cleared_hr" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group row">
								<label for="staticEmail" class="col-sm-4 col-form-label">No of Candidate cleared Shortlisted<span class ="red">*</span></label>
								<div class="col-sm-5">
									<input type="text" required name="no_of_cand_cleard" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group row ">
								<label for="staticEmail" class="col-sm-4 col-form-label">No of Candidate Joined<span class ="red">*</span></label>
								<div class="col-sm-5">
									<input type="text" required name="no_of_cand_joind" class="form-control">
								</div>
							</div>
						</div>
						<input type="hidden" id="appID" required name="appID">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"  id="hideSubmitbtn">Submit</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="showDesignationsss" tabindex="-1" role="dialog" data-toggle="modal" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Manpower Requisition Form</h4>
				</div>
				<div class="modal-body">
					<table id="departmentss" width="100%">
						
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="showMrfStatusModal" tabindex="-1" role="dialog" data-toggle="modal" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">MRF Closed Detail</h4>
				</div>
				<div class="modal-body">
					<table width="100%" id="mrfstatusview">
						
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	
</div>

<script>
	$(document).on('click','#mrf_status_btn',function(){  
		var shrtlistID = $(this).data('ids');
		$(".modal-body #appID").val( shrtlistID );
		$('#hideSubmit').show();
		$('#hideSubmitbtn').show();
		$('#myModal').modal({backdrop: 'static', keyboard: false});
		$('#successmsg').html('');
	});
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
				var data = JSON.parse(data);
				str += '<tr class="pkdheading1"><td colspan="2"> Applier Details</td></tr>';
				str += '<tr><td>Name</td><td>'+data[0].full_name+'</td></tr>';
				str += '<tr><td>Employee Id</td><td>'+data[0].loginhandle+'</td></tr>';
				str += '<tr><td>Deaprtment Name</td><td>'+data[0].department_name+'</td></tr>';
				str += '<tr class="pkdheading"><td colspan="2"> MRF Details</td></tr>';
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
	var frm = $('#mrf_close');
    frm.submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: frm.attr('method'),
            url: base_url+'en/hr/close_mrf_form',
            data: frm.serialize(),
            success: function (data) {
                console.log('Submission was successful.');
                console.log(data);
				$('#hideSubmit').hide();
				$('#hideSubmitbtn').hide();
				$('#successmsg').html('<h4>Your form is Submitted Successfully<h4>');
				setTimeout(function() {
					location.reload();
				}, 3000);
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
            },
        });
    });
	
	$(document).on('click','#showMrfStatus',function(){  
		var mid = $(this).data('idss');
		var str = "";
		$.ajax({
			type:'POST',
			url:base_url+'en/hr/getDepartments_details',
			data:{mid:mid},
			success:function(data){
				console.log(data);
				var status = '';
				var data = JSON.parse(data);
				if(data[0].mrf_status == '0')
				{
					status = 'Closed';
				}else if(data[0].mrf_status == '2')
				{
					status = 'Hold';
				}
				str += '<tr><td width="40%">MRF Status</td><td width="60%">'+status+'</td></tr>';
				str += '<tr><td>MRF Action Date</td><td>'+data[0].mrf_close_date+'</td></tr>';
				str += '<tr><td>HR Description</td><td>'+data[0].hr_desc+'</td></tr>';
				str += '<tr><td>No of Resume Received</td><td>'+data[0].resume_receive+'</td></tr>';
				str += '<tr><td>No of Candidate cleared HR Round</td><td>'+data[0].clear_hr_round+'</td></tr>';
				str += '<tr><td>No of Candidate cleared Shortlisted</td><td>'+data[0].shortlisted+'</td></tr>';
				str += '<tr><td>No of Candidate Joined</td><td>'+data[0].joined+'</td></tr>';
				$('#mrfstatusview').html(str);
				console.log(str);
				$('#showMrfStatusModal').modal('toggle');
			}
		});
	});
	
	$( document ).ready(function() {
		$('.datepickerShow').datepicker({
				dateFormat: 'dd-mm-yy'
		});
	});
</script>


