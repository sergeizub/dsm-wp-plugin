<?php
	namespace DanceStudioManager;
	$class_id = App::GetApi()->GetIdParam();
?>
<div id="tab-classes-calendar" class="tab-pane">
<?php
if (!empty($class_id)) :
	App::GetTemplate()->Load('class-registration.php');
else :
	include plugin_dir_path( __FILE__ ) . 'snippets/unsigned-waivers.php';
	include plugin_dir_path( __FILE__ ) . 'snippets/class-filters.php'; 
?>
<div class="class-container" data-unsigned-waivers-allow="<?php echo DSM_OC_UNSIGNED_WAIVERS_CLASS_REG; ?>">
<script>
jQuery(function() {
	var class_filter = new Object();
	
	if (jQuery("#filter_class_code").length && jQuery("#filter_class_code").val() != "0" && jQuery("#filter_class_code").val() != "") {
       class_filter.class_code = jQuery("#filter_class_code").val();
    }
	if (jQuery("#filter_class_name").length && jQuery("#filter_class_name").val() != "0" && jQuery("#filter_class_name").val() != "") {
       class_filter.class_name = jQuery("#filter_class_name").val();
    }
	if (jQuery("#filter_class_level").length && jQuery("#filter_class_level").val() != "0" && jQuery("#filter_class_level").val() != "") {
       class_filter.class_level = jQuery("#filter_class_level").val();
    }
	if (jQuery("#filter_class_location").length && jQuery("#filter_class_location").val() != "0" && jQuery("#filter_class_location").val() != "") {
       class_filter.class_location = jQuery("#filter_class_location").val();
    }
	if (jQuery("#filter_class_program").length  && jQuery("#filter_class_program").val() != "0" && jQuery("#filter_class_program").val() != "") {
       class_filter.class_program = jQuery("#filter_class_program").val();
    }

	if (!localStorage.getItem('cal_offset'))
		localStorage.setItem('cal_offset', moment().format('YYYY-MM-DD'));
		
	jQuery('#dsm_calendar').fullCalendar({
		header: {
			left: 'prev next', 
			center: 'title',
			right: 'month, agendaWeek, listWeek, agendaDay'
		},
		height: 'auto',
		allDaySlot: false,
		slotMinutes: 15,
		timeFormat: '<?php echo DSM_CALENDARTIME; ?>',
		views: { listWeek: { buttonText: 'list week' } },
        minTime: '<?php echo ((DSM_CALENDAR_START_TIME) ? DSM_CALENDAR_START_TIME  : '6:00'); ?>',
        maxTime: '<?php echo ((DSM_CALENDAR_END_TIME) ? DSM_CALENDAR_END_TIME  : '24:00'); ?>',
		editable: false,
		defaultDate: localStorage.getItem('cal_offset'),					
		loading: function(bool) {
			if (bool) jQuery('#dsm_loading').show();
			else jQuery('#dsm_loading').hide();
		},
		eventSources: [
            {
                url: dsmajax.url,
                type: 'POST',
                data: {
                    action : 'dsmclient',
                    boot_tab: 'classes-calendar',
					type: 'json',
					filter: JSON.stringify(class_filter),
                },
                error: function() {
                    alert('There was an error while fetching schedules!');
                }
            }
	    ],
		eventRender: function(event, element, calEvent) {
			if (event.class_id) {
				element.attr('href','#tab-class-registration-' + event.class_id);
				element.attr('dsm_schedule_id',event.schedule_id);
				element.attr('dsm_class_id',event.class_id);
				element.attr('dsm_obj','classes');
				element.attr('dsm_method','GetInfo');
				element.attr('dsm_classes_view','classes-calendar');
			}
		},
        eventAfterAllRender: function () {
	        var d = jQuery('#dsm_calendar').fullCalendar('getDate');
            localStorage.setItem('cal_offset', d.format('YYYY-MM-DD'));
        },
		eventClick: function(calEv, jsEv) {
			jsEv.preventDefault();
			if (navigator.onLine && jQuery(this).attr('dsm_class_id') > 0) {
				dsm_ajax_click(this);
			}
		}
		
	});
});
</script>
	<div id="dsm_calendar" ></div>
</div>
<?php endif; ?>
</div>
