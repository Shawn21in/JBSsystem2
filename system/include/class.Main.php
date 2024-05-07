<?php
const DIR_SEP=DIRECTORY_SEPARATOR;
class Main {
	function set_head() {
		include_once(__DIR__ . DIR_SEP.'..'.DIR_SEP.'php_sys'.DIR_SEP.'head.php');
	}

	function set_header() {
		global $DB_DataBase, $Admin_data, $Sys_Tables_Arr;
		include_once(dirname(__DIR__) . DIR_SEP.'php_sys'.DIR_SEP.'header.php');
	}

	function set_footer() {
		include_once(__DIR__ . DIR_SEP.'php_sys'.DIR_SEP.'footer.php');
	}

	function set_box() {
		include_once(__DIR__ . DIR_SEP.'..'.DIR_SEP.'php_sys'.DIR_SEP.'extra_box.php');
	}
}