

<!-- start Feedback Section -->
<div id="feedback">
	<a href="#" class='feedback openColorBox' data-toggle="modal" data-target="#feedbackModal" style="right: 0px;"></a>	
</div>
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				 <button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id="exampleModalLabels">
				Give Your Feedback / Suggestion
				</h4>
			</div>
				<div class="modal-body">
				<div class="row">
				
				<div class="col-md-1"></div>
				<div class="col-md-11">
					<fieldset> 
						<div id="feedback_err_msg" style="display:none;color:red;">Please fill all the fields.</div>
						<div id="feedback_server_err_msg" style="display:none;color:red;">Data can not be inserted.Please try again</div>
						<div id="feedback_server_succ_msg" style="display:none;color:green;"><h4>Feedback added successfully</h4></div>
						<div class="feedbackSec">
							<div class="form-group">
								<label class="col-md-4 control-label" for="name">Title <span class="red">*</span></label>  
								<div class="col-md-6">
									<input type="text" id="feedback_title" name="feedback_title" class="form-control required input-md" onfocus="removeError(this);" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" for="pass">Description <span class="red">*</span></label>
								<div class="col-md-6">
									<textarea id="feedback_description" name="feedback_description" class="form-control required input-md" onfocus="removeError(this);" required> </textarea>
								</div>
							</div>
						</div>
					</fieldset>	
				</div>
				</div>
				</div>
				<div class="modal-footer">
					<input type="button" id="btnFeedbackPost" name="btnFeedbackPost" class="btn btn-info pull-right btnFeedbackPost" onclick="submitFeedBack(this);" value="Submit" />
				</div>	
		</div>
	</div>
</div>
<!-- end Feedback Section -->

