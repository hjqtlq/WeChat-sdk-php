<?php
define('WECHAT_ROOT', dirname(__FILE__));
require_once WECHAT_ROOT . '/config.php';
require_once WECHAT_ROOT . '/lib/WeChatException.php';
require_once WECHAT_ROOT . '/lib/WeChatHttp.php';
require_once WECHAT_ROOT . '/lib/WeChatXmlMsgTranslator.php';
require_once WECHAT_ROOT . '/lib/WeChatMsgStructure.php';
class WeChatClient
{
    public static $APP_ID;
    public static $APP_SECRET;

    private static $_clientMap = array();

    public function __get($name)
    {
        return self::clientCreater($name);
    }

    public static function __callstatic($name, $args = array())
    {
        return self::clientCreater($name);
    }

    public static function clientCreater($class, $singleton = true)
    {
        $class = 'WeChat' . ucfirst($class) . 'Client';
        if(isset(self::$_clientMap[$class])) {
            return $singleton ? self::$_clientMap[$class] : new $class;
        }
        if(!class_exists($class)) {
            require dirname(__FILE__) . '/clients/' . $class . '.php';
            if(!class_exists($class)) {
                throw new WeChatException('Client ' . $class . ' not exists.');
            }
        }
        $obj = new $class;
        if($singleton) {
            self::$_clientMap[$class] = $obj;
        }
        return $obj;
    }
}