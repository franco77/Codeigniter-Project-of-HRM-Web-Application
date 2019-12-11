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
					<legend class="pkheader">Interview Scheduled Candidate</legend>
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Interview Scheduled Candidate</legend>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-5 col-form-label">Resume Type </label>
									<div class="col-sm-7">
										<select ng-model="searchResumeType" name="searchResumeType" id="searchResumeType" class="form-control input-sm" onchange="resumeType()"> 
											<option value="applicants" >Solicited</option> 
                                            <option value="unsolicited" >Unsolicited</option> 
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row" id="resumeType">
									<label for="staticEmail" class="col-sm-5 col-form-label">Job Code </label>
									<div class="col-sm-7">
										<select ng-model="searchJobCode" name="searchJobCode" id="searchJobCode" class="form-control input-sm"> 
											<option value="" >Select</option>
											<?php for($i=0; $i<count($jobRow); $i++){ ?>
                                                <option value="<?php echo $jobRow[$i]['ID'];?>" ><?php echo 'AIT0'.$jobRow[$i]['ID'].' ('.$jobRow[$i]['post_title'].')';?></option>
                                                <?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail"  class="col-sm-5 col-form-label">Start Date </label>
									<div class="col-sm-7">
										<input type="text" ng-model="searchStartDate" name="searchStartDate"  id="searchStartDate" class="form-control input-sm datepickerShow"> 
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-5 col-form-label">End Date </label>
									<div class="col-sm-7">
										<input type="text" ng-model="searchEndDate" name="searchEndDate"  id="searchEndDate" class="form-control input-sm datepickerShow"> 
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
										<th width="25%">Email</th>
										<th width="15%">Telephone</th>
										<th nowrap  width="20%">Applied Postion</th> 
										<th width="15%">H.Qualification</th>
										<th width="5%">Resume</th> 
									</tr>
								</thead>
								<tbody id="filterData">
									<tr ng-repeat="data in filtered = (getprofilelist | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td><a data-ids="{{data.appid}}" id="description"  data-toggle="modal" data-target="#myModal">{{data.first_name}} {{data.last_name}}</a></td>
										<td>{{data.email}}</td> 
										<td>{{data.tel}}</td>
										<td>{{data.post_title}}</td>
										<td>{{data.highest_qualification}}</td>
										<td align="center"><a ng-if="data.cv !=''" href="{{data.cv}}" download><img alt="Delete" src="<?php echo base_url(); ?>assets/images/icon/move.png" /></a></td>
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
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">HR Rating</h4>
				</div>
				<div class="modal-body">
					<div class="successMsg" id="messageSuccess"></div>
					<div class="srlst">
						<h5>HR Rating<span class ="red">*</span></h5>
						<select type="text" name="hr_rating" id="hr_rating" class= "form-control" required style="width:400px" required="">
							<option value="">Select</option>                                                   
							<option value="1">1 (Poor)</option>
							<option value="2">2 (Average)</option>
							<option value="3">3 (Good)</option>
							<option value="4">4 (Very Good)</option>
							<option value="5">5 (Excellent)</option>
						</select>
						<input type="hidden" id="shortlist_id" name="appID">
						<h5>HR Description<span class ="red">*</span></h5>
						<textarea id="rm_desc" name="rm_desc" cols="80" rows="7" required=""></textarea>
					</div>
				</div>
				<div class="modal-footer srlst">
					<button type="submit" name="shortlistedApp" onclick="submit_(this.value)" value="0" class="btn btn-primary shortlistedApp" >Rejected</button>
					<button type="submit" name="shortlistedApp" onclick="submit_(this.value)"  value="1" class="btn btn-primary shortlistedApp" >Scheduled</button>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	$(document).on('click','#description',function(){
		var shrtlistID = $(this).data('ids');
		$(".modal-body #shortlist_id").val( shrtlistID );
		$('#messageSuccess').html('');
		$(".srlst").show();
		$('textarea#rm_desc').val('');
		$('#hr_rating').val('');
	});
	 
 function submit_(val){
	var rm_desc = $('textarea#rm_desc').val();
	var hr_rating = $('#hr_rating').val();
	var appID = $('#shortlist_id').val();
	$('#messageSuccess').html('');
	if($('textarea#rm_desc').val() !="" && $('#hr_rating').val() !=""){
	  $.ajax({
		  type:'POST',
		  data:{rm_desc:rm_desc,appID:appID,hr_rating:hr_rating,type:val},
		  url:site_url+'en/hr/interview_scheduled_candidate_rating',
		  success:function(data){
			   $('#messageSuccess').html('<h4>Your Rating is Submited Successfully</h4>');
			   $(".srlst").hide();
			   
			}
	  });
	}
 }
</script>

