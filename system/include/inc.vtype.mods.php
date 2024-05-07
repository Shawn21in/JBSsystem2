<!-------------------------------------------------->	
<?php if( $Vtype == 'checkbox' ){ ?>

<td>
	<?php if( Menu_Use($Now_List, 'edit') && $table_option_arr[$key_t]['TO_OutEdit'] ){ ?>
	<label>
		<input type="checkbox" class="ace ace-switch ace-switch-6 TableCheck" value="1" check-type="checkbox" check-id="<?=$val_v?>" check-field="<?=$Turncode?>" <?=$Value?'checked="checked"':''?>>
		<span class="lbl lb2 eshow" e-txt="直接點選更新"></span>
	</label>
	<?php }else{ ?>
	
	<span class="<?=$Value?'control-label7':'control-label3'?>"><?=$Value?'啟用':'停用'?></span>
	<?php } ?>
</td>	
<!-------------------------------------------------->	
<?php }else if( $Vtype == 'datestart' || $Vtype == 'dateend' || $Vtype == 'datecreat' || $Vtype == 'dateedit' ){ 
				
		$Time_ID 		= $Turncode.'i'.$Vsn;
		
		$Time_Format 	= $table_option_arr[$key_t]['TO_TimeFormat'];
		
		$Value			= ForMatDate($Value, $Time_Format);
		
		$ConnectField 	= !empty($table_option_arr[$key_t]['TO_ConnectField']) ? Turnencode($table_option_arr[$key_t]['TO_ConnectField']).'i'.$Vsn : '';
?>
	
<td>
	<?php if( Menu_Use($Now_List, 'edit') && $table_option_arr[$key_t]['TO_OutEdit'] ){ ?>
	
	<div class='control-div2'>
		<label>
			<input type="text" id="<?=$Time_ID?>" class="table-dateitme eshow" e-txt="點選日期更新" value="<?=$Value?>" check-type="datetime" check-id="<?=$val_v?>" check-field="<?=$Turncode?>" check-data="<?=$Value?>" check-datatype="<?=$Vtype?>" check-connectfield="<?=$ConnectField?>" check-name="<?=$val_t?>">
		</label>
	</div>
	<script type="text/javascript">Datetimepicker('#<?=$Time_ID?>', '<?=$Time_Format?>');</script>
	<?php }else{ ?>
	
		<?=$Value?>
	<?php } ?>
</td>

<!-------------------------------------------------->	
<?php }else if( $Vtype == 'number' || $Vtype == 'sortasc' || $Vtype == 'sortdesc' ){ 

		$Attr_Content = '';
		if( $table_option_arr[$key_t]['TO_NumOpen'] ){
		
			$Attr_Content = ' check-min="' .$table_option_arr[$key_t]['TO_Min']. '" check-max="' .$table_option_arr[$key_t]['TO_Max']. '"';
		}
?>
<td>
	<?php if( Menu_Use($Now_List, 'edit') && $table_option_arr[$key_t]['TO_OutEdit'] ){ ?>
	<label>
		<input type="text" class="TableChange eshow" e-txt="直接更改更新" value="<?=$Value?>" check-type="number" <?=$Attr_Content?> check-id="<?=$val_v?>" check-field="<?=$Turncode?>" check-data="<?=$Value?>" style="width: 50px;">
	</label>
	<?php }else{ ?>
	
		<?=$Value?>
	<?php } ?>
</td>

<!-------------------------------------------------->	
<?php }else if( $Vtype == 'select' ){ 
	
	$select = $this->Select_Arr[$key_t];
?>

<td>                        
	<?php if( Menu_Use($Now_List, 'edit') && $table_option_arr[$key_t]['TO_OutEdit'] ){ ?>
	<label>
		<select class="TableChange eshow" e-txt="直接更改更新" check-type="select" check-id="<?=$val_v?>" check-field="<?=$Turncode?>" check-data="<?=$Value?>" style="width: 80px;">
			<?=Select_Option($select, $Value, 'NO_FIRST_OPTION')?>
		</select>
	</label>
	<?php }else{ ?>
	
		<?=$select[$Value]?>
	<?php } ?>
</td>

<!-------------------------------------------------->	
<?php }else if( $Vtype == 'uploadimg' ){ ?>
<td>
	<?php if( !empty($Value) ){ ?>
	<ul class="ace-thumbnails clearfix">
		<li>
			<a href="<?=$Value_Array[$key_t.'_bUrl'][$key_v]?>" class="file-url <?=$this->Albums_TF==true?'file-size50':''?> imgtable" style="background-image: url('<?=$Value_Array[$key_t.'_sUrl'][$key_v]?>');" title="<?=$Value?>">
			</a>
		</li>
	</ul>
	<?php } ?>
</td>

<!-------------------------------------------------->	
<?php }else if( $Vtype == 'uploadfile' ){ ?>
<td>
	<?php if( !empty($Value) ){ ?>
	<label>
		<button type="button" class="btn btn-white btn-inverse btn-sm eshow control-size20 chk-file" e-txt="<?=$Value?> 下載" check-type="downfile" check-id="<?=$val_v?>" check-field="<?=$Turncode?>">
			<i class="fa fa-download"></i>
		</button>
	</label>
	<?php } ?>
</td>

<!-------------------------------------------------->	
<?php }else if( $Vtype == 'youtube' ){ ?>
<td>
	<?php 
	if( !empty($Value) ){ 
		
		$Youtube = true;
	?>
	<div class="youtube" yt-code="<?=$Value?>" yt-title="<?=$Value?>">
	<?php 
	} ?>
</td>

<!-------------------------------------------------->	
<?php }else{ ?> 
			   
<td><?=$Value?></td>
<?php }?>