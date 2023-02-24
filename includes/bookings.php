<style type="text/css">
    /* Bookings Admin List Table */
    .rtb-admin-bookings-filters-start,
.rtb-admin-bookings-filters-end {
  position: relative;
  float: left;
  width: 100%;
  margin: 0 0 4px;
}
.rtb-admin-bookings-filters-start input,
.rtb-admin-bookings-filters-end input {
  position: relative;
  float: left;
  width: calc(50% - 4px);
  margin: 0 2px 4px;
  border: 1px solid #ddd;
  background: #f5f5f5;
  color: #555;
  border-radius: 2px;
}
.rtb-admin-bookings-filters-start input::placeholder,
.rtb-admin-bookings-filters-end input::placeholder {
  color: #555;
}


.bookings_page_rtb-settings .wrap h1 {
  width: 100%;
}
#rtb-bookings-table .rtb-primary-controls {
	margin-top: 2em;
}
#rtb-bookings-table .rtb-primary-controls {
    min-height: 50px;
    height:auto;
}
#rtb-bookings-table .subsubsub {
	float: none;
	margin: 0.5em 0 1em;
	text-align: left;
}

#rtb-bookings-table  .subsubsub .trash a {
	color: #a00;
}

#rtb-bookings-table  .subsubsub .trash a:hover {
	color: red;
}

#rtb-filters {
	position: relative;
}

#rtb-filters .date-filters {
	position: absolute;
	top: -9999px;
	left: -9999px;
	display: inline-block;
	padding: 12px;
  background: #fff;
  box-sizing: border-box;
	-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
	box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}

#rtb-filters.date-filters-visible .date-filters {
	position: relative;
	top: auto;
  left: 0;
  width: 300px;
}

#rtb-filters .date-filters .datepicker {
	max-width: 10em;
}

#rtb-filters .date-filter-range {
	padding: 0.25em;
}

#rtb-date-filter-link {
	position: relative;
}

#rtb-date-filter-link .dashicons {
	line-height: 1.5;
}

#rtb-filters .date-filter-range + #rtb-date-filter-link {
	margin-left: 0.5em;
}

#rtb-date-filter-link .rtb-date-filter-label {
	position: absolute;
	top: -9999px;
	left: -9999px;
}

#rtb-filters.date-filters-visible #rtb-date-filter-link {
	color: #777;
}

#rtb-filters.date-filters-visible #rtb-date-filter-link:before {
	content: '';
	position: absolute;
	top: 60%;
	left: 50%;
	margin-left: -1em;
	width: 0;
	height: 0;
	border: 1em solid transparent;
	border-bottom: 1em solid #fff;
}

#rtb-filters .current {
	font-weight: 600;
	color: #000;
}

#rtb-filters li.filter_name input {
	width: 100%;
	height: 30px;
	max-width: 150px;
	padding: 0 0.5em;
	margin: 0 0 0 0.2em;
	border-radius: 5px 0 0 5px;
}

#rtb-filters li.filter_name a {
	height: 28px;
	padding: 0 0.2em;
	display: inline-block;
	vertical-align: bottom;
	border: 1px solid #8c8f94;
	border-left: 0;
	border-radius: 0 4px 4px 0;
}

#rtb-filters li.filter_name .dashicons {
	vertical-align: middle;
}

#rtb-bookings-table .tablenav .actions .button {
	margin-top: 1px;
	margin-bottom: 1px;
}

#rtb-bookings-table .tablenav .actions .button .dashicons {
	line-height: 28px;
}

#rtb-bookings-table .rtb-notice,
#rtb-bookings-table .rtb-top-actions-wrapper,
#rtb-bookings-table .rtb-table-header-controls {
	margin: 1em 0;
}

#rtb-bookings-table .rtb-table-header-controls {
	margin-bottom: 0;
}

#rtb-bookings-table .rtb-notice {
	padding: 1em;
	background: #2ea2cc;
	color: #fff;
    clear:both;
}

#rtb-bookings-table .rtb-table-header-controls {
	position: relative;
	top: 1px;
	overflow: hidden;
	margin-bottom: 0.5em;
}

#rtb-bookings-table select[name="action"],
#rtb-bookings-table select[name="action2"],
#rtb-bookings-table .rtb-location-switch select {
	max-width: 120px;
}

#rtb-bookings-table .rtb-table-header-controls .bulkactions {
	float: left;
}

#rtb-bookings-table .rtb-locations {
	display: none;
	margin: 0;
	width: 9999px;
}

#rtb-bookings-table .rtb-locations li {
	display: inline-block;
	margin: 0;
	line-height: 38px;
	position: relative;
}

#rtb-bookings-table .rtb-locations a {
	display: block;
	padding: 0 1em;
	font-weight: 700;
	color: #777;
	text-decoration: none;
}

#rtb-bookings-table .rtb-locations .current a {
	background: #fff;
	color: #333;
	border: 1px solid #ddd;
	border-bottom: 0;
}

#rtb-bookings-table .rtb-location-switch {
	float: left;
}

#rtb-bookings-table .rtb-locations-button {
	margin-bottom: 0;
}

#rtb-bookings-table tr.closed {
	opacity: 0.6;
	filter: opacity(alpha=60);
}
#rtb-bookings-table tr.closed:hover {
	opacity: 1;
	filter: opacity(alpha=100);
}

