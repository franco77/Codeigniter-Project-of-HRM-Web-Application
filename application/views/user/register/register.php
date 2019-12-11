<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="section section-title-wr">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h3 class="section-title">
                    <span class="c-dark">Register</span>
                </h3> 
            </div>
        </div>
    </div>
</div>
<div class="section main-section">
	<div class="container">
		<div class="row"> 
		    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		    	<div class="form-content page-content">
					<?= form_open() ?>
						<h4 class="dark-grey">Create a Free account</h4>
						<hr class="colorgraph">
						<?php if (validation_errors()) : ?>
							<div class="col-md-12">
								<div class="alert alert-danger" role="alert">
									<?= validation_errors() ?>
								</div>
							</div>
						<?php endif; ?>
						<?php if (isset($error)) : ?>
							<div class="col-md-12">
								<div class="alert alert-danger" role="alert">
									<?= $error ?>
								</div>
							</div>
						<?php endif; ?>
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>First name</label>
								<div class="form-group">
			                        <input type="text" name="fname" id="fname" class="form-control input-sm">
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>Last name</label>
								<div class="form-group">
									<input type="text" name="lname" id="lname" class="form-control input-sm"/>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>Postal code</label>
								<div class="form-group">
			                        <input type="text" name="postcode" id="postcode" class="form-control input-sm"/>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>Mobile number</label>
								<div class="form-group">
									<input type="text" name="mobile_no" id="mobile_no" class="form-control input-sm"/>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-12">
								<label>Email id</label>
								<div class="form-group">
									<input type="email" name="email" id="email" class="form-control input-sm">
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-12">
								<label>Choose your username</label>
								<div class="form-group">
									<input type="text" name="username" id="username" class="form-control input-sm">
								</div>
							</div>
						</div> 
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>Password</label>
								<div class="form-group">
									<input type="password" name="password" id="password" class="form-control input-sm">
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>Confirm password</label>
								<div class="form-group">
									<input type="password" name="password_confirm" id="password_confirm" class="form-control input-sm">
								</div>
							</div>
						</div> 
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>Select your state</label>
								<div class="form-group">
									<select name="state" id="state" class="form-control input-sm">
										<option value="">--Select State--</option>
										<option value="odisha">Odisha</option> 
									</select> 
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>Select your district</label>
								<div class="form-group">
									<select name="district" id="district" class="form-control input-sm">
										<option>--Select district--</option>
										<option value="puri">Puri</option>
										<option value="khurda">Khurda</option> 
										<option value="cuttack">Cuttack</option> 
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>Select your block</label>
								<div class="form-group">
									<select name="block" id="block" class="form-control input-sm">
										<option>--Select block--</option>
										<option value="Nimapada">Nimapada</option>
										<option value="Satyabadi">Satyabadi</option> 
										<option value="Brahmagiri">Brahmagiri</option> 
									</select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>Select your panchayat</label>
								<div class="form-group">
									<select name="panchayat" id="panchayat" class="form-control input-sm">
										<option>--Select panchayat--</option>
										<option value="Ambapada">Ambapada</option>
										<option value="Badabenakudi">Badabenakudi</option> 
										<option value="Kapileswarpur">Kapileswarpur</option> 
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>Pan card number</label>
								<div class="form-group">
									<input type="text" name="pancard" id="pancard" class="form-control input-sm">
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>Birthday</label>
								<div class="form-group">
									<input type="text" name="birthday" id="datepicker" class="form-control input-sm"> 
								</div>
							</div>  
						</div> 
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-12">
							<label>User Type</label>
								<div class="form-group">
									<input type="radio" name="usertype" value="A"/> Agent
									<input type="radio" name="usertype" value="B"/> Block Administrator
									<input type="radio" name="usertype" value="C"/> District Administrator
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6">
								<label>Gender</label>
								<div class="form-group">
									<input type="radio" name="gender" value="M"/> Male
									<input type="radio" name="gender" value="F"/> Female
								</div>
							</div> 
						</div>

						<!--<div class="row">
							<div class="col-xs-4 col-sm-3 col-md-3">
								<span class="button-checkbox">
									<button type="button" class="btn" data-color="info" tabindex="7">I Agree</button>
			                        <input type="checkbox" name="t_and_c" id="t_and_c" class="hidden" value="1">
								</span>
							</div>
							<div class="col-xs-8 col-sm-9 col-md-9">
								 By clicking <strong class="label label-primary">Register</strong>, you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a> set out by this site, including our Cookie Use.
							</div>
						</div>-->
						
						<hr class="colorgraph">
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-12">
								<div class="form-group">
			                    	<input type="submit" class="btn btn-primary center-block" value="Register">
			                    </div>
							</div>
							<!--<div class="col-xs-6 col-sm-6 col-md-6">
								<a href="" class="btn btn-lg btn-primary btn-block">Register</a>
							</div>-->
						</div> 
					<?= form_close() ?>
				</div>
			</div>
		</div>
	</div> 
</div>