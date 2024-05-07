<?php
require_once("include/web.config.php");
if (!$_Login) {
  header("Location:index.php");
  exit;
}

$_Title = '會員中心';

$_No = 'profile';           //按鈕列名稱，對應m_aside.php的<li data-no=" $_No ">

$comp = GET_COMP_DATA();
?>
<!DOCTYPE html>
<html dir="ltr" lang="tw">

<head>
  <script>
    var no = '<?= $_No ?>';
  </script>
  <?php include('m_head.php'); ?>
  <script src="js/member/profile.js"></script>
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">
  <script>
    NProgress.configure({
      showSpinner: false
    });
    NProgress.start();
  </script>
  <!-- <div id="toaster"></div> -->
  <!-- ====================================
    ——— WRAPPER
    ===================================== -->
  <div class="wrapper">
    <!-- ====================================
          ——— LEFT SIDEBAR WITH OUT FOOTER
        ===================================== -->
    <?php require('m_aside.php'); ?>
    <!-- ====================================
        ——— PAGE WRAPPER
        ===================================== -->
    <div class="page-wrapper">
      <!-- Header -->
      <?php require('m_header.php'); ?>
      <!-- ====================================
          ——— CONTENT WRAPPER
          ===================================== -->
      <div class="content-wrapper">
        <div class="content">
          <div class="bg-white border rounded">
            <div class="row no-gutters">
              <div class="col-lg-4 col-xl-3">
                <div class="profile-content-left profile-left-spacing pt-5 pb-3 px-3 px-xl-5">
                  <div class="card text-center widget-profile px-0 border-0">
                    <div class="card-img mx-auto rounded-circle">
                      <img src="images/member_.png" alt="user image">
                    </div>

                    <div class="card-body">
                      <h4 class="py-2 text-dark"><?= $comp['coname1'] ?></h4>
                      <p><?= $comp['coemail'] ?></p>
                    </div>
                  </div>

                  <hr class="w-100">

                  <div class="contact-info pt-4">
                    <h5 class="text-dark mb-1">帳號資訊</h5>
                    <p class="text-dark font-weight-medium pt-4 mb-2">帳號狀態</p>
                    <p><?= $_MemberData['Company_Is_Pay'] ? '開通' : '尚未開通' ?></p>
                    <p class="text-dark font-weight-medium pt-4 mb-2">使用版本</p>
                    <p><?= $plan[$_MemberData['Company_Plan']] ?></p>
                    <p class="text-dark font-weight-medium pt-4 mb-2">使用期限</p>
                    <p><?= date('Y-m-d', strtotime($_MemberData['Company_END'])) ?></p>
                    <!-- <p class="text-dark font-weight-medium pt-4 mb-2">Social Profile</p> -->
                    <!-- <p class="pb-3 social-button">
                      <a href="#" class="mb-1 btn btn-outline btn-twitter rounded-circle">
                        <i class="mdi mdi-twitter"></i>
                      </a>

                      <a href="#" class="mb-1 btn btn-outline btn-linkedin rounded-circle">
                        <i class="mdi mdi-linkedin"></i>
                      </a>

                      <a href="#" class="mb-1 btn btn-outline btn-facebook rounded-circle">
                        <i class="mdi mdi-facebook"></i>
                      </a>

                      <a href="#" class="mb-1 btn btn-outline btn-skype rounded-circle">
                        <i class="mdi mdi-skype"></i>
                      </a>
                    </p> -->
                  </div>
                </div>
              </div>

              <div class="col-lg-8 col-xl-9">
                <div class="profile-content-right profile-right-spacing py-5">
                  <ul class="nav nav-tabs px-3 px-xl-5 nav-style-border" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">公司資訊</a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">帳號設定</a>
                    </li>
                  </ul>

                  <div class="tab-content px-3 px-xl-5" id="myTabContent">
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="tab-pane-content mt-5">
                        <form id="form1" onsubmit="return false;">
                          <div class="row mb-2">
                            <div class="col-lg-2">
                              <div class="form-group">
                                <label for="cono">公司編號 *</label>
                                <input type="text" data-name="公司編號" class="form-control" name="cono" id="cono" maxlength="2" value="<?= $comp['cono'] ?>" placeholder="Ex:01" required>
                              </div>
                            </div>

                            <div class="col-lg-10">
                              <div class="form-group">
                                <label for="coname2">公司名稱 *</label>
                                <input type="text" data-name="公司名稱" class="form-control" name="coname2" id="coname2" maxlength="50" value="<?= $comp['coname2'] ?>" placeholder="Ex:庫點子文創資訊產業有限公司" required>
                                <span class="d-block mt-1">請輸入公司全名。</span>
                              </div>
                            </div>

                          </div>

                          <div class="row mb-2">
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="coname1">公司簡號 *</label>
                                <input type="text" data-name="公司簡號" class="form-control" name="coname1" id="coname1" maxlength="10" value="<?= $comp['coname1'] ?>" placeholder="Ex:庫點子" required>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="coper">負責人 *</label>
                                <input type="text" data-name="負責人" class="form-control" name="coper" id="coper" maxlength="10" value="<?= $comp['coper'] ?>" placeholder="Ex:廖石龍" required>
                              </div>
                            </div>
                          </div>

                          <div class="row mb-2">
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="couno">統編 *</label>
                                <input type="text" data-name="統編" class="form-control" name="couno" id="couno" maxlength="8" value="<?= $comp['couno'] ?>" placeholder="Ex:0423586802" required>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="coemail">電子信箱 *</label>
                                <input type="email" data-name="電子信箱" class="form-control" name="coemail" id="coemail" maxlength="68" value="<?= $comp['coemail'] ?>" placeholder="Ex:bmidp888@gmail.com" required>
                              </div>
                            </div>
                          </div>

                          <div class="row mb-2">
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="cotel1">公司電話 *</label>
                                <input type="text" data-name="公司電話" class="form-control" name="cotel1" id="cotel1" maxlength="20" value="<?= $comp['cotel1'] ?>" placeholder="Ex:0423586802" required>
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="cofax1">公司傳真 *</label>
                                <input type="text" data-name="公司傳真" class="form-control" name="cofax1" id="cofax1" maxlength="20" value="<?= $comp['cofax1'] ?>" placeholder="Ex:0423586807" required>
                              </div>
                            </div>
                          </div>

                          <div class="form-group mb-4">
                            <label for="coaddr1">地址 *</label>
                            <input type="text" data-name="地址" class="form-control" name="coaddr1" id="coaddr1" maxlength="60" value="<?= $comp['coaddr1'] ?>" placeholder="Ex: 407台中市西屯區中工二路120號" required>
                          </div>

                          <div class="row mb-2">
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="laobaono">勞保投保代號 *</label>
                                <input type="text" data-name="勞保投保代號" class="form-control" name="laobaono" id="laobaono" maxlength="10" value="<?= $comp['laobaono'] ?>" placeholder="Ex:1234" required>
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="jianbaono">健保投保代號 *</label>
                                <input type="text" data-name="健保投保代號" class="form-control" name="jianbaono" id="jianbaono" maxlength="10" value="<?= $comp['jianbaono'] ?>" placeholder="Ex:1234" required>
                              </div>
                            </div>
                          </div>

                          <div class="form-group mb-4">
                            <label for="cowww">網路地址</label>
                            <input type="text" data-name="網路地址" class="form-control" name="cowww" id="cowww" maxlength="68" value="<?= $comp['cowww'] ?>" placeholder="Ex:https://www.bm888.com.tw/">
                          </div>

                          <div class="form-group mb-4">
                            <label for="comemo1">備註</label>
                            <input type="text" data-name="備註" class="form-control" name="comemo1" id="comemo1" maxlength="68" value="<?= $comp['comemo1'] ?>" placeholder="Ex:">
                          </div>
                          <!-- <div class="form-group mb-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" value="albrecht.straub@gmail.com">
                          </div> -->

                          <div class="d-flex justify-content-end mt-5">
                            <button type="button" class="btn btn-primary mb-2 btn-pill memBtn" data-type="mem_edit">儲存</button>
                          </div>
                        </form>
                      </div>
                    </div>

                    <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                      <div class="tab-pane-content mt-5">
                        <form id="form2" onsubmit="return false;">
                          <div class="form-group mb-4">
                            <label for="account">帳號</label>
                            <input type="text" class="form-control" id="account" value="<?= $_MemberData['Company_Acc'] ?>" readonly>
                          </div>
                          <div class="form-group mb-4">
                            <label for="oldPassword">舊密碼 *</label>
                            <input type="password" data-name="舊密碼" name="oldPassword" class="form-control" id="oldPassword" required>
                          </div>

                          <div class="form-group mb-4">
                            <label for="newPassword">新密碼 *</label>
                            <input type="password" data-name="新密碼" name="newPassword" class="form-control" id="newPassword" required>
                          </div>

                          <div class="form-group mb-4">
                            <label for="conPassword">重新輸入新密碼 *</label>
                            <input type="password" data-name="重新輸入新密碼" name="conPassword" class="form-control" id="conPassword" required>
                            <span class="d-block mt-1 text-danger">此欄位需與新密碼完全相同。</span>
                          </div>

                          <div class="d-flex justify-content-end mt-5">
                            <button type="button" class="btn btn-primary mb-2 btn-pill pwBtn" data-type="pw_edit">儲存</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End Content -->
      </div><!-- End Content Wrapper -->
      <!-- Footer -->
      <?php require('m_footer.php'); ?>
    </div><!-- End Page Wrapper -->
  </div><!-- End Wrapper -->
  <!-- <script type="module">
      import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';
      const el = document.createElement('pwa-update');
      document.body.appendChild(el);
    </script> -->

</body>

</html>