<?php 
require_once(__DIR__.'/include/web.config.php');
// if( $_Login ) {
// 	switch($_state){
// 		case 'company':
// 			header("Location:com_index.php"); exit;
// 		break;
// 		case 'member':
// 			header("Location:cust_index.php"); exit;
// 		break;
// 	}
// }
$db = new MySQL();
$db->Order_By = ' ORDER BY Video_Sort DESC, Video_ID DESC';
$db->Where = " WHERE Video_Open = '1'";

$db->query_sql($video_db, '*');

while($row = $db->query_fetch()){
	
	$_html_video[$row['Video_ID']] = $row;
}
$_html 			= $CM->GET_WEB_SETTING( "Setting_Index01,Setting_Index02" );

//-----------------------------SEO-------------------------------------------
$_setting_['WO_Keywords'] 		.= $_html['SEO']['WO_Keywords'];
$_setting_['WO_Description'] 	.= $_html['SEO']['WO_Description'];

$_setting_['WO_Keywords'] 		.= $_ProductList['SEO']['WO_Keywords'];
$_setting_['WO_Description'] 	.= $_ProductList['SEO']['WO_Description'];

$_setting_['WO_Keywords'] 		.= $_BannerList['SEO']['WO_Keywords'];
$_setting_['WO_Description'] 	.= $_BannerList['SEO']['WO_Description'];
//---------------------------------------------------------------------------

$_Title 	= '首頁';
?>
<!doctype html>
<html>
<head>
<?php require('head.php') ?>
<title>庫點子文創 - 創造企業e化的好點子</title>
<style>
  /* 2022/04/01追加 */

.intro02-2,.intro02-3{
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 85%;
    margin:100px auto;
    flex-flow: row wrap;
}

.intro02-2__left{
    width: 43%;
}
.intro02-2__left img{
    width: 100%;
}
.tit_h2,.tit_h3{
    width: 100%;
    text-align: center;
    margin: auto;
    display:flex;
    justify-content: center;
    align-items: center;
}
.tit_h2{
  margin-top: 80px;
  margin-bottom: 20px;
}
.tit_h2 img{
  height: auto;
  margin-right: 10px;
}
.tit_h2 p{
  font-size:36px;
  color: #000;
}
.tit_p{
  font-size:16px;
  color: #b97808;
  line-height: 1.7em;
}

@media (max-width: 640px) {
    
    .intro02-2{
        width: 90%;
        flex-flow: column-reverse wrap;
    }
    .intro02-3{
        width: 90%;
        flex-flow: column wrap;
    }
    .intro02-2__left{
        width: 100%;
        margin-top: 30px;
        margin-bottom: 30px;
    }
}

</style>

</head>

<body  data-target=".navbar-spy" data-spy="scroll" data-offset="150">


<?php require('header.php') ?>


<article>

  <section class="bannerslider">
    <ul>
      <li class="bannerslider__item">
        <img src="images/banner.png" width="100%">
        <p>
          <span class="t01">E化大革命 一機搞定！</span>
          <span class="t02">手機APP打卡，資料直接同步至公司資料庫，<br>打卡不再僅限於公司內！</span>
          <span class="t02">現在購買手機APP打卡<br>享優惠價格購入人事薪資系統</span>
          <!-- <span class="t02" >現在購買手機APP打卡，<br>享有優惠價格加購人事薪資系統!</span> -->
          <button onClick="location.href='register.php'">立即註冊 購買打卡APP帳號</button>
        </p>
      </li>
    </ul>
  </section>

