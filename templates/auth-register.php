<?php
namespace DanceStudioManager;
use \DateTime;

if ( ! defined( 'ABSPATH' ) ) exit;

include plugin_dir_path( __FILE__ ) . 'auth-login.php';

$register_fields = App::GetClient()->GetController('auth')->GetRegisterForm();
$filters = App::GetClient()->GetController('classes')->GetFilters();

//Add Required Primary Location
if (DSM_MEMBERS_PRIMARY_LOCATION_ENABLED == '1') {
	$primary_location = (object) [
			'name' => 'PRIMARY_LOCATION',
            'type' => 'select',
			'values' =>  $filters->location,
            'required' => true,
            'label' => 'Primary Location',
	];
	
	foreach ($primary_location->values as $k_location => $location) 
		if ($k_location == "0")
			$primary_location->values[$k_location]->option = "Please Select...";
		else
			$primary_location->values[$k_location]->option = $location->label;
			
	$has_primary_location = false;	
    foreach ($register_fields as $field)
			if ($field->name == 'PRIMARY_LOCATION')
				$has_primary_location = true;
	
	if (!$has_primary_location)
		array_push($register_fields,$primary_location);
}
//Add Required Primary Location - end

$order = array_reverse (array('USERNAME', 'PASSWORD', 'PASSWORD2', 'EMAIL', 'EMAIL2', 'FIRSTNAME', 'LASTNAME', 'BIRTHDAY', 'GENDER',
							  'ADDRESS', 'ADDRESS2', 'CITY', 'STATE', 'ZIP', 'PHONE', 'PRIMARY_PHONE', 'PHONE_NOTES', 'PHONE2', 'PHONE2_NOTES', 'PHONE1', 'PHONE1_NOTES', 'PHONE3', 'PHONE3_NOTES',
							  'PRIMARY_LOCATION', 'NOTES'));

usort($register_fields, function ($a, $b) use ($order) {
    $pos_a = array_search($a->name, $order);
    $pos_b = array_search($b->name, $order);
	if($pos_a !== false && $pos_b !== false)
		return $pos_b - $pos_a;
	else if($pos_a !== false && $pos_b === false)
		return -1;
	else if($pos_a === false && $pos_b !== false)
		return 1;
	else if($pos_a === false && $pos_b === false)
		return 0;
});

$error_fields = array();
if (is_array($register_fields)) :
	?>
