function CheckInput( Field ){
	
	var _msg = _focus = '';
	var _type = _val = _min = _max = data = '';
	$(Field+' input').each(function(index, element) {
		
		_focus = $(this);
		
		if( _focus.attr('disabled') == false || _focus.attr('disabled') == null ){
			
			_msg	= checkin(_focus.attr('msg'));
			
			_type 	= checkin(_focus.attr('input-type'));
			_val  	= checkin(_focus.val());
			_min 	= checkin(_focus.attr('input-min'));
			_max 	= checkin(_focus.attr('input-max'));
			//_minlen	= checkin(_focus.attr('minlength'));
			//_maxlen	= checkin(_focus.attr('maxlength'));
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
						
						_msg = '請輸入 0 - 9 數字';
						return false;
					}else if( parseInt(_val) < _min && _min != '' ){
						
						_msg = '數字不能小於 ( ' + _min + ' )';
						return false;
					}else if( parseInt(_val) > _max && _max != '' ){
						
						_msg = '數字不能大於 ( ' + _max + ' )';
						return false;
					}
				}else{
					
					_focus.val(0);
				}
			}else if( _type == 'tel' ){
				
				if( _val != '' ){
					
					var r_number = /^[\-0-9]+$/;
					if( !r_number.test(_val) ){
						
						_msg = '請輸入 0 - 9 數字';
						return false;
					}else if( _val.length < _min && _min != '' ){
						
						_msg = '電話碼數過少';
						return false;
					}else if( _val.length > _max && _max != '' ){
						
						_msg = '電話碼數過多';
						return false;
					}
				}else{
					
					_focus.val('');
				}
			}else if(_type == 'ctel'){
				if( _val != '' ){
					
					var r_number = /^09\d{8}$/;
					if( r_number.test(_val) ){
						return true;
					}else{
						_msg = '行動電話格式錯誤';
						return false;
						
					}
					
				}else{
					
					_focus.val('');
				}
			}else if(_type == 'id'){
				if( _val != '' ){
					
					var r_number = /^[A-Z]{1}[1-2]{1}[0-9]{8}$/;
					if( r_number.test(_val) ){
						return true;
					}else{
						_msg = '身分證格式錯誤';
						return false;
						
					}
					
				}else{
					
					_focus.val('');
				}
				
			}else if(_type == 'time'){
				if( _val != '' ){
					
					var r_number = /^(0[0-9]|1[0-9]|2[0-3])[0-5][0-9]$/;
					if( r_number.test(_val) ){
						return true;
					}else{
						_msg = '時間格式錯誤(0000-2359)';
						return false;
						
					}
					
				}else{
					
					_focus.val('');
				}
				
			}else if( _type == 'repassword' ){
				
				var re_val = $('#' +_focus.attr('input-re-id')).val();
				
				if( _val.length < _min && _min != '' ){
						
					_msg = '密碼長度過少';
					return false;
				}else if( _val.length > _max && _max != '' ){
					
					_msg = '密碼長度過多';
					return false;
				}else if( _val != re_val ){
					
					_msg = '密碼與密碼確認不一樣';
					return false;
				}
			}else if( _type == 'checkbox' ){
				
				if( !_focus.prop('checked') ) return false;
			}else if( _val != '' ){
				
				if( _type == 'aznumber' ){//只能英文數字
					
					var r_test = /^[a-zA-Z0-9]+$/;
					
					if( !r_test.test(_val) ){
						
						_msg = '只能輸入英文數字';
						return false;
					}
				}else if( _type == 'email' ){
					
					var r_test = /^[0-9a-zA-Z]([-._]*[0-9a-zA-Z])*@[0-9a-zA-Z]([-._]*[0-9a-zA-Z])*\.+[a-zA-Z]+$/;
					
					if( !r_test.test(_val) ){
						
						_msg = '請輸入正確的email';
						return false;
					}
				}
			}
		
			_msg = '';
		}
	});
	
	if( _msg == '' ){
		
		$(Field+' select').each(function(index, element) {
			
			_focus = $(this);
			
			if( _focus.prop('disabled') == false ){
				
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
		
		$(Field+' textarea').each(function(index, element) {
			
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
		
		_focus.focus();
		
		alert(_msg);
		return false;
	}
	
	return true;
}

//判斷是否為空值
function checkin( val ){
	
	if( val == '' || val == null ){
		
		val = '';
	}
	
	return val;
}