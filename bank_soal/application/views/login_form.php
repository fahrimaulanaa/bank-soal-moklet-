<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo base_url('asset/images/favicon.jpg');?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('asset/css/reset.css');?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('asset/css/style.css');?>" />
        <link rel="stylesheet" href="<?php echo base_url('asset/css/bootstrap.min.css'); ?>" />
        <title>Login | Bank Soal</title>
    </head>
	<body style="background: #e8e8e8 !important">
		<div class="container">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				
				<?php
					$attributes = array('name' => 'login_form', 'id' => 'login_form');
					echo form_open('login', $attributes);
				?>
					<div class="panel panel-default top150">
						<div class="panel-heading panel-login">
							<span id="title-login">Bank Soal</span>
							<br>
							<span id="title-desc">SMK Telkom Malang</span>
						</div>

						<div class="panel-body">
							<!-- pesan start -->
						    <?php if (! empty($pesan)) : ?>
						        <p class="alert alert-warning">
						            <?php echo $pesan; ?>
						        </p>
						    <?php endif ?>
						    <!-- pesan end -->
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<!-- <label for="username">Username:</label> -->
								<input type="text" placeholder="Username" name="username" class="form_field form-control" value="<?php echo set_value('username');?>" style="width: 100% !important">
							</div>
							<?php echo form_error('username', '<p class="field_error_login">', '</p>');?>
							
							<div class="input-group top15">
								<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
								<!-- <label for="password">Password:</label> -->
								<input type="password" placeholder="Password" name="password" class="form_field form-control" value="<?php echo set_value('password');?>" style="width: 100% !important">
							</div>
							<?php echo form_error('password', '<p class="field_error_login">', '</p>');?>
						</div>
						<div class="footer" style="background: #f5f4f3;">
							<div class="panel-heading">
								<input type="submit" name="submit" class="btn btn-danger btn-block" value="LOGIN"/>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-4"></div>
		</div>
	</body>
</html>