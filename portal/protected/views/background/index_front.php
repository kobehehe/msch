<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <title>首页</title>
    <link rel="stylesheet" type="text/css" href="css/base3.css" />
    <link rel="stylesheet" type="text/css" href="css/kort.css" />
    <link rel="stylesheet" type="text/css" href="css/index_front.css" />
</head>

<body>
    <div class="container" id="container">
        <div class="mask"></div>
        <div class="index_wrapper rel">
            <div class="yun abs"><img class="img_block" src="images/yun.png" alt=""></div>
            <div class="taiyang abs"><img class="img_block" src="images/taiyang.png" alt=""></div>
            <!--导航-->
            <div class="nav_box clearfix">
                <ul class="nav_list left clearfix" >
                    <li action="index_front"><a>首页</a></li>
                    <li action='library'><a>灵感库</a></li>
                    <li action='selected'><a>精选库</a></li>
                    <!-- <li action='share_store'><a>共享库房</a></li> -->
                    <li class="has_more">
                        <a href="javasctipt:;">创意集市</a>
                        <ul class="nav_down_list">
                            <li class="icon al_icon" action="case"><i></i><a>案例商城</a></li>
                            <li class="icon dj_icon" action="prop"><i></i><a>道具商城</a></li>
                            <li class="icon xgt_icon" action="drawing"><i></i><a>效果图商城</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="login_box right">
                    <!-- 未登录 -->
                    <a class="no_login">登录</a>
                    <!--已登录-->
                    <div class="logined clearfix hide">
                        <div class="tx_box left">
                            <img src="images/member.png" alt="">
                        </div>
                        <p class="user_name left">小红</p>
                    </div>
                    <ul class="nav_down_list">
                        <li class="icon yh_icon" action="buyVIP"><i></i><a>升级VIP帐号</a></li>
                        <li class="icon m_icon hide" action="background"><i></i><a>我的美思</a></li>
                        <li class="icon sz_icon hide" action="supplier"><i></i><a>美思供应商</a></li>
                        <li class="icon tc_icon" action="exit"><i></i><a>退出</a></li>
                    </ul>
                </div>
            </div>
            <!--三只小猫-->
            <div class="index_content">
                <!--左边小猫-->
                <div class="cat cat_left PathInner" id="cat01" data-flag="0" onmouseleave="PathRun($('#cat01'),1,1);">
                    <div class="PathMain">
                        <div class="Tmain" onmouseenter="PathRun($('#cat01'),1,0);"></div>
                    </div>
                    <div class="cat_left_item cat_left_item01 PathItem" pre-type='2'>
                        <a class="link" href="javascript:;">
                            <span class="item"></span>
                        </a>
                    </div>
                    <div class="cat_left_item cat_left_item02 PathItem" pre-type='1'>
                        <a class="link" href="javascript:;">
                            <span class="item"></span>
                        </a>
                    </div>
                </div>
                <!--中间小猫-->
                <div class="cat cat_center PathInner" id="cat02" data-flag="0" onmouseleave="PathRun($('#cat02'),2,1);">
                    <div class="PathMain">
                        <div class="Tmain" onmouseenter="PathRun($('#cat02'),2,0);"></div>
                    </div>
                    <div class="cat_center_item cat_center_item01 PathItem" pre-type='3'>
                        <a class="link" href="javascript:;">
                            <span class="item"></span>
                        </a>
                    </div>
                    <div class="cat_center_item cat_center_item02 PathItem" pre-type='4'>
                        <a class="link" href="javascript:;">
                            <span class="item"></span>
                        </a>
                    </div>
                </div>
                <!--右边小猫-->
                <div class="cat cat_right PathInner" id="cat03" data-flag="0" onmouseleave="PathRun($('#cat03'),3,1);">
                    <div class="PathMain">
                        <div class="Tmain" onmouseenter="PathRun($('#cat03'),3,0);"></div>
                    </div>
                    <div class="cat_right_item cat_right_item01 PathItem" id="example" pre-type='' order-id=''>
                        <a class="link" href="javascript:;">
                            <span class="item"></span>
                        </a>
                    </div>
                    <div class="cat_right_item cat_right_item02 PathItem" id="print" pre-type='' order-id=''>
                        <a class="link" href="javascript:;">
                            <span class="item"></span>
                        </a>
                    </div>
                </div>
                <!--logo区域  两种状态-->
                <div class="logo_area">
                    <!-- logo状态 -->
                    <h1 class="logo " id="logo_info"><img class="img_block" src="images/ms_logo.png" alt=""></h1>
                    <div class="con hide" id="order_info">
                        <p class="name">某某 & 某某</p>
                        <p class="date">2017-07-09</p>
                    </div>
                    <div class="btn_box">
                        <a href="javascript:;" class="order_btn"></a>
                        <img class="shu" src="images/shu.png" alt="">
                    </div>

                    <!-- logo状态 -->
                    <a href="javascript:;" class="link" id="n_status">开启美思策划</a>
                    <a href="javascript:;" class="link hide" id="y_status">订单详情</a>
                </div>
            </div>
            <!--弹框-->
            <div class="masgbox">
                <div class="msg_content">
                    <div class="top clearfix">
                        <div class="search_box left clearfix">
                            <input type="text" placeholder="请输入查找内容" class="left">
                            <button type="" class="search_btn right"></button>
                        </div>
                        <a href="javascript:;" class="new_order_btn right"></a>
                    </div>
                    <div class="con_wrap">
                        <div class="con">
                            <div class="tab_nav_box clearfix">
                                <div class="tab_nav left">
                                    <span class="PathInner active" type='2'>婚礼</span>
                                    <span class="PathInner" type='1' style='margin-left: 4rem;'>会议</span>
                                </div>
                                <button class="btn right history_btn">历史订单</button>
                            </div>
                            <ul class="tab_content" id="order" action="">
                                <script id="tpl-order" type="text/html">
                                {{each list as value index}}
                                    {{if value.order_type==type}}
                                    <li class="item clearfix" order-id="{{value.id}}" order-name="{{value.name}}" order-date="{{value.order_date}}" order-type="{{value.order_type}}">
                                        <i class="icon"><img src="images/icon_order01.png" alt=""></i>
                                        <div class="name_box left">
                                            <p class="date">{{value.name}} {{value.order_date}}</p>
                                            <p class="name"><span>{{value.designer}}</span></p>
                                        </div>
                                        <p class="day_num left">{{value.to_date}}天 <span>{{value.order_status}}</span></p>
                                        <button class="btn right del_order_btn" style="background:rgba(255, 107, 41, 0.7)">删除</button>      
                                        <button class="btn right wjdj_btn">编辑</button>              
                                    </li>
                                    {{/if}}
                                {{/each}}
                                </script>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="shatan_wrap rel" id="footer">
        <div class="shatan rel" id="shatan">
            <img class="hx_left abs" src="images/haixing_left.png" alt="">
            <img class="jy abs" src="images/jiaoyin.png" alt="">
            <img class="hx_right abs" src="images/haixing_right.png" alt=""> 
        </div>
        <div class="bot" id="bot"></div>
        <!--美思宝藏-->
        <div class="content">
            <div class="thumb concave"  id="model">
                <div class="imgbox kort no_bg"><img src="images/f_imgmore.png" width="334" height="240"></div>
                <script id="tpl-model" type="text/html">
                {{each list as value index}}
                    <div class="imgbox item model" model-id="{{value.id}}" order-id="{{value.model_order}}"><img src="{{value.poster}}" width="200" height="120"></div>
                {{/each}}
                </script>
                <div class="imgbox no_bg"><img src="images/f_img01.png" width="334" height="240"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="js/jquery.1.7.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/jquery.transform.js"></script>
    <script type="text/javascript" src="js/template.js"></script>
    <script src="js/layer/layer.js"></script><!--弹框-->
    <script type="text/javascript" src="js/kort.js"></script>
    <script type="text/javascript" src="js/index_front.js"></script>
</body>

</html>