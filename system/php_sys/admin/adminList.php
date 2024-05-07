<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

<script type="text/javascript" src="assets/js/sys-table.js"></script>
<script type="text/javascript">

var Exec_Url = 'post/SPOST_Admin.php?fun=' + getUrlVal(location.search, 'fun');
</script>

<div class="table-header">
	<span><?=$Now_List['Menu_Name']?></span>
    
<?php if( !empty($_Search_Option) ){ ?>
    <span class="extra-fun">
    	<i class="fa fa-tasks"></i>進階功能
    </span>
<?php } ?>
</div>

<?php require_once(SYS_PATH.'php_sys/extra_div.php')?>

<div id="table_content"><?=$_html?></div>

<div id="table_content_edit" class="display_none"></div>

<!--<div id="table_content_view" class="modal-demo">
    <button type="button" class="close" onclick="Custombox.close()">
        <span>×</span><span class="sr-only">Close</span>
    </button>
    <!--<h4 class="title">Modal title</h4>-->
    <!--<div class="contents"></div>
</div>-->

<div id="table_content_view" class="remodal" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div class="contents"></div>
</div>