<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<legend class="pkheader">Minimum Requirements</legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"></span>Add/Edit Minimum Requirements</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="frmUpdateCountry" name="frmUpdateCountry" method="POST">
										<div class="col-md-12">
											<div class="col-sm-6">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-4 col-form-label">Department: </label>
												<div class="col-sm-8">
													<select type="text" class="form-control" id="dept_id"  name="dept_id"  onchange="getDesgnation(this);" required="">
														<option selected value="">Select Department</option>
														<?php foreach($departments as $v1): ?>
															<option value="<?= $v1['dept_id'] ?>" ><?= $v1['dept_name'] ?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											</div>
											<div class="col-sm-6">
											<div class="form-group row">											
												<label for="staticEmail" class="col-sm-4 col-form-label">Designation: </label>
												<div class="col-sm-8">
													<select type="text" class="form-control desgSelections" id="desg_id"  name="desg_id"  required="" >
														<option selected value="">Select Designation</option>
														<?php foreach($designations as $v1): ?>
															<option value="<?= $v1['desg_id'] ?>" ><?= $v1['desg_name'] ?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											</div>
											<div class="col-sm-6">
											<div class="form-group row">											
												<label for="staticEmail" class="col-sm-4 col-form-label">Reference Type: </label>
												<div class="col-sm-8">
													<select id="ddl_reftype" name="ddl_reftype" class="required  form-control" onchange="getReference(this);">
														<option value="S" selected >Skills</option>
														<option value="E" >Education</option>
														<option value="W" >Work Experience</option>
													</select>
												</div>
											</div>
											</div>
											<div class="col-sm-6">
											<div class="form-group row">											
												<label for="staticEmail" class="col-sm-4 col-form-label">Reference: </label>
												<div class="col-sm-8">
													<select type="text" class=" form-control selectRef" id="ddl_ref"  name="ddl_ref[]" multiple  required="" >
														<?php foreach($references as $v1): ?>
															<option value="<?= $v1['skill_id'] ?>" ><?= $v1['skill_name'] ?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											</div>
										</div>
										 <div class="col-md-12 add_btn">
											<input type="submit" id="btn_add" name="btn_update" class="search_sbmt pull-right btn btn-primary" value="Add"/>
											<input type="hidden" id="required_id" name="required_id" value="0" />
										 </div>
										 <div class="col-md-12 successMsg" id="piMSG" style="text-align:center;"></div>
										
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="row well" id="listpayroll">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info"> 
										<th width="10%">Sl. No</th>
										<th width="15%">Department</th>
										<th width="20%">Designation</th>
										<th width="20%">Reference Type</th>
										<th width="25%">Reference</th>
										<th width="10%">Action</th> 
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($requirements as $v1): ?>
									<tr>
										<td><?= $i; ?></td>
										<td><?= $v1['dept_name'] ?></td>
										<td><?= $v1['desg_name'] ?></td>
										<td><?php if($v1['requirement_type'] =='S'){ echo 'Skills'; } else if($v1['requirement_type'] =='E'){ echo 'Education'; } else if($v1['requirement_type'] =='W'){ echo 'Work Experience'; } ?></td>
										<td><?php for($k=0; $k<count($v1['requirement_types']); $k++){
											echo $v1['requirement_types'][$k]['name'].'<br/>';
										} ?></td>
										<td><a onclick="delete_(<?= $v1['required_id'] ?>)"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <!--<a onclick="modify_('<?= $v1['required_id'] ?>','<?= $v1['dept_id'] ?>',<?= $v1['desg_id'] ?>,'<?= $v1['requirement_type'] ?>','<?= $v1['requirement_type_id'] ?>')"><i class="fa fa-pencil"></i></a>-->	</td>
									</tr>
									<?php $i++; endforeach; ?>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
					</div> 
					</div> 
					
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
<script type="text/javascript">
    var frm = $('#frmUpdateCountry');
	var site_url = "<?= base_url(); ?>";
    frm.submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: frm.attr('method'),
            url: site_url+'en/hr/add_emp_minrequirement',
            data: frm.serialize(),
            success: function (data) {
                console.log(data);
				$('#piMSG').html('Minimum Requirements submitted Successfully');
				 $("#listpayroll").load(" #listpayroll > *");
					$('.selectpicker').selectpicker('val','');
					$('#desg_name').val('');
					$('#desg_id').val('0');
					$('#btn_add').val('Add');
					setTimeout(function(){ $('#piMSG').html(''); }, 3000);
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
            },
        });
    });
	
	function delete_(dat){
		$.ajax({
			type: 'POST',
			url:site_url+'en/hr/delete_emp_minrequirement',
			data:{required_id:dat},
			success:function(data){
				console.log(data);
				 $("#listpayroll").load(" #listpayroll > *");	
			}
		});
	}
	
	function modify_(required_id,dept_id,desg_id,requirement_type,requirement_type_id){
		getReferenceVal(requirement_type);
		//$('.selectpicker').selectpicker('val',requirement_type_id);
		$('#dept_id').val(dept_id);
		$('#desg_id').val(desg_id);
		$('#ddl_reftype').val(requirement_type);
		$('#required_id').val(required_id);
		$('#btn_add').val('Update');
		var ty = requirement_type_id.split(',');
		$('select[name=ddl_ref]').val(ty);
		//setVal(ty);
		//$('.selectpicker').selectpicker('refresh')
	}
	function setVal(ty){
		$('select[name=ddl_ref]').val([25]);
	}
	
