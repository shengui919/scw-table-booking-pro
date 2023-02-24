<?php
/**
 * Plugin Name: Advance Table Booking PRO with Seat Reservation
 * Plugin URI: http://smartcmsmarket.net/
 * Description: table booking - online restaurant reservation system
 * Version: 1.7
 * Author: SmartCms Team
 * Author URI: http://smartcmsmarket.net/
 * License: GPLv2 or later
*/

define ( 'SCWATBWSR_URL', plugin_dir_url(__FILE__));
define ('SCW_BOOKING_POST_TYPE','scw-booking');
function scwatbwsr_boot_session(){
	if (session_status() == PHP_SESSION_NONE)
		session_start();
}
add_action('wp_loaded', 'scwatbwsr_boot_session');

register_activation_hook(__FILE__, 'scwatbwsr_install');


global $wnm_db_version;
$wnm_db_version = "1.0";



function scwatbwsr_install(){
	global $wpdb;
	global $wnm_db_version;
	
	$charset_collate = $wpdb->get_charset_collate();
	
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
	
	$roomsSql = "CREATE TABLE $roomsTB (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`roomname` varchar(255) DEFAULT NULL,
		`roomcolor` varchar(255) DEFAULT NULL,
		`roombg` varchar(255) DEFAULT NULL,
		`width` varchar(255) DEFAULT NULL,
		`height` varchar(255) DEFAULT NULL,
		`tbbookedcolor` varchar(255) DEFAULT NULL,
		`seatbookedcolor` varchar(255) DEFAULT NULL,
		`compulsory` varchar(255) DEFAULT NULL,
		`bookingtime` int(11) DEFAULT NULL,
		`zoomoption` int(11) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) $charset_collate;";
	
	$typesSql = "CREATE TABLE $typesTB (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`roomid` varchar(255) DEFAULT NULL,
		`name` varchar(255) DEFAULT NULL,
		`tbbg` varchar(255) DEFAULT NULL,
		`tbshape` varchar(255) DEFAULT NULL,
		`tbrecwidth` varchar(255) DEFAULT NULL,
		`tbrecheight` varchar(255) DEFAULT NULL,
		`tbcirwidth` varchar(255) DEFAULT NULL,
		`seatbg` varchar(255) DEFAULT NULL,
		`seatshape` varchar(255) DEFAULT NULL,
		`seatwidth` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) $charset_collate;";
	
	$schedulesSql = "CREATE TABLE $schedulesTB (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`roomid` int(11) DEFAULT NULL,
		`schedule` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) $charset_collate;";
	
	$dailyschedulesSql = "CREATE TABLE $dailyschedulesTB (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`roomid` int(11) DEFAULT NULL,
		`daily` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) $charset_collate;";
	
	$dailytimesSql = "CREATE TABLE $dailytimesTB (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`roomid` int(11) DEFAULT NULL,
		`time` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) $charset_collate;";
	
	$priceSql = "CREATE TABLE $pricesTB (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`typeid` int(11) DEFAULT NULL,
		`price` varchar(255) DEFAULT NULL,
		`type` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) $charset_collate;";
	
	$tablesSql = "CREATE TABLE $tablesTB (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`roomid` int(11) DEFAULT NULL,
		`label` varchar(255) DEFAULT NULL,
		`seats` varchar(255) DEFAULT NULL,
		`type` int(11) DEFAULT NULL,
		`tleft` varchar(255) DEFAULT NULL,
		`ttop` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) $charset_collate;";
	
	$seatsSql = "CREATE TABLE $seatsTB (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`tbid` int(11) DEFAULT NULL,
		`seat` varchar(255) DEFAULT NULL,
		`tleft` varchar(255) DEFAULT NULL,
		`ttop` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) $charset_collate;";
	
	$productsSql = "CREATE TABLE $productsTb (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`roomid` int(11) DEFAULT NULL,
		`proid` int(11) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) $charset_collate;";
	
	$ordersSql = "CREATE TABLE $ordersTB (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`orderId` varchar(255) DEFAULT NULL,
		`productId` varchar(255) DEFAULT NULL,
		`schedule` varchar(255) DEFAULT NULL,
		`seats` varchar(255) DEFAULT NULL,
		`name` varchar(255) DEFAULT NULL,
		`address` varchar(255) DEFAULT NULL,
		`email` varchar(255) DEFAULT NULL,
		`phone` varchar(255) DEFAULT NULL,
		`note` varchar(255) DEFAULT NULL,
		`total` double(10,2) NOT NULL DEFAULT 0.00,
		`_ipp_tax` double(10,2) NOT NULL DEFAULT 0.00,
		`_ipp_commission` double(10,2) NOT NULL DEFAULT 0.00,
		`booking_status` enum('Process','Pending','Completed','Cancelled') DEFAULT 'Process',
		`_ipp_status` enum('Process','Pending','Completed') DEFAULT 'Pending',
		`_ipp_transaction_id` varchar(255) DEFAULT NULL,
		`billing_first_name` varchar(255) NOT NULL,
		`billing_last_name` varchar(255) NOT NULL,
		`billing_address_1` varchar(255) DEFAULT NULL,
		`billing_address_2` varchar(255) DEFAULT NULL,
		`billing_city` varchar(255) DEFAULT NULL,
		`billing_country` varchar(255) DEFAULT NULL,
		`billing_state` varchar(255) DEFAULT NULL,
		`billing_postcode` varchar(255) DEFAULT NULL,
		`billing_email` varchar(255) DEFAULT NULL,
		`billing_phone` varchar(255) DEFAULT NULL,
		`_ipp_payment_url` text DEFAULT NULL,
		`user` varchar(255) DEFAULT NULL,
		`tran_id` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) $charset_collate;";
	
	$bookedSql = "CREATE TABLE $bookedTB (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`roomid` int(11) DEFAULT NULL,
		`tb` varchar(255) DEFAULT NULL,
		`seat` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) $charset_collate;";
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($roomsSql);
	dbDelta($typesSql);
	dbDelta($schedulesSql);
	dbDelta($dailyschedulesSql);
	dbDelta($dailytimesSql);
	dbDelta($priceSql);
	dbDelta($tablesSql);
	dbDelta($seatsSql);
	dbDelta($productsSql);
	dbDelta($ordersSql);
	dbDelta($bookedSql);
	
	add_option("wnm_db_version", $wnm_db_version);
	$reservations_page = wp_insert_post(array(
		'post_title' => sanitize_text_field('Reservations'),
		'post_content' => '[scw_booking_form]',
		'post_status' => 'publish',
		'post_type' => 'page'
		
	));

	if ( $reservations_page ) { 
		$rtb_options = get_option( 'scw-settings' );
		$rtb_options['scw-booking-page'] = $reservations_page;
		update_option( 'scw-settings', $rtb_options );
		
	}
	$roomName = filter_var("My Room", FILTER_SANITIZE_STRING);
	
	$roomsTb = $wpdb->prefix . 'scwatbwsr_rooms';
	$getdtSql = $wpdb->prepare("SELECT * from {$roomsTb} where roomname = %s", $roomName);
	$rs = $wpdb->get_results($getdtSql);
	
	if(!$rs){
		
		$vl =$wpdb->query($wpdb->prepare("INSERT INTO $roomsTb (roomname)
		VALUES (%s)", 
		$roomName));
		$proid = $reservations_page;
	    
	}
	$tableName = $wpdb->prefix . 'scwatbwsr_products';
	$getrs = $wpdb->prepare("SELECT * from {$tableName} where proid=%d", $proid);
	$rs = $wpdb->get_results($getrs);
	
	if($rs){
		$wpdb->query($wpdb->prepare("UPDATE {$tableName} SET roomid=%d where proid=%d",
		$vl, $proid));
	}else{
		$wpdb->query($wpdb->prepare("INSERT INTO $tableName (roomid, proid)
		VALUES (%d, %d)",
		$vl, $proid));
	}
}

