<?php
/**
 * 微信企业号推送消息类
 * @author lixiuran
 */
class wechat
{
    private    $api = '';
    private    $corpid = '';
    private    $corpsecret = '';

    public  function __construct()
    {
        $this->api = 'https://qyapi.weixin.qq.com/cgi-bin';
        $this->corpid = 'wx7fb625b033694d41';
        $this->corpsecret ='KiiETrsU4GAsNilvXR5xsQxrD4S8vZg4ym-2OinC6Bsn0FhGSykyO55A1gN51tx2';

    }

    public  function sendMsg($check_id,$content,$type)
    {
        if(empty($check_id) || empty($content) || empty($type)){
            return false;
        }
        $api_url = $this->api.'/message/send?access_token='.$this->_getToken();
        
        if ($type == 'u') {
            $user_id  = $check_id;
        }
        if ($type == 'g') {
            $party_id = $check_id;
        }

        $data = array(
            'msgtype'=>'text',
            'touser' =>$user_id,
            "toparty"=>$party_id,
            'agentid'=>1,
            'text'=>array(
                'content'=>urlencode($content)
            )
        );
        $json_data = json_encode($data);
        $json_data = urldecode($json_data);
        $ret = $this->curl_post($api_url, $json_data);
        echo $ret."\n";
    }

    private   function  _getToken()
    {
        $url = $this->api.'/gettoken?corpid='.$this->corpid.'&corpsecret='.$this->corpsecret;
        $access_token = $this->curl_get($url);
        $access_token = json_decode($access_token,true);
        return $access_token['access_token'];
    }

    /**
     * post请求封装
     * @param string $url        请求链接
     * @param mixed  $data       post数据
     * @param int    $timeout_ms 超时，毫秒
     * @param array  $header     header，数组形式
     * @return boolean|mixed
     */
    private  function curl_post($url, $data, $timeout_ms = 3000, $header = array())
    {
        $ch = curl_init();
        // $post_fields = http_build_query($data,'&');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "server curl");
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout_ms);
        if (!empty($header) && is_array($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        if(curl_errno($ch)){
            return false;
        }
        curl_close($ch);
        return $result;
    }

    /**
     *
     * @param string $url        请求链接
     * @param int    $timeout_ms 超时时间毫秒
     * @param array  $header     header 数组形式 例： array('Host : xxx.ezubo.com')
     * @return boolean|mixed
     */
    private  function curl_get($url, $timeout_ms = 2000, $header = array())
    {
        if (empty($header)) {
            $header = array('Connection: Keep-Alive','Content-type: application/x-www-form-urlencoded;charset=UTF-8');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "server curl");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout_ms);
        if(! empty($header) && is_array($header)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $result = curl_exec($ch);
        $curl_info = curl_getinfo($ch);

        if(curl_errno($ch)){
               return false;
        }
        curl_close($ch);
        return $result;
    }

}

$options = getopt("u:g:c:");
// var_dump($options,$argv);die;

error_reporting(E_ALL ^ E_NOTICE);

if (!$options['c']) {
     die("消息内容必填, 用法：/path/to/php -u <微信ID> -g <组ID,多个用逗号分割> －c <消息内容> \n");
}

$wechat = new wechat();

if (!empty($options['u'])) {
    $wechat->sendMsg($options['u'],$options['c'],'u');die;
}

if (!empty($options['g'])) {
    $wechat->sendMsg($options['g'],$options['c'],'g');die;
}


