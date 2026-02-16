<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

$class_id = App::GetApi()->GetIdParam();
$schedules_list = App::GetClient()->GetController('classes')->GetClasses();	
?>
<button class="btn btn-default dsm_ajax_tab"  dsm_boot_tab="classes" >Classes</button>
<?php
foreach ($schedules_list->schedules as $k => $v) {
	foreach ($v->data as $data)
		if ($data->CLASS_ID == $class_id && $data->STATUS != '2') {
			if ($class_header != 1) : ?>
                <div class="schedule" style="border-left: 30px solid silver;">
					<h3>
						<?php echo $data->CODE; ?>
						<?php echo $data->NAME; ?>
						<?php echo $data->LEVEL; ?>
					</h3>
					<?php if (DSM_OC_CLASS_DETAILS_AGE == 1) : ?>
					<p>Age: <?php echo $data->MIN_AGE; ?> - <?php echo $data->MAX_AGE; ?></p>
					<?php endif; ?>
					<h4><?php echo $data->LOCATION; ?></h4>
				</div>
			<?php
				$class_header = '1';
			endif;
			if ($schedules_show == 'only_future' && strtotime($data->END) < time())
				continue;
			$start_date = date("M j, Y",strtotime($data->START));
			$end_date = date("M j, Y",strtotime($data->END));
			$start_time = date("g:i A",strtotime($data->START));
			$end_time = date("g:i A",strtotime($data->START));
		?>
		<div class="schedule" style="border-left: 30px solid silver;">
			<h4><?php echo $start_date; ?> <?php echo ((!empty($end_date) && $start_date != $end_date) ? '- '.$end_date : ''); ?>
				<small><?php echo ($start_time.' - '.$end_time); ?></small>
			</h4>
		</div>
		<?php
	}
}