<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

?>
<table class="table table-borderless table_mobile_block" style="table-layout: auto;">
    <thead>
        <tr>
        	<th>Class</th>
            <?php if (DSM_OC_CLASS_LIST_CLASS_AGE == '1') { ?><th class="text-center">Age</th><?php } ?>
            <?php if (DSM_OC_CLASS_LIST_CLASS_LEVEL == '1') { ?><th class="text-center">Level</th><?php } ?>
            <?php if (DSM_OC_CLASS_LIST_CLASS_LOCATION == '1') { ?><th class="text-center">Location</th><?php } ?>
			<?php if (DSM_OC_CLASS_LIST_CLASS_DAY_TIME == '1') { ?><th class="text-center">Day</th><?php } ?>
			<?php if (DSM_OC_CLASS_LIST_CLASS_INSTRUCTOR == '1') { ?><th class="text-center">Instructor</th><?php } ?>                    
            <?php if (DSM_OC_CLASS_LIST_CLASS_DATES == '1') { ?><th>Dates</th><?php } ?>
            <?php if (DSM_OC_CLASS_LIST_CLASS_PRICE == '1') { ?><th style="min-width:90px;" class="text-left">Pricing</th><?php } ?>
			<?php if (DSM_OC_CLASS_LIST_TYPE != 'list_by_program' && DSM_OC_CLASS_LIST_TYPE != 'list_by_program_table') { ?><th>Schedules</th><?php } else { ?><th></th><?php } ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($item as $class): ?>
		<?php //if ($class->PAYMENT_METHOD != "sales_packages") : ?>
		<?php  //continue ; ?>
		<?php //endif; ?>
        <tr>
			<td>
				<h4>
            	<?php if (DSM_OC_CLASS_LIST_CLASS_ID == '1') { ?><div class="label label-default"><?php echo $class->ID; ?></div><?php } ?>
            	<?php echo (DSM_OC_CLASS_LIST_CLASS_CODE == '1') ? $class->CODE.' ' : ''; ?>
            	<?php echo (DSM_OC_CLASS_LIST_CLASS_NAME == '1') ? $class->NAME.' ' : ''; ?>
				</h4>
			</td>
            <?php if (DSM_OC_CLASS_LIST_CLASS_AGE == '1') { ?><td class="text-center"><?php echo $class->MIN_AGE; ?> - <?php echo $class->MAX_AGE; ?></td><?php } ?>
            <?php if (DSM_OC_CLASS_LIST_CLASS_LEVEL == '1') { ?><td class="text-center"><?php echo $class->LEVEL; ?></td><?php } ?>
            <?php if (DSM_OC_CLASS_LIST_CLASS_LOCATION == '1') { ?><td class="text-center"><h4><?php echo $class->LOCATION; ?></h4></td><?php } ?></h4>  
			<?php if (DSM_OC_CLASS_LIST_CLASS_DAY_TIME == '1') { ?>
			<td class="text-right">
				<?php echo $class->DAY_OF_WEEK;
					if (isset($class->SCHEDULES) && is_array($class->SCHEDULES))
						echo ", ".$class->SCHEDULES[0]->START_TIME.' - '.$class->SCHEDULES[0]->END_TIME; 
				?>
            </td>
            <?php } ?>
			<?php if (DSM_OC_CLASS_LIST_CLASS_INSTRUCTOR == '1') { ?>
            <td class="text-center">
                <?php echo $class->INSTRUCTOR; ?>
            </td>
            <?php } ?>
            <?php if (DSM_OC_CLASS_LIST_CLASS_DATES == '1') { ?>
                <td>
                    <span style="white-space: nowrap;"><?php echo $class->CLASS_START; ?></span> - <span style="white-space: nowrap;"><?php echo $class->CLASS_END; ?></span>
            	</td>
            <?php } ?>
            <?php if (DSM_OC_CLASS_LIST_CLASS_PRICE == '1') { ?>
                <td>
                <?php if ($class->PAYMENT_METHOD == "sales_packages") : ?>
                    <ul class="gc list-unstyled">
			            <li><b><?php echo DSM_CURRENCY_SIGN; ?><?php echo $class->SALES_ITEM_PRICE; ?></b> <?php echo $class->SALES_ITEM; ?></li>
                    </ul>
                <?php else: ?>
                <?php echo $class->PRICING; ?>
                <?php if (DSM_OC_CLASS_REG_FEE_ENABLED == '1' && $class->REG_FEE > 0 ) { ?>
						<br /><small>(<?php echo DSM_OC_CLASS_REG_FEE_LABEL; ?> <?php echo DSM_CURRENCY_SIGN; ?><?php echo $class->REG_FEE; ?>)</small>
				<?php } ?>
                <?php endif; ?>
	            </td>
            <?php } ?>
				<td>
			<?php if ( (DSM_OC_CLASS_LIST_TYPE == 'list_by_program' || DSM_OC_CLASS_LIST_TYPE == 'list_by_program_table' || $_SESSION['dsm_client_attrs']['view'] == "List") && $class->PAYMENT_METHOD == "sales_packages") :?>
					<table class="table table-borderless" style="table-layout: auto;">
						<thead>
							<tr>
								<th>Day</th>
								<th>Time</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($class->SCHEDULES as $schedule): ?>
                        <?php if ($schedule->STATUS != '2'): ?>      
							<tr>
								<td><span style="white-space: nowrap;"><?php echo $schedule->DAY; ?></span></td>
								<td><span style="white-space: nowrap;"><?php echo $schedule->START_TIME; ?></span> - <span style="white-space: nowrap;padding: 12px;"><?php echo $schedule->END_TIME; ?></span></td>
								<td>
								<?php if ($schedule->M_STATUS != 'Book Now'): ?>
									<button class="btn btn-warning " type="button" style="<?php echo ((!empty($schedule->M_STATUS_COLOR)) ? 'background-color:'.$schedule->M_STATUS_COLOR.';' : ''); ?><?php echo ((!empty($schedule->M_STATUS_TEXT_COLOR)) ? 'color:'.$schedule->M_STATUS_TEXT_COLOR.';': ''); ?><?php echo ((!empty($schedule->M_STATUS_BORDER_COLOR)) ? 'border-color:'.$schedule->M_STATUS_BORDER_COLOR.';' : ''); ?>">
										<span><?php echo $schedule->TITLE; ?> <?php echo $schedule->M_STATUS; ?></span>
									</button>
								<?php elseif (App::GetClient()->GetController('auth')->isLogged()): ?>
									<?php if ($schedule->MAX_STUDENTS <= ($schedule->NUM_STUDENTS) && DSM_OC_ALLOW_WAIT_LIST == "1"): ?>
									<a href="#tab-class-registration-<?php echo $class->ID; ?>"  dsm_class_id="<?php echo $class->ID; ?>" dsm_schedule_id="<?php echo $schedule->ID; ?>" title="Add to Wait List" class="btn btn-success dsm_ajax_tab">
										<i class="fa fa-plus-circle"></i> <?php echo $schedule->TITLE;?> Add to Wait List
									</a>
									<?php else: ?>
									<a href="#tab-class-registration-<?php echo $class->ID; ?>" dsm_class_id="<?php echo $class->ID; ?>" dsm_schedule_id="<?php echo $schedule->ID;?>" title="Register" class="btn btn-success dsm_ajax_tab">
										<i class="fa fa-plus-circle"></i> <?php echo $schedule->TITLE; ?> Register
									</a>
									<?php endif; ?>
								<?php else: ?>
									<button class="btn btn-success btn-login-alert" type="button" dsm_class_id="<?php echo $class->ID; ?>" dsm_schedule_id="<?php echo $schedule->ID; ?>">
										<span><i class="fa fa-plus-circle"></i> <?php echo $schedule->TITLE?> Register</span>
									</button>		
								<?php endif; ?>
								</td>
							</tr>
                        <?php endif; ?>
						<?php endforeach; ?>
						</tbody>
					</table>
			<?php else: ?>
				<?php if (App::GetClient()->GetController('auth')->isLogged()): ?>
					<?php if ($class->MAX_STUDENTS <= ($class->STUDENTS_QUANTITY) && DSM_OC_ALLOW_WAIT_LIST == "1"): ?>
					<a href="#tab-class-registration-<?php echo $class->ID; ?>" title="Add to Wait List" class="btn btn-success dsm_ajax_tab" dsm_schedule_id="<?php echo $class->SCHEDULES[0]->ID; ?>">
						<i class="fa fa-plus-circle"></i> Add to Wait List
					</a>
					<?php else: ?>
					<a href="#tab-class-registration-<?php echo $class->ID; ?>" title="Register" class="btn btn-success dsm_ajax_tab" dsm_schedule_id="<?php echo $class->SCHEDULES[0]->ID; ?>">
						<i class="fa fa-plus-circle"></i> Register
					</a>
					<?php endif; ?>
				<?php else: ?>
					<a href="#tab-class-registration-<?php echo $class->ID; ?>" title="Register" class="btn btn-info dsm_ajax_tab" dsm_schedule_id="<?php echo $class->SCHEDULES[0]->ID; ?>">
						<span><i class="fa fa-info-circle"></i> Info</span>
					</a>&nbsp;
					<button class="btn btn-success btn-login-alert" type="button">
						<span><i class="fa fa-plus-circle"></i> Register</span>
					</button>						
				<?php endif; ?>
			<?php endif; ?>
			</td>
        </tr>
	<?php endforeach; ?>               
	</tbody>
</table>