add_action( 'admin_menu', 'scwatbwsr_admin_menu' );
function scwatbwsr_admin_menu(){
	add_menu_page(
        'SCW Bookings Settings',
        'SCW Bookings Settings',
        'manage_options',
        'scwatbwsr-table-settings',
        'scwatbwsr_options_page'
    );
    add_submenu_page(
		'scwatbwsr-table-settings',
		'SCW Bookings Dashboard',
        'SCW Bookings Dashboard',
        'manage_options',
        'scwatbwsr-table-dashboard',
        'scwatbwsr_dashboard_page'
	);
	add_submenu_page(
		'scwatbwsr-table-settings',
		'SCW Table Bookings',
        'SCW Table Bookings',
        'manage_options',
        'scwatbwsr-table-bookings',
        'show_admin_bookings_page'
	);
	
}
function scwatbwsr_dashboard_page()
{
	adminJsandCss();
	include_once dirname(__FILE__) . '/includes/dashboard.php';
	
}
function scwatbwsr_bookings_page()
{
	
	adminJsandCss();
	include_once dirname(__FILE__) . '/includes/bookings.php';
	
}
function adminJsandCss()
{
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
	
	wp_register_script('jquery-ui', 'https://code.jquery.com/ui/1.9.2/jquery-ui.js');
	wp_enqueue_script('jquery-ui');
	
	wp_register_script('scwatbwsr-adminscript', SCWATBWSR_URL .'js/admin.js');
	wp_enqueue_script('scwatbwsr-adminscript');
	wp_register_style('scwatbwsr-admincss', SCWATBWSR_URL .'css/admin.css?v=1.1');
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
}
function adminMenuPage()
{
	?>
	<div class="rtb-admin-header-menu">
			
				

									<a id="dashboard-menu" href="admin.php?page=scwatbwsr-table-dashboard" class="menu-tab nav-tab nav-tab-active">
						Dashboard					</a>
				
				<a id="bookings-menu" href="admin.php?page=scwatbwsr-table-bookings" class="menu-tab nav-tab ">
					Bookings				</a>

									<a id="options-menu" href="admin.php?page=scwatbwsr-table-settings" class="menu-tab nav-tab ">
						Settings					</a>
								
							
		</div>
	<?php
}
function scwatbwsr_options_page(){
	?>
	<h2><?php echo esc_html__("Table Booking Management", "scwatbwsr-translate") ?></h2>
		
		<?php 
		
		
		do_settings_sections( 'pluginSCWTBWSRPage' );
		
		?>
	
	<?php
}
	
