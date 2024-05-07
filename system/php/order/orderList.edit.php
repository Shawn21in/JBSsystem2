<?php if( !function_exists('Chk_Login') ) header('Location: ../../index.php'); ?>

<div class="abgne_tab">
    <ul class="tabs">
        <li><a href="javascript:void(0)" for-id="tab1">基本資料</a></li>
    </ul>
    <div class="tab_container">
		
        <form id="form_edit_save" class="form-horizontal">
        
			<?php foreach( $_html_ as $key => $val ){ 
                    
                    $table_sn = 'tabsn'.$key;
            ?>
			<div class="Table_border <?=$table_sn?>">
            	
                <div id="tab1" class="tab_content">
                    <input type="hidden" id="<?=$Main_Key?>" name="<?=$Main_Key?>[]" value="<?=$val[$Main_Key]?>">
                    
                    <?php 
					//-----------------------------------------//
					$Arr_Name = 'Orderm_ID'; 
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', '', $Arr_Name, $val, '', 0);
					//-----------------------------------------//
					$Arr_Name = 'Member_ID'; 
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', '', $Arr_Name, $val, '', 0);
					//-----------------------------------------//
					$Arr_Name = 'Orderm_RName'; 
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
					//-----------------------------------------//
					$Arr_Name = 'Orderm_RMobile'; 
					
					echo $_TF->html_number($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1, '', '');
					//-----------------------------------------//
					$Arr_Name = 'Orderm_Freight'; 
					
					echo $_TF->html_number($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1, '', '');
					//-----------------------------------------//
					$Arr_Name = 'Orderm_TotalPrice'; 
					
					echo $_TF->html_number($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1, '', '');
					//-----------------------------------------//
					$Arr_Name = 'Orderm_Delivery'; 
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 0);
						
						//-----------------------------------------//
						$Arr_Name = 'Orderm_snid'; 
						
						echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 0);
						//-----------------------------------------//
						$Arr_Name = 'Orderm_stCode'; 
						
						echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 0);
						//-----------------------------------------//
						$Arr_Name = 'Orderm_stName'; 
						
						echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 0);
					
						
						//-----------------------------------------//
						$Arr_Name = 'Orderm_RCity';
						
						$selcity  	= '#'.$Arr_Name;
						$selcounty	= '#Orderm_RCounty';
						echo $_TF->html_city($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', $table_sn, $selcity, $selcounty);
						//-----------------------------------------//
						$Arr_Name = 'Orderm_RCounty';
						
						echo $_TF->html_county($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '');
						//-----------------------------------------//
						$Arr_Name = 'Orderm_RAddress'; 
						
						echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
					
					
					//-----------------------------------------//
					/*$Arr_Name = 'Orderm_PickupTime'; 
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);*/
					//-----------------------------------------//
					$Arr_Name = 'Orderm_Invoice'; 
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
					//-----------------------------------------//
					$Arr_Name = 'Orderm_Idn'; 
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
					//-----------------------------------------//
					$Arr_Name = 'Orderm_RNote';
					
					echo $_TF->html_textarea($table_info[$Arr_Name]['Comment'], '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '');
					//-----------------------------------------//
					$Arr_Name = 'Orderm_Sdate';
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', '', $Arr_Name, $val, '', 0);
					?>     
                </div>
                
                <div id="tab2" class="tab_content"></div>
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