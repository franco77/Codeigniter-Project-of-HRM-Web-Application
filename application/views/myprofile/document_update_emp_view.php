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
						<?php $this->load->view('myprofile/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<a href="<?= base_url('my_account/document_readonly_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View</a>
					<legend class="pkheader">Documents</legend>
					<div class="row well">
						<p><?php echo $this->session->flashdata('statusMsg'); ?></p>
						<form id="frmDocUpdate" name="frmDocUpdate" method="POST" action="" enctype="multipart/form-data">
							<?php
							if($success_msg != '') { ?>
							<div class="col-md-12"><div class="alert alert-success" role="alert"> <?php echo $success_msg; ?> </div>
							<?php } else if($error_msg != '') { ?>
							<div class="col-md-12"><div class="alert alert-danger" role="alert"> <?php echo $error_msg; ?> </div>
							<?php } ?>
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-condensed">
									<tbody>
										<tr>
											<td width="30%"><span class="red"></span> <strong>File Upload:</strong></td>
											<td width="40%">
											<select name="docid" class="form-control">
												<option>Select</option>
												<?php $kitSQL_count = COUNT($kitInfo); 
													 for($i=0; $i<$kitSQL_count; $i++) {	?>
														<option value="<?php echo $kitInfo[$i]['joining_kit_id'];?>"><?php echo $kitInfo[$i]['kit_name'];?></option>
													<?php }  ?>
											</select>
											</td>									
											<td width="40%">
												<input type="file" multiple="multiple" style="width:200px;"  name="file" value="" class="form-control" />
											  
											</td>
											<td width="50">
											  
											</td>
										</tr>
									</tbody>
								</table>	                                 	
								<div class="link_section">
									<input type="submit" id="btnUpdateDoc" name="btnUpdateDoc" class="btn btn-primary pull-right" value="Save" />
									<div class="clear"></div>
								</div>
							</div>
						</form>
						</div>
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>
