<?php

class TableFun
{

	var $Msg 	= ''; //訊息
	var $Href	= ''; //前往連結
	var $Eval	= ''; //執行的JS

	function TableChange($Type, $Data, $Path, $Update = true)
	{ //切換資料

		global $Menu_Data;

		$db = new MySQL();

		$Table 	= $Data['Table'];
		$Key 	= $Data['Key'];
		$ID 	= $Data['ID'];
		$Field 	= $Data['Field'];

		if (!Menu_Use($Menu_Data, 'edit')) {

			$this->Msg = '無權限編輯';
		} else if (($Type != 'norepeat' && empty($ID)) || empty($Table) || empty($Key) || empty($Field)) {

			$this->Msg = '沒設定要更新的重要資料';
		}

		if (empty($this->Msg)) {

			$table_info = $db->get_table_info($Table); //取出資料表欄位的詳細的訊息

			if (empty($table_info[$Field])) {

				$this->Msg = '操作錯誤一';
			}
		}

		if (empty($Type) && empty($this->Msg)) {

			$this->Msg = '沒設定要記錄的種類';
		}

		if (empty($this->Msg)) {

			if ($Type == 'checkbox' || $Type == 'checkboxs') {

				$data  = $Data['Data'] == 'true' ? 1 : 0;
			} else if ($Type == 'delimg') {

				$data  = '';
			} else {

				$data  = $Data['Data'];
			}

			if ($Type == 'norepeat') {

				$db->Where = " WHERE BINARY " . $Field . " = '" . $db->val_check($data) . "'";
				$db->query_sql($Table, $Field);

				if ($db->query_rows() != 0) {

					$this->Msg = '已有 ( ' . $data . ' ) 資料';
				}
			} else {

				$db->Where = " WHERE BINARY " . $Key . " = '" . $db->val_check($ID) . "'";
				$db->query_sql($Table, $Key . ', ' . $Field);

				$row = $db->query_fetch();
				if (empty($row)) {

					$this->Msg = '無此資料';
				} else if ($Update) {

					if ($Type == 'delimg' || $Type == 'delimgs' || $Type == 'delfile') {

						$File_Name = $row[$Field];


						Delete_File($Path, $File_Name);

						// $File = ICONV_CODE('UTF8_TO_BIG5', $Path . $File_Name);
						$File =  $Path . $File_Name;

						// if (is_file($File)) {

						// 	$this->Msg = '圖片刪除失敗';
						// }
					}

					if (empty($this->Msg)) {

						ob_start();

						if ($Type == 'delimgs') {

							$db->query_delete($Table);

							if ($db->Error) {

								$this->Msg = '刪除失敗';
							}
						} else {

							$db_data[$Field] = $data;
							$db->query_data($Table, $db_data, 'UPDATE');

							if ($db->Error) {

								$this->Msg = '更新失敗';
							} else if ($Type == 'make_cover_photo') {

								$this->Msg = '封面圖設定完成';
							}
						}

						ob_end_clean();
					}
				}
			}
		}
	}

	function TableDownLoad($Data)
	{

		global $Menu_Data, $PathFile, $PathFileWeb;

		$db = new MySQL();

		$Table 	= $Data['Table'];
		$Key 	= $Data['Key'];
		$ID 	= $Data['ID'];
		$Field 	= $Data['Field'];

		ob_start();

		if (empty($ID) || empty($Field)) {

			$this->Msg = '沒設定主鍵或欄位';
		}

		if (empty($this->Msg)) {

			$table_info = $db->get_table_info($Table); //取出資料表欄位的詳細的訊息

			if (empty($table_info[$Field])) {

				$this->Msg = '操作錯誤一';
			}
		}

		if (empty($this->Msg)) {

			$db->Where = " WHERE BINARY " . $Key . " = '" . $db->val_check($ID) . "'";
			$db->query_sql($Table, $Field);
			$row = $db->query_fetch();

			if (empty($row)) {

				$this->Msg = '無此資料';
			} else {

				$File_Name = $row[$Field];

				$File = ICONV_CODE('UTF8_TO_BIG5', $PathFile . $File_Name);

				if (!is_file($File)) {

					$this->Msg = '檔案不存在';
				} else {

					$session = md5($Table . time() . $ID . session_id());

					$_SESSION['system']['downloadcode'] = $session;

					$db_data = array(
						'DL_Session' => $session,
						'DL_DownLoadInfo' => $Table . '-' . $Key . '-' . $ID . '-' . $Field,
						'DL_DownLoadPath' => $PathFile,
						'DL_DownLoadUrl' => $PathFileWeb
					);

					$db->query_data(Download_DB, $db_data, 'INSERT');

					if ($db->Error) {

						$this->Msg  = '請重新操作';
						$this->Eval = 'Reload()';
					} else {

						$this->Href	= DOWN_EXEC . '?code=' . $session;
					}
				}
			}
		}

		ob_end_clean();
	}

