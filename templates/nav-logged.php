<?php
	namespace DanceStudioManager;
?>
<script>
jQuery(function() {
    jQuery('.dsm_ajax_tab').click(function() {
		return dsm_ajax_click(this);
    });
	<?php if (!empty($_SESSION['dsm_client_attrs']['default_tab']) && empty($_SESSION['dsm_redirect']['boot_tab'])) :?>
	dsm_ajax_click(jQuery('.default_tab'));
	<?php endif; ?>
	<?php echo App::GetClient()->NavRedirect(); ?>
});
</script>
<div>
<ul class="nav nav-pills">
		<?php if (isset($_SESSION['dsm_client_attrs']['view']) && $_SESSION['dsm_client_attrs']['view'] == "Calendar"): ?>
			<li><a href="#tab-classes-calendar" data-toggle="tab" class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Classes</a></li>
		<?php elseif (DSM_OC_USE_CLASSES_LIST_VIEW == "1" || (isset($_SESSION['dsm_client_attrs']['view']) && $_SESSION['dsm_client_attrs']['view'] == "List")) : ?>
			<?php if (DSM_OC_CLASS_LIST_TYPE == 'list_by_program' || DSM_OC_CLASS_LIST_TYPE == 'list_by_program_table' || (isset($_SESSION['dsm_client_attrs']['view']) && $_SESSION['dsm_client_attrs']['view'] == "List")) : ?>
				<li><a href="#tab-classes-list" data-toggle="tab" class="dsm_ajax_tab <?php if (($_SESSION['dsm_client_attrs']['default_tab']) == 'classes' || empty($_SESSION['dsm_client_attrs']['default_tab'])) echo 'default_tab'; ?>"><i class="fa fa-users"></i> Classes</a></li>
			<?php else : ?>
				 <li><a href="#tab-classes" data-toggle="tab" class="dsm_ajax_tab <?php if (($_SESSION['dsm_client_attrs']['default_tab']) == 'classes' || empty($_SESSION['dsm_client_attrs']['default_tab'])) echo 'default_tab'; ?>"><i class="fa fa-users"></i> Classes</a></li>
			<?php endif; ?>
		<?php else : ?>
			<li><a href="#tab-classes" data-toggle="tab" class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Classes</a></li>
		
	<?php endif; ?>
	<?php if (DSM_OC_ANNOUNCEMENTS_SECTION == '1'): ?>
		<?php 
			$param = (!empty($_COOKIE['last_check_announcements'])) ? "?last_check_announcements=".$_COOKIE['last_check_announcements'] : "";
			$count_new_announcements = App::GetClient()->GetController('news')->GetCountNewAnnouncements($param); 
		?>
		<li>
			<a href="#tab-news" data-toggle="tab" class="dsm_ajax_tab" >
				<i class="fa fa-newspaper-o"></i> <?php echo DSM_OC_ANNOUNCEMENTS_SECTION_TITLE; ?>
				<?php if (!empty($count_new_announcements["data"]) && $count_new_announcements["data"] > 0): ?>
				<sup><span class="badge badge-pill badge-danger js_news_count"><?php echo $count_new_announcements["data"]; ?></span></sup>
				<?php endif; ?>
			</a>
	    </li>
	<?php endif; ?>
	<?php if (DSM_OC_VIDEOS_SECTION == '1'): ?>
		<?php 
			$param = (!empty($_COOKIE['last_check'])) ? "?last_check=".$_COOKIE['last_check'] : "";
			$count_new_videos = App::GetClient()->GetController('videos')->GetCountNewVideos($param); 
		?>
		<li>
			<a href="#tab-videos" data-toggle="tab" class="dsm_ajax_tab" >
				<i class="fa fa-video-camera"></i> <?php echo DSM_OC_VIDEOS_SECTION_TITLE; ?>
				<?php if (!empty($count_new_videos["data"]) && $count_new_videos["data"] > 0): ?>
				<sup><span class="badge badge-pill badge-danger js_videos_count"><?php echo $count_new_videos["data"]; ?></span></sup>
				<?php endif; ?>
			</a>
	    </li>
	<?php endif; ?>
	<?php if (DSM_OC_SHOW_SALES_ITEMS == "1" || $_SESSION['dsm_client_attrs']['default_tab'] == 'sales-items'): ?>
		<li><a href="#tab-checkout-sales-items" class="dsm_ajax_tab <?php if (($_SESSION['dsm_client_attrs']['default_tab']) == 'sales-items') echo 'default_tab'; ?>"><i class="fa fa-cube"></i> <?php echo DSM_OC_SALES_ITEMS_SECTION_TITLE; ?></a></li>
	<?php endif; ?>
	<?php if (DSM_OC_SHOPPING_CART_ENABLED  == '1') : ?>	
			<li>
				<a href="#tab-checkout-cart" data-toggle="tab" class="dsm_ajax_tab" ><i class="fa fa-shopping-cart"></i> Cart</a>
			</li>
	<?php endif; ?>
	<li class="dropdown" id="m-dd">
		<a href="#" data-toggle="dropdown"><i class="fa fa-users"></i> <?php echo App::GetClient()->GetController('members')->GetName(); ?><span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#tab-members-edit" class="dsm_ajax_tab"><i class="fa fa-users"></i> Profile</a></li>
				<li><a href="#tab-members-change-password" class="dsm_ajax_tab"><i class="fa fa-lock"></i> Change Password</a></li>
				<li><a href="#tab-members-student" class="dsm_ajax_tab"><i class="fa fa-child"></i>  Add Related Student</a></li>
				<li><a href="#tab-members-classes" class="dsm_ajax_tab"><i class="fa fa-list"></i> Classes</a></li>
				<?php if (get_option('dsm_private_lesson_section') == '1'): ?>
				<li><a href="#tab-members-private-lessons" class="dsm_ajax_tab"><i class="fa fa-user-circle"></i>  Private Lessons</a></li>
				<?php endif; ?>
				<li><a href="#tab-members-charges" class="dsm_ajax_tab"><i class="fa fa-dollar"></i> Charges</a></li>
				<li><a href="#tab-members-purchases" class="dsm_ajax_tab"><i class="fa fa-shopping-cart"></i> Purchases</a></li>
				<li><a href="#tab-members-gift-cards" class="dsm_ajax_tab"><i class="fa fa-gift"></i> Gift Cards</a></li>
				<?php if (DSM_OC_LEDGER_SHOW_PAYMENTS == "1"): ?>
				<li><a href="#tab-members-payments" class="dsm_ajax_tab"><i class="fa fa-credit-card"></i> Payments</a></li>
				<?php endif; ?>
				<li><a href="#tab-members-cards-accounts" class="dsm_ajax_tab"><i class="fa fa-credit-card"></i> Stored Cards</a></li>
			</ul>
	</li>
	<li><a href="#" data-toggle="tab"  dsm_obj="auth" dsm_method="Logout"  dsm_reload="true" class="dsm_ajax_tab"><i class="fa fa-sign-out"></i> Logout</a></li>
</ul>
</div>
<div id="dsm-tab-content" class="tab-content">