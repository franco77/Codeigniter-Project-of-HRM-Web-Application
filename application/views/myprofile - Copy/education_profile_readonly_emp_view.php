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
					<a href="<?= base_url('my_account/education_update_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a> 
					<legend class="pkheader">Education</legend>
					<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed"> 
								<tbody>
								<?php 
								if(count($eduInfo) > 0){
									for($i=0; $i < count($eduInfo); $i++) 
									{
										$j = $i+1;
									?>
										<tr class="info">
											<td colspan="4" class="form_title"><strong>Education Info <?php echo $j;?></strong></td>
										</tr>
										<tr >
											<td width="20%" valign="top"><span class="red">&nbsp;</span> <strong>Course:</strong></td>
											<td width="20%" colspan="" valign="top">
												  <?php echo $eduInfo[$i]['course_type']; ?> 
											</td>
										   
											<td width="20%" valign="top"><span class="red">&nbsp;</span> <strong>Course:</strong></td>
											<td width="20%" colspan="" valign="top">
												  <?php echo $eduInfo[$i]['course_name']; if($eduInfo[$i]['specialization_name']){ echo ' ('.$eduInfo[$i]['specialization_name'].')';}?>
												
											</td>
										
										</tr>
										  <tr>
											<td width="20%" valign="top"><span class="red">&nbsp;</span> <strong>Year of Passing:</strong></td>
											<td width="20%" valign="top">
											<?php echo $eduInfo[$i]['passing_year']?>
											</td>
											<td width="20%" valign="top"><span class="red">&nbsp;</span> <strong>Percentage/CGPA:</strong></td>
											<td width="20%" valign="top">
												<?php echo $eduInfo[$i]['percentage']?>
											</td>
										   
										</tr>
										<tr>
											<td width="20%" valign="top"><span class="red">&nbsp;</span> <strong>Board/University:</strong></td>
											<td width="20%" valign="top">
												<?php echo $eduInfo[$i]['board_university_name']?>
											</td>
											<td width="20%" valign="top">&nbsp;</td>
											<td width="20%" valign="top">&nbsp;</td>
										</tr>
								<?php } } else{ ?>
									<tr>
										<td colspan="4" class=""><strong>No education information added till now.</strong></td>
									</tr>
								<?php }  ?>
								</tbody>
							</table>
							<table class="table table-striped table-bordered table-condensed">
								<tr class="info">
									<td class="form_title" colspan="2"><strong>Education Check List</strong></td>
								</tr>
								<tr>
									<td align="center"><strong>Required</strong></td>
									<td align="center"><strong>Actual</strong></td>
								</tr>
								<tr>
									<td align="center">
										<?php
										if($reqEduNUM > 0){
											while($reqEduINFO){
												echo $reqEduINFO[0]["course_name"] . "<br/>";
											}
										}else{
											echo "Not Defined";
										}
										?>
									</td>
									<td align="center">
										<?php 
										for($i=0; $i < count($eduInfo); $i++) 
										{
											echo $eduInfo[$i]["course_name"] . "<br/>";		
										} ?>
									</td>
								</tr>
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