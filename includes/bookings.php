<?php 
    global $wpdb;
    $roomsTb = $wpdb->prefix . 'scwatbwsr_rooms';
	$ordersTb = $wpdb->prefix . 'scwatbwsr_orders';
	$proTb = $wpdb->prefix . 'scwatbwsr_products';
	$getRoomsSql = $wpdb->prepare("SELECT * from {$roomsTb} where %d", 1);
	$rooms = $wpdb->get_results($getRoomsSql);
	if($rooms){
		$room=$rooms[0];
		$getProSql = $wpdb->prepare("SELECT * from {$proTb} order by id desc");
									$pro = $wpdb->get_results($getProSql);
									if($pro){
										$proid = $pro[0]->proid;
										$getOrdersSql = $wpdb->prepare("SELECT * from {$ordersTb} where productId=%d", $proid);
										$orders = $wpdb->get_results($getOrdersSql);
									}else $orders = "";
	?>
	<h2><?php echo esc_html__("Table Bookings", "scwatbwsr-translate") ?></h2>
	<div class="wrap">
		<div class="scwatbwsr_content">
			<div><?=settings_errors()?></div>
        </div>
		<div class="scwatbwsr_content mb-3">
					<?php adminMenuPage()?>
        </div>
		<div class="scwatbwsr_content">
					<div class="scwatbwsr_orders" id="scw-bookings-table">
														<!-- Restaurant booking -->
														
														<form id="rtb-booked-table" class="scw_booked_seat" method="POST" action="">
															<input type="hidden" name="post_type" value="" />
															<input type="hidden" name="page" value="rtb-bookings">
															
															<div class="rtb-primary-controls clearfix">
																<div class="rtb-views"></div>
															</div>
															
															<!-- top list -->
															<div class="toplist-scw">
																<ul class="top-list-left">
																	<li>Upcoming</li>
																	<li class="line"></li>
																	<li>Today</li>
																	<li class="line"></li>
																	<li>Past</li>
																	<li class="line"></li>
																	<li>All</li>
																	<li class="line"></li>
																	<li>Specific Date(s)/Time</li>
																	<li class="line"></li>
																	<li>
																		<div class="search-container">
																			<form action="/action_page.php">
																				<input type="text" placeholder="Search.." name="search" class="search-texticon-scw" autocomplete="off">
																				<button type="submit" class="search-icon-scw"><i class="fa fa-search"></i></button>
																			</form>
																		</div>
																	</li>
																</ul>
																<ul class="top-list-right">
																	<li>All <span>(0)</span></li>
																	<li class="line"></li>
																	<li>Closed <span>(0)</span></li>
																	<li class="line"></li>
																	<li>Confirmed <span>(0)</span></li>
																	<li class="line"></li>
																	<li>pending <span>(0)</span></li>
																	<li class="line"></li>
																	<li>Trash <span>(0)</span></li>
																</ul>
															</div>
															<!-- top list -->
															<!-- notification -->
															<div class="rtb-notice ">
																Only upcoming bookings are being shown.</>
															</div>
															<!-- notification -->
														
														<!-- Restaurant booking -->
														<table class="wp-list-table widefat fixed striped table-view-list bookings">
														<thead>
	<tr>
		<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
		<td scope="col" id="date" class="manage-column column-date column-primary sortable asc"><a href="http://localhost/booking-app/wp-admin/admin.php?page=rtb-bookings&amp;orderby=date&amp;order=desc"><span>Date</span><span class="sorting-indicator"></span></a></td>
		<td scope="col" id="id" class="manage-column column-id sortable asc"><a href="http://localhost/booking-app/wp-admin/admin.php?page=rtb-bookings&amp;orderby=ID&amp;order=desc"><span>ID</span><span class="sorting-indicator"></span></a></td>
		<td scope="col" id="party" class="manage-column column-party">Party</td>
		<td scope="col" id="name" class="manage-column column-name sortable asc"><a href="http://localhost/booking-app/wp-admin/admin.php?page=rtb-bookings&amp;orderby=title&amp;order=desc"><span>Name</span><span class="sorting-indicator"></span></a></td>
		<td scope="col" id="email" class="manage-column column-email">Email</td>
		<td scope="col" id="address" class="manage-column column-address">Address</td>
		<td scope="col" id="phone" class="manage-column column-phone">Phone</td>
		<td scope="col" id="note" class="manage-column column-note">Notes</td>
		<td scope="col" id="total" class="manage-column column-total">Total</td>
		<td scope="col" id="status" class="manage-column column-status sortable asc"><a href="http://localhost/booking-app/wp-admin/admin.php?page=rtb-bookings&amp;orderby=status&amp;order=desc"><span>Payment Status</span><span class="sorting-indicator"></span></a></td>
		<td scope="col" id="details" class="manage-column column-details">Booking Status</td>	
	</tr>
	</thead>
	<tbody id="the-list" data-wp-lists="list:booking">
														<?php
															if($orders){
																foreach($orders as $order){
																	?>
																<tr class="pending">
																
																		<th scope="row" class="check-column">
																			<input class="scwatbwsr_orders_item_oid" type="checkbox" name="bookings[]" value="<?php echo esc_attr($order->id) ?>">
																			<input class="scwatbwsr_orders_item_oid" type="hidden" value="<?php echo esc_attr($order->id) ?>">
																		</th>
																		<td class="date column-date has-row-actions column-primary" data-colname="Date">
                                                                            <?php if($order->schedule) echo esc_attr($order->schedule) ?>
                                                                            <div class="status"><span class="spinner"></span> Loading</div><div class="actions"><a href="#" data-id="35" data-action="edit">Edit</a> | <a href="#" class="trash" data-id="35" data-action="trash">Trash</a></div><button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td>
																		<td class="id column-id" data-colname="ID"><span class="scwatbwsr_orders_item_id">
																		<?php if($order->orderId){ ?>
																			<a target="_blank" href="<?php echo get_site_url() ?>/wp-admin/post.php?post=<?php echo esc_attr($order->orderId) ?>&action=edit"><?php echo esc_attr($order->orderId) ?></a>
																		<?php }else echo  esc_attr($order->id) ?>
																		</span></td>
																		<td class="party column-party" data-colname="Party"><?php echo esc_attr(str_replace(",", " ", $order->seats)) ?></td>
																		<td class="name column-name" data-colname="Name"><?php if($order->name){ ?>
																		<span class="scwatbwsr_orders_item_name"><?php if($order->name) echo esc_attr($order->name) ?></span>
																		<?php } ?>
																	    </td>
																		<td class="email column-email" data-colname="Email"><?php if($order->email){ ?>
																		<span class="scwatbwsr_orders_item_email"><?php if($order->email) echo esc_attr($order->email) ?></span>
																		<?php } ?><div class="actions"><a href="#" data-id="35" data-action="email" data-email="<?=$order->email?>" data-name="<?=$order->name?>">Send Email</a></div></td>
																		<td class="email column-address" data-colname="Address"><?php if($order->address){ ?>
																		<span class="scwatbwsr_orders_item_address"><?php if($order->address) echo esc_attr($order->address) ?></span>
																		<?php } ?>
																		</td>
																		<td class="email column-phone" data-colname="Phone">
																		<?php if($order->phone){ ?>
																		<span class="scwatbwsr_orders_item_phone"><?php if($order->phone) echo esc_attr($order->phone) ?></span>
																		<?php } ?>
																		</td>
																		<td class="email column-note" data-colname="Note">
																		<?php if($order->note){ ?>
																		<span class="scwatbwsr_orders_item_note"><?php if($order->note) echo esc_attr($order->note) ?></span>
																		<?php } ?>
																		</td>
																		<td class="email column-total" data-colname="Total">
																		<?php if($order->total){ ?>
																		<span class="scwatbwsr_orders_item_total"><?php if($order->total) echo esc_attr("$".$order->total) ?></span>
																		<?php } ?>
																		</td>
																		<td class="email column-status" data-colname="Status">
																		 <?=$order->_ipp_status?>
																		</td>
																		<td class="email column-action" data-colname="Actions">
                                                                        <?=$order->booking_status?>
																		</td>	
																	</tr>
																	<?php
																}
															}
														?>
		                                            </tbody>
	</table>
    </form>
														</div>
									
				
		</div>
	</div>
	<?php
	}