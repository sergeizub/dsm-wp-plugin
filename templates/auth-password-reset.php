<div id="tab-auth-login" class="tab-pane">
	<div class="dsm-header"><h2>Reset Password</h2></div>
	<form method="post"  action="index.php" class="form-horizontal" role="form">
		<div class="form-group">
			<?php if (DSM_OC_PASSWORD_RECOVERY_BY_EMAIL == "1"): ?>
			<label for="lastname" class="col-sm-3 control-label"><span style="color: red;">*</span> Email</label>
			<div class="col-sm-6">
				<input type="text" class="form-control" name="EMAIL" value="" placeholder="Your Email">
			</div>
			<?php else: ?>
			<label for="lastname" class="col-sm-3 control-label"><span style="color: red;">*</span> Username</label>
			<div class="col-sm-6">
				<input type="text" class="form-control" name="USERNAME" value="" placeholder="Your Username">
			</div>
			<?php endif; ?>
		</div>				
		<div class="form-group">
			<div class="col-sm-3">
				<input type="hidden" name="action" value="dsmclient"/>
				<input type="hidden" name="obj" value="auth"/>
				<input type="hidden" name="method" value="PasswordReset" />
			</div>
			<div class="col-sm-6">
				<button class="btn btn-warning" type="submit" id="cl_sign_in">Reset Password</button>
			</div>
		</div>
	</form>
</div>