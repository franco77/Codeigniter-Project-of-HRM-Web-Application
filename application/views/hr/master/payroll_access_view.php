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
					<legend class="pkheader">Payroll Acess</legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"></span>Payroll Acess</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="frmUpdatepayroll" name="frmUpdateHOD" method="POST">
										<div class="col-md-10">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">Employee NameID: </label>
												<div class="col-sm-6">
													<select type="text" class="selectpicker form-control" data-live-search="true" id="employee"  name="employee" >
														<option selected value="">Select Employee</option>
														<?php foreach($employee as $v1): ?>
															<option value="<?= $v1['full_name'] ?>(<?= $v1['loginhandle'] ?>)"><?= $v1['full_name'] ?>(<?= $v1['loginhandle'] ?>)</option>
														<?php endforeach;?>
													</select>
													<input type="hidden" id="controller" name="controller" value="1">
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
										<th width="70%">List of Employee</th>
										<th width="20%">Action</th> 
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($payroll_access as $v1): ?>
									<tr>
										<td><?= $i; ?></td>
										<td><?= $v1['emp_code'] ?></td>
										<td><a onclick="delete_(<?= $v1['aid'] ?>)"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="modify_('<?= $v1['emp_code'] ?>',<?= $v1['aid'] ?>)"><i class="fa fa-pencil"></i></a></td>
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
    var frm = $('#frmUpdatepayroll');
	var site_url = "<?= base_url(); ?>";
    frm.submit(function (e) {
        e.preventDefault();
		if($('#employee').val() !=""){
			$.ajax({
				type: frm.attr('method'),
				url: site_url+'en/hr/add_payroll_access',
				data: frm.serialize(),
				success: function (data) {
					console.log(data);
					$('#piMSG').html('Employee is Successfully Added');
					 $("#listpayroll").load(" #listpayroll > *");
						$('.selectpicker').selectpicker('val','');
						$('#controller').val('1');
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
			url:site_url+'en/hr/delete_payroll_access',
			data:{payroll:dat},
			success:function(data){
				console.log(data);
				 $("#listpayroll").load(" #listpayroll > *");	
			}
		});
	}
	
	function modify_(dat,aid){
		$('.selectpicker').selectpicker('val',dat);
		$('#controller').val(aid);
		$('#btn_add').val('Update');
	}
</script>