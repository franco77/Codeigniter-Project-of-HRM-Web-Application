<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 

<div class="section main-section">
    <div class="container page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-content ">
                    <div class="mt mb">
                        <div class="col-md-3 center-xs">
							<div class="form-content">
								<?php $this->load->view('hr_help_desk/left_sidebar');?>
							</div>
                        </div>
                        <div class="col-md-9 center-xs">
							<legend class="pkheader">Online Conference Room Booking(<small>Fill theOnline Conference Room Booking Form Here</small>)</legend>
							<div class="row well">
								<form class="form-horizontal" id="frmRoomBooking" name="frmRoomBooking" method="POST" action="" enctype="multipart/form-data">
									<div class="form-group">
									  <label class="col-md-2 control-label" for="name">Book Date</label>  
									  <div class="col-md-4">
									  <input type="text" id="book_date" name="book_date" placeholder="" class="form-control input-md showDatePicker" required="" > 
									  </div>
									</div> 
									<div class="form-group">
									  <label class="col-md-2 control-label" for="name">Book Time</label>  
									  <div class="col-md-4">
									  <input type="text" id="book_time" name="book_time" placeholder="HH:MM:SS" class="form-control input-md" required=""> 
									  </div>
									</div> 
									<div class="form-group">
										<label class="col-md-2 control-label" for="name">Room Name</label>  
										<div class="col-md-4"> 
											<select id="room_name" name="room_name" class="form-control">
												<option value="Ground Floor">Ground Floor</option>
												<option value="Upper Ground Floor">Upper Ground Floor</option>
												<option value="Mezzanine Floor">Mezzanine Floor</option>
												<option value="Training Hall">Training Hall</option> 
											</select>									  
										</div>
									</div> 
									<div class="form-group">
										<label class="col-md-2 control-label" for="signup"></label>
										<div class="col-md-6">
											<input type="submit" id="btnAddMessage" name="btnAddMessage" class="btn btn-primary" value="APPLY" /> 
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