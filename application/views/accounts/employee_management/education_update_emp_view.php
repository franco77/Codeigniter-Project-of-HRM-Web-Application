<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_id = "";
if (isset($_GET['id']))
{
	$get_id = "?id=".$_GET['id'];
}
echo '<input type="hidden" id="getURLIdVal" name="getURLIdVal" class="btn btn-sm btn-info" value="'.$get_id.'" />';
?>
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('accounts/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
				<?php $this->load->view('accounts/top_view');?>
					<a href="<?= base_url('en/accounts_admin/education_profile_readonly_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View</a>
					<legend class="pkheader">Education Update</legend>
					<div class="row well">
						<div class="table-responsive">
							<form id="frmEduUpdateNew" name="frmEduUpdate" method="POST" action="" enctype="multipart/form-data">
								<table class="table table-striped table-bordered table-condensed">
									<tbody>
										<tr>
											<td><span class="red">&nbsp;</span> <strong>Course:</strong></td>
											<td>
												<select id="ddl_course" name="ddl_course" class="form-control" onchange="changeCourse(this.value, this)" style="width:190px;">
													<option value="Graduation" >Graduation</option>
													<option value="Professional">Professional Qual.</option>
												</select>
											</td>
											<td>
												<div id="courseType">
												<select id="ddl_coursetype" name="ddl_coursetype" onchange="changeSpecialization(this.value,this)" class="form-control" style="width:120px;">
													 <option value="">Course Type</option>
													 <?php 
													for($l=0; $l < count($courseType); $l++) 
													{?>
														<option value="<?php echo $courseType[$l]->course_id; ?>"><?php echo $courseType[$l]->course_name; ?></option>	
													<?php } ?>
												</select>
												</div>
											</td>
											<td>
												<div id="specialization">                                            		
												</div>
											</td>
										</tr>
										<tr>
											<td><span class="red">*</span> <strong>Year of Passing:</strong></td>
											<td><input type="text" id="txtpassing_year" name="txtpassing_year" value="" class="required form-control" style="width:180px;" /></td>
											<td><span class="red">*</span> <strong>Percentage/CGPA:</strong></td>
											<td><input type="text" id="txtpercentage" name="txtpercentage" value="" class="required form-control" style="width:180px;" /></td>    
										</tr>
										<tr>
											<td><span class="red">*</span> <strong>Board/University:</strong></td>
											<td>
												<select id="selBorU" name="selBorU" class="required form-control" style="width:190px;">
													<option value="">Select</option>
													<?php 
													for($i=0; $i < count($bordRows); $i++) 
													{?>
														<option value="<?php echo $bordRows[$i]['board_university_id']; ?>"><?php echo $bordRows[$i]['board_university_name']; ?></option>	
													<?php } ?>
												</select>
											 </td>
											<td colspan="2">&nbsp;</td>
										</tr>
									</tbody>
								</table>
								<div class="row">
									<input type="submit" id="btnAddEdu" name="btnAddEdu" class="btn btn-sm btn-info pull-right" value="Add" />
								</div>
							</form>
						</div>
						<div class="clearfix"></div> 
						<br>
						<div class="table-responsive">
							<form id="frmEduUpdate1" name="frmEduUpdate" method="POST" action="" enctype="multipart/form-data">
								<?php   
								if(count($eduRows)>0){
									for($i=0; $i < count($eduRows); $i++) 
									{
										$j = $i+1;
									?>
								<table class="table table-striped table-bordered table-condensed">
									<tbody>
										<tr class="info">
											<td colspan="4" class="form_title">Education Info <?php echo $j;?></td>
										</tr>
										<tr>
											<td><span class="red">&nbsp;</span> <strong>Course:</strong></td>
											<td>
												<select id="ddl_course_<?php echo $eduRows[$i]['education_id']?>" onchange="changeCourseByidUpdate(this.value, this, <?php echo $eduRows[$i]['education_id']?>)" name="ddl_course_<?php echo $eduRows[$i]['education_id']?>"  class="form-control">
														<option value="Graduation"  <?php echo ($eduRows[$i]['course_type']=='Graduation' ) ?'selected':'';?> >Graduation</option>
														<option value="Professional" <?php echo ($eduRows[$i]['course_type']=='Professional' ) ?'selected':'';?>>Professional Qual</option>
												</select>
											 </td>
											<td>
												<div id="courseType_<?php echo $eduRows[$i]['education_id']?>">
												<select id="ddl_coursetype_<?php echo $eduRows[$i]['education_id']?>" onchange="changeSpecializationByidUpdate(this.value, this, <?php echo $eduRows[$i]['education_id']?>)" name="ddl_coursetype_<?php echo $eduRows[$i]['education_id']?>" class="form-control">
													 <option value="">Course Type</option>
													<?php 
													for($l=0; $l < count($eduRows[$i]['course_type_arr']); $l++) 
													{?>
														<option value="<?php echo $eduRows[$i]['course_type_arr'][$l]->course_id; ?>"  <?php echo ($eduRows[$i]['course_type_arr'][$l]->course_id == $eduRows[$i]['course_id'] ) ?'selected':'';?> ><?php echo $eduRows[$i]['course_type_arr'][$l]->course_name; ?></option>	
													<?php } ?>
												</select>
												</div>
											</td>
											<td>
												
												<div id="courseSpecialization_<?php echo $eduRows[$i]['education_id']?>">
												<?php if(count($eduRows[$i]['specialization_arr']) > 0){ ?>
												<select id="ddl_specialization_<?php echo $eduRows[$i]['education_id']?>" name="ddl_specialization_<?php echo $eduRows[$i]['education_id']?>" class="form-control">
													<?php 
													for($l=0; $l < count($eduRows[$i]['specialization_arr']); $l++) 
													{?>
														<option value="<?php echo $eduRows[$i]['specialization_arr'][$l]->specialization_id; ?>"  <?php echo ($eduRows[$i]['specialization_arr'][$l]->specialization_id == $eduRows[$i]['specialization_id'] ) ?'selected':'';?> ><?php echo $eduRows[$i]['specialization_arr'][$l]->specialization_name; ?></option>	
													<?php } ?>
												</select>
												<?php } ?>
												</div>
												
											</td>
										</tr>
										<tr>
											<td><span class="red">*</span> <strong>Year of Passing:</strong></td>
											<td><input type="text" id="txtpassing_year_<?php echo $eduRows[$i]['education_id']?>" name="txtpassing_year_<?php echo $eduRows[$i]['education_id']?>" value="<?php echo $eduRows[$i]['passing_year']?>" class="required form-control" style="width:180px;" /></td>
											<td><span class="red">*</span> <strong>Percentage/CGPA:</strong></td>
											<td><input type="text" id="txtpercentage_<?php echo $eduRows[$i]['education_id']?>" name="txtpercentage_<?php echo $eduRows[$i]['education_id']?>" value="<?php echo $eduRows[$i]['percentage']?>" class="required form-control" style="width:180px;" /></td>    
										</tr>
										<tr>
											<td><span class="red">*</span> <strong>Board/University:</strong></td>
											<td>
												<select id="selBorU_<?php echo $eduRows[$i]['education_id']?>" name="selBorU_<?php echo $eduRows[$i]['education_id']?>" class="required form-control" style="width:190px;">
													<?php 
													for($k=0; $k < count($bordRows); $k++) 
													{?>
														<option value="<?php echo $bordRows[$k]['board_university_id']; ?>" <?php echo ($bordRows[$k]['board_university_id'] == $eduRows[$i]['board_id'] )? 'selected' :'' ; ?>><?php echo $bordRows[$k]['board_university_name']; ?></option>	
													<?php } ?>
												</select>
											</td>
											<td colspan="2">&nbsp;</td>
										</tr>
									</tbody>
								</table>
								<div class="row submtSec"  style="margin-bottom: 20px;">
									<div class="msg-sec"></div>
									<div class="col-md-3 pull-right">
										<span class="pull-right">
											<input type="button" id="btnUpdateEdu_<?php echo $eduRows[$i]['education_id']?>" name="btnUpdateEdu_<?php echo $eduRows[$i]['education_id']?>" class="btn btn-sm btn-info" value="Update" onclick="UpdateEdu(this,'update',<?php echo $eduRows[$i]['education_id']?>)" />
											<input type="button" id="btnDeleteEdu_<?php echo $eduRows[$i]['education_id']?>" name="btnDeleteEdu_<?php echo $eduRows[$i]['education_id']?>" class="btn btn-sm btn-danger" value="Delete" onclick="UpdateEdu(this,'delete',<?php echo $eduRows[$i]['education_id']?>)" />
										</span>
									</div>
								</div>
									<?php }}?>
							</form>
						</div>
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>

<script type="text/javascript">
/*
function changeCourseByid(course)
{

 var id=course.id;
 var edu_id=id.substring(id.lastIndexOf("_")+1,id.lengh);
 var coursetype=course.value;

  $k.ajax({
   type: "POST",
   url: 'ajax/change_coursebyid.php',
   data: 'coursetype='+coursetype+'&edu_id='+edu_id,
   success: function(data) {
     //alert(data);
    $k("#courseType_"+edu_id).html(data);
   },
   error: function(e) {
     alert("There is somme error in the network. Please try later.");
   }
 }); 
}
function changeSpecializationByid(specialization)
{

 var id=specialization.id;
 var edu_id=id.substring(id.lastIndexOf("_")+1,id.lengh);
 var courseSpecialization=specialization.value;

  $k.ajax({
   type: "POST",
   url: 'ajax/change_specializationbyid.php',
   data: 'courseSpecialization='+courseSpecialization+'&edu_id='+edu_id,
   success: function(data) {
     //alert(data);
    $k("#courseSpecialization_"+edu_id).html(data);
   },
   error: function(e) {
     alert("There is somme error in the network. Please try later.");
   }
 }); 
}
function UpdateEdu(btn,action)
{
 var id=btn.id;
        var action=action;
   var education_id=id.substring(id.lastIndexOf("_")+1,id.lengh);
    var course_id=$k("#ddl_coursetype_"+education_id).val();
        var specialization_id=$k("#ddl_specialization_"+education_id).val();
   var passing_year=$k("#txtpassing_year_"+education_id).val();
   var percentage=$k("#txtpercentage_"+education_id).val();
   var board_id=$k("#selBorU_"+education_id).val();
 
    $k.ajax({
      type: "POST",
      url: 'ajax/update_edu_emp.php',
      data: 'action='+action+'&education_id='+education_id+'&course_id='+course_id+'&specialization_id='+specialization_id+'&passing_year='+passing_year+'&percentage='+percentage+'&board_id='+board_id,
      success: function(data) {
       //alert(data);
      if(action=='update')
                                                    alert("Data Updated Succesfully.");
                                                if(action=='delete')
                                                    window.location.reload();
      },
      error: function(e) {
        alert("There is somme error in the network. Please try later.");
      }
    }); 
  
}
*/
</script>