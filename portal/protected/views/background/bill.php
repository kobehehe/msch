<!DOCTYPE html>
<html>

<head>
    <title>报价单预览</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/base3.css" />    
    <link rel="stylesheet" type="text/css" href="css/bill.css" />
</head>
<body>
    <div class="bread_nav" style="width: 1200px;margin-left: auto;margin-right: auto;"></div>
    
    <article class="print_module"  style="width: 1200px;margin: 0 auto;">
        <div class="print_top flexbox">
            <div class="print_title_box flex1">
                <header>
                    <div class="right edit_btn t_btn" style='position: absolute;top: 7rem;right: 12rem;color: #f60;text-decoration: none;cursor: pointer;'>[编辑]</div>
                    <div class="right  t_btn" style='position: absolute;top: 7rem;right: 8rem;color: #f60;text-decoration: none;cursor: pointer;'><a href="#" style="color:#f60" id="down">[打印]</a></div>
                </header>
                <!--startprint-->
                <header>
                    <h1><?php echo $data['order_data']['order_name']?></h1>
                    <p class="address">地址：<span><?php echo $data['order_data']['order_place']?></span>
                    </p>
                    <p class="date">日期：<span><?php echo $data['order_data']['order_date']?></span>
                    </p>
                </header>
                <div class="set_price_table_box" id="exportArticle">
                    <table class="set_price" width=100%>
                        <tr>
                            <td><img style="height:2.1rem;width:auto;margin-top:.5rem;margin-bottom:.5rem;margin-left:.3rem" src="images/print_zk.png" alt=""></td>
                            <td><?php echo $data['order_data']['discount']['other_discount']?></td>
                        </tr>
                        <tr>
                            <td><img style="height:3rem;width:auto;" src="http://file.cike360.com/image/print_ml.png" alt=""></td>
                            <td colspan="4"><?php echo $data['order_data']['cut_price']?></td> 
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div class="foot flexbox v_center">
                                    <div class="flex1">
                                        <p>策划师 : <br/></p>
                                        <p>
                                            <span class="name"><?php echo $data['order_data']['designer_name']?></span><span class="tel"><?php echo $data['order_data']['designer_phone']?></span>
                                        </p>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!--echart-->
            <div class="chart_box flexbox center">
                <div id="main" style="width: 500px;height:450px;">
                    
                </div>
            </div>
        </div>

    <!--打印弹框-->
    <div class="msgbox print_msgbox">
        <div class="mask"></div>
        <div class="mascontent print_msg">
            <div class="titlebox clearfix">
                <div class="left clearfix">
                    <h3 class="left">邮箱</h3>
                    <button class="left add_mail">新增邮箱</button>
                </div>
                <div class="right">
                    <p class="">操作</p>
                </div>
            </div>
            <ul class="mail_list">
                <?php foreach ($email_list as $key => $value) {?>
                <li class="clearfix">
                    <div class="left">
                        <p class="name"><?php echo $value['email']?></p>
                    </div>
                    <div class="left">
                        <div>
                            <span class="left send">发送报价单</span>
                            <span class="left sep">|</span>
                            <span class="left del" data-id="<?php echo $value['id']?>">删除</span>
                        </div>
                </li>
                <?php }?>
            </ul>
        </div>
    </div>
