<script type="text/javascript"> 
var site_url = '<?php echo base_url(); ?>';
$(function()
{ 
	$("#txtadvanceamount").hide();
	$("#txtadvanceinstalment").hide();	
	$("#txtapplyfor").change(function()
	{
		var calType = $(this).val();
		if(calType == "loan")
		{
			$("#txtadvanceamount").hide();
			$("#txtloanamount").show();
			$("#txtadvanceinstalment").hide();
			$("#txtloaninstalment").show();
			$("#txtloanamount").addClass("required");
			$("#txtloaninstalment").addClass("required");
			$("#txtadvanceinstalment").removeClass("required");
			$("#txtadvanceamount").removeClass("required");
			<?php if($noofyears < 1){ ?>
					alert("You are not Eligible for apply Loan");
			<?php } ?>            
		}
		else if(calType == "advance")
		{
			$("#txtloanamount").hide();
			$("#txtadvanceamount").show();	
			$("#txtadvanceinstalment").show();
			$("#txtloaninstalment").hide();
			$("#txtadvanceamount").addClass("required");
			$("#txtadvanceinstalment").addClass("required");
			$("#txtloanamount").removeClass("required");
			$("#txtloaninstalment").removeClass("required");
		}	
	}); 

	
}); 
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("error").style.display = ret ? "none" : "inline";
	return ret;
}

function applyLoanAdvance(dis){
	var txtapplyfor = $('#txtapplyfor').val();
	var txtadvanceamount = $('#txtadvanceamount').val();
	var txtloanamount = $('#txtloanamount').val();
	var txtamountapplied = $('#txtamountapplied').val();
	var txtadvanceinstalment = $('#txtadvanceinstalment').val();
	$('.err-oldmsg').html("");
	$('#txtapplyfor').removeAttr('style');
	$('#txtadvanceamount').removeAttr('style');
	$('#txtloanamount').removeAttr('style');
	$('#txtamountapplied').removeAttr('style');
	$('#txtadvanceinstalment').removeAttr('style');
	$('.resetSec').show();
	if(txtapplyfor == ""){
		$('#txtapplyfor').attr('style', 'border-color: #f00000;');
	}
	if(txtadvanceamount == ""){
		$('#txtadvanceamount').attr('style', 'border-color: #f00000;');
	}
	if(txtloanamount == ""){
		$('#txtloanamount').attr('style', 'border-color: #f00000;');
	}
	if(txtamountapplied == ""){
		$('#txtamountapplied').attr('style', 'border-color: #f00000;');
	}
	if(txtadvanceinstalment == ""){
		$('#txtadvanceinstalment').attr('style', 'border-color: #f00000;');
	}
	if(newPassword != cnewPassword){
		$('#cnewPassword').attr('style', 'border-color: #f00000;');
		$('#oldPassword').attr('style', 'border-color: #f00000;');
		$('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> New Password & Confirm New Password are Not Matched</div></div>');
	}
	else if(oldPassword !="" && newPassword !=""){
		$.ajax({
			type: "POST",
			url: site_url+'home/reset_password',
			data: {oldPassword : oldPassword, newPassword : newPassword}, // serializes the form's elements.
			success: function(data)
			{
				if(data == 1){
					$('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Invalid Old Password</div></div>');
					$('#oldPassword').attr('style', 'border-color: #f00000;');
				}
				else{
					$('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Password changed successfully </div></div>');
					$('#oldPassword').val("");
					$('#newPassword').val("");
					$('#cnewPassword').val("");
					$('.resetSec').hide();
				}
		   }
		});
	}
}
</script>
