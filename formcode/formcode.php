<?php  #產生驗證碼
session_start();

$mkImg = new mk_ChkImg();
$mkImg->makeRand('5');
$img = $mkImg->makeImg('110', '35');
echo $img;

class mk_ChkImg{

	function makeRand( $num ){

		$this->code = '';

		//$_char = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$_char = '0123456789';

		mt_srand( (double)microtime() * 1000000 );
		for ( $i = 0; $i < $num; $i++ ){
			//$rand_val = mt_rand( 0, 35 );
			$rand_val = mt_rand( 0, 9 );
			$gid .= $_char[$rand_val];
		}

	    $this->code = $gid;
		
		$_SESSION[$_Website]['website']['formcode'] = $this->code;
	}

	function makeImg( $width, $hight ){

		$s_x = 0;
		$s_y = 0;
		$ans_right_move = '';

		$im = imagecreate( $width, $hight );

		$red2 = imagecolorallocate( $im, 255, 0, 0 );  //文字顏色

		$gray2 = imagecolorallocate( $im, 200, 200, 200 );  //背影顏色

		imagefill( $im, 0, 0, $gray2 );
		mt_srand((double)microtime() * 1000000);  //重置隨機值

		//隨機30點
		$s_dot = imagecolorallocate( $im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,128) );
		for( $i=0; $i<30; $i++ ){
		     imagesetpixel( $im,mt_rand( 10, $width-10 ),mt_rand( 5, $hight-5 ), $s_dot );
		}

		//文字隨機浮動
		$s_x = mt_rand(5,10);
		$font_path = 'CornFed.ttf';
		for($i=0; $i<6; $i++){
		     $ans_right_move = substr( $this->code, $i, 1 );
		     $s_y = mt_rand(15,25);
		     // imagestring( $im,5,$s_x,$s_y,$ans_right_move,$red2);
		     imagettftext($im, 14, 0, $s_x, $s_y, $red2, $font_path, $ans_right_move);
		     $s_x = $s_x + mt_rand(15,25);
		}
		
		//輸出圖片
		header('Content-type: image/png');

		return imagepng($im);

		imagedestroy($im);
	}

}
?>