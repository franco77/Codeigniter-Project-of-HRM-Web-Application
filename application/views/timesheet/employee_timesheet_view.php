<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<!--********** Tooltip ****************-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/frontend/timesheet/jquery-1.9.1.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/frontend/timesheet/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/dist/frontend/timesheet/jquery-ui.css">
<!--********** /Tooltip ****************-->
<script language="javascript" type="text/javascript">
var j191 = jQuery.noConflict();
  j191(function () {
      j191(document).tooltip({
          content: function () {
              return $(this).prop('title');
          }
      });
  });
</script>
<?php
$reqEmpid = "";
if (isset($_GET['reqEmpid']))
{
	$reqEmpid = $_GET['reqEmpid'];
}
?>

<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content" >
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Time Sheet Yearly View</legend>
					<div class="row well">
						<div class="row pkdsearch">
							<form id="viewForm" name="viewForm" method="POST" action="">
								<div class="col-md-2">Year:
									<select name="dd_year" class="form-control input-sm">
										<?php
										  $yr=date("Y");
										  for ($j=$yr;$j>=2011;$j--){
											if ($j == $dd_year){
										 ?>
											<option value="<?php echo($j)?>" selected><?php echo($j)?></option>
										 <?php }else{?>
										 <option value="<?php echo($j)?>"><?php echo($j)?></option>
										 <?php }
										 }?>
									</select>
								</div>
								<div class="col-md-3">Emp Code:
									<?php if($this->input->post('reqEmpid')==null)
										{?>
											 
											<?php if(trim($emp_code) == '') 
											{ ?>
											<input type="text" name="emp_code" placeholder="Enter Employee Code" class="form-control input-sm" <?php if($reqEmpid != ""){ echo "readonly"; } ?>>
											<?php } 
											else
											{ ?>
											<input type="text" name="emp_code" value="<?php echo(stripslashes(htmlspecialchars($emp_code)))?>" class="form-control input-sm" <?php if($reqEmpid != ""){ echo "readonly"; } ?>>
											<?php } ?> 
										<?php }?> 
								</div> 
								<div class="col-md-5">
									<input type="submit" id="btnView" name="btnView" value="View" class="btn btn-sm btn-info" style="margin-top:18px;"/>
								</div>
							</form>  
						</div>
						<?php if($viewMessage == TRUE)
						{?>
						<div class="row pkdsearch">
							<p><?php echo $message;?></p>
						</div>
						<?php }
						else
						{?> 
						<div class="col-md-1" style="padding-left: 0px;">
						</div>
						<div id="floating" class="col-md-11" style="padding-left: 0px; padding-right:0px;z-index: 99;">
							<h3 style="font-size: 16px;"><?php echo 'Timesheet of '.$empName;?></h3>
							<table class="table table-bordered table-fixed months_b">
								<thead> 
									<tr> 
										<?php echo $monthHeader;?>
									</tr>
								</thead>
							</table>
						</div>  
						<table class="table table-bordered table-striped table-fixed"> 
							<tbody class="timesheet">
								<?php 
									for($d=0; $d<37; $d++)
									{  
										echo '<tr>';
										echo $calRow[$d];
										echo '</tr>'; 
									} 
								?>
							</tbody>
						</table>
						<?php } ?>
					</div> 
					<div class="clearfix"></div> 
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>