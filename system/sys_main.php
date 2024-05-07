<?php
require_once("include/inc.config.php");
require_once("include/inc.check_login.php");

$Main = new Main();
$Main->set_head();

$Contents = new Contents();
?>
<script type="text/javascript" src="assets/js/ace-elements.min.js"></script>
<script type="text/javascript" src="assets/js/ace.min.js"></script>
<script type="text/javascript" src="assets/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<!------------------地址插件------------------>
<script type="text/javascript" src="assets/jquery/jquery-address.js"></script>
<!------------------地址插件------------------>

<!------------------彈出式視窗多效果插件------------------>
<!--<script type="text/javascript" src="assets/custombox/custombox.min.js"></script>
<script type="text/javascript" src="assets/custombox/legacy.min.js"></script>
<link type="text/css" rel="stylesheet" href="assets/custombox/custombox.min.css">
<!------------------彈出式視窗多效果插件------------------>

<!------------------頁籤插件------------------>
<link rel="stylesheet" type="text/css" href="assets/easy-tabs/easy-tabs.css" />
<script type="text/javascript" src="assets/easy-tabs/easy-tabs.js"></script>
<!------------------頁籤插件------------------>

<!------------------日期行事曆插件------------------>
<script type="text/javascript" src="assets/datetimepicker4.17.47/moment.min.js"></script>
<script type="text/javascript" src="assets/datetimepicker4.17.47/locale/zh-tw.js"></script>
<script src="assets/datetimepicker4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="assets/datetimepicker4.17.47/css/bootstrap-datetimepicker.min.css">

<!------------------日期行事曆插件------------------>

<!------------------彈出式圖片視窗插件------------------>
<script type="text/javascript" src="style/colorbox/jquery.colorbox.js"></script>
<link rel="stylesheet" href="style/colorbox/style1/colorbox.css" />
<!------------------彈出式圖片視窗插件------------------>

<!------------------彈出式視窗純文字插件------------------>
<script type="text/javascript" src="assets/Remodal/remodal.js"></script>
<link rel="stylesheet" href="assets/Remodal/remodal.css" />
<link rel="stylesheet" href="assets/Remodal/remodal-default-theme.css" />
<!------------------彈出式視窗純文字插件------------------>
<script type="text/javascript" src="plugins/ckeditor/ckeditor.js?v=20201112"></script>
<script type="text/javascript" src="plugins/ckfinder/ckfinder.js"></script>
<script src="plugins/ckeditor/lang/zh.js"></script>

<body class="no-skin">
<?php
$Main->set_box();

$Main->set_header();

$db = new MySQL();

if( Multi_WebUrl ){
	
	$db->Where = " WHERE Admin_ID = '" .$Admin_data['Admin_ID']. "'";
}else{
	
	$db->Where = " WHERE Admin_ID = '" .Multi_WebUrl_ID. "'";
}

