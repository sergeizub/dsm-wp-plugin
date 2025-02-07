<?php
namespace DanceStudioManager;
?>
<script>
jQuery(function() {
    <?php if (DSM_OC_ALLOW_CLASS_REG_PURCH_ITEMS == "1") : ?>
	InitClassRegWithPurchesdItem();	
	<?php endif;?>
});
</script>
<div id="tab-classes-list" class="tab-pane">
    <div class="page-header">
        <h2>Class Registration</h2>
    </div>
<?php include plugin_dir_path( __FILE__ ) . 'snippets/class-details.php'; ?>
<br/><br/>
<a type="button" class="btn btn-primary geturl checkout dsm_ajax_tab" onclick="jQuery('.cart-checkout-tab').tab('show');" href="#tab-checkout-cart"><i class="fa fa-shopping-cart"></i> Checkout</a>
</div>