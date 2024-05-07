<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

<script type="text/javascript" src="assets/js/sys-table.js"></script>
<script type="text/javascript">

var Exec_Url = 'post/SPOST_Group.php?fun=' + getUrlVal(location.search, 'fun');

</script>

<div class="table-header"><?=$Now_List['Menu_Name']?></div>

<div id="table_content"><?=$_html?></div>

<div id="table_content_edit" class="display_none"></div>