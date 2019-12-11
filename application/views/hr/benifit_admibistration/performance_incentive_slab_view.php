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
					<legend class="pkheader">Performance Incentive Slab</legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12"><span id="formType"><?php echo $actionBtn; ?></span> PI Slab</strong> </legend>
							<div class="form">
								<div class="form1">
									<form id="frmUpdateHOD" name="frmUpdateHOD" method="POST" action="">
										<input type="hidden" name="pi_id" id="pi_id" value="<?php if(count($rowedit)>0) echo $rowedit[0]['pi_id']; ?>" />
										
										<div class="col-md-6">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-3 col-form-label">PI Slab: </label>
												<div class="col-sm-4">
													<?php 
													$rangeFrom = "";
													$rangeTo = "";
													if(count($rowedit)>0){ 
														$range = $rowedit[0]['range'];
														$rangeAr = explode('-', $range);
														$rangeFrom = $rangeAr[0];
														$rangeTo = $rangeAr[1];
													}
													?>
													<input type="text" id="txtItemValue1" placeholder="From" name="txtItemValue1" value="<?php echo $rangeFrom;?>" autocomplete="off" class="required number form-control" />
												</div>
												<div class="col-sm-5">
													 <input type="text" id="txtItemValue2" placeholder="To" name="txtItemValue2" value="<?php echo $rangeTo;?>" autocomplete="off" class="required number form-control" />
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group row">
												<label for="staticEmail" class="col-sm-4 col-form-label">Value:</label>
												<div class="col-sm-8">
													<input type="text" id="txtItemValue" name="txtItemValue" value="<?php if(count($rowedit)>0) echo $rowedit[0]['pi_value'];?>" autocomplete="off" class="required number form-control" />
												</div>
											</div>
										</div>
										 <div class="col-md-2">
											<input type="button" id="btnUpdateItem" name="btnUpdateItem" class="search_sbmt pull-right btn btn-primary" value="<?php echo $actionBtn; ?>" onclick="piSubmit(this)" />
										 </div>
										 <div class="col-md-12 successMsg" id="piMSG" style="text-align:center;"></div>
										
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info"> 
										<th width="10%">Sl. No</th>
										<th width="40%">PI Slab Range</th>
										<th width="30%">PI Value</th>
										<th width="20%">Action</th> 
									</tr>
								</thead>
								<tbody id="filterData">
									<?php 
									$result = count($performance_details);
									if($result >0)
									{
										for($i=0; $i< $result; $i++)
										{ $j=$i;?>
										<tr>
											<td><?php echo $j+1;?></td>
											<td><?php echo $performance_details[$i]['range'];?></td>
											<td><?php echo $performance_details[$i]['pi_value'];?></td>
											<td><a href="<?php echo base_url(); ?>en/hr/performance_incentive_slab?id=<?php echo $performance_details[$i]['pi_id'];?>&action=edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.gif" alt="Edit" /></a>  <a href="<?php echo base_url(); ?>en/hr/performance_incentive_slab?id=<?php echo $performance_details[$i]['pi_id'];?>&action=delete"><img alt="Delete" src="<?php echo base_url(); ?>assets/images/icon/delete.gif" /></a></td>
										</tr> 
										<?php } 
									}
									else{
									?>
									<tr><td colspan="4" align="center">No records found</td></tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
					</div> 
					</div> 
					
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>