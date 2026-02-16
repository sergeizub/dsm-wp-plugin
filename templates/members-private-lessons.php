<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

$private_lessons_totals =  json_decode(json_encode(App::GetClient()->GetController('members')->GetPrivateLessonsTotals()),true);
$my_classes = json_decode(json_encode(App::GetClient()->GetController('members')->GetMyClasses()),true);

?>
<div id="tab-members-cards-accounts" class="tab-pane">
	<h2 class="page-header">Private Lessons</h2>
	<?php if (DSM_SHOW_LESSONS_TOTALS == '1'): ?>
	<table class="table table-striped table-sm lessons_totals mt-2 mb-5">
		<tr>
			<th width="30%"></th>
			<th class="text-right">Private Lessons</th>
			<th class="text-right">Coaching</th>
		</tr>
		<tr>
			<th class="text-right">Purchased <?php echo $private_lessons_totals['units']; ?></th>
			<td id="private_lessons_purchased" class="text-right"><?php echo $private_lessons_totals['private_lessons']['purchased']; ?></td>
			<td id="coaching_purchased" class="text-right"><?php echo $private_lessons_totals['coaching']['purchased']; ?></td>
		</tr>
		<tr>
			<th class="text-right">Completed <?php echo $private_lessons_totals['units']; ?></th>
			<td id="private_lessons_completed" class="text-right"><?php echo $private_lessons_totals['private_lessons']['completed']; ?></td>
			<td id="coaching_completed" class="text-right"><?php echo $private_lessons_totals['coaching']['completed']; ?></td>
		</tr>
		<tr>
			<th class="text-right">Remaining <?php echo $private_lessons_totals['units']; ?></th>
			<td id="private_lessons_remaining" class="text-right"><?php echo $private_lessons_totals['private_lessons']['remaining']; ?></td>
			<td id="coaching_remaining" class="text-right"><?php echo $private_lessons_totals['coaching']['remaining']; ?></td>
		</tr>
	</table>
	<?php endif; ?>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Student</th>
					<th>Instructor</th>
					<th>Lesson Start</th>
					<th>Duration, hours</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($my_classes['schedules'])) : ?>
			<?php foreach ($my_classes['schedules'] as $schedule) : ?>
				<tr>
					<td>
					<?php echo $schedule['data'][0]['STUDENT']; ?>
					</td>
					<td><?php echo $schedule['data'][0]['INSTRUCTOR']; ?></td>
					<td><?php echo $schedule['data'][0]['DATEF']; ?> <?php echo $schedule['data'][0]['STARTF']; ?></td>
					<td>
					<?php
						$start = strtotime($schedule['data'][0]['STARTF']);
						$end = strtotime($schedule['data'][0]['ENDF']);
						echo round(abs($end - $start) / 3600,2);
					?>
					</td>
					<td>
						<?php echo $schedule['data'][0]['STATUS']; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>