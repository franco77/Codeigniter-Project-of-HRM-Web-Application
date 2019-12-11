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
					<legend class="pkheader">Grade</legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"></span>Add/Edit Grade</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="frmUpdateCountry" name="frmUpdateCountry" method="POST">
										<div class="col-md-10">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">Level: </label>
												<div class="col-sm-6">
													<select type="text" class="selectpicker form-control" data-live-search="true" id="level_id"  name="level_id" >
														<option selected value="">Select Level</option>
														<?php foreach($levels as $v1): ?>
															<option value="<?= $v1['level_id'] ?>" ><?= $v1['level_name'] ?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">Grade: </label>
												<div class="col-sm-6">
													<input type="text" class="selectpicker form-control" id="grade_name"  name="grade_name" />
													<input type="hidden" id="grade_id" name="grade_id" value="0" />
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
										<th width="30%">Level</th>
										<th width="40%">Grade</th>
										<th width="20%">Action</th> 
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($grades as $v1): ?>
									<tr>
										<td><?= $i; ?></td>
										<td><?= $v1['level_name'] ?></td>
										<td><?= $v1['grade_name'] ?></td>
										<td><a onclick="delete_(<?= $v1['grade_id'] ?>)"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="modify_('<?= $v1['grade_name'] ?>',<?= $v1['grade_id'] ?>,<?= $v1['level_id'] ?>)"><i class="fa fa-pencil"></i></a></td>
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
		if($('#level_id').val() !="" && $('#grade_name').val() !="" ){
			$.ajax({
				type: frm.attr('method'),
				url: site_url+'en/hr/add_grade',
				data: frm.serialize(),
				success: function (data) {
					console.log(data);
					$('#piMSG').html('Grade submitted Successfully');
					 $("#listpayroll").load(" #listpayroll > *");
						$('.selectpicker').selectpicker('val','');
						$('#grade_name').val('');
						$('#grade_id').val('0');
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
			url:site_url+'en/hr/delete_grade',
			data:{grade_id:dat},
			success:function(data){
				console.log(data);
				 $("#listpayroll").load(" #listpayroll > *");	
			}
		});
	}
	
	function modify_(grade_name,grade_id,level_id){
		$('.selectpicker').selectpicker('val',level_id);
		$('#grade_name').val(grade_name);
		$('#grade_id').val(grade_id);
		$('#btn_add').val('Update');
	}
</script>