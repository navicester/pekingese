<?php


function getLotteryResult($keyword)
{
	$url = "http://f.apiplus.cn/".urlencode($keyword);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	
	$lotteryResult = json_decode($output, true);
	$errorcode = $lotteryResult['errorCode'];
	
	if(isset($errorcode) && $errorcode != 0){
		return "translation failure error code:".$errorcode;
	}
	else{
	/*
		$translation = "code ".$lotteryResult['code']."\n";
		
		foreach ($lotteryResult['data'] as $value){
	        $translation .= "opentime at ".$value['opentime']." opencode is ".$value['opencode']."\n";
	    }
		return $translation;
	*/	
		$lotteryArray[] = array(
		        "Title" => $lotteryResult['code']."重庆时时彩-彩票预报", 
				"Description" =>"", 
				"PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", 
				"Url" =>""); 
		for ($i = 0; $i < count($lotteryResult['data']); $i++) {
			$lotteryArray[] = array("Title"=>
				"时间 ".$lotteryResult['data'][$i]["opentime"]."\n".
				"结果 ".$lotteryResult['data'][$i]["opencode"]."",
			"Description"=>"", 
			"PicUrl"=> "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", 
			"Url"=>"");
		}
		return $lotteryArray;		
	}
}
						
/*

http://f.apiplus.cn/cqssc.json (重庆时时彩json格式5行utf-8编码)

{
    "rows":5,
	"code":"cqssc",
	"info":"免费接口随机延迟3-5分钟，实时接口请访问opencai.net或QQ:23081452(注明彩票或API)",
	"data":[
		{
			"expect":"20160111073",
			"opencode":"9,7,0,8,5",
			"opentime":"2016-01-11 18:10:40",
			"opentimestamp":1452507040},
		{
			"expect":"20160111072",
			"opencode":"9,9,4,1,8",
			"opentime":"2016-01-11 18:00:40",
			"opentimestamp":1452506440},
		{
			"expect":"20160111071",
			"opencode":"0,0,8,2,3",
			"opentime":"2016-01-11 17:50:40",
			"opentimestamp":1452505840},
		{
			"expect":"20160111070",
			"opencode":"9,2,9,6,1",
			"opentime":"2016-01-11 17:40:40",
			"opentimestamp":1452505240},
		{
			"expect":"20160111069",
			"opencode":"0,4,5,6,2",
			"opentime":"2016-01-11 17:30:40",
			"opentimestamp":1452504640}
		]
}

*/

?>
