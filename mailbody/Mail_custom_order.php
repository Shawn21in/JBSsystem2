<?php 
require_once("../include/web.config.php");

$Input = GDC( $_GET['c'] , 'custom_order' );
$oid = $Input['oid'];

$SC = new ShopCart();
$_OrderData = $SC->Get_Order($oid);


$color_setting = 1 ; //0為黑，1為白;

if( $color_setting ==0 ){
	$colorSet= "dark";
	$mailbg = "url('http://www.bm888.com.tw/images/dark_mailbg.png');";
	$mailbg_footer = "url('http://www.bm888.com.tw/images/dark_mailbg.png');";
}else{
	$mailbg = "url('http://www.bm888.com.tw/images/mailbg.png');";
	$mailbg_footer ="none";
}

$URLweb 	= $_setting_['WO_Url']."/system/assets/css/images/";
$logo_url = $URLweb.$_setting_['WO_FooterLOGO'];



?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<style>
		.body{
			 font-family: "microsoft jhenghei";
			background-color: #efefef;
			max-width: 740px;
			margin: 0 auto;
			padding: 20px 20px;

		}
		.mailbody{
			background-color: #fff;
			padding: 20px 20px;
			max-width: 700px;
			margin: 0 auto;
			box-sizing: border-box;
		}
		.mailbody h2{
			text-align: center;
		}
		.mailbody p{
			font-size: 15px;
			line-height: 1.7;
			font-family: "microsoft jhenghei";
			color: #787878;
		}
		.mailbody p.tip{
			font-size: 13px;
			line-height: 1.7;
			margin: 0;
			letter-spacing: 0.06em;
			margin-bottom: -20px;
			font-family: "microsoft jhenghei";
		}
		.dark .mailbody p.tip{
			color: fff;
			margin-bottom: 20px;
		}



		.mailbody table{
			width: 100%;
			/*border: 2px solid #999;*/
			border-spacing: 0;
			margin: 20px 0;
		}

		.mailbody table tr td a.link{
			background-color: #1a81d8;
			color: #fff;
			border: 0;
			font-weight: 700;
			font-size: 20px;
			padding: 10px 60px;
			border-radius: 5px;
			 font-family: "microsoft jhenghei";
			 text-decoration: none;
			 outline: none;
			 cursor: pointer;
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

		.mailbody table.prolist{
			background-color: #e5e4e4;
			padding: 20px;

		}
		.mailbody table.prolist tr{
			
		}
		.mailbody table.prolist tr th{
			border-bottom: 1px solid #000;
			padding: 10px 5px;
		}
		.mailbody table.prolist tr th p{
			font-family: "microsoft jhenghei";
			font-size: 15px;
			color: #000;
			font-weight: 500;
			margin: 0;
			
		}
		.mailbody table.prolist tr td{
			padding: 5px;
		}
		.mailbody table.prolist tr td p{
			font-family: "microsoft jhenghei";
			font-size: 14px;
			margin: 0;
			font-weight: 300;
		}

		.mailbody table.contact{
			background-color: #e5e4e4;
			padding: 20px;
		}
		.mailbody table.contact tr th{
			border-bottom: 0.02em solid #999;
			padding: 8px 0;
		}
		.mailbody table.contact tr td{
			border-bottom: 0.02em solid #999;
			padding: 8px 10px;
		}
		.mailbody table.contact tr:last-child td,.mailbody table.contact tr:last-child th{
			border:0;

		}
		.mailbody table.contact tr th p{
			font-family: "microsoft jhenghei";
			font-size: 15px;
			color: #000;
			font-weight: 300;
			margin: 0;
		}

		.mailbody table.contact tr td p{
			font-family: "microsoft jhenghei";
			font-size: 15px;
			color: #787878;
			font-weight: 300;
			margin: 0;
		}

		.maillogo{
			margin: 0 auto;
			width: 100%;
			max-width: 700px;

		}
		.mailtop{
			margin: 0 auto;
			width: 100%;
			max-width: 700px;
			
		}

		.mailtop table{
			/*background-color: #1a81d8;*/
			width: 100%;
			padding: 20px 30px;
			background-image: <?=$mailbg?>
			-webkit-background-size: cover;
			background-size: cover;	
		}
		.mailtop table tr td h2{
			color: #787878;
		    text-align: center;
		    display: table;
		    margin: 0 auto;
		    font-size: 30px;
		    position: relative;
		    letter-spacing: -0.05em;
		    font-weight: 900;
		    margin-bottom: 20px;
		    font-family: "microsoft jhenghei";
		}
		.dark .mailtop table tr td h2{
			color: #fff;
		}

		.mailtop table tr td p{
			color: #fff;
			text-align: left;
			font-size: 13px;
			letter-spacing: 0.08em;
			font-weight: 300;
			font-family: "microsoft jhenghei";
		}
		.mailtop table tr td h2 span{
			width: 4px;
			height: 4px;
			border-radius: 50%;
			border: 4px solid #999;
			/*background: #666;*/
			content: '';
			display: inline-block;
			position: absolute;
			right: -15px;
			bottom: 5px;

		}

		.dark .mailtop table tr td h2 span{
			border: 4px solid #fff;
		}
		.mailbody table.links{
			background-color: #e5e4e4;
			padding: 40px 30px;
		}
		.mailbody table.links tr td p{
			font-size: 15px;
			letter-spacing: 0.04em;
			line-height: 1.5;
		}



		.mailfooter{
			background-color: #fff;
			width: 100%;
			max-width: 700px;
			margin: 0 auto;
			padding: 20px 30px;
			box-sizing: border-box;
			margin-bottom: 25px;
			background-image: <?=$mailbg_footer?> ; 
			-webkit-background-size: cover;
			background-size: cover;

		}

		.mailfooter p{
			margin: 0 ;
			font-family: "microsoft jhenghei";

		}

		.mailfooter table tr td p.connect{
			color: #b8b8b8;
			font-size: 12px;
			letter-spacing: 0.08em;
			border-top: 1px solid #d5d5d5;
			padding-top: 20px;
			font-family: "microsoft jhenghei";
		}
		.mailfooter table tr td p.name{
			color: #333;
			font-size: 18px;
			font-weight: 900;
			letter-spacing: 0.08em;
			padding-top: 20px;
			letter-spacing: -0.05em;
			font-family: "microsoft jhenghei";
		}
		.mailfooter table tr td p.center{
			color: #b8b8b8;
			font-size: 12px;
			letter-spacing: 0.08em;
			padding-top: 20px;
			display: inline-block;
			margin-right: 30px;
			font-family: "microsoft jhenghei";
		}
		.mailfooter table tr td p.center a{
			color: #b8b8b8;
			text-decoration: none;
		}
		.mailfooter table tr td p.copyright{
			color: #b8b8b8;
			font-size: 12px;
			letter-spacing: 0.08em;
			display: inline-block;
			margin-right: 30px;
			font-family: "microsoft jhenghei";
		}
		.dark .mailfooter table tr td p.center{
			color: #fff;
			border: 0;
			font-family: "microsoft jhenghei";
		}
		.dark .mailfooter table tr td p.copyright{
			color: #fff;
			border: 0;
			font-family: "microsoft jhenghei";
		}

		.dark .mailfooter table tr td p.connect{
			color: #fff;
			border: 0;
			padding-top: 0;
			font-family: "microsoft jhenghei";
		}
		.dark .mailfooter table tr td p.center a{
			color: #fff;
		}
		.dark .mailfooter table tr td p.copyright a{
			color: #fff;
		}

		.mailfooter table{
			width: 100%;
		}

		@media(max-width: 480px){
			.mailbody table.prolist tr th p{
				font-size: 13px;	
			}
			.mailbody table.prolist tr td p{
				font-size: 12px;
			}

		}



		
		
	</style>
</head>
<body>
	<article class="body <?=$colorSet?>">
		<article class="mailtop">
			<h2></h2>

			<table>
				
				<tr>
					<td align="left"><img src="<?=$logo_url?>" width="100"></td>
				</tr>
				
				<tr>
					<td><h2>訂單成立通知信<span></span></h2></td>
				</tr>	
			</table>
		</article>

		<article class="mailbody">
 
			<p>親愛的顧客，您好：<br>※本通知信僅代表已收到您的訂購訊息、並供您再次自行核對之用，不代表交易已經完成。</br>
		您於「<?=$_setting_['WO_Title']?>」成立了一筆訂單，以下為您的訂單資訊：</p>
			
			<table cellpadding="0"  border-spacing="0" border-collapse="0" class="contact">
				<tr>
					<td width="30%" align="left"><p>訂單編號</p></td>
					<td width="70%" align="left"><p><?=$_OrderData['Orderm_ID']?></p></td>
				</tr>
				<tr>
					<td width="30%" align="left"><p>訂單日期</p></td>
					<td width="70%" align="left"><p><?=$_OrderData['Orderm_Sdate']?></p></td>
				</tr>
				<tr>
					<td width="100%" align="left" colspan="2"><p>訂單狀態與資訊可至會員中心<a href="<?=$_setting_['WO_Url'].'/order.php'?>">【訂單查詢】</a>查看！</p></td>
				</tr>
				
			</table>
			<p class="tip">※ 本電子信箱為系統自動發送通知使用，請勿直接回覆， 如有任何疑問，歡迎來信以下客服專用信箱 ※
				<br>※ 為保護您的個資，本信件將不顯示您的基本資料 ※</p>

		</article>

		<article class="mailfooter">
			<table>
				<tr>
					<td>
						<p class="connect">CONNECT </p>
						<p class="name"><a href="<?=$_setting_['WO_Url']?>"><img src="<?=$logo_url?>" alt="" width="100"></a> </p>
						<p class="center">電話 / <?=$_setting_['WO_Tel']?></p>
			        	<p class="center">Email / <?=$_setting_['WO_Email']?></p>
			        	<p class="center"><a href="<?=$_setting_['WO_Url']?>"><?=$_setting_['WO_Name']?></a></p>
					</td>
				</tr>
				
			</table>
			
		</article>
	</article>
	
</body>
</html>