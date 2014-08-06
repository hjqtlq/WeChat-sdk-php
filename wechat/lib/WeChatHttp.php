<?php
class WeChatHttp
{
    private static $_try = 3;

    public static function get($url, $params = array())
    {
        if(!empty($params)) {
            $paramsStr = http_build_query($params);
            if(!empty($paramsStr)) {
                $url .= false === strpos('?', $url) ? '?' : '&';
                $url .= $paramsStr;
            }
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        $err = curl_errno($ch);
        curl_close($ch);
        if($err) {
            throw new WeChatException('get url failure : ' . $url, $err);
        }
        $objResponse = json_decode($response);
        if(!$objResponse) {
            throw new WeChatException('response is not a json ' . $response);
        }
        if(isset($objResponse->errcode)) {
            throw new WeChatException($response->errmsg,$response->errcode);
        }
        return $objResponse;
    }

    public static function post($url, $data)
    {
        $ch = curl_init(); // 初始化curl
        if(is_array($data)) { $data = http_build_query($data); }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0); // 设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); // post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($ch); // 运行curl
        $err = curl_errno($ch);
        curl_close($ch);
        if($err) {
            throw new WeChatException('post url failure : ' . $url, $err);
        }
        $objResponse = json_decode($response);
        if(!$objResponse) {
            throw new WeChatException('response is not a json ' . $response);
        }
        if(isset($objResponse->errcode) && !empty($objResponse->errcode)) {
            throw new WeChatException($objResponse->errmsg,$objResponse->errcode);
        }
        return $objResponse;
    }

    function upload($url, $filePath)
    {
        if(stripos('http', $filePath)) {

        }
        $file = realpath($filePath); //要上传的文件
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('media' => '@' . $file));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    function download($url, $savePath)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0); // 对body进行输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);

        curl_close($ch);
        $media = array_merge(array('mediaBody' => $package), $httpinfo);

        //求出文件格式
        preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
        $fileExt = $extmatches[1];

        $filename = uniqid(). '.' . $fileExt;
        $dirname = "./wximages/";
        if(!file_exists($dirname)){
            mkdir($dirname,0777,true);
        }
        file_put_contents($dirname.$filename,$media['mediaBody']);
        return $dirname.$filename;
    }

    public function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $tmpArr = array(WECHAT_TOKEN, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        return $tmpStr === $signature;
    }
}