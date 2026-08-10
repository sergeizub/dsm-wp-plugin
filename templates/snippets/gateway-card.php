<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

$user_data = App::GetClient()->GetController('members')->GetUserData();

if (!empty($_POST['first_name']))
	$first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
else if (!empty($user_data) && isset($user_data->FIRSTNAME))
	$first_name = $user_data->FIRSTNAME;
	
if (!empty($_POST['last_name']))
	$last_name = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
else if (!empty($user_data) && isset($user_data->LASTNAME))
	$last_name = $user_data->LASTNAME;

$payment_method = 'card';
$label_class = 'col-sm-5'; 
?>
<div class="row">
	<h2 class="page-header">Add Card</h2>
	<div class="col-md-10">
		<form action="index.php" method="post" id="gateway-form-card" class="form-horizontal" role="form">
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
				var gw_form_id_card = 'gateway-form-card';
			</script>
			<?php include plugin_dir_path( __FILE__ ) . 'hosted_forms/bluefin-card.php'; ?>
			<?php else: ?>
			<div class="form-group">
				<label class="col-sm-5 control-label"><span class="text-warning">*</span> Card Number</label>
				<div class="col-sm-7">
					<input class="form-control" type="text" name="card_number" value="<?php echo isset($_POST['card_number']) ? esc_attr(sanitize_text_field($_POST['card_number'])) : ''; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label"><span class="text-warning">*</span> Card Expiration (MMYY)</label>
				<div class="col-xs-3">
					<input class="form-control" type="text" name="card_expiration" maxlength="4" value="<?php echo isset($_POST['card_expiration']) ? esc_attr(sanitize_text_field($_POST['card_expiration'])) : ''; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label"><span class="text-warning">*</span> Card Verification (CVV2)</label>
				<div class="col-xs-3">
					<input class="form-control" type="text" name="card_verification" maxlength="4" value="<?php echo isset($_POST['card_verification']) ? esc_attr(sanitize_text_field($_POST['card_verification'])) : ''; ?>">
				</div>
			</div>
			<?php endif; ?>
			<br>
			<?php if (!defined('DSM_OC_HIDE_AUTO_PAYMENT') || empty(DSM_OC_HIDE_AUTO_PAYMENT)): ?>
			<div class="form-group">
				<label class="col-sm-5 control-label"></label>
				<div class="col-sm-7">
					<input type="checkbox" name="auto_payment" <?php echo ((!empty($_POST['auto_payment']) && $_POST['auto_payment'] == 'on') ? 'checked="checked"' : ''); ?>> agree to enroll in automatic regular payment
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-5 control-label">Description</label>
				<div class="col-sm-7">
					<input class="form-control" type="text" name="description" maxlength="255" value="<?php echo isset($_POST['description']) ? esc_attr(sanitize_textarea_field($_POST['description'])) : ''; ?>" />
				</div>      
			</div>
			<input type="hidden" name="action" value="dsmclient"/>
			<input type="hidden" name="obj" value="gateway"/>
			<input type="hidden" name="method" value="SubmitCard"/>
			<input type="hidden" name="boot_tab" value="tab-gateway-finance"/>
			<input type="hidden" name="sub_tab" value="#tab-gateway-card"/>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-6">
					<?php if (defined('DSM_PAYMENT_SYSTEM') && DSM_PAYMENT_SYSTEM == 'bluefin'): ?>
					<button type="submit" onclick="return SavePaymentMethodCard();" class="btn btn-primary">Save Card</button>
					<?php else: ?>
					<button type="submit" id="gateway-form-card-submit" class="btn btn-primary">Save Card</button>
					<?php endif; ?>
				</div>
			</div>
		</form>
	</div>
</div>