<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('aabsys_classified/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">POST YOUR REQUIRMENT ON POLOSOFT CLASSIFIED</legend>  
					<div class="row well">
						<div class="panel-group" id="accordion">
							<div class="panel panel-default"> 
								<div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body">
										<form id="classifiedForm" name="classifiedForm" action="<?php echo base_url('aabsys_classified/submit_my_classified'); ?>" method="POST" class="form-horizontal" enctype="multipart/form-data" > 
											<fieldset> 
											<div class="form-group">
												<label class="col-md-2 control-label" for="name">Classified Header :</label>  
												<div class="col-md-4">
													<input type="text" id="classifiedHeader" name="classifiedHeader" class="form-control input-md" required=""> 
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label" for="name">Classified Description :</label>  
												<div class="col-md-6">
													<textarea id="classifiedDesc" name="classifiedDesc" class="form-control" rows="5"  required=""></textarea> 
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label" for="name">Image :</label>  
												<div class="col-md-6">
													<input type="file" id="txtClassifiedFile" name="txtClassifiedFile" class="form-control input-md">  
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label" for="signup"></label>
												<div class="col-md-6"> 
													<input type="submit" id="btnClassifiedPost" name="btnClassifiedPost" class="btn btn-info pull-right" value="POST" />
												</div>
											</div>
											</fieldset>
										</form> 
									</div>
								</div>
							</div> 
						</div> 
					</div> 
					<legend class="pkheader">My CLassified View</legend>  
					<div class="row well">
						<div class="content-data"> 
							<div class="white_box">
							<?php //print_r($myClassifiedRes);
								$classifiedNum = count($myClassifiedRes);
								if($classifiedNum > 0)
								{
								  for($i=0; $i < $classifiedNum; $i++)
								  {?>
							<div class="news_box">
							<div class="news_box_left">
							  <div class="classified_img" style="margin-left: 6px;">
								<?php 
									if($myClassifiedRes[$i]->classified_file != '')
									{?>
										<img class="img-responsive hof-emp-img" alt="" src="<?php echo base_url('assets/upload/classified/'. $myClassifiedRes[$i]->classified_file);?>" alt="" align="left" style="width: 65px; height: 65px;" onerror="this.src='<?php echo base_url('assets/images/no-image.jpg');?>';"> 
										 
									<?php }
									else
									{?>
										<img src="<?= base_url('assets/images/no-image.jpg')?>" alt="" align="left" style="width: 65px; height: 65px;" />
									<?php }
								?>
							  </div>
							</div>
							<div class="news_box_right">
							<div class="hdr">
							 <a onclick="showClassifiedDetails(<?php echo $myClassifiedRes[$i]->ix_classified;?>);"><?php echo $myClassifiedRes[$i]->classified_header;?></a>
							 <div class="classified_contact">
								Name : <strong><?php echo $myClassifiedRes[$i]->name_first.' '.$myClassifiedRes[$i]->name_last;?></strong><br />
												Contact No : <strong><?php echo $myClassifiedRes[$i]->mobile;?></strong>
												<br />Posted Date : <strong><?php echo date('g:ia \o\n l jS F Y',strtotime($myClassifiedRes[$i]->date));?></strong>
							  </div>
							</div>
							</div>
							<div class="clear"></div>
							</div>
							<?php }
							}else{?>
							<div class="news_box">
							<div class="pad_10">No Classified Posted.</div>
							</div>
							<?php } ?>
							</div>
						</div>
					</div> 
					
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div>
	<div class="modal fade" id="classifiedDetails" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">AABSyS Classified Details</h4>
              </div>
			  <div class="modal-body1" id="classified_show">
			  
			  </div>
			  <div class="modal-footer">
			   <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			  </div>
            </div>
        </div>
    </div>
</div>


<script>
var site_url = '<?php echo base_url(); ?>';
$(document).on('click','#modal',function(){
		$('#greetBTN').show();
		$('.greetSec').show();
		$("#greetFORM")[0].reset();
		$('#messageSuccess').html('');
})

function showClassifiedDetails(ix_classified){
	 $('#classifiedDetails').modal('show');
	 var str = "";
	 $.ajax({
		 type:'POST',
		 url: site_url+'aabsys_classified/fetch_classified_details',
		 data:{ix_classified:ix_classified},
		 success:function(data){
			 
			 obj = JSON.parse(data);
			str =  '<div class="media">'+
						'<div class="media-left">'+
							'<img class="media-object" src="'+site_url+'assets/upload/classified/'+obj['classified_file']+'" alt="" style="width: 150px; height: 150px;" onerror="this.src=\''+site_url+'assets/images/no-image.jpg\'"/>'+
						'</div>'+
						'<div class="media-body">'+
							'<a  ><h4 class="media-heading">'+obj['classified_header']+'</h4></a>'+
							'<h4 class="media-heading">'+obj['classified_body']+'</h4>'+
							'<h6>Name: '+obj['name_first']+'</h6>'+
							'<h6>Contact No: '+obj['mobile'] +'</h6>'+
						'</div>'+
					'</div> ';
			$('#classified_show').html(str);
		 }
	 });
}
</script>