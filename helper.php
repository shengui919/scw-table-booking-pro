<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-config.php');
include_once dirname(__FILE__) . '/includes/settings-ipp.php';
include_once dirname(__FILE__) . '/includes/functions.php';
global $wpdb;
if (isset($_GET['notify']) && $_GET['notify'] == 1) {
	orderUpdate($_GET['id'], ["seats" => "Booked"]);
}

$task = $_POST["task"];

if ($task == "add_page") {
	$reservations_page = wp_insert_post(array(
		'post_title' => (isset($_POST['reservations_page_title']) ? sanitize_text_field($_POST['reservations_page_title']) : ''),
		'post_content' => '[scw_booking_form]',
		'post_status' => 'publish',
		'post_type' => 'page'

	));

	if ($reservations_page) {
		$rtb_options = get_option('scw-settings');
		$rtb_options['scw-booking-page'] = $reservations_page;
		update_option('scw-settings', $rtb_options);
	}
} elseif ($task == "add_room") {
	$roomName = filter_var($_POST["roomName"], FILTER_SANITIZE_STRING);

	$roomsTb = $wpdb->prefix . 'scwatbwsr_rooms';
	$getdtSql = $wpdb->prepare("SELECT * from {$roomsTb} where roomname = %s", $roomName);
	$rs = $wpdb->get_results($getdtSql);

	if ($rs) {
		echo "This room already exists!";
	} else {
		$wpdb->query($wpdb->prepare(
			"INSERT INTO $roomsTb (roomname)
		VALUES (%s)",
			$roomName
		));
		$allOptions = get_option('scw-settings');
		$proid = $allOptions['scw-booking-page'];
		$vl = $wpdb->insert_id;

		$tableName = $wpdb->prefix . 'scwatbwsr_products';
		$getrs = $wpdb->prepare("SELECT * from {$tableName} where proid=%d", $proid);
		$rs = $wpdb->get_results($getrs);

		if ($rs) {
			$wpdb->query($wpdb->prepare(
				"UPDATE {$tableName} SET roomid=%d where proid=%d",
				$vl,
				$proid
			));
		} else {
			$wpdb->query($wpdb->prepare(
				"INSERT INTO $tableName (roomid, proid)
		VALUES (%d, %d)",
				$vl,
				$proid
			));
		}
	}
} elseif ($task == "save_base_setting") {
	$roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
	$bktime = filter_var(30, FILTER_VALIDATE_INT);
	$width = filter_var($_POST["width"], FILTER_SANITIZE_STRING);
	$height = filter_var($_POST["height"], FILTER_SANITIZE_STRING);
	$newRoomname = filter_var($_POST["newRoomname"], FILTER_SANITIZE_STRING);
	$color = filter_var($_POST["color"], FILTER_SANITIZE_STRING);
	$bg = filter_var($_POST["bg"], FILTER_SANITIZE_STRING);
	$tbbookedcolor = filter_var($_POST["tbbookedcolor"], FILTER_SANITIZE_STRING);
	$seatbookedcolor = filter_var($_POST["seatbookedcolor"], FILTER_SANITIZE_STRING);
	$compulsory = filter_var("no", FILTER_SANITIZE_STRING);
	$zoom = filter_var(1, FILTER_VALIDATE_INT);

	$roomsTb = $wpdb->prefix . 'scwatbwsr_rooms';
	$wpdb->query($wpdb->prepare(
		"UPDATE $roomsTb SET roomname=%s, roomcolor=%s, roombg=%s, width=%s, height=%s, tbbookedcolor=%s, seatbookedcolor=%s, bookingtime=%d, compulsory=%s, zoomoption=%d WHERE id=%d",
		$newRoomname,
		$color,
		$bg,
		$width,
		$height,
		$tbbookedcolor,
		$seatbookedcolor,
		$bktime,
		$compulsory,
		$zoom,
		$roomId
	));
	
} 
elseif ($task == "update_room_position") {
	$roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
	$rleft = filter_var($_POST["rleft"], FILTER_SANITIZE_STRING);
	$rtop = filter_var($_POST["rtop"], FILTER_SANITIZE_STRING);
	

	$roomsTb = $wpdb->prefix . 'scwatbwsr_rooms';
	$wpdb->query($wpdb->prepare(
		"UPDATE $roomsTb SET rleft=%s, rtop=%s  WHERE id=%d",
		$rleft,
		$rtop,
		$roomId
	));
	
}
elseif ($task == "add_type") {
	$roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
	$typename = filter_var($_POST["typename"], FILTER_SANITIZE_STRING);
	$tbbg = filter_var($_POST["tbbg"], FILTER_SANITIZE_STRING);
	$tbshape = filter_var($_POST["tbshape"], FILTER_SANITIZE_STRING);
	$tbrecwidth = filter_var($_POST["tbrecwidth"], FILTER_SANITIZE_STRING);
	$tbrecheight = filter_var($_POST["tbrecheight"], FILTER_SANITIZE_STRING);
	$tbcirwidth = filter_var($_POST["tbcirwidth"], FILTER_SANITIZE_STRING);
	$seatbg = filter_var($_POST["seatbg"], FILTER_SANITIZE_STRING);
	$seatshape = filter_var($_POST["seatshape"], FILTER_SANITIZE_STRING);
	$seatwidth = filter_var($_POST["seatwidth"], FILTER_SANITIZE_STRING);

	$typesTb = $wpdb->prefix . 'scwatbwsr_types';
	$getdtSql = $wpdb->prepare("SELECT * from {$typesTb} where roomid = %s and name=%s", $roomId, $typename);
	$rs = $wpdb->get_results($getdtSql);

	if ($rs) {
		echo "This type already exists!";
	} else {
		$wpdb->query($wpdb->prepare(
			"INSERT INTO $typesTb (roomid, name, tbbg, tbshape, tbrecwidth, tbrecheight, tbcirwidth,
		seatbg, seatshape, seatwidth)
		VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
			$roomId,
			$typename,
			$tbbg,
			$tbshape,
			$tbrecwidth,
			$tbrecheight,
			$tbcirwidth,
			$seatbg,
			$seatshape,
			$seatwidth
		));
	}
} elseif ($task == "save_type") {
	$thistypeid = filter_var($_POST["thistypeid"], FILTER_VALIDATE_INT);
	$thistbcolor = filter_var($_POST["thistbcolor"], FILTER_SANITIZE_STRING);
	$thistbrecwidth = filter_var($_POST["thistbrecwidth"], FILTER_SANITIZE_STRING);
	$thistbrecheight = filter_var($_POST["thistbrecheight"], FILTER_SANITIZE_STRING);
	$thistbcirwidth = filter_var($_POST["thistbcirwidth"], FILTER_SANITIZE_STRING);
	$thisseatcolor = filter_var($_POST["thisseatcolor"], FILTER_SANITIZE_STRING);
	$seatwidth = filter_var($_POST["seatwidth"], FILTER_SANITIZE_STRING);

	$typesTb = $wpdb->prefix . 'scwatbwsr_types';
	$wpdb->query($wpdb->prepare(
		"UPDATE $typesTb SET tbbg=%s, tbrecwidth=%s, tbrecheight=%s, tbcirwidth=%s, 
	seatbg=%s, seatwidth=%s WHERE id=%d",
		$thistbcolor,
		$thistbrecwidth,
		$thistbrecheight,
		$thistbcirwidth,
		$thisseatcolor,
		$seatwidth,
		$thistypeid
	));
} elseif ($task == "delete_type") {
	$thistypeid = filter_var($_POST["thistypeid"], FILTER_VALIDATE_INT);

	$typesTb = $wpdb->prefix . 'scwatbwsr_types';
	$wpdb->query($wpdb->prepare("DELETE FROM $typesTb where id=%d", $thistypeid));
} elseif ($task == "add_schedule") {
	$roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
	$schedule = date("Y-m-d H:i", strtotime(filter_var($_POST["schedule"], FILTER_SANITIZE_STRING)));
	$startTime = filter_var($_POST["startTime"], FILTER_SANITIZE_STRING);
	$endTime = filter_var($_POST["endTime"], FILTER_SANITIZE_STRING);
	$schedulesTb = $wpdb->prefix . 'scwatbwsr_schedules';
	$getdtSql = $wpdb->prepare("select * from $schedulesTb where roomid=%d and date(schedule)=%s", $roomId, date("Y-m-d", strtotime($schedule)));
	$rs = $wpdb->get_row($getdtSql);

	if ($rs) {
		echo "This date already exists!";
	} else {
		$wpdb->query($wpdb->prepare(
			"INSERT INTO $schedulesTb (roomid, schedule,start_time,end_time)
	VALUES (%d, %s, %s, %s)",
			$roomId,
			$schedule,
			$startTime,
			$endTime
		));
	}
} elseif ($task == "save_schedule") {
	$scheid = filter_var($_POST["scheid"], FILTER_VALIDATE_INT);
	$thisschedule = date("Y-m-d H:i", strtotime(filter_var($_POST["thisschedule"], FILTER_SANITIZE_STRING)));
	$startTime = filter_var($_POST["startTime"], FILTER_SANITIZE_STRING);
	$endTime = filter_var($_POST["endTime"], FILTER_SANITIZE_STRING);

	$schedulesTb = $wpdb->prefix . 'scwatbwsr_schedules';
	$wpdb->query($wpdb->prepare(
		"UPDATE $schedulesTb SET schedule=%s , start_time=%s, end_time=%s WHERE id=%d",
		$thisschedule,
		$startTime,
		$endTime,
		$scheid
	));
} elseif ($task == "delete_schedule") {
	$scheid = filter_var($_POST["scheid"], FILTER_VALIDATE_INT);

	$schedulesTb = $wpdb->prefix . 'scwatbwsr_schedules';
	$wpdb->query($wpdb->prepare("DELETE FROM $schedulesTb where id=%d", $scheid));
} elseif ($task == "change_daily") {
	$roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
	$dailys = filter_var($_POST["dailys"], FILTER_SANITIZE_STRING);
	$dailys = implode(",", $_POST["dailys"]);

	$dailyScheTb = $wpdb->prefix . 'scwatbwsr_dailyschedules';
	$getdtSql = $wpdb->prepare("SELECT * from {$dailyScheTb} where roomid=%s", $roomId);
	$rs = $wpdb->get_results($getdtSql);

	if ($rs) {
		$wpdb->query($wpdb->prepare(
			"UPDATE $dailyScheTb SET daily=%s WHERE roomid=%d",
			$dailys,
			$roomId
		));
	} else {
		$wpdb->query($wpdb->prepare(
			"INSERT INTO $dailyScheTb (roomid, daily)
		VALUES (%d, %s)",
			$roomId,
			$dailys
		));
	}
} elseif ($task == "add_time") {
	$roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
	$scheduletime = filter_var($_POST["scheduletime"], FILTER_SANITIZE_STRING);

	$dailyTimeTb = $wpdb->prefix . 'scwatbwsr_dailytimes';
	$getdtSql = $wpdb->prepare("SELECT * from {$dailyTimeTb} where roomid=%s and time=%s", $roomId, $scheduletime);
	$rs = $wpdb->get_results($getdtSql);

	if ($rs) {
		echo "This time already exists!";
	} else {
		$wpdb->query($wpdb->prepare(
			"INSERT INTO $dailyTimeTb (roomid, time)
		VALUES (%d, %s)",
			$roomId,
			$scheduletime
		));
	}
} elseif ($task == "save_time") {
	$thistimeid = filter_var($_POST["thistimeid"], FILTER_VALIDATE_INT);
	$thistimetime = filter_var($_POST["thistimetime"], FILTER_SANITIZE_STRING);
	$thisendtime = filter_var($_POST["endtime"], FILTER_SANITIZE_STRING);
	$week_day = filter_var($_POST["week_day"], FILTER_SANITIZE_STRING);
	$roomid = filter_var($_POST["roomid"], FILTER_VALIDATE_INT);
	$tableName = $wpdb->prefix . 'scwatbwsr_dailytimes';
	if ($thistimeid == 0) {
		$wpdb->query($wpdb->prepare(
			"INSERT INTO $tableName (start_time, end_time, week_day, roomid)
		VALUES (%s,%s,%s,%d)",
			$thistimetime,
			$thisendtime,
			$week_day,
			$roomid
		));
	} else {
		$wpdb->query($wpdb->prepare(
			"UPDATE $tableName SET start_time=%s,end_time=%s WHERE id=%d",
			$thistimetime,
			$thisendtime,
			$thistimeid
		));
	}
} elseif ($task == "delete_time") {
	$thistimeid = filter_var($_POST["thistimeid"], FILTER_VALIDATE_INT);

	$tableName = $wpdb->prefix . 'scwatbwsr_dailytimes';
	$wpdb->query($wpdb->prepare("DELETE FROM $tableName where id=%d", $thistimeid));
} elseif ($task == "save_price") {
	$priceString = filter_var($_POST["priceString"], FILTER_SANITIZE_STRING);
	$priceString = explode("@", $priceString);

	$pricesTb = $wpdb->prefix . 'scwatbwsr_prices';

	foreach ($priceString as $price) {
		if ($price) {
			$cprice = explode("-", $price);

			if (!$cprice[1] || !is_numeric($cprice[1])) $pri = 0;
			else $pri = $cprice[1];

			$getdtSql = $wpdb->prepare("SELECT * from {$pricesTb} where typeid=%d", $cprice[0]);
			$rs = $wpdb->get_results($getdtSql);

			if ($rs) {
				$wpdb->query($wpdb->prepare(
					"UPDATE $pricesTb SET price=%s, type=%s WHERE typeid=%d",
					$pri,
					$cprice[2],
					$cprice[0]
				));
			} else {
				$wpdb->query($wpdb->prepare(
					"INSERT INTO $pricesTb (typeid, price, type)
				VALUES (%d, %s, %s)",
					$cprice[0],
					$pri,
					$cprice[2]
				));
			}
		}
	}
} elseif ($task == "add_table") {
	$roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
	$label = filter_var($_POST["label"], FILTER_SANITIZE_STRING);
	$seats = filter_var($_POST["seats"], FILTER_SANITIZE_STRING);
	$type = filter_var($_POST["type"], FILTER_SANITIZE_STRING);

	$tablesTb = $wpdb->prefix . 'scwatbwsr_tables';
	$getdtSql = $wpdb->prepare("SELECT * from {$tablesTb} where roomid = %d and label=%s", $roomId, $label);
	$rs = $wpdb->get_results($getdtSql);

	if ($rs) {
		echo "This table already exists!";
	} else {
		$wpdb->query($wpdb->prepare(
			"INSERT INTO $tablesTb (roomid, label, seats, type)
		VALUES (%d, %s, %s, %d)",
			$roomId,
			$label,
			$seats,
			$type
		));
	}
} elseif ($task == "save_table") {
	$thistbid = filter_var($_POST["thistbid"], FILTER_VALIDATE_INT);
	$thistbseats = filter_var($_POST["thistbseats"], FILTER_SANITIZE_STRING);
	$thistbtype = filter_var($_POST["thistbtype"], FILTER_VALIDATE_INT);

	$tableName = $wpdb->prefix . 'scwatbwsr_tables';
	$wpdb->query($wpdb->prepare(
		"UPDATE $tableName SET seats=%s, type=%d WHERE id=%d",
		$thistbseats,
		$thistbtype,
		$thistbid
	));
} elseif ($task == "delete_table") {
	$thistbid = filter_var($_POST["thistbid"], FILTER_VALIDATE_INT);

	$tableName = $wpdb->prefix . 'scwatbwsr_tables';
	$wpdb->query($wpdb->prepare("DELETE FROM $tableName where id=%d", $thistbid));
} elseif ($task == "clone_table") {

	$tableId = filter_var($_POST["thistbid"], FILTER_VALIDATE_INT);
	$label = filter_var($_POST["newname"]);
	$seats = filter_var($_POST["thistbseats"]);
	$type = filter_var($_POST["thistbtype"]);

	$tablesTb = $wpdb->prefix . 'scwatbwsr_tables';
	$getdtSql = $wpdb->prepare("SELECT * from {$tablesTb} where label=%s", $label);
	$rs = $wpdb->get_results($getdtSql);

	$getdatatSql = $wpdb->prepare("SELECT * from {$tablesTb} where id=%s", $tableId);
	$trs = $wpdb->get_results($getdatatSql);

	if ($rs) {
		echo "This table already exists!";
	} else {
		$wpdb->query($wpdb->prepare(
			"INSERT INTO $tablesTb (roomid, label, seats, type, tleft, ttop)
		VALUES (%d, %s, %s, %d, %s, %s)",

			$trs[0]->roomid,
			$label,
			$seats,
			$type,
			$trs[0]->tleft,
			$trs[0]->ttop,
		));
	}
}elseif($task == "offline_payment_history"){

	$bookingId = $_POST['bookingId'];
	$date = $_POST['date'];
	$price = $_POST['amount'];
	$type = $_POST['paymentType'];

	$bookingpaymenthistoryTB = $wpdb->prefix . 'scwatbwsr_booking_payment_history';
	$getdatatSql = $wpdb->prepare("SELECT sum(price) from {$bookingpaymenthistoryTB} where booking_id=%d", $bookingId);
	$trs = $wpdb->get_results($getdatatSql);
	echo $trs;
	die;

	$wpdb->query($wpdb->prepare(
		"INSERT INTO $bookingpaymenthistoryTB (booking_id, date,price, payment_type)
	VALUES (%d, %s, %d, %s)",

		$bookingId,
		$date,
		$price,
		$type,
		
	));
	
} elseif ($task == "save_mapping") {
	$tbstring = filter_var($_POST["tbstring"], FILTER_SANITIZE_STRING);
	$tbstring = explode("@", $tbstring);
	$seatstring = filter_var($_POST["seatstring"], FILTER_SANITIZE_STRING);
	$seatstring = explode("@", $seatstring);

	$tableName = $wpdb->prefix . 'scwatbwsr_tables';
	$tableSeat = $wpdb->prefix . 'scwatbwsr_seats';

	foreach ($tbstring as $tb) {
		$tbdt = explode("#", $tb);

		$wpdb->query($wpdb->prepare(
			"UPDATE $tableName SET tleft=%s, ttop=%s WHERE id=%d",
			$tbdt[1],
			$tbdt[2],
			$tbdt[0]
		));
	}

	foreach ($seatstring as $st) {
		$checkdt = explode("#", $st);

		$tbid = $checkdt[0];
		$seatdts = explode("&", $checkdt[1]);

		foreach ($seatdts as $seatdt) {
			$sdt = explode("$", $seatdt);

			$getdtSql = $wpdb->prepare("SELECT * from {$tableSeat} where tbid = %d and seat=%s", $tbid, $sdt[0]);
			$rs = $wpdb->get_results($getdtSql);

			if ($rs) {
				$wpdb->query($wpdb->prepare(
					"UPDATE $tableSeat SET tleft=%s, ttop=%s WHERE tbid=%d and seat=%s",
					$sdt[1],
					$sdt[2],
					$tbid,
					$sdt[0]
				));
			} else {
				$wpdb->query($wpdb->prepare(
					"INSERT INTO $tableSeat (tbid, seat, tleft, ttop)
				VALUES (%d, %s, %s, %s)",
					$tbid,
					$sdt[0],
					$sdt[1],
					$sdt[2]
				));
			}
		}
	}
} elseif ($task == "save_product_profile") {
	$proid = filter_var($_POST["proid"], FILTER_VALIDATE_INT);
	$vl = filter_var($_POST["vl"], FILTER_VALIDATE_INT);

	$tableName = $wpdb->prefix . 'scwatbwsr_products';
	$getrs = $wpdb->prepare("SELECT * from {$tableName} where proid=%d", $proid);
	$rs = $wpdb->get_results($getrs);

	if ($rs) {
		$wpdb->query($wpdb->prepare(
			"UPDATE {$tableName} SET roomid=%d where proid=%d",
			$vl,
			$proid
		));
	} else {
		$wpdb->query($wpdb->prepare(
			"INSERT INTO $tableName (roomid, proid)
		VALUES (%d, %d)",
			$vl,
			$proid
		));
	}
} elseif ($task == "copy_room") {
	$roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
	$newname = filter_var($_POST["newname"], FILTER_SANITIZE_STRING);

	$tableRooms = $wpdb->prefix . 'scwatbwsr_rooms';

	$getMaxid = $wpdb->prepare("SELECT MAX(id) maxid from {$tableRooms} where roomname=%s", $newname);
	$getMaxidRs = $wpdb->get_var($getMaxid);
	if ($getMaxidRs) {
		echo "Room name is exits!";
		die;
	}

	$getRoomData = $wpdb->prepare("SELECT * from {$tableRooms} where id=%d", $roomId);
	$roomData = $wpdb->get_results($getRoomData);
	if ($roomData) {
		$wpdb->query($wpdb->prepare(
			"INSERT INTO $tableRooms ( roomname, roomcolor, roombg, width, height, tbbookedcolor, seatbookedcolor)
		VALUES ( %s, %s, %s, %s, %s, %s, %s)",
			$newname,
			$roomData[0]->roomcolor,
			$roomData[0]->roombg,
			$roomData[0]->width,
			$roomData[0]->height,
			$roomData[0]->tbbookedcolor,
			$roomData[0]->seatbookedcolor
		));
		$maxid = $wpdb->insert_id;
	}

	$tableDailySche = $wpdb->prefix . 'scwatbwsr_dailyschedules';
	$getDailySche = $wpdb->prepare("SELECT * from {$tableDailySche} where roomid=%d", $roomId);
	$dailySche = $wpdb->get_results($getDailySche);
	if ($dailySche) {
		$wpdb->query($wpdb->prepare(
			"INSERT INTO $tableDailySche (roomid, daily)
		VALUES (%d, %s)",
			$maxid,
			$dailySche[0]->daily
		));
	}

	$tableDailyTime = $wpdb->prefix . 'scwatbwsr_dailytimes';
	$getDailyTime = $wpdb->prepare("SELECT * from {$tableDailyTime} where roomid=%d", $roomId);
	$dailyTime = $wpdb->get_results($getDailyTime);
	if ($dailyTime) {
		foreach ($dailyTime as $dt) {
			$wpdb->query($wpdb->prepare(
				"INSERT INTO $tableDailyTime (roomid, start_time,end_time,week_day)
			VALUES (%d, %s, %s, %s)",
				$maxid,
				$dt->start_time,
				$dt->end_time,
				$dt->week_day
			));
		}
	}

	$tableSches = $wpdb->prefix . 'scwatbwsr_schedules';
	$getSches = $wpdb->prepare("SELECT * from {$tableSches} where roomid=%d", $roomId);
	$sches = $wpdb->get_results($getSches);
	if ($sches) {
		foreach ($sches as $sche) {
			$wpdb->query($wpdb->prepare(
				"INSERT INTO $tableSches (roomid, schedule, start_time, end_time)
			VALUES (%d, %s, %s, %s)",
				$maxid,
				$sche->schedule,
				$sche->start_time,
				$sche->end_time
			));
		}
	}

	$tablePrices = $wpdb->prefix . 'scwatbwsr_prices';
	$tableTypes = $wpdb->prefix . 'scwatbwsr_types';
	$getTypes = $wpdb->prepare("SELECT * from {$tableTypes} where roomid=%d", $roomId);
	$types = $wpdb->get_results($getTypes);
	if ($types) {
		$getMaxType = $wpdb->prepare("SELECT MAX(id) maxtypeid from {$tableTypes} where %d", 1);
		$getMaxTypeRs = $wpdb->get_results($getMaxType);

		foreach ($types as $type) {
			$wpdb->query($wpdb->prepare(
				"INSERT INTO $tableTypes ( roomid, name, tbbg, tbshape, tbrecwidth, tbrecheight, tbcirwidth, seatbg, seatshape, seatwidth)
			VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
				$maxid,
				$type->name,
				$type->tbbg,
				$type->tbshape,
				$type->tbrecwidth,
				$type->tbrecheight,
				$type->tbcirwidth,
				$type->seatbg,
				$type->seatshape,
				$type->seatwidth
			));
			$maxtype = $wpdb->insert_id;

			$getPrice = $wpdb->prepare("SELECT * from {$tablePrices} where typeid=%d", $oldid);
			$price = $wpdb->get_results($getPrice);
			if ($price) {
				$wpdb->query($wpdb->prepare(
					"INSERT INTO $tablePrices (typeid, price, type)
				VALUES (%d, %s, %s)",
					$maxtype,
					$price[0]->price,
					$price[0]->type
				));
			}
		}
	}

	$tableTables = $wpdb->prefix . 'scwatbwsr_tables';
	$tableSeats = $wpdb->prefix . 'scwatbwsr_seats';
	$tableBookedSeats = $wpdb->prefix . 'scwatbwsr_bookedseats';
	$getTables = $wpdb->prepare("SELECT * from {$tableTables} where roomid=%d", $roomId);
	$tables = $wpdb->get_results($getTables);
	if ($tables) {
		$getMaxTable = $wpdb->prepare("SELECT MAX(id) maxtbid from {$tableTables} where %d", 1);
		$getMaxTableRs = $wpdb->get_results($getMaxTable);
		$maxtb = $getMaxTableRs[0]->maxtbid + 1;

		foreach ($tables as $table) {
			$wpdb->query($wpdb->prepare(
				"INSERT INTO $tableTables (id, roomid, label, seats, type, tleft, ttop)
			VALUES (%d, %d, %s, %s, %d, %s, %s)",
				$maxtb,
				$maxid,
				$table->label,
				$table->seats,
				$maxtype,
				$table->tleft,
				$table->ttop
			));
			$maxtb = $wpdb->insert_id;
			$oldid = $table->id;

			$getSeat = $wpdb->prepare("SELECT * from {$tableSeats} where tbid=%d", $oldid);
			$seats = $wpdb->get_results($getSeat);
			if ($seats) {
				foreach ($seats as $s) {
					$wpdb->query($wpdb->prepare(
						"INSERT INTO $tableSeats (tbid, seat, tleft, ttop)
					VALUES (%d, %s, %s, %s)",
						$maxtb,
						$s->seat,
						$s->tleft,
						$s->ttop
					));
					$wpdb->query($wpdb->prepare(
						"INSERT INTO $tableBookedSeats (roomid, tb, tb_id, seat)
					VALUES (%d, %s, %d, %d)",
						$maxid,
						$table->label,
						$s->tb_id,
						$s->seat
					));
				}
			}
		}
	}
} elseif ($task == "delete_room") {
	$roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);

	$roomsTb = $wpdb->prefix . 'scwatbwsr_rooms';
	$wpdb->query($wpdb->prepare("DELETE FROM $roomsTb where id=%d", $roomId));

	$typesTB = $wpdb->prefix . 'scwatbwsr_types';
	$pricesTB = $wpdb->prefix . 'scwatbwsr_prices';
	$getTypes = $wpdb->prepare("SELECT * from {$typesTB} where roomid=%d", $roomId);
	$types = $wpdb->get_results($getTypes);
	if ($types) {
		foreach ($types as $type) {
			$wpdb->query($wpdb->prepare("DELETE FROM $pricesTB where typeid=%d", $type->id));
		}
	}
	$wpdb->query($wpdb->prepare("DELETE FROM $typesTB where roomid=%d", $roomId));

	$tablesTB = $wpdb->prefix . 'scwatbwsr_tables';
	$seatsTB = $wpdb->prefix . 'scwatbwsr_seats';
	$getTables = $wpdb->prepare("SELECT * from {$tablesTB} where roomid=%d", $roomId);
	$tables = $wpdb->get_results($getTables);
	if ($tables) {
		foreach ($tables as $tb) {
			$wpdb->query($wpdb->prepare("DELETE FROM $seatsTB where tbid=%d", $tb->id));
		}
	}
	$wpdb->query($wpdb->prepare("DELETE FROM $tablesTB where roomid=%d", $roomId));

	$dlsTB = $wpdb->prefix . 'scwatbwsr_dailyschedules';
	$wpdb->query($wpdb->prepare("DELETE FROM $dlsTB where roomid=%d", $roomId));

	$dltTB = $wpdb->prefix . 'scwatbwsr_dailytimes';
	$wpdb->query($wpdb->prepare("DELETE FROM $dltTB where roomid=%d", $roomId));

	$proTB = $wpdb->prefix . 'scwatbwsr_products';
	$wpdb->query($wpdb->prepare("DELETE FROM $proTB where roomid=%d", $roomId));

	$schedulesTB = $wpdb->prefix . 'scwatbwsr_schedules';
	$wpdb->query($wpdb->prepare("DELETE FROM $schedulesTB where roomid=%d", $roomId));
} elseif ($task == "check_schedule") {
	
	$schedule = date("Y-m-d H:i:s", strtotime(filter_var($_POST["schedule"], FILTER_SANITIZE_STRING)));
	
	$seats = filter_var($_POST["seats"], FILTER_SANITIZE_STRING);
    
	$dayname = strtolower(date("l",strtotime($schedule)));
    
	$bookingtime = date("H:i",strtotime($schedule));

	$rest_settings = get_option("scwatbwsr_settings_rest");

	$duration = $rest_settings["booking_time"];

	// find the daily schedules 

	$startTime ="03:00 PM";

	$endTime  ="10:00 PM";

	$times = [];
   
	
	
    
	$schedulesListQuery = $wpdb->prepare("SELECT * from ".schedulesTB." where roomid=%d and schedule>=%s",0,$schedule);
    $schedulesList = $wpdb->get_results($schedulesListQuery,ARRAY_A);
	$dailyschedulesListQuery = $wpdb->prepare("SELECT * from ".dailyschedulesTB);
	$dailyschedulesList = $wpdb->get_results($dailyschedulesListQuery,ARRAY_A);
	$dailytimesTBListQuery = $wpdb->prepare("SELECT * from ".dailytimesTB);
	$dailytimesTBListQueryList = $wpdb->get_results($dailytimesTBListQuery,ARRAY_A);
	$listUnabvaileRoom=[];
	$findMatchingRoom =  array_filter($schedulesList,function($d)use($schedule){
		return ($d["rooomid"]!="0" && date("Y-m-d",strtotime($d["schedule"])) == date("Y-m-d",strtotime($schedule)));
	 });
	 if($findMatchingRoom)
	 array_push($listUnabvaileRoom,...array_values($findMatchingRoom));
	 $findMatchingRoom =  array_filter($dailyschedulesList,function($d)use($dayname){
		$daysArr=explode(",",$d["daily"]);
		
		return ($d["roomid"] != "0" && in_array($dayname,$daysArr));
	});
	if($findMatchingRoom)
	array_push($listUnabvaileRoom,...array_values($findMatchingRoom));
	if(count($schedulesList)>0)
	{
       $findMatchingDay =  array_filter($schedulesList,function($d)use($schedule){
		  return (date("Y-m-d",strtotime($d["schedule"])) == date("Y-m-d",strtotime($schedule)));
	   });
	   if($findMatchingDay)
	   {
		$findMatchingDay = array_values($findMatchingDay);
		 $startTime = $findMatchingDay[0]["start_time"];
		 $endTime = $findMatchingDay[0]["end_time"];
		 $times= bookingTimes($duration,$startTime,$endTime);
	   }
	}
    else 
	{
		
		
		$findMatchingDay =  array_filter($dailyschedulesList,function($d)use($dayname){
			$daysArr=explode(",",$d["daily"]);
			
			return ($d["roomid"] == "0" && in_array($dayname,$daysArr));
		});
		
		if($findMatchingDay)
		{
		
		$findMatchingDay = array_filter($dailytimesTBListQueryList,function($d)use($dayname){
			return ($dayname==$d["week_day"]);
		});
		$findMatchingDay = array_values($findMatchingDay);
			$startTime = $findMatchingDay[0]["start_time"];
			$endTime = $findMatchingDay[0]["end_time"];
			
			$times= bookingTimes($duration,$startTime,$endTime);
        }
	
	}
    
	$ordersTb = $wpdb->prefix . 'scwatbwsr_orders';

	$bookedSeats = array();
    
	

	if ($bookingtime) {
		
			$cschedule = explode(" ", $schedule);
			$cschedule1 = explode("-", $cschedule[0]);
			$schedule = $cschedule1[2] . "-" . $cschedule1[1] . "-" . $cschedule1[0] . " " . $cschedule[1];
		

		for ($i = 0; $i <= $bookingtime; $i += 5) {
			$datesche = date(get_option('date_format') . " H:i", strtotime("-" . $i . " minutes", strtotime($schedule)));
			$getOrdersSql = $wpdb->prepare("SELECT * from {$ordersTb} where  schedule=%s",  $datesche);
			$orders = $wpdb->get_results($getOrdersSql);
			if ($orders) {
				foreach ($orders as $order) {
					$oseats = explode(",", $order->seats);
					foreach ($oseats as $os) {
						array_push($bookedSeats, $os);
					}
				}
			}
		}
	} else {
		$getOrdersSql = $wpdb->prepare("SELECT * from {$ordersTb} where schedule=%s",  $schedule);
		$orders = $wpdb->get_results($getOrdersSql);
		if ($orders) {
			foreach ($orders as $order) {
				$oseats = explode(",", $order->seats);
				foreach ($oseats as $os) {
					array_push($bookedSeats, $os);
				}
			}
		}
	}

	
	$bookedSeats = array_unique($bookedSeats);

	echo wp_send_json(array("times"=>$times,"bookedSeats"=>$bookedSeats,"rest_settings"=>$rest_settings,"listUnabvaileRoom"=>$listUnabvaileRoom));
} elseif ($task == "sess_seats") {
	$proid = filter_var($_POST["proid"], FILTER_VALIDATE_INT);
	$seats = filter_var($_POST["seats"], FILTER_SANITIZE_STRING);
	if ($seats == '') {
		echo 0;
	} else {
		$posttype = filter_var($_POST["posttype"], FILTER_SANITIZE_STRING);

		$_SESSION["seats" . $proid] = $seats;

		if ($posttype == "post" || $posttype == "page") {
			$proTb = $wpdb->prefix . 'scwatbwsr_products';
			$tablesTb = $wpdb->prefix . 'scwatbwsr_tables';
			$pricesTb = $wpdb->prefix . 'scwatbwsr_prices';

			$getRoomSql = $wpdb->prepare("SELECT * from {$proTb} where proid=%d", $proid);
			$room = $wpdb->get_results($getRoomSql);
			$roomid = $room[0]->roomid;

			$total = 0;

			if (isset($_SESSION["seats" . $proid])) {
				$seats = explode("@", $_SESSION["seats" . $proid]);
				$pertbArr = array();
				$onetimeArr = array();

				foreach ($seats as $seat) {
					$checkseat = explode(".", $seat);

					$getTypeSql = $wpdb->prepare("SELECT * from {$tablesTb} where roomid=%d and label=%s", $roomid, $checkseat[0]);
					$getType = $wpdb->get_results($getTypeSql);
					$typeid = $getType[0]->type;

					$getPriceSql = $wpdb->prepare("SELECT * from {$pricesTb} where typeid=%d", $typeid);
					$getPrice = $wpdb->get_results($getPriceSql);

					if ($getPrice && $getPrice[0]->price) {
						if ($getPrice[0]->type == "seat") {
							$total += $getPrice[0]->price;
						} elseif ($getPrice[0]->type == "table") {
							$pertbArr[] = array(
								'tb' => $checkseat[0],
								'price' => $getPrice[0]->price
							);
						} else {
							$onetimeArr[] = array(
								'tb' => $checkseat[0],
								'price' => $getPrice[0]->price
							);
						}
					}
				}

				$pertbArr = array_map("unserialize", array_unique(array_map("serialize", $pertbArr)));
				if ($pertbArr)
					$total += $pertbArr[0]["price"] * count($pertbArr);
				if ($onetimeArr)
					$total += $onetimeArr[0]["price"];
			}

			echo $total;
		}
	}
} elseif ($task == "delete_order") {
	$oid = filter_var($_POST["oid"], FILTER_VALIDATE_INT);

	$ordersTB = $wpdb->prefix . 'scwatbwsr_orders';
	$wpdb->query($wpdb->prepare("DELETE FROM $ordersTB where id=%d", $oid));
} elseif ($task == "make_as_booked") {
	$roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
	$seat = filter_var($_POST["seat"], FILTER_VALIDATE_INT);
	$bookedTb = $wpdb->prefix . 'scwatbwsr_bookedseats';
	$tableTb = $wpdb->prefix . 'scwatbwsr_tables';
	if (is_integer($seat)) {
		$getdtSql = $wpdb->prepare("SELECT * from {$tableTb} where  id=%d", $seat);
		$tableSeats = $wpdb->get_row($getdtSql);
		$getdtSql = $wpdb->prepare("SELECT * from {$bookedTb} where roomid=%d and tb=%s", $roomId, $tableSeats->label);
		$rs = $wpdb->get_results($getdtSql);

		if ($rs) {
			$wpdb->query($wpdb->prepare("DELETE FROM $bookedTb where roomid=%d and tb=%s ", $roomId, $tableSeats->label));
		} else {
			$seats = explode(",", $tableSeats->seats);
			foreach ($seats as $k => $seat) {
				$wpdb->query($wpdb->prepare(
					"INSERT INTO $bookedTb (roomid, tb, seat, tb_id)
			VALUES (%d, %s, %s, %d)",
					$roomId,
					$tableSeats->label,
					$seat,
					$tableSeats->id
				));
			}
		}
	} else {
		$seat = filter_var($_POST["seat"], FILTER_SANITIZE_STRING);

		$cseat = explode(".", $seat);


		$getdtSql = $wpdb->prepare("SELECT * from {$bookedTb} where roomid=%d and tb=%s and seat=%s", $roomId, $cseat[0], $cseat[1]);
		$rs = $wpdb->get_results($getdtSql);

		if ($rs) {
			$wpdb->query($wpdb->prepare("DELETE FROM $bookedTb where roomid=%d and tb=%s and seat=%s", $roomId, $cseat[0], $cseat[1]));
		} else {
			$wpdb->query($wpdb->prepare(
				"INSERT INTO $bookedTb (roomid, tb, seat)
		VALUES (%d, %s, %s)",
				$roomId,
				$cseat[0],
				$cseat[1]
			));
		}
	}
} elseif ($task == "custom_send_mail") {
	$booking = $_POST;

	bookingEmail($booking);
} else if ($task == "booking_update") {
	$booking_id = filter_var($_POST["booking_id"], FILTER_VALIDATE_INT);

	$booking_update  = $_POST["booking_update"];

    print_r($booking_update);
	$booking = (array) orderGet($booking_id);

	orderUpdate($booking_id, $booking_update);

	$booking['textarea_email'] = " Previous status is $booking[booking_status] Restaurant manager change to new status $booking_status";

	echo send_message(strip_tags($booking['textarea_email']), $booking['phone']);
	bookingEmail($booking, "Booking status is updated!");
} else if ($task == "booking_change_status") {
	$booking_id = filter_var($_POST["booking_id"], FILTER_VALIDATE_INT);

	$booking_status  = filter_var($_POST["booking_status"], FILTER_SANITIZE_STRING);


	$booking = (array) orderGet($booking_id);

	orderUpdate($booking_id, ["booking_status" => $booking_status]);

	$booking['textarea_email'] = " Previous status is $booking[booking_status] Restaurant manager change to new status $booking_status";

	echo send_message(strip_tags($booking['textarea_email']), $booking['phone']);
	bookingEmail($booking, "Booking status is updated!");
} else if ($task == "booking_change_schedule") {
	$booking_id = filter_var($_POST["booking_id"], FILTER_VALIDATE_INT);

	$schedule  = filter_var($_POST["schedule"], FILTER_SANITIZE_STRING);


	$booking = (array) orderGet($booking_id);

	orderUpdate($booking_id, ["schedule" => date("Y-m-d h:i", strtotime($schedule))]);

	$booking['textarea_email'] = " Previous Booking Date is date('F j, Y, H:i',strtotime($booking[schedule])) Restaurant manager change to new Date date('F j, Y, H:i',strtotime($schedule))";
	echo send_message(strip_tags($booking['textarea_email']), $booking['phone']);
	bookingEmail($booking, "Booking Date  is updated!");
} else if ($task == "booking_change_payment") {
	$booking_id = filter_var($_POST["booking_id"], FILTER_VALIDATE_INT);

	$payment_status  = filter_var($_POST["payment_status"], FILTER_SANITIZE_STRING);


	$booking = (array) orderGet($booking_id);

	orderUpdate($booking_id, ["_ipp_status" => $payment_status]);

	$booking['textarea_email'] = " Previous payment status is $booking[_ipp_status] Restaurant manager change to new payment status $payment_status";
	echo send_message(strip_tags($booking['textarea_email']), $booking['phone']);
	bookingEmail($booking, "Booking payment status is updated!");
} elseif ($task == "send_mail") {
	$name = $_POST["name"];
	$address = $_POST["address"];
	$email = $_POST["email"];
	$phone = $_POST["phone"];
	$note = $_POST["note"];
	$proId = $_POST["proId"];
	$total = $_POST["total"];
	$seats = $_POST["seat"];
	$no_seat = $_POST['no_seat'];
	$schedule = date("Y-m-d H:i:s", strtotime($_POST["schedule"]));
	$billing_first_name = $_POST['billing_first_name'] ? $_POST['billing_first_name'] : '';
	$billing_last_name = $_POST['billing_last_name'] ? $_POST['billing_last_name'] : '';
	$billing_address_1 = $_POST['billing_address_1'] ? $_POST['billing_address_1'] : '';
	$billing_address_2 = $_POST['billing_address_2'] ? $_POST['billing_address_2'] : '';
	$billing_country = $_POST['billing_country'] ? $_POST['billing_country'] : '';
	$billing_city = $_POST['billing_city'] ? $_POST['billing_city'] : '';
	$billing_state = $_POST['billing_state'] ? $_POST['billing_state'] : '';
	$billing_postcode = $_POST['billing_postcode'] ? $_POST['billing_postcode'] : '';
	$billing_email = $_POST['billing_email'] ? $_POST['billing_email'] : '';
	$billing_phone = $_POST['billing_phone'] ? $_POST['billing_phone'] : '';
	$_ipp_payment_url = $_POST['_ipp_payment_url'] ? $_POST['_ipp_payment_url'] : '';
	$user = get_current_user_id();
	if (!$user)
		$user = $_COOKIE['PHPSESSID'];

	$table_name = $wpdb->prefix . 'scwatbwsr_orders';
	$count_query = "select count(*) from $table_name";
	$num = $wpdb->get_var($count_query);
	
	
	$booking_status = "Pending";
	
	$wpdb->query($wpdb->prepare(
		"INSERT INTO $table_name (`roomid`,`productId`, `orderId`, `seats`, `schedule`, `name`, `address`, `email`, `phone`, `note`, `total`,`_ipp_status`,`_ipp_transaction_id`,
	`billing_first_name`,`billing_last_name`,`billing_address_1`,`billing_address_2`,`billing_city`,`billing_country`,`billing_state`,`billing_postcode`,`billing_email`,`billing_phone`,`user`,`_ipp_payment_url`,`booking_status`,`no_seats`)
	VALUES (%d,%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d)",
	    $proId,
		$proId,
		date("Ymdhis") . ($num + 1),
		$seats,
		$schedule,
		$name,
		$address,
		$email,
		$phone,
		$note,
		$total,
		"Pending",
		time() . $num,
		$billing_first_name,
		$billing_last_name,
		$billing_address_1,
		$billing_address_2,
		$billing_city,
		$billing_country,
		$billing_state,
		$billing_postcode,
		$billing_email,
		$billing_phone,
		$user,
		$_ipp_payment_url,
		$booking_status,
		$no_seat
	));
	$lastid = $wpdb->insert_id;
	$getdtSql = $wpdb->prepare("SELECT * from {$table_name} where id = %d", $lastid);
	$order = $wpdb->get_row($getdtSql);

	$adminEmail = get_option('admin_email');
	$subject = 'Booking Information';
	$body = 'Booking information<br>';
	$body .= 'Schedule: ' . $schedule . '<br>';

	$body .= 'Name: ' . $name . '<br>';
	$body .= 'Address: ' . $address . '<br>';
	$body .= 'Email: ' . $email . '<br>';
	$body .= 'Phone: ' . $phone . '<br>';
	$body .= 'Note: ' . $note . '<br>';

	$headers = array('Content-Type: text/html; charset=UTF-8');



	if ($total > 0 && $total != '') {
		include_once  'class-wc-gateway-ipp.php';
		$wcGatewayIpp = new WC_Gateway_IPP($_POST['url']);
		$gateway = $wcGatewayIpp->process_payment($lastid);
		$body .= 'Seats: ' . $no_seat . '<br>';
		$body .= 'Total: ' . $total . '<br>';
	} else {
		$gateway = array(
			"message" => "Booked your table successfully",
			"success" => true
		);
		$body .= 'Table: ' . $no_seat . '<br>';
		$body .= 'Status: Completed<br>';
	}
	//send_message(date('F j, Y, H:i', strtotime($schedule)), $phone);
	wp_mail(array($email, $adminEmail), $subject, $body, $headers);
	echo wp_send_json($gateway);
} else if ($task == "reports_filter") {
	$table_name = $wpdb->prefix . 'scwatbwsr_orders';
	$where = "WHERE p.productId > 0";
	$filter_type = filter_var($_POST["type"], FILTER_VALIDATE_INT);
	$result = [
		"online_revenue" => 0,
		"booked_table" => 0,
		"cancelled_table" => 0,
		"confirmed_table" => 0,
		"total_expenses" => 0,
		"total_revenue" => 0,
	];
	$startDate = filter_var($_POST["startDate"], FILTER_SANITIZE_STRING);
	$enddDate = filter_var($_POST["endDate"], FILTER_SANITIZE_STRING);

	$where .= " AND p.schedule >= '" . $startDate . "'";
	$where .= " AND p.schedule <= '" . $enddDate . "'";

	$result["total_revenue"] = $wpdb->get_var("select sum(total) from $table_name p $where");
	$result["booked_table"] = $wpdb->get_var("select count(id) from $table_name p $where");
	$result["cancelled_table"] = $wpdb->get_var("select count(id) from $table_name p $where AND booking_status='trash'");
	$result["confirmed_table"] = $wpdb->get_var("select count(id) from $table_name p $where AND (booking_status='confirmed' OR booking_status='closed')");
	$result["total_expenses"] = $wpdb->get_var("select sum(_ipp_tax) from $table_name p $where");
	$result["online_revenue"] = $wpdb->get_var("select sum(total) from $table_name p $where AND p.tran_id!='offline' AND p._ipp_status='Completed'");
	$result["online_revenue"] = ($result["online_revenue"] == 'null') ? $result["online_revenue"] : "0.00";
	echo wp_send_json($result);
} else if ($task == "revenue_filter") {
	$table_name = $wpdb->prefix . 'scwatbwsr_orders';
	$where = "WHERE p.productId > 0";
	$filter_type = filter_var($_POST["type"], FILTER_VALIDATE_INT);
	$result = [
		"online_revenue" => 0,
		"booked_table" => 0,
		"cancelled_table" => 0,
		"confirmed_table" => 0,
		"total_expenses" => 0,
		"total_revenue" => 0,
	];
	$startDate = filter_var($_POST["startDate"], FILTER_SANITIZE_STRING);
	$enddDate = filter_var($_POST["endDate"], FILTER_SANITIZE_STRING);

	$where .= " AND p.schedule >= '" . $startDate . "'";
	$where .= " AND p.schedule <= '" . $enddDate . "'";

	$result["total_revenue"] = $wpdb->get_results("select sum(total) as total ,MONTH(schedule) as month from $table_name p $where group by month", ARRAY_A);
	$result["booked_table"] = $wpdb->get_results("select count(id) as total ,MONTH(schedule) as month from $table_name p $where", ARRAY_A);
	$result["cancelled_table"] = $wpdb->get_results("select count(id) as total ,MONTH(schedule) as month from $table_name p $where AND booking_status='trash'", ARRAY_A);
	$result["confirmed_table"] = $wpdb->get_results("select count(id) as total ,MONTH(schedule) as month from $table_name p $where AND (booking_status='confirmed' OR booking_status='closed')", ARRAY_A);
	$result["total_expenses"] = $wpdb->get_results("select sum(total) as total ,MONTH(schedule) as month from $table_name p $where", ARRAY_A);
	$result["online_revenue"] = $wpdb->get_results("select sum(total) as total ,MONTH(schedule) as month from $table_name p $where AND p.tran_id!='offline' AND p._ipp_status='Completed'", ARRAY_A);


	$result["booked_table"] = array("label" => "Booked", "borderWidth" => 1, "data" => array_key_filter_count($result['booked_table']));
	$result['total_revenue'] = array("label" => "Revenue", "borderWidth" => 1, "data" => array_key_filter_count($result['total_revenue']));
	$result["cancelled_table"] = array("label" => "Cancelled", "borderWidth" => 1, "data" => array_key_filter_count($result['cancelled_table']));
	$result["confirmed_table"] = array("label" => "Confirmed", "borderWidth" => 1, "data" => array_key_filter_count($result['confirmed_table']));
	$result["total_expenses"] = array("label" => "Expenses", "borderWidth" => 1, "data" => array_key_filter_count($result['total_expenses']));
	$result["online_revenue"] = array("label" => "Online Revenue", "borderWidth" => 1, "data" => array_key_filter_count($result['online_revenue']));

	echo wp_send_json(array_values($result));
}

function array_key_filter_count($result)
{
	$output = [];
	$month_arr = [
		'1',
		'2',
		'3',
		'4',
		'5',
		'6',
		'7',
		'8',
		'9',
		'10',
		'11',
		'12'
	];

	foreach ($month_arr as $key => $val) {
		$datas = array_filter($result, function ($r) use ($val) {
			return ($r['month'] == $val);
		});
		$total_revenue = array_values($datas);
		if (count($total_revenue) == 0) {
			$output[$key] = array("total" => count($total_revenue), "month" => $key);
		} else {
			$output[$key] = array("total" => $datas[0]["total"] ? $datas[0]["total"] : 0, "month" => $key);
		}
	}
	$output = array_column($output, "total");
	return $output;
}
