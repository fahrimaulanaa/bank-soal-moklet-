<div class="col-md-12">
    <div class="panel panel-danger">
        <div class="panel-heading">Upload Suara</div>
        <div class="panel-body">
			<div class="alert alert-info">
				<strong>Upload Suara Soal !</strong><br>
				<?php 
					if(isset($error) && !empty($error)){
						echo $error;
					} else {
						echo 'Silahkan pilih file suara Anda...';
					}
				?>
			</div>

			<?php echo form_open_multipart($form_action);?>
				<p>
					<?php echo form_label('Pilih File Suara', 'userfile'); ?>
					<br>
					<br>
					<input type="file" name="userfile" size="50" class="form_field form-control"/>
				</p>
				<br>
				<p>
					<input type="submit" value="Upload" class="btn btn-primary" />
					<?php echo anchor('entry','Batal', array('class' => 'btn btn-danger')) ?>
				</p>

			<?php echo form_close(); ?>
		</div>
	</div>
</div>
