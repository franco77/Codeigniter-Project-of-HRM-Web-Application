<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('accounts/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Loan & Advance(<small>Define Loan & Advance of </small>)</legend>
					<div class="row well">
						<form id="frmPhoneAdd" name="frmPhoneAdd" method="POST" action=""> 
							<table class="table table-striped table-bordered table-condensed">		
                                <tr>
									<td><strong>Loan & Advance For the Month of </strong></td>
									<td>
										<select id="selMonth" name="selMonth" class="required" style="width:110px;">
											<option value="">Select</option>
											<option value="01" <?php if($this->input->post('selMonth') == '01') echo 'selected="selected"';?>>January</option>
											<option value="02" <?php if($this->input->post('selMonth') == '02') echo 'selected="selected"';?>>February</option>
											<option value="03" <?php if($this->input->post('selMonth') == '03') echo 'selected="selected"';?>>March</option>
											<option value="04" <?php if($this->input->post('selMonth') == '04') echo 'selected="selected"';?>>April</option>
											<option value="05" <?php if($this->input->post('selMonth') == '05') echo 'selected="selected"';?>>May</option>
											<option value="06" <?php if($this->input->post('selMonth') == '06') echo 'selected="selected"';?>>June</option>
											<option value="07" <?php if($this->input->post('selMonth') == '07') echo 'selected="selected"';?>>July</option>
											<option value="08" <?php if($this->input->post('selMonth') == '08') echo 'selected="selected"';?>>August</option>
											<option value="09" <?php if($this->input->post('selMonth') == '09') echo 'selected="selected"';?>>September</option>
											<option value="10" <?php if($this->input->post('selMonth') == '10') echo 'selected="selected"';?>>October</option>
											<option value="11" <?php if($this->input->post('selMonth') == '11') echo 'selected="selected"';?>>November</option>
											<option value="12" <?php if($this->input->post('selMonth') == '12') echo 'selected="selected"';?>>December</option>
										</select>
										<select id="selYear" name="selYear" class="required" style="width:90px; margin-left:10px;">
											<option value="">Select</option>
											<?php for($i=date('Y')-1; $i<=(date('Y')+10);$i++){ ?>
											<option value="<?php echo $i;?>" <?php if($this->input->post('selYear') == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
											<?php } ?> 														 
										</select> 
										<input type="submit" style="float: none !important; margin: 0 !important;" id="btnFind" name="btnFind" class="btn btn-primary pull-right" value="FIND" />
									</td>
								</tr>	
								<tr><td>&nbsp;</td></tr>                                        
								<tr>
									<td valign="top"> <strong>Loan </strong>(Monthly) :</td>
									<td valign="top">
									<input type="text" id="txtloan" name="txtloan" value="<?php echo $empInfo['loan']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td> 
								</tr>
								 <tr>
									<td valign="top"> <strong>Advance </strong>(Monthly) :</td>
									<td valign="top">
									<input type="text" id="txtadvance" name="txtadvance" value="<?php echo $empInfo['advance']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td> 
								</tr>    
								<tr><td>&nbsp;</td></tr> 
							</table>
							<div class="col-md-12">
								<input type="submit" id="btnAddMessage" name="btnAddMessage" class="btn btn-primary pull-right" value="SUBMIT" /> 
							</div>
						</form>   
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
 

