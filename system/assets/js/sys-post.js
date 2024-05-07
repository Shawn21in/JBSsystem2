function Post_JS( FormData, ExecUrl, Field ){
	
	loading('open');
	
	$.post( ExecUrl, FormData 
		,function( data ){
			
			loading('close');
			
			if( data.html_boxtype == '' && data.html_boxtype != null ){
				
				data.html_boxtype = 1;
			}
						
			if( data.html_clear != '' && data.html_clear != null ){
				
				$.each( data.html_clear, function( key, value ) {
					$('#' + value).val('');
				});
			}
			
			if( data.html_msg != '' && data.html_msg != null ){
				
				$(".tc_box").BoxWindow({
					_msg: data.html_msg,
					_url: data.html_href,
					_eval: data.html_eval,
					_type: data.html_boxtype
				});
				
				return false;
			}
			
			if( data.html_href != '' && data.html_href != null ){
				
				window.location.href = data.html_href;
				return false;
			}

			if( data.html_content != '' && data.html_content != null ){
				
				$(Field).html(data.html_content);
				
				if( window.Reload_Tabs ){
					
					Reload_Tabs();//重新載入頁籤
				}
				
				return false;
			}
			
			if( data.html_eval != '' && data.html_eval != null ){
				
				setTimeout(data.html_eval, 1);
				return false;
			}
		},'json'
	);
}

function Ajax_Table( FormData, ExecUrl, Field ){
	
	loading('open');
	
	$.ajax({
		url: ExecUrl,
		data: FormData,
		type: "POST",
		dataType: 'json',
		mimeType:"multipart/form-data",
		contentType: false,
		cache: false,
		processData:false,
		success: function(data){
			
			loading('close');
			
			if( data.html_boxtype == 3 ){
				
				$(Field + ' .contents').html(data.html_content);
				/*Custombox.open({
					target: Field,
					effect: 'newspaper',
					//close: function(){
						
					//	Reload();	
					//},
					//width: 'full',
					//overlayEffect: 'makeway'
				});*/
				
				/*$(".tc_box").BoxWindow({
					_content: data.html_content,
					_type: 4
				});*/
				
				if( location.href.substr(-1) == '#' ){
					location.href = location.href+ 'modal'
				}else{
					location.href = location.href+ '#modal'
				}
				
				return false;
			}
			
			if( data.html_clear != '' && data.html_clear != null ){
				
				$.each( data.html_clear, function( key, value ) {
					$('#' + value).val('');
				});
			}
			
			if( data.html_msg != '' && data.html_msg != null ){
				
				$(".tc_box").BoxWindow({
					_msg: data.html_msg,
					_url: data.html_href,
					_eval: data.html_eval,
					_type: data.html_boxtype
				});
			}
			
			if( data.html_content != '' && data.html_content != null ){
				
				$(Field).html(data.html_content).show();
				
				if( data.html_type == 'Table_Edit' || data.html_type == 'Table_Copy' ){
					
					Reload_Tabs();//重新載入頁籤
				}

				return false;
			}
			
			if( data.html_href != '' && data.html_href != null && data.html_msg == '' ){
				
				window.location.href = data.html_href;
				return false;
			}
			
			if( data.html_eval != '' && data.html_eval != null && data.html_msg == '' ){
				
				setTimeout(data.html_eval, 1);
				return false;
			}
			
			if( ( data.html_type1 == 'delimg' || data.html_type1 == 'delfile' ) && data.html_msg == '' ){
				
				$('.ace-file-name', Field).attr('data-title', '無選取檔案 ...');
				$('.ace-thumbnails', Field).parent('div').remove();
			}else if( data.html_type1 == 'delimgs' && data.html_msg == '' ){
				
				Field.remove();
			}
		},
		error:function(xhr, ajaxOptions, thrownError){ 
			
			loading('close');
			
			$(".tc_box").BoxWindow({
				_msg: thrownError,
			});
		}
	});
}

