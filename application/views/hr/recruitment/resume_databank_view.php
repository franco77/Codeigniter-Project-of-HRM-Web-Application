<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<style>
#appl_des tr,#appl_des td{
padding:2px 10px;
}
</style>
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
				<a  class="btn btn-primary pull-right btn-sm" role="button" ng-click="export_resume_databank()" style="margin-right: 10px; margin-top: 4px;" target="_blank"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp;Export</a>
					<legend class="pkheader">Resume Databank</legend>
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Filter Resume Databank by Job-Code wise/ Date wise</legend>
							<div class="col-md-6">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-5 col-form-label">Resume Type </label>
									<div class="col-sm-7">
										<select ng-model="searchResumeType" name="searchResumeType" onchange="resumeType_(this.value)" id="searchResumeType" class="form-control input-sm" onchange="resumeType()"> 
											<option value="applicants" >Solicited</option> 
                                            <option value="unsolicited" >Unsolicited</option> 
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row   blsbns">
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
							<div class="col-md-6">
								<div class="form-group row">
									<label for="staticEmail"  class="col-sm-5 col-form-label">Skills</label>
									<div class="col-sm-7">
										<input type="text" ng-model="skills" name="skills"  id="skills" class="form-control"> 
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label for="staticEmail"  class="col-sm-5 col-form-label">Full Name</label>
									<div class="col-sm-7">
										<input type="text" ng-model="full_name" name="full_name"  id="full_name" class="form-control"> 
									</div>
								</div>
							</div>
							<div class="col-md-6 selHIDE">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-5 col-form-label">Current Designation</label>
									<div class="col-sm-7">
										<select ng-model="searchcurrdesignation"  id="searchcurrdesignation" class="form-control input-sm"> 
											<option value="" >Select</option>
											<?php foreach($cur_desgQry as $v110):?>
											<option value="<?php echo $v110['cur_designation'] ?>"><?php echo  $v110['cur_designation'] ?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6 selHIDE">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-5 col-form-label">Current Company</label>
									<div class="col-sm-7">
										<select ng-model="searchcurremployee"  id="searchcurremployee" class="form-control input-sm"> 
											<option value="" >Select</option>
											<?php foreach($cur_companyQry as $v12):?>
											<option value="<?php echo $v12['cur_company'] ?>"><?php echo $v12['cur_company'] ?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label for="staticEmail"  class="col-sm-5 col-form-label">Start Date </label>
									<div class="col-sm-7">
										<input type="text" ng-model="searchStartDate" name="searchStartDate"  id="searchStartDate" class="form-control input-sm datepickerShow"> 
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-5 col-form-label">End Date </label>
									<div class="col-sm-7">
										<input type="text" ng-model="searchEndDate" name="searchEndDate"  id="searchEndDate" class="form-control input-sm datepickerShow"> 
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-5 col-form-label">Contact Status</label>
									<div class="col-sm-7">
										<select ng-model="contact_status" name="contact_status"  id="contact_status" class="form-control"> 
											<option value="">Select</option>
											<option value="0">Not Contacted</option>
											<option value="1">Contacted</option>
										</select>
									</div>
								</div>
							</div>
							 <div class="col-md-6">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary pull-right" value="Search" ng-click="advanceFilter();" /> 
							 </div>
						</div>
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Email to the Candidate</legend>
							<div class="col-md-6">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-5 col-form-label">Email Category</label>
									<div class="col-sm-7">
										<select ng-model="email_category" onchange="getemailTemplate(this.value)" name="email_category" id="email_category" class="form-control input-sm" onchange="resumeType()"> 
											<option value="">Select</option>
											<option value="1" >Solicited</option> 
                                            <option value="2" >Unsolicited</option> 
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row" >
									<label for="staticEmail" class="col-sm-5 col-form-label">Email Template </label>
									<div class="col-sm-7">
										<select ng-model="email_template" name="email_template" id="email_template" class="form-control input-sm"> 
											<option value="" >Select</option>
											
										</select>
									</div>
								</div>
							</div>
							 <div class="col-md-12">
								<input type="button" class="btn btn-primary pull-right" onclick="get_view_()" value="View" /> 
								<input type="button" class="btn btn-primary pull-right" onclick="sendEmailtoSelected()" value="To Selected" style="margin-right: 10px;" /> 
								<input type="button" class="btn btn-primary pull-right" value="To All" onclick = "send_email_toALL()" style="margin-right: 10px;"  />
							 </div>
							 <div ng-modal="successmessage"></div>
						</div>
					<div class="row well">
						<div class="table-responsive">
						  <div id="checkboxes">	
    							<table class="table table-striped table-bordered table-condensed">
    								<thead>
    									<tr class="info">
    										<th></th>
    										<th width="20%">Name</th> 
    										<th width="25%">Current Designation</th>
    										<th width="15%">Telephone</th>
    										<th width="20%">Applied Postion</th> 
    										<th width="15%">H.Qualification</th>
    										<th width="5%">Resume</th> 
    										<th width="5%">No of time Contacted</th>
    										<th width="5%">Application Details</th>
    									</tr>
    								</thead>
    								<tbody id="filterData">
    									<tr ng-repeat="data in filtered = (getprofilelist | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
    										<td><input class="applicationID" type="checkbox" name="applicationID[]" value="{{data.email}}, {{data.full_name}}" ></td>
    										<td><a data-ids="{{data.appid}}" id="description"  data-toggle="modal" data-target="#myModal">{{data.full_name}}</a></td>
    										<td>{{data.cur_designation}}</td> 
    										<td>{{data.tel}}</td>
    										<td>{{data.post_title}}</td>
    										<td>{{data.highest_qualification}}</td>
    										<td align="center"><a ng-if="data.cv !=''" href="{{data.cv}}" download><img alt="Delete" src="<?php echo base_url(); ?>assets/images/icon/move.png" /></a></td>
    										<td><a data-idss="{{data.appid}}"  id="noOfTimess">{{data.noof_time}} times</a></td>
    										<td><a data-toggle="modal" data-target="#myModal_viewApp_{{data.appid}}" ><center><i class="fa fa-eye"></i></center></a></td>
    									</tr>
    									<tr ng-show="filteredItems == 0">
    										<td colspan="9" align="center">No records found</td> 
    									</tr>
    								</tbody>
    							</table>
							</div>
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
	<div  class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
						<select type="text" name="hr_rating" id="hr_rating" class= "form-control" required style="width:400px">
							<option value="">Select</option>                                                   
							<option value="1">1 (Poor)</option>
							<option value="2">2 (Average)</option>
							<option value="3">3 (Good)</option>
							<option value="4">4 (Very Good)</option>
							<option value="5">5 (Excellent)</option>
						</select>
						<input type="hidden" id="shortlist_id" name="appID">
						<h5>HR Description<span class ="red">*</span></h5>
						<textarea id="rm_desc" name="rm_desc" cols="80" rows="7" required></textarea>
					</div>
				</div>
				<div class="modal-footer srlst">
					<button type="submit" name="shortlistedApp" onclick="submit_(this.value)" value="0" class="btn btn-primary shortlistedApp" >Rejected</button>
					<button type="submit" name="shortlistedApp" onclick="submit_(this.value)"  value="1" class="btn btn-primary shortlistedApp" >Scheduled</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" ng-repeat="data in filtered = getprofilelist" id="myModal_viewApp_{{data.appid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Application Details <span style="margin-right: 30px"class="pull-right">{{data.date_diff}} Months ago...</span></h4>
				</div>
				<div class="modal-body">
					<table width="100%" id="appl_des" cellpadding = '10' border="1" bordercolor="#ececec">
						<tr>
							<td width="40%"><b>Full Name</b></td>
							<td width="60%">{{data.full_name}}</td>
						</tr>
						<tr>
							<td><b>Email</b></td>
							<td>{{data.email}}</td>
						</tr>
						<tr>
							<td><b>Phone Number</b></td>
							<td>{{data.tel}}</td>
						</tr>
						<tr>
							<td><b>Gender</b></td>
							<td ng-if="data.gender == 'M'">Male</td>
							<td ng-if="data.gender == 'F'">Female</td>
						</tr>
						<tr>
							<td><b>Marital Status</b></td>
							<td ng-if="data.maritial == 'S'">Single</td>
							<td ng-if="data.maritial == 'M'">Married</td>
						</tr>
						<tr>
							<td><b>Open for Relocation</b></td>
							<td>{{data.relocation}}</td>
						</tr>
						<tr>
							<td><b>Current Location</b></td>
							<td>{{data.location}}</td>
						</tr>
						<tr>
							<td><b>Total Experience</b></td>
							<td>{{data.exp}} Years</td>
						</tr>
						<tr>
							<td><b>Current Designation</b></td>
							<td>{{data.desg}}</td>
						</tr>
						<tr>
							<td><b>Current Employer</b></td>
							<td>{{data.employr}}</td>
						</tr>
						<tr>
							<td><b>Current Annual CTC</b></td>
							<td>{{data.cctc}}</td>
						</tr>
						<tr>
							<td><b>Expected Annual CTC</b></td>
							<td>{{data.ectc}}</td>
						</tr>
						<tr>
							<td><b>Notice Period</b></td>
							<td>{{data.nperiod}}</td>
						</tr>
						<tr>
							<td><b>Earliest joining Date</b></td>
							<td>{{data.ejoingdate}}</td>
						</tr>
						<tr>
							<td><b>Highlest Qualification</b></td>
							<td>{{data.high_qual}}</td>
						</tr>
						<tr>
							<td><b>Year of Passing</b></td>
							<td>{{data.year_of_pass}}</td>
						</tr>
						<tr>
							<td><b>Specialization</b></td>
							<td>{{data.spclization}}</td>
						</tr>
						<tr>
							<td><b>Institution Name</b></td>
							<td>{{data.instName}}</td>
						</tr>
						<tr>
							<td><b>Key Skills</b></td>
							<td>{{data.keySkills}}</td>
						</tr>
						<tr>
							<td><b>Have you ever been interviewed by AABSyS</b></td>
							<td>{{data.inrviewd}}</td>
						</tr>
						<tr>
							<td><b>Have you ever been employed by AABSyS</b></td>
							<td>{{data.employedbefr}}</td>
						</tr>
						<tr>
							<td><b>Cover Note</b></td>
							<td>{{data.coverNote}}</td>
						</tr>
						<tr>
							<td><b>Emoployee Reference</b></td>
							<td>{{data.employeeR}}</td>
						</tr>
						<tr>
							<td><b>HR Rating</b></td>
							<td>{{data.hr_rating}}</td>
						</tr>
						<tr>
							<td><b>HR Description</b></td>
							<td>{{data.hr_desc}}</td>
						</tr>
						
					</table>
				</div>
				<div class="modal-footer srlst">
					<button type="submit" name="shortlistedApp" data-dismiss="modal" class="btn btn-primary shortlistedApp" >Close</button>
				</div>
			</div>
		</div>
	</div>
	<div  class="modal fade" ng-repeat="data in filtered = getprofilelist" id="no_of_times_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Email History </h4>
				</div>
				<div class="modal-body">
					<table width="100%" cellpadding = '10' border="1" bordercolor="#ececec" id= 'appl_des'>
						<div id="noOOfTTime"></div>
					</table>
				</div>
				<div class="modal-footer ">
					<button type="submit" name="shortlistedApp" data-dismiss="modal" value="0" class="btn btn-primary shortlistedApp" >Close</button>
				</div>
			</div>
		</div>
	</div>

    <div  class="modal fade" ng-repeat="data in filtered = getprofilelist" id="email_view_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    		<div class="modal-dialog" role="document">
    			<div class="modal-content">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
    					<h4 class="modal-title" id="exampleModalLabel">Email template Content Details</h4>
    				</div>
    				<div class="modal-body">
    					<div class="row">
    					<div class="col-md-2">
    						<b>Email Template Content</b>
    					</div>
    					<div class="col-md-10">
    							<div id="emailVView"></div>
    					</div>
    					</div>
    				</div>
    				<div class="modal-footer" style="boder-top:none">
    					<button type="submit" name="shortlistedApp" data-dismiss="modal" value="0" class="btn btn-primary shortlistedApp" >Close</button>
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
var site_url = '<?php base_url()?>';
function submit_(val){
 var rm_desc = $('textarea#rm_desc').val();
  var hr_rating = $('#hr_rating').val();
  var appID = $('#shortlist_id').val();
  $('#messageSuccess').html('');
  $.ajax({
	  type:'POST',
	  data:{rm_desc:rm_desc,appID:appID,hr_rating:hr_rating,type:val},
	  url:site_url+'hr/resume_databank_submit_rating',
	  success:function(data){
		   $('#messageSuccess').html('<h4>Your Rating is Submtted Successfully</h4>');
		   $(".srlst").hide();
		   
		}
  });
}
 
 $( document ).ready(function() {
		$('.datepickerShow').datepicker({
				dateFormat: 'dd-mm-yy'
		});
		$('.datepickerShows').datepicker({
	        dateFormat: 'dd-mm-yy',
			onSelect: function(selected) {
				$(".datepickerShowe").datepicker("option","minDate", selected)
	        }
			});
			$('.datepickerShowe').datepicker({
				dateFormat: 'dd-mm-yy',
				onSelect: function(selected) {
				   $(".datepickerShows").datepicker("option","maxDate", selected)
				}
			});
		$('.selHIDE').hide();
	});

	function resumeType_(val){
		if(val == 'applicants'){
			$('.selHIDE').hide();
			$('.blsbns').show();
		} else if(val == 'unsolicited'){
			$('.selHIDE').show();
			$('.blsbns').hide();
		} 
	}

	document.getElementById('checkboxes').addEventListener('change', function(e) {
        var el = e.target;
        var inputs = document.getElementById('checkboxes').getElementsByTagName('input');
        
        if (el.id === 'all') {
            for (var i = 1, input; input = inputs[i++]; ) {
                input.checked = el.checked;
            }
        }
        else {
            var numChecked = 0;
            
            for (var i = 1, input; input = inputs[i++]; ) {
                if (input.checked) {
                    numChecked++;
                }
            }
            inputs[0].checked = numChecked === inputs.length - 1;
        }
    }, false);

    function getemailTemplate(val){
      $.ajax({
			type:'POST',
			url:site_url+'hr/getemailTemplate',
			data:{form_id:val},
			success: function(data){console.log(JSON.parse(data));
						var mydata = JSON.parse(data);
						var str = '<option value="">Select</option>';
						for(var i=0; i< mydata.length; i++){
							str +='<option value="'+mydata[i].form_id+'">'+mydata[i].form_name+'</option>';
							}
						$('#email_template').html(str);
				}
			})
    }

    $(document).on('click','#noOfTimess',function(){
    	var shrtlistID = $(this).data('idss');
    	$.ajax({
        	type:'POST',
        	url:site_url+'hr/getEmailDetails',
			data:{appID:shrtlistID},
			success: function(data){
						console.log(data);
						var str = '<tr><th width="10%">Sl. No.</th><th width="40%">Email Template Name</th><th width="30%">Email Date</th><th width="20%">Remarks</th></tr>';
						var z = 1;
						if(data.length > 0 ){
    						for(var i = 0;i < data.length;i++){
    							str +='<tr>'+
    									'<td>'+z+'</td>'+
    									'<td>'+data[i].email_template_name+'</td>'+
    									'<td>'+data[i].email_date+'</td><td>';
    								if(data[i].email_remark != null){
    									str += data[i].email_remark; 
    								}
    								str += '</td></tr>'; 
    								z++;		
    							}
						} else {
							str += '<td colspan="4"><center><br><b>No data found</b></center></td>';
							}
						$('#noOOfTTime').html(str);
						$('#no_of_times_modal').modal('toggle');	
					}
            	})
        })  
        
        
            
   	function get_view_(){
		var cid  = $('#email_category').val();
		var form_id = $('#email_template').val(); 
		$.ajax({
			type:'POST',
			data:{cid:cid,form_id:form_id},
			url:site_url+'hr/get_email_view',
			success : function(data){
						var mydata = JSON.parse(data);
						console.log(mydata[0].content); 
						$('#emailVView').html(mydata[0].content);
						$('#email_view_data').modal('toggle');
				}	
    	})
   	}

   	function sendEmailtoSelected(){
       		var cid  = $('#email_category').val();
    		var form_id = $('#email_template').val();
    		var paramsToSend = {};
    		var i = 1;
			$("input[name='applicationID[]']").each(function(key, val){
				if(this.checked) 
				{
					paramsToSend[i] = $(this).val();
					i++;
				}
			  });
    		 
    		 $.ajax({
    	         type: "POST",
    	         url: site_url+'hr/sendemailToSelected',
    	         data: {params:JSON.stringify(paramsToSend), cid: cid, form_id: form_id},
    	         success: function(data) {
    	          		console.log(data);
    	         }
   	   	});
   	}

   	function send_email_toALL(){
       		var cid  = $('#email_category').val();
    		var form_id = $('#email_template').val();
    		var paramsToSend = {};
    		var i = 1;
			$("input[name='applicationID[]']").each(function(key, val){
					paramsToSend[i] = $(this).val();
					i++;
			  });
    		 
    		 $.ajax({
    	         type: "POST",
    	         url: site_url+'hr/sendemailToSelected',
    	         data: {params:JSON.stringify(paramsToSend), cid: cid, form_id: form_id},
    	         success: function(data) {
    	          		console.log(data);
    	         }
   	   	});
   	}	
       
</script>

