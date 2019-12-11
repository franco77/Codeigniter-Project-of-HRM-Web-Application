<script>
 var site_url = '<?php echo base_url(); ?>';
$(function()
{ 
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
})


function UpdateReference(dis){
	var getURLIdVal = $('#getURLIdVal').val();
	var txtref_name_1 = $('#txtref_name_1').val();
	var txtcomp_name_1 = $('#txtcomp_name_1').val();
	var txtdesignation_1 = $('#txtdesignation_1').val();
	var txtcont_no_1 = $('#txtcont_no_1').val();
	var txtref_id_1 = $('#txtref_id_1').val();
	var txtref_name_2 = $('#txtref_name_2').val();
	var txtcomp_name_2 = $('#txtcomp_name_2').val();
	var txtdesignation_2 = $('#txtdesignation_2').val();
	var txtcont_no_2 = $('#txtcont_no_2').val();
	var txtref_id_2 = $('#txtref_id_2').val();
	var txtref_name_3 = $('#txtref_name_3').val();
	var txtcomp_name_3 = $('#txtcomp_name_3').val();
	var txtdesignation_3 = $('#txtdesignation_3').val();
	var txtcont_no_3 = $('#txtcont_no_3').val();
	var txtref_id_3 = $('#txtref_id_3').val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	$.ajax({
		type: "POST",
		url: site_url+'my_account/reference_update_single_emp'+getURLIdVal,
		data: {txtref_name_1 : txtref_name_1, txtcomp_name_1 : txtcomp_name_1, txtdesignation_1 : txtdesignation_1, txtcont_no_1 : txtcont_no_1, txtref_name_2 : txtref_name_2, txtcomp_name_2 : txtcomp_name_2, txtdesignation_2 : txtdesignation_2, txtcont_no_2 : txtcont_no_2, 
		txtref_name_3 : txtref_name_3, txtcomp_name_3 : txtcomp_name_3, txtdesignation_3 : txtdesignation_3, txtcont_no_3 : txtcont_no_3, txtref_id_1 : txtref_id_1, txtref_id_2 : txtref_id_2, txtref_id_3 : txtref_id_3 },
		success: function(data)
		{
			if(data == 1){
				$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
				setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 5000);
			}
			else{
				
			}
	   }
	});
}
 
</script>