$(document).ready(function(e) {
//-------------------------------------------------------------//
//目錄開合效果
	$('#menu_content .icon').click( function(){
		
		var op = $(this).attr('data-op');
		
		if( op == 0 ){
			
			menu_sh($(this), 1);
			$(this).siblings('ul').fadeIn();
		}else{
			
			menu_sh($(this), 0);
			$(this).siblings('ul').fadeOut();
		}
	});
//目錄全開
	$('.menu_show').click( function(){
		
		$('#menu_content ul').show();
		
		$('#menu_content .icon').each(function(index, element) {
            
			menu_sh($(this), 1);
        });
	});
//目錄全縮	
	$('.menu_hide').click( function(){
		
		$('#menu_content ul').hide();
		
		$('#menu_content .icon').each(function(index, element) {
            
			menu_sh($(this), 0);
        });
	});	
//新增層級
	$('.menu_create').click( function(){
		
		var content = '';
		
		var lv 		= $(this).attr('mlv');
		
		var pre		= $('.tablepre').val();
		
		if( lv == 1 ){//主類別
			
			var Obj	   	= $('input[name="' +pre+ '_Name[]"]').first();
			var Obj2	= $('input[name="' +pre+ '_Sort[]"]').first();
		}else{
			
			var UpMID  	= $(this).siblings('input[name="' +pre+ '_ID[]"]').val();
			var Obj	   	= $(this).siblings('input[name="' +pre+ '_Name[]"]');
			var Obj2	= $(this).siblings('input[name="' +pre+ '_Sort[]"]');	
		}
		
		var Class  	= Obj.attr('class');
		var Class2 	= Obj2.attr('class');
		var e_txt  	= Obj.attr('e-txt');
		var e_txt2 	= Obj2.attr('e-txt');
		var maxlen	= Obj.attr('maxlength');
		var maxlen2	= Obj2.attr('maxlength');
		
		if( lv == 1 ){//主類別
		
			content += '<div class="content">';
			content += '	<i class="icon fa fa-times-circle menu_del"></i>';
			content += '	<input type="hidden" name="' +pre+ '_ID[]" value="">';
			content += '	<input type="hidden" name="' +pre+ '_Lv[]" value="' +lv+ '">';
			content += '	<input type="text" name="' +pre+ '_Name[]" value="" class="' + Class + '" e-txt="' + e_txt + '" maxlength="' + maxlen + '">';
			content += '	<input type="text" name="' +pre+ '_Sort[]" value="0" class="' + Class2 + '" e-txt="' + e_txt2 + '" maxlength="' + maxlen2 + '" input-type="number" input-min="0" input-max="99999">';
			content += '	<ul></ul>';
			content += '</div>';
	
			$('.menu_list').prepend(content);
			
			$('.menu_del').click( function(){
				
				$(this).parent('div').remove();
			});
		}else{
			
			content += '<li>';
			content += '	<i class="icon fa fa-times-circle menu_del"></i>';
			content += '	<input type="hidden" name="' +pre+ '_ID[]" value="">';
			content += '	<input type="hidden" name="' +pre+ '_Lv[]" value="' +lv+ '">';
			content += '	<input type="hidden" name="' +pre+ '_UpMID[]" value="' + UpMID + '">';
			content += '	<input type="text" name="' +pre+ '_Name[]" value="" class="' + Class + '" e-txt="' + e_txt + '" maxlength="' + maxlen + '">';
			content += '	<input type="text" name="' +pre+ '_Sort[]" value="0" class="' + Class2 + '" e-txt="' + e_txt2 + '" maxlength="' + maxlen2 + '" input-type="number" input-min="0" input-max="99999">';
			/*content += '	<button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_del">';
			content += '		<span>刪除</span>';
			content += '	</button>';*/
			content += '</li>';
	
			$(this).siblings('ul').fadeIn().prepend(content);
			menu_sh($(this).siblings('i'), 1);
			
			$('.menu_del').click( function(){
				
				$(this).parent('li').remove();
			});
		}
		
		eshow();
	});
//新增裝備層級	
	$('.menu_equ_create').click( function(){
		
		var content = '';
		
		var lv 		= $(this).attr('mlv');
		
		var pre		= $('.tablepre').val();
		GET_EQU();
		if( lv == 1 ){//主類別
			
			var Obj	   	= $('input[name="' +pre+ '_Name[]"]').first();
			var Obj2	= $('input[name="' +pre+ '_Sort[]"]').first();
		}else{
			
			var UpMID  	= $(this).siblings('input[name="' +pre+ '_ID[]"]').val();
			var Obj	   	= $(this).siblings('input[name="' +pre+ '_Name[]"]');
			var Obj2	= $(this).siblings('input[name="' +pre+ '_Sort[]"]');	
		}
		
		var Class  	= Obj.attr('class');
		var Class2 	= Obj2.attr('class');
		var e_txt  	= Obj.attr('e-txt');
		var e_txt2 	= Obj2.attr('e-txt');
		var maxlen	= Obj.attr('maxlength');
		var maxlen2	= Obj2.attr('maxlength');
		
		if( lv == 1 ){//主類別
		
			content += '<div class="content">';
			content += '	<i class="icon fa fa-times-circle menu_del"></i>';
			content += '	<input type="hidden" name="' +pre+ '_ID[]" value="">';
			content += '	<input type="hidden" name="' +pre+ '_Lv[]" value="' +lv+ '">';
			content += '	<input type="text" name="' +pre+ '_Name[]" value="" class="' + Class + '" e-txt="' + e_txt + '" maxlength="' + maxlen + '">';
			content += '	<input type="text" name="' +pre+ '_Sort[]" value="0" class="' + Class2 + '" e-txt="' + e_txt2 + '" maxlength="' + maxlen2 + '" input-type="number" input-min="0" input-max="99999">';
			content += '	<ul></ul>';
			content += '</div>';
	
			$('.menu_list').prepend(content);
			
			$('.menu_del').click( function(){
				
				$(this).parent('div').remove();
			});
		}else{
			
			content += '<li>';
			content += '	<i class="icon fa fa-times-circle menu_del"></i>';
			content += '	<input type="hidden" name="' +pre+ '_ID[]" value="">';
			content += '	<input type="hidden" name="' +pre+ '_Lv[]" value="' +lv+ '">';
			content += '	<input type="hidden" name="' +pre+ '_UpMID[]" value="' + UpMID + '">';
			
			content += '	<select name="' +pre+ '_Name[]" msg="請選擇裝備" class="col-xs-5 col-sm-2 padding_none">';
			
			content +=		 GET_EQU();
			
			content += '	</select>';
			
			content += '	<input type="text" name="' +pre+ '_Sort[]" value="0" class="' + Class2 + '" e-txt="' + e_txt2 + '" maxlength="' + maxlen2 + '" input-type="number" input-min="0" input-max="99999">';

			content += '</li>';

			$(this).siblings('ul').fadeIn().prepend(content);
			menu_sh($(this).siblings('i'), 1);
			
			$('.menu_del').click( function(){
				
				$(this).parent('li').remove();
			});
		}
		
		eshow();
	});
	function GET_EQU(){
		
		var rs = [];
		
		$.ajax({
			url: 'ajax/get_equipment.php',
			async : false,
			type: "POST",
			dataType: 'json',
			success: function(result){
				
				rs =  result;
			}
			
		});
		
		return rs;
	}
//目錄列表資料記錄	
	$('.menu_save').click( function(){
		
		var formthis = $('#menu_form');
		if( CheckInput(formthis) ){
			
			var Form_Data = formthis.serialize();
			Form_Data += '&_type=menu_save';
			
			Post_JS( Form_Data, Exec_Url );
		}
	});	
//目錄編輯畫面
	$('.menu_edit').click( function(){
		
		var pre		= $('.tablepre').val();
		
		var Field 	= '#menu_content_edit';
		var MID   	= $(this).siblings('input[name="' +pre+ '_ID[]"]').val();
		
		var Form_Data = 'id=' +MID+ '&_type=menu_edit';
		
		Post_JS( Form_Data, Exec_Url, Field );
		
		$('#menu_content').hide();
		$('#menu_content_edit').show();
		$('#mainRe').attr('disabled', false);
	});	
//目錄刪除	
	$('.menu_del').click( function(){
		
		var pre	  = $('.tablepre').val();
		var MID   = $(this).siblings('input[name="' +pre+ '_ID[]"]').val();
		var MNAME = $(this).siblings('input[name="' +pre+ '_Name[]"]').val();
		
		$(".tc_box").BoxWindow({
			_msg: '確定刪除 ( ' + MNAME + ' ) 目錄?',//訊息
			_type: 2,
			_eval: 'Menu_Delete("' + MID + '")'
		});
	});
//目錄切換顯示/隱藏
	$('.menu_display').click( function(){
		
		var show = $(this).attr('msh') == 1 ? 0 : 1;
		
		$(this).find('span').attr('class', '').addClass((show?'color-blue':'color-red')).html((show?'顯示':'隱藏'));
		$(this).attr('msh', show);
		
		var pre	= $('.tablepre').val();
		
		var MID	= $(this).siblings('input[name="' +pre+ '_ID[]"]').val();
		
		var Form_Data = 'id=' +MID+ '&show=' +show+ '&_type=menu_display';
		
		Post_JS( Form_Data, Exec_Url );
	});
//返回按鈕
	$('#mainRe').click( function(){
		
		$('#menu_content').show();
		$('#menu_content_edit').hide().html('');
		$('#mainRe').attr('disabled', true);
	});
//關閉上面不需要按鈕
	$('#mainAdd').parent('span').remove();
	$('#mainEdt').parent('span').remove();
	$('#mainDel').parent('span').remove();
//開合function		
	function menu_sh( _this, type ){
		
		if( type == 1 ){
			
			_this.removeClass('fa-plus-circle').addClass('fa-minus-circle').attr('data-op', 1);
		}else{
			
			_this.removeClass('fa-minus-circle').addClass('fa-plus-circle').attr('data-op', 0);
		}
	}
});

