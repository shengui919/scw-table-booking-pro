
var upload_image_button=false;
(function(jQuery) {
"use strict";

jQuery( '#rtb-date-filter-link' ).click( function() {
	jQuery( '#rtb-filters' ).toggleClass( 'date-filters-visible' );
});
// Name filter helper
jQuery(document)
.on('click', '#rtb-filters .filter_name a' , function(ev) {
	ev.preventDefault();
	filterByName();
})
.on('keydown', '#rtb-filters .filter_name input' , function(ev) {
	if(event.keyCode == 13) {
		event.preventDefault();
		filterByName();
		return false;
	}
});

function filterByName() {
let text = jQuery('#rtb-filters .filter_name input').val();
let href = jQuery('#rtb-filters .filter_name a').prop('href');
href += '='+encodeURIComponent(text);
href += '&date_range=all';
window.location = href;
}
jQuery(document).on('click', '.date-filters input[type="submit"]', function(event) {
	event.preventDefault();
	
	let args = [];
	let url = new URL(window.location.href);

	jQuery('.date-filters input[type="type"]').each((i, x) => {
		'' === jQuery(x).val() ? null : args.push([jQuery(x).prop('name'), jQuery(x).val()]);
	});

	args.push(['page', url.searchParams.get('page')]);
	args = new URLSearchParams(args);

	window.location = `${url.origin}${url.pathname}?${args.toString()}`;
});
jQuery('.rtb-admin-bookings-filters-start #start-date,#end-date').datetimepicker({
	format: jQuery(".scw_date_format").val()+' H:i',
	closeOnDateSelect: false,
	step: 5,
	defaultTime: "00:00"
});
	jQuery('.scwatbwsr_media_upload').on("click", function(){
        upload_image_button =true;
        var formfieldID = jQuery(this).prev('input');
		
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        if(upload_image_button==true){
			var oldFunc = window.send_to_editor;
			window.send_to_editor = function(html) {
				var imgurl = jQuery('img', html).attr('src');
				jQuery(formfieldID).val(imgurl);
				tb_remove();
				window.send_to_editor = oldFunc;
			}
        }
        upload_image_button=false;
    });
	
	jQuery(".scwatbwsr_add_button").on("click", function(){
		var roomName = jQuery(".scwatbwsr_add_name").val();
		if(roomName){
			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					roomName : roomName,
					task : "add_room"
				},
				type: 'POST',
				beforeSend: function(data){
					jQuery(".scwatbwsr_add_button").append(' <i class="fa fa-refresh fa-spin scwspin"></i>');
				},
				success: function(data){
					jQuery(".scwspin").remove();
					if(!data)
						location.reload();
					else
						alert(data);
				}
			});
		}
	});

	jQuery(".scwatbwsr_add_page_button").on("click", function(){
		var pageName = jQuery(".scwatbwsr_add_page_name").val();
		if(pageName){
			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					reservations_page_title : pageName,
					task : "add_page"
				},
				type: 'POST',
				beforeSend: function(data){
					jQuery(".scwatbwsr_add_page_button").append(' <i class="fa fa-refresh fa-spin scwspin"></i>');
				},
				success: function(data){
					jQuery(".scwspin").remove();
					if(!data)
						location.reload();
					else
						alert(data);
				}
			});
		}
	});
	
	///////
	jQuery(".rooms_area").each(function(lotkey, lotval){
		var elthis = jQuery(this);
		var roomId = jQuery(".scwatbwsr_room_id").val();
		
		elthis.children(".scwatbwsr_room_head").children("i").on("click", function(){
			if(elthis.children(".scwatbwsr_room_content").is(":visible")){
				elthis.children(".scwatbwsr_room_content").slideUp();
				setCookie("status"+lotkey, "close", 1);
				jQuery(this).removeClass("fa-angle-double-down");
				jQuery(this).addClass("fa-angle-double-right");
			}else{
				elthis.children(".scwatbwsr_room_content").slideDown();
				setCookie("status"+lotkey, "open", 1);
				jQuery(this).removeClass("fa-angle-double-right");
				jQuery(this).addClass("fa-angle-double-down");
			}
		});
		var checkStatus = getCookie("status"+lotkey);
		if(checkStatus == "open"){
			elthis.children(".scwatbwsr_room_content").slideDown();
			elthis.children(".scwatbwsr_room_head").children("i").removeClass("fa-angle-double-right");
			elthis.children(".scwatbwsr_room_head").children("i").addClass("fa-angle-double-down");
		}
		
		//////////
		elthis.find(".scwatbwsr_room_content_tabs_input").each(function(key, val){
			var thistab = jQuery(this);
			
			thistab.on("click", function(){
				elthis.find(".scwatbwsr_room_content_tabs_label").removeClass("active");
				elthis.find(".scwatbwsr_room_content_tabs_label:eq("+key+")").addClass("active");
				
				elthis.find(".tab-content").removeClass("active");
				elthis.find(".tab-content:eq("+key+")").addClass("active");
			});
		});
		
		/////////
		elthis.find(".scwatbwsr_basesetting_save").on("click", function(){
			var width = elthis.find(".scwatbwsr_roomsize_width").val();
			var height = elthis.find(".scwatbwsr_roomsize_height").val();
			var color = elthis.find(".scwatbwsr_roombg_con_color").val();
			var bg = elthis.find(".scwatbwsr_roombg_con_image").val();
			var newRoomname = elthis.find(".scwatbwsr_room_content_editname_name").val();
			var tbbookedcolor = elthis.find(".scwatbwsr_bookedpr_tbcolor").val();
			var seatbookedcolor = elthis.find(".scwatbwsr_bookedpr_seatcolor").val();
			var bktime = elthis.find(".scwatbwsr_bktime_ip").val();
			var compulsory = elthis.find(".scwatbwsr_compulsory_ip").is(":checked")?"yes":"no";
			var zoom = elthis.find(".scwatbwsr_zoom_ip").is(":checked")?"1":"0";
			
			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					roomId : roomId,
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
					task : "save_base_setting"
				},
				type: 'POST',
				beforeSend: function(data){
					elthis.find(".scwatbwsr_basesetting_save").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
				},
				success: function(data){
					jQuery(".scw_spin").remove();
					if(!data){
						alert("Saved!");
					}else
						alert("Error!");
				}
			});
		});
		
		////////
		elthis.find("input[name='scwatbwsr_roomtype_add_tbshape']").on("change", function(){
			elthis.find(".scwatbwsr_roomtype_add_tbshape_rec_width").val("");
			elthis.find(".scwatbwsr_roomtype_add_tbshape_rec_height").val("");
			elthis.find(".scwatbwsr_roomtype_add_tbshape_cir_width").val("");
		});
		elthis.find("input[name='scwatbwsr_roomtype_add_seatshape']").on("change", function(){
			elthis.find(".scwatbwsr_roomtype_add_seatshape_rec_width").val("");
			elthis.find(".scwatbwsr_roomtype_add_seatshape_rec_height").val("");
			elthis.find(".scwatbwsr_roomtype_add_seatshape_cir_width").val("");
		});
		elthis.find(".scwatbwsr_roomtype_add_button").on("click", function(){
			var typename = elthis.find(".scwatbwsr_roomtype_add_name").val();
			var tbbg = elthis.find(".scwatbwsr_roomtype_add_tbcolor_input").val();
			var tbshape = elthis.find("input[name='scwatbwsr_roomtype_add_tbshape']:checked").val();
			var tbrecwidth = elthis.find(".scwatbwsr_roomtype_add_tbshape_rec_width").val();
			var tbrecheight = elthis.find(".scwatbwsr_roomtype_add_tbshape_rec_height").val();
			var tbcirwidth = elthis.find(".scwatbwsr_roomtype_add_tbshape_cir_width").val();
			var seatbg = elthis.find(".scwatbwsr_roomtype_add_seatcolor_input").val();
			var seatshape = elthis.find("input[name='scwatbwsr_roomtype_add_seatshape']:checked").val();
			var seatwidth = elthis.find(".scwatbwsr_roomtype_add_seat_size").val();
			
			if(typename && tbshape && seatshape){
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId : roomId,
						typename: typename,
						tbbg: tbbg,
						tbshape: tbshape,
						tbrecwidth: tbrecwidth,
						tbrecheight: tbrecheight,
						tbcirwidth: tbcirwidth,
						seatbg: seatbg,
						seatshape: seatshape,
						seatwidth: seatwidth,
						task : "add_type"
					},
					type: 'POST',
					beforeSend: function(data){
						elthis.find(".scwatbwsr_roomtype_add_button").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
						jQuery(".scw_spin").remove();
						if(!data){
							alert("Added!");
						}else
							alert(data);
					}
				});
			}else{
				alert("Please enter information!");
			}
		});
		elthis.find(".scwatbwsr_roomtype_add_reload").on("click", function(){
			location.reload();
		});
		
		////////
		elthis.find(".scwatbwsr_roomtype_item").each(function(){
			var thistype = jQuery(this);
			
			thistype.children(".scwatbwsr_roomtype_item_save").on("click", function(){
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
						thistypeid : thistypeid,
						thistbcolor: thistbcolor,
						thistbrecwidth: thistbrecwidth,
						thistbrecheight: thistbrecheight,
						thistbcirwidth: thistbcirwidth,
						thisseatcolor: thisseatcolor,
						seatwidth: seatwidth,
						task : "save_type"
					},
					type: 'POST',
					beforeSend: function(data){
						thistype.children(".scwatbwsr_roomtype_item_save").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
						jQuery(".scw_spin").remove();
						if(!data){
							alert("Saved!");
						}else
							alert("Error!");
					}
				});
			});
			thistype.children(".scwatbwsr_roomtype_item_del").on("click", function(){
				var r = confirm("This type will be delete, are you sure?");
				if(r == true){
					var thistypeid = thistype.children(".scwatbwsr_roomtype_item_id").val();
					jQuery.ajax({
						url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
						data: {
							thistypeid : thistypeid,
							task : "delete_type"
						},
						type: 'POST',
						beforeSend: function(data){
							thistype.children(".scwatbwsr_roomtype_item_del").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
						},
						success: function(data){
							jQuery(".scw_spin").remove();
							if(!data)
								thistype.remove();
							else
								alert(data);
						}
					});
				}else{
					return false;
				}
			});
		});
		
		///////////
		elthis.find('.scwatbwsr_schedules_spec_add_input').datetimepicker({
			format: jQuery(".scw_date_format").val()+' H:i',
			closeOnDateSelect: false,
			step: 5,
			defaultTime: "00:00"
		});
		elthis.find(".scwatbwsr_schedules_spec_button").on("click", function(){
			var schedule = elthis.find(".scwatbwsr_schedules_spec_add_input").val();
			if(schedule){
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId : roomId,
						schedule : schedule,
						task : "add_schedule"
					},
					type: 'POST',
					beforeSend: function(data){
						elthis.find(".scwatbwsr_schedules_spec_button").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
						jQuery(".scw_spin").remove();
						if(!data)
							alert("Added!");
						else
							alert(data);
					}
				});
			}
		});
		elthis.find(".scwatbwsr_schedules_spec_add_reload").on("click", function(){
			location.reload();
		});
		
		////////
		elthis.find(".scwatbwsr_schedules_spec_list_item").each(function(){
			var thische = jQuery(this);
			
			thische.children('.scwatbwsr_schedules_spec_list_item_schedule').datetimepicker({
				format: jQuery(".scw_date_format").val()+' H:i',
				closeOnDateSelect: false,
				step: 5,
				defaultTime: "00:00"
			});
			thische.children(".scwatbwsr_schedules_spec_list_item_save").on("click", function(){
				var scheid = thische.children(".scwatbwsr_schedules_spec_list_item_id").val();
				var thisschedule = thische.children(".scwatbwsr_schedules_spec_list_item_schedule").val();
				
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						scheid : scheid,
						thisschedule : thisschedule,
						task : "save_schedule"
					},
					type: 'POST',
					beforeSend: function(data){
						thische.children(".scwatbwsr_schedules_spec_list_item_save").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
						jQuery(".scw_spin").remove();
						if(!data)
							alert("Saved!");
						else
							alert("Error!");
					}
				});
			});
			///
			thische.children(".scwatbwsr_schedules_spec_list_item_delete").on("click", function(){
				var r = confirm("This schedule will be delete, are you sure?");
				if(r == true){
					var scheid = thische.children(".scwatbwsr_schedules_spec_list_item_id").val();
					jQuery.ajax({
						url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
						data: {
							scheid : scheid,
							task : "delete_schedule"
						},
						type: 'POST',
						beforeSend: function(data){
							thische.children(".scwatbwsr_schedules_spec_list_item_delete").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
						},
						success: function(data){
							jQuery(".scw_spin").remove();
							if(!data)
								thische.remove();
							else
								alert("Error!");
						}
					});
				}else{
					return false;
				}
			});
		});
		
		///////////
		elthis.find(".scwatbwsr_daily_schedules_week").each(function(){
			var thisweek = jQuery(this);
			thisweek.children("input").change(function(){
				var dailys = elthis.find(".scwatbwsr_daily_schedules_week > input:checked").map(function(){
					return jQuery(this).val();
				}).get();
				
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId : roomId,
						dailys : dailys,
						task : "change_daily"
					},
					type: 'POST',
					beforeSend: function(data){
						thisweek.append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
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
		elthis.find(".scwatbwsr_daily_schedules_times_add_button").on("click", function(){
			var scheduletime = elthis.find(".scwatbwsr_daily_schedules_times_add_input").val();
			
			if(scheduletime){
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId : roomId,
						scheduletime : scheduletime,
						task : "add_time"
					},
					type: 'POST',
					beforeSend: function(data){
						elthis.find(".scwatbwsr_daily_schedules_times_add_button").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
						jQuery(".scw_spin").remove();
						if(!data)
							alert("Added!");
						else
							alert(data);
					}
				});
			}
		});
		elthis.find(".scwatbwsr_daily_schedules_times_refresh_button").on("click", function(){
			location.reload();
		});
		
		elthis.find(".scwatbwsr_daily_schedules_times_list_item").each(function(){
			var thistime = jQuery(this);
			
			thistime.children(".scwatbwsr_daily_schedules_times_list_item_input").datetimepicker({
				datepicker: false,
				step: 5,
				format: "H:i"
			});
			thistime.children(".scwatbwsr_daily_schedules_times_list_item_button").on("click", function(){
				var thistimeid = thistime.children(".scwatbwsr_daily_schedules_times_list_item_id").val();
				var thistimetime = thistime.children(".scwatbwsr_daily_schedules_times_list_item_input").val();
				
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						thistimeid : thistimeid,
						thistimetime : thistimetime,
						task : "save_time"
					},
					type: 'POST',
					beforeSend: function(data){
						thistime.children(".scwatbwsr_daily_schedules_times_list_item_button").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
						jQuery(".scw_spin").remove();
						if(!data)
							alert("Saved!");
						else
							alert("Error!");
					}
				});
			});
			////
			thistime.children(".scwatbwsr_daily_schedules_times_list_item_delete").on("click", function(){
				var r = confirm("This time will be delete, are you sure?");
				if(r == true){
					var thistimeid = thistime.children(".scwatbwsr_daily_schedules_times_list_item_id").val();
					
					jQuery.ajax({
						url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
						data: {
							thistimeid : thistimeid,
							task : "delete_time"
						},
						type: 'POST',
						beforeSend: function(data){
							thistime.children(".scwatbwsr_daily_schedules_times_list_item_delete").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
						},
						success: function(data){
							jQuery(".scw_spin").remove();
							if(!data)
								thistime.remove();
							else
								alert("Error!");
						}
					});
				}else{
					return false;
				}
			});
		});
		
		////////////
		elthis.find(".scwatbwsr_prices_save").on("click", function(){
			var priceString = "";
			
			elthis.find(".scwatbwsr_prices_item").each(function(){
				var typeid = jQuery(this).children(".scwatbwsr_prices_item_typeid").val();
				var price = jQuery(this).children(".scwatbwsr_prices_item_price").val();
				var type = jQuery(this).children(".scwatbwsr_prices_item_type").val();
				
				if(priceString)
					priceString += "@"+typeid+"-"+price+"-"+type;
				else
					priceString += typeid+"-"+price+"-"+type;
			});
			
			if(priceString){
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						priceString: priceString,
						task : "save_price"
					},
					type: 'POST',
					beforeSend: function(data){
						elthis.find(".scwatbwsr_prices_save").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
						jQuery(".scw_spin").remove();
						if(!data)
							alert("Saved!");
						else
							alert("Error!");
					}
				});
			}
		});
		
		/////////////
		elthis.find(".scwatbwsr_tables_add_button").on("click", function(){
			var label = elthis.find(".scwatbwsr_tables_add_label").val();
			var seats = elthis.find(".scwatbwsr_tables_add_seats").val();
			var type = elthis.find(".scwatbwsr_tables_add_type").val();
			
			if(label && seats && type){
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId : roomId,
						label: label,
						seats: seats,
						type: type,
						task : "add_table"
					},
					type: 'POST',
					beforeSend: function(data){
						elthis.find(".scwatbwsr_tables_add_button").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
						jQuery(".scw_spin").remove();
						if(!data){
							alert("Added!");
						}else
							alert(data);
					}
				});
			}else{
				alert("Please enter information!");
			}
		});
		elthis.find(".scwatbwsr_tables_add_reload").on("click", function(){
			
			   window.location.href="admin.php?page=scwatbwsr-table-settings&tab="+jQuery(".scwatbwsr_room_content_tabs_label.active").attr("for")
		});
		
		elthis.find(".scwatbwsr_tables_list_item").each(function(){
			var thistable = jQuery(this);
			
			thistable.children(".scwatbwsr_tables_list_item_save").on("click", function(){
				var thistbid = thistable.children(".scwatbwsr_tables_list_item_id").val();
				var thistbseats = thistable.children(".scwatbwsr_tables_list_item_seats").val();
				var thistbtype = thistable.children(".scwatbwsr_tables_list_item_type").val();
				
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						thistbid : thistbid,
						thistbseats : thistbseats,
						thistbtype : thistbtype,
						task : "save_table"
					},
					type: 'POST',
					beforeSend: function(data){
						thistable.children(".scwatbwsr_tables_list_item_save").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
						jQuery(".scw_spin").remove();
						if(!data)
							alert("Saved!");
						else
							alert("Error!");
					}
				});
			});
			////
			thistable.children(".scwatbwsr_tables_list_item_del").on("click", function(){
				var r = confirm("This table will be delete, are you sure?");
				if(r == true){
					var thistbid = thistable.children(".scwatbwsr_tables_list_item_id").val();
					
					jQuery.ajax({
						url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
						data: {
							thistbid : thistbid,
							task : "delete_table"
						},
						type: 'POST',
						beforeSend: function(data){
							thistable.children(".scwatbwsr_tables_list_item_del").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
						},
						success: function(data){
							jQuery(".scw_spin").remove();
							if(!data)
								thistable.remove();
							else
								alert("Error!");
						}
					});
				}else{
					return false;
				}
			});
		});
		
		///////////////////
		elthis.find(".scwatbwsr_mapping_table").each(function(){
			var thistbmap = jQuery(this);
			thistbmap.draggable({
				containment: "parent",
				drag: function() {
					thistbmap.children('.topline').css('display', 'block');
					thistbmap.children('.rightline').css('display', 'block');
					thistbmap.children('.botline').css('display', 'block');
					thistbmap.children('.leftline').css('display', 'block');
				},
				start: function() {
					thistbmap.children('.topline').css('display', 'block');
					thistbmap.children('.rightline').css('display', 'block');
					thistbmap.children('.botline').css('display', 'block');
					thistbmap.children('.leftline').css('display', 'block');
				},
				stop: function() {
					thistbmap.children('.topline').css('display', 'none');
					thistbmap.children('.rightline').css('display', 'none');
					thistbmap.children('.botline').css('display', 'none');
					thistbmap.children('.leftline').css('display', 'none');
				}
			});
			
			thistbmap.find(".scwatbwsr_mapping_table_seat").each(function(){
				jQuery(this).draggable({
					containment: "parent"
				});
			});
		});
		
		/////////////
		elthis.find(".scwatbwsr_mapping_preview_save").on("click", function(){
			var tbstring = "";
			var seatstring = "";
			
			elthis.find(".scwatbwsr_mapping_table").each(function(){
				var thistb = jQuery(this);
				var tbid = thistb.children(".scwatbwsr_mapping_table_id").val();
				var tbleft = thistb.position().left;
				var tbtop = thistb.position().top;
				
				if(tbstring)
					tbstring += "@"+tbid+"#"+tbleft+"#"+tbtop;
				else
					tbstring += tbid+"#"+tbleft+"#"+tbtop;
				
				var seatdt = "";
				thistb.find(".scwatbwsr_mapping_table_seat").each(function(){
					var seatlb = jQuery(this).text().trim();
					var sleft = jQuery(this).position().left;
					var stop = jQuery(this).position().top;
					
					if(seatdt)
						seatdt += "&"+seatlb+"$"+sleft+"$"+stop;
					else
						seatdt += seatlb+"$"+sleft+"$"+stop;
				});
				if(seatstring)
					seatstring += "@"+tbid+"#"+seatdt;
				else
					seatstring += tbid+"#"+seatdt;
			});
			
			jQuery.ajax({
				url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
				data: {
					tbstring: tbstring,
					seatstring: seatstring,
					task: "save_mapping"
				},
				type: 'POST',
				beforeSend: function(data){
					elthis.find(".scwatbwsr_mapping_preview_save").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
				},
				success: function(data){
					jQuery(".scw_spin").remove();
					if(!data)
						alert("Saved!");
					else
						alert("Error!");
				}
			});
		});
		
		////////////////
		elthis.find(".scwatbwsr_room_head_copy").on("click", function(){
			var newname = prompt("[Copy] Please enter new Room name", "");
			if(newname){
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						newname : newname,
						roomId: roomId,
						task : "copy_room"
					},
					type: 'POST',
					beforeSend: function(data){
						elthis.find(".scwatbwsr_room_head_copy").append(' <i class="fa fa-refresh fa-spin scwspin"></i>');
					},
					success: function(data){
						jQuery(".scwspin").remove();
						if(!data)
							location.reload();
						else
							alert(data);
					}
				});
			}
		});
		
		/////////////
		elthis.find(".scwatbwsr_room_head_delete").on("click", function(){
			var r = confirm("This room will be delete, are you sure?");
			if(r == true){
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId : roomId,
						task : "delete_room"
					},
					type: 'POST',
					beforeSend: function(data){
						elthis.find(".scwatbwsr_room_head_delete").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
						jQuery(".scw_spin").remove();
						if(!data)
							elthis.remove();
						else
							alert(data);
					}
				});
			}else{
				return false;
			}
		});
		
		/////////////
		elthis.find(".scwatbwsr_orders_item").each(function(){
			var thisorder = jQuery(this);
			
			thisorder.children(".scwatbwsr_orders_item_del").on("click", function(){
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						oid : thisorder.children(".scwatbwsr_orders_item_oid").val(),
						task : "delete_order"
					},
					type: 'POST',
					beforeSend: function(data){
						thisorder.children(".scwatbwsr_orders_item_del").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
						jQuery(".scw_spin").remove();
						if(!data)
							thisorder.remove();
						else
							alert(data);
					}
				});
			});
		});
		
		//////////
		
			var thiss = elthis.find(".scwatbwsr_bktables_seat")
			
			jQuery(".scwatbwsr_bktables_seat_make_input").change(function(){
				jQuery.ajax({
					url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
					data: {
						roomId : roomId,
						seat : jQuery(this).val(),
						task : "make_as_booked"
					},
					type: 'POST',
					beforeSend: function(data){
						thiss.children(".scwatbwsr_bktables_seat_make").append(' <i class="fa fa-refresh fa-spin scw_spin"></i>');
					},
					success: function(data){
						jQuery(".scw_spin").remove();
						if(data) alert(data);
					}
				});
			});
		
		
		
	});
})(jQuery);

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
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