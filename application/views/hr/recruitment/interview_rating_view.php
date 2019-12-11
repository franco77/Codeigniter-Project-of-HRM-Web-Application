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
					<legend class="pkheader">Interview Rating Candidate</legend>
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
										<td><a data-ids="{{data.appid}}" id="description"  >{{data.first_name}} {{data.last_name}}</a></td>
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
					<h4 class="modal-title" id="exampleModalLabel">Rating Details</h4>
				</div>
				<div class="modal-body">
					<input id = "shortlist_id" name="appID" type="hidden">
					<table width="100%">
						<div id="get_data_"></div>
					</table>
				</div>
				<div class="green messageSuccess"></div>
				<div class="modal-footer srlst">
					<button type="submit" name="shortlistedApp" onclick="submit_()" value="0" class="btn btn-primary shortlistedApp" >Save</button>
				</div>
			</div>
		</div>
	</div>
</div>


<script>


	$(document).on('click','#description',function(){
		$(".srlst").show();
		var shrtlistID = $(this).data('ids');
		$('#myModal').modal('toggle');
		$('#messageSuccess').html('');
		$(".modal-body #shortlist_id").val( shrtlistID );
		$.ajax({
			type:'POST',
			data:{id:shrtlistID},
			url:site_url+'en/hr/get_interview_candidate_info',
			success:function(data){
				var obj = JSON.parse(data);
				console.log(data);
				//var obj = data;
				var hr_rating = '';
				if(obj[0]['hr_rating'] ==1){
					hr_rating = '1 (Poor)';
				}
				else if(obj[0]['hr_rating'] ==2){
					hr_rating = '2 (Average)';
				}
				else if(obj[0]['hr_rating'] ==3){
					hr_rating = '3 (Good)';
				}
				else if(obj[0]['hr_rating'] ==4){
					hr_rating = '4 (Very Good)';
				}
				else if(obj[0]['hr_rating'] ==5){
					hr_rating = '5 (Excellent)';
				}
				var str = '<tr><td style="width:200px">HR Rating</td><td>'+hr_rating+'</td></tr>';
				 str +=		'<tr><td>HR Description</td><td>'+obj[0]['hr_desc']+'</td></tr>';
				 str +=		'<tr><td>Interview Date</td><td>'+obj[0]['interview_date']+'</td></tr>';
				 str +=		'<tr><td>Interview Description</td><td>'+obj[0]['interview_desc']+'</td></tr>';
				 str +=		'<tr><td>Interviewer</td><td>';
				if(obj[0]['interviewer'] != null){
						 str += obj[0]['interviewer'];
				}	
				 str +='</td></tr>';
				 str +='<tr><td>Rept. Manager Rating</td><td>';
				if(obj[0]['rm_rating'] != null){
					str+= obj[0]['rm_rating']; }
				str +=     '</td></tr>';
				 str +=		'<tr><td>Rept Manager Description</td><td>';
				if(obj[0]['rm_rating'] != null){
				  str+=  obj[0]['rm_desc']; }
				str += '</td></tr>';
				 str +=		'<tr><td>Dept Head Rating</td><td>';
				if(obj[0]['rm_rating'] != null){
							str+=  obj[0]['dh_rating']; }
				str +=	'</td></tr>';
				 str +=		'<tr><td>Dept Head Rating</td><td>';
				if(obj[0]['dh_desc'] != null){ 
						str+= obj[0]['dh_desc']; }
				str +=  '</td></tr>';
				 str +=		'<tr><td>Composite Head Rating</td><td>'+obj[0]['hr_rating']+'</td></tr>';
				 str +=		'<tr><td>Offer Letter Issue Date</td><td><input type="text"  name="issuedate"  id="issuedate" class="form-control input-sm datepickerShow srlst" style="width:200px"></td></tr>';
					$('#get_data_').html(str);
						$('.datepickerShow').datepicker({
							 dateFormat: 'dd-mm-yy'
						});
				console.log(str);	
			}
	  });
	});
	 
 function submit_(){
	  var issuedate = $('#issuedate').val();
	  var appID = $('#shortlist_id').val();
	if($('#issuedate').val() !=""){ 
	  $.ajax({
		  type:'POST',
		  data:{appID:appID,issuedate:issuedate},
		  url:site_url+'en/hr/submit_offer_letter_issue_date',
		  success:function(data){
			   $('.messageSuccess').html('<h4><center>Your Rating is Submited Successfully</center></h4>');
			   $(".srlst").hide();
			   
			}
	  });
	}
 }
</script>

