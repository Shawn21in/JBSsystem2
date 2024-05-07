<?php
require_once("include/web.config.php");
if (!$_Login) {
  header("Location:index.php");
  exit;
}

$_Title = '健保眷屬關係';

$_No = 'family';           //按鈕列名稱，對應m_aside.php的<li data-no=" $_No ">

$comp = GET_COMP_DATA();

$Input   = GDC($_GET['c'], 'family');

$familyno = $Input['v'];

if (empty($familyno)) { //判斷是否為編輯模式
  $edit = 0;
} else {
  $family = $CM->GET_FAMILY_DATA($familyno);
  $family = $family[0];
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
              <h2>眷屬編號設定</h2>
            </div>

            <div class="card-body">
              <form id="form1" onsubmit="return false;">
                <input type="hidden" class="form-control" name="relationid" value="<?= $family['relationno'] ?>">
                <div class="row mb-2">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="relationno">眷屬編號 *</label>
                      <input type="text" data-name="眷屬編號" class="form-control" name="relationno" id="relationno" value="<?= $family['relationno'] ?>" placeholder="Ex:9" required>
                    </div>
                  </div>
                </div>

                <div class="form-group mb-4">
                  <label for="relationship">眷屬名稱 *</label>
                  <input type="text" data-name="眷屬名稱" class="form-control" name="relationship" id="relationship" value="<?= $family['relationship'] ?>" placeholder="Ex:外曾祖父母" required>
                  <span class="d-block mt-1">請輸入眷屬名稱全名。</span>
                </div>
                <div class="d-flex justify-content-end mt-5">
                  <button type="button" class="btn btn-success mb-2 btn-pill mr-2" onclick="location.href='m_familylist.php'">查看所有編號</button>
                  <button type="button" class="btn btn-primary mb-2 btn-pill saveBtn" data-type="family_edit">
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