<?php
namespace DanceStudioManager;
use \DateTime;

if ( ! defined( 'ABSPATH' ) ) exit;

$student = array();
$student_id = App::GetApi()->GetIdParam();

if (!empty($student_id)) {
	$student = App::GetClient()->GetController('members')->GetUser($student_id);
	if (!empty($student))
		$student_data = json_decode(json_encode($student->data),true);
}
else {
	$student =  App::GetClient()->GetController('members')->GetStudentForm();
}
?>
<script>
	jQuery(function() {
		InputDateInit();
	});
</script>
<?php if (is_array($student->form)) :
	?>
	<div id="<?php echo ((!empty($student_id) ? 'members_edit_'.esc_attr($student_id) : 'tab-members-student')); ?>" class="<?php echo esc_html((!empty($student_id)) ? '' : 'tab-pane'); ?>">
	<div class="dsm-header"><h2><?php echo (!empty($student_id) ? 'Edit' : 'Create'); ?> Student</h2></div>
	<?php if (DSM_REGISTRATION_FEE_ENABLED && DSM_REGISTRATION_MAX) : ?>
        <div class="alert alert-warning text-center">
            Registering first <?php echo esc_html(DSM_REGISTRATION_MAX); ?> student(s) will cost <?php echo esc_html(DSM_CURRENCY_SIGN); ?><?php echo esc_html(DSM_REGISTRATION_FEE); ?> each. <?php echo ((DSM_REGISTRATION_FEE_OVER_MAX == 0 && DSM_REGISTRATION_FAMILY_FEE > 0) ? 'Max family registration fee '.esc_html(DSM_CURRENCY_SIGN.DSM_REGISTRATION_FAMILY_FEE) : 'All other students will cost '.esc_html(DSM_CURRENCY_SIGN.DSM_REGISTRATION_FEE_OVER_MAX).' each.'); ?>
        </div>
	<?php elseif (DSM_REGISTRATION_FEE_ENABLED && DSM_REGISTRATION_MAX == "0") : ?>
		<div class="alert alert-warning text-center">
            Registering students will cost <?php echo esc_html(DSM_CURRENCY_SIGN); ?><?php echo esc_html(DSM_REGISTRATION_FEE); ?>.
        </div>
    <?php endif; ?>
	<form class="form-horizontal" role="form" id="student-form" action="" method="post"> <?php
		foreach ($student->form as $field) {
			echo '<div class="form-group">
					<label class="col-sm-3 control-label">
					'.((isset($field->required) && $field->required == true) ? '<span style="color: red;">*</span>' : '').' 
					'.esc_attr($field->label).'</label>
						<div class="col-sm-6">';
					
			switch ($field->type) {
				case "select":
					echo '<select name="'.esc_attr($field->name).'" class="form-control">';
					if (is_array($field->values))
						foreach ($field->values as $v)
							echo '<option value="'.esc_attr($v->value).'"
										class="form-control option"
										'.((isset($student_data[$field->name]) && $student_data[$field->name] == $v->value) ? 'selected="selected"' : '').'
										>'.esc_html($v->option).'</option>';
					echo '</select>';
				break;
				case "text-area";
					echo '<textarea class="form-control" rows="4" name="'.esc_attr($field->name).'">'.(isset($student_data[$field->name]) ? esc_html($student_data[$field->name]) : '').'</textarea>';
				break;
				case "date";
					$dsm_day = new DateTime(date(DSM_PHPDATE, strtotime($student_data[$field->name])));
					echo
						'<div class="input-group date">
							<input type="text" class="form-control" name="'.esc_attr($field->name).'"
								value="'.((isset($student_data[$field->name]) && $student_data[$field->name] != '0000-00-00') ?  esc_html($dsm_day->format(DSM_PHPDATE)) : '').'"
								'.((isset($field->required) && $field->required == true) ? 'required' : '').'
								placeholder="'.esc_attr($field->label).'" readonly="readonly">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>';
						unset($dsm_day);
				break;
				default :
				case "input":
					if (mb_strpos($field->name,'PASSWORD') !== false)
						echo '<input type="password" ';
					elseif  (mb_strpos($field->name,'EMAIL') !== false)
						echo '<input type="email" ';
					else
						echo '<input type="text" ';
							
					echo 'class="form-control" maxlength="32"
										name="'.esc_attr($field->name).'"
										value="'.(isset($student_data[$field->name]) ? esc_attr($student_data[$field->name]) : '').'"
										'.((isset($field->required) && $field->required == true) ? 'required' : '').'
										placeholder="'.esc_attr($field->label).'">';
			}
			echo '</div></div>';
		}
		if (!empty($student_id))
			echo '<input type="hidden" name="student_id" value="'.esc_attr($student_id).'"/>';

		echo '<input type="hidden" name="action" value="dsmclient"/>';
		echo '<input type="hidden" name="obj" value="members"/>';
		echo '<input type="hidden" name="method" value="SubmitStudent"/>';
		echo '<input type="hidden" name="boot_tab" value="tab-members-edit"/>';
		echo '<div class="form-group"><div class="col-sm-offset-3 col-sm-6">
		<button type="submit" class="btn btn-default">Save</button></div></div>';
	?> </form>
	</div>
<?php else:
App::GetError()->Show("Unable Send Api Reqest");
endif; 