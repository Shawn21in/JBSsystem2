<?php

class ReCaptcha{
	
	var $Data 	= array();
	var $rs 	= "";
	
	function __construct(){
		
		
		$db = new MySQL();
		$db->Where = "Where Admin_ID = 2";
		$db->query_sql( "mod_recaptcha" , "*" );
		
		if( $row = $db->query_fetch( '', 'assoc') ){
			
			$this->Data = $row;
		}else{
		
			exit("ReCaptcha Option Data is Error");
		}
		
	}
	
	function Call_Init( $_FieldName , $Action = 'contact' ){
		
		$_content = "";
		$_content .= "<script src='".$this->Data['Recaptcha_JS_url'].$this->Data['Recaptcha_SiteKey']."'></script>";
		$_content .= "<script>$(document).ready(function() { grecaptcha.ready(function() { grecaptcha.execute('".$this->Data['Recaptcha_SiteKey']."', {action: '".$Action."'}).then(function(token) { $('".$_FieldName."').val(token);});});});</script>";
		
		return $_content;
	}
	
	function Verify( $_response ){
	
		$recaptcha_url 		= $this->Data['Recaptcha_API_url'];
		$recaptcha_secret 	= $this->Data['Recaptcha_SecretKey'];
		$recaptcha_response = $_response;

		//Make and decode POST request:
		$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
		$recaptcha = json_decode($recaptcha);

		//如果驗證成功，就進一步計算使用者分數，官方回饋分數為 0-1，分數愈接近 1 就是正常，低於 0.5 以下就有可能是機器人了
		if($recaptcha->success==true){
			
			if($recaptcha->score >= 0.5) {
				$this->rs = "success";
			} else {
				$this->rs = "error";
			} 
		} else {
		  
		  $this->rs = "Curl Error";
		}
		
		return $this->rs;
	}
	
	
	function __destruct(){
		
		
		unset($this->rs);
		unset($this->Data);
	}
}
?>