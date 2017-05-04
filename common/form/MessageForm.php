<?php

/**
 * Class ProtectForm
 * Protect info
 */
class MessageForm extends InitForm
{
  
    public function pushMessageToSingle($message, $target, $requstId, $appid, $appkey, $mastersecret, $host, $cid, $body, $title){
        //单推接口案例
        $igt = new IGeTui($host,$appkey,$mastersecret);

        //消息模版：
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        $template = $this->IGtTransmissionTemplateDemo($appid, $appkey, $body, $title);


        //定义"SingleMessage"
        $message = new IGtSingleMessage();

        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        // $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，2为4G/3G/2G，1为wifi推送，0为不限制推送
        //接收方
        $target = new IGtTarget();
        $target->set_appId($appid);
        $target->set_clientId($cid);
    //    $target->set_alias(Alias);

        try {
            $rep = $igt->pushMessageToSingle($message, $target, '', $appid, $appkey, $mastersecret, $host, $cid);
            var_dump($rep);
            echo ("<br><br>");

        }catch(RequestException $e){
            $requstId =e.getRequestId();
            //失败时重发
            $rep = $igt->pushMessageToSingle($message, $target,$requstId, $appid, $appkey, $mastersecret, $host, $cid);
            var_dump($rep);
            echo ("<br><br>");
        }
    }

    public function IGtTransmissionTemplateDemo($appid, $appkey, $body, $title){
        $template =  new IGtTransmissionTemplate();
        $template->set_appId($appid);//应用appid
        $template->set_appkey($appkey);//应用appkey
        $template->set_transmissionType(1);//透传消息类型
        $template->set_transmissionContent("新订单通知");//透传内容
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息

// 如下有两个推送模版，一个简单一个高级，可以互相切换使用。此处以高级为例，所以把简单模版注释掉。
//        APN简单推送
//      $apn = new IGtAPNPayload();
//      $alertmsg=new SimpleAlertMsg();
//      $alertmsg->alertMsg="";
//      $apn->alertMsg=$alertmsg;
//      $apn->badge=2;
//      $apn->sound="";
//      $apn->add_customMsg("payload","payload");
//      $apn->contentAvailable=1;
//      $apn->category="ACTIONABLE";
//      $template->set_apnInfo($apn);

//       APN高级推送
        $apn = new IGtAPNPayload();
        $alertmsg=new DictionaryAlertMsg();
        $alertmsg->body=$body;
        $alertmsg->actionLocKey=$title;
        $alertmsg->locKey=$body;
        $alertmsg->locArgs=array("locargs");
        $alertmsg->launchImage="launchimage";
//        IOS8.2 支持
        $alertmsg->title=$title;
        $alertmsg->titleLocKey=$body;
        $alertmsg->titleLocArgs=array("TitleLocArg");

        $apn->alertMsg=$alertmsg;
        $apn->badge=1;
        $apn->sound="";
        $apn->add_customMsg("payload","");
//      $apn->contentAvailable=1;
        $apn->category="ACTIONABLE";
        $template->set_apnInfo($apn);

        return $template;
    }
    

}
