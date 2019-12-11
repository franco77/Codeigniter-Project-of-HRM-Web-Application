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
                                                        <td width="100"><strong> JD:</strong></td>
                                                        <td><?php //if($jd != ""){ echo "<div class='glowingtabs3'><a href='".SITE_BASE_URL."/upload/document/".$jd."' target='_blank'><span>View</span></a></div>";}else{echo "NA";}?></td>
                                                        <td><strong> KRA &amp; KPI:</strong></td>
                                                        <td><?php //if($kpi != ""){ echo "<div class='glowingtabs3'><a href='".SITE_BASE_URL."/upload/document/".$kpi."' target='_blank'><span>View</span></a></div>";}else{echo "NA";}?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <?php
                                            /* if($letterNUM > 0){ $i = 0;
                                                echo '<div class="form marT_10"  style="display:none;"><div class="form1"><table cellpadding="0" cellspacing="0" width="100%">';
                                                echo '<tr><td class="form_title" colspan="4" align="center"><strong>Letters Issued</strong></td></tr>';
                                                echo '<tr>';
                                                echo '<th>Sl. No</th>';
                                                echo '<th>Letter Name</th>';
                                                echo '<th>Issued Date</th>';
                                                echo '<th>&nbsp;</th>';
                                                echo '</tr>';
                                                while($letterINFO = mysql_fetch_array($letterRES)){ $i++;
                                                    echo '<tr>';
                                                    echo '<td>'.$i.'</td>';
                                                    echo '<td>'.$letterINFO["letter_name"].'</td>';
                                                    echo '<td>'.date("d M, Y", strtotime($letterINFO["issued_date"])).'</td>';
                                                    echo '<td>';
                                                    echo "<div class='glowingtabs3'><a href='".SITE_BASE_URL."/upload/document/".$letterINFO["letter_document"]."' target='_blank'><span>View</span></a></div>";
                                                    echo '</td>';
                                                    echo '</tr>';
                                                }
                                                echo '</table></div></div>'; */
                                            //}
                                            ?>
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
                                                            <?php
                                                                /* if($reqSkillNUM > 0){
                                                                    while($reqSkillINFO = mysql_fetch_assoc($reqSkillRES)){
                                                                        echo $reqSkillINFO["skill_name"] . "<br/>";
                                                                    }
                                                                }else{
                                                                    echo "Not Defined";
                                                                } */
                                                                ?>
                                                        </td>
                                                        <td align="center"><?php //echo $actuallSkillList;?></td>
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
                                                        <select name="year" id="year" class="form-control" onchange="document.frmJobUpdate.submit();" style="width: 54%;float: right;">
                                                            <?php
                                                                 $yr=date("Y");
                                                                for ($j=$yr;$j>=2014;$j--){                                        
                                                                ?>                                         
                                                            <option value="<?php echo $j.'-'.($j+1);?>" <?php ///if($ydate==($j) || $_REQUEST['year']==($j)) echo "selected";?>><?php echo $j.'-'.($j+1);?></option>
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
                                                    </tr>
												</thead>
                                                <tbody> 
                                                    <?php 
                                                        /* $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND DATE_FORMAT(annualdate, '%Y') ='".$ydate."'"; 
                                                         $resGoal=mysql_query($qryGoal);
                                                         $numRow= mysql_num_rows($resGoal);                                           
                                                        while($rowGoal = mysql_fetch_array($resGoal)){  */
                                                        ?>
                                                    <tr>
                                                        <td width="40%"><?php //echo $rowGoal['objective']; ?></td>
                                                        <td width="40%"><?php //echo $rowGoal['target']; ?></td>
                                                        <td valign="top" width="20%"><?php //echo $rowGoal['weightage']; ?></td>
                                                    </tr>
                                                    <?php  //}   ?>
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