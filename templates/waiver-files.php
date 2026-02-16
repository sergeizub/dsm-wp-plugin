<?php 
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

$waivers =  App::GetClient()->GetController('members')->GetWaivers();
?>
<div class="page-header">
    <h3>Waivers</h3>
</div>
<div class="table-responsive">
    <table class="table table-striped table-condensed table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>File</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($waivers && $waivers->data) : ?> 
        <?php foreach($waivers->data as $p) : ?> 
            <tr>
                <td>
                    <?php if (!$p->FILE): ?>
                    <a href="javascript:ReadAgreement('<?php echo $p->ID; ?>')" title="<?php echo $p->TITLE;?>" class="read-agreement-link">
                    <?php endif; ?>
                    <?php echo $p->TITLE;?>
                    <?php if (!$p->FILE): ?></a><?php endif; ?>
                </td>
                <td><?php if ($p->FILE): ?><a href="<?php echo get_option('dsm_api_url'); ?>/assets/uploads/waivers/<?php echo $p->FILE; ?>" target="_blank"><?php echo $p->FILE; ?></a><?php else: ?>agreed <?php endif; ?></td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td class="text-center" colspan="3">No records found</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>