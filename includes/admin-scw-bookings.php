<input type="hidden" value="<?php echo esc_attr(get_option('date_format')) ?>" class="scw_date_format">
<div class="wrap">
	<div class="scwatbwsr_content">
		<div><?= settings_errors() ?></div>
	</div>
	<div class="scwatbwsr_content mb-3">
		<?php adminMenuPage() ?>
	</div>
	<?php if (@$_GET['type'] == 'view' && @$_GET['booking_id'] != '') {
		$order  = orderGet($_GET['booking_id']);
		$reload = '
			<div class="scwatbwsr_schedules_spec_reload">
			<a  href="admin.php?page=scwatbwsr-table-bookings"><span class="scwatbwsr_schedules_spec_add_reload" style="width:150px">Refresh Bookings <i class="fa fa-refresh" aria-hidden="true"></i></span></a>
			</div>';
		if (!$order) {
			$output = "<div class='scwatbwsr_content pd-10'><div id='setting-error-settings_updated' class='notice notice-error mb-3'> \n";
			$output .= "<p><strong>Booking is not found 404!</strong></p>";
			$output .= "</div>\n";
			$output .= "$reload</div> \n";
			echo $output;
		} else {
	?>
			<div class="scwatbwsr_content pd-10">
				<h2>Booking Information</h2>
				<input type="hidden" id="booking_view_booking_id" value="<?= $order->id ?>" />
				<div class="content-area-left">
					<div class="promotional-title">
						<h2>Order Date</h2>
					</div>
					<div class="container__item">
						<h2 id="booking_data_schedule"><?= date("l d Y, H:i", strtotime($order->schedule)) ?></h2>
					</div>
				</div>
				<div class="content-area-left">
					<div class="promotional-title">
						<h2>Phone</h2>
					</div>
					<div class="container__item">
						<h2><?= $order->phone ?></h2>
					</div>
				</div>
			</div>

			<div class="scwatbwsr_content">
				<div class="cart-content">
					<div class="container">
						<!-- Start Table Cart -->
						<table id="cart" class="table table-hover table-condensed">
							<thead>
								<tr>
									<th style="width: 15%;font-size: 18px;">Name</th>
									<th style="width:30%;font-size: 18px;">No Seats</th>
									<th style="width:15%;font-size: 18px;">Email</th>
									<th class="text-center" style="width:30%;font-size: 18px;">Message</th>

								</tr>
							</thead>
							<tbody>
								<tr>
									<td data-th="Product">
										<div class="row">

											<div class="col-sm-12">
												<h4 class="nomargin"><?= $order->name ?></h4>
											</div>
										</div>
									</td>
									<td data-th="Size">
										<div class="col-sm-12">
											<h4 class="nomargin"><?= $order->seats ?></h4>
										</div>
									</td>

									<td data-th="Quantity">
										<div class="row">
											<div class="col-sm-12">
												<h4 class="nomargin"><?= $order->email ?></h4>
											</div>
										</div>
									</td>
									<td data-th="Subtotal" class="text-center" style="color:hsl(11, 71%, 50%);font-size: 18px;"><?= $order->note ?></td>

								</tr>
							</tbody>
						</table>
						<!-- End Table Cart -->
						<div class="content-area-left">
							<div class="promotional-title">
								<h2>Change Status</h2>
							</div>
							<div class="container__item">
								<form class="form promo-left">
									<select id="booking_view_change_status_select" class="size-form">
										<?php foreach ($booking_status as $st => $status) { ?>
											<option value="<?= $st ?>" <?php if ($st == $order->booking_status) echo "selected='selected'"; ?>><?= $status['label'] ?></option>
										<?php } ?>
									</select>
									<button type="button" class="btn--primary btn--inside" id="booking_view_change_status_button">Apply</button>
								</form>

							</div>
						</div>
						<div class="content-area-left">
							<div class="promotional-title">
								<h2>Change Time</h2>
							</div>
							<div class="example-container">
								<div>
									<input type="text" name="alt_example_4_alt" id="alt_example_4_alt" value="" style="cursor: pointer;">
									<br />
									<button type="button" class="btn--primary btn--inside" id="booking_view_change_schedule">Apply</button>
								</div>

							</div>
						</div>
						<div class="content-area">
							<!-- Start Booking -->
							<div class="booking">
								<h2>Select Table & Seats</h2>
							</div>
							<div class="row note">
								<div class="col-md-2 col-sm-4 col-xs-4">
									<h2>Available Seat</h2>
									<div class="available">1</div>
								</div>
								<div class="col-md-2 col-sm-4 col-xs-4">
									<h2>Booked Seat</h2>
									<div class="booked">1</div>
								</div>
								<div class="col-md-2 col-sm-4 col-xs-4">
									<h2>Selected Seat</h2>
									<div class="selected-seat">1</div>
								</div>
							</div>
							<div class="row note">
								<div class="cart-total-row mt-3">
									<div class="total-order">
										<p class="cart-total-title">Confirm Tables and Seats</p>
										<p class="cart-sub"><strong>Total Required Seats </strong><span id="req_seat"><?= $order->no_seats ?></span></p>
										<p class="cart-ship"><strong>Selected Table</strong><span id="total-table"><?= $order->_ipp_tax ?></span></p>
										<p class="cart-ship"><span id="total-table-list"><?= $order->_ipp_tax ?></span></p>
										<p class="cart-order"><strong>Selected Seats</strong><span id="total-seats">$<?= $order->total ?></span></p>
									</div>
									<button type="button" class="submit-button-table btn--primary btn--inside">Update Table</button>
								</div>

							</div>
						</div>
						<div class="content-area">
							<div class="model">
								<?php
								$roomsTB = $wpdb->prefix . 'scwatbwsr_rooms';
								$typesTB = $wpdb->prefix . 'scwatbwsr_types';
								$schedulesTB = $wpdb->prefix . 'scwatbwsr_schedules';
								$dailyschedulesTB = $wpdb->prefix . 'scwatbwsr_dailyschedules';
								$dailytimesTB = $wpdb->prefix . 'scwatbwsr_dailytimes';
								$pricesTB = $wpdb->prefix . 'scwatbwsr_prices';
								$tablesTB = $wpdb->prefix . 'scwatbwsr_tables';
								$seatsTB = $wpdb->prefix . 'scwatbwsr_seats';
								$productsTb = $wpdb->prefix . 'scwatbwsr_products';
								$ordersTB = $wpdb->prefix . 'scwatbwsr_orders';
								$bookedTB = $wpdb->prefix . 'scwatbwsr_bookedseats';

								$getTypesSql = $wpdb->prepare("SELECT * from {$typesTB} where roomid>%d", 0);
								$types = $wpdb->get_results($getTypesSql);
								foreach ($types as $type) {
									$getTablesSql = $wpdb->prepare("SELECT * from {$tablesTb} where type=%d", $type->id);
									$arrayClass = range('A', 'Z');
									$tables = $wpdb->get_results($getTablesSql);
									$getPriceSql = $wpdb->prepare("SELECT * from {$pricesTB} where typeid=%d", $type->id);
									$price = $wpdb->get_row($getPriceSql);
								?>
									
									<div class="col-md-2 col-sm-6 col-xs-6 column1">
										<?php
										foreach ($tables as $key => $table) {
											$seats = explode(",", $table->seats);
											$seatsC = count($seats);
											$arrNumb = range(1, $seatsC);
											$even = range(0, count($arrNumb), 2);
											$add = range(1, count($arrNumb), 2);

										?>
											<div class="table-<?= $arrayClass[$key] ?> table-list">
												<div class="chart-status">
													<h2><i class="fa-solid fa-plus-large"></i>Booked</h2>
												</div>
												<div class="chart-left">
													<?php foreach ($add as $i => $a) {
														if ($a > 0) { ?>
															<div id="seat-<?= $a ?>" data-seat="<?= $a ?>" class="chart1 <?= $price->type ?> selected-color"><?= $a ?>
																<i class="fa fa-check-circle"></i>
															</div>
													<?php }
													} ?>
												</div>
												<div data-id="<?= $table->id ?>" data-name="<?= $table->label ?>" class="name-table <?= $price->type ?>">
													<h2><i class="fa fa-check-circle"></i><?= $table->label ?></h2>
												</div>
												<div class="chart-right">
													<?php foreach ($even as $i => $a) {
														if ($a > 0) { ?>
															<div id="seat-<?= $a ?>" data-seat="<?= $a ?>" class="chart1 <?= $price->type ?> selected-color"><?= $a ?>
																<i class="fa fa-check-circle"></i>
															</div>
													<?php }
													} ?>
												</div>

											</div>
										<?php  } ?>
									</div>

								<?php } ?>
								<button type="button" class="submit-button-bottom btn--primary btn--inside">Update Table</button>

							</div>

						</div>
						<!-- End Booking -->

						<!-- Start cart total -->
						<?php if ($order->tran_id > 0 || $order->total == 0) { ?>
							<div class="content-area clear pd-10">
								<div class="booking mb-3">
									<h2>Payment Information</h2>
								</div>
								<div class="cart-total mt-3">
									<div class="total-order">
										<p class="cart-total-title">Price</p>
										<p class="cart-sub"><strong>Cart Sub Total </strong><span>$<?= number_format($order->total - $order->_ipp_tax, 2) ?></span></p>
										<p class="cart-ship"><strong>Tax </strong><span><?= $order->_ipp_tax ?></span></p>
										<p class="cart-order"><strong>Order Total </strong><span>$<?= $order->total ?></span></p>
									</div>
								</div>
								<div class="cart-total mt-3">
									<div class="total-order">
										<p class="cart-total-title">Payment Status</p>
										<p class="cart-sub"><strong>Status </strong><span><?= $order->_ipp_status ?></span></p>
										<p class="cart-ship"><strong>Change Status </strong></p>
										<p class="cart-order">
											<select class="size-form" id="booking_view_payment_status_select">
												<?php
												$payment_status = ['Process', 'Pending', 'Completed'];
												foreach ($payment_status as $st => $status) { ?>
													<option value="<?= $status ?>" <?php if ($status == $order->_ipp_status) echo "selected='selected'"; ?>><?= $status ?></option>
												<?php } ?>
											</select>
										</p>

										<button type="button" class="btn--primary btn--inside promo-right  mb-3" id="booking_view_payment_status_change">Update Payment</button>

									</div>
								</div>
								<div class="cart-total mt-3">
									<div class="total-order">
										<p class="cart-total-title">Ippayware Info</p>
										<p class="cart-sub"><strong>Transaction ID </strong><span><?= $order->_ipp_transaction_id ?></span></p>
										<p class="cart-ship"><strong>Order Number </strong><span><?= $order->orderId ?></span></p>
										<p class="cart-order"><strong>Ippayware ID </strong><span><?= $order->tran_id ?></span></p>
									</div>
								</div>
								<div class="cart-total mt-3" style="width:50%;">
									<div class="total-order">
										<p class="cart-total-title">Billing Address</p>
										<p class="cart-sub"><strong>Name</strong><span><?= $order->billing_first_name . " " . $order->billing_last_name ?></span></p>
										<p class="cart-ship"><strong>Address </strong><span><?= $order->billing_address_1 . " " . $order->billing_address_2 ?></span></p>
										<p class="cart-order"><strong>City </strong><span><?= $order->billing_city ?></span></p>
										<p class="cart-order"><strong>Zip Code</strong><span><?= $order->billing_postcode ?></span></p>
										<p class="cart-order"><strong>City </strong><span><?= $order->billing_state ?></span></p>
										<p class="cart-order"><strong>Country </strong><span><?= $order->billing_country ?></span></p>
										<p class="cart-order"><strong>Phone </strong><span><?= $order->billing_phone ?></span></p>
										<p class="cart-order"><strong>Email </strong><span><?= $order->billing_email ?></span></p>
									</div>
								</div>

								<div class="cart-total mt-3">
									<div class="total-order">
										<p class="cart-total-title">Booking Payment History</p>
										<table id="history" class="table table-hover table-condensed">
											<thead>
												<tr>
													<th>Date</th>
													<th>Amount</th>
													<th>Type</th>


												</tr>
											</thead>
											<tbody>

												<?php
												$bookingpaymenthistoryTB = $wpdb->prefix . 'scwatbwsr_booking_payment_history';

												$booking_id = $_GET['booking_id'];
												$getHistorySql = $wpdb->prepare("SELECT * from {$bookingpaymenthistoryTB} where booking_id=%d", $booking_id);

												$histories = $wpdb->get_results($getHistorySql);

												foreach ($histories as $st => $history) { ?>
													<tr>
														<td data-th="Date">
															<div class="row">

																<div class="col-sm-12">
																	<h4 class="nomargin"><?= $history->date ?></h4>
																</div>
															</div>
														</td>
														<td data-th="Amount">
															<div class="col-sm-12">
																<h4 class="nomargin"><?= $history->price ?></h4>
															</div>
														</td>

														<td data-th="Type">
															<div class="row">
																<div class="col-sm-12">
																	<h4 class="nomargin"><?= $history->payment_type ?></h4>
																</div>
															</div>
														</td>


													</tr>
												<?php } ?>
											</tbody>
										</table>
									
										
										<button type="button" class="btn--primary btn--inside promo-right  mb-3 pt-4"  data-id="<?= esc_attr($booking_id) ?>" 
										data-orderAmount="<?= esc_attr($order->total) ?>" id="add_offline_payment">Add Offline Payment</button>
									</div>
								</div>

							</div>

						<?php  } ?>
						<!-- End cart Total -->
					</div>
				</div>
			</div>
		<?php
		}
	} else {

		?>
		<div class="scwatbwsr_content pd-10">
			<h2 class="mb-3">
				<?php _e('Bookings', 'scwatbwsr-translate'); ?>

			</h2>
			<?php do_action('rtb_bookings_table_top'); ?>
			<form id="rtb-bookings-table" method="POST" action="">
				<input type="hidden" name="post_type" value="<?php echo SCW_BOOKING_POST_TYPE; ?>" />
				<input type="hidden" name="page" value="rtb-bookings">

				<div class="rtb-primary-controls clearfix">
					<div class="rtb-views">
						<?php $bookings_table->views(); ?>
					</div>
					<?php $bookings_table->advanced_filters(); ?>
				</div>

				<?php $bookings_table->display(); ?>
			</form>
			<?php do_action('rtb_bookings_table_btm'); ?>
		</div>
	<?php
	}
	?>


</div>