<!-- －－－－－－－－－－－－－－－－－－－－－－－－－婚宴部分－－－－－－－－－－－－－－－－－－－－－－－－－ -->

        <?php foreach ($data['set_data']['feast'] as $key => $value) { ?>
        
        <section class="option_table_box">
            <div class="option_table">
                <table width=100%;>
                    <thead>
                        <tr>
                            <td><img src="images/print_feast.png" alt=""></td>
                            <td>参考图</td>
                            <td>数量</td>
                            <td>单位</td>
                            <td>价格</td>
                            <td>备注</td>
                            <td>总价</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($value['product_list'] as $idx => $val) { ?>
                        <tr>
                            <td><?php echo $val['product_name']?></td>
                            <td><?php echo $val['amount']?></td>
                            <td><?php echo $val['unit']?></td>
                            <td><?php echo $val['price']?></td>
                            <td><?php echo ($val['amount']*$val['price'])?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="total bule">总计</td>
                            <td colspan="5"></td>
                            <td><?php echo $data['order_total']['feast'][$key]['zhehou_total']?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
        <?php };?>
<!-- －－－－－－－－－－－－－－－－－－－－－－－－－婚礼部分－－－－－－－－－－－－－－－－－－－－－－－－－ -->
        <?php foreach ($data['area_product'] as $key => $value) { ?>
        <?php if(count($value['product_list']) != 0){?>
        <section class="option_table_box">
            <div class="option_table">
                <table width=100%;>
                    <thead>
                        <tr>
                            <td><img src="images/print_t<?php echo $value['area_id']?>.png" alt=""></td>
                            <td>参考图</td>
                            <td>数量</td>
                            <td>单位</td>
                            <td>价格</td>
                            <td>备注</td>
                            <td>总价</td>
                        </tr>
                    </thead> 
                    <tbody>
                        <?php foreach ($value['product_list'] as $idx => $val) { ?>
                        <tr class="product_item" style="height: 3rem; line-height: 3rem;" op-id="<?php echo $val['product_id']?>" area_id="<?php echo $value['area_id']?>">
                            <td class="product_item_td">
                                <div style="position: relative; top: -2rem"><?php echo $val['product_name']?></div>
                            </td>
                            <td><img class="list_img" style="margin-top: 1rem; margin-left: .5rem;" src="<?php echo $val['ref_pic_url']?>" md="<?php echo $val['ref_pic_url']?>"></td>
                            <td class="product_item_td">
                                <div style="position: relative; top: -2rem"><?php echo $val['amount']?></div>
                            </td>
                            <td class="product_item_td">
                                <div style="position: relative; top: -2rem"><?php echo $val['unit']?></div>
                            </td>
                            <td class="product_item_td">
                                <div style="position: relative; top: -2rem"><?php echo $val['price']?></div>
                            </td>
                            <td>
                                <div class="list_remark" style="position: relative; top: -2rem"><?php echo $val['description'] ?></div>
                            </td>
                            <td class="product_item_td">
                                <div style="position: relative; top: -2rem"><?php echo ($val['amount']*$val['price'])?></div>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="total bule">总计</td>
                            <td colspan="5"></td>
                            <td><?php echo $data['order_total']['areap'][$key]['zhehou_total']?></td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </section>

        <?php }}?>
        <!--endprint-->
    </article>
    
    <form action="/portal/index.php?r=background/export_pdf" method="post" name="hld_res" id="hideform">
      <input type="hidden" id="hide_content" name="html"/>
    </form>  
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script src="js/echarts.min.js"></script>
<script type="text/javascript" src="js/top_nav.js"></script>
<script type="text/javascript">

$(function () {
      //获取需要传递的Html代码 通过<!--startprint--><!--endprint-->截取
      bdhtml=window.document.body.innerHTML; 
      sprnstr="<!--startprint-->"; 
      eprnstr="<!--endprint-->"; 
      prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17); 
      prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr)); 
      //将获取的html代码添加到隐藏域中传给php文件处理
      $("#hide_content").val(""+prnhtml+"");
    } );   

    $("#down").click(function(){
      $("#hideform").submit();
    }); 


