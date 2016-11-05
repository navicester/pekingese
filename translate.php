<?php
/*
    方倍工作室
    CopyRight 2014 All Rights Reserved


header("Content-Type:text/html; charset=utf-8");
define("TOKEN", "pekingese");

     
$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();    
}


function logger($log_content)
{
  
}
*/

function translate($keyword)
{
	$url = "http://fanyi.youdao.com/openapi.do?keyfrom=pekingese&key=1351788821&type=data&doctype=json&version=1.1&q=".urlencode($keyword);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	
	$youdaoResult = json_decode($output, true);
	$errorcode = $youdaoResult['errorCode'];
	
	if(isset($errorcode) && $errorcode != 0){
		return "translation failure error code:".$errorcode;
	}
	else{
		$translation = $youdaoResult['translation'][0]."\n".$youdaoResult['basic']['phonetic']."\n";
		foreach ($youdaoResult['basic']['explains'] as $value){
	        $translation .= $value."; ";
	    }
		return $translation;
	}
}
/*

http://fanyi.youdao.com/openapi.do?keyfrom=pekingese&key=1351788821&type=data&doctype=json&version=1.1&q=good
{
	"translation":["好"],
	"basic":
	{
	    "us-phonetic":"ɡʊd",
		"phonetic":"gʊd",
		"uk-phonetic":"gʊd",
		"explains":
		[
		    "n. 好处；善行；慷慨的行为",
			"adj. 好的；优良的；愉快的；虔诚的",
			"adv. 好","n. (Good)人名；(英)古德；(瑞典)戈德"
		]
	},
	"query":"good",
	"errorCode":0,
	"web":
	[
	    {
		    "value":["好","善","商品"],
			"key":"GOOD"
		},
		{
		    "value":["耶稣受难节","耶稣受难日","受难节"],
			"key":"Good Friday"
		},
		{
		    "value":["위키백과 동음이의어 문서","Good Time","Good Time"],
			"key":"Good Time"
		}
	]
}
*/

/*
class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature()){
            header('content-type:text');			
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        logger("R ".$postStr);
        //extract post data
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "text":
                    $resultStr = $this->receiveText($postObj);
                    break;
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                default:
                    $resultStr = "unknow msg type: ".$RX_TYPE;
                    break;
            }
            logger("T ".$resultStr);
            echo $resultStr;
        }else {
            echo "";
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    private function receiveText($object)
    {
        $funcFlag = 0;
        $keyword = trim($object->Content);
        $resultStr = "";
        $cityArray = array();
        $contentStr = "";
        $needArray = false;
        $illegal = false;
        $saytome = false;
        
        if (1 == 1){
            $contentStr = translate($keyword);
            $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
            return $resultStr;
        }
        //Content 消息内容，大小限制在2048字节，字段为空为不合法请求
        return $resultStr;
    }
    
    private function receiveEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = "请直说，我将为你自动翻译";
                break;
            case "unsubscribe":
                $contentStr = "";
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    default:
                        $contentStr = "receive a eventkey: ".$object->EventKey;
                        break;
                }
                break;
            default:
                $contentStr = "receive a new event: ".$object->Event;
                break;
        }
        $resultStr = $this->transmitText($object, $contentStr);
        return $resultStr;
    }
    
    private function transmitText($object, $content, $flag = 0)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>%d</FuncFlag>
</xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }
}*/
?>