<div class="row pkhead">
<div class="container">
	<div class="col-md-3"> 
		<div class="logo">
			<a href="<?php echo base_url('home');?>"><img src="<?php echo base_url('assets/images/logo.gif')?>" alt="POLOHRM"></a>
		</div>
	</div>
	<?php if($this->session->userdata('user_name') != ''){ ?>  
    
    <div class="col-md-6"> 
     <?php 
			$login_id=$this->session->userdata('user_id');
			$cur_date=date('Y-m-d');
			$cur_hourtime=date('H');
			
				$date_of_month = $cur_date;
				$day  = date('Y-m-d',strtotime($date_of_month));
				$result = date("l", strtotime($day));
				
				$time_disp=0;
				if($result == "Saturday")
				{
					$time_disp = 16;
				}
				else
				{
					$time_disp = 18;	
				}
			
			if($login_id!=10010){
				
				if($cur_hourtime>=$time_disp)
				{
				
					if($result == "Saturday")
					{
						$dur_time_office = 7;
						$dur_time_work = 6.30;
					}
					else
					{
						$dur_time_office = 9.00;
						$dur_time_work = 8.00;	
					}
	?>
    <table>
    <tr>
    <td>     
     <?php
			
			$sql="SELECT tot_office_duration,tot_attend_duration FROM biomatrics_daily_workduration WHERE login_id = ? AND attend_date = ? ";
			$qr_attnd = $this->db->query($sql,array($login_id,$cur_date))->result_array();
			
			$offc_hrs_arry=explode('.',@$qr_attnd[0]['tot_office_duration'])	; 
			
			$work_hrs_arr=explode('.',@$qr_attnd[0]['tot_attend_duration']);	
	  ?>
      
        <div style="width:230px;" class="<?php if(@$qr_attnd[0]['tot_office_duration']>$dur_time_office){echo "regularize_day";}else{echo "absent_day";} ?> " title="Office duration should be <?=$dur_time_office?> hours for consider as present">
        <div class="white_dates">
        Office Duration: 
        <?php
				if(count(@$qr_attnd)>0)
				{echo ' '.@$offc_hrs_arry[0].' hours '. $offc_hrs_arry[1].' mins.';}
				else
				{echo ' 0 hour 0 min.';}
		 ?>
        </div>
        </div>        
        </td>
        
        <td style="padding-left:5px;">
        <div style="width:230px;" class="<?php if(@$qr_attnd[0]['tot_attend_duration']>$dur_time_work){echo "regularize_day";}else{echo "absent_day";} ?>" title="Work duration should be 
		<?=$dur_time_work?> hours for consider as present">
        <div class="white_dates">        
        Work Duration: 
         <?php
				if(count(@$qr_attnd)>0)
				{echo ' '.@$work_hrs_arr[0].' hours '. $work_hrs_arr[1].' mins.';}
				else
				{echo ' 0 hour 0 min.';}
		 ?>
        
        </div>
        </div> 
       
    </td>
    <td style="padding-left:5px;">
    <img style="cursor:pointer;" title="Click To Update Time" src="<?php echo base_url().'/assets/images/link3.png' ?>" onclick="window.location.href='<?php echo current_url(); ?>' " />    
    </td>
    </tr>    
    </table>
    <?php
				} // evening 6 PM check condition end
	 } ?>
    </div>
    
    <div class="col-md-3">
	<div class="topnav pull-right">
		<a onclick="return false;" class="user">Welcome <b> <?php echo ucwords($this->session->userdata('user_name'));?></b> </a>
		<a href="<?php echo base_url('news_and_events?type=Today');?>"><button type="button" class="btn btn-circle-micro btn-primary" data-tooltip="News & Events Post"  ><?php echo (isset($countEventToday))? $countEventToday: '0' ; ?></button></a>
		<a href="<?php echo base_url('timesheet/my_regularise_application');?>"><button type="button" class="btn btn-circle-micro btn-success"  data-tooltip="Regularization Pending" ><?php echo (isset($countRegularizationPending))? $countRegularizationPending: '0' ; ?> </button></a>
		<a href="<?php echo base_url('timesheet/my_leave_application');?>"><button type="button" class="btn btn-circle-micro btn-danger"  data-tooltip="Leave Pending" ><?php echo (isset($countLeavePending))? $countLeavePending: '0' ; ?></button></a>
		<span class="dropdown">
			<a data-toggle='dropdown' class='dropdown-toggle' href='#'>
				<!--<i class="fa fa-address-book-o fa-2x" aria-hidden="true"></i>-->
				<span class='menu-profile-pic'><img src="<?php echo base_url().'assets/upload/profile/'.$this->session->userdata('user_photo_name');?>" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';" alt="" /></span>
				<span class='caret'></span>
			</a>
			<div class='dropdown-menu dropdown-menu-right profile-box'>
			<div class='profile-pic'><img src="<?php echo base_url().'assets/upload/profile/'.$this->session->userdata('user_photo_name');?>" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';" alt="profile picture" /></div>
				<div role='menu' class='profile-content'> 							
					<a href="<?php echo base_url('my_account');?>" style="text-decoration:none;"><i class="fa fa-user-circle" aria-hidden="true"></i> My profile</a>											
					<a href="#" data-toggle="modal" data-target="#changePasswordModal" style="text-decoration:none;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Change password</a>							
					<a href="<?= base_url('home/logout')?>" style="text-decoration:none;"><i class="fa fa-power-off" aria-hidden="true"></i> Sign out</a>							 
				</div>
			</div>
		</span>
		
	</div>
	<?php if($this->session->userdata('isAReportingManager') == 'YES'){ ?>
	<div class="row">
		<div class="">
		<span class="pull-right" style="margin-right:84px;">
			<p style="float: left;margin-top: 16px;margin-right: 0px;"><a onclick="return false;" class="user">Reporting Manager </a></p>
			<a href="<?php echo base_url('timesheet/regularise_request');?>"><button type="button" class="btn btn-circle-micro btn-success"  data-tooltip="Regularization Pending" ><?php echo (isset($countRegularizationPendingRM))? $countRegularizationPendingRM: '0' ; ?> </button></a>
			<a href="<?php echo base_url('timesheet/leave_request');?>"><button type="button" class="btn btn-circle-micro btn-danger"  data-tooltip="Leave Pending" ><?php echo (isset($countLeavePendingRM))? $countLeavePendingRM: '0' ; ?></button></a>
		</span>
		</div>
	</div>
	<?php } ?>
	
	</div>
	<?php } ?>
    
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				 <button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id="exampleModalLabel">Change Your Password</h4>
			</div>
				<div class="modal-body">
				<div class="row">
				<div class="col-md-1"></div>
				<div class="col-md-11">
					<fieldset> 
					<div class="form-group err-oldmsg">
					</div>
					<div class="resetSec">
					<div class="form-group">
						<label class="col-md-4 control-label" for="name">Old Password <span class="red">*</span></label>  
						<div class="col-md-6">
							<input type="password" id="oldPassword" name="oldPassword" class="form-control required input-md" onfocus="removeError(this);" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="pass">New Password <span class="red">*</span></label>
						<div class="col-md-6">
							<input type="password" id="newPassword" name="newPassword" class="form-control required input-md" onfocus="removeError(this);" required> 
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="pass">Confirm New Password <span class="red">*</span></label>
						<div class="col-md-6">
							<input type="password" id="cnewPassword" name="cnewPassword" class="form-control input-md" onfocus="removeError(this);" required> 
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-12 control-label" for="pass"><span class="red">*</span> <i>Marked fields are mandatory</i></label>
					</div>
					</div>
					</fieldset>	
				</div>
				</div>
				</div>
				<div class="modal-footer">
					<input type="button" id="btnClassifiedPost" name="btnClassifiedPost" class="btn btn-info pull-right resetSec" onclick="resetPasswordSubmit(this);" value="Submit" />
				</div>	
		</div>
	</div>
