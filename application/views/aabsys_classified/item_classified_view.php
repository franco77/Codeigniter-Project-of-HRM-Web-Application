<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h4 class="section-title">
				<span class="c-dark">Classified</span>
			</h4>
		</div>
	</div>
</div>
<div class="section main-section">
    <div class="container page-content"> 
		<div class="col-md-3 center-xs">
			<div class="form-content">
				<?php $this->load->view('aabsys_classified/left_sidebar');?>
			</div>
		</div>
		<div class="col-md-9"> 
			<div class="panel-default"> 
				<!-- /.panel-heading --> 
				<div class="panel-body"> 
					<div class="content-heading col-md-12">
						<div class="text">
							<h4>AABSyS Classified Details</h4> 
						</div>
					</div>
					<div class="col-md-12 content-bottom">
						<div class="content-data"> 
							<div class="white_box"> 
								<div class="news_box">
									<div class="news_box_left">
										<div class="classified_img" style="margin-left: 6px;">
										<?php 
											if($classified_Info['classified_file'] != '')
											{?>
												<img class="img-responsive hof-emp-img" alt="" src="<?php echo base_url('assets/upload/classified/'. $classified_Info['classified_file']);?>" alt="" align="left" style="width: 65px; height: 65px;" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';"> 
												 
											<?php }
											else
											{?>
												<img src="<?php echo base_url('assets/images/no-image.jpg');?>" alt="" align="left" style="width: 65px; height: 65px;" />
											<?php }
										?>
										</div>
									</div>
									<div class="news_box_right">
										<div class="hdr">
											<div><strong><?php echo $classified_Info['classified_header'];?></strong></div>
										   <div><?php echo $classified_Info['classified_body'];?></div>
										   <div class="classified_contact">
											   Name : <strong><?php echo $classified_Info['name_first'].' '.$classified_Info['name_last'];?></strong><br />
											   Contact No : <strong><?php echo $classified_Info['mobile'];?></strong>
										   </div>
										</div>
									</div>
									<div class="clear"></div>
								</div> 
							</div>
						</div> 
					</div> 
				</div> 
			</div>
		</div>
		<div class="clearfix"></div> 
    </div>
</div>