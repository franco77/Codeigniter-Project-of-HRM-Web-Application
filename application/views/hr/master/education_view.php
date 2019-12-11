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
					<legend class="pkheader">Education</legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"></span>Add/Edit Education</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="frmUpdateCountry" name="frmUpdateCountry" method="POST">
										<div class="col-md-11">
											<div class="col-sm-6">
											<div class="form-group">
												<label for="staticEmail" class="col-sm-5 col-form-label">Course Type: </label>
												<div class="col-sm-7">
													<select id="course_type" name="course_type" class="required  form-control ">
														<option value="Graduation" >Graduation</option>
														<option value="Professional"  >Professional</option>
													</select>
												</div>
											</div>
											</div>
											<div class="col-sm-6">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-5 col-form-label">Course Category: </label>
												<div class="col-sm-7">
													<select id="course_category" name="course_category" class="required  form-control ">
														<option value="Technical" >Technical</option>
														<option value="Non Technical"  >Non Technical</option>
													</select>
												</div>
											</div>
											</div>
											<div class="col-sm-6">
											<div class="form-group">
												<label for="staticEmail" class="col-sm-5 col-form-label">Course: </label>
												<div class="col-sm-7">
													<input type="text" class=" form-control" id="course_name"  name="course_name" />
													<input type="hidden" id="course_id" name="course_id" value="0" />
												</div>
											</div>
											</div>
										</div>
										 <div class="col-md-1 add_btn">
											<input type="submit" id="btn_add" name="btn_update" class="search_sbmt pull-right btn btn-primary" value="Add"/>
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
										<th width="25%">Course Type</th>
										<th width="25%">Course Category</th>
										<th width="30%">Course Name</th>
										<th width="10%">Action</th> 
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($grades as $v1): ?>
									<tr>
										<td><?= $i; ?></td>
										<td><?= $v1['course_type'] ?></td>
										<td><?= $v1['course_category'] ?></td>
										<td><?= $v1['course_name'] ?></td>
										<td><a onclick="delete_(<?= $v1['course_id'] ?>)"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="modify_('<?= $v1['course_name'] ?>','<?= $v1['course_category'] ?>','<?= $v1['course_type'] ?>',<?= $v1['course_id'] ?>)"><i class="fa fa-pencil"></i></a></td>
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
		if($('#course_name').val() !=""){
			$.ajax({
				type: frm.attr('method'),
				url: site_url+'en/hr/add_education',
				data: frm.serialize(),
				success: function (data) {
					console.log(data);
					$('#piMSG').html('Education submitted Successfully');
					 $("#listpayroll").load(" #listpayroll > *");
						$('#course_type').selectpicker('val','Graduation');
						$('#course_category').selectpicker('val','Technical');
						$('#course_name').val('');
						$('#course_id').val('0');
						$('#btn_add').val('Add');
						setTimeout(function(){ $('#piMSG').html(''); }, 3000);
				},
				error: function (data) {
					console.log('An error occurred.');
					console.log(data);
				},
			});
		}
    });
	
	function delete_(dat){
		$.ajax({
			type: 'POST',
			url:site_url+'en/hr/delete_education',
			data:{course_id:dat},
			success:function(data){
				console.log(data);
				 $("#listpayroll").load(" #listpayroll > *");	
			}
		});
	}
	
	function modify_(course_name,course_category,course_type,course_id){
		$('#course_category').selectpicker('val',course_category);
		$('#course_type').selectpicker('val',course_type);
		$('#course_name').val(course_name);
		$('#course_id').val(course_id);
		$('#btn_add').val('Update');
	}
</script>