<?php
namespace DanceStudioManager;
use \DateTime;

if ( ! defined( 'ABSPATH' ) ) exit;

$member_id = App::GetApi()->GetIdParam();

if(!empty($member_id) && $member_id > 0) {
	App::GetTemplate()->Load('members-student.php');
}
else {
	$user_data = json_decode(json_encode(App::GetClient()->GetController('members')->GetUserData()),true);
	$related_students =  App::GetClient()->GetController('members')->GetChildList();
?>
<script>
	jQuery(function() {
		InputDateInit();
	});
</script>
<?php if (!empty($related_students->family) && $related_students->success) : ?>
<div class="row" style="padding-top:20px;">
  	<label class="col-sm-3 control-label text-right" style="padding-top:10px;">Related Students</label>
  	<div class="col-sm-6">
     	<ul class="list-group">
			<?php foreach ($related_students->family as $student): ?>
			<li class="list-group-item"><i class="fa fa-user"></i> <?php echo esc_html($student->FIRSTNAME); ?> <?php echo esc_html($student->LASTNAME); ?>
				<div class="pull-right">
				<?php if ($student->PARENT_ID > 0 && DSM_OC_RELATED_STUDENTS_ALLOW_EDIT_ARCHIVE == '1') : ?>
					<a href="#tab-members-edit-<?php echo esc_attr($student->ID); ?>" class="dsm_ajax_tab btn btn-primary btn-xs geturl">
				  		<i class="fa fa-pencil"></i> Edit
					</a>
					<button type="button" dsm_obj="members" dsm_method="DeleteStudent" dsm_student_id="<?php echo esc_attr($student->ID); ?>"
				    	href="#tab-members-edit"
						onclick="if (confirm('Are you sure you want to archive student?')) { dsm_ajax_click(this) };return false;"
						class="btn btn-danger btn-xs">
				  	<i class="fa fa-remove"></i> Archive
					</button>
				<?php endif; ?>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
  	</div>
</div>	
<?php endif;
$user_form =  App::GetClient()->GetController('members')->GetUserForm();
if (is_array($user_form)) : ?>
<div id="tab-members-edit" class="tab-pane">
	<div class="dsm-header"><h2>Edit Profile</h2></div>
	<form class="form-horizontal" role="form" id="members-form" action="" method="post">
	<?php
		foreach ($user_form as $field) {
			echo '<div class="form-group">
					<label class="col-sm-3 control-label">
					'.((isset($field->required) && $field->required == true) ? '<span style="color: red;">*</span>' : '').' 
					'.esc_html($field->label).'</label>
						<div class="col-sm-6">';
		
			switch ($field->type) {
				case "select":
					if($field->name == 'I_AM' && DSM_MODE != 'COMBINED')
						continue;
					echo '<select name="'.esc_attr($field->name).'" class="form-control">';
					if (is_array($field->values))
						foreach ($field->values as $v) {
							
							echo '<option value="'.esc_attr($v->value).'"
										class="form-control option"
										'.(($field->name == 'I_AM' &&  $v->value == 'adult-student' && $user_data['IS_STUDENT'] == '1' && $user_data['IS_GUARDIAN'] == '0') ? 'selected="selected"' : '').'
										'.(($field->name == 'I_AM' &&  $v->value == 'guardian' && $user_data['IS_STUDENT'] == '0' && $user_data['IS_GUARDIAN'] == '1') ? 'selected="selected"' : '').'
										'.(($field->name == 'I_AM' &&  $v->value == 'guardian-student' && $user_data['IS_STUDENT'] == '1' && $user_data['IS_GUARDIAN'] == '1') ? 'selected="selected"' : '').'
										
										'.((isset($user_data[$field->name]) && $user_data[$field->name] == $v->value) ? 'selected="selected"' : '').'
										>'.esc_html($v->option).'</option>';
						}
					echo '</select>';
				break;
				case "text-area";
					echo '<textarea class="form-control" rows="4" name="'.esc_attr($field->name).'">'.(isset($user_data[$field->name]) ? esc_textarea($user_data[$field->name]) : '').'</textarea>';
				break;
				case "date";
					$dsm_day = new DateTime($user_data[$field->name]);
					echo
						'<div class="input-group date">
							<input type="text" class="form-control" name="'.esc_attr($field->name).'"
								value="'.((isset($user_data[$field->name]) && $user_data[$field->name] != '0000-00-00') ?  esc_attr($dsm_day->format(DSM_PHPDATE)) : '').'"
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
										value="'.(isset($user_data[$field->name]) ? esc_attr($user_data[$field->name]) : '').'"
										'.((isset($field->required) && $field->required == true) ? 'required' : '').'
										placeholder="'.esc_attr($field->label).'">';
			}
			echo '</div></div>';
		}
		echo '<input type="hidden" name="action" value="dsmclient"/>';
		echo '<input type="hidden" name="obj" value="members"/>';
		echo '<input type="hidden" name="method" value="Submit"/>';
		echo '<input type="hidden" name="boot_tab" value="tab-members-edit"/>';
		echo '<div class="form-group"><div class="col-sm-offset-3 col-sm-6"><button type="submit" id="oc-members-edit-button" class="btn btn-primary">Submit Profile</button></div></div>';
	?>
	</form>
</div>
<?php else:
App::GetError()->Show("Unable Send Api Reqest");
	endif;
}