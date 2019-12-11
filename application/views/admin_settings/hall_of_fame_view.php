<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 

<div class="section main-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-content page-content">
                    <div class="mt mb">
                        <div class="col-md-3 center-xs">
							<div class="form-content page-content">
								<?php $this->load->view('admin_settings/left_sidebar');?>
							</div>
                        </div>
                        <div class="col-md-9 center-xs">
							<legend class="pkheader">Hall of Fame</legend>
							<div class="row well">
								<form class="form-horizontal" name="frmRoomBooking" method="POST" action=""  id="hall_of_fame" enctype="multipart/form-data">
								<?php
									if($success_msg != '') { ?>
									<div class="col-md-12"><div class="alert alert-success" role="alert"> <?php echo $success_msg; ?> </div>
									<?php } else if($error_msg != '') { ?>
									<div class="col-md-12"><div class="alert alert-danger" role="alert"> <?php echo $error_msg; ?> </div>
									<?php } ?>
									<div class="form-group">
									  <label class="col-md-2 control-label" for="name">Month</label>  
									  <div class="col-md-3"> 
											<select class="form-control" name="month">
												<?php 
												if(COUNT($halloffame) > 0){ $curmonth = $halloffame[0]['month']; } else { $curmonth = date('m'); }
												for($i = 1 ; $i <= 12; $i++)
												{
												   $allmonth = date("F",mktime(0,0,0,$i,1,date("Y"))); ?>
												   <option value="<?php echo $i;?>" <?php if($curmonth==$i){echo 'selected';}?> ><?php echo $allmonth;?></option>
												<?php } ?>
											</select>
									  </div>
									</div> 
									<div class="form-group">
									  <label class="col-md-2 control-label" for="name">Year</label>  
									  <div class="col-md-3">
											<select id="year" name="year" class="form-control">
												<?php for($i=2014; $i<=(date('Y'));$i++){ ?>
												<option <?php if(COUNT($halloffame) > 0){ if($halloffame[0]['year'] == $i ) echo "selected"; } else if(date('Y') == $i) { echo "selected";} ?> value="<?php echo $i;?>" ><?php echo $i;?></option>
												<?php } ?> 												
											</select>	 
									  </div>
									</div> 
									<div class="form-group">
									  <label class="col-md-2 control-label" for="name">Order</label>  
									  <div class="col-md-3">
											<select id="order" name="order" class="form-control">
												<?php for($i=1;$i<=6;$i++): ?>
													<option <?php if(COUNT($halloffame) > 0){ if($halloffame[0]['h_order'] == $i ) echo "selected"; } else if(date('Y') == $i) { echo "selected";} ?> value="<?= $i ?>"><?= $i ?></option>
												<?php endfor; ?>
											</select>	 
									  </div>
									</div>
									<div class="form-group">
									  <label class="col-md-2 control-label" for="name">Employee</label>  
									  <div class="col-md-6">
											<select id="empID" name="empID" class="selectpicker form-control"  data-live-search="true" required="">
												<option value="" selected disabled>Select Employee</option>
												<?php foreach($employee as $v1): ?>
												<option <?php if(COUNT($halloffame) > 0){ if($halloffame[0]['user_id'] == $v1['login_id'] ) echo "selected"; } ?> value="<?= $v1['login_id']?>"><?php echo $v1['full_name'].'('.$v1['loginhandle'].')';?></option>
												<?php endforeach; ?>												
											</select>	 
									  </div>
									</div> 
									<div class="form-group">
									  <label class="col-md-2 control-label" for="name">Title</label>  
									  <div class="col-md-6">
											<input class="form-control" rows="5" name="title" id="title" required="" <?php if(COUNT($halloffame) > 0){ ?> value="<?= $halloffame[0]['title']; ?>"<?php } ?> >
									  </div>
									</div>
									<div class="form-group">
									  <label class="col-md-2 control-label" for="name">Description</label>  
									  <div class="col-md-6">
											<textarea class="form-control" rows="5" name="description" id="description" required="" ><?php if(COUNT($halloffame) > 0){ echo $halloffame[0]['description'];  } ?></textarea>
									  </div>
									</div> 
									<div class="form-group">
									  <label class="col-md-2 control-label" for="name">Photo</label>  
									  <div class="col-md-6">
											<input type="file" name="photo" id="photo" class="form-control"><?php if(COUNT($halloffame) > 0){ ?><a href="<?= base_url('assets/upload/hall_of_fame/'.$halloffame[0]['image']) ?>" target="_blank">View</a><?php } ?>
									  </div>
									</div>
									
									<div class="form-group">
										<label class="col-md-2 control-label" for="signup"></label>
										<div class="col-md-6">
											<?php if(COUNT($halloffame) > 0){ ?><input type="submit" name="Update" value="Update"  class="btn btn-primary" value="Update" /><?php }  else {?>
											<input type="submit" name="Submit" value="Submit"  class="btn btn-primary" value="Submit" /> <?php } ?>
										</div>
									</div> 
								  </fieldset>
								</form>
							</div>
                        </div>
                        <div class="clearfix"></div> 
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // var frm = $('#hall_of_fame');
	// var site_url = '<?= base_url();?>';
    // $('#hall_of_fame').submit(function (e) {
		// e.preventDefault();
		// var empID = $('#empID').val();
		// var title = $('#title').val();
		// var description = $('#description').val();
		// var photo =  $('#photo')[0].files[0];
		// console.log(photo);
		// $.ajax({
            // type: 'POST',
            // url: site_url+'admin_settings/submit_fall_of_fame',
            // data: {empID:empID,title:title,description:description,photo:photo},
			// cache: false,
			// contentType: false,
			// processType: false,
            // success: function (data) {
                // alert('Submission was successful.');
                // console.log(data);
            // },
            // error: function (data) {
                // console.log('An error occurred.');
                // console.log(data);
            // }
			
        // });
    // });
	// var site_url = '<?= base_url();?>';	
	// $("form#hall_of_fame").submit(function(e) {
		// e.preventDefault();   
		// var formData =  new FormData($(this)[0]);
		// console.log(formData);
		// $.ajax({
			// url: site_url+'admin_settings/submit_fall_of_fame',
			// type: 'POST',
			// data: formData,
			// success: function (data) {
				// alert(data);
			// },
			// cache: false,
			// contentType: false,
			// processData: false
		// });
	// });
</script>