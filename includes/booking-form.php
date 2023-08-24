<?php
function scwatbwsr_content($content){
	include_once dirname(__FILE__) . '/settings-ipp.php';
	include_once dirname(__FILE__) . '/functions.php';
	global $wpdb;
	global $post;
	$options_rest = get_option( 'scwatbwsr_settings_rest' );
	$options_ippayware = get_option( 'scwatbwsr_settings_ippayware' );
	$options_twilio = get_option( 'scwatbwsr_settings_twilio' );
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
    ob_start();
		
	wp_register_style('font-awesome', SCWATBWSR_URL .'css/font-awesome.css');
	wp_enqueue_style('font-awesome');
	wp_register_style('scwatbwsr-style-frontend', SCWATBWSR_URL .'css/front.css',array(),time());
	wp_enqueue_style('scwatbwsr-style-frontend');
	wp_register_style('bookingcss',SCWATBWSR_URL .'css/booking-styles.css',array(),time());
	wp_enqueue_style('bookingcss');
	wp_register_script('scwjquery', 'https://code.jquery.com/jquery-1.11.2.min.js');
	wp_enqueue_script('scwjquery');
	
		
		wp_register_script('jqueryvalidation','https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js');
		wp_enqueue_script('jqueryvalidation');
		
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
		if(!function_exists('scw_this_script_footer')) { 
			function scw_this_script_footer($ipp_message){ 
				
			?>
			 <script type='text/javascript'>
				
				Swal.fire(
				'Booking Status',
				'<?=$ipp_message?>',
				'success'
				);
				
			</script>
			<?php }  }   
		?>
		<script type='text/javascript'>
		var customer_table ="<?=@$options_rest["customer_table"]?>";
		var enabled_payment ="<?=@$options_rest["enabled_payment"]?>";
		</script>
<?php
	require_once dirname(dirname(__FILE__))."/includes/booking-template.php";
	$string = ob_get_contents();
		ob_end_clean();
		$content .= $string;
	return $content;	
 }