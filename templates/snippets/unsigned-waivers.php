<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

if (App::GetClient()->GetController('auth')->isLogged()) :
$unsigned_waivers = array();
$waivers =  App::GetClient()->GetController('members')->GetWaivers();

if (!empty($waivers->data))
    foreach($waivers->data as $waiver) {
        if (empty($waiver->SIGNED))
            array_push($unsigned_waivers, $waiver);
    }
    if (!empty($unsigned_waivers)):
?>
<script>
    jQuery(function() {
        ValidateWaivers()
    });
</script>
<div class="row">
    <div class="text-right" id="action-required-button">
		<a class="btn btn-warning btn-lg" data-toggle="collapse" href="#unsigned-waivers-container" role="button" aria-expanded="false" aria-controls="unsigned-waivers-container"><i class="fa fa-exclamation-triangle"></i> Agreement Required</a>
	</div>
    <br/>
    <div id="unsigned-waivers-container" class="collapse">
        <?php foreach($unsigned_waivers as $waiver): ?>
           <div class="alert alert-danger">
                <strong>Agreement Required!</strong>
                <br/>Please click the button below in order to review and sign the agreements <?php echo $waiver->TITLE; ?>
				<br/><br/>
                <p class="text-right">
                    <button type="button" class="btn btn-success dsm_ajax_tab" dsm_boot_tab="waiver-sign" dsm_waiver_id="<?php echo $waiver->ID; ?>">Review & Sign</button>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
    <br/><br/>
</div>
    <?php endif; ?>
<?php endif; ?>