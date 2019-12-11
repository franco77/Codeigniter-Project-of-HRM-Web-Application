<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Apply For Resignation/Termination</legend>
					<div class="row well">
						<form id="frmSalaryUpdate" name="frmSalaryUpdate" method="POST" action="" enctype="multipart/form-data" class="form-horizontal">
						  <fieldset> 
							 <div><?php echo $messageForm; ?></div>
							<div class="form-group">
							  <label class="col-md-2 control-label" for="name">Subject</label>  
							  <div class="col-md-4">
							  <input type="text" id="subject" name="subject" class="form-control input-md" required=""> 
							  </div>
							</div>
							<div class="form-group">
							  <label class="col-md-2 control-label" for="name">Last Working Date</label>  
							  <div class="col-md-4">
							  <input  type="text" name="date" id="dp1" placeholder="Last Working Date" class="form-control input-md" required=""  autocomplete="off"> 
							  </div>
							</div>
							 
							<div class="form-group">
								<label class="col-md-2 control-label" for="name">Reason Of Separation</label>  
								<div class="col-md-4"> 
									<select id="selReaSep" name="selReaSep" class="form-control">
										<option>Select</option>
										<?php
											$res = count($separation);
											for($i=0;$i<$res;$i++)
											{?> 
												<option value="<?php echo $separation[$i]['separation_id'];?>" ><?php echo $separation[$i]['separation_name'];?></option>
											<?php }
										?> 
									</select>									  
								</div>
							</div> 
							
							<div class="form-group">
							  <label class="col-md-2 control-label" for="name">Message</label>  
							  <div class="col-md-6">
							  <textarea class="form-control" rows="5" name="txtmessage" id="txtmessage"></textarea> 
							  </div>
							</div> 
							<div class="form-group">
							<label class="col-md-4 control-label" for="name">&nbsp;</label>  
							  <div class="col-md-4">
								<input type="submit" id="btnAddMessage" name="btnAddMessage" value="APPLY" class="btn btn-info pull-right"/> 
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
 