<script>
 var site_url = '<?php echo base_url(); ?>';
function AddExp(dis,types,exp_id){
	var getURLIdVal = $('#getURLIdVal').val();
	var comp_name = $('#txtcomp_name').val();
	var designation = $('#txtdesignation').val();
	var experince = $('#txtexperince').val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(comp_name !="" && designation !="" && experince !=""){
		$.ajax({
			type: "POST",
			url: site_url+'my_account/exp_add_single_emp'+getURLIdVal,
			data: {txtcomp_name : comp_name, txtdesignation : designation, txtexperince : experince},
			success: function(data)
			{
				if(data == 1){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
					setTimeout(function(){ location.reload(); }, 3000);
				}
				else{
					//location.reload();
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please fill all the fields </div></div>');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
				}
		   }
		});
	}
	else{
		$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please fill all the fields </div></div>');
		setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
	}
}

function UpdateExp(dis,types,exp_id){
	var getURLIdVal = $('#getURLIdVal').val();
	var comp_name = $('#txtcomp_name_'+exp_id).val();
	var designation = $('#txtdesignation_'+exp_id).val();
	var experince = $('#txtexperince_'+exp_id).val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(comp_name !="" && designation !="" && experince !=""){
		$.ajax({
			type: "POST",
			url: site_url+'my_account/exp_update_single_emp'+getURLIdVal,
			data: {comp_name : comp_name, designation : designation, experince : experince, exp_id : exp_id, types : types},
			success: function(data)
			{
				if(data == 1){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
				}
				else{
					location.reload();
				}
		   }
		});
	}
	else{
		$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please fill all the fields </div></div>');

	}
}

	 
	function save_records()
	{
		var skill = $('#txtskill_name').val();
		var experience = $('#txtexp').val();
		$('#error_msg').append("");
		if(skill != "" && experience != "")
		{
			$.ajax({
				type: "POST",
				url: site_url+'my_account/exp_save_skills',
				data: {skill: skill, experience: experience, type: "insert"},
				success: function(data){
					console.log(data);
					$('#error_msg').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
					setTimeout(function(){ location.reload() }, 300)
				}
			});
		}
		else{
			$('#error_msg').append('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please fill all the fields </div></div>');
		}
		
		setTimeout(function(){ $('#error_msg').html(""); }, 3000);
	}	
	
	function update_records(dis, id, no)
	{
		var skill = $('#txtskill_name'+no).val();
		var experience = $('#txtexp'+no).val();
		if(skill != "" && experience != "")
		{
			$.ajax({
				type: "POST",
				url: site_url+'my_account/exp_save_skills',
				data: {skill: skill, experience: experience, type: "update", id: id},
				success: function(data){
					console.log(data);
					$('#error_msg'+no).html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
					setTimeout(function(){ location.reload() }, 300)
				}
			});
		}
		else{
			$('#error_msg'+no).append('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please fill all the fields </div></div>');
		}
		
		setTimeout(function(){ $('#error_msg'+no).html(""); }, 3000);
	}
	
	function delete_records(dis, id, no)
	{
		
		if(id != "")
		{
			$.ajax({
				type: "POST",
				url: site_url+'my_account/exp_save_skills',
				data: {skill: '', experience: '', type: "delete", id: id},
				success: function(data){
					console.log(data);
					$('#error_msg'+no).html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Deleted successfully </div></div>');
					setTimeout(function(){ location.reload() }, 300)
				}
			});
		}
		
		setTimeout(function(){ $('#error_msg'+no).html(""); }, 3000);
	}
	 
</script>