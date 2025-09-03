<?php
namespace DanceStudioManager;
$user_data = App::GetClient()->GetController('members')->GetUserData();

if (!empty($_POST['first_name']))
	$first_name = sanitize_text_field($_POST['first_name']);
else if (!empty($user_data) && isset($user_data->FIRSTNAME))
	$first_name = $user_data->FIRSTNAME;
	
if (!empty($_POST['last_name']))
	$last_name = sanitize_text_field($_POST['last_name']);
else if (!empty($user_data) && isset($user_data->LASTNAME))
	$last_name = $user_data->LASTNAME;

$payment_sources = App::GetClient()->GetController('members')->GetCardsAccounts();
?>
<script>
	window.account_credit =  '<?php echo ($cart['balance'] * -1);?>';
	window.cart_total = '<?php echo ($cart['total']);?>';
	jQuery(function() {	
		if (jQuery('#transaction_amount').val() == 0) {
				jQuery('#card_info').hide();
				jQuery('#card_extra_info').hide();
				jQuery('#card_cvv_info').hide();
				jQuery('#source_selector').prepend('<option value="do_not_add_card">Don\'t add card</option>').val('do_not_add_card');
		}
	});	
</script>
<?php if (DSM_OC_SHOPPING_CART_PAYPAL == '1') : ?>
	<div class="row">
		<div class="col-md-offset-6 col-sm-offset-5 col-md-5 col-sm-6">
			<?php include plugin_dir_path( __FILE__ ) . 'discount-coupon.php'; ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-offset-6 col-sm-offset-5 col-md-5 col-sm-6">
			<?php if (DSM_PAYPAL_URL != '' &&  DSM_PAYPAL_EMAIL != '') : ?>
		    <form action="<?php echo DSM_PAYPAL_URL; ?>" method="post" id="gateway-form-checkout">
		        <input type="hidden" name="cmd" value="_xclick" />
		        <input type="hidden" name="business" value="<?php echo DSM_PAYPAL_EMAIL; ?>" />
		        <input type="hidden" name="locale" value="en_US" />
		        <input type="hidden" name="currency_code" value="<?php echo DSM_PAYPAL_PAYMENT_CURRENCY; ?>" />
		        <input type="hidden" name="item_name" value="Packages & Classes" />
		        <input type="hidden" name="item_number" value="" />
		        <input type="hidden" name="quantity" value="1" />
		        <input type="hidden" id="transaction_amount" name="amount" value="<?php echo $cart['total']; ?>" />
				<input type="hidden" name="on1" value="Member ID">
		        <input type="hidden" name="os1" value="<?php echo $cart['list']['0']['ID']; ?>">	
				<?php if ($cart['discount_coupon'] != "" ) : ?>
			    <input type="hidden" name="on2" value="Discount Coupon">
				<input type="hidden" name="os2" value="<?php echo $cart['discount_coupon']; ?>">	
				<?php endif; ?>
		        <input type="hidden" name="return" value="<?php echo "//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" />
		        <input type="hidden" name="cancel_return" value="<?php echo "//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" />
		        <input type="hidden" name="notify_url" value="<?php echo get_option('dsm_api_url'); ?>ipn-paypal.php">
		        <input type="image" style="float:right;" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" 
		               alt="PayPal - The safer, easier way to pay online!" role="button" id="submit-pay-pal" />
		    </form>
		    <?php else : ?>
	    	<div class="alert alert-warning">
		    	PayPal Account not specified
		    </div>
		    <?php endif; ?>
		</div>
	</div>
