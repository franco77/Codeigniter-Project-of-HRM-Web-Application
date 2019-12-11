<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_id = "";
if (isset($_GET['id']))
{
	$get_id = "?id=".$_GET['id'];
}
echo '<input type="hidden" id="getURLIdVal" name="getURLIdVal" class="btn btn-sm btn-info" value="'.$get_id.'" />';
?>
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
					<?php $this->load->view('hr/top_view');?>
					<a href="<?= base_url('en/hr/exp_profile_readonly_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View</a>
					<legend class="pkheader">Experience Update</legend>
					<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<tbody>
									<form id="frmExpUpdate" name="frmExpUpdate" method="POST" action="<?php //echo $actionURL;?>" enctype="multipart/form-data">
										<div class="row">
											<div class="col-md-3"><span style="line-height: 3;"> <strong>Experience in Polosoft IT :</strong></span></div>
											<div class="col-md-2"><span style="line-height: 3;"> <strong><i class="fa fa-bell" aria-hidden="true"></i> <?php echo $expInAABSyS?> Months</strong></span></div>
										</div>
									</form>
									<form id="frmPrevExpAdd" name="frmPrevExpAdd" method="POST" action="" enctype="multipart/form-data">
										<div class="row"> 
											
												<h3 style="font-size: 13px; background: #d9edf7; padding: 9px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc;"><strong>Experience Check List</strong></h3>
											
											<div class="col-md-4"><input type="text" id="txtcomp_name" name="txtcomp_name" value="" placeholder="Company Name" class="required form-control" style="width:200px;" /></div>
											<div class="col-md-4"><input type="text" id="txtdesignation" name="txtdesignation" value="" placeholder="Designation" class="required form-control" style="width:200px;" /></div>
											<div class="col-md-3"><input type="text" id="txtexperince" name="txtexperince" value="" placeholder="Experience in Months" class="required digits form-control" style="width:150px;" /></div>
											<div class="col-md-1">
												<input type="submit" id="btnAddPrevExp" name="btnAddPrevExp" class="btn btn-sm btn-info" value="Add" />
											</div>
										</div>
									</form>
									<?php 
									$expRows = count($expRes_arr);
									if($expRows > 0)
									{
										for($i=0; $i < $expRows; $i++) 
										{
											$j = $i+1;
									?>
									<div class="row">
										<table class="table">
											<tr class="info">
												<td colspan="6" class="form_title"><strong>Previous Experience Info<?php echo $j;?> </strong></td>
											</tr>
											<tr>
												<td style="width:15%;"><span style="line-height: 3;"><strong>Company Name:</strong></span></td>
												<td style="width:20%;">
													<input type="text" id="txtcomp_name_<?php echo $expRes_arr[$i]['exp_id'];?>" name="txtcomp_name_<?php echo $expRes_arr[$i]['exp_id'];?>" value="<?php echo $expRes_arr[$i]['comp_name'];?>" class="required form-control" /></td>
												<td style="width:15%;"><span style="line-height: 3;"><strong>Designation:</strong></span></td>
												<td style="width:20%;">
													<input type="text" id="txtdesignation_<?php echo $expRes_arr[$i]['exp_id'];?>" name="txtdesignation_<?php echo $expRes_arr[$i]['exp_id'];?>" value="<?php echo $expRes_arr[$i]['designation'] ;?>" class="required  form-control" />
												</td>
												<td style="border-top:none; width:20%;"><span style="line-height: 3;"><strong>Experience(in Months):</strong></span></td>
												<td style="border-top:none;width:10%;">
													<input type="text" id="txtexperince_<?php echo $expRes_arr[$i]['exp_id'];?>" name="txtexperince_<?php echo $expRes_arr[$i]['exp_id'];?>" value="<?php echo $expRes_arr[$i]['experince'] ;?>" class="required form-control"/>
												</td>
											</tr>
										
										</table>
										<div class="row submtSec"   style="margin-bottom: 20px;">
											<div class="msg-sec"></div>
											<div class="col-md-3 pull-right">
												<span class="pull-right">
													<input type="button"  id="btnUpdatePrevExp_<?php echo $expRes_arr[$i]['exp_id'];?>" name="btnUpdatePrevExp_<?php echo $expRes_arr[$i]['exp_id'];?>" class="btn btn-sm btn-info" value="Update" onclick="UpdateExp(this,'update', <?php echo $expRes_arr[$i]['exp_id'];?>)" />
													<input type="button"  id="btnDeletePrevExp_<?php echo $expRes_arr[$i]['exp_id'];?>" name="btnDeletePrevExp_<?php echo $expRes_arr[$i]['exp_id'];?>" class="btn btn-sm btn-danger" value="Delete" onclick="UpdateExp(this,'delete', <?php echo $expRes_arr[$i]['exp_id'];?>)" />
												</span>
											</div>
										</div> 
                                	</div>
									<?php } } ?>
							</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>