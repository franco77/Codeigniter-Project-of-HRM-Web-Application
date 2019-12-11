<script type="text/javascript">
var currentTime = new Date();
// First Date Of the month 
var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth(),1);
// Last Date Of the Month 
var startDateTo = new Date(currentTime.getFullYear(),currentTime.getMonth() +1,0);
var xx="";
$(function(){
	$('#dp1').change(function(){
		xx=$('#dp1').val();
	});
	
	
	window.prettyPrint && prettyPrint();
	$('#dp1').datepicker({		
		maxDate:currentTime,
		format: 'mm/dd/yyyy',
		todayBtn: 'linked',
		onSelect: function(){
        $('#dp2').datepicker('option', 'minDate', $("#dp1").datepicker("getDate"));
		}
	});
	
	$('#dp2').datepicker({
		minDate:$("#dp1").datepicker("getDate"),
		maxDate:currentTime,
		format: 'mm/dd/yyyy',
		todayBtn: 'linked'
	});
	$('#btn2').click(function(e){
		e.stopPropagation();
		$('#dp2').datepicker('update', '03/17/12');
	});             
	
	$('#dp3').datepicker();
	
	
	var startDate = new Date(2012,1,20);
	var endDate = new Date(2012,1,25);
	$('#dp4').datepicker()
		.on('changeDate', function(ev){
			if (ev.date.valueOf() > endDate.valueOf()){
				$('#alert').show().find('strong').text('The start date can not be greater then the end date');
			} else {
				$('#alert').hide();
				startDate = new Date(ev.date);
				$('#startDate').text($('#dp4').data('date'));
			}
			$('#dp4').datepicker('hide');
		});
	$('#dp5').datepicker()
		.on('changeDate', function(ev){
			if (ev.date.valueOf() < startDate.valueOf()){
				$('#alert').show().find('strong').text('The end date can not be less then the start date');
			} else {
				$('#alert').hide();
				endDate = new Date(ev.date);
				$('#endDate').text($('#dp5').data('date'));
			}
			$('#dp5').datepicker('hide');
		});
		
	//inline    
	$('#dp6').datepicker({
		todayBtn: 'linked'
	});
		
	$('#btn6').click(function(){
		$('#dp6').datepicker('update', '15-05-1984');
	});            
	
	$('#btn7').click(function(){
		$('#dp6').data('datepicker').date = null;
		$('#dp6').find('.active').removeClass('active');                
	});            
});
 </script>