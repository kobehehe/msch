<?php
include "TopSdk.php";
// $post = json_decode(file_get_contents('php://input'));
$telephone = $_POST['telephone'];
// $telephone = "18611323194";
$code = rand(100000, 999999);
$product = "婚礼策划师";
$appkey = "23365214";
$secret = "2059843bfceda38bfcfe84565ea207b0";
$c = new TopClient;
$c->appkey = $appkey;
$c->secretKey = $secret;
$req = new AlibabaAliqinFcSmsNumSendRequest;
$req->setExtend("1");
$req->setSmsType("normal");
// $req->setSmsFreeSignName("注册验证");
$req->setSmsFreeSignName("美思");
$req->setSmsParam("{code:'$code',product:'$product'}");
// $req->setSmsParam("{code:'$code'");
$req->setRecNum($telephone);
// $req->setSmsTemplateCode("SMS_9445074");
$req->setSmsTemplateCode("SMS_60770274");
$resp = $c->execute($req);
// echo $resp;
// print_r($_POST);

// print_r($_POST);



    // $httpdns = new HttpdnsGetRequest;
    // $client = new ClusterTopClient("4272","0ebbcccfee18d7ad1aebc5b135ffa906");
    // $client->gatewayUrl = "http://api.daily.taobao.net/router/rest";
    // var_dump($client->execute($httpdns,"6100e23657fb0b2d0c78568e55a3031134be9a3a5d4b3a365753805"));

?>