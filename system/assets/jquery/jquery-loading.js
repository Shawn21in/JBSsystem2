(function(jQuery) {

	jQuery.fn.Loading = function(options) {
	
		//預設設定
		var defaults =
		{
			_action : false,	//關閉讀取畫面
		};

		options = jQuery.extend(defaults, options);
		
		var box = $('.jquery-loading');
		
		//if( !box.size() ){
		if( !box.length ){
			
			var content = '<div class="jquery-loading"></div>';
			
			$('body').prepend(content);
			
			box = $('.jquery-loading');
			
			box.css({
				'width': '100%',
				'height': '0px',
				'position': 'absolute',
				'z-index': '99999',
				'top': '0',
				'left': '0',
				'background': 'url("images/loading_icon.gif") no-repeat',
				'background-position': '50% 50%',
				'background-repeat': 'no-repeat',
				'display': 'none'
			});
		}
		
		if( options._action == true ){
			
			reset_position();
			box.show();
			$('html').css('overflow', 'hidden');
		}else{
			
			box.hide();
			$('html').css('overflow', 'auto');
		}
		
		$(window).resize( function() {
			
			if( options._action == true ){
				
				reset_position();
			}
		});
		
		//讓BOX置中
		function reset_position(){
			
			var height, scrollTop, getPosTop;
			
			dheight 	= $(document).height();
			wheight		= $(window).height();
			scrollTop	= $(document).scrollTop();
			getPosTop	= wheight / 2 - 100;

			box.css({
				'background-position' : '50% ' + (scrollTop + getPosTop + 50) + 'px',
				'height': dheight+ 'px',
			});		
		}		
	};
})(jQuery);