<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

require_once dirname(__FILE__) . '/Query.class.php';
require_once dirname(__FILE__) . '/settings-ipp.php';
if ( !class_exists( 'scwBookingsTable' ) ) {
/**
 * Bookings Table Class
 *
 * Extends WP_List_Table to display the list of bookings in a format similar to
 * the default WordPress post tables.
 *
 * @h/t Easy Digital Downloads by Pippin: https://easydigitaldownloads.com/
 * @since 0.0.1
 */
class scwBookingsTable extends WP_List_Table {

	/**
	 * Number of results to show per page
	 *
	 * @var string
	 * @since 0.0.1
	 */
	public $per_page = 20;

	/**
	 * URL of this page
	 *
	 * @var string
	 * @since 0.0.1
	 */
	public $base_url;

	/**
	 * Array of booking counts by total and status
	 *
	 * @var array
	 * @since 0.0.1
	 */
	public $booking_counts;

	/**
	 * Array of bookings
	 *
	 * @var array
	 * @since 0.0.1
	 */
	public $bookings;

	/**
	 * Current date filters
	 *
	 * @var string
	 * @since 0.0.1
	 */
	public $filter_start_date = null;
	public $filter_end_date = null;

	/**
	 * Current time filters
	 *
	 * @var string
	 * @since 2.2.0
	 */
	public $filter_start_time = null;
	public $filter_end_time = null;

	/**
	 * Current location filter
	 *
	 * @var int
	 * @since 1.6
	 */
	public $filter_location = 0;

	/**
	 * Current name filter
	 *
	 * @var string
	 * @since 2.4.4
	 */
	public $filter_name = '';

	/**
	 * Current query string
	 *
	 * @var string
	 * @since 0.0.1
	 */
	public $query_string;

	/**
	 * Results of a bulk or quick action
	 *
	 * @var array
	 * @since 1.4.6
	 */
	public $action_result = array();

	/**
	 * Type of bulk or quick action last performed
	 *
	 * @var string
	 * @since 1.4.6
	 */
	public $last_action = '';

	/**
	 * Stored reference to visible columns
	 *
	 * @var string
	 * @since 1.5
	 */
	public $visible_columns = array();

	/**
	 * Stored reference to rtb_booking post statuses
	 *
	 * @var array
	 * @since 2.4.4
	 */
	public $booking_statuses = array();

	/**
	 * set the custom table
	 */
    private $table_data;

	private $table_name;
	/**
	 * Initialize the table and perform any requested actions
	 *
	 * @since 0.0.1
	 */
	public function __construct() {

		global $status, $page, $wpdb;
        $this->table_name = $wpdb->prefix . 'scwatbwsr_orders';
		// Set parent defaults
		parent::__construct( array(
			'singular'  => __( 'Booking', 'scwatbwsr-translate' ),
			'plural'    => __( 'Bookings', 'scwatbwsr-translate' ),
			'ajax'      => false
		) );

		$this->populate_booking_status();

		// Set the date filter
		$this->set_date_filter();
		
		// Set the name filter
		$this->set_other_filter();

		// Strip unwanted query vars from the query string or ensure the correct
		// vars are used
		$this->query_string_maintenance();

		// Run any bulk action requests
		$this->process_bulk_action();

		// Run any quicklink requests
		$this->process_quicklink_action();

		// Retrieve a count of the number of bookings by status
		$this->get_booking_counts();

		// Retrieve bookings data for the table
		$this->bookings_data();

		$this->base_url = admin_url( 'admin.php?page=scwatbwsr-table-bookings' );

		// Add default items to the details column if they've been hidden
			add_filter( 'rtb_bookings_table_column_details', array( $this, 'add_details_column_items' ), 10, 2 );
	}

	public function populate_booking_status()
	{
		$this->booking_statuses[ 'closed' ] = array( 
			'label' => __( 'Closed', 'scwatbwsr-translate' ),
			'count' => _n_noop(
				'Closed <span class="count">(%s)</span>', 
				'Closed <span class="count">(%s)</span>', 
				'scwatbwsr-translate' 
			)[ 'singular' ]
		);
		$this->booking_statuses[ 'confirmed' ] = array( 
			'label' => __( 'Confirmed', 'scwatbwsr-translate' ),
			'count' => _n_noop(
				'Confirmed <span class="count">(%s)</span>', 
				'Confirmed <span class="count">(%s)</span>', 
				'scwatbwsr-translate' 
			)[ 'singular' ]
		);
		$this->booking_statuses[ 'pending' ] = array( 
			'label' => __( 'Pen', 'scwatbwsr-translate' ),
			'count' => _n_noop(
				'Pending <span class="count">(%s)</span>', 
				'Pending <span class="count">(%s)</span>', 
				'scwatbwsr-translate' 
			)[ 'singular' ]
		);
		$this->booking_statuses[ 'all' ] = array( 
			'label' => __( 'All', 'scwatbwsr-translate' ),
			'count' => _n_noop(
				'All <span class="count">(%s)</span>', 
				'All <span class="count">(%s)</span>', 
				'scwatbwsr-translate' 
			)[ 'singular' ]
		);
		$this->booking_statuses[ 'trash' ] = array( 
			'label' => __( 'Trash', 'scwatbwsr-translate' ),
			'count' => _n_noop(
				'Trash <span class="count">(%s)</span>', 
				'Trash <span class="count">(%s)</span>', 
				'scwatbwsr-translate' 
			)[ 'singular' ]
		);
	}

	/**
	 * Set the correct date filter
	 *
	 * $_POST values should always overwrite $_GET values
	 *
	 * @since 0.0.1
	 */
	public function set_date_filter( $start_date = null, $end_date = null, $start_time = null, $end_time = null ) {

		if ( !empty( $_GET['action'] ) && $_GET['action'] == 'clear_date_filters' ) {
			$this->filter_start_date 	= null;
			$this->filter_end_date 		= null;
			$this->filter_start_time 	= null;
			$this->filter_end_time 		= null;
		}

		$this->filter_start_date 	= $start_date;
		$this->filter_end_date 		= $end_date;
		$this->filter_start_time 	= $start_time;
		$this->filter_end_time 		= $end_time;

		if ( $start_date === null ) {
			$this->filter_start_date = !empty( $_GET['start_date'] ) ? sanitize_text_field( $_GET['start_date'] ) : null;
			$this->filter_start_date = !empty( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : $this->filter_start_date;
		}

		if ( $end_date === null ) {
			$this->filter_end_date = !empty( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : null;
			$this->filter_end_date = !empty( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : $this->filter_end_date;
		}

		if ( $start_time === null ) {
			$this->filter_start_time = !empty( $_GET['start_time'] ) ? sanitize_text_field( $_GET['start_time'] ) : null;
			$this->filter_start_time = !empty( $_POST['start_time'] ) ? sanitize_text_field( $_POST['start_time'] ) : $this->filter_start_time;
		}

		if ( $end_time === null ) {
			$this->filter_end_time = !empty( $_GET['end_time'] ) ? sanitize_text_field( $_GET['end_time'] ) : null;
			$this->filter_end_time = !empty( $_POST['end_time'] ) ? sanitize_text_field( $_POST['end_time'] ) : $this->filter_end_time;
		}
	}

	/**
	 * Set filter like name
	 *
	 * @since 2.4.4
	 */
	public function set_other_filter()
	{
		if( isset( $_GET['filter_name'] ) && ! empty( $_GET['filter_name'] ) ) {
			$this->filter_name = sanitize_text_field( $_GET['filter_name'] );
		}
	}

	/**
	 * Get the current date range
	 *
	 * @since 1.3
	 */
	public function get_current_date_range() {

		$range = empty( $this->filter_start_date ) ? _x( '*', 'No date limit in a date range, eg 2014-* would mean any date from 2014 or after', 'scwatbwsr-translate' ) : $this->filter_start_date;
		$range .= empty( $this->filter_start_date ) || empty( $this->filter_end_date ) ? '' : _x( '&mdash;', 'Separator between two dates in a date range', 'scwatbwsr-translate' );
		$range .= empty( $this->filter_end_date ) ? _x( '*', 'No date limit in a date range, eg 2014-* would mean any date from 2014 or after', 'scwatbwsr-translate' ) : $this->filter_end_date;

		return $range;
	}

	/**
	 * Strip unwanted query vars from the query string or ensure the correct
	 * vars are passed around and those we don't want to preserve are discarded.
	 *
	 * @since 0.0.1
	 */
	public function query_string_maintenance() {

		$this->query_string = remove_query_arg( array( 'action', 'start_date', 'end_date' ) );

		if ( $this->filter_start_date !== null ) {
			$this->query_string = add_query_arg( array( 'start_date' => $this->filter_start_date ), $this->query_string );
		}

		if ( $this->filter_end_date !== null ) {
			$this->query_string = add_query_arg( array( 'end_date' => $this->filter_end_date ), $this->query_string );
		}

		if ( $this->filter_start_time !== null ) {
			$this->query_string = add_query_arg( array( 'start_time' => $this->filter_start_time ), $this->query_string );
		}

		if ( $this->filter_end_time !== null ) {
			$this->query_string = add_query_arg( array( 'end_time' => $this->filter_end_time ), $this->query_string );
		}

		$this->filter_location = ! isset( $_GET['location'] ) ? 0 : intval( $_GET['location'] );
		$this->filter_location = ! isset( $_POST['location'] ) ? $this->filter_location : intval( $_POST['location'] );
		$this->query_string = remove_query_arg( 'location', $this->query_string );
		if ( !empty( $this->filter_location ) ) {
			$this->query_string = add_query_arg( array( 'location' => $this->filter_location ), $this->query_string );
		}

	}

	/**
	 * Show the time views, date filters and the search box
	 * @since 0.0.1
	 */
	public function advanced_filters() {

		// Show the date_range views (today, upcoming, all)
		if ( !empty( $_GET['date_range'] ) ) {
			$date_range = sanitize_text_field( $_GET['date_range'] );
		} else {
			$date_range = '';
		}

		// Use a custom date_range if a date range has been entered
		if ( $this->filter_start_date !== null || $this->filter_end_date !== null ) {
			$date_range = 'custom';
		}

		// Strip out existing date filters from the date_range view urls
		$date_range_query_string = remove_query_arg(
			array( 'date_range', 'start_date', 'end_date', 'filter_name' ), 
			$this->query_string
		);

		$views = array(
			'upcoming' => sprintf( 
				'<a href="%s"%s>%s</a>', 
				esc_url( 
					add_query_arg( 
						array( 'paged' => FALSE ), 
						$date_range_query_string 
					) 
				), 
				$date_range === '' ? ' class="current"' : '', 
				__( 'Upcoming', 'scwatbwsr-translate' ) ), 

			'today' => sprintf( 
				'<a href="%s"%s>%s</a>', 
				esc_url( 
					add_query_arg( 
						array( 'date_range' => 'today', 'paged' => FALSE ), 
						$date_range_query_string 
					) 
				), 
				$date_range === 'today' ? ' class="current"' : '', 
				__( 'Today', 'scwatbwsr-translate' ) ),

			'past' => sprintf( 
				'<a href="%s"%s>%s</a>', 
				esc_url( 
					add_query_arg( 
						array( 'date_range' => 'past', 'paged' => FALSE ), 
						$date_range_query_string
					)
				), 
				$date_range === 'past' ? ' class="current"' : '', 
				__( 'Past', 'scwatbwsr-translate' ) ),

			'all' => sprintf( 
				'<a href="%s"%s>%s</a>', 
				esc_url( 
					add_query_arg( 
						array( 'date_range' => 'all', 'paged' => FALSE ), 
						$date_range_query_string 
					) 
				), 
				$date_range == 'all' ? ' class="current"' : '', 
				__( 'All', 'scwatbwsr-translate' ) 
			),
		);

		if ( $date_range == 'custom' ) {
			$views['date'] = '<span class="date-filter-range current">' . $this->get_current_date_range() . '</span>';
			$views['date'] .= '<a id="rtb-date-filter-link" href="#"><span class="dashicons dashicons-calendar"></span> <span class="rtb-date-filter-label">Change date range</span></a>';
		} else {
			$views['date'] = '<a id="rtb-date-filter-link" href="#">' . esc_html__( 'Specific Date(s)/Time', 'scwatbwsr-translate' ) . '</a>';
		}

		$views['filter_name'] = sprintf( 
			'<input type="text" name="filter_name" id="filter_name" value="%s"><a href="%s"%s>%s</a>', 
			$this->filter_name,
			esc_url( 
				add_query_arg( 
					array( 'filter_name' => '', 'paged' => FALSE ), 
					$date_range_query_string 
				) 
			), 
			'' != $this->filter_name ? ' class="current"' : '', 
			'<span class="dashicons dashicons-search"></span>' 
		);

		$views = apply_filters( 'rtb_bookings_table_views_date_range', $views );
		?>

		<div id="rtb-filters">
			<ul class="subsubsub rtb-views-date_range">
				<?php
					$total = count( $views );
					$index = 1;
					foreach ($views as $class => $value) {
						$separator = $index != $total ? ' |' : '';
						echo "<li class=\"{$class}\">{$value}{$separator}</li>";
						$index++;
					}
				?>
			</ul>

			<div class="date-filters">
				<div class="rtb-admin-bookings-filters-start">
					<label for="start-date" class="screen-reader-text"><?php _e( 'Start Date:', 'scwatbwsr-translate' ); ?></label>
					<input type="text" id="start-date" name="start_date" class="datepicker" value="<?php echo esc_attr( $this->filter_start_date ); ?>" placeholder="<?php _e( 'Start Date', 'scwatbwsr-translate' ); ?>" />
					</div>	
				<div class="rtb-admin-bookings-filters-end">
					<label for="end-date" class="screen-reader-text"><?php _e( 'End Date:', 'scwatbwsr-translate' ); ?></label>
					<input type="text" id="end-date" name="end_date" class="datepicker" value="<?php echo esc_attr( $this->filter_end_date ); ?>" placeholder="<?php _e( 'End Date', 'scwatbwsr-translate' ); ?>" />
					</div>
				
				<input type="submit" class="button button-secondary" value="<?php _e( 'Apply', 'scwatbwsr-translate' ); ?>"/>
				
				<?php if( !empty( $this->filter_start_date ) || !empty( $this->filter_end_date ) || !empty( $this->filter_start_time ) || !empty( $this->filter_end_time ) ) : ?>
					<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'clear_date_filters' ) ) ); ?>" class="button button-secondary"><?php _e( 'Clear Filter', 'scwatbwsr-translate' ); ?></a>
				<?php endif; ?>

				<?php if( !empty( $_GET['status'] ) ) : ?>
					<input type="hidden" name="status" value="<?php echo esc_attr( sanitize_text_field( $_GET['status'] ) ); ?>"/>
				<?php endif; ?>

				</div>
		</div>

<?php
	}

	/**
	 * Retrieve the view types
	 * @since 0.0.1
	 */
	public function get_views() {

		$current = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '';
        $this->populate_booking_status();
		

		ksort( $this->booking_statuses );

		$views = [];

		foreach ( $this->booking_statuses as $status => $data )
		{
			$url = 'all' == $status 
				? esc_url( 
			      remove_query_arg( 
			        array( 'status', 'paged' ), 
			        $this->query_string 
			      ) 
			    )
				: esc_url( 
			      add_query_arg( 
			        array( 'status' => $status, 'paged' => FALSE ), 
			        $this->query_string 
			      ) 
			    );

			$views[ $status ] = sprintf(
		    '<a href="%s"%s>%s</a>', 
		    $url, 
		    $current === $status ? ' class="current"' : '', 
		    sprintf( $data['count'], $this->booking_counts[ $status ] )
		  );
		}

		return apply_filters( 'rtb_bookings_table_views_status', $views );
	}

	/**
	 * Generates content for a single row of the table
	 * @since 0.0.1
	 */
	public function single_row( $item ) {
	    $item = (OBJECT) $item;
		static $row_alternate_class = 'alternate';
		$row_alternate_class = ( $row_alternate_class == 'alternate' ? '' : 'alternate' );

		$row_classes = array( esc_attr( $item->booking_status ) );

		if ( !empty( $row_alternate_class ) ) {
			$row_classes[] = $row_alternate_class;
		}

		$row_classes = apply_filters( 'rtb_admin_bookings_list_row_classes', $row_classes, $item );

		echo '<tr class="' . implode( ' ', $row_classes ) . '">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}

	/**
	 * Retrieve the table columns
	 *
	 * @since 0.0.1
	 */
	public function get_columns() {

		// Prevent the lookup from running over and over again on a single
		// page load
		if ( !empty( $this->visible_columns ) ) {
			return $this->visible_columns;
		}

		$all_default_columns = $this->get_all_default_columns();
		$all_columns = $this->get_all_columns();

		
		$visible_columns = [];
		if ( empty( $visible_columns ) ) {
			$columns = $all_default_columns;
		} else {
			$columns = array();
			$columns['cb'] = $all_default_columns['cb'];
			$columns['date'] = $all_default_columns['date'];

			foreach( $all_columns as $key => $column ) {
				if ( in_array( $key, $visible_columns ) ) {
					$columns[$key] = $all_columns[$key];
				}
			}
			$columns['details'] = $all_default_columns['details'];
		}

		$this->visible_columns = apply_filters( 'rtb_bookings_table_columns', $columns );

		return $this->visible_columns;
	}

	/**
	 * Retrieve all default columns
	 *
	 * @since 1.5
	 */
	public function get_all_default_columns() {
		

		$columns = array(
			'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
			'date'     	=> __( 'Date', 'scwatbwsr-translate' ),
			'id'     	=> __( 'ID', 'scwatbwsr-translate' ),
			'party'  	=> __( 'Seats', 'scwatbwsr-translate' ),
			'table'  	=> __( 'Table', 'scwatbwsr-translate' ),
			'name'  	=> __( 'Name', 'scwatbwsr-translate' ),
			'email'  	=> __( 'Email', 'scwatbwsr-translate' ),
			'phone'  	=> __( 'Phone', 'scwatbwsr-translate' ),
			'status'  	=> __( 'Status', 'scwatbwsr-translate' ),
		);

		if ( get_settings_scw( 'enabled_payment' ) ) { $columns['deposit'] = __( 'Price', 'scwatbwsr-translate' ) ; }
		if ( get_settings_scw( 'enabled_payment' )) { $columns['table'] = __( 'Table', 'scwatbwsr-translate' ) ; } 
		

		// This is so that deposit comes before details, is there a better way to do this?
		$columns['details'] = __( 'Details', 'scwatbwsr-translate' );

		return $columns;
	}

	/**
	 * Retrieve all available columns
	 *
	 * This is used to get all columns including those deactivated and filtered
	 * out via get_columns().
	 *
	 * @since 1.5
	 */
	public function get_all_columns() {
		$columns = $this->get_all_default_columns();
		$columns['submitted-by'] = __( 'Submitted By', 'scwatbwsr-translate' );
		return apply_filters( 'rtb_bookings_all_table_columns', $columns );
	}

	/**
	 * Retrieve the table's sortable columns
	 * @since 0.0.1
	 */
	public function get_sortable_columns() {
		$columns = array(
			'id' 		=> array( 'ID', true ),
			'date' 		=> array( 'date', true ),
			'name' 		=> array( 'title', true ),
			'status' 	=> array( 'status', true ),
		);
		return apply_filters( 'rtb_bookings_table_sortable_columns', $columns );
	}

	/**
	 * This function renders most of the columns in the list table.
	 * @since 0.0.1
	 */
	public function column_default( $booking, $column_name ) {
		
       
		switch ( $column_name ) {
			case 'date' :
				$value = date("F d, Y h:i  A" ,strtotime($booking->schedule ));
				$value .= '<div class="status"><span class="spinner"></span> ' . __( 'Loading', 'scwatbwsr-translate' ) . '</div>';

				if ( $booking->booking_status !== 'trash' ) {
					$value .= '<div class="actions">';
					$value .= '<a href="javascript:editBooking('.esc_attr( $booking->id ).')" data-id="' . esc_attr( $booking->id ) . '" data-action="edit">' . __( 'Edit', 'scwatbwsr-translate' ) . '</a>';
					$value .= '</div>';
				}

				break;

			case 'id' :
				$value = $booking->id;
				break;

			case 'party' :
				$value = ( $booking->no_seats >0 ) ? $booking->no_seats : explode(",",$booking->seats);
				if(is_array($value)) $value = count($value);
				break;

			case 'name' :
				$value = esc_html( $booking->name );
				break;

			case 'email' :
				$value = esc_html( $booking->email );
				$value .= '<div class="actions">';
				$value .= '<a href="javascript:sendMail('.esc_attr( $booking->id ).')" data-id="' . esc_attr( $booking->id ) . '" data-action="email" data-email="' . esc_attr( $booking->email ) . '" data-name="' . esc_attr( $booking->name ) . '">' . __( 'Send Email', 'scwatbwsr-translate' ) . '</a>';
				$value .= '</div>';
				break;

			case 'phone' :
				$value = esc_html( $booking->phone );
				break;

			case 'deposit' :
				$currency_symbol = '$';
				$value = ( $currency_symbol ? $currency_symbol : '$' ) . esc_html( $booking->total );
				break;

			case 'table' :
				$value = $booking->seats;
				break;

			case 'status' :
				
				if ( !empty( $this->booking_statuses[$booking->booking_status] ) ) {
					$value = $this->booking_statuses[$booking->booking_status]['label'];
				} elseif ( $booking->booking_status == 'trash' ) {
					$value = _x( 'Trash', 'Status label for bookings put in the trash', 'scwatbwsr-translate' );
				} else {
					$value = $booking->booking_status;
				}
				break;

			case 'details' :
				$value = '';

				$details = array();
				if ( trim( $booking->note ) ) {
					$details[] = array(
						'label' => __( 'Message', 'scwatbwsr-translate' ),
						'value' => esc_html( $booking->note ),
					);
				}

				if ( $booking->_ipp_status == 'Pending' ) {
					$details[] = array(
						'label' => 'Payment Failure Reason',
						'value' => isset( $booking->payment_failure_message ) ? $booking->payment_failure_message : 'Unknown payment failure reason. Check with payment processor.'
					);
				}

				$details = apply_filters( 'rtb_bookings_table_column_details', $details, $booking );

				if ( !empty( $details ) ) {
					$value = '<a href="javascript:editBooking('.esc_attr( $booking->id ).')" class="rtb-show-details" data-id="details-' . esc_attr( $booking->id ) . '"><span class="dashicons dashicons-testimonial"></span></a>';
					$value .= '<div class="rtb-details-data"><ul class="details">';
					foreach( $details as $detail ) {
						$value .= '<li><div class="label">' . $detail['label'] . '</div><div class="value">' . $detail['value'] . '</div></li>';
					}
					$value .= '</ul></div>';
				}
				break;

			case 'submitted-by' :
				
				$ip = !empty( $booking->ip ) ? $booking->ip : __( 'Unknown IP', 'scwatbwsr-translate' );
				$date_submission = !empty( $booking->date_submission ) ? $booking->format_timestamp( $booking->date_submission ) : __( 'Unknown Date', 'scwatbwsr-translate' );
				$value = sprintf( esc_html__( 'Request from %s on %s.', 'scwatbwsr-translate' ), $ip, $date_submission );
				
					
						$value .= '<div class="consent">' . sprintf( esc_html__( '✘ Consent not acquired', 'scwatbwsr-translate' ) ) . '</div>';
					
				$value .= '<div class="actions">';
				$value .= '<a href="#" data-action="ban" data-email="' . esc_attr( $booking->email ) . '" data-id="' . absint( $booking->id ) . '" data-ip="' . $ip . '">';
				$value .= __( 'Ban', 'scwatbwsr-translate' );
				$value .= '</a>';
				$value .= ' | <a href="#" data-action="delete" data-email="' . esc_attr( $booking->email ) . '" data-id="' . absint( $booking->id ) . '">';
				$value .= __( 'Delete Customer', 'scwatbwsr-translate' );
				$value .= '</a>';
				$value .= '</div>';
				break;

			default:
				$value = isset( $booking->$column_name ) ? $booking->$column_name : '';
				break;

		}

		return apply_filters( 'rtb_bookings_table_column', $value, $booking, $column_name );
	}

	/**
	 * Render the checkbox column
	 * @since 0.0.1
	 */
	public function column_cb( $booking ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			'bookings',
			$booking->id
		);
	}

	/**
	 * Add hidden columns values to the details column
	 *
	 * This only handles the default columns. Custom data needs to hook in and
	 * add it's own items to the $details array.
	 *
	 * @since 1.5
	 */
	public function add_details_column_items( $details, $booking ) {
		
		$visible_columns = $this->get_columns();
		$all_columns = $this->get_all_columns();

		$detail_columns = array_diff( $all_columns, $visible_columns );

		foreach( $detail_columns as $key => $label ) {

			$value = $this->column_default( $booking, $key );
			if ( empty( $value ) ) {
				continue;
			}

			$details[] = array(
				'label' => $label,
				'value' => $value,
			);
		}

		return $details;
	}

	/**
	 * Retrieve the bulk actions
	 * @since 0.0.1
	 */
	public function get_bulk_actions() {
		$actions = array(
			'delete'                => __( 'Delete',                	'scwatbwsr-translate' ),
			'set-status-confirmed'  => __( 'Set To Confirmed',      	'scwatbwsr-translate' ),
			'set-status-pending'    => __( 'Set To Pending Review', 	'scwatbwsr-translate' ),
			'set-status-closed'     => __( 'Set To Closed',         	'scwatbwsr-translate' ),
			'send-email'      		=> __( 'Send Email',         		'scwatbwsr-translate' )
		);

		return apply_filters( 'rtb_bookings_table_bulk_actions', $actions );
	}

	/**
	 * Process the bulk actions
	 * @since 0.0.1
	 */
	public function process_bulk_action() {
		$ids    = isset( $_POST['bookings'] ) 
			? rtbHelper::sanitize_recursive( $_POST['bookings'], 'absint' ) 
			: false;
		$action = isset( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) : false;

		// Check bulk actions selector below the table
		$action = $action == '-1' && isset( $_POST['action2'] ) 
			? sanitize_text_field( $_POST['action2'] ) 
			: $action;

		if( empty( $action ) || $action == '-1' ) {
			return;
		}

		if ( !current_user_can( 'manage_bookings' ) ) {
			return;
		}

		if ( ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		
		$results = array();
		foreach ( $ids as $id ) {
			if ( 'delete' === $action ) {
				$results[$id] = delete_booking( intval( $id ) );
			}

			if ( 'set-status-confirmed' === $action ) {
				$results[$id] = update_booking_status( intval( $id ), 'confirmed' );
			}

			if ( 'set-status-pending' === $action ) {
				$results[$id] = update_booking_status( intval( $id ), 'pending' );
			}

			if ( 'set-status-closed' === $action ) {
				$results[$id] = update_booking_status( intval( $id ), 'closed' );
			}

			$results = apply_filters( 'rtb_bookings_table_bulk_action', $results, $id, $action );
		}

		if( count( $results ) ) {
			$this->action_result = $results;
			$this->last_action = $action;
			add_action( 'rtb_bookings_table_top', array( $this, 'admin_notice_bulk_actions' ) );
		}
	}

	/**
	 * Process quicklink actions sent out in notification emails
	 * @since 0.0.1
	 */
	public function process_quicklink_action() {

		if ( empty( $_REQUEST['rtb-quicklink'] ) ) {
			return;
		}

		if ( !current_user_can( 'manage_bookings' ) ) {
			return;
		}

		
		$results = array();

		$id = !empty( $_REQUEST['booking'] ) ? intval( $_REQUEST['booking'] ) : false;

		if ( $_REQUEST['rtb-quicklink'] == 'confirm' ) {
			$results[$id] = update_booking_status( $id, 'confirmed' );
			$this->last_action = 'set-status-confirmed';
		} elseif ( $_REQUEST['rtb-quicklink'] == 'close' ) {
			$results[$id] = update_booking_status( $id, 'closed' );
			$this->last_action = 'set-status-closed';
		}

		if( count( $results ) ) {
			$this->action_result = $results;
			add_action( 'rtb_bookings_table_top', array( $this, 'admin_notice_bulk_actions' ) );
		}
	}

	/**
	 * Display an admin notice when a bulk action is completed
	 * @since 0.0.1
	 */
	public function admin_notice_bulk_actions() {

		$success = 0;
		$failure = 0;
		foreach( $this->action_result as $id => $result ) {
			if ( $result === true || $result === null ) {
				$success++;
			} else {
				$failure++;
			}
		}

		if ( $success > 0 ) :
		?>

		<div id="rtb-admin-notice-bulk-<?php esc_attr( $this->last_action ); ?>" class="updated">

			<?php if ( $this->last_action == 'delete' ) : ?>
			<p><?php echo sprintf( _n( '%d booking deleted successfully.', '%d bookings deleted successfully.', $success, 'scwatbwsr-translate' ), $success ); ?></p>

			<?php elseif ( $this->last_action == 'set-status-confirmed' ) : ?>
			<p><?php echo sprintf( _n( '%d booking confirmed.', '%d bookings confirmed.', $success, 'scwatbwsr-translate' ), $success ); ?></p>

			<?php elseif ( $this->last_action == 'set-status-pending' ) : ?>
			<p><?php echo sprintf( _n( '%d booking set to pending.', '%d bookings set to pending.', $success, 'scwatbwsr-translate' ), $success ); ?></p>

			<?php elseif ( $this->last_action == 'set-status-closed' ) : ?>
			<p><?php echo sprintf( _n( '%d booking closed.', '%d bookings closed.', $success, 'scwatbwsr-translate' ), $success ); ?></p>

			<?php endif; ?>
		</div>

		<?php
		endif;

		if ( $failure > 0 ) :
		?>

		<div id="rtb-admin-notice-bulk-<?php esc_attr( $this->last_action ); ?>" class="error">
			<p><?php echo sprintf( _n( '%d booking had errors and could not be processed.', '%d bookings had errors and could not be processed.', $failure, 'scwatbwsr-translate' ), $failure ); ?></p>
		</div>

		<?php
		endif;
	}

	/**
	 * Generate the table navigation above or below the table
	 *
	 * This outputs a separate set of options above and below the table, in
	 * order to make room for the locations.
	 *
	 * @since 1.6
	 */
	public function display_tablenav( $which ) {

		

		// Just call the parent method if locations aren't activated
		if ( 'top' === $which ) {
			$this->add_notification();
			parent::display_tablenav( $which );
			return;
		}

		// Just call the parent method for the bottom nav
		if ( 'bottom' == $which ) {
			parent::display_tablenav( $which );
			return;
		}

		
		?>

		<div class="tablenav top rtb-top-actions-wrapper">
			<?php wp_nonce_field( 'bulk-' . $this->_args['plural'] ); ?>
			<?php $this->extra_tablenav( $which ); ?>
		</div>

		<?php $this->add_notification(); ?>

		<div class="rtb-table-header-controls">
			<?php if ( $this->has_items() ) : ?>
				<div class="actions bulkactions">
					<?php $this->bulk_actions( $which ); ?>
				</div>
			<?php endif; ?>
			<ul class="rtb-locations">
				<li<?php if ( empty( $this->filter_location ) ) : ?> class="current"<?php endif; ?>>
					<a href="<?php echo esc_url( remove_query_arg( 'location', $this->query_string ) ); ?>"><?php esc_html_e( 'All Locations', 'scwatbwsr-translate' ); ?></a>
				</li>
				
			</ul>
			<div class="rtb-location-switch">
				
				<input type="submit" class="button rtb-locations-button" value="<?php esc_attr_e( 'Switch', 'scwatbwsr-translate' ); ?>">
			</div>
		</div>

		<?php
	}

	/**
	 * Extra controls to be displayed between bulk actions and pagination
	 *
	 * @param string pos Position of this tablenav: `top` or `btm`
	 * @since 1.4.1
	 */
	public function extra_tablenav( $pos ) {
		do_action( 'rtb_bookings_table_actions', $pos );
	}

	/**
	 * Add notifications above the table to indicate which bookings are
	 * being shown.
	 * @since 1.3
	 */
	public function add_notification() {



		$notifications = array();

		$status = '';
		if ( !empty( $_GET['status'] ) ) {
			$status = sanitize_text_field( $_GET['status'] );
			if ( $status == 'trash' ) {
				$notifications['status'] = __( "You're viewing bookings that have been moved to the trash.", 'scwatbwsr-translate' );
			} elseif ( !empty( $booking_statuses[ $status ] ) ) {
				$notifications['status'] = sprintf( _x( "You're viewing bookings that have been marked as %s.", 'Indicates which booking status is currently being filtered in the list of bookings.', 'scwatbwsr-translate' ), $this->booking_statuses[ $_GET['status'] ]['label'] );
			}
		}

		if ( !empty( $this->filter_start_date ) || !empty( $this->filter_end_date ) ) {
			$notifications['date'] = sprintf( _x( 'Only bookings from %s are being shown.', 'Notification of booking date range, eg - bookings from 2014-12-02-2014-12-05', 'scwatbwsr-translate' ), $this->get_current_date_range() );
		} elseif ( !empty( $_GET['date_range'] ) && $_GET['date_range'] == 'today' ) {
			$notifications['date'] = __( "Only today's bookings are being shown.", 'scwatbwsr-translate' );
		} elseif ( empty( $_GET['date_range'] ) ) {
			$notifications['date'] = __( 'Only upcoming bookings are being shown.', 'scwatbwsr-translate' );
		}

		$notifications = apply_filters( 'rtb_admin_bookings_table_filter_notifications', $notifications );

		if ( !empty( $notifications ) ) :
		?>

			<div class="rtb-notice <?php echo esc_attr( $status ); ?>">
				<?php echo join( ' ', $notifications ); ?>
			</div>

		<?php
		endif;
	}

	/**
	 * Retrieve the counts of bookings
	 * @since 0.0.1
	 */
	public function get_booking_counts() {

		global $wpdb;

		$where = "WHERE p.productId > 0";

		if ( $this->filter_start_date !== null || $this->filter_end_date !== null ) {

			if ( $this->filter_start_date !== null ) {
				$start_date = new DateTime( $this->filter_start_date . ' ' . $this->filter_start_time );
				$where .= " AND p.schedule >= '" . $start_date->format( 'Y-m-d H:i:s' ) . "'";
			}

			if ( $this->filter_end_date !== null ) {
				if( empty( $this->filter_end_time ) ) {
					$this->filter_end_time = '23:59:58';
				}
				$end_date = new DateTime( $this->filter_end_date . ' ' . $this->filter_end_time );
				$where .= " AND p.schedule <= '" . $end_date->format( 'Y-m-d H:i:s' ) . "'";
			}

		} elseif ( !empty( $_GET['date_range'] ) ) {

			if ( $_GET['date_range'] ==  'today' ) {
				$where .= " AND p.schedule >= '" . date( 'Y-m-d', current_time( 'timestamp' ) ) . "' AND p.schedule <= '" . date( 'Y-m-d', current_time( 'timestamp' ) + 86400 ) . "'";
			}

		// Default date setting is to show upcoming bookings
		} else {
			$where .= " AND p.schedule >= '" . date( 'Y-m-d H:i:s', current_time( 'timestamp' ) - 3600 ) . "'";
		}

		// Filter by name
		if( ! empty( $this->filter_name ) ) {
			$where .= " AND ( p.name LIKE '%".esc_sql( $wpdb->esc_like( $this->filter_name ) )."%' OR p.seats LIKE '%".esc_sql( $wpdb->esc_like( $this->filter_name ) )."%')";
		}

		$join = '';
		if ( $this->filter_location ) {
			$join .= " LEFT JOIN $wpdb->term_relationships t ON (t.object_id=p.ID)";
			$where .= " AND t.term_taxonomy_id=" . absint( $this->filter_location );
		}

		$query = "SELECT p.booking_status,count( * ) AS num_posts
			FROM $this->table_name p
			$join
			$where
			GROUP BY p.booking_status
		";

		$count = $wpdb->get_results( $query, ARRAY_A );
		
		$this->booking_counts = array();
		
		foreach ( $this->booking_statuses as $state=>$data ) {
			$this->booking_counts[$state] = 0;
		}

		$this->booking_counts['all'] = 0;
		
		foreach ( (array) $count as $row ) {
			$this->booking_counts[$row['booking_status']] = $row['num_posts'];
			$this->booking_counts['all'] += $row['num_posts'];
		}
	}

	/**
	 * Retrieve all the data for all the bookings
	 * @since 0.0.1
	 */
	public function bookings_data() {

		$args = array(
			'posts_per_page'	=> $this->per_page,
		);

		if ( !empty( $this->filter_start_date ) ) {
			$args['start_date'] = $this->filter_start_date;
			$args['start_time'] = $this->filter_start_time;
		}

		if ( !empty( $this->filter_end_date ) ) {
			$args['end_date'] = $this->filter_end_date;
			$args['end_time'] = $this->filter_end_time;
		}

		if ( ! empty( $this->filter_name ) ) {
			$args['filter_name'] = $this->filter_name;
		}

		$query = new scwQuery( $args, 'bookings-table' );
		$query->parse_request_args();
		$query->prepare_args();

		// Sort all bookings by newest first if no specific orderby is in play
		if ( $query->args['date_range'] == 'all' && !isset( $_REQUEST['orderby'] ) ) {
			$query->args['order'] = 'DESC';
		}
       
		$query->args = apply_filters( 'rtb_bookings_table_query_args', $query->args );
		
		$this->bookings = $query->get_bookings();
		
	}
    /**
	 * Setup the custom table
	 */
	// Get table data
    private function get_table_data() {
        global $wpdb;

        $table = $this->table_name;
        
		$where = "WHERE p.productId > 0";

		if ( $this->filter_start_date !== null || $this->filter_end_date !== null ) {

			if ( $this->filter_start_date !== null ) {
				$start_date = new DateTime( $this->filter_start_date . ' ' . $this->filter_start_time );
				$where .= " AND p.schedule >= '" . $start_date->format( 'Y-m-d H:i:s' ) . "'";
			}

			if ( $this->filter_end_date !== null ) {
				if( empty( $this->filter_end_time ) ) {
					$this->filter_end_time = '23:59:58';
				}
				$end_date = new DateTime( $this->filter_end_date . ' ' . $this->filter_end_time );
				$where .= " AND p.schedule <= '" . $end_date->format( 'Y-m-d H:i:s' ) . "'";
			}

		} elseif ( !empty( $_GET['date_range'] ) ) {

			if ( $_GET['date_range'] ==  'today' ) {
				$where .= " AND p.schedule >= '" . date( 'Y-m-d', current_time( 'timestamp' ) ) . "' AND p.schedule <= '" . date( 'Y-m-d', current_time( 'timestamp' ) + 86400 ) . "'";
			}

		// Default date setting is to show upcoming bookings
		} else {
			$where .= " AND p.schedule >= '" . date( 'Y-m-d H:i:s', current_time( 'timestamp' ) - 3600 ) . "'";
		}

		// Filter by name
		// Filter by name
		if( ! empty( $this->filter_name ) ) {
			$where .= " AND ( p.name LIKE '%".esc_sql( $wpdb->esc_like( $this->filter_name ) )."%' OR p.seats LIKE '%".esc_sql( $wpdb->esc_like( $this->filter_name ) )."%')";
		}
        // Filter by status
		if( ! empty( $_GET['status'] ) && array_key_exists($_GET['status'],$this->booking_counts) ) {
			$where .= " AND p.booking_status = '".esc_sql( $wpdb->esc_like( $_GET['status']))."'";
		}
		$join = '';
		if ( $this->filter_location ) {
			$join .= " LEFT JOIN $wpdb->term_relationships t ON (t.object_id=p.ID)";
			$where .= " AND t.term_taxonomy_id=" . absint( $this->filter_location );
		}
        $startRow=0;
		if(isset($_GET['paged']) && $_GET['paged']>0)
		$startRow = ($_GET['paged']-1) * $this->per_page;
		$query = "SELECT *
			FROM $this->table_name p
			$join
			$where limit $startRow , $this->per_page
			
		";
        return $wpdb->get_results(
            $query,
            ARRAY_A
        );
    }
	/**
	 * Setup the final data for the table
	 * @since 0.0.1
	 */
	public function prepare_items() {

		$columns  = $this->get_columns();
		$hidden   = array(); // No hidden columns
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );
        $this->table_data = $this->get_table_data();
		$this->items = $this->table_data;

		$total_items   = empty( $_GET['status'] ) ? $this->booking_counts['all'] : $this->booking_counts[$_GET['status']];

		$this->set_pagination_args( array(
				'total_items' => $total_items,
				'per_page'    => $this->per_page,
				'total_pages' => ceil( $total_items / $this->per_page )
			)
		);
	}

}
} // endif;
