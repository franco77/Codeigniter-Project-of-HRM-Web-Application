<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
<div id="loaderSection" class="loader-section" style='display:block;'>
	<div>&nbsp;</div>
	<div>&nbsp;<img src="<?php echo base_url(); ?>assets/images/loader.gif" alt="Processing..." /></div>
	<div>&nbsp;</div>
</div>
<style>
#loaderSection.loader-section {
    position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
    background: #fff;
	opacity: 0.7;
}
#loaderSection.loader-section img{
	position: absolute;
	left: 50%;
    top: 35%;
    width: 100px;
	height: auto;
	margin: 0 auto;
}
</style>
<?php
	foreach ($scripts['foot'] as $file)
	{
		$url = starts_with($file, 'http') ? $file : base_url($file);
		echo "<script src='$url'></script>".PHP_EOL;
	}
?>
<?php // Google Analytics ?>
<?php $this->load->view('_partials/ga') ?>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
	
	$('#txt_email').keyup(function(){
        $(this).val($(this).val().toUpperCase());
    });
	
	$('#loaderSection').hide();
});

</script>

</body>
</html>
