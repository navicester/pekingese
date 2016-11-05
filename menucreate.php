<?php

$appid = "wxe90ebbe29377e650";
$appsecret = "d4624c36b6795d1d99dcf0547af5443d";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

$output = https_request($url);
$jsoninfo = json_decode($output, true);

$access_token = $jsoninfo["access_token"];
/*
{"access_token":"UForUdvQi-zwKW95v2ZYte3NKH6uVhzXXqsYO1EUb7NewnGlNL6uQAfMgT7CbQrl4eW-vhW51YZlb5insHZW6XltbvFNKsijT4DfEUqWi_IQBTaAJAZKC","expires_in":7200}
*/

$jsonmenu = '{
        "button":[
        {
            "name":"应用切换",
            "sub_button":[
            {
               "type":"click",
               "name":"中英翻译",
               "key":"translate"
            },
            {
               "type":"click",
               "name":"新浪天气预报",
               "key":"sinaweather"
            },
            {
               "type":"click",
               "name":"百度天气预报",
               "key":"baiduweather"
            },
            {
               "type":"click",
               "name":"小黄鸡",
               "key":"小黄鸡"
            },
            {
                "type":"click",
                "name":"脸部识别",
                "key":"脸部识别"
            }]
        },
        {
            "name":"彩票",
            "sub_button":[
            {
               "type":"click",
               "name":"彩票重庆时时彩",
               "key":"cqssc"
            },
            {
               "type":"click",
               "name":"江苏体彩7位数",
               "key":"jstc7ws"
            },
            {
                "type":"view",
                "name":"spare",
                "url":"http://m.hao123.com/a/tianqi"
            }]
      
        },
        {
            "name":"方倍工作室",
            "sub_button":[
            {
               "type":"click",
               "name":"公司简介",
               "key":"company"
            },
            {
               "type":"click",
               "name":"趣味游戏",
               "key":"游戏"
            },
            {
                "type":"click",
                "name":"讲个笑话",
                "key":"笑话"
            }]
        }]
}';

#$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$access_token;
$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$access_token;
$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$result = https_request($url, $jsonmenu);
var_dump($result);


function https_request($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

/*string(27) "{"errcode":0,"errmsg":"ok"}" 返回正确结果*/

?>