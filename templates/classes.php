<?php
namespace DanceStudioManager;

   $class_id = App::GetApi()->GetIdParam();
?>
<div id="tab-classes-calendar" class="tab-pane">
<?php
if (!empty($class_id)) :
   App::GetTemplate()->Load('class-registration.php');
else :
	include plugin_dir_path( __FILE__ ) . 'snippets/class-filters.php'; 
   if ($_SESSION['dsm_client_attrs']["start_date"] && strtotime($_SESSION['dsm_client_attrs']["start_date"]) > strtotime(DSM_PHPDATE))
	  $date_now = date(DSM_PHPDATE, strtotime(sanitize_text_field($_SESSION['dsm_client_attrs']["start_date"])));
   else
	  $date_now = date(DSM_PHPDATE);

	?>
	<script>
	
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
	var schedule_week;
	jQuery(function() {
   <?php if($_SESSION['dsm_client_attrs']["week"] == "true") : ?>
		 schedule_week = 1;
		 SchedulesList('<?php echo $date_now; ?>');
   <?php else: ?>
		 schedule_week = 0;
		 SchedulesList('<?php echo $date_now; ?>');
   <?php endif; ?>
		jQuery(document).on("click", "#today-schedules", function() {
			schedule_week = 0;
			SchedulesList('<?php echo $date_now; ?>');
		});
		
		jQuery(document).on("click", "#week-schedules", function() {
			schedule_week = 1;
			SchedulesList(moment().day(<?php echo DSM_CALENDAR_START_DAY; ?>).format('dddd, ' + window.dtp_date));
		});		
	
		jQuery(document).on("click", "#next-date", function() {
			SchedulesList(jQuery('#next-date').attr('data-start'));
		});		
	
		jQuery(document).on("click", "#prev-date", function() {
			SchedulesList(jQuery('#prev-date').attr('data-start'));
		});
	
		jQuery('body').on('focus',"#current-date input[name=currentdate]", function() {
			jQuery(this).datetimepicker({
				showClear: true,
				format: 'dddd, ' + window.dtp_date
			}).on("dp.change", function (e) {
				SchedulesList(jQuery("#current-date input[name=currentdate]").val());
			});	
		});
	});

	function SchedulesList(date)
	{
		jQuery('#dsm_loading').show();
		jQuery('#schedules-container').html('');
		
	jQuery.post(dsmajax.url, { action : 'dsmclient', boot_tab: 'classes' , type: 'json', start: date, filter: JSON.stringify(class_filter), schedule_week: schedule_week},
		function(data) 
		{
			if (data.schedules != '' && data.schedules != undefined) {
				var s = '';
				
				jQuery.each(data.schedules, function(i, v) {
					var button = '';
					var login = '1';
					
					if (v.COLOR == '') v.COLOR = 'cccccc';
					var OC_CLASS_LIST_CLASS_ID = '<?php echo DSM_OC_CLASS_LIST_CLASS_ID; ?>';
					var OC_CLASS_LIST_CLASS_CODE = '<?php echo DSM_OC_CLASS_LIST_CLASS_CODE; ?>';
					var OC_CLASS_LIST_CLASS_NAME = '<?php echo DSM_OC_CLASS_LIST_CLASS_NAME; ?>';
					var OC_CLASS_LIST_CLASS_LEVEL = '<?php echo DSM_OC_CLASS_LIST_CLASS_LEVEL; ?>';
					var OC_CLASS_LIST_CLASS_DESCRIPTION = '<?php echo DSM_OC_CLASS_LIST_CLASS_DESCRIPTION; ?>';
					var OC_CLASS_LIST_CLASS_AGE = '<?php echo DSM_OC_CLASS_LIST_CLASS_AGE; ?>';
					var OC_CLASS_LIST_AVAILABLE_SLOTS = '<?php echo DSM_OC_CLASS_LIST_AVAILABLE_SLOTS; ?>';
					var OC_CLASS_LIST_CLASS_LOCATION = '<?php echo DSM_OC_CLASS_LIST_CLASS_LOCATION; ?>';
					var OC_ALLOW_WAIT_LIST = '<?php echo DSM_OC_ALLOW_WAIT_LIST; ?>';
					
					<?php if (App::GetClient()->GetController('auth')->isLogged()) : ?>
					fclass = 'book-now dsm_ajax_tab';
               <?php else: ?>
               fclass = 'book-now btn-login-alert';
               <?php endif; ?>
					if (OC_CLASS_LIST_CLASS_ID == '1') var listid = '<div class="label label-default">' + v.CLASS_ID + '</div>'; else var listid = '';
					if (OC_CLASS_LIST_CLASS_CODE == '1' && v.CODE !== null) var code = v.CODE; else var code = '';
					if (OC_CLASS_LIST_CLASS_NAME == '1' && v.NAME !== null) var genre = v.NAME; else var genre = '';
					if (OC_CLASS_LIST_CLASS_LEVEL == '1' && v.LEVEL !== null) var level = v.LEVEL; else var level = '';
					if (OC_CLASS_LIST_CLASS_AGE == '1' && v.MIN_AGE !== null && v.MAX_AGE !== null ) var age = '<p>' + v.MIN_AGE + ' - ' + v.MAX_AGE + '</p>'; else var age = '';
					if (OC_CLASS_LIST_CLASS_DESCRIPTION == '1' && v.DESCRIPTION !== null) var description = '<p>' + v.DESCRIPTION + '</p>'; else var description = '';
					var av_slots = v.MAX_STUDENTS - v.STUDENTS_QUANTITY;
					if (av_slots < 0) av_slots = 0;
					var slots = '<p>' + av_slots + ' out of ' + v.MAX_STUDENTS + ' slots available</p>';
					if (OC_CLASS_LIST_AVAILABLE_SLOTS == '1') var slots = '<p>' + av_slots + ' out of ' + v.MAX_STUDENTS + ' slots available</p>'; else var slots = '';
					if (OC_CLASS_LIST_CLASS_LOCATION == '1' && v.LOCATION !== null) var location = '<h4>' + v.LOCATION + '</h4>'; else var location = '';
					
					if (v.M_STATUS == 'Cancelled') {
                        button = '<div class="btn btn-danger text-center">Cancelled</div>';
                    }
					else if (v.MEMBER_SCHEDULE_STATUS == '0') {
						button = '<div class="alert alert-success text-center">Booked</div>';
					}					
					else if (v.MEMBER_SCHEDULE_STATUS == '1') {
						button = '<div class="alert alert-success text-center">Attended</div>';
					}					
					else if (v.MEMBER_SCHEDULE_STATUS == '5') {
						button = '<div class="alert alert-warning text-center">Waitlisted</div>';
					}
					else if (parseInt(v.STUDENTS_QUANTITY) >= parseInt(v.MAX_STUDENTS)) {
						if (OC_ALLOW_WAIT_LIST == '1' && v.WAIT_LIST == '1')
							button = '<button class="btn btn-warning btn-lg ' + fclass + '" href="#tab-class-registration-' + v.CLASS_ID + '" dsm_schedule_id="'+v.ID+'" title="Wait List">Wait List</a>';
						else
							button = '<div class="alert alert-warning text-center">Full</div>';
					}
					else {
						button = '<a class="btn btn-success btn-lg ' + fclass + '" href="#tab-class-registration-' + v.CLASS_ID + '"  dsm_schedule_id="'+v.ID+'" title="Book Now">Book Now</a>';
					}

					s += '<div class="schedule" style="border-left: 30px solid #' + v.COLOR + ';"><h4>' + v.START_DATE + '  <small>' + v.START_TIME + ' - ' + v.END_TIME + '</small></h4>'+
					'<div class="row"><div class="col-md-9"><a href="#tab-classes" class="dsm_ajax_tab" dsm_obj="classes" dsm_method="GetInfo" dsm_class_id="' + v.CLASS_ID + '" ><h3>'+ listid + ' ' + code + ' ' + genre + ' ' + level + 
					'</h3></a></div><div class="col-md-3 text-right">' + button +
					'</div></div><p></p>' + description +  slots + location + '</div>';
				});
		    }
		    else
			  s ='<div class="alert alert-warning">There are no classes on ' + data.current_date + '</div>';
		    
		    jQuery('#schedules-container').html(s);
		    
		    if (data.prev_date != '' && data.prev_date != undefined)
		    	jQuery('#prev-date').attr('data-start', data.prev_date);
		    if (data.next_date != '' && data.next_date != undefined)				
				jQuery('#next-date').attr('data-start', data.next_date);
		    if (data.current_date != '' && data.current_date != undefined)
				jQuery('#current-date input[name=currentdate]').val(data.current_date);
			
			jQuery('#dsm-tab-content .dsm_ajax_tab').on('click', function(e) {
				e.preventDefault();
				dsm_ajax_click(this);
			});
			
			jQuery('#dsm_loading').hide();
        }, 
        'json'
    );	
}
</script>
<style>
<?php if($_SESSION['dsm_client_attrs']["week"] == "true"): ?>
#current-date {
	min-width: 60%;
}
<?php endif; ?>
</style>

	<div class="panel panel-default ">
		<div class="panel-heading">
			<h3 class="panel-title text-center">
			   <?php if($_SESSION['dsm_client_attrs']["week"] == "true") : ?>
				  <a href="#" id="week-schedules" class="btn btn-default pull-left hidden-xs" style="margin-right:12px;" data-start="">Week</a>
			   <?php endif; ?>
				  <a href="#" id="today-schedules" class="btn btn-default pull-left hidden-xs" data-start="">Today</a>
				<a href="#" id="prev-date" class="btn btn-default" data-start=""><i class="fa fa-caret-left"></i></a>
				<span id="current-date" class="input-group" title="Click to change date">
					<input type="text" class="form-control" name="currentdate" value="" style="">
				</span>
				<a href="#" id="next-date" class="btn btn-default" data-start=""><i class="fa fa-caret-right"></i></a>
			</h3>
		</div>
		<div class="panel-body" id="schedules-container"></div>
	</div>
	<?php endif; ?>
</div>