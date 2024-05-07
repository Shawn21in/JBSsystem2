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
                
                    <input type="hidden" id="Group_ID" name="Group_ID[]" value="<?=$val['Group_ID']?>">

                    <?php 
					//-----------------------------------------//
					$Arr_Name = 'Group_Name'; 
					
					echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '(ex. 一般管理員)', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
					//-----------------------------------------//
					$Arr_Name = 'Group_Lv'; 
					
					if( $val['Group_ID'] != 1 ){
						
						$val[$Arr_Name] = !empty($val[$Arr_Name]) ? $val[$Arr_Name] : $Admin_data['Group_Lv'] + 1;
					}
					
					echo $_TF->html_number($table_info[$Arr_Name]['Comment'], '', '(ex. ' .($Admin_data['Group_Lv']+1). ' ~ 255)', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, $table_info[$Arr_Name]['Comment'].'請輸入數字', 1, 0, 255);
					?>    
                    
                <?php if( count($_html) < 2 ){ ?>
                    <!-------------------------------------------------------------->
                    <?php $Arr_Name = Turnencode('Group_MenuUse'); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?=$table_info[$Arr_Name]['Comment']?></label>
                        <div class="col-sm-9 adminContainer">
                    <?php foreach( $MU_html as $Mkey => $Mval ){ ?>
                    
                            <div class="adminbox">

                                <div class="admin__group">
                                    <label class="control-label6 admin__group__tit">( 主類別 )</label>
                                    <div class="admin__group__option">
                                        <label class="control-label4">
                                            <input type="checkbox" name="<?=$Arr_Name?>[]" class="ace ace-switch ace-switch-7" value="<?=$Mval['Menu_ID']?>" <?=in_array($Mval['Menu_ID'], $MU_In_Arr)?'checked="checked"':''?>>
                                            <span class="lbl"></span>
                                        </label>
                                        <label class="control-label5 "><?=$Mval['Menu_Name']?></label>
                                    </div>
                                </div>
                            <?php if( !empty($MU_html2[$Mkey]) ){ ?>
                                
                                <?php foreach( $MU_html2[$Mkey] as $Mkey2 => $Mval2 ){ ?>
                                <div class="admin__group">
                                    <label class="control-label6 admin__group__tit">( 次類別 )</label>
                                     <div class="admin__group__option">
                                        <label class="control-label4">
                                            <input type="checkbox" name="<?=$Arr_Name?>[]" class="ace ace-switch ace-switch-7" value="<?=$Mval2['Menu_ID']?>" <?=in_array($Mval2['Menu_ID'], $MU_In_Arr)?'checked="checked"':''?>>
                                            <span class="lbl"></span>
                                        </label>
                                        <label class="control-label5"><?=$Mval2['Menu_Name']?></label>
                                        
                                        <label class="control-label4 eshow" e-txt="<?=$Mval2['Add_txt']?>">
                                            <input type="checkbox" name="<?=$Arr_Name?>[]" class="ace ace-switch ace-switch-7 " value="<?=$Mval2['Menu_ID']?>_1" <?=$Mval2['chk_Add']==1?'checked':''?> <?=$Mval2['chk_Add']==2?'disabled':''?>>
                                            <span class="lbl"></span>
                                        </label>
                                        <label class="control-label5">新增</label>
                                        
                                        <label class="control-label4 eshow" e-txt="<?=$Mval2['Edt_txt']?>">
                                            <input type="checkbox" name="<?=$Arr_Name?>[]" class="ace ace-switch ace-switch-7" value="<?=$Mval2['Menu_ID']?>_2" <?=$Mval2['chk_Edt']==1?'checked':''?> <?=$Mval2['chk_Edt']==2?'disabled':''?>>
                                            <span class="lbl"></span>
                                        </label>
                                        <label class="control-label5">編輯</label>
                                        
                                        <label class="control-label4 eshow" e-txt="<?=$Mval2['Del_txt']?>">
                                            <input type="checkbox" name="<?=$Arr_Name?>[]" class="ace ace-switch ace-switch-7" value="<?=$Mval2['Menu_ID']?>_3" <?=$Mval2['chk_Del']==1?'checked':''?> <?=$Mval2['chk_Del']==2?'disabled':''?>>
                                            <span class="lbl"></span>
                                        </label>
                                        <label class="control-label5">刪除</label>
                                        
                                        <label class="control-label4 eshow" e-txt="<?=$Mval2['View_txt']?>">
                                            <input type="checkbox" name="<?=$Arr_Name?>[]" class="ace ace-switch ace-switch-7" value="<?=$Mval2['Menu_ID']?>_4" <?=$Mval2['chk_View']==1?'checked':''?> <?=$Mval2['chk_View']==2?'disabled':''?>>
                                            <span class="lbl"></span>
                                        </label>
                                        <label class="control-label5">檢視</label>
                                    </div>
                                </div>
                                <?php } ?>	
                                
                            <?php } ?>		
                            </div>
                            
                    <?php } ?>
                        </div>
                    </div>
                <?php } ?>
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