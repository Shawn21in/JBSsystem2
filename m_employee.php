<?php
require_once("include/web.config.php");
if (!$_Login) {
  header("Location:index.php");
  exit;
}

$_Title = '基本資料設定';

$_No = 'employee';           //按鈕列名稱，對應m_aside.php的<li data-no=" $_No ">

$comp = GET_COMP_DATA();

$jobs_list = $CM->GET_JOBS_DATA();

$part_list = $CM->GET_PART_DATA();

$attd_list = $CM->GET_ATTENDANCE_LIST();

$bank_list = $CM->GET_BANK_DATA();

$seclab1_list = $CM->GET_SECLAB1_DATA();

$purchaser1_list = $CM->GET_PURCHASER1_DATA();

$deduction_list = $CM->GET_DEDUCTION_DATA();

$Input   = GDC($_GET['c'], 'employee');

$employid = $Input['v'];

if (empty($employid)) { //判斷是否為編輯模式
  $edit = 0;
} else {
  $employee = $CM->get_employee_data($employid);
  $ed_list = $CM->get_employdeduction_list($employee['eid']);
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
  <link href="js/datepicker-tw/bootstrap-datepicker3.min.css" rel="stylesheet" />
  <script src="js/datepicker-tw/bootstrap-datepicker.js"></script>
  <script src="js/member/employee.js"></script>
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
              <div class="col-xl-12">
                <div class="profile-content-right profile-right-spacing py-5">
                  <div class="row">
                    <div class="col-lg-10">
                    </div>

                    <div class="col-lg-2">
                      <button type="button" class="btn btn-success mb-2 btn-pill mr-2" onclick="location.href='m_employeelist.php'">查看所有員工</button>
                    </div>

                  </div>
                  <ul class="nav nav-tabs px-3 px-xl-5 nav-style-border" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="employee-tab" data-toggle="tab" href="#employee" role="tab" aria-controls="employee" aria-selected="true">基本資料設定</a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="salary-tab" data-toggle="tab" href="#salary" role="tab" aria-controls="salary" aria-selected="false">薪資設定</a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="overtime-tab" data-toggle="tab" href="#overtime" role="tab" aria-controls="overtime" aria-selected="false">加班設定</a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="insurance-tab" data-toggle="tab" href="#insurance" role="tab" aria-controls="insurance" aria-selected="false">勞健保設定</a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="ededuction-tab" data-toggle="tab" href="#ededuction" role="tab" aria-controls="ededuction" aria-selected="false">固定加扣款</a>
                    </li>
                  </ul>
                  <div class="tab-content px-3 px-xl-5" id="myTabContent">
                    <div class="tab-pane fade show active" id="employee" role="tabpanel" aria-labelledby="employee-tab">
                      <div class="tab-pane-content mt-5">
                        <form id="form1" onsubmit="return false;">
                          <input type="hidden" name="eid" value="<?= $employee['eid'] ?>">
                          <div class="row mb-2">
                            <div class="col-lg-2">
                              <div class="form-group">
                                <label for="cono">公司編號 *</label>
                                <input type="text" data-name="公司編號" class="form-control" name="cono" id="cono" value="<?= $comp['cono'] ?>" readonly required>
                              </div>
                            </div>

                            <div class="col-lg-10">
                              <div class="form-group">
                                <label for="coname1">公司名稱 *</label>
                                <input type="text" data-name="公司名稱" class="form-control" name="coname1" id="coname1" value="<?= $comp['coname1'] ?>" readonly required>
                              </div>
                            </div>

                          </div>

                          <div class="row mb-2">
                            <div class="col-lg-2">
                              <div class="form-group">
                                <label for="employeid">員工編號 *</label>
                                <input type="text" data-name="員工編號" maxlength="10" class="form-control" name="employeid" id="employeid" value="<?= $employee['employeid'] ?>" placeholder="EX:A001" <?= $edit ? 'readonly' : '' ?> required>
                              </div>
                            </div>

                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="employename">員工姓名 *</label>
                                <input type="text" data-name="員工姓名" maxlength="10" class="form-control" name="employename" id="employename" value="<?= $employee['employename'] ?>" placeholder="EX:張先生" required>
                                <span class="d-block mt-1">請輸入員工全名。</span>
                              </div>
                            </div>

                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="EngName">英文姓名</label>
                                <input type="text" data-name="英文姓名" maxlength="35" class="form-control" name="EngName" id="EngName" value="<?= $employee['EngName'] ?>" placeholder="EX:ZHANG,XIAN-SHENG">
                              </div>
                            </div>

                          </div>

                          <div class="row mb-2">
                            <div class="col-lg-2">
                              <div class="form-group">
                                <label for="no">卡片編號</label>
                                <input type="text" data-name="卡片編號" maxlength="10" class="form-control" name="no" id="no" value="<?= $employee['no'] ?>" placeholder="EX:A001">
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="id">身分證字號</label>
                                <input type="text" data-name="身分證字號" maxlength="10" class="form-control" name="id" id="id" value="<?= $employee['id'] ?>" placeholder="EX:A12345678">
                              </div>
                            </div>

                            <div class="col-lg-2">
                              <div class="form-group">
                                <label class="d-inline-block" for="">性別</label>
                                <ul class="list-unstyled list-inline">
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">男
                                      <input type="radio" name="sex" value="男" <?= $employee['sex'] == '男' ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>

                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">女
                                      <input type="radio" name="sex" value="女" <?= $employee['sex'] == '女' ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                </ul>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label class="d-inline-block" for="">婚姻狀態</label>
                                <ul class="list-unstyled list-inline">
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">未婚
                                      <input type="radio" name="marry" value="未" <?= $employee['marry'] == '未' ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>

                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">已婚
                                      <input type="radio" name="marry" value="已" <?= $employee['marry'] == '已' ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                </ul>
                              </div>
                            </div>
                            <div class="col-lg-2 form-pill">
                              <div class="form-group">
                                <label for="blood">血型</label>
                                <select class="form-control" id="blood" name="blood">
                                  <option value="O" <?= $employee['blood'] == 'O' ? 'selected' : '' ?>>O</option>
                                  <option value="A" <?= $employee['blood'] == 'A' ? 'selected' : '' ?>>A</option>
                                  <option value="B" <?= $employee['blood'] == 'B' ? 'selected' : '' ?>>B</option>
                                  <option value="AB" <?= $employee['blood'] == 'AB' ? 'selected' : '' ?>>AB</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="nationality">國籍</label>
                                <input type="text" data-name="國籍" maxlength="20" class="form-control" name="nationality" id="nationality" value="<?= $employee['nationality'] ?>" placeholder="EX:台灣">
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="born">籍貫</label>
                                <input type="text" data-name="籍貫" maxlength="16" class="form-control" name="born" id="born" value="<?= $employee['born'] ?>" placeholder="EX:台中市">
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="bornday">出生日期</label>
                                <div id="datebornday">
                                  <input type="text" data-name="出生日期" class="form-control datepicker-tw" name="bornday" id="bornday" value="<?= $employee['bornday'] ? $employee['bornday'] : '80-01-01' ?>">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="email">電子信箱</label>
                                <input type="text" data-name="通訊地址" maxlength="100" class="form-control" name="email" id="email" value="<?= $employee['email'] ?>" placeholder="EX：example@example.com.tw">
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="address">住址</label>
                                <input type="text" data-name="住址" maxlength="160" class="form-control" name="address" id="address" value="<?= $employee['address'] ?>" placeholder="Ex: 407台中市西屯區中工二路120號">
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="tel">聯絡電話</label>
                                <input type="text" data-name="聯絡電話" maxlength="16" class="form-control" name="tel" id="tel" value="<?= $employee['tel'] ?>" placeholder="EX：0412345678">
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="fax">傳真號碼</label>
                                <input type="text" data-name="傳真號碼" maxlength="16" class="form-control" name="fax" id="fax" value="<?= $employee['fax'] ?>" placeholder="EX：0412345678">
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="mphone">行動電話</label>
                                <input type="text" data-name="行動電話" maxlength="16" class="form-control" name="mphone" id="mphone" value="<?= $employee['mphone'] ?>" placeholder="EX：0912345678">
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">

                            <div class="col-lg-2 form-pill">
                              <div class="form-group">
                                <label for="partno">部門名稱 *</label>
                                <select class="form-control" data-name="部門名稱" id="partno" name="partno" required>
                                  <option value="" data-type="">選擇部門</option>
                                  <?php foreach ($part_list as $key => $value) { ?>
                                    <option value="<?= $value['partno'] ?>" data-type="<?= $value['partname'] ?>" <?= $employee['partno'] == $value['partno'] ? 'selected' : '' ?>><?= $value['partno'] ?>-<?= $value['partname'] ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="partname">　</label>
                                <input type="text" class="form-control" name="partname" id="partname" value="<?= $employee['partname'] ?>" readonly>
                              </div>
                            </div>
                            <div class="col-lg-2 form-pill">
                              <div class="form-group">
                                <label for="appno">職謂名稱</label>
                                <select class="form-control" data-name="職謂名稱" id="appno" name="appno">
                                  <option value="" data-type="">選擇職謂</option>
                                  <?php foreach ($jobs_list as $key => $value) { ?>
                                    <option value="<?= $value['appno'] ?>" data-type="<?= $value['appname'] ?>" <?= $employee['appno'] == $value['appno'] ? 'selected' : '' ?>><?= $value['appno'] ?>-<?= $value['appname'] ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="appname">　</label>
                                <input type="text" class="form-control" name="appname" id="appname" value="<?= $employee['appname'] ?>" readonly>
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-2 form-pill">
                              <div class="form-group">
                                <label for="presenttype">出勤類別 *</label>
                                <select class="form-control" data-name="出勤類別" id="presenttype" name="presenttype" required>
                                  <option value="" data-type="">選擇班別</option>
                                  <?php foreach ($attd_list as $key => $value) { ?>
                                    <option value="<?= $value['attendanceno'] ?>" data-type="<?= $value['attendancename'] ?>" <?= $employee['presenttype'] == $value['attendanceno'] ? 'selected' : '' ?>><?= $value['attendanceno'] ?>-<?= $value['attendancename'] ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="presentname">　</label>
                                <input type="text" class="form-control" name="presentname" id="presentname" value="<?= $employee['presentname'] ?>" readonly>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="workday">到職日期</label>
                                <div id="datebornday">
                                  <input type="text" data-name="到職日期" class="form-control datepicker-tw" name="workday" id="workday" value="<?= $employee['workday'] ? $employee['workday'] : '80-01-01' ?>">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="expireday">離職日期</label>
                                <div id="datebornday">
                                  <input type="text" data-name="離職日期" class="form-control datepicker-tw" name="expireday" id="expireday" value="<?= $employee['expireday'] ? $employee['expireday'] : '80-01-01' ?>">
                                  <?php if (empty($employee['expireday'])) { ?>
                                    <script>
                                      $(function() {
                                        $(window).on('load', function() {
                                          $('#expireday').val("").datepicker("update");
                                        })
                                      })
                                    </script>
                                  <?php } ?>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="contact">緊急聯絡人</label>
                                <input type="text" data-name="緊急聯絡人" maxlength="10" class="form-control" name="contact" id="contact" value="<?= $employee['contact'] ?>" placeholder="EX：張太太">
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="contactrelation">聯絡人關係</label>
                                <input type="text" data-name="聯絡人關係" maxlength="10" class="form-control" name="contactrelation" id="contactrelation" value="<?= $employee['contactrelation'] ?>" placeholder="妻子">
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="contacttel1">聯絡電話一</label>
                                <input type="text" data-name="聯絡電話一" maxlength="16" class="form-control" name="contacttel1" id="contacttel1" value="<?= $employee['contacttel1'] ?>" placeholder="EX：0912345678">
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="contacttel2">聯絡電話二</label>
                                <input type="text" data-name="聯絡電話二" maxlength="16" class="form-control" name="contacttel2" id="contacttel2" value="<?= $employee['contacttel2'] ?>" placeholder="EX：0912345678">
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="contactadd">聯絡地址</label>
                                <input type="text" data-name="聯絡地址" maxlength="150" class="form-control" name="contactadd" id="contactadd" value="<?= $employee['contactadd'] ?>" placeholder="">
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="pro">專長</label>
                                <input type="text" data-name="專長" maxlength="50" class="form-control" name="pro" id="pro" value="<?= $employee['pro'] ?>" placeholder="">
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="add1">戶籍地址</label>
                                <input type="text" data-name="戶籍地址" maxlength="160" class="form-control" name="add1" id="add1" value="<?= $employee['add1'] ?>" placeholder="">
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="add2">通訊地址</label>
                                <input type="text" data-name="通訊地址" maxlength="160" class="form-control" name="add2" id="add2" value="<?= $employee['add2'] ?>" placeholder="">
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="buildday">建檔日期</label>
                                <input type="text" data-name="建檔日期" maxlength="160" class="form-control" name="buildday" id="buildday" value="<?= $employee['buildday'] ?>" readonly>
                              </div>
                            </div>
                          </div>
                          <div class="d-flex justify-content-end mt-5">
                            <button type="button" class="btn btn-primary mb-2 btn-pill saveBtn" data-type="employee_edit">儲存</button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="salary" role="tabpanel" aria-labelledby="salary-tab">
                      <div class="tab-pane-content mt-5">
                        <form id="form2" onsubmit="return false;">
                          <input type="hidden" name="eid" value="<?= $employee['eid'] ?>">
                          <div class="em_title mb-2">
                            <h2>薪資設定</h2>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label class="d-inline-block" for="">薪資方式</label>
                                <ul class="list-unstyled list-inline">
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">月薪
                                      <input type="radio" name="sandtype" value="1" <?= $employee['sandtype'] == '1' || $employee['sandtype'] == '' ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">日薪
                                      <input type="radio" name="sandtype" value="2" <?= $employee['sandtype'] == '2' ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">時薪
                                      <input type="radio" name="sandtype" value="3" <?= $employee['sandtype'] == '3' ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                </ul>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="standardday">基準天數</label>
                                <input type="number" data-name="基準天數" class="form-control" step="0.0001" name="standardday" id="standardday" placeholder="ex:30" value="<?= $employee['standardday'] ? $employee['standardday'] : '30' ?>" required>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="standardhour">基準時數</label>
                                <input type="number" data-name="基準時數" class="form-control" step="0.1" name="standardhour" id="standardhour" placeholder="ex:8" value="<?= $employee['standardhour'] ? $employee['standardhour'] : '8' ?>" required>
                              </div>
                            </div>
                          </div>

                          <div class="row mb-2">
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="monthmny">月薪金額</label>
                                <input type="number" data-name="月薪金額" class="form-control" step="0.0001" name="monthmny" id="monthmny" value="<?= $employee['monthmny'] ? $employee['monthmny'] : 0 ?>" <?= $employee['sandtype'] == '2' || $employee['sandtype'] == '3' ? 'readonly' : '' ?>>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="daymny">換算日薪</label>
                                <input type="number" data-name="換算日薪" class="form-control" step="0.0001" name="daymny" id="daymny" value="<?= $employee['daymny'] ? $employee['daymny'] : 0 ?>" <?= $employee['sandtype'] == '3' ? 'readonly' : '' ?>>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="hourmny">換算時薪</label>
                                <input type="number" data-name="換算時薪" class="form-control" step="0.0001" name="hourmny" id="hourmny" value="<?= $employee['hourmny'] ? $employee['hourmny'] : 0 ?>">
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="taxmny">扣繳稅額</label>
                                <input type="number" data-name="扣繳稅額" class="form-control" step="0.0001" name="taxmny" id="taxmny" value="<?= $employee['taxmny'] ? $employee['taxmny'] : 0 ?>">
                              </div>
                            </div>
                          </div>
                          <div class="row mb-6">
                            <div class="col-lg-2">
                              <label>上班打卡</label>
                              <label class="switch switch-primary switch-pill form-control-label">
                                <input type="checkbox" class="switch-input form-check-input" name="starttype" value="1" <?= $employee['starttype'] == '1' ? 'checked' : '' ?>>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                              </label>
                            </div>
                            <div class="col-lg-2">
                              <label>休息打卡</label>
                              <label class="switch switch-primary switch-pill form-control-label">
                                <input type="checkbox" class="switch-input form-check-input" name="resttype" value="1" <?= $employee['resttype'] == '1' ? 'checked' : '' ?>>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                              </label>
                            </div>
                          </div>
                          <div class="em_title mb-2">
                            <h2>銀行帳號1</h2>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-2 form-pill">
                              <div class="form-group">
                                <label for="bankno">銀行名稱</label>
                                <select class="form-control" data-name="銀行名稱" id="bankno" name="bankno">
                                  <option value="" data-type="">選擇銀行</option>
                                  <?php foreach ($bank_list as $key => $value) { ?>
                                    <option value="<?= $value['bankno'] ?>" data-type="<?= $value['bankname'] ?>" <?= $employee['bankno'] == $value['bankno'] ? 'selected' : '' ?>><?= $value['bankno'] ?> <?= $value['bankname'] ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="bankname">　</label>
                                <input type="text" class="form-control" name="bankname" id="bankname" value="<?= $employee['bankname'] ?>" readonly>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="huming">戶名</label>
                                <input type="text" data-name="戶名" maxlength="15" class="form-control" name="huming" id="huming" value="<?= $employee['huming'] ?>">
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="bankid">銀行帳號</label>
                                <input type="text" data-name="銀行帳號" maxlength="30" class="form-control" name="bankid" id="bankid" value="<?= $employee['bankid'] ?>">
                              </div>
                            </div>
                          </div>
                          <div class="em_title mb-2">
                            <h2>銀行帳號2</h2>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-2 form-pill">
                              <div class="form-group">
                                <label for="bankno2">銀行名稱</label>
                                <select class="form-control" data-name="銀行名稱" id="bankno2" name="bankno2">
                                  <option value="" data-type="">選擇銀行</option>
                                  <?php foreach ($bank_list as $key => $value) { ?>
                                    <option value="<?= $value['bankno'] ?>" data-type="<?= $value['bankname'] ?>" <?= $employee['bankno2'] == $value['bankno'] ? 'selected' : '' ?>><?= $value['bankno'] ?> <?= $value['bankname'] ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="bankname2">　</label>
                                <input type="text" class="form-control" name="bankname2" id="bankname2" value="<?= $employee['bankname2'] ?>" readonly>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="huming2">戶名</label>
                                <input type="text" data-name="戶名" maxlength="15" class="form-control" name="huming2" id="huming2" value="<?= $employee['huming2'] ?>">
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="bankid2">銀行帳號</label>
                                <input type="text" data-name="銀行帳號" maxlength="30" class="form-control" name="bankid2" id="bankid2" value="<?= $employee['bankid2'] ?>">
                              </div>
                            </div>
                          </div>
                          <div class="d-flex justify-content-end mt-5">
                            <button type="button" class="btn btn-primary mb-2 btn-pill saveBtn" data-no="2" data-type="salary_edit" <?= $employid ? '' : 'disabled' ?>>儲存</button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="overtime" role="tabpanel" aria-labelledby="overtime-tab">
                      <div class="tab-pane-content mt-5">
                        <form id="form3" onsubmit="return false;">
                          <input type="hidden" name="eid" value="<?= $employee['eid'] ?>">
                          <div class="em_title mb-2">
                            <h2>加班費</h2>
                          </div>
                          <div class="row mb-6">
                            <div class="col-lg-2 form-group">
                              <label>是否支付加班</label>
                              <label class="switch switch-primary switch-pill form-control-label">
                                <input type="checkbox" class="switch-input form-check-input" name="overtimetype" value="1" <?= $employee['overtimetype'] == '1' ? 'checked' : '' ?>>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                              </label>
                            </div>
                            <div class="col-lg-5">
                              <div class="form-group">
                                <label class="d-inline-block" for="">計算方式</label>
                                <ul class="list-unstyled list-inline">
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">固定金額
                                      <input type="radio" name="overtimemnytype" value="1" <?= $employee['overtimemnytype'] == '1'  ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">時薪比例
                                      <input type="radio" name="overtimemnytype" value="2" <?= $employee['overtimemnytype'] == '2' ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">全薪計算
                                      <input type="radio" name="overtimemnytype" value="3" <?= $employee['overtimemnytype'] == '3' || $employee['overtimemnytype'] == '' ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-2 form-inline font-weight-bold"><label>一般加班費</label></div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="normalovertimemny">金額</label>
                                <input type="number" data-name="金額" class="form-control mny" name="normalovertimemny" id="normalovertimemny" value="<?= $employee['normalovertimemny'] ? $employee['normalovertimemny'] : '0' ?>" <?= $employee['overtimemnytype'] == '1' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="normalovertimerate">比例%</label>
                                <input type="number" data-name="比例%" class="form-control rate" step="0.01" name="normalovertimerate" id="normalovertimerate" value="<?= $employee['normalovertimerate'] ? $employee['normalovertimerate'] : '1.34' ?>" <?= $employee['overtimemnytype'] == '3' || $employee['overtimemnytype'] == '2' || $employee['overtimemnytype'] == '' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-2 form-inline font-weight-bold"><label>小於(含)2小時</label></div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-2 form-inline font-weight-bold"><label>延長加班費</label></div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="extendovertimemny"></label>
                                <input type="number" data-name="金額" class="form-control mny" name="extendovertimemny" id="extendovertimemny" value="<?= $employee['extendovertimemny'] ? $employee['extendovertimemny'] : '0' ?>" <?= $employee['overtimemnytype'] == '1' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="extendovertimerate"></label>
                                <input type="number" data-name="比例%" class="form-control rate" step="0.01" name="extendovertimerate" id="extendovertimerate" value="<?= $employee['extendovertimerate'] ? $employee['extendovertimerate'] : '1.67' ?>" <?= $employee['overtimemnytype'] == '3' || $employee['overtimemnytype'] == '2' || $employee['overtimemnytype'] == '' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-2 form-inline font-weight-bold"><label>超過2小時</label></div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-2 form-inline font-weight-bold"><label>例假日加班費</label></div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="holidayovertimemny"></label>
                                <input type="number" data-name="金額" class="form-control mny" name="holidayovertimemny" id="holidayovertimemny" value="<?= $employee['holidayovertimemny'] ? $employee['holidayovertimemny'] : '0' ?>" <?= $employee['overtimemnytype'] == '1' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="holidayovertimerate"></label>
                                <input type="number" data-name="比例%" class="form-control rate" step="0.01" name="holidayovertimerate" id="holidayovertimerate" value="<?= $employee['holidayovertimerate'] ? $employee['holidayovertimerate'] : '2.00' ?>" <?= $employee['overtimemnytype'] == '3' || $employee['overtimemnytype'] == '2' || $employee['overtimemnytype'] == '' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-2 form-inline font-weight-bold"><label>每小時</label></div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-2 form-inline font-weight-bold"><label>國定日加班費</label></div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="publicholidayovertimemny"></label>
                                <input type="number" data-name="金額" class="form-control mny" name="publicholidayovertimemny" id="publicholidayovertimemny" value="<?= $employee['publicholidayovertimemny'] ? $employee['publicholidayovertimemny'] : '0' ?>" <?= $employee['overtimemnytype'] == '1' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="publicholidayovertimerate"></label>
                                <input type="number" data-name="比例%" class="form-control rate" step="0.01" name="publicholidayovertimerate" id="publicholidayovertimerate" value="<?= $employee['publicholidayovertimerate'] ? $employee['publicholidayovertimerate'] : '2.00' ?>" <?= $employee['overtimemnytype'] == '3' || $employee['overtimemnytype'] == '2' || $employee['overtimemnytype'] == '' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-2 form-inline font-weight-bold"><label>每小時</label></div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-2 form-inline font-weight-bold"><label>休息日加班費</label></div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="restovertimemny1"></label>
                                <input type="number" data-name="金額" class="form-control mny" name="restovertimemny1" id="restovertimemny1" value="<?= $employee['restovertimemny1'] ? $employee['restovertimemny1'] : '0' ?>" <?= $employee['overtimemnytype'] == '1' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="resthourrate1"></label>
                                <input type="number" data-name="比例%" class="form-control rate" step="0.01" name="resthourrate1" id="resthourrate1" value="<?= $employee['resthourrate1'] ? $employee['resthourrate1'] : '1.34' ?>" <?= $employee['overtimemnytype'] == '3' || $employee['overtimemnytype'] == '2' || $employee['overtimemnytype'] == '' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-2 form-inline font-weight-bold"><label>1~2小時</label></div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-2 form-inline font-weight-bold"></div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="restovertimemny2"></label>
                                <input type="number" data-name="金額" class="form-control mny" name="restovertimemny2" id="restovertimemny2" value="<?= $employee['restovertimemny2'] ? $employee['restovertimemny2'] : '0' ?>" <?= $employee['overtimemnytype'] == '1' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="resthourrate2"></label>
                                <input type="number" data-name="比例%" class="form-control rate" step="0.01" name="resthourrate2" id="resthourrate2" value="<?= $employee['resthourrate2'] ? $employee['resthourrate2'] : '1.67' ?>" <?= $employee['overtimemnytype'] == '3' || $employee['overtimemnytype'] == '2' || $employee['overtimemnytype'] == '' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-2 form-inline font-weight-bold"><label>3~8小時</label></div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-2 form-inline font-weight-bold"></div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="restovertimemny3"></label>
                                <input type="number" data-name="金額" class="form-control mny" name="restovertimemny3" id="restovertimemny3" value="<?= $employee['restovertimemny3'] ? $employee['restovertimemny3'] : '0' ?>" <?= $employee['overtimemnytype'] == '1' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="resthourrate3"></label>
                                <input type="number" data-name="比例%" class="form-control rate" step="0.01" name="resthourrate3" id="resthourrate3" value="<?= $employee['resthourrate3'] ? $employee['resthourrate3'] : '2.67' ?>" <?= $employee['overtimemnytype'] == '3' || $employee['overtimemnytype'] == '2' || $employee['overtimemnytype'] == '' ? '' : 'readonly' ?>>
                              </div>
                            </div>
                            <div class="col-lg-2 form-inline font-weight-bold"><label>8小時以上</label></div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-5">
                              <div class="form-group">
                                <label class="d-inline-block" for="">加班方式</label>
                                <ul class="list-unstyled list-inline">
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">一般情況
                                      <input type="radio" name="otway" value="1" <?= $employee['otway'] == '1' || $employee['otway'] == '' ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">加班單並行
                                      <input type="radio" name="otway" value="2" <?= $employee['otway'] == '2' ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="row mb-6">
                            <div class="col-lg-2 form-group otdiv" <?= $employee['otway'] == '2' ? 'style="opacity:0.5";' : '' ?>>
                              <label class="otlabel">不計算加班單</label>
                              <label class="switch switch-primary switch-pill form-control-label">
                                <input type="checkbox" class="switch-input form-check-input" name="jiabanbudadan" value="1" <?= $employee['jiabanbudadan'] == '1' ? 'checked' : '' ?> <?= $employee['otway'] == '2' ? 'disabled' : '' ?>>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                              </label>
                            </div>
                            <div class="col-lg-5">
                              <div class="form-group">
                                <label class="d-inline-block" for="">　</label>
                                <ul class="list-unstyled list-inline">
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">依據員工出勤曆的加班時段
                                      <input type="radio" name="overtime" value="2" <?= $employee['overtime'] == '2' || $employee['overtime'] == '' ? 'checked' : '' ?> <?= $employee['jiabanbudadan'] == '1' || $employee['otway'] == '2' ? '' : 'disabled' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">超過
                                      <input type="radio" name="overtime" value="1" <?= $employee['overtime'] == '1' ? 'checked' : '' ?> <?= $employee['jiabanbudadan'] == '1' || $employee['otway'] == '2' ? '' : 'disabled' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                </ul>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="jiabanbudashi"></label>
                                <input type="number" data-name="超過分鐘加班" class="form-control" name="jiabanbudashi" id="jiabanbudashi" value="<?= $employee['jiabanbudashi'] ? $employee['jiabanbudashi'] : '0' ?>" <?= $employee['overtime'] == '1' ? '' : 'readonly' ?> required>
                              </div>
                            </div>
                            <div class="col-lg-2 form-inline font-weight-bold"><label>分鐘算加班</label></div>
                          </div>
                          <div class="row mb-6">
                            <div class="col-lg-2 form-group">
                              <label>是否加班給誤餐費</label>
                              <label class="switch switch-primary switch-pill form-control-label">
                                <input type="checkbox" class="switch-input form-check-input" name="mealflag" value="1" <?= $employee['mealflag'] == '1' ? 'checked' : '' ?>>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                              </label>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="mealmny">金額</label>
                                <input type="number" data-name="誤餐費金額" class="form-control" name="mealmny" id="mealmny" value="<?= $employee['mealmny'] ? $employee['mealmny'] : '0' ?>" required>
                              </div>
                            </div>
                          </div>
                          <div class="d-flex justify-content-end mt-5">
                            <button type="button" class="btn btn-primary mb-2 btn-pill saveBtn" data-no="3" data-type="overtime_edit" <?= $employid ? '' : 'disabled' ?>>儲存</button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="insurance" role="tabpanel" aria-labelledby="insurance-tab">
                      <div class="tab-pane-content mt-5">
                        <form id="form4" onsubmit="return false;">
                          <input type="hidden" name="eid" value="<?= $employee['eid'] ?>">
                          <div class="em_title mb-2">
                            <h2>勞健保</h2>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-5">
                              <div class="form-group">
                                <label class="d-inline-block" for="">投保者身分</label>
                                <ul class="list-unstyled list-inline">
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">本籍員工
                                      <input type="radio" name="insuredperson" value="1" <?= $employee['insuredperson'] == '1' || empty($employee['insuredperson'])  ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                  <li class="d-inline-block mr-3">
                                    <label class="control control-radio">外籍員工
                                      <input type="radio" name="insuredperson" value="2" <?= $employee['insuredperson'] == '2' ? 'checked' : '' ?> />
                                      <div class="control-indicator"></div>
                                    </label>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">

                            <div class="col-lg-2 form-pill">
                              <div class="form-group">
                                <label for="seclabno">勞保投保金額</label>
                                <select class="form-control" data-name="勞保投保金額" id="seclabno" name="seclabno">
                                  <option value="" data-type="">選擇等級</option>
                                  <?php foreach ($seclab1_list as $key => $value) { ?>
                                    <option value="<?= $value['seclabNo'] ?>" data-type="<?= $value['seclabMny'] ?>" data-self1="<?= $value['seclablMny'] ?>" data-self2="<?= $value['ForeignMny'] ?>" data-afford="<?= $value['employerSeclablMny'] ?>" <?= $employee['seclabno'] == $value['seclabNo'] ? 'selected' : '' ?>><?= 'Lv' . $value['seclabNo'] . '→' . intval($value['seclabMny']) ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="lmoney">薪資</label>
                                <input type="number" class="form-control" name="lmoney" id="lmoney" value="<?= $employee['lmoney'] ?>" readonly>
                              </div>
                            </div>
                            <div class="col-lg-2"></div>

                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="selflmoney">自付金額</label>
                                <input type="number" class="form-control" name="selflmoney" id="selflmoney" value="<?= $employee['selflmoney'] ?>">
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">

                            <div class="col-lg-2 form-pill">
                              <div class="form-group">
                                <label for="seclabtno">勞退自提金額</label>
                                <select class="form-control" data-name="勞保投保金額" id="seclabtno" name="seclabtno">
                                  <option value="" data-type="">選擇等級</option>
                                  <?php foreach ($seclab1_list as $key => $value) { ?>
                                    <option value="<?= $value['seclabNo'] ?>" data-type="<?= $value['seclabMny'] ?>" data-self1="<?= $value['seclablMny'] ?>" data-self2="<?= $value['ForeignMny'] ?>" <?= $employee['seclabtno'] == $value['seclabNo'] ? 'selected' : '' ?>><?= 'Lv' . $value['seclabNo'] . '→' . intval($value['seclabMny']) ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="tmoney">薪資</label>
                                <input type="number" class="form-control" name="tmoney" id="tmoney" value="<?= $employee['tmoney'] ?>" readonly>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <div class="form-group">
                                <label for="selftrate">比例(%)</label>
                                <input type="number" class="form-control" name="selftrate" id="selftrate" value="<?= $employee['selftrate'] ?>">
                              </div>
                            </div>

                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="selftmoney">自付金額</label>
                                <input type="number" class="form-control" name="selftmoney" id="selftmoney" value="<?= $employee['selftmoney'] ?>">
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">

                            <div class="col-lg-2 form-pill">
                              <div class="form-group">
                                <label for="purchaserno">健保投保金額</label>
                                <select class="form-control" data-name="健保投保金額" id="purchaserno" name="purchaserno">
                                  <option value="" data-type="">選擇等級</option>
                                  <?php foreach ($purchaser1_list as $key => $value) { ?>
                                    <option value="<?= $value['purchaserno'] ?>" data-type="<?= $value['purchasermny'] ?>" data-self="<?= $value['purchaserhmny'] ?>" data-afford="<?= $value['employerPurchaserhmny'] ?>" <?= $employee['purchaserno'] == $value['purchaserno'] ? 'selected' : '' ?>><?= 'Lv' . $value['purchaserno'] . '→' . intval($value['purchasermny']) ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="hmoney">薪資</label>
                                <input type="number" class="form-control" name="hmoney" id="hmoney" value="<?= $employee['hmoney'] ?>" readonly>
                              </div>
                            </div>
                            <div class="col-lg-2">

                            </div>

                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="selfhmoney">自付金額</label>
                                <input type="number" class="form-control" name="selfhmoney" id="selfhmoney" value="<?= $employee['selfhmoney'] ?>">
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-4">
                              <div class="form-group">
                                <div class="form-group">
                                  <label for="insuredsum">健保眷屬人數</label>
                                  <input type="number" class="form-control" name="insuredsum" id="insuredsum" value="<?= $employee['insuredsum'] ?>">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="insuredmny">自付金額</label>
                                <input type="number" class="form-control" name="insuredmny" id="insuredmny" value="<?= $employee['insuredmny'] ?>">
                              </div>
                            </div>
                          </div>
                          <div class="em_title mb-2">
                            <h2>退休金提撥基準</h2>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-4">
                              <div class="form-group">
                                <div class="form-group">
                                  <label for="tuixiuselfmny">雇主負擔比例</label>
                                  <input type="number" class="form-control" name="tuixiuselfmny" id="tuixiuselfmny" value="<?= $employee['tuixiuselfmny'] ?>">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2 form-inline font-weight-bold"><label>X 6%</label></div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <div class="form-group">
                                  <label for="tuixiugerenmny">雇主負擔金額</label>
                                  <input type="number" class="form-control" name="tuixiugerenmny" id="tuixiugerenmny" value="<?= $employee['tuixiugerenmny'] ?>">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="em_title mb-2">
                            <h2>雇主負擔勞健保</h2>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-4">
                              <div class="form-group">
                                <div class="form-group">
                                  <label for="employerlmny">雇主負擔勞保</label>
                                  <input type="number" class="form-control" name="employerlmny" id="employerlmny" value="<?= $employee['employerlmny'] ?>">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <div class="form-group">
                                  <label for="employerhmny">雇主負擔健保</label>
                                  <input type="number" class="form-control" name="employerhmny" id="employerhmny" value="<?= $employee['employerhmny'] ?>">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="d-flex justify-content-end mt-5">
                            <button type="button" class="btn btn-primary mb-2 btn-pill saveBtn" data-no="4" data-type="insurance_edit" <?= $employid ? '' : 'disabled' ?>>儲存</button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="ededuction" role="tabpanel" aria-labelledby="ededuction-tab">
                      <div class="tab-pane-content mt-5">
                        <form id="form5" onsubmit="return false;">
                          <input type="hidden" name="eid" value="<?= $employee['eid'] ?>">
                          <div>
                            <button type="button" class="mb-1 btn btn-outline-primary" id="addBtn">
                              <i class=" mdi mdi-plus mr-1"></i> 新增</button>
                            <table class="table table-hover">
                              <thead>
                                <tr>
                                  <th scope="col">加扣款編號</th>
                                  <th scope="col">加扣款名稱</th>
                                  <th scope="col">加扣款金額</th>
                                  <th scope="col">加入全薪金額</th>
                                  <th scope="col">套用公式</th>
                                  <th scope="col">選項</th>
                                </tr>
                              </thead>
                              <tbody class="datalist otherclass">
                                <?php foreach ($ed_list as $edk => $edv) { ?>
                                  <tr>
                                    <th scope="col">
                                      <select name="deductionno[]" class="form-control" data-name="加扣款編號" required>
                                        <option value="" data-name="" data-mny="" <?= isset($edv['deductionno']) ? '' : 'selected' ?> disabled hidden>選擇編號</option>
                                        <?php foreach ($deduction_list as $key => $value) { ?>
                                          <option value="<?= $value['deductionno'] ?>" data-name="<?= $value['deductionname'] ?>" data-mny="<?= $value['dedmny'] ?>" <?= $edv['deductionno'] == $value['deductionno'] ? 'selected' : '' ?>><?= $value['deductionno'] ?></option>
                                        <?php } ?>
                                      </select>
                                    </th>
                                    <th scope="col"><input name="deductionname[]" type="text" class="form-control" value="<?= $edv['deductionname'] ?>" readonly></th>
                                    <th scope="col"><input name="deductionmny[]" type="text" class="form-control" value="<?= $edv['deductionmny'] ?>" readonly></th>
                                    <th scope="col">
                                      <label class="switch switch-primary switch-pill form-control-label">
                                        <input type="checkbox" class="switch-input form-check-input" name="dotype[<?= $edk ?>]" value="1" <?= $edv['dotype'] == '1' ? 'checked' : '' ?>>
                                        <span class="switch-label"></span>
                                        <span class="switch-handle"></span>
                                      </label>
                                    </th>
                                    <th scope="col">
                                      <select name="jstype[]" class="form-control" data-name="套用公式" required>
                                        <option value="" <?= isset($edv['jstype']) ? '' : 'selected' ?> disabled hidden>選擇公式</option>
                                        <option value="0" <?= $edv['jstype'] == '0' ? 'selected' : '' ?>>固定金額</option>
                                        <option value="1" <?= $edv['jstype'] == '1' ? 'selected' : '' ?>>金額*實際出勤天數</option>
                                        <option value="2" <?= $edv['jstype'] == '2' ? 'selected' : '' ?>>日新*實際公休天數</option>
                                        <option value="3" <?= $edv['jstype'] == '3' ? 'selected' : '' ?>>固定金額*實際公休天數</option>
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
                          <div class="d-flex justify-content-end mt-5">
                            <button type="button" class="btn btn-primary mb-2 btn-pill saveBtn" data-no="5" data-type="ededuction_edit" <?= $employid ? '' : 'disabled' ?>>儲存</button>
                          </div>
                        </form>
                        <table class="invisible_table">
                          <tbody>
                            <tr>
                              <th scope="col">
                                <select name="deductionno[]" class="form-control" data-name="加扣款編號" required>
                                  <option value="" data-name="" data-mny="" selected disabled hidden>選擇編號</option>
                                  <?php foreach ($deduction_list as $key => $value) { ?>
                                    <option value="<?= $value['deductionno'] ?>" data-name="<?= $value['deductionname'] ?>" data-mny="<?= $value['dedmny'] ?>"><?= $value['deductionno'] ?></option>
                                  <?php } ?>
                                </select>
                              </th>
                              <th scope="col"><input name="deductionname[]" type="text" class="form-control" readonly></th>
                              <th scope="col"><input name="deductionmny[]" type="text" class="form-control" readonly></th>
                              <th scope="col">
                                <label class="switch switch-primary switch-pill form-control-label">
                                  <input type="checkbox" class="switch-input form-check-input" name="dotype[]" value="1">
                                  <span class="switch-label"></span>
                                  <span class="switch-handle"></span>
                                </label>
                              </th>
                              <th scope="col">
                                <select name="jstype[]" class="form-control" data-name="套用公式" required>
                                  <option value="" selected disabled hidden>選擇公式</option>
                                  <option value="0">固定金額</option>
                                  <option value="1">金額*實際出勤天數</option>
                                  <option value="2">日新*實際公休天數</option>
                                  <option value="3">固定金額*實際公休天數</option>
                                </select>
                              </th>
                              <th scope="col">
                                <a href="javascript:void(0)" class="data_del"><span class="mdi mdi-delete mdi-18px"></span></a>
                              </th>
                            </tr>
                          </tbody>
                        </table>
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