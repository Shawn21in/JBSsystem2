<div class="extra-div col-xs-12 dhide">
	
<?php if( !empty($_Search_Option) ){ ?>
	
    <script type="text/javascript">
	//設定( 下拉選擇 )搜尋內容
	var Search_KArr = new Array();
	var Search_VArr = new Array();
	<?php
	foreach( $_Search_Option as $key => $val ){
			
		if( $val['type'] == 'select' ){
		?>
			Search_KArr['<?=$key?>'] = new Array();
			Search_VArr['<?=$key?>'] = new Array();
		<?php
			foreach( $val['select'] as $key2 => $val2 ){
			
			?>
				Search_KArr['<?=$key?>'].push('<?=$key2?>');
				Search_VArr['<?=$key?>'].push('<?=$val2?>');
			<?php
			}
		}
	}
	?>
	</script>
	<form id="searchform" onSubmit="return false">
    	
        <div class="search-main">
        
            <div class="col-xs-12 search-border">
            
                <select name="search_field[]" onChange="Search_Chg($(this))">
                    <option value="">請選擇查詢項目</option>
                <?php 
				foreach( $_Search_Option as $key => $val ){ 
				
					$Format = $val['format'] ? 'data-format="' .$val['format']. '"' : ''; 
				?>
                
                    <option value="<?=$key?>" data-type="<?=$val['type']?>" <?=$Format?>><?=$val['name']?></option>	
                <?php 
				} ?>
                </select>
                
                <span class="search_content"></span>
            </div>
        </div>
    
        <button type="button" class="btn btn-purple btn-sm search-button">查詢</button> 
        <button type="button" class="btn btn-purple btn-sm search-additem">新增查詢項目</button>
    <?php if( $_Clean_Option ){ ?>
    	
        <button type="button" class="btn btn-purple btn-sm search-cleandata" search-type="clean">清除查詢資料</button>
    <?php } ?>
    <?php if( $_Excel_Option ){ ?>
    	
        <button type="button" class="btn btn-purple btn-sm search-cleandata" search-type="excel">匯出查詢資料</button>
    <?php } ?>
    </form>
<?php } ?>
</div>
