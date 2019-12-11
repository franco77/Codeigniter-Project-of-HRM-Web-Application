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
					<a href="<?= base_url('my_account/reference_update_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a> 
					<legend class="pkheader">Reference</legend>
					<div class="row well">
						<div class="table-responsive">
							<?php  
							//$count = count($refInfo);
							//if($count>0)
							//{
								//for($i=0; $i<$count; $i++)
								//{ ?> 
								<table class="table table-striped table-bordered table-condensed">
									<tr class="info">
										<td colspan="4" class="form_title"><strong>Reference 1</strong></td>
									</tr>
									<tr>
										<td width="20%"><span class="red"></span> <strong> Name:</strong></td>
										<td width="30%"><?php if(count($refInfo)>0){ echo $refInfo[0]['ref_name']; } ?></td>
										<td width="20%"><span class="red"></span> <strong>Company Name:</strong></td>
										<td width="30%"><?php if(count($refInfo)>0){ echo $refInfo[0]['comp_name']; }?></td>
									</tr>
									<tr>
									</tr>
									<tr>
										<td><span class="red"></span> <strong>Designation</strong></td>
										<td><?php if(count($refInfo)>0){ echo $refInfo[0]['designation']; }?></td>
										<td><span class="red"></span> <strong>Contact No.</strong></td>
										<td><?php if(count($refInfo)>0){ echo $refInfo[0]['cont_no']; }?></td> 
									</tr>
								</table> 
								<table class="table table-striped table-bordered table-condensed">
									<tr class="info">
										<td colspan="4" class="form_title"><strong>Reference 2</strong></td>
									</tr>
									<tr>
										<td width="20%"><span class="red"></span> <strong> Name:</strong></td>
										<td width="30%"><?php if(count($refInfo)>1){ echo $refInfo[1]['ref_name']; } ?></td>
										<td width="20%"><span class="red"></span> <strong>Company Name:</strong></td>
										<td width="30%"><?php if(count($refInfo)>1){ echo $refInfo[1]['comp_name']; }?></td>
									</tr>
									<tr>
									</tr>
									<tr>
										<td><span class="red"></span> <strong>Designation</strong></td>
										<td><?php if(count($refInfo)>1){ echo $refInfo[1]['designation']; }?></td>
										<td><span class="red"></span> <strong>Contact No.</strong></td>
										<td><?php if(count($refInfo)>1){ echo $refInfo[1]['cont_no']; }?></td> 
									</tr>
								</table> 
								<table class="table table-striped table-bordered table-condensed">
									<tr class="info">
										<td colspan="4" class="form_title"><strong>Reference 3</strong></td>
									</tr>
									<tr>
										<td width="20%"><span class="red"></span> <strong> Name:</strong></td>
										<td width="30%"><?php if(count($refInfo)>2){ echo $refInfo[2]['ref_name']; } ?></td>
										<td width="20%"><span class="red"></span> <strong>Company Name:</strong></td>
										<td width="30%"><?php if(count($refInfo)>2){ echo $refInfo[2]['comp_name']; }?></td>
									</tr>
									<tr>
									</tr>
									<tr>
										<td><span class="red"></span> <strong>Designation</strong></td>
										<td><?php if(count($refInfo)>2){ echo $refInfo[2]['designation']; }?></td>
										<td><span class="red"></span> <strong>Contact No.</strong></td>
										<td><?php if(count($refInfo)>2){ echo $refInfo[2]['cont_no']; }?></td> 
									</tr>
								</table> 
							<?php //$i++;} } 
							/*else
							{ ?>  
								<table class="table table-striped table-bordered table-condensed">
									<tr>
										<td>&nbsp; </td>
									</tr>
									<tr>
										<td><p style='text-align: center'>No Reference Found! </p> </td>
									</tr>
									 <tr>
										<td>&nbsp; </td>
									</tr>
								</table>

							<?php } */ ?> 
						</div>
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>