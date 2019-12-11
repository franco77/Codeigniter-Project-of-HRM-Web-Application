<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_type = "";
if (isset($_GET['type']))
{
	$get_type = $_GET['type'];
}
?>
<div class="section main-section">
    <div class="container"> 
		<div class="col-md-3 center-xs">
			<div class="form-content page-content">
				<?php $this->load->view('news_and_events/left_sidebar');?>
			</div>
		</div>
		<div class="col-md-9">
			<legend class="pkheader">News & Events <small>(Read all news and events of <span class="searchMonth"><?php echo date('F'); ?></span>)</small></legend>
			<div class="row well">
				<div class="panel-default"> 
					<!-- /.panel-heading --> 
					<div class="panel-body">
						<?php if($get_type == ""){ ?>
						<form id="searchForm" name="searchForm" method="POST" action="">
							<div class="well"> 
								<h4 class="box-title">SEARCH</h4>
								<div class="row">
									<div class="col-md-2">
										<span class="pull-right">Choose Month :</span>
									</div>
									<div class="col-md-3">
										<select name="dd_month" id="dd_month" class="form-control input-sm"> 
											<?php
											  for ($i=1;$i<=12;$i++) {
												if ($i == $dd_month){
											 ?>
												<option value="<?php echo($i)?>" selected><?php echo(date(F,mktime(0,0,0,$i,1,2000)))?></option>
											 <?php }else{?>
												 <option value="<?php echo($i)?>"><?php echo(date(F,mktime(0,0,0,$i,1,2000)))?></option>
											 <?php }
											   } ?> 
										</select>
										 
									</div>
									<div class="col-md-2">
										<span class="pull-right">Choose Year :</span>
									</div>
									<div class="col-md-3">
										<select name="dd_year"  id="dd_year" class="form-control input-sm">
										  <?php
										  $yr=date("Y");
										  for ($j=$yr;$j>=2009;$j--){
											if ($j == $dd_year){
										 ?>
											<option value="<?php echo($j)?>" selected><?php echo($j)?></option>
										 <?php }else{?>
										 <option value="<?php echo($j)?>"><?php echo($j)?></option>
										 <?php }
										 }?>
										</select> 
									</div>
									<div class="col-md-2">
										<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search" onclick="searchAnniversary(this);" /> 
									</div>
								</div>
							</div>
						</form>
						<?php } ?>
						<div class="content-heading col-md-12">
							<div class="text">
								<h4><?php echo $title;?></h4>
								<p>Read all anniversary of <span class="searchMonth"><?php echo date('F'); ?></span></p>
							</div>
						</div>
						<div class="col-md-12 content-bottom">
							<div class="content-data anniversary-content-data">
								<?php
									$news_res = count($anniversary);
									if($news_res > 0)
									{
										for($i=0; $i < $news_res; $i++ )
										{?>
											<div class="news_calender">
												<div class="news_box_calendar pull-left">
													<div class="month_year"><?php echo date("M Y");?></div>
													<div class="date"><?php echo date("d", strtotime($anniversary[$i][7]));?></div>
												</div>
											</div>
											<?php if($anniversary[$i][1] == 'AN'){?>
											<div class="news_box_right">
												<?php 
													if($anniversary[$i][5] != '')
													{?>
														<img src="<?php echo base_url('assets/upload/profile/'. $anniversary[$i][5]);?>" alt="" class="profile-picture"> 
														 
													<?php }
													else
													{?>
														<img src="<?php echo base_url('assets/images/no-image.jpg');?>" alt="" class="profile-picture"/>
													<?php }
												?> 
												<div class="birthday-content">
													<h4><?php echo $anniversary[$i][2];?></h4>
													<p>( <?php echo $anniversary[$i][3];?> )</p>
													<p>Anniversary on <?php echo date("jS F", strtotime($anniversary[$i][7]));?></p>
													<img src="<?php echo base_url('assets/dist/images/candel.png')?>" alt="HBD" class="greeting-picture"/>
												</div>									
											</div>
											  
										<?php } }
									}
									else
									{?>
										<div class="news_box">
											<div class="pad_10">No News / Event Found Under this section.</div>
											<div class="clear"></div>
										</div>
									<?php }
								?>  
							</div>
					</div> 
				</div> 
			</div>
		</div>
		<div class="clearfix"></div> 
    </div>
</div>