function CheckInput( _this ){
	
	var _msg = _focus = '';
	var _type = _val = _min = _max = _data = '';
	
	//if( _this.size() > 0 ){
	if( _this.length > 0 ){
		
		$('input', _this).each(function(index, element) {
			
			_focus = $(this);
			
			if( _focus.attr('disabled') == false || _focus.attr('disabled') == null ){
				
				_msg	= checkin(_focus.attr('msg'));
				
				_type 	= checkin(_focus.attr('input-type'));
				_name	= checkin(_focus.attr('input-name'));
				_val  	= checkin(_focus.val());
				_min 	= checkin(_focus.attr('input-min'));
				_max 	= checkin(_focus.attr('input-max'));
				_data 	= checkin(_focus.attr('input-data'));
				
				if( _msg != '' && _val == '' ){
					
					return false;
				}else if( _type == 'number' ){
					
					if( _val != '' ){
						
						var r_number = /^[\-0-9]+$/;
						if( !r_number.test(_val) ){
						//_val = parseInt(_val);
						//alert(_val);
						//if( isNaN(_val) ){
							
							_msg = _name+'( 請輸入 0 - 9 數字 )';
							return false;
						}else if( parseInt(_val) < _min && _min != '' ){
							
							_msg = _name+'( 數字不能小於 ' +_min+ ' )';
							return false;
						}else if( parseInt(_val) > _max && _max != '' ){
							
							_msg = _name+'( 數字不能大於 ' +_max+ ' )';
							return false;
						}
					}else{
						
						_focus.val(0);
					}
				}else if( _type == 'repassword' ){
					
					var re_this = $('#' +_focus.attr('input-re-id'));
					
					var re_msg	= checkin(re_this.attr('msg'));
					var re_name	= checkin(re_this.attr('input-name'));
					var re_val 	= checkin(re_this.val());
					
					if( re_val == '' ){
						
						_focus 	= re_this;
						_msg 	= re_msg;
						return false;
					}else if( _val.length < _min && _min != '' ){
							
						_msg = _name+ '( 長度最少' +_min+ '碼 )';
						return false;
					}else if( _val.length > _max && _max != '' ){
						
						_msg = _name+ '( 長度最多' +_max+ '碼 )';
						return false;
					}else if( _val != re_val ){
						
						_msg = _name+'與' +re_name+ '不一樣';
						return false;
					}
				}else if( _val != '' ){
					
					if( _type == 'aznumber' ){//只能英文數字
						
						var r_test = /^[a-zA-Z0-9]+$/;
						
						if( !r_test.test(_val) ){
							
							_msg = _name+'只能輸入英文數字';
							return false;
						}
					}else if( _type == 'email' ){
						
						var r_test = /^[0-9a-zA-Z]([-._]*[0-9a-zA-Z])*@[0-9a-zA-Z]([-._]*[0-9a-zA-Z])*\.+[a-zA-Z]+$/;
						
						if( !r_test.test(_val) ){
							
							_msg = _name+'請輸入正確的email';
							return false;
						}
					}
				}
			
				_msg = '';
			}
		});
	}else{
		
		_msg = '找不到元素';
	}
	
	if( _msg == '' ){
		
		$('select', _this).each(function(index, element) {
			
			_focus = $(this);
			
			if( _focus.attr('disabled') == false || _focus.attr('disabled') == null ){
				
				_msg = checkin(_focus.attr('msg'));
				
				_val = checkin(_focus.val());
				
				if( _msg != '' && ( _val == '' || _val == 0 ) ){
					
					return false;
				}
				
				_msg = '';
			}
		});
	}
	
	if( _msg == '' ){
		
		$('textarea', _this).each(function(index, element) {
			
			_focus = $(this);
			
			if( _focus.attr('disabled') == false || _focus.attr('disabled') == null ){
				
				_msg = checkin(_focus.attr('msg'));
				
				_val = checkin(_focus.val());
				
				if( _msg != '' && ( _val == '' || _val == 0 ) ){
					
					return false;
				}
				
				_msg = '';
			}
		});
	}
	
	if( _msg != '' ){
		
		if( _data != '' ){
			
			_focus.val(_data);
		}
		
		$(".tc_box").BoxWindow({
			_msg: _msg,
			_focus: _focus
		});
		return false;
	}
	
	return true;
}