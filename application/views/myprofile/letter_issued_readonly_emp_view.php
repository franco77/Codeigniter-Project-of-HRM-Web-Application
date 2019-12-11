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
					<a href="<?= base_url('my_account/letter_issued_update_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-upload" aria-hidden="true"></i>&nbsp;Upload</a>
					<legend class="pkheader">Letter Issued</legend>
					<div class="row well">
						<div class="table-responsive">
							<form > 
								
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