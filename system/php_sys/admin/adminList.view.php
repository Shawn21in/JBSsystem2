<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

<div class="form-horizontal">
<?php 
foreach( $_html_ as $key => $val ){ 

	echo $_TF->html_view_content(array('comment' => $table_info['Admin_Acc']['Comment'], 'data' => $val['Admin_Acc']));
	
	echo $_TF->html_view_content(array('comment' => $table_info['Admin_Name']['Comment'], 'data' => $val['Admin_Name']));
	
	echo $_TF->html_view_content(array('comment' => $table_info['Group_ID']['Comment'], 'data' => $val['Group_Name']));
	
	if( $Admin_data['Group_ID'] == 1 ){
		
		echo $_TF->html_view_content(array('comment' => $table_info['Tables_ID']['Comment'], 'data' => $Tables_Arr[$val['Tables_ID']]));
	}
	
	echo $_TF->html_view_content(array('comment' => $table_info['Admin_Sdate']['Comment'], 'data' => $val['Admin_Sdate']));
	
	echo $_TF->html_view_content(array('comment' => $table_info['Admin_LastLogin']['Comment'], 'data' => $val['Admin_LastLogin']));
	echo $_TF->html_view_content(array('comment' => $table_info['Admin_IP']['Comment'], 'data' => $val['Admin_IP']));
	
	if( $Admin_data['Group_ID'] == 1 ){
		 
		echo $_TF->html_view_content(array('comment' => $table_info['Admin_Checkbox']['Comment'], 'data' => $val['Admin_Checkbox']), 'checkbox');
	}
	
	echo $_TF->html_view_content(array('comment' => $table_info['Admin_Open']['Comment'], 'data' => $val['Admin_Open']), 'checkbox');
	
	if( $Admin_data['Admin_Permissions'] == 255 || $Admin_data['Group_ID'] == 1 ){
		 
		echo $_TF->html_view_content(array('comment' => $table_info['Admin_Permissions']['Comment'], 'data' => $val['Admin_Permissions']?$val['Admin_Permissions']:0));
	}
} 
?>
</div>