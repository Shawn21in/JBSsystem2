<?php if( !function_exists('Chk_Login') ) header('Location: ../../index.php'); ?>

<div class="tab_container">
    
    <form id="form_psave" class="form-horizontal">
    
        <div class="Table_border">
        
            <input type="hidden" id="<?=$Main_Key2?>" name="<?=$Main_Key2?>" value="<?=$_html_[0][$Main_Key2]?>">
            
            <!-------------------------------------------------------------->
            <div class="form-group">
                <div class="col-sm-12">
                    
                    <img src="<?=$_html_[0]['Img_sUrl']?>" class="img-mw100">
                </div>
            </div>
        <?php
        foreach( $table_option_arr2 as $tofield => $toarr ){
			
			if( empty($table_info2[$tofield]) ){ continue; }
				
			$Comment = $table_info2[$tofield]['Comment'];
			$Length  = $table_info2[$tofield]['Field_Length'];	
			if( $toarr['TO_InType'] == 'checkbox' ){
												
			//-----------------------------------------//
			}else if( $toarr['TO_InType'] == 'uploadimg' ){
			
			//-----------------------------------------//
			}else if( $toarr['TO_InType'] == 'sortasc' || $toarr['TO_InType'] == 'sortdesc' ){
							
			//-----------------------------------------//
			}else if( $toarr['TO_InType'] == 'datestart' || $toarr['TO_InType'] == 'dateend' || $toarr['TO_InType'] == 'datecreat' || $toarr['TO_InType'] == 'dateedit' ){
						
				$_msg = $toarr['TO_Must'] == 1 ? '請選擇'.$Comment : '';
				
				echo $_TF->html_datetime($Comment, $toarr['TO_Comment1'], $toarr['TO_Comment2'], $tofield, $_html_[0], $_msg, $toarr['TO_InEdit'], $key, $toarr['TO_InType'], $toarr['TO_ConnectField']);
			//-----------------------------------------//
			}else if( $toarr['TO_InType'] == 'textarea' ){
				
				$_msg = $toarr['TO_Must'] == 1 ? '請輸入'.$Comment : '';
				
				echo $_TF->html_textarea($Comment, $toarr['TO_Comment1'], $Length, $tofield, $_html_[0], $_msg);
			//-----------------------------------------//
			}else if( empty($toarr['TO_InType']) ){
									
				$_msg = $toarr['TO_Must'] == 1 ? '請輸入'.$Comment : '';
				
				echo $_TF->html_text($Comment, $toarr['TO_Comment1'], $toarr['TO_Comment2'], $Length, $tofield, $_html_[0], $_msg, $toarr['TO_InEdit']);
			}else{
				
				$val['Error'] = '請查看欄位種類是否已設定正確或者此模組不支援此欄位種類';
				echo $_TF->html_text($Comment, '', '', '', 'Error', $val, '', 0);	
			}	
		}
		?>
        </div>
    
        <div class="clear_both form-actions">
            <button id="saveb" class="btn btn-info" type="button" onClick="form_psave()">
                <i class="ace-icon fa fa-check bigger-110"></i>儲存
            </button>&nbsp;&nbsp;&nbsp; 
                    
            <button id="rsetb" class="btn btn" type="reset">
                <i class="ace-icon fa fa-check bigger-110"></i>重設
            </button>
        </div>
    </form>
</div>