	function TableUpload($ID, $Type, $Field, $Path, $Save_Name, $Cover = true, $PicSize = array())
	{

		global $Main_Table, $Main_Table2, $Main_Key, $Main_TablePre2;

		$Upload 	= new Upload();

		$File_Data  = array();
		foreach ($_FILES[$Field] as $Fkey2 => $Fval2) {

			foreach ($Fval2 as $Fkey3 => $Fval3) {

				$File_Data[$Fkey3][$Fkey2] = $Fval3;
			}
		}

		CHK_PATH($Path); //判斷位置是否存在

		$time = time();
		foreach ($File_Data as $Fkey2 => $Fval2) {

			$db = new MySQL();

			if ($Type == 'uploadimg') {

				$Upload_Name = $Upload->Upload_Img($Fval2, $Path, $Save_Name, '', '', $Cover);
			} else if ($Type == 'uploadmultiple') {

				$Save_Name 	 = $ID . '_' . ($time + $Fkey2);

				$Upload_Name = $Upload->Upload_Img($Fval2, $Path, $Save_Name, '', '', $Cover);
			} else if ($Type == 'uploadfile') {

				$Upload_Name = $Upload->Upload_File($Fval2, $Path, $Save_Name, $Cover);
			}

			if (empty($Upload->Error) && !empty($Upload_Name)) {

				if ($Type == 'uploadimg' || $Type == 'uploadmultiple') {

					foreach ($PicSize as $picpre => $picsize) {

						$Upload->Upload_Img($Fval2, $Path, $picpre . '_' . $Save_Name, $picsize['w'], $picsize['h'], $Cover);
					}
				}

				if ($Type == 'uploadimg' || $Type == 'uploadfile') {

					$db->Where = " WHERE BINARY " . $Main_Key . " = '" . $ID . "'";
					$db->query_sql($Main_Table, $Field);
					$file_row = $db->query_fetch();

					if ($file_row[$Field] != $Upload_Name) {

						$db_data = array(
							$Field => $Upload_Name
						);

						$db->query_data($Main_Table, $db_data, 'UPDATE');

						if (empty($db->Error)) {

							Delete_File($Path, $file_row[$Field]);
						} else {

							$this->Msg = '寫入上傳資料失敗';

							Delete_File($Path, $Upload_Name);

							ob_end_clean();

							return;
						}
					}
				} else if ($Type == 'uploadmultiple') {

					$db_data = array(
						$Main_Key => $ID,
						$Field => $Upload_Name,
						$Main_TablePre2 . '_Sdate' => date('Y-m-d H:i:s')
					);

					$db->query_data($Main_Table2, $db_data, 'INSERT');

					if (!empty($db->Error)) {

						$this->Msg = '新增上傳資料失敗';

						Delete_File($Path, $Upload_Name);

						ob_end_clean();

						return;
					}
				}
			} else {

				$this->Msg = $Upload->Error;

				return;
			}
		}
	}

	function html_label_titile($Val)
	{

		return '<label class="col-sm-2 control-label">' . $Val . '</label>';
	}

	function html_label_comment($Val, $Type = 0)
	{

		if ($Type == 1) {

			return '<label class="col-sm-7 control-label11">' . $Val . '</label>'; //上傳專用
		} else if ($Type == 2) {

			return '<label class="control-label6">' . $Val . '</label>'; //checkbox 後面註解
		} else if ($Type == 3) {

			return '<label class="col-sm-12 control-label7">' . $Val . '</label>'; //一般專用 藍色字
		} else if ($Type == 4) {

			return '<label class="col-sm-12 control-label3">' . $Val . '</label>'; //一般專用 紅色字
		} else {

			return '<label class="col-sm-7 control-label2">' . $Val . '</label>'; //一般專用
		}
	}

	function html_view_content($Data, $Type = '')
	{

		$content  = '';
		$content .= '<div class="form-group view">';
		$content .=		'<table class="col-xs-12">';
		$content .=			'<tr >';
		$content .=				'<td class="col-xs-3 view-label">' . $Data['comment'] . '</td>';
		$content .=				'<td class="col-xs-9 vstyle">';

		if ($Type == 'checkbox') {

			$content .=				'<label class="' . ($Data['data'] ? 'vstyle1' : 'vstyle2') . '">' . ($Data['data'] ? '啟用' : '停用') . '</label>';
		} else if ($Type == 'uploadimg') {

			if (!empty($Data['data'])) {

				$content .=			'<label>';
				$content .=				'<a href="' . $Data['bUrl'] . '" class="vstyle3" style="background-image: url(\'' . $Data['sUrl'] . '\');" target="_blank"></a>';
				$content .=			'</label>';
			}
		} else {

			$content .=				'<label>' . $Data['data'] . '</label>';
		}

		$content .=				'</td>';
		$content .=			'</tr>';
		$content .=		'</table>';
		$content .= '</div>';

		return $content;
	}

