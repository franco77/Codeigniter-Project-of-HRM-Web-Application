<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Reimbursement</legend>
					<div class="row well">
						<form id="" name="" method="POST" action="" enctype="multipart/form-data" class="form-horizontal">
						<?php
							if($success_msg != '') { ?>
							<div class="col-md-12"><div class="alert alert-success" role="alert"> <?php echo $success_msg; ?> </div>
							<?php } else if($error_msg != '') { ?>
							<div class="col-md-12"><div class="alert alert-danger" role="alert"> <?php echo $error_msg; ?> </div>
							<?php } ?>
						  <fieldset> 
							<div class="form-group">
								  <label class="col-md-3 control-label" for="name">Reimbursement for the Month of</label>  
								  <div class="col-md-3">
									<select type="text" id="selMonth" name="selMonth" class="form-control input-md" >
										<option value="" selected disabled>Select</option>
										<option value="01" <?php if($month == '1') echo 'selected="selected"';?>>January</option>
										<option value="02" <?php if($month == '2') echo 'selected="selected"';?>>February</option>
										<option value="03" <?php if($month == '3') echo 'selected="selected"';?>>March</option>
										<option value="04" <?php if($month == '4') echo 'selected="selected"';?>>April</option>
										<option value="05" <?php if($month == '5') echo 'selected="selected"';?>>May</option>
										<option value="06" <?php if($month == '6') echo 'selected="selected"';?>>June</option>
										<option value="07" <?php if($month == '7') echo 'selected="selected"';?>>July</option>
										<option value="08" <?php if($month == '8') echo 'selected="selected"';?>>August</option>
										<option value="09" <?php if($month == '9') echo 'selected="selected"';?>>September</option>
										<option value="10" <?php if($month == '10') echo 'selected="selected"';?>>October</option>
										<option value="11" <?php if($month == '11') echo 'selected="selected"';?>>November</option>
										<option value="12" <?php if($month == '12') echo 'selected="selected"';?>>December</option>
									</select>
								  </div>
								  <div class="col-md-4">
									<select type="text" id="selYear" name="selYear" class="form-control input-md" >
										<option value="" selected disabled>Select</option>
										<?php for($i=date('Y')-1; $i<=(date('Y')+10);$i++){ ?>
											<option value="<?php echo $i;?>" <?php if($year == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
											<?php } ?>
									</select>
								  </div>
								   <div class="col-md-2">
									<input type="submit" id="btnFind" name="btnFind" value="FIND" class="btn btn-info pull-right"/> 
								  </div>
							</div>
							<?php $j=0; ?>
							<div class="form-group">
								  <label class="col-md-3 control-label" for="name">Mobile(Official)
								  <a class="tooltips">
												&nbsp;<b>?</b>&nbsp;
												<span>
													<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />
													<ol>
														<li>PDF file is allowed</li>
													</ol>
												</span>
											</a></label>  
								  <div class="col-md-4">
									<input id="txtmobile_official"  <?php if($access=='user') echo 'readonly="readonly"';?> name="txtmobile_official" id="dp1" class="form-control input-md"  value="<?php if(COUNT($empInfo) > 0) echo $empInfo[0]['mobile_official']?>" > 
								  </div>
								  <div class="col-md-5">
									<?php if($access=='user'){ ?><input  type="file" name="rdoc_<?= $j++; ?>"  class="form-control input-md" > <?php } if(COUNT($empInfo) > 0){ if($empInfo[0]['doc_mobile_official'] != ''){ ?><a href="<?php echo base_url() ;?>assets/upload/reimbursement/<?php echo $empInfo[0]['doc_mobile_official']?>" target="_blank">View</a><?php }} ?>
								  </div>
							</div>
							<div class="form-group">
								  <label class="col-md-3 control-label" for="name">Mobile(Landline)
								   <a  class="tooltips">
												&nbsp;<b>?</b>&nbsp;
												<span>
													<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />
													<ol>
														<li>PDF file is allowed</li>
													</ol>
												</span>
											</a></label>  
								  <div class="col-md-4">
									<input id="txtmobile_landline"  <?php if($access=='user') echo 'readonly="readonly"';?> name="txtmobile_landline" id="dp1" class="form-control input-md"  value="<?php if(COUNT($empInfo) > 0) echo $empInfo[0]['mobile_landline']?>" > 
								  </div>
								  <div class="col-md-5">
									<?php if($access=='user'){ ?><input  type="file" name="rdoc_<?= $j++; ?>"  class="form-control input-md" > <?php } if(COUNT($empInfo) > 0){  if($empInfo[0]['doc_mobile_landline'] != ''){ ?><a href="<?php echo base_url() ;?>assets/upload/reimbursement/<?php echo $empInfo[0]['doc_mobile_landline']?>" target="_blank">View</a><?php }} ?>
								  </div>
							</div>
							<div class="form-group">
								  <label class="col-md-3 control-label" for="name">Fuel
								   <a class="tooltips">
												&nbsp;<b>?</b>&nbsp;
												<span>
													<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />
													<ol>
														<li>PDF file is allowed</li>
													</ol>
												</span>
											</a></label>  
								  <div class="col-md-4">
									<input id="txtfuel"  <?php if($access=='user') echo 'readonly="readonly"';?> name="txtfuel" class="form-control input-md"  value="<?php if(COUNT($empInfo) > 0) echo $empInfo[0]['fuel']?>" > 
								  </div>
								  <div class="col-md-5">
									<?php if($access=='user'){ ?><input  type="file" name="rdoc_<?= $j++; ?>"  class="form-control input-md" > <?php } if(COUNT($empInfo) > 0){ if($empInfo[0]['doc_fuel'] != ''){ ?><a href="<?php echo base_url() ;?>assets/upload/reimbursement/<?php echo $empInfo[0]['doc_fuel']?>" target="_blank">View</a><?php }} ?>
								  </div>
							</div>
							<div class="form-group">
								  <label class="col-md-3 control-label" for="name">Vehicle Maintenance:
								   <a class="tooltips">
												&nbsp;<b>?</b>&nbsp;
												<span>
													<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />
													<ol>
														<li>PDF file is allowed</li>
													</ol>
												</span>
											</a></label>  
								  <div class="col-md-4">
									<input id="txtvehicle_maintenance"  <?php if($access=='user') echo 'readonly="readonly"';?> name="txtvehicle_maintenance" class="form-control input-md"  value="<?php if(COUNT($empInfo) > 0) echo $empInfo[0]['vehicle_maintenance']?>" > 
								  </div>
								  <div class="col-md-5">
									<?php if($access=='user'){ ?><input  type="file" name="rdoc_<?= $j++; ?>"  class="form-control input-md" > <?php } if(COUNT($empInfo) > 0){  if($empInfo[0]['doc_vehicle_maintenance'] != ''){ ?><a href="<?php echo base_url() ;?>assets/upload/reimbursement/<?php echo $empInfo[0]['doc_vehicle_maintenance']?>" target="_blank">View</a><?php } }?>
								  </div>
							</div>
							<div class="form-group">
								  <label class="col-md-3 control-label" for="name">Entertainment:
								   <a class="tooltips">
												&nbsp;<b>?</b>&nbsp;
												<span>
													<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />
													<ol>
														<li>PDF file is allowed</li>
													</ol>
												</span>
											</a></label>  
								  <div class="col-md-4">
									<input id="txtentertainment"  <?php if($access=='user') echo 'readonly="readonly"';?> name="txtentertainment" class="form-control input-md"  value="<?php if(COUNT($empInfo) > 0) echo $empInfo[0]['entertainment']?>" > 
								  </div>
								  <div class="col-md-5">
									<?php if($access=='user'){ ?><input  type="file" name="rdoc_<?= $j++; ?>"  class="form-control input-md" > <?php } if(COUNT($empInfo) > 0){  if($empInfo[0]['doc_entertainment'] != ''){ ?><a href="<?php echo base_url() ;?>assets/upload/reimbursement/<?php echo $empInfo[0]['doc_entertainment']?>" target="_blank">View</a><?php } }?>
								  </div>
							</div>
							<div class="form-group">
								  <label class="col-md-3 control-label" for="name">Books & Periodical:<a class="tooltips">
												&nbsp;<b>?</b>&nbsp;
												<span>
													<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />
													<ol>
														<li>PDF file is allowed</li>
													</ol>
												</span>
											</a></label>  
								  <div class="col-md-4">
									<input id="txtbook_periodical"  <?php if($access=='user') echo 'readonly="readonly"';?> name="txtbook_periodical" class="form-control input-md"  value="<?php if(COUNT($empInfo) > 0) echo $empInfo[0]['book_periodical']?>" > 
								  </div>
								  <div class="col-md-5">
									<?php if($access=='user'){ ?><input  type="file" name="rdoc_<?= $j++; ?>"  class="form-control input-md" > <?php } if(COUNT($empInfo) > 0){  if($empInfo[0]['doc_book_periodical'] != ''){ ?><a href="<?php echo base_url() ;?>assets/upload/reimbursement/<?php echo $empInfo[0]['doc_book_periodical']?>" target="_blank">View</a><?php } }?>
								  </div>
							</div>
							<div class="form-group">
								  <label class="col-md-3 control-label" for="name">LTA:
								   <a class="tooltips">
												&nbsp;<b>?</b>&nbsp;
												<span>
													<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />
													<ol>
														<li>PDF file is allowed</li>
													</ol>
												</span>
											</a></label>  
								  <div class="col-md-4">
									<input id="txtlta"  <?php if($access=='user') echo 'readonly="readonly"';?> name="txtlta" class="form-control input-md"  value="<?php if(COUNT($empInfo) > 0) echo $empInfo[0]['lta']?>" > 
								  </div>
								  <div class="col-md-5">
									<?php if($access=='user'){ ?><input  type="file" name="rdoc_<?= $j++; ?>"  class="form-control input-md" > <?php } if(COUNT($empInfo) > 0){  if($empInfo[0]['doc_lta'] != ''){?><a href="<?php echo base_url() ;?>assets/upload/reimbursement/<?php echo $empInfo[0]['doc_lta']?>" target="_blank">View</a><?php }} ?>
								  </div>
							</div>
							<div class="form-group">
								  <label class="col-md-3 control-label" for="name">Mediclaim:
								   <a class="tooltips">
												&nbsp;<b>?</b>&nbsp;
												<span>
													<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />
													<ol>
														<li>PDF file is allowed</li>
													</ol>
												</span>
											</a></label>  
								  <div class="col-md-4">
									<input id="txtmediclaim"  <?php if($access=='user') echo 'readonly="readonly"';?> name="txtmediclaim" class="form-control input-md"  value="<?php if(COUNT($empInfo) > 0) echo $empInfo[0]['mediclaim']?>" > 
								  </div>
								  <div class="col-md-5">
									<?php if($access=='user'){ ?><input  type="file" name="rdoc_<?= $j++; ?>"  class="form-control input-md" > <?php } if(COUNT($empInfo) > 0){  if($empInfo[0]['doc_mediclaim'] != ''){ ?><a href="<?php echo base_url() ;?>assets/upload/reimbursement/<?php echo $empInfo[0]['doc_mediclaim']?>" target="_blank">View</a><?php }} ?>
								  </div>
							</div>
							<div class="form-group">
								  <label class="col-md-3 control-label" for="name">Lunch:
								   <a class="tooltips">
												&nbsp;<b>?</b>&nbsp;
												<span>
													<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />
													<ol>
														<li>PDF file is allowed</li>
													</ol>
												</span>
											</a></label>  
								  <div class="col-md-4">
									<input id="txtlunch"  <?php if($access=='user') echo 'readonly="readonly"';?> name="txtlunch" class="form-control input-md"  value="<?php if(COUNT($empInfo) > 0) echo $empInfo[0]['lunch']?>" > 
								  </div>
								  <div class="col-md-5">
									<?php if($access=='user'){ ?><input  type="file" name="rdoc_<?= $j++; ?>"  class="form-control input-md" > <?php } if(COUNT($empInfo) > 0){  if($empInfo[0]['doc_lunch'] != ''){?><a href="<?php echo base_url() ;?>assets/upload/reimbursement/<?php echo $empInfo[0]['doc_lunch']?>" target="_blank">View</a><?php }} ?>
								  </div>
							</div>
							<div class="form-group">
								  <label class="col-md-3 control-label" for="name">Driver's Salary:
								   <a class="tooltips">
												&nbsp;<b>?</b>&nbsp;
												<span>
													<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />
													<ol>
														<li>PDF file is allowed</li>
													</ol>
												</span>
											</a></label>  
								  <div class="col-md-4">
									<input id="txtdriver_salary"  <?php if($access=='user') echo 'readonly="readonly"';?> name="txtdriver_salary" class="form-control input-md"  value="<?php if(COUNT($empInfo) > 0) echo $empInfo[0]['driver_salary']?>" > 
								  </div>
								  <div class="col-md-5">
									<?php if($access=='user'){ ?><input  type="file" name="rdoc_<?= $j++; ?>"  class="form-control input-md" > <?php } if(COUNT($empInfo) > 0){  if($empInfo[0]['doc_driver_salary'] != ''){ ?><a href="<?php echo base_url() ;?>assets/upload/reimbursement/<?php echo $empInfo[0]['doc_driver_salary']?>" target="_blank">View</a><?php } }?>
								  </div>
							</div>
							
							<div class="form-group">
							  <label class="col-md-2 control-label" for="signup"></label>
							  <div class="col-md-12">
								<input type="submit" id="btnAddMessage" name="btnAddMessage" value="SUBMIT" class="btn btn-info pull-right"/> 
							  </div>
							</div> 
						  </fieldset>
						</form>
					</div>
				</div>
				<div class="clearfix"></div> 
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div>
</div>
 
 <style>
 a.tooltips {outline:none; text-decoration: none;
    background: none repeat scroll 0 0 #06c;
    border-radius: 50%;
    box-shadow: 1px 1px 2px rgba(255, 255, 255, 0.6) inset, -1px -1px 2px rgba(0, 0, 0, 0.6) inset;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-size: 13px;
    font-weight: bold;
    height: 15px;
    line-height: 15px;    
    text-align: left;
    vertical-align: middle;
    width: 15px;
    }
    a.tooltips strong {line-height:30px;} 
    a.tooltips:hover {text-decoration:none;font-weight: normal;} 
    a.tooltips span { z-index:10;display:none; padding:14px 20px; margin-top:-30px; margin-left:15px; width:300px; line-height:16px; }
    a.tooltips:hover span{ display:inline; position:absolute; color:#111; border:1px solid #DCA; background:#fffAF0;} 
    .callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;} 
    /*CSS3 extras*/ 
    a.tooltips span { border-radius:4px; box-shadow: 5px 5px 8px #CCC; }
    #dataTable td{
        padding: 4px 7px !important;
    }
 </style>