<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h4 class="section-title">
                    <span class="c-dark">Resources</span>
                </h4>
            </div>
        </div>
    </div>
</div>
<div class="section main-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-content page-content">
                    <div class="mt mb">
                        <div class="col-md-3 center-xs">
							<div class="form-content page-content">
								<?php $this->load->view('resources/left_sidebar');?>
							</div>
                        </div>
                        <div class="col-md-9">
							<div class="form-content page-content">
								<div class="box">
									<form name="mainform" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
										<div class="box-header">
											<h3 class="box-title">Official Holidays</h3> 
											<div class="box-tools">
												<div class="input-group input-group-sm" style="width: 120px;">
													<select id="choose_year" name="choose_year" class="form-control" onChange="javascript:document.forms[0].submit();">
													<?php 
														$cYear = date("Y"); 
														for($y = 2009; $y <= $cYear; $y++){
															$rg_year[$y] = $y;
														}
														foreach($rg_year as $key => $val){
															$checkselect = ($choose_year == $key) ? 'selected' : '';
															echo('<option value="'.$key.'" '.$checkselect.'>'.$val.'</option>');
														}
													?>  
													</select> 
												</div>
											</div>
										</div>
										<!-- /.box-header -->
										<div class="box-body table-responsive no-padding">
											<table class="table table-bordered table-hover dataTable">
												<tr>  
													<th>Event Name</th>
													<th>Event Date</th>
													<th>Branch</th> 
												</tr>
												<?php 
													$no_of_leave = count($leave_list);
													for ($i = 0; $i < $no_of_leave; $i++){?>
													<tr>
														<td><?php echo $leave_list[$i]['s_event_name'] ?></td>
														<td><?php echo date("jS F, Y", strtotime($leave_list[$i]['dt_event_date']));?></td>
														<td><?php  if($leave_list[$i]['branch']==0)echo "Normal"; else echo $leave_list[$i]['branch_name'] ?></td> 
													</tr>
												<?php }?>
											</table>
										</div>
									<form>
								</div> 
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