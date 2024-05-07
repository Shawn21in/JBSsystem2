<?php
require_once("include/web.config.php");
if (!$_Login) {
  header("Location:index.php");
  exit;
}

$_Title = '國定假日列表';

$_No = 'holidays';           //按鈕列名稱，對應m_aside.php的<li data-no=" $_No ">

$comp = GET_COMP_DATA();

$holidays_list = $CM->GET_HOLIDAYS_LIST();

?>
<!DOCTYPE html>
<html dir="ltr" lang="tw">

<head>
  <script>
    var no = '<?= $_No ?>';
  </script>
  <?php include('m_head.php'); ?>
  <script type="text/javascript" src="js/member/searchbox.js"></script>
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
          <div class="row">
            <div class="col-12">
              <div class="col-lg-12">
                <div class="card card-default">
                  <div class="card-header card-header-border-bottom">
                    <h2>出勤列表</h2>
                    <div class="search_box">
                      <div class="input-group">
                        <input type="text" class="form-control" id="search_box" placeholder="搜尋" aria-label="search">
                        <div class="input-group-append">
                          <span class="input-group-text bg-primary"><span class="mdi mdi-magnify"></span></span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card-body">
                    <!-- <p class="mb-5"></p> -->
                    <button type="button" class="mb-1 btn btn-outline-primary" onclick="location.href='m_holidays.php'">
                      <i class=" mdi mdi-plus mr-1"></i> 新增</button>
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">年度</th>
                          <th scope="col">選項</th>
                        </tr>
                      </thead>

                      <tbody class="datalist">
                        <?php foreach ($holidays_list as $key => $value) { ?>
                          <tr>
                            <td><?= $value['niandu'] ?></td>
                            <td>
                              <form onsubmit="return false;">
                                <a href="m_holidays.php?c=<?php echo OEncrypt('v=' . $value['niandu'], 'holidays'); ?>"><span class="mdi mdi-pencil mdi-18px"></span></a>
                                <input type="hidden" name="niandu" value="<?= rawurldecode(OEncrypt('v=' . $value['niandu'], 'holidays')) ?>">
                                <a href="javascript:void(0)" class="delBtn" data-type="holidays_del"><span class="mdi mdi-delete mdi-18px"></span></a>
                              </form>
                            </td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
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