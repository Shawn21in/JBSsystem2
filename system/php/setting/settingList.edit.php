<?php if( !function_exists('Chk_Login') ) header('Location: ../../index.php'); ?>

<div class="tab_container">

    <form id="form_edit_save" class="form-horizontal">

    <?php foreach( $_html_ as $key => $val ){ 
            
            $table_sn = 'tabsn'.$key;
    ?>
        <div class="Table_border <?=$table_sn?>">
        
            <input type="hidden" id="<?=$Main_Key?>" name="<?=$Main_Key?>[]" value="<?=$val[$Main_Key]?>">
                
            <?php
			
			if( $Settype == 1 ){ 
				
				//-----------------------------------------//

				$Arr_Name = 'Setting_BankCode';
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				
				//-----------------------------------------//		
				$Arr_Name = 'Setting_BankAcc';
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				
				//-----------------------------------------//
				$Arr_Name = 'Setting_BankName';
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				
				//-----------------------------------------//
			
			}else if( $Settype == 2 ) {
				
				//-----------------------------------------//

				$Arr_Name = 'Setting_Index01';
				
				echo $_TF->html_textarea($table_info[$Arr_Name]['Comment'], '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '');
				
				//-----------------------------------------//	
				
				$Arr_Name = 'Setting_Index02';
				
				echo $_TF->html_textarea($table_info[$Arr_Name]['Comment'], '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '');
				
				//-----------------------------------------//	
				
			}else if( $Settype == 3 ) {
				
				//-----------------------------------------//

				$Arr_Name = 'Setting_privacy';
				
				//echo $_TF->html_textarea($table_info[$Arr_Name]['Comment'], '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '');
				echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);
				//-----------------------------------------//	
				
			}else if( $Settype == 4 ) {
				
				//-----------------------------------------//

				$Arr_Name = 'Setting_Alert';
				
				echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);
				//-----------------------------------------//	
				
			}

			?> 
        </div>
    <?php } ?>
    
        <div class="clear_both form-actions">
            <button id="saveb" class="btn btn-info" type="button" <?=(Menu_Use($Now_List, 'edit')||Menu_Use($Now_List, 'add'))?'onclick="form_edit_save()"':'disabled="disabled"'?>>
                <i class="ace-icon fa fa-check bigger-110"></i>儲存
            </button>&nbsp;&nbsp;&nbsp; 
                    
            <button id="rsetb" class="btn btn" type="reset">
                <i class="ace-icon fa fa-check bigger-110"></i>重設
            </button>&nbsp;&nbsp;&nbsp; 
            
        <?php if( $WOtype == 3 ){ ?>
            <button id="sendb" class="btn btn eshow" e-txt="" type="button">
                <i class="ace-icon fa fa-envelope-o bigger-110"></i>測試寄信
            </button>
        <?php } ?>
        </div>
    </form>
</div>