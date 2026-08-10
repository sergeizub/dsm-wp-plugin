<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

$filter = array();
?>
<div id="tab-classes-list" class="tab-pane">
<?php $filters = (!empty($classes_list->filters) ? $classes_list->filters : false); ?>
<?php include plugin_dir_path( __FILE__ ) . 'snippets/class-filters.php'; ?>
<?php
foreach($_REQUEST['filter'] as $k => $v)
	$filter[$k] = sanitize_text_field($v);
	
if (is_array($_SESSION['dsm_client_attrs']['class_code']))
	foreach($_SESSION['dsm_client_attrs']['class_code'] as $k => $v)
		$programs_class_code[$k] = sanitize_text_field($v);
else
	$programs_class_code = sanitize_text_field($_SESSION['dsm_client_attrs']['class_code']);
	
if(!empty($_SESSION['dsm_client_attrs']))
	$classes_list = App::GetClient()->GetController('classes')->GetClassesData((array)$_SESSION['dsm_client_attrs'] + (array)$filter);
else
	$classes_list = App::GetClient()->GetController('classes')->GetClassesData($filter);

foreach ($classes_list->groupclasses as $class) {
	if ($_SESSION['dsm_client_attrs']['hide_closed'] && !empty($class->OC_REGISTRATION) && $class->OC_REGISTRATION == "closed")
		continue;
	if ($programs_class_code) {
		$classes_tabs['class_code'][] =  $class;
		if (is_array($programs_class_code))
			$programs['class_code'] = implode(", ",$programs_class_code);
		else
			$programs['class_code'] = $programs_class_code;
	}
	else {
		$classes_tabs[$class->PROGRAM_ID][] =  $class;
		$programs[$class->PROGRAM_ID] = $class->PROGRAM;
	}
}

//Sort By Location Asc
foreach ($classes_tabs as $k => $v_array) {
	usort($classes_tabs[$k], 'dsm_location_sort');
}
?>
<?php include plugin_dir_path( __FILE__ ) . 'snippets/unsigned-waivers.php';?>
<div class="class-container" data-unsigned-waivers-allow="<?php echo esc_html(DSM_OC_UNSIGNED_WAIVERS_CLASS_REG); ?>"	>
<?php if (!empty($classes_tabs)): ?>
	<div id="tabs">
		<ul class="nav nav-tabs" role="tablist">
        	<?php foreach ($classes_tabs as $key => $item): ?>
			<?php reset($classes_tabs);?>
			<li role="presentation" class="<?php echo ($key === key($classes_tabs) ? 'active' : ''); ?>">
				<a href="#tab<?php echo esc_attr($key); ?>" aria-controls="<?php echo esc_attr($programs[$key]); ?>"
				role="tab" data-toggle="tab" data-program_id="<?php echo esc_attr($key); ?>"><?php echo esc_attr($programs[$key]); ?></a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="tab-content classes-list">
	<?php foreach ($classes_tabs as $key => $item): ?>
	<?php reset($classes_tabs);?>
		<div role="tabpanel" class="tab-pane <?php echo ($key === key($classes_tabs) ? 'active' : ''); ?>" id="tab<?php echo esc_attr($key); ?>">
			<div class="tab-content">
				<div class="get-page" data-relation="categories" data-relation_id="<?php echo esc_attr($key); ?>"></div>
				<?php include plugin_dir_path( __FILE__ ) . 'snippets/select-class-table.php'; ?>
			</div>
		</div>
	<?php endforeach; ?>
	</div>
</div>
<?php else: ?>
	<h4>No classes scheduled at this time</h4>
<?php endif; ?>
</div>