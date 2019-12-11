<script>  
$(function() 
{ 
	$('#chooseall').click(function() {
		$(".chkColumns").each(function() {
			$(this).prop('checked', $('#chooseall').is(':checked'));
			//$(this).attr('checked', $('#chooseall').is(':checked'));
			//$(this).prop('checked',true);
		});
		if ($('#chooseall').is(':checked')) {
			$(".filterItem").show();
		} else {
			$(".filterItem").hide();
		}
	});

	$(".chkColumns").each(function() {
		$(this).click(function() {
			var chkID = $(this).attr("id");
			if ($(this).is(':checked')) {
				$("#" + chkID + "Box").show();
			} else {
				$("#" + chkID + "Box").hide();
			}
		});
	});

	$(".filterItem").hide();

	
});

$(document).ready(function(){
	$('#dojFrom').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
			$("#dojTo").datepicker("option","minDate", selected)
        }
	});
	$('#dojTo').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
           $("#dojFrom").datepicker("option","maxDate", selected)
        }
	});
	
	//initialize the pqSelect widget.
	$(".selDepartment").pqSelect({
		multiplePlaceholder: 'Select Department', 
		maxDisplay: 1,		
		checkbox: true //adds checkbox to options    
	}).pqSelect( 'open' );
	
	$(".selDesignation").pqSelect({
		multiplePlaceholder: 'Select Designation',  
		maxDisplay: 1,		  
		checkbox: true //adds checkbox to options    
	}).pqSelect( 'open' );
	
});


</script>