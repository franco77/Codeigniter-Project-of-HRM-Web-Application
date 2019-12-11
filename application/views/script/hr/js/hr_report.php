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
	
	$('#docFrom').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
			$("#docTo").datepicker("option","minDate", selected)
        }
	});
	$('#docTo').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
           $("#docFrom").datepicker("option","maxDate", selected)
        }
	});
	
	$('#dopFrom').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
			$("#dopTo").datepicker("option","minDate", selected)
        }
	});
	$('#dopTo').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
           $("#dopFrom").datepicker("option","maxDate", selected)
        }
	});
	
	$('#dobFrom').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
			$("#dobTo").datepicker("option","minDate", selected)
        }
	});
	$('#dobTo').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
           $("#dobFrom").datepicker("option","maxDate", selected)
        }
	});
	
	$('#dorFrom').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
			$("#dorTo").datepicker("option","minDate", selected)
        }
	});
	$('#dorTo').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
           $("#dorFrom").datepicker("option","maxDate", selected)
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