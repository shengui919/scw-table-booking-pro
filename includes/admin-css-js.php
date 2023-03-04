  <?php 
    $screen = get_current_screen();
	$screenID = $screen->id;
	global $rtb_controller;

	$options = get_option( 'scwatbwsr_settings' );
	global $wpdb;
	$rtb_options = get_option( 'scw-settings' );
	$bookingPageExit= $rtb_options['scw-booking-page'];
	$adminPage=$_GET['page'];
	wp_enqueue_script('jquery');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
	
	wp_register_style('font-awesome', SCWATBWSR_URL .'css/font-awesome.css');
	wp_enqueue_style('font-awesome');
	
	wp_register_script('datetimepicker', SCWATBWSR_URL .'datetimepicker/jquery.datetimepicker.full.min.js');
	wp_enqueue_script('datetimepicker');
	wp_register_style('datetimepicker', SCWATBWSR_URL .'datetimepicker/jquery.datetimepicker.css');
	wp_enqueue_style('datetimepicker');
	wp_register_script('jquery-chart', 'https://cdn.jsdelivr.net/npm/chart.js');
	wp_enqueue_script('jquery-chart');
	wp_register_script('jquery-ui', 'https://code.jquery.com/ui/1.9.2/jquery-ui.js');
	wp_enqueue_script('jquery-ui');
	wp_register_script('sweetalert','https://cdn.jsdelivr.net/npm/sweetalert2@11');
	wp_enqueue_script('sweetalert');
	wp_register_script('momentjs','https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js');
	wp_enqueue_script('momentjs');
	wp_register_script('scwatbwsr-adminscript', SCWATBWSR_URL .'js/admin.js',array(),time(),true);
	wp_enqueue_script('scwatbwsr-adminscript');
	wp_register_style('scwatbwsr-admincss', SCWATBWSR_URL .'css/admin.css',array(),time());
	wp_enqueue_style('scwatbwsr-admincss');
	
	$roomsTb = $wpdb->prefix . 'scwatbwsr_rooms';
	$getRoomsSql = $wpdb->prepare("SELECT * from {$roomsTb} where %d", 1);
	$rooms = $wpdb->get_results($getRoomsSql);
	
	$typesTB = $wpdb->prefix . 'scwatbwsr_types';
	$tableSchedules = $wpdb->prefix . 'scwatbwsr_schedules';
	$tableDailySchedules = $wpdb->prefix . 'scwatbwsr_dailyschedules';
	$tableDailyTimes = $wpdb->prefix . 'scwatbwsr_dailytimes';
	$pricesTb = $wpdb->prefix . 'scwatbwsr_prices';
	$tablesTb = $wpdb->prefix . 'scwatbwsr_tables';
	$seatsTb = $wpdb->prefix . 'scwatbwsr_seats';
	$ordersTb = $wpdb->prefix . 'scwatbwsr_orders';
	$proTb = $wpdb->prefix . 'scwatbwsr_products';
	$bookedTB = $wpdb->prefix . 'scwatbwsr_bookedseats';