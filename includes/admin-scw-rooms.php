	<div class="wrap">
		<div class="scwatbwsr_content">
			<div><?= settings_errors() ?></div>
		</div>
		<div class="scwatbwsr_content mb-3">
			<?php adminMenuPage() ?>
		</div>
		<div class="rooms_area scwatbwsr_content pd-10">
			<h2>
				<?php echo esc_html__("Table Booking Management", "scwatbwsr-translate") ?>
				<a class="new_room_link" href="javascript:newRoom()"><i class="fa fa-plus"></i> New Room</a>
			</h2>

			<div class="scwatbwsr_content">
				<input type="hidden" value="<?php echo esc_attr(get_option('date_format')) ?>" class="scw_date_format">

				<div class="scwatbwsr_rooms">
					<?php
					if ($rooms) {
						foreach ($rooms as $room) {
							$getTypesSql = $wpdb->prepare("SELECT * from {$typesTB} where roomid=%d", 0);
							$types = $wpdb->get_results($getTypesSql);
							$nowtime = date("Y-m-d H:i:s");
							$wpdb->query($wpdb->prepare(
								"UPDATE $tableSchedules SET status=%d  WHERE schedule <= %s",
								0,
								$nowtime
							));
							$getScheSql = $wpdb->prepare("SELECT * from {$tableSchedules} where roomid=%d and status=%d", $room->id, 1);
							$schedules = $wpdb->get_results($getScheSql);

							$getdailiesSql = $wpdb->prepare("SELECT * from {$tableDailySchedules} where roomid=%d", $room->id);
							$dailies = $wpdb->get_results($getdailiesSql);
							if (isset($dailies[0]->daily)) $dailies = explode(",", $dailies[0]->daily);
							else $dailies = array();

							$getTimesSql = $wpdb->prepare("SELECT * from {$tableDailyTimes} where roomid=%d", $room->id);
							$times = $wpdb->get_results($getTimesSql);

							$getTablesSql = $wpdb->prepare("SELECT * from {$tablesTb} where roomid=%d", $room->id);
							$tables = $wpdb->get_results($getTablesSql);

							$getProSql = $wpdb->prepare("SELECT * from {$proTb} where roomid=%d", $room->id);
							$pro = $wpdb->get_results($getProSql);
							if ($pro) {
								$proid = $pro[0]->proid;
								$getOrdersSql = $wpdb->prepare("SELECT * from {$ordersTb} where productId=%d", $proid);
								$orders = $wpdb->get_results($getOrdersSql);
							} else $orders = "";
					?>
							<div class="scwatbwsr_room">
								<input class="scwatbwsr_room_id" value="<?php echo esc_attr($room->id) ?>" type="hidden">
								<span class="scwatbwsr_room_head" data-id="<? $room->id ?>">
									<i class="fadown fa fa-angle-double-right" aria-hidden="true"></i>
									<span class="scwatbwsr_room_head_name" data-id="<? $room->id ?>"><?php echo esc_attr($room->roomname) ?></span>
									<span class="scwatbwsr_room_head_delete"><i class="fa fa-trash" aria-hidden="true"></i></span>
									<span class="scwatbwsr_room_head_copy"><i class="fa fa-files-o" aria-hidden="true"></i></span>
								</span>
								<div class="scwatbwsr_room_content <?= $room->id ?>">
									<div class="disfleitem">
										<div class="scwatbwsr_room_content_tabs tabmaxwidth">
										<input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab1<?php echo esc_attr($room->id) ?>" type="radio" name="scwatbwsr_tabs<?php echo esc_attr($room->id) ?>">
										<label class="scwatbwsr_room_content_tabs_label <?= getActiveClass($room->id, 'scwatbwsr_tab1') ?>" for="scwatbwsr_tab1<?php echo esc_attr($room->id) ?>"><i class="fa fa-cog"></i><span><?php echo esc_html__("Room Setting", "scwatbwsr-translate") ?></span></label>

									

										<input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab3<?php echo esc_attr($room->id) ?>" type="radio" name="scwatbwsr_tabs<?php echo esc_attr($room->id) ?>">
										<label class="scwatbwsr_room_content_tabs_label <?= getActiveClass($room->id, 'scwatbwsr_tab3') ?>" for="scwatbwsr_tab3<?php echo esc_attr($room->id) ?>"><i class="fa fa-calendar"></i><span><?php echo esc_html__("Schedules", "scwatbwsr-translate") ?></span></label>

										<input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab4<?php echo esc_attr($room->id) ?>" type="radio" name="scwatbwsr_tabs<?php echo esc_attr($room->id) ?>">
										<label class="scwatbwsr_room_content_tabs_label <?= getActiveClass($room->id, 'scwatbwsr_tab4') ?>" for="scwatbwsr_tab4<?php echo esc_attr($room->id) ?>"><i class="fa fa-usd"></i><span><?php echo esc_html__("Price", "scwatbwsr-translate") ?></span></label>

										<input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab5<?php echo esc_attr($room->id) ?>" type="radio" name="scwatbwsr_tabs<?php echo esc_attr($room->id) ?>">
										<label class="scwatbwsr_room_content_tabs_label <?= getActiveClass($room->id, 'scwatbwsr_tab5') ?>" for="scwatbwsr_tab5<?php echo esc_attr($room->id) ?>"><i class="fa fa-th"></i><span><?php echo esc_html__("Tables", "scwatbwsr-translate") ?></span></label>

										<input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab6<?php echo esc_attr($room->id) ?>" type="radio" name="scwatbwsr_tabs<?php echo esc_attr($room->id) ?>">
										<label class="scwatbwsr_room_content_tabs_label <?= getActiveClass($room->id, 'scwatbwsr_tab6') ?>" for="scwatbwsr_tab6<?php echo esc_attr($room->id) ?>"><i class="fa fa-braille"></i><span><?php echo esc_html__("Mapping", "scwatbwsr-translate") ?></span></label>
						</div>

<div class="scwatbwsr_room_content_tabs padequal">
										<section id="scwatbwsr_content1<?php echo esc_attr($room->id) ?>" class="tab-content <?= getActiveClass($room->id, 'scwatbwsr_tab1') ?>">
											<span class="scwatbwsr_room_content_editname">
												<span class="scwatbwsr_room_content_editname_head"><?php echo esc_html__("Edit Name", "scwatbwsr-translate") ?></span>
												<input class="scwatbwsr_room_content_editname_name" value="<?php echo esc_attr($room->roomname) ?>" type="text">
											</span>
											<span class="scwatbwsr_roombg">
												<span class="scwatbwsr_roombg_label"><?php echo esc_html__("Room Background", "scwatbwsr-translate") ?></span>
												<span class="scwatbwsr_roombg_con">
													<input type="color" id="scwatbwsr_roombg_con_color" class="scwatbwsr_roombg_con_color" value="<?php echo esc_attr($room->roomcolor) ?>">
													<label for="scwatbwsr_roombg_con_color" class="scwatbwsr_roombg_con_color_button"><?php echo esc_html__("Pick Color", "scwatbwsr-translate") ?></label>
													<span class="scwatbwsr_roombg_con_or"><?php echo esc_html__("OR", "scwatbwsr-translate") ?></span>
													<span class="scwatbwsr_roombg_con_bgpreview">
														<?php
														if ($room->roombg) {
														?><img src="<?php echo esc_attr($room->roombg) ?>"><?php
																										}
																											?>
													</span>
													<input class="scwatbwsr_roombg_con_image" value="<?php echo esc_attr($room->roombg) ?>" type="text">
													<span class="scwatbwsr_roombg_con_upload scwatbwsr_media_upload"><?php echo esc_html__("Upload Image", "scwatbwsr-translate") ?></span>
												</span>
											</span>
											<span class="scwatbwsr_roomsize">
												<span class="scwatbwsr_roomsize_label"><?php echo esc_html__("Room Size", "scwatbwsr-translate") ?> (px)</span>
												<input class="scwatbwsr_roomsize_width" placeholder="Width" value="<?php echo esc_attr($room->width) ?>" type="text">
												<input class="scwatbwsr_roomsize_height" placeholder="Height" value="<?php echo esc_attr($room->height) ?>" type="text">
											</span>
											<span class="scwatbwsr_bookedpr">
												<span class="scwatbwsr_bookedpr_label"><?php echo esc_html__("Table Booked Color", "scwatbwsr-translate") ?></span>
												<input class="scwatbwsr_bookedpr_tbcolor" value="<?php echo esc_attr($room->tbbookedcolor) ?>" type="color">
												<span class="scwatbwsr_bookedpr_label"><?php echo esc_html__("Seat Booked Color", "scwatbwsr-translate") ?></span>
												<input class="scwatbwsr_bookedpr_seatcolor" value="<?php echo esc_attr($room->seatbookedcolor) ?>" type="color">
											</span>
											
											
											<span class="scwatbwsr_basesetting_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save", "scwatbwsr-translate") ?></span>
										</section>

										<section id="scwatbwsr_content3<?php echo esc_attr($room->id) ?>" class="tab-content <?= getActiveClass($room->id, 'scwatbwsr_tab3') ?>">
											<div class="scwatbwsr_schedules_spec">
												<h2 class="scwatbwsr_schedules_spec_head"><?php echo esc_html__("Select Room Off Days", "scwatbwsr-translate") ?></h2>
												<div class="scwatbwsr_schedules_spec_input_row">
													<div class="scwatbwsr_schedules_spec_input">
														<label>Start date and time</label>
														<input class="scwatbwsr_schedules_spec_add_input" type="text">
														<input type="hidden" class="start_time_hidden" />
													</div>
													<div class="scwatbwsr_schedules_spec_input">
														<label>End time</label>
														<input class="scwatbwsr_schedules_spec_end_time_input" type="text">
													</div>
													<div class="scwatbwsr_schedules_spec_add">

														<span class="scwatbwsr_schedules_spec_button mb-3"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo esc_html__("ADD", "scwatbwsr-translate") ?></span>

													</div>
												</div>

												<h2 class="scwatbwsr_schedules_spec_head"><?php echo esc_html__("List of  Schedules", "scwatbwsr-translate") ?></h2>

												<span class="scwatbwsr_schedules_spec_list">
													<?php
													if ($schedules) {
														foreach ($schedules as $schedule) {
													?>
															<span class="scwatbwsr_schedules_spec_list_item">
																<input type="hidden" value="<?php echo esc_attr($schedule->id) ?>" class="scwatbwsr_schedules_spec_list_item_id">
																<input type="hidden" class="start_time_hidden_list" value="<?php echo esc_attr($schedule->start_time) ?>" />
																<input class="scwatbwsr_schedules_spec_list_item_schedule scwatbwsr_schedules_spec_list_item_schedule_start" value="<?php echo esc_attr(date("F j, Y H:i", strtotime($schedule->schedule))) ?>" type="text">
																<input class="scwatbwsr_schedules_spec_list_item_schedule scwatbwsr_schedules_spec_list_item_schedule_end" value="<?php echo esc_attr($schedule->end_time) ?>" type="text">
																<span class="scwatbwsr_schedules_spec_list_item_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save", "scwatbwsr-translate") ?></span>
																<span class="scwatbwsr_schedules_spec_list_item_delete"><i class="fa fa-trash-o" aria-hidden="true"></i> <?php echo esc_html__("Delete", "scwatbwsr-translate") ?></span>
															</span>
													<?php
														}
													}
													?>
												</span>
												<div class="scwatbwsr_schedules_spec_reload">
													<p><span class="scwatbwsr_schedules_spec_add_reload" data-id="<?= $room->id ?>"><?php echo esc_html__("Refresh Data", "scwatbwsr-translate") ?> <i class="fa fa-refresh" aria-hidden="true"></i></span></p>
												</div>
											</div>
											<span class="scwatbwsr_schedules_or"><?php echo esc_html__("OR", "scwatbwsr-translate") ?></span>
											<span class="scwatbwsr_schedules_right">
												<span class="scwatbwsr_schedules_right_head"><?php echo esc_html__("Daily Schedules Off Day", "scwatbwsr-translate") ?></span>

												<?php
												$weekDays = array(
													"monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"
												);
												foreach ($weekDays as $wk => $week) { ?>
													<div class="scwatbwsr_daily_schedules">
														<div class="scwatbwsr_daily_schedules_week">
															<input <?php if (in_array($week, $dailies)) echo "checked='checked'" ?> value="<?= $week ?>" type="checkbox" class="scwatbwsr_daily_schedules_<?= $week ?>" id="scwatbwsr_daily_schedules_<?= $week ?>">
															<label for="scwatbwsr_daily_schedules_<?= $week ?>"><?php echo esc_html__(ucfirst($week), "scwatbwsr-translate") ?></label>

														</div>
														<?php
														$timeData = array_filter($times, function ($t) use ($week) {
															return ($t->week_day == $week);
														});
														$time = array_reverse($timeData);

														if (!$time) {
															$time = (object) array();
															$time->id = 0;
															$time->start_time = "09:00";
															$time->week_day = $week;
															$time->end_time = "15:00";
															$time->roomid = $room->id;
														} else {
															$time = $time[0];
														}
														?>
														<div class="scwatbwsr_daily_schedules_times_list_item">
															<input class="scwatbwsr_daily_schedules_times_list_item_week" type="hidden" value="<?php echo esc_attr($time->week_day) ?>">
															<input class="scwatbwsr_daily_schedules_times_list_item_id" type="hidden" value="<?php echo esc_attr($time->id) ?>">
															<input class="scwatbwsr_daily_schedules_times_list_item_input input_start" id="scwatbwsr_daily_schedules_times_list_item_inpu_<?= $time->id ?>t" placeholder="daily time" value="<?php echo esc_attr($time->start_time) ?>" type="text">
															<input class="scwatbwsr_daily_schedules_times_list_item_input input_end" id="scwatbwsr_daily_schedules_times_list_item_input_<?= $time->id ?>" placeholder="daily time" value="<?php echo esc_attr($time->end_time) ?>" type="text">
															<span class="scwatbwsr_daily_schedules_times_list_item_button"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save", "scwatbwsr-translate") ?></span>
														</div>
													</div>
												<?php } ?>



											</span>
										</section>

										<section id="scwatbwsr_content4<?php echo esc_attr($room->id) ?>" class="tab-content <?= getActiveClass($room->id, 'scwatbwsr_tab4') ?>">
											<span class="scwatbwsr_prices">
												<?php
												$prices = [];
												if ($types) {
													foreach ($types as $type) {
														$getPriceSql = $wpdb->prepare("SELECT * from {$pricesTb} where typeid=%d", $type->id);
														$price = $wpdb->get_results($getPriceSql);
														if ($price)
															$prices[] = $price[0];

														if (isset($price[0]->price)) $pri = $price[0]->price;
														else $pri = 0;

														if (isset($price[0]->type)) $itype = $price[0]->type;
														else $itype = "seat";
												?>
														<span class="scwatbwsr_prices_item">
															<span class="scwatbwsr_prices_item_head"><?php echo esc_attr($type->name) . " " . esc_html__("price", "scwatbwsr-translate") ?></span>
															<input class="scwatbwsr_prices_item_typeid" type="hidden" value="<?php echo esc_attr($type->id) ?>">
															<input class="scwatbwsr_prices_item_price" type="text" value="<?php echo esc_attr($pri) ?>">
															<select class="scwatbwsr_prices_item_type">
																<option <?php if ($itype == "seat") echo "selected" ?> value="seat"><?php echo esc_html__("Per Person", "scwatbwsr-translate") ?></option>
																<option <?php if ($itype == "table") echo "selected" ?> value="table"><?php echo esc_html__("Per Table", "scwatbwsr-translate") ?></option>
																<option <?php if ($itype == "time") echo "selected" ?> value="time"><?php echo esc_html__("One Time", "scwatbwsr-translate") ?></option>
															</select>
														</span>
												<?php
													}
												}
												?>
											</span>
											<span class="scwatbwsr_prices_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save", "scwatbwsr-translate") ?></span>
										</section>

										<section id="scwatbwsr_content5<?php echo esc_attr($room->id) ?>" class="tab-content <?= getActiveClass($room->id, 'scwatbwsr_tab5') ?>">
											<span class="scwatbwsr_tables_add">
												<span class="scwatbwsr_tables_add_head"><?php echo esc_html__("Add a table", "scwatbwsr-translate") ?></span>
												<input class="scwatbwsr_tables_add_label" type="text" placeholder="Label">
												<input class="scwatbwsr_tables_add_seats" type="text" placeholder="No of Room">
												<select class="scwatbwsr_tables_add_type">
													<option value="">-- <?php echo esc_html__("Choose a type", "scwatbwsr-translate") ?> --</option>
													<?php
													if ($types) {
														foreach ($types as $type) {
													?>
															<option value="<?php echo esc_attr($type->id) ?>"><?php echo esc_attr($type->name) ?></option>
													<?php
														}
													}
													?>
												</select>
												<span class="scwatbwsr_tables_add_button"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo esc_html__("ADD", "scwatbwsr-translate") ?></span>
												<span class="scwatbwsr_tables_add_reload" data-id="<?= $room->id ?>"><?php echo esc_html__("Refresh Data", "scwatbwsr-translate") ?> <i class="fa fa-refresh" aria-hidden="true"></i></span>
											</span>
											<hr />
											<span class="scwatbwsr_tables_list">
												<span class="scwatbwsr_tables_list_head"><?php echo esc_html__("Tables", "scwatbwsr-translate") ?></span>
												<?php
												if ($tables) {
													foreach ($tables as $table) {
												?>
														<span class="scwatbwsr_tables_list_item">
															<input type="hidden" value="<?php echo esc_attr($table->id) ?>" class="scwatbwsr_tables_list_item_id">

															<input type="text" class="scwatbwsr_tables_list_item_label" value="<?php echo esc_attr($table->label) ?>">
															<input type="text" class="scwatbwsr_tables_list_item_seats" value="<?php echo esc_attr($table->seats) ?>">
															<select class="scwatbwsr_tables_list_item_type">
																<option value="">-- <?php echo esc_html__("Choose a type", "scwatbwsr-translate") ?> --</option>
																<?php
																if ($types) {
																	foreach ($types as $type) {
																?>
																		<option <?php if ($table->type == $type->id) echo "selected" ?> value="<?php echo esc_attr($type->id) ?>"><?php echo esc_attr($type->name) ?></option>
																<?php
																	}
																}
																?>
															</select>
															<span class="scwatbwsr_tables_list_item_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save", "scwatbwsr-translate") ?></span>
															<span class="scwatbwsr_tables_list_item_del"><i class="fa fa-trash-o" aria-hidden="true"></i> <?php echo esc_html__("Delete", "scwatbwsr-translate") ?></span>
															<span class="scwatbwsr_tables_list_item_clone"><i class="fa fa-files-o" aria-hidden="true"></i> <?php echo esc_html__("Clone", "scwatbwsr-translate") ?></span>
														</span>
												<?php
													}
												}
												?>
											</span>
										</section>

										<section id="scwatbwsr_content6<?php echo esc_attr($room->id) ?>" class="tab-content <?= getActiveClass($room->id, 'scwatbwsr_tab6') ?>">
											<span class="scwatbwsr_mapping_listpreview">
												<span class="scwatbwsr_mapping_preview" style="width: <?php echo esc_attr($room->width) ?>; height: <?php echo esc_attr($room->height) ?>">
													<?php
													if ($room->roombg) {
													?><img class="scwatbwsr_mapping_preview_image" src="<?php echo esc_attr($room->roombg) ?>"><?php
																																			} else {
																																				?><span style="background: <?php echo esc_attr($room->roomcolor) ?>" class="scwatbwsr_mapping_preview_color"><?php echo esc_attr($room->roomcolor) ?></span><?php
																																																																										}
																																																																											?>
													<span class="scwatbwsr_mapping_preview_tables">
														<?php
														if ($tables) {
															foreach ($tables as $table) {
																$getType = $wpdb->prepare("SELECT * from {$typesTB} where id=%d", $table->type);
																$type = $wpdb->get_results($getType);

																if ($table->tleft) $tleft = $table->tleft;
																else $tleft = 0;

																if ($table->ttop) $ttop = $table->ttop;
																else $ttop = 0;

																$padding = $type[0]->seatwidth + 2;

																$style = 'background: ' . $type[0]->tbbg . ' none repeat scroll 0% 0% padding-box content-box;left: ' . $tleft . 'px;top: ' . $ttop . 'px;padding: ' . $padding . 'px;';
																if ($type[0]->tbshape == "rectangular")
																	$style .= 'width: ' . $type[0]->tbrecwidth . 'px; height: ' . $type[0]->tbrecheight . 'px;line-height: ' . ($type[0]->tbrecheight + ($type[0]->seatwidth + 2) * 2) . 'px';
																else
																	$style .= 'width: ' . $type[0]->tbcirwidth . 'px; height: ' . $type[0]->tbcirwidth . 'px;line-height: ' . ($type[0]->tbcirwidth + ($type[0]->seatwidth + 2) * 2) . 'px;border-radius: ' . $type[0]->tbcirwidth . 'px';

																$seatstyle = 'background: ' . $type[0]->seatbg . ';';
																if ($type[0]->seatshape == "rectangular")
																	$seatstyle .= 'width: ' . $type[0]->seatwidth . 'px; height: ' . $type[0]->seatwidth . 'px;line-height: ' . $type[0]->seatwidth . 'px;';
																else
																	$seatstyle .= 'width: ' . $type[0]->seatwidth . 'px; height: ' . $type[0]->seatwidth . 'px;line-height: ' . $type[0]->seatwidth . 'px;border-radius: ' . $type[0]->seatwidth . 'px;';

																$seats = explode(",", $table->seats);
														?>
																<span class="scwatbwsr_mapping_table" style="<?php echo esc_attr($style) ?>">
																	<span class="scwatbwsr_mapping_table_seats" style="width: calc(100% + <?php echo esc_attr(($type[0]->seatwidth + 2) * 2) ?>px);
																				margin-left: -<?php echo esc_attr($type[0]->seatwidth + 2) ?>px;
																				height: calc(100% + <?php echo esc_attr(($type[0]->seatwidth + 2) * 2) ?>px);
																				margin-top: -<?php echo esc_attr($type[0]->seatwidth + 2) ?>px;
																				">
																		<?php
																		if ($seats) {
																			foreach ($seats as $seat) {
																				$getSeatDt = $wpdb->prepare("SELECT * from {$seatsTb} where tbid=%d and seat=%s", $table->id, $seat);
																				$seatdt = $wpdb->get_results($getSeatDt);
																				if (isset($seatdt[0]->tleft)) $sleft = $seatdt[0]->tleft;
																				else $sleft = 0;

																				if (isset($seatdt[0]->ttop)) $stop = $seatdt[0]->ttop;
																				else $stop = 0;

																				$newseatstyle = $seatstyle . 'left: ' . $sleft . 'px; top: ' . $stop . 'px';
																		?><span style="<?php echo esc_attr($newseatstyle) ?>" class="scwatbwsr_mapping_table_seat"><?php echo esc_attr($seat) ?></span><?php
																																																	}
																																																}
																																																		?>
																	</span>
																	<input type="hidden" class="scwatbwsr_mapping_table_id" value="<?php echo esc_attr($table->id) ?>">
																	<span class="scwatbwsr_mapping_table_label"><?php echo esc_attr($table->label) ?></span>
																	<span style="margin-left: -<?php echo esc_attr($room->width) ?>; width: <?php echo esc_attr($room->width) ?>" class="topline"></span>
																	<span style="margin-top:-<?php echo esc_attr($room->height) ?>; height: <?php echo esc_attr($room->height) ?>" class="rightline"></span>
																	<span style="margin-left:-<?php echo esc_attr($room->width) ?>; width: <?php echo esc_attr($room->width) ?>" class="botline"></span>
																	<span style="margin-top:-<?php echo esc_attr($room->height) ?>; height: <?php echo esc_attr($room->height) ?>" class="leftline"></span>
																</span>
														<?php
															}
														}
														?>
													</span>
												</span>
												<br clear="all">
												<span class="scwatbwsr_mapping_preview_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save Mapping", "scwatbwsr-translate") ?></span>

											</span>

										</section>

													</div>

									</div>
								</div>
							</div>
					<?php

						}
					}
					?>
				</div>
			</div>
		</div>

	</div>