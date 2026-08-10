<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

$user_data = App::GetClient()->GetController('members')->GetUserData();

if (!empty($_POST['first_name']))
	$first_name = sanitize_text_field($_POST['first_name']);
else if (!empty($user_data) && isset($user_data->FIRSTNAME))
	$first_name = $user_data->FIRSTNAME;
	
if (!empty($_POST['last_name']))
	$last_name = sanitize_text_field($_POST['last_name']);
else if (!empty($user_data) && isset($user_data->LASTNAME))
	$last_name = $user_data->LASTNAME;

$payment_method = 'ach';
$label_class = 'col-sm-5'; 
?>
<div class="row">
	<h2 class="page-header">Add ACH</h2>
	<div class="col-md-10">
		<form action="index.php" method="post" id="gateway-form-ach" class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-sm-5 control-label"><span class="text-warning">*</span> First Name</label>
				<div class="col-sm-7">
					<input class="form-control" type="text" name="first_name" maxlength="50" value="<?php echo esc_attr($first_name); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label"><span class="text-warning">*</span> Last Name</label>
				<div class="col-sm-7">
					<input class="form-control" type="text" name="last_name" maxlength="50" value="<?php echo esc_attr($last_name); ?>">
				</div>
			</div>
			<?php if (defined('DSM_PAYMENT_SYSTEM') && DSM_PAYMENT_SYSTEM == 'bluefin'): ?>
			<script>
				var gw_form_id = 'gateway-form-ach';
			</script>
			<?php include plugin_dir_path( __FILE__ ) . 'hosted_forms/bluefin-ach.php'; ?>
			<?php else: ?>
			<div class="form-group">
				<label class="col-sm-5 control-label"><span class="text-warning">*</span> Bank Account Number</label>
				<div class="col-sm-7">
					<input class="form-control" type="text" name="bank_account_number" value="<?php echo esc_attr(sanitize_text_field($_POST['bank_account_number'])); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label"><span class="text-warning">*</span> <?php echo ((DSM_DSM_DATE_FORMAT == 'AU' && DSM_PAYMENT_SYSTEM == 'quickpay') ? 'BSB' : 'Bank Routing Number'); ?></label>
				<div class="col-sm-7">
					<input class="form-control" type="text" name="bank_routing_number" value="<?php echo esc_attr(sanitize_text_field($_POST['bank_routing_number'])); ?>">
				</div>
			</div>
			<?php endif; ?>
			<br>
			<?php if (!defined('DSM_OC_HIDE_AUTO_PAYMENT') || empty(DSM_OC_HIDE_AUTO_PAYMENT)): ?>
			<div class="form-group">
				<label class="col-sm-5 control-label">Auto Payment</label>
				<div class="col-sm-7">
					<input type="checkbox" name="auto_payment" <?php echo (($_POST['auto_payment'] == 'on') ? 'checked="checked"' : ''); ?>> agree to enroll in automatic regular payment	
				</div>      
			</div>
			<?php endif; ?>	
			<div class="form-group">
				<label class="col-sm-5 control-label">Description</label>
				<div class="col-sm-7">
					<input class="form-control" type="text" name="description" maxlength="255" value="<?php echo esc_attr(sanitize_textarea_field($_POST['description'])); ?>" />
				</div>      
			</div>
			<input type="hidden" name="action" value="dsmclient"/>
			<input type="hidden" name="obj" value="gateway"/>
			<input type="hidden" name="method" value="SubmitACH"/>
			<input type="hidden" name="boot_tab" value="tab-gateway-finance"/>
			<input type="hidden" name="sub_tab" value="#tab-gateway-account"/>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-6">
					<?php if (defined('DSM_PAYMENT_SYSTEM') && DSM_PAYMENT_SYSTEM == 'bluefin'): ?>
					<button type="submit" onclick="return SavePaymentMethodAch();" class="btn btn-primary">Save Card</button>
					<?php else: ?>
					<button type="submit" id="gateway-form-ach-submit" class="btn btn-primary">Save ACH</button>
					<?php endif; ?>
				</div>
			</div>
		</form>
	</div>
</div>