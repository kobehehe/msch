<?php
include "TopSdk.php";
//获取参数
$telephone = $_POST['telephone'];
$supplier_type = $_POST['supplier_type'];
$supplier_name = $_POST['supplier_name'];
$order_date = $_POST['order_date'];
$order_name = $_POST['order_name'];
$designer_name = $_POST['designer_name'];

$product = "美思";
$appkey = "23365214";
$secret = "2059843bfceda38bfcfe84565ea207b0";
$c = new TopClient;
$c->appkey = $appkey;
$c->secretKey = $secret;
$req = new AlibabaAliqinFcSmsNumSendRequest;
$req->setExtend("1");
$req->setSmsType("normal");
$req->setSmsFreeSignName("拒绝派单");
$req->setSmsParam("{supplier_type:'$supplier_type',supplier_name:'$supplier_name',order_date:'$order_date',order_name:'$order_name',designer_name:'$designer_name'}");
$req->setRecNum($telephone);
$req->setSmsTemplateCode("SMS_23805033");
$resp = $c->execute($req);
echo $code;


?>