add_action( 'admin_init', 'scwatbwsr_settings_init' );
function scwatbwsr_settings_init() {
	register_setting( 'pluginSCWTBWSRPage', 'scwatbwsr_settings' );
	add_settings_section(
		'smartcms_pluginPage_section', '', '', 'pluginSCWTBWSRPage'
	);
	add_settings_field( 
		'','',
		'scwatbwsr_parameters', 
		'pluginSCWTBWSRPage', 
		'smartcms_pluginPage_section' 
	);
}
function getActiveClass($roomId,$scwatbwsr_tab1)
{

   if(isset($_GET['tab']) && $_GET['tab']!='')
    {
		if($_GET['tab']==$scwatbwsr_tab1.$roomId)
		echo "active";
		
	}
	else
	{
		if($scwatbwsr_tab1=="scwatbwsr_tab1")
		echo "active";
	}
}
function show_admin_bookings_page() {
    adminJsandCss();
	include_once dirname(__FILE__) . '/includes/WP_List_Table.BookingsTable.class.php';
	$bookings_table = new scwBookingsTable();
	$bookings_table->prepare_items();
	
	?>

	
		<h1>
			<?php _e( 'Restaurant Bookings', 'restaurant-reservations' ); ?>
			<a href="#" class="add-new-h2 page-title-action add-booking"><?php _e( 'Add New', 'restaurant-reservations' ); ?></a>
		</h1>
	<div class="wrap">
		<div class="scwatbwsr_content">
			<div><?=settings_errors()?></div>
        </div>
		<div class="scwatbwsr_content mb-3">
					<?php adminMenuPage()?>
        </div>
		<div class="scwatbwsr_content">
		<?php do_action( 'rtb_bookings_table_top' ); ?>
		<form id="rtb-bookings-table" method="POST" action="">
			<input type="hidden" name="post_type" value="<?php echo RTB_BOOKING_POST_TYPE; ?>" />
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
    </div>

	<?php
}
function scwatbwsr_parameters(){
	$screen = get_current_screen();
	$screenID = $screen->id;
	
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
	
	wp_register_script('jquery-ui', 'https://code.jquery.com/ui/1.9.2/jquery-ui.js');
	wp_enqueue_script('jquery-ui');
	
	wp_register_script('scwatbwsr-adminscript', SCWATBWSR_URL .'js/admin.js');
	wp_enqueue_script('scwatbwsr-adminscript');
	wp_register_style('scwatbwsr-admincss', SCWATBWSR_URL .'css/admin.css?v=1.1');
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
		if (empty( $bookingPageExit ) ) {
		
	?>
	<div class="wrap">		
    </div>
	<?php } else  { ?>		
	<div class="wrap">
	<div class="scwatbwsr_content">
		<div><?=settings_errors()?></div>
	</div>
		<!-- tabs -->
		<div class="rooms_area">
		<?php adminMenuPage()?>
			
			
				
				<section>
					<div class="scwatbwsr_content">
						<div class="scwatbwsr_rooms">
						<?php
							if($rooms){
								$room=$rooms[0];
									$getTypesSql = $wpdb->prepare("SELECT * from {$typesTB} where roomid=%d", $room->id);
									$types = $wpdb->get_results($getTypesSql);
									
									$getScheSql = $wpdb->prepare("SELECT * from {$tableSchedules} where roomid=%d", $room->id);
									$schedules = $wpdb->get_results($getScheSql);
									
									$getdailiesSql = $wpdb->prepare("SELECT * from {$tableDailySchedules} where roomid=%d", $room->id);
									$dailies = $wpdb->get_results($getdailiesSql);
									if(isset($dailies[0]->daily)) $dailies = explode(",", $dailies[0]->daily);
									else $dailies = array();
									
									$getTimesSql = $wpdb->prepare("SELECT * from {$tableDailyTimes} where roomid=%d", $room->id);
									$times = $wpdb->get_results($getTimesSql);
									
									$getTablesSql = $wpdb->prepare("SELECT * from {$tablesTb} where roomid=%d", $room->id);
									$tables = $wpdb->get_results($getTablesSql);
									
									$getProSql = $wpdb->prepare("SELECT * from {$proTb} where roomid=%d", $room->id);
									$pro = $wpdb->get_results($getProSql);
									if($pro){
										$proid = $pro[0]->proid;
										$getOrdersSql = $wpdb->prepare("SELECT * from {$ordersTb} where productId=%d", $proid);
										$orders = $wpdb->get_results($getOrdersSql);
									}else $orders = "";
									?>
									<div class="scwatbwsr_room">
										<input class="scwatbwsr_room_id" value="<?php echo esc_attr($room->id) ?>" type="hidden">
										<input type="hidden" value="<?php echo esc_attr(get_option('date_format')) ?>" class="scw_date_format">

										<div class="scwatbwsr_room_content">
											<div class="scwatbwsr_room_content_tabs">
												<input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab1<?php echo esc_attr($room->id) ?>" type="radio" name="scwatbwsr_tabs<?php echo esc_attr($room->id) ?>">
												<label class="scwatbwsr_room_content_tabs_label <?=getActiveClass($room->id,'scwatbwsr_tab1')?>" for="scwatbwsr_tab1<?php echo esc_attr($room->id) ?>"><i class="fa fa-cog"></i><span><?php echo esc_html__("Basic Setting", "scwatbwsr-translate") ?></span></label>

												<input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab2<?php echo esc_attr($room->id) ?>" type="radio" name="scwatbwsr_tabs<?php echo esc_attr($room->id) ?>">
												<label class="scwatbwsr_room_content_tabs_label <?=getActiveClass($room->id,'scwatbwsr_tab2')?>" for="scwatbwsr_tab2<?php echo esc_attr($room->id) ?>"><i class="fa fa-codepen"></i><span><?php echo esc_html__("Table Types", "scwatbwsr-translate") ?></span></label>

												<input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab3<?php echo esc_attr($room->id) ?>" type="radio" name="scwatbwsr_tabs<?php echo esc_attr($room->id) ?>">
												<label class="scwatbwsr_room_content_tabs_label <?=getActiveClass($room->id,'scwatbwsr_tab3')?>" for="scwatbwsr_tab3<?php echo esc_attr($room->id) ?>"><i class="fa fa-calendar"></i><span><?php echo esc_html__("Schedules", "scwatbwsr-translate") ?></span></label>

												<input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab4<?php echo esc_attr($room->id) ?>" type="radio" name="scwatbwsr_tabs<?php echo esc_attr($room->id) ?>">
												<label class="scwatbwsr_room_content_tabs_label <?=getActiveClass($room->id,'scwatbwsr_tab4')?>" for="scwatbwsr_tab4<?php echo esc_attr($room->id) ?>"><i class="fa fa-usd"></i><span><?php echo esc_html__("Price", "scwatbwsr-translate") ?></span></label>

												<input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab5<?php echo esc_attr($room->id) ?>" type="radio" name="scwatbwsr_tabs<?php echo esc_attr($room->id) ?>">
												<label class="scwatbwsr_room_content_tabs_label <?=getActiveClass($room->id,'scwatbwsr_tab5')?>" for="scwatbwsr_tab5<?php echo esc_attr($room->id) ?>"><i class="fa fa-th"></i><span><?php echo esc_html__("Tables", "scwatbwsr-translate") ?></span></label>

												<input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab6<?php echo esc_attr($room->id) ?>" type="radio" name="scwatbwsr_tabs<?php echo esc_attr($room->id) ?>">
												<label class="scwatbwsr_room_content_tabs_label <?=getActiveClass($room->id,'scwatbwsr_tab6')?>" for="scwatbwsr_tab6<?php echo esc_attr($room->id) ?>"><i class="fa fa-braille"></i><span><?php echo esc_html__("Mapping", "scwatbwsr-translate") ?></span></label>

												<input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab7<?php echo esc_attr($room->id) ?>" type="radio" name="scwatbwsr_tabs<?php echo esc_attr($room->id) ?>">
												<label class="scwatbwsr_room_content_tabs_label <?=getActiveClass($room->id,'scwatbwsr_tab7')?>" for="scwatbwsr_tab7<?php echo esc_attr($room->id) ?>"><i class="fa fa-file-text-o"></i><span><?php echo esc_html__("Settings", "scwatbwsr-translate") ?></span></label>

												<section id="scwatbwsr_content1<?php echo esc_attr($room->id) ?>" class="tab-content <?=getActiveClass($room->id,'scwatbwsr_tab1')?>">
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
																	if($room->roombg){
																		?><img src="<?php echo esc_attr($room->roombg) ?>"><?php
																	}
																?>
															</span>
															<input class="scwatbwsr_roombg_con_image" value="<?php echo esc_attr($room->roombg) ?>" type="text">
															<span class="scwatbwsr_roombg_con_upload scwatbwsr_media_upload"><?php echo esc_html__("Upload Image", "scwatbwsr-translate") ?></span>
														</span>
													</span>
													<span class="scwatbwsr_roomsize">
														<span class="scwatbwsr_roomsize_label"><?php echo esc_html__("Room Size", "scwatbwsr-translate") ?></span>
														<input class="scwatbwsr_roomsize_width" placeholder="Width (px)" value="<?php echo esc_attr($room->width) ?>" type="text">
														<input class="scwatbwsr_roomsize_height" placeholder="Height (px)" value="<?php echo esc_attr($room->height) ?>" type="text">
													</span>
													<span class="scwatbwsr_bookedpr">
														<span class="scwatbwsr_bookedpr_label"><?php echo esc_html__("Table Booked Color", "scwatbwsr-translate") ?></span>
														<input class="scwatbwsr_bookedpr_tbcolor" value="<?php echo esc_attr($room->tbbookedcolor) ?>" type="color">
														<span class="scwatbwsr_bookedpr_label"><?php echo esc_html__("Seat Booked Color", "scwatbwsr-translate") ?></span>
														<input class="scwatbwsr_bookedpr_seatcolor" value="<?php echo esc_attr($room->seatbookedcolor) ?>" type="color">
													</span>
													<span class="scwatbwsr_bktime">
														<span class="scwatbwsr_bktime_label"><?php echo esc_html__("Booking Time (in minutes - the time customers will stay)", "scwatbwsr-translate") ?></span>
														<input class="scwatbwsr_bktime_ip" value="<?php echo esc_attr($room->bookingtime) ?>" type="text">
													</span>
													<span class="scwatbwsr_compulsory">
														<input class="scwatbwsr_compulsory_ip" <?php if($room->compulsory == "yes") echo "checked" ?> type="checkbox">
														<span class="scwatbwsr_compulsory_label"><?php echo esc_html__("Compulsory choose seats and tables before add product to cart", "scwatbwsr-translate") ?></span>
													</span>
													<span class="scwatbwsr_zoom">
														<input class="scwatbwsr_zoom_ip" <?php if($room->zoomoption == "1") echo "checked" ?> type="checkbox">
														<span class="scwatbwsr_zoom_label"><?php echo esc_html__("Enable panning and zooming", "scwatbwsr-translate") ?></span>
													</span>
													<span class="scwatbwsr_basesetting_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save", "scwatbwsr-translate") ?></span>
												</section>
												
												<section id="scwatbwsr_content2<?php echo esc_attr($room->id) ?>" class="tab-content <?=getActiveClass($room->id,'scwatbwsr_tab2')?>">
													<span class="scwatbwsr_roomtype_add">
														<span class="scwatbwsr_roomtype_add_head"><?php echo esc_html__("Add a type", "scwatbwsr-translate") ?></span>
														<input class="scwatbwsr_roomtype_add_name" placeholder="Name of type" type="text">
														<span class="scwatbwsr_roomtype_add_table"><?php echo esc_html__("Table", "scwatbwsr-translate") ?></span>
															<span class="scwatbwsr_roomtype_add_tbcolor">
																<span class="scwatbwsr_roomtype_add_tbcolor_head"><?php echo esc_html__("Background Color", "scwatbwsr-translate") ?></span>
																<input type="color" class="scwatbwsr_roomtype_add_tbcolor_input" id="scwatbwsr_roomtype_add_tbcolor_input">
																<label class="scwatbwsr_roomtype_add_tbcolor_button" for="scwatbwsr_roomtype_add_tbcolor_input"><?php echo esc_html__("Pick Color", "scwatbwsr-translate") ?></label>
															</span>
															<span class="scwatbwsr_roomtype_add_tbshape">
																<span class="scwatbwsr_roomtype_add_tbshape_head"><?php echo esc_html__("Shape", "scwatbwsr-translate") ?></span>
																<span class="scwatbwsr_roomtype_add_tbshape_con">
																	<label><?php echo esc_html__("Rectangular", "scwatbwsr-translate") ?></label>
																	<input type="radio" class="scwatbwsr_roomtype_add_tbshape_rec" name="scwatbwsr_roomtype_add_tbshape" value="rectangular">
																	<input type="text" class="scwatbwsr_roomtype_add_tbshape_rec_width" placeholder="Width (px)">
																	<input type="text" class="scwatbwsr_roomtype_add_tbshape_rec_height" placeholder="Height (px)">
																</span>
																<span class="scwatbwsr_roomtype_add_tbshape_con">
																	<label><?php echo esc_html__("Circle", "scwatbwsr-translate") ?></label>
																	<input type="radio" class="scwatbwsr_roomtype_add_tbshape_cir" name="scwatbwsr_roomtype_add_tbshape" value="circle">
																	<input type="text" class="scwatbwsr_roomtype_add_tbshape_cir_width" placeholder="Width (diameter-px)">
																</span>
															</span>
														<span class="scwatbwsr_roomtype_add_seat"><?php echo esc_html__("Seat", "scwatbwsr-translate") ?></span>
															<span class="scwatbwsr_roomtype_add_seatcolor">
																<span class="scwatbwsr_roomtype_add_seatcolor_head"><?php echo esc_html__("Background Color", "scwatbwsr-translate") ?></span>
																<input type="color" class="scwatbwsr_roomtype_add_seatcolor_input" id="scwatbwsr_roomtype_add_seatcolor_input">
																<label class="scwatbwsr_roomtype_add_seatcolor_button" for="scwatbwsr_roomtype_add_seatcolor_input"><?php echo esc_html__("Pick Color", "scwatbwsr-translate") ?></label>
															</span>
															<span class="scwatbwsr_roomtype_add_seatshape">
																<span class="scwatbwsr_roomtype_add_seatshape_head"><?php echo esc_html__("Shape", "scwatbwsr-translate") ?></span>
																<span class="scwatbwsr_roomtype_add_seatshape_con">
																	<label><?php echo esc_html__("Rectangular", "scwatbwsr-translate") ?></label>
																	<input type="radio" class="scwatbwsr_roomtype_add_seatshape_rec" name="scwatbwsr_roomtype_add_seatshape" value="rectangular">
																</span>
																<span class="scwatbwsr_roomtype_add_seatshape_con">
																	<label><?php echo esc_html__("Circle", "scwatbwsr-translate") ?></label>
																	<input type="radio" class="scwatbwsr_roomtype_add_seatshape_cir" name="scwatbwsr_roomtype_add_seatshape" value="circle">
																</span>
															</span>
															<input type="text" class="scwatbwsr_roomtype_add_seat_size" placeholder="Width (px)">
														<span class="scwatbwsr_roomtype_add_button"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo esc_html__("ADD", "scwatbwsr-translate") ?></span>
														<span class="scwatbwsr_roomtype_add_reload"><?php echo esc_html__("Refresh Data", "scwatbwsr-translate") ?> <i class="fa fa-refresh" aria-hidden="true"></i></span>
													</span>
													<span class="scwatbwsr_roomtype_items">
														<span class="scwatbwsr_roomtype_items_head"><?php echo esc_html__("Types", "scwatbwsr-translate") ?></span>
														<?php
															if($types){
																foreach($types as $type){
																	?>
																	<span class="scwatbwsr_roomtype_item">
																		<input value="<?php echo esc_attr($type->id) ?>" type="hidden" class="scwatbwsr_roomtype_item_id">
																		<span class="scwatbwsr_roomtype_item_name">
																			<span><?php echo esc_attr($type->name) ?></span><br>
																			<span class="scwatbwsr_roomtype_item_name_shape"><?php echo esc_html__("Table: ", "scwatbwsr-translate").esc_attr($type->tbshape) ?></span><br>
																			<span class="scwatbwsr_roomtype_item_name_shape"><?php echo esc_html__("Seat: ", "scwatbwsr-translate").esc_attr($type->seatshape) ?></span>
																		</span>
																		<span class="scwatbwsr_roomtype_item_tbbg">
																			<label><?php echo esc_html__("Table Color", "scwatbwsr-translate") ?></label>
																			<input type="color" class="scwatbwsr_roomtype_item_tbbg_input" value="<?php echo esc_attr($type->tbbg) ?>">
																		</span>
																		<span class="scwatbwsr_roomtype_item_tbsize <?php echo esc_attr($type->tbshape) ?>">
																			<label><?php echo esc_html__("Table Size", "scwatbwsr-translate") ?></label>
																			<input type="text" class="scwatbwsr_roomtype_item_tbsize_recwidth" value="<?php echo esc_attr($type->tbrecwidth) ?>">
																			<input type="text" class="scwatbwsr_roomtype_item_tbsize_recheight" value="<?php echo esc_attr($type->tbrecheight) ?>">
																			<input type="text" class="scwatbwsr_roomtype_item_tbsize_cirwidth" value="<?php echo esc_attr($type->tbcirwidth) ?>">
																		</span>
																		<span class="scwatbwsr_roomtype_item_seatbg">
																			<label><?php echo esc_html__("Seat Color", "scwatbwsr-translate") ?></label>
																			<input type="color" class="scwatbwsr_roomtype_item_seatbg_input" value="<?php echo esc_attr($type->seatbg) ?>">
																		</span>
																		<span class="scwatbwsr_roomtype_item_seatsize <?php echo esc_attr($type->seatshape) ?>">
																			<label><?php echo esc_html__("Seat Size", "scwatbwsr-translate") ?></label>
																			<input type="text" class="scwatbwsr_roomtype_item_seatsize_width" value="<?php echo esc_attr($type->seatwidth) ?>">
																		</span>
																		<span class="scwatbwsr_roomtype_item_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save", "scwatbwsr-translate") ?></span>
																		<span class="scwatbwsr_roomtype_item_del"><i class="fa fa-trash-o" aria-hidden="true"></i> <?php echo esc_html__("Delete", "scwatbwsr-translate") ?></span>
																	</span>
																	<?php
																}
															}
														?>
													</span>
												</section>
												
												<section id="scwatbwsr_content3<?php echo esc_attr($room->id) ?>" class="tab-content <?=getActiveClass($room->id,'scwatbwsr_tab3')?>">
													<span class="scwatbwsr_schedules_spec">
														<span class="scwatbwsr_schedules_spec_head"><?php echo esc_html__("Separate Schedules", "scwatbwsr-translate") ?></span>
														<span class="scwatbwsr_schedules_spec_add">
															<input class="scwatbwsr_schedules_spec_add_input" type="text">
															<span class="scwatbwsr_schedules_spec_button"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo esc_html__("ADD", "scwatbwsr-translate") ?></span>
															<span class="scwatbwsr_schedules_spec_add_reload"><?php echo esc_html__("Refresh Data", "scwatbwsr-translate") ?> <i class="fa fa-refresh" aria-hidden="true"></i></span>
														</span>
														<span class="scwatbwsr_schedules_spec_list">
															<?php
																if($schedules){
																	foreach($schedules as $schedule){
																		?>
																		<span class="scwatbwsr_schedules_spec_list_item">
																			<input type="hidden" value="<?php echo esc_attr($schedule->id) ?>" class="scwatbwsr_schedules_spec_list_item_id">
																			<input class="scwatbwsr_schedules_spec_list_item_schedule" value="<?php echo esc_attr($schedule->schedule) ?>" type="text">
																			<span class="scwatbwsr_schedules_spec_list_item_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save", "scwatbwsr-translate") ?></span>
																			<span class="scwatbwsr_schedules_spec_list_item_delete"><i class="fa fa-trash-o" aria-hidden="true"></i> <?php echo esc_html__("Delete", "scwatbwsr-translate") ?></span>
																		</span>
																		<?php
																	}
																}
															?>
														</span>
													</span>
													<span class="scwatbwsr_schedules_or"><?php echo esc_html__("OR", "scwatbwsr-translate") ?></span>
													<span class="scwatbwsr_schedules_right">
														<span class="scwatbwsr_schedules_right_head"><?php echo esc_html__("Daily Schedules", "scwatbwsr-translate") ?></span>
														<span class="scwatbwsr_daily_schedules">
															<span class="scwatbwsr_daily_schedules_week">
																<input <?php if(in_array("monday", $dailies)) echo "checked='checked'" ?> value="monday" type="checkbox" class="scwatbwsr_daily_schedules_monday" id="scwatbwsr_daily_schedules_monday">
																<label for="scwatbwsr_daily_schedules_monday"><?php echo esc_html__("Monday", "scwatbwsr-translate") ?></label>
															</span>
															<span class="scwatbwsr_daily_schedules_week">
																<input <?php if(in_array("tuesday", $dailies)) echo "checked='checked'" ?> value="tuesday" type="checkbox" class="scwatbwsr_daily_schedules_tuesday" id="scwatbwsr_daily_schedules_tuesday">
																<label for="scwatbwsr_daily_schedules_tuesday"><?php echo esc_html__("Tuesday", "scwatbwsr-translate") ?></label>
															</span>
															<span class="scwatbwsr_daily_schedules_week">
																<input <?php if(in_array("wednesday", $dailies)) echo "checked='checked'" ?> value="wednesday" type="checkbox" class="scwatbwsr_daily_schedules_wednesday" id="scwatbwsr_daily_schedules_wednesday">
																<label for="scwatbwsr_daily_schedules_wednesday"><?php echo esc_html__("Wednesday", "scwatbwsr-translate") ?></label>
															</span>
															<span class="scwatbwsr_daily_schedules_week">
																<input <?php if(in_array("thursday", $dailies)) echo "checked='checked'" ?> value="thursday" type="checkbox" class="scwatbwsr_daily_schedules_thursday" id="scwatbwsr_daily_schedules_thursday">
																<label for="scwatbwsr_daily_schedules_thursday"><?php echo esc_html__("Thursday", "scwatbwsr-translate") ?></label>
															</span>
															<span class="scwatbwsr_daily_schedules_week">
																<input <?php if(in_array("friday", $dailies)) echo "checked='checked'" ?> value="friday" type="checkbox" class="scwatbwsr_daily_schedules_friday" id="scwatbwsr_daily_schedules_friday">
																<label for="scwatbwsr_daily_schedules_friday"><?php echo esc_html__("Friday", "scwatbwsr-translate") ?></label>
															</span>
															<span class="scwatbwsr_daily_schedules_week">
																<input <?php if(in_array("saturday", $dailies)) echo "checked='checked'" ?> value="saturday" type="checkbox" class="scwatbwsr_daily_schedules_saturday" id="scwatbwsr_daily_schedules_saturday">
																<label for="scwatbwsr_daily_schedules_saturday"><?php echo esc_html__("Saturday", "scwatbwsr-translate") ?></label>
															</span>
															<span class="scwatbwsr_daily_schedules_week">
																<input <?php if(in_array("sunday", $dailies)) echo "checked='checked'" ?> value="sunday" type="checkbox" class="scwatbwsr_daily_schedules_sunday" id="scwatbwsr_daily_schedules_sunday">
																<label for="scwatbwsr_daily_schedules_sunday"><?php echo esc_html__("Sunday", "scwatbwsr-translate") ?></label>
															</span>
														</span>
														<span class="scwatbwsr_daily_schedules_times">
															<span class="scwatbwsr_daily_schedules_times_add">
																<input class="scwatbwsr_daily_schedules_times_add_input" placeholder="daily time" type="text">
																<span class="scwatbwsr_daily_schedules_times_add_button"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo esc_html__("ADD", "scwatbwsr-translate") ?></span>
																<span class="scwatbwsr_daily_schedules_times_refresh_button"><?php echo esc_html__("Refresh Data", "scwatbwsr-translate") ?> <i class="fa fa-refresh" aria-hidden="true"></i></span>
															</span>
															<span class="scwatbwsr_daily_schedules_times_list">
																<?php
																	if($times){
																		foreach($times as $time){
																			?>
																			<span class="scwatbwsr_daily_schedules_times_list_item">
																				<input class="scwatbwsr_daily_schedules_times_list_item_id" type="hidden" value="<?php echo esc_attr($time->id) ?>">
																				<input class="scwatbwsr_daily_schedules_times_list_item_input" placeholder="daily time" value="<?php echo esc_attr($time->time) ?>" type="text">
																				<span class="scwatbwsr_daily_schedules_times_list_item_button"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save", "scwatbwsr-translate") ?></span>
																				<span class="scwatbwsr_daily_schedules_times_list_item_delete"><i class="fa fa-trash-o" aria-hidden="true"></i> <?php echo esc_html__("Delete", "scwatbwsr-translate") ?></span>
																			</span>
																			<?php
																		}
																	}
																?>
															</span>
														</span>
													</span>
												</section>
												
												<section id="scwatbwsr_content4<?php echo esc_attr($room->id) ?>" class="tab-content <?=getActiveClass($room->id,'scwatbwsr_tab4')?>">
													<span class="scwatbwsr_prices">
														<?php
															if($types){
																foreach($types as $type){
																	$getPriceSql = $wpdb->prepare("SELECT * from {$pricesTb} where typeid=%d", $type->id);
																	$price = $wpdb->get_results($getPriceSql);
																	
																	if(isset($price[0]->price)) $pri = $price[0]->price;
																	else $pri = 0;
																	
																	if(isset($price[0]->type)) $itype = $price[0]->type;
																	else $itype = "seat";
																	?>
																	<span class="scwatbwsr_prices_item">
																		<span class="scwatbwsr_prices_item_head"><?php echo esc_attr($type->name)." ".esc_html__("price", "scwatbwsr-translate") ?></span>
																		<input class="scwatbwsr_prices_item_typeid" type="hidden" value="<?php echo esc_attr($type->id) ?>">
																		<input class="scwatbwsr_prices_item_price" type="text" value="<?php echo esc_attr($pri) ?>">
																		<select class="scwatbwsr_prices_item_type">
																			<option <?php if($itype=="seat") echo "selected" ?> value="seat"><?php echo esc_html__("Per Seat", "scwatbwsr-translate") ?></option>
																			<option <?php if($itype=="table") echo "selected" ?> value="table"><?php echo esc_html__("Per Table", "scwatbwsr-translate") ?></option>
																			<option <?php if($itype=="time") echo "selected" ?> value="time"><?php echo esc_html__("One Time", "scwatbwsr-translate") ?></option>
																		</select>
																	</span>
																	<?php
																}
															}
														?>
													</span>
													<span class="scwatbwsr_prices_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save", "scwatbwsr-translate") ?></span>
												</section>
												
												<section id="scwatbwsr_content5<?php echo esc_attr($room->id) ?>" class="tab-content <?=getActiveClass($room->id,'scwatbwsr_tab5')?>">
													<span class="scwatbwsr_tables_add">
														<span class="scwatbwsr_tables_add_head"><?php echo esc_html__("Add a table", "scwatbwsr-translate") ?></span>
														<input class="scwatbwsr_tables_add_label" type="text" placeholder="Label">
														<input class="scwatbwsr_tables_add_seats" type="text" placeholder="Label of seats">
														<select class="scwatbwsr_tables_add_type">
															<option value="">-- <?php echo esc_html__("Choose a type", "scwatbwsr-translate") ?> --</option>
															<?php
															if($types){
																foreach($types as $type){
																	?>
																	<option value="<?php echo esc_attr($type->id) ?>"><?php echo esc_attr($type->name) ?></option>
																	<?php
																}
															}
															?>
														</select>
														<span class="scwatbwsr_tables_add_button"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo esc_html__("ADD", "scwatbwsr-translate") ?></span>
														<span class="scwatbwsr_tables_add_reload"><?php echo esc_html__("Refresh Data", "scwatbwsr-translate") ?> <i class="fa fa-refresh" aria-hidden="true"></i></span>
													</span>
													<span class="scwatbwsr_tables_list">
														<span class="scwatbwsr_tables_list_head"><?php echo esc_html__("Tables", "scwatbwsr-translate") ?></span>
														<?php
															if($tables){
																foreach($tables as $table){
																	?>
																	<span class="scwatbwsr_tables_list_item">
																		<input type="hidden" value="<?php echo esc_attr($table->id) ?>" class="scwatbwsr_tables_list_item_id">
																		<span class="scwatbwsr_tables_list_item_label"><?php echo esc_attr($table->label) ?></span>
																		<input type="text" class="scwatbwsr_tables_list_item_seats" value="<?php echo esc_attr($table->seats) ?>">
																		<select class="scwatbwsr_tables_list_item_type">
																			<option value="">-- <?php echo esc_html__("Choose a type", "scwatbwsr-translate") ?> --</option>
																			<?php
																			if($types){
																				foreach($types as $type){
																					?>
																					<option <?php if($table->type == $type->id) echo "selected" ?> value="<?php echo esc_attr($type->id) ?>"><?php echo esc_attr($type->name) ?></option>
																					<?php
																				}
																			}
																			?>
																		</select>
																		<span class="scwatbwsr_tables_list_item_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save", "scwatbwsr-translate") ?></span>
																		<span class="scwatbwsr_tables_list_item_del"><i class="fa fa-trash-o" aria-hidden="true"></i> <?php echo esc_html__("Delete", "scwatbwsr-translate") ?></span>
																	</span>
																	<?php
																}
															}
														?>
													</span>
												</section>
												
												<section id="scwatbwsr_content6<?php echo esc_attr($room->id) ?>" class="tab-content <?=getActiveClass($room->id,'scwatbwsr_tab6')?>">
													<span class="scwatbwsr_mapping_listpreview">
														<span class="scwatbwsr_mapping_preview" style="width: <?php echo esc_attr($room->width) ?>px; height: <?php echo esc_attr($room->height) ?>px">
															<?php
																if($room->roombg){
																	?><img class="scwatbwsr_mapping_preview_image" src="<?php echo esc_attr($room->roombg) ?>"><?php
																}else{
																	?><span style="background: <?php echo esc_attr($room->roomcolor) ?>" class="scwatbwsr_mapping_preview_color"><?php echo esc_attr($room->roomcolor) ?></span><?php
																}
															?>
															<span class="scwatbwsr_mapping_preview_tables">
															<?php
																if($tables){
																	foreach($tables as $table){
																		$getType = $wpdb->prepare("SELECT * from {$typesTB} where id=%d", $table->type);
																		$type = $wpdb->get_results($getType);
									
																		if($table->tleft) $tleft = $table->tleft;
																		else $tleft = 0;
																		
																		if($table->ttop) $ttop = $table->ttop;
																		else $ttop = 0;
																		
																		$padding = $type[0]->seatwidth + 2;
																		
																		$style = 'background: '.$type[0]->tbbg .' none repeat scroll 0% 0% padding-box content-box;left: '.$tleft.'px;top: '.$ttop.'px;padding: '.$padding.'px;';
																		if($type[0]->tbshape == "rectangular")
																			$style .= 'width: '.$type[0]->tbrecwidth .'px; height: '.$type[0]->tbrecheight .'px;line-height: '.($type[0]->tbrecheight + ($type[0]->seatwidth + 2)*2).'px';
																		else
																			$style .= 'width: '.$type[0]->tbcirwidth .'px; height: '.$type[0]->tbcirwidth .'px;line-height: '.($type[0]->tbcirwidth + ($type[0]->seatwidth + 2)*2).'px;border-radius: '.$type[0]->tbcirwidth .'px';
																		
																		$seatstyle = 'background: '.$type[0]->seatbg .';';
																		if($type[0]->seatshape == "rectangular")
																			$seatstyle .= 'width: '.$type[0]->seatwidth .'px; height: '.$type[0]->seatwidth .'px;line-height: '.$type[0]->seatwidth .'px;';
																		else
																			$seatstyle .= 'width: '.$type[0]->seatwidth .'px; height: '.$type[0]->seatwidth .'px;line-height: '.$type[0]->seatwidth .'px;border-radius: '.$type[0]->seatwidth .'px;';
																		
																		$seats = explode(",", $table->seats);
																		?>
																		<span class="scwatbwsr_mapping_table" style="<?php echo esc_attr($style) ?>">
																			<span class="scwatbwsr_mapping_table_seats" style="width: calc(100% + <?php echo esc_attr(($type[0]->seatwidth+2)*2) ?>px);
																			margin-left: -<?php echo esc_attr($type[0]->seatwidth+2) ?>px;
																			height: calc(100% + <?php echo esc_attr(($type[0]->seatwidth+2)*2) ?>px);
																			margin-top: -<?php echo esc_attr($type[0]->seatwidth+2) ?>px;
																			">
																			<?php
																				if($seats){
																					foreach($seats as $seat){
																						$getSeatDt = $wpdb->prepare("SELECT * from {$seatsTb} where tbid=%d and seat=%s", $table->id, $seat);
																						$seatdt = $wpdb->get_results($getSeatDt);
																						if(isset($seatdt[0]->tleft)) $sleft = $seatdt[0]->tleft;
																						else $sleft = 0;
																						
																						if(isset($seatdt[0]->ttop)) $stop = $seatdt[0]->ttop;
																						else $stop = 0;
																						
																						$newseatstyle = $seatstyle.'left: '.$sleft.'px; top: '.$stop.'px';
																						?><span style="<?php echo esc_attr($newseatstyle) ?>" class="scwatbwsr_mapping_table_seat"><?php echo esc_attr($seat) ?></span><?php
																					}
																				}
																			?>
																			</span>
																			<input type="hidden" class="scwatbwsr_mapping_table_id" value="<?php echo esc_attr($table->id) ?>">
																			<span class="scwatbwsr_mapping_table_label"><?php echo esc_attr($table->label) ?></span>
																			<span style="margin-left: -<?php echo esc_attr($room->width) ?>px; width: <?php echo esc_attr($room->width*2) ?>px" class="topline"></span>
																			<span style="margin-top:-<?php echo esc_attr($room->height) ?>px; height: <?php echo esc_attr($room->height*2) ?>px" class="rightline"></span>
																			<span style="margin-left:-<?php echo esc_attr($room->width) ?>px; width: <?php echo esc_attr($room->width*2) ?>px" class="botline"></span>
																			<span style="margin-top:-<?php echo esc_attr($room->height) ?>px; height: <?php echo esc_attr($room->height*2) ?>px" class="leftline"></span>
																		</span>
																		<?php
																	}
																}
															?>
															</span>
														</span>
														<br>
														<span class="scwatbwsr_mapping_preview_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo esc_html__("Save Mapping", "scwatbwsr-translate") ?></span>
													</span>
												</section>
											
												<section id="scwatbwsr_content7<?php echo esc_attr($room->id) ?>" class="tab-content <?=getActiveClass($room->id,'scwatbwsr_tab7')?>">
												<div class="scwatbwsr_content vertical_tabs_style">
						<div class="tabs">    
							<div class="tab">
								<input class="radioTab" type="radio" id="tab-1" name="tab-group-1" checked>
								<label class="tab" for="tab-1">Payment Portal</label>							
								<div class="content">
								<form action='options.php' method='post'>
									<?php 
									settings_fields( 'pluginSCWTBWSRPage' );
									   
