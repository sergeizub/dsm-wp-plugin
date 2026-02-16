<?php
namespace DanceStudioManager;
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="row mt10">
	<div class="col-xs-4 col-md-3 text-right" style="padding-top: 5px !important;font-weight:bold;"><?php echo $student['FIRSTNAME']; ?> <?php echo $student['LASTNAME']; ?></div>
	<div class="col-xs-4 col-md-4 text-left">
		<?php include plugin_dir_path( __FILE__ ) . 'sales-item-quantity.php'; ?>
	</div>
	<div class="col-xs-4 col-md-5">
		<a class="btn btn-success select-product dsm_ajax_tab" type="button" id="quant-<?php echo $sales_item['ID']; ?>-<?php echo $student['ID']; ?>"
                    href="#tab-checkout-sales-items-<?php echo $sales_item['ID']; ?>"
			dsm_obj="checkout" dsm_method="SubmitSalesItem" dsm_student_id="<?php echo $student['ID']; ?>" dsm_sales_item_id="<?php echo $sales_item['ID']; ?>" dsm_quantity="1"
	            	data-sales_item_id="<?php echo $sales_item['ID']; ?>"
	            	data-student_id="<?php echo $student['ID']; ?>">
	        <i class="fa fa-shopping-cart"></i> <span>Add to Cart</span>
		</a>
	</div>
</div>
 