function getDesgnation(dis){
	var department = $(dis).val();
	var str="";
	$('.desgSelections').html("");
	$.ajax({
		type: "POST",
		url: site_url+'my_account/get_designation',
		data: {department : department},
		success: function(response)
		{
			response = JSON.parse(response);
			str += '<option value="">Select Designation</option>';
			for(var i=0; i< response.length; i++){
				str += '<option value="'+response[i].desg_id+'">'+response[i].desg_name+'</option>';
			}
			$('.desgSelections').html(str);
	   }
	});
}	

function getReference(dis){
	var requirement_type = $(dis).val();
	var str="";
	//$('.selectRef').html("");
	$.ajax({
		type: "POST",
		url: site_url+'en/hr/get_requirement_types',
		data: {requirement_type : requirement_type},
		success: function(response)
		{
			//response = JSON.parse(response);
			if(requirement_type =='S'){
				for(var i=0; i< response.length; i++){
					str += '<option value="'+response[i].skill_id+'">'+response[i].skill_name+'</option>';
				}
			}
			else if(requirement_type =='E'){
				for(var i=0; i< response.length; i++){
					str += '<option value="'+response[i].course_id+'">'+response[i].course_name+'</option>';
				}
			}
			else if(requirement_type =='W'){
				for(var i=0; i< response.length; i++){
					str += '<option value="'+response[i].experience_id+'">'+response[i].experience_name+'</option>';
				}
			}
			else {
				for(var i=0; i< response.length; i++){
					str += '<option value="'+response[i].skill_id+'">'+response[i].skill_name+'</option>';
				}
			}
			
			//var select = jQuery("#ddl_ref");
			//select.prop("multiple", !select.prop("multiple"));

			$('.selectRef').html(str);
			/* Destroy the selectpicker and re-initialize. */
			//select.selectpicker('destroy');
			//select.selectpicker();
	   }
	});
}
function getReferenceVal(requirement_type){
	//var requirement_type = $(dis).val();
	var str="";
	//$('.selectRef').html("");
	$.ajax({
		type: "POST",
		url: site_url+'en/hr/get_requirement_types',
		data: {requirement_type : requirement_type},
		success: function(response)
		{
			//response = JSON.parse(response);
			if(requirement_type =='S'){
				for(var i=0; i< response.length; i++){
					str += '<option value="'+response[i].skill_id+'">'+response[i].skill_name+'</option>';
				}
			}
			else if(requirement_type =='E'){
				for(var i=0; i< response.length; i++){
					str += '<option value="'+response[i].course_id+'">'+response[i].course_name+'</option>';
				}
			}
			else if(requirement_type =='W'){
				for(var i=0; i< response.length; i++){
					str += '<option value="'+response[i].experience_id+'">'+response[i].experience_name+'</option>';
				}
			}
			else {
				for(var i=0; i< response.length; i++){
					str += '<option value="'+response[i].skill_id+'">'+response[i].skill_name+'</option>';
				}
			}
			
			//var select = jQuery("#ddl_ref");
			//select.prop("multiple", !select.prop("multiple"));

			$('.selectRef').html(str);
			/* Destroy the selectpicker and re-initialize. */
			//select.selectpicker('destroy');
			//select.selectpicker();
	   }
	});
}
</script>