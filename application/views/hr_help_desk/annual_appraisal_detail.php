<?php 

$successMsg = FALSE;  

//Query for select
/* 

$repMgrSql = "SELECT login_id, full_name, email FROM `internal_user` WHERE login_id =(SELECT reporting_to FROM `internal_user` WHERE login_id = '".$rowAppraisal['login_id']."')";
$repMgrRes = mysql_query($repMgrSql);
$repMgrInfo = mysql_fetch_row($repMgrRes);  */
?>

<style>
    a.tooltip {outline:none; text-decoration: none;
    background: none repeat scroll 0 0 #06c;
    border-radius: 50%;
    box-shadow: 1px 1px 2px rgba(255, 255, 255, 0.6) inset, -1px -1px 2px rgba(0, 0, 0, 0.6) inset;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-size: 13px;
    font-weight: bold;
    height: 15px;
    line-height: 15px;    
    text-align: left;
    vertical-align: middle;
    width: 15px;
    }
    a.tooltip strong {line-height:30px;} 
    a.tooltip:hover {text-decoration:none;font-weight: normal;} 
    a.tooltip span { z-index:10;display:none; padding:14px 20px; margin-top:-30px; margin-left:15px; width:300px; line-height:16px; }
    a.tooltip:hover span{ display:inline; position:absolute; color:#111; border:1px solid #DCA; background:#fffAF0;} 
    .callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;} 
    /*CSS3 extras*/ 
    a.tooltip span { border-radius:4px; box-shadow: 5px 5px 8px #CCC; }
    #dataTable td{
        padding: 4px 7px !important;
    }
