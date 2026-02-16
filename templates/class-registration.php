<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

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
<?php if (App::GetClient()->GetController('auth')->isLogged()): ?>
<a type="button" class="btn btn-primary geturl checkout dsm_ajax_tab" onclick="jQuery('.cart-checkout-tab').tab('show');" href="#tab-checkout-cart"><i class="fa fa-shopping-cart"></i> Checkout</a>
<?php else: ?>
    <a type="button" class="btn btn-primary geturl dsm_ajax_tab" onclick="jQuery(this).attr('href', jQuery('.default_tab').attr('href')); jQuery('.default_tab').tab('show');" href="#"><i class="fa fa-users"></i> Classes</a>
<?php endif; ?>
</div>