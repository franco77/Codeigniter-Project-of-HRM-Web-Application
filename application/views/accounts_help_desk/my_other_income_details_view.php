
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 

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
		<h3 align="center">Other Incom Tax Declaration</h3>       
		<div class="form">
			<div class="form1">
				<table cellpadding="0" cellspacing="0" width="100%" class="form1 itax">
					<tr><td width='55%'><strong>Name:&nbsp;</strong><?php echo $empInfo[0]['full_name']; ?></td><td><strong>Designation:&nbsp;</strong><?php echo $empInfo[0]['desg_name']; ?></td></tr>
					<tr><td><strong>Department:&nbsp;</strong><?php echo $empInfo[0]['dept_name']; ?></td><td><strong>Reporting manager's Name:&nbsp;</strong><?php echo $repMgrInfo[0]['full_name']; ?></td></tr>
					<tr><td><strong>Employee ID:&nbsp;</strong><?php echo $empInfo[0]['loginhandle']; ?></td><td><strong>Date of Joinning:&nbsp;</strong><?php echo date('d-m-Y',strtotime($empInfo[0]['join_date'])); ?></td></tr>
					<!--<tr><td><strong>Evaluation Period - From:&nbsp;</strong> 1st April 2015</td><td><strong>To: &nbsp;</strong>31st Mar 2016</td></tr>-->
					<?php  
														$fadte = date('m',strtotime($empInfo[0]['apply_date']));
														if( $fadte >=4 ){
															$fy = date('Y',strtotime($empInfo[0]['apply_date']));
														}
														else if( $fadte <= 3 ){
															$fy = date('Y',strtotime($empInfo[0]['apply_date'])) - 1;
														}
													?>
													<?php //echo $fy.'-'.(string)($fy+1);?>
					<tr><td><strong>Evaluation Period - From:</strong> 1st April <?php echo $fy;?></td><td><strong>To:</strong> 31st Mar <?php echo ($fy+1);?></td></tr>
				</table>
				<table cellpadding="0" cellspacing="0" width="100%" class="form1 itax">
					<tr>
						<td valign="top"> <strong> For the Financial Year</strong></td>
							<td valign="top" colspan="2">2017-18</td>
					</tr>
					<tr>
						<td colspan="1" class="form_title"><strong>Nature Of Payment</strong></td>
						<td colspan="1" class="form_title"><strong>Payment Date</strong></td>
						<td colspan="1" class="form_title"><strong>Amount Paid</strong></td>
					</tr> 
					<tr>
						<td valign="top"> <strong>Project Allowance </strong></td>
						<td valign="top">
							<?php echo $empInfo[0]['project_allowance_date']?>
						</td>
						<td valign="top">
							<?php echo $empInfo[0]['project_allowance_amount']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> <strong>Statutory Bonus</strong></td>
						<td valign="top">
							<?php echo $empInfo[0]['statutory_bonus_date']?>
						</td>
						<td valign="top">
							<?php echo $empInfo[0]['statutory_bonus_amount']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> <strong>Performance Bonus </strong></td>
						<td valign="top">
							<?php echo $empInfo[0]['performance_bonus_date']?>
						</td>
						<td valign="top">
							<?php echo $empInfo[0]['performance_bonus_amount']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> <strong>Incentive Payment </strong></td>
						<td valign="top">
							<?php echo $empInfo[0]['incentive_payment_date']?>
						</td>
						<td valign="top">
							<?php echo $empInfo[0]['incentive_payment_amount']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> <strong>Leave Incashment </strong></td>
						<td valign="top">
							<?php echo $empInfo[0]['leave_incashment_date']?>
						</td>
						<td valign="top">
							<?php echo $empInfo[0]['leave_incashment_amount']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> <strong>Other Payment </strong></td>
						<td valign="top">
							<?php echo $empInfo[0]['other_payment_date']?>
						</td>
						<td valign="top">
							<?php echo $empInfo[0]['other_payment_amount']?>
						</td> 
					</tr> 
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>  
			</div>
		</div>   
	</div>
    
    <div class="clear"></div>
</div>
<div style="height: 50px;">&nbsp;</div>
<p style="text-align: center; font-size: 10px; color: #000; cursor: pointer;"><img alt="Print" onclick="javascript:window.print();" src="<?php echo base_url(); ?>assets/images/printer_icon.png" /></p>
<div style="height: 50px;">&nbsp;</div>
<link rel="stylesheet" media="all" type="text/css" href="<?php echo base_url();?>assets/dist/frontend/main.css" />
                                              
                             