<?php
require dirname(__FILE__) . '/wechat/WeChatClient.php';

$trans = new WeChatXmlMsgTranslator();
$xml = '<xml>
 <ToUserName><![CDATA[toUser]]></ToUserName>
 <FromUserName><![CDATA[fromUser]]></FromUserName>
 <CreateTime>1348831860</CreateTime>
 <MsgType><![CDATA[image]]></MsgType>
 <PicUrl><![CDATA[this is a url]]></PicUrl>
 <MediaId><![CDATA[media_id]]></MediaId>
 <MsgId>1234567890123456</MsgId>
 </xml>';
$trans->translate($xml);

// menu test
// $menu = WeChatClient::menu();

// $oAuth = WeChatClient::auth();
// $button = $menu->createButton('最新发布', WeChatMenuButton::TYPE_VIEW, $oAuth->getOAuthRedirectUrl('http://115.29.185.48:10002/member/publish/last/'));
// $menu->addButton($button);

// $button = $menu->createButton('活动管理', WeChatMenuButton::TYPE_VIEW, '');
// $button->addSubButton($menu->createButton('我的活动', WeChatMenuButton::TYPE_VIEW, $oAuth->getOAuthRedirectUrl('http://115.29.185.48:10002/member/publish/list/')));
// $menu->addButton($button);



// $button = $menu->createButton('更多管理', WeChatMenuButton::TYPE_VIEW, 'http://www.wevent.cn/');
// $button->addSubButton($menu->createButton('绑定账号', WeChatMenuButton::TYPE_VIEW, $oAuth->getOAuthRedirectUrl('http://115.29.185.48:10002/member/wechat/bind/')));
// $button->addSubButton($menu->createButton('解除绑定', WeChatMenuButton::TYPE_VIEW, $oAuth->getOAuthRedirectUrl('http://115.29.185.48:10002/member/wechat/bind/')));
// $button->addSubButton($menu->createButton('更换绑定账号', WeChatMenuButton::TYPE_VIEW, $oAuth->getOAuthRedirectUrl('http://115.29.185.48:10002/member/wechat/binded/')));
// $menu->addButton($button);
// echo '<pre>';
// print_r($menu);
// print_r($menu->create());