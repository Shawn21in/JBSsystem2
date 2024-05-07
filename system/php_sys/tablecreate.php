<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

<script type="text/javascript">

var ExecUrl = 'index.php?<?=$this->QUERY_STRING?>';

$(document).ready(function(e) {
	
	$('#saveto').click(function(){
		
		var formthis = $('#form');
		if( CheckInput(formthis) ){
			
			$(".tc_box").BoxWindow({
				_msg: '確定送出?',//訊息
				_type: 2,
				_eval: 'form_submit()'
			});
		}
	});
	
	$('#tab_chk').click(function(){
		
		var _this = $(this);
		var sel	  = $('#tablestyle').val();
		
		$('.' +sel+ ' input[name="tab_chks[]"]').each(function(index, element) {
			
			if( $(this).closest('label').css('display') == 'block' ){
				
				$(this).prop('checked', _this.prop('checked'));
			}else{
			
				$(this).prop('checked', false);
			}
		});
	});
	
	$('#tablename').change(function() {
		
		var Form_Data = 'tablename=' +$(this).val();
		
		Post_JS( Form_Data, ExecUrl, '.table-header' );
	});
	
	$('#tablestyle').change(function() {
		
		chg_style($(this));
	});
	
	chg_style($('#tablestyle'));
});

function form_submit(){
	
	var _this = $('#form');
	
	var Form_Data = new FormData();
	
	var other_data = _this.serializeArray();
	$.each(other_data,function(key,input){
		
		Form_Data.append(input.name, input.value);
	});
	
	Ajax_Table( Form_Data, ExecUrl );
}

function chg_style( _this ){
	
	$('.style').hide();
	/*$('input', $('.style')).attr('disabled', true);*/
	if( _this.val() != 0 ){
		
		$('.' +_this.val()).show()/*.find('input').attr('disabled', false)*/;
		
		var disabled;
		if( _this.val() == 'style1' || _this.val() == 'style2' || _this.val() == 'style3' ){
			
			disabled = false;
		}else{
			
			disabled = true;
		}
		
		$('#tablesuf, #tab_chk').attr('disabled', disabled);
	}
}
</script>

<style type="text/css">
body{
	background: #FFF;
}
</style>
<form id="form" method="post">
<div class="col-xs-12 tablesoption">

    <div class="table-header">資料表創建 && 資料表新增修改欄位的設定</div>
    
    <div id="table_content">
    
        <div class="dataTables_wrapper form-inline">
    	
        <div class="row">
            <div class="col-xs-12">
                <div class="tablecrete">
                    <label>
                    	<span>輸入資料表名稱</span>
                        <input type="text" id="tablename" name="tablename" msg="請填寫資料表名稱" maxlength="20" autocomplete="off">
					</label>
                </div>
            </div>
            
            <div class="col-xs-12">
                <div class="tablecrete">
                    <label>
                    	<span>資料表欄位前輟</span>
                        <input type="text" id="tablepre" name="tablepre" msg="請填寫資料表前輟" class="eshow" e-txt="例如資料表前輟 = News, 資料表欄位格式選了 ( 標題 = Title ) 結果: News_Title" maxlength="10" autocomplete="off">
					</label>
                </div>
            </div>
                        
            <div class="col-xs-12">
                <div class="tablecrete">
                    <label>
                    	<span>資料表欄位樣式</span>
                        <select id="tablestyle" name="tablestyle" msg="請選擇資料表格式">
                            <option value="0">請選擇</option>
                        <?php foreach( $this->TS_Style as $key => $val ){ ?>
                            <option value="<?=$key?>"><?=$val?></option>
                        <?php } ?>
                        </select> 
					</label>
                </div>
            </div>
            
            <div class="col-xs-12 style style1 style2 style3">
                <div class="tablecrete">
                    <label>
                    	<span>資料表欄位後輟</span>
                        <input type="text" id="tablesuf" name="tablesuf" class="eshow" e-txt="例如資料表前輟 = News, 資料表後輟 = 2, 資料表欄位格式選了 ( 標題 = Title ) 結果: News_Title2" maxlength="10" autocomplete="off" placeholder="非必填" disabled>
					</label>
                </div>
            </div>
            
            <div class="col-xs-12 style style4">
                <div class="tablecrete">
                    <label>
                    	<span>資料表分類層數</span>
                        <input type="text" id="tableclass" name="tableclass" class="eshow" e-txt="當創建資料表含分類或分類資料表時會用到 ( 預設: 2層 )" maxlength="2" value="2" autocomplete="off" input-type="number" input-name="資料表分類層數" input-min="1" input-max="10">
					</label>
                </div>
            </div>
            
            <div class="col-xs-12 style style1 style2 style3">
            	                
                <label class="col-xs-12 tablecrete">
                    <input type="checkbox" id="tab_chk" class="ace" disabled>
                    <span class="lbl"></span>
                    <span>全選 / 反選</span>
                </label>
            </div>
            
			<!---------------------------------style1---------------------------------> 
            <div class="col-xs-12 style style1">
           	
            <?php foreach( $this->TS_Style1 as $key2 => $val2 ){ ?> 
                       
				<label class="col-sm-5 tablecrete1">
                    <input type="checkbox" name="tab_chks[]" class="ace" value="<?=$key2?>">
                    <span class="lbl"></span>
                    <span><?=$val2?></span>
                </label> 
            <?php } ?>
			</div>
            
            <!---------------------------------style2---------------------------------> 
            <div class="col-xs-12 style style2">
            	
            <?php foreach( $this->TS_Style2 as $key2 => $val2 ){ ?> 
                       
				<label class="col-sm-5 tablecrete1">
                    <input type="checkbox" name="tab_chks[]" class="ace" value="<?=$key2?>">
                    <span class="lbl"></span>
                    <span><?=$val2?></span>
                </label> 
            <?php } ?>                                               
            </div>
				
			<!---------------------------------style3---------------------------------> 
            <div class="col-xs-12 style style3">   
				                 
			<?php foreach( $this->TS_Style3 as $key2 => $val2 ){ ?> 
                       
				<label class="col-sm-5 tablecrete1">
                    <input type="checkbox" name="tab_chks[]" class="ace" value="<?=$key2?>">
                    <span class="lbl"></span>
                    <span><?=$val2?></span>
                </label> 
            <?php } ?>
            </div>
            
            <!---------------------------------style4---------------------------------> 
            <div class="col-xs-12 style style4">   
				
            <?php foreach( $this->TS_Style4 as $key2 => $val2 ){ ?> 
                       
				<label class="col-sm-5 tablecrete1">
                    <input type="radio" name="tab_radio" class="ace" value="<?=$key2?>">
                    <span class="lbl"></span>
                    <span><?=$val2?></span>
                </label> 
            <?php } ?>
            </div>
        </div>        
	
        <div class="left form-actions">
            <button id="saveto" class="btn btn-info" type="button">
                <i class="ace-icon fa fa-check bigger-110"></i>儲存
            </button>&nbsp;&nbsp;&nbsp; 
                    
            <button id="rsetto" class="btn btn" type="reset">
                <i class="ace-icon fa fa-check bigger-110"></i>重設
            </button>
        </div>
	</div>
</div>  
</form> 