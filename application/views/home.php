<div class="container">  
	<div class="row">
		
		<?php /* ?>
		<div class="col-md-12">
			<span behavior="scroll" scrollamount="6" onmouseover="this.stop();" onmouseout="this.start();">
				<sup> <img src="<?php //echo base_url('assets/images/new.gif');?>" align="center" width="48" height="35"></sup>
			<?php
			 $count = count($resource_marque); 
				for($i=0; $i < $count;$i++)
				{?>
					<a class="bodystyle" target="#" href="<?= base_url('home/download_generate_res');?>?doc_id=<?php echo $resource_marque[$i]['doc_id'];?>&amp;doc_name=<?php echo $resource_marque[$i]['doc_name'];?>"><?php echo strtoupper($resource_marque[$i]['doc_title']); ?></a>
					
			<?php } ?> 
			</span>
			<?php
				
			?>
			
			<div class="alert alert-warning">
				<h4>Unique Pin: <?php echo $get_unique_pin; ?>  </h4>
				<?php
					$gmessage = count($general_alert);
					for($i=0;$i<$gmessage;$i++)
					{
						echo $general_alert[$i]['message'];
					}
				?>
				<hr>
				<p style="font-size:12px; line-height:12px;">[This yellow section is the &quot;Alert section&quot; for important announcements such as upcoming staff activities, internal job openings or requests to regularize your attendance. These alerts will appear every day until the activity is completed or the issue is resolved.] </p>
			</div>
		</div>
		<?php  */?>

		
		
		<style>
		.panel-title>a, .panel-title>a:active{
			display: block;
			padding: 2px;
			color: #fff;
			font-size: 16px;
			font-weight: bold;
			text-transform: uppercase;
			letter-spacing: 1px;
			word-spacing: 3px;
			text-decoration: none;
		}
		.panel-heading  a:before {
		   font-family: 'Glyphicons Halflings';
		   content: "\e114";
		   float: right;
		   transition: all 0.5s;
		}
		.panel-heading.active a:before {
			-webkit-transform: rotate(180deg);
			-moz-transform: rotate(180deg);
			transform: rotate(180deg);
		} 
		.notiSect {
			height: 217px;
		}
		.hof-content {height: 100%}
		</style>
		<div class="item">
		<div class="col-md-7 col-sm-6">
			<div class="panel panel-default" id="accordion1" role="tablist" aria-multiselectable="true">
				<div class="panel-heading" style="height:39px;" role="tab" id="headingOne">
					<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						<i class="fa fa-bell"></i>&nbsp <?php if($get_unique_pin !=""){ ?>Unique Pin: <?php echo $get_unique_pin; } ?>
					</a>
					</h4>
					
				</div>
				<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body notiSect" style="background:#faebd7;height: 230px; overflow:auto;">
							
					<div class="col-md-12">
						<?php
							$gmessage = count($general_alert);
							for($i=0;$i<$gmessage;$i++)
							{
								echo $general_alert[$i]['message'];
							}
						?>
					</div>	
				</div>
				<div class="panel-footer" style="padding: 9px 20px;">
					<p style="font-size:12px; line-height:16px; margin-bottom: 0px;">[This yellow section is the &quot;Alert section&quot; for important announcements such as upcoming staff activities, internal job openings or requests to regularize your attendance. These alerts will appear every day until the activity is completed or the issue is resolved.] </p>
				</div>
				</div>

			</div>
		</div>
		<!-- /notice -->

		
		<!-- Goal Progress -->
		<div class="col-md-5 col-sm-6">
			<div class="panel panel-default" id="accordion" role="tablist" aria-multiselectable="true">
				<div class="panel-heading" style="height:39px;" role="tab" id="headingOne">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne11" aria-expanded="true" aria-controls="collapseOne">
							<i class="fa fa-bar-chart"></i> Self Assessed Goal Progress Bar
						</a>
					</h4>
				</div>
				<div id="collapseOne11" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
					<div class="panel-body left_box defaultHeightBar">
						<?php $qryInfo_count = COUNT($qryInfo);   
						if($qryInfo_count != 0) { $cntt=1;
						for($i=0; $i<$qryInfo_count; $i++) { ?>
						
						
							<div class=" report">
								<p style="margin:0px;color: #868282;"><?php echo $cntt.'. '; ?><?php echo strlen($qryInfo[$i]['objective']) > 45 ? substr($qryInfo[$i]['objective'],0,45)."..." : $qryInfo[$i]['objective']; ?></p>
								<a href="#" class="tooltips"><b>?</b></a>
								<span class="progress-report"> Progress <span id="final"><?php echo $qryInfo[$i]['progress']; ?></span>% out of 100%</span>
								<div class="progress">
									<?php $qryInfoLog_count = count($qryInfo[$i]['goalLog']);   
									if($qryInfoLog_count > 0) {  
									$default_progress =0;
									for($j=0; $j<count($qryInfo[$i]['goalLog']); $j++) {  
										if($j ==0){
											$progress = $qryInfo[$i]['goalLog'][$j]['progress'];
										}
										else{
											$progress = (int)($qryInfo[$i]['goalLog'][$j]['progress']) - $default_progress;
										}
									 ?>
										<div class="progress-bar progress-bar-striped progress-bar-info" role="progressbar" style="width:<?php echo $progress; ?>%; <?php if($j > 0){ ?> border-left:1px solid #fff; <?php } ?>" data-toggle="tooltip" title="<?php echo date('d-m-Y', strtotime($qryInfo[$i]['goalLog'][$j]['annualdate'])); ?>" data-placement="top">
											<span id="num1"><?php echo $progress; ?>%</span>
										</div>
							   
									<?php 
											$default_progress = (int)($qryInfo[$i]['goalLog'][$j]['progress']); 
										} ?>
									<?php } else {?>
										<div class="progress-bar progress-bar-striped progress-bar-info" id="st3" role="progressbar" style="width:0%">
											<span id="num3">0%</span>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php $cntt++; } ?>
						<?php } else {?>
							<div class="">
								<p style="margin-top: 50px;color: #868282; text-align: center;">Set your goal to track your progress...</p>
								<div class="progress">
									<div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%;"></div>
								</div>
							</div>
						<?php } ?>
					</div>
					<div class="row footer-pannel viewDeatils">
						<a href="<?= base_url('my_account/job_description_update_emp');?>" class="btn btn-info pull-right btn-sm btn-viewAll btn-viewDeatils" role="button">Update Details</a>
					</div>
				</div>
			</div>
		</div>
		</div>
		<script>
			$('.progress-bar[data-toggle="tooltip"]').tooltip({
				animated: 'fade',
				placement: 'top'
			}); 
			$(function() {
				$('.hof-content').matchHeight();
			});
		</script>
		<script>
			/* $(function () { 
				$('[data-toggle="tooltip"]').tooltip({trigger: 'manual'}).tooltip('show');
			});  
	 
			$(".progress-bar").each(function(){
				each_bar_width = $(this).attr('aria-valuenow');
				$(this).width(each_bar_width + '%');
			}); */
		</script>
		
		<div class="col-md-12"> 
			<div class="hof">
				<div class="panel-body">
					<h2 class="heading"> <span class="pk_headtext">Hall Of Fame - <?php if(count($hall_of_fame)>0){ echo date('F Y', strtotime($hall_of_fame[0]['year'].'-'.$hall_of_fame[0]['month'].'-01')); } else{ echo date('M'); } ?></span></h2>
					<div class="row"> 
						<?php
							$count = count($hall_of_fame); 
							if($count>0)
							{
							$perone = 12/$count;
							for($i=0; $i < $count;$i++)
							{
								if($count < 5){
								?>
								<div class="col-md-<?= $perone ;?> ">
									<div class="hof-content">
										<p class="hof-title"> <?php echo $hall_of_fame[$i]['title']; ?></p>
										<div> 
											<img class="img-responsive hof-emp-img" alt="" src="<?php echo base_url('assets/upload/hall_of_fame/'. $hall_of_fame[$i]['image']);?>"> 
											<p class="hof-name"> <span><?php echo $hall_of_fame[$i]['full_name']; ?></span></p> 
											<p class="hof-name2"><?php echo $hall_of_fame[$i]['loginhandle']; ?></p>
											<div class="hof-description"> <?php echo $hall_of_fame[$i]['description']; ?> </div> 
										</div>
									</div>
								</div>
							<?php } else if($count == 5){ ?>
								<div class="col-1-of-5">
									<div class="hof-content">
										<p class="hof-title"> <?php echo $hall_of_fame[$i]['title']; ?></p>
										<div> 
											<img class="img-responsive hof-emp-img" alt="" src="<?php echo base_url('assets/upload/hall_of_fame/'. $hall_of_fame[$i]['image']);?>"> 
											<p class="hof-name"> <span><?php echo $hall_of_fame[$i]['full_name']; ?></span></p> 
											<p class="hof-name2"><?php echo $hall_of_fame[$i]['loginhandle']; ?></p>
											<div class="hof-description"> <?php echo $hall_of_fame[$i]['description']; ?> </div> 
										</div>
									</div>
								</div>
							
							<?php } else if($count == 6){ ?>
								<div class="col-1-of-6">
									<div class="hof-content">
										<p class="hof-title"> <?php echo $hall_of_fame[$i]['title']; ?></p>
										<div> 
											<img class="img-responsive hof-emp-img" alt="" src="<?php echo base_url('assets/upload/hall_of_fame/'. $hall_of_fame[$i]['image']);?>"> 
											<p class="hof-name"> <span><?php echo $hall_of_fame[$i]['full_name']; ?></span></p> 
											<p class="hof-name2"><?php echo $hall_of_fame[$i]['loginhandle']; ?></p>
											<div class="hof-description"> <?php echo $hall_of_fame[$i]['description']; ?> </div> 
										</div>
									</div>
								</div>
							
							<?php } } 
								}
							 ?>
					</div> 
				</div>
			</div> 
		</div>
	</div>
    <div class="row">
		<div class="col-md-3 col-sm-6">
		
			<div class="panel panel-default" id="accordion" role="tablist" aria-multiselectable="true">
				<div class="panel-heading" style="height:39px;" role="tab" id="headingOne">
					<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne1" aria-expanded="true" aria-controls="collapseOne">
						<i class="fa fa-calendar"></i>&nbsp Event Calender
					</a>
					</h4>
					
				</div>
				<div id="collapseOne1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body left_box">
					<?php
						function week_start_date($wk_num, $yr, $first = 0, $format = 'm/d/Y')
						{
							$wk_ts  = strtotime('+' . $wk_num . ' weeks', strtotime($yr . '0101'));
							$mon_ts = strtotime('-' . date('w', $wk_ts) + $first . ' days', $wk_ts);
							return date($format, $mon_ts);
						}
						$c_date = date("Y-m-d");
						if(isset($_GET['c_date'])){
							$c_date = $_GET['c_date'];
						}
						if($c_date == '')
						{
							$c_date = date("Y-m-d");
							$c_date_arr = explode('-',$c_date);
						}
						else
						{
							$c_date_arr = explode('-',$c_date);
						}
						$yy = $c_date_arr[0];
						$mm = $c_date_arr[1];
						$dd = $c_date_arr[2];
						$nextMonth = $mm + 1;
						$nextYear = $yy;
						if($mm > 11){
							$nextMonth = '01';
							$nextYear = $yy + 1;
						}
						$prevMonth = $mm - 1;
						$prevYear = $yy;
						if($mm <= 1){
							$prevMonth = 12;
							$prevYear = $yy - 1;
						}
						$nextMonthDate = $nextYear.'-'.$nextMonth.'-01';
						$prevMonthDate = $prevYear.'-'.$prevMonth.'-01';
						$start_week = date("W", strtotime($yy.'-'.$mm.'-01'));
						$cur_week = date("W", strtotime(date("Y-m-d")));
						$displayMonthText = date("F Y", strtotime($c_date));
						$days = cal_days_in_month(CAL_GREGORIAN,$mm,$yy);
						$firstDay = date("N", strtotime($mm.'/01/'.$yy));
						$daysPrev = cal_days_in_month(CAL_GREGORIAN,$prevMonth,$prevYear);
					?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="calNav">
						<tr>
							<td width="12%"><a href="<?php echo base_url('home').'?c_date='.$prevMonthDate;?>" class="leftNav"></a></td>
							<td width="76%"><strong><?php echo $displayMonthText;?></strong></td>
							<td width="12%"><a href="<?php echo base_url('home').'?c_date='.$nextMonthDate;?>" class="rightNav"></a></td>
						</tr>
					</table>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="calendar">
						<tr>
							<th width="12%">S</th>
							<th width="12%">M</th>
							<th width="12%">T</th>
							<th width="12%">W</th>
							<th width="12%">T</th>
							<th width="12%">F</th>
							<th width="12%">S</th>
							<th width="16%">&nbsp;</th>
						</tr> 
						<?php	
							$monthBody = '';
							$i = 0;
							for($j=0; $j<42; $j++){
								if($j%7==0){
									$monthBody .= '<tr>';
							}
							
							
							if($j < $firstDay){
								$iD = $daysPrev-$firstDay+$j+1;
								$cool = strtotime($prevYear.'-'.$prevMonth.'-'.$iD);
								$monthBody .= '<td class="inactive-day"><a onclick="getEvents('.$cool.','.$prevYear.')">'.($iD).'</a></td>';
							}elseif($j >= $firstDay && $i < $days){
								$i++;
								$cool = strtotime($yy.'-'.$mm.'-'.$i);
								$classToday = '';
								$toDayDate = date("Y-m-d");
								if(date("Y-m-d", $cool) == $toDayDate){
									$classToday = 'week';
								}
								$monthBody .= '<td class="'.$classToday.'"><a onclick="getEvents('.$cool.','.$yy.')">'.($i).'</a></td>';
							}else{
								$iD = $j - $days - $firstDay + 1;
								$cool = strtotime($nextYear.'-'.$nextMonth.'-'.$iD);
								$monthBody .= '<td class="inactive-day"><a onclick="getEvents('.$cool.','.$nextYear.')">'.($iD).'</a></td>';
							}
							
							if($j%7==6){
							$wStartDate = week_start_date($start_week, $yy);
							//echo $wStartDate;
							$wEndDate   = date('Y/m/d', strtotime('+6 days', strtotime($wStartDate)));
							
							$monthBody .= '<td class="week"><a onclick="weekly_event(\''.$wStartDate.'\',\''.$wEndDate.'\',\''.$yy.'\')">W'.$start_week.'</a></td>';
							$monthBody .= '</tr>';
							if($start_week == 53){
								$start_week = 0;
							}
							$start_week++;
							}
							}
							echo $monthBody;
							?>
					</table>
				</div>
				</div>
			</div>
		
		
			<div class="panel panel-default">
				<div class="panel-heading">
					
					<p class="heading-text"> News & Events Today </p>
				</div>
				<div class="panel-body">
					<?php
					$todaycount = count($today);
					if($todaycount > 0)
					{
						$k = ($todaycount > 3)?3:$todaycount;
						for($i=0; $i < $k; $i++)
						{?>
							<div class="media">
								<div class="media-left"> 
									<?php if($today[$i]['user_photo_name'] != '')
									{?>
										<img class="media-object" src="<?php echo base_url('assets/upload/profile/'. $today[$i]['user_photo_name']);?>" alt="" style="width: 55px; height: 55px;" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';"/>
									  <?php }
									  else{?>
										<img class="media-object" src="<?= base_url('assets/images/no-image.jpg')?>" alt="" style="width: 55px; height: 55px;" />
									<?php } ?>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?php echo $today[$i]['name_first'].'&nbsp'.$today[$i]['name_last']; ?></h6>
									<h6><?php echo $today[$i]['desg_name']; ?></h6>
									<h6 class="ontime"> <i class="fa fa-birthday-cake" aria-hidden="true"></i> Birthday on <?php echo date("jS F", strtotime($today[$i]['dob']));?></h6>
									<a style="cursor:pointer; float: right;" data-id='<?= $today[$i]['login_id']; ?>' class='birthday' data-toggle="modal" id="modal" data-target="#birthday_myModal"><i class="fa fa-comments"></i> click and greet</a>
								</div>
							</div> 
					<?php } 
					}else{?>
                        <div class="row"> <p class="pk_margin">No News &amp; Event For Today.</p></div>
                    <?php }?>
					<div class="row footer-pannel">
						<a href="<?= base_url('news_and_events?type=Today');?>" class="btn btn-info pull-right btn-sm btn-viewAll" role="button">View All</a>
					</div>
				</div>
			</div> 
            <div class="panel panel-default">
				<div class="panel-heading">
					<p class="heading-text"> News &amp; Events this week </p>
				</div>
				<div class="panel-body">
					<?php
					$weekcount = count($weekly);
					if($weekcount > 0)
					{
						$kk = ($weekcount > 3)?3:$weekcount;
						for($i=0; $i < $kk;$i++)
						{?>
							<div class="media">
								<div class="media-left"> 
									<?php if($weekly[$i]['user_photo_name'] != '')
									{?>
										<img class="media-object" src="<?php echo base_url('assets/upload/profile/'. $weekly[$i]['user_photo_name']);?>" alt="" style="width: 55px; height: 55px;" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';"/>
									  <?php }
									  else{?>
										<img class="media-object" src="<?= base_url('assets/images/no-image.jpg')?>" alt="" style="width: 55px; height: 55px;" />
									<?php } ?>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?php echo $weekly[$i]['name_first'].'&nbsp'.$weekly[$i]['name_last']; ?></h6>
									<h6><?php echo $weekly[$i]['desg_name']; ?></h6>
									<h6 class="ontime"> <i class="fa fa-birthday-cake" aria-hidden="true"></i> Birthday on <?php echo date("jS F", strtotime($weekly[$i]['dob']));?></h6>
								</div>
							</div> 
					<?php } 
					}else{?>
                        <div class="row"> <p class="pk_margin">No News &amp; Event For This Week.</p></div>
                    <?php }?>
					<div class="row footer-pannel">
						<a href="<?= base_url('news_and_events?type=ThisWeek');?>" class="btn btn-info pull-right btn-sm btn-viewAll" role="button">View All</a>
					</div>
				</div>
			</div>
            <div class="panel panel-default">
				<div class="panel-heading">
					
					<p class="heading-text"> News &amp; Events this month</p>
				</div>
				<div class="panel-body">
					<?php
					$monthcount = count($monthly);
					if($monthcount > 0)
					{
						$kk = ($monthcount > 3)?3:$monthcount;
							for($i=0; $i < $kk;$i++)
							{?>
								<div class="media">
									<div class="media-left"> 
										<?php if($monthly[$i]['user_photo_name'] != '')
										{?>
											<img class="media-object" src="<?php echo base_url('assets/upload/profile/'. $monthly[$i]['user_photo_name']);?>" alt="" style="width: 55px; height: 55px;" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';"/>
										  <?php }
										  else{?>
											<img class="media-object" src="<?= base_url('assets/images/no-image.jpg')?>" alt="" style="width: 55px; height: 55px;" />
										<?php } ?>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><?php echo $monthly[$i]['name_first'].'&nbsp'.$monthly[$i]['name_last']; ?></h6>
										<h6><?php echo $monthly[$i]['desg_name']; ?></h6>
										<h6 class="ontime"> <i class="fa fa-birthday-cake" aria-hidden="true"></i> Birthday on <?php echo date("jS F", strtotime($monthly[$i]['dob']));?></h6>
									</div>
								</div> 
					<?php } 
					}else{?>
                        <div class="row"> <p class="pk_margin">No News &amp; Event For this Month.</p></div>
                    <?php }?>
					<div class="row footer-pannel">
						<a href="<?= base_url('news_and_events?type=ThisMonth');?>" class="btn btn-info pull-right btn-sm btn-viewAll" role="button">View All</a>
					</div>
				</div>
			</div>
            <div class="panel panel-default">
				<div class="panel-heading">
					
					<p class="heading-text"> Upcoming Events </p>
				</div>
				<div class="panel-body">
					<?php
					$upcomingcount = count($upcoming);
					if($upcomingcount > 0)
					{
						$kk = ($upcomingcount > 3)?3:$upcomingcount; 
							for($i=0; $i < $kk;$i++)
							{?>
								<div class="media">
									<div class="media-left"> 
										<?php if($upcoming[$i]['user_photo_name'] != '')
										{?>
											<img class="media-object" src="<?php echo base_url('assets/upload/profile/'. $upcoming[$i]['user_photo_name']);?>" alt="" style="width: 55px; height: 55px;" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';"/>
										  <?php }
										  else{?>
											<img class="media-object" src="<?= base_url('assets/images/no-image.jpg')?>" alt="" style="width: 55px; height: 55px;" />
										<?php } ?>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><?php echo $upcoming[$i]['name_first'].'&nbsp'.$upcoming[$i]['name_last']; ?></h6>
										<h6><?php echo $upcoming[$i]['desg_name']; ?></h6>
										<h6 class="ontime"> <i class="fa fa-birthday-cake" aria-hidden="true"></i> Birthday on <?php echo date("jS F", strtotime($upcoming[$i]['dob']));?></h6>
									</div>
								</div> 
					<?php } } else{ ?>
                        <div class="row"> <p class="pk_margin">No Upcoming News &amp; Event.</p></div>
                    <?php }?>  
					<div class="row footer-pannel">
						<a href="<?= base_url('en/news_and_events?type=Upcoming');?>" class="btn btn-info pull-right btn-sm btn-viewAll" role="button">View All</a>
					</div> 
				</div>
			</div>
			
            <div class="panel panel-default">
				<div class="panel-heading">
					<p class="heading-text"> Anniversary this Month </p>
				</div>
				<div class="panel-body">
					<?php
					$upcomingcount = count($aniversarry_this_month);
					if($upcomingcount > 0)
					{
						$kk = ($upcomingcount > 3)?3:$upcomingcount; 
							for($i=0; $i < $kk;$i++)
							{?>
								<div class="media">
									<div class="media-left"> 
										<?php if($aniversarry_this_month[$i]['user_photo_name'] != '')
										{?>
											<img class="media-object" src="<?php echo base_url('assets/upload/profile/'. $aniversarry_this_month[$i]['user_photo_name']);?>" alt="" style="width: 55px; height: 55px;" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';"/>
										  <?php }
										  else{?>
											<img class="media-object" src="<?= base_url('assets/images/no-image.jpg')?>" alt="" style="width: 55px; height: 55px;" />
										<?php } ?>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><?php echo $aniversarry_this_month[$i]['name_first'].'&nbsp'.$aniversarry_this_month[$i]['name_last']; ?></h6>
										<h6><?php echo $aniversarry_this_month[$i]['desg_name']; ?></h6>
										<h6 class="ontime"> <i class="fa fa-birthday-cake" aria-hidden="true"></i> Anniversary on <?php echo date("jS F", strtotime($aniversarry_this_month[$i]['anniversary_date']));?></h6>
									</div>
								</div> 
					<?php } }else{ ?>
                        <div class="row"> <p class="pk_margin">No Anniversary For this Month.</p></div>
                    <?php }?> 
					<div class="row footer-pannel">
						<a href="<?= base_url('anniversary?type=ThisMonth');?>" class="btn btn-info pull-right btn-sm btn-viewAll" role="button">View All</a>
					</div> 
				</div>
			</div>
			
            <div class="panel panel-default">
				<div class="panel-heading">
					<p class="heading-text"> Upcoming Anniversary </p>
				</div>
				<div class="panel-body">
					<?php
					$aniversarrycount = count($aniversarry);
					if($aniversarrycount > 0)
					{
						$kk = ($aniversarrycount > 3)?3:$aniversarrycount; 
							for($i=0; $i < $kk;$i++)
							{?>
								<div class="media">
									<div class="media-left"> 
										<?php if($aniversarry[$i]['user_photo_name'] != '')
										{?>
											<img class="media-object" src="<?php echo base_url('assets/upload/profile/'. $aniversarry[$i]['user_photo_name']);?>" alt="" style="width: 55px; height: 55px;" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';"/>
										  <?php }
										  else{?>
											<img class="media-object" src="<?= base_url('assets/images/no-image.jpg')?>" alt="" style="width: 55px; height: 55px;" />
										<?php } ?>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><?php echo $aniversarry[$i]['name_first'].'&nbsp'.$aniversarry[$i]['name_last']; ?></h6>
										<h6><?php echo $aniversarry[$i]['desg_name']; ?></h6>
										<h6 class="ontime"> <i class="fa fa-birthday-cake" aria-hidden="true"></i> Anniversary on <?php echo date("jS F", strtotime($aniversarry[$i]['anniversary_date']));?></h6>
									</div>
								</div> 
					<?php } }else{?>
                        <div class="row"> <p class="pk_margin">No Upcomming Anniversary.</p></div>
                    <?php }?> 
					<div class="row footer-pannel">
						<a href="<?= base_url('anniversary?type=Anniversary');?>" class="btn btn-info pull-right btn-sm btn-viewAll" role="button">View All</a>
					</div> 
				</div>
			</div>
        </div>
		<div class="col-md-6 col-sm-6">
            <div class="panel panel-default">
				<div class="panel-heading">
					
					<p class="heading-text2">Important Links</p>
					<p class="small-text">Important stuff you should visit</p>
				</div>
				<div class="row panel-body important-link">
					
				    <div class="col-md-3 col-sm-3 col-xs-6">
					    <div class="link-box">
							<a href="<?php echo base_url('/resources/official_holidays') ?>"><i class="fa fa-calendar-minus-o fa-2x" aria-hidden="true"></i> 
							<p class="category-name">Holiday List</p></a>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-6">
					    <div class="link-box">
					     <a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-users fa-2x" aria-hidden="true"></i><br/> <p class="category-name">Organogram</p></a>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-6">
					    <div class="link-box">
					     <a target="_blank" href="<?= base_url('home/download_generate_res');?>?doc_id=1&doc_name=EMPLOYEE%20SERVICE%20RULE.pdf"><i class="fa fa-mars-double fa-2x" aria-hidden="true"></i><br/> <p class="category-name">Employee Service Rules</p></a>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-6">
					    <div class="link-box">
					     <a href="javascript: void(0);"><i class="fa fa-id-card-o fa-2x" aria-hidden="true"></i><br/> <p class="category-name">SOP</p></a>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-6">
					    <div class="link-box">
					     <a href="<?php echo base_url('en/resources');?>"><i class="fa fa-book fa-2x" aria-hidden="true"></i><br/> <p class="category-name">HR Policies</p></a>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-6">
					    <div class="link-box">
					    <a href="<?php echo base_url('en/resources');?>"><i class="fa fa-comments fa-2x" aria-hidden="true"></i><br/> <p class="category-name">In House Monthly Magazine</p></a>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-6">
					    <div class="link-box">
					    <a href="<?php echo base_url('en/resources');?>"><i class="fa fa-recycle fa-2x" aria-hidden="true"></i><br/> <p class="category-name">News/Circulars</p></a>
						</div>
					</div>
				
				
				</div>
			</div>
            <div class="panel panel-default">
				<div class="panel-heading">
					
					<p class="heading-text2">POLOHRM Classified</p>
					<p class="small-text">View &amp; post your classified</p>
				</div>
				<div class="panel-body">
					<?php
					$classified = count($latest_classified);
					for($i=0; $i< $classified; $i++)
					{ ?>
						<div class="media">
							<div class="media-left"> 
								<?php if($latest_classified[$i]['classified_file'] != '')
								{?>
									<img class="media-object" src="<?= base_url('assets/upload/classified/'.$latest_classified[$i]['classified_file'].'')?>" alt="" style="width: 55px; height: 55px;" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';"/>
								  <?php }
								  else{?>
									<img class="media-object" src="<?= base_url('assets/images/no-image.jpg')?>" alt="" style="width: 55px; height: 55px;" />
								<?php } ?>
							</div>
							<div class="media-body">
								<a onclick="showClassifiedDetails(<?php echo $latest_classified[$i]['ix_classified'];?>);" ><h4 class="media-heading"><?php echo $latest_classified[$i]['classified_header'];?></h6></a> 
								<h6>Name: <?php echo $latest_classified[$i]['name_first'] ?></h6>
								<h6>Contact No: <?php echo $latest_classified[$i]['mobile'] ?></h6>
							</div>
						</div> 
					<?php } ?>
					<div class="row" style="margin:8px;">
						<a href="<?= base_url('aabsys_classified/my_classified');?>" class="btn btn-info pull-left btn-sm btn-viewAll" role="button">Post Classified</a>
						<a href="<?= base_url('aabsys_classified/classified');?>" class="btn btn-info pull-right btn-sm btn-viewAll" role="button">View All</a>
					</div>
				</div>
			</div>
			
			
			
			<?php $birthDayWishCOUNT = count($birthDayWish); 
			if($birthDayWishCOUNT > 0)
					{?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<p class="heading-text"> Your Birthday Message </p>
					<p class="small-text">View all Birth Day Message</p>
				</div>
				<div class="panel-body greetingSec">
					<?php
							for($i=0; $i < $birthDayWishCOUNT;$i++)
							{?>
								<div class="media">
									<div class="media-left"> 
										<?php if($birthDayWish[$i]['user_photo_name'] != '')
										{?>
											<img class="media-object" src="<?php echo base_url('assets/upload/profile/'. $birthDayWish[$i]['user_photo_name']);?>" alt="" style="width: 55px; height: 55px;" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';"/>
										  <?php }
										  else{?>
											<img class="media-object" src="<?= base_url('assets/images/no-image.jpg')?>" alt="" style="width: 55px; height: 55px;" />
										<?php } ?>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><?php echo $birthDayWish[$i]['name_first'].'&nbsp'.$birthDayWish[$i]['name_last']; ?></h4>
										<h6>Message :<?php echo $birthDayWish[$i]['message']; ?></h6>
										<h6 class="ontime"> <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo date('d-m-Y h:i:s A',strtotime($birthDayWish[$i]['cdate'])); ?></h6>
									</div>
								</div> 
					<?php } ?>
					 
				</div>
			</div>
			<?php } ?>
			
        </div>
		<div class="col-md-3 col-sm-6">
			<?php if(count($assets)>0){ ?>
            <div class="panel panel-default">
				<div class="panel-heading">
					
					<p class="heading-text">My Assets</p>
					<p class="small-text">Assets Used By Me</p>
				</div>
				<div class="panel-body">
					<table class="table">
						<thead>
							<tr>
								<th>Asset</th>
								<th>Asset Code</th>  
							</tr>
						</thead>
						<tbody id="staffDirectory1" style="font-size: 9px;"> 
							<?php
								$assetsC = count($assets);
								for($i=0; $i < $assetsC; $i++)
								{ 
								$class = ($i % 2 == 0)?'odd':'even';
							?>
								<tr class="<?php echo $class; ?>">
									<td><?php echo $assets[$i]['AssetName'];?></td>
									<td><?php echo $assets[$i]['AssetCode'];?></td>
								</tr>
							<?php } ?> 
						</tbody>
					</table>
				</div>
			</div>
			<?php } ?>
            <div class="panel panel-default">
				<div class="panel-heading">
					
					<p class="heading-text">Staff Directory</p>
					<p class="small-text">Contact Numbers of Employees</p>
				</div>
				<div class="panel-body">
					<div class="row media"> 
					<div class=""> 
						<div class="input-group add-on"> 
							<input class="form-control" placeholder="Search" name="search_directory_key" id="search_directory_key" type="text" style="padding-left: 10px;"> 
							<div class="input-group-btn"> 
								<button class="btn btn-default" type="buttom" onclick="searchStaffDirectory();"><i class="glyphicon glyphicon-search"></i></button> 
							</div> 
						</div>
					</div>
					</div>
					<table class="table">
						<thead>
						  <tr>
							<th>Name</th>
							<th>Phone No</th> 
						  </tr>
						</thead>
						<tbody id="staffDirectory"> 
								<?php
									$telephone = count($telephone_no);
									for($i=0; $i < $telephone; $i++)
									{ $class = ($i % 2 == 0)?'odd':'even'; ?>
										<tr class="<?php echo $class; ?>">
											<td><?php echo $telephone_no[$i]['name'];?></td>
											<td><?php echo $telephone_no[$i]['phone'];?></td>
										</tr>
								<?php } ?> 
						</tbody>
					</table>
					<div class="row footer-pannel">
						<a href="<?= base_url('resources/phone_directory');?>" class="btn btn-info pull-right btn-sm btn-viewAll" role="button">View All</a> 
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					
					<p class="heading-text">Company Policies</p>
				</div>
				<div class="panel-body policies">
					<sup> <img src="<?php echo base_url('assets/images/new.gif');?>" align="center" width="48" height="35" style="margin-top:5px;"></sup>
					<?php
					$count = count($resource_marque); 
					for($i=0; $i < 15;$i++)
					{?>
						<ul style="margin-left: -10px;">
							<li>
								<a href="<?= base_url('assets/share/docs/');?><?php echo 'rd_'.@$resource_marque[$i]['doc_id'].'_'.@$resource_marque[$i]['doc_name'];?>" download ><?php echo strtoupper(@$resource_marque[$i]['doc_title']); ?></a>
								
							</li>
						</ul>
					<?php }
					
					if($count > 15){
						for($i=15; $i < $count;$i++)
					{ ?>
						<ul class="more-policy" style="margin-left: -10px;">
							<li>
								<a href="<?= base_url('assets/share/docs/');?><?php echo 'rd_'.$resource_marque[$i]['doc_id'].'_'.$resource_marque[$i]['doc_name'];?>" download ><?php echo strtoupper($resource_marque[$i]['doc_title']); ?></a>
								
							</li>
						</ul>
					<?php }
					}
					?> 
				</div>
				<div class="row footer-pannel">
						<button type="button" class="btn btn-info pull-right btn-sm btn-viewAll policy-button" role="button">View All</button>
				</div>
			</div>
			
			
        </div> 
    </div>
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<img src="<?php echo base_url('assets/images/orgn-production.jpg'); ?>">
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="birthday_myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Send Greetings</h4>
              </div>
			  <form action="" method = 'POST' id="greetFORM">
				  <div class="modal-body">
					<div class="successMsg" id="messageSuccess"></div>
					<div class="greetSec">
						<h5>Write Greetings<span class ="red">*</span></h5>
						<input type="hidden" id="loginID" name="loginID" id="loginID">
						<textarea id="message" name="message" cols="80" rows="7" onfocus="removeError(this);" required></textarea>
					</div>					
				  </div>
				  <div class="modal-footer">
				   <button type="button" id="greetBTN" class="btn btn-success" name="greetBTN" value="greetings">Submit</button>
				   <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				  </div>
			  </form>
            </div>
        </div>
    </div>
	<div class="modal fade" id="events" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">News & Events</h4>
              </div>
			  <div class="modal-body1" id="event_show">
			  
			  </div>
			  <div class="modal-footer">
			   <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			  </div>
            </div>
        </div>
    </div>
	<div class="modal fade" id="classifiedDetails" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">POLOHRM Classified Details</h4>
              </div>
			  <div class="modal-body1" id="classified_show">
			  
			  </div>
			  <div class="modal-footer">
			   <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			  </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $(".policy-button").click(function(){
	    var $this = $(this);
		$this.toggleClass('SeeMore2');
        if($this.hasClass('SeeMore2')){
        $this.text('View Less');         
        } else {
        $this.text('View All');
        }
       $("ul.more-policy").slideToggle(1000); 
    });
	
});



