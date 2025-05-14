<?php
namespace DanceStudioManager;
$cart = App::GetClient()->GetController('checkout')->GetCart();
$selected_account = $cart['selected_account'];
?>
<div id="tab-checkout-cart" class="tab-pane">
    <div class="page-header">
        <h2>Shopping Cart</h2>
    </div>
	<?php include plugin_dir_path( __FILE__ ) . 'snippets/unsigned-waivers.php'; ?>
</div>
<?php if ($cart['list']) : ?>
<div id="cart-items-list">
	<div class="table-responsive">
        <table class="table table-striped">
            <?php foreach ($cart['list'] as $student ) : ?>
            <thead>		        
	        	<tr>
		        	<th colspan="8"><h4><?php echo $student['FIRSTNAME'];?> <?php echo $student['LASTNAME'];?></h4></th>
	        	</tr>
		        <tr>
		            <th>Item</th>
                    <th>&nbsp;</th>
		            <?php if (DSM_MAIN_DISCOUNT == 'HOURLY_RATES' && false) : ?>
		            <th class="text-right">
			            Hours per  <?php if (DSM_HOURLY_TIME_RATES == 'WEEKLY') echo 'week'; elseif (DSM_HOURLY_TIME_RATES == 'MONTHLY') echo 'month'; ?>
		            </th>
					<?php endif; ?>
					<th class="text-right" width="90">Price,&nbsp;<?php echo DSM_CURRENCY_SIGN;?></th>
					<th class="text-center"></th>
                    <?php if (DSM_ENABLE_DISCOUNT_COUPONS == '1' || DSM_MAIN_DISCOUNT == 'MULTI_CLASS' || DSM_MULTI_STUDENT_ENABLED == '1') : ?>
		            <th class="text-right" width="90">Discount,&nbsp;<?php echo DSM_CURRENCY_SIGN;?></th>
		            <th class="text-right" width="90">Subtotal,&nbsp;<?php echo DSM_CURRENCY_SIGN;?></th>
		           <?php endif; ?>
		            <th class="text-right" width="90"><?php echo ((DSM_TAX_ENABLED == '1') ? 'Tax,&nbsp;'.DSM_CURRENCY_SIGN : ''); ?></th>
		            <th class="text-right" width="90"></th>
		        </tr>
		    </thead>
            <tbody>
            <?php foreach ($student['items'] as $k_item => $item) :?>
			<?php if (defined ('DSM_ENABLE_PAYMENT_ACCOUNT_2') && DSM_ENABLE_PAYMENT_ACCOUNT_2 == '1') $cart_locations[$item['location_id']] = $cart_locations[$item['location_id']]; ?>
                <?php if ($item['student_id']) : ?>
                    <tr id="tr-<?php echo $k_item; ?>">
			            <td colspan="2">
                            <?php if ($item['class']['CODE']) : ?>
                                <?php echo $item['class']['CODE']; ?><br><i class="text-muted"><?php echo $item['title']; ?><?php echo (($item['season']['NAME']) ? ' ('.$item['season']['NAME'].')' : ''); ?></i>
                            <?php else: ?>
                                <?php echo $item['title']; ?>
                            <?php endif; ?>
                            <?php echo (($item['wait_list'] == '1') ? '<div class="label label-warning">Wait List</div>' : ''); ?>
                            <?php if ($item['payment_plan']) : ?>
				            <p class="font-italic">
                                <i>
                                    <b>Payment Plan:</b><br>
                                    First Payment <?php echo DSM_CURRENCY_SIGN.$item['payment_plan']['FIRST_PAYMENT_AMOUNT'] ?> plus <?php echo DSM_CURRENCY_SIGN.$item['payment_plan']['PAYMENT_PLAN_FEE'] ?> fee
                                    <br>and <?php echo $item['payment_plan']['REPEATS'] ?> payment(s) <?php echo DSM_CURRENCY_SIGN.$item['payment_plan']['RECURRING_AMOUNT'] ?> <?php echo $item['payment_plan']['SCHEDULE_NAME'] ?>
                                <i>
                            </p>
				            <?php endif; ?>
			            </td>
                        <?php if (DSM_MAIN_DISCOUNT == 'HOURLY_RATES'  && false) : ?>
                            <td class="text-right"><?php echo (($item['hours'] != 0) ? $item['hours'] : ''); ?></td>
                        <?php endif; ?>
                            <td class="text-right"><?php echo number_format($item['price'],2); ?></td>
                            <td class="text-right" <?php echo (($item['discount_description'] != 0) ? 'width="270"' : ''); ?> ><?php echo $item['discount_description']; ?></td>
			            <?php if (DSM_ENABLE_DISCOUNT_COUPONS == '1' || DSM_MAIN_DISCOUNT == 'MULTI_CLASS' || DSM_MULTI_STUDENT_ENABLED == '1') : ?>
                            <td class="text-right"><?php echo number_format($item['discount'],2); ?></td>
                            <td class="text-right"><?php echo number_format($item['subtotal'],2); ?></td>
                        <?php endif; ?>
			            <td class="text-right"><?php echo ((DSM_TAX_ENABLED) ? $item['tax'] : ''); ?></td>
			            <td class="text-right">
				            <a class="btn btn-warning btn-sm select-class <?php echo (($item['remove'] == "1") ? 'dsm_ajax_tab' : ''); ?>" type="button"
                                href = '#tab-checkout-cart'
                                <?php echo (($item['remove'] != "1") ? 'disabled="disabled"' : ''); ?>
                                dsm_obj="checkout"
								dsm_method="DeleteCartItem"
				            	dsm_item_key="<?php echo $item['cart_item_key']; ?>"
				            	dsm_class_id="<?php echo $item['class_id']; ?>"
				            	dsm_student_id="<?php echo $item['student_id']; ?>"
				            	dsm_sales_item_id="<?php echo $item['sales_item_id']; ?>"
								>
				            	<span><i class="fa fa-minus-circle"></i> Remove</span>
				            </a> 
			            </td>
			        </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if (DSM_MAIN_DISCOUNT == 'HOURLY_RATES' && DSM_CALCULATE_TOTALS_FOR == 'student' && false) : ?>
		        <tr>
		            <th class="text-right" colspan="2">Total  <?php echo $cart['total_hours'][$student['ID']]; ?> hour(s) per <?php if (DSM_HOURLY_TIME_RATES == 'WEEKLY') echo 'week'; elseif (DSM_HOURLY_TIME_RATES == 'MONTHLY') echo 'month'; ?>, rate <?php echo DSM_CURRENCY_SIGN; ?> <?php echo $cart['hours_rate'][$student['ID']]; ?></th>
		            <th colspan="8"></th>
		        </tr>
                <?php elseif (DSM_MAIN_DISCOUNT == 'MULTI_CLASS_RATES' && DSM_CALCULATE_TOTALS_FOR == 'student' && $cart['class_rates'][$student['ID']]['classes'] != '') : ?>
		        <tr>
		            <th class="text-right" colspan="2"><?php echo $cart['class_rates'][$student['ID']]['classes']; ?> Regular Class(es) Rate <?php echo DSM_CURRENCY_SIGN; ?><?php echo $cart['class_rates'][$student['ID']]['rate']; ?></th>
		            <th colspan="8"></th>
		        </tr>
	        <?php endif; ?>
			<?php endforeach; ?>
			
			<?php if (DSM_MAIN_DISCOUNT == 'HOURLY_RATES' && DSM_CALCULATE_TOTALS_FOR == 'family' && false) : ?>
				<tr>
		            <th class="text-right">Total <?php echo $cart['total_hours'][0]; ?> hour(s) per <?php if (DSM_HOURLY_TIME_RATES == 'WEEKLY') echo 'week'; elseif (DSM_HOURLY_TIME_RATES == 'MONTHLY') echo 'month'; ?>, rate <?php echo DSM_CURRENCY_SIGN; ?><?php echo $cart['hours_rate'][0]; ?></th>
		            <th colspan="8"></th>
		        </tr>
			<?php elseif (DSM_MAIN_DISCOUNT == 'MULTI_CLASS_RATES' && DSM_CALCULATE_TOTALS_FOR == 'family' && $cart['class_rates'][0]['classes'] != '') : ?>
		        <tr>
		            <th class="text-right"><?php echo $cart['class_rates'][0]['classes'];?> Regular Class(es) Rate <?php echo DSM_CURRENCY_SIGN; ?><?php echo $cart['class_rates'][0]['rate']; ?></th>
		            <th colspan="8"></th>
		        </tr>
			<?php endif; ?>
            </tbody>
			<tfoot>        
		        <tr>
		            <th class="text-right" colspan="2">Totals:</th>
					<?php echo ((DSM_MAIN_DISCOUNT == 'HOURLY_RATES' && false) ? '<th class="text-right"></th>' : ''); ?>
		            <th class="text-right"><?php echo number_format($cart['total_price'],2); ?></th>
		            <th></th>
					<?php if (DSM_ENABLE_DISCOUNT_COUPONS == '1' || DSM_MAIN_DISCOUNT == 'MULTI_CLASS' || DSM_MULTI_STUDENT_ENABLED == '1') : ?>
		            <th class="text-right"><?php echo number_format($cart['total_discount'],2); ?></th>
		            <th class="text-right"><?php echo number_format($cart['subtotal'],2); ?></th>
		            <?php endif; ?>
		            <th class="text-right"><?php echo ((DSM_TAX_ENABLED == '1') ? number_format($cart['tax'],2) : ''); ?></th>
		            <th></th>
		        </tr>
		    </tfoot>
		</table>
	</div>
