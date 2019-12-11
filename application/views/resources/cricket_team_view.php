<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('resources/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Our Cricket Team<small> (Details Of Team)</small></legend>
					<div class="row well"> 
						<form name="mainform" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
							<div class="row pkdsearch">
								<div class="box-header">
									<div class="box-tools">
										<div class="input-group input-group-sm pull-right" style="width: 120px;">
											<select id="choose_year" name="choose_year" class="form-control" onChange="changeCricketTeam();" >
											<?php
												$year = date("Y");
												if($this->input->get('choose_year') != ''){
													$year = $this->input->get('choose_year');
												}
												$yr=date("Y");
												for($j=$yr;$j>=2011;$j--){
													if ($j == $year){
												?>
											<option value="<?php echo $j;?>" selected><?php echo $j;?></option>
											<?php }else{?>
											<option value="<?php echo $j;?>"><?php echo $j;?></option>
											<?php }
											}?> 
											</select> 
										</div>
									</div>
								</div>
							</div>
							<!-- /.box-header -->
							<div class="row">
								
								<?php 
								$players = count($no_of_player);
								if($players > 0){
								for ($i = 0; $i < $players; $i++){?>	
								<div class="col-sm-3">
									<div class="card"> 
										<div class="avatar">
											<?php 
												if($no_of_player[$i]['user_photo_name'] != '')
												{?>
													<img src="<?php echo base_url('assets/upload/profile/'. $no_of_player[$i]['user_photo_name']);?>" alt=""  onerror="this.src='<?php echo base_url('assets/images/head.png');?>';"> 
													 
												<?php }
												else
												{?>
													<img src="<?php echo base_url('assets/images/head.png');?>" alt="" class="profile-picture"/>
												<?php }
											?>  
										</div>
										<div class="content">
											<h4><?php echo $no_of_player[$i]['position'] ?></h4>
											<p>
												
												<?php echo $no_of_player[$i]['full_name'] ?> <br>
											   <?php echo $no_of_player[$i]['loginhandle'] ?></p>
										</div>
									</div>
								</div>
								<?php } } else {?>
									<div class="col-sm-3">
									<div class="card"> 
										<div class="content">
											<p>
												No Team data added yet.
										</div>
									</div>
									</div>
								<?php }?>
							</div>
						<form> 
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>
<script type="text/javascript">
var site_url = '<?php echo base_url(); ?>';
function changeCricketTeam(){
	var ch_year = $("#choose_year").val();
	window.location.href = site_url+"resources/cricket_team?choose_year="+ch_year;
}
</script>