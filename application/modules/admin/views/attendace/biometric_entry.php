<?php echo $form->messages(); ?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h4>Select Daily/Monthly Sheet</h4>
			</div>
			<div class="box-body">
				<?php echo $form->open(); ?>
					<div class="form-inline">
					  <div class="form-group">
						<input type="file" name="files[]" id="js-upload-files" multiple>
					  </div>
					  <button type="submit" class="btn btn-sm btn-primary" id="js-upload-submit">Upload files</button>
					</div>
				<?php echo $form->close(); ?>
			</div> 
		</div>
	</div>
	
</div>