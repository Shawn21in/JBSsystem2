<?php
require_once("include/web.config.php");
if (!$_Login) {
  header("Location:index.php");
  exit;
}

$_Title = '健保等級列表';

$_No = 'purchaser1';           //按鈕列名稱，對應m_aside.php的<li data-no=" $_No ">

$comp = GET_COMP_DATA();

$bank = $CM->GET_PURCHASER1_DATA();

?>
<!DOCTYPE html>
<html dir="ltr" lang="tw">

<head>
  <script>
    var no = '<?= $_No ?>';
  </script>
  <?php include('m_head.php'); ?>
  <script src="js/member/labor.js"></script>
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
                    <h2>健保等級列表</h2>
                  </div>
                  <div class="card-body">
                    <!-- <p class="mb-5"></p> -->
                    <div id="enabled" data-flag="0">
                      <button type="button" class="mb-1 btn btn-primary">
                        <span class="mdi mdi-pencil"></span> 編輯模式</button>
                    </div>
                    <div class="editnow" style="display:none;">
                      <button type="button" class="mb-1 btn btn-outline-primary" id="addBtn">
                        <i class=" mdi mdi-plus mr-1"></i> 新增</button>
                      <button type="button" class="mb-1 btn btn-outline-success saveBtn" data-type="purchaser_edit">
                        <span class="mdi mdi-content-save"></span> 全部儲存</button>
                    </div>
                    <form id="form1" onsubmit="return false;">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th scope="col">等級</th>
                            <th scope="col">投保金額</th>
                            <th scope="col">健保費</th>
                            <th scope="col">雇主負擔金額</th>
                            <th scope="col">選項</th>
                          </tr>
                        </thead>

                        <tbody class="datalist">
                          <?php foreach ($bank as $key => $value) { ?>
                            <tr>
                              <td><?= $value['purchaserno'] ?></td>
                              <td><?= $value['purchasermny'] ?></td>
                              <td><?= $value['purchaserhmny'] ?></td>
                              <td><?= $value['employerPurchaserhmny'] ?></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                        <tbody class="edit_datalist" style="display:none">
                          <?php foreach ($bank as $key => $value) { ?>
                            <tr>
                              <td class="no"><?= $value['purchaserno'] ?></td>
                              <td><input class="form-control" name="purchasermny[]" type="number" value="<?= $value['purchasermny'] ?>" step="any"></td>
                              <td><input class="form-control" name="purchaserhmny[]" type="number" value="<?= $value['purchaserhmny'] ?>" step="any"></td>
                              <td><input class="form-control" name="employerPurchaserhmny[]" type="number" value="<?= $value['employerPurchaserhmny'] ?>" step="any"></td>
                              <td><a href="javascript:void(0)" class="data_del"><span class="mdi mdi-delete mdi-18px"></span></a></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </form>
                    <table class="invisible_table">
                      <tbody>
                        <tr>
                          <td class="no"></td>
                          <td><input class="form-control" name="purchasermny[]" type="number" value="" step="any"></td>
                          <td><input class="form-control" name="purchaserhmny[]" type="number" value="" step="any"></td>
                          <td><input class="form-control" name="employerPurchaserhmny[]" type="number" value="" step="any"></td>
                          <td><a href="javascript:void(0)" class="data_del"><span class="mdi mdi-delete mdi-18px"></span></a></td>
                        </tr>
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