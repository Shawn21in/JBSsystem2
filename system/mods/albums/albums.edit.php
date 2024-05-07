<?php if( !function_exists('Chk_Login') ) header('Location: ../../index.php'); ?>

<div class="abgne_tab">

    <ul class="tabs">
        <li><a href="javascript:void(0)" for-id="tab1">基本資料</a></li>
        <li><a href="javascript:void(0)" for-id="tab2">圖檔資料</a></li>
    </ul>
    
    <div class="tab_container">

		<form id="form_edit_save" class="form-horizontal">
            
            <?php foreach( $_html_ as $key => $val ){ 
			
				$city = $county = '';
				$table_sn = 'tabsn'.$key;
			?>
			<div class="Table_border <?=$table_sn?>">
				
                <div id="tab1" class="tab_content">
                
                    <input type="hidden" id="<?=$Main_Key?>" name="<?=$Main_Key?>[]" value="<?=$val[$Main_Key]?>">
				<?php 
				foreach( $table_option_arr as $tofield => $toarr ){
					
					if( empty($table_info[$tofield]) ){ continue; }
					
					$Comment = $table_info[$tofield]['Comment'];
					$Length  = $table_info[$tofield]['Field_Length'];			
					if( $toarr['TO_InType'] == 'checkbox' ){
												
						echo $_TF->html_checkbox($Comment, $toarr['TO_Comment2'], $tofield, $val, '', $toarr['TO_InEdit']);
					//-----------------------------------------//
					}else if( $toarr['TO_InType'] == 'uploadimg' ){
						
						$_msg = $toarr['TO_Must'] == 1 && empty($val[$tofield]) ? '請上傳'.$Comment : '';
												
						echo $_TF->html_uploadimg($Comment, $toarr['TO_Comment2'], $tofield, $val, $_msg, $toarr['TO_UploadSize']);
					//-----------------------------------------//
					}else if( $toarr['TO_InType'] == 'uploadfile' ){
						
						$_msg = $toarr['TO_Must'] == 1 && empty($val[$tofield]) ? '請上傳'.$Comment : '';
												
						echo $_TF->html_uploadfile($Comment, $toarr['TO_Comment2'], $tofield, $val, $_msg, $toarr['TO_UploadSize']);
					//-----------------------------------------//
					}else if( $toarr['TO_InType'] == 'number' || $toarr['TO_InType'] == 'sortasc' || $toarr['TO_InType'] == 'sortdesc' ){
						
						$_msg = $toarr['TO_Must'] == 1 ? '請輸入'.$Comment : '';
												
						$input_min = $toarr['TO_NumOpen'] ? $toarr['TO_Min'] : '';
						$input_max = $toarr['TO_NumOpen'] ? $toarr['TO_Max'] : '';
						
						echo $_TF->html_number($Comment, $toarr['TO_Comment1'], $toarr['TO_Comment2'], $Length, $tofield, $val, $_msg, $toarr['TO_InEdit'], $input_min, $input_max);
					//-----------------------------------------//
					}else if( $toarr['TO_InType'] == 'aznumber' ){
						
						$_msg = $toarr['TO_Must'] == 1 ? '請輸入'.$Comment : '';
						
						echo $_TF->html_aznumber($Comment, $toarr['TO_Comment1'], $toarr['TO_Comment2'], $Length, $tofield, $val, $_msg, $toarr['TO_InEdit']);
					//-----------------------------------------//
					}else if( $toarr['TO_InType'] == 'select' ){
						
						$_msg = $toarr['TO_Must'] == 1 ? '請選擇'.$Comment : '';
						
						if( $Main_Key3 == $tofield && !empty($Main_Table3) && !empty($Main_TablePre3) ){//有開啟分類資料表
							
							$select = $Class_Arr[$Main_Key3];			
						}else{
							
							$select = ($$toarr)['TO_SelStates'];
						}
						
						echo $_TF->html_select($Comment, $toarr['TO_Comment2'], $tofield, $val, $_msg, $toarr['TO_InEdit'], $select, 'NO_FIRST_OPTION');
					//-----------------------------------------//
					}else if( $toarr['TO_InType'] == 'datestart' || $toarr['TO_InType'] == 'dateend' || $toarr['TO_InType'] == 'datecreat' || $toarr['TO_InType'] == 'dateedit' ){
						
						$_msg = $toarr['TO_Must'] == 1 ? '請選擇'.$Comment : '';
						
						echo $_TF->html_datetime($Comment, $toarr['TO_Comment1'], $toarr['TO_Comment2'], $tofield, $val, $_msg, $toarr['TO_InEdit'], $key, $toarr['TO_InType'], $toarr['TO_ConnectField'], $toarr['TO_TimeFormat']);
					//-----------------------------------------//
					}else if( $toarr['TO_InType'] == 'textarea' ){
						
						$_msg = $toarr['TO_Must'] == 1 ? '請輸入'.$Comment : '';
						
						echo $_TF->html_textarea($Comment, $toarr['TO_Comment1'], $Length, $tofield, $val, $_msg);
					//-----------------------------------------//
					}else if( $toarr['TO_InType'] == 'textedit' ){
												
						echo $_TF->html_textedit($Comment, $tofield, $val);
					//-----------------------------------------//
					}else if( empty($toarr['TO_InType']) ){
											
						$_msg = $toarr['TO_Must'] == 1 ? '請輸入'.$Comment : '';
						
						echo $_TF->html_text($Comment, $toarr['TO_Comment1'], $toarr['TO_Comment2'], $Length, $tofield, $val, $_msg, $toarr['TO_InEdit']);
					}else{
				
						$val['Error'] = '請查看欄位種類是否已設定正確或者此模組不支援此欄位種類';
						echo $_TF->html_text($Comment, '', '', '', 'Error', $val, '', 0);	
					}	
				}
				?>
				</div>
                
                <div id="tab2" class="tab_content">
                	
				<?php
				$Radio_Arr = !empty($table_info[$Main_TablePre.'_Mcp']) ? array('name' => $Main_TablePre.'_Mcp') : array();
				
				foreach( $table_option_arr2 as $tofield2 => $toarr2 ){
					
					$Comment = $table_info2[$tofield2]['Comment'];
					if( $toarr2['TO_InType'] == 'uploadimg' ){
						
						$_msg = $toarr2['TO_Must'] == 1 && empty($val[$tofield2.'_Arr']) ? '請上傳'.$toarr2['Comment'] : '';
												
						echo $_TF->html_uploadimg($Comment, $toarr2['TO_Comment2'], $tofield2, $val, $_msg, $toarr2['TO_UploadSize'], true, $Radio_Arr);
					}
				}
                ?> 
                </div>
			<?php } ?>
            </div>
            
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

<script type="text/javascript">$('.imgajax').colorbox({width:"70%", height:"100%", rel:'imgajax'});</script>