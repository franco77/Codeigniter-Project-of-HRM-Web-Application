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
					<legend class="pkheader">Placement Consultant:</legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"></span>Placement Consultant:</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="frmUpdatepayroll" name="frmUpdateHOD" method="POST">
										<div class="col-md-10">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-5 col-form-label">Placement Consultant: </label>
												<div class="col-sm-6">
													<input type="text" name="company" id="company" class="form-control">
													<input type="hidden" id="controller" name="controller" value="0">
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
										<th width="70%">List of Companies</th>
										<th width="20%">Action</th> 
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($placement as $v1): ?>
									<tr>
										<td><?= $i; ?></td>
										<td><?= $v1['consultant_name'] ?></td>
										<td><a onclick="delete_(<?= $v1['pid'] ?>)"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="modify_('<?= $v1['consultant_name'] ?>','<?= $v1['pid'] ?>')"><i class="fa fa-pencil"></i></a></td>
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
		if($('#company').val() !=""){
			$.ajax({
				type: frm.attr('method'),
				url: site_url+'en/hr/add_placement_consultant',
				data: frm.serialize(),
				success: function (data) {
					console.log(data);
					$('#piMSG').html(data);
					$('#btn_add').val('Add');
					$('#company').val('');
					$('#controller').val('0');
					$("#listpayroll").load(" #listpayroll > *");
				},
				error: function (data) {
					console.log('An error occurred.');
					console.log(data);
				},
			});
		}
    });
	
	function delete_(dat){
		console.log(dat);
		$.ajax({
			type: 'POST',
			url:site_url+'en/hr/delete_placement_consultant',
			data:{consultant_name:dat},
			success:function(data){
				$('#piMSG').html(data);
				 $("#listpayroll").load(" #listpayroll > *");	
			}
		});
	}
	
	function modify_(dat,aid){
		console.log(dat);
		//$('.selectpicker').selectpicker('val', 'Ajay  Patnaik(AITPL-989901)');
		$('#controller').val(aid);
		$('#company').val(dat);
		$('#btn_add').val('Update');
	}
</script>