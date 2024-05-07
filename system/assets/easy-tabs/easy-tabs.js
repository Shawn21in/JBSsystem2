//-------------------------------------------------------------//
//重新載入頁籤
function Reload_Tabs(){
	
	var _showTab = 0;
	$('.abgne_tab').each(function(){
		
		// 目前的頁籤區塊
		var $tab = $(this);
	
		$('ul.tabs li', $tab).eq(_showTab).addClass('active');
		$('.tab_content', $tab).hide().eq(_showTab).show();
	
		$('ul.tabs li', $tab).click(function() {
	
			var $this = $(this),
			_clickTab = $this.find('a').attr('for-id');
			$this.addClass('active').siblings('.active').removeClass('active');
			$('#'+_clickTab).fadeIn().siblings().hide();
	
			return false;
		}).find('a').focus(function(){
			
			this.blur();
		});
	});
}