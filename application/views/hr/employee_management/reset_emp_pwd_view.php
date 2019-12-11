<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">Reset Password <small>(Reset employee password to default password)</small></legend>
					<div class="row well"> 
						<div class="table-responsive">
							<form id="frmResetEmpPwd" name="frmResetEmpPwd" method="POST" action="">
								<table class="table table-striped table-bordered table-condensed"> 
									<tbody>
										<tr>
											<td>Employee Code:</td>
											<td>
												<div class="srch_bg" style="width: 395px;">
													<input type="text" id="txtEmpSearch" name="txtEmpSearch" class="srch_input_o" placeholder="Search Employee with Employee ID" onclick="if(this.value=='Search Employee'){this.value=''; this.className='srch_input'}" onblur="if(this.value==''){ this.value='Search Employee'; this.className='srch_input_o'}" style="width: 195px;" onfocus="removeErrorEmp(this);" />
													<input type="button" id="btnEmpSearch" name="btnEmpSearch" class="sbmt" value="Click Here" onclick="resetEmpCheck(this);" />
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="2">
											<div id="empNameShow" style="height: 100px;">
												<?php if(isset($_SESSION['showPwdReset'])){
													echo 'Password of employee <strong>"'.$_SESSION['pwdResetEmpName'].'"</strong> reset to default password successfully.';
													unset($_SESSION['showPwdReset']);
													unset($_SESSION['pwdResetEmpName']);
												}?>
											</div>
											</td>
										</tr>
									</tbody> 
								</table>
							<input type="button" id="btnResetEmpPwd" name="btnResetEmpPwd" class="btn btn-primary pull-right" value="Reset"  onclick="resetEmpPassword(this);" />
							</form>
						</div> 
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>
