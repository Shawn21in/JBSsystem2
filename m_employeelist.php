<?php
require_once("include/web.config.php");
if (!$_Login) {
  header("Location:index.php");
  exit;
}

$_Title = '員工列表';

$_No = 'employee';           //按鈕列名稱，對應m_aside.php的<li data-no=" $_No ">

$comp = GET_COMP_DATA();

$employee_list = $CM->get_employee_list();

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
                    <h2>員工列表</h2>
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
                    <button type="button" class="btn btn-outline-primary mb-2" onclick="location.href='m_employee.php'">
                      <i class=" mdi mdi-plus mr-1"></i> 新增一筆</button>
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">編號</th>
                          <th scope="col">員工姓名</th>
                          <th scope="col">性別</th>
                          <th scope="col">卡片編號</th>
                          <th scope="col">部門名稱</th>
                          <th scope="col">職謂名稱</th>
                          <th scope="col">到職日期</th>
                          <th scope="col">在職狀況</th>
                          <th scope="col">選項</th>
                        </tr>
                      </thead>

                      <tbody class="datalist">
                        <?php foreach ($employee_list as $key => $value) { ?>
                          <tr>
                            <td><?= $value['employeid'] ?></td>
                            <td><?= $value['employename'] ?></td>
                            <td><?= $value['sex'] ?></td>
                            <td><?= $value['no'] ?></td>
                            <td><?= $value['partname'] ?></td>
                            <td><?= $value['appname'] ?></td>
                            <td><?= $value['workday'] ?></td>
                            <td><?= $value['expireday'] == '' ? '在職' : '離職' ?></td>
                            <td>
                              <form onsubmit="return false;">
                                <a href="m_employee.php?c=<?php echo OEncrypt('v=' . $value['employeid'], 'employee'); ?>"><span class="mdi mdi-pencil mdi-18px"></span></a>
                                <input type="hidden" name="eid" value="<?= rawurldecode(OEncrypt('v=' . $value['eid'], 'employee')) ?>">
                                <a href="javascript:void(0)" class="delBtn" data-type="employee_del"><span class="mdi mdi-delete mdi-18px"></span></a>
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