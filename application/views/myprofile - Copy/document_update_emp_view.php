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
					
					<legend class="pkheader">Documents</legend>
					<div class="row well">
						<p><?php echo $this->session->flashdata('statusMsg'); ?></p>
						<form id="frmDocUpdate" name="frmDocUpdate" method="POST" action="" enctype="multipart/form-data">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-condensed">
									<tbody>
										<tr>
											<td width="250"><span class="red"></span> <strong>Bulk Upload:</strong></td>
																				
											<td width="250">
												<input type="file" multiple="multiple" style="width:200px;"  name="file[]" value="" class="form_ui" />
											  
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