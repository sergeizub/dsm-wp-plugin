<?php
namespace DanceStudioManager;
?>
<?php include plugin_dir_path( __FILE__ ) . '../snippets/members-charges.php'; ?>
<?php if (!defined('DSM_OC_LEDGER_SHOW_PAYMENTS') || DSM_OC_LEDGER_SHOW_PAYMENTS == "1"): ?>
<br/><br/>
<?php include plugin_dir_path( __FILE__ ) . '../snippets/members-payments.php'; ?>
<?php endif; ?>
