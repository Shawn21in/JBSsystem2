var ShopCart_Url = 'web_ShopCart_post.php';

//商品內頁 - 加入購物車
function add_cart( ID , Type ){
	
	var count = $("#pro_count").val();
	var unit = $("#selectUnit").find("option:selected").attr("id");
	if(checkin(count) == ''){
		
		alert('請輸入數量');
		return false;	
	}
	// alert(unit);
	var FormData = 'id=' + ID + '&count=' + count + '&_type=add_cart' + '&unit=' + unit;
	
	if( Type == 'NTC' ) {
		FormData += "&href_type=NTC";
	}
	
	Post_JS(FormData, ShopCart_Url);
}


function chg_cart( ID , Unit , Type ){
	
	_this = $("#pro_count_"+ID);
	
	var _old_count = parseInt( _this.attr('data-count') );
	
	if( Type == 'add' ) {
		
		_new_count = _old_count + 1;
	}else if( Type = 'less'){
		
		_new_count = _old_count - 1;
	}

	if( !/^\d+$/.test(_new_count) || _new_count <= 0 ){
		
		alert("請輸入正確的數字");
		_this.val(_old_count);
		return	false;
	}
	
	var FormData = 'id=' + ID + '&count=' + _new_count + '&unit=' + Unit + '&_type=chg_cart';
	
	Post_JS(FormData, ShopCart_Url);
}

function chg_cartx(_this, ID, Unit, Type) {

	var _old_count = $("." + _this).val();
	var _new_count = document.getElementById(_this).value;


	if (!/^\d+$/.test(_new_count) || _new_count == 0) {
		$('#' + _this).val(_old_count);
		alert("請輸入正確的數字");

		return false;
	}

	var FormData = 'id=' + ID + '&unit=' + Unit + '&count=' + _new_count + '&_type=' + Type;


	Post_JS(FormData, ShopCart_Url);
}

function del_cart( ID, Unit ){
	
	if( confirm('確定刪除此商品?') ){
		
		var FormData = 'id=' + ID + '&unit=' + Unit + '&_type=del_cart';
	
		Post_JS(FormData, ShopCart_Url);
	}
}

function cancel_order( ID ){
	
	if( confirm('確定取消 ' +ID+ ' 該筆訂單?') ){
		
		var FormData = 'id=' + ID + '&_type=cancel_order';
	
		Post_JS(FormData, ShopCart_Url);
	}
}

//動態更新會員運費
function chg_delivery( sel_delivery , OrderID ){
	
	if( checkin(sel_delivery) == '' ){
		
		alert("請選擇付款方式");
		return false;
	}else{
		
		var FormData = 'delivery=' + sel_delivery;
		FormData += '&id=' + OrderID;
		FormData += '&_type=chg_delivery';
		 
		Post_JS(FormData, ShopCart_Url);
	}
}

$(document).ready(function(e) {
	
	$('.cart1_submit').click(function() {
		
		var auth 			= $(this).attr("data-auth");
		var delivery_type 	= $("#delivery_type").val();
		
		
		if( checkin(delivery_type) == '' ){
			
			alert("請選擇付款方式");
			return false;
		}else{
			
			if( checkin(auth) == '' ){
				
				location.href= "cart2.php";
			}else{
				
				location.href= "cart2.php?c="+auth;
			}
		}
	});
	
	$('.cart2_submit').click(function() {
		
		var field = '#'+$(this).closest('form').attr('id');
		
		if( !$('input[name=chk_privacy]').prop("checked") && $(this).attr('data-member') == 'noM') {
			
			alert('請勾選同意隱私權政策');
			$('input[name=chk_privacy]').focus();
			return false;
		} else {

			if( CheckInput(field) ){
				
				var FormData 	= '';
				var _name 		= $("#recevice_name").val();
				var _city 		= $("#recevice_city").val();
				var _county 	= $("#recevice_county").val();
				var _address 	= $("#recevice_address").val();
				
				msg	= "即將送出訂單請確認以下資訊\n1.收件人姓名 ：　"+_name+"\n2.收件人地址 ：　"+_city+_county+_address+"\n是否正確?";
				
				FormData += $(field).serialize();
				FormData += '&_type=save_cart';
				
				if (confirm(msg)==true){ 
				
					Post_JS(FormData, ShopCart_Url);
				}else{ 
					return false; 
				} 
				
				
			}	
		}
		
	});
	
	
});