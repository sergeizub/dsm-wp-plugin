<?php
namespace DanceStudioManager;
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<script>
jQuery(function() {
    jQuery('.dsm_ajax_tab').click(function() {
		return dsm_ajax_click(this);
    });
	dsm_ajax_click(jQuery('.default_tab'));
});
var show_login_alert = '<?php if (defined('DSM_OC_SHOW_LOGIN_ALERT')) echo esc_html(DSM_OC_SHOW_LOGIN_ALERT); else echo ''; ?>';
</script>
<ul class="nav nav-pills">
	<?php if (isset($_SESSION['dsm_client_attrs']['default_tab']) && $_SESSION['dsm_client_attrs']['default_tab'] == 'sales-items') : ?>
	<li><a href="#tab-checkout-sales-items" data-toggle="tab"  class="dsm_ajax_tab default_tab"><i class="fa fa-cube"></i> <?php echo esc_html(DSM_OC_SALES_ITEMS_SECTION_TITLE); ?></a></li>
	<?php elseif (isset($_SESSION['dsm_client_attrs']['view']) && $_SESSION['dsm_client_attrs']['view'] == "Calendar") : ?>
	<li><a href="#tab-classes-calendar" data-toggle="tab"  class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Classes</a></li>
	<?php elseif ((isset($_SESSION['dsm_client_attrs']['view']) && $_SESSION['dsm_client_attrs']['view'] == "Location Class List")) : ?>
	<li><a href="#tab-classes-list-location" data-toggle="tab" class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Location Class List</a></li>
	<?php elseif (DSM_OC_USE_CLASSES_LIST_VIEW == "1" || (isset($_SESSION['dsm_client_attrs']['view']) && $_SESSION['dsm_client_attrs']['view'] == "List")) : ?>
		<?php if (DSM_OC_CLASS_LIST_TYPE == 'list_by_program' || DSM_OC_CLASS_LIST_TYPE == 'list_by_program_table' || (isset($_SESSION['dsm_client_attrs']['view']) && $_SESSION['dsm_client_attrs']['view'] == "List")) : ?>
	<li><a href="#tab-classes-list" data-toggle="tab" class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Classes</a></li>
		<?php else : ?>
		<?php ///echo <li><a href="#tab-classes-list" data-toggle="tab" class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Classes</a></li>'?>
	<li><a href="#tab-classes" data-toggle="tab" class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Classes</a></li>
		<?php endif; ?>
		<?php else : ?>
		<?php //echo '<li><a href="#tab-classes-calendar" data-toggle="tab"  class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Classes</a></li>';?>
	<li><a href="#tab-classes" data-toggle="tab" class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Classes</a></li>
		<?php endif; ?>
    <li><a href="#tab-auth-register" data-toggle="tab" class="dsm_ajax_tab"><i class="fa fa-user"></i> Sign In / Create Account</a></li>
	<li><a href="#tab-auth-password-reset" data-toggle="tab" class="dsm_ajax_tab"><i class="fa fa-lock"></i> Reset Password</a></li>
</ul>
<div id="dsm-tab-content" class="tab-content">