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
<?php }else if( $Vtype == 'datestart' || $Vtype == 'dateend' ){ 
		
		$Time_ID 		= $Turncode.'o'.$Vsn;
		
		$Time_Format 	= $table_option_arr[$key_t]['TO_TimeFormat'];
		
		$Value			= ForMatDate($Value, $Time_Format);
		
		$ConnectField 	= !empty($table_option_arr[$key_t]['TO_ConnectField']) ? Turnencode($table_option_arr[$key_t]['TO_ConnectField']).'o'.$Vsn : '';
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
<?php }else if( $Vtype == 'number' ){ ?>
	
<td>
	<?php if( Menu_Use($Now_List, 'edit') && $table_option_arr[$key_t]['TO_OutEdit'] ){ ?>
	<label>
		<input type="text" class="TableChange eshow" e-txt="直接更改更新" value="<?=$Value?>" check-type="number" check-min="0" check-max="99999" check-id="<?=$val_v?>" check-field="<?=$Turncode?>" check-data="<?=$Value?>" style="width: 50px;">
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
<?php }else if( $Vtype == 'favorable' ){ 

	$ConnectField 	= !empty($table_option_arr[$key_t]['TO_ConnectField']) ? $table_option_arr[$key_t]['TO_ConnectField'] : '';
	
	$select2		= $this->Select_Arr[$ConnectField];
	
	$Value2			= $Value_Array[$ConnectField][$key_v];
?>
<td>
	<?php if( Menu_Use($Now_List, 'edit') && $table_option_arr[$key_t]['TO_OutEdit'] ){ ?>
	<label>
    	可享有
		<input type="text" class="TableChange eshow" e-txt="直接更改更新" value="<?=$Value?>" check-type="number" check-min="0" check-max="99999" check-id="<?=$val_v?>" check-field="<?=$Turncode?>" check-data="<?=$Value?>" style="width: 50px;">
        
    	<?php if( !empty($ConnectField) ){ 
			
			$Turncode2 	= Turnencode($ConnectField);
		?>
        
		<select class="TableChange eshow" e-txt="直接更改更新" check-type="select" check-id="<?=$val_v?>" check-field="<?=$Turncode2?>" check-data="<?=$Value2?>" style="width: 50px;">
			<?=Select_Option($select2, $Value2, 'NO_FIRST_OPTION')?>
		</select>
        <?php } ?>
    	的優惠價格
    </label>
	<?php }else{ ?>
	
		可享有 <?=$Value?> <?=$select2[$Value2]?> 的優惠 
	<?php } ?>
</td>

<!-------------------------------------------------->	
<?php }else{ ?> 
			   
<td><?=$Value?></td>
<?php }?>