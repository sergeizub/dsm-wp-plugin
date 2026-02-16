<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

$my_classes = json_decode(json_encode(App::GetClient()->GetController('members')->GetMyClasses()),true);
$user_data = json_decode(json_encode(App::GetClient()->GetController('members')->GetUserData()),true);
$related_students =  json_decode(json_encode(App::GetClient()->GetController('members')->GetChildList()),true);

$students = array();
foreach ($my_classes['classes'] as $student) {
	foreach ($student as $class) {
		$student_name = $class['STUDENT'];
		$class_id = $class['CLASS_ID'];
		if (!array_key_exists($student_name,$students)) {
			$students[$student_name] = array();
			//If parent assigned to the class
			if ($student_name == $user_data['FIRSTNAME'].' '.$user_data['LASTNAME']) {
				$students[$student_name]['item'] = $user_data;
			} else {
				//If child assigned to the class
				foreach ($related_students['family'] as $child) {
					if ($student_name == $child['FIRSTNAME'].' '.$child['LASTNAME']) 
						$students[$student_name]['item'] = $child;
				}
			}
		}
		if (!array_key_exists('classes',$students[$student_name]))
				$students[$student_name]['classes'] = array();
		
		$students[$student_name]['classes'][$class_id] = $class;	
	}
}
?>
<div class="page-header">
	<h3>Classes</h3>
</div>
<script>
function SchedulesSection(section_id)
{
	if (jQuery('#'+section_id).hasClass('hidden')) {
		jQuery('tr.schedules').addClass('hidden');
		jQuery('#'+section_id).removeClass('hidden');
	}
	else {
		jQuery('#'+section_id).addClass('hidden');
	}
}	
</script>
<?php foreach ($students as $student) : ?>
<div class="panel panel-primary">
<div class="panel-heading">
	<?php echo ( ($student['item']['GENDER'] == 'F') ? '<i class="fa fa-female"></i>&nbsp;' : ( ($student['item']['GENDER'] == 'M') ? '<i class="fa fa-male"></i>&nbsp;' : '' ) ); ?>
	<?php echo ( (!empty($student['item']['BIRTHDAY_MONTH']) && $student['item']['CURRENT_MONTH'] == $student['item']['BIRTHDAY_MONTH']) ? '<i class="fa fa-birthday-cake"></i>&nbsp;' : '' ); ?> <?php echo $student['item']['FIRSTNAME'].' '.$student['item']['LASTNAME']; ?>  
</div>
<div class="panel-body">
    <?php if (count($student['classes']) > 0) : ?>
	<table class="table table-striped">
	<thead>
    <tr>
    	<th>Class</th>
    	<th>Description</th>
		<?php if (DSM_MAIN_DISCOUNT === 'MULTI_CLASS' || DSM_MAIN_DISCOUNT === 'HOURLY_DISCOUNTS') : ?>
    	<th>Discount</th>
    	<th class="text-right">Amount, <?php echo ((defined("DSM_CURRENCY_SIGN")) ? DSM_CURRENCY_SIGN : ''); ?></th>
    	<?php endif; ?>
    	<th class="text-center"><?php echo ((defined("DSM_OC_CLASS_LIST_ACTION_LABEL")) ? DSM_OC_CLASS_LIST_ACTION_LABEL : 'Action'); ?></th>
    </tr>
	</thead>
	<tbody>
	<?php foreach($student['classes'] as $class) : ?>
    <tr data-class_id="<?php echo $class['CLASS_ID']; ?>" class="class-row">
    	<td>
			<?php echo $class['CLASS_NAME'];?>
    	<td>
			<?php echo ( (count($class['CLASS_SCHEDULES']) && $class['PAYMENT_METHOD'] === 'billing_schedule' > 0 && $class['STATUS_IN_CLASS'] == '1' &&  (DSM_MAIN_DISCOUNT === 'MULTI_CLASS' || DSM_MAIN_DISCOUNT === 'HOURLY_DISCOUNTS')) ? $class['tuition_description'] : ''); ?>
        	<?php echo ( ($class['CLASS_TYPE'] === 'private') ? 'Completed '.$class['LESSONS_COMPLETED'].' of '.$class['LESSONS_PURCHASED'].' hours. Remaining hours: '.$class['LESSONS_REMAINING'] : '' ); ?>
        </td>
    	<?php if (DSM_MAIN_DISCOUNT === 'MULTI_CLASS' || DSM_MAIN_DISCOUNT === 'HOURLY_DISCOUNTS') : ?>
    	<td>
			<?php echo ( (count($class['CLASS_SCHEDULES']) && $class['PAYMENT_METHOD'] === 'billing_schedule' > 0 && $class['STATUS_IN_CLASS'] == '1') ? $class['discount_description'] : '' ); ?>
    	</td>
    	<td class="text-right">
			<?php echo ( (count($class['CLASS_SCHEDULES']) && $class['PAYMENT_METHOD'] === 'billing_schedule' > 0 && $class['STATUS_IN_CLASS'] == '1') ? $class['tuition_amount'] : '' ); ?>
    	</td>
    	<?php endif; ?>
    	<td class="text-right">
        	<button class="btn btn-primary btn-sm" onclick="javascript:SchedulesSection('schedules_<?php echo $class['MEMBER_ID']; ?>_<?php echo $class['ID']; ?>')" title="Schedules" style="width: 130px;">
        		<i class="fa fa-calendar-day"></i> Schedules (<?php echo count($class['CLASS_SCHEDULES']); ?>)
        	</button>
    	</td>
    </tr>
    <tr id="schedules_<?php echo $class['MEMBER_ID']; ?>_<?php echo $class['ID']; ?>" class="schedules hidden class-row" data-class_id="<?php echo $class['ID']; ?>">
        <td colspan="3">
			<?php if ($class['CLASS_SCHEDULES']) : ?>
			<table class="table table-striped table-condensed table-hover">
			<tr>
				<th>Schedule</th>
				<?php if (defined('DSM_OC_HIDE_ATTENDANCE') && DSM_OC_HIDE_ATTENDANCE != '1') : ?>
				<th><?php echo ( ($class['CLASS_TYPE'] === 'private' || DSM_ATTENDANCE_STATUS_LATE === '1') ? 'Status' : 'Present' ); ?></th>
				<?php endif; ?>
				<?php echo ( ($class['PAYMENT_METHOD'] == 'sales_packages' && $class['CLASS_TYPE'] != 'private') ? '<th>Assigned Purchase</th>' : '' ); ?>
			</tr>
			<?php foreach($class['CLASS_SCHEDULES'] as $schedule) : ?>
			<tr>
				<td><?php echo $schedule['STARTF']; ?> - <?php echo $schedule['ENDF']; ?></td>
				<?php echo ( (defined('DSM_OC_HIDE_ATTENDANCE') && DSM_OC_HIDE_ATTENDANCE != '1') ? '<td id="pls_'.$schedule['ID'].'_'.$class['MEMBER_ID'].'">'.$schedule['PRESENT'].'</td>' : '' ); ?>
				<?php echo ( ($class['PAYMENT_METHOD'] == 'sales_packages' && $class['CLASS_TYPE'] != 'private') ? '<td><span class="purchase">'.$schedule['PURCHASE'].'</span></td>' : '' ); ?>
			</tr>
			<?php endforeach; ?>
			</table>
			<?php endif; ?>
        </td>	            
    </tr>
    <?php endforeach; ?>
	</tbody>
	</table>
	<?php  else: ?>
	<div class="alert alert-info">Student is not enrolled in any classes.</div>					
	<?php endif; ?>
</div>
</div>
<?php endforeach;?>