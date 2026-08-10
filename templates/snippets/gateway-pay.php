<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

$result = App::GetClient()->GetController('gateway')->PaymentForm();
if (!empty($result->form))
	$form = $result->form;
?>
<h2 class="page-header">Make Payment</h2>
<div class="col-md-10">
<?php if (!empty($form->payment_sources)): ?>
	<form action="" method="post" id="gateway-form-pay" class="form-horizontal" role="form">
		<input type="hidden" name="action" value="dsmclient"/>
		<input type="hidden" name="obj" value="gateway"/>
		<input type="hidden" name="boot_tab" value="tab-gateway-finance"/>
    	<input type="hidden" name="method" value="MakePayment">
    	<input type="hidden" name="verbose" value="1">
    	<input type="hidden" name="transaction_type" value="SALE">
    	<input type="hidden" name="tender_type" value="">
		<input type="hidden" name="source" value="oc_make_payment">
    	<input type="hidden" name="apply_to_charges" value="true">
		<input type="hidden" name="charge_discount_coupon" value="">
    	<input type="hidden" name="convenience_fee_percent" value="<?php if (defined('DSM_CONVENIENCE_FEE_PERCENT')) echo esc_attr(DSM_CONVENIENCE_FEE_PERCENT);?>">
   		<input type="hidden" name="convenience_fee_amount" value="<?php  if (defined('DSM_CONVENIENCE_FEE_AMOUNT')) echo esc_attr(DSM_CONVENIENCE_FEE_AMOUNT);?>">   
    	<input type="hidden" name="convenience_fee_for_check" value="<?php  if (defined('DSM_CONVENIENCE_FEE_FOR_CHECK')) echo esc_attr(DSM_CONVENIENCE_FEE_FOR_CHECK);?>">       

    	<div class="form-group row">
        	<label class="col-sm-3 control-label"><span class="text-warning">*</span> Pay for</label>
        	<div class="col-sm-7">
			<?php if ($form->charges_list): ?>
			<?php foreach ($form->charges_list as $item): ?>
			<?php if ($item->id == 'convenience_fee'): ?>
				<input type="checkbox" value="convenience_fee" data-amount="0" id="convenience_fee_checkbox" onclick="return false;" checked="checked" /> <?php echo esc_html($item->value); ?> - <?php echo esc_html(DSM_CURRENCY_SIGN);?><span id="convenience_fee_amount"></span><br />
				<input type="hidden" name="charges[]" value="<?php echo esc_attr($item->id); ?>" data-amount="<?php echo esc_attr($item->amount); ?>">
			<?php else: ?>
				<input type="checkbox" name="charges[]" value="<?php echo esc_attr($item->id); ?>" data-amount="<?php echo esc_attr($item->amount); ?>" data-charge_category_id="<?php echo esc_attr($item->charge_category_id); ?>" /> <?php echo esc_html($item->value); ?> <span id="charge_discount_<?php echo esc_attr($item->id); ?>" class="text-muted"></span><br />
			<?php endif; ?> 
			<?php endforeach; ?>
			<?php else: ?>
				<span class="col-form-label">No charges to pay</span>
			<?php endif; ?>	
			</div>
    	</div>
    	<div class="form-group row">
        	<label class="col-sm-3 control-label"><span class="text-warning">*</span> Amount</label>
        	<div class="col-sm-3">
            	<input class="form-control" name="transaction_amount" <?php if (defined('DSM_ALLOW_PARTIAL_PAYMENTS') && DSM_ALLOW_PARTIAL_PAYMENTS == "0") echo 'readonly="readonly"';?> type="text" value="0.00" />
			</div>
    	</div>    
    	<div class="form-group row">
        	<label class="col-sm-3 control-label"><span class="text-warning">*</span> Card<?php if (defined('DSM_OC_ALLOW_ACH_PAYMENTS') && DSM_OC_ALLOW_ACH_PAYMENTS == '') echo '/ACH'; ?></label>
        	<div class="col-sm-7">
				<select name="token_id" class="form-control">
					<option value="0">Please select...</option>
					<?php foreach ($form->payment_sources as $item): ?>
					<option value="<?php echo esc_attr($item->token);?>" <?php if ($item->default == "1") echo 'selected="selected"'?> data-tender_type="<?php echo esc_attr($item->tender_type); ?>">
						<?php if (!empty($item->card_type)) echo esc_html($item->card_type); else echo esc_html($item->tender_type); ?>
						**** **** <?php echo esc_html($item->last4); ?>
						<?php if (!empty($item->card_expire)) echo '[Exp:]'.esc_html($item->card_expire); ?>
					</option>
					<?php endforeach; ?>
				</select>
            	<script>
            	jQuery(function () {
                	jQuery("#gateway-form-pay select[name=token_id]").trigger("change");
            	});
            	</script>
			</div>
    	</div>
		<?php if (!empty($form->payment_form_cvv)): ?>
		<div class="form-group row">
        	<label class="col-sm-3 control-label"><?php if (!empty($form->payment_form_cvv_required)) echo '<span class="text-warning" id="payment-form-cvv-required">*</span>'; ?> Card Verification (CVV2)</label>
        	<div class="col-sm-3">
				<input class="form-control" type="text" name="card_verification" maxlength="4" value="">
			</div>
    	</div>
    	<?php endif; ?>
		<div class="form-group row">
        	<label class="col-sm-3 control-label"><span class="text-warning">*</span> Billing First Name</label>
        	<div class="col-sm-7">
				<input class="form-control" type="text" name="first_name" maxlength="50" value="<?php echo esc_attr($form->first_name); ?>">
			</div>
    	</div>
		<div class="form-group row">
        	<label class="col-sm-3 control-label"><span class="text-warning">*</span> Billing Last Name</label>
        	<div class="col-sm-7">
				<input class="form-control" type="text" name="last_name" maxlength="50" value="<?php echo esc_attr($form->last_name); ?>">
			</div>
    	</div>
		<div class="col-sm-3"></div>		            
		<div class="col-sm-9"> 
			<button type="submit" class="btn btn-primary float-right" id="gateway-form-pay-submit">Make Payment</button>
		</div>
		<br/><br/>
	</form>
</div>
<?php endif; ?>