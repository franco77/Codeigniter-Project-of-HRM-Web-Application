<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h4 class="section-title">
				<span class="c-dark">Events</span>
			</h4>
		</div>
	</div>
</div>
<div class="section main-section">
    <div class="container"> 
		<div class="col-md-3 center-xs">
			<div class="form-content page-content">
				<?php $this->load->view('news_and_events/left_sidebar');?>
			</div>
		</div>
		<div class="col-md-9"> 
			<div class="panel-default"> 
				<!-- /.panel-heading --> 
				<div class="panel-body"> 
					<div class="content-heading col-md-12">
						<div class="text">
							<h4>NEWS &amp; EVENTS TODAY</h4>
							<p>Read all news and events of <?php echo date('F') ?></p>
						</div>
					</div>
					<div class="col-md-12 content-bottom">
						<div class="content-data">
							<div class="news_calender">
								<div class="news_box_calendar pull-left">
									<div class="month_year"><?php echo date("M Y")?></div>
									<div class="date"><?php echo date("d");?></div>
								</div>
							</div>
							<div class="news_box_right">
									<img src="<?php echo base_url('assets/dist/images/no-image-medium.jpg')?>" alt="profile picture" class="profile-picture" />
									<div class="birthday-content">
										<h4>Rangaballava Swain</h4>
										<p>( Sr. Software Engineer )</p>
										<p>Birthday on 1st October</p>
										<img src="<?php echo base_url('assets/dist/images/candel.png')?>" alt="HBD" class="greeting-picture"/>
									</div>									
							</div>
						</div>
						<div class="content-data">
							<div class="news_calender">
								<div class="news_box_calendar pull-left">
									<div class="month_year"><?php echo date("M Y")?></div>
									<div class="date"><?php echo date("d");?></div>
								</div>
							</div>
							<div class="news_box_right">
									<img src="<?php echo base_url('assets/dist/images/no-image-medium.jpg')?>" alt="profile picture" class="profile-picture" />
									<div class="birthday-content">
										<h4>Subhransu Tripathy</h4>
										<p>( Software Engineer )</p>
										<p>Birthday on 22nd October</p>
										<img src="<?php echo base_url('assets/dist/images/candel.png')?>" alt="HBD" class="greeting-picture"/>
									</div>									
							</div>
					</div>
					
				</div> 
			</div> 
		</div>
		<div class="clearfix"></div> 
    </div>
</div>