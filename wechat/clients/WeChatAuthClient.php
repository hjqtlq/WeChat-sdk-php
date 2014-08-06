<?php
class WeChatAuthClient
{
    const OAUTH_SCOPE_BASE = 'snsapi_base';
    const OAUTH_SCOPE_USER_INFO = 'snsapi_userinfo';

    public $accessToken;
    public function getToken($forceReflash = false)
    {
        if(empty($this->accessToken) || $forceReflash) {
            $url = WECHAT_API_DOMAIN . 'token';
            $params = array(
            	'grant_type' => 'client_credential',
                'appid' => WECHAT_APP_ID,
                'secret' => WECHAT_APP_SECRET
            );
            $info = WeChatHttp::get($url, $params);
            if(isset($info->access_token)) {
                $this->accessToken = $info->access_token;
                return $this->accessToken;
            }
            return false;
        }
    }

    public function getAccessInfoByCode($code)
    {
        if(empty($code)) {
            throw new WeChatException('code empty');
        }
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $params = array(
        	'appid' => WECHAT_APP_ID,
            'secret' => WECHAT_APP_SECRET,
            'code' => $code,
            'grant_type' => 'authorization_code'
        );
        return WeChatHttp::get($url, $params);
    }

    public function refreshToken($refreshToken)
    {
        if(empty($refreshToken)) {
            throw new WeChatException('refresh token empty');
        }
        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
        $params = array(
            'appid' => WECHAT_APP_ID,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken
        );
    }

    public function getOAuthRedirectUrl($url, $state = '', $scope = self::OAUTH_SCOPE_BASE)
    {
        return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . WECHAT_APP_ID
            . '&redirect_uri=' . urlencode(strval($url))
            . '&state=' . strval($state)
            . '&scope=' . strval($scope)
            . '&response_type=code'
            . '#wechat_redirect';
    }
}