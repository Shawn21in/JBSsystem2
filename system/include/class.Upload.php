<?php

class Upload{
	
	var $Error;
	
	var $Upload_Url;
	
	var $Upload_Size = 2048000;//預設上傳大小2MB
	
	var $Chk_Size	 = false;//判斷大小
	
	var $Allow_File  = 'jpg, jpeg, png, gif, doc, docx, pdf, ppt, pptx, xls, xlsx, csv, ai, psd, zip, rar, mp4, odt, ods, odp';
	function Upload_File( $Files, $Path, $Save_Name = '', $Cover = true ){
		//Cover 檔案是否覆蓋，預設覆蓋原檔案
		$this->Error = '';
		
		if( $this->Chk_Size ){
			
			$Size = $Files['size'];
		
			if( $Size > $this->Upload_Size ){
				
				$this->Error = '上傳檔案大小不能超過 ( ' .$this->SizeFormat($this->Upload_Size). ' )';
				return;
			}
		}
		
		$ex			= explode('.', $Files['name']);
		$Files_Name = $ex[0];
		$Sub_Name 	= strtolower(end($ex));
		
		if( empty($Save_Name) ){
			
			$Save_Name = $Files_Name;
		}
		
		$Upload_Name = $Save_Name.'.'.$Sub_Name;
		
		if( !preg_match('/' .$Sub_Name. '/i', $this->Allow_File) ){
					
			$this->Error = '這是不允許上傳的檔案';
			return;
		}
						
		if( !is_dir($Path) ){
			
			mkdir($Path, 0775);
		}
		
		if( $Cover == false ){//不覆蓋原檔案
			
			$FileCnt = 0;
			while( file_exists($Path.ICONV_CODE('UTF8_TO_BIG5', $Upload_Name)) ){
				
				$FileCnt++;
				$Upload_Name = $Save_Name.'(' .$FileCnt. ')'.'.'.$Sub_Name; 
			}
		}
				
		$Upload_Url = $Path.ICONV_CODE('UTF8_TO_BIG5', $Upload_Name);
		
		move_uploaded_file($Files['tmp_name'], $Upload_Url);
		
		if( !is_file($Upload_Url) ){
			
			$this->Error = '檔案上傳失敗';
			return;
		}
		
		return $Upload_Name;
	}
	
	function Upload_Img( $Files, $Path, $Save_Name = '', $ReSize_w = '', $ReSize_h = '', $Cover = true ){
		
		$File = $Files['tmp_name'];
		
		$this->Error = '';
		
		if( $this->Chk_Size ){
			
			$Size = $Files['size'];
			
			if( $Size > $this->Upload_Size ){
				
				$this->Error = '上傳圖片大小不能超過 ( ' .$this->SizeFormat($this->Upload_Size). ' )';
				return;
			}
		}
		
		if( empty($Save_Name) ){
			
			$ex			= explode('.', $Files['name']);
			$Save_Name 	= $ex[0];
		}
		
		list($width, $height, $image_type) = getimagesize($File);
		
		$Upload_Name = '';
		switch( $image_type ){
			
			case 1: 
				
				$Sub_Name 	= 'gif';
				$src 		= imagecreatefromgif($File); 
			break;
			
			case 2: 
				
				$Sub_Name 	= 'jpg';
				$src 		= imagecreatefromjpeg($File); 
			break;
			
			case 3: 
				
				$Sub_Name 	= 'png';
				$src 		= imagecreatefrompng($File);
			break;
			
			default: 
			
				$this->Error = '圖片格式錯誤';
				return;
			break;
		}
				
		$Upload_Name = $Save_Name.'.'.$Sub_Name;
				
		if( !is_dir($Path) ){
			
			mkdir($Path, 0775);
		}
		
		if( $Cover == false ){//不覆蓋原檔案
			
			$FileCnt = 0;
			while( file_exists($Path.ICONV_CODE('UTF8_TO_BIG5', $Upload_Name)) ){
				
				$FileCnt++;
				$Upload_Name = $Save_Name.'_' .$FileCnt. '.'.$Sub_Name; 
			}
		}
		
		
		if( !empty($ReSize_w) || !empty($ReSize_h) ){
	
			$ReSize_w = !empty($ReSize_w) ? $ReSize_w : $ReSize_h;
			$ReSize_h = !empty($ReSize_h) ? $ReSize_h : $ReSize_w;
			
			$percent = $this->ResizePercent($width, $height, $ReSize_w, $ReSize_h);
			
			$tn_width  = ceil($width * $percent);
			$tn_height = ceil($height * $percent);
		}else{
			
			$tn_width  = $width;
			$tn_height = $height;
		}
				
		$tmp = imagecreatetruecolor($tn_width,$tn_height);
	
		if( $image_type == 1 ){
			
			$white = imagecolorallocate($tmp, 255, 255, 255); 
			imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $white);  
			imagecolortransparent($tmp, $white);
			
		}else if( $image_type == 3 ){
			
			imagesavealpha($src, true);
			imagealphablending($tmp, false);
			imagesavealpha($tmp, true);
		}
		
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
		
