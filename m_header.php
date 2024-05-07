<header class="main-header" id="header">
  <nav class="navbar navbar-static-top navbar-expand-lg">
    <!-- Sidebar toggle button -->
    <button class="sidebar-toggle" id="sidebar-toggler"><span class="sr-only">Toggle navigation</span></button> <!-- search form -->
    <div class="search-form d-none d-lg-inline-block">
      <div class="input-group">
        <button class="btn btn-flat" id="search-btn" name="search" type="button"><i class="mdi mdi-magnify"></i></button> <input autocomplete="off" autofocus="" class="form-control" id="search-input" name="query" placeholder="'button', 'chart' etc." type="text">
      </div>
      <div id="search-results-container">
        <ul id="search-results"></ul>
      </div>
    </div>
    <input type="hidden" name="token" value="<?= $_MemberData['Company_Verify'] ?>">
    <div class="navbar-right">
      <ul class="nav navbar-nav">
        <!-- User Account -->
        <li class="dropdown user-menu">
          <button class="dropdown-toggle nav-link" data-toggle="dropdown"><img alt="User Image" class="user-image" src="images/member_.png"> <span class="d-none d-lg-inline-block"><?= $_MemberData['Company_NAME'] ?>
            </span></button>
          <ul class="dropdown-menu dropdown-menu-right">
            <!-- User image -->
            <li class="dropdown-header">
              <img alt="User Image" class="img-circle" src="images/member_.png">
              <div class="d-inline-block">
                <?= $_MemberData['Company_NAME'] ?>
                <small class="pt-1"><?= $_MemberData['Company_EMAIL'] ?>
                </small>
              </div>
            </li>
            <li>
              <a href="index.php"><i class="mdi mdi-home"></i> 首頁</a>
            </li>
            <li>
              <a data-tag="profile" href="m_profile.php#profile"><i class="mdi mdi-account"></i> 公司資訊</a>
            </li>
            <!-- <li>
              <a href="#"><i class="mdi mdi-email"></i> Message</a>
            </li>
            <li>
              <a href="#"><i class="mdi mdi-diamond-stone"></i> Projects</a>
            </li> -->
            <li class="right-sidebar-in">
              <a data-tag="settings" href="m_profile.php#settings"><i class="mdi mdi-settings"></i> 帳號設定</a>
            </li>
            <li class="dropdown-footer">
              <a class="logout" href="javascript:void(0);"><i class="mdi mdi-logout"></i> 登出</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>