<?php else: ?>
	<div class="row">
		<div class="col-md-10 form-horizontal">
			<?php include plugin_dir_path( __FILE__ ) . 'discount-coupon.php'; ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10">
			<form action="" method="post" id="gateway-form-checkout" class="form-horizontal" role="form" data-valid="validateGatewayForm()">
			<input type="hidden" name="action" value="dsmclient"/>
			<input type="hidden" name="obj" value="checkout"/>
			<input type="hidden" name="boot_tab" value="tab-checkout-cart"/>
			<input type="hidden" name="method" value="Submit"/>
			<input type="hidden" name="convenience_fee_percent" value="<?php echo DSM_CONVENIENCE_FEE_PERCENT; ?>">
		    <input type="hidden" name="convenience_fee_amount" value="<?php echo DSM_CONVENIENCE_FEE_AMOUNT; ?>">   
		    <input type="hidden" name="convenience_fee_for_check" value="<?php echo DSM_CONVENIENCE_FEE_FOR_CHECK; ?>">
			<input type="hidden" name="convenience_fee" value="<?php echo $cart["convenience_fee"];?>" id="convenience_fee_value">
			<input type="hidden" name="total_amount" value="<?php echo $cart["total"];?>" id="total_amount">   
			<?php if (!empty($selected_account)) :?>
			<input type="hidden" name="selected_account" value="<?php echo $selected_account; ?>"/>
			<?php endif; ?>
			    <div class="form-group">
			        <label class="col-sm-5 control-label">Total Amount to Pay, <?php echo DSM_CURRENCY_SIGN; ?></label>
			        <div class="col-sm-7" id="transaction_amount_field">
			            <input class="form-control" type="text" readonly="readonly" id="transaction_amount" name="transaction_amount" style="width:100px;" value="<?php echo $cart['total']; ?>">
			        </div>
			    </div>
				<?php if ($cart['balance'] < 0):?>
			    <div class="form-group row">
			        <label class="col-sm-5 col-form-label"></label>
			        <div class="col-sm-7">
			            <input type="checkbox" id="use_account_credit" name="use_account_credit"> Use Account Credit (<?php echo DSM_CURRENCY_SIGN; echo $cart['balance'] * -1;?>)
			        </div>
			    </div>
			   <?php endif; ?>
				<?php if (DSM_OC_PAY_AT_STUDIO == "1") : ?>
			    <div class="form-group">
			        <label class="col-sm-5 control-label"></label>
			        <div class="col-sm-7">
			            <input type="checkbox" id="pay_at_studio" name="pay_at_studio" value="1"
							<?php echo ((DSM_OC_PAY_AT_STUDIO != "" && (DSM_OC_ALLOW_CARD_PAYMENTS == "0")) ? 'checked="checked"' : ''); ?>> Pay at Studio
			        </div>
			    </div>
			    <?php endif; ?>
				<script>
				jQuery(function() {
					var convenience_fee_for_check = parseFloat(jQuery("input[name='convenience_fee_for_check']").val()); 
					var option = jQuery('#source_selector option:selected');
		        	var tender_type = jQuery('input[name="tender_type"]');
		        	var card_info = jQuery('#card_info');
		        	var card_cvv_info = jQuery('#card_cvv_info');
		        	if (option.val() == 0) {
		            	tender_type.val('CARD');
		            	card_info.show();
		            	card_cvv_info.show();
		        	} else {
		            	tender_type.val(option.data('tender_type'));
		            	card_info.hide();
						if (window.payment_form_cvv) 
							card_cvv_info.show();
						else  
							card_cvv_info.hide();
		        	}
		
					var fee = jQuery('#convenience_fee_value').val(), total_amount = jQuery('#total_amount').val();
		
					if (option.val() == 0 ||  option.data('tender_type') == 'CARD' || (option.data('tender_type') == 'ACH' && convenience_fee_for_check==1)) { 
						jQuery('#transaction_amount').val(parseFloat(total_amount).toFixed(2));
						jQuery('#grand_total_place').html(parseFloat(total_amount).toFixed(2));
						jQuery('#convenience_fee_amount_place').html(parseFloat(fee).toFixed(2));
						jQuery('#convenience_fee_block').show();
					}
					else {
						jQuery('#convenience_fee_block').hide();
						jQuery('#transaction_amount').val(parseFloat(total_amount-fee).toFixed(2));
						jQuery('#grand_total_place').html(parseFloat(total_amount-fee).toFixed(2));
						jQuery('#convenience_fee_amount_place').html(parseFloat(0).toFixed(2));			
					}
				});	
				</script>	    
				<div class="form-group">
					<label class="col-sm-5 control-label"></label>
					<div class="col-sm-7">
						<select class="form-control" name="token_id" id="source_selector">
							<option value="0">Add New Card</option> 
							<?php foreach ($payment_sources as $token) : ?> 
							<option value="<?php echo $token['id']; ?>" data-tender_type="<?php echo $token['tender_type'];?>" <?php echo ((!empty($selected_account) && !empty($token['account_id']) && $selected_account != $token['account_id']) ? 'disabled="disabled"' : ''); ?>>
								<?php echo ((DSM_DSM_DATE_FORMAT == 'AU' && DSM_PAYMENT_SYSTEM == 'quickpay' && $token['tender_type'] == 'ACH') ? 'DD' : $token['tender_type']); ?> **** **** **** <?php echo $token['last4']; ?> <?php echo (($token['account']) ? '('.$token['account'].')': ''); ?>
		                    </option>
							<?php endforeach; ?>
						</select>
		        </div>
		    </div>
			<div id="card_info">	
			<div class="form-group">
			    <label class="col-sm-5 control-label"><span class="text-warning">*</span> First Name</label>
			    <div class="col-sm-7">
					<input class="form-control" type="text" name="first_name" maxlength="50" value="<?php echo $first_name; ?>">
				</div>
			</div>
			<div class="form-group">
			    <label class="col-sm-5 control-label"><span class="text-warning">*</span> Last Name</label>
			    <div class="col-sm-7">
					<input class="form-control" type="text" name="last_name" maxlength="50" value="<?php echo $last_name; ?>">
				</div>
			</div>		
			<div class="form-group">
			    <label class="col-sm-5 control-label"><span class="text-warning">*</span> Card Number</label>
			    <div class="col-sm-7">
					<input class="form-control" type="text" name="card_number" value="<?php echo sanitize_text_field($_POST['card_number']); ?>">
				</div>
			</div>
			<div class="form-group">
			    <label class="col-sm-5 control-label"><span class="text-warning">*</span> Card Expiration (MMYY)</label>
			    <div class="col-xs-3">
					<input class="form-control" type="text" name="card_expiration" maxlength="4" value="<?php echo sanitize_text_field($_POST['card_expiration']); ?>">
				</div>
			</div>
			</div>
		    <div id="card_cvv_info">
				<div class="form-group">
			        <label class="col-sm-5 control-label">Card Verification (CVV2)</label>
			        <div class="col-xs-3">
						 <input class="form-control" type="text" name="card_verification" maxlength="4" value="<?php echo sanitize_text_field($_POST['card_verification']); ?>">
					</div>
			    </div>
				<?php if (!empty(trim(get_option('dsm_payment_notice')))) :?>
				<div class="form-group">
					<label class="col-sm-5 control-label">&nbsp;</label>
					<div class="col-sm-7">
						<?php echo get_option('dsm_payment_notice'); ?>
					</div>      
				</div>
				<?php else: ?>
				<?php if (!defined('DSM_OC_HIDE_AUTO_PAYMENT') || empty(DSM_OC_HIDE_AUTO_PAYMENT)): ?>
				<div class="form-group">
				  <label class="col-sm-5 control-label"></label>
				  <div class="col-sm-7">
					 <input type="checkbox" name="auto_payment" <?php echo (($_POST['auto_payment'] == 'on') ? 'checked="checked"' : ''); ?>> agree to enroll in automatic regular payment
				  </div>
				</div>
				<?php endif; ?>
				<?php endif; ?>
		    </div>
			<?php if(!empty($cart['checkout_agreements'])): ?>
				<?php include plugin_dir_path( __FILE__ ) . 'checkout-agreements.php'; ?>
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-5 control-label"></label>
				<div class="col-sm-7">
					<button type="submit" class="btn btn-primary" id="gateway-form-checkout-submit">Checkout</button>
				</div>
			</div>
			</form>
		</div>
	</div>
<?php endif; ?>