$(document).on("click", ".birthday", function () {
     var myBookId = $(this).data('id');
	$(".modal-body #loginID").val( myBookId );
	var loginID = $('#loginID').val();
	var user_id = '<?php echo $this->session->userdata('user_id') ?>';
	$('#messageSuccess').html('');
		$.ajax({
		  type:'POST',
		  data:{loginID:loginID,user_id:user_id},
		  url:site_url+'news_and_events/submit_greetings_check',
		  success:function(data){
			  var data= JSON.parse(data);
				if(data.length > 0){
					$('textarea#message').val(data[0].message);
				}
				else{
					$('textarea#message').val('');
				}
				
			}
		});
});

$(document).on('click','#modal',function(){
		$('#greetBTN').show();
		$('.greetSec').show();
		$("#greetFORM")[0].reset();
		$('#messageSuccess').html('');
})

$(document).on('click','#greetBTN',function(){
	var message = $('textarea#message').val();
	var loginID = $('#loginID').val();
	var user_id = '<?php echo $this->session->userdata('user_id') ?>';
	$('#messageSuccess').html('');
	if(message.trim().length == 0){
		$('textarea#message').attr('style', 'border-color: #f00000;');
	}
	if(message.trim().length > 0){
		$.ajax({
		  type:'POST',
		  data:{message:message,loginID:loginID,user_id:user_id},
		  url:site_url+'news_and_events/submit_greetings',
		  success:function(data){
			  if(data == 1){
				   $('#messageSuccess').html('<h4>Your Greetings has sent Successfully</h4>');
				   $('#greetBTN').hide();
				   $('.greetSec').hide();
				   $("#greetFORM")[0].reset();
			  } else {
				  $('#messageSuccess').html('<h4>Your Greetings has updated Successfully</h4>');
				  $('#greetBTN').hide();
				  $('.greetSec').hide();
			  }
			}
		});
	}
});
 
