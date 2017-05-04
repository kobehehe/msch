<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <title>登录</title>
    <link rel="stylesheet" type="text/css" href="css/base_cat.css" />
    <link rel="stylesheet" type="text/css" href="css/login.css" />
</head>

<body ng-app="ms" ng-controller="login">
    <div class="wapper">
        <!--logo-->
        <h1 class="logo clearfix">
            <img class="left" src="images/logo_h.png" alt="美思策划">
        </h1>
        <div class="container clearfix">
            <div class="img left">
                <img src="images/login_ad.jpg" alt="">
                <img src="images/ewm.jpg" alt="" class="QR_code">
            </div>
            <div class="form_box right">
                <h2><img src="images/logo_v.png"></h2>
                <form>
                    <div class="in_box clearfix">
                        <span class="icon"><img src="images/user_icon.png"></span>
                        <input type="text" name="" ng-model="mobile" ng-change="error=null" placeholder="手机号">
                    </div>
                    <div class="in_box clearfix">
                        <span class="icon"><img src="images/pwd_icon.png"></span>
                        <input class="password_input" type="password" name="" ng-model="password" ng-change="error=null" placeholder="密码">
                    </div>
                    <div class="notice_box">
                        <p class="notice" ng-show="error!=null"><span class="tag">i</span>{{error}}</p>
                    </div>
                    <button class="btn login_btn" type="button" ng-click="Login()">登 录</button>
                </form>
                <p class="info">没有账号？微信<a href="<?php echo $this->createUrl("background/weixin") ?>" class="fast_login">快速注册</a></p>
            </div>
        </div>
        
    </div>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/angular.js"></script>
    <script type="text/javascript" src="js/login_cat.js"></script>
</body>

</html> 