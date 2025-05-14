<?php
namespace DanceStudioManager;

class Template
{
    public function __construct()
    { }
	
	public function Load($file)
	{
		 if (file_exists(plugin_dir_path( __FILE__ ) . '../templates/'. $file )) {
			$tab = App::GetClient()->GetTab();
			?>
			<?php if (!isset($_REQUEST['type']) || $_REQUEST['type'] != 'json'): ?>
			<script>
				jQuery(function() {
				<?php if ($tab): ?>
					jQuery('.nav-pills a[href="#<?php echo $tab; ?>"]').tab('show');
				<?php else: ?>
					jQuery('.nav-pills a:first').tab('show');
				<?php endif; ?>
				});
			</script>
			<div id="dsm_loading"><i class="fa fa-refresh fa-spin fa-3x"></i></div>
			<?php endif; ?>
			<?php
            load_template(plugin_dir_path( __FILE__ ) . '../templates/'. $file);
		 }
	}
}