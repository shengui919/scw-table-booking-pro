<?php
require_once dirname(dirname(__FILE__))."/library/twilio/autoload.php";	
use Twilio\Rest\Client;
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-config.php');

function bookingTimes($duration=30,$starttime='',$endtime='')
{
if($starttime=='')
$starttime = '00:00';  // your start time
if($endtime=='')
$endtime = '23:59';  // End time


$array_of_time = array ();
$start_time    = strtotime ($starttime); //change to strtotime
$end_time      = strtotime ($endtime); //change to strtotime

$add_mins  = $duration * 60;

while ($start_time <= $end_time) // loop between time
{
   $array_of_time[] = date ("H:i", $start_time);
   $start_time += $add_mins; // to check endtie=me
} 
return $array_of_time;
}
function bookingDates($days=20)
{
$starttime = date('Y-m-d');  // your start time
$array_of_time = array ();
$endtime = date("Y-m-d", strtotime("+$days days"));
$begin = new DateTime(date("Y-m-d"));
$end = new DateTime($endtime);
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);
foreach ($period as $dt) {
    $array_of_time[$dt->format("Y-m-d")]=$dt->format("l jS \of F");
}
return $array_of_time;
}
function orderUpdate($order_id,$updateArray)
{ 
    global $wpdb;
    $ordersTb = $wpdb->prefix . 'scwatbwsr_orders';
    $wpdb->update( $ordersTb, $updateArray, array( 'id' => $order_id ) );
}
function orderGet($order_id)
{ 
    global $wpdb;
    $ordersTb = $wpdb->prefix . 'scwatbwsr_orders';
	$query = "SELECT * from $ordersTb where id=$order_id";
    return $wpdb->get_row($query);
}
function getAllroomsLiveView()
{
	global $wpdb;
	
	$rooomQuery = $wpdb->prepare("SELECT r.width as rw,r.height as rh,t.ttop,t.tleft,t.label as tname,r.rtop,r.rleft,r.id,r.roomname,COUNT(t.id) as count from ".roomsTB." AS r
	INNER JOIN ".tablesTB." AS t ON t.roomid = r.id
	WHERE  t.seats>%d 
	GROUP by t.roomid",0);
	return $wpdb->get_results($rooomQuery);
	
}
function getAllTableLiveView()
{
	global $wpdb;
	
	$rooomQuery = $wpdb->prepare("SELECT  r.width as rw,r.height as rh,t.label,t.seats,t.ttop,t.tleft from ".tablesTB." AS t
	INNER JOIN ".roomsTB." AS r ON t.roomid = r.id
	WHERE  t.seats>%d 
	GROUP by t.roomid",0);
	return $wpdb->get_results($rooomQuery);
	
}
function getAllTableLiveViewFrontByRoom($roomid)
{
	global $wpdb;
	//LEFT JOIN ".ordersTB." AS o ON  o.seats = t.id
	//o.phone,o.name as customer_name,o.schedule,o.seats as tableID o.booking_status,
	$rooomQuery = $wpdb->prepare("SELECT ty.tbrecwidth as tw,ty.tbrecheight as th,r.width as rw,r.height as rh,p.price,t.id,t.label,t.seats,t.ttop,t.tleft from ".tablesTB." AS t
	INNER JOIN ".roomsTB." AS r ON t.roomid = r.id
	LEFT JOIN ".pricesTB." AS p ON p.typeid = t.type
	LEFT JOIN ".typesTB." AS ty ON t.type = ty.id
	WHERE  t.seats>%d  AND t.roomid=%d
	",0,$roomid);
	return $wpdb->get_results($rooomQuery);
	
}
function getAllTableLiveViewByRoom($roomid)
{
	global $wpdb;
	//LEFT JOIN ".ordersTB." AS o ON  o.seats = t.id
	//o.phone,o.name as customer_name,o.schedule,o.seats as tableID o.booking_status,
	$rooomQuery = $wpdb->prepare("SELECT ty.tbrecwidth as tw,ty.tbrecheight as th,r.width as rw,r.height as rh,p.price,t.id,t.label,t.seats,t.ttop,t.tleft from ".tablesTB." AS t
	INNER JOIN ".roomsTB." AS r ON t.roomid = r.id
	LEFT JOIN ".pricesTB." AS p ON p.typeid = t.type
	LEFT JOIN ".typesTB." AS ty ON t.type = ty.id
	WHERE  t.seats>%d  AND t.roomid=%d 
	GROUP by t.roomid
	",0,$roomid);
	return $wpdb->get_results($rooomQuery);
	
}
function getTablePrices($tid)
{
	global $wpdb;
	
	$rooomQuery = $wpdb->prepare("SELECT t.type,t.id,p.price from ".tablesTB." AS t
	INNER JOIN ".pricesTB." AS p ON t.type = p.typeid
	WHERE  t.seats>%d  AND t.roomid!=%d AND t.id=%d
	",0,0,$tid);
	return $wpdb->get_row($rooomQuery);
	
}
function findBooking($selectedDate,$startTime,
$endTime)
{
	$start = date("Y-m-d H:i:s",strtotime($selectedDate." ".$startTime));

	$end = date("Y-m-d H:i:s",strtotime($selectedDate." ".$endTime));
	
	global $wpdb;
	
	$rooomQuery = $wpdb->prepare("SELECT r.roomname,t.label,t.id as tid,r.id as rid,o.* from ".ordersTB." AS o
	LEFT JOIN ".roomsTB." AS r ON o.roomid = r.id
	LEFT JOIN ".tablesTB." AS t ON o.seats = t.id
	WHERE  o.schedule >= %s  AND o.schedule <= %s
	",$start,$end);
	return $wpdb->get_results($rooomQuery);
	
}
function findBookingTable($allBookings,$tid)
{
   $bookings=array_filter($allBookings,function($b) use ($tid){
         return ($b->seats==$tid && $b->booking_status!="closed" && $b->booking_status!="trash");
   });
   if($bookings)
   {
	$bookings = array_values($bookings);
   }
   else 
   {
	
	$bookings=false;
   }
   return $bookings;
}
function findBookingByTable($table,$selectedDate,$startTime,
$endTime)
{
	$start = date("Y-m-d H:i:s",strtotime($selectedDate." ".$startTime));

	$end = date("Y-m-d H:i:s",strtotime($selectedDate." ".$endTime));
	global $wpdb;
	//INNER JOIN ".roomsTB." AS r ON t.roomid = r.id
	$rooomQuery = $wpdb->prepare("SELECT o.* from ".ordersTB." AS o
	
	WHERE  o.schedule >= %s  AND o.schedule <= %s AND o.seats = %s
	",$table,$start,$end);
	return $wpdb->get_results($rooomQuery);
	
}
function findTableClass($seats)
{
	$tlClass="tablew5";
	if($seats>=1 && $seats<=4)
	{
		$tlClass="tablew4";
	}
	else if($seats>=5 && $seats<=6)
	{
		$tlClass="tablew6";
	}
	else if($seats>=6 && $seats<=10)
	{
		$tlClass="tablew10";
	}
	else if($seats>=10 && $seats<=15)
	{
		$tlClass="tablew15";
	}
	else 
	{
		$tlClass ="tablew20";
	}
	return $tlClass;
}
add_action( 'add_meta_boxes', 'scwatbwsr_add_tab_admin_product', 10, 2 );
function scwatbwsr_add_tab_admin_product(){
	global $wp_meta_boxes;
	
	$wp_meta_boxes[ 'product' ][ 'normal' ][ 'core' ][ 'scwatbwsr' ][ 'title' ] = esc_html__("Table Booking PRO", "scwatbwsr-translate");
	$wp_meta_boxes[ 'product' ][ 'normal' ][ 'core' ][ 'scwatbwsr' ][ 'args' ] = "";
	$wp_meta_boxes[ 'product' ][ 'normal' ][ 'core' ][ 'scwatbwsr' ][ 'id' ] = "scwatbwsr";
	$wp_meta_boxes[ 'product' ][ 'normal' ][ 'core' ][ 'scwatbwsr' ][ 'callback' ] = "scwatbwsr_add_tab_admin_product_display";
}
function scwatbwsr_add_tab_admin_product_display(){
	global $wpdb;
	$postId = $_GET['post'];
	
	if($postId){
		wp_register_script('scwatbwsr-productscript', SCWATBWSR_URL .'js/product.js');
		wp_enqueue_script('scwatbwsr-productscript');
		wp_register_style('scwatbwsr-productcss', SCWATBWSR_URL .'css/product.css');
		wp_enqueue_style('scwatbwsr-productcss');
		
		$roomsTb = $wpdb->prefix . 'scwatbwsr_rooms';
		$getRoomsSql = $wpdb->prepare("SELECT * from {$roomsTb} where %d", 1);
		$rooms = $wpdb->get_results($getRoomsSql);
		
		$productsTb = $wpdb->prefix . 'scwatbwsr_products';
		$getProductsSql = $wpdb->prepare("SELECT * from {$productsTb} where proid=%d", $postId);
		$proInfo = $wpdb->get_results($getProductsSql);
		if(isset($proInfo[0]->roomid)) $currentId = $proInfo[0]->roomid;
		else $currentId = 0;
		
		$roomname = "";
		?>
		<div class="scwatbwsr_content">
			<input type="hidden" class="scwatbwsr_proid" value="<?php echo esc_attr($postId) ?>">
			<div class="scwatbwsr_select">
				<select class="scwatbwsr_select_profile">
					<option value="">-- <?php echo esc_html__("Select a Room", "scwatbwsr-translate") ?> --</option>
					<?php
						if($rooms){
							foreach($rooms as $room){
								if($room->id == $proInfo[0]->roomid) $roomname = $room->roomname;
								?><option <?php if($room->id == $currentId) echo "selected" ?> value="<?php echo esc_attr($room->id) ?>"><?php echo esc_attr($room->roomname) ?></option><?php
							}
						}
					?>
				</select>
			</div>
			<div class="scwatbwsr_roomname"><?php echo esc_attr($roomname) ?></div>
			<div class="scwatbwsr_booked">
			
			</div>
		</div>
		<?php
	}
}

add_action('woocommerce_after_single_product', 'scwatbwsr_fontend_single');
function scwatbwsr_fontend_single(){
	global $product;
	global $wpdb;
	$proId = $product->get_id();
	$currencyS = get_woocommerce_currency_symbol();
	
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
					
    
	if($room && $room[0]->roomid){
		$roomid = $room[0]->roomid;
		
		wp_register_script('datetimepicker', SCWATBWSR_URL .'datetimepicker/jquery.datetimepicker.full.min.js');
		wp_enqueue_script('datetimepicker');
		wp_register_style('datetimepicker', SCWATBWSR_URL .'datetimepicker/jquery.datetimepicker.css');
		wp_enqueue_style('datetimepicker');
		
		wp_register_script('panzoom', 'https://cdn.jsdelivr.net/npm/@panzoom/panzoom/dist/panzoom.min.js');
		wp_enqueue_script('panzoom');
		
		wp_register_script('scwatbwsr-script-frontend', SCWATBWSR_URL .'js/front.js');
		wp_enqueue_script('scwatbwsr-script-frontend');
		wp_register_style('scwatbwsr-style-frontend', SCWATBWSR_URL .'css/front.css?v=1.1');
		wp_enqueue_style('scwatbwsr-style-frontend');
		
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
		<div class="scwatbwsr_content" style="display: none">
			<input type="hidden" value="<?php echo esc_attr(SCWATBWSR_URL) ?>" class="scwatbwsr_url">
			<input type="hidden" value="<?php echo esc_attr($proId) ?>" class="product_id">
			<input type="hidden" value="<?php echo esc_attr($roomid) ?>" class="profileid">
			<input type="hidden" value="<?php echo esc_attr($tbbookedcolor) ?>" class="tbbookedcolor">
			<input type="hidden" value="<?php echo esc_attr($seatbookedcolor) ?>" class="seatbookedcolor">
			<input type="hidden" value="<?php echo esc_attr($roomData[0]->compulsory) ?>" class="scw_compulsory">
			<input type="hidden" value="<?php echo esc_attr($roomData[0]->bookingtime) ?>" class="scw_bookingtime">
			<input type="hidden" value="<?php echo esc_attr($roomData[0]->zoomoption) ?>" class="scw_zoomoption">
			<input type="hidden" value="<?php echo esc_attr(get_option('date_format')) ?>" class="scw_date_format">

			<div class="scwatbwsr_types">
				<label class="form-label form-label-top" id="" for="input"> My Bookings </label>
				<?php
					if($types){
						foreach($types as $type){
							$getPriceSql = $wpdb->prepare("SELECT * from {$pricesTb} where typeid=%d", $type->id);
							$price = $wpdb->get_results($getPriceSql);
							?>
							<span class="scwatbwsr_types_item" style="background: <?php echo esc_attr($type->tbbg) ?>">
								<span class="scwatbwsr_types_item_name"><b><?php echo esc_attr($type->name) ?></b></span>
								<span class="scwatbwsr_types_item_bg" >bg</span>
								<?php
									if($price && $price[0]->price){
										if($price[0]->type == "seat") $pricetype = esc_html__("per seat", "scwatbwsr-translate");
										elseif($price[0]->type == "table") $pricetype = esc_html__("per table", "scwatbwsr-translate");
										elseif($price[0]->type == "time") $pricetype = esc_html__("one time for all", "scwatbwsr-translate");
									?>
									<span class="scwatbwsr_types_item_price">(<?php echo esc_attr($currencyS.$price[0]->price ." ".$pricetype) ?>)</span>
								<?php } ?>
							</span>
							<?php
						}
					}
				?>
				<span class="scwatbwsr_types_item" style="background: <?php echo esc_attr($tbbookedcolor) ?>">
					<span class="scwatbwsr_types_item_name"><b><?php echo esc_html__("Booked Table", "scwatbwsr-translate") ?></b></span>
					<span class="scwatbwsr_types_item_bg" >bg</span>
				</span>
			</div>
			
			<div class="scwatbwsr_schedules <?php if($checkSchedules){ ?>scwatbwsr_schedules_special<?php }else{ ?>scwatbwsr_schedules_daily<?php } ?>">
				<?php
				if($checkSchedules){
					?><div class="scwatbwsr_schedules_header"><?php echo esc_html__("Please choose schedule first!", "scwatbwsr-translate") ?></div><?php
					foreach($checkSchedules as $sche){
						?><span class="scwatbwsr_schedules_item"><?php echo esc_attr(date("Y-m-d H:i:s a",strtotime($sche->schedule)))?></span><?php
					}
				}else{
					$arroDay = array(0, 1, 2, 3, 4, 5, 6);
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
						<div class="scwatbwsr_schedules_header"><?php echo esc_html__("Please choose schedule first!", "scwatbwsr-translate") ?></div>
						<input class="array_dates" type="hidden" value='<?php echo json_encode($arrfDay, 1) ?>'>
						<input class="array_times" type="hidden" value="<?php echo esc_attr($arrTime) ?>">
						<input id="scwatbwsr_schedules_picker" type="text">
						<?php
					}
				}
				?>
			</div>
			
			<div class="scwatbwsr_map">
				<div class="scwatbwsr_map_head"><?php echo esc_html__("Choose your Seats", "scwatbwsr-translate") ?></div>
				<?php
				if($roomData[0]->zoomoption){ ?>
				<div class="scwatbwsr_map_zoom">
					<span id="scwatbwsr_map_zoom-in"><?php echo esc_html__("Zoom In", "scwatbwsr-translate") ?></span>
					<span id="scwatbwsr_map_zoom-out"><?php echo esc_html__("Zoom Out", "scwatbwsr-translate") ?></span>
					<span id="scwatbwsr_map_zoom_reset"><?php echo esc_html__("Reset", "scwatbwsr-translate") ?></span>
				</div>
				<?php } ?>
				<div class="scwatbwsr_map_block">
				<div id="scwatbwsr_map_panzoom" class="<?php if(!$roomData[0]->zoomoption) echo 'scwatbwsr_map_panzoom_nozoom' ?>">
					<div class="scwatbwsr_map_bg" style="width: <?php echo esc_attr($roomData[0]->width) ?>px; height: <?php echo esc_attr($roomData[0]->height) ?>px">
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
														?><span id="seat<?php echo esc_attr($table->label .$seat) ?>" style="<?php echo esc_attr($newseatstyle) ?>" class="scwatbwsr_map_exclude scwatbwsr_map_tables_table_seat <?php if(in_array($table->label .".".$seat, $bookedSeats)) echo "seatbooked" ?>"><?php echo esc_attr($seat) ?></span><?php
													}
												}
											?>
											</span>
											<span style="<?php echo esc_attr($labelStyle) ?>" class="scwatbwsr_map_tables_table_label scwatbwsr_map_exclude"><?php echo esc_attr($table->label) ?></span>
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
		<?php
	}
}

add_filter( 'woocommerce_cart_item_price', 'scwatbwsr_change_product_price_display', 10, 3 );
function scwatbwsr_change_product_price_display( $price, $product ){
	global $wpdb;
	
	$proId = $product["product_id"];
	
	$customString = "";
	if(isset($_SESSION["seats".$proId])){
		$customString .= "<br>".esc_html__("Booked Seats", "scwatbwsr-translate").": ".str_replace("@", " ", $_SESSION["seats".$proId]);
	}
	if(isset($_SESSION["schedule".$proId])){
		$customString .= "<br>";
		$customString .= esc_html__("Schedule", "scwatbwsr-translate").": ".$_SESSION["schedule".$proId];
	}
	$allowed_html = ['br' => [], 'span' => []];
	echo wp_kses($price.$customString, $allowed_html);
}

add_action( 'woocommerce_before_calculate_totals', 'scwatbwsr_add_custom_price' );
function scwatbwsr_add_custom_price( $cart_object ){
	global $wpdb;
	global $woocommerce;
	$woove = $woocommerce->version;
	
    if ( is_admin() && !defined('DOING_AJAX') )
        return;
	
	foreach ( $cart_object->get_cart() as $cart_item ) {
		if( (float)$woove < 3 ){
			$proId = $cart_item['data']->id;
			$sale_price = $cart_item['data']->price;
		}else{
			$proId = $cart_item['data']->get_id();
			$cuprice = $cart_item['data']->get_data();
			$sale_price = $cuprice["sale_price"];
			if(!$sale_price) $sale_price = $cuprice["regular_price"];
		}
		
		$proTb = $wpdb->prefix . 'scwatbwsr_products';
		$tablesTb = $wpdb->prefix . 'scwatbwsr_tables';
		$pricesTb = $wpdb->prefix . 'scwatbwsr_prices';
		
		$getRoomSql = $wpdb->prepare("SELECT * from {$proTb} where proid=%d", $proId);
		$room = $wpdb->get_results($getRoomSql);
		$roomid = $room[0]->roomid;
		
		$total = 0;
		
		if(isset($_SESSION["seats".$proId])){
			$seats = explode("@", $_SESSION["seats".$proId]);
			$pertbArr = array();
			$onetimeArr = array();
			
			foreach($seats as $seat){
				$checkseat = explode(".", $seat);
				
				$getTypeSql = $wpdb->prepare("SELECT * from {$tablesTb} where roomid=%d and label=%s", $roomid, $checkseat[0]);
				$getType = $wpdb->get_results($getTypeSql);
				$typeid = $getType[0]->type;
				
				$getPriceSql = $wpdb->prepare("SELECT * from {$pricesTb} where typeid=%d", $typeid);
				$getPrice = $wpdb->get_results($getPriceSql);
				
				if($getPrice && $getPrice[0]->price){
					if($getPrice[0]->type == "seat"){
						$total += $getPrice[0]->price;
					}elseif($getPrice[0]->type == "table"){
						$pertbArr[] = array(
							'tb'=> $checkseat[0],
							'price' => $getPrice[0]->price
						);
					}else{
						$onetimeArr[] = array(
							'tb'=> $checkseat[0],
							'price' => $getPrice[0]->price
						);
					}
				}
			}
			
			$pertbArr = array_map("unserialize", array_unique(array_map("serialize", $pertbArr)));
			if($pertbArr)
				$total += $pertbArr[0]["price"] * count($pertbArr);
			if($onetimeArr)
				$total += $onetimeArr[0]["price"];
		}
		
		if($total)
			$cart_item['data']->set_price( ((float)$total / $cart_item['quantity']) + $sale_price );
	}
}

add_filter( 'woocommerce_order_item_name', 'scwatbwsr_order_complete' , 10, 2 );
function scwatbwsr_order_complete( $link, $item ){
	global $wpdb;
	global $wp;
	
	$proId = $item["product_id"];
	$order_id  = absint($wp->query_vars['order-received']);
	
	$customString = "";
	if($proId && $order_id){
		$orderTable = $wpdb->prefix . 'scwatbwsr_orders';
		
		if(isset($_SESSION["seats".$proId])){
			$checkSeats = explode("@", $_SESSION["seats".$proId]);
			$insetArr = $checkSeats;
			$boughtArr = array();
			
			$customString .= "<br>".esc_html__("Booked Seats", "scwatbwsr-translate").": ";
			
			foreach($checkSeats as $ks=>$seat){
				if(isset($_SESSION["schedule".$proId]))
					$checkOrder = $wpdb->prepare("SELECT * from {$orderTable} where FIND_IN_SET(%s, seats) and productId=%d and schedule=%s", $seat, $proId, $_SESSION["schedule".$proId]);
				else
					$checkOrder = $wpdb->prepare("SELECT * from {$orderTable} where FIND_IN_SET(%s, seats) and productId=%d", $seat, $proId);
				$checkOrderRs = $wpdb->get_results($checkOrder);
				
				if(isset($checkOrderRs[0]->seats)){
					unset($insetArr[$ks]);
					array_push($boughtArr, $seat);
				}
			}
			if($insetArr){
				$wpdb->query($wpdb->prepare("INSERT INTO $orderTable (`productId`, `orderId`, `seats`, `schedule`)
				VALUES (%d, %s, %s, %s)", 
				$proId, $order_id, implode(",", $insetArr), $_SESSION["schedule".$proId]));
				
				$customString .= "<br>".implode(" ", $insetArr);
			}
			if($boughtArr){
				$customString .= "<br>These seats no longer available: ".implode(" ", $boughtArr);
			}
			
			if(isset($_SESSION["schedule".$proId])){
				$customString .= "<br>";
				$customString .= esc_html__("Schedule", "scwatbwsr-translate").": ".$_SESSION["schedule".$proId];
			}
		}
	}
	
	$allowed_html = ['br' => [], 'span' => []];
	echo wp_kses($link.$customString, $allowed_html);
}

add_action( 'woocommerce_before_order_itemmeta', 'scwatbwsr_admin_edit_order', 10, 3 );
function scwatbwsr_admin_edit_order( $item_id, $item, $product ){
	global $wpdb;
	$proId = $product->get_id();
    $postId = $_GET['post'];
	
	$orderSeatTable = $wpdb->prefix . 'scwatbwsr_orders';
	$selectTypeSql = $wpdb->prepare("SELECT * from {$orderSeatTable} where productId=%d and orderId=%s", $proId, $postId);
	$orderSeats = $wpdb->get_results($selectTypeSql);
	
	if($orderSeats){
		echo "<br>";
		echo esc_html__("Booked Seats", "scwatbwsr-translate").": ".str_replace(",", " ", $orderSeats[0]->seats);
		
		if($orderSeats[0]->schedule)
			echo "<br>".esc_html__("Schedule", "scwatbwsr-translate").": ".$orderSeats[0]->schedule;
	}
}

function scwatbwsr_cart_updated( $removed_cart_item_key, $cart ) {
    $line_item = $cart->removed_cart_contents[ $removed_cart_item_key ];
    $product_id = $line_item[ 'product_id' ];
	
	unset($_SESSION["seats".$product_id]);
	unset($_SESSION["schedule".$product_id]);
};
add_action( 'woocommerce_cart_item_removed', 'scwatbwsr_cart_updated', 10, 2 );

// wordpress post
//add_action( 'add_meta_boxes', 'scwatbwsr_add_tab_admin_post', 10, 2 );
function scwatbwsr_add_tab_admin_post($post_type, $post){
	global $wp_meta_boxes;
	$wp_meta_boxes[ 'post' ][ 'normal' ][ 'core' ][ 'scwatbwsr' ][ 'title' ] = "SCW Table Booking";
	$wp_meta_boxes[ 'post' ][ 'normal' ][ 'core' ][ 'scwatbwsr' ][ 'args' ] = "";
	$wp_meta_boxes[ 'post' ][ 'normal' ][ 'core' ][ 'scwatbwsr' ][ 'id' ] = "scwatbwsr";
	$wp_meta_boxes[ 'post' ][ 'normal' ][ 'core' ][ 'scwatbwsr' ][ 'callback' ] = "scwatbwsr_add_tab_admin_post_display";
}
function scwatbwsr_add_tab_admin_post_display(){
	global $wpdb;
	$postId = $_GET['post'];
	
	if($postId && get_post_type($postId) == "post"){
		wp_register_script('scwatbwsr-productscript', SCWATBWSR_URL .'js/product.js');
		wp_enqueue_script('scwatbwsr-productscript');
		wp_register_style('scwatbwsr-productcss', SCWATBWSR_URL .'css/product.css');
		wp_enqueue_style('scwatbwsr-productcss');
		
		$roomsTb = $wpdb->prefix . 'scwatbwsr_rooms';
		$getRoomsSql = $wpdb->prepare("SELECT * from {$roomsTb} where %d", 1);
		$rooms = $wpdb->get_results($getRoomsSql);
		
		$productsTb = $wpdb->prefix . 'scwatbwsr_products';
		$getProductsSql = $wpdb->prepare("SELECT * from {$productsTb} where proid=%d", $postId);
		$proInfo = $wpdb->get_results($getProductsSql);
		if(isset($proInfo[0]->roomid)) $currentId = $proInfo[0]->roomid;
		else $currentId = 0;
		
		$roomname = "";
		?>
	
		<div class="scwatbwsr_content">
			<input type="hidden" class="scwatbwsr_proid" value="<?php echo esc_attr($postId) ?>">
			<div class="scwatbwsr_select">
				<select class="scwatbwsr_select_profile">
					<option value="">-- <?php echo esc_html__("Select a Room", "scwatbwsr-translate") ?> --</option>
					<?php
						if($rooms){
							foreach($rooms as $room){
								if($room->id == $proInfo[0]->roomid) $roomname = $room->roomname;
								?><option <?php if($room->id == $currentId) echo "selected" ?> value="<?php echo esc_attr($room->id) ?>"><?php echo esc_attr($room->roomname) ?></option><?php
							}
						}
					?>
				</select>
			</div>
			<div class="scwatbwsr_roomname"><?php echo esc_attr($roomname) ?></div>
			<div class="scwatbwsr_booked">
			
			</div>
		</div>
		<?php
	}
}

function bookingEmail($booking,$subject='')
{
	$htmlContent = '<table>'.
	'<tr><td>Name</td><td>'.$booking['name'].'</td>'.
	'<tr><td>Date</td><td>'.$booking['schedule'].'</td>'.
	'<tr><td>Email</td><td>'.$booking['email'].'</td>'.
	'<tr><td>Phone</td><td>'.$booking['phone'].'</td>'.
	'<tr><td>Seats</td><td>'.$booking['seats'].'</td>'.
	'<tr><td>No Seats</td><td>'.$booking['no_seats'].'</td>'.
	'<tr><td>Notes</td><td>'.$booking['note'].'</td>';
	if($booking['total']>0)
	{
		if($booking['tran_id']!='' || $booking['trand_id']=='offline')
		{
			$htmlContent .= '<tr><td>Payment</td><td>Offline</td>';
		}
		else 
		{
			$htmlContent .= '<tr><td>Payment</td><td>Online</td>';
			$htmlContent .='<tr><td>Transaction ID</td><td>'.$booking['_ipp_transaction_id'].'</td>';
			$htmlContent .= '<tr><td>Payment Status</td><td>'.$booking['_ipp_status'].'</td>';
			$htmlContent .= '<tr><td>Tax</td><td>'.$booking['_ipp_tax'].'</td>';
		}
		$htmlContent .= '<tr><td>Price</td><td>'.$booking['total'].'</td>';
	}
	else 
	{
		$htmlContent .= '<tr><td>Payment</td><td>Free Booking</td>';
	}
	$htmlContent .= '<tr><td>Booking Status</td><td>'.$booking['booking_status'].'</td>'.
	'<tr><td>Order ID</td><td>'.$booking['orderId'].'</td>'.
	'</table>';
    if($subject=='')
	$subject = 'Remainder Booking Information';

	$body .= '<h2>Booking Date<h2> '.date("l M d, Y",strtotime($booking['schedule']));

	$body .="<p>Restaurant Message : ".$booking['textarea_email']." </p>";

	$body = '<h1>Booking information<h1>';

	
	$body .=$htmlContent;

	$headers = array('Content-Type: text/html; charset=UTF-8');

	$adminEmail = get_option( 'admin_email' );

	wp_mail( array($booking['email'], $adminEmail), $subject, $body, $headers );
}
    function setUSANumber($phone)
	{
		$phone = preg_replace('/[^0-9]/', '', $phone);
		$phone = substr($phone,-10);
		return  "+1".$phone;
	}
   function send_message($message, $to)
    {
     
	
		$api_details = get_option( 'scwatbwsr_settings_twilio' );
        
        if (is_array($api_details) and count($api_details) != 0) {
            $TWILIO_SID = $api_details["twilio_sid"];
            $TWILIO_SECERT = $api_details["twilio_secert"];
			$TWILIO_ACCOUNT_SID = $api_details['twilio_account_sid'];
			$TWILIO_SENDER_ID = $api_details['twilio_number'];
			
        }
		
        $to = setUSANumber($to);
		$TWILIO_SENDER_ID = setUSANumber($TWILIO_SENDER_ID);
		
        try {
            $client = new Client($TWILIO_SID, $TWILIO_SECERT, $TWILIO_ACCOUNT_SID);
            $response = $client->messages->create(
                $to,
                array(
                    "from" => $TWILIO_SENDER_ID,
                    "body" => $message
                )
            );
			
            return true;
        } catch (Exception $e) {
			
           return false;
        }
    }