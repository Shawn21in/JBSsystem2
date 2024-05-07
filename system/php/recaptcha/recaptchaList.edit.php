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

				$Arr_Name = 'Setting_Aboutus';
				
				echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);
				
				//-----------------------------------------//			
			
			}else if( $Settype == 2 ) {
				
				//-----------------------------------------//

				$Arr_Name = 'Setting_Vision';
				
				echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);
				
				//-----------------------------------------//	
				
			}else if( $Settype == 201 ) {
				
				//-----------------------------------------//

				$Arr_Name = 'Setting_Custom1_Open';
				
				echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1);
				
				//-----------------------------------------//

				$Arr_Name = 'Setting_Custom1_Name';
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				
			}else if( $Settype == 202 ) {
				
				//-----------------------------------------//
	
				$Arr_Name = 'Setting_Custom2_Open';
				
				echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1);
				
				//-----------------------------------------//

				$Arr_Name = 'Setting_Custom2_Name';
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				
			}
			else if( $Settype == 203 ) {
				
				//-----------------------------------------//
	
				$Arr_Name = 'Setting_Youtube_Title';
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				
				//-----------------------------------------//

				$Arr_Name = 'Setting_Youtube';
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				
			}
			else if( $Settype == 204 ) {
				
				//-----------------------------------------//
	
				$Arr_Name = 'Setting_Youtube_Title';
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				
				//-----------------------------------------//

				$Arr_Name = 'Setting_Youtube';
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				
			}
			else if( $Settype == 205 ) {
				
					$Arr_Name = 'Setting_Guide';
				
				echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);
			}
			
			else if( $Settype == 206 ) {
				
				$Arr_Name = 'Setting_Security';
				
				echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);
			}
			else if( $Settype == 207 ) {
	
				$Arr_Name = 'Setting_Opendata';
				
				echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);
			}
			else if( $Settype == 208 ) {
				
				$Arr_Name = 'Setting_Copyright';
				
				echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);
			}
			else if( $Settype == 209 ) {
				
				$Arr_Name = 'Setting_Privacy';
				
				echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);
			}
			else if( $Settype == 210 ) {
				
				$Arr_Name = 'Setting_Plan';
				
				echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);
			}
			else if( $Settype == 301 ) {
				
				$Arr_Name = 'Setting_statist_exp';
				
				echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'Setting_info_exp';
				
				echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1);
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