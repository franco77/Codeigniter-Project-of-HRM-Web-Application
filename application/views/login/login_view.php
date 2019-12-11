<div class="container-login100" style="background-image: url('../assets/dist/images/polo_bg.jpg');">
	<div class="container">
		<div class="col-md-12">
			<center>
				<a href="<?php echo base_url();?>"><img alt="POLOSOFT" src="<?php echo base_url('assets/images/logo.gif')?>" style="width: 220px;"></a>
			</center>
		</div>
	</div>
	<div class="container">
		<div class="col-md-6">
			<div class="row">
				<div class="bd_section">
                	<div class="bd_hdr">
                    	<div class="bd_hdr_text">Upcoming Birthdays</div>
                    </div>
					
					<?php
					for($i=0; $i < count($birthdayInfo); $i++)
					{?>
					
                    <div class="bd_box">
                        <div class="cndel">
                            <div class="bd_img">
								<img src="<?php echo base_url('assets/upload/profile/'. $birthdayInfo[$i]['user_photo_name']);?>" alt="" style="width: 38px; height: 38px;" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';" />                            
							</div>
                            <div class="bd_name">
                                <div class="name"><?php echo $birthdayInfo[$i]['full_name']; ?></div>
                                <div class="icos">
                                    <img src="<?php echo base_url(); ?>assets/images/calendar_ico.gif" alt="" align="left"> <span class="small_text">  &nbsp;<?php echo date("jS F", strtotime($birthdayInfo[$i]['dob'])).', '.date('Y');?></span>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
					<?php } ?>
				</div>					
			</div>
		</div>
		<div class="col-md-6">
			<div class="pklogin">
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
									
								</div>
								<div class="form-group">
									<label class="sr-only" for="form-password">Password</label>
									 <input type="password" id="txt_pwd" name="txt_pwd" placeholder="Password" value="<?php echo(stripslashes(htmlspecialchars($this->input->post('txt_pwd'))))?>" class="form-password form-control"/>
									
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
		</div>
		
		<div class="row login-footer col-md-12">
			<p style="font-family:calibri;">Copyright © <?php echo date("Y") ?> POLOSOFT TECHNOLOGIES Pvt. Ltd. All rights reserved.</p>
		</div>
	</div>
	<!-- Modal -->
			<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header"> 
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
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
