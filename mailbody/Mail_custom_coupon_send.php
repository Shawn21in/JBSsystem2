<?php 
require_once("../include/web.config.php");

$WEB_Mail_URL = 'http://'.dirname(dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'])).'/';

$Input 	= GDC( $_GET['c'] , 'coupon_send');

$cc_id = $Input['v'];

$coupon_id = $Input['coupid'];

$survey_id = $Input['sid'];

$member_id = $Input['mid'];

$url = $WEB_Mail_URL.'coupon.php?c='.OEncrypt('v='.$cc_id , 'coupon');
$coupon 	= $CM->GET_COUPON_DATA($coupon_id);
$survey 	= $CM->GET_SURVEY_DATA($survey_id);
$db 	= new MySQL();
$db->Where = " WHERE Company_ID='".$survey['Data']['Survey_CID']."'";
$db->query_sql($company_db, 'Company_Name');
if( $row = $db->query_fetch() ){
	$com_name = $row['Company_Name'];
}
$db->Where = " WHERE Member_ID='".$member_id."'";
$db->query_sql($member_db, 'Member_Name');
if( $row = $db->query_fetch() ){
	$mem_name = $row['Member_Name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<style>
		body{
			font-family: '微軟正黑體';
		}
		.mailbody{
			border: 3px solid #999;
			padding: 20px 30px;
			max-width: 700px;
			box-sizing: border-box;
		}
		.mailbody h2{
			text-align: center;
		}
		.mailbody p{
			font-size: 15px;
			line-height: 1.7;
		}

		.mailbody p.tip{
			color: #ff0000;
			font-weight: bold;
			font-size: 17px;
			margin-bottom: 20px;
		}
		.mailbody p.center{
			margin: 0;
			text-align: center;
			font-size: 14px;
		}

		.mailbody table{
			width: 100%;
			border: 2px solid #999;
			border-spacing: 0;
			margin: 30px 0;
		}
		.mailbody table tr td{
			padding: 10px;
			border-collapse: collapse;
			border: 1px solid #999;
			letter-spacing: 0.05em;
			font-size: 15px;
		}
		.mailbody span{
			margin: 50px 0;
			display: block;
		}
		.mailbody a{
			color: #4963ed;
			font-weight: bold;
			margin: 0 5px;
		}
	</style>
</head>
<body>
	<article class="mailbody">
		<h2>恭喜你抽中了<?php echo  $com_name;?>的一張<?php echo $coupon['Data']['Coupon_Title']; ?>折扣卷</h2>
        
		<p>親愛的<?php echo $mem_name; ?>，您好：<br><br>
      
        <?php echo  $com_name;?>真誠地感謝您填寫了本公司的問卷，並發送折扣卷中獎通知給您。<br>
		※點網址後需先登入會員才能查看持有折扣卷，而每張折扣卷使用次數限定一次。<br>

		<table cellpadding="0"  border-spacing="0" border-collapse="0">
			<tr>
				<td width="20%">折扣卷標題</td>
				<td width="20%">折扣卷活動內容</td>
				<td width="20%">折扣卷備註</td>
				<td width="40%">使用期限</td>
			</tr>
			<tr>
				<td width="20%"><?php echo $coupon['Data']['Coupon_Title']; ?></td>
				<td width="20%"><?php echo $coupon['Data']['Coupon_Content3']; ?></td>
				<td width="20%"><?php echo $coupon['Data']['Coupon_Content2']; ?></td>
				<td width="40%"><?php echo $coupon['Data']['Coupon_Start_date'].'~'.$coupon['Data']['Coupon_End_date']; ?></td>
			</tr>
			<tr>
				<td width="40%" colspan="2" bgcolor="#ebeefc">折扣卷連結</td>
				<td width="60%" colspan="2"><a href="<?=$url?>">點我前往查看</td>
			</tr>
		</table>
		
		<span>祝您使用愉快！</span>

		<p class="tip">※本電子信箱為系統自動發送通知使用，請勿直接回覆， 如有任何疑問，歡迎來信以下客服專用信箱※</p>
		<p class="center"><?=$_setting_['WO_Name']?> </p>
		<p class="center">客服電話:<?=$_setting_['WO_Tel']?></p>
        <p class="center">客服信箱:<?=$_setting_['WO_Email']?></p>
	</article>
	
</body>
</html>