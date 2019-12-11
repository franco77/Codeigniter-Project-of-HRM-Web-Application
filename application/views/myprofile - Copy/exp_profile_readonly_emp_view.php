<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>  
<?php
$get_id = "";
if (isset($_GET['id']))
{
	$get_id = "?id=".$_GET['id'];
}
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
					<a href="<?= base_url('my_account/exp_update_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a> 
					<legend class="pkheader">Experience</legend>
					<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed"> 
								<tbody>
									<tr>
										<td width="20%" valign="top"><span class="red">&nbsp;</span> <strong> Experience in Polosoft:</strong></td>
										<td width="20%" valign="top">
											<?php echo $expInAABSyS?> Month(s)</td>
										<td width="20%" valign="top"><span class="red">&nbsp;</span> <strong>Prior to Polosoft:</strong></td>
										<td width="20%" valign="top">
										<?php echo $empInfo[0]['exp_others']?> Month(s)</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php 
						$expRows = count($expRes_arr);
						if($expRows > 0)
						{
							for($i=0; $i < $expRows; $i++) 
							{ ?> 
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-condensed">  
									<tr class="info"><td colspan="4" class="form_title" style="text-align:center; text-transform:uppercase;"><strong>Previous Experience <?php echo $i+1;?> </strong></td></tr>
									<tr>
										   <td width="20%" valign="top"><span class="red">&nbsp;</span> <strong>Company Name:</strong></td>
									   <td width="20%" valign="top">
										   <?php echo $expRes_arr[$i]['comp_name'];?></td>
										   <td width="20%" valign="top"><span class="red">&nbsp;</span> <strong>Designation:</strong></td>
									   <td width="20%" valign="top">
										  <?php echo $expRes_arr[$i]['designation'] ;?></td>
								   </tr>
									<tr>
										<td width="20%" valign="top"><span class="red">&nbsp;</span> <strong>Experience:</strong></td>
										<td width="20%" valign="top"><?php echo $expRes_arr[$i]['experince'] ;?> Months</td>
										<td colspan="2">&nbsp;</td
								   </tr>
								</table>
							</div>
						
						<?php } }
						else {?> 
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<tr class="info">
									<td colspan="4" class="form_title" style="text-align:center; text-transform:uppercase;"><strong>Previous Experience Info</strong></td>
								</tr>
								<tr>
									<td colspan="4" class=""><strong>No previous experience information added till now.</strong></td>
								</tr>
							</table> 
						</div>
					    
						<?php }?>
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed"> 
								<tbody>
									<tr class="info">
										<td class="form_title" colspan="2" style="text-align:center; text-transform:uppercase;"><strong>Experience Check List</strong></td>
									</tr>
									<tr>
										<td align="center"><strong>Required</strong></td>
										<td align="center"><strong>Actual</strong></td>
									</tr>
									<tr>
										<td align="center"><?php echo $reqExperience;?></td>
										<td align="center"><?php echo (($expTotal > 0)?$expTotal . " Months": "Nil");?></td>
									</tr>
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