<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
});


function piSubmit(dis){
	var pi_id = $('#pi_id').val();
	var rangeFrom = $('#txtItemValue1').val();
	var rangeTo = $('#txtItemValue2').val();
	var pi_value = $('#txtItemValue').val();
	var str="";
	if(rangeFrom !="" && rangeTo !="" && pi_value !="" ){
		$.ajax({
			type: "POST",
			url: site_url+'en/hr/performance_incentive_slab_submit',
			dataType: "json",
			data: {pi_id : pi_id, rangeFrom : rangeFrom, rangeTo : rangeTo, pi_value : pi_value},
			success: function(data)
			{
				$('#pi_id').val('');
				$('#txtItemValue1').val('');
				$('#txtItemValue2').val('');
				$('#txtItemValue').val('');
				if(data > 0){
					$('#piMSG').html('<h4>Data updated successfully</h4>');
					setTimeout(function(){ window.location.href = site_url+'en/hr/performance_incentive_slab'; }, 3000);
				}
				else{
					$('#piMSG').html('<h4>Data inserted successfully</h4>');
					setTimeout(function(){ window.location.href = site_url+'en/hr/performance_incentive_slab'; }, 3000);
				}
		   }
		});
	}
}

</script>