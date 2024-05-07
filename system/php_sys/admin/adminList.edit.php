<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

<div class="abgne_tab">

    <ul class="tabs">
        <li><a href="javascript:void(0)" for-id="tab1">基本資料</a></li>
    </ul>
    
    <div class="tab_container">
    
		<div id="tab1" class="tab_content">
        
            <form id="form_edit_save" class="form-horizontal">
            
            <?php foreach( $_html_ as $key => $val ){ ?>
            
                <div class="Table_border">
                
                    <input type="hidden" id="Admin_ID" name="Admin_ID[]" value="<?=$val['Admin_ID']?>">
                    
                    <?php 
					//-----------------------------------------//
					$Arr_Name = 'Admin_Acc';
					
					echo $_TF->html_unique($table_info[$Arr_Name]['Comment'], '', '(ex. Admin)', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '請輸入'.$table_info[$Arr_Name]['Comment'], 1);
					//-----------------------------------------//
					$Arr_Name = 'Admin_Pwd';
					
					echo $_TF->html_pwd($table_info[$Arr_Name]['Comment'], '', '(輸入新密碼, 儲存後直接更新密碼)', $table_info[$Arr_Name]['Field_Length'], $Arr_Name); 
					//-----------------------------------------//
					$Arr_Name = 'Admin_Name';
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '(ex. 後台管理員)', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '請輸入'.$table_info[$Arr_Name]['Comment'], 1);
					//-----------------------------------------//
					$Arr_Name = 'Group_ID';
					
					if( $Admin_data['Admin_ID'] == $val['Admin_ID'] ){
						
						echo $_TF->html_select($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '請選擇'.$table_info[$Arr_Name]['Comment'], 0, $Group_Arr, '');
					}else{
						
						echo $_TF->html_select($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '請選擇'.$table_info[$Arr_Name]['Comment'], 1, $Group_Arr, '');
					}
					
					//-----------------------------------------//
					if( $Admin_data['Group_ID'] == 1 ){
						
						$Arr_Name = 'Tables_ID';
					
						echo $_TF->html_select($table_info[$Arr_Name]['Comment'], '(系統管理員才能修改)', $Arr_Name, $val, '', 1, $Tables_Arr, '');
					}
					//-----------------------------------------//
					$Arr_Name = 'Admin_Sdate';
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', '', $Arr_Name, $val, '', 0);
					//-----------------------------------------//
					$Arr_Name = 'Admin_LastLogin';
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', '', $Arr_Name, $val, '', 0);
					//-----------------------------------------//
					$Arr_Name = 'Admin_IP';
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', '', $Arr_Name, $val, '', 0);
					//-----------------------------------------//
					if( $Admin_data['Group_ID'] == 1 ){
						
						$Arr_Name = 'Admin_Checkbox';
					
						echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '(系統管理員才能修改)', $Arr_Name, $val, '', 1);
					}
					//-----------------------------------------//
					$Arr_Name = 'Admin_Open';
					
					if( $Admin_data['Admin_ID'] == $val['Admin_ID'] ){
						
						echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 0);
					}else{
						
						echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1);
					}
					//-----------------------------------------//
					if( $Admin_data['Admin_Permissions'] == 255 || $Admin_data['Group_ID'] == 1 ){
						
						$Arr_Name = 'Admin_Permissions';
					
						echo $_TF->html_number($table_info[$Arr_Name]['Comment'], '', '(ex. 0 ~ 255) 管理員權限255才能設定', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1, 0, 255);
					}
					?>    
                </div>
            <?php } ?>
            
                <div class="clear_both form-actions">
                    <button id="saveb" class="btn btn-info" type="button" onclick="form_edit_save()">
                        <i class="ace-icon fa fa-check bigger-110"></i>儲存
                    </button>&nbsp;&nbsp;&nbsp; 
                            
                    <button id="rsetb" class="btn btn" type="reset">
                        <i class="ace-icon fa fa-check bigger-110"></i>重設
                    </button>
                </div>
            </form>
		</div>
    </div>
</div>