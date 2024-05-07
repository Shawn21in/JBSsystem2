<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

<script type="text/javascript" src="assets/js/sys-menu.js?<?=time()?>"></script>
<script type="text/javascript">
var Exec_Url = 'post/SPOST_Menu.php?fun=' + getUrlVal(location.search, 'fun');
</script>
<div class="table-header"><?=$Now_List['Menu_Name']?></div>

<div id="menu_content">

	<div class="content_top_option" style="padding-bottom: 10px;">
    	<button type="button" class="btn btn-white btn-purple btn-sm menu_create" mlv="1" <?=(Menu_Use($Now_List, 'edit')&&Menu_Use($Now_List, 'add'))?'':'disabled="disabled"'?>>
            <span>新增主類別</span>
        </button>
        
        <button type="button" class="btn btn-white btn-purple btn-sm menu_show">
            <span>全展開</span>
        </button>
        
        <button type="button" class="btn btn-white btn-purple btn-sm menu_hide">
            <span>全縮起</span>
        </button>
        
        <input type="hidden" class="tablepre" value="<?=$Main_TablePre?>" />
        <input type="hidden" name="<?=$Main_TablePre?>_Name[]" class="menu_input1 eshow" e-txt="第1層<?=$table_info[$Main_TablePre.'_Name']['Comment']?>" maxlength="<?=$table_info[$Main_TablePre.'_Name']['Field_Length']?>">
		<input type="hidden" name="<?=$Main_TablePre?>_Sort[]" class="menu_input2 eshow" e-txt="排序" maxlength="<?=$table_info[$Main_TablePre.'_Sort']['Field_Length']?>" >
    </div>
    
    <form id="menu_form" method="post" action="">
    
        <div class="menu_list">
        
        <?php foreach( $_html as $key => $val ){ ?>
            
            <div class="content">
            
                <i class="icon fa fa-minus-circle" data-op="1"></i>
                
                <input type="hidden" name="<?=$Main_TablePre?>_ID[]" value="<?=$val[$Main_TablePre.'_ID']?>">
                <input type="hidden" name="<?=$Main_TablePre?>_Lv[]" value="<?=$val[$Main_TablePre.'_Lv']?>">
                <input type="text" name="<?=$Main_TablePre?>_Name[]" value="<?=$val[$Main_TablePre.'_Name']?>" class="menu_input1 eshow" e-txt="目錄名稱" msg="請輸入主目錄名稱" maxlength="<?=$table_info[$Main_TablePre.'_Name']['Field_Length']?>">
                <input type="text" name="<?=$Main_TablePre?>_Sort[]" value="<?=$val[$Main_TablePre.'_Sort']?>" class="menu_input2 eshow" e-txt="排序" msg="排序請輸入數字" input-type="number" input-min="0" input-max="99999" maxlength="<?=$table_info[$Main_TablePre.'_Sort']['Field_Length']?>" >
               
            <?php if( $Admin_data['Admin_Permissions'] == 255 || $Admin_data['Group_ID'] == 1 ){ ?>
            	<button type="button" class="btn btn-white <?=$val[$Main_TablePre.'_SysUse']==1?'btn-danger':'btn-inverse'?> btn-sm menu_btn eshow" e-txt="權限">
                    <span><?=$val[$Main_TablePre.'_Permissions']?></span>
                </button>
            <?php } ?>
               
                <button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_create" mlv="2" <?=(Menu_Use($Now_List, 'edit')&&Menu_Use($Now_List, 'add'))?'':'disabled="disabled"'?>>
                    <span>新增次類別</span>
                </button>
                
                <button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_edit" <?=Menu_Use($Now_List, 'edit')?'':'disabled="disabled"'?>>
                    <span>編輯</span>
                </button>
    
                <button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_del" <?=Menu_Use($Now_List, 'delete')?'':'disabled="disabled"'?>>
                    <span>刪除</span>
                </button>
                
                <?=menu_create_lv($key, $_html2, 'Main')?>
            </div>
        <?php } ?>
        </div>
    
        <div class="clearfix form-actions">
            <div class="col-md-12">
                <button id="saveb" class="btn btn-info menu_save" type="button" <?=(Menu_Use($Now_List, 'edit'))?'':'disabled="disabled"'?>>
                    <i class="ace-icon fa fa-check bigger-110"></i>儲存
                </button>&nbsp;&nbsp;&nbsp; 
                
                <button id="rsetb" class="btn btn" type="reset">
                    <i class="ace-icon fa fa-check bigger-110"></i>重設
                </button>
            </div>
        </div>
    </form> 
</div>

<div id="menu_content_edit" class="display_none">
</div>