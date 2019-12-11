<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">Interview Schedule Candidate Detail</legend>
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Interview Schedule Candidate Filter </legend>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-5 col-form-label">Resume Type </label>
									<div class="col-sm-7">
										<select name="searchResumeType" id="searchResumeType" class="form-control input-sm" onchange="resumeType()"> 
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
										<select name="searchJobCode" id="searchJobCode" class="form-control input-sm"> 
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
									<label for="staticEmail" class="col-sm-5 col-form-label">Start Date </label>
									<div class="col-sm-7">
										<input type="text" name="searchStartDate"  id="searchStartDate" class="form-control input-sm datepickerShow"> 
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-5 col-form-label">End Date </label>
									<div class="col-sm-7">
										<input type="text" name="searchEndDate"  id="searchEndDate" class="form-control input-sm datepickerShow"> 
									</div>
								</div>
							</div>
							 <div class="col-md-2">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search" onclick="advanceFilter(this);" /> 
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
									<?php 
									$result = count($rowMRF);
									if($result >0)
									{
										for($i=0; $i< $result; $i++)
										{?>
										<tr>
											<td><a data-id="<?= $rowMRF[$i]['id'] ?>" id="description"  data-toggle="modal" data-target="#myModal"><?php echo $rowMRF[$i]['first_name'].' '.$rowMRF[$i]['last_name'];?></a></td>
											<td><?php echo $rowMRF[$i]['email'];?></td>
											<td><?php echo $rowMRF[$i]['tel'];?></td>
											<td><?php echo $rowMRF[$i]['post_title'];?></td>
											<td><?php echo $rowMRF[$i]['highest_qualification'];?></td>
                                            <td align="center">
												<?php if($rowMRF[$i]['cv'] !=""){ ?>
												<a href="<?php echo $rowMRF[$i]['cv'];?>" download><img alt="Delete" src="<?php echo base_url(); ?>assets/images/icon/move.png" /></a></td>
												<?php } ?>
										</tr> 
										<?php } 
									}
									else{
									?>
										<tr><td colspan="6" align="center">No records found</td></tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
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
					<h4 class="modal-title" id="exampleModalLabel">Shortlisted Candidate</h4>
				</div>
				<div class="modal-body">
					<div class="successMsg" id="messageSuccess"></div>
					<div class="srlst">
						<h5>RM Rating<span class ="red">*</span></h5>
						<select type="text" class="form-control" name="rm_rating" id="rm_rating" style="width:200px">
							<option value="" selected disabled>Select</option>                                                 
							<option value="1">1 (Poor)</option>
							<option value="2">2 (Average)</option>
							<option value="3">3 (Good)</option>
							<option value="4">4 (Very Good)</option>
							<option value="5">5 (Excellent)</option>
						</select>
						<input type="hidden" id="shortlist_id" name="appID">
						<h5>RM Description<span class ="red">*</span></h5>
						<textarea id="rm_desc" name="rm_desc" cols="80" rows="7" required></textarea>
					</div>
				</div>
				<div class="modal-footer srlst">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
					<button type="submit" name="intervE" id="intervE" value="interVSchd" class="btn btn-primary" >Yes</button>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	$(document).on('click','#description',function(){
		$('#messageSuccess').html('');
		$(".srlst").show();
		var shrtlistID = $(this).data('id');
		$(".modal-body #shortlist_id").val( shrtlistID );
		$('textarea#rm_desc').val('');
		$('#rm_rating').val('');
		$('#shortlist_id').val('');
	});
	
	
$(document).on('click','#intervE',function(){
	  var rm_desc = $('textarea#rm_desc').val();
	  var rm_rating = $('#rm_rating').val();
	  var appID = $('#shortlist_id').val();
	  $('#messageSuccess').html('');
	if($('textarea#rm_desc').val() !="" && $('#rm_rating').val() !=""){
		  $.ajax({
			  type:'POST',
			  data:{rm_desc:rm_desc,rm_rating:rm_rating,appID:appID},
			  url:site_url+'hr_help_desk/interview_candidate_rating',
			  success:function(data){
				   $('#messageSuccess').html('<h4>Your Rating is Submited Successfully</h4>');
				   $(".srlst").hide();
				   
				}
		  });
	}
 });
</script>