<div class="tit_h2"><img src="images/titImg01.png" alt=""><p>打卡APP快速又方便</p></div>
<div class="tit_h3 "><p class="tit_p">打卡順手拍照，同步紀錄、回傳座標位置，<br>外出差旅好方便。</p></div>
  



  <section class="intro" id="intro">
    <div class="intro__left">
      <h2 class="tit">同步作業快速方便</h2>
      <p class="con">手機打卡的資料，可同步寫入至公司的資料庫，<br>進行後續薪資作業處理</p>
      <p  class="con">打卡介面超簡單<br>同步紀錄座標位置</p>
    </div>
    <div class="intro__right">
      <img src="images/m01.png">
      <img src="images/m02.png">

    </div>
    
  </section>
  
  <section class="intro02">
    <div class="intro02__left">
      <img src="images/m03.png">
      <img src="images/m04.png">
      <img src="images/m05.png">
      <img src="images/m06.png">
    </div>
    <div class="intro02__right">
      <H2 class="tit">E化系統<br>輕鬆管理</H2>
      <p class="con">主管只需用手機，即可進行人事管理、人事建檔、休假審核、出缺勤查詢..等</p>
    </div>
    
  </section>
  
  <section class="intro03">
    <div class="intro03__left">
      <H2 class="tit">不疾不徐保障安全</H2>
      <p class="con">用手機即可在外拍照回傳完成打卡，出差不再需要趕回公司打下班卡讓我們的安全暴露在風險下</p>
    </div>
    <div class="intro03__right">
      <img src="images/m07.png">
      <img src="images/m08.png">
    </div>
    
  </section>

  <div class="tit_h2"><img src="images/titImg02.png" alt=""><p>優惠加購人事薪資系統</p></div>
  <div class="tit_h3 " style="margin-bottom:50px"><p class="tit_p">自動計算人事薪資，連接考勤機生成考勤資料，<br>並與銀行系統整合進行薪資發放，同時提供各種報表供管理者查詢。</p></div>
  
  <section class="intro02-2" >
    <div class="intro02-2__left">
      <img class="" src="images/pay01-2.png">
    </div>
    <div class="intro02__right" >
      <H2 class="tit">人事管理</H2>
      <p class="con">員工基本資料建檔 （到職日、班別）。<br>管理人員目前就職狀態：就職、離職、調職、復職。</p>
    </div>
  </section>
  
  <section class="intro02-3" id="intro">
    <div class="intro__left">
      <h2 class="tit">薪資管理</h2>
      <p class="con">管理薪資各類：勞保、健保、勞退、薪資計算、<br>分紅獎金..等各種類別。</p>
    </div>
    <div class="intro02-2__left">
      <img src="images/pay02-2.png">
    </div>
    
  </section>

  <section class="intro02-2">
    <div class="intro02-2__left">
      <img src="images/pay03-2.png">
    </div>
    <div class="intro02__right">
      <H2 class="tit">考勤管理</H2>
      <p class="con">管理出缺勤狀況<br>
        遲到、排休、特休、加班、病假、喪假、婚假..等<br>
        各種假別設定。<br>
        各種假別申請、核准機制。</p>
    </div>
  </section>

   <section class="program" id="program">
    <div class="container-fluid">
      <h2 class="tit">方案介紹</h2>
      <div class="program__left">
        <ul>
          <div  style="text-align:center;background-color: #737373;padding:15px 0;color:#fff;font-size: 17px;margin-bottom:5px;">打卡APP</div>
          <li class="program__left__item"><p>GPS定位</p>打卡時，系統會同時記錄座標位置，並存入資料庫中。</li>
          <li class="program__left__item"><p>行動打卡</p>用手機即可立即打卡上下班，並記錄至人事薪資系統內</li>
          <li class="program__left__item"><p>打卡拍照</p>可要求員工打卡時拍下現場照片回傳至公司，才算完成打卡作業。</li>
          <li class="program__left__item"><p>手機建檔</p>利用手機即可建立員工檔案。</li>
          <li class="program__left__item"><p>休假申請</p>可用手機與主管申請休假，免去公文往返的時間。</li>
          <li class="program__left__item"><p>休假審核</p>主管用手機就可審核員工的休假申請。</li>
          <li class="program__left__item"><p>出缺勤查詢</p>主管可即時用手機查詢員工的出缺勤的狀況，以利薪資計算。</li>
          <li class="program__left__item"><p>跑馬燈公告</p>可隨時更改跑馬燈內的公告內容</li>
          <div  style="text-align:center;background-color: #737373;padding:15px 0;color:#fff;font-size: 17px;margin-bottom:5px;">人事薪資系統</div>
          <li class="program__left__item"><p>人事管理</p>員工基本資料建檔，清楚掌握員工到職日、離職日、復職等基本資料。</li>
          <li class="program__left__item"><p>薪資管理</p>支援多種薪資計算方式與各種不同薪資計算方法，並與銀行系統整合進行薪資發放</li>
          <li class="program__left__item"><p>考勤管理</p>管理出勤狀況與請假申請核准機制，並可依公司需求新增假別。</li>
          <li class="program__left__item"><p>員工出勤曆報表</p>可產生多種出勤報表供管理者查詢，快速調閱員工出勤狀況。</li>

        </ul>
      </div>
      <div class="program__right">
        <div class="title">方案原價<!--<br>(年租，每年酌收一次費用)--></div>
        <ul>
        	<!--<li class="program__right__item"><p>60人以上</p><p class="cost"> 另外報價</p></li>-->
        	<li class="program__right__item"><p>40人</p><p class="cost"> NT.60000</p></li>
        	<!--<li class="program__right__item"><p>20人</p><p class="cost"> NT.30000</p></li>-->
        	
        </ul>

        
		    <!-- <div class="title">攻頂會員價格</div>
        <ul>
          <?php //foreach($Version_Data as $key => $val){ ?>
            <li class="program__right__item"><p><?=$val['Version_Title']?>(<?=$val['Version_Day'].'天'?>)</p><p class="cost"> NT.<?=$val['Version_Price']?></p></li>
            <?php //} ?>
            
          </ul> -->

          <div class="title">含人事薪資組合價</div>
          <ul>
            <!--<li class="program__right__item"><p>PLUS版(60人以上)</p><p class="cost"> NT.400000</p></li>-->
            <!--<li class="program__right__item"><p>專業版(40人)</p><p class="cost"> NT.300000</p></li>-->
            <li class="program__right__item"><p>標準版(20人)</p><p class="cost"><b class="color:#ff0000">(特價)</b> NT.60000</p></li>
            <li class="program__right__item"><p>人事薪資版60人(plus)</p><p class="cost"> NT.400000</p></li>
            
          </ul>
        <!--<div class="remind">(版本費用每年酌收一次)</div>
        <div class="title">開通費用</div>
        <div class="remind"><span>請洽經銷商</span> (僅酌收一次)</div>-->
        <button onClick="location.href='register.php'">直接前往註冊會員<img src="images/icon.png"></button>

      </div>
      
      <ul class="tips">
        <li>目前僅供 iOS、Android 可使用。</li>
        <!--<li>若已完成付款，請登入會員帳號填寫匯款後五碼及日期給我們，我們對帳後將會開通你註冊的帳號，您即可開始使用APP或登入網站後台操作。</li>-->
        <li>請注意！帳號開通七日後無法取消退回已支付的款項。</li>
        <li>可補差額升級版本。</li>
      </ul>
    </div>

   </section>


  <section class="download" id="download">
     <div class="container-fluid">
      <H2 class="tit">下載安裝說明</H2>
      <ul class="download__list">
        <li  class="download__item">
          <div>
            <h3>您還不是會員？</h3>
            <button class="btn01" onClick="location.href='register.php'"><span>點我立即註冊會員帳號<br>並完成繳費動作</span></button>
            <p>填寫資料註冊成為會員<br>
			並完成繳費動作後<br>
			請<a href="login.php">直接登入管理會員</a><br>
			確認您的匯款帳號<br>
			查看帳號狀態<br>
			</p>
					</div>
					</li>
					<li  class="download__item">
					<div>
						<h3>我已註冊並完成繳費</h3>
						<button class="btn02" onClick="location.href='login.php'"><span>網站管理會員<br>後台登入</span></button>
						<p><span>登入後可由後台操作以下功能</span><br>
			確認匯款帳號<br>
			續約<br>
			版本升級<br>
			修改會員基本資料<br>
			修改密碼<br>
			查看員工基本資料<br>
			查詢或匯出員工出缺勤</p>
					</div>
					</li>
					<li  class="download__item">
					<div>
						<h3>我已完成繳費<br>
			並收到MAIL帳號開通確認信<br>
			開始使用手機APP版</h3>
			<button class="btn03" onClick="location.href='https://itunes.apple.com/app/id1289615237'">立即下載 IOS版</button>
			<button class="btn04" onClick="location.href='https://play.google.com/store/apps/details?id=com.leo.ci_app'">立即下載 Android版</button>
          <p><a href="#video" style="border-bottom: 2px solid #000; padding-bottom: 2px;">點我瀏覽教學影片</a></p>
          </div>
        </li>
      </ul>
    </div>

  </section>

  <section class="video" id="video">
    <div class="container-fluid">
      <H2 class="tit">教學影片</H2>
      <ul class="video__list">
      	<?php foreach($_html_video as $val){ ?>
            <li>
              <div>
                <iframe src="https://www.youtube.com/embed/<?=$val['Video_YouTube']?>" frameborder="0" allowfullscreen></iframe>
              </div>
              <p><?=$val['Video_Title']?></p>
            </li>
        <?php } ?>
        
      </ul>
    </div>
  </section>
  
  
 
</article>


<?php require('footer.php') ?>

</body>

</html>