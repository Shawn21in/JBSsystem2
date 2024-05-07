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
				$Arr_Name = 'Ecpay_SenderName';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '最多5個中文字(請勿輸入公司名)', 5, $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'Ecpay_SenderPhone';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '請輸入09開頭純數字手機號碼', 10, $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'Ecpay_MerchantID';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'Ecpay_HashKey';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'Ecpay_HashIV';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'Ecpay_Url';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'Ecpay_ReturnUrl';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'Ecpay_OrderResultURL';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'Ecpay_ClientRedirectURL';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				
			}else if( $Settype == 2 ){
				
				//-----------------------------------------//
				$Arr_Name = 'pay2go_MerchantID';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'pay2go_HashKey';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'pay2go_HashIV';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'pay2go_Url';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'pay2go_ReturnUrl';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
				//-----------------------------------------//
				$Arr_Name = 'pay2go_NotifyURL';
		 
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
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

<script type="text/javascript">$('.imgajax').colorbox({width:"70%", height:"100%", rel:'imgajax'});</script>