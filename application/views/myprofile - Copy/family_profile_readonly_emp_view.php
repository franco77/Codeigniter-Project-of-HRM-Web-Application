<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
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
					<a href="<?= base_url('my_account/family_update_emp');?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a> 
					<legend class="pkheader">Family</legend>
					<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed"> 
								<tbody>
									<tr>
                                            <td width="20%" valign="top"><strong>Father's Name:</strong></td>
                                            <td width="20%" valign="top"><?php echo $empInfo[0]['fathers_name']?></td>
	                                    <td width="20%" valign="top"><strong>Father's DOB:</strong></td>
                                            <td width="20%" valign="top"><?php echo $fatherdob?></td>
                                        </tr>
                                        <tr>
	                                    <td width="20%" valign="top"><strong>Mother's Name:</strong></td>
                                            <td width="20%" valign="top"><?php echo $empInfo[0]['mother_name']?></td>
                                            <td width="20%" valign="top"><strong>Mother's DOB:</strong></td>
                                            <td width="20%" valign="top"><?php echo $motherdob?></td>
                                        </tr>
                                          <tr>
                                              <td width="30%" valign="top"><strong>Spouse Name:</strong></td>
                                              <td width="20%" valign="top"><?php echo $empInfo[0]['spouse_name']?></td>
                                              <td width="30%" valign="top"><strong>Spouse DOB:</strong></td>
                                              <td width="20%" valign="top"><?php echo $spousedob?></td>
                                        </tr>
                                        <tr>
                                            <td width="30%" valign="top"><strong>Anniversary Date:</strong></td>
                                            <td width="20%" valign="top"><?php echo $anniversarydate?></td>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
								</tbody>
							</table>
							<?php
							$childRows = count($childInfo); 
							if($childRows > 0)
							{  
                                for($i=0; $i< $childRows; $i++)
                                    {
                                          $i++;
                                          $childdob =  "";
                                          if($childInfo[0]["child_dob"] != "" && $childInfo[0]["child_dob"] != "0000-00-00")
                                              $childdob = date("d M, Y", strtotime($childInfo[0]["child_dob"]));
                                    ?>
                                   
                                          <table class="table table-striped table-bordered table-condensed"> 
                                              <tr class="info"><td colspan="6" class="form_title"><strong>Children Info <?php echo $i;?></strong></td></tr>
                                              <tr>
                                                  <td width="15%"><strong>Name:</strong></td>
                                                  <td width="20%"><?php echo $childInfo[0]['child_name']?></td>
                                                  <td width="15%"><strong>Gender:</strong></td>
                                                  <td width="15%"><?php echo (($childInfo[0]['child_gender'] == "M")?'Male':'Female')?></td>
                                                  <td width="15%"><strong>DOB:</strong></td>
                                                  <td width="20%"><?php echo $childdob?></td>
                                              </tr>
                                         </table>  
                                  <?php } }else {?> 
                                		<table class="table table-striped table-bordered table-condensed"> 
                                			<tr>
										<td colspan="4" class="form_title"><strong>Children Info</strong></td>
	                                        </tr>
	                                        <tr>
										<td colspan="4" class=""><strong>No children information added till now.</strong></td>
	                                        </tr>
	                                 	</table> 
                               	 <?php }?>  
						</div>
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>