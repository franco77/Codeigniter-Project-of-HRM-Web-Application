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
					<legend class="pkheader">Recuritment Report</legend>
					<form action="<?= base_url('en/hr/ytd_recuritment_export') ?>" method="POST">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">YTD Report of all Positions</legend>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Department </label>
									<div class="col-sm-8">
										<select name="searchDepartment" class="form-control input-sm" onchange="getDesgnation(this)"> 
											<option value="" >Select</option>
											<?php 
											for($l=0; $l < count($department); $l++) 
											{?>
												<option value="<?php echo $department[$l]['dept_id']; ?>" ><?php echo $department[$l]['dept_name']; ?></option>	
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail"  class="col-sm-4 col-form-label">Location</label>
									<div class="col-sm-8">
										<select type="text" name="loc"  class="form-control input-sm">
											<option value="">Select</option>
											<?php foreach($location as $v1): ?>
												<option value="<?= $v1['branch_id']?>"><?= $v1['branch_name']?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">From Date </label>
									<div class="col-sm-8">
										<input type="text" name="Sdate"  class="form-control input-sm datepickerShow_ytds"> 
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">To Date</label>
									<div class="col-sm-8">
										<input type="text" name="Edate"  class="form-control input-sm datepickerShow_ytde"> 
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Status </label>
									<div class="col-sm-8">
										<select type="text" name="status"  class="form-control input-sm"> 
											<option value="all">All</option> 
                                                <option value="0">Close</option> 
                                                <option value="1">Open</option> 
                                                <option value="2">Hold</option> 
											
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
							</div>
							 <div class="col-md-2">
								<input type="submit" id="btnSearch" name="btnSearch" class="btn btn-primary pull-right" value="Export" /> 
							 </div>
						</div>
						</form>
						<br><br>
						<form action="<?= base_url('en/hr/monthly_recuritment_export') ?>" method="POST">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Monthly Report for each Vacant MRF</legend>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Department </label>
									<div class="col-sm-8">
										<select name="dept"  class="form-control input-sm" onchange="getDesgnation(this)"> 
											<option value="" >Select</option>
											<?php 
											for($l=0; $l < count($department); $l++) 
											{?>
												<option value="<?php echo $department[$l]['dept_id']; ?>" ><?php echo $department[$l]['dept_name']; ?></option>	
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail"  class="col-sm-4 col-form-label">Location</label>
									<div class="col-sm-8">
										<select type="text" name="loc"  id="location" class="form-control input-sm">
											<option value="">Select</option>
											<?php foreach($location as $v1): ?>
												<option value="<?= $v1['branch_id']?>"><?= $v1['branch_name']?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">From Date </label>
									<div class="col-sm-8">
										<input type="text" name="Sdate" id="from_date" class="form-control input-sm monthlys"> 
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">To Date</label>
									<div class="col-sm-8">
										<input type="text" name="Edate"  id="to_date" class="form-control input-sm monthlye"> 
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Status </label>
									<div class="col-sm-8">
										<select type="text" name="status"  id="mrf_status" class="form-control input-sm"> 
											<option value="all">All</option> 
                                                <option value="0">Close</option> 
                                                <option value="1">Open</option> 
                                                <option value="2">Hold</option> 
											
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
							</div>
							 <div class="col-md-2">
								<input type="submit"  name="btnSearch" class="btn btn-primary pull-right" value="Export" /> 
							 </div>
						</div>
					</form>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div>
</div>
<script>
$( document ).ready(function() {
		$('.datepickerShow_ytds').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
			$(".datepickerShow_ytde").datepicker("option","minDate", selected)
        }
		});
		$('.datepickerShow_ytde').datepicker({
			dateFormat: 'dd-mm-yy',
			onSelect: function(selected) {
			   $(".datepickerShow_ytds").datepicker("option","maxDate", selected)
			}
		});
		$('.monthlys').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
			$(".monthlye").datepicker("option","minDate", selected)
        }
		});
		$('.monthlye').datepicker({
			dateFormat: 'dd-mm-yy',
			onSelect: function(selected) {
			   $(".monthlys").datepicker("option","maxDate", selected)
			}
		});
	});
</script>