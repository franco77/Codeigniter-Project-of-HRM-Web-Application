<?php
session_start(); // start the session

include('../../config/config.inc.php');
include('../../lib/common.class.php');
include('../../lib/db.func.php'); // include common functions

$db = new DBSystem($HOST, $DBNAME, $DBUSER, $DBPASSWORD);
openConnection();

extract($_POST);

 $specializationSql = "SELECT * FROM `specialization_master` WHERE course_id='".$specializationType."'";
 $specializationRes = mysql_query($specializationSql);
 if(mysql_num_rows($specializationRes) > 0){
?>

	<select id="ddl_specialization" name="ddl_specialization" class="form_ui" style="width:170px;">
		<?php 
		while($specializationInfo= mysql_fetch_array($specializationRes))
		{ ?>
			<option value="<?php echo $specializationInfo['specialization_id'];?>" ><?php echo $specializationInfo['specialization_name'];?></option>
		<?php }
		?>
   </select>
 <?php } ?>