</style>
<div id="lightbox_form" style="width:900px;">
    <div class="form_bg">    	
                                <h3 align="center">Annual Appraisal Form</h3>       
                                <div class="form">
                                 <div class="form1">
                                     <table width="100%" >
                                         <tr><td width='50%'><strong>Name:&nbsp;</strong><?php echo $rowAppraisal[0]['full_name']; ?></td><td width='50%'><strong>Designation:&nbsp;</strong><?php echo $rowAppraisal[0]['desg_name']; ?></td></tr>
                                         <tr><td><strong>Department:&nbsp;</strong><?php echo $rowAppraisal[0]['dept_name']; ?></td><td><strong>Reporting manager's Name:&nbsp;</strong><?php echo $rowAppraisal[0]['reporting_manager_full_name']; ?></td></tr>
                                         <tr><td><strong>Employee ID:&nbsp;</strong><?php echo $rowAppraisal[0]['loginhandle']; ?></td><td><strong>Date of Joinning:&nbsp;</strong><?php echo date('d-m-Y',strtotime($rowAppraisal[0]['join_date'])); ?></td></tr>
                                         <!--<tr><td><strong>Evaluation Period - From:&nbsp;</strong> 1st April 2015</td><td><strong>To: &nbsp;</strong>31st Mar 2016</td></tr>-->
										<tr><td><strong>Evaluation Period - From:</strong> 31st Mar <?php echo date("Y",strtotime("-1 year"));?></td><td><strong>To:</strong> 1st April <?php echo date('Y', strtotime($rowAppraisal[0]['apply_date']));?></td></tr>
                                     </table> 
                                    <table cellpadding="0" cellspacing="0" width="100%" class="form1">
                                        <tr><td>&nbsp;</td></tr>  
                                         <tr><td class="form_title"><strong>General Guidelines:</strong></td></tr>
                                      <tr><td colspan="5">
                                         <p>The Appraisal Form has two sections: </p>
                                         <br/>
                                         <ol style="margin-left: 25px;">                                             
                                                <li>Section A (Performance Objectives) </li>
                                                <li>Section B (Competency Assessment) </li>                                              
                                            </ol>
                                         <br/>                                     
                                            <p>Performance for different category is graded into the following: </p>
                                            <table>
                                              <tr>
                                                <td width="53" valign="top" style="border: 1px solid #000;"><p>Rating </p></td>
                                                <td width="439" valign="top" style="border: 1px solid #000;"><p> </p></td>
                                              </tr>
                                              <tr>
                                                <td width="53" valign="top" style="border: 1px solid #000;"><p>5 </p></td>
                                                <td width="439" valign="top" style="border: 1px solid #000;"><p>Performance is exceptional and far exceeds the standards </p></td>
                                              </tr>
                                              <tr>
                                                <td width="53" valign="top" style="border: 1px solid #000;"><p>4 </p></td>
                                                <td width="439" valign="top" style="border: 1px solid #000;"><p>Performance is above standards </p></td>
                                              </tr>
                                              <tr>
                                                <td width="53" valign="top" style="border: 1px solid #000;"><p>3 </p></td>
                                                <td width="439" valign="top" style="border: 1px solid #000;"><p>Performance is consistent and clearly meets the standards. </p></td>
                                              </tr>
                                              <tr>
                                                <td width="53" valign="top" style="border: 1px solid #000;"><p>2 </p></td>
                                                <td width="439" valign="top" style="border: 1px solid #000;"><p>Performance is short of standards but can be improved. </p></td>
                                              </tr>
                                              <tr>
                                                <td width="53" valign="top" style="border: 1px solid #000;"><p>1 </p></td>
                                                <td width="439" valign="top" style="border: 1px solid #000;"><p>Performance does not meet the standards. </p></td>
                                              </tr>
                                            </table>
                                            <br/>                                  
                                            <p >Any specific achievement and outstanding contribution may be supported by your remarks and justification in a separate sheet.  
                                                For statements that do not apply to the person being evaluated,<span style="color: red;"> please mark &#8220;Not Applicable&#8221; (NA)</span>.  
                                                Comments should be specific (including examples) and explanatory. </p>
                                          </td>
                                          
                                      </tr>
                                       <tr><td class="form_title" align="center"><strong>SECTION A - Performance Objectives</strong></td></tr>
                                      <tr><td colspan="5">
                                         
                                          </td>
                                      </tr>
                                        <tr>
                                            <td>
                                                <table width="100%" border="1" id="dataTable">
                                                     <tbody>
                                                    <tr valign="top">                
                                                        <td style="border: solid 1px #000;"><strong>Sl. No.</strong></td>
                                                        <td style="border: solid 1px #000;"><strong>Performance Standard & Objectives</strong></td>
                                                        <td style="border: solid 1px #000;"><strong>Target</strong></td>
                                                        <td style="border: solid 1px #000;"><strong>Weightage</strong></td>
                                                        <td style="border: solid 1px #000;"> <strong>Actual Achievement (by appraiser)</strong></td>
                                                        <td style="border: solid 1px #000;"><strong>Achievement in % (by appraiser) </strong></td>
                                                        <td style="border: solid 1px #000;"><strong>Score (Weightage * Achievement %) </strong></td>
                                                    </tr>
                                                    
                                                     <?php 
                                                        $divisible=5;
														$total_score_aa = $i = $k =0;
                                                       for($m=0; $m<count($rowGoal); $m++){                                                           
                                                            $i++;$k++;
															if($rowGoal[$m]['score'] !=""){
																$total_score_aa = $total_score_aa + $rowGoal[$m]['score'];
															}
                                                     ?>
                                                    <tr valign="top">      
                                                        <td><?php echo $k; ?></td>
                                                        <td><?php echo $rowGoal[$m]['objective']; ?></td>
                                                        <td><?php echo $rowGoal[$m]['target']; ?></td>
                                                        <td><?php echo $rowGoal[$m]['weightage']; ?></td>
                                                        <td><?php echo $rowGoal[$m]['act_achievement']; ?></td>
                                                        <td><?php echo $rowGoal[$m]['achievement_per']; ?></td> 
                                                        <td><?php echo $rowGoal[$m]['score']; ?></td>
                                                         
                                                    </tr> 
                                                     <?php
                                                        }
                                                     ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                       
                                        </tr>
                                        <tr><td align="center"><strong>Total score in section A: &nbsp;<?php echo $rowAppraisal[0]['total_score_a'];?></strong> </td></tr>
                                        <tr><td>&nbsp;</td></tr>  
                                        <tr><td>
                                        <table width="100%" border="1" id="dataTable">
                                        <tr><td colspan="7" class="form_title" align="center"><strong>Section B- Competency Assessment </strong> </td></tr>  
                                        <tr><td  colspan="7"><p><span style="color: red;">Please rate in the 5 POINT RATING SCALE.</span></p></td></tr>
                                        <tr>
                                                <td><strong>Competencies</strong></td>
                                                <td>&nbsp;</td>
                                                <td><input type="text" value="Ind. Comment" readonly="" style="width:85px; font-weight: bold;" /></td>
                                                <td><input type="text" value="Ind. Rating" readonly="" style="width:75px;  font-weight: bold;" /></td>
                                                <td><input type="text" value="Sup. Comment" readonly="" style="width:90px;  font-weight: bold;" /></td>
                                                <td><input type="text" value="Sup. Rating" readonly="" style="width:75px;  font-weight: bold;" /></td>
                                                <td><input type="text" value="Final rating" readonly="" style="width:75px;  font-weight: bold;" />
                                                </td>
                                        </tr>
                                        <tr valign="top">
                                            <td> <strong>Job Knowledge :</strong> <span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
                                                     <span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
                                                       <ol>                                             
                                                            <li>Possesses knowledge of work procedures and requirements of job.</li>
                                                            <li>Shows technical competence/skill in area of specialization.</li>                                              
                                                       </ol>
                                                    </span> 
                                                </a></td>
                                            <td>:</td>
                                            <td><?php echo $rowAppraisal[0]['knowledge_job_ind_comment']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['knowledge_job_ind_rating']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['knowledge_job_sup_comment']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['knowledge_job_sup_rating']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['knowledge_job_fin_rating']; ?> </td>
                                            </tr>
                                         <tr valign="top">
                                            <td><strong>Quality of Work :</strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
                                                     <span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
                                                       <ol>                                             
                                                            <li>Is accurate, thorough and careful with work performed.</li>                                                                                                       
                                                       </ol>
                                                    </span> 
                                                </a></td>
                                            <td>:</td>
                                            <td><?php echo $rowAppraisal[0]['quality_work_ind_comment']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['quality_work_ind_rating']; ?></td>
                                               <td> <?php echo $rowAppraisal[0]['quality_work_sup_comment']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['quality_work_sup_rating']; ?></td>
                                               <td> <?php echo $rowAppraisal[0]['quality_work_fin_rating']; ?></td>
                                            </tr>                                       
                                           <tr valign="top">
                                             <td><strong>Quantity of Work :</strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
                                                     <span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
                                                       <ol>                                             
                                                            <li>Is able to handle a reasonable volume of work.</li>                                                                                                       
                                                       </ol>
                                                    </span> 
                                                </a></td>
                                                <td>:</td>
                                                <td valign="top"><?php echo $rowAppraisal[0]['quantity_work_ind_comment']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['quantity_work_ind_rating']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['quantity_work_sup_comment']; ?></td>
                                               <td> <?php echo $rowAppraisal[0]['quantity_work_sup_rating']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['quantity_work_fin_rating']; ?></td>
                                             </tr>
                                        <tr valign="top">
                                            <td> <strong>Work Attitude : </strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
                                                     <span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
                                                      <ol>                                             
                                                            <li>Displays commitment to work.</li> 
                                                            <li>Is proactive and displays initiative.</li>         
                                                            <li>Displays a willingness to learn.</li>         
                                                       </ol>
                                                    </span> 
                                                </a></td>
                                            <td>:</td>
                                            <td><?php echo $rowAppraisal[0]['work_attitude_ind_comment']; ?></td>
                                               <td> <?php echo $rowAppraisal[0]['work_attitude_ind_rating']; ?></td>
                                              <td> <?php echo $rowAppraisal[0]['work_attitude_sup_comment']; ?></td>
                                              <td>  <?php echo $rowAppraisal[0]['work_attitude_sup_rating']; ?></td>
                                              <td>  <?php echo $rowAppraisal[0]['work_attitude_fin_rating']; ?></td>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td> <strong>Teamwork : </strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
                                                     <span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
                                                      <ol>                                             
                                                            <li>Able and willing to work effectively with others in a team.</li>                                                            
                                                       </ol>
                                                    </span> 
                                                </a></td>
                                            <td>:</td>
                                            <td><?php echo $rowAppraisal[0]['teamwork_ind_comment']; ?></td>
                                              <td><?php echo $rowAppraisal[0]['teamwork_ind_rating']; ?></td>
                                             <td>   <?php echo $rowAppraisal[0]['teamwork_sup_comment']; ?></td>
                                              <td>  <?php echo $rowAppraisal[0]['teamwork_sup_rating']; ?></td>
                                              <td>  <?php echo $rowAppraisal[0]['teamwork_fin_rating']; ?></td>
                                        </tr>
                                        <?php //echo $rowAppraisal['user_role'].'---'.$_SESSION['user_role'];exit; 
                                        if(($rowAppraisal[0]['user_role'] < 5) && ($this->session->userdata('user_role') < 5)){ $divisible=9; ?>
                                        <tr><td  colspan="7" class="form_title"><strong>For Employees in Leadership role</strong> </td></tr>
                                         <tr valign="top">
                                               <td ><strong>Problem Solving</strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
                                                     <span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
                                                       <ol>                                             
                                                            <li>Helps resolve staff problems on work-related matters.</li>       
                                                             <li>Handles problem situations effectively .</li>       
                                                       </ol>
                                                    </span> 
                                                </a></td>
                                                <td>:</td>
                                                <td><?php echo $rowAppraisal[0]['problem_solving_ind_comment']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['problem_solving_ind_rating']; ?></td>
                                               <td> <?php echo $rowAppraisal[0]['problem_solving_sup_comment']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['problem_solving_sup_rating']; ?></td>
                                               <td> <?php echo $rowAppraisal[0]['problem_solving_fin_rating']; ?></td>
                                             </tr>
                                               <tr valign="top">
                                               <td ><strong>Responsibility</strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
                                                     <span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
                                                       <ol>                                             
                                                            <li>Is trustworthy, responsible and reliable.</li>      
                                                       </ol>
                                                    </span> 
                                                </a></td>
                                                <td>:</td>
                                                <td><?php echo $rowAppraisal[0]['responsibility_ind_comment']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['responsibility_ind_rating']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['responsibility_sup_comment']; ?></td>
                                               <td><?php echo $rowAppraisal[0]['responsibility_sup_rating']; ?></td>
                                               <td> <?php echo $rowAppraisal[0]['responsibility_fin_rating']; ?></td>
                                             </tr>
                                               <tr valign="top">
                                               <td ><strong>Motivation of Staff</strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
                                                     <span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
                                                       <ol>                                             
                                                            <li>Is a positive role model for other staff.</li>         
                                                       </ol>
                                                    </span> 
                                                </a></td>
                                                <td>:</td>
                                                <td><?php echo $rowAppraisal[0]['motivation_ind_comment']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['motivation_ind_rating']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['motivation_sup_comment']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['motivation_sup_rating']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['motivation_fin_rating']; ?></td>
                                             </tr>
                                               <tr valign="top">
                                               <td ><strong>Delegation of work</strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
                                                     <span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
                                                       <ol>                                             
                                                            <li>Empowers and encourages subordinates to handle the task independently.</li>        
                                                       </ol>
                                                    </span> 
                                                </a></td>
                                                <td>:</td>
                                                <td><?php echo $rowAppraisal[0]['delegation_work_ind_comment']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['delegation_work_ind_rating']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['delegation_work_sup_comment']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['delegation_work_sup_rating']; ?></td>
                                                <td><?php echo $rowAppraisal[0]['delegation_work_fin_rating']; ?></td>
                                             </tr>
                                             
                                        <?php } ?>
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td align="center"  colspan="7"><strong>Total score in section B:&nbsp;<?php echo substr(($rowAppraisal[0]['knowledge_job_fin_rating']+$rowAppraisal[0]['quality_work_fin_rating']+$rowAppraisal[0]['quantity_work_fin_rating']+$rowAppraisal[0]['work_attitude_fin_rating']+$rowAppraisal[0]['teamwork_fin_rating']+$rowAppraisal[0]['problem_solving_fin_rating']+$rowAppraisal[0]['responsibility_fin_rating']+$rowAppraisal[0]['motivation_fin_rating']+$rowAppraisal[0]['delegation_work_fin_rating'])/$divisible, 0, 4);?></strong> </td></tr>
                                        <tr><td>&nbsp;</td></tr>
                                        </td></tr> 
                                         </table>
                                        <tr><td class="form_title" align="center"><strong>To be filled in by Appraisee:</strong> </td></tr>
                                        
                                        <tr><td><strong>I. Other assignments/tasks that you would like to mention other than those stated in section- A.</strong><span class="red">*</span> </td></tr>
                                         <tr><td>&nbsp;</td></tr> 
                                        <tr><td><?php echo $rowAppraisal[0]['assignments_other']; ?></td></tr> 
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td ><strong>II. Specify the key focus areas of your improvement.</strong><span class="red">*</span> </td></tr>
                                         <tr><td>&nbsp;</td></tr> 
                                        <tr><td><?php echo $rowAppraisal[0]['key_improvement']; ?></td></tr> 
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td ><strong>III. Suggest the ways of improvement.</strong><span class="red">*</span> </td></tr>
                                         <tr><td>&nbsp;</td></tr> 
                                        <tr><td><?php echo $rowAppraisal[0]['way_improvement']; ?></td> </tr> 
                                        <tr><td>&nbsp;</td></tr> 
                                        
                                        <tr><td class="form_title" align="center"><strong>To be filled in by Appraiser:</strong> </td></tr>                                        
                                        <tr><td ><strong>I. Describe the appraisee's areas of additional responsibilities and/or other work-related achievements</strong> <span class="red">*</span> </td></tr>
                                         <tr><td>&nbsp;</td></tr> 
                                        <tr><td><?php echo $rowAppraisal[0]['additional_responsibilities']; ?></td></tr> 
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td ><strong>II. List the appraisee's strengths.</strong><span class="red">*</span> </td></tr>
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td><?php echo $rowAppraisal[0]['appraisee_strengths']; ?></td></tr> 
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td ><strong>III. List the areas for improvement</strong><span class="red">*</span> </td></tr>
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td><?php echo $rowAppraisal[0]['areas_improvement']; ?></td></tr> 
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td ><strong>IV. What specific plans of action, including training, will be taken to help the appraisee in their current job or for possible advancement in the company?</strong><span class="red">*</span> </td></tr>
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td><?php echo $rowAppraisal[0]['action_plans']; ?></td></tr> 
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td class="form_title"><strong>Promotion Recommendation:</strong>&nbsp;&nbsp;<input type="radio" name="promotion" value="<?php echo $rowAppraisal[0]['promotion']; ?>" checked="" /> <?php echo $rowAppraisal[0]['promotion']; ?> </td></tr>
                                        <tr><td>&nbsp;</td></tr>                                       
                                        <tr><td class="form_title"><strong>To be filled in by the Reviewing manager (Feedback/Recommendation): </strong> </td></tr>
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td><?php echo $rowAppraisal[0]['recommendation']; ?></td></tr> 
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td>&nbsp;</td></tr> 
                                        <tr><td>Please sign below indicating we have discussed all of the above items.</td></tr>	
					<tr><td><table>
                                         <tr><td width='300px'>____________________</td><td width='300px'>____________________</td><td width='300px'>____________________</td></tr>
                                         <tr><td width='300px'>Employee</td><td width='300px'>Reporting Manager</td><td width='300px'>Reviewing Manager</td></tr>
                                         
                                     </table> </td></tr>			
						</table>
                                    </div>
                                </div>   
                                </div>
    
    <div class="clear"></div>
</div>
<div style="height: 50px;">&nbsp;</div>
<p style="text-align: center; font-size: 10px; color: #000; cursor: pointer;"><img alt="Print" onclick="javascript:window.print();" src="<?php echo base_url(); ?>assets/images/printer_icon.png" /></p>
<div style="height: 50px;">&nbsp;</div>
<link rel="stylesheet" media="all" type="text/css" href="<?php echo base_url(); ?>assets/dist/frontend/main.css" />
                                              
                             