<?php
class WeChatMessageClient
{
    const MESSAGE_TYPE_TEXT = 'text';
    const MESSAGE_TYPE_IMAGE = 'image';
    const MESSAGE_TYPE_VOICE = 'voice';
    const MESSAGE_TYPE_VIDEO = 'video';
    const MESSAGE_TYPE_MUSIC = 'music';
    const MESSAGE_TYPE_NEWS = 'news';

    public function send($data)
    {
        if(!is_string($data)) {
            $data = json_encode($data);
        }
        $token = WeChatClient::auth()->getToken();
        $url = 'message/custom/send?access_token=' . $token;
        return WeChatHttp::post($url, $data);
    }

    public function sendText($openId, $content)
    {
        if(empty($openId)) {
            throw new WeChatException('Open id is empty');
        }
        if(empty($content)) {
            throw new WeChatException('Content is empty');
        }
        $data = array(
        	'touser' => $openId,
            'msgtype' => self::MESSAGE_TYPE_TEXT,
            'text' => array('content' => strval($content))
        );
        $this->send($data);
    }

    public function sendImage($openId, $mediaId)
    {
        if(empty($openId)) {
            throw new WeChatException('Open id is empty');
        }
        if(empty($mediaId)) {
            throw new WeChatException('Media id is empty');
        }
        $data = array(
            'touser' => $openId,
            'msgtype' => self::MESSAGE_TYPE_IMAGE,
            'image' => array('media_id' => strval($mediaId))
        );
        $this->send($data);
    }

    public function sendVoice($openId, $mediaId)
    {
        if(empty($openId)) {
            throw new WeChatException('Open id is empty');
        }
        if(empty($mediaId)) {
            throw new WeChatException('Media id is empty');
        }
        $data = array(
            'touser' => $openId,
            'msgtype' => self::MESSAGE_TYPE_VOICE,
            'voice' => array('media_id' => strval($mediaId))
        );
        $this->send($data);
    }

    public function sendVideo($openId, $mediaId, $title, $description)
    {
        if(empty($openId)) {
            throw new WeChatException('Open id is empty');
        }
        if(empty($mediaId)) {
            throw new WeChatException('Media id is empty');
        }
        $data = array(
            'touser' => $openId,
            'msgtype' => self::MESSAGE_TYPE_TEXT,
            'video' => array(
                'media_id' => strval($mediaId),
                'title' => strval($title),
                'description' => strval($description)
            )
        );
        $this->send($data);
    }

    public function sendMusic($openId, $mediaId, $title, $description, $musicUrl, $hqMusicurl, $thumbMediaId)
    {
        if(empty($openId)) {
            throw new WeChatException('Open id is empty');
        }
        if(empty($mediaId)) {
            throw new WeChatException('Media id is empty');
        }
        $data = array(
            'touser' => $openId,
            'msgtype' => self::MESSAGE_TYPE_MUSIC,
            'music' => array(
                'media_id' => strval($mediaId),
                'title' => strval($title),
                'description' => strval($description),
                'musicurl' => strval($musicUrl),
                'hqmusicurl' => strval($hqMusicurl),
                'thumb_media_id' => $thumbMediaId
            )
        );
        $this->send($data);
    }
}