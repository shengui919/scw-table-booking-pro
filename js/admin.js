
var upload_image_button = false;
function rangeyears(startYear) {
	var currentYear = new Date().getFullYear(), years = [];
	startYear = startYear || 2010;
	while (startYear <= currentYear) {
		years.push(startYear++);
	}
	return years;
}
function bookingChangePayment(booking_id,status)
{
	Swal.fire({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes!'
	  }).then((result) => {
		if (result.isConfirmed) {
            
			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					booking_id: booking_id,
					payment_status: status,
					task: "booking_change_payment"
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function (data) {


				},
				success: function (data) {
					swal.close();
					window.location.reload();
				},
				error: function (data) {
					swal.close();
					alert("Try again!")
				}
			});
		}
	});
}
function bookingChangeSchedule(booking_id,schedule)
{
	Swal.fire({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes!'
	  }).then((result) => {
		if (result.isConfirmed) {
	       

			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					booking_id: booking_id,
					schedule: schedule,
					task: "booking_change_schedule"
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function (data) {


				},
				success: function (data) {
					window.location.reload();
				},
				error: function (data) {
					swal.close();
					alert("Try again!")
				}
			});
		}
	});
}
function bookingChangeStatus(booking_id,booking_status)
{
	Swal.fire({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes!'
	  }).then((result) => {
		if (result.isConfirmed) {

			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					booking_id:booking_id,
					booking_status:booking_status,
					task: "booking_change_status"
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function (data) {


				},
				success: function (data) {
					window.location.reload();
				},
				error: function (data) {
					swal.close();
					alert("Try again!")
				}
			});
		}
	});
}
function newRoom() {
	htmlContent = '<div class="scwatbwsr_add">' +
		'<div class="scwatbwsr_add_head">Add a Room</div>' +
		'<input class="scwatbwsr_add_name" placeholder="Room Name" type="text">' +
		'<span class="scwatbwsr_add_button mt-3-right"><i class="fa fa-plus" aria-hidden="true"></i> ADD </span>' +
		'</div>';
	Swal.fire({
		title: '',
		html: htmlContent,
		showCloseButton: true,
		showCancelButton: false,
		focusConfirm: false,
		showConfirmButton: false
	});
	jQuery(".scwatbwsr_add_button").on("click", function () {
		var roomName = jQuery(".scwatbwsr_add_name").val();
		if (roomName) {
			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					roomName: roomName,
					task: "add_room"
				},
				type: 'POST',
				beforeSend: function (data) {
					jQuery(".scwatbwsr_add_button").append(' <i class="fa fa-refresh fa-spin scwspin"></i>');
				},
				success: function (data) {
					jQuery(".scwspin").remove();
					if (!data)
						location.reload();
					else
						alert(data);
				}
			});
		}
	});
}
function openCustomDate(htmlType = 1,redirect=0) {
	var htmlContent = '<div class="date-filters-swal">' +
		'<div class="rtb-admin-bookings-filters-start">' +
		'<label for="start-date" class="screen-reader-text">Start Date:</label>' +
		'<input type="text" id="start-date-swal" name="start_date_swal" class="datepickerswal" value="" placeholder="Start Date">' +
		'</div>' +
		'<div class="rtb-admin-bookings-filters-end">' +
		'<label for="end-date" class="screen-reader-text">End Date:</label>' +
		'<input type="text" id="end-date-swal" name="end_date_swal" class="datepickerswal" value="" placeholder="End Date">' +
		'</div>' +
		'<input type="submit" onClick="reportsFilter(1,'+redirect+')" class="button button-secondary" value="Apply">' +
		'</div>';
	if (htmlType == '2') {
		var dropdownHtml = '';
		var listYears = rangeyears();
		listYears.forEach(function (year, ind) {
			dropdownHtml += '<option value="' + year + '">' + year + '</option>';
		});
		htmlContent = '<div class="date-filters-swal">' +
			'<div class="rtb-admin-bookings-filters-start">' +
			'<label for="start-date" class="screen-reader-text">Select Year</label>' +
			'<select class="year_filter mb-3" id="filter_year"  style="width:200px;height:45px">';
		htmlContent += dropdownHtml;
		htmlContent += '</select></div>' +
			'<input type="submit" onClick="reportsFilter(2,'+redirect+')" class="button button-secondary" value="Apply">' +
			'</div>';
	}
	Swal.fire({
		title: '<strong>Select Date</strong>',
		html: htmlContent,
		showCloseButton: true,
		showCancelButton: false,
		focusConfirm: false,
		showConfirmButton: false
	});
	jQuery('#start-date-swal,#end-date-swal').datetimepicker({
		format: jQuery(".scw_date_format").val(),
		closeOnDateSelect: false,
		timepicker: false
	});
}
function sendEmailBooking(booking_id) {

	var booking = JSON.parse(jQuery("#hid_" + booking_id).val());
	var textarea_email = jQuery(".textarea_email").val();
	if (textarea_email.length < 5) {
		alert("Email message minmum 5 letters")
	}
	else {
		booking.textarea_email = textarea_email,
			booking.task = "custom_send_email"
		jQuery.ajax({
			url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
			data: booking,
			type: 'POST',
			dataType: 'JSON',
			beforeSend: function (data) {
				swal.close();

			},
			success: function (data) {

				swal.close();
				Swal.fire(
					'Email Sent!',
					'Email sent successfully!',
					'success'
				)


			},
			error: function (data) {

				alert('Try again!')
			}
		});
	}
}
function reportsFilterYear(year) {
	var startDate = '';
	var endDate = '';
	var filterText = '';
	jQuery(".weeks-option__item_year").removeClass("active")
	jQuery(".weeks-option__item_year.year_" + year).addClass("active")
	startDate = year + "-01-01";
	endDate = year + "-12-31";
	if (startDate != '') startDate = startDate + " 00:00:00";
	if (endDate != '') endDate = endDate + " 23:59:59";
	Swal.fire('Loading.....')

	jQuery.ajax({
		url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
		data: {
			startDate: startDate,
			endDate: endDate,
			year: year,
			type: 'year',
			task: "revenue_filter"
		},
		type: 'POST',
		dataType: 'JSON',
		beforeSend: function (data) {
			swal.close();

		},
		success: function (data) {

			drawChart(data)

		},
		error: function (data) {


		}
	});

}
function openDate(ID)
{
	var startTime =jQuery(".slider-time").text().trim();
	var endTime =jQuery(".slider-time2").text().trim();
	window.location.href="admin.php?page=scwatbwsr-table-bookings&type=live&selectedDate="+jQuery("#selectedDate"+ID).val()+"&startTime="+startTime+"&endTime="+endTime;
}

