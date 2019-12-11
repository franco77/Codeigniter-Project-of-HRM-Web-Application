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
					<legend class="pkheader">Define Miscellaneous Items</legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"></span>Add/Edit Define Miscellaneous Items</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="frmUpdateCountry" name="frmUpdateCountry" method="POST">
										<div class="col-md-10">
											<div class="col-sm-6">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">Item: </label>
												<div class="col-sm-9">
													<select type="text" class="form-control" id="miscellaneous_id"  name="miscellaneous_id" >
														<option selected value="0">Select Item</option>
														<?php foreach($branches as $v1): ?>
															<option value="<?= $v1['miscellaneous_id'] ?>" ><?= $v1['miscellaneous_item'] ?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											</div>
											<div class="col-sm-6">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">Value: </label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="miscellaneous_value"  name="miscellaneous_value" />
												</div>
											</div>
											</div>
										</div>
										 <div class="col-md-2 add_btn">
											<input type="submit" id="btn_add" name="btn_update" class="search_sbmt pull-right btn btn-primary" value="Add/Update"/>
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
										<th width="30%">Miscellaneous Item</th>
										<th width="40%">Miscellaneous Value</th>
										<th width="20%">Action</th> 
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($branches as $v1): ?>
									<tr>
										<td><?= $i; ?></td>
										<td><?= $v1['miscellaneous_item'] ?></td>
										<td><?= $v1['miscellaneous_value'] ?></td>
										<td><a onclick="modify_(<?= $v1['miscellaneous_value'] ?>,<?= $v1['miscellaneous_id'] ?>)"><i class="fa fa-pencil"></i></a></td>
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
		if($('#miscellaneous_id').val() !="" && $('#miscellaneous_value').val() !="" ){
			$.ajax({
				type: frm.attr('method'),
				url: site_url+'en/hr/add_miscellaneous',
				data: frm.serialize(),
				success: function (data) {
					console.log(data);
					$('#piMSG').html('Miscellaneous submitted Successfully');
					 $("#listpayroll").load(" #listpayroll > *");
						$('#miscellaneous_value').val('');
						$('#miscellaneous_id').val('0');
						$('#btn_add').val('Add/Update');
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
			url:site_url+'en/hr/delete_miscellaneous',
			data:{miscellaneous_id:dat},
			success:function(data){
				console.log(data);
				 $("#listpayroll").load(" #listpayroll > *");	
			}
		});
	}
	
	function modify_(miscellaneous_value,miscellaneous_id){
		$('#miscellaneous_value').val(miscellaneous_value);
		$('#miscellaneous_id').val(miscellaneous_id);
		$('#btn_add').val('Update');
	}
</script>