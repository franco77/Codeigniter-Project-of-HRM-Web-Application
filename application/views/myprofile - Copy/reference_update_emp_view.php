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
					<a href="<?= base_url('my_account/reference_readonly_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View</a> 
					<legend class="pkheader">Reference Update</legend>
					<div class="row well">
						
						<?php //$activeProfileTab = "Reference"; $action = "Edit"; include("../include/profile-sub-header.php");?>		
                                <form id="frmRefUpdate" name="frmRefUpdate" method="POST" action="<?php //echo $actionURL;?>" enctype="multipart/form-data">
                                <div class="form">
                                    <div class="form1">
                                    	<?php 
                                    	//for($i=1;$i<=3;$i++)
                                    	// {
                                    		//$refInfo = mysql_fetch_assoc($refRes);
                                    	?>
                                    <table class="table table-striped table-bordered table-condensed">
                                    	<tr class="info">
											<td colspan="4" class="form_title"><strong>Reference 1</strong></td>
	                                    </tr>
                                        <tr>
                                            <td> <strong> Name:</strong></td>
                                            <td><input type="text" id="txtref_name_1" name="txtref_name_1" value="<?php if(count($refInfo)>0){ echo $refInfo[0]['ref_name']; } ?>" class=" form-control" style="width:190px;" /></td>
                                        <td> <strong>Company Name:</strong></td>
                                            <td><input type="text" id="txtcomp_name_1" name="txtcomp_name_1" value="<?php if(count($refInfo)>0){ echo $refInfo[0]['comp_name']; }?>" class=" form-control" style="width:190px;" /></td> 
                                        </tr>
                                        <tr>
                                        </tr>
                                        <tr>
                                            <td> <strong>Designation</strong></td>
                                            <td><input type="text" id="txtdesignation_1" name="txtdesignation_1"  value="<?php if(count($refInfo)>0){ echo $refInfo[0]['designation']; }?>" class=" form-control" style="width:190px;" />
	                                		</td>
	                                		 <td> <strong>Contact No.</strong></td>
                                            <td><input type="text" id="txtcont_no_1" name="txtcont_no_1"  value="<?php if(count($refInfo)>0){ echo $refInfo[0]['cont_no']; }?>" class=" form-control" style="width:190px;" />
	                                		<input type="hidden" id="txtref_id_1" name="txtref_id_1" value="<?php if(count($refInfo)>0){ echo $refInfo[0]['ref_id']; }?>"
	                                		</td> 
                                        </tr>
	                                </table>
                                    <table class="table table-striped table-bordered table-condensed">
                                    	<tr class="info">
											<td colspan="4" class="form_title"><strong>Reference 2</strong></td>
	                                    </tr>
                                        <tr>
                                            <td> <strong> Name:</strong></td>
                                            <td><input type="text" id="txtref_name_2" name="txtref_name_2" value="<?php if(count($refInfo)>1){ echo $refInfo[1]['ref_name']; } ?>" class=" form-control" style="width:190px;" /></td>
                                        <td> <strong>Company Name:</strong></td>
                                            <td><input type="text" id="txtcomp_name_2" name="txtcomp_name_2" value="<?php if(count($refInfo)>1){ echo $refInfo[1]['comp_name']; }?>" class=" form-control" style="width:190px;" /></td> 
                                        </tr>
                                        <tr>
                                        </tr>
                                        <tr>
                                            <td> <strong>Designation</strong></td>
                                            <td><input type="text" id="txtdesignation_2" name="txtdesignation_2"  value="<?php if(count($refInfo)>1){ echo $refInfo[1]['designation']; }?>" class=" form-control" style="width:190px;" />
	                                		</td>
	                                		 <td> <strong>Contact No.</strong></td>
                                            <td><input type="text" id="txtcont_no_2" name="txtcont_no_2"  value="<?php if(count($refInfo)>1){ echo $refInfo[1]['cont_no']; }?>" class=" form-control" style="width:190px;" />
	                                		<input type="hidden" id="txtref_id_2" name="txtref_id_2" value="<?php if(count($refInfo)>1){ echo $refInfo[1]['ref_id']; }?>"
	                                		</td> 
                                        </tr>
	                                </table>
                                    <table class="table table-striped table-bordered table-condensed">
                                    	<tr class="info">
											<td colspan="4" class="form_title"><strong>Reference 3</strong></td>
	                                    </tr>
                                        <tr>
                                            <td> <strong> Name:</strong></td>
                                            <td><input type="text" id="txtref_name_3" name="txtref_name_3" value="<?php if(count($refInfo)>2){ echo $refInfo[2]['ref_name']; } ?>" class=" form-control" style="width:190px;" /></td>
                                        <td> <strong>Company Name:</strong></td>
                                            <td><input type="text" id="txtcomp_name_3" name="txtcomp_name_3" value="<?php if(count($refInfo)>2){ echo $refInfo[2]['comp_name']; }?>" class=" form-control" style="width:190px;" /></td> 
                                        </tr>
                                        <tr>
                                        </tr>
                                        <tr>
                                            <td> <strong>Designation</strong></td>
                                            <td><input type="text" id="txtdesignation_3" name="txtdesignation_3"  value="<?php if(count($refInfo)>2){ echo $refInfo[2]['designation']; }?>" class=" form-control" style="width:190px;" />
	                                		</td>
	                                		 <td> <strong>Contact No.</strong></td>
                                            <td><input type="text" id="txtcont_no_3" name="txtcont_no_3"  value="<?php if(count($refInfo)>2){ echo $refInfo[2]['cont_no']; }?>" class=" form-control" style="width:190px;" />
	                                		<input type="hidden" id="txtref_id_3" name="txtref_id_3" value="<?php if(count($refInfo)>2){ echo $refInfo[2]['ref_id']; }?>"
	                                		</td> 
                                        </tr>
	                                </table>
	                                 <?php
	                                 	//}?>
	                                 <div class="submtSec">
										<div class="row">
											<div class="msg-sec"></div>
                                            <input type="button" id="btnUpdateRef" name="btnUpdateRef" class="btn btn-sm btn-info pull-right" value="Update" onclick="UpdateReference(this)"  />
	                                     <div class="clear"></div>
										</div>
                                	</div>
                                    </div>
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