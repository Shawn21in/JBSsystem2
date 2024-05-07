var Exec_Login 	= 'post/SPOST_Login.php';

var ImgExt	   	= new Array('jpg', 'jpeg', 'png', 'gif');

var FileExt	   	= new Array();

FileExt['default'] = new Array('jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'pdf', 'ppt', 'pptx', 'xls', 'xlsx', 'csv', 'ai', 'psd', 'zip', 'rar', 'mp4', 'odt', 'ods', 'odp');

var Editor 		= new Array();

var myEditor 		= new Array();

var Upload_Size	= new Array();

Upload_Size['default'] = 2048000;

$(document).ready(function(e) {
//-------------------------------------------------------------//
//開啟彈出視窗
	$(".tc_box").BoxWindow({
		_open: true
	});
//-------------------------------------------------------------//
//登出功能
	$('.logout').click( function() {

		var Form_Data = '_type=logout';
		Post_JS( Form_Data, Exec_Login );
	});
//-------------------------------------------------------------//	
//主畫面左邊目錄選擇錨點
	var path = getUrlVal(location.search, 'fun');

    if( path == '' || path == null ){
		
		$('#main').addClass('active open');
	}else{
		
		$('#'+path).addClass('active');
		$('#'+path).parent().parent('li').addClass('active open');
	}
//-------------------------------------------------------------//
//載入小註解JS
	eshow();
//-------------------------------------------------------------//
//檢查登入資訊
	var url_arr = location.pathname.split("/");
	var value   = url_arr[url_arr.length-1];
	if( value != '' && !value.match(/\index/) ){
		
		Form_Data = '_type=checklogin';
		Post_JS( Form_Data, Exec_Login );
	}
//-------------------------------------------------------------//
//修正移除上傳檔案沒動作	
	$('.ace-file-input .remove').click(function() {
		
		$(this).siblings('span').removeClass('selected');
		$('.ace-file-name').attr('data-title', '無選取檔案 ...');
		return false;
	});
//-------------------------------------------------------------//
//進階功能按下
	$(".extra-fun").click(function() {
		
		var div = $(".extra-div");
		if ( div.css('display') == 'none' ){
			
			div.fadeIn();
		}else{
			
			div.fadeOut();
		}
	});
//-------------------------------------------------------------//
//確認刪除、下載檔案功能, 設定封面功能
	$(this).on('click', '.chk-file, .make_cover_photo', function(e){
		
		var type	= $(this).attr('check-type');//資料第一種類
		var id		= $(this).attr('check-id');
		var field	= $(this).attr('check-field');
		var data	= $(this).attr('check-data');
	
		var Form_Data = new FormData();

		if( type == 'delimg' || type == 'delimgs' || type == 'delfile' ){
			
			$(".tc_box").BoxWindow({
				_msg: '確定刪除檔案',//訊息
				_type: 2,
				_eval: 'DelFile("' + type + '", "' + id + '", "' + field + '")'
			});
		}else if( type == 'downfile' ){
			
			Form_Data.append('_type', 'Table_Download');
			Form_Data.append('id', id);
			Form_Data.append('field', field);
			
			setTimeout( function(){ Ajax_Table( Form_Data, Exec_Url ) }, 500);
		}else if( type == 'make_cover_photo' ){
			
			Form_Data.append('_type', 'Table_Data_Change');
			Form_Data.append('_type1', type);
			Form_Data.append('id', id);
			Form_Data.append('field', field);
			Form_Data.append('data', data);
			
			Ajax_Table( Form_Data, Exec_Url );
		}
	});
//-------------------------------------------------------------//
//點擊全選
	$(this).on('click', '.selectAll', function(e){
		
		$(this).select();
	});
//-------------------------------------------------------------//
//上傳檔案檢查	
	$(this).on('change', '.table-updload', function(e){
		
		Chk_File($(this));
	});	
//-------------------------------------------------------------//
//將井字號後面清空	
	location.hash = '';
});
//-------------------------------------------------------------//	
//抓取Top的高只執行在Google Chrome
/*$(document).ready(function(e){
	
	//navigator.userAgent.match("Firefox")　//判斷是否為 FireFox
	//navigator.userAgent.match("MSIE")　//判斷是否為 IE
	//navigator.userAgent.match("Opera")　//判斷是否為 Opera
	//navigator.userAgent.match("Safari")　//判斷是否為 Safari 或 Google Chrome
	if( navigator.userAgent.match('Safari') ){
	
		TopHeight_Re();
		
		$(window).resize(function(){
				
			TopHeight_Re();
		});
	} 
});

function TopHeight_Re(){
	
	var MainCon   = $('.main-container');
	var TopHeight = $('.navbar-fixed-top').innerHeight();
	
	if( TopHeight == null ){
		
		TopHeight = $('.navbar-container').innerHeight();
	}
	
	if( TopHeight > 120 ){
		
		MainCon.css('padding-top', '135px');
	}else if( TopHeight > 80 ){
		
		MainCon.css('padding-top', '90px');
	}else{
		
		MainCon.css('padding-top', '');
	}
}*/
//-------------------------------------------------------------//	
//前往FACEBOOK
function GoFB( url ){
	
	if( checkin(url) ){
		
		window.open(url);
	}else{
		
		$(".tc_box").BoxWindow({
			_msg: '請至網站管理 -> 網站設定 -> 設定Facebook連結',//訊息
		});
	}
}
//-------------------------------------------------------------//	
//抓取網址GET的function
function getUrlVal( url, str ){
	
	var strUrl = url;
	var getPara, ParaVal;
	
	if ( strUrl.indexOf("?") != -1 ) {
		
		var getSearch = strUrl.split("?");
		getPara = getSearch[1].split("&");
		
		for( i = 0; i < getPara.length; i++ ) {
			
			ParaVal = getPara[i].split("=");
			
			if( ParaVal[0] == str ){
				
				return ParaVal[1];
			}
		}
	}
}
//-------------------------------------------------------------//	
//網址刷新
function Reload(){
	
	window.location.reload();
}
//-------------------------------------------------------------//	
//網址返回
function Back(){
	
	window.history.go(-1);
}
//-------------------------------------------------------------//	
//打開網址
function Wopen( url, target ){
	
	window.open( url, target );
}
//-------------------------------------------------------------//
//顯示小註解
function eshow(){
	
	var est = $('.e-show-text');
	
	//if( !est.size() ){
	if( !est.length ){
		
		var content = '<div class="e-show-text"></div>';
		
		$('body').append(content);
		
		est = $('.e-show-text');
	}
	
	est.hide();
	
	$(document).on('mousemove', '.eshow', function(e){
		
		var txt  = $(this).attr('e-txt');
		var top  = e.pageY - 5;
		var left = e.pageX + 10;
		
		if( txt != '' && txt != null ){
			
			est.css({top: top, left: left}).html(txt).show();
		}
	}).on('mouseleave', '.eshow', function(e){
		
		est.html('').hide();
	});
}
//-------------------------------------------------------------//
//呼叫彈出行事曆
function Datetimepicker( Field, Format ){
	
	if( !checkin( Format ) ){
		
		Format = 'YYYY-MM-DD HH:mm:ss';
	}
	
	$(Field).datetimepicker({
		ignoreReadonly: true,
		format: Format,
		locale: 'zh-tw',
		/*debug: true*/
	}).on("dp.hide",function (e) {
								
		Ajax_Chk($(this));
		//$(this).data("DateTimePicker").hide();
		//$(this).data("DateTimePicker").maxDate(e.date);
	}).on("dp.change",function(e) {
		
	});
}
//-------------------------------------------------------------//
//判斷是否為空值
function checkin( val ){
	
	if( val == '' || val == null || val == 'undefined' ){
		
		val = '';
	}
	
	return val;
}
//-------------------------------------------------------------//
//讀取畫面
var myVar;
//var myVar2;

function loading( Type ){
	
	if( Type == 'open' ){
		
		myVar	= setTimeout(loading_longtime, 120000);//120秒
		//myVar2 	= setTimeout(loading_show, 300);//0.3秒	
		$(document).Loading({ _action : true });
	}else if( Type == 'close' ){
		
		$(document).Loading({ _action : false });
		
		clearTimeout(myVar);
		//clearTimeout(myVar2);
	}
}
//-------------------------------------------------------------//
//讀取太久要做的動作
function loading_longtime(){
	
	$(".tc_box").BoxWindow({
		_msg: '讀取太久, 請重新登入',//訊息
		_url: 'index.php'
	});
}
//-------------------------------------------------------------//
//顯示讀取畫面
function loading_show(){
	
	$(document).Loading({ _action : true });
}
//-------------------------------------------------------------//
//文字編輯器
/*
function CREAT_CKEDITOR( Field, Height ){
	
	if( !checkin( Height ) ){
		
		Height = 400;
	}
	
	var editor = ClassicEditor.create( document.querySelector( "#"+Field ), {
                
                ckfinder: {uploadUrl: 'plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json'
                    },heading: {
					options: [
						{ model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
						{ model: 'heading1', view: 'h1', title: 'H1', class: 'ck-heading_heading1' },
						{ model: 'heading2', view: 'h2', title: 'H2', class: 'ck-heading_heading2' },
						{ model: 'heading3', view: 'h3', title: 'H3', class: 'ck-heading_heading3' },
						{ model: 'heading4', view: 'h4', title: 'H4', class: 'ck-heading_heading4' },
						{ model: 'heading5', view: 'h5', title: 'H5', class: 'ck-heading_heading5' }
					]
				}
                }
                
            )
            .then( editor => {
	            myEditor[Field+"[]"] = editor;
	        } )            
            .catch( error => {
                console.error( error );
            } );
	
	return editor;
}
*/
function CREAT_CKEDITOR( Field, Height ){
	
	if( !checkin( Height ) ){
		
		Height = 400;
	}
	
	var editor = CKEDITOR.replace( Field, {
		enterMode 					: CKEDITOR.ENTER_BR,
		height 						: Height,
		//toolbar						: 'MXICToolbar',
		filebrowserBrowseUrl 		: 'plugins/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl 	: 'plugins/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl 	: 'plugins/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl 		: 'plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files', 
		//可上傳一般檔案
        filebrowserImageUploadUrl 	: 'plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		//可上傳圖檔
        filebrowserFlashUploadUrl 	: 'plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
		//可上傳Flash檔案
		removeButtons : 'Save,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Language,Flash,Smiley,NewPage'
	});
	
	return editor;
}

//-------------------------------------------------------------//
//文字編輯器2
function TinyMCE( Field, Height ){
	
	if( !checkin( Height ) ){
		
		Height = 400;
	}
	
	tinymce.init({
		selector: Field,
		language: 'zh_TW',
		height: Height,
		plugins: [
			"advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
			"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			"table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern",
			"jbimages"
		],
		
		
		toolbar1: "undo redo newdocument preview code fullscreen | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent blockquote",
		toolbar2: "fontselect fontsizeselect | link unlink image media | charmap emoticons | ltr rtl | pagebreak | jbimages | forecolor backcolor",		
		
		//menubar: false,//關閉菜單
		//toolbar_items_size: 'small',//縮小圖形
	});
}
//-------------------------------------------------------------//
//上傳圖片函數
function Upload_File_Class( Field ){
	
	var _this = $(Field).parent().parent();
		
	var _file = $('.file-url', _this).attr('file-url');
	if( _file == '' || _file == null ){ 
	
		_file = '無選取檔案 ...'; 
	}
	
	$(Field).ace_file_input({
		no_file: _file,
		btn_choose: '選擇',
		btn_change: '更改'
	});
	
	var upload_size = $(Field).attr('max-uploadsize');
	if( checkin(upload_size) ){//儲存設定可上傳的檔案大小
		
		Upload_Size[Field] = upload_size;
	}
	
	var fileext = $(Field).attr('fileext');
	if( checkin(fileext) ){//儲存設定上傳檔案類型
		
		FileExt[Field] = fileext.split(',');
	}
}
//-------------------------------------------------------------//
//判斷檔案格式
function Chk_File( _this ){
	
	var msg  			= '';
	var file_count 		= 0;
	var max_file_count 	= 15;
	
	_this.each(function() {
		
		var allow_file  = ''
		
		var file 		= $(this);
		var type 		= $(this).attr('file-type');
		
		if( type == 'img' ){
			
			allow_file = ImgExt;
		}else if( type == 'file' ){
			
			allow_file 	= FileExt['#'+$(this).attr('id')] ? FileExt['#'+$(this).attr('id')] : FileExt['default'];
		}
		
		var upload_size	= Upload_Size['#'+$(this).attr('id')] ? Upload_Size['#'+$(this).attr('id')] : Upload_Size['default'];

		if( type == 'img' || type == 'file' ){
			
			if( checkin(file.val()) != '' ){
			
				var file_data = file[0].files; // for multiple files
				
				for( var i = 0; i < file_data.length; i++ ){
					
					var ext = file_data[i].name.split('.').pop().toLowerCase();
				
					if( $.inArray(ext, allow_file) == -1 ){
						
						msg = file_data[i].name + ' ( 不允許上傳檔案格式 )';
						break;
					}else if( file_data[i].length > upload_size ){//2MB
						
						msg = file_data[i].name + ' ( 檔案大小超過 ' +File_Size_Unit(upload_size, 0)+ ' )';
						break;
					}
				}
				
				if( msg == '' ){
					
					file_count += file_data.length;
				}else{
					
					return;
				}
			}
		}else{
			
			msg = '未設定上傳格式或非上傳格式';
			return;
		}
	});
	
	if( msg == '' && file_count > max_file_count ){
		
		msg = '上傳檔案數量最多 ' + max_file_count + ' 個';
	}
	
	if( msg != '' ){
		
		$(".tc_box").BoxWindow({
			_msg: msg
		});
		
		return false;
	}else{
		
		return true;
	}
}
//-------------------------------------------------------------//
//刪除檔案功能
function DelFile( type, id, field ){
	
	var error = Field = '';
	
	if( type == 'delimgs' ){
		
		Field = $('#' +field+ '_' +id);
	}else{
		
		Field = $('#'+field).parent().parent().parent();
	}
	
	if( type == '' ){
		
		error = '錯誤 ( 沒有設定種類 )';
	}else if( id == ''){
		
		error = '錯誤 ( 沒有設定刪除編號 )';
	}else if( checkin(field) == '' ){
		
		error = '錯誤 ( 沒有設定欄位 )';
	}else{
			
		var Form_Data = new FormData();

		Form_Data.append('_type', 'Table_Data_Change');
		Form_Data.append('_type1', type);
		Form_Data.append('id', id);
		Form_Data.append('field', field);

		Ajax_Table( Form_Data, Exec_Url, Field );
	}
	
	if( error != '' ){
		
		$(".tc_box").BoxWindow({
			_msg: error,//訊息
		});
	}
}
//-------------------------------------------------------------//
//檔案大小單位轉換函式
function File_Size_Unit( size, format ){
	//設定單位
	var size_unit = new Array('Bytes','KB','MB','GB','TB');
	
	if( size < 1024 ){
		
		return size+'bytes';
	}else if(  size < ( 1024 * 1024 ) ){
		
		size = ( size / 1024 ).toFixed(format);
		return size+'KB';
	}else if(  size < ( 1024 * 1024 * 1024 ) ){
		
		size = ( size / ( 1024 * 1024 ) ).toFixed(format);
		return size+'MB';
	}else{
			
		size = ( size / ( 1024 * 1024 * 1024 ) ).toFixed(format);
		return size+"GB";
	}
}