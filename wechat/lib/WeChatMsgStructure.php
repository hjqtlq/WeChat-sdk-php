<?php
class WeChatMsgStructure
{
    public $toUserName;
    public $fromUserName;
    public $createTime;
    public $msgType;
    public $content;
    public $msgId;

    public function setAttributes($attributes)
    {
        foreach($attributes as $name => $value) {
            $name = strtoupper($name[0]);
            var_dump($name);continue;
            $this->{$name} = $value;
        }
    }

    public function set($key, $value)
    {
        $this->{$key} = $value;
    }

    public function __set($key, $value)
    {
        $key = strtolower($key{0}) . substr($key, 1, strlen($key));
        if(!property_exists($this, $key)) {
            throw new WeChatException('property not exists.');
        }
        $this->{$key} = $value;
    }

    public function __get($name)
    {
        if(!property_exists($this, $name)) {
            return null;
        }
        return $this->{$name};
    }

    public function __call($name, $args = array())
    {
        return $this->__get($name);
    }
}