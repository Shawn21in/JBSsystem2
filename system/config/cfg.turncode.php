<?php

if( $Type == 'website' ){

	$TURNCODES = 'GgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz_AaBbCcDdEeFf0123456789/&?';
	$TURNCODEE = '_0123456789abcdefghijklmnopqrstuvwxyz/&?ABCDEFGHIJKLMNOPQRSTUVWXYZ';
}else if( $Type == 'downfile' ){
	
	$TURNCODES = 'UuVvWwXxYyZz\\GgHhIiJjK:kLlMmNnOoP&pQqRrSsTt_AaBb012345CcDdEeFf6789';
	$TURNCODEE = '_0123456789abcdefghijklmnopqrstuvwxy&zABCDEFGHIJKLMNOPQRSTUVWX\\YZ:';
}else if( $Type == 'password' ){
	
	$TURNCODES = 'UuVvWwXxYyZzGgHhIiJjKkLlMmNnOoPpQqRrSsTt_AaBb012345CcDdEeFf6789';
	$TURNCODEE = '_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
}else{
	
	$TURNCODES = '0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz_';
	$TURNCODEE = '_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
}
?>