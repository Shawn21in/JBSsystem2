(function(jQuery) {

	jQuery.fn.YTvideo = function(options) {
	
		//預設設定
		var defaults =
		{
			width :				'',		//視窗寬度
			height :			'',		//視窗高度
			imgsize :			0, 		// 設定要取得的預覽圖的尺寸, 有寬高為 0 => 480X360 及寬高為 1 => 120X90 兩種
			enablejsapi :		1,		// 是否啟用JS的互動, 1: 啟用, 0:不啟用
			autoplay :			1,		// 是否自動播放, 1: 自動, 0:不自動
			modestbranding :	0, 		// 是否隱藏在播放控制器上的 Youtube logo(不過右下角的還是會有), 1: 顯示, 0: 隱藏
			showinfo :			0,		// 是否隱藏在影片上方的標題列, 1: 顯示, 0: 隱藏
			rel :				0,		// 是否隱藏在影片上方的 Share 及 More Info功能, 1: 顯示, 0: 隱藏
			controls :			1,		// 是否隱藏影片下方的控制列, 1: 顯示, 0: 隱藏
			allowfullscreen :   1,		// 是否啟用全螢幕, 1: 啟用, 0:不啟用
			checkvideo : 		1,		// 是否啟用檢查影片, 1: 啟用, 0:不啟用
			checkspeed :        1000	// 多久檢查影片一次
		};

		options = jQuery.extend(defaults, options);
				
		jQuery(this).each(function(index, element) {
            
			var $this		= $(this);
			var code 		= $this.attr('yt-code');
			var title		= $this.attr('yt-title') != null ? $this.attr('yt-title') : '';
			var url  		= 'http://www.youtube.com/v/' +code;
			
			var embedId 	= 'myytplayer_' +index;
			var playerId 	= 'ytapiplayer_' +index; 
			var player, timer;
			
			var imgurl 		= 'http://img.youtube.com/vi/' +code+ '/' +options.imgsize+ '.jpg';

			var content		= '';
				
			content	+= '<div id="' +playerId+ '" class="ytvideo">';
			
			content	+= '	<img src="' +imgurl+ '" alt="' +title+ '" title="' +title+ '"';
			if( options.width != '' ){
				
				content += ' width="' +options.width+ '"';
			}
			
			if( options.height != '' ){
				
				content += ' height="' +options.height+ '"';
			}
			content	+= '	/>';
			
			content	+= '	<div class="play" title="' +title+ '"></div>';
			content	+= '</div>';
			
			$this.prepend(content).on('click', '.play', function(){
				
				clearInterval(timer);
				
				var object = '';
				
				object	= '<object id="' +embedId+ '" type="application/x-shockwave-flash" data="' +url+ '?playerapiid=' +playerId+ '"';
				
				if( options.width != '' ){
					
					object += ' width="' +options.width+ '"';
				}
				
				if( options.height != '' ){
					
					object += ' height="' +options.height+ '"';
				}
				
				if( options.allowfullscreen ){
					
					object += ' allowfullscreen="true"';
				}
				object	+= '>';
				
				object	+= '	<param name="allowScriptAccess" value="always">';
				object	+= '	<param name="flashvars" value="enablejsapi=' +options.enablejsapi+ '&autoplay=' +options.autoplay+ '&modestbranding=' +options.modestbranding+ '&showinfo=' +options.showinfo+ '&rel=' +options.rel+ '&controls=' +options.controls+ '">';
				object	+= '</object>';
				
				
				//object = '<object id="myytplayer_2" type="application/x-shockwave-flash" width="480" height="360"  data="http://www.youtube.com/v/tHwVRqRKaok?playerapiid=ytapiplayer_2" allowfullscreen="true"><param name="allowScriptAccess" value="always"><param name="flashvars" value="enablejsapi=0&autoplay=1&modestbranding=0&showinfo=0&rel=0&controls=1"></object>';
				$this.html(object);
				
				timer = setInterval(checkStatechange, options.checkSpeed);
				
				return false;
			});
			
			replay_position();
			
			$(window).resize( function() {
			
				replay_position();
			});
				
			// 監視 YouTube 影片播放狀態
			function checkStatechange(){
				
				if( ( player = $('#' + embedId)[0] ) == undefined ) return;
				
				try{
					
					var currentState = player.getPlayerState();
					// 如果已經播放完畢
					if( currentState == '0' ){
						
						clearInterval(timer);
						$this.html(content);
						replay_position();
					}
				}catch(err){}
			}
			
			//讓播放按鈕置中
			function replay_position(){
				
				var height, scrollTop, getPosTop;
				
				var retop   = $this.find('img').height() / 2 - 25;
				var releft	= $this.find('img').width() / 2 - 25;
				
				$this.find('.play').css({
					'top': retop,
    				'left': releft
				});
			}
        });
	};
})(jQuery);