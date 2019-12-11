
<div class="container-login100" style="background-image: url('../assets/dist/images/bg-01.jpg');">
	<div class="container">
				<div class="col-md-12">
					<center>
						<a href="<?php echo base_url();?>"><img alt="" src="<?php echo base_url('assets/images/logo.gif')?>"></a>
					</center>
				</div>
	</div>
	<div class="container">
		<div class="omb_login">
			
			
			<div class="col-md-5 display-none">
				<div class="form-box">
					<div class="panel panel-default panel-order">
						<div class="panel-heading">
							<h4><img src="<?php echo base_url('assets/images/birthday.gif');?>" class="birthday-img">&nbsp;Upcoming Birthdays</h4>
						</div>
						<div class="panel-body">
						 <?php 
						 $sNum = count($sInfo_arr);
						 
						 for($i=0; $i < $sNum ; $i++){
                    ?>
							<div class="row gap-section">
								<div class="col-md-2">
								<?php 
								if($sInfo_arr[$i]['user_photo_name'] != '')
								{ ?>
									<img src="<?php echo base_url('assets/upload/profile/'. $sInfo_arr[$i]['user_photo_name']);?>" align="left" style="width: 34px; height: 40px;" class="media-object">
								<?php }
								else
								{?>
									<img src="<?php echo base_url('assets/images/no-image.jpg');?>" align="left" style="width: 34px; height: 40px;" class="media-object"> 
								<?php }
								?>
								 
								</div>
								<div class="col-md-10 no-padding">
									
											<div class="pull-right"><img src="<?php echo base_url('assets/images/candel.png')?>" class="media-object"></div>
											<span><strong><?php echo ucfirst($sInfo_arr[$i]['full_name'])?></strong></span><br>
											<span><?php echo date('jS F, Y', strtotime(date("Y").'-'.$sInfo_arr[$i]['dob_with_current_year']));?></span> 					
										
								</div>
							</div>
							<?php }?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-2 display-none"></div>
			<div class="col-md-5">
				<div class="login-form">
					<div class="form-box">
						<div class="form-top">
							<div class="form-top-left">
								<h4 class="pk_login">Login</h4> 
							</div>
						</div>
						<div class="form-bottom">
							<?php if(validation_errors()) : ?>
										<div class="col-md-12">
											<div class="alert alert-danger" role="alert">
												<?= validation_errors() ?>
											</div>
										</div>
									<?php endif; ?>
									<?php if(isset($error)) : ?>
										<div class="col-md-12">
											<div class="alert alert-danger" role="alert">
												<?= $error ?>
											</div>
										</div>
							<?php endif; ?>
							<form method="post" action="" name="login">
								<div class="form-group">
									<label class="sr-only" for="form-username">Username</label>
									<input type="text" id="txt_email" name="txt_email" placeholder="Employee Id" class="form-username form-control" value="<?php echo(stripslashes(htmlspecialchars($this->input->post('txt_email'))))?>"  >
									<i class="fa fa-user"></i>
								</div>
								<div class="form-group">
									<label class="sr-only" for="form-password">Password</label>
									 <input type="password" id="txt_pwd" name="txt_pwd" placeholder="Password" value="<?php echo(stripslashes(htmlspecialchars($this->input->post('txt_pwd'))))?>" class="form-password form-control"/>
									<i class="fa fa-lock"></i>
								</div>
							<div class="container-login100-form-btn">
						

									<button type="submit" class="btn btn-md btn-info" name="login">Log In</button>
														</div>
								
								<div class="form-bottom">
									<label class="control">
										<input type="checkbox" class="label-checkbox100" value="1" id="remember_me" name="remember_me" <?php echo $this->input->post('remember_me') ? 'checked' : '';?> /><font color="#fff">Remember me ?</font>
									</label> 
									<a href="#" class="custom-link" data-toggle="modal" data-target="#myModal">Forgot password?</a> 
								</div>
								<input type="hidden" name="hid_status" value="login" />
								<input type="hidden" name="loginstate" value="1" />
								<input type="hidden" name="url_to_go" value="<?php //echo $url_to_go) ?>" />
								<input type="hidden" name="attach_str" value="<?php //echo($attach_str) ?>" />
							</form>
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
						<h5>Please Contact HR people for new password.</h5> 
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row login-footer col-md-12">
			<p>Copyright © <?php echo date("Y") ?> AABSYS IT Pvt. Ltd. All rights reserved.</p>
		</div>
	</div>
</div>