$(function () {
    //导航初始化
    $('body').top_nav({
        active_no:10
    });

    //打印pdf
    $("#exportPdf").click(function(){
            var content = $("#exportArticle").html();
            //$.post("/portal/index.php?r=background/export_pdf",{content:content});
          // $.ajax({
            //     type:"post",
            //     url:'/portal/index.php?r=background/export_pdf',
            //     data:{content:content},
            //     async:false
            // });
            //window.open("/portal/index.php?r=background/export_pdf?content="+content);
           
        });
    //面包屑导航
    $.fn.bread_nav = function(options) {
        var defaults = {
            father_class: 'bread_nav',
            font_size: '14px',          
            nav_name_list:[{
                nav_name: '首页',
                nav_link: 'index_front',
            }]
        };
        var options = $.extend(defaults, options);
        xuanran();

        function xuanran(){
            var html = '<div style="font-size:14px ;margin: 20px 0 10px 0;height: 20px;line-height: 20px;">';
            var nav_num = options.nav_name_list.length;
            $.each(options.nav_name_list,function(i,val){
                var color = '#444';
                if(i==nav_num-1) color = '#feb300;';
                html += '<a href="' + val.nav_link + '" style="color:'+color+';display:inline-block;">' + val.nav_name + '</a>';
                if( i != nav_num-1){
                    html += '<i  style="display:inline-block;width: 12px;height: 15px;position: relative;top: 1px;background: url(images/sprite.png) no-repeat -8px -74px;margin: 0 10px;"></i>';
                }   
            });
            html += '</div>';
            $("."+options.father_class).prepend(html);
        }
    }
    $('body').bread_nav({
        father_class: 'bread_nav',
        nav_name_list:[{
            nav_name: '首页',
            nav_link: 'index.php?r=background/index_front'
        },{
            nav_name: "报价单",
            nav_link: ''
        }]
    });


    var post_data = {
        'token' : <?php echo $_GET['token']?>,
        'order_id' : <?php echo $_GET['order_id']?>
    };
    $.get("<?php echo $this->createUrl("resource/orderdetail");?>",post_data,function(data){
        order_info = $.parseJSON(data);
        //基本信息渲染
        var orderTotal = order_info.order_total;
        var allTotal = orderTotal.total.price.toFixed(0);
        var pieName = null;
        var pieName = [];
        var outData = [];
        var inData = [];
        //计算婚宴
        var feast_total = 0;
        $.each(orderTotal.feast, function(index, value) {
            feast_total += parseFloat(value.zhehou_total);
        });
        //计算未分配
        var non_area_total = orderTotal.non_area.zhehou_total;
        //构造外圈呈现内容
        outData.push({
            value: feast_total,
            name: '婚宴',
            itemStyle: {
                normal: {
                    color: '#dc69aa'
                }
            }
        });
        $.each(orderTotal.areap, function(index, value) {
            if(value.zhehou_total!=0){
                pieName.push(value.name);
                outData.push({
                    value: value.zhehou_total,
                    name: value.name
                });    
            };
        });
        //pieName.push("待分配产品", '服务人员', '场地布置', '灯光音响视频');
        outData.push({
            value: non_area_total,
            name: '待分配产品',
            itemStyle: {
                normal: {
                    color: '#8d98b3'
                }
            }
        });

        //构造内圈呈现内容
        inData.push({
            value: feast_total,
            name: '婚宴',
            itemStyle: {
                normal: {
                    color: '#dc69aa'
                }
            }
        });
        if(feast_total != 0){pieName.push('婚宴');};
        inData.push({
            value: order_info.order_data.type_price.service,
            name: '服务人员',
            itemStyle: {
                normal: {
                    color: '#2094e6'
                }
            }
        });
        if(order_info.order_data.type_price.service != 0){pieName.push('服务人员');};
        inData.push({
            value: order_info.order_data.type_price.decorat,
            name: '场地布置',
            itemStyle: {
                normal: {
                    color: '#ecd70f'
                }
            }
        });
        if(order_info.order_data.type_price.decorat != 0){pieName.push('场地布置');};
        inData.push({
            value: order_info.order_data.type_price.light,
            name: '灯光音响视频',
            itemStyle: {
                normal: {
                    color: '#0fec63'
                }
            }
        });
        if(order_info.order_data.type_price.light != 0){pieName.push('灯光音响视频');};
        var myChart = echarts.init(document.getElementById('main'));
        option = {
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            legend: {
                orient: 'horizontal',
                y: 'bottom',
                x: '20',
                itemWidth: 15,
                textStyle: {
                    fontSize: 10,
                    color: '#999',
                },
                data: pieName
            },
            series: [{
                name: '类别',
                type: 'pie',
                selectedMode: 'single',
                radius: ['30%', '50%'],
                itemStyle: {
                    normal: {
                        label: {
                            show: false,
                            normal: {
                                position: 'inner'
                            }
                        },
                        labelLine: {
                            show: false
                        }
                    }
                },
                data: inData
            }, {
                name: '总价',
                type: 'pie',
                selectedMode: '',
                radius: ['0', '32%'],
                itemStyle: {
                    normal: {
                        label: {
                            show: true,
                            position: 'center',
                            textStyle: {
                                fontSize: '20',
                                fontWeight: 'bold',
                                color: '#333',
                            },
                            formatter: function(data) {
                                return data.name + "\n" + data.value;
                            },
                        },
                        labelLine: {
                            show: false
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data: [{
                    value: '￥' + allTotal,
                    name: '总价TOTAL',
                    itemStyle: {
                        normal: {
                            color: '#fff'
                        }
                    }
                }, ]
            }, {
                name: '区域',
                type: 'pie',
                radius: ['54%', '73%'],
                itemStyle: {
                    normal: {
                        label: {
                            show: false
                        },
                        labelLine: {
                            show: false
                        }
                    }
                },
                data: outData
            }]
        };
        myChart.setOption(option);
    });

    

    /*打印弹框*/
    var printMsg = {
        init: function () {
            printMsg.initPosition();
            $('.print_btn').live('click', printMsg.show)
            $('.print_msgbox').on('click', '.mask', printMsg.close)
            $('.print_msgbox').on('click', '.add_mail', printMsg.addSureaddMail)
            $('.print_msgbox').on('click', '.sure_btn', printMsg.addSure)
            $('.print_msgbox').on('click', '.del', printMsg.del)
            $('.print_msgbox').on('click', '.send', printMsg.sendMail)
        },
        initPosition: function () {
            var winWidth = document.body.clientWidth;
            var left = (winWidth - 700) / 2 + 'PX';
            $('.print_msgbox .print_msg').css('left', left);
        },
        show: function () {
            $('.print_msgbox').show();
        },
        close: function () {
            $('.print_msgbox').hide();
        },
        addSureaddMail: function () {
            $('.mail_list').prepend('<li class="clearfix new_item"><div class="left"><input type="text"></div><div class="left"><div><button class = "sure_btn" > 确定 </button> </div></div> </li>')
        },
        addSure: function () {
            var _e = $(this);
            var _newMail = $(this).parents('.new_item').find('input').val();
            
            /*此处ajax 新增邮箱*/
            $.post("/portal/index.php?r=resource/new_email_print",
                JSON.stringify({
                    email: _newMail,
                    order_id: '<?php echo $_GET["order_id"]?>',
                    staff_id: '<?php echo $_GET["token"]?>'
                })).
                success(function (data) {
                    alert('发送成功！');
                    if ($.trim(_newMail) != '') {
                        _e.parents('.new_item').find('input').after('<p class="name">' + _newMail + '</p></div>');
                        _e.parents('.new_item').find('input').remove();
                        _e.after('<span class="left send">发送报价单</span><span class="left sep">|</span><span class="left del" data-id="'+data.id+'">删除</span>');
                        _e.remove();
                    };
                    $('.print_msgbox').hide();
                }).
                error(function (err) {
                    alert("网络错误，发送失败，请稍后重试！");
                }
            );

        },
        del: function () {
            /*此处ajax 删除邮箱*/
            var _e = $(this);
            $.post("/portal/index.php?r=dailyReport/del_email",
                JSON.stringify({
                    email_id: _e.attr('data-id'),
                })).
                success(function (data) {
                    _e.parents('li.clearfix').remove();
                }).
                error(function (err) {
                    alert("网络错误，请稍后重试！");
                });
        },
        sendMail: function () {
            var _Mail = $(this).parents('li.clearfix').find('p.name').html();
            console.log(_Mail);
            /*发送报价单相关操作*/
            $.post("/portal/index.php?r=resource/old_email_print",
                JSON.stringify({
                    email: _Mail,
                    order_id: '<?php echo $_GET["order_id"]?>',
                    staff_id: '<?php echo $_GET["token"]?>'
                })).
                success(function (data) {
                    alert('发送成功！');
                    $('.print_msgbox').hide();
                }).
                error(function (err) {
                    alert("网络错误，发送失败，请稍后重试！");
                }
            );
            
        }
    };

    /*打印弹框初始化*/
    printMsg.init();

    //编辑按钮
    $(".edit_btn").on("click", function(){
        location.href = '/portal/index.php?r=background/price_list&order_id=<?php echo $_GET["order_id"]?>&token=<?php echo $_GET["token"]?>';
    });

    /*图片放大*/
    var tableOption = {
        init: function () {
            winWidth = window.screen.availWidth,
            winHeight = window.screen.availHeight;  
            wScale=winWidth/winHeight;
            $('.print_module').on('click', '.list_img', tableOption.imgMsg);
            $('body').on('click','.imgmsgbox',tableOption.imgClose);
        },
        imgMsg: function () {
            var _src_md = $(this).attr('src');    
            console.log(_src_md)                                                           
            var i = new Image();
            i.src = _src_md;
            var rw = i.width;
            var rh = i.height;
            if(rw/rh>wScale){
                var html = '<div class="imgmsgbox">'
                    + '<div class="mask"></div>'
                    + '<div class="imgbox"><img src=' + _src_md + ' style="width:100%;height:auto"></div>'
                    + '</div>'
            }else{
                    var html = '<div class="imgmsgbox">'
                    + '<div class="mask"></div>'
                    + '<div class="imgbox"><img src=' + _src_md + '></div>'
                    + '</div>'
            } 
            $("body").append(html);
        },
        imgClose: function () {
            $('.imgmsgbox').remove();
        }
    }
    tableOption.init();
})
</script>
</body>

</html>