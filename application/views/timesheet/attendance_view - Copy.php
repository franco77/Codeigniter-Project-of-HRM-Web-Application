<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section section-title-wr">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h3 class="section-title">
                    <span class="c-dark">Time Sheet</span>
                </h3>
            </div>
        </div>
    </div>
</div>
<div class="section main-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-content page-content">
                    <div class="mt mb">
                        <div class="col-md-3 center-xs">
                            <?php $this->load->view('timesheet/left_sidebar');?>
                        </div>
                        <div class="col-md-9">
                            <form class="form-horizontal" action='' method="POST">
							  <fieldset>
								<div id="legend">
								  <legend class="">Register</legend>
								</div>
								<div class="control-group">
								  <!-- Username -->
								  <label class="control-label"  for="username">Username</label>
								  <div class="controls">
									<input type="text" id="username" name="username" placeholder="" class="input-xlarge">
									<p class="help-block">Username can contain any letters or numbers, without spaces</p>
								  </div>
								</div>
							 
								<div class="control-group">
								  <!-- E-mail -->
								  <label class="control-label" for="email">E-mail</label>
								  <div class="controls">
									<input type="text" id="email" name="email" placeholder="" class="input-xlarge">
									<p class="help-block">Please provide your E-mail</p>
								  </div>
								</div>
							 
								<div class="control-group">
								  <!-- Password-->
								  <label class="control-label" for="password">Password</label>
								  <div class="controls">
									<input type="password" id="password" name="password" placeholder="" class="input-xlarge">
									<p class="help-block">Password should be at least 4 characters</p>
								  </div>
								</div>
							 
								<div class="control-group">
								  <!-- Password -->
								  <label class="control-label"  for="password_confirm">Password (Confirm)</label>
								  <div class="controls">
									<input type="password" id="password_confirm" name="password_confirm" placeholder="" class="input-xlarge">
									<p class="help-block">Please confirm password</p>
								  </div>
								</div>
							 
								<div class="control-group">
								  <!-- Button -->
								  <div class="controls">
									<button class="btn btn-success">Register</button>
								  </div>
								</div>
							  </fieldset>
							</form>
                        </div>
                        <div class="clearfix"></div> 
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>