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
					<legend class="pkheader">Level</legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"></span>Add/Edit Level</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="frmUpdateCountry" name="frmUpdateCountry" method="POST">
										<div class="col-md-10">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">Level: </label>
												<div class="col-sm-6">
													<input type="text" class="selectpicker form-control" id="level_name"  name="level_name" />
													<input type="hidden" id="level_id" name="level_id" value="0" />
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
										<th width="70%">List of Level</th>
										<th width="20%">Action</th> 
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($levels as $v1): ?>
									<tr>
										<td><?= $i; ?></td>
										<td><?= $v1['level_name'] ?></td>
										<td><a onclick="delete_(<?= $v1['level_id'] ?>)"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="modify_('<?= $v1['level_name'] ?>',<?= $v1['level_id'] ?>)"><i class="fa fa-pencil"></i></a></td>
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
		if($('#level_name').val() !=""){
			$.ajax({
				type: frm.attr('method'),
				url: site_url+'en/hr/add_level',
				data: frm.serialize(),
				success: function (data) {
					$('#piMSG').html('Level submitted Successfully');
					$("#listpayroll").load(" #listpayroll > *");
					$('#level_name').val('');
					$('#level_id').val('0');
					$('#btn_add').val('Add');
					setTimeout(function(){ $('#piMSG').html(''); }, 3000);
				},
				error: function (data) {
					console.log('An error occurred.');
				},
			});
		}
    });
	
	function delete_(dat){
		$.ajax({
			type: 'POST',
			url:site_url+'en/hr/delete_level',
			data:{level_id:dat},
			success:function(data){
				 $("#listpayroll").load(" #listpayroll > *");	
			}
		});
	}
	
	function modify_(level_name,level_id){
		$('#level_name').val(level_name);
		$('#level_id').val(level_id);
		$('#btn_add').val('Update');
	}
</script>