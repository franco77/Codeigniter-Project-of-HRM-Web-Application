<script type="text/javascript"> 
$(function()
{ 
	$("#sr1").hide();
	$("#sr2").hide();
	$("#sr3").hide(); 
	$("#employee_id").change(function()
	{
		if($("#employee_id").val() !=""){
			$('#btnSaveMessage').removeAttr('disabled');
			$('#btnAddMessage').removeAttr('disabled');
			var emp_id = $(this).val();
			//alert(emp_id);
			window.location.href = "<?php echo base_url();?>hr_help_desk/probation_assessment_form?employee_id="+emp_id;
		}
		else{
			$('#btnSaveMessage').attr('disabled', 'disabled');
			$('#btnAddMessage').attr('disabled', 'disabled');
			window.location.href = "<?php echo base_url();?>hr_help_desk/probation_assessment_form";
		}
            
    }); /* 
    $("#employee_id").change(function()
	{ 
		if($("#employee_id").val() !=""){
			$('#btnSaveMessage').removeAttr('disabled');
			$('#btnAddMessage').removeAttr('disabled');
		}
		else{
			$('#btnSaveMessage').attr('disabled', 'disabled');
			$('#btnAddMessage').attr('disabled', 'disabled');
		}
	}); */
    $("#probation_type").change(function()
	{
		var calType = $(this).val();
		if(calType == "1"){			                     
                        $("#sr1").hide();
                        $("#sr2").hide();
                        $("#sr3").hide();  
                        $("#jr1").show();   
                        $("#jr2").show();
                        $("#jr3").show();
                        $("#jr4").show();
//                        $("#problem_solving_4thweek").removeClass("number required");
//                        $("#problem_solving_8thweek").removeClass("number required");
//                        $("#problem_solving_12thweek").removeClass("number required");
//                        $("#motivation_employees_4thweek").removeClass("number required");
//                        $("#motivation_employees_8thweek").removeClass("number required");
//                        $("#motivation_employees_12thweek").removeClass("number required");
//                        $("#responsibility_4thweek").removeClass("number required");
//                        $("#responsibility_8thweek").removeClass("number required");
//                        $("#responsibility_12thweek").removeClass("number required");
//                        
//                        $("#capacity_develop_4thweek").addClass("number required");
//                        $("#capacity_develop_8thweek").addClass("number required");
//                        $("#capacity_develop_12thweek").addClass("number required");
//                        $("#attendance_reliability_4thweek").addClass("number required");
//                        $("#attendance_reliability_8thweek").addClass("number required");
//                        $("#attendance_reliability_12thweek").addClass("number required");
//                        $("#relations_supervisor_4thweek").addClass("number required");
//                        $("#relations_supervisor_8thweek").addClass("number required");
//                        $("#relations_supervisor_12thweek").addClass("number required");
//                        $("#quantity_work_4thweek").addClass("number required");
//                        $("#quantity_work_8thweek").addClass("number required");
//                        $("#quantity_work_12thweek").addClass("number required");
                        
		}else if(calType == "2"){
			$("#sr1").show();
                        $("#sr2").show();
                        $("#sr3").show();  
                        $("#jr1").hide();   
                        $("#jr2").hide();
                        $("#jr3").hide();
                        $("#jr4").hide();
//                        $k("#problem_solving_4thweek").addClass("number required");
//                        $k("#problem_solving_8thweek").addClass("number required");
//                        $k("#problem_solving_12thweek").addClass("number required");
//                        $k("#motivation_employees_4thweek").addClass("number required");
//                        $k("#motivation_employees_8thweek").addClass("number required");
//                        $k("#motivation_employees_12thweek").addClass("number required");
//                        $k("#responsibility_4thweek").addClass("number required");
//                        $k("#responsibility_8thweek").addClass("number required");
//                        $k("#responsibility_12thweek").addClass("number required");
//                        
//                        $k("#capacity_develop_4thweek").removeClass("number required");
//                        $k("#capacity_develop_8thweek").removeClass("number required");
//                        $k("#capacity_develop_12thweek").removeClass("number required");
//                        $k("#attendance_reliability_4thweek").removeClass("number required");
//                        $k("#attendance_reliability_8thweek").removeClass("number required");
//                        $k("#attendance_reliability_12thweek").removeClass("number required");
//                        $k("#relations_supervisor_4thweek").removeClass("number required");
//                        $k("#relations_supervisor_8thweek").removeClass("number required");
//                        $k("#relations_supervisor_12thweek").removeClass("number required");
//                        $k("#quantity_work_4thweek").removeClass("number required");
//                        $k("#quantity_work_8thweek").removeClass("number required");
//                        $k("#quantity_work_12thweek").removeClass("number required");
		}	
	}); 
        
});
</script>
