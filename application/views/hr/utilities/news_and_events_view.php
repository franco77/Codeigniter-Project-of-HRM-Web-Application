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
				<?php $this->load->view('hr/left_sidebar');?>
			</div>
		</div>
		<div class="col-md-9">
			<legend class="pkheader">Birth Day Reminder <small>(Read all news and events of <?php echo $newsAndEventsMonth; ?>)</small></legend>
			<div class="row well">
				<div class="panel-default"> 
					<!-- /.panel-heading --> 
					<div class="panel-body">
						<?php if($get_type == "" || $get_type == "Upcoming"){ ?>
						<form id="searchForm" name="searchForm" method="POST" action="">
							<div class="well"> 
								<h4 class="box-title">Search</h4>
								<div class="row pkdsearch">
									<div class="col-md-2">
										<span class="pull-right">Choose Month :</span>
									</div>
									<div class="col-md-3">
										<select name="dd_month" class="form-control input-sm"> 
											 <?php
											  for ($i=1;$i<=12;$i++) {
												if ($i == $dd_month){
											 ?>
												<option value="<?php echo($i); ?>" selected><?php echo date("F", strtotime('2018-'.$i.'-01')); ?></option>
											 <?php } else { ?>
												 <option value="<?php echo($i); ?>"><?php echo date("F", strtotime('2018-'.$i.'-01')); ?></option>
											 <?php }
											   } ?>
										</select>
										 
									</div>
									<div class="col-md-2">
										<span class="pull-right">Choose Year :</span>
									</div>
									<div class="col-md-3">
										<select name="dd_year" class="form-control input-sm">
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
										<input type="submit" id="btnSearch" name="btnSearch" class="btn btn-info" value="Search" /> 
									</div>
								</div>
							</div>
						</form>
						<?php } ?>
						<div class="content-heading col-md-12">
							<div class="text">
								<h4><?php echo $title;?></h4>
								<p>Read all news and events of <?php echo $newsAndEventsMonth; ?></p>
							</div>
						</div>
						<div class="col-md-12 content-bottom">
							<div class="content-data"> 
								<?php
									$news_res = count($newsAndEvents);
									if($news_res > 0)
									{
										for($i=0; $i < $news_res; $i++ )
										{?> 
											<?php if($newsAndEvents[$i][1] == 'B'){?>
											<div class="news_box_right">
											<div class="news_box_calendar pull-left">
													<div class="month_year"><?php echo date("M Y", strtotime($newsAndEvents[$i][0]));?></div>
													<div class="date"><?php echo date("d", strtotime($newsAndEvents[$i][0]));?></div>
												</div>
												<?php 
													if($newsAndEvents[$i][5] != '')
													{?>
														<img src="<?php echo base_url('assets/upload/profile/'. $newsAndEvents[$i][5]);?>" alt="" class="profile-picture" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';"> 
														 
													<?php }
													else
													{?>
														<img src="<?php echo base_url('assets/images/head.png');?>" alt="" class="profile-picture"/>
													<?php }
												?> 
												<div class="birthday-content">
													<h4><?php echo $newsAndEvents[$i][2];?></h4>
													<p>( <?php echo $newsAndEvents[$i][3];?> )</p>
													<p>Birthday on <?php echo date("jS F", strtotime($newsAndEvents[$i][0]));?></p>
													<img src="<?php echo base_url('assets/images/bday-cake.png')?>" alt="HBD" class="greeting-picture"/>
												</div>									
											</div>
											  
										<?php } }
									}
									else
									{?>
										<div class="news_box">
											<div class="col-md-12" style="padding:20px; text-align:center;">No News / Event Found Under this section.</div>
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