<?php 
require_once("include/inc.config.php"); 

$db = new MySQL();

$db->Where = " WHERE Admin_ID = '2'";
$db->query_sql(Web_Option_DB, 'WO_LOGO2');

$_setting_ = $db->query_fetch();

if( !empty($_setting_['WO_LOGO2']) ) {

	$_LOGO = SYS_URL."assets/css/images/".$_setting_['WO_LOGO2'];
}else{

	$_LOGO = "assets/css/images/bm_logo.png";
}
?>
<div class="main-container">

    <div class="loginbox">
        <div class="loginbox__left col-md-7 col-sm-6">
            <div class="loginbox__left__content">
                <img src="<?=$_LOGO?>" alt="">
                <p>歡迎您使用後台管理系統，如操作上有遇任何問題，<br>可透過下列連絡方式與我們聯絡，我們將有專員為您服務。</p>
                <div class="loginbox__left__imfo">
                    <p><i class="fa fa-phone icon" aria-hidden="true"></i>04-23586802</p>
                    <p><i class="fa fa-envelope icon" aria-hidden="true"></i>bmidp888@gmail.com</p>
                </div>
                
            </div>
        </div>
        <div class="loginbox__right col-md-5 col-sm-6">
            <div class="loginbox__right__box">
                <h1>
                    <span class="white" id="id-text2">網站後台管理登入</span>
                </h1>
                <form id="login_form">
                    <fieldset>
                        <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input id="account" name="account" type="text" class="form-control" placeholder="使用者帳號" msg="請輸入使用者帳號" autocomplete="off" />
                                <i class="ace-icon fa fa-user"></i>
                            </span>
                        </label>

                        <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input id="password" name="password" type="password" class="form-control" placeholder="使用者密碼" msg="請輸入使用者密碼" autocomplete="off" />
                                <i class="ace-icon fa fa-lock"></i>
                            </span>
                        </label>

                        <div class="space"></div>

                        <div class="clearfix">
                            <button type="button" class="width-100 pull-right btn btn-sm btn-primary login-submit">
                                <i class="ace-icon fa fa-key"></i>
                                <span class="bigger-110">登入</span>
                            </button>
							<input type="hidden" id="SYSTEM_IDENTIFY" value="SYS">
                        </div>
						<div class="space"></div><div class="space"></div><div class="space"></div><div class="space"></div>
						
                        <div class="space-4"></div>
                    </fieldset>
                </form>
            </div>
            <p class="copyright">Copyright © 2020 華越資通. All rights reserved</p>
            
        </div>
    
    
    
    </div><!-- /.row -->
</div><!-- /.main-container -->