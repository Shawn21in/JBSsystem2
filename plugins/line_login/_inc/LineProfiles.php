<?php
//namespace LittleChou\LineLogin;

//use LittleChou\LineLogin\Exceptions\LineAccessTokenNotFoundException;

class LineProfiles
{

    /**
     * @var ConfigManager
     */
    private $configManager;

    public function __construct(ConfigManager $configManager)
    {
        $this->configManager = $configManager;
    }

    /**
     * 取得用戶端 Profile
     *
     * @see https://developers.line.biz/en/docs/social-api/getting-user-profiles/
     * @param $code
     * @return bool|mixed|string
     * @throws LineAccessTokenNotFoundException
     */
    public function get($code)
    {
        $accessToken = self::getAccessToken($code);
        $headerData = [
            "content-type: application/x-www-form-urlencoded",
            "charset=UTF-8",
            'Authorization: Bearer ' . $accessToken,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerData);
        curl_setopt($ch, CURLOPT_URL, "https://api.line.me/v2/profile");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result);
        $result['accessToken2'] = $accessToken;
        return $result;
    }

    /**
     * 取得用戶端 Profile 已經有 $accessTokene
     *
     * @see https://developers.line.biz/en/docs/social-api/getting-user-profiles/
     * @param $code
     * @return bool|mixed|string
     * @throws LineAccessTokenNotFoundException
     */
    public function getLineProfile_access_token($accessToken)
    {
        
		$headerData = array(
			"content-type: application/x-www-form-urlencoded",
            "charset=UTF-8",
            "Authorization: Bearer " . $accessToken,
		);
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerData);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, "https://api.line.me/v2/profile");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result);
        return $result;
    }



    /**
     * 取得用戶端 Access Token
     *
     * @see https://developers.line.biz/en/docs/line-login/web/integrate-line-login/
     * @param $code
     * @return string
     * @throws LineAccessTokenNotFoundException
     */
    public function getAccessToken($code)
    {
        $config = $this->configManager->getConfigs();
        
        $url = "https://api.line.me/oauth2/v2.1/token";
		
		$query = array(
			'grant_type' 		=> "authorization_code",
			'client_id' 		=> $config[$this->configManager::CLIENT_ID],
			'code' 				=> $code,
			'client_secret' 	=> $config[$this->configManager::CLIENT_SECRET],
			'redirect_uri' 		=> $config[$this->configManager::CLIENT_REDIRECT_URI]
		);

        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: " . strlen(http_build_query($query)),
        );
		
        $context = array(
            "http" => array(
                "method"        => "POST",
                "header"        => implode("\r\n", $header),
                "content"       => http_build_query($query),
                "ignore_errors" => true,
            ),
        );
        //---------------------
        // id token を取得する
        //---------------------
		
        $res_json = file_get_contents($url, false, stream_context_create($context));
        $info = json_decode($res_json);
        
        if (empty($info->access_token)) {

            echo 'Can Not Find User Access Token';
        }
        return $info->access_token;
    }
}
