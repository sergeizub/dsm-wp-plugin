<?php
namespace DanceStudioManager;
use \DateTime, \DateInterval;
$filter = $classes_locations = array();

if (!empty($_REQUEST['start']))
	$filter['start'] = sanitize_text_field($_REQUEST['start']);
else
    $filter['start'] = date(DSM_PHPDATE);

$start_date = new DateTime($filter['start']);
$end_date = new DateTime($filter['start']);

if (isset($_SESSION['dsm_client_attrs']) && isset($_SESSION['dsm_client_attrs']['days_quantity']) && (int)$_SESSION['dsm_client_attrs']['days_quantity'] > 0) {
   $filter['days_quantity'] = $_SESSION['dsm_client_attrs']['days_quantity'];
} elseif(!isset($_SESSION['dsm_client_attrs']['days_quantity']))  {
    $filter['days_quantity'] = '99999'; //Unlimited
}

$end_date->add(new DateInterval('P'.$filter['days_quantity'].'D'));

$classes_list = App::GetClient()->GetController('classes')->GetClasses((array)$_SESSION['dsm_client_attrs'] + $filter);

//Group By Location
foreach ($classes_list->schedules as $day) 
    foreach($day->data as $schedule) 
        $classes_locations[$schedule->LOCATION_ID][] = $schedule;
?>
<script>
    function LocationLoadMoreSchedules(button) {
        jQuery(button).attr("disabled","disabled");
        jQuery('#dsm_loading').show();
        jQuery.post(dsmajax.url, { action : 'dsmclient', boot_tab: 'classes-list-location' , type: 'json', start: jQuery(button).attr('dsm_start_date'), class_location: jQuery(button).attr('dsm_class_location') },
            function(data) {
                jQuery(data.location_classes).each(function(index, element) {
                    let row = '<tr><td>';
                    <?php if (DSM_OC_CLASS_LIST_CLASS_ID == '1') { ?>row += '<div class="label label-default">' + element.CLASS_ID + '</div>'<?php } ?>
                    <?php if (DSM_OC_CLASS_LIST_CLASS_CODE == '1') { ?>row += element.CODE; <?php } ?>
                    <?php if (DSM_OC_CLASS_LIST_CLASS_NAME == '1') { ?>row += element.NAME; <?php } ?>
                    row += '</td><td>';
                    row += '<span style="white-space: nowrap;">' + element.DAY + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + element.START_TIME + '</span> - <span style="white-space: nowrap;">' + element.END_TIME + '</span>';
            	    row += '</td><td>';
                    if (element.M_STATUS != 'Book Now')
                        row += '<button class="btn btn-warning" type="button" style="' + (element.M_STATUS_COLOR ? 'background-color:' + element.M_STATUS_COLOR + ';' : '') + (element.M_STATUS_TEXT_COLOR ? 'color:' + element.M_STATUS_TEXT_COLOR + ';' : '') + (element.M_STATUS_BORDER_COLOR ? 'border-color:' + element.M_STATUS_BORDER_COLOR + ';' : '') + '"> <span>' + element.START_DATE + ' ' + element.M_STATUS + '</span></button>';
                    else {
                        <?php if (App::GetClient()->GetController('auth')->isLogged()): ?>
                        if (element.MAX_STUDENTS <= element.NUM_STUDENTS && <?php echo (DSM_OC_ALLOW_WAIT_LIST == "1" ? 'true' : 'false'); ?>)
                            row += '<a href="#tab-class-registration-' + element.CLASS_ID + '" dsm_class_id="' + element.CLASS_ID + '" dsm_schedule_id="' + element.ID + '" title="Add to Wait List" class="btn btn-success dsm_ajax_tab" onclick="dsm_ajax_click(this);" ><i class="fa fa-plus-circle"></i> ' + element.START_DATE + ' Add to Wait List</a>';
                        else 
                            row += '<a href="#tab-class-registration-' + element.CLASS_ID + '" dsm_class_id="' + element.CLASS_ID + '" dsm_schedule_id="' + element.ID + '" title="Register" class="btn btn-success dsm_ajax_tab" onclick="dsm_ajax_click(this);" ><i class="fa fa-plus-circle"></i> ' + element.START_DATE + ' Register</a>';
                        <?php else: ?>
                            row += '<button class="btn btn-success btn-login-alert" type="button" dsm_class_id="' + element.CLASS_ID + '" dsm_schedule_id="' + element.ID + '" onclick = "LoginAlertClick(this);"><span><i class="fa fa-plus-circle"></i> ' + element.START_DATE + ' Register</span></button>';
                        <?php endif; ?>
                    }
                    row += '</td></tr>';
                    jQuery('#table_location_' + jQuery(button).attr('dsm_class_location') + ' tbody').append(row);
                });
                jQuery(button).attr('dsm_start_date', data.end_date);
                jQuery(button).removeAttr("disabled");
                jQuery('#dsm_loading').hide();
            },
            'json'
        );
    }
