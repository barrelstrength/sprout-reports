;(function($){
	$.fn.animateAutoHeight = function(){
	
		var	curHeight = this.css('height'),
			height = this.css('height','auto').height(),
			duration = 200,
			easing = 'swing',
			callback = $.noop,
			parameters = { height: height };
			
		this.css('height', curHeight);
		
		for (var i in arguments) {
			switch (typeof arguments[i]) {
				case 'object':
					parameters = arguments[i];
					parameters.height = height;
					break;
				case 'string':
					if (arguments[i] == 'slow' || arguments[i] == 'fast') duration = arguments[i];
					else easing = arguments[i];
				break;
				case 'number': duration = arguments[i]; break;
				case 'function': callback = arguments[i]; break;
			}
		}
		this.animate(parameters, duration, easing, function() {
			$(this).css('height', 'auto');
			callback.call(this, arguments);
		});
		return this;
	}
})(jQuery);


$(document).ready(function() {
	$('.tablecontent').hover(
		function() {
			$('.tablefade').fadeOut( 500 ).addClass('hide-fade');
		}, function() {
			$('.tablefade').fadeIn( 500 ).removeClass('hide-fade');
	});
	
	$('.table_row').click(function () {
		if($(this).hasClass('expanded')) {
			$(this).children().find(".item_content").animate({ height : '40px'}, {queue: false});
			$(this).removeClass('expanded');
		} else {
			$(this).children().find(".item_content").animateAutoHeight();
			$(this).addClass('expanded');
		}
	});
});