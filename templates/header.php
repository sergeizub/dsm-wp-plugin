<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div id="dsm_content">
<script>
	var dtp_date = 'MMM D, YYYY';
	<?php if (get_option('dsm_random_url_parameter') == '1'): ?>
	const dsm_urlParams = new URLSearchParams(window.location.search);
	if (!dsm_urlParams.has('qrnd')) {
		const dsm_qrnd = 'qrnd=' + Math.random().toString(36).substring(2,18);
		if(window.location.href.indexOf('?') != -1) {
			window.location.href = window.location.href + '&' + dsm_qrnd;
		}
		else {
			window.location.href = window.location.href + '?' + dsm_qrnd;
		}
	}
	<?php endif; ?>
</script>
