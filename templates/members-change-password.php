<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div id="tab-classes-list" class="tab-pane">
	<div class="dsm-header">
		<h2>Change Password</h2>
	</div>
	<form id="form" action="index.php" method="post" class="form-horizontal" role="form">
	<input type="hidden" name="action" value="dsmclient"/>
	<input type="hidden" name="obj" value="members"/>
	<input type="hidden" name="method" value="ChangePassword"/>
	<input type="hidden" name="boot_tab" value="tab-members-change-password"/>
	<div class="form-group">
		<label class="col-sm-3 control-label">*  New password</label>
		<div class="col-sm-3">
			<input class="form-control" maxlength="40" id="PASSWORD" name="PASSWORD" type="password"  placeholder="Enter Password"/>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 control-label"></label>
		<div class="col-sm-3">
			<input class="form-control" maxlength="40" id="PASSWORD2" name="PASSWORD2" type="password" placeholder="Repeat Password"/>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 control-label"></label>
		<div class="col-sm-3">
			<button type="submit" class="btn btn-default">Save</button>
		</div>
	</div>
	
	</form>
</div>
