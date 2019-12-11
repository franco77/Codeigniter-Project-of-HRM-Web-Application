<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('accounts/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<span  class="pull-right " role="button" style="margin-right: 10px; margin-top: 4px;" > <span style="color:#fff;">FY : </span>
						<select name="searchYear1" id="searchYear1" class="input-sm" onchange="getFYData(this);" > 
							<?php
							$yr=date("Y");
							for ($j=$yr;$j>=2017;$j--){
								if ($j == $fyear){
								?>
									<option value="<?php echo $j;?>" selected ><?php echo $j.'-'.($j+1);?></option>
								<?php }else{?>
									<option value="<?php echo $j;?>" ><?php echo $j.'-'.($j+1);?></option>
								<?php }
							}?> 
						</select>
					</span>
					<legend class="pkheader">Income Tax Slab <small>(Define Income Tax Slab Here)</small></legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"></span>Add/Edit Income Tax Slab</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="frmUpdateCountry" name="frmUpdateCountry" method="POST">
										
											<div class="col-md-4" style="padding-left: 0px;padding-right: 0px;">
												<div class="form-group">
													<label for="staticEmail" class="col-sm-4 col-form-label">Income Tax Slab</label>
													<div class="col-sm-8 row">
														<input type="text" class=" form-control" id="from_range" required name="from_range" />
														<input type="hidden" class=" form-control" id="searchYear" placeholder="from" required name="searchYear" value="<?php echo $fyear;?>"/>
													</div>
												</div>
											</div>
											<div class="col-md-4" style="padding-left: 0px;padding-right: 0px;">
												<div class="form-group">
													<div class="col-sm-10 row">
														<input type="text" class=" form-control" id="to_range" required name="to_range" />
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="staticEmail" class="col-sm-3 col-form-label">Value</label>
													<div class="col-sm-9">
														<input type="text" class=" form-control" id="pt_value" required  name="pt_value" />
													</div>
												</div>
											</div>
											<input type="hidden" id="controller" required name="controller" value="0" />
										<br>
										<div class="row">
											<div class="col-md-12">
												 <div class="col-md-12 add_btn">
													<input type="submit" id="btn_add" name="btn_update" class="search_sbmt pull-right btn btn-primary" value="Add"/>
												 </div>
												 <div class="col-md-12 successMsg" id="piMSG" style="text-align:center;"></div>
											</div>	 
										</div>
									</form>	
								</div>
							</div>
						</div>
					</div>
					<div class="row well" id="listpayroll">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info"> 
										<th width="10%">Sl. No</th>
										<th width="50%">PT Slab Range</th>
										<th width="20%">PT Value</th> 
										<th width="20%">Action</th> 
									</tr>
								</thead>
								<tbody id="secVal">
									<?php $i = 1; foreach($sel_qryinfo as $v1): ?>
									<tr>
										<td><?= $i; ?></td>
										<td><?= $v1['range'] ?></td>
										<td><?= $v1['it_value'] ?></td>
										<td><a onclick="delete_(<?= $v1['it_id'] ?>)"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="modify_('<?= $v1['it_id'] ?>','<?= $v1['range'] ?>','<?= $v1['it_value'] ?>')"><i class="fa fa-pencil"></i></a></td>
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
            url: site_url+'en/accounts_admin/add_edit_incomeslab',
            data: frm.serialize(),
            success: function (data) {
                console.log(data);
				$('#piMSG').html('Income Tax Slab submitted Successfully');
				 $("#listpayroll").load(" #listpayroll > *");
					$('#from_range').val('');
					$('#to_range').val('');
					$('#pt_value').val('');
					$('#controller').val('0');
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
			url:site_url+'en/accounts_admin/delete_incomeslab',
			data:{pt_id:dat},
			success:function(data){
				$('#piMSG').html('Income Tax Slab deleted Successfully');
				 $("#listpayroll").load(" #listpayroll > *");	
			}
		});
	}
	
	function modify_(pt_id,range,pt_value){
		//$('#range').val(range);
		var a = range.split('-');
		$('#from_range').val(a[0]);
		$('#to_range').val(a[1]);		
		$('#pt_value').val(pt_value);
		$('#controller').val(pt_id);
		$('#btn_add').val('Update');
	}
	
	function getFYData(dis){
		var searchYear = $(dis).val();
		$('#searchYear').val(searchYear);
		$.ajax({
			type: 'POST',
			url:site_url+'en/accounts_admin/get_income_tax_slab_define_fy',
			data:{searchYear:searchYear},
			success:function(data){
				var str="";
				var j=0;
				for (var i=0; i<data.length; i++){
					j++;
					str += '<tr>';
					str += '<td>'+j+'</td>';
					str += '<td>'+data[i].range+'</td>';
					str += '<td>'+data[i].it_value+'</td>';
					str += '<td><a onclick="delete_('+data[i].it_id+')"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="modify_('+data[i].it_id+',\''+data[i].range+'\',\''+data[i].it_value+'\')"><i class="fa fa-pencil"></i></a></td>';
					str += '</tr>';
				}
				$('#secVal').html(str);
			}
		});
	}
</script>