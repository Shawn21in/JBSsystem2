<?php if (!function_exists('Chk_Login')) header('Location: ../../index.php'); ?>

<div class="abgne_tab">

	<ul class="tabs">
		<li><a href="javascript:void(0)" for-id="tab1">基本資料</a></li>
	</ul>

	<div class="tab_container">

		<div id="tab1" class="tab_content">

			<form id="form_edit_save" class="form-horizontal">

				<?php foreach ($_html_ as $key => $val) {

					$table_sn = 'tabsn' . $key;
				?>
					<div class="Table_border <?= $table_sn ?>">

						<input type="hidden" id="<?= $Main_Key ?>" name="<?= $Main_Key ?>[]" value="<?= $val[$Main_Key] ?>">

						<?php
						//-----------------------------------------//
						$Arr_Name = 'Product_ID';

						echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', '', $Arr_Name, $val, '', 0);
						//-----------------------------------------//
						$Arr_Name = 'Product_Name';

						echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '請輸入' . $table_info[$Arr_Name]['Comment'], 1);
						//-----------------------------------------//
						$Arr_Name = 'ProductC_ID';

						echo $_TF->html_select('產品分類', '', $Arr_Name, $val, '請輸入'.$table_info[$Arr_Name]['Comment'], 1, $Class_Arr[$Main_Key3], '');
						//-----------------------------------------//

						$Arr_Name = 'Product_Intro';

						echo $_TF->html_textarea($table_info[$Arr_Name]['Comment'], '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '');
						//-----------------------------------------//
						$Arr_Name = 'Product_Mcp';

						echo $_TF->html_uploadimg($table_info[$Arr_Name]['Comment'], '( 建議尺寸 600 * 450 )', $Arr_Name, $val, '', 0, false, $Radio_Arr);
						//-----------------------------------------//
						$Arr_Name = 'Product_Img1';

						echo $_TF->html_uploadimg($table_info[$Arr_Name]['Comment'], '( 建議尺寸 600 * 450 )', $Arr_Name, $val, '', 0, false, $Radio_Arr);
						//-----------------------------------------//
						$Arr_Name = 'Product_Img2';

						echo $_TF->html_uploadimg($table_info[$Arr_Name]['Comment'], '( 建議尺寸 600 * 450 )', $Arr_Name, $val, '', 0, false, $Radio_Arr);
						//-----------------------------------------//
						$Arr_Name = 'Product_Img3';

						echo $_TF->html_uploadimg($table_info[$Arr_Name]['Comment'], '( 建議尺寸 600 * 450 )', $Arr_Name, $val, '', 0, false, $Radio_Arr);
						//-----------------------------------------//

						// $Arr_Name = 'Product_EX';

						// echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);

						//-----------------------------------------//
						$Arr_Name = 'Product_Content';


						echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);

						//-----------------------------------------//
						echo '<hr style=" padding-left:20px;border: 0; height: 1px;background: #ccc;">';

						echo $_TF->html_unit($table_info, $val);

						echo "<div class='unit-form'> </div>";
						//------------------------------------------//

						//---------------------------------------------**


						$Arr_Name = 'Product_Sort';

						echo $_TF->html_number($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1, 0, 99999);
						//-----------------------------------------//

						$Arr_Name = 'Product_OpenNew';

						echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1);

						//-----------------------------------------//

						$Arr_Name = 'Product_OpenHot';

						echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1);

						//-----------------------------------------//

						$Arr_Name = 'Product_Open';

						echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1);
						//-----------------------------------------//
						
						$Arr_Name = 'Product_Sdate';

						echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', '', $Arr_Name, $val, '', 0);
						?>
					</div>
				<?php } ?>

				<div class="clear_both form-actions">
					<button id="saveb" class="btn btn-info" type="button" onclick="form_edit_save()">
						<i class="ace-icon fa fa-check bigger-110"></i>儲存
					</button>&nbsp;&nbsp;&nbsp;

					<button id="rsetb" class="btn btn" type="reset">
						<i class="ace-icon fa fa-check bigger-110"></i>重設
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('.imgajax').colorbox({
		width: "70%",
		height: "100%",
		rel: 'imgajax'
	});
</script>
<script>
	$(document).ready(function(e) {

		$('.unit-additem').click(function() {

			var content = '';
			// content += '<div class="form-group "><label class="col-sm-2 control-label" style="font-size:16px; text-decoration:underline;">新增規格</label></div>';

			//2
			content += '<div class="form-group " style="display:none">';
			content += '<label class="col-sm-2 control-label">規格UID</label>';
			content += '	<div class="col-sm-9">';

			content += '	<input type="text" id="" name="DICgOeMZNAqMZpf[]" class="col-xs-12 col-sm-5" value=""  maxlength="60" placeholder="" autocomplete="off" ">';

			content += '	</div>';
			content += '</div>';

			//---------------------------------------------------------------------------------------------------
			//2
			content += '<div class="form-group " style="display:none">';
			content += '<label class="col-sm-2 control-label">進銷存產品編號</label>';
			content += '	<div class="col-sm-9">';

			content += '	<input type="text" id="" name="DICgOeMZNAqMZzB[]" class="col-xs-12 col-sm-5" value="" msg="" maxlength="60" placeholder="" autocomplete="off" ">';
			
			content += '	</div>';
			content += '</div>';

			//---------------------------------------------------------------------------------------------------
			//1
			content += '<div class="form-group ">';
			content += '<label class="col-sm-2 control-label">規格</label>';
			content += '	<div class="col-sm-9">';

			content += '	<input type="text" id="" name="DICgOeMZNAqM[]" class="col-xs-12 col-sm-5" value="" msg="請輸入規格" maxlength="60" placeholder="" autocomplete="off" ">';

			content += '	</div>';
			content += '</div>';

			//---------------------------------------------------------------------------------------------------
			//2
			content += '<div class="form-group ">';
			content += '<label class="col-sm-2 control-label">原價</label>';
			content += '	<div class="col-sm-9">';

			content += '	<input type="text" id="" name="DICgOeMZDIqei[]" class="col-xs-12 col-sm-5" value="0" msg="" maxlength="15" input-type="number" input-name="調整後核定經費" input-min="" input-max="" placeholder="" autocomplete="off">';

			content += '	</div>';
			content += '</div>';
			
			//---------------------------------------------------------------------------------------------------
			//3
			content += '<div class="form-group ">';
			content += '<label class="col-sm-2 control-label">售價</label>';
			content += '	<div class="col-sm-9">';

			content += '	<input type="text" id="" name="DICgOeMZDIqei0[]" class="col-xs-12 col-sm-5" value="0" msg="" maxlength="15" input-type="number" input-name="調整後核定經費" input-min="" input-max="" placeholder="" autocomplete="off">';

			content += '	</div>';
			content += '</div>';
			content += '<hr style=" padding-left:20px;border: 0; height: 1px;background: #ccc;">';



			$('.unit-form').after(content);

		});

		$('.unit-delitem').click(function() {
			$(this).parent().parent().remove()
		});
	});
</script>