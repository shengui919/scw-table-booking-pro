(function($) {
"use strict";
	
	
	/*var phantram = 100/roomwidth*roomheight;
	var bw = jQuery(".scwatbwsr_map_block").width();
	console.log(bw);
	var fh = bw/100*phantram;
	jQuery(".scwatbwsr_map_block").css("height", fh+"px");*/
	$( document ).ready(function() {
		$(".body .is-layout-flow").css({'clear':'both'})
		var url = jQuery(".scwatbwsr_url").val();
		var proid = jQuery(".product_id").val();
		var roomid = jQuery(".profileid").val();
		var tbbookedcolor = jQuery(".tbbookedcolor").val();
		var seatbookedcolor = jQuery(".seatbookedcolor").val();
		var compulsory = jQuery(".scw_compulsory").val();
		var bookingtime = jQuery(".scw_bookingtime").val();
		var date_format = jQuery(".scw_date_format").val();
		var roomwidth = jQuery(".scw_roomwidth").val();
		var roomheight = jQuery(".scw_roomheight").val();
		var posttype = jQuery(".scw_posttype").val();
		var zoomoption = jQuery(".scw_zoomoption").val();
	
	jQuery(".woocommerce-tabs").before(jQuery(".scwatbwsr_content").show());
	jQuery(".scwatbwsr_content").after(jQuery("form.cart"));
	
	if(compulsory == "yes")
		jQuery(".single_add_to_cart_button").prop("disabled", true);
	if(jQuery(".scwatbwsr_schedules_daily").length>0 && jQuery(".scwatbwsr_schedules_daily").length > 0){
		var array_dates = jQuery(".array_dates").val();
		
		if(jQuery(".array_times").val()){
			var array_times = jQuery(".array_times").val().split(",");
			jQuery('#scwatbwsr_schedules_picker').datetimepicker({
				disabledWeekDays: array_dates,
				allowTimes: array_times,
				format: date_format+' H:i',
				defaultTime: array_times[0],
				closeOnDateSelect: false,
				onSelectTime:function(ct, $i){
					checkSchedule($i[0].value);
				},
				onSelectDate:function(ct,$i){
					checkSchedule($i[0].value);
				}
			});
		}else{
			jQuery('#scwatbwsr_schedules_picker').datetimepicker({
				disabledWeekDays: array_dates,
				step: 5,
				format: date_format+' H:i',
				defaultTime: "00:00",
				closeOnDateSelect: false,
				onSelectTime:function(ct, $i){
					checkSchedule($i[0].value);
				},
				onSelectDate:function(ct,$i){
					checkSchedule($i[0].value);
				}
			});
		}
	}else{
		jQuery(".scwatbwsr_schedules_item").each(function(){
			var thische = jQuery(this);
			thische.on("click", function(){
				jQuery(".scwatbwsr_schedules_item").removeClass("active");
				thische.addClass("active");
				
				checkSchedule(thische.text());
			});
		});
	}
	function checkSchedule(schedule){
		jQuery.ajax({
			type: "POST",
			url: url+"helper.php",
			data:{
				task: "check_schedule",
				schedule: schedule,
				roomid: roomid,
				proid: proid,
				bookingtime: bookingtime
			},
			beforeSend : function(data){
				jQuery(".scwatbwsr_map").css("opacity", "0.5");
			},
			success : function(data){
				jQuery(".scwatbwsr_map").css("opacity", "1");
				
				jQuery(".scwatbwsr_map_tables_table").each(function(){
					var thistb = jQuery(this);
					
					var tbreadcolor = thistb.children(".scwatbwsr_table_readcolor").val();
					var seatreadcolor = thistb.children(".scwatbwsr_seat_readcolor").val();
					
					thistb.css("background", tbreadcolor+" none repeat scroll 0% 0% padding-box content-box");
					thistb.find(".scwatbwsr_map_tables_table_seat").css("background", seatreadcolor).removeClass("seatbooked");
				});
				
				if(data.length > 0){
					jQuery.each(data, function(key, val){
						var seat = val.replace(".", "");
						
						jQuery("#seat"+seat).css("background", seatbookedcolor).addClass("seatbooked");
					});
					
					jQuery(".scwatbwsr_map_tables_table").each(function(){
						if(jQuery(this).find(".scwatbwsr_map_tables_table_seat").length == jQuery(this).find(".scwatbwsr_map_tables_table_seat.seatbooked").length)
							jQuery(this).css("background", tbbookedcolor+" none repeat scroll 0% 0% padding-box content-box");
					});
				}
			},
			dataType: 'json'
		});
	}
	
	jQuery(".scwatbwsr_map_tables_table").each(function(){
		var thistb = jQuery(this);
		
		thistb.find(".scwatbwsr_map_tables_table_seat").each(function(){
			var thiseat = jQuery(this);
			thiseat.on("click", function(){
				
				if(!thiseat.hasClass("seatbooked")){
					if(jQuery("#scwatbwsr_schedules_picker").length > 0 || jQuery(".scwatbwsr_schedules_item").length > 0){
						if(jQuery("#scwatbwsr_schedules_picker").val() || jQuery(".scwatbwsr_schedules_item.active").length > 0){
							if(thiseat.hasClass("active"))
								thiseat.removeClass("active");
							else
								thiseat.addClass("active");
							sessSeat();
						}else
							alert("Please choose schedule first!");
					}else{
						if(thiseat.hasClass("active"))
							thiseat.removeClass("active");
						else
							thiseat.addClass("active");
						sessSeat();
					}
				}
			});
		});
		thistb.children(".scwatbwsr_map_tables_table_label").on("click", function(){
			if(jQuery("#scwatbwsr_schedules_picker").length > 0 || jQuery(".scwatbwsr_schedules_item").length > 0){
				if(jQuery("#scwatbwsr_schedules_picker").val() || jQuery(".scwatbwsr_schedules_item.active").length > 0){
					if(thistb.find(".seatbooked").length > 0){
						alert("Can not book whole table!");
					}else{
						if(jQuery(this).hasClass("active")){
							jQuery(this).removeClass("active");
							thistb.find(".scwatbwsr_map_tables_table_seat").removeClass("active");
						}else{
							jQuery(this).addClass("active");
							thistb.find(".scwatbwsr_map_tables_table_seat").addClass("active");
						}
						sessSeat();
					}
				}else
					alert("Please choose schedule first!");
			}else{
				if(thistb.find(".seatbooked").length > 0){
					alert("Can not book whole table!");
				}else{
					if(jQuery(this).hasClass("active")){
						jQuery(this).removeClass("active");
						thistb.find(".scwatbwsr_map_tables_table_seat").removeClass("active");
					}else{
						jQuery(this).addClass("active");
						thistb.find(".scwatbwsr_map_tables_table_seat").addClass("active");
					}
					sessSeat();
				}
			}
		});
	});
	
	function sessSeat(){
		var seats = "";
		jQuery(".scwatbwsr_map_tables_table").each(function(){
			var tbname = jQuery(this).children(".scwatbwsr_map_tables_table_label").text().trim();
			jQuery(this).find(".scwatbwsr_map_tables_table_seat.active").each(function(){
				if(seats)
					seats += "@"+tbname+"."+jQuery(this).text().trim();
				else
					seats += tbname+"."+jQuery(this).text().trim();
			});
		});
		
		jQuery.ajax({
			type: "POST",
			url: url+"helper.php",
			data:{
				task: "sess_seats",
				seats: seats,
				proid: proid,
				posttype: posttype
			},
			beforeSend : function(data){
				jQuery(".scwatbwsr_map").css("opacity", "0.5");
			},
			success : function(data){
				jQuery(".scwatbwsr_map").css("opacity", "1");
				
				if(compulsory == "yes"){
					if(jQuery(".scwatbwsr_map_tables_table_seat.active").length > 0)
						jQuery(".single_add_to_cart_button").prop("disabled", false);
					else
						jQuery(".single_add_to_cart_button").prop("disabled", true);
				}
				
				if(posttype == "post" || posttype == "page"){
					jQuery(".scwatbwsr_total_value").text(data);
				}
			}
		});
	}
	
	if(zoomoption=="1"){
		const element = document.getElementById('scwatbwsr_map_panzoom');
		const zoomInButton = document.getElementById('scwatbwsr_map_zoom-in');
		const zoomOutButton = document.getElementById('scwatbwsr_map_zoom-out');
		const resetButton = document.getElementById('scwatbwsr_map_zoom_reset');
		const panzoom = Panzoom(element, {
			 bounds: true,
			 zoomDoubleClickSpeed: 1,
			 excludeClass: "scwatbwsr_map_exclude"
		});
		
		const parent = element.parentElement
		parent.addEventListener('wheel', panzoom.zoomWithWheel);
		zoomInButton.addEventListener('click', panzoom.zoomIn)
		zoomOutButton.addEventListener('click', panzoom.zoomOut)
		resetButton.addEventListener('click', panzoom.reset)
	}
	
	// wordpress post
	if(posttype == "post" || posttype == "page"){
		jQuery(".scwatbwsr_form_submit").click(function(){
			var name = jQuery(".scwatbwsr_form_name_input").val();
			var address = jQuery(".scwatbwsr_form_address_input").val();
			var email = jQuery(".scwatbwsr_form_email_input").val();
			var phone = jQuery(".scwatbwsr_form_phone_input").val();
			var note = jQuery(".scwatbwsr_form_note_input").val();
			var total = jQuery(".scwatbwsr_total_value").text().trim();

			
			var seats = "";
			jQuery(".scwatbwsr_map_tables_table").each(function(){
				var tbname = jQuery(this).children(".scwatbwsr_map_tables_table_label").text().trim();
				jQuery(this).find(".scwatbwsr_map_tables_table_seat.active").each(function(){
					if(seats)
						seats += "@"+tbname+"."+jQuery(this).text().trim();
					else
						seats += tbname+"."+jQuery(this).text().trim();
				});
			});
			
			var schedule = jQuery(".scwatbwsr_schedules_item.active").text().trim();
			if(!schedule) schedule = jQuery("#scwatbwsr_schedules_picker").val();
			
			if(seats){
				jQuery.ajax({
					url: url+"helper.php",
					data: {
						name: name,
						address: address,
						email: email,
						phone: phone,
						note: note,
						proId: proid,
						total: total,
						seats: seats,
						schedule: schedule,
						task : "send_mail",
						url:url,
						billing_first_name:$(".billing_first_name").val(),
						billing_last_name:$(".billing_last_name").val(),
						billing_address_1:$(".billing_address_1").val(),
						billing_address_2:$(".billing_address_2").val(),
						billing_city:$(".billing_city").val(),
						billing_state:$(".billing_state").val(),
						billing_postcode:$(".billing_postcode").val(),
						billing_country:$(".billing_country").val(),
						billing_email:$(".billing_email").val(),
						billing_phone:$(".billing_phone").val()

					},
					type: 'POST',
					beforeSend: function(data){
						jQuery(".scwatbwsr_sendform").css("opacity", "0.5");
					},
					dataType: "json",
					success: function(data){
						jQuery(".scwatbwsr_sendform").css("opacity", "1");
						if(data == "1")
							alert("We got the order, will contact you soon!");
						else
							
						{
							if(data && data.result=='success')
							{
								window.location.href = data.redirect;
							}
						}
					}
				});
			}
		});
	}
});
})(jQuery);