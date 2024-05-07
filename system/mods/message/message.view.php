<?php if( !function_exists('Chk_Login') ) if( !function_exists('Chk_Login') ) header('Location: ../../index.php'); ?>

<div class="form-horizontal">
<?php 
foreach( $_html_ as $key => $val ){ 

	foreach($table_option_arr as $tofield => $toarr ){
		
		if( empty($table_info[$tofield]) ){ continue; }
		
		$Comment = $table_info[$tofield]['Comment'];
		if( $toarr['TO_InType'] == 'uploadimg' ){
			
			$data = array(
				'comment' 	=> $Comment,
				'data' 	  	=> $val[$tofield],
				'bUrl' 		=> $val[$tofield.'_bUrl'],
				'sUrl' 		=> $val[$tofield.'_sUrl']
			);
		}else if( $toarr['TO_InType'] == 'select' ){
						
			if( $Main_Key3 == $tofield && !empty($Main_Table3) && !empty($Main_TablePre3) ){//有開啟分類資料表
							
				$select = $Class_Arr[$Main_Key3];			
			}else{
				
				$select = ($$toarr)['TO_SelStates'];
			}
			
			$data = array(
				'comment' 	=> $Comment,
				'data' 	  	=> $select[$val[$tofield]],
			);
		}else if( $toarr['TO_InType'] == 'address' && !empty($toarr['TO_ConnectField1']) ){
						
			$data = array(
				'comment' 	=> $Comment,
				'data' 	  	=> $val[$toarr['TO_ConnectField1']].' '.$val[$tofield],
			);
		}else if( $toarr['TO_InType'] == 'datetime' || $toarr['TO_InType'] == 'datetime1' || $toarr['TO_InType'] == 'datetimecreat' || $toarr['TO_InType'] == 'datetimeedit' ){
						
			$data = array(
				'comment' 	=> $Comment,
				'data' 	  	=> ForMatDate($val[$tofield], $toarr['TO_TimeFormat']),
			);
		}else{
			
			$data = array(
				'comment' 	=> $Comment,
				'data' 		=> $val[$tofield],
			);
			
			if( $tofield == 'Contact_conType' ) {
			
				global $contact_states;
				
				$data['data'] = $contact_states[$val[$tofield]];
			}
			
		}
		
		echo $_TF->html_view_content($data, $toarr['TO_InType']);
	} 
} 
?>
</div>