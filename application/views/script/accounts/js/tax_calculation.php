<script type="text/javascript">

	var staticVarWithValue=[];
	//var income_tax_declaration2_limit = $('#income_tax_declaration2_limit').val();
	//staticVarWithValue.push({Name:'income_tax_declaration2',Amount:income_tax_declaration2_limit });
	var income_tax_declaration4_limit = $('#income_tax_declaration4_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration4',Amount:income_tax_declaration4_limit});
	var income_tax_declaration6_limit = $('#income_tax_declaration6_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration6',Amount:income_tax_declaration6_limit});
	var income_tax_declaration8_limit = $('#income_tax_declaration8_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration8',Amount:income_tax_declaration8_limit})
	var income_tax_declaration10_limit = $('#income_tax_declaration10_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration10',Amount:income_tax_declaration10_limit})
	var income_tax_declaration12_limit = $('#income_tax_declaration12_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration8',Amount:income_tax_declaration12_limit})
	var income_tax_declaration14_limit = $('#income_tax_declaration14_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration14',Amount:income_tax_declaration14_limit});
	var income_tax_declaration16_limit = $('#income_tax_declaration16_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration16',Amount:income_tax_declaration16_limit});
	var income_tax_declaration18_limit = $('#income_tax_declaration18_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration18',Amount:income_tax_declaration18_limit});
	var income_tax_declaration22_limit = $('#income_tax_declaration22_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration22',Amount:income_tax_declaration22_limit});
	var income_tax_declaration24_limit = $('#income_tax_declaration24_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration24',Amount:income_tax_declaration24_limit});
	var income_tax_declaration26_limit = $('#income_tax_declaration26_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration26',Amount:income_tax_declaration26_limit});
	var income_tax_declaration28_limit = $('#income_tax_declaration28_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration28',Amount:income_tax_declaration28_limit});
	var income_tax_declaration32_limit = $('#income_tax_declaration32_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration32',Amount:income_tax_declaration32_limit});
	var income_tax_declaration34_limit = $('#income_tax_declaration34_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration34',Amount:income_tax_declaration34_limit});
	var income_tax_declaration38_limit = $('#income_tax_declaration38_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration38',Amount:income_tax_declaration38_limit});
	var income_tax_declaration40_limit = $('#income_tax_declaration40_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration40',Amount:income_tax_declaration40_limit});
	var income_tax_declaration42_limit = $('#income_tax_declaration42_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration42',Amount:income_tax_declaration42_limit});
	var income_tax_declaration46_limit = $('#income_tax_declaration46_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration46',Amount:income_tax_declaration46_limit});
	var income_tax_declaration48_limit = $('#income_tax_declaration48_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration48',Amount:income_tax_declaration48_limit});
	//var income_tax_declaration50_limit = $('#income_tax_declaration50_limit').val();
	//staticVarWithValue.push({Name:'income_tax_declaration50',Amount:income_tax_declaration50_limit});
	var income_tax_declaration52_limit = $('#income_tax_declaration52_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration52',Amount:income_tax_declaration52_limit});
	var income_tax_declaration54_limit = $('#income_tax_declaration54_limit').val();
	staticVarWithValue.push({Name:'income_tax_declaration54',Amount:income_tax_declaration54_limit});
	console.log(staticVarWithValue);
	var income_tax_declaration20=150000;
	var income_tax_declaration30=55000;
	var income_tax_declaration36=150000;
	var income_tax_declaration44=180000;
	var income_tax_declaration56=150000;
