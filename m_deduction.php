<?php
require_once("include/web.config.php");
if (!$_Login) {
  header("Location:index.php");
  exit;
}

$_Title = '加扣款編號設定';

$_No = 'deduction';           //按鈕列名稱，對應m_aside.php的<li data-no=" $_No ">

$comp = GET_COMP_DATA();

$Input   = GDC($_GET['c'], 'deduction');

$deductionno = $Input['v'];

if (empty($deductionno)) { //判斷是否為編輯模式
  $edit = 0;
} else {
  $deduction = $CM->GET_DEDUCTION_DATA($deductionno);
  $deduction = $deduction[0];
  $edit = 1;
}

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
              <h2>加扣款編號設定</h2>
            </div>

            <div class="card-body">
              <form id="form1" onsubmit="return false;">
                <input type="hidden" class="form-control" name="deductionid" value="<?= $deduction['deductionno'] ?>">
                <div class="row mb-2">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="deductionno">加扣款編號 *</label>
                      <input type="text" data-name="加扣款編號" class="form-control" name="deductionno" id="deductionno" value="<?= $deduction['deductionno'] ?>" placeholder="Ex:" required>
                    </div>
                  </div>
                </div>

                <div class="form-group mb-4">
                  <label for="deductionname">加扣款名稱 *</label>
                  <input type="text" data-name="加扣款名稱" class="form-control" name="deductionname" id="deductionname" value="<?= $deduction['deductionname'] ?>" placeholder="Ex:" required>
                  <span class="d-block mt-1">請輸入加扣款名稱。</span>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="dedmny">加扣款金額 *</label>
                      <input type="number" data-name="加扣款金額" class="form-control" name="dedmny" id="dedmny" step="any" value="<?= $deduction['dedmny'] ?>" required>
                    </div>
                  </div>
                </div>
                <div class="d-flex justify-content-end mt-5">
                  <button type="button" class="btn btn-success mb-2 btn-pill mr-2" onclick="location.href='m_deductionlist.php'">查看所有編號</button>
                  <button type="button" class="btn btn-primary mb-2 btn-pill saveBtn" data-type="deduction_edit">
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