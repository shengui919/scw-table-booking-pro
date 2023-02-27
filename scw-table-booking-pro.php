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
		`no_seats` varchar(255) DEFAULT NULL,
		`name` varchar(255) DEFAULT NULL,
		`address` varchar(255) DEFAULT NULL,
		`email` varchar(255) DEFAULT NULL,
		`phone` varchar(255) DEFAULT NULL,
		`note` varchar(255) DEFAULT NULL,
		`total` double(10,2) NOT NULL DEFAULT 0.00,
		`_ipp_tax` double(10,2) NOT NULL DEFAULT 0.00,
		`_ipp_commission` double(10,2) NOT NULL DEFAULT 0.00,
		`booking_status` enum('trash', 'pending', 'confirmed', 'closed') DEFAULT 'pending',
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
        'scwatbwsr_parameters'
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

function scwatbwsr_options_page(){
	do_settings_sections( 'pluginSCWTBWSRPage' );
		
}
	
add_action( 'admin_init', 'scwatbwsr_settings_init' );
function scwatbwsr_settings_init() {
	scwatbwsr_options_page();
	register_setting( 'pluginSCWTBWSRPage', 'scwatbwsr_settings' );
	add_settings_section(
		'smartcms_pluginPage_section', '', '', 'pluginSCWTBWSRPage'
	);
	// add_settings_field( 
	// 	'','',
	// 	'scwatbwsr_parameters', 
	// 	'pluginSCWTBWSRPage', 
	// 	'smartcms_pluginPage_section' 
	// );
}

function scwatbwsr_parameters(){
	include_once dirname(__FILE__) . '/includes/admin-css-js.php';
	include_once dirname(__FILE__) . '/includes/admin-scw-settings.php';
}
include_once dirname(__FILE__) . '/includes/booking-form.php';
add_shortcode('scw_booking_form', 'scwatbwsr_content');

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
    include_once dirname(__FILE__) . '/includes/admin-css-js.php';
	include_once dirname(__FILE__) . '/includes/WP_List_Table.BookingsTable.class.php';
	$bookings_table = new scwBookingsTable();
	$bookings_table->prepare_items();
	include_once dirname(__FILE__) . '/includes/admin-scw-bookings.php';
	
}

	
function scwatbwsr_dashboard_page()
{
	include_once dirname(__FILE__) . '/includes/admin-css-js.php';
	include_once dirname(__FILE__) . '/includes/admin-scw-dashboard.php';
	
}
function scwatbwsr_bookings_page()
{
	
	include_once dirname(__FILE__) . '/includes/admin-css-js.php';
	include_once dirname(__FILE__) . '/includes/admin-scw-bookings.php';
	
}
function adminMenuPage()
{
	$page=$_GET['page'];
	?>
	<div class="rtb-admin-header-menu">
			
				

									<a id="dashboard-menu" href="admin.php?page=scwatbwsr-table-dashboard" class="menu-tab nav-tab <?php if($page=='scwatbwsr-table-dashboard') echo 'nav-tab-active';?>">
						Dashboard					</a>
				
				<a id="bookings-menu" href="admin.php?page=scwatbwsr-table-bookings" class="menu-tab nav-tab <?php if($page=='scwatbwsr-table-bookings') echo 'nav-tab-active';?>">
					Bookings				</a>

									<a id="options-menu" href="admin.php?page=scwatbwsr-table-settings" class="menu-tab nav-tab <?php if($page=='scwatbwsr-table-settings') echo 'nav-tab-active';?>">
						Settings					</a>
								
							
		</div>
	<?php
}

