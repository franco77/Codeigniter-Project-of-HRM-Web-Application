<?php  
$Header='Account Help Desk';
$subheader= 'Payroll Help';
$nested_link = 'Download Salary Slip';
  
$count=  count($rowResume);
$content=''; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?= $Header ?></title>
  </head>
  
<body style="margin-left: 40px;">
<br>
	<table cellpadding="0" cellspacing="0" width="100%" class="form1" style=" margin-left:5em;">
		<tr>
			<td><strong><center><font size="6"><?= $rowResume[0]['full_name'] ?></font></center></strong></td>
		</tr>
		<tr>
			<td><strong><center><?= $rowResume[0]['desg_name'] ?></center></strong></td>
		</tr>
	</table>
	<br>
	<table cellpadding="0" cellspacing="0" width="100%" class="form1">
		<tr>
			 <td colspan = "2"><h3><strong>TECHNICAL SKILLS</strong></h3></td>
		</tr>
		<tr>
			<td>CAD Softwares</td>
			<td><?= $rowResume[0]['cad_software'] ?></td>
		</tr>
		<tr>
			<td>GIS Softwares</td>
			<td><?= $rowResume[0]['gis_software'] ?></td>
		</tr>
		<tr>
			<td> Technical Languages</td>
			<td><?= $rowResume[0]['tech_languages'] ?></td>
		</tr>
		<tr>
			<td>Operating System</td>
			<td><?= $rowResume[0]['operating_system'] ?></td>
		</tr>
		<tr>
			<td>Others (please specify)</td>
			<td><?= $rowResume[0]['tech_others'] ?></td>
		</tr>
	</table>
	<br>
	<table cellpadding="0" cellspacing="0" width="100%" class="form1">
		<tr>
			 <td><h3><strong>MAJOR FUCTIONAL AREAS</strong></h3></td>
		</tr>
		<br>
		<tr>
			<td><?= $rowResume[0]['functional_areas'] ?></td>
		</tr>
	</table>
	<br>
	<table cellpadding="0" cellspacing="0" width="100%" class="form1">
		<tr>
			 <td><h3><strong>PROFESSIONAL SKILLS AND RESPONSIBILITIES</strong></h3></td>
		</tr>
		<br>
		<tr>
			<td><?= $rowResume[0]['professional_skills_resp'] ?></td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" width="100%" class="form1">
		<tr>
			 <td colspan="2"><h3><strong>PROFESSIONAL EXPERIENCE</strong></h3></td>
		</tr>
		<br>
		<?php  for($j=0; $j<count($rowComp); $j++){  ?>
		<tr>
			<td><strong>NAME OF COMPANY:</strong> </td>
			<td><?= $rowComp[$j]['rowComps']['comp_name']?></td>
		</tr>
		<tr>
			<td><strong>Designation:</strong> </td>
			<td><?= $rowComp[$j]['rowComps']['designation']?></td>
		</tr>
		<tr>
			<td><strong>Year of Experience:</strong> </td>
			<td><?= $rowComp[$j]['rowComps']['year_exp']?></td>
		</tr>
		
	</table>
	<br>
	<!-- Some important projects are as under: -->
	<table cellpadding="0" cellspacing="0" width="100%" class="form1">
	<tr>
			 <td colspan="2"><h3><strong>Some important projects are as under:</strong></h3></td>
		</tr>
	<?php 
		$rowCompProject = $rowComp[$j]['rowCompProjects'];
		//print_r($rowCompProject);
		$i=0;                           
		for($m=0; $m<count($rowCompProject); $m++){
				$i = $m+1; ?>
		<tr>
			  <td><strong>Project <?= $i?>:</strong>&nbsp;<?= $rowCompProject[$m]['pro_name'] ?>
			  <ul style="padding-left:80px;">
              <li>
                <strong>Project Description:</strong>&nbsp; 
                <?= $rowCompProject[$m]['pro_desc'] ?>
                </li>
              <li>
                <strong>Final Product &amp; Usage:</strong>&nbsp; 
                <?= $rowCompProject[$m]['product_usage'] ?>
                </li>
              <li>
                <strong>Duration:</strong>&nbsp; 
                <?= $rowCompProject[$m]['duration'] ?>
                </li>
              <li>
                <strong>Role:</strong>&nbsp; 
                <?= $rowCompProject[$m]['role'] ?>
                </li>
              <li>
                <strong>Team Size:</strong>&nbsp; 
                <?= $rowCompProject[$m]['team_size'] ?>
                </li>
            </ul></td>
		</tr>
		<?php } ?>
	
	
	</table>
	<?php } ?>
	
	
	
	
	
	<table cellpadding="0" cellspacing="0" width="100%" class="form1">
		<tr>
			 <td><h3><strong>EDUCATION</strong></h3></td>
		</tr>
		<br>
		<tr>
			<td><?php if($rowResume[0]['education']!=''){ echo $rowResume[0]['education']; }?></td>
		</tr>

	</table>