function Ajax_Chk( _this ){
		
	var msg		 		= '';
	var type	 		= _this.attr('check-type') ? _this.attr('check-type') : '';//資料第一種類
	var id	  	 		= _this.attr('check-id') ? _this.attr('check-id') : '';//主鍵
	var field			= _this.attr('check-field') ? _this.attr('check-field') : '';//更新欄位
	var data_new 		= _this.val() ? _this.val() : '';//資料值
	var data_old 		= _this.attr('check-data') ? _this.attr('check-data') : '';//資料舊值
	var sn 		 		= _this.attr('check-sn') ? _this.attr('check-sn') : '';//資料序號
	var datatype 		= _this.attr('check-datatype') ? _this.attr('check-datatype') : '';//資料第二種類
	
	var Min 	 		= _this.attr('check-min') ? _this.attr('check-min') : '';//資料最小值
	var Max 	 		= _this.attr('check-max') ? _this.attr('check-max') : '';//資料最大值
	
	var name 	 		= _this.attr('check-name') ? _this.attr('check-name') : '';//資料名稱
	
	var connectfield 	= _this.attr('check-connectfield') ? _this.attr('check-connectfield') : '';//相互作用欄位
	
	if( datatype == 'datestart' && connectfield != '' ){
		
		var datatime	= $('#'+connectfield).val();
		var name2		= checkin($('#'+connectfield).attr('check-name'));
		if( data_new > datatime && datatime != 0 ){
			
			msg = name+'要小於'+name2;
		}
	}else if( datatype == 'dateend' && connectfield != '' ){
		
		var datatime 	= $('#'+connectfield).val();
		var name2		= checkin($('#'+connectfield).attr('check-name'));
		if( data_new < datatime ){
			
			msg = name+'要大於'+name2;
		}
	}else if( type == 'number' ){
		
		var r_number = /^[\-0-9]+$/;
		if( !r_number.test(data_new) ){
						
			msg = '請輸入數字';
		}else if( parseInt(data_new) < Min && Min != '' ){
			
			msg = '數字不能小於 ( ' + Min+ ' )';
		}else if( parseInt(data_new) > Max && Max != '' ){
			
			msg = '數字不能大於 ( ' + Max + ' )';
		}
	}else if( type == 'checkbox' || type == 'checkboxs' ){
		
		data_new = _this.prop('checked');//資料值
		data_old = data_new ? false : true;//資料值
	}
			
	if( msg != '' ){//錯誤訊息印出
		
		if( type != 'checkbox' ){
			
			_this.val(data_old);
		}
		
		$(".tc_box").BoxWindow({
			_msg: msg,//訊息
			//_focus: _this
		});
	}else if( data_new != data_old ){//不一樣值才執行
		
		if( ( checkin(id) != '' && checkin(field) != '' ) || checkin(type) == 'norepeat' ){//有主鍵和欄位才執行或者判斷不重複
		
			var Form_Data = new FormData();
	
			Form_Data.append('_type', 'Table_Data_Change');
			Form_Data.append('_type1', type);
			Form_Data.append('id', id);
			Form_Data.append('field', field);
			Form_Data.append('data', data_new);
			
			loading('open');
			
			$.ajax({
				url: Exec_Url,
				data: Form_Data,
				type: "POST",
				dataType: 'json',
				mimeType:"multipart/form-data",
				contentType: false,
				cache: false,
				processData:false,
				success: function(data){
					
					loading('close');
					
					if( data.html_msg != '' ){
					
						$(".tc_box").BoxWindow({
							_msg: data.html_msg,
						});
						
						_this.val(data_old);
					}else{
						
						if( type != 'norepeat' ){
							
							_this.attr('check-data', data_new);
						}
					}
					
					if( data.html_eval != '' && data.html_eval != null ){
				
						setTimeout(data.html_eval, 1);
					}
				},
				error:function(xhr, ajaxOptions, thrownError){ 
					
					loading('close');
					
					$(".tc_box").BoxWindow({
						_msg: thrownError,
					});
				}
			});
		}
	}
}
