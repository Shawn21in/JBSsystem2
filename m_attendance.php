<?php
require_once("include/web.config.php");
if (!$_Login) {
  header("Location:index.php");
  exit;
}

$_Title = '員工班別設定';

$_No = 'attendance';           //按鈕列名稱，對應m_aside.php的<li data-no=" $_No ">

$comp = GET_COMP_DATA();

$Input   = GDC($_GET['c'], 'attendance');

$attendanceno = $Input['v'];

if (empty($attendanceno)) { //判斷是否為編輯模式
  $edit = 0;
} else {
  $attendance = $CM->GET_ATTENDANCE_DATA($attendanceno);
  if (empty($attendance)) {
    JSAH("資料不存在", "m_attendancelist.php");
    exit;
  }
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
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="js/member/attendance.js"></script>
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
                    <h2>員工班別設定</h2>
                    <button type="button" class="btn btn-success mb-2 btn-pill mr-2" onclick="location.href='m_attendancelist.php'">查看所有班別</button>
                  </div>
                  <div class="card-body">
                    <form id="form1" onsubmit="return false;">
                      <div class="row mb-2">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="attendanceno">班別編號 *</label>
                            <input type="hidden" class="form-control" name="origin_attendanceno" value="<?= $attendance[0]['attendanceno'] ?>">
                            <input type="text" data-name="班別編號" class="form-control" name="attendanceno" id="attendanceno" placeholder="Ex:B" value="<?= $attendance[0]['attendanceno'] ?>" required>
                          </div>
                        </div>
                      </div>
                      <div class="form-group mb-4">
                        <label for="attendancename">班別名稱 *</label>
                        <input type="text" data-name="班別名稱" class="form-control" name="attendancename" id="attendancename" placeholder="Ex:B" value="<?= $attendance[0]['attendancename'] ?>" required>
                      </div>
                      <div class="asy">
                        <button type="button" class="btn btn-secondary mb-2 btn-square" id="copyBtn">複製</button>
                        <span style="color:red;">* 星期一的資料複製給星期二~星期日的資料</span>
                      </div>
                      <div class="table-scroll">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th scope="col">星期</th>
                              <th scope="col">上班時間</th>
                              <th scope="col">遲到時間</th>
                              <th scope="col">休息時間1</th>
                              <th scope="col"></th>
                              <th scope="col">休息時間2</th>
                              <th scope="col"></th>
                              <th scope="col">下班時間</th>
                              <th scope="col">加班上班時間</th>
                              <th scope="col">加班下班時間</th>
                              <th scope="col">誤餐時間</th>
                              <th scope="col">出勤分鐘</th>
                              <th scope="col">出勤日</th>
                            </tr>
                          </thead>
                          <tbody class="datalist">
                            <?php foreach ($week_states as $key => $value) { ?>
                              <tr>
                                <td>
                                  &nbsp;&nbsp;&nbsp;<?= $value ?>&nbsp;&nbsp;&nbsp;
                                  <input class="form-control" name="attendanceid[]" type="hidden" value="<?= $attendance[$key - 1]['attendanceid'] ?>">
                                  <input class="form-control" name="week[]" type="hidden" value="<?= $value ?>">
                                </td>
                                <td><input class="form-control" name="ontime[]" type="time" value="<?= !empty($attendance[$key - 1]['ontime']) ? substr_replace($attendance[$key - 1]['ontime'], ':', 2, 0) : '' ?>"></td>
                                <td><input class="form-control" name="latetime[]" type="time" value="<?= !empty($attendance[$key - 1]['latetime']) ? substr_replace($attendance[$key - 1]['latetime'], ':', 2, 0) : '' ?>"></td>
                                <td><input class="form-control" name="resttime1[]" type="time" value="<?= !empty($attendance[$key - 1]['resttime1']) ? substr_replace($attendance[$key - 1]['resttime1'], ':', 2, 0) : '' ?>"></td>
                                <td><input class="form-control" name="resttime2[]" type="time" value="<?= !empty($attendance[$key - 1]['resttime2']) ? substr_replace($attendance[$key - 1]['resttime2'], ':', 2, 0) : '' ?>"></td>
                                <td><input class="form-control" name="resttime3[]" type="time" value="<?= !empty($attendance[$key - 1]['resttime3']) ? substr_replace($attendance[$key - 1]['resttime3'], ':', 2, 0) : '' ?>"></td>
                                <td><input class="form-control" name="resttime4[]" type="time" value="<?= !empty($attendance[$key - 1]['resttime4']) ? substr_replace($attendance[$key - 1]['resttime4'], ':', 2, 0) : '' ?>"></td>
                                <td><input class="form-control" name="offtime[]" type="time" value="<?= !empty($attendance[$key - 1]['offtime']) ? substr_replace($attendance[$key - 1]['offtime'], ':', 2, 0) : '' ?>"></td>
                                <td><input class="form-control" name="addontime[]" type="time" value="<?= !empty($attendance[$key - 1]['addontime']) ? substr_replace($attendance[$key - 1]['addontime'], ':', 2, 0) : '' ?>"></td>
                                <td><input class="form-control" name="addofftime[]" type="time" value="<?= !empty($attendance[$key - 1]['addofftime']) ? substr_replace($attendance[$key - 1]['addofftime'], ':', 2, 0) : '' ?>"></td>
                                <td><input class="form-control" name="mealtime[]" type="time" value="<?= !empty($attendance[$key - 1]['mealtime']) ? substr_replace($attendance[$key - 1]['mealtime'], ':', 2, 0) : '' ?>"></td>
                                <td><input class="form-control" name="worktime[]" type="text" style="width:unset" oninput="value=value.replace(/[^0-9]/g,'')" value="<?= $attendance[$key - 1]['worktime'] ?>"></td>
                                <td>
                                  <select class="form-control" name="daytype[]" style="width:unset">
                                    <option value="工作日" <?= $attendance[$key - 1]['type'] == '工作日' ? 'selected' : '' ?>>工作日</option>
                                    <option value="休息日" <?= $attendance[$key - 1]['type'] == '休息日' ? 'selected' : '' ?>>休息日</option>
                                    <option value="例假日" <?= $attendance[$key - 1]['type'] == '例假日' ? 'selected' : '' ?>>例假日</option>
                                    <option value="國定日" <?= $attendance[$key - 1]['type'] == '國定日' ? 'selected' : '' ?>>國定日</option>
                                    <option value="空班日" <?= $attendance[$key - 1]['type'] == '空班日' ? 'selected' : '' ?>>空班日</option>
                                  </select>
                                </td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </form>
                    <div class="d-flex justify-content-end mt-5">
                      <button type="button" class="btn btn-primary mb-2 btn-pill saveBtn" data-type="attendance_edit">
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