$db->query_sql(DB_DataBase.'.'.Web_Option_DB, 'WO_FBLink');
$FBLink = $db->query_fetch('WO_FBLink');
?>
<div class="main-container" id="main-container">
    <div id="sidebar" class="sidebar responsive">
        <div class="sidebar-shortcuts" id="sidebar-shortcuts">

            <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                <button class="btn btn-success eshow" onClick="location.href='<?=MAIN_EXEC?>'" e-txt="後台首頁">
                    <i class="ace-icon fa fa-home"></i>
                </button>

                <button class="btn btn-info eshow" onClick="Reload()" e-txt="重整頁面">
                    <i class="ace-icon fa fa-refresh"></i>
                </button>

                <button class="btn btn-warning eshow" onClick="window.open('../')" e-txt="前台首頁">
                    <i class="ace-icon fa fa-eye"></i>
                </button>

                <button class="btn btn-danger eshow" onClick="GoFB('<?=$FBLink?>')" e-txt="FACEBOOK">
                    <i class="ace-icon fa fa-facebook"></i>
                </button>
            </div>

            <!--<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                <span class="btn btn-success"></span>
                <span class="btn btn-info"></span>
                <span class="btn btn-warning"></span>
                <span class="btn btn-danger"></span>
            </div>-->
        </div><!--.sidebar-shortcuts -->
        <ul class="nav nav-list">
            <li id="main" class="">
                <a href="<?=MAIN_EXEC?>">
                    <i class="menu-icon fa fa-tachometer"></i>
                    <span class="menu-text"> 控制台 </span>
                </a>
                <b class="arrow"></b>
            </li>
		<?php 
		$Contents->set_MenuList();
		$_html  = $Contents->html;
		$_html2 = $Contents->html2;
		foreach( $_html as $key => $val ){ 
		
			if( empty($_html2[$key]) ){ continue; }
		?>
            <li>
                <a href="javascript:void(0)" class="dropdown-toggle">
                    <i class="menu-icon fa <?=$val['Menu_Smallpic']?>"></i>
                    <span class="menu-text"> <?=$val['Menu_Name']?> </span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>
			<?php //if( !empty($_html2[$key]) ){ ?>
                <ul class="submenu">
				<?php foreach( $_html2[$key] as $key2 => $val2 ){ ?>
                    <li id="<?=$val2['Menu_Focus']?>" class="eshow" e-txt="<?=$Admin_data['Group_ID']==1?$val2['Menu_Exec']:''?>">
                        <a href="<?=$val2['Menu_href']?>">
                            <i class="menu-icon fa fa-caret-right"></i> <?=$val2['Menu_Name']?>
                        </a>
                        <b class="arrow"></b>
                    </li>
				<?php } ?>	
                </ul>
			<?php //} ?>			
            </li>
		<?php 
		} ?>
		</ul>
         <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
         </div>
	</div>
        
	<div class="main-content">
    
     	<div class="breadcrumbs" id="breadcrumbs">
        
			<ul class="breadcrumb">
		 		<li>
		 			<i class="ace-icon fa fa-home home-icon"></i>
		 			<a href="<?=MAIN_EXEC?>">首頁</a>
		 		</li>
            <?php
			$TitleList = $Contents->set_TitleList(); 
			foreach( $TitleList as $val ){
			?>
		 		<li class="active">
		 			<?=$val['Name']?>
		 		</li>
            <?php 
			} ?>
		 	</ul>
			
            <?php if( !empty($Contents->Now_List) && FUN ){ ?>
            <div class="nav-action">
            	<span class="input-icon">
                	<button id="mainAdd" type="button" class="btn btn-white btn-danger btn-sm" <?=$Contents->Now_List['Menu_Add'] && Menu_Use($Contents->Now_List, 'add')?'':'disabled="disabled"'?>>
                    	<span>新增</span>
					</button>
                </span>
                <span class="input-icon">
               		<button id="mainEdt" type="button" class="btn btn-white btn-purple btn-sm" <?=$Contents->Now_List['Menu_Edt'] && Menu_Use($Contents->Now_List, 'edit')?'':'disabled="disabled"'?>>
                    	<span>批次編輯</span>
					</button>
                </span>
                <span class="input-icon">
                	<button id="mainDel" type="button" class="btn btn-white btn-inverse btn-sm" <?=$Contents->Now_List['Menu_Del'] && Menu_Use($Contents->Now_List, 'delete')?'':'disabled="disabled"'?>>
                    	<span>批次刪除</span>
                    </button>
                </span>
                <span class="input-icon">
                	<button id="mainRe" type="button" class="btn btn-white btn-success btn-sm" disabled="disabled">
                   		<span>返回</span>
                    </button>
                </span>
            </div>
            <?php } ?>
            
		 	<!--<div class="nav-search" id="nav-search">
		 		
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
            	</form>
		 	</div>-->
     	</div>
        
		<div class="page-content">
            <div class="ace-settings-container" id="ace-settings-container">
                <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                    <i class="ace-icon fa fa-cog bigger-150"></i>
                </div>
                <div class="ace-settings-box clearfix" id="ace-settings-box">
                    <div class="pull-left width-50">
                        <div class="ace-settings-item">
                            <div class="pull-left">
                                <select id="skin-colorpicker" class="hide">
                                    <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                                    <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                                    <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                                    <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                                </select>
                            </div>
                            <span> 更改樣式 </span>
                        </div>
        
                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
                            <label class="lbl" for="ace-settings-navbar"> 固定標頭 </label>
                        </div>
        
                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
                            <label class="lbl" for="ace-settings-sidebar"> 固定側欄 </label>
                        </div>
        
                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
                            <label class="lbl" for="ace-settings-breadcrumbs"> 固定次標頭 </label>
                        </div>
        
                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
                            <label class="lbl" for="ace-settings-add-container"> 更改窄畫面 </label>
                        </div>
                    </div><!-- /.pull-left -->
                </div><!-- /.ace-settings-box -->
            </div><!-- /.ace-settings-container -->
            <div class="page-content-area">
                <div class="row">
                    <div class="col-xs-12">
        				<?=$Contents->set_Content_List()?>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.page-content-area -->
		</div><!-- /.page-content -->
	</div><!-- /.main-content -->
<?php
$Main->set_footer();
?>
</div>
</body>
</html>    