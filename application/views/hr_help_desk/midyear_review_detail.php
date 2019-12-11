
<div id="lightbox_form" style="width:900px;">
    <div class="form_bg">    	
                                <h3 align="center">Mid Year Review Form</h3>       
                                <div class="form">
                                 <div class="form1">
                                     <table>
                                         <tr><td width='55%'><strong>Name:</strong><?php echo $rowAppraisal[0]['full_name']; ?></td><td><strong>Designation:</strong><?php echo $rowAppraisal[0]['desg_name']; ?></td></tr>
                                         <tr><td><strong>Department:</strong><?php echo $rowAppraisal[0]['dept_name']; ?></td><td><strong>Reporting manager's Name:</strong><?php echo $rowAppraisal[0]['reporting_manager_full_name']; ?></td></tr>
                                         <tr><td><strong>Evaluation Period - From:</strong> 1st April <?php echo date('Y', strtotime($rowAppraisal[0]['apply_date']));?></td><td><strong>To:</strong>30th Sep <?php echo date('Y', strtotime($rowAppraisal[0]['apply_date']));?></td></tr> 

                                     </table> 
                                    <table cellpadding="0" cellspacing="0" width="100%" class="form1">
                                         <tr><td colspan="3" class="form_title" align="center"><strong>To be filled in by the Appraisee</strong></td></tr>
                                         <tr><td colspan="3" class="form_title"><strong>1. Note your accomplishments/progress on a copy of the Performance Objectives page.</strong><span class="red">*</span> </td></tr>
                                         <td colspan="3">&nbsp;</td>
                                         <tr><td colspan="3"><?php echo $rowAppraisal[0]['accomplishments']; ?></td>
                                        </tr> 
                                       <td colspan="3">&nbsp;</td>
                                         <tr><td colspan="3" class="form_title"><strong>2. What other special contributions did you make during the Mid- Year performance period? please be more specific on this.</strong><span class="red">*</span> </td></tr>
                                         <td colspan="3">&nbsp;</td>
                                         <tr><td colspan="3"><?php echo $rowAppraisal[0]['contributions']; ?></td>
                                        </tr> 
                                        <td colspan="3">&nbsp;</td>
                                         <tr><td colspan="3" class="form_title"><strong>3. Comment on any unplanned events and/or significant problems that may have prevented you from fully achieving performance results so far during this evalution period.</strong><span class="red">*</span> </td></tr>
                                         <td colspan="3">&nbsp;</td>
                                         <tr><td colspan="3"><?php echo $rowAppraisal[0]['unplanned_events']; ?></td> </tr> 
                                         <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3" class="form_title" align="center"><strong>PERFORMANCE OBJECTIVE</strong></td></tr>
                                        <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3">Performance Review -  Insert the performance objectives developed at the beginning of the evaluation period below and note the accomplishments/progress and any comments related to the objective.</td></tr>
                                        <td colspan="3">&nbsp;</td>
                                        <tr>
                                            <td colspan="3">
                                                <table width="100%" border="1" id="dataTable">
                                                    <tbody>
                                                    <tr valign="top">                
                                                        <td style="border: solid 1px #000;"><strong>Sl. No.</strong></td>
                                                        <td style="border: solid 1px #000;"><strong>Performance Standard & Objectives</strong></td>
                                                        <td style="border: solid 1px #000;"><strong>Target</strong></td>
                                                        <td style="border: solid 1px #000;"><strong>Weightage</strong></td>
                                                        <td style="border: solid 1px #000;"> <strong>Accomplishments and Comments</strong> <i>(To be filled by Appraisee)</i></td>
                                                        <td style="border: solid 1px #000;"><strong>Rating </strong><i>(To be filled by Appraiser</i></td>
                                                    </tr>
                                                    
                                                     <?php
    
                                                        $i = $j = $k =0;
                                                       for($m=0; $m<count($rowGoal); $m++){                                                                
                                                            $i++;$k++
                                                     
                                                     ?>
                                                    <tr>      
                                                        <td><?php echo $k; ?></td>
                                                        <td><?php echo $rowGoal[$m]['objective']; ?></td>
                                                        <td><?php echo $rowGoal[$m]['target']; ?></td>
                                                        <td><?php echo $rowGoal[$m]['weightage']; ?></td>
                                                        <td><?php echo $rowGoal[$m]['comment']; ?></td>
                                                        <td><?php if($rowGoal[$m]['rating']=='Progressing'){ $j++; echo "Progressing"; }else { echo "NotProgressing"; } ?> </td>
                                                    </tr> 
                                                     <?php
                                                        }
                                                     ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                       
                                        </tr>
                                        <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3" class="form_title"><strong>Performance Areas Meeting and Exceeding Expectation: </strong><i>(To be filled by Appraiser)</i>.<span class="red">*</span> </td></tr>
                                         <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3"><?php echo $rowAppraisal[0]['exceeding_expectation']; ?></td></tr> 
                                        <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3" class="form_title"><strong>Performance Areas Identified for Improvement: </strong><i>(To be filled by Appraiser)</i>.<span class="red">*</span> </td></tr>
                                        <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3"><?php echo $rowAppraisal[0]['improvement']; ?></td> </tr> 
                                        <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3" class="form_title"><strong>Additional Discussion Items: </strong><i>(e.g. project update, progress on priorities, training and professional development, employee's <i>(To be filled by Appraiser)</i> concern, due dates)</i>.<span class="red">*</span> </td></tr>
                                         <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3"><?php echo $rowAppraisal[0]['discussion']; ?></td> </tr> 
                                         <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3" class="form_title"><strong>Summary of Expectation: </strong><i>(To be filled by Appraiser)</i>.<span class="red">*</span> </td></tr>
                                         <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3"><?php echo $rowAppraisal[0]['summary_expectation']; ?></td></tr> 
                                        <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3" class="form_title"><strong>Next Step in Employee Development: </strong><i>(To be filled by Appraiser)</i>.<span class="red">*</span> </td></tr>
                                         <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3"><?php echo $rowAppraisal[0]['employee_development']; ?></td></tr> 
                                         <td colspan="3">&nbsp;</td>
                                        <tr><td colspan="3">Total No.of Performance Objective = <?php echo $i; ?><br/>No.of Progressive Rating= <?php echo $j ?><br/>% of Progressive Rating= <?php if($j > 0){ echo number_format((float)($j/$i*100), 2, '.', ''); } else { echo $j;}?></td></tr>
                                        <tr><td colspan="3">Please sign below indicating we have discussed all of the above items.</td></tr>	
					<tr><td colspan="3"><table>
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
<link rel="stylesheet" media="all" type="text/css" href="<?php echo base_url(); ?>assets/css/main.css" />
                                              
                             