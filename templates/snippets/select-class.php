<?php
namespace DanceStudioManager;
?>
<?php if (trim($student['name']) || !App::GetClient()->GetController('auth')->isLogged()) : ?>

	<?php if ($student['STUDENT_ID']): ?>
	<h3>
		<span>Select classes for</span>&nbsp;
		<b><?php echo $student['name']; ?></b>
		<?php if (DSM_OC_SHOW_MEMBER_LABELS == "1"): ?>
			<?php if ($student['IS_STUDENT'] == "1"): ?>
				<span class="badge btn-primary"><?php echo (($student['PARENT_ID'] == "0") ? 'adult' : ''); ?> student</span>
			<?php endif; ?>
			<?php echo (($student['IS_GUARDIAN'] == "1") ? ' <span class="badge btn-primary">guardian</span> ' : ' '); ?>
		<?php endif; ?>
		<span class="badge btn-primary"><?php echo $student['RELATION'];?></span>
	</h3>
	<?php endif; ?>
	<?php foreach ($groupclasses as $class) : ?>
		<h4>
			<?php echo ((DSM_OC_CLASS_LIST_CLASS_ID == "1") ? '<div class="label label-default">'.$class['ID'].'</div>' : ''); ?>
			<?php echo ((DSM_OC_CLASS_LIST_CLASS_CODE == "1") ? ''.$class['CODE'].' ' : ''); ?>
			<?php echo ((DSM_OC_CLASS_LIST_CLASS_NAME == "1") ? ''.$class['NAME'].' ' : ''); ?>
			<?php echo ((DSM_OC_CLASS_LIST_CLASS_LEVEL == "1") ? ''.$class['LEVEL'].' ' : ''); ?>
			<small><?php echo $class['CLASS_START'];?> - <?php echo $class['CLASS_END'];?></small>
			<?php /* if ($class['INWAITLIST'] == "1"): ?>
				<div class="label label-warning">Waiting</div>
			<?php elseif ($class['INCLASS'] == "1"): ?>
				<div class="label label-success">Enrolled</div>
			<?php endif;*/ ?>
		</h4>
		<?php if ($student['prerequisites_complete'] && DSM_OC_ALLOW_WAIT_LIST == "1" && $class['SCHEDULE']['WAIT_LIST'] == "1" && $class['SCHEDULE']['MAX_STUDENTS'] <= ($class['SCHEDULE']['STUDENTS_QUANTITY'])) : ?>
			<div class="alert alert-warning"> Class is full. Student will be added to Wait List.</div>
		<?php endif; ?>
		<?php if (DSM_OC_CLASS_LIST_AVAILABLE_SLOTS == '1' && false): ?>
			<p><?php echo ((($class['MAX_STUDENTS'] - $class['STUDENTS_QUANTITY']) > 0) ? ($class['MAX_STUDENTS'] - $class['STUDENTS_QUANTITY']) :'0'); ?> out of <?php echo $class['MAX_STUDENTS']; ?>  slots available</p>
		<?php endif ;?>
		
		<?php echo (($class['PAGES']) ? '<div>'.str_replace('[:pg:]','<br><br>',$class['PAGES']).'</div>' : ''); ?>
		
		<?php if (($class['MIN_AGE'] > $student['AGE'] || $class['MAX_AGE'] < $student['AGE']) && DSM_ENROLL_CHECK_AGE == "1") : ?>
			<div class="label label-warning" style="margin-left:20px;">Age ineligible</div>
		<?php elseif (!$student['member_category_allowed']) : ?>
			<div class="label label-warning" style="margin-left:20px;">Member Category ineligible</div>
		<?php elseif ($student['student_reg_blocked']) : ?>
			<div class="label label-warning" style="margin-left:20px;">New students registration is not allowed</div>
		<?php else: ?>
			<span style="display:none;" id="ap_options">
			<select class="form-control" name="purchase_id" >
					<?php foreach($active_purchases as $purchase):  ?>
					<?php if ($purchase['value'] == 'unpaid') continue; ?>
					
					<option value="<?php echo $purchase['value']; ?>" data-class_registration_method="<?php if ($purchase['value'] == 'unpaid') { echo "all_future_schedules"; } else {echo $purchase['class_registration_method'];} ?>" data-price="<?php echo $purchase['price']; ?>" data-period="<?php echo $purchase['period']; ?>" data-lessons_available="<?php echo $purchase['lessons_available']; ?>" data-type="<?php echo $purchase['type']; ?>"><?php echo $purchase['label']; ?></option>
					<?php endforeach; ?>
			</select>
			</span>
			<ul class="gc list-unstyled">
				<?php if ($student['prerequisites_complete']) : ?>
					<?php if (DSM_OC_ALLOW_CLASS_REG_PURCH_ITEMS == "1" && $student['STUDENT_ID'] > 0 && $class['ID'] > 0 && ($class['PAYMENT_METHOD'] == 'sales_packages' || DSM_OC_ALLOW_DROP_IN_REGULAR == "1") && $student['classes'][$class['ID']]['sales_items'] && !empty($active_purchases)) : ?>
						<li><span class="active_purchases" id="ap_<?php echo $student['STUDENT_ID']; ?>_<?php echo $class['ID']; ?>" data-student_id="<?php echo $student['STUDENT_ID']; ?>" data-season_status="<?php echo $class['SCHEDULE']['SEASON_STATUS'];?>" data-class_id="<?php echo $class['ID'];?>" data-schedule_id="<?php echo (($schedule_id) ? $schedule_id : $class['SCHEDULE_ID']); ?>"></span></li>
					<?php endif; ?>
					<?php if (($class['PAYMENT_METHOD'] == 'sales_packages' || DSM_OC_ALLOW_DROP_IN_REGULAR == "1") && $student['classes'][$class['ID']]['sales_items']) : ?>
						<?php foreach($student['classes'][$class['ID']]['sales_items'] as $sales_item) : ?>
						<?php if (($class['SCHEDULE'] != false || ($class['SELL_SEASON_STATUS_1'] == '1' && $sales_item['SELL_SEASON_STATUS_1'] == '1') || ($class['SELL_SEASON_STATUS_1'] == '2' && $sales_item['SELL_SEASON_STATUS_2'] == '1')) &&
									  ($sales_item['SELL_AS_PRODUCT'] == '0' || DSM_OC_SHOW_CLASS_ASSIGNED_PRODUCTS == '1') &&
									  ($sales_item['SELL_INDIVIDUALLY'] == '1' || $sales_item['TYPE'] == 'package')) : ?>
								<li>
									<div class="row">
										<div class="col-xs-3 col-sm-3 col-md-2">
								<?php if ($student['STUDENT_ID'] > 0) :  ?>
									<?php if (DSM_OC_SHOPPING_CART_ENABLED == '1') : ?>
										<?php if ($class['REGISTRATION'] == 'invitation' && $class['ELIGIBLE']) :  ?>
											<div class="label label-warning">Invitation Only</div>
										<?php else: ?>
											<?php if ($sales_item['SALE_STARTED']) : ?>
											
												<a class="btn <?php echo ((!$sales_item['INCART']) ? 'btn-success' : 'btn-primary'); ?> btn-sm select-class dsm_ajax_tab"
													<?php echo (($class['SCHEDULE']['WAIT_LIST'] == '0' && $class['SCHEDULE']['MAX_STUDENTS'] <= ($class['SCHEDULE']['STUDENTS_QUANTITY'])) ? 'disabled="disabled"' : ''); ?>
													href = "#tab-class-registration-<?php echo $class['ID']; ?>";
													<?php if ($sales_item['INCART']) : ?>
														dsm_obj="checkout"
														dsm_method="DeleteCartItem"
														dsm_item_key = "<?php echo $sales_item['INCART']; ?>"
													<?php else: ?>
														dsm_obj="checkout"
														dsm_method="SubmitCartItem"
													<?php endif; ?>
													dsm_class_id="<?php echo $class['ID']; ?>"
													dsm_student_id="<?php echo $student['STUDENT_ID']; ?>"
													dsm_schedule_id="<?php echo (($schedule_id) ? $schedule_id : $class['SCHEDULE_ID']);?>"
													dsm_sales_item_type="<?php echo $sales_item['TYPE']; ?>"
													dsm_related_item_id="<?php echo $sales_item['RELATED_ITEM_ID']; ?>"
													dsm_sales_item_id="<?php echo $sales_item['ID']; ?>"
													>
													<span><?php echo ((!$sales_item['INCART']) ? '<i class="fa fa-plus-circle"></i> Select' : '<i class="fa fa-minus-circle"></i> Remove'); ?></span>
												</a>
											<?php else: ?>
												<div class="label label-warning">Sale starts <?php echo $sales_item['SALE_START_DATE']; ?></div>
											<?php endif; ?>
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
										</div>
										<div class="col-xs-9 col-sm-9 col-md-10 ctitle">
											<?php echo DSM_CURRENCY_SIGN; ?><?php echo $sales_item['PRICE']; ?> <strong><?php echo $sales_item['NAME']; ?></strong>
											<?php echo (($sales_item['PRICE_DESCRIPTION']) ? '<br><span class="label label-warning">'.$sales_item['PRICE_DESCRIPTION'].'</span>' : ''); ?>
											<?php echo (($sales_item['DESCRIPTION']) ? '<p><small>'.$sales_item['DESCRIPTION'].'</small></p>' : ''); ?>
											<?php if ($sales_item['PAYMENT_PLANS'] && $sales_item['SALE_STARTED'] && $student['STUDENT_ID'] > 0 && DSM_OC_SHOPPING_CART_ENABLED == '1'): ?>
											<h5>Payment plans</h5>
											<?php foreach($sales_item['PAYMENT_PLANS'] as $pp) : ?>
											<div class="row mt-3 mb-4">
												<div class="col-xs-3 col-sm-3 col-md-2">
													<a class="btn <?php echo ((!$pp['INCART']) ? 'btn-success' : 'btn-primary' ); ?> btn-sm select-class dsm_ajax_tab"
														href = "#tab-class-registration-<?php echo $class['ID']; ?>";
														<?php echo (($class['info']['SCHEDULE']['WAIT_LIST'] == '0' && $class['info']['SCHEDULE']['MAX_STUDENTS'] <= $class['info']['SCHEDULE']['STUDENTS_QUANTITY']) ? 'disabled="disabled"' : '' ); ?>
														<?php if ($pp['INCART']) : ?>
															dsm_obj="checkout"
															dsm_method="DeleteCartItem"
															dsm_item_key = "<?php echo $pp['INCART']; ?>"
														<?php else: ?>
															dsm_obj="checkout"
															dsm_method="SubmitCartItem"
														<?php endif; ?>
														dsm_payment_plan_id="<?php echo $pp['ID']?>"
														dsm_class_id="<?php echo $class['ID']; ?>"
														dsm_student_id="<?php echo $student['STUDENT_ID']; ?>"
														dsm_schedule_id="<?php echo (($schedule_id) ? $schedule_id : $class['SCHEDULE_ID']);?>"
														dsm_sales_item_type="<?php echo $sales_item['TYPE']; ?>"
														dsm_related_item_id="<?php echo $sales_item['RELATED_ITEM_ID']; ?>"
														dsm_sales_item_id="<?php echo $sales_item['ID']; ?>"
													>
														<span><?php echo ((!$pp['INCART']) ? '<i class="fa fa-plus-circle"></i> Select' : '<i class="fa fa-minus-circle"></i> Remove'); ?></span>
													</a>										
									</div>
									<div class="col-xs-9 col-sm-9 col-md-10 ctitle">
										<i>
										First Payment <?php echo DSM_CURRENCY_SIGN.$pp['FIRST_PAYMENT_AMOUNT'] ?> plus <?php echo DSM_CURRENCY_SIGN.$pp['PAYMENT_PLAN_FEE'] ?> fee and <?php echo $pp['REPEATS'] ?> payment(s) <?php echo DSM_CURRENCY_SIGN.$pp['RECURRING_AMOUNT'] ?> <?php echo $pp['SCHEDULE_NAME'] ?>
										</i>
									</div>
									<br/><br/>
								</div>				
								<?php endforeach; ?>	
								<?php endif; ?>	
											
										</div>
									</div>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if ($class['PAYMENT_METHOD'] == 'billing_schedule') : ?>
						<?php foreach($class['CLASS_PRICING'] as $gcp) : ?>
								<li>
									<div class="row">
										<div class="col-xs-3 col-sm-3 col-md-2">
											
										<?php if ($student['STUDENT_ID'] > 0) : ?>
											<?php if (DSM_OC_CLASS_REG_FEE_ENABLED == '1' && $class['REG_FEE'] == "0" && DSM_OC_ZERO_CLASS_REG_FEE_ENABLED != "1") : ?>
												<div id="rcl_<?php echo $class['ID']; ?>_<?php echo $student['STUDENT_ID']; ?>">
													<?php if ($class['INCLASS'] == "1") : ?>
														<div class="label label-success">Enrolled</div>
													<?php elseif ($class['OC_REGISTRATION'] == 'invitation' && $class != '1') : ?>
														<div class="label label-warning">Invitation Only</div>
													<?php else: ?>
														<?php if (DSM_OC_SHOW_REGISTER_AND_SELECT_BUTTONS) : ?>
															<a class="btn btn-primary btn-sm register-for-class dsm_ajax_tab"
																href = "#tab-class-registration-<?php echo $class['ID']; ?>";
																dsm_obj="checkout"
																dsm_method="SubmitCartItem"
																<?php echo (($class['MAX_STUDENTS'] <= $class['STUDENTS_QUANTITY']) ? 'disabled="disabled"' : ''); ?>
																dsm_class_id="<?php echo $class['ID']; ?>"
																dsm_billing_schedule="<?php echo $gcp['BILLING_SCHEDULE']; ?>"		                                	
																dsm_student_id="<?php echo $student['STUDENT_ID']; ?>"
																dsm_schedule_id="<?php echo (($schedule_id) ? $schedule_id : $class['SCHEDULE_ID']);?>"
																>
																<span><i class="fa fa-plus-circle"></i> Register</span>
															</a>
														<?php endif; ?>
													<?php endif; ?>
												</div>
											<?php else: ?>
												<?php if (DSM_OC_SHOPPING_CART_ENABLED == "1") : ?>
													<?php if ($class['INWAITLIST'] == "1") : ?>
														<div class="label label-success">Waiting</div>
													<?php elseif ($class['INCLASS'] == "1") : ?>
														<div class="label label-success">Enrolled</div>
													<?php else: ?>
														<?php if ($class['OC_REGISTRATION'] == 'invitation' && $class['ELIGIBLE'] != '1') : ?>
															<div class="label label-warning">Invitation Only</div>
														<?php else: ?>
													
															<?php if (DSM_OC_SHOW_REGISTER_AND_SELECT_BUTTONS == "1") : ?>
																<?php if ($class['SCHEDULE']['MAX_STUDENTS'] <= ($class['SCHEDULE']['STUDENTS_QUANTITY']) && DSM_OC_ALLOW_WAIT_LIST == "1") : ?>
																	<div id="atwl_<?php echo $class['ID']; ?>_<?php echo $student['STUDENT_ID']; ?>">
																		<a class="btn btn-primary btn-sm add-to-wait-list dsm_ajax_tab"
																				href = "#tab-class-registration-<?php echo $class['ID']; ?>";
																				dsm_obj="checkout"
																				dsm_method="SubmitCartItem"
																				dsm_class_id="<?php echo $class['ID']; ?>"
																				dsm_student_id="<?php echo $student['STUDENT_ID']; ?>"
																				dsm_schedule_id="<?php echo (($schedule_id) ? $schedule_id : $class['SCHEDULE_ID']);?>"
																				<span><i class="fa fa-plus-circle"></i> Add to Wait List</span>
																		</a>
																	</div>
																<?php else: ?>																
																	<a class="btn <?php echo ((!$class['INCART']) ? 'btn-success' : 'btn-primary'); ?> btn-sm select-class dsm_ajax_tab"
																			<?php echo (($class['SCHEDULE']['MAX_STUDENTS'] <= ($class['SCHEDULE']['STUDENTS_QUANTITY'])) ? 'disabled="disabled"' : ''); ?>
																			href = "#tab-class-registration-<?php echo $class['ID']; ?>";
																			<?php if ($class['INCART']) : ?>
																				dsm_obj="checkout"
																				dsm_method="DeleteCartItem"
																			<?php else: ?>
																				dsm_obj="checkout"
																				dsm_method="SubmitCartItem"
																			<?php endif; ?>
																			dsm_item_key="<?php echo $class['INCART']; ?>"
																			dsm_class_id="<?php echo $class['ID']; ?>"
																			dsm_student_id="<?php echo $student['STUDENT_ID']; ?>"
																			dsm_billing_schedule="<?php echo $gcp['BILLING_SCHEDULE']; ?>"	
																			dsm_schedule_id="<?php echo (($schedule_id) ? $schedule_id : $class['SCHEDULE_ID']);?>">
																		<span><?php echo ((!$class['INCART']) ? '<i class="fa fa-plus-circle"></i> Select' : '<i class="fa fa-minus-circle"></i> Remove'); ?></span>
																	</a>
																<?php endif; ?>
															<?php endif; ?>
														<?php endif; ?>
													<?php endif; ?>
												<?php else: ?>
													<?php if ($class['INWAITLIST'] == '1') : ?>
														<div class="label label-success">Waiting</div>
													<?php elseif ($class['INCLASS'] == '1') : ?>
														<div class="label label-success">Enrolled</div>
													<?php else: ?>
														<?php if ($class['OC_REGISTRATION'] == 'invitation' && $class['ELIGIBLE'] != '1') : ?>
															<div class="label label-warning">Invitation Only</div>
														<?php else: ?>
															<?php if (DSM_OC_SHOW_REGISTER_AND_SELECT_BUTTONS == "1") : ?>
																<?php if ($class['SCHEDULE']['MAX_STUDENTS'] <= ($class['SCHEDULE']['STUDENTS_QUANTITY']) && DSM_OC_ALLOW_WAIT_LIST == '1') : ?>
																	<div id="atwl_<?php echo $class['ID']; ?>_<?php echo $student['STUDENT_ID']; ?>">
																		<a class="btn btn-primary btn-sm add-to-wait-list dsm_ajax_tab"
																		    href = "#tab-class-registration-<?php echo $class['ID']; ?>";
																			dsm_obj="checkout"
																			dsm_method="SubmitCartItem"
																			dsm_class_id="<?php echo $class['ID']; ?>"
																			dsm_student_id="<?php echo $student['STUDENT_ID']; ?>"
																			dsm_schedule_id="<?php echo (($schedule_id) ? $schedule_id : $class['SCHEDULE_ID']);?>">
																			<span><i class="fa fa-plus-circle"></i> Add to Wait List</span>
																		</a>
																	</div>
																<?php else: ?>
																	<div id="rcl_<?php echo $class['ID']; ?>_<?php echo $student['STUDENT_ID']; ?>">
																		<a class="btn btn-primary btn-sm register-for-class dsm_ajax_tab"
																			<?php echo (($class['SCHEDULE']['MAX_STUDENTS'] <= ($class['SCHEDULE']['STUDENTS_QUANTITY'])) ? 'disabled="disabled"' : ''); ?>
																		    href = "#tab-class-registration-<?php echo $class['ID']; ?>";
																			dsm_obj="checkout"
																			dsm_method="SubmitCartItem"
																			dsm_class_id="<?php echo $class['ID']; ?>"
																			dsm_billing_schedule="<?php echo $gcp['BILLING_SCHEDULE']; ?>"	
																			dsm_student_id="<?php echo $student['STUDENT_ID']; ?>"
																			dsm_schedule_id="<?php echo (($schedule_id) ? $schedule_id : $class['SCHEDULE_ID']);?>">
																			<span><i class="fa fa-plus-circle"></i> Register</span>
																		</a>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
														<?php endif; ?>
													<?php endif; ?>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
										<?php if (!App::GetClient()->GetController('auth')->isLogged()) : ?>
											<button class="btn btn-success btn-sm btn-login-alert" >
												<span><i class="fa fa-plus-circle"></i> Select</span>
											</button>                            	
										<?php endif; ?>
									</div>
									<div class="col-xs-9 col-sm-9 col-md-10 ctitle">
									<?php if (DSM_OC_CLASS_LIST_CLASS_PRICE == '1') : ?>
									<?php echo $class['PRICING']; ?>
									<?php endif; ?>
									<?php if (DSM_OC_CLASS_REG_FEE_ENABLED == '1' && $class['REG_FEE'] > 0) : ?>
										(Registration fee <?php echo DSM_CURRENCY_SIGN; ?><?php echo $class['REG_FEE']; ?>)
									<?php endif; ?>
									</div>
								</div>								
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php else: ?>
                		<div class="alert alert-warning"><b>Registration Unavailable!</b> Student doesn't meet pre-requisite requirements for this class. </div>
				<?php endif; ?>
			</ul>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>