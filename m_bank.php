<?php
require_once("include/web.config.php");
if (!$_Login) {
  header("Location:index.php");
  exit;
}

$Input   = GDC($_GET['c'], 'bank');

$bankno = $Input['v'];

if (empty($bankno)) { //判斷是否為編輯模式
  $edit = 0;
} else {
  $bank = $CM->GET_BANK_DATA($bankno);
  $bank = $bank[0];
  $edit = 1;
}



$_Title = '銀行編號設定';

$_No = 'bank';           //按鈕列名稱，對應m_aside.php的<li data-no=" $_No ">


?>
<!DOCTYPE html>
<html dir="ltr" lang="tw">

<head>
  <script>
    var no = '<?= $_No ?>';
  </script>
  <?php include('m_head.php'); ?>
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
          <div class="card card-default">
            <div class="card-header card-header-border-bottom">
              <h2>銀行編號設定</h2>
            </div>

            <div class="card-body">
              <form id="form1" onsubmit="return false;">
                <input type="hidden" class="form-control" name="bankid" value="<?= $bank['bankno'] ?>">
                <div class="row mb-2">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="bankno">銀行編號 *</label>
                      <input type="text" data-name="銀行編號" class="form-control" name="bankno" id="bankno" value="<?= $bank['bankno'] ?>" placeholder="Ex:822" required>
                    </div>
                  </div>
                </div>

                <div class="form-group mb-4">
                  <label for="bankname">銀行名稱 *</label>
                  <input type="text" data-name="銀行名稱" class="form-control" name="bankname" id="bankname" value="<?= $bank['bankname'] ?>" placeholder="Ex:中國信託商業銀行" required>
                  <span class="d-block mt-1">請輸入銀行名稱全名。</span>
                </div>
                <div class="d-flex justify-content-end mt-5">
                  <button type="button" class="btn btn-success mb-2 btn-pill mr-2" onclick="location.href='m_banklist.php'">查看所有編號</button>
                  <button type="button" class="btn btn-primary mb-2 btn-pill saveBtn" data-type="bank_edit">
                    <?php if ($edit) { ?>
                      儲存
                    <?php } else { ?>
                      新增
                    <?php } ?>
                  </button>
                </div>
              </form>
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