function reportsFilter(type = 0,redirect=0) {
	var startDate = '';
	var endDate = '';
	var year = '';
	var filterText = '';
	jQuery(".weeks-option__item").removeClass("active")
	jQuery(".weeks-option__item." + type).addClass("active")
	if (type == "1") {

		startDate = jQuery("#start-date-swal").val();
		endDate = jQuery("#end-date-swal").val();

	}
	else if (type == "2") {
		year = jQuery("#filter_year").val();
		startDate = year + "-01-01";
		endDate = year + "-12-31";
		filterText = "Year " + year
	}
	else if (type == "last_week") {
		startDate = moment().subtract(1, 'weeks').startOf('week').format('YYYY-MM-DD');
		endDate = moment().subtract(1, 'weeks').endOf('week').format('YYYY-MM-DD');
	}
	else if (type == "week") {
		startDate = moment().startOf('week').format('YYYY-MM-DD');
		endDate = moment().endOf('week').format('YYYY-MM-DD');
	}
	else if (type == "yesterday") {
		startDate = moment().subtract(1, 'day').format('YYYY-MM-DD');
		endDate = moment().subtract(1, 'day').format('YYYY-MM-DD');
	}
	else if (type == "today") {
		startDate = moment().format('YYYY-MM-DD');
		endDate = moment().format('YYYY-MM-DD');
	}
	else if (type == "month") {
		startDate = moment().startOf('month').format('YYYY-MM-DD');
		endDate = moment().endOf('month').format('YYYY-MM-DD');
	}
	else if (type == "last_month") {
		startDate = moment().subtract(1, 'months').startOf('month').format('YYYY-MM-DD');
		endDate = moment().subtract(1, 'months').endOf('month').format('YYYY-MM-DD');
	}
	filterText = startDate + " - " + endDate
	jQuery("#filter_label").text(filterText)
	if (startDate != '') startDate = startDate + " 00:00:00";
	if (endDate != '') endDate = endDate + " 23:59:59";
	if(redirect==1)
	{
		window.location.href="admin.php?page=scwatbwsr-table-bookings&type=live&startDate="+startDate+"&endDate="+endDate
	}
	else 
	{
	Swal.fire('Loading.....')

	jQuery.ajax({
		url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
		data: {
			startDate: startDate,
			endDate: endDate,
			year: year,
			type: type,
			task: "reports_filter"
		},
		type: 'POST',
		dataType: 'JSON',
		beforeSend: function (data) {
			swal.close();
			jQuery(".cards.reports-filter .card").css("display", "none");
		},
		success: function (data) {
			jQuery(".cards.reports-filter .card").css("display", "block");

			jQuery(".card__title.online_revenue").text("$" + data.online_revenue)
			jQuery(".card__title.booked_table").text(data.booked_table)
			jQuery(".card__title.cancelled_table").text(data.cancelled_table)
			jQuery(".card__title.confirmed_table").text(data.confirmed_table)
			jQuery(".card__title.total_expenses").text("$" + data.total_expenses)
			jQuery(".card__title.total_revenue").text("$" + data.total_revenue)
		},
		error: function (data) {
			jQuery(".cards.reports-filter .card").css("display", "block");
			jQuery(".card__title.online_revenue").text("$0.00")
			jQuery(".card__title.booked_table").text('0')
			jQuery(".card__title.cancelled_table").text('0')
			jQuery(".card__title.confirmed_table").text('0')
			jQuery(".card__title.total_expenses").text("$0.00")
			jQuery(".card__title.total_revenue").text("$0.00")
		}
	});
}
}
function totalCounts() {
	var tableCount = jQuery(".name-table.active").length;
	var seatCount = jQuery(".chart1.active").length;
	jQuery("#total-table").text(tableCount);
	jQuery("#total-seats").text(seatCount);
	var tableName = [];
	for (var i = 0; i < tableCount; i++) {
		tableName.push(jQuery(".name-table.active:eq('" + i + "')").data("name"));
	}

	jQuery("#total-table-list").text(tableName.toString())

}
function sendMail(booking_id) {
	var booking = JSON.parse(jQuery("#hid_" + booking_id).val());

	if (booking && booking.id) {
		if (new Date(booking.schedule) < new Date()) {
			Swal.fire(
				'Error!',
				'Booking is closed!',
				'error'
			)
		}
		else {
			var htmlContent = '<table>' +
				'<tr><td>Name</td><td>' + booking.name + '</td>' +
				'<tr><td>Date</td><td>' + booking.schedule + '</td>' +
				'<tr><td>Email</td><td>' + booking.email + '</td>' +
				'<tr><td>Phone</td><td>' + booking.phone + '</td>' +
				'<tr><td>Seats</td><td>' + booking.seats + '</td>' +
				'<tr><td>No Seats</td><td>' + booking.no_seats + '</td>' +
				'<tr><td>Notes</td><td>' + booking.note + '</td>';
			if (booking.total > 0) {
				if (booking.tran_id != '' || booking.trand_id == 'offline') {
					htmlContent += '<tr><td>Payment</td><td>Offline</td>';
				}
				else {
					htmlContent += '<tr><td>Payment</td><td>Online</td>';
					htmlContent += '<tr><td>Transaction ID</td><td>' + booking._ipp_transaction_id + '</td>';
					htmlContent += '<tr><td>Payment Status</td><td>' + booking._ipp_status + '</td>';
					htmlContent += '<tr><td>Tax</td><td>' + booking._ipp_tax + '</td>';
				}
				htmlContent += '<tr><td>Price</td><td>' + booking.total + '</td>';
			}
			else {
				htmlContent += '<tr><td>Payment</td><td>Free Booking</td>';
			}
			htmlContent += '<tr><td>Booking Status</td><td>' + booking.booking_status + '</td>' +
				'<tr><td>Order ID</td><td>' + booking.orderId + '</td>' +

				'<tr><td>Message</td><td><textarea class="textarea_email"></textarea></td>' +
				'<tr><td colspan="2"><button type="button" onClick="sendEmailBooking(' + booking_id + ')" class="btn--primary">Send</button></td>' +
				'</table>';
			var bDate = new Date(booking.schedule)
			Swal.fire({
				title: bDate.toLocaleString('default', { month: 'long' }) + ', ' + bDate.toLocaleDateString("en-Latn-US", { weekday: 'long' }) + ' ' + bDate.getDate(),
				html: htmlContent,
				showCloseButton: true,
				showCancelButton: false,
				focusConfirm: false,
				showConfirmButton: false
			});
		}
	}
	else {
		Swal.fire(
			'Error!',
			'Booking is invalid!',
			'error'
		)
	}
}
function editBooking(booking_id) {
	window.location.href = "admin.php?page=scwatbwsr-table-bookings&type=view&booking_id=" + booking_id;
}
function fetchTime(dateTime, elthis) {
	const d = new Date(dateTime);
	var hoursTime = "0" + d.getHours();
	var minuTime = "0" + d.getMinutes();
	var time = hoursTime.slice(-2) + ":" + minuTime.slice(-2);
	elthis.find('.start_time_hidden').val(time)
	var endTime = elthis.find('.scwatbwsr_schedules_spec_end_time_input').datetimepicker({
		format: 'H:i',
		datepicker: false,
		step: parseInt(jQuery(".scwatbwsr_bktime_ip").val() ? jQuery(".scwatbwsr_bktime_ip").val() : "30"),
		minTime: time
	});
}
function fetchTimeList(dateTime, elthis, i) {

	const d = new Date(dateTime);
	var hoursTime = "0" + d.getHours();
	var minuTime = "0" + d.getMinutes();
	var time = hoursTime.slice(-2) + ":" + minuTime.slice(-2);

	jQuery('.scwatbwsr_schedules_spec_list_item:eq(' + i + ')').find('.start_time_hidden_list').val(time)
	var endTime = jQuery('.scwatbwsr_schedules_spec_list_item:eq(' + i + ')').find('.scwatbwsr_schedules_spec_list_item_schedule_end').datetimepicker({
		format: 'H:i',
		datepicker: false,
		step: parseInt(jQuery(".scwatbwsr_bktime_ip").val() ? jQuery(".scwatbwsr_bktime_ip").val() : "30"),
		minTime: time
	});
}
function findMintesCount(element)
{
	if(jQuery(element).length>0)
	 {
		var times  =jQuery(element).text().trim();
		var timesAmArr = times.split(" ");
		var timeAm =  timesAmArr[1];
		var timesArr = timesAmArr[0].trim().split(":");

		var hoursNum = parseInt(timesArr[0]);
		
		var minsNum = parseInt(timesArr[1]);
		if(timeAm=="AM" && hoursNum==12)
		return (10+minsNum);
		else 
		{
		if(timeAm=="PM")
		hoursNum = hoursNum+12;
		return (hoursNum*60) + minsNum ;
		}
	 }
	 else 
	 return 540;
}
function showStatusDiv(status)
{
	jQuery(".maintopsec").addClass("hide");
	jQuery(".maintopsec").each(function(i,e)
	{
        if(status=="all")
		{
			jQuery(this).removeClass("hide")
		}
		else if(status=='waiting')
		{
			if(jQuery(this).hasClass("booking-pending"))
			jQuery(this).removeClass("hide");
			if(jQuery(this).hasClass("booking-closed"))
			jQuery(this).addClass("hide");
		}
		else if(status=='reservation')
		{
			
			if(!jQuery(this).hasClass("booking-pending"))
			jQuery(this).removeClass("hide");
			if(jQuery(this).hasClass("booking-closed"))
			jQuery(this).addClass("hide");
		}
	})
}
function setBookingIdforTable(bookingId)
{
	var table_id=jQuery("body").data("seats_id");
	var roomid=jQuery("body").data("room_id");
	booking_update(bookingId,{roomid:roomid,seats:table_id,booking_status:"Confirmed"})
}
function mysecondtab(table_id,roomid)
{
	var booking_id=jQuery("body").data("booking_id");
	booking_update(booking_id,{roomid:roomid,seats:table_id,booking_status:"Confirmed"})
}
function booking_update(booking_id,booking_update)
{
	swal.close();
	Swal.fire('Loading.....')

			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					booking_id: booking_id,
					booking_update: booking_update,
					task: "booking_update"
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function (data) {


				},
				success: function (data) {
					swal.close();
					alert("Succsully updated")
					window.location.reload();
				},
				error: function (data) {
					swal.close();
					alert("Try again!")
				}
			});
}
(function (jQuery) {
	"use strict";
	
	Swal.bindClickHandler();
     var values=[540,1020];
	 var minStart = findMintesCount(".slider-time");
	 var minEnd = findMintesCount(".slider-time2");
	
	 if(minStart >0  && minEnd >0)
	 values=[minStart,minEnd];
    jQuery("#slider-range").slider({
		range: true,
		min: 0,
		max: 1440,
		step: 45,
		values: values,
		stop:function(event, ui) {
			openDate('Current')
		},
		slide: function (e, ui) {
			var hours1 = Math.floor(ui.values[0] / 60);
			var minutes1 = ui.values[0] - (hours1 * 60);
	
			if (hours1.length == 1) hours1 = '0' + hours1;
			if (minutes1.length == 1) minutes1 = '0' + minutes1;
			if (minutes1 == 0) minutes1 = '00';
			if (hours1 >= 12) {
				if (hours1 == 12) {
					hours1 = hours1;
					minutes1 = minutes1 + " PM";
				} else {
					hours1 = hours1 - 12;
					minutes1 = minutes1 + " PM";
				}
			} else {
				hours1 = hours1;
				minutes1 = minutes1 + " AM";
			}
			if (hours1 == 0) {
				hours1 = 12;
				minutes1 = minutes1;
			}
	
	
	
			jQuery('.slider-time').html(hours1 + ':' + minutes1);
			
			var hours2 = Math.floor(ui.values[1] / 60);
			var minutes2 = ui.values[1] - (hours2 * 60);
	
			if (hours2.length == 1) hours2 = '0' + hours2;
			if (minutes2.length == 1) minutes2 = '0' + minutes2;
			if (minutes2 == 0) minutes2 = '00';
			if (hours2 >= 12) {
				if (hours2 == 12) {
					hours2 = hours2;
					minutes2 = minutes2 + " PM";
				} else if (hours2 == 24) {
					hours2 = 11;
					minutes2 = "59 PM";
				} else {
					hours2 = hours2 - 12;
					minutes2 = minutes2 + " PM";
				}
			} else {
				hours2 = hours2;
				minutes2 = minutes2 + " AM";
			}
	
			jQuery('.slider-time2').html(hours2 + ':' + minutes2);
			
		}
	});
	jQuery(".sec12-open").click(function(){
		var bookingId=jQuery(this).data("id")

	    jQuery("body").data("booking_id",bookingId)
		Swal.fire({
			template: '#my-template'
		  });
	})
	jQuery(".live-open").click(function(){
		var tables = jQuery(this).attr("id")
		var table=tables.split(":");
		var table_id=table[0];
		var roomid=table[1];
		jQuery("body").data("seats_id",table_id)
		jQuery("body").data("room_id",roomid)
		Swal.fire({
			template: '#booking-template'
		});
		 
	})
	
	jQuery(".reservationtext,.waitingtext,.alltext").click(function(){
		showStatusDiv(jQuery(this).data("status"))
	})
	jQuery(".maintopsec.booking-pending").draggable({ helper: "clone",revert: "invalid" });
	jQuery(".tablew4.live-open").droppable({
		accept: ".maintopsec.booking-pending",
		activeClass:true,
		classes: {
		  "ui-droppable-active": "ui-state-active",
		  "ui-droppable-hover": "ui-state-hover"
		},
		drop: function( event, ui ) {
		    if(ui && ui.draggable && ui.draggable.length>0)
			{
		       var booking_id = ui.draggable[0].id;
			   var tables = jQuery(this)[0].id;
			   var table=tables.split(":");
			   var table_id=table[0];
			   var roomid=table[1];
			   booking_update(booking_id,{roomid:roomid,seats:table_id,booking_status:"Confirmed"})
			   window.location.reload();
			}
		   
		}
	  
	});
	jQuery("#myInput").on("keyup", function() {
		var value = jQuery(this).val().toLowerCase();
		jQuery(".filterrow .leftsecont").filter(function() {
			jQuery(this).parent().toggle(jQuery(this).text().toLowerCase().indexOf(value) > -1)
		});
	  });
	//room table drag
	
	jQuery(".leaderboard__name").each(function () {
		var thistbmap = jQuery(this);
		thistbmap.draggable({
			containment: "parent",
			scroll: true,
			drag: function () {
				
			},
			start: function () {
				
			},
			stop: function ($event) {
				
			   var room_id=jQuery($event.target).data("id");
			   var sleft = jQuery($event.target).position().left;
			   var stop = jQuery($event.target).position().top;
			   jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					roomId: room_id,
					rleft: sleft,
					rtop: stop,
					task: "update_room_position"
				},
				type: 'POST',
				
				
			   });
			}
		});

		
	});
	// room drag start
	jQuery(".tablesize-drag").each(function () {
		var thistbmap = jQuery(this);
		thistbmap.draggable({
			containment: "parent",
			scroll: true,
			drag: function () {
				
			},
			start: function () {
				
			},
			stop: function ($event) {
				
			   var room_id=jQuery($event.target).data("id");
			   var sleft = jQuery($event.target).position().left;
			   var stop = jQuery($event.target).position().top;
			   jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					roomId: room_id,
					rleft: sleft,
					rtop: stop,
					task: "update_room_position"
				},
				type: 'POST',
				
				
			   });
			}
		});

		
	});
	// room drag
	jQuery("#booking_view_change_status_button").click(function () {
		var schedule = jQuery("#booking_data_schedule").text().trim();
		if (new Date(schedule) < new Date()) {
			Swal.fire(
				'Error!',
				'Booking is closed!',
				'error'
			)
		}
		else {

			bookingChangeStatus(jQuery("#booking_view_booking_id").val(),
jQuery("#booking_view_change_status_button_select").val());
		}
	})


	jQuery("#booking_view_change_schedule").click(function () {
		var schedule = jQuery("#booking_data_schedule").text().trim();
		if (new Date(schedule) < new Date()) {
			Swal.fire(
				'Error!',
				'Booking is closed!',
				'error'
			)
		}
		else {
			bookingChangeSchedule(jQuery("#booking_view_booking_id").val(),jQuery("#alt_example_4_alt").val())
			
		}
	})

	jQuery("#booking_view_payment_status_change").click(function () {
		var schedule = jQuery("#booking_data_schedule").text().trim();
		if (new Date(schedule) < new Date()) {
			Swal.fire(
				'Error!',
				'Booking is closed!',
				'error'
			)
		}
		else {

			bookingChangeSchedule(jQuery("#booking_view_booking_id").val(),jQuery("#booking_view_payment_status_select").val());

			
		}
	})
	jQuery('.name-table.table').click(function () {
		var element = jQuery(this);
		element.toggleClass('active');
		var seatEle = element.parent(".table-list").find(".chart1");
		seatEle.each(function (index) {
			jQuery(this).toggleClass("active")
		})
		totalCounts()
	});
	jQuery('.chart1.seat').click(function () {
		var element = jQuery(this);
		element.toggleClass('active')
		totalCounts()
	});
	jQuery('.name-table.seat').click(function () {
		alert("You can't select full table")
	});
	jQuery('.chart1.table').click(function () {
		alert("You can't select single seat")
	});

	jQuery('#rtb-date-filter-link').click(function () {
		jQuery('#rtb-filters').toggleClass('date-filters-visible');
	});
	// Name filter helper
	jQuery(document)
		.on('click', '#rtb-filters .filter_name a', function (ev) {
			ev.preventDefault();
			filterByName();
		})
		.on('keydown', '#rtb-filters .filter_name input', function (ev) {
			if (event.keyCode == 13) {
				event.preventDefault();
				filterByName();
				return false;
			}
		});
	reportsFilter('month');
	function filterByName() {
		let text = jQuery('#rtb-filters .filter_name input').val();
		let href = jQuery('#rtb-filters .filter_name a').prop('href');
		href += '=' + encodeURIComponent(text);
		href += '&date_range=all';
		window.location = href;
	}
	jQuery(document).on('click', '.date-filters input[type="submit"]', function (event) {
		event.preventDefault();

		let args = [];
		let url = new URL(window.location.href);

		jQuery('.date-filters input[type="text"]').each((i, x) => {
			'' === jQuery(x).val() ? null : args.push([jQuery(x).prop('name'), jQuery(x).val()]);
		});

		args.push(['page', url.searchParams.get('page')]);
		args = new URLSearchParams(args);

		window.location = `${url.origin}${url.pathname}?${args.toString()}`;
	});
	jQuery('.rtb-admin-bookings-filters-start #start-date,#end-date').datetimepicker({
		format: jQuery(".scw_date_format").val() + ' H:i',
		closeOnDateSelect: false,
		step: 5,
		defaultTime: "00:00"
	});

	jQuery('#alt_example_4_alt').datetimepicker({
		format: jQuery(".scw_date_format").val() + ' H:i',
		closeOnDateSelect: false,
		step: 5,
		defaultTime: "00:00"
	});

	jQuery('.scwatbwsr_media_upload').on("click", function () {
		upload_image_button = true;
		var formfieldID = jQuery(this).prev('input');

		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		if (upload_image_button == true) {
			var oldFunc = window.send_to_editor;
			window.send_to_editor = function (html) {
				var imgurl = jQuery('img', html).attr('src');
				jQuery(formfieldID).val(imgurl);
				tb_remove();
				window.send_to_editor = oldFunc;
			}
		}
		upload_image_button = false;
	});

	jQuery(".scwatbwsr_add_button").on("click", function () {
		var roomName = jQuery(".scwatbwsr_add_name").val();
		if (roomName) {
			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					roomName: roomName,
					task: "add_room"
				},
				type: 'POST',
				beforeSend: function (data) {
					jQuery(".scwatbwsr_add_button").append(' <i class="fa fa-refresh fa-spin scwspin"></i>');
				},
				success: function (data) {
					jQuery(".scwspin").remove();
					if (!data)
						location.reload();
					else
						alert(data);
				}
			});
		}
	});

	jQuery(".scwatbwsr_add_page_button").on("click", function () {
		var pageName = jQuery(".scwatbwsr_add_page_name").val();
		if (pageName) {
			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					reservations_page_title: pageName,
					task: "add_page"
				},
				type: 'POST',
				beforeSend: function (data) {
					jQuery(".scwatbwsr_add_page_button").append(' <i class="fa fa-refresh fa-spin scwspin"></i>');
				},
				success: function (data) {
					jQuery(".scwspin").remove();
					if (!data)
						location.reload();
					else
						alert(data);
				}
			});
		}
	});

	///////
	jQuery(".scwatbwsr_room").each(function (lotkey, lotval) {
		var elthis = jQuery(this);
		var roomId = jQuery(".scwatbwsr_room_id").val();

		elthis.children(".scwatbwsr_room_head,.scwatbwsr_room_head_name").on("click", function () {

			if (elthis.children(".scwatbwsr_room_content").is(":visible")) {
				elthis.children(".scwatbwsr_room_content").slideUp();
				setCookie("status" + lotkey, "close", 1);
				jQuery(this).find("i.fadown").removeClass("fa-angle-double-down");
				jQuery(this).find("i.fadown").addClass("fa-angle-double-right");
			} else {
				elthis.children(".scwatbwsr_room_content").slideDown();
				setCookie("status" + lotkey, "open", 1);
				jQuery(this).find("i.fadown").removeClass("fa-angle-double-right");
				jQuery(this).find("i.fadown").addClass("fa-angle-double-down");
			}
			// var selectRoom=jQuery(this).data("id");
			// if(jQuery("."+selectRoom+".scwatbwsr_room_content .scwatbwsr_room_content_tabs_label.active").length == 0)
			// {

			// 	jQuery("."+selectRoom+".scwatbwsr_room_content .scwatbwsr_room_content_tabs_input.first").trigger("click")
			// }

		});
		var checkStatus = getCookie("status" + lotkey);
		// jQuery(".scwatbwsr_room_content").slideUp();
		// jQuery(".scwatbwsr_room_head").children("i.fadown").addClass("fa-angle-double-right");
		// jQuery(".scwatbwsr_room_head").children("i.fadown").removeClass("fa-angle-double-down");
		if (checkStatus == "open") {
			elthis.children(".scwatbwsr_room_content").slideDown()
			elthis.children(".scwatbwsr_room_head").children("i.fadown").removeClass("fa-angle-double-right");
			elthis.children(".scwatbwsr_room_head").children("i.fadown").addClass("fa-angle-double-down");
		}


		//////////
		elthis.find(".scwatbwsr_room_content_tabs_input").each(function (key, val) {
			var thistab = jQuery(this);

			thistab.on("click", function () {
				elthis.find(".scwatbwsr_room_content_tabs_label").removeClass("active");
				elthis.find(".scwatbwsr_room_content_tabs_label:eq(" + key + ")").addClass("active");

				elthis.find(".tab-content").removeClass("active");
				elthis.find(".tab-content:eq(" + key + ")").addClass("active");
			});
		});

		/////////
		elthis.find(".scwatbwsr_basesetting_save").on("click", function () {
			roomId  = elthis.find(".scwatbwsr_room_id").val()
			var width = elthis.find(".scwatbwsr_roomsize_width").val();
			var height = elthis.find(".scwatbwsr_roomsize_height").val();
			var color = elthis.find(".scwatbwsr_roombg_con_color").val();
			var bg = elthis.find(".scwatbwsr_roombg_con_image").val();
			var newRoomname = elthis.find(".scwatbwsr_room_content_editname_name").val();
			var tbbookedcolor = elthis.find(".scwatbwsr_bookedpr_tbcolor").val();
			var seatbookedcolor = elthis.find(".scwatbwsr_bookedpr_seatcolor").val();
			var bktime = elthis.find(".scwatbwsr_bktime_ip").val();
			var compulsory = elthis.find(".scwatbwsr_compulsory_ip").is(":checked") ? "yes" : "no";
			var zoom = elthis.find(".scwatbwsr_zoom_ip").is(":checked") ? "1" : "0";

			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					roomId: elthis.find(".scwatbwsr_room_id").val(),
					width: width,
					height: height,
					color: color,
					bg: bg,
					newRoomname: newRoomname,
					tbbookedcolor: tbbookedcolor,
					seatbookedcolor: seatbookedcolor,
					bktime: bktime,
					compulsory: compulsory,
					zoom: zoom,
					task: "save_base_setting"
				},
				type: 'POST',
				beforeSend: function (data) {
					elthis.find(".scwatbwsr_basesetting_save").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
				},
				success: function (data) {
					jQuery(".scw_spin").remove();
					if (!data) {
						alert("Saved!");
					} else
						alert("Error!");
				}
			});
		});

		////////
		elthis.find("input[name='scwatbwsr_roomtype_add_tbshape']").on("change", function () {
			elthis.find(".scwatbwsr_roomtype_add_tbshape_rec_width").val("");
			elthis.find(".scwatbwsr_roomtype_add_tbshape_rec_height").val("");
			elthis.find(".scwatbwsr_roomtype_add_tbshape_cir_width").val("");
		});
		elthis.find("input[name='scwatbwsr_roomtype_add_seatshape']").on("change", function () {
			elthis.find(".scwatbwsr_roomtype_add_seatshape_rec_width").val("");
			elthis.find(".scwatbwsr_roomtype_add_seatshape_rec_height").val("");
			elthis.find(".scwatbwsr_roomtype_add_seatshape_cir_width").val("");
		});
		elthis.find(".scwatbwsr_roomtype_add_button").on("click", function () {
			var typename = elthis.find(".scwatbwsr_roomtype_add_name").val();
			var tbbg = elthis.find(".scwatbwsr_roomtype_add_tbcolor_input").val();
			var tbshape = elthis.find("input[name='scwatbwsr_roomtype_add_tbshape']:checked").val();
			var tbrecwidth = elthis.find(".scwatbwsr_roomtype_add_tbshape_rec_width").val();
			var tbrecheight = elthis.find(".scwatbwsr_roomtype_add_tbshape_rec_height").val();
			var tbcirwidth = elthis.find(".scwatbwsr_roomtype_add_tbshape_cir_width").val();
			var seatbg = elthis.find(".scwatbwsr_roomtype_add_seatcolor_input").val();
			var seatshape = elthis.find("input[name='scwatbwsr_roomtype_add_seatshape']:checked").val();
			var seatwidth = elthis.find(".scwatbwsr_roomtype_add_seat_size").val();

			if (typename && tbshape && seatshape) {
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId: elthis.find(".scwatbwsr_room_id").val(),
						typename: typename,
						tbbg: tbbg,
						tbshape: tbshape,
						tbrecwidth: tbrecwidth,
						tbrecheight: tbrecheight,
						tbcirwidth: tbcirwidth,
						seatbg: seatbg,
						seatshape: seatshape,
						seatwidth: seatwidth,
						task: "add_type"
					},
					type: 'POST',
					beforeSend: function (data) {
						elthis.find(".scwatbwsr_roomtype_add_button").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function (data) {
						jQuery(".scw_spin").remove();
						if (!data) {
							alert("Added!");
						} else
							alert(data);
					}
				});
			} else {
				alert("Please enter information!");
			}
		});
		elthis.find(".scwatbwsr_roomtype_add_reload").on("click", function () {
			location.reload();
		});

		////////
		elthis.find(".scwatbwsr_roomtype_item").each(function () {
			var thistype = jQuery(this);

			thistype.children(".scwatbwsr_roomtype_item_save").on("click", function () {
				var thistypeid = thistype.children(".scwatbwsr_roomtype_item_id").val();
				var thistbcolor = thistype.find(".scwatbwsr_roomtype_item_tbbg_input").val();
				var thistbrecwidth = thistype.find(".scwatbwsr_roomtype_item_tbsize_recwidth").val();
				var thistbrecheight = thistype.find(".scwatbwsr_roomtype_item_tbsize_recheight").val();
				var thistbcirwidth = thistype.find(".scwatbwsr_roomtype_item_tbsize_cirwidth").val();
				var thisseatcolor = thistype.find(".scwatbwsr_roomtype_item_seatbg_input").val();
				var seatwidth = thistype.find(".scwatbwsr_roomtype_item_seatsize_width").val();

				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						thistypeid: thistypeid,
						thistbcolor: thistbcolor,
						thistbrecwidth: thistbrecwidth,
						thistbrecheight: thistbrecheight,
						thistbcirwidth: thistbcirwidth,
						thisseatcolor: thisseatcolor,
						seatwidth: seatwidth,
						task: "save_type"
					},
					type: 'POST',
					beforeSend: function (data) {
						thistype.children(".scwatbwsr_roomtype_item_save").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function (data) {
						jQuery(".scw_spin").remove();
						if (!data) {
							alert("Saved!");
						} else
							alert("Error!");
					}
				});
			});
			thistype.children(".scwatbwsr_roomtype_item_del").on("click", function () {
				var r = confirm("This type will be delete, are you sure?");
				if (r == true) {
					var thistypeid = thistype.children(".scwatbwsr_roomtype_item_id").val();
					jQuery.ajax({
						url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
						data: {
							thistypeid: thistypeid,
							task: "delete_type"
						},
						type: 'POST',
						beforeSend: function (data) {
							thistype.children(".scwatbwsr_roomtype_item_del").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
						},
						success: function (data) {
							jQuery(".scw_spin").remove();
							if (!data)
								thistype.remove();
							else
								alert(data);
						}
					});
				} else {
					return false;
				}
			});
		});

		/////////// 


		elthis.find('.scwatbwsr_schedules_spec_add_input').datetimepicker({
			format: jQuery(".scw_date_format").val() + ' H:i',
			minDate: 0,
			closeOnDateSelect: false,
			step: parseInt(jQuery(".scwatbwsr_bktime_ip").val() ? jQuery(".scwatbwsr_bktime_ip").val() : "30"),
			onSelectDate: function (ct, $i) {

				fetchTime(ct, elthis);

			},
			onSelectTime: function (ct, $i) {
				fetchTime(ct, elthis);
			}
		});
		//:parseInt(jQuery(".scwatbwsr_schedules_spec_add_input").val()?jQuery(".scwatbwsr_schedules_spec_add_input").val().slice(-5):"15:00"),

		elthis.find(".scwatbwsr_schedules_spec_button").on("click", function () {
			var schedule = elthis.find(".scwatbwsr_schedules_spec_add_input").val();
			var endTime = elthis.find('.scwatbwsr_schedules_spec_end_time_input').val();
			var startTime = elthis.find('.start_time_hidden').val();
			if (startTime == '') {
				alert('Select start time')
			}
			else if (endTime == '') {
				alert('Select end time')
			}
			else if (endTime == startTime) {
				alert('Start and end time should be different!')
			}

			else {
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId: elthis.find(".scwatbwsr_room_id").val(),
						schedule: schedule,
						endTime: endTime,
						startTime: startTime,
						task: "add_schedule"
					},
					type: 'POST',
					beforeSend: function (data) {
						elthis.find(".scwatbwsr_schedules_spec_button").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function (data) {
						jQuery(".scw_spin").remove();
						if (!data) {
							alert("Added!");
							jQuery(".scwatbwsr_schedules_spec_add_reload").trigger("click")
						}
						else
							alert(data);
					}
				});
			}
		});
		elthis.find(".scwatbwsr_schedules_spec_add_reload").on("click", function () {
			location.reload();
		});

		////////
		elthis.find(".scwatbwsr_schedules_spec_list_item").each(function (key, i) {
			var thische = jQuery(this);
			thische.children('.scwatbwsr_schedules_spec_list_item_schedule_end').datetimepicker({
				format: 'H:i',
				datepicker: false,
				step: parseInt(jQuery(".scwatbwsr_bktime_ip").val() ? jQuery(".scwatbwsr_bktime_ip").val() : "30"),
				minTime: thische.children('.start_time_hidden_list').val(),
			});

			thische.children('.scwatbwsr_schedules_spec_list_item_schedule_start').datetimepicker({
				format: jQuery(".scw_date_format").val() + ' H:i',
				closeOnDateSelect: false,
				minDate: 0,
				step: parseInt(jQuery(".scwatbwsr_bktime_ip").val() ? jQuery(".scwatbwsr_bktime_ip").val() : "30"),
				defaultTime: "00:00",
				onSelectDate: function (ct, $i) {

					fetchTimeList(ct, thische, key);

				},
				onSelectTime: function (ct, $i) {
					fetchTimeList(ct, thische, key);
				}
			});
			thische.children(".scwatbwsr_schedules_spec_list_item_save").on("click", function () {
				var scheid = thische.children(".scwatbwsr_schedules_spec_list_item_id").val();
				var thisschedule = thische.children(".scwatbwsr_schedules_spec_list_item_schedule").val();
				var startTime = thische.children('.start_time_hidden_list').val();
				var endTime = thische.children('.scwatbwsr_schedules_spec_list_item_schedule_end').val();
				if (startTime == '') {
					alert('Select start time')
				}
				else if (endTime == '') {
					alert('Select end time')
				}
				else if (endTime == startTime) {
					alert('Start and end time should be different!')
				}

				else {
					jQuery.ajax({
						url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
						data: {
							scheid: scheid,
							thisschedule: thisschedule,
							startTime: startTime,
							endTime: endTime,
							task: "save_schedule"
						},
						type: 'POST',
						beforeSend: function (data) {
							thische.children(".scwatbwsr_schedules_spec_list_item_save").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
						},
						success: function (data) {
							jQuery(".scw_spin").remove();
							if (!data) {
								alert("Saved!");
								jQuery(".scwatbwsr_schedules_spec_add_reload").trigger("click");
							}
							else
								alert("Error!");
						}
					});
				}
			});
			///
			thische.children(".scwatbwsr_schedules_spec_list_item_delete").on("click", function () {
				var r = confirm("This schedule will be delete, are you sure?");
				if (r == true) {
					var scheid = thische.children(".scwatbwsr_schedules_spec_list_item_id").val();
					jQuery.ajax({
						url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
						data: {
							scheid: scheid,
							task: "delete_schedule"
						},
						type: 'POST',
						beforeSend: function (data) {
							thische.children(".scwatbwsr_schedules_spec_list_item_delete").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
						},
						success: function (data) {
							jQuery(".scw_spin").remove();
							if (!data)

								thische.remove();
							else
								alert("Error!");
						}
					});
				} else {
					return false;
				}
			});
		});

		///////////
		elthis.find(".scwatbwsr_daily_schedules_week").each(function () {
			var thisweek = jQuery(this);
			thisweek.children("input").change(function () {
				var dailys = elthis.find(".scwatbwsr_daily_schedules_week > input:checked").map(function () {
					return jQuery(this).val();
				}).get();

				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId:  elthis.find(".scwatbwsr_room_id").val(),
						dailys: dailys,
						task: "change_daily"
					},
					type: 'POST',
					beforeSend: function (data) {
						thisweek.append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function (data) {
						jQuery(".scw_spin").remove();
					}
				});
			});
		});

		//////////
		elthis.find(".scwatbwsr_daily_schedules_times_add_input").datetimepicker({
			datepicker: false,
			step: 5,
			format: "H:i"
		});
		elthis.find(".scwatbwsr_daily_schedules_times_add_button").on("click", function () {
			var scheduletime = elthis.find(".scwatbwsr_daily_schedules_times_add_input").val();

			if (scheduletime) {
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId:  elthis.find(".scwatbwsr_room_id").val(),
						scheduletime: scheduletime,
						task: "add_time"
					},
					type: 'POST',
					beforeSend: function (data) {
						elthis.find(".scwatbwsr_daily_schedules_times_add_button").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function (data) {
						jQuery(".scw_spin").remove();
						if (!data)
							alert("Added!");
						else
							alert(data);
					}
				});
			}
		});
		elthis.find(".scwatbwsr_daily_schedules_times_refresh_button").on("click", function () {
			location.reload();
		});

		elthis.find(".scwatbwsr_daily_schedules_times_list_item").each(function () {
			var thistime = jQuery(this);

			thistime.children(".scwatbwsr_daily_schedules_times_list_item_input.input_start").datetimepicker({
				datepicker: false,
				step: 5,
				format: "H:i"
			});
			thistime.children(".scwatbwsr_daily_schedules_times_list_item_input.input_end").datetimepicker({
				datepicker: false,
				step: 5,
				format: "H:i"
			});
			thistime.children(".scwatbwsr_daily_schedules_times_list_item_button").on("click", function () {
				var thistimeid = thistime.children(".scwatbwsr_daily_schedules_times_list_item_id").val();
				var thistimeweek = thistime.children(".scwatbwsr_daily_schedules_times_list_item_week").val();
				var thistimetime = thistime.children(".scwatbwsr_daily_schedules_times_list_item_input.input_start").val();
				var thistimeend = thistime.children(".scwatbwsr_daily_schedules_times_list_item_input.input_end").val();
				var week_day = thistime.children(".scwatbwsr_daily_schedules_times_list_item_week").val();
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						thistimeid: thistimeid,
						thistimetime: thistimetime,
						endtime: thistimeend,
						thistimeweek: thistimeweek,
						roomid: jQuery(".scwatbwsr_room_id").val(),
						task: "save_time",
						week_day: week_day
					},
					type: 'POST',
					beforeSend: function (data) {
						thistime.children(".scwatbwsr_daily_schedules_times_list_item_button").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function (data) {
						jQuery(".scw_spin").remove();
						if (!data)
							alert("Saved!");
						else
							alert("Error!");
					}
				});
			});
			////
			thistime.children(".scwatbwsr_daily_schedules_times_list_item_delete").on("click", function () {
				var r = confirm("This time will be delete, are you sure?");
				if (r == true) {
					var thistimeid = thistime.children(".scwatbwsr_daily_schedules_times_list_item_id").val();

					jQuery.ajax({
						url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
						data: {
							thistimeid: thistimeid,
							task: "delete_time"
						},
						type: 'POST',
						beforeSend: function (data) {
							thistime.children(".scwatbwsr_daily_schedules_times_list_item_delete").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
						},
						success: function (data) {
							jQuery(".scw_spin").remove();
							if (!data)
								thistime.remove();
							else
								alert("Error!");
						}
					});
				} else {
					return false;
				}
			});
		});

		////////////
		elthis.find(".scwatbwsr_prices_save").on("click", function () {
			var priceString = "";

			elthis.find(".scwatbwsr_prices_item").each(function () {
				var typeid = jQuery(this).children(".scwatbwsr_prices_item_typeid").val();
				var price = jQuery(this).children(".scwatbwsr_prices_item_price").val();
				var type = jQuery(this).children(".scwatbwsr_prices_item_type").val();

				if (priceString)
					priceString += "@" + typeid + "-" + price + "-" + type;
				else
					priceString += typeid + "-" + price + "-" + type;
			});

			if (priceString) {
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						priceString: priceString,
						task: "save_price"
					},
					type: 'POST',
					beforeSend: function (data) {
						elthis.find(".scwatbwsr_prices_save").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function (data) {
						jQuery(".scw_spin").remove();
						if (!data)
							alert("Saved!");
						else
							alert("Error!");
					}
				});
			}
		});

		/////////////
		elthis.find(".scwatbwsr_tables_add_button").on("click", function () {
			var label = elthis.find(".scwatbwsr_tables_add_label").val();
			var seats = elthis.find(".scwatbwsr_tables_add_seats").val();
			var type = elthis.find(".scwatbwsr_tables_add_type").val();

			if (label && seats && type) {
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId:  elthis.find(".scwatbwsr_room_id").val(),
						label: label,
						seats: seats,
						type: type,
						task: "add_table"
					},
					type: 'POST',
					beforeSend: function (data) {
						elthis.find(".scwatbwsr_tables_add_button").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function (data) {
						jQuery(".scw_spin").remove();
						if (!data) {
							alert("Added!");
						} else
							alert(data);
					}
				});
			} else {
				alert("Please enter information!");
			}
		});
		elthis.find(".scwatbwsr_tables_add_reload,.scwatbwsr_schedules_spec_add_reload,.scwatbwsr_roomtype_add_reload,.scwatbwsr_daily_schedules_times_refresh_button").on("click", function () {
			var dataId = jQuery(this).data("id");
			window.location.href = "admin.php?page=scwatbwsr-table-settings&tab=" + jQuery(".scwatbwsr_room_content." + dataId + " .scwatbwsr_room_content_tabs_label.active").attr("for")
		});

		elthis.find(".scwatbwsr_tables_list_item").each(function () {
			var thistable = jQuery(this);

			thistable.children(".scwatbwsr_tables_list_item_save").on("click", function () {
				var thistbid = thistable.children(".scwatbwsr_tables_list_item_id").val();
				var thistbseats = thistable.children(".scwatbwsr_tables_list_item_seats").val();
				var thistbtype = thistable.children(".scwatbwsr_tables_list_item_type").val();

				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						thistbid: thistbid,
						thistbseats: thistbseats,
						thistbtype: thistbtype,
						task: "save_table"
					},
					type: 'POST',
					beforeSend: function (data) {
						thistable.children(".scwatbwsr_tables_list_item_save").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function (data) {
						jQuery(".scw_spin").remove();
						if (!data)
							alert("Saved!");
						else
							alert("Error!");
					}
				});
			});
			////
			thistable.children(".scwatbwsr_tables_list_item_del").on("click", function () {
				var r = confirm("This table will be delete, are you sure?");
				if (r == true) {
					var thistbid = thistable.children(".scwatbwsr_tables_list_item_id").val();

					jQuery.ajax({
						url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
						data: {
							thistbid: thistbid,
							task: "delete_table"
						},
						type: 'POST',
						beforeSend: function (data) {
							thistable.children(".scwatbwsr_tables_list_item_del").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
						},
						success: function (data) {
							jQuery(".scw_spin").remove();
							if (!data)
								thistable.remove();
							else
								alert("Error!");
						}
					});
				} else {
					return false;
				}
			});

			////////////////
			elthis.find(".scwatbwsr_tables_list_item_clone").on("click", function () {
				var newname = prompt("[clone] Please enter new Table name", "");
				var thistbid = thistable.children(".scwatbwsr_tables_list_item_id").val();
				var thistbseats = thistable.children(".scwatbwsr_tables_list_item_seats").val();
				var thistbtype = thistable.children(".scwatbwsr_tables_list_item_type").val();

				if (newname) {
					jQuery.ajax({
						url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
						data: {
							newname: newname,
							thistbid: thistbid,
							thistbseats: thistbseats,
							thistbtype: thistbtype,
							task: "clone_table"
						},
						type: 'POST',
						beforeSend: function (data) {
							elthis.find(".scwatbwsr_tables_list_item_clone").append(' <i class="fa fa-refresh fa-spin scwspin"></i>');
						},
						success: function (data) {
							jQuery(".scwspin").remove();
							if (!data)
								location.reload();
							else
								alert(data);
						}
					});
				}
			});
		});



		///////////////////
		elthis.find(".scwatbwsr_mapping_table").each(function () {
			var thistbmap = jQuery(this);
			thistbmap.draggable({
				containment: "parent",
				drag: function () {
					thistbmap.children('.topline').css('display', 'block');
					thistbmap.children('.rightline').css('display', 'block');
					thistbmap.children('.botline').css('display', 'block');
					thistbmap.children('.leftline').css('display', 'block');
				},
				start: function () {
					thistbmap.children('.topline').css('display', 'block');
					thistbmap.children('.rightline').css('display', 'block');
					thistbmap.children('.botline').css('display', 'block');
					thistbmap.children('.leftline').css('display', 'block');
				},
				stop: function () {
					thistbmap.children('.topline').css('display', 'none');
					thistbmap.children('.rightline').css('display', 'none');
					thistbmap.children('.botline').css('display', 'none');
					thistbmap.children('.leftline').css('display', 'none');
				}
			});

			
		});

		/////////////
		elthis.find(".scwatbwsr_mapping_preview_save").on("click", function () {
			var tbstring = "";
			var seatstring = "";

			elthis.find(".scwatbwsr_mapping_table").each(function () {
				var thistb = jQuery(this);
				var tbid = thistb.children(".scwatbwsr_mapping_table_id").val();
				var tbleft = thistb.position().left;
				var tbtop = thistb.position().top;

				if (tbstring)
					tbstring += "@" + tbid + "#" + tbleft + "#" + tbtop;
				else
					tbstring += tbid + "#" + tbleft + "#" + tbtop;

				var seatdt = "";
				thistb.find(".scwatbwsr_mapping_table_seat").each(function () {
					var seatlb = jQuery(this).text().trim();
					var sleft = jQuery(this).position().left;
					var stop = jQuery(this).position().top;

					if (seatdt)
						seatdt += "&" + seatlb + "$" + sleft + "$" + stop;
					else
						seatdt += seatlb + "$" + sleft + "$" + stop;
				});
				if (seatstring)
					seatstring += "@" + tbid + "#" + seatdt;
				else
					seatstring += tbid + "#" + seatdt;
			});

			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					tbstring: tbstring,
					seatstring: seatstring,
					task: "save_mapping"
				},
				type: 'POST',
				beforeSend: function (data) {
					elthis.find(".scwatbwsr_mapping_preview_save").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
				},
				success: function (data) {
					jQuery(".scw_spin").remove();
					if (!data)
						alert("Saved!");
					else
						alert("Error!");
				}
			});
		});

		////////////////
		elthis.find(".scwatbwsr_room_head_copy").on("click", function () {
			var newname = prompt("[Copy] Please enter new Room name", "");
			if (newname) {
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						newname: newname,
						roomId:  elthis.find(".scwatbwsr_room_id").val(),
						task: "copy_room"
					},
					type: 'POST',
					beforeSend: function (data) {
						elthis.find(".scwatbwsr_room_head_copy").append(' <i class="fa fa-refresh fa-spin scwspin"></i>');
					},
					success: function (data) {
						jQuery(".scwspin").remove();
						if (!data)
							location.reload();
						else
							alert(data);
					}
				});
			}
		});

		/////////////
		elthis.find(".scwatbwsr_room_head_delete").on("click", function () {
			var r = confirm("This room will be delete, are you sure?");
			if (r == true) {
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId: elthis.find(".scwatbwsr_room_id").val(),
						task: "delete_room"
					},
					type: 'POST',
					beforeSend: function (data) {
						elthis.find(".scwatbwsr_room_head_delete").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function (data) {
						jQuery(".scw_spin").remove();
						if (!data)
							elthis.remove();
						else
							alert(data);
					}
				});
			} else {
				return false;
			}
		});

		/////////////
		elthis.find(".scwatbwsr_orders_item").each(function () {
			var thisorder = jQuery(this);

			thisorder.children(".scwatbwsr_orders_item_del").on("click", function () {
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						oid: thisorder.children(".scwatbwsr_orders_item_oid").val(),
						task: "delete_order"
					},
					type: 'POST',
					beforeSend: function (data) {
						thisorder.children(".scwatbwsr_orders_item_del").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function (data) {
						jQuery(".scw_spin").remove();
						if (!data)
							thisorder.remove();
						else
							alert(data);
					}
				});
			});
		});

		//////////

		var thiss = elthis.find(".scwatbwsr_bktables_seat")

		jQuery(".scwatbwsr_bktables_seat_make_input").change(function () {
			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					roomId:  elthis.find(".scwatbwsr_room_id").val(),
					seat: jQuery(this).val(),
					task: "make_as_booked"
				},
				type: 'POST',
				beforeSend: function (data) {
					thiss.children(".scwatbwsr_bktables_seat_make").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
				},
				success: function (data) {
					jQuery(".scw_spin").remove();
					if (data) alert(data);
				}
			});
		});



	});



	///////
	jQuery(".scwatbwsr_room_content").each(function (lotkey, lotval) {
		if (jQuery(this).find(".scwatbwsr_room_content_tabs_label.active").length == 0) {
			jQuery(this).slideUp();
		}
	});
})(jQuery);

