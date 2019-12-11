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
					<legend class="pkheader">Email Template</legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"></span>Add/Edit Email Template</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="frmUpdateCountry" name="frmUpdateCountry" method="POST">
										<div class="col-md-12">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">Email Category: </label>
												<div class="col-sm-6">
													<select type="text" class="form-control" id="cid"  name="cid" required="" >
														<option selected value="">Select Category</option>
														<?php foreach($category as $v1): ?>
															<option value="<?= $v1['cid'] ?>" ><?= $v1['cname'] ?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">Title: </label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="form_name"  name="form_name"  required="" />
													<input type="hidden" id="form_id" name="form_id" value="0" />
												</div>
											</div>
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">Content: </label>
												<div class="col-sm-9">
													<textarea name="content" class="ckeditor form-control"  id="content"  required="" ></textarea>
												</div>
											</div>
										</div>
										 <div class="col-md-12 add_btn">
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
										<th width="20%">Email Category</th>
										<th width="30%">Email Title</th>
										<th width="10%">Action</th> 
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($branches as $v1): ?>
									<tr>
										<td><?= $i; ?></td>
										<td><?= $v1['cname'] ?></td>
										<td><?= $v1['form_name'] ?></td>
										<td><a onclick="delete_(<?= $v1['form_id'] ?>)"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
<script src="https://cdn.ckeditor.com/4.9.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace( 'description',
    {
        toolbar : 'Basic', /* this does the magic */
        uiColor : '#9AB8F3'
    });
</script>
<script type="text/javascript">
    var frm = $('#frmUpdateCountry');
	var site_url = "<?= base_url(); ?>";
    frm.submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: frm.attr('method'),
            url: site_url+'en/hr/add_email_template',
            data: frm.serialize(),
            success: function (data) {
                console.log(data);
				$('#piMSG').html('Template submitted Successfully');
				 /* $("#listpayroll").load(" #listpayroll > *");
					$('#cid').val('');
					$('#form_name').val('');
					$('#content').val('');
					$('#form_id').val('0');
					$('#btn_add').val('Add'); */
					setTimeout(function(){ location.reload(); }, 3000);
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
			url:site_url+'en/hr/delete_email_template',
			data:{form_id:dat},
			success:function(data){
				console.log(data);
				 $("#listpayroll").load(" #listpayroll > *");	
			}
		});
	}
	
</script>