<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	var coursetype = $('#ddl_course').val();
	//changeCourse(coursetype);
});

function changeCourse(coursetype, dis)
{
	var str = "";
	//$(dis).parents('#frmEduUpdateNew').find('#courseType').html(str);
	$.ajax({
		type: "POST",
		url: site_url+'my_account/education_change_course',
		data: {coursetype : coursetype},
		dataType: 'json',
		success: function(response)
		{
			str +='<select id="ddl_coursetype" name="ddl_coursetype" onchange="changeSpecialization(this.value)" class="form-control" style="width:120px;">';
            str +='<option value="">Course Type</option>';
			for(var i =0; i < response.length; i++){
				var course_id = response[i].course_id;
				var course_name = response[i].course_name;
				str +='<option value="'+course_id+'" >'+course_name+'</option>';
			}
			str +='</select>'; 
			$(dis).parents('#frmEduUpdateNew').find('#courseType').html(str);
	   }
	});
}

function changeSpecialization(specialization,dis)
{
	var str = "";
	$(dis).parents('#frmEduUpdateNew').find('#specialization').html(str);
	$.ajax({
		type: "POST",
		url: site_url+'my_account/education_change_specialization',
		data: {specialization : specialization},
		dataType: 'json',
		success: function(response)
		{
			str +='<select id="ddl_specialization" name="ddl_specialization" class="form-control" style="width:170px;">';
            str +='<option value="">Course Type</option>';
			for(var i =0; i < response.length; i++){
				var specialization_id = response[i].specialization_id;
				var specialization_name = response[i].specialization_name;
				str +='<option value="'+specialization_id+'" >'+specialization_name+'</option>';
			}
			str +='</select>'; 
			$(dis).parents('#frmEduUpdateNew').find('#specialization').html(str);
	   }
	});
}

function changeCourseByidUpdate(coursetype, dis, education_id)
{
	var str = "";
	//$(dis).parents('#frmEduUpdateNew').find('#courseType').html(str);
	$.ajax({
		type: "POST",
		url: site_url+'my_account/education_change_course',
		data: {coursetype : coursetype},
		dataType: 'json',
		success: function(response)
		{
			str +='<select id="ddl_coursetype_'+education_id+'" name="ddl_coursetype_'+education_id+'" onchange="changeSpecializationByidUpdate(this.value,this,'+education_id+')" class="form-control" style="width:120px;">';
            str +='<option value="">Course Type</option>';
			for(var i =0; i < response.length; i++){
				var course_id = response[i].course_id;
				var course_name = response[i].course_name;
				str +='<option value="'+course_id+'" >'+course_name+'</option>';
			}
			str +='</select>'; 
			$(dis).parents('#frmEduUpdate1').find('#courseType_'+education_id).html(str);
			$(dis).parents('#frmEduUpdate1').find('#courseSpecialization_'+education_id).html("");
	   }
	});
}

function changeSpecializationByidUpdate(specialization,dis,education_id)
{
	var str = "";
	$(dis).parents('#frmEduUpdate1').find('#courseSpecialization_'+education_id).html("");
	$.ajax({
		type: "POST",
		url: site_url+'my_account/education_change_specialization',
		data: {specialization : specialization},
		dataType: 'json',
		success: function(response)
		{
			str +='<select id="ddl_specialization_'+education_id+'" name="ddl_specialization_'+education_id+'" class="form-control" style="width:170px;">';
            str +='<option value="">Course Type</option>';
			for(var i =0; i < response.length; i++){
				var specialization_id = response[i].specialization_id;
				var specialization_name = response[i].specialization_name;
				str +='<option value="'+specialization_id+'" >'+specialization_name+'</option>';
			}
			str +='</select>'; 
			$(dis).parents('#frmEduUpdate1').find('#courseSpecialization_'+education_id).html(str);
	   }
	});
}

function AddEdu(dis){
	var getURLIdVal = $('#getURLIdVal').val();
	var ddl_coursetype = $('#ddl_coursetype').val();
	var ddl_specialization = $('#ddl_specialization').val();
	var txtpassing_year = $('#txtpassing_year').val();
	var txtpercentage = $('#txtpercentage_'+education_id).val();
	var selBorU = $('#selBorU_'+education_id).val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(ddl_coursetype !="" && txtpassing_year !="" && txtpercentage !="" && selBorU !=""){
		$.ajax({
			type: "POST",
			url: site_url+'my_account/education_update_single_emp'+getURLIdVal,
			data: {ddl_coursetype : ddl_coursetype, ddl_specialization : ddl_specialization, txtpassing_year : txtpassing_year, txtpercentage : txtpercentage, selBorU : selBorU, education_id : education_id, types : types},
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


function UpdateEdu(dis,types,education_id){
	var getURLIdVal = $('#getURLIdVal').val();
	var ddl_coursetype = $('#ddl_coursetype_'+education_id).val();
	var ddl_specialization = $('#ddl_specialization_'+education_id).val();
	var txtpassing_year = $('#txtpassing_year_'+education_id).val();
	var txtpercentage = $('#txtpercentage_'+education_id).val();
	var selBorU = $('#selBorU_'+education_id).val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(ddl_coursetype !="" && txtpassing_year !="" && txtpercentage !="" && selBorU !=""){
		$.ajax({
			type: "POST",
			url: site_url+'my_account/education_update_single_emp'+getURLIdVal,
			data: {ddl_coursetype : ddl_coursetype, ddl_specialization : ddl_specialization, txtpassing_year : txtpassing_year, txtpercentage : txtpercentage, selBorU : selBorU, education_id : education_id, types : types},
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
 
</script>