<?php
/**
 * @example
 * <pre>$menu = new WeChatMenuComponent();
 * $button = $menu->createButton('最新发布', WeChatMenuButton::TYPE_VIEW, 'http://www.wevent.cn/');
 * $menu->addButton($button);
 *
 * $button = $menu->createButton('活动管理', WeChatMenuButton::TYPE_VIEW, 'http://www.wevent.cn/');
 * $button->addSubButton($menu->createButton('我的活动', WeChatMenuButton::TYPE_VIEW, 'http://www.wevent.cn/'));
 * $menu->addButton($button);
 *
 * print_r($menu->create());</pre>
 *
 * @author T.L.Q. <email:hjq_tlq@163.com>
 * @version 1.0.0
 */
class WeChatMenuClient
{
    public $buttons = array();

    public function createButton($name, $type, $action)
    {
        return new WeChatMenuButton($name, $type, $action);
    }

    public function addButton(WeChatMenuButton $button)
    {
        $this->buttons[] = $button;
    }

    public function create()
    {
        $url = WECHAT_API_DOMAIN . 'menu/create?access_token=' . WeChatClient::auth()->getToken();
        return WeChatHttp::post($url, json_encode(array('button' => $this->buttons), JSON_UNESCAPED_UNICODE));
    }


//     public function __construct()
//     {
//         $url = WECHAT_API_DOMAIN . 'menu/create?access_token=' . WeChatClient::auth()->getToken();
//         $buttons = array();
//         $button = new WeChatMenuButton('最新发布', WeChatMenuButton::TYPE_VIEW, 'http://115.29.185.48:10002/member/publish/last/');
//         $buttons['button'][] = $button;

//         $button = new WeChatMenuButton('活动管理', WeChatMenuButton::TYPE_VIEW, '');
//         $button->addSubButton(new WeChatMenuButton('我的活动', WeChatMenuButton::TYPE_VIEW, 'http://115.29.185.48:10002/member/publish/list/'));
//         $buttons['button'][] = $button;

//         $button = new WeChatMenuButton('更多管理', WeChatMenuButton::TYPE_VIEW, '');
//         $button->addSubButton(new WeChatMenuButton('绑定账号', WeChatMenuButton::TYPE_VIEW, 'http://115.29.185.48:10002/member/wechat/bind/'));
//         $button->addSubButton(new WeChatMenuButton('解除绑定', WeChatMenuButton::TYPE_VIEW, 'http://115.29.185.48:10002/member/wechat/unbind/'));
//         $button->addSubButton(new WeChatMenuButton('更换绑定账号', WeChatMenuButton::TYPE_VIEW, 'http://115.29.185.48:10002/member/wechat/binded/'));
//         $buttons['button'][] = $button;
//         echo '<pre>';
//         echo json_encode($buttons, JSON_UNESCAPED_UNICODE);
//         $res = WeChatHttp::post($url, json_encode($buttons, JSON_UNESCAPED_UNICODE));
//         print_r($res);
//     }
}

class WeChatMenuButton
{
    const TYPE_CLICK = 'click';
    const TYPE_VIEW = 'view';

    public $type;
    public $name;
    public $key;
    public $url;
    public $sub_button = null;

    public function __construct($name, $type, $action, $subButton = null)
    {
        $this->init($name, $type, $action, $subButton);
    }

    private $_buttonArray = array();

    public function getButton()
    {
        $this->_buttonArray = array();
        $this->_buttonArray['name'] = $this->name;
        $this->_buttonArray['type'] = $this->type;
        if($this->type === self::TYPE_CLICK) {
            $this->_buttonArray['key'] = $this->key;
        } else {
            $this->_buttonArray['url'] = $this->url;
        }
        if(!empty($this->sub_button)) {
            foreach($this->sub_button as $button) {
                $this->_buttonArray['sub_button'][] = $button->getButton();
            }
        }
        var_dump($this->_buttonArray);
    }

    public function __toString()
    {
        if($this->type === self::TYPE_CLICK) {
            unset($this->url);
        } else {
            unset($this->key);
        }
        if(empty($this->sub_button)) {
            unset($this->sub_button);
        } else {

        }
        return json_encode($this);
    }

    public function init($name, $type, $action, $subButton = null)
    {
        $this->setName($name);
        $this->setType($type);
        if($type === self::TYPE_CLICK) {
            $this->setKey($action);
        } else {
            $this->setUrl($action);
        }
        $this->setSubButton($subButton);
    }

//     public function __toString()
//     {
//         if($this->type === self::TYPE_CLICK) {
//             unset($this->url);
//         } else if($this->type === self::TYPE_VIEW) {
//             unset($this->key);
//         }
//         return parent::__toString();
//     }

    /**
     * @return the $type
     */
    public function getType ()
    {
        return $this->type;
    }

	/**
     * @param field_type $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

	/**
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
    }

	/**
     * @param field_type $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

	/**
     * @return the $key
     */
    public function getKey()
    {
        return $this->key;
    }

	/**
     * @param field_type $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

	/**
     * @return the $url
     */
    public function getUrl()
    {
        return $this->url;
    }

	/**
     * @param field_type $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

	/**
     * @return the $subButton
     */
    public function getSubButton()
    {
        return $this->sub_button;
    }

	/**
     * @param WeCahtMenuButton $subButton
     */
    public function setSubButton($subButton)
    {
        $this->sub_button = $subButton;
    }

    public function addSubButton(WeChatMenuButton $button)
    {
        $this->sub_button[] = $button;
    }
}