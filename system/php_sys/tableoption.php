<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

<script type="text/javascript">
$(document).ready(function(e) {
    
	$('#table_name').change(function() {
		
		$('#form').submit();
	});
	
	$('#saveto').click(function() {
		
		var formthis = $('#form');
		if( CheckInput(formthis) ){
			
			$('input[name="exec_action"]').val('save');
			$('#form').submit();
		}
	});
	
	$('.close').click(function() {
		
		var Field = $(this).parent().children('span').html();
		
		$('.' +Field+ ', .table_List table:first').remove();
		
		re_table_display();
	});
	
	$('.selectchg').change(function() {
		
		Field_Chg($(this));
	});
	
	$('.selectchg').each(function() {
        
		Field_Chg($(this));
    });
	
	//$('body').css('overflow-x', 'hidden');
});

function Field_Chg( _this ){
	
	var field = _this.attr('data-field');
		
	var shdata = new Array();
	//0=>欄位註解1, 1=>欄位註解2, 2=>欄位內顯示, 3=>欄位內編輯, 4=>欄位外顯示, 5=>欄位外編輯, 6=>欄位必填, 7=>欄位種類1, 8=>數字大小啟用, 9=>數字最大值, 10=>數字最小值, 11=>相互作用欄位, 12=>相互作用欄位1, 13=>選擇圖片大小, 14=>欄位狀態選擇, 15=>欄位上傳大小, 16=>時間格式
	if( _this.val() == 'checkbox' ){
		
		shdata = [0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0];
	}else if( _this.val() == 'number' || _this.val() == 'sortasc' || _this.val() == 'sortdesc' ){
		
		shdata = [1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0];
	}else if( _this.val() == 'aznumber' ){
		
		shdata = [1, 1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	}else if( _this.val() == 'select' ){
		
		shdata = [0, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0];
	}else if( _this.val() == 'datestart' || _this.val() == 'dateend' ){
		
		shdata = [1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1];
	}else if( _this.val() == 'datecreat' || _this.val() == 'dateedit' ){
		
		shdata = [0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	}else if( _this.val() == 'textedit' ){
		
		shdata = [0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	}else if( _this.val() == 'uploadimg' ){
		
		shdata = [0, 1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 1, 0, 1, 0];
	}else if( _this.val() == 'uploadfile' ){
		
		shdata = [0, 1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0];
	}else if( _this.val() == 'city' ){
		
		shdata = [0, 1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0];
	}else if( _this.val() == 'county' ){
		
		shdata = [0, 1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	}else if( _this.val() == 'address' ){
		
		shdata = [0, 1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0];
	}else if( _this.val() == 'textarea' ){
		
		shdata = [1, 0, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	}else if( _this.val() == 'youtube' ){
		
		shdata = [1, 1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	}else if( _this.val() == 'unique' ){
		
		shdata = [1, 1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	}else if( _this.val() == 'password' ){
		
		shdata = [1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	}else if( _this.val() == 'radio' ){
		
		shdata = [0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0];
	}else{
		
		shdata = [1, 1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	}
		
	for( var i = 0; i < shdata.length; i++ ){
		
		if( shdata[i] == 1 ){
			
			$('#' +field+ '_' +i).attr('disabled', false);
		}else{
			
			$('#' +field+ '_' +i).attr('disabled', true);
		}
	}
}

function re_table_display(){
	
	$('.tablesoption').css('width', ''); 
				
	var content = '';
	
	content += '<table class="table table-striped table-bordered table-hover dataTable" style="position: fixed; top: 0px; z-index: 100; display: none; width: inherit;">';
	content += '	<thead>';
	content += '		<tr>';
	$('.table_List #mainTable').find('th').each(function(index, element) {
		
		var str  = $(this).find('span').html() != null ?$(this).find('span').html() : '';
		content += '		<th class="center" style="min-width: ' +$(this).css('width')+ ';">' +str+ '</th>';
	});
	content += '		</tr>';
	content += '	</thead>';
	content += '</table>';
	
	$('.table_List').prepend(content);   
	
	$('.tablesoption').css('width', $('.table_List table').innerWidth() + 24); 
}
</script>

<style type="text/css">
body{
	background: #FFF;
	overflow-x: auto;	
}
</style>
<form id="form" method="post">
<div class="col-xs-12 tablesoption">

    <input type="hidden" name="exec_action" value="" />
    <div class="table-header">資料表設定</div>
    
    <div id="table_content">
    
        <div class="dataTables_wrapper form-inline" style="overflow: visible;">
        
            <div class="row">
                <div class="col-xs-12">
                    <div class="dataTables_length" id="mainTable_length">
                        <label>
                            <span>選擇資料表</span>
                            <select id="table_name" name="table_name" class="form-control input-sm">
                                <option value="">請選擇</option>
                            <?php foreach( $TablesArr as $key => $val ){ ?>
                                <option value="<?=$val['Name']?>" <?=$val['Name']==$Table_Name?'selected="selected"':''?>><?=$val['Name']?></option>
                            <?php } ?>
                            </select> 
                        </label>
                        
                        <label class="eshow" e-txt="資料表註解">
                            <input type="text" name="table_comment" value="<?=$Comment?>" class="table-input" autocomplete="off" />
                        </label>
                    </div>
                </div>
            </div>
        
		<?php if( !empty($table_info) ){ ?>
            
            <script type="text/javascript">
            $(document).ready(function(e) {
                
				re_table_display();         
            });
                        
			var Top = 0; 
			var Left = 0;
            $(document).scroll(function() {
                
				var firsttable = $('.table_List table:first');
                if( $(this).scrollTop() > 120 && Top != $(this).scrollTop() ){
                    
					Top = $(this).scrollTop();
                    firsttable.css({'position': 'fixed', 'top' : '0px', 'left' : -($(this).scrollLeft()-12)+ 'px', 'z-index' : 10}).show();
                }else if( $(this).scrollTop() <= 120){
                    
                    firsttable.css({'position': 'relative', 'top' : 'initial', 'left' : 'initial'}).hide();
                }
				
				if( $(this).scrollTop() > 120 && Left != $(this).scrollLeft() ){
					
					Left = $(this).scrollLeft();
					firsttable.css({'position': 'absolute', 'top' : $(this).scrollTop()-10+ 'px', 'left' : '12px'});
				}
            });
            </script>
            <div class="table_List">
                
                <table id="mainTable" class="table table-striped table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th class="center"></th>
                        <?php foreach( $table_info as $key => $val ){ ?>
                            
                            <th class="center p-relative <?=$key?>">
                                <span><?=$key?></span>
                                <div class="close">X</div>
                                <input type="hidden" name="<?=$key?>" value="<?=$key?>" />
                            </th>
                        <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                                            
                        <tr>
                            <td class="eshow" e-txt="資料表內的欄位註解" nowrap="nowrap">欄位註解</td>
                            
                            <?php foreach( $table_info as $key => $val ){ ?>
                            
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="text" name="<?=$key.'_Comment'?>" value="<?=$val['Comment']?>" class="table-input center" autocomplete="off" />
                                    <label>
                                </td>
                            <?php } ?>                
                        </tr>
                        
                        <?php $Field_Name = 'TO_Sort'; ?>
                        <tr>
                            <td class="eshow" e-txt="欄位顯示順序 ( 小到大 )" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name] ? $table_option_arr[$key][$Field_Name] : 0;
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="text" name="<?=$key.'_'.$Field_Name?>" value="<?=$Field_Value?>" msg="請輸入數字" input-type="number" input-min="0" input-max="99999" class="table-input center" autocomplete="off" />
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_InType'; ?>
                        <tr>
                            <td class="eshow" e-txt="欄位格式選擇 ( input )" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name];
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <select class="selectchg" data-field="<?=$key?>" name="<?=$key.'_'.$Field_Name?>">
                                            <option value="">文字格式</option>
                                            <?=Select_Option($this->TO_InType, $Field_Value, 'NO_FIRST_OPTION')?>
                                        </select>
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_Comment1'; ?>
                        <tr>
                            <td class="eshow" e-txt="自定義欄位註解 ( 欄位空值時顯示 )" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name];
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="text" id="<?=$key?>_0" name="<?=$key.'_'.$Field_Name?>" value="<?=$Field_Value?>" class="table-input center" autocomplete="off" />
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_Comment2'; ?>
                        <tr>
                            <td class="eshow" e-txt="自定義欄位註解 ( 欄位額外註解 )" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name];
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="text" id="<?=$key?>_1" name="<?=$key.'_'.$Field_Name?>" value="<?=$Field_Value?>" class="table-input center" autocomplete="off" />
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_InShow'; ?>
                        <tr>
                            <td class="eshow" e-txt="欄位在編輯畫面 ( 顯示 / 隱藏 )" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name] ? 'checked' : '';
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="checkbox" id="<?=$key?>_2" name="<?=$key.'_'.$Field_Name?>" class="ace ace-switch ace-switch-6" value="1" <?=$Field_Value?> />
                                        <span class="lbl lb2"></span>
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_InEdit'; ?>
                        <tr>
                            <td class="eshow" e-txt="欄位在編輯畫面 ( 是 / 否) 編輯" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name] ? 'checked' : '';
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="checkbox" id="<?=$key?>_3" name="<?=$key.'_'.$Field_Name?>" class="ace ace-switch ace-switch-6" value="1" <?=$Field_Value?> />
                                        <span class="lbl lb2"></span>
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                    <?php if( $Table_Name != 'sys_web_option' ){ ?>
                    
                        <?php $Field_Name = 'TO_OutShow'; ?>
                        <tr>
                            <td class="eshow" e-txt="欄位在列表畫面 ( 顯示 / 隱藏 )<br>選擇顯示會一併開啟 : 進階搜尋、簡易搜尋、排序等功能" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name] ? 'checked' : '';
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="checkbox" id="<?=$key?>_4" name="<?=$key.'_'.$Field_Name?>" class="ace ace-switch ace-switch-6" value="1" <?=$Field_Value?> />
                                        <span class="lbl lb2"></span>
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                    <?php } ?>
                    
                    <?php if( $Table_Name != 'sys_web_option' ){ ?>
                        
                        <?php $Field_Name = 'TO_OutEdit'; ?>
                        <tr>
                            <td class="eshow" e-txt="欄位在列表畫面 ( 是 / 否) 編輯" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name] ? 'checked' : '';
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="checkbox" id="<?=$key?>_5" name="<?=$key.'_'.$Field_Name?>" class="ace ace-switch ace-switch-6" value="1" <?=$Field_Value?> />
                                        <span class="lbl lb2"></span>
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                    <?php } ?>
                    
                        <?php $Field_Name = 'TO_Must'; ?>
                        <tr>
                            <td class="eshow" e-txt="欄位必須要填資料" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name] ? 'checked' : '';
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="checkbox" id="<?=$key?>_6" name="<?=$key.'_'.$Field_Name?>" class="ace ace-switch ace-switch-6" value="1" <?=$Field_Value?> />
                                        <span class="lbl lb2"></span>
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_ChkType'; ?>
                        <!--<tr>
                            <td class="eshow" e-txt="欄位格式選擇 ( check )" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name];
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <select id="<?=$key?>_7" name="<?=$key.'_'.$Field_Name?>">
                                        <?=Select_Option($this->TO_ChkType, $Field_Value)?>
                                        </select>
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>-->
                        
                        <?php $Field_Name = 'TO_NumOpen'; ?>
                        <tr>
                            <td class="eshow" e-txt="數字格式大小判斷 ( 開啟 / 關閉 )" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name] ? 'checked' : '';
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="checkbox" id="<?=$key?>_8" name="<?=$key.'_'.$Field_Name?>" class="ace ace-switch ace-switch-6" value="1" <?=$Field_Value?> />
                                        <span class="lbl lb2"></span>
                                    </label>
                                </td>
                            <?php 
                            } ?>          
                        </tr>
                        
                        <?php $Field_Name = 'TO_Min'; ?>
                        <tr>
                            <td class="eshow" e-txt="數字格式大小判斷 ( 開啟 ) 才有作用 ( 最小值 )" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name] ? $table_option_arr[$key][$Field_Name] : 0;
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="text" id="<?=$key?>_10" name="<?=$key.'_'.$Field_Name?>" value="<?=$Field_Value?>" msg="請輸入數字" input-type="number" class="table-input center" autocomplete="off" />
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_Max'; ?>
                        <tr>
                            <td class="eshow" e-txt="數字格式大小判斷 ( 開啟 ) 才有作用 ( 最大值 )" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name] ? $table_option_arr[$key][$Field_Name] : 0;
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="text" id="<?=$key?>_9" name="<?=$key.'_'.$Field_Name?>" value="<?=$Field_Value?>" msg="請輸入數字" input-type="number" class="table-input center" autocomplete="off" />
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_ConnectField'; ?>
                        <tr>
                            <td class="eshow" e-txt="時間格式或縣市格式專用" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name];
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="text" id="<?=$key?>_11" name="<?=$key.'_'.$Field_Name?>" value="<?=$Field_Value?>" class="table-input center" autocomplete="off" />
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_ConnectField1'; ?>
                        <tr>
                            <td class="eshow" e-txt="縣市格式與地址格式填寫區域碼專用" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name];
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="text" id="<?=$key?>_12" name="<?=$key.'_'.$Field_Name?>" value="<?=$Field_Value?>" class="table-input center" autocomplete="off" />
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_SelPicSize'; ?>
                        <tr>
                            <td class="eshow" e-txt="選擇圖片大小 ( 用於上傳圖片可多幾種圖片壓縮比例 )" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name];
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <select id="<?=$key?>_13" name="<?=$key.'_'.$Field_Name?>">
                                            <?=Select_Option($PicSize_Type, $Field_Value)?>
                                        </select>
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_SelStates'; ?>
                        <tr>
                            <td class="eshow" e-txt="欄位狀態選擇 ( 用於進階查詢或者選擇格式 )" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name];
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <select id="<?=$key?>_14" name="<?=$key.'_'.$Field_Name?>">
                                            <?=Select_Option($States_Type, $Field_Value)?>
                                        </select>
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_UploadSize'; ?>
                        <tr>
                            <td class="eshow" e-txt="設定圖片 / 檔案 上傳檔案大小" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name];
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <input type="text" id="<?=$key?>_15" name="<?=$key.'_'.$Field_Name?>" value="<?=$Field_Value?>" msg="請輸入數字" input-type="number" class="table-input center" autocomplete="off" />
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                        
                        <?php $Field_Name = 'TO_TimeFormat'; ?>
                        <tr>
                            <td class="eshow" e-txt="設定時間的顯示格式" nowrap="nowrap"><?=$table_option_info[$Field_Name]['Comment']?></td>
                            
                            <?php 
                            foreach( $table_info as $key => $val ){ 
                            
                                $Field_Value = $table_option_arr[$key][$Field_Name];
                            ?>
                                <td class="center <?=$key?>">
                                    <label>
                                        <select id="<?=$key?>_16" name="<?=$key.'_'.$Field_Name?>">
                                            <?=Select_Option($this->TO_TimeFormat, $Field_Value, 'NO_FIRST_OPTION')?>
                                        </select>
                                    </label>
                                </td>
                            <?php 
                            } ?>               
                        </tr>
                                          
                    </tbody>
                </table>
            </div>
            
            <div class="left form-actions">
                <button id="saveto" class="btn btn-info button1" type="button">
                    <i class="ace-icon fa fa-check bigger-110"></i>儲存
                </button>&nbsp;&nbsp;&nbsp; 
                        
                <button id="rsetto" class="btn button2" type="reset">
                    <i class="ace-icon fa fa-check bigger-110"></i>重設
                </button>
            </div>
		<?php } ?>
		</div>
    </div>  
    </form> 
</div>