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
                    <a href="<?= base_url('my_account/profile_update_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a> 
                    <legend class="pkheader">General</legend>
                    <div class="row well">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-condensed">
                                <tbody>
                                    <tr>
                                        <td width="20%"><strong>Name:</strong></td>
                                        <td width="30%"><?php echo $empInfo[0]['full_name'];?></td>
                                        <td width="50%" colspan="2" rowspan="6" align="center">
                                            <?php 
												if($empInfo[0]['user_photo_name'] != '')
												{
													echo '<img src="'.base_url().'assets/upload/profile/'.$empInfo[0]['user_photo_name'].'" alt="" width="130" height="150" class="form_img" />';
												}
												else
												{
													echo '<img src="'.base_url().'assets/images/no-image.jpg" alt="" class="form_img" />';
												}
												if($empInfo[0]['user_sign_name'] != '')
												{
													echo '<br/><img src="'.base_url().'assets/upload/profile/'.$empInfo[0]['user_sign_name'].'" alt="" width="225" height="40" class="form_img" />';
												}
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Employee Code:</strong></td>
                                        <td><?php echo $empInfo[0]['loginhandle']?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>DOB:</strong></td>
                                        <td><?php echo date("d-m-Y", strtotime($empInfo[0]['dob']))?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Age:</strong></td>
                                        <td>
                                            <?php 
                                                echo $age .' Months,'.$year. ' Years';
                                                ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Highest Qualif:</strong></td>
                                        <td><?php echo $empInfo[0]['course_name'].' ('.$empInfo[0]['specialization_name'].')';?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Location of H. Qualif:</strong></td>
                                        <td><?php
                                            if($empInfo[0]['loc_highest_qual'] != '' && $empInfo[0]['loc_highest_qual'] != '0') echo $empInfo[0]['loc_highest_qual_name'];
                                            	?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Personal Email:</strong></td>
                                        <td><?php echo $empInfo[0]['per_email']?></td>
                                        <td><strong>Official Mobile No:</strong></td>
                                        <td><?php echo $empInfo[0]['official_mobile']?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Gender:</strong></td>
                                        <td><?php echo $gender?></td>
                                        <td><strong>Marital Status:</strong></td>
                                        <td><?php echo $mStatus?></td>
                                    </tr>
                                    <tr>
                                        <!--<td><strong>Em. Contact No:</strong></td>
                                        <td><?php
                                            if($empInfo[0]['phone1'] != ''){
                                            	echo $empInfo[0]['phone1'];
                                            }else{
                                            	echo '--N/A--';
                                            } ?>                                          
                                        </td>-->
                                        <td><strong>Blood Group:</strong></td>
                                        <td><?php echo $empInfo[0]['blood_group'];?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Show Mobile No:</strong></td>
                                        <td colspan="3"><?php
                                            echo ($empInfo[0]['isShowOnSearch'] == 'Y')?'Show':'Do not Show';
                                            echo '</strong> My Mobile No on Polosoft Staff Directory.';
                                            ?>                                          
                                        </td>
                                    </tr>
                                    <tr class="info">
                                        <td colspan="4" class="form_title">Address</td>
                                    </tr>
                                    <tr>
                                        <td valign="top"><strong>Permanent Address:</strong></td>
                                        <td><?php echo $empInfo[0]['address1'].'<br/>'.$empInfo[0]['city_district1'].'<br/>'.$empInfo[0]['state_name1'].' '.$empInfo[0]['pin_zip1'].'<br/>'.$empInfo[0]['country_name1'];?></td>
                                        <td valign="top"><strong>Correspondence Address:</strong></td>
                                        <td><?php echo $empInfo[0]['address2'].'<br/>'.$empInfo[0]['city_district2'].'<br/>'.$empInfo[0]['state_name2'].' '.$empInfo[0]['pin_zip2'].'<br/>'.$empInfo[0]['country_name2'];?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Permanent Landline No:</strong></td>
                                        <td> <?php echo $empInfo[0]['phone1']; ?></td>
                                        <td><strong> Correspondence Landline No:</strong></td>
                                        <td> <?php echo $empInfo[0]['phone2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Permanent Mobile No:</strong></td>
                                        <td><?php echo $empInfo[0]['mobile1']; ?></td>
                                        <td> <strong>Correspondence Mobile No:</strong></td>
                                        <td><strong> <?php echo $empInfo[0]['mobile']; ?></strong></td>
                                    </tr>
                                    <tr class="info">
                                        <td colspan="4" class="form_title">Identification Number(s)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Passport No:</strong></td>
                                        <td><?php echo $empInfo[0]['passport_no']?></td>
                                        <td><strong>PAN Card No:</strong></td>
                                        <td><?php echo $empInfo[0]['pan_card_no']?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Voter ID:</strong></td>
                                        <td><?php echo $empInfo[0]['voter_id']?></td>
                                        <td><strong>Driving License:</strong></td>
                                        <td><?php echo $empInfo[0]['drl_no']?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Aadhar Card No:</strong></td>
                                        <td><?php echo $empInfo[0]['adharcard_no']?></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
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