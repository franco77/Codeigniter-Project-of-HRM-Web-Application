<script type="text/javascript">
 $(document).ready(function(){
	$('#imgprint').click(function(){    
       $('section#page').print(); 
	});  
	$(window).scroll(function(){	  
		var fromTop = $(window).scrollTop();
		//alert(fromTop)	;
		if(fromTop > 200)
			$("#floating").css('top',fromTop-227+'px');		
		else
			$("#floating").css('top','0px');
	});
});
 </script>