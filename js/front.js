var rest_settings;
var listUnabvaileRoom;
var setDate;
var setTime;
(function($) {
"use strict";
	
	
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

        $(".people_number_selected").html($(".people_number option:first").text());
        $(".people_number").change(function(){
			
			$(".people_number_selected").html($(".people_number option:selected").text());
		})
		$(".booking_date_selected").html($(".booking_date option:first").text());
        $(".booking_date").change(function(){
			jQuery(".timebtn").html('')
			$(".booking_date_selected").html($(".booking_date option:selected").text());
			$(".Selected_Time").text(
				"Booking Date : "+$(".booking_date_selected").html()+" "+$(".booking_time_selected").html()
			)
			$("#time_hidden").val($(".booking_date_selected").html()+" "+$(".booking_time_selected").html())
		})
		$(".booking_time_selected").html($(".booking_time option:first").text());
		$(".bornone.booking_time").val("17:00").change();
		$(".booking_time_selected").html($(".booking_time option:selected").text());
		$(".Selected_Time").text(
			"Booking Date "+$(".booking_date_selected").html()+" "+$(".booking_time_selected").html()
		)
		$("#time_hidden").val($(".booking_date_selected").html()+" "+$(".booking_time_selected").html())
        $(".booking_time").change(function(){
			jQuery(".timebtn").html('')
			$(".booking_time_selected").html($(".booking_time option:selected").text());
			$(".Selected_Time").text(
				"Booking Date "+$(".booking_date_selected").html()+" "+$(".booking_time_selected").html()
			)
			$("#time_hidden").val($(".booking_date_selected").html()+" "+$(".booking_time_selected").html())
		})
		$("#time_hidden").val($(".booking_date_selected").html()+" "+$(".booking_time_selected").html())
		$("#no_people_hidden").val($(".people_number option:first").val())

	function myDateDay(speDay='') {
			var a = new Date();
			if(speDay && speDay!='')
			a = new Date(speDay);
			var weekdays = new Array(7);
			weekdays[0] = "sunday";
			weekdays[1] = "monday";
			weekdays[2] = "tuesday";
			weekdays[3] = "wednesday";
			weekdays[4] = "thursday";
			weekdays[5] = "friday";
			weekdays[6] = "saturday";
			var r = weekdays[a.getDay()];
			return r;
	}	
	function formBook()
	{
		 
			        var name = jQuery(".scwatbwsr_form_name_input").val() +" "+ jQuery(".scwatbwsr_form_name_last_input").val();
					var address = jQuery(".scwatbwsr_form_address_input").val();
					var email = jQuery(".scwatbwsr_form_email_input").val();
					var phone = jQuery(".scwatbwsr_form_phone_input").val();
					var note = jQuery(".scwatbwsr_form_note_input").val();
					var total = jQuery(".scwatbwsr_total_value").val();
					var no_seat= jQuery("#no_people_hidden").val();
					var schedule = setDate+ " "+ setTime
					var roomId = jQuery(".scwatbwsr_form_room_input").val();
					var data= {
						name: name,
						address: address,
						email: email,
						phone: phone,
						note: note,
						proId: 0,
						seat:jQuery(".scwatbwsr_form_tabel_input").val(),
						roomid: roomId,
						total: total,
						schedule: schedule,
						task : "send_mail",
						url:url,
						customer_table:customer_table,
						enabled_payment:enabled_payment
					}
				// 	if(customer_table=="yes")
				// 	{
						
					
				// 	jQuery(".scwatbwsr_map_tables_table").each(function(){
				// 		var tbname = jQuery(this).children(".scwatbwsr_map_tables_table_label").text().trim();
				// 		jQuery(this).find(".scwatbwsr_map_tables_table_seat.active").each(function(){
				// 			if(seats)
				// 				seats += "@"+tbname+"."+jQuery(this).text().trim();
				// 			else
				// 				seats += tbname+"."+jQuery(this).text().trim();
				// 		});
				// 	});
				// 	data.seats= seats;
				//    }
				//    else 
				//    {
				// 	  no_seat = jQuery(".scwatbwsr_form_seat_input").val();
				// 	  data.no_seat = no_seat;
				//    }
					
					
					if(enabled_payment=="on")
					{
						data.billing_first_name=$(".scwatbwsr_form_name_input").val()
						data.billing_last_name=$(".scwatbwsr_form_name_last_input").val()
						data.billing_address_1=$(".scwatbwsr_form_address_input").val()
						data.billing_address_2='';//$(".billing_address_2").val()
						data.billing_city='';//$(".billing_city").val()
						data.billing_state='';//$(".billing_state").val()
						data.billing_postcode='';//$(".billing_postcode").val()
						data.billing_country='';//$(".billing_country").val()
						data.billing_email=$(".scwatbwsr_form_email_input").val()
						data.billing_phone=$(".scwatbwsr_form_phone_input").val()
					}
					
					if(no_seat>0){
						jQuery.ajax({
							url: url+"helper.php",
							data:data,
							type: 'POST',
							beforeSend: function(data){
								jQuery(".scwatbwsr_sendform").css("opacity", "0.5");
							},
							dataType: "json",
							success: function(data){
								jQuery(".scwatbwsr_sendform").css("opacity", "1");
								if(data && data.success)
								{
									Swal.fire(
										'Booking Status',
										data.message,
										'success'
										)
										window.location.reload();
								}
									
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
					else 
					{
						alert('select seats')
					}
				
			
	}
		

	$("#scw-booking-form").validate ({
			submitHandler: function(form) {  
				formBook();
			}
	});
	
	$(".findtable").click(function(){
		checkSchedule();
	})
		
	
	function checkSchedule(){
		
		setDate= jQuery(".booking_date option:selected").val();
		jQuery.ajax({
			type: "POST",
			url: url+"helper.php",
			dataType:"JSON",
			data:{
				task: "check_schedule",
				schedule: jQuery(".booking_date option:selected").val()+" "+jQuery(".booking_time option:selected").val(),
				seats: jQuery("#no_people_hidden").val()
			},
			beforeSend : function(data){
				jQuery(".br-btm").css("display","none");
				jQuery(".scwatbwsr_map").css("opacity", "0.5");
				jQuery(".standard-outdoor").removeClass("hide");
				jQuery(".timebtn").css({"opacity": "1","display":"block"});
				jQuery(".timebtn").html('').css({"min-height":"100px"});
				jQuery(".pricetrue").removeClass("hide")
				jQuery("#nodata-table").html('');
			},
			success : function(data){
				
				var html='';
				
				if(data && data.times && data.times.length>0)
				{
					var timeData= data.times;
					var mH= parseInt(timeData.length)/4;
					
					mH= parseInt(Math.ceil(mH)*80) +80
					jQuery(".timebtn").css({"height":mH+"px"})
					rest_settings = data.rest_settings;
					listUnabvaileRoom = data.listUnabvaileRoom;
					if(listUnabvaileRoom && listUnabvaileRoom.length>0)
					{
						var nohtml='';
						for(var i=0;i<listUnabvaileRoom.length;i++)
					    {
							var tt=listUnabvaileRoom[i];
							
							jQuery(".roomava-"+tt.roomid).addClass("hide");
							var tName =jQuery(".roomava-"+tt.roomid).data("room")
							nohtml+='<div class="mainlounge">'+tName+'</div>'+
							'<div class="notable">No tables available.</div>';
						}
						jQuery(".br-btm").css("display","block");
						jQuery("#nodata-table").html(nohtml);
					}
					if(data && data.rest_settings && data.rest_settings.enabled_payment!="on")
					{
						jQuery(".pricetrue").addClass("hide")
					}
					for(var i=0;i<timeData.length;i++)
					{
						
						var tt=timeData[i];
						var timeInterval=tt.replace(":","");
						html+="<button id='"+timeInterval+"' data-time='"+tt+"' data-id='"+setDate+" "+tt+"' onclick='myFunction("+timeInterval+")'><span>"+
							'<i class="fa fa-clock-o time-3-icon"></i>'+
						    '</span> '+tt+' </button>';
					}
				
				}
				else 
				{
				html+="<button style='width:100%'><span>"+
					'<i class="fa fa-remove time-3-icon"></i>'+
					'</span> Restaurant Closed! </button>';
				}
				
				jQuery(".timebtn").html(html)
			},
			dataType: 'json'
		});
	}
	
	
	
	
	
});
})(jQuery);

function myFunction(setDate) {
	var vRoomLen=jQuery(".standard-outdoor").length-1;
	var hRoomLen=jQuery(".mainlounge").length;
	if(vRoomLen==hRoomLen)
	{
		jQuery("#booking-continue").removeClass("hideNone");
		jQuery(".othertext").addClass("hideNone")
		jQuery(".br-btm").addClass("hideNone")
	}
	
	else 
	{
		jQuery("#booking-continue").addClass("hideNone");
		jQuery(".othertext").removeClass("hideNone")
		jQuery(".br-btm").removeClass("hideNone")
		mysecondtab()
	}
	setTime = jQuery("#"+setDate).data("time");
	var dateL=jQuery("#time_hidden").val().substring(0,jQuery("#time_hidden").val().length-5);
	jQuery("#booking_time_span").html(dateL+" "+setTime);
	jQuery("#span_booking_date").html(dateL)
	jQuery("#span_booking_time").html(setTime)
	jQuery("#span_people").html(jQuery("#no_people_hidden").val())
	
	
	
	
	if(rest_settings && rest_settings.customer_table=="yes")
	{
	document.getElementById("myPopup").style.display = "block";
	document.getElementById("topsectionid").style.display = "none";
	
	}
  }
function mybackfn() {
	document.getElementById("myPopup").style.display = "none";
	document.getElementById("topsectionid").style.display = "block";
	jQuery("#secondidtabs").css("display","none");
	jQuery("#sectioncon").css("display","block");
	jQuery("#flifirsttabone").trigger("click")

}
function mysecondtab(id=0,rid=0){
	
	let priceEle = jQuery("#price-find-"+id);
	jQuery(".scwatbwsr_total_value").val(jQuery(priceEle).data("price"))
	document.getElementById("secondidtabs").style.display = "block";
	document.getElementById("sectioncon").style.display = "none"
	document.getElementById("bgtabcol").classList.add("flextablecolor");
	document.getElementById("flifirsttab").style.display = 'block';
	document.getElementById('flifirsttabone').style.display = 'none'
	document.getElementById("myPopup").style.display = "none";
	document.getElementById("topsectionid").style.display = "block";
	jQuery(".scwatbwsr_form_room_input").val(rid)
	jQuery(".scwatbwsr_form_tabel_input").val(id)
}

function mymainfirsttab() {
	document.getElementById("bgtabcol").classList.remove("flextablecolor");
	document.getElementById('flifirsttabone').style.display = 'block'
	document.getElementById("flifirsttab").style.display = 'none';
	document.getElementById("secondidtabs").style.display = "none";
	document.getElementById("sectioncon").style.display = "block"
	
}