	function html_city($Title = '', $Comment = '', $Field = '', $Val = '', $Msg = '', $SelClass = '', $SelCity = '', $SelCounty = '', $SelZipcode = '')
	{

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';
		$content .= '		<select id="' . $TurnCode . '" name="' . $TurnCode . '[]" msg="' . $Msg . '" class="col-xs-12 col-sm-5 padding_none">';
		$content .= '			<option value="0">縣市</option>';
		$content .= '		</select>';

		if (!empty($Comment)) {

			$content .=		$this->html_label_comment($Comment);
		}

		$content .= '		<script type="text/javascript">';
		$content .= '		$(document).ready(function() {';
		$content .= "			$('." . $SelClass . "').ajaddress({ city: '" . $Val[$Field] . "', selcity: '" . Turnencode($SelCity) . "', selcounty: '" . Turnencode($SelCounty) . "', selzipcode: '" . Turnencode($SelZipcode) . "' });";
		$content .= '		});';
		$content .= '		</script>';
		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_county($Title = '', $Comment = '', $Field = '', $Val = '', $Msg = '')
	{

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';
		$content .= '		<select id="' . $TurnCode . '" name="' . $TurnCode . '[]" msg="' . $Msg . '" county="' . $Val[$Field] . '" class="col-xs-12 col-sm-5 padding_none">';
		$content .= '			<option value="0">區域</option>';
		$content .= '		</select>';

		if (!empty($Comment)) {

			$content .=		$this->html_label_comment($Comment);
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_address($Title = '', $Placeholde = '', $Length = '', $Field = '', $Val = '', $Msg = '', $Zipcode = false, $Placeholde2 = '', $Length2 = '', $Field2 = '', $Msg2 = '')
	{

		$TurnCode  = Turnencode($Field);

		$TurnCode2 = Turnencode($Field2);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		if ($Zipcode) {

			$content .= '	<input type="text" id="' . $TurnCode2 . '" name="' . $TurnCode2 . '[]" class="col-sm-1" value="' . htmlspecialchars($Val[$Field2]) . '" msg="' . $Msg2 . '" maxlength="' . $Length2 . '" placeholder="' . $Placeholde2 . '" style="width: 60px;" readonly>';
		}

		$content .= '		<input type="text" id="' . $TurnCode . '" name="' . $TurnCode . '[]" class="col-xs-12 col-sm-5" value="' . htmlspecialchars($Val[$Field]) . '" msg="' . $Msg . '" maxlength="' . $Length . '" placeholder="' . $Placeholde . '">';

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_checkbox($Title = '', $Comment = '', $Field = '', $Val = '', $Msg = '', $Show = 0, $id = '')
	{

		global $_html_;

		if (count($_html_) > 1) {
			return;
		}

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		if (empty($Show)) {

			if (!empty($Val[$Field])) {

				$content .= $this->html_label_comment('啟用', 3);
			} else {

				$content .= $this->html_label_comment('停用', 4);
			}
		} else {

			$content .= '	<label class="control-label4">';
			$content .= '		<input type="checkbox" id="' . $id . '" name="' . $TurnCode . '[]" msg="' . $Msg . '" class="ace ace-switch ace-switch-7" value="1" ' . ($Val[$Field] ? 'checked="checked"' : '') . '>';
			$content .= '		<span class="lbl"></span>';
			$content .= '	</label>';

			if (!empty($Comment)) {

				$content .=		$this->html_label_comment($Comment, 2);
			}
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}
	function html_checkbox_muti($Title = '', $Comment = '', $Field = '', $Val = '', $Msg = '', $Show = 0, $id = '', $Arr = array())
	{

		global $_html_;

		if (count($_html_) > 1) {
			return;
		}

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9 outputlist_chbox_box">';

		if (empty($Show)) {

			if (!empty($Val[$Field])) {

				$content .= $this->html_label_comment('啟用', 3);
			} else {

				$content .= $this->html_label_comment('停用', 4);
			}
		} else {
			$Checked = unserialize($Val[$Field]);


			foreach ($Arr as $key => $val) {
				$check = 0;
				foreach ($Checked as $key2 => $val2) {
					if ($key == $val2) {
						$check = 1;
					}
				}
				$content .= '	<label class="control-label4">';
				$content .= '	<div class="boxitem">';
				$content .= '		<input type="checkbox" id="' . $id . '" name="' . $TurnCode . '[]" msg="' . $Msg . '" class=" ace-switch ace-switch-7" value="' . $key . '"  ' . ($check ? 'checked="checked"' : '') . '>' . $val;
				$content .= '	</div>';
				$content .= '	</label>';
			}


			if (!empty($Comment)) {

				$content .=		$this->html_label_comment($Comment, 2);
			}
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_uploadimg($Title = '', $Comment = '', $Field = '', $Val = '', $Msg = '', $UploadSize = 0, $Multiple = false, $Radio_Arr = array())
	{

		global $Menu_Data, $Main_Key, $Main_TablePre, $Main_Key2, $Main_TablePre2, $_html_, $table_info;

		if (count($_html_) > 1) {
			return;
		}

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';
		$content .= '		<div class="col-sm-5 padding_none">';

		if ($Multiple == false) { //單一圖片專用

			$content .= '		<input type="file" id="' . $Field . '" name="' . $Field . '[]" msg="' . $Msg . '" file-type="img" class="input-file table-updload" accept="image/*" max-uploadsize="' . (!empty($UploadSize) ? $UploadSize : '') . '" />';
		} else {

			$content .= '		<input type="file" id="' . $Field . '" name="' . $Field . '[]" msg="' . $Msg . '" multiple file-type="img" class="input-file table-updload" accept="image/*" max-uploadsize="' . (!empty($UploadSize) ? $UploadSize : '') . '" />';
		}
		$content .= '		</div>';

		$Upload = new Upload();
		$Format = !empty($UploadSize) ? $Upload->SizeFormat($UploadSize) : '2 MB';

		$Comment = !empty($Comment) ? '限制上傳 ' . $Format . ' ' . $Comment : '限制上傳 ' . $Format;

		if (!empty($Comment)) {

			$content .=		$this->html_label_comment($Comment, 1);
		}

		$content .= '		<script type="text/javascript">';
		$content .= '		$(document).ready(function() {';
		$content .= "			Upload_File_Class('#" . $Field . "');";
		$content .= '		});';
		$content .= '		</script>';

		if (!empty($Val[$Field]) && $Multiple == false) { //單一圖片專用

			$content .= '	<div class="clear_both">';
			$content .= '		<ul class="ace-thumbnails clearfix">';

			if (!empty($Radio_Arr)) {

				$content .= '		<li class="radio">';
				$content .= '			<input type="radio" name="' . Turnencode($Radio_Arr['name']) . '[]" value="' . $Radio_Arr['value'] . '" class="ace eshow" e-txt="設為封面圖" ' . ($Val[$Radio_Arr['name']] == $Radio_Arr['value'] ? 'checked' : '') . '>';
				$content .= '			<span class="lbl"></span>';
				$content .= '		</li>';
			}

			$content .= '			<li id="' . $TurnCode . '">';
			$content .= '				<a href="' . $Val[$Field . '_bUrl'] . '" class="imgajax file-url" file-url="' . $Val[$Field] . '" style="background-image: url(\'' . $Val[$Field . '_sUrl'] . '\');"></a>';
			$content .= '				<div class="tools tools-right">';
			$content .= '					<a href="javascript:void(0)" class="chk-file eshow" e-txt="刪除" check-type="delimg" check-id="' . $Val[$Main_Key] . '" check-field="' . $TurnCode . '">';
			$content .= '						<i class="ace-icon fa fa-times red"></i>';
			$content .= '					</a>';
			$content .= '				</div>';
			$content .= '			</li>';
			$content .= '		</ul>';
			$content .= '	</div>';
		}

		$content .= '	</div>';
		$content .= '</div>';

		if ($Multiple == true) { //相簿專用

			$content .= '<div class="form-group">';
			$content .= '	<div class="col-sm-12">';
			$content .= '		<div class="clear_both">';

			if (empty($Val[$Field . '_Arr'])) { //從post撈出圖片資料

				$content .= '		<h4 class="margin_none">( 暫無圖片 )</h4>';
			} else {

				$content .= '		<ul class="ace-thumbnails clearfix">';
				foreach ($Val[$Field . '_Arr'] as $ikey => $ival) {

					$content .= '		<li id="' . $TurnCode . '_' . $ival[$Main_Key2] . '">';
					$content .= '			<a href="' . $ival[$Field . '_bUrl'] . '" class="imgajax file-url2" style="background-image: url(\'' . $ival[$Field . '_sUrl'] . '\');" title="' . $ival[$Field] . '"></a>';
					$content .= '			<div class="tools tools-right">';

					if (!empty($Radio_Arr) && $Menu_Data['Menu_Albums_Mpc']) {

						$content .= '			<a href="javascript:void(0)" class="make_cover_photo eshow" e-txt="設為封面圖" check-type="make_cover_photo" check-id="' . $Val[$Main_Key] . '" check-field="' . Turnencode($Radio_Arr['name']) . '" check-data="' . $ival[$Field] . '" style="padding: 0 0 0 2px">';
						$content .= '				<i class="ace-icon fa fa-photo"></i>';
						$content .= '			</a>';
					}

					if ($Menu_Data['Menu_Albums_Edt']) {

						$content .= '			<a href="javascript:PEdit(\'' . $ival[$Main_Key2] . '\')" class="eshow" e-txt="編輯" style="padding: 0 0 0 2px">';
						$content .= '				<i class="ace-icon fa fa-pencil"></i>';
						$content .= '			</a>';
					}

					$content .= '				 <a href="javascript:void(0)" class="chk-file eshow" e-txt="刪除" check-type="delimgs" check-id="' . $ival[$Main_Key2] . '" check-field="' . $TurnCode . '">';
					$content .= '					<i class="ace-icon fa fa-times"></i>';
					$content .= '				</a>';

					$Open_Name = $Main_TablePre2 . '_Open';
					if ($ival[$Open_Name] != '') {

						$checked  = !empty($ival[$Open_Name]) ? 'checked' : '';
						$content .= '			<input type="checkbox" class="TableCheck ace eshow" e-txt="顯示" ' . $checked . '  check-type="checkboxs" check-id="' . $ival[$Main_TablePre2 . '_ID'] . '" check-field="' . Turnencode($Open_Name) . '">';
						$content .= '			<span class="lbl"></span>';
					}

					$content .= '			</div>';

					$Sort_Name = $Main_TablePre2 . '_Sort';
					if ($ival[$Sort_Name] != '') {

						$content .= '		<div>';
						$content .= '			<input type="text" value="' . $ival[$Sort_Name] . '" class="TableChange eshow selectAll" e-txt="排序" check-type="numbers" check-min="0" check-max="99999" check-id="' . $ival[$Main_TablePre2 . '_ID'] . '" check-field="' . Turnencode($Sort_Name) . '" check-data="' . $ival[$Sort_Name] . '" style="width: 100%;">';
						$content .= '		</div>';
					}

					$content .= '		</li>';
				}
				$content .= '		</ul>';
			}

			$content .= '		</div>';
			$content .= '	</div>';
			$content .= '</div>';
		}

		return $content;
	}

	function html_uploadfile($Title = '', $Comment = '', $Field = '', $Val = '', $Msg = '', $UploadSize = 0, $FileExt = '', $disabled = false, $Del = true)
	{

		global $Main_Key, $_html_;

		if (count($_html_) > 1) {
			return;
		}

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';
		$content .= '		<div class="col-sm-5 padding_none">';

		$content .= '			<input type="file" id="' . $Field . '" name="' . $Field . '[]" msg="' . $Msg . '" file-type="file" class="input-file table-updload" max-uploadsize="' . (!empty($UploadSize) ? $UploadSize : '') . '" fileext="' . $FileExt . '" ' . ($disabled ? 'disabled' : '') . ' />';
		$content .= '		</div>';

		$Upload = new Upload();
		$Format = !empty($UploadSize) ? $Upload->SizeFormat($UploadSize) : '2 MB';

		$Comment = !empty($Comment) ? '限制上傳 ' . $Format . ' ' . $Comment : '限制上傳 ' . $Format;

		if (!empty($Comment)) {

			$content .=		$this->html_label_comment($Comment, 1);
		}

		$content .= '		<script type="text/javascript">';
		$content .= '		$(document).ready(function() {';
		$content .= "			Upload_File_Class('#" . $Field . "');";
		$content .= '		});';
		$content .= '		</script>';

		if (!empty($Val[$Field])) {

			$content .= '	<div class="clear_both">';
			$content .= '		<span class="ace-thumbnails clearfix">';
			$content .= '			<span id="' . $TurnCode . '" class="input-icon">';

			if ($Del) {

				$content .= '				<button type="button" class="btn btn-white btn-danger btn-sm eshow control-size20 chk-file" e-txt="刪除" check-type="delfile" check-id="' . $Val[$Main_Key] . '" check-field="' . $TurnCode . '">';
				$content .= '					<i class="fa fa-trash-o"></i>';
				$content .= '				</button>';
			}

			$content .= '				<button type="button" class="btn btn-white btn-success btn-sm eshow control-size20 chk-file" e-txt="下載" check-type="downfile" check-id="' . $Val[$Main_Key] . '" check-field="' . $TurnCode . '">';
			$content .= '					<i class="fa fa-download"></i>';
			$content .= '				</button>';
			$content .= '				<a href="javascript:void(0)" class="file-url" file-url="' . $Val[$Field] . '"></a>';
			$content .= '			</span>';
			$content .= '		</span>';
			$content .= '	</div>';
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_number($Title = '', $Placeholde = '', $Comment = '', $Length = '', $Field = '', $Val = '', $Msg = '', $Show = 0, $Min = '', $Max = '')
	{

		$TurnCode = Turnencode($Field);

		$Val[$Field] = !empty($Val[$Field]) ? $Val[$Field] : 0;

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		if (empty($Show)) {

			$content .= 	$this->html_label_comment($Val[$Field], 3);
		} else {

			$content .= '	<input type="text" id="' . $TurnCode . '" name="' . $TurnCode . '[]" class="col-xs-12 col-sm-5" value="' . $Val[$Field] . '" msg="' . $Msg . '" maxlength="' . $Length . '" input-type="number" input-name="' . $Title . '" input-min="' . $Min . '" input-max="' . $Max . '" placeholder="' . $Placeholde . '" autocomplete="off">';
			if (!empty($Comment)) {

				$content .=	$this->html_label_comment($Comment);
			}
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_aznumber($Title = '', $Placeholde = '', $Comment = '', $Length = '', $Field = '', $Val = '', $Msg = '', $Show = 0)
	{

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		if (empty($Show)) {

			$content .= 	$this->html_label_comment($Val[$Field], 3);
		} else {

			$content .= '	<input type="text" id="' . $TurnCode . '" name="' . $TurnCode . '[]" class="col-xs-12 col-sm-5" value="' . $Val[$Field] . '" msg="' . $Msg . '" maxlength="' . $Length . '" input-type="aznumber" input-name="' . $Title . '" placeholder="' . $Placeholde . '" autocomplete="off">';
			if (!empty($Comment)) {

				$content .=	$this->html_label_comment($Comment);
			}
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_select($Title = '', $Comment = '', $Field = '', $Val = '', $Msg = '', $Show = 0, $Arr = array(), $Seloption = '')
	{

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		if (empty($Show)) {

			$content .= 	$this->html_label_comment($Arr[$Val[$Field]], 3);
		} else {

			$content .= '	<select id="' . $TurnCode . '" name="' . $TurnCode . '[]" msg="' . $Msg . '" class="col-xs-12 col-sm-5 padding_none">';
			$content .=			Select_Option($Arr, $Val[$Field], $Seloption);
			$content .= '	</select>';

			if (!empty($Comment)) {

				$content .=		$this->html_label_comment($Comment);
			}
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_radio($Title = '', $Comment = '', $Field = '', $Val = '', $Arr = array())
	{

		global $_html_;

		if (count($_html_) > 1) {
			return;
		}

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		if (empty($Arr)) {

			$content .= 	$this->html_label_comment('請設定選擇格式的內容', 3);
		} else {

			$content .= '		<p class="contro1-p">';
			$i = 0;
			foreach ($Arr as $key => $val) {

				$checked  = $Val[$Field] == $key || (empty($Val[$Field]) && $i == 0) ? 'checked' : '';

				$content .= '		<label>';
				$content .= '			<input type="radio" name="' . $TurnCode . '[]" class="ace" value="' . $key . '" ' . $checked . '>';
				$content .= '			<span class="lbl">&nbsp ' . $val . ' &nbsp;</span>';
				$content .= '		</label>';

				$i++;
			}
			$content .= '		</p>';
		}

		if (!empty($Comment)) {

			$content .=		$this->html_label_comment($Comment, 2);
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_datetime($Title = '', $Placeholde = '', $Comment = '', $Field = '', $Val = '', $Msg = '', $Show = 0, $Sn = '', $Type = '', $ConnectField = '', $Format = '')
	{

		global $_html_;

		if (empty($Show) && count($_html_) > 1) {
			return;
		}

		$TurnCode 		= Turnencode($Field);

		//$Val[$Field] 	= !empty($Val[$Field]) ? $Val[$Field] : Date_YmdHis;

		$Time_ID 		= $TurnCode . $Sn;

		$Val[$Field]	= ForMatDate($Val[$Field], $Format);

		$ConnectField 	= !empty($ConnectField) ? Turnencode($ConnectField) . $Sn : '';

		$content  		= '';

		$content 	   .= '<div class="form-group">';
		$content 	   .= 	$this->html_label_titile($Title);
		$content 	   .= '	<div class="col-sm-9">';

		if (empty($Show)) {

			$content .= 	$this->html_label_comment($Val[$Field], 3);
		} else {

			$content .= '	<input type="text" id="' . $Time_ID . '" name="' . $TurnCode . '[]" class="col-xs-12 col-sm-5 eshow" e-txt="點選日期" value="' . $Val[$Field] . '" msg="' . $Msg . '" check-data="' . $Val[$Field] . '" check-datatype="' . $Type . '" check-connectfield="' . $ConnectField . '" check-name="' . $Title . '" placeholder="' . $Placeholde . '">';

			$content .= '	<script type="text/javascript">Datetimepicker(\'#' . $Time_ID . '\', \'' . $Format . '\');</script>';
			if (!empty($Comment)) {

				$content .=	$this->html_label_comment($Comment);
			}
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}
	
	function html_Between_datetime($Title = '', $Placeholde = '', $Comment = '', $Field = '', $Val = '', $Msg = '', $Show = 0, $Sn = '', $Format = '')
	{

		global $_html_;

		if (empty($Show) && count($_html_) > 1) {
			return;
		}
		
		$_Field = $_TurnCode = $Time_ID = $_Val =  array();
		
		$Field = explode("," , $Field );
		
		$_Field['start'] 	= trim($Field[0]);
		$_Field['end'] 		= trim($Field[1]);
		
		$_TurnCode['start'] = Turnencode($_Field['start']);
		$_TurnCode['end'] 	= Turnencode($_Field['end']);
		
		$Time_ID['start'] 	= $_TurnCode['start'] . $Sn;
		$Time_ID['end'] 	= $_TurnCode['end'] . $Sn;
		
		$_Val['start'] 	= ForMatDate($Val[$_Field['start']], $Format);
		
		$_Val['end'] 	= ForMatDate($Val[$_Field['end']], $Format);
		
		$content  		= '';

		$content 	   .= '<div class="form-group">';
		$content 	   .= 	$this->html_label_titile($Title);
		$content 	   .= '	<div class="col-sm-9 betweenBox">';

		if (empty($Show)) {
			
			$content .= 	$this->html_label_comment($_Val['start'].' ～ '.$_Val['end'], 3);

		} else {

			$content .= '	<input type="text" id="' . $Time_ID['start'] . '" name="' . $_TurnCode['start'] . '[]" class="col-xs-12 col-sm-3 eshow" e-txt="點選日期" value="' . $_Val['start'] . '" msg="' . $Msg . '" check-data="' . $_Val['start'] . '" check-datatype="datestart" check-connectfield="' . $_TurnCode['end'] . '" check-name="' . $Title . '" placeholder="' . $Placeholde . '">';
			$content .= 	'<p class="betweenBox__text">To</p>';
			$content .= '	<input type="text" id="' . $Time_ID['end'] . '" name="' . $_TurnCode['end'] . '[]" class="col-xs-12 col-sm-3 eshow" e-txt="點選日期" value="' . $_Val['end'] . '" msg="' . $Msg . '" check-data="' . $_Val['end'] . '" check-datatype="dateend" check-connectfield="' . $_TurnCode['start'] . '" check-name="' . $Title . '" placeholder="' . $Placeholde . '">';

			$content .= '	<script type="text/javascript">Datetimepicker(\'#' . $Time_ID['start'] . '\', \'' . $Format . '\');</script>';
			$content .= '	<script type="text/javascript">Datetimepicker(\'#' . $Time_ID['end'] . '\', \'' . $Format . '\');</script>';
			
			if (!empty($Comment)) {

				$content .=	$this->html_label_comment($Comment);
			}
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}
	
	function html_textarea($Title = '', $Placeholde = '', $Length = '', $Field = '', $Val = '', $Msg = '', $disabled = false, $Rows = '3' , $Comment = '')
	{

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		$content .= '		<textarea rows="' . $Rows . '" class="col-xs-12 col-sm-8 control-textarea" id="' . $TurnCode . '" name="' . $TurnCode . '[]" msg="' . $Msg . '" maxlength="' . $Length . '" placeholder="' . $Placeholde . '" ' . ($disabled ? 'disabled' : '') . '>' . TurnSymbol($Val[$Field]) . '</textarea>';
		if( !empty($Comment) ){
			
			$content .=		$this->html_label_comment($Comment);
		}
		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_textedit($Title = '', $Field = '', $Val = '')
	{

		global $_html_;

		if (count($_html_) > 1) {
			return;
		}

		$TurnCode = Turnencode($Field);
		/*
		preg_match_all('/<img .* src="(.*?)"/i', $Val[$Field], $match);

		if (is_array($match[1])) { //判斷圖片位置

			$textedit_Url = WEB_URL.'upload/userfiles/images/';

			foreach ($match[1] as $val) {

				$path_parts  = pathinfo($val);

				$Img_Url  = $textedit_Url . $path_parts['basename'];

				$Val[$Field]  = str_replace($val, $Img_Url, $Val[$Field]);
			}
		}*/

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		$content .= '		<textarea id="' . $TurnCode . '" name="' . $TurnCode . '[]" editor="open_ckeditor">' . TurnSymbol($Val[$Field]) . '</textarea>';
		$content .= '		<script type="text/javascript">';
		$content .= '		$(document).ready(function() {';
		$content .= "			Editor['" . $TurnCode . "[]'] = CREAT_CKEDITOR('" . $TurnCode . "');";
		$content .= '		});';
		$content .= '		</script>';

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_youtube($Title = '', $Placeholde = '', $Comment = '', $Length = '', $Field = '', $Val = '', $Msg = '')
	{

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		$content .= '		<input type="text" id="' . $TurnCode . '" name="' . $TurnCode . '[]" class="col-xs-12 col-sm-5" value="' . htmlspecialchars($Val[$Field]) . '" msg="' . $Msg . '" maxlength="' . $Length . '" placeholder="' . $Placeholde . '" autocomplete="off">';
		if (!empty($Comment)) {

			$content .=		$this->html_label_comment($Comment);
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_text($Title = '', $Placeholde = '', $Comment = '', $Length = '', $Field = '', $Val = '', $Msg = '', $Show = 0, $disabled = false, $id = '', $readonly = false)
	{ //$disabled和$id目前用在網站管理
		global $_html_;

		if (empty($Show) && count($_html_) > 1) {
			return;
		}

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		if (empty($Show)) {

			$content .= 	$this->html_label_comment($Val[$Field], 3);
		} else {

			$content .= '	<input type="text" id="' . $id . '" name="' . $TurnCode . '[]" class="col-xs-12 col-sm-5" value="' . htmlspecialchars($Val[$Field]) . '" msg="' . $Msg . '" maxlength="' . $Length . '" placeholder="' . $Placeholde . '" autocomplete="off" ' . ($disabled ? 'disabled' : '') . ' ' . ($readonly ? 'readonly' : '') . '>';
			if (!empty($Comment)) {

				$content .=	$this->html_label_comment($Comment);
			}
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}
	
	function html_customtext( $Title = '', $Val )
	{ 
		global $_html_;

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';
		
		$content .= 	$this->html_label_comment($Val, 3);
		
		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}
	
	function html_unique($Title = '', $Placeholde = '', $Comment = '', $Length = '', $Field = '', $Val = '', $Msg = '', $Show = 0)
	{

		global $_html_;

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		if (empty($Show) || count($_html_) > 1) {

			$content .= 	$this->html_label_comment($Val[$Field], 3);
		} else {
			//防止自動填入帳號密碼出現提示已有此帳號用
			$content .= '	<input type="text" name="' . $Field . '[]" style="display: none;" disabled>';
			//防止自動填入帳號密碼出現提示已有此帳號用
			$content .= '	<input type="text" id="' . $TurnCode . '" name="' . $TurnCode . '[]" class="TableChange col-xs-12 col-sm-5" value="' . $Val[$Field] . '" msg="' . $Msg . '" maxlength="' . $Length . '" check-type="norepeat" check-field="' . $TurnCode . '" check-data="' . $Val[$Field] . '" autocomplete="off" placeholder="' . $Placeholde . '">';
			if (!empty($Comment)) {

				$content .=	$this->html_label_comment($Comment);
			}
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_pwd($Title = '', $Placeholde = '', $Comment = '', $Length = '', $Field = '')
	{

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		$content .= '		<input type="password" id="' . $TurnCode . '" name="' . $TurnCode . '[]" class="col-xs-12 col-sm-5" maxlength="' . $Length . '" placeholder="' . $Placeholde . '">';
		if (!empty($Comment)) {

			$content .=		$this->html_label_comment($Comment);
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}

	function html_favorable($Title = '', $Placeholde = '', $Length = '', $Field = '', $Val = '', $Msg = '', $Show = 0, $Field2 = '', $Msg2 = '', $Arr2 = array(), $Seloption = '')
	{

		$TurnCode  = Turnencode($Field);

		$TurnCode2 = Turnencode($Field2);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		if (empty($Show)) {

			$content .= 	$this->html_label_comment('可享有 ' . $Val[$Field] . $Arr2[$Val[$Field2]] . ' 的優惠價格', 3);
		} else {

			$content .= '	可享有';
			$content .= '	<input type="text" id="' . $TurnCode . '" name="' . $TurnCode . '[]" value="' . htmlspecialchars($Val[$Field]) . '" msg="' . $Msg . '" maxlength="' . $Length . '" placeholder="' . $Placeholde . '" style="width: 50px;height: 30px;">';

			$content .= '	<select id="' . $TurnCode2 . '" name="' . $TurnCode2 . '[]" msg="' . $Msg . '" style="width: 50px;height: 30px;">';
			$content .=			Select_Option($Arr2, $Val[$Field2], $Seloption);
			$content .= '	</select>';
			$content .= '	的優惠價格';
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}
	
	function html_link($Title = '', $Placeholde = '', $Comment = '', $Length = '', $Field = '', $Val = '', $Msg = '', $Show = 0, $disabled = false, $id = '', $readonly = false, $ConnectField = '')
	{
		global $_html_ , $html_target;

		if (empty($Show) && count($_html_) > 1) {
			return;
		}

		$TurnCode 	= Turnencode($Field);
		
		if( !empty($ConnectField) ) {
		
			$TurnCode2 	= Turnencode($ConnectField);
		}
		
		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';

		if (empty($Show)) {

			$content .= 	$this->html_label_comment($Val[$Field], 3);
		} else {

			$content .= '	<input type="text" id="' . $id . '" name="' . $TurnCode . '[]" class="col-xs-12 col-sm-5" value="' . htmlspecialchars($Val[$Field]) . '" msg="' . $Msg . '" maxlength="' . $Length . '" placeholder="' . $Placeholde . '" autocomplete="off" ' . ($disabled ? 'disabled' : '') . ' ' . ($readonly ? 'readonly' : '') . '>';
			
			//
			
			foreach( $html_target as $key => $val ) {
				
				$checked  = $Val[$ConnectField] == $key ? 'checked' : '';
				
				$content .= '		<label>';
				$content .= '			<input type="radio" name="' . $TurnCode2 . '[]" class="ace" value="' . $key . '" ' . $checked . '>';
				$content .= '			<span class="lbl">&nbsp ' . $val . ' &nbsp;</span>';
				$content .= '		</label>';
				
			}
				
			if (!empty($Comment)) {

				$content .=	$this->html_label_comment($Comment);
			}
		}

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}
	
	function html_keyword($Title = '', $Comment = '' , $Field = '', $Val = '')
	{

		global $_html_;

		$TurnCode = Turnencode($Field);

		$content  = '';

		$content .= '<div class="form-group">';
		$content .= 	$this->html_label_titile($Title);
		$content .= '	<div class="col-sm-9">';
		$content .= '		<ul class="keywordBox" data-field="'.$TurnCode.'">';
		
		if( !empty($Val[$Field]) ) {
		
			$_Arr = explode( "," , $Val[$Field] );

			foreach( $_Arr as $_val ){
			
				$content .= '			<li class="keywordBox__item"><p>'.$_val.'</p><a class="deleteKY">✕</a></li>';
			}
		}
		
		$content .= '			<input type="text" class="keywordBox__input" onkeydown="KeyWord_ClickEnter()">';
		if (!empty($Comment)) {

			$content .=	$this->html_label_comment($Comment);
		}
		
		$content .= '		</ul>';
		$content .= '		<script type="text/javascript">';
		$content .= '		$(document).ready(function() {';
		
		$content .= '			$( ".keywordBox__input" ).on( "keyup", function( event ) {	';
		$content .= '				if(event.keyCode ==13 || event.keyCode ==188 ){	';
		$content .= '					$( ".keywordBox__input" ).val("");	';
		$content .= '				}';
		$content .= '			});';
		
		$content .= '			$("body").on("click",".deleteKY",function(){';
		$content .= '				$(this).parent(".keywordBox__item").remove();';
		$content .= '			});';
		
		$content .= '		});';
		$content .= '		</script>';

		$content .= '	</div>';
		$content .= '</div>';

		return $content;
	}
	
	function html_text_hidden($Title = '', $Placeholde = '', $Comment = '', $Length = '', $Field = '', $Val = '', $Msg = '', $Show = 0, $disabled = false, $id = '', $readonly = false)
	{ //$disabled和$id目前用在網站管理
		global $_html_;

		if (empty($Show) && count($_html_) > 1) {
			return;
		}

		$TurnCode = Turnencode($Field);

		$content  = '';

	

		if (empty($Show)) {

			$content .= 	$this->html_label_comment($Val[$Field], 3);
		} else {

			$content = '	<input type="hidden" id="' . $id . '" name="' . $TurnCode . '[]" class="col-xs-12 col-sm-5" value="' . htmlspecialchars($Val[$Field]) . '" msg="' . $Msg . '" maxlength="' . $Length . '" placeholder="' . $Placeholde . '" autocomplete="off" ' . ($disabled ? 'disabled' : '') . ' ' . ($readonly ? 'readonly' : '') . '>';
			if (!empty($Comment)) {

				$content .=	$this->html_label_comment($Comment);
			}
		}

	

		return $content;
	}
	
	function html_unit($table_info = array(), $Val = '')
	{

		$_FieldsList = array(

			'Product_Unit',
			'Product_Unit_NO',
			'Product_Price',
			'Product_Price1',
			'Product_Unit_ID'

		);

		//初始化資料
		foreach ($_FieldsList as $_Field) {

			$_ArrayList[$_Field] = unserialize($Val[$_Field]);
		}

		$_ArrayCount = count($_ArrayList['Product_Unit']);

		for ($i = 0; $i < $_ArrayCount; $i++) {
			$content .='<div id = box_'.$i.'>';
			if ($i == 0) {
				$content .= '<div class="form-group "><label class="col-sm-2 control-label" style="font-size:16px; text-decoration:underline;">第 ' . ($i + 1) . ' 種規格</label>	<button type="button" class="btn btn-purple btn-sm unit-additem" style="margin-left: 12px;">新增規格</button></div>';
			} else {
				$content .= '<div class="form-group "><label class="col-sm-2 control-label" style="font-size:16px; text-decoration:underline;">第 ' . ($i + 1) . ' 種規格</label><button type="button" class="btn btn-purple btn-sm unit-delitem" style="margin-left: 12px;">刪除規格</button></div>';
			}
			//規格
			$Arr_Name = 'Product_Unit_ID';

			$Val[$Arr_Name] = $_ArrayList[$Arr_Name][$i];

			$content .= $this->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $Val, '', 0, '', '', '');
			$content .= $this->html_text_hidden($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $Val, '', 1, '', '', '');
			//-----------------------------------------//
			//進銷存產品編號
			$Arr_Name = 'Product_Unit_NO';

			$Val[$Arr_Name] = $_ArrayList[$Arr_Name][$i];

			$content .= $this->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $Val, '', 1, '', '', '');
			//-----------------------------------------//
			$Arr_Name = 'Product_Unit';

			$Val[$Arr_Name] = $_ArrayList[$Arr_Name][$i];

			$content .= $this->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $Val, '請輸入規格', 1, '', '', '');
			//-----------------------------------------//
			//原價
			$Arr_Name = 'Product_Price';

			$Val[$Arr_Name] = $_ArrayList[$Arr_Name][$i];

			$content .=  $this->html_number($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $Val, '', 1, '0', '99999');
			
			//-----------------------------------------//
			//售價
			$Arr_Name = 'Product_Price1';

			$Val[$Arr_Name] = $_ArrayList[$Arr_Name][$i];

			$content .=  $this->html_number($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $Val, '', 1, '0', '99999');

			$content .= '<hr style=" padding-left:20px;border: 0; height: 1px;background: #ccc;">';
			$content.='</div>';
			
		}

		return $content;
	}
}