$settings_fields= [
    'enabled' => [
        'title'   => __('Enable/Disable', 'woocommerce'),
        'type'    => 'checkbox',
        'label'   => __('Enable Ticket Payment', 'woocommerce'),
        'default' => 'no',
    ],
    
   
    'api_key' => [
        'title'       => __('API Key', 'woocommerce'),
        'type'        => 'text',
        'description' => __('Please enter your IPPayware Portal API Key; this is needed in order to take payment.', 'woocommerce'),
        'desc_tip'    => true
    ],
    'api_secret' => [
        'title'       => __('API Secret', 'woocommerce'),
        'type'        => 'text',
        'description' => __('Please enter your IPPayware Portal API Secret Token; this is needed in order to take payment.', 'woocommerce'),
        'desc_tip'    => true
    ]
];
?>


		               <div class="scwatbwsr_content">	
						<div class="general-setting-scw">
							<div class="general-setting-left">
								<p><?=$settings_fields['enabled']['title']?></p>
							</div>
							<div class="general-setting-right">
								<!-- <input type="checkbox"> -->
								<label class="sap-admin-switch">
									<input name="scwatbwsr_settings[enabled_payment]" <?php if($options['enabled_payment']=="on") echo "checked='true'";?> type="checkbox" class="sap-admin-option-toggle">
									<span class="sap-admin-switch-slider round"></span>
								</label>
								<p><?=$settings_fields['enabled']['label']?></p>
							</div>
						</div>
						<div class="general-setting-scw">
							<div class="general-setting-left">
								<p>Payment Type</p>
							</div>
							<div class="general-setting-right">
							      <div class="form-radio-scw-per">
										<input type="radio" id="reservation" <?php if($options['desposit_type']=="Reservation") echo "checked='true'";?> name="scwatbwsr_settings[desposit_type]" value="Reservation">
										<label for="html">Per Reservation</label>
									</div>
									<div class="form-radio-scw-per">
										<input type="radio" id="guest" <?php if($options['desposit_type']=="Guest") echo "checked='true'";?> name="scwatbwsr_settings[desposit_type]" value="Guest">
										<label for="css">Per Guest</label>
									</div>
								<p>What type of deposit should be required, per reservation or per guest?</p>
							</div>
						</div>
						<div class="general-setting-scw">
							<div class="general-setting-left">
								<p><?=$settings_fields['api_key']['title']?></p>
							</div>
							<div class="general-setting-right">
								<input type="text" value="<?=$options['api_key']?>" name="scwatbwsr_settings[api_key]" class="require-deposit-scw">
								<p><?=$settings_fields['api_key']['description']?></p>
							</div>
						</div> 
						<div class="general-setting-scw">
							<div class="general-setting-left">
								<p><?=$settings_fields['api_secret']['title']?></p>
							</div>
							<div class="general-setting-right">
								<input type="text"  value="<?=$options['api_secret']?>" name="scwatbwsr_settings[api_secret]" class="require-deposit-scw">
								<p><?=$settings_fields['api_secret']['description']?></p>
							</div>
						</div> 
						<div class="general-setting-scw">
							<div class="general-setting-left">
								<p>Ippayware  Comission</p>
							</div>
							<div class="general-setting-right">
								<input type="text"  value="<?=$options['commission']?>" name="scwatbwsr_settings[commission]" class="require-deposit-scw">
								<p>What deposit amount is required (either per reservation or per guest, depending on the setting above)? Minimum is $0.50 in most currencies.</p>
							</div>
						</div>
					  </div>
					  <?php

    submit_button();
		?>
	</form> 
								</div> 
							</div>							
							
							<div class="tab">
								<input  class="radioTab" type="radio" id="tab-4" name="tab-group-1">
								<label class="tab" for="tab-4">Booked Tables</label>							
								<div class="content">
									
													<!-- accordion -->
													<div class="scwatbwsr_room">
										<input class="scwatbwsr_room_id" value="" type="hidden">
										
										<div class="scwatbwsr_room_content">
											<div class="scwatbwsr_room_content_tabs">
												<span class="scwatbwsr_bktables">
													<table class="accordion_table_width">
														<tbody>
													<?php
														if($tables){
															foreach($tables as $table){
																$seats = explode(",", $table->seats);
																if($seats){
																	foreach($seats as $seat){
																		$getBookedSql = $wpdb->prepare("SELECT * from {$bookedTB} where roomid=%d and tb=%s and seat=%s", $room->id, $table->label, $seat);
																		$bookedseat = $wpdb->get_results($getBookedSql);
																		?>
																		<tr>
																		<td>
																		<span class="scwatbwsr_bktables_seat">
																		
																			<span class="scwatbwsr_bktables_seat_name"><?php echo esc_attr($table->label .". Seat ".$seat) ?></span>
																		</td>
																		<td>
																			<span class="scwatbwsr_bktables_seat_make">
																				<label><?php echo esc_html__("Make as booked", "scwatbwsr-translate") ?></label>
																				<input <?php if($bookedseat) echo "checked" ?> value="<?=$table->label.'.'.$seat?>" type="checkbox" class="scwatbwsr_bktables_seat_make_input">
																			</span>
																			
																		</span>
																		</td>
																		</tr>
																	<?php
																	}
																}
																?>
															
																<?php
															}
														}
													?>
													</tbody>
													</table>
												</span>
											</div>
										</div>
									</div>		
									<!-- accordion -->		
								</div> 
							</div>							
						</div>
					</div>
					
															
												</section>
												
											</div>
										</div>
									</div>
									<?php
								
							}
						?>
						</div>
					</div>
				</section>
				
					
		</div>
		<!-- tabs -->
	</div>
	<?php
	}
}

