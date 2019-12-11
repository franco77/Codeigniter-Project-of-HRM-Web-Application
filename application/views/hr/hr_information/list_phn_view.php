<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="holidaylist" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9"> 
				<a  class="btn btn-primary pull-right btn-sm" role="button" data-toggle="modal" data-target="#addHoliday" style="margin-right: 10px; margin-top: 4px;" target="_blank"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add</a>
					<legend class="pkheader">Polohrm Phone Directory({{ totalItems}})</legend> 
					<div class="row well"> 
					
						<div class="row pkdsearch"> 
						<?php
							if($success_msg != '') { ?>
							<div class="col-md-12"><div class="alert alert-success" role="alert"> <?php echo $success_msg; ?> </div></div>
							<?php }?>
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
							<div class="col-md-5">
								<h5>Filtered {{ filtered.length }} of {{ totalItems}} total employees </h5>
							</div>
						</div>
						
						<div class="table-responsive" >
							<table class="table table-striped table-bordered table-condensed table-hover">
								<thead>
									<tr class="info">
										<th>#</th>
										<th>Name</th>
										<th>Phone No</th>
										<th>Employee Code</th>
										<th>Action</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getprofilelist | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.name}} </td>
										<td>{{data.phone}} </td>
										<td>{{data.loginhandle}} </td>
										<td><a data-toggle="modal" class="selectEmployee" data-idss="{{data.login_id}}" data-target="#editphn{{data.id}}"><img src="<?php echo base_url('assets/images/icon/edit.png'); ?>"></a></td> 
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="6" align="center">No records found</td> 
									</tr>
								</tbody>
							</table>
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
	<div  class="modal fade sqllll"  ng-repeat="data in getprofilelist" id="editphn{{data.id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Edit Phone Number</h4>
				</div>
				<form method="POST" name="editPHND" action="<?= base_url('en/hr/edit_phn_submit') ?>">
					<div class="modal-body">
						<div class="successMsg piMSG"></div>
						<div class="srlst">
							<div class="row">
								<label for="staticEmail" class="col-sm-2 col-form-label">Name </label>
								<div class="col-sm-5">
									<input type="text" value="{{data.name}}"  class="form-control" name="name">
								</div>
							</div>
							<input type="hidden" value="{{data.id}}" name="id">
							<br>
							<div class="row">
								<label for="staticEmail" class="col-sm-2 col-form-label">Phone No</label>
								<div class="col-sm-5">
									<input type="text" value="{{data.phone}}" name="phone"  class="form-control">
								</div>
							</div>
							<br>
							<div class="row">
								<label for="staticEmail" class="col-sm-2 col-form-label">Choose Employee </label>
								<div class="col-sm-5">
									<select type="text" class="selectpicker form-control" data-live-search="true"  name="employee" >
										<option selected value="">Select Employee</option>
										<?php foreach($employee as $v1): ?>
											<option value="<?= $v1['login_id'] ?>"><?= $v1['full_name'] ?>(<?= $v1['loginhandle'] ?>)</option>
										<?php endforeach;?>
									</select>
								</div>
							</div>							
						</div>
					</div>
					<div class="modal-footer ">
						<button type="submit" data-dismiss="modal" class="btn btn-secondry" >Close</button>
						<input type="submit" name="editSubmit"  class="btn btn-primary srlst" value="SUBMIT">
					</div>
				</form>
			</div>
		</div>
	</div>
	<div  class="modal fade " id="addHoliday" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Add Phone Number</h4>
				</div>
				<form id="add_holiday" name="addPHND" method="POST" action="<?= base_url('en/hr/add_contact_details') ?>">
					<div class="modal-body">
						<div class="successMsg piMSG"></div>
						<div class="srlst">
							<div class="row">
								<label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" name="name" id="name">
								</div>
							</div>
							<br>
							<div class="row">
								<label for="staticEmail" class="col-sm-2 col-form-label">Phone No</label>
								<div class="col-sm-5">
									<input type="text" name="phone" id="phone"  class="form-control">
								</div>
							</div>
							<br>
							<div class="row">
								<label for="staticEmail" class="col-sm-2 col-form-label">Choose Employee </label>
								<div class="col-sm-5">
									<select type="text" class="selectpicker form-control" data-live-search="true" id="employee"  name="employee" >
										<option selected value="">Select Employee</option>
										<?php foreach($employee as $v1): ?>
											<option value="<?= $v1['login_id'] ?>"><?= $v1['full_name'] ?>(<?= $v1['loginhandle'] ?>)</option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<br>				
						</div>
					</div>
					<div class="modal-footer ">
						<button type="submit" data-dismiss="modal" class="btn btn-secondry" >Close</button>
						<input type="submit" name="addholidaySubmit"   class="btn btn-primary srlst" value="SUBMIT">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>

$(document).on('click','.selectEmployee',function(){
	var loginID = $(this).data('idss');
	$('.selectpicker').selectpicker('val',loginID);
});


	var frm = $('#add_holiday');
	var site_url = "<?= base_url(); ?>";
    frm.submit(function (e) {
        e.preventDefault();
		if($('#name').val() !="" && $('#phone').val() !="" && $('#employee').val() !=""){
			$.ajax({
				type: frm.attr('method'),
				url: site_url+'en/hr/add_contact_details',
				data: frm.serialize(),
				success: function (data) {
					console.log(data);
					$('.piMSG').html('<h4>Contact No is successfully Added</h4>');
					$('.srlst').hide();
					setTimeout(function(){ window.location.reload(1); }, 3000);
				},
				error: function (data) {
					console.log('An error occurred.');
					console.log(data);
				}
			});
		}
    });

	
</script>