</div>
<script >
var site_url = '<?php echo base_url(); ?>';
function submitFeedBack(dis){
	$('#feedback_server_err_msg').hide();
	$('#feedback_server_succ_msg').hide();
	$('.feedbackSec').show();
		if($.trim( $("#feedback_description").val())){
			if(!$("#feedback_title").val()){
			 $('#feedback_title').attr('style', 'border-color: #f00000;');
			 return;				
			}

		}else if($("#feedback_title").val()){
			if(!$.trim( $("#feedback_description").val())){
				$('#feedback_description').attr('style', 'border-color: #f00000;');
				return;				
			}
		}else{
			$('#feedback_title').attr('style', 'border-color: #f00000;');
			$('#feedback_description').attr('style', 'border-color: #f00000;');
			return;			
		}
		
		$.ajax({
			type: "POST",
			url: site_url+'home/submit_feedback',
			data: $("#feedbackForm").serialize(), // serializes the form's elements.
			success: function(data)
			{
				if(data != 1){
					$('#feedback_server_err_msg').show();
					$('#feedback_server_succ_msg').hide();
				}
				else{
					$('#feedback_server_err_msg').hide();
					$('#feedback_server_succ_msg').show();	
					$("#feedback_description").val("");
					$("#feedback_title").val("");
					$('.feedbackSec').hide();
					$('.btnFeedbackPost').hide();
					setTimeout(function(){ location.reload(); }, 3000);
				}
		   }
		});
}
function resetPasswordSubmit(dis){
	var oldPassword = $('#oldPassword').val();
	var newPassword = $('#newPassword').val();
	var cnewPassword = $('#cnewPassword').val();
	$('.err-oldmsg').html("");
	$('#oldPassword').removeAttr('style');
	$('#newPassword').removeAttr('style');
	$('#cnewPassword').removeAttr('style');
	$('.resetSec').show();
	if(oldPassword == ""){
		$('#oldPassword').attr('style', 'border-color: #f00000;');
	}
	if(newPassword == ""){
		$('#newPassword').attr('style', 'border-color: #f00000;');
	}
	if(cnewPassword == ""){
		$('#cnewPassword').attr('style', 'border-color: #f00000;');
	}
	if(newPassword != cnewPassword){
		$('#cnewPassword').attr('style', 'border-color: #f00000;');
		$('#oldPassword').attr('style', 'border-color: #f00000;');
		$('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> New Password & Confirm New Password are Not Matched</div></div>');
	}
	else if(oldPassword !="" && newPassword !=""){
		$.ajax({
			type: "POST",
			url: site_url+'home/reset_password',
			data: {oldPassword : oldPassword, newPassword : newPassword}, // serializes the form's elements.
			success: function(data)
			{
				if(data == 1){
					$('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Invalid Old Password</div></div>');
					$('#oldPassword').attr('style', 'border-color: #f00000;');
				}
				else{
					$('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Password changed successfully </div></div>');
					$('#oldPassword').val("");
					$('#newPassword').val("");
					$('#cnewPassword').val("");
					$('.resetSec').hide();
				}
		   }
		});
	}
	/* else{
		$('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please fill all the fields </div></div>');
	} */
}
function removeError(dis){
	$(dis).removeAttr('style');
}

