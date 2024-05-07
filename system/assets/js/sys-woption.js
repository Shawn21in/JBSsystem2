var Exec_Url = 'post/SPOST_WOption.php?fun=' + getUrlVal(location.search, 'fun');

$(document).ready(function(e) {
    
	$('#mainAdd').parent('span').remove();
	$('#mainEdt').parent('span').remove();
	$('#mainDel').parent('span').remove();
	
	$(this).on('click', '#WO_StmpAuth', function() {
		
		$('#WO_StmpAcc').attr('disabled', $(this).prop('checked')?false:true);
		$('#WO_StmpPass').attr('disabled', $(this).prop('checked')?false:true);
	});
	
	$(this).on('change', 'input, select', function() {
				
		$('#sendb').attr('e-txt', '資料已更改, 儲存後才能測試寄信');
	});
	
	$(this).on('click', '#sendb', function() {
		
		if( $(this).attr('e-txt') == '' ){
			
			var ID = $('#form_edit_save input:first').val();
			
			var Form_Data = new FormData();
			
			Form_Data.append('id', ID);
			Form_Data.append('_type', 'testsend');
			
			Ajax_Table(Form_Data, Exec_Url);
		}
	});
});