<div id="tab-auth-register" class="tab-pane">
	<script>
	jQuery(function() {
		InputDateInit();
	});
	</script>
	<div class="dsm-header"><h2>Create Account</h2></div>
	<form class="form-horizontal" role="form" id="members-form"  action="index.php" method="post" enctype="multipart/form-data"> <?php
		foreach ($register_fields as $k_field => $field) {
			if ($field->name == 'PASSWORD') { ?>
				<div class="form-group">
					<label class="col-sm-3 control-label"><?php echo ((isset($field->required) && $field->required == true) ? '<span style="color: red;">*</span>' : ''); ?> <?php echo esc_html($field->label); ?></label>
					<div class="col-sm-3 <?php echo ((in_array($field->name,$error_fields)) ? 'has-error' : ''); ?>">
						<input type="password" class="form-control" maxlength="32"
								name="<?php echo esc_attr($field->name); ?>"
								<?php echo ((isset($field->required) && $field->required == true) ? 'required' : ''); ?>
								placeholder="<?php echo esc_attr($field->label); ?>">
					</div>     
					<div class="col-sm-3">
						<input type="password" class="form-control" maxlength="32" name="PASSWORD2" placeholder="Repeat Password" />
		<?php } else if ($field->name == 'FIRSTNAME') { ?>
				<div class="form-group">
					<label class="col-sm-3 control-label"><?php echo ((isset($field->required) && $field->required == true) ? '<span style="color: red;">*</span>' : ''); ?> <?php echo esc_html($field->label); ?></label>
					<div class="col-sm-3 <?php echo ((in_array($field->name,$error_fields)) ? 'has-error' : ''); ?>">
						<input type="text" class="form-control" maxlength="64"
								   name="<?php echo esc_attr($field->name); ?>"
								    value="<?php echo (isset($_POST[$field->name]) ? esc_attr($_POST[$field->name]) : ''); ?>"
								   <?php echo ((isset($field->required) && $field->required == true) ? 'required' : ''); ?>
								   placeholder="<?php echo esc_attr($field->label); ?>">
					</div>     
					<div class="col-sm-3">
						<input type="text" class="form-control" maxlength="64" name="LASTNAME"  value="<?php echo (isset($_POST['LASTNAME']) ? esc_attr($_POST['LASTNAME']) : ''); ?>" placeholder="Last Name" required/>
		<?php } else if ($field->name == 'BIRTHDAY') { ?>
				<?php
					if (isset($_POST[$field->name]) && $_POST[$field->name] != '0000-00-00')
						$dsm_day = new DateTime($_POST[$field->name]);
				?>
				<div class="form-group">
					<label class="col-sm-3 control-label"><?php echo ((isset($field->required) && $field->required == true) ? '<span style="color: red;">*</span>' : ''); ?> <?php echo esc_html($field->label); ?>
					<?php if (DSM_OC_GENDER_ENABLED == "1"):?>, &nbsp;<?php echo ((DSM_OC_REQ_GENDER == '1') ? '<span style="color: red;">*</span>' : ''); ?>Gender<?php endif; ?></label>
					<div class="col-sm-3 <?php echo ((in_array($field->name,$error_fields)) ? 'has-error' : ''); ?>">
						<div class="input-group date">
							<input type="text" class="form-control" maxlength="64"
								name="<?php echo esc_attr($field->name); ?>"
								value="<?php echo ((isset($_POST[$field->name]) && $_POST[$field->name] != '0000-00-00') ?  esc_attr($dsm_day->format(DSM_PHPDATE)) : ''); ?>"
								<?php echo ((isset($field->required) && $field->required == true) ? 'required' : ''); ?>
								placeholder="<?php echo esc_attr($field->label); ?>" readonly="readonly">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
					<div class="col-sm-3">
						<?php if (DSM_OC_GENDER_ENABLED) : ?>
						<select name="GENDER" class="form-control" <?php echo ((DSM_OC_REQ_GENDER == '1') ? 'required' : ''); ?>>
							<?php if (is_array($register_fields[$k_field+1]->values)): ?>
							<?php  foreach ($register_fields[$k_field+1]->values as $v): ?>
							<option value="<?php echo esc_attr($v->value); ?>"
									class="form-control option"
									<?php echo ((isset($_POST['GENDER']) && $_POST['GENDER'] == $v->value) ? 'selected="selected"' : ''); ?>
									><?php echo esc_html($v->option); ?>
							</option>
							<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<?php endif; ?>
		<?php } else if ($field->name == 'PHONE1'
					  || ($field->name == 'PHONE2' && DSM_MEMBERS_PHONE2_ENABLED == '1')
					  || ($field->name == 'PHONE3' && DSM_MEMBERS_PHONE3_ENABLED == '1')) { ?>
				<div class="form-group">
					<label class="col-sm-3 control-label"><?php echo (( $field->name == 'PHONE1' && DSM_OC_REQ_PHONE1 == '1' || ($field->name == 'PHONE2' && DSM_OC_REQ_PHONE2 == '1')) ? '<span style="color: red;">*</span>' : ''); ?> <?php echo esc_html($field->label); ?></label>
					<div class="col-sm-3 <?php echo ((in_array($field->name,$error_fields)) ? 'has-error' : ''); ?>">
						<input type="text" class="form-control" maxlength="20" id="<?php echo esc_attr($field->name); ?>" name="<?php echo esc_attr($field->name); ?>"  value="<?php echo (isset($_POST[$field->name]) ? esc_attr($_POST[$field->name]) : ''); ?>" placeholder="<?php echo esc_attr($field->label); ?>"
							<?php echo (( $field->name == 'PHONE1' && DSM_OC_REQ_PHONE1 == '1' || ($field->name == 'PHONE2' && DSM_OC_REQ_PHONE2 == '1')) ? 'required' : ''); ?>
						>
					</div>
					<div class="col-sm-3">
						<input type="text" class="form-control" maxlength="128" name="<?php echo esc_attr($field->name); ?>_NOTES" value="<?php echo (isset($_POST[$field->name.'_NOTES']) ? esc_attr($_POST[$field->name.'_NOTES']) : ''); ?>" placeholder="Phone Notes">
		<?php } else if ($field->name == 'PASSWORD2' || $field->name == 'LASTNAME' || $field->name == 'GENDER'
					|| $field->name == 'PHONE1_NOTES' || $field->name == 'PHONE2_NOTES' || $field->name == 'PHONE3_NOTES'
					|| $field->name == 'PHONE1' || $field->name == 'PHONE2' || $field->name == 'PHONE3'
					|| ($field->name == 'EMAIL2' && DSM_MEMBERS_EMAIL2_ENABLED != '1')
					|| ($field->name == 'PRIMARY_PHONE' && DSM_OC_PRIMARY_PHONE_SELECT_ENABLED != '1')
					 ) {
				continue; 
				} else {
					echo '<div class="form-group">
						<label class="col-sm-3 control-label">
						'.((isset($field->required) && $field->required == true) ? '<span style="color: red;">*</span>' : '').' 
						'.esc_html($field->label).'</label>
						<div class="col-sm-6 '.((in_array($field->name,$error_fields)) ? 'has-error' : '').'">';
					
					switch ($field->type) {
						case "select":
							echo '<select name="'.esc_attr($field->name).'" class="form-control" '.((isset($field->required) && $field->required == true) ? 'required' : '').'>';
							if (is_array($field->values))
								foreach ($field->values as $v)
									echo '<option value="'.esc_attr($v->value).'"
										class="form-control option"
										'.((isset($_POST[$field->name]) && $_POST[$field->name] == $v->value) ? 'selected="selected"' : '').'
										>'.esc_html($v->option).'</option>';
							echo '</select>';
						break;
						case "text-area";
							echo '<textarea class="form-control" rows="4" name="'.esc_attr($field->name).'">'.(isset($_POST[$field->name]) ? esc_html($_POST[$field->name]) : '').'</textarea>';
						break;
						case "date";
							if (isset($_POST[$field->name]) && $_POST[$field->name] != '0000-00-00')
								$dsm_day = new DateTime($_POST[$field->name]);
							echo '<div class="input-group date">
									<input type="text" class="form-control" name="'.esc_attr($field->name).'"
									value="'.((isset($_POST[$field->name]) && $_POST[$field->name] != '0000-00-00') ?  esc_attr($dsm_day->format(DSM_PHPDATE)) : '').'"
									'.((isset($field->required) && $field->required == true) ? 'required' : '').'
									placeholder="'.esc_attr($field->label).'" readonly="readonly">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>';
							unset($dsm_day);
						break;
						default:
							case "input":
							if (mb_strpos($field->name,'PASSWORD') !== false)
								echo '<input type="password" ';
							elseif  (mb_strpos($field->name,'EMAIL') !== false)
								echo '<input type="email" ';
							else
								echo '<input type="text" ';
							echo 'class="form-control" maxlength="32"
										name="'.esc_attr($field->name).'"
										value="'.(isset($_POST[$field->name]) ? esc_attr($_POST[$field->name]) : '').'"
										'.((isset($field->required) && $field->required == true) ? 'required' : '').'
										placeholder="'.esc_attr($field->label).'">';
					}
				}
			echo '</div></div>';
		}
		echo '<input type="hidden" name="action" value="dsmclient"/>';
		echo '<input type="hidden" name="obj" value="auth"/>';
		echo '<input type="hidden" name="method" value="Submit"/>';
		echo '<input type="hidden" name="boot_tab" value="tab-auth-register"/>';
		echo '<input type="hidden" name="class_id" value="'.sanitize_key($_POST['class_id']).'"/>';
		echo '<input type="hidden" name="schedule_id" value="'.sanitize_key($_POST['schedule_id']).'"/>';
		echo '<input type="hidden" name="sales-item_id" value="'.sanitize_key($_POST['sales-item_id']).'"/>';
		echo '<div class="form-group"><div class="col-sm-offset-3 col-sm-6"><button type="submit" id="oc-auth-register-button" class="btn btn-primary">Create Account</button></div></div>';
	?> </form>
	</div>
<?php else:
App::GetError()->Show("Unable Send Api Reqest");
endif; 