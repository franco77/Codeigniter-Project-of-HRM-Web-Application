<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
});

function getDesgnation(dis){
	var getURLIdVal = $('#getURLIdVal').val();
	var department = $('#department').val();
	var str="";
	$.ajax({
		type: "POST",
		url: site_url+'my_account/get_designation'+getURLIdVal,
		data: {department : department},
		success: function(response)
		{
			response = JSON.parse(response);
			str += '<option value="">Select</option>';
			for(var i=0; i< response.length; i++){
				str += '<option value="'+response[i].desg_id+'">'+response[i].desg_name+'</option>';
			}
			$('#designation').html(str);
	   }
	});
	
}


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
	var txtmediclaim_no = $('#txtmediclaim_no').val();
	var selBank = $('#selBank').val();
	var txtbank_no = $('#txtbank_no').val();
	var payment_mode = $('#payment_mode').val();
	var txtbank_ifsc_code = $('#txtbank_ifsc_code').val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(txtgross_salary !="" && txtbasic !="" && txthra !="" && txtconveyance_allowance !="" && txtpf_no !="" && txtmediclaim_no !=""){
		$.ajax({
			type: "POST",
			url: site_url+'my_account/salary_profile_update_emp_submit'+getURLIdVal,
			data: {calculation_type : calculation_type, txtgross_salary : txtgross_salary, txtfixed_basic : txtfixed_basic, txtbasic : txtbasic, txthra : txthra, txtconveyance_allowance : txtconveyance_allowance, txtpf_no : txtpf_no, txtmediclaim_no : txtmediclaim_no, selBank : selBank, txtbank_no : txtbank_no, payment_mode : payment_mode, txtbank_ifsc_code : txtbank_ifsc_code, reimbursement : reimbursement},
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