$(document).ready(function(){
	$(".side-menu #dropdown > .collapse.in").parent("#dropdown").children("a").toggleClass("newColor");
	$(".side-menu-container").find(".panel-default").click(function(){
		var current_clicked_elem =  this;
		var cur_ele_id = $(current_clicked_elem).find(".close-state").attr("id");
		$(".close-state").each(function(){
			if($(this).attr("id")==cur_ele_id){
				$(this).find("a.newColor").removeClass("newColor"); 
				$(this).find("a.newColor").addClass("newColor"); 
			}
			else{
				$(this).slideUp();	
			 
				//$(".side-menu #dropdown > .collapse.in").prev().css({"color": "#3fafda", "font-weight": "700"});
			}	
		})
		$(current_clicked_elem).find(".panel-collapse").toggle();
	});
});



$(document).on('ready', function() {
	$('.responsive').slick({
		dots: false,
		infinite: false,
		speed: 600,
		slidesToShow: 7,
		slidesToScroll: 3,
		responsive: [
		{
			breakpoint: 1024,
			settings: {
				slidesToShow: 5,
				slidesToScroll: 3,
				infinite: true,
				dots: true
			}
		},
		{
			breakpoint: 600,
			settings: {
				slidesToShow: 2,
				slidesToScroll: 1
			}
		},
		{
			breakpoint: 480,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			}
		}
		]
	});
	
	//$('.list-item').hide();
	//setTimeout(function(){$(".list-item").show()},1000);

	if($('.list-item').last().prev('.slick-active')){
		$('.list-item').addClass('slamdown');
	} 

	if ($('.slick-arrow').length) {
		$('.slider').css('margin','0px auto');
	}
	var n = $( ".list-item" ).length;
	if (n>8) {
		$(".slick-arrow").css(
		"opacity" , "1"
		);
	}

});
</script>
<div class="menu-section">
	<div class="container"> 
		<div id="custom-bootstrap-menu" class="navbar navbar-default " role="navigation">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			
			 <p class="navbar-text visible-xs-inline-block">I Compass MENU</p>
				
				<!--<a class="navbar-brand" href=""><?php //echo $site_name; ?></a>-->
				
			<div class="collapse navbar-collapse navbar-menubuilder">
			 
			<?php 
				if($this->session->userdata('user_type') == 'ADMINISTRATOR')
				{?>
					<ul class="nav navbar-nav navbar-left responsive slider">
					<?php foreach ($administrator_menu as $parent => $parent_params): ?>
						<?php if ( empty($page_auth[$parent_params['url']]) || $this->ion_auth->in_group($page_auth[$parent_params['url']]) ): ?>
						<?php if (empty($parent_params['children'])): ?>
							<?php //echo $current_uri. '='.$parent_params['url'].' ###'; ?>
							<?php $active = ($current_uri==$parent_params['url'] || $ctrler==$parent); ?>
							<li class="list-item <?php if ($active) echo 'active'; ?>">
								<a href='<?php echo $parent_params['url']; ?>'  <?php if($parent_params['name'] == 'Help Desk'){ echo 'target="_blank"'; } ?>>
									<?php echo $parent_params['name']; ?>
								</a>
							</li>

						<?php else: ?>

							<?php $parent_active = ($ctrler==$parent); ?>
							<li class='dropdown list-item <?php if ($parent_active) echo 'active'; ?>'>
								<a data-toggle='dropdown' class='dropdown-toggle' href='#'>
									<?php echo $parent_params['name']; ?> <span class='caret'></span>
								</a>
								<ul role='menu' class='dropdown-menu'>
									<?php foreach ($parent_params['children'] as $name => $url): ?>
									<?php if ( empty($page_auth[$url]) || $this->ion_auth->in_group($page_auth[$url]) ): ?>
										<li><a href='<?php echo $url; ?>'><?php echo $name; ?></a></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</li>

						<?php endif; ?>
						<?php endif; ?>

					<?php endforeach; ?>
				</ul> 
				<?php }
				elseif($this->session->userdata('user_type') == 'MANAGEMENT')
				{?>
					<ul class="nav navbar-nav navbar-left responsive slider">
					<?php foreach ($managementt_menu as $parent => $parent_params): ?>
						<?php if ( empty($page_auth[$parent_params['url']]) || $this->ion_auth->in_group($page_auth[$parent_params['url']]) ): ?>
						<?php if (empty($parent_params['children'])): ?>

							<?php $active = ($current_uri==$parent_params['url'] || $ctrler==$parent); ?>
							<li class="list-item <?php if ($active) echo 'active'; ?>">
								<a href='<?php echo $parent_params['url']; ?>'  <?php if($parent_params['name'] == 'Help Desk'){ echo 'target="_blank"'; } ?>>
									<?php echo $parent_params['name']; ?>
								</a>
							</li>

						<?php else: ?>

							<?php $parent_active = ($ctrler==$parent); ?>
							<li class='dropdown list-item <?php if ($parent_active) echo 'active'; ?>'>
								<a data-toggle='dropdown' class='dropdown-toggle' href='#'>
									<?php echo $parent_params['name']; ?> <span class='caret'></span>
								</a>
								<ul role='menu' class='dropdown-menu'>
									<?php foreach ($parent_params['children'] as $name => $url): ?>
									<?php if ( empty($page_auth[$url]) || $this->ion_auth->in_group($page_auth[$url]) ): ?>
										<li><a href='<?php echo $url; ?>'><?php echo $name; ?></a></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</li>

						<?php endif; ?>
						<?php endif; ?>

					<?php endforeach; ?>
				</ul>  
				<?php }
				elseif($this->session->userdata('user_type') == 'HR' || $this->session->userdata('user_type') == 'HRM')
				{?>
					<ul class="nav navbar-nav navbar-left responsive slider">
					<?php foreach ($hrr_menu as $parent => $parent_params): ?>
						<?php if ( empty($page_auth[$parent_params['url']]) || $this->ion_auth->in_group($page_auth[$parent_params['url']]) ): ?>
						<?php if (empty($parent_params['children'])): ?>

							<?php $active = ($current_uri==$parent_params['url'] || $ctrler==$parent); ?>
							<li class="list-item <?php if ($active) echo 'active'; ?>">
								<a href='<?php echo $parent_params['url']; ?>'  <?php if($parent_params['name'] == 'Help Desk'){ echo 'target="_blank"'; } ?>>
									<?php echo $parent_params['name']; ?>
								</a>
							</li>

						<?php else: ?>

							<?php $parent_active = ($ctrler==$parent); ?>
							<li class='dropdown list-item <?php if ($parent_active) echo 'active'; ?>'>
								<a data-toggle='dropdown' class='dropdown-toggle' href='#'>
									<?php echo $parent_params['name']; ?> <span class='caret'></span>
								</a>
								<ul role='menu' class='dropdown-menu'>
									<?php foreach ($parent_params['children'] as $name => $url): ?>
									<?php if ( empty($page_auth[$url]) || $this->ion_auth->in_group($page_auth[$url]) ): ?>
										<li><a href='<?php echo $url; ?>'><?php echo $name; ?></a></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</li>

						<?php endif; ?>
						<?php endif; ?>

					<?php endforeach; ?>
				</ul> 
				<?php }
				elseif($this->session->userdata('user_type') == 'AC' || $this->session->userdata('user_type') == 'ACM')
				{ ?>
					<ul class="nav navbar-nav navbar-left responsive slider">
					<?php foreach ($ac_menu as $parent => $parent_params): ?>
						<?php if ( empty($page_auth[$parent_params['url']]) || $this->ion_auth->in_group($page_auth[$parent_params['url']]) ): ?>
						<?php if (empty($parent_params['children'])): ?>

							<?php $active = ($current_uri==$parent_params['url'] || $ctrler==$parent); ?>
							<li class="list-item <?php if ($active) echo 'active'; ?>">
								<a href='<?php echo $parent_params['url']; ?>'  <?php if($parent_params['name'] == 'Help Desk'){ echo 'target="_blank"'; } ?>>
									<?php echo $parent_params['name']; ?>
								</a>
							</li>

						<?php else: ?>

							<?php $parent_active = ($ctrler==$parent); ?>
							<li class='dropdown list-item <?php if ($parent_active) echo 'active'; ?>'>
								<a data-toggle='dropdown' class='dropdown-toggle' href='#'>
									<?php echo $parent_params['name']; ?> <span class='caret'></span>
								</a>
								<ul role='menu' class='dropdown-menu'>
									<?php foreach ($parent_params['children'] as $name => $url): ?>
									<?php if ( empty($page_auth[$url]) || $this->ion_auth->in_group($page_auth[$url]) ): ?>
										<li><a href='<?php echo $url; ?>'><?php echo $name; ?></a></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</li>

						<?php endif; ?>
						<?php endif; ?>

					<?php endforeach; ?>
				</ul> 
				<?php }
				elseif($this->session->userdata('user_type') == 'EMP')
				{ ?>
					<ul class="nav navbar-nav navbar-left responsive slider">
					<?php foreach ($emp_menu as $parent => $parent_params): ?>
						<?php if ( empty($page_auth[$parent_params['url']]) || $this->ion_auth->in_group($page_auth[$parent_params['url']]) ): ?>
						<?php if (empty($parent_params['children'])): ?>

							<?php $active = ($current_uri==$parent_params['url'] || $ctrler==$parent); ?>
							<li class="list-item <?php if ($active) echo 'active'; ?>">
								<a href='<?php echo $parent_params['url']; ?>' <?php if($parent_params['name'] == 'Help Desk'){ echo 'target="_blank"'; } ?>>
									<?php echo $parent_params['name']; ?>
								</a>
							</li>

						<?php else: ?>

							<?php $parent_active = ($ctrler==$parent); ?>
							<li class='dropdown list-item <?php if ($parent_active) echo 'active'; ?>'>
								<a data-toggle='dropdown' class='dropdown-toggle' href='#'>
									<?php echo $parent_params['name']; ?> <span class='caret'></span>
								</a>
								<ul role='menu' class='dropdown-menu'>
									<?php foreach ($parent_params['children'] as $name => $url): ?>
									<?php if ( empty($page_auth[$url]) || $this->ion_auth->in_group($page_auth[$url]) ): ?>
										<li><a href='<?php echo $url; ?>'><?php echo $name; ?></a></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</li>

						<?php endif; ?>
						<?php endif; ?>

					<?php endforeach; ?>
				</ul>  
				<?php }
				elseif($this->session->userdata('user_type') == 'IT')
				{ ?>
					<ul class="nav navbar-nav navbar-left responsive slider">
					<?php foreach ($emp_menu as $parent => $parent_params): ?>
						<?php if ( empty($page_auth[$parent_params['url']]) || $this->ion_auth->in_group($page_auth[$parent_params['url']]) ): ?>
						<?php if (empty($parent_params['children'])): ?>

							<?php $active = ($current_uri==$parent_params['url'] || $ctrler==$parent); ?>
							<li class="list-item <?php if ($active) echo 'active'; ?>">
								<a href='<?php echo $parent_params['url']; ?>' <?php if($parent_params['name'] == 'Help Desk'){ echo 'target="_blank"'; } ?>>
									<?php echo $parent_params['name']; ?>
								</a>
							</li>

						<?php else: ?>

							<?php $parent_active = ($ctrler==$parent); ?>
							<li class='dropdown list-item <?php if ($parent_active) echo 'active'; ?>'>
								<a data-toggle='dropdown' class='dropdown-toggle' href='#'>
									<?php echo $parent_params['name']; ?> <span class='caret'></span>
								</a>
								<ul role='menu' class='dropdown-menu'>
									<?php foreach ($parent_params['children'] as $name => $url): ?>
									<?php if ( empty($page_auth[$url]) || $this->ion_auth->in_group($page_auth[$url]) ): ?>
										<li><a href='<?php echo $url; ?>'><?php echo $name; ?></a></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</li>

						<?php endif; ?>
						<?php endif; ?>

					<?php endforeach; ?>
				</ul> 
				<?php } 
				elseif($this->session->userdata('user_type') == 'ADMIN')
				{ ?>
					<ul class="nav navbar-nav navbar-left responsive slider">
					<?php foreach ($admin_menu as $parent => $parent_params): ?>
						<?php if ( empty($page_auth[$parent_params['url']]) || $this->ion_auth->in_group($page_auth[$parent_params['url']]) ): ?>
						<?php if (empty($parent_params['children'])): ?>

							<?php $active = ($current_uri==$parent_params['url'] || $ctrler==$parent); ?>
							<li class="list-item <?php if ($active) echo 'active'; ?>">
								<a href='<?php echo $parent_params['url']; ?>' <?php if($parent_params['name'] == 'Help Desk'){ echo 'target="_blank"'; } ?>>
									<?php echo $parent_params['name']; ?>
								</a>
							</li>

						<?php else: ?>

							<?php $parent_active = ($ctrler==$parent); ?>
							<li class='dropdown list-item <?php if ($parent_active) echo 'active'; ?>'>
								<a data-toggle='dropdown' class='dropdown-toggle' href='#'>
									<?php echo $parent_params['name']; ?> <span class='caret'></span>
								</a>
								<ul role='menu' class='dropdown-menu'>
									<?php foreach ($parent_params['children'] as $name => $url): ?>
									<?php if ( empty($page_auth[$url]) || $this->ion_auth->in_group($page_auth[$url]) ): ?>
										<li><a href='<?php echo $url; ?>'><?php echo $name; ?></a></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</li>

						<?php endif; ?>
						<?php endif; ?>

					<?php endforeach; ?>
				</ul> 
				<?php } ?>
				
			<?php //$this->load->view('_partials/language_switcher'); ?>
			
			</div>
		</div>
	</div>
</div>


<style>
#feedback a {
    background:url(../assets/images/feedback.png) no-repeat scroll -34px 0 transparent;
    height: 105px;
    margin-top: 40px;
    position: fixed;
    right: -100px;
    top: 35%;
    width: 34px;
    z-index: 9999;
	cursor:pointer;
}
#feedback a:hover{ background-position:0 0; }
.side-menu #dropdown > a.newColor {color: #3fafda; font-weight: 700;}
</style>