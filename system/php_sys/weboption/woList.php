<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

<script type="text/javascript" src="assets/js/sys-table.js"></script>
<script type="text/javascript" src="assets/js/sys-woption.js"></script>

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