<?php

class SendMail{
	
	var $MailType				= "";
	var $MailData				= "";
	var $Option					= array();
	var $SendList				= array();
	var $MailTo					= array();
	var $MailBody				= "";
	var $result					= "";
	
	function __construct( ){
		
		$Admin_FromName			=  !empty($_setting_['WO_SendName']) ? $_setting_['WO_SendName'] : '本網站';
	}
	
	function get_MailBody(){
		
		
		switch( $this->MailType ){
			
			//訂單會員
			case "custom_order":
				$url = WEB_Mail_URL.'Mail_custom_order.php?c='.OEncrypt( $this->MailData , $this->MailType );
			break;
			//訂單經銷
			case "custom_sellorder":
				$url = WEB_Mail_URL.'Mail_custom_sellorder.php?c='.OEncrypt( $this->MailData , $this->MailType );
			break;
			
			//訂單會員-管理者
			case "admin_order":
				$url = WEB_Mail_URL.'Mail_admin_order.php?c='.OEncrypt( $this->MailData , $this->MailType );
			break;
			
			//訂單經銷-管理者
			case "admin_sellorder":
				$url = WEB_Mail_URL.'Mail_admin_sellorder.php?c='.OEncrypt( $this->MailData , $this->MailType );
			break;
			
			//聯絡我們-會員
			case "custom_contact":
				$url = WEB_Mail_URL.'Mail_custom_contact.php?c='.OEncrypt( $this->MailData , $this->MailType );
			break;
			
			//聯絡我們-管理者
			case "admin_contact":
				$url = WEB_Mail_URL.'Mail_admin_contact.php?c='.OEncrypt( $this->MailData , $this->MailType );
			break;
			
			//信箱驗證
			case "emailauth":
				$url = WEB_Mail_URL.'Mail_custom_emailauth.php?c='.OEncrypt( $this->MailData , $this->MailType );
			break;
			
			//會員忘記密碼
			case "forgetpw_member":
				$url = WEB_Mail_URL.'Mail_custom_forgetpw.php?c='.OEncrypt( $this->MailData , $this->MailType );
			break;
			
			//經銷忘記密碼
			case "forgetpw_sell":
				$url = WEB_Mail_URL.'Mail_custom_sellforgetpw.php?c='.OEncrypt( $this->MailData , $this->MailType );
			break;
			
		}
		
		
		$ch = curl_init();
	
		curl_setopt($ch , CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch , CURLOPT_HEADER, false);
		curl_setopt($ch , CURLOPT_NOBODY , false);
		curl_setopt($ch , CURLOPT_URL , $url );
		
		
		$result = curl_exec($ch);
	
		curl_close($ch);
		
		$this->MailBody = $url;
	}

	function Test_Send(){
		
		$this->get_MailBody();
		
		$Send_TF 	= SendMail($_setting_, '您在 '.$this->Admin_FromName. ' 留言內容, 此信件為系統發出信件，請勿直接回覆', $this->MailBody, $this->MailTo);
		
		if( $Send_TF ) {
			
			$this->result= "SUCCESS";
		}else{
			
			$this->result= "ERROR";
		}
	}
}
?>