<?php
function scwatbwsr_content($content){
	include_once dirname(__FILE__) . '/settings-ipp.php';
	include_once dirname(__FILE__) . '/functions.php';
	global $wpdb;
	global $post;
	$options = get_option( 'scwatbwsr_settings' );
	$ipp_message='';
	$proId = $post->ID;
	
	$currencyS = "$";
	
	$tableRooms = $wpdb->prefix . 'scwatbwsr_rooms';
	$tableProducts = $wpdb->prefix . 'scwatbwsr_products';
	$tableTypes = $wpdb->prefix . 'scwatbwsr_types';
	$tableSchedules = $wpdb->prefix . 'scwatbwsr_schedules';
	$pricesTb = $wpdb->prefix . 'scwatbwsr_prices';
	$tablesTb = $wpdb->prefix . 'scwatbwsr_tables';
	$seatsTb = $wpdb->prefix . 'scwatbwsr_seats';
	$ordersTb = $wpdb->prefix . 'scwatbwsr_orders';
	$bookedTB = $wpdb->prefix . 'scwatbwsr_bookedseats';
	
	$getRoomSql = $wpdb->prepare("SELECT r.*,t.roomid,s.tbid,t.id as tid,s.id as sid from {$tableRooms} AS r 
	  INNER JOIN {$tablesTb} AS t ON r.id= t.roomid
	  INNER JOIN {$seatsTb}  AS s ON t.id= s.tbid
	  where t.roomid!=%d", 0);
	$rooms = $wpdb->get_results($getRoomSql);
	
	
	$ippay_return= file_get_contents('php://input');
    parse_str($ippay_return,$obj);
	
	$order_data=[];
	// payment capture
	
	if(array_key_exists("tran_id",$obj))
	{
		$ipp_message="Payment Successfully and booking is completed!";
		$order_data =json_decode($obj["custom"],true);
		if($obj["payment_status"]=="completed")
		{
          $tran_id=$obj["tran_id"];
		  $order_id =$order_data["order_id"];
		  $transaction_id=$order_data["order_key"];
		  orderUpdate($order_id,["tran_id"=>$tran_id,"_ipp_status"=>$obj["payment_status"]]);
		}
		else
		{

		}
	}
	// payment cancel or return 

	if(isset($_GET['cancel_return']) && $_GET['cancel_return']=="1")
	{
		$ipp_message="Payment Cancel Return! and Booking is Pending";
	}
	// payment back 
	if(isset($_GET['back']) && $_GET['back']=="1")
	{
		$ipp_message="Payment Cancelled by the customer and Booking is Pending";
	}
	if(!function_exists('scw_this_script_footer')) { 
	function scw_this_script_footer($ipp_message){ 
		
	?>
     <script type='text/javascript'>
		
		Swal.fire(
		'Booking Status',
		'<?=$ipp_message?>',
		'success'
		)
		</script>
	<?php }  }
		
	wp_register_style('scwatbwsr-style-frontend', SCWATBWSR_URL .'css/front.css',array(),time());
	wp_enqueue_style('scwatbwsr-style-frontend');
	if(count($rooms) > 0){
		
		ob_start();
		
		
		wp_register_script('scwjquery', SCWATBWSR_URL .'js/jquery.min.js');
		wp_enqueue_script('scwjquery');
		wp_register_script('datetimepicker', SCWATBWSR_URL .'datetimepicker/jquery.datetimepicker.full.min.js');
		wp_enqueue_script('datetimepicker');
		wp_register_style('datetimepicker', SCWATBWSR_URL .'datetimepicker/jquery.datetimepicker.css');
		wp_enqueue_style('datetimepicker');
		wp_register_script('jqueryvalidation','https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js');
		wp_enqueue_script('jqueryvalidation');
		wp_register_script('panzoom', 'https://cdn.jsdelivr.net/npm/@panzoom/panzoom/dist/panzoom.min.js');
		wp_enqueue_script('panzoom');
		wp_register_script('sweetalert','https://cdn.jsdelivr.net/npm/sweetalert2@11');
		wp_enqueue_script('sweetalert');
		wp_register_script('scwatbwsr-script-frontend', SCWATBWSR_URL .'js/front.js',array(),time(),true);
		wp_enqueue_script('scwatbwsr-script-frontend');
		
		if($ipp_message!='')
		{
		add_action('wp_print_footer_scripts', function() use ($ipp_message){
			scw_this_script_footer($ipp_message);
		});
		do_action('scw_this_script_footer');
		}
		
		?>
		<script type='text/javascript'>
		var customer_table ="<?=@$options["customer_table"]?>";
		var enabled_payment ="<?=@$options["enabled_payment"]?>";
		</script>
		<div class="scw_front_content">
			<div class="scwatbwsr_content <?php echo get_post_type($proId) ?>">
			<form action="post" id="scw-booking-form">
			
			
			<div class="scwatbwsr_sendform">
			        <div class="bghover_scw">
							<div class="scwatbwsr_form_item scw_form_name">
								<label><?php echo esc_html__("Name", "scwatbwsr-translate") ?></label>
								<input name="fullname" class="scwatbwsr_form_name_input scwatcommon_style" required type="text" autocomplete="off">
							</div>
							<div class="scwatbwsr_form_item scw_form_address">
								<label><?php echo esc_html__("Address", "scwatbwsr-translate") ?></label>
								<input name="address"  class="scwatbwsr_form_address_input scwatcommon_style" required type="text" autocomplete="off">
							</div>
							<div class="scwatbwsr_form_item scw_form_email">
								<label><?php echo esc_html__("Email", "scwatbwsr-translate") ?></label>
								<input name="email"  class="scwatbwsr_form_email_input scwatcommon_style" required type="email" autocomplete="off">
							</div>
							<div class="scwatbwsr_form_item scw_form_phone">
								<label><?php echo esc_html__("Phone", "scwatbwsr-translate") ?></label>
								<input name="phone"  id="phone" class="scwatbwsr_form_phone_input scwatcommon_style" required type="text" autocomplete="off">
							</div>
							
							<div class="scwatbwsr_form_item scw_form_note">
								<label><?php echo esc_html__("Note", "scwatbwsr-translate") ?></label>
								<textarea name="note" class="scwatbwsr_form_note_input scwatcommon_style" rows="4"></textarea>
							</div>
			<?php
			$getRoomDataSql = $wpdb->prepare("SELECT * from {$tableRooms}  where id!=%d", 0);
			$roomData = $wpdb->get_results($getRoomDataSql);
			?>
			<input type="hidden" value="<?php echo esc_attr(SCWATBWSR_URL) ?>" class="scwatbwsr_url">
			<input type="hidden" value="<?php echo esc_attr($proId) ?>" class="product_id">
			<input type="hidden" value="<?php echo esc_attr($roomData[0]->id) ?>" class="profileid">
			<input type="hidden" value="<?php echo esc_attr($roomData[0]->tbbookedcolor) ?>" class="tbbookedcolor">
			<input type="hidden" value="<?php echo esc_attr($roomData[0]->seatbookedcolor) ?>" class="seatbookedcolor">
			<input type="hidden" value="<?php echo esc_attr($roomData[0]->compulsory) ?>" class="scw_compulsory">
			<input type="hidden" value="<?php echo esc_attr($roomData[0]->bookingtime) ?>" class="scw_bookingtime">
			<input type="hidden" value="<?php echo esc_attr(get_option('date_format')) ?>" class="scw_date_format">
			<input type="hidden" value="<?php echo esc_attr(get_post_type($proId)) ?>" class="scw_posttype">
			<input type="hidden" value="<?php echo esc_attr($roomData[0]->zoomoption) ?>" class="scw_zoomoption">
			<input type="hidden" value="" id="time_hidden" />
							<?php 
			               if(@$options["customer_table"]!="yes"){?>
						   <div class="scwatbwsr_form_item scw_form_phone">
								<label><?php echo esc_html__("No of Seats", "scwatbwsr-translate") ?></label>
								<input name="no_seats"  id="no_seats" class="scwatbwsr_form_seat_input scwatcommon_style" min="1" required type="number" autocomplete="off">
							</div>
						   <?php } 
							
							?>
			
							<?php
							foreach($roomData as $roomcount=>$room)
							{
							$roomid = $room->id;
							$getTypeSql = $wpdb->prepare("SELECT * from {$tableTypes} where roomid=%d", $roomid);
							$types = $wpdb->get_results($getTypeSql);
							$nowtime = date("Y-m-d H:i:s");
							$wpdb->query($wpdb->prepare("UPDATE $tableSchedules SET status=%d  WHERE schedule <= %s",
						0,$nowtime));
							$getSchedulesSql = $wpdb->prepare("SELECT * from {$tableSchedules} where roomid=%d and status=%d order by schedule asc", $roomid,1);
							$checkSchedules = $wpdb->get_results($getSchedulesSql);
							
							if(isset($roomData[0]->tbbookedcolor))
								$tbbookedcolor = $roomData[0]->tbbookedcolor;
							else
								$tbbookedcolor = "#000";
							if(isset($roomData[0]->seatbookedcolor))
								$seatbookedcolor = $roomData[0]->seatbookedcolor;
							else
								$seatbookedcolor = "#000";
							
							$getTablesSql = $wpdb->prepare("SELECT * from {$tablesTb} where roomid=%d", $roomid);
							$tables = $wpdb->get_results($getTablesSql);
							
							$bookedSeats = array();
							$getOrdersSql = $wpdb->prepare("SELECT * from {$ordersTb} where roomid=%d", $roomid);
							$orders = $wpdb->get_results($getOrdersSql);
							if($orders){
								foreach($orders as $order){
									$oseats = explode(",", $order->seats);
									foreach($oseats as $os){
										array_push($bookedSeats, $os);
									}
								}
							}
							$getBookedSql = $wpdb->prepare("SELECT * from {$bookedTB} where roomid=%d", $roomid);
							$bookeds = $wpdb->get_results($getBookedSql);
							if($bookeds){
								foreach($bookeds as $bk){
									array_push($bookedSeats, $bk->tb .".".$bk->seat);
								}
							}
							$bookedSeats = array_unique($bookedSeats);
							$tableDailySchedules = $wpdb->prefix . 'scwatbwsr_dailyschedules';
					        $getDSSql = $wpdb->prepare("SELECT * from {$tableDailySchedules} where roomid=%d", $roomid);
					        $getDSRs = $wpdb->get_results($getDSSql);
							if($checkSchedules  || $getDSRs) {
						    ?>
							<div class="scwatbwsr_form_item">
							<label>Select Room</label>
								<div class="roomselection">
								<span class="room-label room-<?php if($roomcount==0) echo'selected';?>"><?=$room->roomname?></span>
								</div>
						    </div>
							<div class="scwatbwsr_form_item scw_form_calendar">
								<label>
									<?php echo esc_html__("Booking Date", "scwatbwsr-translate");?>
									   
								</label>
									<?php if($checkSchedules){?>
										<div class="scwatbwsr_schedules scwatbwsr_schedules_special">
										<?php foreach($checkSchedules as $sche)
										{
						                ?><span class="scwatbwsr_schedules_item" data-mintime="<?=$sche->start_time?>" data-maxtime="<?=$sche->end_time?>"><?php echo esc_attr(date("l M d, Y",strtotime($sche->schedule)))?></span>
										<?php } ?>
									    </div>
									<?php } else  { ?>
										<div class="scwatbwsr_schedules scwatbwsr_schedules_daily">
										<?php $arroDay = array(0, 1, 2, 3, 4, 5, 6);
					$arrDay = array();
					$arrTime = "";
					
					
					if(isset($getDSRs[0]->daily)) $dailies = explode(",", $getDSRs[0]->daily);
					else $dailies = array();
					if($dailies){
						foreach($dailies as $dai){
							if($dai == "monday")
								array_push($arrDay, 1);
							elseif($dai == "tuesday")
								array_push($arrDay, 2);
							elseif($dai == "wednesday")
								array_push($arrDay, 3);
							elseif($dai == "thursday")
								array_push($arrDay, 4);
							elseif($dai == "friday")
								array_push($arrDay, 5);
							elseif($dai == "saturday")
								array_push($arrDay, 6);
							elseif($dai == "sunday")
								array_push($arrDay, 0);
						}
					}
					$arrfDay = array_diff($arroDay, $arrDay);
					
					$tableDailyTimes = $wpdb->prefix . 'scwatbwsr_dailytimes';
					$getDTSql = $wpdb->prepare("SELECT * from {$tableDailyTimes} where roomid=%d", $roomid);
					$times = $wpdb->get_results($getDTSql);
					if($times){
						foreach($times as $time){
							if($arrTime)
							{
								$arrTime .= ",".$time->start_time;
							}
							else
							{
								$arrTime .= $time->start_time;
							}
							echo '<input class="array_times_start_'.$time->week_day.'" type="hidden" value="'.esc_attr($time->start_time).'">';
							echo '<input class="array_times_end_'.$time->week_day.'" type="hidden" value="'.esc_attr($time->end_time).'">';
						}
					}
					
					if(count($dailies) > 0 && $dailies[0]){
						?>
						<input class="array_dates" type="hidden" value='<?php echo json_encode($arrfDay, 1) ?>'>
						<input class="array_times" type="hidden" value="<?php echo esc_attr($arrTime) ?>">
						<input id="scwatbwsr_schedules_picker" autocomplete="off" class="scwatcommon_style" type="text">
						<?php
					}
				     ?>
									    </div>
					                
								
							</div>
                           
							
						
			
			<?php 
			if(@$options["customer_table"]=="yes"):?>
			<div class="mainpage-seats">
				<div class="scwatbwsr_map">
					<div class="scwatbwsr_map_head"><?php echo esc_html__("Choose your Seats", "scwatbwsr-translate") ?></div>
					<?php if($roomData[0]->zoomoption){ ?>
					<div class="scwatbwsr_map_zoom">
						<span id="scwatbwsr_map_zoom-in"><?php echo esc_html__("Zoom In", "scwatbwsr-translate") ?></span>
						<span id="scwatbwsr_map_zoom-out"><?php echo esc_html__("Zoom Out", "scwatbwsr-translate") ?></span>
						<span id="scwatbwsr_map_zoom_reset"><?php echo esc_html__("Reset", "scwatbwsr-translate") ?></span>
					</div>
					<?php } ?>
					<div class="scwatbwsr_map_block">
					<div id="scwatbwsr_map_panzoom" class="<?php if(!$roomData[0]->zoomoption) echo 'scwatbwsr_map_panzoom_nozoom' ?>">
						<div class="scwatbwsr_map_bg" style="width: <?php echo esc_attr($roomData[0]->width) ?>; height: <?php echo esc_attr($roomData[0]->height) ?>">
							<?php
								if($roomData[0]->roombg){
									?><img class="scwatbwsr_map_bg_img" src="<?php echo esc_attr($roomData[0]->roombg) ?>"><?php
								}else{
									?><span class="scwatbwsr_map_bg_color" style="background: <?php echo esc_attr($roomData[0]->roomcolor) ?>">.</span><?php
								}
							?>
							<div class="scwatbwsr_map_tables">
								<?php
									if($tables){
										foreach($tables as $table){
											$getType = $wpdb->prepare("SELECT * from {$tableTypes} where id=%d", $table->type);
											$type = $wpdb->get_results($getType);
											
											$seats = explode(",", $table->seats);
											if($seats){
												$tmpArr = array();
												foreach($seats as $seat){
													array_push($tmpArr, $table->label .".".$seat);
												}
												$checkSame = array_intersect($bookedSeats, $tmpArr);
												if(count($seats) == count($checkSame))
													$tbcolor = $tbbookedcolor;
												else
													$tbcolor = $type[0]->tbbg;
											}else
												$tbcolor = $type[0]->tbbg;
											
											if($table->tleft) $tleft = $table->tleft;
											else $tleft = 0;
											
											if($table->ttop) $ttop = $table->ttop;
											else $ttop = 0;
											
											$padding = $type[0]->seatwidth + 2;
											
											$style = 'background: '.$tbcolor.' none repeat scroll 0% 0% padding-box content-box;left: '.$tleft.'px;top: '.$ttop.'px;padding: '.$padding.'px;';
											$labelStyle = 'top: '.($type[0]->seatwidth + 2).'px;left: '.($type[0]->seatwidth + 2).'px;';
											if($type[0]->tbshape == "rectangular"){
												$style .= 'width: '.($type[0]->tbrecwidth + ($type[0]->seatwidth + 2)*2).'px; height: '.($type[0]->tbrecheight + ($type[0]->seatwidth + 2)*2).'px;line-height: '.($type[0]->tbrecheight + ($type[0]->seatwidth + 2)*2).'px';
												$labelStyle .= 'width: '.$type[0]->tbrecwidth .'px; height: '.$type[0]->tbrecheight .'px; line-height: '.$type[0]->tbrecheight .'px';
											}else{
												$style .= 'width: '.($type[0]->tbcirwidth + ($type[0]->seatwidth + 2)*2).'px; height: '.($type[0]->tbcirwidth + ($type[0]->seatwidth + 2)*2).'px;line-height: '.($type[0]->tbcirwidth + ($type[0]->seatwidth + 2)*2).'px;border-radius: '.$type[0]->tbcirwidth .'px';
												$labelStyle .= 'width: '.$type[0]->tbcirwidth .'px; height: '.$type[0]->tbcirwidth .'px; line-height: '.$type[0]->tbcirwidth .'px;border-radius: '.$type[0]->tbcirwidth .'px';
											}
											
											$seatstyle = '';
											if($type[0]->seatshape == "rectangular")
												$seatstyle .= 'width: '.$type[0]->seatwidth .'px; height: '.$type[0]->seatwidth .'px;line-height: '.$type[0]->seatwidth .'px;';
											else
												$seatstyle .= 'width: '.$type[0]->seatwidth .'px; height: '.$type[0]->seatwidth .'px;line-height: '.$type[0]->seatwidth .'px;border-radius: '.$type[0]->seatwidth .'px;';
											
											?>
											<span id="table<?php echo esc_attr($table->label) ?>" class="scwatbwsr_map_tables_table" style="<?php echo esc_attr($style) ?>">
												<input type="hidden" class="scwatbwsr_table_readcolor" value="<?php echo esc_attr($type[0]->tbbg) ?>">
												<input type="hidden" class="scwatbwsr_seat_readcolor" value="<?php echo esc_attr($type[0]->seatbg) ?>">
												<span class="scwatbwsr_map_tables_table_seats" style="width: calc(100% + <?php echo esc_attr(($type[0]->seatwidth+2)*2) ?>px);
												margin-left: -<?php echo esc_attr($type[0]->seatwidth+2) ?>px;
												height: calc(100% + <?php echo esc_attr(($type[0]->seatwidth+2)*2) ?>px);
												margin-top: -<?php echo esc_attr($type[0]->seatwidth+2) ?>px;
												">
												<?php
													if($seats){
														foreach($seats as $seat){
															$getSeatDt = $wpdb->prepare("SELECT * from {$seatsTb} where tbid=%d and seat=%s", $table->id, $seat);
															$seatdt = $wpdb->get_results($getSeatDt);
															$getPriceSql = $wpdb->prepare("SELECT * from {$pricesTb} where typeid=%d", $table->type);
							                                $price = $wpdb->get_row($getPriceSql);
															if(isset($seatdt[0]->tleft)) $sleft = $seatdt[0]->tleft;
															else $sleft = 0;
															
															if(isset($seatdt[0]->ttop)) $stop = $seatdt[0]->ttop;
															else $stop = 0;
															
															$newseatstyle = $seatstyle.'left: '.$sleft.'px; top: '.$stop.'px;';
															
															if(in_array($table->label .".".$seat, $bookedSeats))
																$newseatstyle .= 'background: '.$seatbookedcolor.';';
															else
																$newseatstyle .= 'background: '.$type[0]->seatbg .';';
															?><span id="seat<?php echo esc_attr($table->label.$seat) ?>" data-id="<?php echo esc_attr($seat) ?>" style="<?php echo esc_attr($newseatstyle) ?>" class="scwatbwsr_map_tables_table_seat per<?=$price->type?> <?php if(in_array($table->label .".".$seat, $bookedSeats)) echo "seatbooked" ?>"><?php echo esc_attr($seat) ?></span><?php
														}
													}
												?>
												</span>
												<span style="<?php echo esc_attr($labelStyle) ?>" class="scwatbwsr_map_tables_table_label"><?php echo esc_attr($table->label) ?></span>
											</span>
											<?php
										}
									}
								?>
							</div>
							</div>
						</div>
					</div>
				</div>			
			</div>
            <?php endif;
							} // check schedules available
							  }
							}
							?>
			<div class="mainpage-seats">
				<div class="scwatbwsr_form">
					
					<div class="scwatbwsr_sendform">
					<?php 
			if(@$options["enabled_payment"]=="on"):?>
							<div class="scwatbwsr_types">
				<div>
					<label class="form-label form-label-top" id="" for="input"> My Tables  </label>
				</div>
				<?php

					if($types){
						foreach($types as $type){
							$getPriceSql = $wpdb->prepare("SELECT * from {$pricesTb} where typeid=%d", $type->id);
							$price = $wpdb->get_results($getPriceSql);
							?>
							<div class="scwatbwsr_types_item">
								<div class="scwatbwsr_types_item_name">
									<p class="card-para-style"><b><?php echo esc_attr($type->name) ?></b></p>
									<!-- <span class="scwatbwsr_types_item_bg" >bg</span> -->
									<?php
									if($price && $price[0]->price){
										if($price[0]->type == "seat") $pricetype = esc_html__("per seat", "scwatbwsr-translate");
										elseif($price[0]->type == "table") $pricetype = esc_html__("per table", "scwatbwsr-translate");
										elseif($price[0]->type == "time") $pricetype = esc_html__("one time for all", "scwatbwsr-translate");
									?>
									<div class="scwatbwsr_types_item_price card_pice_scw">(<?php echo esc_attr($currencyS.$price[0]->price ." ".$pricetype) ?>)</div>
								<?php } ?>
								</div>								
							</div>
							<?php
						}
					}
				?>
				<div class="scwatbwsr_types_item">
					<div class="scwatbwsr_types_item_name">
					<p class="card-para-style"><b><?php echo esc_html__("Booked Tables", "scwatbwsr-translate") ?></b></p>
					<div class="scwatbwsr_types_item_price card_pice_scw total_seats_count">Total Seats : 0</div>
				</div>

				<div class="scwatbwsr_total">
					<span class="total_price_card"><?php echo esc_html__("Total:", "scwatbwsr-translate") ?></span>
					<span class="scwatbwsr_total_value"><?php echo esc_html__("$", "scwatbwsr-translate") ?>0.00</span>
				</div>
			</div>
			
			            <?php endif;?>
						<?php 
			if(@$options["enabled_payment"]=="on"):?>
						<div class="scwatbwsr_form_item_payment scw_form_payment">
						<label><?php echo esc_html__("Billing Address", "scwatbwsr-translate") ?></label>
							<div class="payment_field_scw">
								<div>
								<label><?php echo esc_html__("First Name", "scwatbwsr-translate") ?></label>
									<input class="billing_first_name" required name="billing_first_name" type="text" >
									
								</div>
								<div>
								<label><?php echo esc_html__("Last Name", "scwatbwsr-translate") ?></label>
									<input class="billing_last_name" required name="billing_last_name" type="text" >
								
								</div>
							</div>
							<div class="payment_addr_field_scw">
								
								<label><?php echo esc_html__("Street Address", "scwatbwsr-translate") ?></label>
								<input class="billing_address_1" required name="billing_address_1" type="text" >
							</div>
							<div class="payment_addr_field_scw">
								
								<label><?php echo esc_html__("Street Address Line 2", "scwatbwsr-translate") ?></label>
								<input class="billing_address_2" name="billing_address_2" type="text" >
							</div>
							<div class="payment_field_scw">
								<div>
									
									<label><?php echo esc_html__("City", "scwatbwsr-translate") ?></label>
									<input class="billing_city" required name="billing_city" type="text" >
								</div>
								<div>
									
										<label><?php echo esc_html__("State / Province", "scwatbwsr-translate") ?></label>
										<input class="billing_state" required name="billing_state" type="text" >
									</div>
								</div>
							<div class="payment_field_scw">
								<div>
									
									<label><?php echo esc_html__("Postal / Zip Code", "scwatbwsr-translate") ?></label>
									<input class="billing_postcode" required name="billing_postcode" type="text">
								</div>
								<div>
								<label><?php echo esc_html__("Country", "scwatbwsr-translate") ?></label>
									<select name="country" class="billing_country" required  id="country">
										<option value="">Please Select</option>
										<?php foreach($countries_list as $val=>$txt){?>
										<option value="<?=$val?>"><?=$txt?></option>
										<?php  }   ?>
									</select>
									
								</div>
							</div>
							<div class="payment_field_scw">
								
								<div>
								
									<label><?php echo esc_html__("Emaill", "scwatbwsr-translate") ?></label>
									<input class="billing_email" type="text"  required name="billing_email" autocomplete="off">
									
								</div>
								<div>
									
									<label><?php echo esc_html__("Phone Number", "scwatbwsr-translate") ?></label>
									<input class="billing_phone" type="text"  required name="billing_phone" autocomplete="off">
								</div>
							</div>
						</div>
						<?php endif;?>
						
						
					</div>
				</div>
			</div>
		    
		</div>
		    <div class="scwatbwsr_form_item scwform_submi mt-3"><button type="submit"><?php echo esc_html__("Submit", "scwatbwsr-translate") ?></button></div>
			</form>
		    </div>
	    </div>
		<?php
		
	}
	else
	{
     ?>
	 <div class="scw_front_content">
			<div class="scwatbwsr_content page">
			<div class="alert-error">
				<span class="closebtn-error" onclick="this.parentElement.style.display='none';">&times;</span> 
				<strong>Danger!</strong> Booking is closed.
			</div>
	        </div>
	</div>
	
	 <?php
	}
	     $string = ob_get_contents();
		ob_end_clean();
		$content .= $string;
	return $content;
}