function showClassifiedDetails(ix_classified){
	 $('#classifiedDetails').modal('show');
	 var str = "";
	 $.ajax({
		 type:'POST',
		 url: site_url+'aabsys_classified/fetch_classified_details',
		 data:{ix_classified:ix_classified},
		 success:function(data){
			 
			 obj = JSON.parse(data);
			str =  '<div class="media">'+
						'<div class="media-left">'+
							'<img class="media-object" src="'+site_url+'assets/upload/classified/'+obj['classified_file']+'" alt="" style="width: 150px; height: 150px;" onerror="this.src=\''+site_url+'assets/images/no-image.jpg\'"/>'+
						'</div>'+
						'<div class="media-body">'+
							'<a  ><h4 class="media-heading">'+obj['classified_header']+'</h4></a>'+
							'<h4 class="media-heading">'+obj['classified_body']+'</h4>'+
							'<h6>Name: '+obj['name_first']+'</h6>'+
							'<h6>Contact No: '+obj['mobile'] +'</h6>'+
						'</div>'+
					'</div> ';
			$('#classified_show').html(str);
		 }
	 });
}
 
function getEvents(val,year){
	 $('#events').modal('show');
	 var str = "";
	 $('#event_show').html(str);
	 var year = '2018'; 
	 $.ajax({
		 type:'POST',
		 url: site_url+'news_and_events/fetch_events',
		 data:{date:val},
		 success:function(data){
			 obj = JSON.parse(data);
			 for(var i = 0; i< obj.length; i++){
				str +=  '<div class="news_box_right">'+
						'<div class="news_box_calendar pull-left">'+
						'<div class="month_year">'+obj[i]['mon']+' '+year+'</div>'+
						'<div class="date">'+obj[i]['date']+'</div>'+
					'</div>'+
					'<img src="'+site_url+'assets/upload/profile/'+obj[i]['user_photo_name']+'" alt="" class="profile-picture" onerror="this.src=\''+site_url+'assets/images/no-image.jpg\'"/>'+
					'<div class="birthday-content">'+
						'<h4>'+obj[i]['name_first']+' '+obj[i]['name_last']+'</h4>'+
						'<p>('+obj[i]['desg_name']+')</p>'+
						'<p>Birthday on '+obj[i]['date']+' '+obj[i]['Month']+'</p>'+
						'<img src="'+site_url+'assets/images/bday-cake.png" alt="HBD" class="greeting-picture"/>'+
					'</div>'+							
				'</div>';
			 }
			 if(str != ""){
				$('#event_show').html(str);
			 } else {
				 $('#event_show').html("No events found for this date");
			 }
		 }
	 });
}
 
 function weekly_event(sdate,edate,year){
	 $('#events').modal('show');
	 var str = "";
	 $('#event_show').html(str);
	 $.ajax({
		 type:'POST',
		 url: site_url+'news_and_events/fetch_events_weekly',
		 data:{sdate:sdate,edate:edate},
		 success:function(data){
			 obj = JSON.parse(data);
			 //console.log(obj);
			 for(var i = 0; i< obj.length; i++){
				str +=  '<div class="news_box_right">'+
						'<div class="news_box_calendar pull-left">'+
						'<div class="month_year">'+obj[i]['mon']+' '+year+'</div>'+
						'<div class="date">'+obj[i]['date']+'</div>'+
					'</div>'+
					'<img src="'+site_url+'assets/upload/profile/'+obj[i]['user_photo_name']+'" alt="" class="profile-picture" onerror="this.src=\''+site_url+'assets/images/no-image.jpg\'"/>'+
					'<div class="birthday-content">'+
						'<h4>'+obj[i]['name_first']+' '+obj[i]['name_last']+'</h4>'+
						'<p>('+obj[i]['desg_name']+')</p>'+
						'<p>Birthday on '+obj[i]['date']+' '+obj[i]['Month']+'</p>'+
						'<img src="'+site_url+'assets/images/bday-cake.png" alt="HBD" class="greeting-picture"/>'+
					'</div>'+							
				'</div>';
			 }
			 if(str != ""){
				$('#event_show').html(str);
			 } else {
				 $('#event_show').html("No events found for this date");
			 }
		 }
	});
}

