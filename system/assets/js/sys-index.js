var L = 1; 
var acc, pwd;

$(document).ready(function(e) {
	
	acc = $('#account');
	pwd = $('#password');
	
	$('#btn-login-dark').on('click', function(e) {
		
		$('body').attr('class', 'login-layout');
		$('#id-text2').attr('class', 'white');
		$('#id-company-text').attr('class', 'blue');
		
		e.preventDefault();
	});
	
	$('#btn-login-light').on('click', function(e) {
		
		$('body').attr('class', 'login-layout light-login');
		$('#id-text2').attr('class', 'grey');
		$('#id-company-text').attr('class', 'blue');
		
		e.preventDefault();
	});
	
	$('#btn-login-blur').on('click', function(e) {
		
		$('body').attr('class', 'login-layout blur-login');
		$('#id-text2').attr('class', 'white');
		$('#id-company-text').attr('class', 'light-blue');
		
		e.preventDefault();
	});
	
	acc.focus();
	
	//-------------------------------------------------------------//
	//建立後台功能
	$('.config-submit').on('click', function(e) {	
		
		var formthis = $(this).closest('form');
		if( CheckInput(formthis) ){
			
			Form_Data = formthis.serialize();
			Form_Data += '&_type=config';

			Post_JS( Form_Data, Exec_Login );
		}
	});
	//-------------------------------------------------------------//
	//建立後台基本功能選擇checkbox
	$('.config-checkbox').on('click', function(e) {	
		
		var checkd = $(this).prop('checked');
		
		$(this).closest('.form-group').find('.config-checkbox').prop('checked', false);
		$(this).prop('checked', checkd);
	});
	//-------------------------------------------------------------//
	//登入功能
	$('.login-submit').on('click', function(e) {	
		
		Login();
	});
	//店家登入功能
	$('.Shop-login-submit').on('click', function(e) {	
		
		SLogin();
	});
	
}).keyup(function(e) {

	if( e.keyCode == 13 && L == 1 && acc.val() != '' && acc.val() != null ){
		
		if( pwd.val() == '' ){
			
			pwd.focus();
		}else{
			if( $("#SYSTEM_IDENTIFY").val() == "SHOP" ) {
				
				SLogin();
			}else{
				Login();
			}
			
		}
	}
});

function Login( Code ){
		
	L = 0;
	var Form_Data = '';
	var formthis = $('#login_form');
	if( CheckInput(formthis) ){
		
		if( Code == null ){
		
			Form_Data = '_type=FormCode';
			Post_JS( Form_Data, Exec_Login );
		}else{
			
			Form_Data = formthis.serialize();
			Form_Data += '&_type=login&code=' + Code;
			
			Post_JS( Form_Data, Exec_Login );
		}
	}
}
//店家後台登入
function SLogin( Code ){
	
	L = 0;
	var Form_Data = '';
	var formthis = $('#login_form');
	if( CheckInput(formthis) ){
		
		if( Code == null ){
		
			Form_Data = '_type=SFormCode';
			Post_JS( Form_Data, Exec_Login );
		}else{
			
			Form_Data = formthis.serialize();
			Form_Data += '&_type=Slogin&code=' + Code;
			
			Post_JS( Form_Data, Exec_Login );
		}
	}
}
function Clear_Index(){
	
	L = 1;
	$('#account').focus();
}