<br>
	<table cellpadding="0" cellspacing="0" width="100%" class="form1">
		<tr>
			 <td><h3><strong>WORKSHOPS</strong></h3></td>
		</tr>
		<br>
		<tr>
			<td><?php if($rowResume[0]['workshops']!=''){ echo $rowResume[0]['workshops']; }?></td>
		</tr>

	</table>
<br>
<table cellpadding="0" cellspacing="0" width="100%" class="form1">
		<tr>
			 <td><h3><strong>AWARDS AND EXCELLENCE</strong></h3></td>
		</tr>
		<br>
		<tr>
			<td><?php if($rowResume[0]['awards_excellence']!=''){ echo $rowResume[0]['awards_excellence']; }?></td>
		</tr>

	</table>
	<br>
	<table cellpadding="0" cellspacing="0" width="100%" class="form1">
		<tr>
			 <td><h3><strong>LANGUAGES YOU KNOW</strong></h3></td>
		</tr>
		<br>
		<tr>
			<td>
				 <ul>
					  <?php 
						for($l=0; $l<count($rowLang); $l++){
								$content .='<li><strong>'.$rowLang[$l]['lname'].':</strong> Excellent in '.$rowLang[$l]['lspeak'].' , '.$rowLang[$l]['lread'].' and '.$rowLang[$l]['lwrite'].'</li>';                                                       
						}  
						if($rowResume[0]['marital_status']=='S') {           
								$mstatus ='Single';
						}else{
						   $mstatus ='Married';
						}
						if($rowResume[0]['gender']=='M')  {          
								$sex ='Male';
						}
						else{
						   $sex ='Female';
						} ?>
				</ul>
			</td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" width="100%" class="form1">
		<tr>
			 <td colspan="2"><h3><strong>PERSONAL DETAILS</strong></h3></td>
		</tr>
		<br>
		<tr>
			<td><strong>Name:</strong> </td>
			<td> <?= $rowResume[0]['full_name'] ?></td>
		</tr>
		<tr>
			<td><strong>Fathers Name:</strong> </td>
			<td><?= $rowResume[0]['father_name'] ?></td>
		</tr>
		<tr>
			<td><strong>Present Address with mobile no.:</strong> </td>
			 <td>  <?php echo $rowResume[0]['address2'].','.$rowResume[0]['city_district2'].','.$rowResume[0]['pin_zip2'].', Mob-'.$rowResume[0]['mobile1'] ?></td>
		</tr>
		<tr>
			<td><strong>Permanent Address:</strong> </td>
			 <td>  <?php echo $rowResume[0]['address1'].','.$rowResume[0]['city_district1'].','.$rowResume[0]['pin_zip1'] ?></td>
		</tr>
		<tr>
			<td><strong>Date of Birth:</strong> </td>
			  <td> <?= date('d/m/Y',strtotime($rowResume[0]['dob'])) ?></td>
		</tr>
		<tr>
			<td><strong>Marital Status:</strong> </td>
			 <td> <?= $mstatus ?></td>
		</tr>
		<tr>
			<td><strong>Sex:</strong> </td>
			 <td> <?= $sex ?></td>
		</tr>
		<tr>
			<td><strong>Religion &amp; Nationality:</strong> </td>
			 <td> Indian</td>
		</tr>
		<tr>
			<td><strong>Passport Detail:</strong> </td>
			 <td>  <?= $rowResume[0]['passport_no'] ?></td>
		</tr>
		<tr>
			<td><strong>Email:</strong> </td>
			   <td><?= $rowResume[0]['email'] ?></td>
		</tr>
	</table>
	
</body>