<?php

define('LINE_PLUGINS_URL', 'https://www.xingcloud.com.tw/plugins/line_login/' );//網頁網址

define("CLIENT_ID", '1655267674');
define("CLIENT_SECRET", '72c15586a43ea4bcc2fcbab471452f90');
define("REDIRECT_URI", LINE_PLUGINS_URL.'callback.php');//登入後返回位置
define("SCOPE", 'profile');//授權範圍以%20分隔 可以有3項openid，profile，email

require_once('_inc/ConfigManager.php');//Line 設定檔 管理器
require_once('_inc/LineAuthorization.php');//產生登入網址
require_once('_inc/LineProfiles.php');//取得用戶端 Profile
require_once('_inc/LineController.php');//LINE控制