<script> 
	var $k=jQuery.noConflict();
		$k(function(){
			$k("#btnEmpSearch").live('click', function(){
				searchEmpName();
			});
			$k("#txtEmpSearch").keypress(function(e) { // start enter click
				if (e.which === 13) {
					searchEmpName();
				}
			});
		});

		function searchEmpName()
		{
			var key = $k("#txtEmpSearch").val();
			if(key != '' && key != 'Search Employee'){
				$k("#empName").html('<div style="padding:20px 300px;"><img src="../images/loader.gif" /><div>');
				$k.ajax({
					type: "POST",
					url: base_url+'hr/show_emp_name',
					data: 'keyword='+key,
					success: function(data) {
						$k("#empName").html(data);
						$k("#resetArea").slideDown();
					},
					error: function(e) {
						alert("There is somme error in the network. Please try later.");
					}
				});
			}
		}
</script>