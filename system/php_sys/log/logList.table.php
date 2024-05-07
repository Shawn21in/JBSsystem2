<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

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
                foreach( $Title_Array as $key => $val ){ 
                    if( !empty($val) ){
                ?>
                    <th class="<?=key_exists($key, $Order_Array)?"sorting".$Order_Array[$key]:""?>" sortfield="<?=$key?>"><?=$val?></th>
                <?php 
                    }
                } ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach( $Key_Arr as $key_v => $val_v ){ ?>
                        
                <tr>
                    <td class="center">
                        <label class="position-relative">
                            <input type="checkbox" class="ace" id="tab_chks" name="tab_chks[]" value="<?=$val_v?>"/>
                            <span class="lbl"></span>
                        </label>
                    </td>
                <?php foreach( $Title_Array as $key_t => $val_t ){
                        
                        $Vtype = !empty($Vtype_Array[$key_t][$key_v]) ? $Vtype_Array[$key_t][$key_v] : '';
                        $Value = $Value_Array[$key_t][$key_v];
                ?><!--放在<td>標籤內的判斷<?=$key_t=='ML_SQL_CON'?'style="word-break: break-all;"':''?>-->
                    <td ><?=htmlspecialchars($Value)?></td>
                <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
	</div>

    <?=$Pages_Html?>
</div>