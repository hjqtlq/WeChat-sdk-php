<?php
class WeChatUserClient
{

    /**
     * 获得用户信息
     * @param unknown $openId  普通用户的标识，对当前公众号唯一
     * @param string $lang zh_CN 简体，zh_TW 繁体，en 英语
     * @return <pre>stdClass :
     * - subscribe 用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。
     * - openid 用户的标识，对当前公众号唯一
     * - nickname 用户的昵称
     * - sex 用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
     * - city 用户所在城市
     * - country 用户所在国家
     * - province 用户所在省份
     * - language 用户的语言，简体中文为zh_CN
     * - headimgurl 用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空
     * - subscribe_time 用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间
     * - unionid 只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。详见：获取用户个人信息（UnionID机制）
     * </pre>
     */
    public function getInfo($openId, $lang = 'zh_CN')
    {
        if(empty($openId)) {
            throw new WeChatException('Open id is empty');
        }
        $token = WeChatClient::auth()->getToken();
        $url = WECHAT_API_DOMAIN . "/user/info?access_token={$token}&openid={$openId}&lang=zh_CN";
        return WeChatHttp::get($url);
    }

    public function getFans($openId, $nextOpenId = '')
    {
        if(empty($openId)) {
            throw new WeChatException('Open id is empty');
        }
        $token = WeChatClient::auth()->getToken();
        $url = WECHAT_API_DOMAIN . 'user/get';
        $params = array('access_token' => WeChatClient::auth()->getToken());
        if(!empty($nextOpenId)) {
            $params['next_openid'] = $nextOpenId;
        }
        return WeChatHttp::get($url, $params);
    }
}