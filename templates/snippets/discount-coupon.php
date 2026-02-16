<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (DSM_ENABLE_DISCOUNT_COUPONS == "1") : ?>
<?php if ($cart['discount_coupon']) : ?>
<div class="form-group">
	<label class="col-sm-5 control-label">Applied Discount Coupon</label>
	<label class="col-sm-4 control-label">
		<i><?php echo $cart['discount_coupon']['COUPON_CODE']; ?>
			<?php echo $cart['discount_coupon']['DISCOUNT_VALUE']; ?> (<?php echo (($cart['discount_coupon']['DISCOUNT_TYPE'] == 'percentage') ? '%' : DSM_CURRENCY_SIGN); ?>)</i>
	</label>
	<div class="col-sm-3">		            
		<a href="#tab-checkout-cart" class="btn btn-warning dsm_ajax_tab" id="remove-discount" dsm_obj="checkout" dsm_method="RemoveDiscount"
		onclick="this.setAttribute('dsm_discount_coupon', '');"
		><i class="fa fa-minus-circle"></i> Remove</a>
	</div>
</div>
<input type="hidden" name="method" value="RemoveDiscount"/>
<?php else: ?>
<div class="form-group">
	<label class="col-sm-5 control-label">Discount Coupon</label>
	<div class="col-sm-4">
		<input class="form-control" type="text" id="discount_coupon" name="discount_coupon" style="width:100px;">
	</div>
	<div class="col-sm-3">
		<a href="#tab-checkout-cart" class="btn btn-success dsm_ajax_tab" id="apply-discount" dsm_obj="checkout" dsm_method="AddDiscount" dsm_discount_coupon = ""
			onclick="this.setAttribute('dsm_discount_coupon', document.getElementById('discount_coupon').value);" >Apply Discount</a>
	</div>
</div>
<input type="hidden" name="method" value="AddDiscount"/>
<?php endif; ?>
<?php endif; ?>