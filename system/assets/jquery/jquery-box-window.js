(function(jQuery) {

	jQuery.fn.BoxWindow = function(options) {
	
		//預設設定
		var defaults =
		{
			_open :			false,	//開啟彈出視窗功能
			_msg :			'',		//訊息
			_content :		'',		//內容
			_url :			'',		//前往網址
			_type :			1,		//1=>只有確定按鈕,2=>有確定取消按鈕
			_focus :		'', 	//錨點
			_eval :			''		//執行FUNCTION
		};

		options = jQuery.extend(defaults, options);
		
		var box_type_str = '';
		var tbox 	 = jQuery(this);
		var sbox	 = $('.sp_box');
		var box_type = $('.box_type');
		
		tbox.unbind();//移除事件
		
		if( !options._open ){
		
			if( options._type == 1 ){
				
				box_type_str += '<button type="button" class="overbutton button width-30 btn btn-sm btn-primary">';
				box_type_str +=		'<span>確定</span>';
				box_type_str += '</button>';
			}else if( options._type == 2 ){
				
				box_type_str += '<button type="button" class="overbutton button width-20 btn btn-sm btn-primary">';
				box_type_str +=		'<span>確定</span>';
				box_type_str += '</button>';
				
				box_type_str += '<button type="button" class="button-cancel button width-20 btn btn-sm btn-primary">';
				box_type_str +=		'<span>取消</span>';
				box_type_str += '</button>';
			}else if( options._type == 3 ){
				
				box_type_str += '<button type="button" class="overbutton button width-30 btn btn-sm btn-primary">';
				box_type_str +=		'<span>關閉</span>';
				box_type_str += '</button>';
			}else if( options._type == 4 ){
				
				box_type_str += options._content;
			}
				
			box_type.html(box_type_str);	
			
			$('.textview', tbox).html(options._msg);
			
			tbox.show();
			sbox.show();
	
			//if( options._focus == 'this' ){
				
				if( options._type == 1 || options._type == 3 ){
					
					$('.overbutton').focus();
				}else{
					
					$('.button-cancel').focus();
				}
			//}else{
				
				//options._focus.focus();
			//}
			
			if( options._type == 1 || options._type == 3 || options._type == 4 ){
			
				$('.overbutton, .overlay-close').click( function() {
							
					box_true(options);
				});
			}
			
			if( options._type == 2 ){
				
				$('.overbutton').click( function() {
							
					box_true(options);
				});
				
				$('.overlay-close, .button-cancel').click( function() {
				
					tbox.hide();
					sbox.hide();
				});
			}
			
			tbox.keydown( function(event) { 
				
				if( event.keyCode == 13 && tbox.css('display') == 'block' ){ 
						
					box_true(options);
				}
				
				if( event.keyCode == 27 && tbox.css('display') == 'block' ){ 
				
					if( options._type == 1 ){
						
						box_true(options);
					}else{
				
						tbox.hide();
						sbox.hide();
					}
				}
			});
		}else{
			
			box_resize();
			$(window).resize( function() {
		
				box_resize();
			});
			
			$(window).scroll( function() {
				
				box_resize();
			});
		}
		
		//確定
		function box_true(options){
			
			if( options._url != '' && options._url != null ){
						
				window.location.href = options._url;
			}else if( options._eval != '' && options._eval != null ){
				
				setTimeout(options._eval, 1);
				//eval(options._eval);
			}
			
			if( options._focus != '' && options._focus != null ){
				
				options._focus.focus();
			}
		
			tbox.hide();
			sbox.hide();
		}
				
		//讓BOX置中
		function box_resize(){
			
			var width, height, scrollTop, getPosLeft, getPosTop
			
			width  		= $(window).width();
			height 		= $(window).height();
			scrollTop	= $(document).scrollTop();
			//getPosLeft   = screenwidth/2 - 300;
			getPosTop	= height / 2 - 100;
			//$(".tc_box").css({"left":getPosLeft,"top":getPosTop});
			tbox.css({'width' : width, 'top' : getPosTop});
			sbox.css({'height' : height + scrollTop, 'background-position' : '50% ' + (scrollTop + getPosTop + 50) + 'px'});
		}
	};

})(jQuery);