add_shortcode('scw_booking_form', 'scwatbwsr_content');
function scwatbwsr_content($content){
	include_once dirname(__FILE__) . '/includes/settings-ipp.php';
	include_once dirname(__FILE__) . '/includes/functions.php';
	global $wpdb;
	global $post;
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
	
	$getRoomSql = $wpdb->prepare("SELECT * from {$tableProducts} where proid=%d", $proId);
	$room = $wpdb->get_results($getRoomSql);
	if(count($room)==0)
	{
		$getRoomSql = $wpdb->prepare("SELECT * from {$tableProducts} where roomid!=0 limit 1");
	    $room = $wpdb->get_results($getRoomSql);
	}
	else if(count($room)>0 && $room[0]->roomid==0)
	{
		$getRoomSql = $wpdb->prepare("SELECT * from {$tableProducts} where roomid!=%d limit 1",0);
	    $room = $wpdb->get_results($getRoomSql);
	}
	
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
		
	
	if(  $room &&  $room[0]->roomid){
		
		ob_start();
		
		$roomid = $room[0]->roomid;
		wp_register_script('scwjquery', SCWATBWSR_URL .'js/jquery.min.js');
		wp_enqueue_script('scwjquery');
		wp_register_script('datetimepicker', SCWATBWSR_URL .'datetimepicker/jquery.datetimepicker.full.min.js');
		wp_enqueue_script('datetimepicker');
		wp_register_style('datetimepicker', SCWATBWSR_URL .'datetimepicker/jquery.datetimepicker.css');
		wp_enqueue_style('datetimepicker');
		
		wp_register_script('panzoom', 'https://cdn.jsdelivr.net/npm/@panzoom/panzoom/dist/panzoom.min.js');
		wp_enqueue_script('panzoom');
		wp_register_script('sweetalert','https://cdn.jsdelivr.net/npm/sweetalert2@11');
		wp_enqueue_script('sweetalert');
		wp_register_script('scwatbwsr-script-frontend', SCWATBWSR_URL .'js/front.js');
		wp_enqueue_script('scwatbwsr-script-frontend');
		wp_register_style('scwatbwsr-style-frontend', SCWATBWSR_URL .'css/front.css?v=1.2');
		wp_enqueue_style('scwatbwsr-style-frontend');
		if($ipp_message!='')
		{
		add_action('wp_print_footer_scripts', function() use ($ipp_message){
			scw_this_script_footer($ipp_message);
		});
		do_action('scw_this_script_footer');
		}
		$getRoomDataSql = $wpdb->prepare("SELECT * from {$tableRooms} where id=%d", $roomid);
		$roomData = $wpdb->get_results($getRoomDataSql);
		
		$getTypeSql = $wpdb->prepare("SELECT * from {$tableTypes} where roomid=%d", $roomid);
		$types = $wpdb->get_results($getTypeSql);
		
		$getSchedulesSql = $wpdb->prepare("SELECT * from {$tableSchedules} where roomid=%d", $roomid);
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
		$getOrdersSql = $wpdb->prepare("SELECT * from {$ordersTb} where productId=%d", $proId);
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
		?>
		<div class="scw_front_content">
		<div class="scwatbwsr_content <?php echo get_post_type($proId) ?>">
			<input type="hidden" value="<?php echo esc_attr(SCWATBWSR_URL) ?>" class="scwatbwsr_url">
			<input type="hidden" value="<?php echo esc_attr($proId) ?>" class="product_id">
			<input type="hidden" value="<?php echo esc_attr($roomid) ?>" class="profileid">
			<input type="hidden" value="<?php echo esc_attr($tbbookedcolor) ?>" class="tbbookedcolor">
			<input type="hidden" value="<?php echo esc_attr($seatbookedcolor) ?>" class="seatbookedcolor">
			<input type="hidden" value="<?php echo esc_attr($roomData[0]->compulsory) ?>" class="scw_compulsory">
			<input type="hidden" value="<?php echo esc_attr($roomData[0]->bookingtime) ?>" class="scw_bookingtime">
			<input type="hidden" value="<?php echo esc_attr(get_option('date_format')) ?>" class="scw_date_format">
			<input type="hidden" value="<?php echo esc_attr(get_post_type($proId)) ?>" class="scw_posttype">
			<input type="hidden" value="<?php echo esc_attr($roomData[0]->zoomoption) ?>" class="scw_zoomoption">
			
			<div class="scwatbwsr_sendform">
			        <div class="bghover_scw">
							<div class="scwatbwsr_form_item scw_form_name">
								<label><?php echo esc_html__("Name", "scwatbwsr-translate") ?></label>
								<input class="scwatbwsr_form_name_input scwatcommon_style" type="text" autocomplete="off">
							</div>
							<div class="scwatbwsr_form_item scw_form_address">
								<label><?php echo esc_html__("Address", "scwatbwsr-translate") ?></label>
								<input class="scwatbwsr_form_address_input scwatcommon_style" type="text" autocomplete="off">
							</div>
							<div class="scwatbwsr_form_item scw_form_email">
								<label><?php echo esc_html__("Email", "scwatbwsr-translate") ?></label>
								<input class="scwatbwsr_form_email_input scwatcommon_style" type="text" autocomplete="off">
							</div>
							<div class="scwatbwsr_form_item scw_form_phone">
								<label><?php echo esc_html__("Phone", "scwatbwsr-translate") ?></label>
								<input id="phone" class="scwatbwsr_form_phone_input scwatcommon_style" type="text" autocomplete="off">
							</div>
							<div class="scwatbwsr_form_item scw_form_note">
								<label><?php echo esc_html__("Note", "scwatbwsr-translate") ?></label>
								<textarea class="scwatbwsr_form_note_input scwatcommon_style" rows="4"></textarea>
							</div>
							<div class="scwatbwsr_form_item scw_form_calendar">
								<label>
									<?php echo esc_html__("Booking Date", "scwatbwsr-translate");?>
									   
								</label>
									<?php if($checkSchedules){?>
										<div class="scwatbwsr_schedules scwatbwsr_schedules_special">
										<?php foreach($checkSchedules as $sche)
										{
						                ?><span class="scwatbwsr_schedules_item"><?php echo esc_attr(date("Y-m-d H:i:s a",strtotime($sche->schedule)))?></span>
										<?php } ?>
									    </div>
									<?php } else  { ?>
										<div class="scwatbwsr_schedules scwatbwsr_schedules_daily">
										<?php $arroDay = array(0, 1, 2, 3, 4, 5, 6);
					$arrDay = array();
					$arrTime = "";
					
					$tableDailySchedules = $wpdb->prefix . 'scwatbwsr_dailyschedules';
					$getDSSql = $wpdb->prepare("SELECT * from {$tableDailySchedules} where roomid=%d", $roomid);
					$getDSRs = $wpdb->get_results($getDSSql);
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
								$arrTime .= ",".$time->time;
							else
								$arrTime .= $time->time;
						}
					}
					
					if($dailies[0]){
						?>
						<input class="array_dates" type="hidden" value='<?php echo json_encode($arrfDay, 1) ?>'>
						<input class="array_times" type="hidden" value="<?php echo esc_attr($arrTime) ?>">
						<input id="scwatbwsr_schedules_picker" class="scwatcommon_style" type="text">
						<?php
					}
				     ?>
									    </div>
					                <?php } ?>
								
							</div>

						</div>
					
			</div>
			
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
															if(isset($seatdt[0]->tleft)) $sleft = $seatdt[0]->tleft;
															else $sleft = 0;
															
															if(isset($seatdt[0]->ttop)) $stop = $seatdt[0]->ttop;
															else $stop = 0;
															
															$newseatstyle = $seatstyle.'left: '.$sleft.'px; top: '.$stop.'px;';
															
															if(in_array($table->label .".".$seat, $bookedSeats))
																$newseatstyle .= 'background: '.$seatbookedcolor.';';
															else
																$newseatstyle .= 'background: '.$type[0]->seatbg .';';
															?><span id="seat<?php echo esc_attr($table->label .$seat) ?>" style="<?php echo esc_attr($newseatstyle) ?>" class="scwatbwsr_map_tables_table_seat <?php if(in_array($table->label .".".$seat, $bookedSeats)) echo "seatbooked" ?>"><?php echo esc_attr($seat) ?></span><?php
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

			<div class="mainpage-seats">
				<div class="scwatbwsr_form">
					<!-- <div class="scwatbwsr_total">
						<span><?php echo esc_html__("Total: $", "scwatbwsr-translate") ?></span>
						<span class="scwatbwsr_total_value">0</span>
					</div> -->
					<div class="scwatbwsr_sendform">
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
					<span class="scwatbwsr_types_item_name"><b><?php echo esc_html__("Booked Table", "scwatbwsr-translate") ?></b></span>
					<!-- <span class="scwatbwsr_types_item_bg" >bg</span> -->
				</div>

				<div class="scwatbwsr_total">
					<span class="total_price_card"><?php echo esc_html__("Total:", "scwatbwsr-translate") ?></span>
					<span class="scwatbwsr_total_value"><?php echo esc_html__("$", "scwatbwsr-translate") ?>0.00</span>
				</div>
			</div>
			
			
						<div class="scwatbwsr_form_item_payment scw_form_payment">
						<div style="display:none">s
							<label><?php echo esc_html__("Payment Method", "scwatbwsr-translate") ?> (<?php echo esc_html__("Credit Card", "scwatbwsr-translate") ?>)</label>
							
							<div class="payment_addr_field_scw">
								
									<input class="" type="text" autocomplete="off">
									<label><?php echo esc_html__("Full Name", "scwatbwsr-translate") ?></label>
								
								
							</div>
							<div class="payment_field_scw">
								<div>
									<input class="" type="text" autocomplete="off">
									<label><?php echo esc_html__("Credit Card Number", "scwatbwsr-translate") ?></label>
								</div>
								<div>
									<input class="" type="text" autocomplete="off">
									<label><?php echo esc_html__("Security Code", "scwatbwsr-translate") ?></label>
								</div>
							</div>
							<div class="payment_field_scw">
								<div>
									<select name="month" id="month">
										<option value=""></option>
										<option value="month">Month</option>
									</select>
									<label><?php echo esc_html__("Expiration Month", "scwatbwsr-translate") ?></label>
								</div>
								<div>
									<select name="date" id="date">
										<option value=""></option>
										<option value="date">Date</option>
									</select>
									<label><?php echo esc_html__("Expiration Year", "scwatbwsr-translate") ?></label>
								</div>
							</div>
							</div>
							<label><?php echo esc_html__("Billing Address", "scwatbwsr-translate") ?></label>
							<div class="payment_field_scw">
								<div>
									<input class="billing_first_name" type="text" >
									<label><?php echo esc_html__("First Name", "scwatbwsr-translate") ?></label>
								</div>
								<div>
									<input class="billing_last_name" type="text" >
									<label><?php echo esc_html__("Last Name", "scwatbwsr-translate") ?></label>
								</div>
							</div>
							<div class="payment_addr_field_scw">
								<input class="billing_address_1" type="text" >
								<label><?php echo esc_html__("Street Address", "scwatbwsr-translate") ?></label>
							</div>
							<div class="payment_addr_field_scw">
								<input class="billing_address_2" type="text" >
								<label><?php echo esc_html__("Street Address Line 2", "scwatbwsr-translate") ?></label>
							</div>
							<div class="payment_field_scw">
								<div>
									<input class="billing_city" type="text" >
									<label><?php echo esc_html__("City", "scwatbwsr-translate") ?></label>
								</div>
								<div>
									<input class="billing_state" type="text" >
										<label><?php echo esc_html__("State / Province", "scwatbwsr-translate") ?></label>
									</div>
								</div>
							<div class="payment_field_scw">
								<div>
									<input class="billing_postcode" type="text">
									<label><?php echo esc_html__("Postal / Zip Code", "scwatbwsr-translate") ?></label>
								</div>
								<div>
									<select name="country" class="billing_country" id="country">
										<option value="">Please Select</option>
										<?php foreach($countries_list as $val=>$txt){?>
										<option value="<?=$val?>"><?=$txt?></option>
										<?php  }   ?>
									</select>
									<label><?php echo esc_html__("Country", "scwatbwsr-translate") ?></label>
								</div>
							</div>
							<div class="payment_field_scw">
								
								<div>
								<input class="billing_email" type="text" autocomplete="off">
									<label><?php echo esc_html__("Emaill", "scwatbwsr-translate") ?></label>
									
								</div>
								<div>
									<input class="billing_phone" type="text" autocomplete="off">
									<label><?php echo esc_html__("Phone Number", "scwatbwsr-translate") ?></label>
								</div>
							</div>
						</div>
						<div class="scwatbwsr_form_item scwform_submit"><span class="scwatbwsr_form_submit"><?php echo esc_html__("Submit", "scwatbwsr-translate") ?></span></div>
					</div>
				</div>
			</div>
		</div>
		</div>
		<?php
		$string = ob_get_contents();
		ob_end_clean();
		$content .= $string;
	}
	return $content;
}

