<?php
class WeChatXmlMsgTranslator
{
    public function __construct()
    {
        if(!function_exists('simplexml_load_string')) {
            throw new WeChatException('simplexml is needed.');
        }
    }

    public function translate($xml)
    {
        echo '<pre>';
        $xml = 'http://v.zjol.com.cn/play/71477.xml';
        $xml = trim($xml);
        var_dump(strpos($xml, '<'));

        if(0 === strpos($xml, '<')) {
            $element = simplexml_load_string($xml);
        } else {
            $element = simplexml_load_file($xml);
        }
var_dump($element);
exit();
        if(false === $element) {
            throw new WeChatException('input is not a xml file.');
        }
        $res = self::createStructure(strval($element->MsgType));
        foreach($element->children() as $child) {
            $res->set($child->getName(), strval($child));
        }
        var_dump($res);
    }

    /**
     * @param string $type
     * @throws WeChatException
     * @return WeChatMsgStructure
     */
    public static function createStructure($type)
    {
        $class = 'WeChat' . ucfirst(strtolower($type)) . 'MsgStructure';
        if(!class_exists($class)) {
            require_once WECHAT_ROOT . '/lib/structure/' . $class . '.php';
            if(!class_exists($class)) {
                throw new WeChatException('message structure not exists.');
            }
        }
        return new $class;
    }
}