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
					<a href="<?= base_url('my_account/document_update_emp');?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-upload" aria-hidden="true"></i>&nbsp;Upload</a>
					<legend class="pkheader">Document</legend>
					<div class="row well">
						<div class="table-responsive">
							<form id="frmDocUpdate" name="frmDocUpdate" method="POST" action="" enctype="multipart/form-data"> 
								<?php 
								$joinRows = count($joinInfo);
								if($joinRows > 0)
								{
								for($i=0; $i < $joinRows; $i++)
								{ ?>
                                    <table class="table table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="5%"><strong><?php echo $i+1;?></strong></td>
                                            <td width="40%"><strong><?php echo $joinInfo[$i]['kit_name'];?>:</strong></td>
                                             
                                            <td width="40%">
                                            	  <?php if($joinInfo[$i]['status'] == 'Y'){ ?>
                                             <a href="<?php echo base_url() ;?>assets/upload/document/<?php echo $joinInfo[$i]['document_name']?>" target="_blank"><button type="button" class="label label-primary pull-right">View</button></a> 	
                                              <?php   }?>
                                            </td>
                                        </tr>
	                                </table>
	                                 <?php
	                                 	}}
	                                 	else {
	                                 		?>
	                                 		 <table class="table table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="">
                                            <strong>No Documents Found.</strong>
                                            </td>
                                        </tr>
	                                </table>
	                                 	<?php }?> 
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