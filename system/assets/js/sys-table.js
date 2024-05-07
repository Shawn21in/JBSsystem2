$(document).ready(function(e) {
	//-------------------------------------------------------------//
	//TOP新增按鈕
	$('#mainAdd').click(function() {
		
		Edit();
	});
	//-------------------------------------------------------------//
	//TOP批次編輯按鈕
	$('#mainEdt').click(function() {
		
		if( $('input[name="tab_chks[]"]:checked').length > 0 ){
			
			var Form_Data = new FormData();
						
			var other_data = $('input[name="tab_chks[]"]:checked').serializeArray();
			$.each(other_data,function(key,input){
				
				Form_Data.append(input.name, input.value);
			});
			
			Form_Data.append('_type', 'Table_Edit');
			
			EditAction(Form_Data);
		}else{
			
			$(".tc_box").BoxWindow({
				_msg: '勾選欲編輯項目?',//訊息
			});
		}
	});
	//-------------------------------------------------------------//
	//TOP批次刪除按鈕
	$('#mainDel').click(function() {
		
		if( $('input[name="tab_chks[]"]:checked').length > 0 ){
			
			$(".tc_box").BoxWindow({
				_msg: '確定刪除選取項目?',//訊息
				_type: 2,
				_eval: 'Delete("checkbox")'
			});
		}else{
			
			$(".tc_box").BoxWindow({
				_msg: '勾選欲刪除項目?',//訊息
			});
		}
	});
	//-------------------------------------------------------------//
	//TOP返回按鈕
	$('#mainRe').click(function() {
		
		Return_Table();
	});	
	//-------------------------------------------------------------//
	//按下查詢按鈕
	$('.search-button, .search-cleandata').click(function() {
		
		var msg 		= '請選擇查詢的項目';
		var search_type = $(this).attr('search-type');
		
		$('#searchform select[name="search_field[]"]').each(function() {
            
			if( checkin($(this).val()) ){
				
				if( checkin(search_type) == 'clean' ){
					
					msg = '請輸入欲清除的查詢資料';
					
					$(this).siblings('.search_content').find('[name="inquire_' +$(this).val()+ '[]"]').each(function() {
						
						if( checkin($(this).val()) ){
							
							msg = '';
							return false;
						}
					});
					
					if( msg == '' ){
						
						return false;
					}
				}else{
					
					msg = '';
					return false;
				}
			}
        });
						
		if( msg != '' ){
			
			$(".tc_box").BoxWindow({
				_msg: msg,//訊息
			});
			
			return false;
		}else if( checkin(search_type) == 'clean' ){
			
			$(".tc_box").BoxWindow({
				_msg: '確定清除查詢資料?',//訊息
				_type: 2,
				_eval: 'Search_Submit("' + search_type + '")'
			});
		}else if( checkin(search_type) == 'excel' ){
			
			$(".tc_box").BoxWindow({
				_msg: '確定匯出查詢資料?',//訊息
				_type: 2,
				_eval: 'Search_Submit("' + search_type + '")'
			});
		}else{
			
			Search_Submit();
		}
	});
	
	//-------------------------------------------------------------//
	//按下新增查詢項目按鈕
	$('.search-additem').click(function(){
		
		var select_count = $('#searchform select[name="search_field[]"]:first option').length - 1;
		var div_count	 = $('#searchform .search-main div').length;
		
		if( div_count >= select_count ){

			$(".tc_box").BoxWindow({
				_msg: '查詢項目只有' +div_count+ '種'//訊息
			});
		}else{
			
			var search_item = $('#searchform .search-main div:first');
			var content = '';
			
			content += '<div class="col-xs-12 search-border">';
			content += '	<i class="fa fa-times"></i>';
			content +=	search_item.html();
			content += '</div>';
			
			$('#searchform .search-main').append(content);
			
			$('#searchform .search-main div:last').find('i').click(function() {
			
				$(this).parent().remove();
			})
			
			$('#searchform .search-main div:last .search_content').html('');
		}
	});
	
	//-------------------------------------------------------------//
	//checkbox全選或全部取消
	$(this).on('click', '#tab_chk', function(e) {
		
		var _this = $(this);
		$('input[name="tab_chks[]"]').each(function(index, element) {
			
			$(this).prop('checked', _this.prop('checked'));
		});
	});
	
	//-------------------------------------------------------------//
	//當按下ENTER鍵執行搜尋
	$(this).on('keypress', '#SearchKey', function(e) {

		if ( e.keyCode == 13 ) {
			
			Re_Table('Table_Re', '', '#table_content');
		}
	});
	
	//-------------------------------------------------------------//
	//筆數切換
	$(this).on('change', '#Page_Size', function(e) {

		Re_Table('Table_Re', '', '#table_content');
	});
	
	//-------------------------------------------------------------//
	//頁碼切換
	$(this).on('click', '.pages_class', function(e) {
		
		var Pages     = $(this).attr('pages');
		var Now_Pages = $('.pagination .active a').attr('pages');
		
		if( Now_Pages == null ){
			
			Now_Pages = 0;
		}
		
		if( Pages > 0 && Now_Pages > 0 && Pages != Now_Pages ){
			
			$('.pagination .active a').attr('pages', Pages)
			
			Re_Table('Table_Re', '', '#table_content');
		}
	});
	
	//-------------------------------------------------------------//
	//排序切換
	$(this).on('click', '#mainTable .sorting', function(e) {
	
		if( $(this).attr('class').match('sorting_asc') != null ){
			
			//Sort = 'DESC';
			$(this).addClass('sorting_desc').removeClass('sorting_asc');
		}else if( $(this).attr('class').match('sorting_desc') != null ){
			
			//Sort = 'ASC';
			$(this).addClass('sorting_asc').removeClass('sorting_desc');
		}else{
		
			//Sort = 'ASC';
			$(this).addClass('sorting_asc').siblings('.sorting').removeClass('sorting_asc').removeClass('sorting_desc');
		}

		Re_Table('Table_Re', '', '#table_content');
	});
	
	//-------------------------------------------------------------//
	//TableCheckBox按下
	$(this).on('click', '.TableCheck', function(e) {
		
		Ajax_Chk($(this));
	});
	
	//-------------------------------------------------------------//
	//TableNumber, TableSelect改變
	$(this).on('change', '.TableChange', function(e) {
		
		Ajax_Chk($(this));
	});
});
//-------------------------------------------------------------//
//編輯畫面記錄	
function form_edit_save(){
	
	var formthis = $('#form_edit_save');
	if( CheckInput(formthis) ){
		
		var Form_Data = new FormData();
		
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
				//console.log(input.value);
			}
			
			if( $('textarea[name="' +input.name+ '"]').attr('editor') == 'open_tinymce' ){//文字編輯器2用
				
				var editor_id = $('textarea[name="' +input.name+ '"]').attr('id');
				
				input.value = tinyMCE.get(editor_id).getBody().innerHTML;//取得內容資料
				
				tinymce.execCommand('mceRemoveEditor', false, editor_id);//移除編輯控制
			}
			
			if( $('textarea[name="' +input.name+ '"]').val() != '' ){//WAF擋住 <> 去除
			
				/*_thisVal = input.value;
				var re 	= new RegExp ('<', 'gi') ;
				var re2 = new RegExp ('>', 'gi') ;
				_thisVal = _thisVal.replace(re,"%bm%1%bm%");
				_thisVal = _thisVal.replace(re2,"%bm%2%bm%");
				
				input.value = _thisVal;//取得內容資料*/
			}
			
			Form_Data.append(input.name, input.value);
		});
		
		//---------------------------------------------------
		//關鍵字
		
		var _kw = '';
		
		var _kw_field = $(".keywordBox__item").parent().attr('data-field');
		
		if( checkin(_kw_field) != '' ){
			
			$.each( $(".keywordBox__item").find('p') ,function(key,input){
				
				if( key == 0 ) {
					
					_kw += $(this).text();
				}else{
					
					_kw += ","+$(this).text();
				}
			});
			
			
			Form_Data.append( _kw_field+"[]" , _kw );
		}
		
		//---------------------------------------------------

		$('input[type="checkbox"]', formthis).each(function(index, element) {
			
			if( checkin($(this).attr('name')) != '' ){
				
				if( $(this).prop('checked') == false ){
					
					Form_Data.append($(this).attr('name'), 0);
				}
			}
		});
		
		Re_Table('Table_Edit_Save', Form_Data, '#table_content');
	}
}
//-------------------------------------------------------------//
//多圖片編輯畫面記錄	
function form_psave(){
	
	var formthis = $('#form_psave');
	if( CheckInput(formthis) ){
		
		var Form_Data = new FormData();
	
		var other_data = formthis.serializeArray();
		$.each(other_data,function(key,input){
			
			Form_Data.append(input.name, input.value);
		});
				
		$('input[type="checkbox"]', formthis).each(function(index, element) {
				
			if( $(this).prop('checked') == false ){
				
				Form_Data.append($(this).attr('name'), 0);
			}
		});
		
		Form_Data.append('_type', 'Table_Edit_PSave');
	
		Ajax_Table(Form_Data, Exec_Url);
	}
}
//-------------------------------------------------------------//
//返回function
function Return_Table(){
	
	var Form_Data	 		= '_type=Table_Return';
	var Table_Content 		= $('#table_content');
	var Table_Content_Edit  = $('#table_content_edit');
	
	//Table_Content_Edit.html('');
	
	//loading('open');
	
	$.post( Exec_Url, Form_Data
		,function( data ){
			
			Table_Content.show();
			Table_Content_Edit.hide();
			
			$('#mainAdd').attr('disabled', data.html_add);
			$('#mainEdt').attr('disabled', data.html_edt);
			$('#mainDel').attr('disabled', data.html_del);
			$('#mainRe').attr('disabled', true);
			
			/*$('textarea[editor="open_tinymce"]').each(function(index, element) {
                
				tinymce.execCommand('mceRemoveEditor', false, $(this).attr('id'));//移除編輯控制
            });*/
			
			//loading('close');
		},'json'
	);
	
	$('#tab_chk, input[name="tab_chks[]"]').prop('checked', false);
	
	/*$('input[name="tab_chks[]"]').each(function(index, element) {
			
		$(this).prop('checked', false);
	});*/
	
	Re_Table('Table_Re', '', '#table_content');
}
//-------------------------------------------------------------//
//表格編輯動作
function EditAction( Form_Data ){
	
	var Table_Content 		= $('#table_content');
	var Table_Content_Edit  = $('#table_content_edit');
	
	Table_Content.hide();
	Table_Content_Edit.show();
	Table_Content_Edit.html('');
	
	$(".extra-div").hide();
	
	$('#mainAdd').attr('disabled', true);
	$('#mainEdt').attr('disabled', true);
	$('#mainDel').attr('disabled', true);
	$('#mainRe').attr('disabled', false);

	Ajax_Table( Form_Data, Exec_Url, Table_Content_Edit );
}
//-------------------------------------------------------------//
//表格檢視功能
function View( ID ){
	
	var Form_Data = new FormData();
	
	Form_Data.append('_type', 'Table_Edit');
	Form_Data.append('_type1', 'View');
	Form_Data.append('tab_chks', ID);
	
	Ajax_Table( Form_Data, Exec_Url, '#table_content_view' );
}
//-------------------------------------------------------------//
//審核功能
function Verift( ID , statue ){
	
	var Form_Data = new FormData();
	
	Form_Data.append('_type', 'Table_Verify');
	Form_Data.append('_statue', statue);
	Form_Data.append('tab_chks', ID);
	
	Ajax_Table( Form_Data, Exec_Url, '#table_content_view' );
}
//-------------------------------------------------------------//
//確認刪除功能
function Conf_Tranfer( ID ){
	
	$(".tc_box").BoxWindow({
		_msg: '確定建立物流單 ( 編號 ' + ID + ' )',//訊息
		_type: 2,
		_eval: 'Tranfer("' + ID + '")'
	});
}
//-------------------------------------------------------------//
//物流單建立功能
function Tranfer( ID ){
	
	var Form_Data = new FormData();
	
	Form_Data.append('_type', 'Ecapy_Tranfer');
	Form_Data.append('tab_chks', ID);
	
	Ajax_Table( Form_Data, Exec_Url );
}
//-------------------------------------------------------------//
//表格複製功能
function Copy( ID ){
	
	var Form_Data = new FormData();
	
	Form_Data.append('_type', 'Table_Copy');
	
	ID = checkin(ID);
	Form_Data.append('tab_chks', ID);
	
	EditAction(Form_Data);
}
//-------------------------------------------------------------//
//表格編輯功能
function Edit( ID ){
	
	var Form_Data = new FormData();
	
	Form_Data.append('_type', 'Table_Edit');
	
	ID = checkin(ID);
	Form_Data.append('tab_chks', ID);
	
	EditAction(Form_Data);
}
//-------------------------------------------------------------//
//表格多圖片編輯功能
function PEdit( ID ){
	
	var Form_Data = new FormData();
	
	Form_Data.append('_type', 'Table_PEdit');
	Form_Data.append('tab_chks', ID);
	
	Ajax_Table( Form_Data, Exec_Url, '#table_content_view' );
}
//-------------------------------------------------------------//
//確認刪除功能
function Del( ID, SN ){
	
	$(".tc_box").BoxWindow({
		_msg: '確定刪除 ( 序號 ' + SN + ' )',//訊息
		_type: 2,
		_eval: 'Delete("' + ID + '")'
	});
}
//-------------------------------------------------------------//
//表格刪除功能
function Delete( ID ){
	
	var Form_Data = new FormData();
						
	if( ID == 'checkbox' ){
		
		var other_data = $('input[name="tab_chks[]"]:checked').serializeArray();
		$.each(other_data,function(key,input){
			
			Form_Data.append(input.name, input.value);
		});
	}else{
		
		Form_Data.append('tab_chks', ID);
	}
	
	Re_Table('Table_Del', Form_Data, '#table_content');
}
//-------------------------------------------------------------//
//刷新表格
function Re_Table( _type, _form_data, _field ){
		
	if( _form_data == '' ){
		
		var _form_data = new FormData();
	}
	
	var Table_Content 		= $('#table_content');
	var Table_Content_Edit  = $('#table_content_edit');

	var Form_Data = '';
	var Sort 	  = '', Field = '';
	var Pages 	  = $('.pagination .active a').attr('pages');
	var SearchKey = $('input[name="SearchKey"]').val();
	var Page_Size = $('select[name="Page_Size"]').val();
	
	if( Pages == null || Pages == '' ){
		
		Pages = 1;
	}
	
	if( $('.sorting_asc').attr('sortfield') != null ){
		
		Sort = 'ASC';
		Field = $('.sorting_asc').attr('sortfield');
	}else if( $('.sorting_desc').attr('sortfield') != null ){
		
		Sort = 'DESC';
		Field = $('.sorting_desc').attr('sortfield');
	}
	
	_form_data.append('_type', _type);
	_form_data.append('Pages', Pages);
	_form_data.append('Page_Size', Page_Size);
	_form_data.append('SearchKey', SearchKey);
	_form_data.append('Sort', Sort);
	_form_data.append('Field', Field);
	
	var other_data = $('#searchform').serializeArray();//查詢資料
	$.each(other_data,function(key,input){
		
		if( input.value != '' ){
			
			_form_data.append(input.name, input.value);
		}
	});
	
	Table_Content_Edit.html('');
	
	Ajax_Table( _form_data, Exec_Url, _field );
}
//-------------------------------------------------------------//
//增加項目
function Add_List( _this ){
	
	var $this 	= _this.parent('label');
	var content = '';
	
	var $this1  = $this.siblings('div');
		
	content += '<div class="control-div">';
	content += $this1.html();
	content += '	<label class="col-sm-7 control-label9">';
	content += '		<i class="icon fa fa-times eshow" e-txt="刪除項目" onclick="Del_List($(this))"></i>';
	content += '	</label>';
	content += '</div>';
	
	content  = content.replace(/value="(.*?)"/ig, 'value=""')
	
	$this1.parent('div').prepend(content);
}
//-------------------------------------------------------------//
//刪除項目
function Del_List( _this ){

	_this.parent('label').parent('div').remove();
}
//-------------------------------------------------------------//
//查詢欄位變更
function Search_Chg( _this ){
	
	var content 	= '';
	var field 		= _this.val();
	var type 		= checkin($('option:selected', _this).attr('data-type'));
	var format		= checkin($('option:selected', _this).attr('data-format'));
	var search_cobj = _this.siblings('.search_content');
	
	if( type == 'text' ){
		
		content += '<input type="text" name="inquire_' +field+ '[]" value="" placeholder="請輸入搜尋字串">';
	}else if( type == 'select' ){
		
		content += '<select name="inquire_' +field+ '[]">';
		//content += '	<option value="">請選擇</option>';
		$.each( Search_KArr[field], function( key, value ) {
			
			content += '<option value="' +value+ '">' +Search_VArr[field][key]+ '</option>';
		});
		
		content += '</select>';
	}else if( type == 'datetime' ){
		
		content += '<input type="text" id="' +field+ '_start" name="inquire_' +field+ '[]" value="" placeholder="請輸入時間開始">';
		content += ' ~ ';
		content += '<input type="text" id="' +field+ '_end" name="inquire_' +field+ '[]" value="" placeholder="請輸入時間結束">';
	}else if( type == 'between' ){
		
		content += '<input type="text" name="inquire_' +field+ '[]" value="">';
		content += ' ~ ';
		content += '<input type="text" name="inquire_' +field+ '[]" value="">';
	}
	
	search_cobj.html(content);
	
	if( type == 'datetime' ){
		
		Datetimepicker('#' +field+ '_start', format);
		Datetimepicker('#' +field+ '_end', format);
	}
}
//-------------------------------------------------------------//
//查詢送出
function Search_Submit( type ){
	
	var Form_Data 	= new FormData();
	
	if( checkin(type) ){
		
		Form_Data.append('search_type', type);
	}

	Re_Table('Table_Re', Form_Data, '#table_content');
}
//-------------------------------------------------------------//
//關鍵字動態輸入
function KeyWord_ClickEnter( field ) { 
	
	if( event.keyCode ==13 || event.keyCode ==188 ){
		
		val = $( ".keywordBox__input" ).val(); 
		
		if(val!=''){
			$( ".keywordBox__input" ).before( "<li class='keywordBox__item'><p>"+val+"</p><a class='deleteKY'>✕</a></li>" );
		}
		$( ".keywordBox__input" ).val("");
		
	}
}