</script>

<?php 
 //Attendance Punch
//if($this->session->userdata('empCode') != 'administrator' && $this->session->userdata('showAttendanceBox')=='YES'){
if($this->session->userdata('empCode') != 'administrator' && $this->session->userdata('showAttendanceBox')=='YES' && $this->session->userdata('remote_access')=='Y'){
//if($this->session->userdata('empCode') != 'administrator' && $this->session->userdata('showAttendanceBox')=='YES'){
//if($this->session->userdata('empCode') != 'administrator' && $this->session->userdata('remote_access')=='Y'){	
    ?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$('#myAttendance .myAttendanceClose').click(function(){
    	$('#myAttendance').removeAttr('style');
    	$('#myAttendance').attr('style', 'display:none');
    });
});

var site_url = '<?php echo base_url(); ?>';
function punchAttendance(dis){
	$('#feedback_server_err_msg').hide();
	$('#feedback_server_succ_msg').hide();
	$('.feedbackSec').show();
	$.ajax({
		type: "POST",
		url: site_url+'home/punch_attendance',
		data: $("#feedbackForm").serialize(), // serializes the form's elements.
		success: function(data)
		{
				$('#myAttendance #punchAttendanceMSG').html('<h4>Attendance punched successfully</h4>');
				$('#myAttendance #atnBTNSec').hide();
	   }
	});
}
</script>
<div class="modal fade in" id="myAttendance" role="dialog" style="display: block; padding-right: 17px;">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close myAttendanceClose" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Do you want to punch in your attendance?</h4>
			</div>
			<form action="" method = 'POST' id="">
				<div class="modal-body1">
					<div class="successMsg" id="punchAttendanceMSG" style="text-align:center;"></div>
								
				</div>
				<div class="modal-footer" id="atnBTNSec">
					<button type="button" id="glowingtabs3" class="btn btn-success" name="atnBTN" onclick="punchAttendance(this)" >Yes</button>
					<button type="button" class="btn btn-danger myAttendanceClose" data-dismiss="modal">No</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $_SESSION['showAttendanceBox'] = FALSE;
  }
  ?>   