$(document).ready(function ()
{
	
	var globalId="income_tax_declaration";
	var globalIdArray=[];
	for (i = 0; i < 72; i=i+2) { 
		globalIdArray.push({X:globalId+(i+1),Y:globalId+(i+2)})
	} 
	$.each(globalIdArray, function (index, value) { 
		$("#"+value.X).keyup(function(){
			$("#"+value.Y).val(this.value);		  
			var inputvalue=this.value;
			$.each(staticVarWithValue,function(index1,value1){
				if(value.Y==value1.Name)
				{
					//console.log(parseInt(inputvalue) < parseInt(value1.Amount));
					if(inputvalue !=""){
						if(parseInt(inputvalue) < parseInt(value1.Amount)){
							  $("#"+value.Y).val(inputvalue);
						}
						else{
							$("#"+value.Y).val(value1.Amount);
						}
					}
					else{
						$("#"+value.Y).val(this.value);
					}
				}
			});
			
		});
	});
	/* $.each(globalIdArray, function (index, value) { 
		 $("#"+value.X).change(function(){
		 $("#"+value.Y).val(this.value);		  
		  var inputvalue=this.value;
			  $.each(staticVarWithValue,function(index1,value1){
				  if(value.Y==value1.Name)
				  {
					  if(parseInt(inputvalue) < parseInt(value1.Amount))
						  $("#"+value.Y).val(inputvalue);
					  else
						  $("#"+value.Y).val(value1.Amount);
				  }
			  });
			
		 });
	});  */	

});
$(document).on("change", ".qty1", function() {
	var sum = 0;
	$(".qty1").each(function(){
		sum += +$(this).val();
	});
	var totalValue=$(".total1").val(sum);
	//alert(totalValue.val());
	if(totalValue.val() < income_tax_declaration20){
			$(".total1").val(totalValue.val());
			$(".total7").val(totalValue.val());
	}
	else{
		$(".total7").val(income_tax_declaration20);
	}
});
$(document).on("change", ".qty2", function() {
var sum = 0;
$(".qty2").each(function(){
	sum += +$(this).val();
});
var totalValue=$(".total2").val(sum);
//alert(totalValue.val());
if(totalValue.val() < income_tax_declaration30){
		$(".total2").val(totalValue.val());
		if($("#income_tax_declaration27").val() > $("#income_tax_declaration28").val())
		{
			//alert('hi');
			var sumX=parseFloat($("#income_tax_declaration24").val())+parseFloat($("#income_tax_declaration26").val())+parseFloat($("#income_tax_declaration28").val());
			//alert(sumX);
			$(".total8").val(sumX);
		}
		else{
			$(".total8").val(totalValue.val());
		} 
}
else{
	$(".total8").val(income_tax_declaration30);
}
});
$(document).on("change", ".qty3", function() {
	var sum = 0;
	$(".qty3").each(function(){
		sum += +$(this).val();
	});
	var totalValue=$(".total3").val(sum);
	//alert(totalValue.val());
	//if(totalValue.val() < income_tax_declaration36){
			$(".total3").val(totalValue.val());
			var declar32 = ($("#income_tax_declaration32").val() !="" && $("#income_tax_declaration32").val() != 'NA') ?  $("#income_tax_declaration32").val() : 0;
			var declar34 = ($("#income_tax_declaration34").val() !="" && $("#income_tax_declaration34").val() != 'NA') ?  $("#income_tax_declaration34").val() : 0;
			var sumX=parseFloat(declar32)+parseFloat(declar34);
			$(".total9").val(sumX);
	//}
	//else{
	//	$(".total9").val(income_tax_declaration36);
	//}
});
$(document).on("change", ".qty4", function() {
	var sum = 0;
	$(".qty4").each(function(){
		if($(this).val() !='NA'){
			sum += +$(this).val();
		}
	});
	var totalValue=$(".total4").val(sum);
	//alert(totalValue.val());
	if(totalValue.val() < income_tax_declaration44){
			$(".total4").val(totalValue.val());
			$(".total10").val(totalValue.val());
	}
	else{
		$(".total4").val(income_tax_declaration44);
		$(".total10").val(income_tax_declaration44);
	}
});
$(document).on("change", ".qty5", function() {
var sum = 0;
$(".qty5").each(function(){
	sum += +$(this).val();
});
var totalValue=$(".total5").val(sum);
//alert(totalValue.val());
if(totalValue.val() < income_tax_declaration56){
		$(".total5").val(totalValue.val());
		$(".total11").val(totalValue.val());
}
else{
	$(".total11").val(income_tax_declaration56);
}
});
$(document).ready(function () {
//called when key is pressed in textbox
$("#quantity").keypress(function (e) {
 //if the letter is not digit then display error and don't type anything
 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	//display error message
	$("#errmsg").html("Digits Only").show().fadeOut("slow");
		   return false;
}
});
});
jQuery('.numbersOnly').keyup(function () { 
this.value = this.value.replace(/[^0-9\.]/g,'');
});

/* $(document).ready(function() {
  $("#income_tax_declaration1").blur(function() {
	  if ($(this).val() != ''){
			if($(this).val() == 0)
				$("#income_tax_declaration15").removeAttr("disabled");
			else
		  $("#income_tax_declaration15").attr("disabled", "disabled");          
	  }
		   
	  else{
		  $("#income_tax_declaration15").removeAttr("disabled");
	  }
  });

  $("#income_tax_declaration15").blur(function() {
	  if ($(this).val() != ''){
			if($(this).val() == 0)
				$("#income_tax_declaration1").removeAttr("disabled");
			else
		  $("#income_tax_declaration1").attr("disabled", "disabled");          
	  }
		   
	  else{
		  $("#income_tax_declaration1").removeAttr("disabled");
	  }
  });
}); */
$(document).ready(function() {
  $("#income_tax_declaration2").blur(function() {
	  if ($(this).val() != '')
		  $("#income_tax_declaration16").attr("disabled", "disabled");
	  else
		  $("#income_tax_declaration16").removeAttr("disabled");
  });

  $("#income_tax_declaration16").blur(function() {
	  if ($(this).val() != '')
		  $("#income_tax_declaration2").attr("disabled", "disabled");
	  else
		  $("#income_tax_declaration2").removeAttr("disabled");
  });
});
</script>