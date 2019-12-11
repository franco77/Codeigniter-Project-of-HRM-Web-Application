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
					<a href="<?= base_url('my_account/job_description_update_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a> 
                    <legend class="pkheader">JD/Goal</legend>
                    <div class="row well">
                        <div class="table-responsive">
                            <div class="content_right">
                                <div class="pad_10">
                                    <?php //$activeProfileTab = "Job"; include("../include/profile-sub-header.php");?>		
                                    <form id="frmJobUpdate" name="frmJobUpdate" method="POST" action="<?php //echo $PHP_SELF.'?id='.$loginID;?>" enctype="multipart/form-data">
                                        <div class="form">
                                            <div class="form1 multiSelectHolder">
                                                <table class="table table-striped table-bordered table-condensed">
                                                    <tr>
                                                        <td width="20%"><strong> JD:</strong></td>
                                                        <td width="30%"> <?php if($empInfo[0]["jd_document"] != ""){ echo "<div class='glowingtabs3'><a href='".base_url()."assets/upload/jd_goal/".$empInfo[0]["jd_document"]."' target='_blank'><span>View</span></a></div>";}?>&nbsp;</td>
                                                        <td width="20%"><strong> KRA &amp; KPI:</strong></td>
                                                        <td width="30%"><?php if($empInfo[0]["kpi_document"] != ""){ echo "<div class='glowingtabs3'><a href='".base_url()."assets/upload/jd_goal/".$empInfo[0]["kpi_document"]."' target='_blank'><span>View</span></a></div>";}?>&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                      
                                        <div class="form marT_10">
                                            <div class="form1">
                                                <table class="table table-striped table-bordered table-condensed">
                                                    <tr class="info">
                                                        <td colspan="2" align="center"><strong>Skills Check List</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center"><strong>Required</strong></td>
                                                        <td align="center"><strong>Actual</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center">
                                                            <?php if(!empty($regSkillInfo[0]['skill_name'])) echo $regSkillInfo[0]['skill_name']; else echo "N/A";  ?>
                                                        </td>
                                                        <td align="center"><?php
																for($i=0; $i<COUNT($skillInfo); $i++) { 
                                                                echo $skillInfo[$i][0]['skill_name'].'<br>';
															} ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form1 multiSelectHolder">
                                            <table class="table table-striped table-bordered table-condensed">
                                                <tr class="info">
                                                    <td colspan="4" align="center">
														<strong>Goal Setting</strong>
													</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" width="50%"><span style="line-height: 3;"><strong>Individual Goals</strong></span></td>
                                                    <td colspan="2">
															<span style="line-height: 3;"><strong>Choose financial year</strong></span>
                                                        <?php if(empty($qryInfo[0]['annualdate'])) $yrrr = date("Y"); else $yrrr = date_parse($qryInfo[0]['annualdate'])['year']; ?>
														<select name="year" id="year" class="form-control" onchange="document.frmJobUpdate.submit();" style="width: 54%;float: right;">
															<?php
																$yr=date("Y");
																$ydate = $yr;
																for ($j=$yr;$j>=2014;$j--){                                         
															?>                                         
															<option value="<?php echo $j+1?>" <?php  if($dyear == $j+1) echo "selected"; ?> ><?php echo $j.'-'.($j+1);?></option>
															<?php 
																}?>
															
														</select>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table class="table table-striped table-bordered table-condensed">
												<thead>
													<tr class="info">
                                                        <th>Performance Objectives</th>
                                                        <th>Target</th>
                                                        <th>Weightage</th>
                                                        <th>Progress(%)</th>
                                                    </tr>
												</thead>
                                                <tbody> 
                                                    <?php 
                                                       $qryInfo_count = COUNT($qryInfo);
													  for($i=0; $i<$qryInfo_count; $i++) { ?>
                                                    <tr>
                                                        <td width="40%"><?php echo $qryInfo[$i]['objective']; ?></td>
                                                        <td width="40%"><?php echo $qryInfo[$i]['target']; ?></td>
                                                        <td valign="top" width="10%"><?php echo $qryInfo[$i]['weightage']; ?></td>
                                                        <td valign="top" width="10%"><?php echo $qryInfo[$i]['progress']; ?></td>
                                                    </tr>
                                                    <?php  }   ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
</div>