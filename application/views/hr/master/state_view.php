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
					<legend class="pkheader">State</legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"></span>Add/Edit State</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="frmUpdateCountry" name="frmUpdateCountry" method="POST">
										<div class="col-md-10">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">Country: </label>
												<div class="col-sm-6">
													<select type="text" class="selectpicker form-control" data-live-search="true" id="country_id"  name="country_id" >
														<option selected value="">Select Country</option>
														<?php foreach($country as $v1): ?>
															<option value="<?= $v1['country_id'] ?>" <?php if($v1['country_id'] == '99'){ echo "selected"; }  ?>><?= $v1['country_name'] ?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">State Name: </label>
												<div class="col-sm-6">
													<input type="text" class="selectpicker form-control" id="state_name"  name="state_name" />
													<input type="hidden" id="state_id" name="state_id" value="0" />
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
										<th width="30%">Country Name</th>
										<th width="40%">State Name</th>
										<th width="20%">Action</th> 
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($state as $v1): ?>
									<tr>
										<td><?= $i; ?></td>
										<td><?= $v1['country_name'] ?></td>
										<td><?= $v1['state_name'] ?></td>
										<td><a onclick="delete_(<?= $v1['state_id'] ?>)"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="modify_('<?= $v1['state_name'] ?>',<?= $v1['state_id'] ?>,<?= $v1['country_id'] ?>)"><i class="fa fa-pencil"></i></a></td>
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
		if($('#state_name').val() !=""){
			$.ajax({
				type: frm.attr('method'),
				url: site_url+'en/hr/add_state',
				data: frm.serialize(),
				success: function (data) {
					console.log(data);
					$('#piMSG').html('State submitted Successfully');
					 $("#listpayroll").load(" #listpayroll > *");
						$('.selectpicker').selectpicker('val','');
						$('#state_name').val('');
						$('#state_id').val('0');
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
			url:site_url+'en/hr/delete_state',
			data:{state_id:dat},
			success:function(data){
				console.log(data);
				 $("#listpayroll").load(" #listpayroll > *");	
			}
		});
	}
	
	function modify_(state_name,state_id,country_id){
		$('.selectpicker').selectpicker('val',country_id);
		$('#state_name').val(state_name);
		$('#state_id').val(state_id);
		$('#btn_add').val('Update');
	}
</script>