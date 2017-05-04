<?php
include "TopSdk.php";
// $post = json_decode(file_get_contents('php://input'));
//获取参数
$telephone = $_POST['telephone'];
$name = $_POST['name'];
$time = $_POST['time'];
$st = $_POST['st'];
$sn = $_POST['sn'];

// $telephone = "18611323194";
$product = "美思";
$appkey = "23365214";
$secret = "2059843bfceda38bfcfe84565ea207b0";
$c = new TopClient;
$c->appkey = $appkey;
$c->secretKey = $secret;
$req = new AlibabaAliqinFcSmsNumSendRequest;
$req->setExtend("1");
$req->setSmsType("normal");
$req->setSmsFreeSignName("审批申请");
$req->setSmsParam("{name:'$name',time:'$time',st:'$st',sn:'$sn'}");
$req->setRecNum($telephone);
$req->setSmsTemplateCode("SMS_15495639");
$resp = $c->execute($req);
echo $code;
// print_r($_POST);

// print_r($_POST);



    // $httpdns = new HttpdnsGetRequest;
    // $client = new ClusterTopClient("4272","0ebbcccfee18d7ad1aebc5b135ffa906");
    // $client->gatewayUrl = "http://api.daily.taobao.net/router/rest";
    // var_dump($client->execute($httpdns,"6100e23657fb0b2d0c78568e55a3031134be9a3a5d4b3a365753805"));

?>