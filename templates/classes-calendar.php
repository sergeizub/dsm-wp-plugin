<?php
	namespace DanceStudioManager;
	$class_id = App::GetApi()->GetIdParam();
?>
<div id="tab-classes-calendar" class="tab-pane">
<?php
if (!empty($class_id)) :
	App::GetTemplate()->Load('class-registration.php');
else :
?>
<script>
jQuery(function() {
	if (!localStorage.getItem('cal_offset'))
		localStorage.setItem('cal_offset', moment().format('YYYY-MM-DD'));
		
	jQuery('#dsm_calendar').fullCalendar({
		header: {
			left: 'prev next', 
			center: 'title',
			right: 'month,agendaWeek'
		},
		defaultView: '<?php echo ((defined('DSM_OC_DEFAULT_CALENDAR_VIEW')) ? DSM_OC_DEFAULT_CALENDAR_VIEW : 'agendaDay' ); ?>',
        height: 'auto',
		allDaySlot: false,
		slotMinutes: 15,
		timeFormat: '<?php echo DSM_CALENDARTIME; ?>',
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
                },
                error: function() {
                    alert('There was an error while fetching schedules!');
                }
            }
	    ],
		eventRender: function(event, element, calEvent) {
			element.attr('dsm_schedule_id',event.schedule_id);
			element.attr('dsm_class_id',event.class_id);
			element.attr('href','#tab-classes');
			element.attr('dsm_obj','classes');
			element.attr('dsm_method','GetInfo');
			element.attr('dsm_classes_view','classes-calendar');
		},
        eventAfterAllRender: function () {
	        var d = jQuery('#dsm_calendar').fullCalendar('getDate');
            localStorage.setItem('cal_offset', d.format('YYYY-MM-DD'));
        },
		eventClick: function(calEv, jsEv) {
			jsEv.preventDefault();
			if (navigator.onLine) {
				dsm_ajax_click(this);
			}
		}
		
	});
});
</script>

<div id="dsm_calendar" ></div>
<?php endif; ?>
</div>