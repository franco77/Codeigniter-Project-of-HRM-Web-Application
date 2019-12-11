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
					<legend class="pkheader">Income Tax</legend>
					<div class="row well">
						<form id="" name="" method="POST" action="" enctype="multipart/form-data" class="form-horizontal">
						<?php
							if($success_msg != '') { ?>
							<div class="col-md-12"><div class="alert alert-success" role="alert"> <?php echo $success_msg; ?> </div>
							<?php } else if($error_msg != '') { ?>
							<div class="col-md-12"><div class="alert alert-danger" role="alert"> <?php echo $error_msg; ?> </div>
						<?php } ?>
						<?php $j=0; ?>
						  <fieldset> 
							<table class="table table-striped table-bordered table-condensed">
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>For the Financial Year</strong>
											</div>
											<div class="col-md-4">
												<select type="text"  id="fyear" name="fyear" class=" form-control"  />
												  <?php $yr=date("Y");
                                                        for ($j = $yr;$j>=2014;$j--){
                                                              if ($j == $yr || ($j.'-'.($j+1)==$fyear)){ ?>
                                                        <option value="<?php echo $j.'-'.($j+1);?>" selected><?php echo $j.'-'.($j+1);?></option>
                                                        <?php } else { ?>
                                                        <option value="<?php echo $j.'-'.($j+1);?>"><?php echo $j.'-'.($j+1);?></option>
                                                       <?php } } ?>
												</select>
											</div>
											<div class="col-md-4">
												<input type="submit" id="btnFind" name="btnFind" value="FIND" class="btn btn-info pull-right"/>
											</div>
										</div>
									</td>
								</tr>
								<?php $j=0; ?>
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>Rent Paid by Employee</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtrentpaid" name="txtrentpaid" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['rentpaid']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_rentpaid'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_rentpaid']?>" target="_blank"><span>View</span></a> <?php }} ?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<h4 class="allheader">Exemption U/s 10</h4> 
							<table class="table table-striped table-bordered table-condensed">
								<tbody>
								<tr>	
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>a) Conveyance Allowance</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtconv_allowance" name="txtconv_allowance" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['conv_allowance']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_conv_allowance'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_conv_allowance']?>" target="_blank"><span>View</span></a><?php } } ?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>b) Children Education Allowance</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtchildreneducationalallowance" name="txtchildreneducationalallowance" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['childreneducationalallowance']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_childreneducationalallowance'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_childreneducationalallowance']?>" target="_blank"><span>View</span></a> <?php } }?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>c) Medical Expenses per month </strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtmedicalexpensesperannum" name="txtmedicalexpensesperannum" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['medicalexpensesperannum']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_medicalexpensesperannum'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_medicalexpensesperannum']?>" target="_blank"><span>View</span></a> <?php } } ?>
											</div>
										</div>
									</td>
								</tr>
								</tbody>
							</table>
							<h4 class="allheader">Deducation U/s 80C Max 1.5 Lakh</h4> 
							<table class="table table-striped table-bordered table-condensed">
								<tbody>
									<tr>
										<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<strong>a) Contribution To Provident Fund</strong>
												</div>
												<div class="col-md-4">
													<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtpensionfund" name="txtpensionfund" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['pensionfund']?>" <?php } ?> class="form-control"  />
												</div>
												<div class="col-md-4">
													<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_pensionfund'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_pensionfund']?>" target="_blank"><span>View</span></a> <?php }  }?>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<strong>b) Life Insurance Premium</strong>
												</div>
												<div class="col-md-4">
													<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtlic" name="txtlic" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['lic']?>" <?php } ?> class="form-control"  />
												</div>
												<div class="col-md-4">
													<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_lic'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_lic']?>" target="_blank"><span>View</span></a> <?php } } ?>
												</div>
											</div>	
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<strong>c) Public Provident Fund(PPF)</strong>
												</div>
												<div class="col-md-4">
													<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtprovidentfund" name="txtprovidentfund" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['providentfund']?>" <?php } ?> class="form-control"  />
												</div>
												<div class="col-md-4">
													<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_providentfund'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_providentfund']?>" target="_blank"><span>View</span></a> <?php } } ?>
												</div>
											</div>	
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<strong>d) National Savings Certification(NSC)</strong>
												</div>
												<div class="col-md-4">
													<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtnsc" name="txtnsc" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['nsc']?>" <?php } ?> class="form-control"  />
												</div>
												<div class="col-md-4">
													<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_nsc'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_nsc']?>" target="_blank"><span>View</span></a> <?php } } ?>
												</div>
											</div>	
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<strong>e) Children Education Fee</strong>
												</div>
												<div class="col-md-4">
													<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtchildreneducationfee" name="txtchildreneducationfee" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['childreneducationfee']?>" <?php } ?> class="form-control"  />
												</div>
												<div class="col-md-4">
													<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_childreneducationfee'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_childreneducationfee']?>" target="_blank"><span>View</span></a> <?php } } ?>
												</div>
											</div>	
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<strong>f) Mutual Funds</strong>
												</div>
												<div class="col-md-4">
													<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtmutualfund" name="txtmutualfund" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['mutualfund']?>" <?php } ?> class="form-control"  />
												</div>
												<div class="col-md-4">
													<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_mutualfund'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_mutualfund']?>" target="_blank"><span>View</span></a> <?php } } ?>
												</div>
											</div>	
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<strong>g) Nischint</strong>
												</div>
												<div class="col-md-4">
													<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtnischint" name="txtnischint" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['nischint']?>" <?php } ?> class="form-control"  />
												</div>
												<div class="col-md-4">
													<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_nischint'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_nischint']?>" target="_blank"><span>View</span></a> <?php  } } ?>
												</div>
											</div>	
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<strong>h) ULIP</strong>
												</div>
												<div class="col-md-4">
													<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtulip" name="txtulip" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['ulip']?>" <?php } ?> class="form-control"  />
												</div>
												<div class="col-md-4">
													<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_ulip'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_ulip']?>" target="_blank"><span>View</span></a> <?php } } ?>
												</div>
											</div>	
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<strong>i) Deposit in Post Office Tax Saving Scheme</strong>
												</div>
												<div class="col-md-4">
													<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtpostofficetax" name="txtpostofficetax" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['postofficetax']?>" <?php } ?> class="form-control"  />
												</div>
												<div class="col-md-4">
													<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_postofficetax'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_postofficetax']?>" target="_blank"><span>View</span></a> <?php } } ?>
												</div>
											</div>	
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<strong>j) Equity Linked Savings Scheme(ELSS)</strong>
												</div>
												<div class="col-md-4">
													<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtelss" name="txtelss" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['elss']?>" <?php } ?> class="form-control"  />
												</div>
												<div class="col-md-4">
													<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_elss'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_elss']?>" target="_blank"><span>View</span></a> <?php } } ?>
												</div>
											</div>	
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<strong>k) Housing Loan Principal Amount Repayment</strong>
												</div>
												<div class="col-md-4">
													<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txthousingloanprincipal" name="txthousingloanprincipal" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['housingloanprincipal']?>" <?php } ?> class="form-control"  />
												</div>
												<div class="col-md-4">
													<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_housingloanprincipal'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_housingloanprincipal']?>" target="_blank"><span>View</span></a> <?php } } ?>
												</div>
											</div>	
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<strong>e) Fixed Deposit with Scheduled Bank for a period of 5 years or more</strong>
												</div>
												<div class="col-md-4">
													<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtfixeddeposit" name="txtfixeddeposit" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['fixeddeposit']?>" <?php } ?> class="form-control"  />
												</div>
												<div class="col-md-4">
													<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_fixeddeposit'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_fixeddeposit']?>" target="_blank"><span>View</span></a> <?php } } ?>
												</div>
											</div>	
										</td>
									</tr>
								</tbody>
							</table>
							<h4 class="allheader">Deducation U/s 80D(Max 15000 - Self's Family)</h4>
							<table class="table table-striped table-bordered table-condensed">
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong></strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtselfsfamily" name="txtselfsfamily" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['selfsfamily']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_selfsfamily'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_selfsfamily']?>" target="_blank"><span>View</span></a> <?php } } ?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<h4 class="allheader">Deducation U/s 80D(Max 15000 - Parents)</h4>
							<table class="table table-striped table-bordered table-condensed">
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong></strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtparents" name="txtparents" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['parents']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_parents'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_parents']?>" target="_blank"><span>View</span></a> <?php } } ?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<h4 class="allheader">Deducation U/s 80E & U/s 24</h4>
							<table class="table table-striped table-bordered table-condensed">
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>a) Interest on Loan for Higher Education - U/s80E</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txthighereducation" name="txthighereducation" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['highereducation']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_highereducation'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_highereducation']?>" target="_blank"><span>View</span></a> <?php } } ?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>b) Interest on Housing Loan (Max 2 Lakhs) - U/s24</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txthousingloaninterest" name="txthousingloaninterest" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['housingloaninterest']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_housingloaninterest'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_housingloaninterest']?>" target="_blank"><span>View</span></a> <?php } } ?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<h4 class="allheader">Deducation U/s 80DD(only Dependants)</h4>
							<table class="table table-striped table-bordered table-condensed">
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>a) Medical Treatment on Dependant (Normal Disability - Max 50K)</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtddnormaldisability" name="txtddnormaldisability" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['ddnormaldisability']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_ddnormaldisability'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_ddnormaldisability']?>" target="_blank"><span>View</span></a> <?php }  }?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>b) Medical Treatment on Dependant (Severe Disability - Max 50K)</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtddseveredisability" name="txtddseveredisability" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['ddseveredisability']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_ddseveredisability'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_ddseveredisability']?>" target="_blank"><span>View</span></a> <?php }  }?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<h4 class="allheader">Deducation U/s 80DD(only for Self)</h4>
							<table class="table table-striped table-bordered table-condensed">
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>a) Medical Treatment of Self (Normal Disability - Max 50K)</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtunormaldisability" name="txtunormaldisability" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['unormaldisability']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php  } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_unormaldisability'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_unormaldisability']?>" target="_blank"><span>View</span></a> <?php } } ?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>b) Medical Treatment of Self (Severe Disability - Max 50K)</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtuseveredisability" name="txtuseveredisability" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['useveredisability']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_useveredisability'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_useveredisability']?>" target="_blank"><span>View</span></a> <?php }  }?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<h4 class="allheader">Deducation U/s 80DDB(Self/Dependant for specified diseases)</h4>
							<table class="table table-striped table-bordered table-condensed">
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>Medical Treatment for Self (Max 40K)</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtspecifieddiseases" name="txtspecifieddiseases" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['specifieddiseases']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_specifieddiseases'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_specifieddiseases']?>" target="_blank"><span>View</span></a> <?php } } ?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<h4 class="allheader">Deducation U/s 80TTA(Intrest on Saving account)</h4>
							<table class="table table-striped table-bordered table-condensed">
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>Max. Amount of Rs 10,000/-</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtsavingsaccountinterest" name="txtsavingsaccountinterest"  value="<?php if(COUNT($empInfo) > 0) { echo $empInfo[0]['savingsaccountinterest']; } ?>"  class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_savingsaccountinterest'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_savingsaccountinterest']?>" target="_blank"><span>View</span></a> <?php }  }?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<h4 class="allheader">Deducation U/s 10(5)(Leave Travel Concession)</h4>
							<table class="table table-striped table-bordered table-condensed">
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>Max. Amount of Rs 10,000/-</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtleavetravelconcession" name="txtleavetravelconcession" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['leavetravelconcession']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_leavetravelconcession'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_leavetravelconcession']?>" target="_blank"><span>View</span></a> <?php } } ?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<h4 class="allheader">Deducation U/s 80G(Donations)</h4>
							<table class="table table-striped table-bordered table-condensed">
								<tr>
									<td>
										<div class="form-row"> 
											<div class="col-md-4">
												<strong>Actual Donation Made</strong>
											</div>
											<div class="col-md-4">
												<input type="text" <?php if($access=='user') echo 'readonly="readonly"';?> id="txtdonation" name="txtdonation" <?php if(COUNT($empInfo) > 0) {?> value="<?php echo $empInfo[0]['donation']?>" <?php } ?> class="form-control"  />
											</div>
											<div class="col-md-4">
												<?php if($access=='user'){ ?> <input type="file" name="rdoc_<?php echo $j++; ?>" class="form-control" /><?php } if(COUNT($empInfo) > 0) { if($empInfo[0]['doc_donation'] != ''){ ?><a href="<?php echo base_url();?>assets/upload/incometax/<?php echo $empInfo[0]['doc_donation']?>" target="_blank"><span>View</span></a> <?php } } ?>
											</div>
										</div>
									</td>
								</tr>
							</table>
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