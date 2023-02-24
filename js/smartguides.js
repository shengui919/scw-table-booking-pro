jQuery(document).ready(function($){
	$(".scwatbwsr_mapping_table").draggable({
        containment: ".scwatbwsr_mapping_preview",
        drag: function() {
            $(this).find($('.topline')).css('display', 'block');
            $(this).find($('.rightline')).css('display', 'block');
            $(this).find($('.botline')).css('display', 'block');
            $(this).find($('.leftline')).css('display', 'block');
        },
        start: function() {
            $(this).find($('.topline')).css('display', 'block');
            $(this).find($('.rightline')).css('display', 'block');
            $(this).find($('.botline')).css('display', 'block');
            $(this).find($('.leftline')).css('display', 'block');
        },
        stop: function() {
            $(this).find($('.topline')).css('display', 'none');
            $(this).find($('.rightline')).css('display', 'none');
            $(this).find($('.botline')).css('display', 'none');
            $(this).find($('.leftline')).css('display', 'none');
        }
    });
});