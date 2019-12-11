<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
});

function UpdateEmpSal(dis){
	var getURLIdVal = $('#getURLIdVal').val();
	var calculation_type = $('#calculation_type').val();
	var txtgross_salary = $('#txtgross_salary').val();
	var txtfixed_basic = $('#txtfixed_basic').val();
	var txtbasic = $('#txtbasic').val();
	var txthra = $('#txthra').val();
	var txtconveyance_allowance = $('#txtconveyance_allowance').val();
	var reimbursement = $('#reimbursement').val();
	var txtpf_no = $('#txtpf_no').val();
	var txtuan_no = $('#txtuan_no').val();
	var txtmediclaim_no = $('#txtmediclaim_no').val();
	var selBank = $('#selBank').val();
	var txtbank_no = $('#txtbank_no').val();
	var payment_mode = $('#payment_mode').val();
	var txtbank_ifsc_code = $('#txtbank_ifsc_code').val();
	var acc_holder_name = $('#txtbank_acc_holdername').val();
	
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(txtgross_salary !="" && txtbasic !="" && txthra !="" ){
		$.ajax({
			type: "POST",
			url: site_url+'my_account/salary_profile_update_emp_submit'+getURLIdVal,
			data: {calculation_type : calculation_type, txtgross_salary : txtgross_salary, txtfixed_basic : txtfixed_basic, txtbasic : txtbasic, txthra : txthra, txtconveyance_allowance : txtconveyance_allowance, txtpf_no : txtpf_no, txtuan_no : txtuan_no, txtmediclaim_no : txtmediclaim_no, selBank : selBank, txtbank_no : txtbank_no, payment_mode : payment_mode, txtbank_ifsc_code : txtbank_ifsc_code, reimbursement : reimbursement, acc_holder_name:acc_holder_name},
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

function AddIncExp(dis){
	var getURLIdVal = $('#getURLIdVal').val();
	var txtincreament = $('#txtincreament').val();
	var txtyear = $('#txtyear').val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(txtincreament !="" && txtyear !=""){
		$.ajax({
			type: "POST",
			url: site_url+'my_account/salary_profile_increment_add_emp'+getURLIdVal,
			data: {txtincreament : txtincreament, txtyear : txtyear},
			success: function(data)
			{
				if(data == 1){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Incremented Added successfully </div></div>');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); location.reload(); }, 3000);
				}
				else{
					//location.reload();
				}
		   }
		});
	}
	else{
		$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please fill all mandatory fields </div></div>');
		setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); location.reload(); }, 3000);
	}
}
 

function UpdateInc(dis,types,increament_info_id){
	var getURLIdVal = $('#getURLIdVal').val();
	var txtincreament = $('#txtincreament_'+increament_info_id).val();
	var txtyear = $('#txtyear_'+increament_info_id).val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(txtincreament !="" && txtyear !=""){
		$.ajax({
			type: "POST",
			url: site_url+'my_account/salary_profile_increment_update_emp'+getURLIdVal,
			data: {txtincreament : txtincreament, txtyear : txtyear, types : types, increament_info_id : increament_info_id},
			success: function(data)
			{
				if(data == 1){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Incremented Updated successfully </div></div>');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
				}
				else{
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Incremented Deleted successfully </div></div>');
					setTimeout(function(){ location.reload(); }, 3000);
				}
		   }
		});
	}
	else{
		$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please fill all mandatory fields </div></div>');
		setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); location.reload(); }, 3000);
	}
}
 
</script>