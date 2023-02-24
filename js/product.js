(function($) {
"use strict";

	var proid = jQuery(".scwatbwsr_proid").val();
	
	jQuery(".scwatbwsr_select_profile").on("change", function(){
		var vl = jQuery(this).val();
		
		jQuery.ajax({
			url: "../wp-content/plugins/scw-table-booking-pro/helper.php",
			data: {
				proid : proid,
				vl : vl,
				task : "save_product_profile"
			},
			type: 'POST',
			beforeSend: function(data){
				jQuery(".scwatbwsr_content").css('opacity', '0.5');
			},
			success: function(data){
				jQuery(".scwatbwsr_content").css('opacity', '1');
				if(!data)
					location.reload();
				else
					alert("Error!");
			}
		});
	});
	
})(jQuery);