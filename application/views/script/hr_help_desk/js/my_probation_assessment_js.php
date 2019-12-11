<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
});

function openNewWindow(mid,login_id){
	window.open(site_url+'hr_help_desk/probation_assessment_print?id='+login_id+'&mid='+mid, '_blank', 'location=yes,width=980,height=600,left=0,top=0,scrollbars=1');
}

</script>