<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

<div class="abgne_tab">

    <ul class="tabs">
        <li><a href="javascript:void(0)" for-id="tab1">基本資料</a></li>
    </ul>
    
    <div class="tab_container">
    
		<div id="tab1" class="tab_content">
        
            <form id="menu_edit_form" class="form-horizontal">
            
            <?php foreach( $_html_ as $key => $val ){ 
			
				$city = $county = '';
				$table_sn = 'tabsn'.$key;
			?>
				<div class="Table_border <?=$table_sn?>">
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?=$table_info[$Main_Key]['Comment']?></label>
                        <div class="col-sm-9">
                            <input type="text" id="<?=$Main_TablePre?>_ID" class="col-xs-12 col-sm-5" value="<?=$val[$Main_Key]?>" disabled="disabled">
                        </div>
                    </div>
                    
				<?php 
                //-----------------------------------------//
                if( !empty($Menu_Lv_Arr) ){ 
                
                    $Arr_Name = $Main_TablePre.'_UpMID';
                    
                    echo $_TF->html_select('第 ' .$menu_lv_states[($val[$Main_TablePre.'_Lv'] - 1)]. ' 層分類', '', $Arr_Name, $val, '', 1, $Menu_Lv_Arr, 'NO_FIRST_OPTION');
                }
                
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
                    }else if( $toarr['TO_InType'] == 'datestart' || $toarr['TO_InType'] == 'dateend' || $toarr['TO_InType'] == 'datecreat' || $toarr['TO_InType'] == 'dateedit' ){
						
						$_msg = $toarr['TO_Must'] == 1 ? '請選擇'.$Comment : '';
						
						echo $_TF->html_datetime($Comment, $toarr['TO_Comment1'], $toarr['TO_Comment2'], $tofield, $val, $_msg, $toarr['TO_InEdit'], $key, $toarr['TO_InType'], $toarr['TO_ConnectField'], $toarr['TO_TimeFormat']);
					//-----------------------------------------//
					}else if( empty($toarr['TO_InType']) ){
                                                
                        $_msg = $toarr['TO_Must'] == 1 ? '請輸入'.$Comment : '';
                        
                        echo $_TF->html_text($Comment, $toarr['TO_Comment1'], $toarr['TO_Comment2'], $Length, $tofield, $val, $_msg, $toarr['TO_InEdit']);
						
					}else if( $toarr['TO_InType'] == 'textedit' ){
                        
                        echo $_TF->html_textedit($Comment, $tofield, $val);
                    //-----------------------------------------//
                    
                    }else{
                        
                        $val['Error'] = '請查看欄位種類是否已設定正確或者此模組不支援此欄位種類';
                        echo $_TF->html_text($Comment, '', '', '', 'Error', $val, '', 0);	
                    } 
                } 
                ?>
                </div>
            <?php } ?>   
                <div class="clear_both form-actions">
                    <button id="saveb" class="btn btn-info" type="button" onclick="menu_edit_form()">
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

<script type="text/javascript">$('.imgajax').colorbox({width:"70%", height:"100%", rel:'imgajax'});</script>