var pathArray = window.location.pathname.split( '/' );
var pathLocation = pathArray[3];

if (pathLocation == "results") {

	function getContentHeight() {
		var viewHeight = $( window ).height();
		
		var viewHeight = (viewHeight/10)*6.5;
		
		if (viewHeight > 500) {
		
			$(".tablecontent").css({
				"height": viewHeight
			});
			var posX = api.getContentPositionX();
			
			setTimeout(function(){api.scrollToX(0, 0)}, 100);
			setTimeout(function(){api.reinitialise()}, 400);
			
			
		} else {
			$(".tablecontent").css({
				"height": "500px"
			});
		}
	}
	
	var element = $('.tablecontent').jScrollPane({
		showArrows: true,
		hideFocus: true
	});
	
	var api = element.data('jsp');
	var horizontalAmmt = api.getContentPositionX();
	var horizontalAmmt = -horizontalAmmt;
	var dateHeight = $("td.date").innerHeight();
	
	function formatTable() {
		var panePaddingLeft = $('.pane').css('padding-left');
		var panePaddingRight = $('.pane').css('padding-right');
		var panePaddingBottom = $('.pane').css('padding-bottom');
		
		$('#content').css({
			'margin-left' : '-' + panePaddingLeft,
			'margin-right' : '-' + panePaddingRight,
			'margin-bottom' : '-' + panePaddingBottom,
			'overflow' : 'hidden'
		});
		
	}
	
	$(function() {
		$('.tablecontent').bind({
			'jsp-scroll-x': function(event, scrollPositionX, isAtLeft, isAtRight) {
			
				
	
				$(".left-border").css({
					"left" : scrollPositionX
					
				});
				
				
				
			},
			
			'jsp-scroll-y': function(event, scrollPositionY, isAtTop, isAtBottom) {
				$("th div").css({
					"top" : scrollPositionY
				});
				
			}
		})
	
	
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
	});
	
	
	$(document).ready(function() {
		
		
		
		
		
		getContentHeight();
		formatTable();
		
		$("th > div").each(function() {
			var divWidth = $(this).parent("th").innerWidth();
			var divHeight = $(this).parent("th").innerHeight();
			
			$(this).css({
				"width" : divWidth,
				"height": divHeight
			});
			
		});
		
		
		$('.table_row').click(function () {
			if($(this).hasClass('expanded')) {
				$(this).children().find(".item_content").animate({ height : '40px'}, {queue: false});
				$(this).removeClass('expanded');
				
				
			} else {
				$(this).children().find(".item_content").animateAutoHeight();
				$(this).children(".left-border").css({height : "100%"});
				$(this).addClass('expanded');
			}
				var contentPosX = api.getContentPositionX();
							
				setTimeout(function(){api.scrollToX(0, 0)}, 100);
				setTimeout(function(){api.reinitialise()}, 100);
				
				setTimeout(function(){api.scrollToX(contentPosX, 0)}, 100);
		});
		
		
	});
	
	
	$( window ).resize(function() {
		getContentHeight();
		
		
	});
}