#rtb-bookings-table .striped tr {
  background-color: #eaeaeac7;
}

#rtb-bookings-table .striped tr.alternate {
  background-color: #f6f7f7;
}

#rtb-bookings-table tr.pending .check-column {
  border-left: 4px solid #dd3d36;
}

#rtb-bookings-table .striped>tbody>tr.pending {
  background-color: rgba(255,0,0,0.35);
}
#rtb-bookings-table .striped>tbody>tr.pending.alternate {
  background-color: rgba(255,0,0,0.25);
}

#rtb-bookings-table tr:is(.pending, .payment_pending, .payment_failed) .check-column input[type=checkbox] {
  margin-left: 4px;
}

#rtb-bookings-table tr:is(.payment_pending, .payment_failed) .check-column {
  border-left: 4px solid #cca322;
}

#rtb-bookings-table .striped>tbody>tr:is(.payment_pending, .payment_failed) {
  background-color: rgba(255,197,7,0.55);
}
#rtb-bookings-table .striped>tbody>tr:is(.payment_pending, .payment_failed).alternate {
  background-color: rgba(255,197,7,0.35);
}

#rtb-bookings-table table.bookings > tbody > tr.payment_failed .column-status::before {
  content: "\f14c";
  font-family: dashicons;
  padding: 0 5px 0 0;
  vertical-align: top;
}

#rtb-bookings-table table.bookings > tbody > tr.payment-on-hold .column-status::before {
  content: "\f18c";
  font-family: dashicons;
  padding: 0 5px;
  vertical-align: top;
  display: inline-block;
}

#rtb-bookings-table .striped>tbody>tr.confirmed {
	background-color: rgba(46,162,204,0.35);
}
#rtb-bookings-table .striped>tbody>tr.confirmed.alternate {
	background-color: rgba(46,162,204,0.25);
}

#rtb-bookings-table th#date {
	width: auto;
}

#rtb-bookings-table th#party {
	width: 3em;
}

#rtb-bookings-table th#details {
	width: 4em;
}

#rtb-bookings-table td .actions {
	line-height: 1.5em;
	opacity: 0;
	-webkit-transition: opacity 0.3s 0;
	-moz-transition: opacity 0.3s 0;
	transition: opacity 0.3s 0;
}

#rtb-bookings-table tr:hover td .actions {
	opacity: 1;
}

#rtb-bookings-table .column-date .actions .trash,
#rtb-bookings-table .actions [data-action="delete"] {
	color: #a00;
}

#rtb-bookings-table .column-date .actions .trash:hover,
#rtb-bookings-table .actions [data-action="delete"]:hover {
	color: red;
}

#rtb-bookings-table .column-date .status {
	width: 0;
	height: 0;
	overflow: hidden;
	line-height: 28px;
	opacity: 0;
	-webkit-transition: opacity 0.6s 0;
	-moz-transition: opacity 0.6s 0;
	transition: opacity 0.6s 0;
}

#rtb-bookings-table .column-date .status .spinner {
	visibility: visible;
	display: inline-block;
	float: left;
	margin: 4px 4px 0 0;
	vertical-align: middle;
}

#rtb-bookings-table .column-date.loading .actions {
	display: none;
}

#rtb-bookings-table .column-date.loading .status {
	width: auto;
	height: auto;
	overflow: visible;
	opacity: 0.5;
}

#rtb-bookings-table tr ul {
	margin: 0;
}

#rtb-bookings-table .consent {
	margin-top: 0.5em;
}

.rtb-details-data {
	display: none;
}

.rtb-details-data .details {
	margin: 0;
}

.rtb-details-data .details > li {
	margin-top: 2em;
}

.rtb-details-data .details > li:first-child {
	margin-top: 0;
}

.rtb-details-data .details .label {
	font-weight: 900;
}

#rtb-details-modal .rtb-details-data {
	display: block;
}

@media screen and (min-width: 783px) {

	#rtb-bookings-table .rtb-locations li {
		line-height: 32px;
	}
}

@media screen and (min-width: 930px) {

	#rtb-bookings-table .rtb-primary-controls {
		margin-top: 0;
	}

	#rtb-bookings-table .rtb-views {
		float: right;
		width: 50%;
	}

	#rtb-bookings-table .rtb-views .subsubsub {
		text-align: right;
	}

	#rtb-filters {
		float: left;
		width: 50%;
	}

	#rtb-filters .date-filters {
		margin-right: 2em;
	}

	#rtb-bookings-table .rtb-locations {
		display: block;
	}

	#rtb-bookings-table .rtb-table-header-controls {
		margin-bottom: 0;
	}

	#rtb-bookings-table .rtb-location-switch {
		position: absolute;
		top: 0;
		right: 0;
		background: #eee;
		padding-left: 1em;
	}
}

@media screen and (max-width: 782px) {

	/* Prevent date column from being hidden in WP versions < 4.3 */
	#rtb-bookings-table .fixed .column-date {
		display: table-cell;
	}

	/* Always display details data in mobile views */
	#rtb-bookings-table .column-details .rtb-details-data {
		display: block;
	}

	#rtb-bookings-table .column-details .rtb-show-details {
		display: none;
	}
}
</style>
<?php 
/*
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
    */