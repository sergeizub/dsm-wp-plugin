<?php
namespace DanceStudioManager;
use \DateTime;
$student = array();

$student_id = App::GetApi()->GetIdParam();

if (!empty($student_id)) {
	$student = App::GetClient()->GetController('members')->GetUser($student_id);
	if(!empty($student))
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
	<div id="<?php echo ((!empty($student_id) ? 'members_edit_'.$student_id : 'tab-members-student')); ?>" class="<?php echo (!empty($student_id) ? '' : 'tab-pane'); ?>">
	<div class="dsm-header"><h2><?php echo (!empty($student_id) ? 'Edit' : 'Create'); ?> Student</h2></div>
	<?php if (DSM_REGISTRATION_FEE_ENABLED && DSM_REGISTRATION_MAX) : ?>
        <div class="alert alert-warning text-center">
            Registering first <?php echo DSM_REGISTRATION_MAX; ?> student(s) will cost <?php echo DSM_CURRENCY_SIGN; ?><?php echo DSM_REGISTRATION_FEE; ?> each. <?php echo ((DSM_REGISTRATION_FEE_OVER_MAX == 0 && DSM_REGISTRATION_FAMILY_FEE > 0) ? 'Max family registration fee '.DSM_CURRENCY_SIGN.DSM_REGISTRATION_FAMILY_FEE : 'All other students will cost '.DSM_CURRENCY_SIGN.DSM_REGISTRATION_FEE_OVER_MAX.' each.'); ?>
        </div>
	<?php elseif (DSM_REGISTRATION_FEE_ENABLED && DSM_REGISTRATION_MAX == "0") : ?>
		<div class="alert alert-warning text-center">
            Registering students will cost <?php echo DSM_CURRENCY_SIGN; ?><?php echo DSM_REGISTRATION_FEE; ?>.
        </div>
    <?php endif; ?>
	<form class="form-horizontal" role="form" id="student-form" action="" method="post"> <?php
		foreach ($student->form as $field) {
			echo '<div class="form-group">
					<label class="col-sm-3 control-label">
					'.((isset($field->required) && $field->required == true) ? '<span style="color: red;">*</span>' : '').' 
					'.$field->label.'</label>
						<div class="col-sm-6">';
					
			switch ($field->type) {
				case "select":
					echo '<select name="'.$field->name.'" class="form-control">';
					if (is_array($field->values))
						foreach ($field->values as $v)
							echo '<option value="'.$v->value.'"
										class="form-control option"
										'.((isset($student_data[$field->name]) && $student_data[$field->name] == $v->value) ? 'selected="selected"' : '').'
										>'.$v->option.'</option>';
					echo '</select>';
				break;
				case "text-area";
					echo '<textarea class="form-control" rows="4" name="'.$field->name.'">'.(isset($student_data[$field->name]) ? $student_data[$field->name] : '').'</textarea>';
				break;
				case "date";
					$dsm_day = new DateTime(date(DSM_PHPDATE, strtotime($student_data[$field->name])));
					echo
						'<div class="input-group date">
							<input type="text" class="form-control" name="'.$field->name.'"
								value="'.((isset($student_data[$field->name]) && $student_data[$field->name] != '0000-00-00') ?  $dsm_day->format(DSM_PHPDATE) : '').'"
								'.((isset($field->required) && $field->required == true) ? 'required' : '').'
								placeholder="'.$field->label.'" readonly="readonly">
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
										name="'.$field->name.'"
										value="'.(isset($student_data[$field->name]) ? $student_data[$field->name] : '').'"
										'.((isset($field->required) && $field->required == true) ? 'required' : '').'
										placeholder="'.$field->label.'">';
			}
			echo '</div></div>';
		}
		if (!empty($student_id))
			echo '<input type="hidden" name="student_id" value="'.$student_id.'"/>';

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