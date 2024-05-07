<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

<div id="navbar" class="navbar navbar-default">
    <div class="navbar-container" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
            <span class="sr-only">快速啟動</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
            <a href="<?=MAIN_EXEC?>" class="navbar-brand">
                <small>
                    <i class="fa fa-leaf"></i>
                    華越資通後台管理系統
                </small>
            </a>
        </div>
		
        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="ace-nav">
                <li class="light-blue">
                
                	<div class="tables_chg">
					
						<select id="language_chg">
							<option value="zh_TW" <?=$_SESSION['system']['language']=='zh_TW'?'selected':''?>>繁體中文</option>
							<!--<option value="en" <?=$_SESSION['system']['language']=='en'?'selected':''?>>English</option>-->
                        </select>
						
					<?php if( ( $Admin_data['Group_ID'] == 1 || $Admin_data['Admin_Checkbox'] == 1 ) && !empty($Sys_Tables_Arr) ){ ?>
                    	
                        <select id="tables_chg">
                        	<?=Select_Option($Sys_Tables_Arr, $_SESSION['system']['tables_chg'], 'NO_FIRST_OPTION')?>
                        </select>
					<?php } ?>
                    
					<?php
					$Admin_Count = 0;
					if( $Admin_data['Group_ID'] != 1 && $_SESSION['system']['admin_chg'] == 1 ){ 
					
						$db = new MySQL();
					
						$db->Where = " WHERE Group_ID = '1' AND Admin_Code = '" .$db->val_check($_SESSION['system']['admin_code']). "'";
						$Admin_Count = $db->query_count(Admin_DB);
					}
					
					if( $Admin_data['Group_ID'] == 1 || $Admin_Count > 0 ){
						
                        $Admin_Arr = Get_Table_Info(DB_DataBase.'.'.Admin_DB, 'Admin_ID', 'Admin_Name', " WHERE Admin_ID != '" .$Admin_data['Admin_ID']. "'");
						//$Admin_Arr[0] = '系統管理者';
						ksort($Admin_Arr);
					?>
                        <select id="admin_chg">
                        	<?=Select_Option($Admin_Arr, '')?>
                        </select>
                        <script type="text/javascript">
						$(document).ready(function(e) {
                            
							$('#tables_chg, #admin_chg, #language_chg').change( function(){
								
								var Form_Data = 'id=' +$(this).val()+ '&_type=' +$(this).attr('id');
								Post_JS( Form_Data, Exec_Login );
							});
                        });
						</script>
                    <?php 
					} 
					?>
                    </div>
                
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                    	<i class="menu-icon fa fa-user"></i>
                        <span class="user-info">
                            <small>歡迎</small>
                            <label id="adminName"><?=$Admin_data['Admin_Name']?></label>
                        </span>
                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>
                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                    <?php if( $Admin_data['Group_ID'] == 1 ){ ?>
                        <li>
                            <a href="<?=SYS_URL?>?tablesoption" target="_blank">
                                <i class="ace-icon icon-ex fa fa-cog"></i>
                                <span>資料表設定</span>
                            </a>
                        </li>
                        
                        <li>
                            <a href="<?=SYS_URL?>?tablescreate" target="_blank">
                                <i class="ace-icon icon-ex fa fa-tasks"></i>
                                <span>資料表創建</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?=SYS_URL?>?versionrefresh" target="_blank">
                                <i class="ace-icon icon-ex fa fa-refresh"></i>
                                <span>更新版本</span>
                            </a>
                        </li>
                        <li class="divider"></li>
                    <?php } ?>
                        <li>
                            <a href="javascript:void(0)" class="logout">
                                <i class="ace-icon icon-ex fa fa-power-off"></i>
                                <span>登出</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div><!--#navbar-container -->
</div>