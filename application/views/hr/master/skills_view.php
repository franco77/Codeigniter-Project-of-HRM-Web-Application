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
					<legend class="pkheader">Skills</legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"></span>Add/Edit Skills</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="frmUpdateCountry" name="frmUpdateCountry" method="POST">
										<div class="col-md-10">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">Category: </label>
												<div class="col-sm-6">
													<select type="text" class="selectpicker form-control" id="skill_category"  name="skill_category" >
														<option selected value="">Select Category</option>
														<option value="Technical" >Technical</option>
                                                     	<option value="Behavioural" >Behavioural</option>
                                                     	<option value="Managerial" >Managerial</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">Skill Name: </label>
												<div class="col-sm-6">
													<input type="text" class="selectpicker form-control" id="skill_name"  name="skill_name" />
													<input type="hidden" id="skill_id" name="skill_id" value="0" />
												</div>
											</div>
										</div>
										 <div class="col-md-2 add_btn">
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
										<th width="30%">Skill Category</th>
										<th width="40%">SKill Name</th>
										<th width="20%">Action</th> 
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($skills as $v1): ?>
									<tr>
										<td><?= $i; ?></td>
										<td><?= $v1['skill_category'] ?></td>
										<td><?= $v1['skill_name'] ?></td>
										<td><a onclick="delete_(<?= $v1['skill_id'] ?>)"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="modify_('<?= $v1['skill_name'] ?>',<?= $v1['skill_id'] ?>,'<?= $v1['skill_category'] ?>')"><i class="fa fa-pencil"></i></a></td>
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
		if($('#skill_category').val() !="" && $('#skill_name').val() !="" ){
			$.ajax({
				type: frm.attr('method'),
				url: site_url+'en/hr/add_skills',
				data: frm.serialize(),
				success: function (data) {
					console.log(data);
					$('#piMSG').html('Skill submitted Successfully');
					 $("#listpayroll").load(" #listpayroll > *");
						$('.selectpicker').selectpicker('val','');
						$('#skill_name').val('');
						$('#skill_id').val('0');
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
			url:site_url+'en/hr/delete_skills',
			data:{skill_id:dat},
			success:function(data){
				console.log(data);
				 $("#listpayroll").load(" #listpayroll > *");	
			}
		});
	}
	
	function modify_(skill_name,skill_id,skill_category){
		$('.selectpicker').selectpicker('val',skill_category);
		$('#skill_name').val(skill_name);
		$('#skill_id').val(skill_id);
		$('#btn_add').val('Update');
	}
</script>