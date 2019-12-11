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
					<legend class="pkheader">Photo Gallery</legend>
					<div class="row well">
						<div class="box"> 
							<div class="row">
								<?php
								$album_no = count($get_photo);
								//var_dump($album_no);exit;
								for ($i = 0; $i < $album_no; $i++){?>
									<div class="col-md-3 img-portfolio">
										
											<img class="img-responsive img-hover" src="<?php echo base_url('assets/images/album/x.jpg')?>" alt="">
										
									</div>
								<?php }?> 
							</div> 
						</div> 
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>