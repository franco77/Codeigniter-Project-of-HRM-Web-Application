<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h4 class="section-title">
				<span class="c-dark">Hr</span>
			</h4>
		</div>
	</div>
</div>
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<form method="POST" action="">
				<div class="mt mb">
					<div class="col-md-3 center-xs">
						<div class="form-content page-content">
							<?php $this->load->view('hr/left_sidebar');?>
						</div>
					</div>
					<div class="col-md-9 center-xs">
						<div class="well"> 
							<h4 class="box-title">All Employee Details(<?php $result = count($activemp); echo $result;?>) <small>(Resource of our Organisation)</small></h4>  
						</div>
						<div class="well">
							<h4 class="box-title">Search / View</h4>
							<div class="row">
								<div class="col-md-3">Department:
									<select name="dd_dept" id="dd_dept" class="form-control input-sm">
										<option>Select</option>
										<?php
											$department = count($dept);
											for($i=0;$i<$department;$i++)
											{?>
												<option><?php echo $dept[$i]['dept_name'];?></option>
											<?php }
										?>
									</select>
								</div>
								<div class="col-md-3">Designation:
									<select name="dd_desg" id="dd_desg" class="form-control input-sm">
										<option>Select</option>
										<?php
											$designation = count($desg);
											for($i=0;$i<$designation;$i++)
											{?>
												<option><?php echo $desg[$i]['desg_name'];?></option>
											<?php }
										?>
									</select>
								</div>
								<div class="col-md-3">Name:
									<input type="text" name="name" size="20" maxlength="50" placeholder="Enter Name" value="<?php echo(stripslashes(htmlspecialchars($this->input->post('name'))))?>" class="form-control input-sm">
								</div>
								<div class="col-md-3">Emp Code:
									<input type="text" name="emp_code" placeholder="Enter Employee Code" value="<?php echo(stripslashes(htmlspecialchars($this->input->post('emp_code'))))?>" class="form-control input-sm"> 
								</div> 
								<div class="col-md-12">
									<input type="submit" name="searchEmployee" value="Find" class="btn btn-primary pull-right"/> 
								</div>
							</div>
						</div>
						<div class="row"> 
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-condensed">
									<thead>
										<tr>
											<th>Name</th>
											<th>Employee Code</th>
											<th>Email</th>
											<th>Designation</th>
											<th>Emp Status</th> 
										</tr>
									</thead>
									<tbody>
									<?php
										$result = count($activemp);
										//var_dump($result);exit;
										if($result >0)
										{
											for($i=0; $i<$result; $i++){
											?>
											<tr>
												<td><a class="link" href="profile_readonly_emp.php?id=<?php echo urlencode($activemp[$i]['login_id']);?>"><?php echo $activemp[$i]['name'];?></a></td>
												<td><?php echo $activemp[$i]['loginhandle'];?></td>
												<td><?php echo $activemp[$i]['email'];?></td>
												<td><?php echo $activemp[$i]['desg_name'];?></td>
												<td><?php if($activemp[$i]['user_status']==1) echo "Active";else if($activemp[$i]['user_status']==2) echo "Inactive";?></td>
											</tr>
											<?php }
										}
										else
										{?>
											<tr><td>No records found</td></tr>
										<?php
										}
									?>
									 
										
									</tbody>
								</table>
							</div> 
						</div> 
					</div>
					<div class="clearfix"></div> 
				</div>
			</form>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>