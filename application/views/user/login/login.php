<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="section section-title-wr">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h3 class="section-title">
                    <span class="c-dark">Login</span>
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
						<fieldset>
							<h4  class="dark-grey">Please Login</h4>
							<hr class="colorgraph">
							<?php if (validation_errors()) : ?>
								<div class="col-md-6">
									<div class="alert alert-danger" role="alert">
										<?= validation_errors() ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if (isset($error)) : ?>
								<div class="col-md-6">
									<div class="alert alert-danger" role="alert">
										<?= $error ?>
									</div>
								</div>
							<?php endif; ?>
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-12">
									<div class="form-group">
					                    <input type="text" name="username" id="username" class="form-control input-sm" placeholder="Username">
									</div>
									<div class="form-group">
					                    <input type="password" name="password" id="password" class="form-control input-sm" placeholder="Password">
									</div>
								</div>
							</div>
							<!-- <span class="button-checkbox">
								<button type="button" class="btn" data-color="info">Remember Me</button>
			                    <input type="checkbox" name="remember_me" id="remember_me" checked="checked" class="hidden">
								<a href="#" class="btn btn-link pull-right">Forgot Password?</a>
							</span>
							<hr class="colorgraph"> -->
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-12">
									<div class="form-group">
			                        	<input type="submit" class="btn btn-primary center-block" value="Login">
			                        </div>
								</div>
								<!--<div class="col-xs-6 col-sm-6 col-md-6">
									<a href="" class="btn btn-lg btn-primary btn-block">Register</a>
								</div>-->
							</div>
						</fieldset>
					<?= form_close() ?>
				</div>
			</div>
		</div>
	</div> 
</div>