		$Upload_Url = $Path.ICONV_CODE('UTF8_TO_BIG5', $Upload_Name);
		
		switch ($image_type){
			
			case 1: imagegif($tmp, $Upload_Url); break;
			case 2: imagejpeg($tmp, $Upload_Url, 100);  break; // best quality
			case 3: imagepng($tmp, $Upload_Url, 1); break; // no compression
		}
		
		if( !is_file($Upload_Url) ){
			
			$this->Error = '圖片上傳失敗'.$Upload_Url;
			return;
		}
		
		return $Upload_Name;
	}
	
	function Crop_Img( $Files, $Path, $Save_Name = '', $ReSize_w = '', $ReSize_h = '', $Cover = true, $Crop ){//圖片裁切
		
		if( !is_array($Crop) || empty($Crop) ){
			
			$this->Error = '請設定裁切的資料';
			return;
		}else{
			
			$x		= $Crop['x'];
			$y		= $Crop['y'];
			$crop_w	= $Crop['cw'];
			$crop_h	= $Crop['ch'];
			$nimg_w	= $Crop['nw'];//目前圖片大小
			$nimg_h	= $Crop['nh'];//目前圖面大小
		}
				
		$File = $Files['tmp_name'];
		
		$this->Error = '';
		
		if( $this->Chk_Size ){
			
			$Size = $Files['size'];
			
			if( $Size > $this->Upload_Size ){
				
				$this->Error = '上傳圖片大小不能超過 ( ' .$this->SizeFormat($this->Upload_Size). ' )';
				return;
			}
		}
		
		if( empty($Save_Name) ){
			
			$ex			= explode('.', $Files['name']);
			$Save_Name 	= $ex[0];
		}
		
		list($width, $height, $image_type) = getimagesize($File);
		
		$Upload_Name = '';
		switch( $image_type ){
			
			case 1: 
				
				$Sub_Name 	= 'gif';
				$src 		= imagecreatefromgif($File); 
			break;
			
			case 2: 
				
				$Sub_Name 	= 'jpg';
				$src 		= imagecreatefromjpeg($File); 
			break;
			
			case 3: 
				
				$Sub_Name 	= 'png';
				$src 		= imagecreatefrompng($File);
			break;
			
			default: 
			
				$this->Error = '圖片格式錯誤';
				return;
			break;
		}
		
		if( !is_dir($Path) ){
			
			mkdir($Path, 0775);
		}
		
		//先縮圖一次
		$percent = $this->ResizePercent($width, $height, $nimg_w, $nimg_h);
		
		$tn_width  = ceil($width * $percent);
		$tn_height = ceil($height * $percent);

		$tmp = imagecreatetruecolor($tn_width, $tn_height);

		if( $image_type == 1 ){
			
			$white = imagecolorallocate($tmp, 255, 255, 255); 
			imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $white);  
			imagecolortransparent($tmp, $white);
		}else if( $image_type == 3 ){
			
			imagesavealpha($src, true);
			imagealphablending($tmp, false);
			imagesavealpha($tmp, true);
		}
		
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
		
		$Temp_CropImg = $Path.'tmpcrop.'.$Sub_Name;
		
		switch ($image_type){
			
			case 1: imagegif($tmp, $Temp_CropImg); break;
			case 2: imagejpeg($tmp, $Temp_CropImg, 100);  break; // best quality
			case 3: imagepng($tmp, $Temp_CropImg, 1); break; // no compression
		}
		//先縮圖一次
		
		switch( $image_type ){
			
			case 1: 
				
				$src = imagecreatefromgif($Temp_CropImg); 
			break;
			
			case 2: 

				$src = imagecreatefromjpeg($Temp_CropImg); 
			break;
			
			case 3: 
				
				$src = imagecreatefrompng($Temp_CropImg);
			break;
		}

		$Upload_Name = $Save_Name.'.'.$Sub_Name;
		
		if( $Cover == false ){//不覆蓋原檔案
			
			$FileCnt = 0;
			while( file_exists($Path.ICONV_CODE('UTF8_TO_BIG5', $Upload_Name)) ){
				
				$FileCnt++;
				$Upload_Name = $Save_Name.'_' .$FileCnt. '.'.$Sub_Name; 
			}
		}
				
		if( !empty($ReSize_w) || !empty($ReSize_h) ){
			
			$ReSize_w = !empty($ReSize_w) ? $ReSize_w : $ReSize_h;
			$ReSize_h = !empty($ReSize_h) ? $ReSize_h : $ReSize_w;
			
			$percent = $this->ResizePercent($crop_w, $crop_h, $ReSize_w, $ReSize_h);
			
			$tn_width  = ceil($crop_w * $percent);
			$tn_height = ceil($crop_h * $percent);
		}else{
			
			$tn_width  = $crop_w;
			$tn_height = $crop_h;
		}
		
		$tmp = imagecreatetruecolor($tn_width, $tn_height);
	
		if( $image_type == 1 ){
			
			$white = imagecolorallocate($tmp, 255, 255, 255); 
			imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $white);  
			imagecolortransparent($tmp, $white);
			
		}else if( $image_type == 3 ){
			
			imagesavealpha($src, true);
			imagealphablending($tmp, false);
			imagesavealpha($tmp, true);
		}
		
		imagecopyresampled($tmp, $src, 0, 0, $x, $y, $tn_width, $tn_height, $crop_w, $crop_h);
		
		$Upload_Url = $Path.ICONV_CODE('UTF8_TO_BIG5', $Upload_Name);
		
		switch ($image_type){
			
			case 1: imagegif($tmp, $Upload_Url); break;
			case 2: imagejpeg($tmp, $Upload_Url, 100);  break; // best quality
			case 3: imagepng($tmp, $Upload_Url, 1); break; // no compression
		}
		
		if( !is_file($Upload_Url) ){
			
			$this->Error = '圖片上傳失敗';
			return;
		}
		
		@unlink($Temp_CropImg);
		
		return $Upload_Name;
	}
	
	function ResizePercent($source_w, $source_h, $ReSize_w, $ReSize_h){
		
		if ($source_w < $ReSize_w && $source_h < $ReSize_h) {
			
			return 1; // Percent = 1, 如果都比預計縮圖的小就不用縮
		}
	
		$w_percent = $ReSize_w / $source_w;
		$h_percent = $ReSize_h / $source_h;
	
		return ($w_percent > $h_percent) ? $h_percent : $w_percent;
	}
	
	function SizeFormat($size){
		
		if( $size < 1024 ){
			
			return $size." bytes";
		}else if( $size < ( 1024 * 1024 ) ){
			
			$size = round($size / 1024, 1);
			return $size." KB";
		}else if( $size < ( 1024 * 1024 * 1024 ) ){
			
			$size = round($size / (1024 * 1024), 1);
			return $size." MB";
		}else{
			
			$size = round($size / (1024 * 1024 * 1024), 1);
			return $size." GB";
		}
	}
}
?>