<?php
namespace DanceStudioManager;

if (!$filters)
	$filters = App::GetClient()->GetController('classes')->GetFilters();

//Sanitize Dsm Client Attrs
if (is_array($_SESSION['dsm_client_attrs']))
	foreach($_SESSION['dsm_client_attrs'] as $k_att => $att)
		if (is_array($att))
			foreach($att as $k => $v)
				$_SESSION['dsm_client_attrs'][$k_att][$k] = sanitize_text_field($v);
		else
			$_SESSION['dsm_client_attrs'][$k_att] = sanitize_text_field($att);
?>
<script>
	jQuery(function() {
		var hide_names = '<?php echo DSM_OC_CLASS_FILTER_NAME_HIDDEN_IDS; ?>';
		if (hide_names != '') {		
			var hn = hide_names.split(',');
			jQuery.each(hn, function( index, value ) {
				jQuery('select[name=class_name] option').each(function() {	
				    if (jQuery(this).val() == value)
				        jQuery(this).remove();
				});
			});
		}
		
		var hide_programs = '<?php echo DSM_OC_CLASS_FILTER_PROGRAM_HIDDEN_IDS; ?>';
		if (hide_programs != '') {
			var hp = hide_programs.split(',');
			jQuery.each(hp, function( index, value ) {
				jQuery('select[name=class_program] option').each(function() {	
				    if (jQuery(this).val() == value)
				        jQuery(this).remove();
				});
			});
		}
	});
</script>
<form method="post"  action="index.php" id="schedule-filter" class="form-inline">
<?php if (DSM_OC_USE_CLASSES_LIST_VIEW == "1" && $_SESSION['dsm_client_attrs']['view'] != "Calendar") : ?>
		<?php if (DSM_OC_CLASS_LIST_TYPE == 'list_by_program' || DSM_OC_CLASS_LIST_TYPE == 'list_by_program_table' || $_SESSION['dsm_client_attrs']['view'] == "List" ) : ?>
			<input type="hidden" name="boot_tab" value="tab-classes-list" />
		<?php else : ?>
			<input type="hidden" name="boot_tab" value="tab-classes" />
		<?php endif; ?>
<?php else : ?>
	<?php echo '<input type="hidden" name="boot_tab" value="tab-classes-calendar" />';?>
<?php endif; ?>
	<input type="hidden" name="action" value="dsmclient"/>
<?php if ($_SESSION['dsm_client_attrs'] && $_SESSION['dsm_client_attrs']['class_code']) : ?>
		<input type="hidden" id="filter_class_code" name="filter[class_code]" value="<?php echo $_SESSION['dsm_client_attrs']['class_code']; ?>" />
<?php endif; ?>
<?php if ($_SESSION['dsm_client_attrs'] && $_SESSION['dsm_client_attrs']['class_name'] && $filters->name) : ?>
	<?php foreach ($filters->name as $name) : ?>
		 <?php echo (($name->label == $_SESSION['dsm_client_attrs']['class_name']) ? '<input type="hidden" id="filter_class_name" name="filter[class_name]" value="'.$name->value.'"/>' : ''); ?>
	<?php endforeach; ?>
<?php elseif (DSM_OC_CLASS_FILTER_NAME == '1' &&  !$_SESSION['dsm_client_attrs']['class_code']) : ?>
<div class="form-group">
	<select name="filter[class_name]" id="filter_class_name" class="form-control">
		<?php foreach ($filters->name as $name) : ?>
			<option value="<?php echo $name->value; ?>" <?php echo (($name->value == $_POST['filter']['class_name']) ? 'selected="selected"' : ''); ?>><?php echo $name->label; ?></option>
		<?php endforeach; ?>
	</select>
</div>
<?php endif; ?>
<?php if ($_SESSION['dsm_client_attrs'] && $_SESSION['dsm_client_attrs']['class_level'] && $filters->level) : ?>
	<?php foreach ($filters->level as $level) : ?>
		 <?php echo (($level->label == $_SESSION['dsm_client_attrs']['class_level']) ? '<input type="hidden" id="filter_class_level" name="filter[class_level]" value="'.$level->value.'"/>' : ''); ?>
	<?php endforeach; ?>
<?php elseif ((DSM_OC_CLASS_FILTER_LOCATION == '1' || $_SESSION['dsm_client_attrs']['show_location_filter']) &&  !$_SESSION['dsm_client_attrs']['class_code']) : ?>
<div class="form-group">
	<select name="filter[class_level]" id="filter_class_level" class="form-control">
		<?php foreach ($filters->level as $level) : ?>
			<option value="<?php echo $level->value; ?>" <?php echo (($level->value == $_POST['filter']['class_level']) ? 'selected="selected"' : ''); ?>><?php echo $level->label; ?></option>
		<?php endforeach; ?>
	</select>
</div>
<?php endif; ?>
<?php if ($_SESSION['dsm_client_attrs'] && $_SESSION['dsm_client_attrs']['class_location'] && $filters->location) : ?>
	<?php foreach ($filters->location as $location) : ?>
		 <?php echo (($location->label == $_SESSION['dsm_client_attrs']['class_location']) ? '<input type="hidden" id="filter_class_location" name="filter[class_location]" value="'.$location->value.'"/>' : ''); ?>
	<?php endforeach; ?>
<?php elseif (DSM_OC_CLASS_FILTER_LOCATION == '1' &&  !$_SESSION['dsm_client_attrs']['class_code']) : ?>
<div class="form-group">
	<select name="filter[class_location]" class="form-control" id="filter_class_location">
		<?php foreach ($filters->location as $location) : ?>
			<option value="<?php echo $location->value; ?>" <?php echo (($location->value == $_POST['filter']['class_location']) ? 'selected="selected"' : ''); ?>><?php echo $location->label; ?></option>
		<?php endforeach; ?>	
	</select>
</div>
<?php endif; ?>
<?php if ($_SESSION['dsm_client_attrs'] && $_SESSION['dsm_client_attrs']['class_program'] && $filters->program) : ?>
	<?php foreach ($filters->program as $program) : ?>
		 <?php echo (($program->label == $_SESSION['dsm_client_attrs']['class_program']) ? '<input type="hidden" id="filter_class_program" name="filter[class_program]" value="'.$program->label.'"/>' : ''); ?>
	<?php endforeach; ?>
<?php elseif (DSM_OC_CLASS_FILTER_PROGRAM == '1' && !$_SESSION['dsm_client_attrs']['class_code']) : ?>
<div class="form-group">
	<select name="filter[class_program]" class="form-control" id="filter_class_program">
		<?php foreach ($filters->program as $program) : ?>
			<option value="<?php echo (($program->value) ? $program->label : ""); ?>" <?php echo (($program->label == $_POST['filter']['class_program']) ? 'selected="selected"' : ''); ?>><?php echo $program->label; ?></option>
		<?php endforeach; ?>
	</select>
</div>
<?php endif; ?>
</form>
<br/>