</script>

<?php
foreach($classes_locations as $location_id => $schedules): ?>
    <table class="table table-borderless table_mobile_block" style="table-layout: auto;" id="table_location_<?php echo $location_id; ?>"> 
        <thead>
        <tr>
        	<th style="border-bottom:"><h3><?php echo $schedules[0]->LOCATION; ?></h3></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php 
        foreach ($schedules as $schedule): ?>
        <tr>
            <td>
            	<?php if (DSM_OC_CLASS_LIST_CLASS_ID == '1') { ?><div class="label label-default"><?php echo $schedule->CLASS_ID; ?></div><?php } ?>
            	<?php echo (DSM_OC_CLASS_LIST_CLASS_CODE == '1') ? $schedule->CODE.' ' : ''; ?>
            	<?php echo (DSM_OC_CLASS_LIST_CLASS_NAME == '1') ? $schedule->NAME.' ' : ''; ?>
            </td>
            <td><span style="white-space: nowrap;"><?php echo $schedule->DAY; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $schedule->START_TIME; ?></span> - <span style="white-space: nowrap;"><?php echo $schedule->END_TIME; ?></span></td>
            <td>
                <?php if ($schedule->M_STATUS != 'Book Now'): ?>
				<button class="btn btn-warning " type="button" style="<?php echo ((!empty($schedule->M_STATUS_COLOR)) ? 'background-color:'.$schedule->M_STATUS_COLOR.';' : ''); ?><?php echo ((!empty($schedule->M_STATUS_TEXT_COLOR)) ? 'color:'.$schedule->M_STATUS_TEXT_COLOR.';': ''); ?><?php echo ((!empty($schedule->M_STATUS_BORDER_COLOR)) ? 'border-color:'.$schedule->M_STATUS_BORDER_COLOR.';' : ''); ?>">
					<span><?php echo $schedule->START_DATE; ?> <?php echo $schedule->M_STATUS; ?></span>
				</button>
                <?php elseif (App::GetClient()->GetController('auth')->isLogged()): ?>
				<?php if ($schedule->MAX_STUDENTS <= ($schedule->NUM_STUDENTS) && DSM_OC_ALLOW_WAIT_LIST == "1"): ?>
				<a href="#tab-class-registration-<?php echo $schedule->CLASS_ID; ?>" dsm_class_id="<?php echo $schedule->CLASS_ID; ?>" dsm_schedule_id="<?php echo $schedule->ID; ?>" title="Add to Wait List" class="btn btn-success dsm_ajax_tab">
					<i class="fa fa-plus-circle"></i> <?php echo $schedule->START_DATE;?> Add to Wait List
				</a>
				<?php else: ?>
					<a href="#tab-class-registration-<?php echo $schedule->CLASS_ID; ?>" dsm_class_id="<?php echo $schedule->CLASS_ID; ?>" dsm_schedule_id="<?php echo $schedule->ID;?>" title="Register" class="btn btn-success dsm_ajax_tab">
						<i class="fa fa-plus-circle"></i> <?php echo $schedule->START_DATE; ?> Register
				    </a>
					<?php endif; ?>
					<?php else: ?>
						<button class="btn btn-success btn-login-alert" type="button" dsm_class_id="<?php echo $schedule->CLASS_ID; ?>" dsm_schedule_id="<?php echo $schedule->ID; ?>">
							<span><i class="fa fa-plus-circle"></i> <?php echo $schedule->START_DATE?> Register</span>
						</button>		
					<?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: center;">
                    <?php if ($filter['days_quantity'] != '99999'): ?>
                    <button class="btn btn-info" type="button" onclick="LocationLoadMoreSchedules(this);" dsm_class_location="<?php echo $location_id; ?>" dsm_start_date ="<?php echo $end_date->format(DSM_PHPDATE);  ?>">
						Load More
					</button>
                    <?php endif; ?>
                </td>
            </tr>
        </tfoot>
    </table>
<?php 
endforeach;