//-------------------------------------------------------------//
//目錄刪除功能
function Menu_Delete( MID ){
	
	var Table_Content = $('#menu_content');
	var Form_Data = 'id=' +MID+ '&_type=menu_del';
	
	//Table_Content.html( LoadingImg );
	
	Post_JS( Form_Data, Exec_Url );
}
//-------------------------------------------------------------//
//目錄編輯畫面記錄	
function menu_edit_form(){
	
	var formthis = $('#menu_edit_form');
	if( CheckInput(formthis) ){
		
		var Form_Data = new FormData();
			
		var pre	= $('.tablepre').val();
			
		var MID = $('#' +pre+ '_ID').val();
		
		var updload	  = $('.table-updload', formthis);
		
		//if( updload.size() > 0 ){
		if( updload.length > 0 ){
			
			if( Chk_File(updload) ){
				
				updload.each(function() {
				
					var file = $(this);
					
					if( checkin(file.val()) != '' ){
						
						var file_data = file[0].files; // for multiple files
						
						for( var i = 0; i < file_data.length; i++ ){
							
							Form_Data.append(file.attr('name'), file_data[i]);
						}
					}
				});
			}else{
				
				return;
			}
		}
		
		var other_data = formthis.serializeArray();
		$.each(other_data,function(key,input){
			
			if( $('textarea[name="' +input.name+ '"]').attr('editor') == 'open_ckeditor' ){//文字編輯器用
			
				input.value = Editor[input.name].getData();//取得內容資料
			}
			
			Form_Data.append(input.name, input.value);
		});
		
		$('input[type="checkbox"]', formthis).each(function(index, element) {
			
			if( checkin($(this).attr('name')) != '' ){
				
				if( $(this).prop('checked') == false ){
					
					Form_Data.append($(this).attr('name'), 0);
				}
			}
		});
		
		Form_Data.append('_type', 'menu_edit_save');
		Form_Data.append('id', MID);
	
		Ajax_Table( Form_Data, Exec_Url );
		
		//var Form_Data = formthis.serialize();
		//Form_Data += '&id=' +MID+ '&_type=menu_edit_save';
		
		/*$('#menu_edit_form input[type="checkbox"]').each(function(index, element) {
            
			if($(this).prop('checked') == false ){
				
				Form_Data += '&' + $(this).attr('name') + '=0';
			}
        });
		
		Post_JS( Form_Data, Exec_Url );*/
	}
}
//-------------------------------------------------------------//
//資料表更換取得所有欄位	
function chg_tablekey( Val, Field ){
	
	var Form_Data = '_type=chg_tablekey&table=' + Val;
	
	Post_JS( Form_Data, Exec_Url, Field );
}
//-------------------------------------------------------------//
//主鍵更換取得前輟	
function chg_tablepre( Val, Field ){
	
	var ext = Val.split('_');
	
	$(Field).val(ext[0]);
}
//-------------------------------------------------------------//
//模式顯示隱藏
function chg_mode( _this ){
	
	$('.mode').hide();
	$('input, select', $('.mode')).attr('disabled', true);
	if( _this.val() != 0 ){
		
		$('.mode' +_this.val()).show().find('input, select').attr('disabled', false);
	}
	
	if( _this.val() == 3 ){
		
		chg_model($('#Menu_Model'));
	}
}
//-------------------------------------------------------------//
//模組顯示隱藏
function chg_model( _this ){

	//if( $('#Menu_Mode').val() == 3 || $('#Menu_Mode').val() == 5 ){
						
		$('.model').hide();
		$('input, select', $('.model')).attr('disabled', true);
		if( _this.val() != '' && _this.val() != 0 ){
			
			$('.m-' +_this.val()).show().find('input, select').attr('disabled', false);
		}
	//}
}