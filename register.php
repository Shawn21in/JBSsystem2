<?php
require_once(__DIR__ . '/include/web.config.php');

//不接受任何get資料
ob_start();
if (!empty($_GET)) {
	header("Location: register.php");
}
ob_end_flush();
//版本
$db = new MySQL();

$db->Order_By = ' ORDER BY Version_Sort DESC, Version_ID DESC';
$db->Where = " WHERE Version_Open = '1'";

$db->query_sql($version_db, '*');

while ($row = $db->query_fetch()) {

	$_html_version[$row['Version_ID']] = $row;
}

//付款方式
$db = new MySQL();

$db->Order_By = ' ORDER BY Delivery_Sort DESC, Delivery_ID DESC';
$db->Where = " WHERE Delivery_Open = '1'";

$db->query_sql($delivery_db, '*');

while ($row = $db->query_fetch()) {

	$_html_pay[$row['Delivery_ID']] = $row;
}


$_Title 	= "會員登入";
?>
<!DOCTYPE html>
<html>

<head>
	<?php require('head.php') ?>
	<link rel="stylesheet" type="text/css" href="stylesheets/login.css" />
	<link rel="stylesheet" type="text/css" href="stylesheets/color_box.css" />
</head>

<body data-target=".navbar-spy" data-spy="scroll" data-offset="150">


	<?php require('colorbox_form.php') ?>
	<?php require('header.php') ?>



	<article class="center">
		<form id="forms" onSubmit="return false;">
			<section class="center_section register_sec">
				<h2><img src="images/member_.png">會員註冊<br><br>
					<span style="font-size: 15px; font-weight: bold; letter-spacing: 0.1em;">經銷商 : <?= Dealer_Name ?><br></span>
				</h2>

				<div class="register__form">
					<input type="text" name="email" input-type="email" msg="請填寫電子信箱" placeholder="設定電子郵件帳號">
					<p class="remind">需發送帳號開通設定資訊至信箱，請輸入正確的電子郵件</p>
					<input type="text" name="acc" msg="請填寫帳號" placeholder="設定帳號">
					<p class="remind">請輸入6~10字的英文及數字</p>
					<div class="line"></div>
					<input type="password" name="pwd" msg="請填寫網頁管理密碼" input-type="repassword" input-re-id="repwd" placeholder="設定網頁管理密碼">
					<input type="password" name="repwd" id="repwd" msg="請填寫網頁管理密碼" placeholder="請再輸入一次網頁管理密碼">

					<input type="password" name="mobile_pwd" msg="請填寫APP連線密碼" input-type="repassword" input-re-id="mobile_repwd" placeholder="設定APP連線密碼">
					<input type="password" name="mobile_repwd" id="mobile_repwd" msg="請填寫APP連線密碼" placeholder="請再輸入一次APP連線密碼">
					<div class="line"></div>
					<input type="text" name="cname" msg="請填寫公司名稱" placeholder="公司名稱">
					<input type="text" name="uniform" msg="請填寫公司統編" placeholder="公司統編">
					<input type="text" name="ctel" msg="請填寫公司電話" input-type="tel" input-min="8" maxlength="15" placeholder="公司電話">
					<input type="text" name="caddr" msg="請填寫公司地址" placeholder="公司地址">
					<input type="text" name="name" msg="請填寫申請人姓名" placeholder="申請人姓名">
					<input type="text" name="mobile" msg="請填寫申請人電話" input-type="tel" input-min="8" maxlength="10" placeholder="申請人電話">
				</div>
			</section>

			<section class="center_section program_sec">

				<div class="register__form receipt">
					<h2>發票資訊<br><br><br></h2>
					<div class="program__form__option">
						<input type="radio" class="inv_rad" name="Invoice_radio" value="二聯" checked><label>二聯</label>
					</div>
					<div class="program__form__option">
						<input type="radio" class="inv_rad" name="Invoice_radio" value="三聯"><label>三聯</label>
					</div>
					<div>
						<input type="text" id="Invoice_title" name="Invoice_title" placeholder="請填入發票抬頭" style="display:none">
						<input type="text" name="Invoice_addr" placeholder="請填入發票寄送地址">
					</div>
				</div>
			</section>


			<section class="center_section program_sec">

				<div class="register__form">
					<div class="program__form">
						<p>選擇欲購買版本</p>
						<?php foreach ($_html_version as $key => $val) { ?>
							<div class="program__form__option disblock">
								<input type="radio" name="program_radio" value="<?= $key ?>"><label><?= $val['Version_Title'] ?><span>＄<?= $val['Version_Price'] ?>／<?= $val['Version_Day'] . '天' ?><!--<?= $val['Version_Day'] == '99999' ? $val['Version_Day'] . '天' : $val['Version_Day'] . '天' ?>--></span></label>
							</div>
						<?php } ?>
					</div>


					<div class="program__form">
						<p>選擇付款方式</p>
						<?php foreach ($_html_pay as $key => $val) { ?>
							<div class="program__form__option">
								<input type="radio" name="pay_radio" value="<?= $key ?>"><label><?= $val['Delivery_Name'] ?></label>
							</div>
						<?php } ?>
					</div>
				</div>
			</section>

			<div class="check__form">
				<input type="hidden" name="formsigncode" value="<?= $_SESSION['website']['formsigncode'] ?>">
				<input type="checkbox" id="check_read" name="check_read"><label>
					<p>請閱讀 &nbsp; <a href="庫點子文創資訊產業有限公司攻頂計畫雲端解決方案買賣契約附約_110-08-20.pdf" target="_blank">附約</a>&nbsp; 內容後勾選同意</p>
				</label><br>
				<input type="checkbox" id="check_agree" name="check_agree"><label>
					<p class="consent_btn">確認註冊，即視為您已閱讀並同意本同意書</p>
				</label>
				<button class="submit_btn fsubmit" data-type="sign_save">確認註冊</button>
			</div>

		</form>
	</article>


	<?php require('footer.php') ?>
</body>

</html>
<script type="text/javascript">
	$(document).ready(function(e) {

		$('input[name="acc"]').change(function() {

			var acc = $(this).val();
			var Form_Data = 'acc=' + acc + '&_type=acc_check';

			$.post('web_post.php', Form_Data, function(data) {

				if (data.html_msg == 'y') {

					$('input[name="acc"]').val('');
					alert('此帳號已有人使用');
				}
			}, 'json');
		});

		$('input[name="email"]').change(function() {

			var email = $(this).val();
			var Form_Data = 'email=' + email + '&_type=email_check';

			$.post('web_post.php', Form_Data, function(data) {

				if (data.html_msg == 'y') {

					$(this).val('');
					alert('此信箱已有人使用');
				}
			}, 'json');
		});

		$(".inv_rad").change(function() {

			var inv = $('input[name="Invoice_radio"]:checked').val();
			if (inv == '三聯') {

				$('#Invoice_title').show();
			} else {
				$('#Invoice_title').hide();

			}

		});


	});
</script>