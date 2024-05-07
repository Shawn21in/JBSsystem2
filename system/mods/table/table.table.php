<?php if( !function_exists('Chk_Login') ) if( !function_exists('Chk_Login') ) header('Location: ../../index.php'); ?>

<div class="dataTables_wrapper form-inline">

    <div class="row">
        <div class="col-xs-6">
            <div class="dataTables_length" id="mainTable_length">
                <label>
                	<span>顯示</span>
                    <select id="Page_Size" name="Page_Size" class="form-control input-sm">
                    <?php foreach( $Show_SizeArr as $val ){ ?>
                    	<option value="<?=$val?>" <?=$val==$Pages_Data[Page_Size]?"selected":""?>><?=$val?></option>
                    <?php } ?>
                    </select> 
                    <span>筆資料</span>
                </label>
            </div>
        </div>
        <div class="simple-search">
            <div id="mainTable_filter" class="dataTables_filter">
                <label>
                	<span>簡易搜尋</span>
                    <input type="search" id="SearchKey" name="SearchKey" value="<?=$SearchKey?>" class="input-sm" aria-controls="mainTable" />
                </label>
            </div>
        </div>
    </div>
	
    <div class="table_List">
        <table id="<?=$this->TableCssName?>" class="table table-striped table-bordered table-hover dataTable">
            <thead>
                <tr>
                    <th class="center" width="5%">
                        <label class="position-relative">
                            <input id="tab_chk" type="checkbox" class="ace" />
                            <span class="lbl"></span>
                        </label>
                    </th>
                    
                <?php
				$Youtube = false;//不啟用youtube
                foreach( $Title_Array as $key => $val ){ 
                    if( !empty($val) ){
                ?>
                    <th class="<?=key_exists($key, $Order_Array)?"sorting".$Order_Array[$key]:""?>" sortfield="<?=$key?>"><?=$val?></th>
                <?php 
                    }
                } ?>
                    <th width="10%">操作</th>
                </tr>
            </thead>
            <tbody>
            <?php 
			foreach( $Key_Arr as $key_v => $val_v ){ 
			?>
                        
                <tr>
                    <td class="center">
                        <label class="position-relative">
                            <input type="checkbox" class="ace" id="tab_chks" name="tab_chks[]" value="<?=$val_v?>"/>
                            <span class="lbl"></span>
                        </label>
                    </td>
                <?php 
				foreach( $Title_Array as $key_t => $val_t ){ 
                        
					$Vsn	= $Value_Array['Ordersn'][$key_v];//序號
					$Vtype	= $table_option_arr[$key_t]['TO_InType'];//資料主種類
					$Value	= $Value_Array[$key_t][$key_v];//資料值
					
					$Turncode = Turnencode($key_t);
					
					include(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'inc.vtype.mods.php');
                } 
				?>
        
                    <td>
                        <div class="action-buttons">
                        <?php if( $Operating['View'] ){ ?>
                            
                            <?php if( !Menu_Use($Now_List, 'view') ){ ?>
                            <a class="grey" href="javascript:void(0)">
                                <i class="ace-icon fa fa-search-plus bigger-130 eshow" e-txt="無法檢視"></i>
                            </a>
                            <?php }else{ ?>
                            <a class="blue" href="javascript:void(0)" onClick="View('<?=$val_v?>')">
                                <i class="ace-icon fa fa-search-plus bigger-130 eshow" e-txt="檢視"></i>
                            </a>
                            <?php } ?>
                            
                        <?php } ?>
                        
                        <?php if( $Operating['Edit'] ){ ?>
                        
                            <?php if( !Menu_Use($Now_List, 'edit') ){ ?>
                            <a class="grey" href="javascript:void(0)">
                                <i class="ace-icon fa fa-pencil bigger-130 eshow" e-txt="無法編輯"></i>
                            </a>
                            <?php }else{ ?>
                            <a class="green" href="javascript:void(0)" onClick="Edit('<?=$val_v?>')">
                                <i class="ace-icon fa fa-pencil bigger-130 eshow" e-txt="編輯"></i>
                            </a>
                            <?php } ?>
                            
                        <?php } ?>
                        
                        <?php if( $Operating['Delete'] ){ ?>
                        
                            <?php if( !Menu_Use($Now_List, 'delete') ){ ?>
                            <a class="grey" href="javascript:void(0)">
                                <i class="ace-icon fa fa-trash-o bigger-130 eshow" e-txt="無法刪除"></i>
                            </a>
                            <?php }else{ ?>
                            <a class="red" href="javascript:void(0)" onClick="Del('<?=$val_v?>', '<?=$Name_Arr[$key_v]?>')">
                                <i class="ace-icon fa fa-trash-o bigger-130 eshow" e-txt="刪除"></i>
                            </a>
                            <?php } ?>
                            
                        <?php } ?>
                        </div>
                    </td>
                </tr>
            <?php 
			} 
			?>
            </tbody>
        </table>
    </div>

    <?=$Pages_Html?>
</div>

<script type="text/javascript">$('.imgtable').colorbox({width:"70%", height:"100%", rel:'imgtable'});</script>

<?php if( $Youtube ){ ?>

<script src="assets/youtube/jquery-ytvideo.js"></script>
<link type="text/css" rel="stylesheet" href="assets/youtube/ytvideo.css" />
<script type="text/javascript">
$(document).ready(function(e) {
    
	$('.table_List .youtube').YTvideo({
		width : 200,
		height : 150,
	});
});
</script>
<?php } ?>