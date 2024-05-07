<?php
require_once("include/web.config.php");
if (!$_Login) {
  header("Location:index.php");
  exit;
}

$_Title = '國定假日設定';

$_No = 'holidays';           //按鈕列名稱，對應m_aside.php的<li data-no=" $_No ">

$comp = GET_COMP_DATA();

$Input   = GDC($_GET['c'], 'holidays');

$niandu = $Input['v'];

if (empty($niandu)) { //判斷是否為編輯模式
  $edit = 0;
} else {
  $holidays = $CM->GET_holidays_DATA($niandu);
  if (empty($holidays)) {
    JSAH("資料不存在", "m_holidayslist.php");
    exit;
  }
  $len = count($holidays);
  $ad_year = $niandu + 1911;
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
  <script src="js/member/holidays.js"></script>
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
                    <h2>國定假日設定</h2>
                    <button type="button" class="btn btn-success mb-2 btn-pill mr-2" onclick="location.href='m_holidayslist.php'">查看所有假別</button>
                  </div>
                  <div class="card-body">
                    <form id="form1" onsubmit="return false;">
                      <div class="row mb-2">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="niandu">年度 *<span style="color:red">(請輸入民國年份)</span></label>
                            <input type="hidden" class="form-control" name="origin_niandu" value="<?= $niandu ?>">
                            <div class="niandu-group">
                              <input type="text" data-name="年度" class="form-control" name="niandu" id="niandu" placeholder="Ex:113" value="<?= $edit ? $niandu : date("Y") - 1911 ?>" <?= $edit ? 'readonly' : '' ?> required>
                              <div id="ensure">
                                <button type="button" class="ml-2 mb-1 btn btn-primary" id="ensureBtn" <?= $edit ? 'disabled' : '' ?>>
                                  <span class="mdi mdi-pencil"></span> 確定</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div>
                        <button type="button" class="mb-1 btn btn-outline-primary" id="addBtn" <?= $edit ? '' : 'style="display:none;"' ?>>
                          <i class=" mdi mdi-plus mr-1"></i> 新增</button>
                        <button type="button" class="mb-1 btn btn-outline-info" id="autoimpBtn" <?= $edit ? '' : 'style="display:none;"' ?>>
                          <i class="autoupload-icon mdi mdi-cloud-upload-outline mr-1"></i> 線上自動匯入</button>
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th scope="col">假日日期</th>
                              <th scope="col">假日名稱</th>
                              <th scope="col">出勤日</th>
                              <th scope="col">刪除</th>
                            </tr>
                          </thead>
                          <tbody class="datalist">
                            <?php for ($i = 0; $i < $len; $i++) { ?>
                              <tr>
                                <th scope="col">
                                  <input name="holiday[]" min="<?= $edit ? date($ad_year . '-01-01') : date('Y-01-01') ?>" max="<?= $edit ? date($ad_year . '-12-31') : date('Y-12-31') ?>" value="<?= date("Y-m-d", strtotime($ad_year . $holidays[$i]['holiday'])) ?>" type="date" class="form-control">
                                </th>
                                <th scope="col"><input name="holidayName[]" value="<?= $holidays[$i]['holidayName'] ?>" type="text" class="form-control"></th>
                                <th scope="col">
                                  <select class="form-control" name="AttendDay[]" style="width:unset">
                                    <option value="工作日" <?= $holidays[$i]['AttendDay'] == '工作日' ? 'selected' : '' ?>>工作日</option>
                                    <option value="休息日" <?= $holidays[$i]['AttendDay'] == '休息日' ? 'selected' : '' ?>>休息日</option>
                                    <option value="例假日" <?= $holidays[$i]['AttendDay'] == '例假日' ? 'selected' : '' ?>>例假日</option>
                                    <option value="國定日" <?= $holidays[$i]['AttendDay'] == '國定日' ? 'selected' : '' ?>>國定日</option>
                                    <option value="空班日" <?= $holidays[$i]['AttendDay'] == '空班日' ? 'selected' : '' ?>>空班日</option>
                                  </select>
                                </th>
                                <th scope="col">
                                  <a href="javascript:void(0)" class="data_del"><span class="mdi mdi-delete mdi-18px"></span></a>
                                </th>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </form>
                    <table class="invisible_table">
                      <tbody>
                        <tr>
                          <th scope="col">
                            <input name="holiday[]" min="<?= $edit ? date($ad_year . '-01-01') : date('Y-01-01') ?>" max="<?= $edit ? date($ad_year . '-12-31') : date('Y-12-31') ?>" type="date" class="form-control">
                          </th>
                          <th scope="col"><input name="holidayName[]" type="text" class="form-control"></th>
                          <th scope="col">
                            <select class="form-control" name="AttendDay[]" style="width:unset">
                              <option value="工作日">工作日</option>
                              <option value="休息日">休息日</option>
                              <option value="例假日">例假日</option>
                              <option value="國定日">國定日</option>
                              <option value="空班日">空班日</option>
                            </select>
                          </th>
                          <th scope="col">
                            <a href="javascript:void(0)" class="data_del"><span class="mdi mdi-delete mdi-18px"></span></a>
                          </th>
                        </tr>
                      </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-5">
                      <button type="button" class="btn btn-primary mb-2 btn-pill saveBtn" data-type="holidays_edit">
                        <?php if ($edit) { ?>
                          儲存
                        <?php } else { ?>
                          新增
                        <?php } ?>
                      </button>
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