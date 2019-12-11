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
				<a  class="btn btn-primary pull-right btn-sm" role="button" data-toggle="modal" data-target="#addHoliday" style="margin-right: 10px; margin-top: 4px;" target="_blank"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Holiday</a>
					<legend class="pkheader">All Holidays Details({{ totalItems}})</legend> 
					<div class="row well"> 
					
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Search/View Filter </legend>
							<div class="col-md-7">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Choose Year </label>
									<div class="col-sm-8">
										<select ng-model="searchyear" class="form-control">
											<?php
												$yr=date("Y");
												for ($j=$yr;$j>=2011;$j--)
												{ ?>
													<option value="<?php echo($j)?>"><?php echo($j); ?></option>
											<?php } ?>
										 </select>
									</div>
								</div>
							</div>
							
							 <div class="col-md-4">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary pull-right" value="Search" ng-click="advanceFilter();" /> 
							 </div>
							 <?php
							if($success_msg != '') { ?>
							<div class="col-md-12"><div class="alert alert-success" role="alert"> <?php echo $success_msg; ?> </div>
							<?php }?>
							<div id="successMsg"></div>
						</div>
					
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
							<div class="col-md-5">
								<h5>Filtered {{ filtered.length }} of {{ totalItems}} total holidays </h5>
							</div>
						</div>
						<div class="table-responsive" >
							<table class="table table-striped table-bordered table-condensed table-hover">
								<thead>
									<tr class="info">
										<th>#</th>
										<th>Event Name<a ng-click="sort_by('name');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Event Date<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Branch<a ng-click="sort_by('email');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Action</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getprofilelist | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.s_event_name}} </td>
										<td>{{data.new_DATE}}</td>
										<td ng-if="data.branch == 0">Normal</td> 
										<td ng-if="data.branch == 1">Bhubaneswar</td>
										<td ng-if="data.branch == 2">Noida</td>
										<td ng-if="data.branch == 4">Berhampur</td>
										<td><a data-toggle="modal" data-target="#editLeave_{{data.ix_declared_leave}}" onclick="hello()"><img src="<?php echo base_url('assets/images/icon/edit.png'); ?>"></a> &nbsp;&nbsp;&nbsp;<a ng-click="delete_(data.ix_declared_leave)"><img src="<?php echo base_url('assets/images/icon/delete.gif'); ?>"></a></td> 
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="6" align="center">No records found</td> 
									</tr>
								</tbody>
							</table>
						</div>
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
	<div  class="modal fade sqllll"  ng-repeat="data in getprofilelist" id="editLeave_{{data.ix_declared_leave}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Edit Office Holiday</h4>
				</div>
				<form class="edit_holiday" method="POST" action="<?= base_url('en/hr/list_holiday') ?>">
					<div class="modal-body">
						<div class="successMsg piMSG"></div>
						<div class="srlst">
						<div class="row">
							<label for="staticEmail" class="col-sm-2 col-form-label">Event Name </label>
							<div class="col-sm-5">
								<input type="text" value="{{data.s_event_name}}"  class="form-control" name="s_event_name">
							</div>
						</div>
						<input type="hidden" value="{{data.ix_declared_leave}}" name="ix_declared_leave">
						<br>
						<div class="row">
							<label for="staticEmail" class="col-sm-2 col-form-label">Event Date </label>
							<div class="col-sm-5">
								<input type="text" value="{{data.dt_event_date}}" name="dt_event_date"  class="form-control datepickerShow">
							</div>
						</div>
						<br>
						<div class="row">
							<label for="staticEmail" class="col-sm-2 col-form-label">Branch</label>
							<div class="col-sm-5">
								<select type="text" class="form-control" name="branch" >
									<option value="0" ng-selected="data.branch == 0" >Normal</option>
									<option value="2" ng-selected="data.branch == 2" >Noida</option>
									<option value="1" ng-selected="data.branch == 1" >Bhubaneswar</option>
									<option value="1" ng-selected="data.branch == 4" >Berhampur</option>
								<select>
							</div>
						</div>					
						</div>
					</div>
					<div class="modal-footer ">
						<button type="submit" data-dismiss="modal" class="btn btn-secondry" >Close</button>
						<input type="submit" name="editSubmit" onsubmit="editHoliday()" class="btn btn-primary srlst" value="SUBMIT">
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
					<h4 class="modal-title" id="exampleModalLabel">Add Office Holiday</h4>
				</div>
				<form id="add_holiday" method="POST" action="<?= base_url('en/hr/add_list_holiday') ?>">
					<div class="modal-body">
						<div class="successMsg piMSG"></div>
						<div class="srlst">
						<div class="row">
							<label for="staticEmail" class="col-sm-2 col-form-label">Event Name </label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="s_event_name" id="s_event_name">
							</div>
						</div>
						<input type="hidden" name="ix_declared_leave">
						<br>
						<div class="row">
							<label for="staticEmail" class="col-sm-2 col-form-label">Event Date </label>
							<div class="col-sm-5">
								<input type="text" name="dt_event_date"  id="dt_event_date"  class="form-control datepickerShow">
							</div>
						</div>
						<br>
						<div class="row">
							<label for="staticEmail" class="col-sm-2 col-form-label">Branch</label>
							<div class="col-sm-5">
								<select type="text" class="form-control" name="branch" >
									<option value="0">Normal</option>
									<option value="2">Noida</option>
									<option value="1">Bhubaneswar</option>
									<option value="4">Berhampur</option>
								<select>
							</div>
						</div>					
						</div>
					</div>
					<div class="modal-footer ">
						<button type="button" data-dismiss="modal" class="btn btn-secondry" >Close</button>
						<input type="submit" name="addholidaySubmit"  class="btn btn-primary srlst" value="SUBMIT">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>

function hello(){
		$('.datepickerShow').datepicker({
				dateFormat: 'dd-mm-yy'
		});
	}
	
	var frm = $('#add_holiday');
	var site_url = "<?= base_url(); ?>";
    frm.submit(function (e) {
        e.preventDefault();
		if($('#s_event_name').val() !="" && $('#dt_event_date').val() !=""){
			$.ajax({
				type: frm.attr('method'),
				url: site_url+'en/hr/add_holiday',
				data: frm.serialize(),
				success: function (data) {
					console.log(data);
					$('.piMSG').html('<h3>Holiday is successfully Added</h3>');
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
	
	function delete_(val){
		$.ajax({
			type:'POST',
			url:site_url+'en/hr/delete_holiday',
			data:{id:val},
			success: function(data){
				console.log(data);
			}
		})
	}

	
</script>