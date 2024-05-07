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
                
                    <input type="hidden" id="Tables_ID" name="Tables_ID[]" value="<?=$val['Tables_ID']?>">
                    
                    <?php 
					//-----------------------------------------//
					$Arr_Name = 'Tables_Name';
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '(ex. new_sys)', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '請輸入'.$table_info[$Arr_Name]['Comment'], 1); 
					//-----------------------------------------//
					$Arr_Name = 'Tables_Name1';
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '(ex. 系統資料庫)', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '請輸入'.$table_info[$Arr_Name]['Comment'], 1); 
					//-----------------------------------------//
					$Arr_Name = 'Tables_Open';
					
					echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1);
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