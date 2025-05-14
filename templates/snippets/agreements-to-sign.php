<script>
    jQuery(function () {
	    jQuery(document).on('click', '.open-signature-pad_<?php echo $w_data->ID; ?>', function() {
            var signatureBox = jQuery('.signature_<?php echo $w_data->ID; ?>').children();
            jQuery(signatureBox[3]).show();
            jQuery('input[type=checkbox]').prop('checked', false);
        });

        var canvas = document.getElementsByTagName("canvas");
        for (var i = 0; i < canvas.length; i++) {
            const signaturePad = new SignaturePad(canvas[i]);
            function resizeCanvas() {
                canvas[i].width = 540;
                canvas[i].height = 300;
                canvas[i].getContext("2d");
            }
            signaturePad.minWidth = 1;
            signaturePad.maxWidth = 3;
            signaturePad.penColor = "black";
            resizeCanvas();
			jQuery(document).on('click', '#clear_<?php echo $w_data->ID; ?>', function() {
                signaturePad.clear();
            });
			jQuery(document).on('click', '#save_<?php echo $w_data->ID; ?>', function() {
                var is_empty = signaturePad.isEmpty();
                var file = signaturePad.toDataURL();
                if (is_empty === true) {
                    jQuery(".warning").append("<div class=\"alert alert-danger col-sm-9\">\n" +
                        "                    <strong>Warning!</strong> Please sign.\n" +
                        "                </div>");
                } else {
                    jQuery.ajax({
                        url: 'index.php',
                        type: 'POST',
                        data: {
                            'file': file,
                            'page': <?php echo $w_data->ID; ?>
                        },
                        success: function () {
                            var signatureBox = jQuery('.signature_<?php echo $w_data->ID; ?>').children();
                            jQuery(signatureBox[3]).hide();
                            jQuery('.open-signature-pad_<?php echo $w_data->ID; ?>').hide();
                            jQuery('.resign_<?php echo $w_data->ID; ?>').show();
                            jQuery('.submit-button').hide();
                        }
                    })
                }
            });

            jQuery(document).on('click', '.resign_<?php echo $w_data->ID; ?>', function() {
                var signatureBox = jQuery('.signature_<?php echo $w_data->ID; ?>').children();
                jQuery(signatureBox[1]).show();
                signaturePad.clear();
                jQuery('input[type=checkbox]').prop('checked', false);
                jQuery('.submit_<?php echo $w_data->ID; ?>').hide();
            });

            jQuery(document).on('change', 'input[type=checkbox]', function() {
                var box = jQuery("input:checkbox:checked").val();
                if (box === '1') {
                    jQuery('.submit_<?php echo $w_data->ID; ?>').show();
                } else {
                    jQuery('.submit_<?php echo $w_data->ID; ?>').hide();
                }
            });

			jQuery( "*[type='submit']" ).click(function() {
                var box = jQuery("input:checkbox:checked").val();
				var file = signaturePad.toDataURL();
                if (box === '1') {
                   jQuery('#input_signature_<?php echo $w_data->ID; ?>').val(file);

                } else {
					jQuery('#input_signature_<?php echo $w_data->ID; ?>').val('');
                }
            });
        }
    })
</script>
<div class="col-sm-offset-3 col-sm-9">
    <div class="signature_<?php echo $w_data->ID; ?> signature">
        <button type="button" class="btn btn-xs btn-primary open-signature-pad_<?php echo $w_data->ID; ?>">Sign</button>
        <button type="button" class="btn btn-xs btn-warning resign_<?php echo $w_data->ID; ?>" style="display: none">Resign</button>
        <span> Please sign "<a href="#" onclick="javascript:jQuery('#myWaiver').modal('show');return false;" title="<?php echo $w_data->TITLE; ?>" ><?php echo $w_data->TITLE; ?></a>"
            <input type="hidden" name="pages[]" value="<?php echo $w_data->ID; ?>">
		    <input type="hidden" class="input_signature" id="input_signature_<?php echo $w_data->ID; ?>" name="signature[<?php echo $w_data->ID; ?>]" value="">
        </span>
        <div id="signature-pad_<?php echo $w_data->ID; ?>" style="display: none">
            <strong>Use your mouse or finger to draw your signature in the box.</strong>
            <div class="signature-pad--body">
                <canvas></canvas>
            </div>
            <div class="signature-pad--footer">
                <div class="signature-pad--actions">
                    <div>
                        <button id="clear_<?php echo $w_data->ID; ?>" type="button" class="btn btn-info">Clear</button>
                    </div>
                </div>
            </div>
            <div class="signature-checkbox">
                <label for="subscribe-<?php echo $w_data->ID; ?>"><input type="checkbox" id="subscribe-<?php echo $w_data->ID; ?>" name="subscribe[<?php echo $w_data->ID; ?>]" value="1"><i> By
                            checking this box, I agree to the use of my signature for the purpose of showing that I
                            agree to the terms.</i>
                </label>
            </div>
            <br>
            <div class="submit_<?php echo $w_data->ID; ?> submit-button" style="display: none">
                <button id="save_<?php echo $w_data->ID; ?>" type="button" class="btn btn-success">Click to Sign</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myWaiver" tabindex="-1" role="dialog" aria-labelledby="myWaiver" aria-hidden="true">
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo $w_data->TITLE; ?></h4>
			</div>
			<div class="modal-body">
				<?php echo $w_data->CONTENT; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->