<?php 
 //Policy Approve
if($this->session->userdata('empCode') != 'administrator' ){
for($i=0; $i<count($policyRes); $i++ ){
if($policyRes[$i]['approve_status'] !='1' ){
    ?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$('#myPolicy_<?php echo $policyRes[$i]['policy_id']; ?> .myPolicyClose').click(function(){
    	$('#myPolicy_<?php echo $policyRes[$i]['policy_id']; ?>').removeAttr('style');
    	$('#myPolicy_<?php echo $policyRes[$i]['policy_id']; ?>').attr('style', 'display:none');
    });
});

var site_url = '<?php echo base_url(); ?>';

function approveEmployeePolicy(dis,policy_id){
	$.ajax({
		type: "POST",
		url: site_url+'home/approv_policy_emp',
		data: {policy_id: policy_id}, // serializes the form's elements.
		success: function(data)
		{
				$('#myPolicy_<?php echo $policyRes[$i]['policy_id']; ?> #punchAttendanceMSG_<?php echo $policyRes[$i]['policy_id']; ?>').html('<h4>Policy Approved successfully</h4>');
				$('#myPolicy_<?php echo $policyRes[$i]['policy_id']; ?> #policyBTNSec_<?php echo $policyRes[$i]['policy_id']; ?>').hide();
				$('#myPolicy_<?php echo $policyRes[$i]['policy_id']; ?> .modal-body1').hide();
		}
	});
}
</script>
<div class="modal fade in" id="myPolicy_<?php echo $policyRes[$i]['policy_id']; ?>" role="dialog" style="display: block; padding-right: 17px;">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close myPolicyClose" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo $policyRes[$i]['policy_title']; ?></h4>
			</div>
			<form action="" method = 'POST' id="">
				<div class="modal-body1">
					<?php echo $policyRes[$i]['policy_content']; ?>
								
				</div>
				
					<div class="successMsg" id="punchAttendanceMSG_<?php echo $policyRes[$i]['policy_id']; ?>" style="text-align:center;"></div>
				<div class="modal-footer" id="policyBTNSec_<?php echo $policyRes[$i]['policy_id']; ?>">
					<button type="button" class="btn btn-danger myPolicyClose" data-dismiss="modal">Close</button>
					<button type="button" id="glowingtabs3" class="btn btn-success" name="atnBTN" onclick="approveEmployeePolicy(this, '<?php echo $policyRes[$i]['policy_id']; ?>')" >Agree</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php 
}
}
  } 
  ?>  


<!--
<div class="modal fade" id="Mymodal-accept" role="dialog">
    <div class="modal-dialog">
    
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Lorem Ipsum</h4>
        </div>
        <div class="modal-body">
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        </div>
        <div class="modal-footer">
            <a type="button" class="btn btn-default pull-left" href=""><i class="fa fa-file-pdf-o" aria-hidden="true" style="color: red"></i> View</a>
          <a type="button" class="btn btn-success" href="">Accept</a>    
        </div>
      </div>
      
    </div>
  </div>  
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$('#Mymodal-accept').modal({
		backdrop: 'static',
		keyboard: false
	});     
});
</script>-->