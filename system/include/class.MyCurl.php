<?php

if(isset($_SERVER['HTTP_USER_AGENT'])){

	define('USER_AGENT', $_SERVER['HTTP_USER_AGENT']);
}else{

	define('USER_AGENT', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
}

class MyCurl{

    private $_verbose		= false;
    private $_progress 		= true;
    private $mycookie 		= "";
    private $myheader 		= "";
	private $myinterface 	= "";
    public $referer_url 	= "";
    public $history_url 	= "";
	public $timeout 		= 20;
	public $error 			= "";
	public $errno 			= "";
	public $force_ipv4 		= false;

    function __construct( $cookie = "", $header = "", $interface = "" ){

    	$this->mycookie = $cookie;
    	$this->myheader = $header;
		$this->myinterface = $interface;
    }

    function saveToString( $url, $post_data = array(), $cookie = "", $proxy = "", $compress = "", &$last_url = "" ){

        $ch = curl_init();

		$this->error = "";
		$this->errno = "";

		if($this->force_ipv4){

			if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){

				curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
			}
		}

        curl_setopt($ch, CURLOPT_URL, $url);
		
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		
		if(substr($url, 0, 8)=='https://'){

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->_verbose);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_NOPROGRESS, $this->_progress);
        curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

		if($this->timeout!=0){

			curl_setopt($ch,CURLOPT_TIMEOUT, $this->timeout);
		}

		if(count($post_data)>0){

			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}

        if($this->myheader=="Expect"){

			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		}else if($this->myheader!=""){

			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->myheader);
		}

        if($cookie!=""){

			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		}else if($this->mycookie!=""){

			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->mycookie);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->mycookie);
		}

		if($this->myinterface!=""){

			curl_setopt($ch, CURLOPT_INTERFACE, $this->myinterface);
		}		

		if($proxy!=""){

			if(is_array($proxy)){

				$proxyType = $proxy['type']=='SOCKS5' ? CURLPROXY_SOCKS5 : CURLPROXY_HTTP;

				curl_setopt($ch, CURLOPT_PROXYTYPE, $proxyType);  
				curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']);  
				curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']);
			}else{

				$proxy_data = explode(":", $proxy);

				//echo $proxy_data[0]."]]][[[" . $proxy_data[1] . "<br>\n";
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);  
				curl_setopt($ch, CURLOPT_PROXY, $proxy_data[0]);
				curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_data[1]);
			}
		}

        if($compress!=""){

			curl_setopt($ch, CURLOPT_ENCODING , $compress);
		}

		if($this->referer_url!=""){

			curl_setopt($ch, CURLOPT_REFERER, $this->referer_url);
		}else if($this->history_url!=""){

			curl_setopt($ch, CURLOPT_REFERER, $this->history_url);
		}

        $string = curl_exec($ch);

		$last_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

		/*

        if ( curl_errno($ch) ) {

            return false;
        }

		*/

		$this->errno = curl_errno($ch);
		$this->error = curl_error($ch);

        curl_close($ch);

        $this->history_url = $last_url;

        return $string;
    }

	function saveToFile( $url, $local, $post_data=array(), $cookie="", $proxy="", $compress="", &$last_url="" ){

        $ch = curl_init();

		$this->error = "";
		$this->errno = "";

		if($this->force_ipv4){

			if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){

				curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
			}
		}

        $fh = fopen($local, 'w');		

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

		if(substr($url, 0, 8)=='https://'){

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

        //curl_setopt($ch, CURLOPT_FILE, $fh);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->_verbose);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_NOPROGRESS, $this->_progress);
        curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

		if(count($post_data)>0){

			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}

		if($this->myheader=="Expect"){

			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		}else if($this->myheader!=""){

			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->myheader);
		}

        if($cookie!=""){

			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		}else if($this->mycookie!=""){

			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->mycookie);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->mycookie);
		}

		if($this->myinterface!=""){

			curl_setopt($curlh, CURLOPT_INTERFACE, $this->myinterface);
		}

		if($proxy!=""){

			if(is_array($proxy)){

				$proxyType = $proxy['type']=='SOCKS5' ? CURLPROXY_SOCKS5 : CURLPROXY_HTTP;

				curl_setopt($ch, CURLOPT_PROXYTYPE, $proxyType);  
				curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']);  
				curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']);
			}else{

				$proxy_data = explode(":", $proxy);

				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);  
				curl_setopt($ch, CURLOPT_PROXY, $proxy_data[0]);
				curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_data[1]);
			}
		}

        if($compress!=""){

			curl_setopt($ch, CURLOPT_ENCODING , $compress);
		}

        $file_content = curl_exec($ch);

        $last_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		
		fwrite($fh, $file_content);
		
		/*

        if ( curl_errno($ch) ) {

            return false;

        }

		*/

		$this->errno = curl_errno($ch);
		$this->error = curl_error($ch);

        curl_close($ch);

        fclose($fh);

        if( filesize($local) > 0 ) {

            return true;
        }

        $history_url = $last_url;

        return false;
    }

    function getRemoteSize( $url, $cookie=NULL ){

        $ch = curl_init();

		if($this->force_ipv4){

			if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){

				curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
			}
		}

        curl_setopt($ch, CURLOPT_URL, $url);

		if(substr($url, 0, 8)=='https://'){

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ( $cookie ) {

            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }

        $head   = curl_exec($ch);

        if ( curl_errno($ch) ) {

            return false;
        }

        curl_close($ch);

		$history_url = $last_url;

        $regex = '/Content-Length:\s([0-9].+?)\s/';

        $count = preg_match($regex, $head, $matches);

        return isset($matches['1']) ? $this->bytes($matches['1']) : 'unknown';
    }

    function getHeaderToString( $url, $post_data=array(), $cookie="", $proxy="", $compress="", &$last_url="" ){

        $ch = curl_init();

		$this->error = "";
		$this->errno = "";

		if($this->force_ipv4){

			if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){

				curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
			}
		}

        curl_setopt($ch, CURLOPT_URL, $url);

		if(substr($url, 0, 8)=='https://'){

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->_verbose);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_NOPROGRESS, $this->_progress);
        curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

		if($this->timeout!=0){

			curl_setopt($ch,CURLOPT_TIMEOUT, $this->timeout);
		}

		if(count($post_data)>0){

			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}

        if($this->myheader=="Expect"){

			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		}else if($this->myheader!=""){

			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->myheader);
		}

        if($cookie!=""){

			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		}else if($this->mycookie!=""){

			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->mycookie);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->mycookie);
		}

		if($this->myinterface!=""){

			curl_setopt($curlh, CURLOPT_INTERFACE, $this->myinterface);
		}

		if($proxy!=""){

			if(is_array($proxy)){

				$proxyType = $proxy['type']=='SOCKS5' ? CURLPROXY_SOCKS5 : CURLPROXY_HTTP;

				curl_setopt($ch, CURLOPT_PROXYTYPE, $proxyType);  
				curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']);  
				curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']);
			}else{

				$proxy_data = explode(":", $proxy);

				//echo $proxy_data[0]."]]][[[" . $proxy_data[1] . "<br>\n";

				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);  
				curl_setopt($ch, CURLOPT_PROXY, $proxy_data[0]);
				curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_data[1]);
			}
		}

        if($compress!=""){

			curl_setopt($ch, CURLOPT_ENCODING , $compress);
		}

		if($this->referer_url!=""){

			curl_setopt($ch, CURLOPT_REFERER, $this->referer_url);
		}else if($this->history_url!=""){

			curl_setopt($ch, CURLOPT_REFERER, $this->history_url);
		}

        $string = curl_exec($ch);

		$last_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

		/*

        if ( curl_errno($ch) ) {

            return false;

        }

		*/

		$this->errno = curl_errno($ch);
		$this->error = curl_error($ch);

        curl_close($ch);

        $this->history_url = $last_url;

        return $string;
    }



    function bytes($a) {

        $unim = array("B","KB","MB","GB","TB","PB");

        $c = 0;

        while ( $a >= 1024 ) {

            $c++;
            $a = $a/1024;
        }

        return number_format($a,($c ? 2 : 0),",",".")." ".$unim[$c];
    }
}
?>