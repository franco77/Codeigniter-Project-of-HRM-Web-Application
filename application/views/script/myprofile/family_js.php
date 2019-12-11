<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('#txtfather_dob').datepicker({
         dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            maxDate: '-1D',
			yearRange: "-100:+0"
	});
	
	$('#txtmother_dob').datepicker({
         dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            maxDate: '-1D',
			yearRange: "-100:+0"
	});
	
	$('#txtspouse_dob').datepicker({
         dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            maxDate: '-1D',
			yearRange: "-100:+0"
	});
	
	$('#txtanniversary_date').datepicker({
         dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            maxDate: '-1D',
			yearRange: "-100:+0"
	});
	
	$('#txtchild_dob').datepicker({
         dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            maxDate: '-1D',
			yearRange: "-100:+0"
	});
	
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            maxDate: '-1D',
			yearRange: "-100:+0"
	});
	
	$("#frmFamilyUpdate")[0].reset();
	$("#frmChildAdd")[0].reset();
});


function AddFamily(dis){
	var getURLIdVal = $('#getURLIdVal').val();
	var txtchild_name = $('#txtchild_name').val();
	var ddl_childgender = $('#ddl_childgender').val();
	var txtchild_dob = $('#txtchild_dob').val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(txtchild_name !="" && ddl_childgender !="" && txtchild_dob !=""){
		$.ajax({
			type: "POST",
			url: site_url+'my_account/family_add_single_emp'+getURLIdVal,
			data: {txtchild_name : txtchild_name, ddl_childgender : ddl_childgender, txtchild_dob : txtchild_dob},
			success: function(data)
			{
				if(data == 1){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Added successfully </div></div>');
					setTimeout(function(){ location.reload(); }, 3000);
				}
				else{
					//location.reload();
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please fill all the fields </div></div>');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
				}
		   }
		});
	}
	else{
		$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please fill all the fields </div></div>');
		setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
	}
}


function UpdateFamily(dis,types,child_id){
	var getURLIdVal = $('#getURLIdVal').val();
	var txtchild_name = $('#txtchild_name_'+child_id).val();
	var ddl_childgender = $('#ddl_childgender_'+child_id).val();
	var txtchild_dob = $('#txtchild_dob_'+child_id).val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(txtchild_name !="" && ddl_childgender !="" && txtchild_dob !="" && types == 'update'){
		$.ajax({
			type: "POST",
			url: site_url+'my_account/family_update_single_emp'+getURLIdVal,
			data: {txtchild_name : txtchild_name, ddl_childgender : ddl_childgender, txtchild_dob : txtchild_dob, child_id : child_id, types : types},
			success: function(data)
			{
				if(data == 1){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
				}
				else{
					location.reload();
				}
		   }
		});
	}
	else if(types == 'delete'){
		$.ajax({
			type: "POST",
			url: site_url+'my_account/family_update_single_emp'+getURLIdVal,
			data: {txtchild_name : txtchild_name, ddl_childgender : ddl_childgender, txtchild_dob : txtchild_dob, child_id : child_id, types : types},
			success: function(data)
			{
				if(data == 1){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
				}
				else{
					location.reload();
				}
		   }
		});
	}
	else{
		$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please fill all the fields </div></div>');
	}
}
 
</script>