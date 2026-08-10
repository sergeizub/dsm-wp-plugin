<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<script>
    jQuery(function () {
	jQuery('.open-signature-pad').click(function () {
		jQuery('#signature-pad_' + jQuery(this).data('id')).show();
	});

	jQuery('.pad-agree').change(function () {
		jQuery(".pad-submit-" + jQuery(this).data('id')).toggle(this.checked);
	});

	jQuery('.pad-agree').each(function() {
		jQuery(this).attr("checked", false);
	});
        initPads();
    })
</script>
<?php foreach($cart['checkout_agreements'] as $data): ?>
<?php if($data['REQUIRE_SIGNATURE'] == "0" || $data['REQUIRE_SIGNATURE'] == "2"): ?>
		<div class="col-sm-5"></div>
		<div class="col-sm-7">
			<div class="checkbox">
				<label>
					<input type="checkbox" name="checkoutagreement[]" value="<?php echo esc_attr($data['ID']); ?>"/> I have read and agree with
					"<a href="javascript:ReadAgreement('<?php echo esc_attr($data['ID']); ?>')" title="<?php echo esc_attr($data['TITLE']); ?>" class="read-agreement-link"><?php echo esc_attr($data['TITLE']); ?></a>"
				</label>
			</div>
		</div>
<?php else: ?>
	<?php if($data['SIGNED'] == "1"): ?>
			<div class="col-sm-5"></div>
			<div class="col-sm-7">
				<div class="signature_<?php echo esc_attr($data['ID']); ?> signature">
					<span>You signed "<a href="javascript:ReadAgreement('<?php echo esc_attr($data['ID']); ?>')" title="<?php echo esc_attr($data['TITLE']); ?>" class="read-agreement-link"><?php echo esc_html($data['TITLE']); ?></a>"
						<input type="hidden" name="pages[<?php echo esc_attr($data['ID']); ?>]" value="1">
						<input class="signed-waiver" id="signed-waiver-<?php echo esc_attr($data['ID']); ?>" data-title="<?php echo esc_attr($data['TITLE']); ?>" type="hidden" name="pages[<?php echo esc_attr($data['ID']); ?>]" value="1">
					</span>
				</div>
			</div>
	<?php else: ?>
			<div class="col-sm-5"></div>
			<div class="col-sm-7">
				<div class="signature_<?php echo esc_attr($data['ID']); ?> signature">
					<button type="button" class="btn btn-sm btn-primary open-signature-pad" data-id="<?php echo esc_attr($data['ID']); ?>"><i class="fa fa-pencil"></i> Sign</button>
					<button type="button" class="btn btn-sm btn-warning resign_<?php echo esc_attr($data['ID']); ?>" style="display: none">Resign</button>
					<span> By signing, I agree that I have read and agree to "<a href="javascript:ReadAgreement('<?php echo esc_attr($data['ID']); ?>')" title="<?php echo esc_attr($data['TITLE']); ?>" class="read-agreement-link"><?php echo esc_attr($data['TITLE']); ?></a>"
						<input type="hidden" name="pages[]" value="<?php echo esc_attr($data['ID']); ?>">
					</span>
					<div class="pad-wrapper" id="signature-pad_<?php echo esc_attr($data['ID']); ?>" style="display: none">
						<strong>Use your mouse or finger to draw your signature in the box.</strong>
						<div class="signature-pad--body">
							<canvas class="signature_pad" data-id="<?php echo esc_attr($data['ID']); ?>"></canvas>
						</div>
						<div class="signature-pad--footer">
							<div class="signature-pad--actions">
								<div>
									<button id="pad-clear-<?php echo esc_attr($data['ID']); ?>" data-id="<?php echo esc_attr($data['ID']); ?>" type="button" class="btn btn-info">Clear</button>
								</div>
							</div>
						</div>
						<div class="signature-checkbox">
							<label for="pad-agree-<?php echo esc_attr($data['ID']); ?>">
								<input type="checkbox" id="pad-agree-<?php echo esc_attr($data['ID']); ?>" data-id="<?php echo esc_attr($data['ID']); ?>" name="pad-agree" value="1" class="pad-agree">
								<i>By checking this box, I agree to the use of my signature for the purpose of showing that I agree to the terms.</i>
							</label>
						</div>
							<input hidden type="checkbox" id="box_<?php echo esc_attr($data['ID']); ?>" class="if-sinned-checkbox_<?php echo esc_attr($data['ID']); ?>" name="signed-checkbox" value="<?php echo esc_attr($data['TITLE']); ?>">
						<div class="pad-submit-<?php echo esc_attr($data['ID']); ?> submit-button" style="display: none">
							<button id="pad-sign-<?php echo esc_attr($data['ID']); ?>" data-id="<?php echo esc_attr($data['ID']); ?>" type="button" class="btn btn-success pad-sign">Click to Sign</button>
						<br><br><br>
						</div>
					</div>
				</div>
				<div class="signature_text_<?php echo esc_attr($data['ID']); ?> signature" style="display: none">
					<span> You signed "<a href="javascript:ReadAgreement('<?php echo esc_attr($data['ID']); ?>')" title="<?php echo esc_attr($data['TITLE']); ?>" class="read-agreement-link"><?php echo esc_html($data['TITLE']); ?></a>"</span>
					<input class="signed-waiver" id="signed-waiver-<?php echo esc_attr($data['ID']); ?>" data-title="<?php echo esc_attr($data['TITLE']); ?>" type="hidden" name="pages[<?php echo esc_attr($data['ID']); ?>]" value="0">
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<br><br><br><br>
<!-- Modal -->
<div class="modal fade" id="agreement_<?php echo esc_attr($data['ID']); ?>" tabindex="-1" role="dialog" aria-labelledby="agreement_<?php echo esc_attr($data['ID']); ?>" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo esc_html($data['TITLE']); ?></h4>
			</div>
			<div class="modal-body">
				<?php echo esc_html($data['CONTENT']); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<?php endforeach; ?>

