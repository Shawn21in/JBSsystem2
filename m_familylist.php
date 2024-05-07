<?php
require_once("include/web.config.php");
if (!$_Login) {
  header("Location:index.php");
  exit;
}

$_Title = '眷屬關係列表';

$_No = 'family';           //按鈕列名稱，對應m_aside.php的<li data-no=" $_No ">

$comp = GET_COMP_DATA();

$family = $CM->GET_FAMILY_DATA();

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
                    <h2>眷屬關係列表</h2>
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
                    <button type="button" class="mb-1 btn btn-outline-primary" onclick="location.href='m_family.php'">
                      <i class=" mdi mdi-plus mr-1"></i> 新增</button>
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">編號</th>
                          <th scope="col">眷屬名稱</th>
                          <th scope="col">選項</th>
                        </tr>
                      </thead>

                      <tbody class="datalist">
                        <?php foreach ($family as $key => $value) { ?>
                          <tr>
                            <td><?= $value['relationno'] ?></td>
                            <td><?= $value['relationship'] ?></td>
                            <td>
                              <form onsubmit="return false;">
                                <a href="m_family.php?c=<?php echo OEncrypt('v=' . $value['relationno'], 'family'); ?>"><span class="mdi mdi-pencil mdi-18px"></span></a>
                                <input type="hidden" name="relationno" value="<?= rawurldecode(OEncrypt('v=' . $value['relationno'], 'family')) ?>">
                                <a href="javascript:void(0)" class="delBtn" data-type="family_del"><span class="mdi mdi-delete mdi-18px"></span></a>
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