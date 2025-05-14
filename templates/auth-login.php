<div id="tab-auth-login" class="tab-pane">
<div class="dsm-header"><h2>Sign In</h2></div>
<div class="form-signin">
	<div class="login-wrapper">
		<div class="login-form">
			<form method="post"  action="index.php" class="form-horizontal" role="form" id="login-form">
				<div class="form-group">
					<input id="cl_login" name="username" type="text" placeholder="Enter your Email or Username" class="form-control" autofocus="" required>
				</div>
				<div class="form-group">
					<input id="cl_password" name="password" type="password" placeholder="Enter your Password" class="form-control" required>
				</div>				
				<div class="form-group">
					<input type="hidden" name="action" value="dsmclient"/>
					<input type="hidden" name="obj" value="auth"/>
					<input type="hidden" name="method" value="Login"/>
					<input type="hidden" name="class_id" value="<?php echo sanitize_key($_POST['class_id']); ?>"/>
					<input type="hidden" name="schedule_id" value="<?php echo sanitize_key($_POST['schedule_id']); ?>"/>
					<input type="hidden" name="sales-item_id" value="<?php echo sanitize_key($_POST['sales-item_id']); ?>"/>
					<button class="btn btn-success" type="submit" id="cl_sign_in"><i class="fa fa-sign-in"></i> Sign In</button>
				</div>
			</form>
	    </div>
	</div>		
</div>
</div>