function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	var expires = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

jQuery("#add_offline_payment").click(function () {
	var booking_id = jQuery(this).attr('data-id');
	var order_amount = jQuery(this).attr('data-orderAmount');
	
	var htmlContent = '<form method="post" id="offlinePayment" action="#" ><table>' +
						'<tr><td>Date</td><td><input type="date" class="form-control" style="width:11rem;" id="paymentDate" placeholder="date">' + '</td>' +
						'<tr><td>Amount</td><td><input type="text" class="form-control" id="amount" placeholder="price">' + '</td>' +
						'<tr><td>Payment Type</td><td><select class="form-control" style="width:11rem;" id="paymentType"><option value="cash">'+'cash'+ '</option><option value="swipe">'+'swipe'+'</option></td>' +
						'<tr><td colspan="2"><button type="submit" onClick="openOfflinePayment(' + booking_id + ')" class="btn--primary">Submit</button></td>'+ '</td></form>';

		Swal.fire({
            title: "Offline Payment",
            html:htmlContent,
            showCloseButton: true,
            showCancelButton: false,
            focusConfirm: false,
            showConfirmButton:false
          });

	
});




function openOfflinePayment(booking_id ) {
var payment_date = jQuery('#paymentDate').val();
var price = jQuery('#amount').val();
var payment_type = jQuery('#paymentType').val();

if (amount) {
	
	jQuery.ajax({
		url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
		data: {
			bookingId: booking_id,
			date:payment_date,
			amount:price,
			paymentType:payment_type,
			task: "offline_payment_history"
		},
		type: 'POST',
		
		success: function (data) {

					swal.close();
					Swal.fire(
						'Offline Payment Saved!',
						'Offline payment saved successfully!',
						'success'
					)
		
		
				},
				error: function (data) {
				
					alert('Try again!')
				}
	});
}

}


var ctx = document.getElementById('myChart').getContext("2d");
var myChart;
function drawChart(data) {

	if (ctx) {
		if (myChart)
			myChart.destroy();
		const MONTHS = [
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December'
		];
		const COLORS = [
			'#4dc9f6',
			'#f67019',
			'#f53794',
			'#537bc4',
			'#acc236',
			'#166a8f',
			'#00a950',
			'#58595b',
			'#8549ba',
			'#4dc9f6',
			'#f67019',
			'#f53794',
		];
		const labels = MONTHS;
		myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: labels,
				datasets: data
			},
			options: {
				scales: {
					y: {
						beginAtZero: true
					}
				}
			}
		});
	}
}


jQuery(document).ready(function () {
	reportsFilterYear(jQuery(".weeks-option__item_year:first").text().trim())
})