<?php if (DSM_TAX_ENABLED == '1' && $cart['tax'] > 0) : ?>
	<div class="row">
		<div class="col-md-offset-7 col-md-6 pt10">		
		    <div class="form-group">
			    <label class="col-sm-5 control-label text-right">Subtotal, <?php echo DSM_CURRENCY_SIGN; ?></label>
			    <label class="col-sm-2 control-label text-right">
					<?php echo number_format($cart['subtotal'],2); ?>
			    </label>
		    </div>	
		</div>
	</div>
	<div class="row">
		<div class="col-md-offset-7 col-md-6 pt10">		
		    <div class="form-group">
			    <label class="col-sm-5 control-label text-right">Tax <?php echo ((DSM_TAX_PERCENTAGE_VALUE > 0) ? DSM_TAX_PERCENTAGE_VALUE.'%' : ''); ?>, <?php echo DSM_CURRENCY_SIGN;?></label>
			    <label class="col-sm-2 control-label text-right">
					<?php echo number_format($cart['tax'],2); ?>
			    </label>
		    </div>	
		</div>
	</div>
<?php endif; ?>
<?php if ($cart['convenience_fee'] > 0) : ?>
	<div class="row">
		<div class="col-md-offset-7 col-md-6 pt10">		
		    <div class="form-group">
			    <label class="col-sm-5 control-label text-right"><?php echo $cart['convenience_fee_category']; ?>, <?php echo DSM_CURRENCY_SIGN; ?></label>
			    <label class="col-sm-2 control-label text-right">
 			        	<?php echo number_format($cart['convenience_fee'],2); ?> <i>(<?php echo $cart['convenience_fee_description']; ?>)</i>
			    </label>
		    </div>	
		</div>
	</div>
<?php endif; ?>
	<div class="row">
		<div class="col-md-offset-7 col-md-6 pt10">		
		    <div class="form-group">
			    <label class="col-sm-5 control-label text-right">Grand Total, <?php echo DSM_CURRENCY_SIGN; ?></label>
			    <label class="col-sm-2 control-label text-right">
 			        <?php echo number_format($cart['total'],2); ?>
			    </label>
		    </div>	
		</div>
	</div>
<?php include plugin_dir_path( __FILE__ ) . 'snippets/checkout.php'; ?>
<?php else: ?>
	<div class="alert alert-info">
		No items in cart
	</div>
<?php endif; ?>