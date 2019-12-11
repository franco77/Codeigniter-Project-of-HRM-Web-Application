<script>
var site_url = '<?php echo base_url(); ?>';
/* $( "#reportingAC" ).autocomplete({
        source: function(request, response) {
            $.ajax({
            url: site_url+'en/hr/get_all_reporting_manager',
            data: { term: $("#reportingAC").val()},
            dataType: "json",
            type: "POST",
            success: function(data){
               var resp = $.map(data,function(obj){
                    return obj.tag;
               }); 

               response(resp);
            }
        });
    },
    minLength: 2
}); */
//var getValue = site_url+'en/hr/get_all_reporting_manager';
$("#reportingAC").autocomplete({
		data: [ site_url+'en/hr/get_all_reporting_manager'],
		onItemSelect: function(item) {
		    if (item.data.length) {
		    	$("#reporting").val(item.data.join(', '));
		    }
		},
		onNoMatch: function(){
			$("#reportingAC").addClass('error');
			$("#reporting").val("");
		},
		maxItemsToShow: 5
	}); 
</script>