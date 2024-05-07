<header data-role="header">
  <nav role="navigation" class="navbar-spy">
    <div class="container-fluid">
      <h1>
        <a href="index.php"><img src="images/logo.png" alt="庫點子文創資訊產業有限公司"></a>
      </h1>


      <ul class="nav navbar-example">
        <li class="intro_btn bttn"><a href="index.php#intro">
            <p>功能介紹</p>
          </a></li>
        <li class="program_btn bttn"><a href="index.php#program">
            <p>方案介紹</p>
          </a></li>
        <li class="download_btn bttn"><a href="index.php#download">
            <p>下載安裝及教學</p>
          </a></li>

        <?php if ($_Login) { ?>
          <li class=""><a href="member.php">
              <p>會員中心</p>
            </a></li>
        <?php } else { ?>
          <li class=""><a href="register.php">
              <p>直接購買成為會員</p>
            </a></li><!--register.php-->
          <li class=""><a href="login.php">
              <p>管理會員登入</p>
            </a></li>
        <?php } ?>
        <!-- 
        <li class="pay_btn bttn"><a href="#pay"><p>匯款資訊</p></a></li>
        -->
      </ul>


    </div>
  </nav>
  <button class="mobile_btn">
    <div id="nav-icon4"> <span></span> <span></span> <span></span> </div>
  </button>


  <div class="mobile_nav navbar-spy">
    <ul>
      <li class="intro_btn bttn"><a href="index.php#intro">功能介紹</a></li>
      <li class="program_btn bttn"><a href="index.php#program">方案介紹</a></li>
      <li class="download_btn bttn"><a href="index.php#download">下載安裝及教學</a></li>

      <?php if ($mlogin_TF) { ?>
        <li class=""><a href="member_edit.php">會員中心</a></li>
      <?php } else { ?>
        <li class=""><a href="register.php">直接購買成為會員</a></li>
        <li class=""><a href="login.php">管理會員登入</a></li>
      <?php } ?>
      <!--
      <li class="pay_btn bttn"><a>匯款資訊</a></li>
      -->
    </ul>
  </div>
</header>