<?php
require_once("include/web.config.php");

//不接受任何get資料

if (!empty($_GET)) {
  header("Location: login.php");
  exit;
}
if ($_Login) {
  header("Location:index.php");
  exit;
}


$_SESSION['website']['formsigncode'] = md5(time());

$_Title = '會員登入';
?>
<!doctype html>
<html>

<head>
  <?php require('head.php') ?>
  <link rel="stylesheet" type="text/css" href="stylesheets/login.css" />

</head>

<body data-target=".navbar-spy" data-spy="scroll" data-offset="150">



  <?php require('header.php') ?>



  <article class="center">

    <section class="center_section">
      <h2><img src="images/member_.png">會員登入</h2>
      <form id="forms" onSubmit="return false;">
        <div class="login__form">
          <input type="text" class="account" name="acc">
          <input type="password" class="password" name="pwd">
          <label for="captcha" class="captcha">驗証碼／verification code</label>
          <div class="capchadiv">
            <input type="text" name="capcha" id="captcha" class="captchainput" style="width:35%;vertical-align:top;" />
            <img src="imagebuilder.php" height="50" alt="">
            <!-- <img src="images/captcha.png" height="50"  alt=""> -->
          </div>

        </div>
        <input type="hidden" name="formsigncode" value="<?= $_SESSION['website']['formsigncode'] ?>">

        <button class="style01 login fsubmit" data-type="mlogin">確認登入</button>
      </form>

      <a class="forget_btn">忘記密碼</a>
      <div class="forget__form">
        <p>請輸入信箱，我們將會將密碼發送至您的信箱。</p>
        <input type="text" name="">
        <div class="code"><input type="text" name=""><img src="images/num.png"></div>
        <button class="style01 submit">確認送出</button>
      </div>
      <button class="style01 regi" onClick="location.href='register.php'">註冊成為會員</button>

    </section>


  </article>



  <footer data-role="footer">
    <div class="container-fluid pay" id="pay">
      <h5>匯款資訊</h5>
      <p>
        <span>華越資通企管顧問有限公司</span>　
        <span>（009）彰化銀行西屯分行</span>　
        <span>93320101520－300</span>
      </p>
    </div>
    <div class="container-fluid footer">
      <p>進銷存軟體．採購訂單．會計系統．票據．製造業．POS系統．發票．固定資產．人事薪資．維修關懷系統．網頁製作．網路行銷</p>
      <p class="copyright">版權所有 © 2018 華越資通企管顧問有限公司 All Rights Reserved</p>
    </div>

  </footer>
</body>

</html>

<style>
  .captcha {
    text-align: center;
    display: inline-block;
    width: 100%;
    margin: 22px 0 5px 0;
    font-weight: bold;
    font-size: 15px;
    cursor: default;
  }

  .captchainput {
    background: center no-repeat #f6f6f6;

  }

  .capchadiv {
    display: flex;
    flex-direction: column;
    align-content: center;
    flex-wrap: wrap;
  }
</style>