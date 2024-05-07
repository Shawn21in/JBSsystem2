<?php 
if( !function_exists('Chk_Login') ) header('Location: ../index.php'); 

$table_info = $db->get_table_info($Main_Table);//取出資料表欄位的詳細的訊息			

$_html = $_html2 = array();
		
$db->Where = " WHERE " .$Main_TablePre. "_Lv <= '" .$Main_maxLv3. "'";

$db->Order_By = " ORDER BY " .$Main_TablePre. "_Lv ASC, " .$Main_TablePre. "_Sort DESC, " .$Main_TablePre. "_ID DESC";

$db->query_sql($Main_Table, "*");
while( $row = $db->query_fetch() ){
	
	if( $row[$Main_TablePre.'_Lv'] == 1 ){
		
		$_html[$row[$Main_TablePre.'_ID']] = $row;
	}else{
		
		$_html2[$row[$Main_TablePre.'_UpMID']][] = $row;
	}
}
?>

<script type="text/javascript" src="assets/js/sys-menu.js"></script>
<script type="text/javascript">

var Exec_Url = '<?=$Now_List['Menu_Path']?>/<?=$Now_List['Menu_Exec_Name']?>.post.php?fun=' + getUrlVal(location.search, 'fun');
</script>
<div class="table-header"><?=$Now_List['Menu_Name']?></div>

<div id="menu_content">

	<div class="content_top_option" style="padding-bottom: 10px;">
    	<button type="button" class="btn btn-white btn-purple btn-sm menu_create" mlv="1" <?=(Menu_Use($Now_List, 'edit')&&Menu_Use($Now_List, 'add'))?'':'disabled="disabled"'?>>
            <span>新增第一層</span>
        </button>
    <?php if( $Main_maxLv3 > 1 ){ ?> 
        <button type="button" class="btn btn-white btn-purple btn-sm menu_show">
            <span>全展開</span>
        </button>
    
        <button type="button" class="btn btn-white btn-purple btn-sm menu_hide">
            <span>全縮起</span>
        </button>
    <?php } ?>
        
        <input type="hidden" class="tablepre" value="<?=$Main_TablePre?>" />
        <input type="hidden" name="<?=$Main_TablePre?>_Name[]" class="menu_input1 eshow" e-txt="<?=$table_info[$Main_TablePre.'_Name']['Comment']?>" maxlength="<?=$table_info[$Main_TablePre.'_Name']['Field_Length']?>">
		<input type="hidden" name="<?=$Main_TablePre?>_Sort[]" class="menu_input2 eshow" e-txt="排序" maxlength="<?=$table_info[$Main_TablePre.'_Sort']['Field_Length']?>" >
    </div>
    
    <form id="menu_form" method="post" action="">
    
        <div class="menu_list">
        
        <?php foreach( $_html as $key => $val ){ ?>
            
            <div class="content">
            
                <i class="icon fa fa-minus-circle" data-op="1"></i>
                
                <input type="hidden" name="<?=$Main_TablePre?>_ID[]" value="<?=$key?>">
                <input type="hidden" name="<?=$Main_TablePre?>_Lv[]" value="<?=$val[$Main_TablePre.'_Lv']?>">
                <input type="text" name="<?=$Main_TablePre?>_Name[]" value="<?=$val[$Main_TablePre.'_Name']?>" class="menu_input1 eshow" e-txt="<?=$table_info[$Main_TablePre.'_Name']['Comment']?>" msg="請輸入<?=$table_info[$Main_TablePre.'_Name']['Comment']?>" maxlength="<?=$table_info[$Main_TablePre.'_Name']['Field_Length']?>">
                <input type="text" name="<?=$Main_TablePre?>_Sort[]" value="<?=$val[$Main_TablePre.'_Sort']?>" class="menu_input2 eshow" e-txt="<?=$table_info[$Main_TablePre.'_Sort']['Comment']?>" input-type="number" input-min="0" input-max="99999" maxlength="<?=$table_info[$Main_TablePre.'_Sort']['Field_Length']?>" >
            
			<?php if( (Menu_Use($Now_List, 'edit')) ){ ?>
                <button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_display" msh="<?=$val[$Main_TablePre.'_Open']?>">
			<?php }else{ ?>
                <button type="button" class="btn btn-white btn-inverse btn-sm" disabled>
			<?php } ?>
                    <span class="<?=$val[$Main_TablePre.'_Open']?'color-blue':'color-red'?>"><?=$val[$Main_TablePre.'_Open']?'顯示':'隱藏'?></span>
                </button>
                
            <?php if( $Main_maxLv3 > 1 ){ ?>           
                <button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_create" mlv="2" <?=(Menu_Use($Now_List, 'edit')&&Menu_Use($Now_List, 'add'))?'':'disabled="disabled"'?>>
                    <span>新增第二層</span>
                </button>
            <?php } ?>
            <!--
                <button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_edit" <?=Menu_Use($Now_List, 'edit')&&($val[$Main_TablePre.'_ID']!='Pr0007')?'':'disabled="disabled"'?>>
                    <span>編輯</span>
                </button>
			-->  
                <button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_del" <?=Menu_Use($Now_List, 'delete')?'':'disabled="disabled"'?>>
                    <span>刪除</span>
                </button>
             
                <?=menu_create_lv($key, $_html2, 'showhide')?>
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