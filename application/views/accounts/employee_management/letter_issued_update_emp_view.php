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
						<?php $this->load->view('accounts/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
				<?php $this->load->view('accounts/top_view');?>
					<a href="<?= base_url('en/accounts_admin/letter_issued_readonly_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View</a>
					<legend class="pkheader">Letter Issued</legend>
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
											<td width="30%">
												<input type="file" id="flLetter" name="file" class="form_ui form-control" />
											</td>	
											<td width="30%">
												<input type="text" id="txtLetterTittle" name="txtLetterTittle" placeholder="Letter Tittle" class="form_ui form-control" />
											</td>									
											<td width="30%">
												<input type="text" id="txtIssuedDate" name="txtIssuedDate" placeholder="Issue Date" class="form_ui form-control datepickerShow" />
											</td>
											<td width="10%">
												<input type="submit" id="btnUpdateDoc" name="btnUpdateDoc" class="btn btn-primary pull-right" value="Save" />
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0" width="100%">
										<tr class="info">
											<th width="10%">Sl. No</th>
											<th width="50%">Letter Name</th>
											<th width="20%">Issued Date</th>
											<th width="20%">&nbsp;</th>
										</tr>
										<?php 
										$joinRows = count($joinInfo);
										if($joinRows > 0)
										{
										for($i=0; $i < $joinRows; $i++)
										{ ?>
                                        <tr>
                                            <td><strong><?php echo $i+1;?></strong></td>
                                            <td><strong><?php echo $joinInfo[$i]['letter_name'];?></strong></td>
                                            <td><strong><?php echo date("d M, Y", strtotime($joinInfo[$i]["issued_date"]));?></strong></td>
                                             
                                            <td>
												<a href="<?php echo base_url() ;?>assets/upload/document/<?php echo $joinInfo[$i]['letter_document']?>" target="_blank"><button type="button" class="label label-primary pull-right">View</button></a> 
                                            </td>
                                        </tr>
										 <?php
	                                 	}}
	                                 	else {
	                                 		?>
										<tr>
                                            <td colspan="4" align="center"><strong>No Documents Found. </strong></td>
                                        </tr>
										<?php }?>
	                                </table>
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
<script>
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
});
</script>