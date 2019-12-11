<script>
$(function()
{ 
	$('#txtdob').datepicker({
         dateFormat: 'dd-mm-yy'
	}).datepicker("setDate", "0");
	$('#txtdoj').datepicker({
         dateFormat: 'dd-mm-yy'
	}).datepicker("setDate", "0");
	
	$("#ddlTypeEmp").change(function()
	{
		var empType = $(this).val();
		if(empType == "F"){
			$("#confirmText").text("Probation Period");
			$("#div_contractEndDate").hide();
			$("#div_dueConf").show();
		}else if(empType == "C"){
			$("#confirmText").text("Contract Peroid");
			$("#div_contractEndDate").show();
			$("#div_dueConf").hide();
		}else if(empType == "I"){
			$("#confirmText").text("Contract Peroid");
			$("#div_contractEndDate").show();
			$("#div_dueConf").hide();
		} else if(empType == "CO"){
			$("#confirmText").text("");
			$("#div_contractEndDate").hide();
			$("#div_dueConf").hide();
		} 
	});
	
	$("#ddlSrcHire").change(function()
	{
		var srcHire = $(this).val();
		if(srcHire == "Employee Reference"){
			$("#div_srcHireEmployee").show();
		}else {
			$("#div_srcHireEmployee").hide();
		}
	});
	
	$("#emp_joining_type").change(function()
	{
		var srcHire = $(this).val();
		if(srcHire == "XE"){
			$("#div_exEmployeeCode").show();
		}else {
			$("#div_exEmployeeCode").hide();
		}
	});
})
function showFullName()
{
	var fName = $('#txtFirstName').val();
	var mName = $('#txtMiddleName').val();
	var lName = $('#txtLastName').val();
	var fullName = '';
	if(fName != ''){
		fullName = fName;
	}
	if(mName != ''){
		fullName += ' '+mName;
	}
	if(lName != ''){
		fullName += ' '+lName;
	}
	$('#txtFullName').val(fullName);
}
</script>