<?php
session_start(); // start the session

include('../../config/config.inc.php');
include('../../lib/common.class.php');
include('../../lib/db.func.php'); // include common functions

$db = new DBSystem($HOST, $DBNAME, $DBUSER, $DBPASSWORD);
openConnection();

extract($_POST);

 $courseSql = "SELECT * FROM `course_info` WHERE course_type='".$coursetype."'";
 $courseRes = mysql_query($courseSql); 
?>

	<select id="ddl_coursetype" name="ddl_coursetype" onchange="changeSpecialization(this)" class="form_ui" style="width:120px;">
            <option value="">Course Type</option>
		<?php 
		while($courseInfo= mysql_fetch_array($courseRes))
		{ ?>
			<option value="<?php echo $courseInfo['course_id'];?>" ><?php echo $courseInfo['course_name'];?></option>
		<?php }
		?>
   </select>