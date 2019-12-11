<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_type = "";
if (isset($_GET['type']))
{
	$get_type = $_GET['type'];
}
?>
<div class="section main-section">
    <div class="container"> 
		<div class="col-md-3 center-xs">
			<div class="form-content page-content">
				<?php $this->load->view('news_and_events/left_sidebar');?>
			</div>
		</div>
		<div class="col-md-9">
			<legend class="pkheader">News & Events <small>(Read all news and events of <?php echo $newsAndEventsMonth; ?>)</small></legend>
			<div class="row well">
				<div class="panel-default"> 
					<!-- /.panel-heading --> 
					<div class="panel-body">
						<?php if($get_type == "" || $get_type == "Upcoming"){ ?>
						<form id="searchForm" name="searchForm" method="POST" action="">
							<div class="well"> 
								<h4 class="box-title">Search</h4>
								<div class="row pkdsearch">
									<div class="col-md-2">
										<span class="pull-right">Choose Month :</span>
									</div>
									<div class="col-md-3">
										<select name="dd_month" class="form-control input-sm"> 
											 <?php
											  for ($i=1;$i<=12;$i++) {
												if ($i == $dd_month){
											 ?>
												<option value="<?php echo($i)?>" selected><?php echo(date(F,mktime(0,0,0,$i,1,2000)))?></option>
											 <?php }else{?>
												 <option value="<?php echo($i)?>"><?php echo(date(F,mktime(0,0,0,$i,1,2000)))?></option>
											 <?php }
											   } ?>
										</select>
										 
									</div>
									<div class="col-md-2">
										<span class="pull-right">Choose Year :</span>
									</div>
									<div class="col-md-3">
										<select name="dd_year" class="form-control input-sm">
										  <?php
										  $yr=date("Y");
										  for ($j=$yr;$j>=2009;$j--){
											if ($j == $dd_year){
										 ?>
											<option value="<?php echo($j)?>" selected><?php echo($j)?></option>
										 <?php }else{?>
										 <option value="<?php echo($j)?>"><?php echo($j)?></option>
										 <?php }
										 }?>
										</select> 
									</div>
									<div class="col-md-2">
										<input type="submit" id="btnSearch" name="btnSearch" class="btn btn-info" value="Search" /> 
									</div>
								</div>
							</div>
						</form>
						<?php } ?>
						<div class="content-heading col-md-12">
							<div class="text">
								<h4><?php echo $title;?></h4>
								<p>Read all news and events of <?php echo $newsAndEventsMonth; ?></p>
							</div>
						</div>
						<div class="col-md-12 content-bottom">
							<div class="content-data"> 
								<?php
									$news_res = count($newsAndEvents);
									if($news_res > 0)
									{
										for($i=0; $i < $news_res; $i++ )
										{?> 
											<?php if($newsAndEvents[$i][1] == 'B'){?>
											<div class="news_box_right">
											<div class="news_box_calendar pull-left">
													<div class="month_year"><?php echo date("M Y", strtotime($newsAndEvents[$i][0]));?></div>
													<div class="date"><?php echo date("d", strtotime($newsAndEvents[$i][0]));?></div>
												</div>
												<?php 
													if($newsAndEvents[$i][5] != '')
													{?>
														<img src="<?php echo base_url('assets/upload/profile/'. $newsAndEvents[$i][5]);?>" alt="" class="profile-picture" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';"> 
														 
													<?php }
													else
													{?>
														<img src="<?php echo base_url('assets/images/head.png');?>" alt="" class="profile-picture"/>
													<?php }
												?> 
												<div class="birthday-content">
													<h4><?php echo $newsAndEvents[$i][2];?></h4>
													<h6 style="margin-bottom: 0px;">( <?php echo $newsAndEvents[$i][3];?> )</h6>
													<h6 class="ontime" style="margin-bottom: 0px;margin-top: 5px;"> <i class="fa fa-clock-o" aria-hidden="true"></i> Birthday on <?php echo date("jS F", strtotime($newsAndEvents[$i][0]));?></h6>
													<?php if($get_type == "Today"){ ?>
													<h6 style="margin-bottom: 0px;margin-top: 5px;"><a style="cursor:pointer;  font-size: 10px;" data-id='<?php echo $newsAndEvents[$i][4];?>' class='birthday' data-toggle="modal" id="modal" data-target="#birthday_myModal"><i class="fa fa-comments"></i> click and greet</a></h6>
													<?php } ?>
													<img src="<?php echo base_url('assets/images/bday-cake.png')?>" alt="HBD" class="greeting-picture"/>
												</div>									
											</div>
											  
										<?php } }
									}
									else
									{?>
										<div class="news_box">
											<div class="col-md-12" style="padding:20px; text-align:center;">No News / Event Found Under this section.</div>
											<div class="clear"></div>
										</div>
									<?php }
								?>  
							</div>
						</div> 
					</div> 
				</div>
			</div>
		<div class="clearfix"></div> 
    </div>
</div>


	<div class="modal fade" id="birthday_myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Send Greetings</h4>
              </div>
			  <form action="" method = 'POST' id="greetFORM">
				  <div class="modal-body">
					<div class="successMsg" id="messageSuccess"></div>
					<div class="greetSec">
						<h5>Write Greetings<span class ="red">*</span></h5>
						<input type="hidden" id="loginID" name="loginID" id="loginID">
						<textarea id="message" name="message" cols="80" rows="7" onfocus="removeError(this);" required></textarea>
					</div>					
				  </div>
				  <div class="modal-footer">
				   <button type="button" id="greetBTN" class="btn btn-success" name="greetBTN" value="greetings">Submit</button>
				   <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				  </div>
			  </form>
            </div>
        </div>
    </div>
	
	
<script>
$(document).on("click", ".birthday", function () {
     var myBookId = $(this).data('id');
	$(".modal-body #loginID").val( myBookId );
	var loginID = $('#loginID').val();
	var user_id = '<?php echo $this->session->userdata('user_id') ?>';
	$('#messageSuccess').html('');
		$.ajax({
		  type:'POST',
		  data:{loginID:loginID,user_id:user_id},
		  url:site_url+'news_and_events/submit_greetings_check',
		  success:function(data){
			  var data= JSON.parse(data);
				if(data.length > 0){
					$('textarea#message').val(data[0].message);
				}
				else{
					$('textarea#message').val('');
				}
				
			}
		});
});

$(document).on('click','#modal',function(){
		$('#greetBTN').show();
		$('.greetSec').show();
		$("#greetFORM")[0].reset();
		$('#messageSuccess').html('');
})

$(document).on('click','#greetBTN',function(){
	var message = $('textarea#message').val();
	var loginID = $('#loginID').val();
	var user_id = '<?php echo $this->session->userdata('user_id') ?>';
	$('#messageSuccess').html('');
	if(message.trim().length == 0){
		$('textarea#message').attr('style', 'border-color: #f00000;');
	}
	if(message.trim().length > 0){
		$.ajax({
		  type:'POST',
		  data:{message:message,loginID:loginID,user_id:user_id},
		  url:site_url+'news_and_events/submit_greetings',
		  success:function(data){
			  if(data == 1){
				   $('#messageSuccess').html('<h4>Your Greetings has sent Successfully</h4>');
				   $('#greetBTN').hide();
				   $('.greetSec').hide();
				   $("#greetFORM")[0].reset();
			  } else {
				  $('#messageSuccess').html('<h4>Your Greetings has updated Successfully</h4>');
				  $('#greetBTN').hide();
				  $('.greetSec').hide();
			  }
			}
		});
	}
});
</script>