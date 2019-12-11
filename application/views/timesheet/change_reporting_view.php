<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>  
<?php
$get_id = "";
if (isset($_GET['user_id']))
{
	$get_id = "?user_id=".$_GET['user_id'];
}
echo '<input type="hidden" id="getURLIdVal" name="getURLIdVal" class="btn btn-sm btn-info" value="'.$get_id.'" />';
?>

<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">Change Reporting Person</legend>
					<div class="row well"> 
						<form id="frmChangeRepoting" name="frmChangeRepoting" method="POST" action="">
							<div class="form_pad">
								<table width="100%" border="0" cellspacing="10" cellpadding="10">
								  <tr>
									<td><span class="red">&nbsp;</span><strong>Reporting To</strong> : </td>
									<td>
									<?php echo $repMgrInfo[0]['full_name'].' : '.$repMgrInfo[0]['loginhandle'];?>
									</td>
								  </tr>
								  <tr>
									<td><span class="red">*</span><strong>New Reporting: </strong>: </td>
									<td>
										<!--<input type="text" id="reportingAC" value="" class="form-control" style="width:190px;" />
										<input type="hidden" id="reporting" name="reporting" value="" class="form-control required" />--> 
										<select  name="reporting" id="reporting"  class="selectpicker form-control" data-live-search="true" required="" >
											<?php 
											for($l=0; $l < count($getReportingManager); $l++) 
											{?>
												<option value="<?php echo $getReportingManager[$l]['login_id']; ?>" <?php if($getReportingManager[$l]['login_id'] == $repMgrInfo[0]['reporting_to']){ echo "selected"; }?> ><?php echo $getReportingManager[$l]['dispName']; ?></option>	
											<?php } ?>
										</select>
									</td>
								  </tr>
							   </table>
								<span class="red">*</span>  Marked fields are mandatory
								<div class="form_sbmt submtSec">
									<div class="msg-sec"></div>
									<div class="col-md-3 pull-right">
										<input type="button" id="btnChangeReporting" name="btnChangeReporting" class="btn btn-info pull-right" value="Change" onclick="changeReportingManager(this,)" />
									</div>
								</div>
								<div class="clear"></div>
							</div>
						</form>
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>