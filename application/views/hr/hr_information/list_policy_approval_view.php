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
				<a  class="btn btn-primary pull-right btn-sm" role="button" data-toggle="modal" data-target="#addPolicy" style="margin-right: 10px; margin-top: 4px;" target="_blank"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Employee Policy</a>
					<legend class="pkheader">All Policy Details({{ totalItems}})</legend> 
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
								<h5>Filtered {{ filtered.length }} of {{ totalItems}} total policies </h5>
							</div>
						</div>
						<div class="row well" id="listpayroll">
						<div class="table-responsive" >
							<table class="table table-striped table-bordered table-condensed table-hover">
								<thead>
									<tr class="info">
										<th width="10%">#</th>
										<th width="50%">Policy<a ng-click="sort_by('policy_title');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="20%">Create Date<a ng-click="sort_by('new_DATE');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="10%">Status<a ng-click="sort_by('policy_status');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th width="10%">Action</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getprofilelist | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>
											<div class="iCompassTip" style="text-align:left;" >
												<a href="" data-toggle="modal" data-target="#viewPolicy_{{data.policy_id}}">
													{{data.policy_title}} 
												</a>
											</div>
											<div class="modal fade" id="viewPolicy_{{data.policy_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal">&times;</button>
															<h4 class="modal-title" id="exampleModalLabel">Policy Content<div id="head" style="display:inline;"></div></h4>
														</div>
														<div class="modal-body">
															<div inner="{{data.policy_content}}"></div>	
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> 
														</div>
													</div>
												</div>
											</div>
										
										
										
										</td>
										<td>{{data.new_DATE}}</td>
										<td ng-if="data.policy_status == 0">Inactive</td> 
										<td ng-if="data.policy_status == 1">Active</td>
										<td><!--<a data-toggle="modal" data-target="#editLeave_{{data.policy_id}}" onclick="hello()"><img src="<?php echo base_url('assets/images/icon/edit.png'); ?>"></a> -->&nbsp;&nbsp;&nbsp;
										<a ng-click="delete_(data.policy_id)"><img src="<?php echo base_url('assets/images/icon/delete.gif'); ?>"></a></td>
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="6" align="center">No records found</td> 
									</tr>
								</tbody>
							</table>
						</div>
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
	<div  class="modal fade sqllll"  ng-repeat="data in getprofilelist" id="editLeave_{{data.policy_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Edit Office Employee Policy</h4>
				</div>
				<form class="edit_holiday" method="POST" action="<?= base_url('en/hr/list_holiday') ?>">
					<div class="modal-body">
						<div class="successMsg piMSG"></div>
						<div class="srlst">
							<div class="row">
								<label for="staticEmail" class="col-sm-12 col-form-label">Policy Title</label>
								<div class="col-sm-12">
									<input type="text" name="policy_title" id="policy_title_{{data.policy_id}}" value="{{data.policy_title}}" class="form-control" ></textarea>
								</div>
							</div>	
							<br/>			
						<input type="hidden" value="{{data.policy_id}}" name="policy_id" id="policy_id_{{data.policy_id}}">				
							<div class="row">
								<label for="staticEmail" class="col-sm-12 col-form-label">Policy Content</label>
								<div class="col-sm-12">
									<textarea name="editor2{{data.policy_id}}" id="policy_content_{{data.policy_id}}"  class="form-control " >{{data.policy_content}}</textarea>
									
								   <script type="text/javascript">
									/* var id = $('#policy_id_').val();
									  CKEDITOR.replace( 'editor2'+id );
									  CKEDITOR.add  */           
								   </script>
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
	<div  class="modal fade " id="addPolicy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Add Office Employee Policy</h4>
				</div>
				<form id="add_policy" method="POST" action="<?= base_url('en/hr/add_policy_approval') ?>">
					<div class="modal-body">
						<div class="successMsg piMSG"></div>
						<div class="srlst">
							<div class="row">
								<label for="staticEmail" class="col-sm-12 col-form-label">Policy Title</label>
								<div class="col-sm-12">
									<input type="text" name="policy_title" id="policy_title" class="form-control" required="" >
								</div>
							</div>	
							<br/>							
							<div class="row">
								<label for="staticEmail" class="col-sm-12 col-form-label">Policy Content</label>
								<div class="col-sm-12">
									<textarea id="txtEditor" class="form-control ckeditor" name="txtMessage" rows="10" cols="120" required=""></textarea> 
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

<script src="https://cdn.ckeditor.com/4.9.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace( 'description',
    {
        toolbar : 'Basic',
        uiColor : '#9AB8F3'
    }); 
</script>
<script>

	function hello(){
		$('.datepickerShow').datepicker({
				dateFormat: 'dd-mm-yy'
		});
	}
	
	var frm = $('#add_policy');
	var site_url = "<?= base_url(); ?>";
	
	
    //frm.submit(function (e) {
    function addHRPolicy() {
		CKEDITOR.instances.SurveyBody.updateElement();
       // e.preventDefault();
	   //alert(CKEDITOR.instances['content'].getData());
	   //alert($('#policy_title').val());
		if($('#policy_title').val() !=""){
			$.ajax({
				type: frm.attr('method'),
				url: site_url+'en/hr/add_policy_approval',
				data: frm.serialize(),
				success: function (data) {
					console.log(data);
					//$('.piMSG').html('<h3>Policy is successfully Added</h3>');
					//$('.srlst').hide();
					//setTimeout(function(){ window.location.reload(1); }, 3000);
				},
				error: function (data) {
					console.log('An error occurred.');
					console.log(data);
				}
			});
		}
    }
	
	
	function delete_(val){
		alert(val);
		$.ajax({
			type:'POST',
			url:site_url+'en/hr/delete_policy_approval',
			data:{id:val},
			success: function(data){
				console.log(data);
				setTimeout(function(){ window.location.reload(1); }, 1000);	
			}
		})
	}

	
</script>