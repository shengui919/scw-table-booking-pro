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
	width: 20em;
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
<input type="hidden" value="<?php echo esc_attr(get_option('date_format')) ?>" class="scw_date_format">
<div class="wrap">
		<div class="scwatbwsr_content">
			<div><?=settings_errors()?></div>
        </div>
		<div class="scwatbwsr_content mb-3">
					<?php adminMenuPage()?>
        </div>
		<?php if(@$_GET['type']=='view' && @$_GET['booking_id']!='')
		{
			$order  = orderGet($_GET['booking_id']);
			$reload='
			<div class="scwatbwsr_schedules_spec_reload">
			<a  href="admin.php?page=scwatbwsr-table-bookings"><span class="scwatbwsr_schedules_spec_add_reload" style="width:150px">Refresh Bookings <i class="fa fa-refresh" aria-hidden="true"></i></span></a>
			</div>';
			if(!$order)
			{
				$output = "<div class='scwatbwsr_content pd-10'><div id='setting-error-settings_updated' class='notice notice-error mb-3'> \n";
				$output .= "<p><strong>Booking is not found 404!</strong></p>";
				$output .= "</div>\n";
				$output .= "$reload</div> \n";
				echo $output;
			}
			else
			{
		?>
            <div class="scwatbwsr_content pd-10">
              <?php echo "<h2 class='mb-3'>Order ID : $order->id</h2>";?>
		    </div>
		<?php
			}
		}
		else 
		{

		?>
		<div class="scwatbwsr_content pd-10">
		<h2 class="mb-3">
			<?php _e( 'Bookings', 'scwatbwsr-translate' ); ?>
			
		</h2>
		<?php do_action( 'rtb_bookings_table_top' ); ?>
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
		<?php do_action( 'rtb_bookings_table_btm' ); ?>
        </div>
        <?php 
		}
		?>
	</div>