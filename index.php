<?php
/*
    CopyRight 2014 All Rights Reserved
*/

#include 'baiduweather.php';

#header("Content-Type:text/html; charset=utf-8");
define("TOKEN", "pekingese");

require_once("mysql.php");
#init_sqlite();
$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();    
}

class wechatCallbackapiTest
{
    //check signature
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            header('content-type:text');
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $this->logger("R ".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

			$result = "";
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "image":
                    $result = $this->receiveImg($postObj);
                    break;					
            }
            $this->logger("T ".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

	private function receiveImg($object)
	{
		
	}
	
    private function receiveEvent($object)
    {
        switch ($object->Event)
        {
            case "subscribe":
                $resultStr = "Welcome!";
                break;
            case "unsubscribe":
                break;
            case "CLICK":
                require_once("mysql.php");
                set_appmode($object->EventKey);
                switch ($object->EventKey)
                {
                    case "company":
                        $contentStr[] = array("Title" =>"Company", 
                        "Description" =>"We will provide you ......", 
                        "PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", 
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;
                    case "translate":
                        $contentStr = "Switch to translation ".get_appmode();
                        break;
                    case "baiduweather":
                        $contentStr = "Swith to baidu whether report ".get_appmode();
                        break;                    
                    case "sinaweather":
                        $contentStr = "Swith to sina whether report ".get_appmode();
                        break;
                    case "cqssc":
					    include("lottery.php");
						$contentStr = getLotteryResult("cqssc.json");
                        break;
                    case "jstc7ws":
					    include("lottery.php");
						$contentStr = getLotteryResult("jstc7ws.json");
                        break;							
                    default:
                        $contentStr[] = array("Title" =>"Healty Force", 
                        "Description" =>"Kouzhao", 
                        "PicUrl" =>"http://wd.geilicdn.com/vshop859844288-1455904796839-152035.jpg",                         
                        "Url" =>"http://weidian.com/item.html?itemID=1736996295");
                        break;
                        $contentStr[] = array("Title" =>"default menu reply", 
                        "Description" =>"You are use default menu test", 
                        "PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", 
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;
                }
                break;
            default:
                break;            
        }

        if (is_array($contentStr)){
            $resultStr = $this->transmitNews($object, $contentStr);
        }else{
            $resultStr = $this->transmitText($object, $contentStr);
        }        
        return $resultStr;
    }

    private function receiveText($object)
    {
        include "mysql.php";
        
		$keyword = trim($object->Content);
        switch (get_appmode())
        {
            case "translate":
                include("translate.php");
                $content = translate($keyword);
                $result = $this->transmitText($object, $content);
                return $result;            
                break;     
            case "baiduweather":
                include("baiduwhether.php");
                $content = getWeatherInfo($keyword);
                // $result = $this->transmitText($object, count($content));
                $result = $this->transmitNews($object, $content);
                return $result;            
                break;     
            case "sinaweather":
                $url = "http://apix.sinaapp.com/weather/?appkey=".$object->ToUserName."&city=".urlencode($keyword); 
                $output = file_get_contents($url);
                $content = json_decode($output, true);
        
                $result = $this->transmitNews($object, $content);
                return $result;         
            default:
                $contentStr = "current application: ".get_appmode();
                $result = $this->transmitText($object, $contentStr);
                return $result;
                break;
        }
    }

    private function transmitText($object, $content)
    {
		if (!isset($content) || empty($content)){
			return "";
		}
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return "";
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[]]></Content>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";

        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    private function logger($log_content)
    {
      
    }
}
?>