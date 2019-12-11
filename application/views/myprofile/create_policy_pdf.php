<?php  
$Header='HR Policy';
$subheader= 'EMployee Policy';
$nested_link = 'Download Employee Policy';
  
$count=  count($resPolicy);
$content=''; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?= $Header ?></title>
  </head>
  
<body style="margin-left: 40px;">

	<table cellpadding="0" cellspacing="0" width="100%" class="form1">
		<tr>
			 <td colspan="2"><h3><strong><?= $resPolicy[0]['policy_title'] ?></strong></h3></td>
		</tr>
		<br>
		<tr>
			<td colspan="2"><?= $resPolicy[0]['policy_content'] ?></td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<img src="<?= base_url(); ?>assets/upload/profile/<?= $resPolicy[0]['user_sign_name']; ?>" alt="" width="225" height="40" class="form_img">
			</td>
		</tr>
	</table>
	
</body>