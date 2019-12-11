<script type="text/javascript">
var $k=jQuery.noConflict();
$k(function()
{
     
        $k("#successMessage").html("Information has been mailed successfully.").slideDown().delay(4000).slideUp();
   
	$k('#frmSalaryUpdate').validate({
		errorPlacement: function(error, element) {
			error.appendTo( element.attr('id'));
		}
	});           
       $k('#4thweek').datepicker({
                dateFormat: "dd-mm-yy"
	});
        
        $k('#8thweek').datepicker({
                dateFormat: "dd-mm-yy"
	});
        $k('#12thweek').datepicker({
                dateFormat: "dd-mm-yy"
	});
        $k("#sr1").hide();
        $k("#sr2").hide();
        $k("#sr3").hide(); 
        $k("#employee_id").change(function()
	{
            var emp_id = $k(this).val();
            alert(emp_id);
            window.location.href = "<?php echo base_url();?>?employee_id="+emp_id;
            
        }); 
        $k("#probation_type").change(function()
	{
		var calType = $k(this).val();
		if(calType == "1"){			                     
                        $k("#sr1").hide();
                        $k("#sr2").hide();
                        $k("#sr3").hide();  
                        $k("#jr1").show();   
                        $k("#jr2").show();
                        $k("#jr3").show();
                        $k("#jr4").show();
//                        $k("#problem_solving_4thweek").removeClass("number required");
//                        $k("#problem_solving_8thweek").removeClass("number required");
//                        $k("#problem_solving_12thweek").removeClass("number required");
//                        $k("#motivation_employees_4thweek").removeClass("number required");
//                        $k("#motivation_employees_8thweek").removeClass("number required");
//                        $k("#motivation_employees_12thweek").removeClass("number required");
//                        $k("#responsibility_4thweek").removeClass("number required");
//                        $k("#responsibility_8thweek").removeClass("number required");
//                        $k("#responsibility_12thweek").removeClass("number required");
//                        
//                        $k("#capacity_develop_4thweek").addClass("number required");
//                        $k("#capacity_develop_8thweek").addClass("number required");
//                        $k("#capacity_develop_12thweek").addClass("number required");
//                        $k("#attendance_reliability_4thweek").addClass("number required");
//                        $k("#attendance_reliability_8thweek").addClass("number required");
//                        $k("#attendance_reliability_12thweek").addClass("number required");
//                        $k("#relations_supervisor_4thweek").addClass("number required");
//                        $k("#relations_supervisor_8thweek").addClass("number required");
//                        $k("#relations_supervisor_12thweek").addClass("number required");
//                        $k("#quantity_work_4thweek").addClass("number required");
//                        $k("#quantity_work_8thweek").addClass("number required");
//                        $k("#quantity_work_12thweek").addClass("number required");
                        
		}else if(calType == "2"){
			$k("#sr1").show();
                        $k("#sr2").show();
                        $k("#sr3").show();  
                        $k("#jr1").hide();   
                        $k("#jr2").hide();
                        $k("#jr3").hide();
                        $k("#jr4").hide();
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
