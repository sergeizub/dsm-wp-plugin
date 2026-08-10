<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

$groupclasses = array();
$class_id = App::GetApi()->GetIdParam();
$schedule_id = sanitize_key($_POST['schedule_id']);

if (!empty($schedule_id))
	$class_full_info = App::GetClient()->GetController('classes')->GetScheduleInfo($class_id, $schedule_id);
else
	$class_full_info = App::GetClient()->GetController('classes')->GetInfo($class_id);
	
	
if (!empty($class_full_info->groupclasses)) {
	$groupclasses = json_decode(json_encode($class_full_info->groupclasses),true);
}

if (!empty($class_full_info->active_purchases)) {
	$active_purchases = json_decode(json_encode($class_full_info->active_purchases),true);
}

if (!empty($class_full_info->items)) {
	$items = json_decode(json_encode($class_full_info->items),true);
}
include plugin_dir_path( __FILE__ ) . 'unsigned-waivers.php';
?>
<?php foreach($groupclasses as $groupclass): ?>
	Code: <?php echo esc_html($groupclass['CODE']); ?><br>
	Genre: <?php echo esc_html($groupclass['NAME']); ?><br>
	Level: <?php echo esc_html($groupclass['LEVEL']); ?><br>
	Location: <?php echo esc_html($groupclass['LOCATION']); ?><br>
	<?php
	if ($schedule_id)
		$label_schedule = $schedule_id;
	else if ($groupclass['SCHEDULE_ID'])
		$label_schedule = $groupclass['SCHEDULE_ID'];
	?>
	<?php if ($label_schedule): ?>
		<?php
			$info = App::GetClient()->GetController('classes')->GetScheduleInfo($class_id,$label_schedule);
			$schedule =  json_decode(json_encode($info->schedule),true);
			if ($schedule)
				echo 'Date and time: <span id="selected_schedule_date">'.esc_html($schedule['START_DATE']).' '.esc_html($schedule['START_TIME']).' - '.esc_html($schedule['END_TIME']).'</span><br>';
		?>
	<?php endif; ?>
	<?php if (DSM_OC_CLASS_SHOW_INSTRUCTOR == 1): ?>
		Instructor: <?php echo esc_html($groupclass['INSTRUCTOR']); ?><br />
		<?php echo (($groupclass['INSTRUCTOR2'] > 0 ) ? esc_html($groupclass['INSTRUCTOR2']).'<br />' : ''); ?>
		<?php echo (($groupclass['INSTRUCTOR3'] > 0 ) ? esc_html($groupclass['INSTRUCTOR3']).'<br />' : ''); ?>
		<br />
	<?php else: ?>
		<br />
	<?php endif; ?>

	<?php echo ((DSM_OC_CLASS_SHOW_MAX_STUDENTS == 1 ) ? 'Max. Students: '.esc_html($groupclass['MAX_STUDENTS']).'<br />' : ''); ?>
	<?php if (DSM_OC_CLASS_DETAILS_AGE == 1) : ?>
	<?php echo (($groupclass['MIN_AGE'] > 0 &&  $groupclass['MAX_AGE'] < 100) ? 'Age: '.esc_html($groupclass['MIN_AGE']).'-'.esc_html($groupclass['MAX_AGE']).'<br />' : ''); ?>
	<?php endif; ?>
	<br />
	<?php echo (($groupclass['DESCRIPTION_HTML']) ? '<br /><p>'. wp_kses($groupclass['DESCRIPTION_HTML'], 'post').'</p>' : ''); ?>
	<?php echo (($groupclass['PAGES']) ? '<div>'.esc_html(str_replace('[:pg:]','<br /><br />',$groupclass['PAGES'])).'</div>' : ''); ?>
	<?php echo (($schedule_id) ? '<input type="hidden" id="selected_schedule_id" value="'.esc_attr($schedule_id).'">' : ''); ?>
	<?php echo (($groupclass['SCHEDULE_ID'] && empty($schedule_id)) ? '<input type="hidden" id="selected_schedule_id" value="'.esc_attr($class['SCHEDULE_ID']).'">' : ''); ?>
	
	<?php foreach ($items as $student): ?>
	<?php include plugin_dir_path( __FILE__ ) . 'select-class.php'; ?>
	<?php endforeach; ?>
<?php endforeach; ?>