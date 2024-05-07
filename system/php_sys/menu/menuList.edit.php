<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

<div class="abgne_tab">

    <ul class="tabs">
        <li><a href="javascript:void(0)" for-id="tab1">基本資料</a></li>
    </ul>
    
    <div class="tab_container">
    
		<div id="tab1" class="tab_content">
        
            <form id="menu_edit_form" class="form-horizontal">
            
                <div class="Table_border">
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?=$table_info['Menu_ID']['Comment']?></label>
                        <div class="col-sm-9">
                            <input type="text" id="Menu_ID" class="col-xs-12 col-sm-5" value="<?=$_html_['Menu_ID']?>" disabled>
                        </div>
                    </div>
                    
                    <!-------------------------------------------------------------->
                    <?php $Arr_Name = 'Menu_Name'; ?>
                    <div class="form-group">
                    	<?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5" value="<?=$_html_[$Arr_Name]?>" msg="請輸入<?=$table_info[$Arr_Name]['Comment']?>" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>">
                            
                            <?=$_TF->html_label_comment('(ex. 目錄列表)')?>
                        </div>
                    </div>
                    
                <?php if( $_html_['Menu_Lv'] == 1 ){ ?>
                    <!-------------------------------------------------------------->
                    <?php $Arr_Name = 'Menu_Smallpic'; ?>
                    <div class="form-group">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <select name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 padding_none">
                                <?=Select_Option($SmallArr, $_html_[$Arr_Name])?>
                            </select>
                        </div>
                    </div>
                <?php } ?>  
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_Lv'] == 2 ){ ?>
                    <?php $Arr_Name = 'Menu_UpMID'; ?>
                    <div class="form-group">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <select id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 padding_none">
                            	<?=Select_Option($Menu_Lv1_Arr, $_html_[$Arr_Name], 'NO_FIRST_OPTION')?>
                            </select>
                        </div>
                    </div>
                <?php } ?>
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_Mode'; ?>
                    <div class="form-group">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <select id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 padding_none" onChange="chg_mode($(this))">
                            	<?=Select_Option($Mode_Arr, $_html_[$Arr_Name])?>
                            </select>
                        </div>
                    </div>
                
                    <script type="text/javascript">
					
					$(document).ready(function(e) {
                        
						chg_mode($('#<?=$Arr_Name?>'));
                    });
					</script>
                <?php } ?>
                
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_Exec'; ?>
                    <div class="form-group mode mode1 mode4">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 " value="<?=$_html_[$Arr_Name]?>" msg="請輸入<?=$table_info[$Arr_Name]['Comment']?>" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>">
                            
                            <?=$_TF->html_label_comment('(ex. menu.php)')?>
                        </div>      
                    </div>
                <?php } ?>
                    
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_Path'; ?>
                    <div class="form-group mode mode1 mode4">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 " value="<?=$_html_[$Arr_Name]?>" msg="請輸入<?=$table_info[$Arr_Name]['Comment']?>" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>">
                            
                            <?=$_TF->html_label_comment('(ex. html_sys)')?>
                        </div>
                    </div>
                <?php } ?>
                  
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_TableName'; ?>
                    <div class="form-group mode mode1 mode3 mode4">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <select name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 padding_none" onchange="chg_tablekey(this.value, '#Menu_TableKey')">
                                <option value="">請選擇</option>
                            <?php foreach( $TablesArr as $key => $val ){ ?>
                                <option value="<?=$val['Name']?>" <?=$val['Name']==$_html_[$Arr_Name]?'selected="selected"':''?>><?=$val['Title']?></option>
                            <?php } ?>
                            </select>
                            
                            <?=$_TF->html_label_comment('(ex. sys_menu)')?>
                        </div>
                    </div>
                <?php } ?>
                    
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_TableKey'; ?>
                    <div class="form-group mode mode1 mode3 mode4">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <select id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 padding_none" onChange="chg_tablepre(this.value, '#Menu_TablePre')">
                            <?=Select_Option($Table_Field_Arr, $_html_[$Arr_Name])?>
                            </select>
                        </div>
                    </div>
                <?php } ?>
                    
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_TablePre'; ?>
                    <div class="form-group mode mode1 mode3 mode4">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 " value="<?=$_html_[$Arr_Name]?>" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>">
                            
                            <?=$_TF->html_label_comment('(ex. Menu)')?>
                        </div>
                    </div>
                <?php } ?>
                
                	<!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_Link'; ?>
                    <div class="form-group mode mode2">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5" value="<?=htmlspecialchars($_html_[$Arr_Name], ENT_QUOTES)?>" msg="請輸入<?=$table_info[$Arr_Name]['Comment']?>" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>">
                        </div>
                    </div>
                <?php } ?>
                    
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_OrderBy'; ?>
                    <div class="form-group mode mode1 mode3 mode4">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 " value="<?=$_html_[$Arr_Name]?>" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>" placeholder="非必填">
                            
                            <?=$_TF->html_label_comment('(ex. ORDER BY Menu_ID DESC)')?>
                        </div>
                    </div>
                <?php } ?>
                
                <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_CstSnPre'; ?>
                    <div class="form-group mode mode1 mode3 mode4">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 " value="<?=$_html_[$Arr_Name]?>" input-type="aznumber" input-name="<?=$table_info[$Arr_Name]['Comment']?>" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>" placeholder="非必填">
                            
                            <?=$_TF->html_label_comment('開啟自訂編號非必填項目')?>
                        </div>
                    </div>
                <?php } ?>
                
                <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_CstSnType'; ?>
                    <div class="form-group mode mode1 mode3 mode4">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        	
                            <select id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 padding_none">
                            	<option value="">請選擇</option>
                            <?=Select_Option($CstSn_Arr, $_html_[$Arr_Name], 'NO_FIRST_OPTION')?>
                            </select>
                            
                            <?=$_TF->html_label_comment('開啟自訂編號非必填項目')?>
                        </div>
                    </div>
                <?php } ?>
                
				
				
                <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_CstSnNum'; ?>
                    <div class="form-group mode mode1 mode3 mode4">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 eshow" e-txt="ex: 自訂編號前輟輸入 ( CL ), 自訂編號種類選擇 ( 依日期取後六碼 ), 自訂編號流水碼數輸入 ( 3 ), 結果為 CL160929001" value="<?=$_html_[$Arr_Name]?>" msg="<?=$table_info[$Arr_Name]['Comment']?>請輸入數字" input-type="number" input-name="<?=$table_info[$Arr_Name]['Comment']?>" input-min="0" input-max="255" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>">
                            
                            <?=$_TF->html_label_comment('開啟自訂編號必填項目 ( 數字大於1才會啟用 )')?>
                        </div>
                    </div>
                <?php } ?>
                
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_Model'; ?>
                    <div class="form-group mode mode3">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <select id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 padding_none" onChange="chg_model($(this))">
                            <?=Select_Option($ModelArr, $_html_[$Arr_Name])?>
                            </select>
                        </div>
                    </div>
                    
                    <script type="text/javascript">
					
					$(document).ready(function(e) {
                        
						//chg_model($('#<?=$Arr_Name?>'));
                    });
					</script>
                <?php } ?>

                <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_TableName1'; ?>
                    <div class="form-group mode mode4 model m-albums">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <select name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 padding_none" onchange="chg_tablekey(this.value, '#Menu_TableKey1')">
                                <option value="">請選擇</option>
                            <?php foreach( $TablesArr as $key => $val ){ ?>
                                <option value="<?=$val['Name']?>" <?=$val['Name']==$_html_[$Arr_Name]?'selected':''?>><?=$val['Title']?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                <?php } ?>
                    
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_TableKey1'; ?>
                    <div class="form-group mode mode4 model m-albums">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <select id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 padding_none" onChange="chg_tablepre(this.value, '#Menu_TablePre1')">
                            <?=Select_Option($Table_Field_Arr1, $_html_[$Arr_Name])?>
                            </select>
                        </div>
                    </div>
                <?php } ?>
                    
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_TablePre1'; ?>
                    <div class="form-group mode mode4 model m-albums">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 " value="<?=$_html_[$Arr_Name]?>" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>">
                        </div>
                    </div>
                <?php } ?>
                
                <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_TableName2'; ?>
                    <div class="form-group mode mode4 model m-albums m-table">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <select name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 padding_none" onchange="chg_tablekey(this.value, '#Menu_TableKey2')">
                                <option value="">請選擇</option>
                            <?php foreach( $TablesArr as $key => $val ){ ?>
                                <option value="<?=$val['Name']?>" <?=$val['Name']==$_html_[$Arr_Name]?'selected="selected"':''?>><?=$val['Title']?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                <?php } ?>
                    
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_TableKey2'; ?>
                    <div class="form-group mode mode4 model m-albums m-table">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <select id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 padding_none" onChange="chg_tablepre(this.value, '#Menu_TablePre2')">
                            <?=Select_Option($Table_Field_Arr2, $_html_[$Arr_Name])?>
                            </select>
                        </div>
                    </div>
                <?php } ?>
                    
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_TablePre2'; ?>
                    <div class="form-group mode mode4 model m-albums m-table">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 " value="<?=$_html_[$Arr_Name]?>" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>">
                        </div>
                    </div>
                <?php } ?>
                
                <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_SysUse'] != 1 && $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_ClassMax'; ?>
                    <div class="form-group mode mode4 model m-albums m-class m-table">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" id="<?=$Arr_Name?>" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5 " value="<?=$_html_[$Arr_Name]?>" msg="<?=$table_info[$Arr_Name]['Comment']?>請輸入數字" input-type="number" input-name="<?=$table_info[$Arr_Name]['Comment']?>" input-min="1" input-max="255" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>">
                        </div>
                    </div>
                <?php } ?>
                
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_Add'; ?>
                    <div class="form-group mode mode1 mode3 mode4 model m-albums m-class m-table">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <label class="control-label4">
                                <input type="checkbox" name="<?=$Arr_Name?>" class="ace ace-switch ace-switch-7"  value="1" <?=$_html_[$Arr_Name]?'checked="checked"':''?>>
                                <span class="lbl"></span>
                            </label>
                        </div>
                    </div>
                <?php } ?>
                    
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_Edt'; ?>
                    <div class="form-group mode mode1 mode3 mode4 model m-albums m-message m-class m-table">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <label class="control-label4">
                                <input type="checkbox" name="<?=$Arr_Name?>" class="ace ace-switch ace-switch-7"  value="1" <?=$_html_[$Arr_Name]?'checked="checked"':''?>>
                                <span class="lbl"></span>
                            </label>
                        </div>
                    </div>
                <?php } ?>
                    
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_Del'; ?>
                    <div class="form-group mode mode1 mode3 mode4 model m-albums m-message m-class m-table">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <label class="control-label4">
                                <input type="checkbox" name="<?=$Arr_Name?>" class="ace ace-switch ace-switch-7"  value="1" <?=$_html_[$Arr_Name]?'checked="checked"':''?>>
                                <span class="lbl"></span>
                            </label>
                        </div>
                    </div>
                <?php } ?>
                    
                    <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_View'; ?>
                    <div class="form-group mode mode1 mode3 mode4 model m-message m-table">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <label class="control-label4">
                                <input type="checkbox" name="<?=$Arr_Name?>" class="ace ace-switch ace-switch-7"  value="1" <?=$_html_[$Arr_Name]?'checked="checked"':''?>>
                                <span class="lbl"></span>
                            </label>
                        </div>
                    </div>
				<?php } ?>
                
                <!-------------------------------------------------------------->
                <?php if( $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_Albums_Edt'; ?>
                    <div class="form-group mode mode1 mode3 mode4 model m-albums">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <label class="control-label4">
                                <input type="checkbox" name="<?=$Arr_Name?>" class="ace ace-switch ace-switch-7"  value="1" <?=$_html_[$Arr_Name]?'checked="checked"':''?>>
                                <span class="lbl"></span>
                            </label>
                            
                            <label class="control-label6">相簿專用</label>
                        </div>
                    </div>
                <?php } ?>
                
                	<!-------------------------------------------------------------->
                <?php if( $_html_['Menu_Lv'] == 2 && $Admin_data['Group_ID'] == 1 ){ ?>
                    <?php $Arr_Name = 'Menu_Albums_Mpc'; ?>
                    <div class="form-group mode mode1 mode3 mode4 model m-albums">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <label class="control-label4">
                                <input type="checkbox" name="<?=$Arr_Name?>" class="ace ace-switch ace-switch-7"  value="1" <?=$_html_[$Arr_Name]?'checked="checked"':''?>>
                                <span class="lbl"></span>
                            </label>
                            
                            <label class="control-label6">相簿專用</label>
                        </div>
                    </div>
                <?php } ?>
                
                    <!-------------------------------------------------------------->
                    <?php $Arr_Name = 'Menu_Sort'; ?>
                    <div class="form-group">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5" value="<?=$_html_[$Arr_Name]?>" msg="<?=$table_info[$Arr_Name]['Comment']?>請輸入數字" input-type="number" input-name="<?=$table_info[$Arr_Name]['Comment']?>" input-min="0" input-max="99999" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>">
                            
                            <?=$_TF->html_label_comment('(ex. 0 ~ 99999)')?>
                        </div>
                    </div>
                    
                <?php if( $Admin_data['Admin_Permissions'] == 255 || $Admin_data['Group_ID'] == 1 ){ ?>
                    <!-------------------------------------------------------------->
                    <?php $Arr_Name = 'Menu_Permissions'; ?>
                    <div class="form-group">
                        <?=$_TF->html_label_titile($table_info[$Arr_Name]['Comment'])?>
                        <div class="col-sm-9">
                        
                            <input type="text" name="<?=$Arr_Name?>" class="col-xs-12 col-sm-5" value="<?=$_html_[$Arr_Name]?>" msg="<?=$table_info[$Arr_Name]['Comment']?>請輸入數字" input-type="number" input-name="<?=$table_info[$Arr_Name]['Comment']?>" input-min="0" input-max="255" maxlength="<?=$table_info[$Arr_Name]['Field_Length']?>">
                            
                            <?=$_TF->html_label_comment('(ex. 0 ~ 255 ) 管理員權限255才能設定')?>
                        </div>
                    </div>
                <?php } ?>
                
                </div>   
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