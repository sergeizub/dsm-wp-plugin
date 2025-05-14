<?php
namespace DanceStudioManager;

$waiver_id = $_POST['waiver_id'];

if (empty($waiver_id))
    exit();

$page =  App::GetClient()->GetController('members')->GetWaiver($waiver_id);
$user_data = json_decode(json_encode(App::GetClient()->GetController('members')->GetUserData()),true);
?>
<style>
    .signature-pad--body {
        margin-left: 13%;
        margin-right: 30%;
    }
    canvas {
        border: solid 1px;
    }
    .signature-pad--footer {
        margin-left: auto;
        margin-right: auto;
        text-align:center;
    }
    img {
        width: 300px;
        height: 200px;
    }
    .checkbox {
        margin-left: 2%;
        margin-right: 1%;
    }
</style>

<script>
    jQuery('.alert-danger').hide();
    var canvas = document.querySelector("canvas");
    var signaturePad = new SignaturePad(canvas);
    function resizeCanvas() {
        canvas.width = 500;
        canvas.height = 200;
        canvas.getContext("2d");
    }
    signaturePad.minWidth = 1;
    signaturePad.maxWidth = 3;
    signaturePad.penColor = "black";
    jQuery('#show').click(function () {
        jQuery('#signature-pad').show();
        jQuery('#show').hide();
        jQuery('.part-sign').show();
    });
    jQuery('#save').click(function () {
        var is_empty = signaturePad.isEmpty();
        var file = signaturePad.toDataURL();
        if (is_empty === true) {
            jQuery(".warning").append("<div class=\"alert alert-danger\">\n" +
                "                    <strong>Warning!</strong> Please sign.\n" +
                "                </div>");
        } else {
            jQuery("img").remove();
            jQuery(".img").append('<img class="img-rounded img-responsive" src="'+file+'">');
            jQuery('#signature-pad').hide();
            jQuery('#show').hide();
            jQuery('.sign').show();
            jQuery('.resign').show();
            jQuery('.checkbox').show();
        }
    });
    jQuery('#clear').click(function () {
        signaturePad.clear();
    });
    jQuery('.resign').click(function () {
        jQuery('#signature-pad').show();
        jQuery('#show').hide();
        jQuery('.sign').hide();
        jQuery('.resign').hide();
        jQuery('.checkbox').hide();
        jQuery('.warning').hide();
        signaturePad.clear();
    });
    jQuery('input[type=checkbox]').change(function () {
        var box = jQuery("input:checkbox:checked").val();
        if (box === '1') {
            jQuery('.submit').show();
        } else {
            jQuery('.submit').hide();
        }
    });
    jQuery('#pad-sign').click(function () {
            var id = jQuery(this).data('id');
            jQuery('.if-sinned-checkbox_' + id).prop('checked', true);
            var is_empty = signaturePad.isEmpty();
            if (is_empty === true) {
                 jQuery(".warning").append("<div class=\"alert alert-danger\">\n" +
                     "                    <strong>Warning!</strong> Please sign.\n" +
                     "                </div>");
            } else {
                var file = signaturePad.toDataURL();

                jQuery.ajax({
                    type: 'POST',
                    url: dsmajax.url,
                    dataType: "json",
                    data: {
                        action: "dsmclient",
                        obj: "members",
                        method: "SignWaiver",
                        signature: file,
                        id: id
                    },
                    success: function (response) {
                        dsm_ajax_click(".active .dsm_ajax_tab");
                    }
                })
            }
        });
    resizeCanvas();
</script>
<h3><?php echo $page->TITLE; ?></h3>
<div><?php echo $page->CONTENT; ?></div>
<div><strong>Participan's Name</strong></div>
<br>
<div>
    First name: <strong><?php echo $user_data['FIRSTNAME']; ?></strong><br>
    Last name: <strong><?php echo $user_data['LASTNAME']; ?></strong>
</div>
<br>
<div><strong>Participan's Signature</strong></div>
<br>
<button id="show" class="btn btn-secondary">Sign</button>
<div class="part-sign" style="display: none"></div>
<div id="signature-pad" class="signature-pad" style="display: none">
    <div class="signature-pad--body"><canvas></canvas></div>
    <br>
    <div class="warning"></div>
    <div class="signature-pad--footer">
        <div class="signature-pad--actions">
            <div>
                <button id="clear" class="btn btn-info">Clear</button>
                <button id="save" class="btn btn-success">Click to Sign</button>
            </div>
        </div>
    </div>
</div>
<div class="sign img" style="display: none"></div>
<div class="resign" style="display: none">
    <button id="resign" class="btn btn-warning">Resign</button>
</div>
<br>
<div class="sign" style="display: none">
    <strong>Electronic Signature Consent</strong>
</div>
<div class="checkbox" style="display: none">
    <div>
        <input type="checkbox" id="subscribe-<?php echo $page->ID; ?>" name="subscribe" value="1">
        <label for="subscribe-<?php echo $page->ID; ?>">
            <i>By checking here, you are consenting to the use of your electronic signature in lieu of an
			original signature on paper. You have the right to request that you sign a paper copy instead. By
			checking here, you are waiving that right. After consent, you may, upon written request to us,
			obtain a paper copy of an electronic record. No fee will be charged for such copy and no special
			hardware or software is required to view it. Your agreement to use an electronic signature with
			us for any documents will continue until such time as you notify us in writing that you no longer
			wish to use an electronic signature. There is no penalty for withdrawing your consent. You
			should always make sure that we have a current email address in order to contact you
			regarding any changes, if necessary.</i>
		</label>
    </div>
    <br>
    <div class="submit" style="display: none">
        <button type="button" id="pad-sign" data-id="<?php echo $page->ID; ?>" class="btn btn-success" >Agree To This Document</button>
    </div>
</div>