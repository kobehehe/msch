function momv(e) {
    var x = e.clientX;
    var y = e.clientY;
    var m = $(".s_model").width() / 2;
    if (x > m) {
        $(".s_model").removeClass("arrow");
        $(".s_model").removeClass("arrowLeft");
        $(".s_model").addClass("arrowRight");
    } else {
        $(".s_model").removeClass("arrow");
        $(".s_model").removeClass("arrowRight");
        $(".s_model").addClass("arrowLeft");
    }
    if ($(e.target).hasClass("noPagingTurning")) {
        $(".s_model").removeClass("arrowLeft");
        $(".s_model").removeClass("arrowRight");
        $(".s_model").addClass("arrow");
    }
}
(function () {
    var mySwiper;
    var orderId = imi.GetQueryString("order_id") != null ? imi.GetQueryString("order_id") : "";
    var token = imi.GetQueryString("token") != null ? imi.GetQueryString("token") : "";
    var modelId = imi.GetQueryString("model-id") != null ? imi.GetQueryString("model-id") : "";
    var page_type = imi.GetQueryString("type") != null ? imi.GetQueryString("type") : "";

    //加入订单按钮
    if(page_type == 'model'){$("#add_to_order").css("display", 'block');};

    $.ajax({
        url: "/portal/index.php?r=background/Get_example_data&order_id=" + orderId + "&token=" + token,
        type: "get",
        async: true,
        cache: false,
        datatype: "json",
        success: function (data) {
            console.log(data);
            data = JSON.parse(data);
            var weddingTheme;
            var weddingColor;

            data.result.order_show.forEach(function (value, index, array) {
                if (value.area_id == 1) {
                    value.subarea.forEach(function (value2, index2, array2) {
                        if (value2.id == 1) {
                            if (value2.data.length > 0) {
                                weddingTheme = value2.data[0].theme_words;
                            }
                        }
                    });
                }
            });

            data.result.order_show.forEach(function (value, index, array) {
                if (value.area_id == 2) {
                    value.subarea.forEach(function (value2, index2, array2) {
                        if (value2.id == 3) {
                            if (value2.data.length > 0) {
                                weddingTheme = value2.data[0].color_name;
                            }
                        }
                    });
                }
            });

            var html = template('temContent', {
                "data": data,
                "weddingTheme": weddingTheme,
                "weddingColor": weddingColor
            });
            $("#temContainer").html(html);

            html = template('orderContent', {
                "data": data.order
            });
            $("#orderContainer").html(html);

            var screenHeight = $(window).height();
            $(".item").css('height', screenHeight);
            $(".item .content").css('height', screenHeight);

            mySwiper = new Swiper($(".s_model"), {
                slidesPerView: 'auto',
                initialSlide: 0,
                autoPlay: false,
                loop: false,
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',
            });
        },
        error: function (data) {
            alert("网络错误，请稍后重试！");
        }
    });

    //背景音乐控制
    $(function () {
        $("#music")[0].play();
    });
    var music = document.getElementById("music");
    $('.musicPlay').click(function () {
        if (music.paused) {
            music.play();
            $(this).removeClass('stop');
        } else {
            music.pause();
            $(this).addClass('stop');
        }
    });

    //图片放大 
    $(".imgAmplify").live("click", function (e) {
        var winWidth = window.screen.availWidth,
            winHeight = window.screen.availHeight,
            wScale = winWidth / winHeight;
        var i = new Image();
        i.src = $(this).attr("data-img");
        var rw = i.width;
        var rh = i.height;
        if (rw / rh > wScale) {
            $(".imgmsgbox img").css({
                'width': '100%',
                'height': 'auto'
            });
        }
        $(".imgmsgbox img").attr("src", $(this).attr("data-img"));
        $(".imgmsgbox").addClass("active");
    });

    $(".imgmsgbox .mascontent").click(function () {
        $(".imgmsgbox").removeClass("active");
    });

    //删除效果图
    $(".delSpaceDesign").live("click", function () {
        var $e = $(this);
        var conf = confirm("确认删除该效果图？");
        if (conf == true) {
            $.ajax({
                url: "/portal/index.php?r=resource/del_order_show_img",
                type: "post",
                async: true,
                cache: false,
                datatype: "json",
                data: JSON.stringify({
                    "draw_id": $e.attr("data-id"),
                    "order_id": orderId
                }),
                success: function (data) {
                    $e.parents(".swiper-slide").remove();
                    mySwiper.update();
                },
                error: function (data) {
                    alert("网络访问出现问题!");
                }
            });
        }
    });

    //删除产品
    $(".delProduct").live("click",
        function () {
            var $e = $(this);
            var conf = confirm("确认删除该产品？");
            if (conf == true) {
                $.ajax({
                    url: "/portal/index.php?r=resource/del_op",
                    type: "post",
                    async: true,
                    cache: false,
                    datatype: "json",
                    data: JSON.stringify({
                        "op_id": $e.attr("data-id")
                    }),
                    success: function (data) {
                        $e.parents(".swiper-slide").remove();
                        mySwiper.update();
                    },
                    error: function (data) {
                        alert("网络访问出现问题!");
                    }
                });
            }
        });

    //返回
    $(".backBtn").live("click", function () {
        window.close();
    });

    //加入订单
    $('.openOrderPop').click(function () {
        var screenWidth = $(window).width();
        $('.order_masgbox .content').css('left', (screenWidth - 700) / 2 + 'px');
        $('.order_masgbox').addClass('show');
    });
    $('.order_masgbox .mask').click(function () {
        $('.order_masgbox').removeClass('show');
    });

    $(".addOrder").live("click",
        function () {
            var $e = $(this);
            var conf = confirm("确认加入订单吗？");
            if (conf == true) {
                alert(JSON.stringify({
                        model_order_id: orderId,
                        cur_order_id: $e.attr("data-id"),
                        model_id: modelId
                    }));
                $.ajax({
                    url: "/portal/index.php?r=resource/selectModel",
                    type: "post",
                    async: true,
                    cache: false,
                    datatype: "json",
                    data: JSON.stringify({
                        model_order_id: orderId,
                        cur_order_id: $e.attr("data-id"),
                        model_id: modelId
                    }),
                    success: function (data) {
                        alert("加入订单成功!");
                        $('.order_masgbox').removeClass('show');
                    },
                    error: function (data) {
                        alert("网络访问出现问题!");
                    }
                });
            }
        });

    //更换模版
    $(".changeTemplate").live("click",
        function () {
            location.href = "/portal/index.php?r=background/order_template_list&type_id=&order_id="+orderId;
        });

    //点击翻页
    $(".s_model").click(function (e) {
        if ($(e.target).hasClass("noPagingTurning")) {
            return;
        }
        var m = $(".s_model").width() / 2;
        if (e.clientX > m) {
            mySwiper.slideNext();
        } else {
            mySwiper.slidePrev();
        }
    });

    //全屏处理
    function EnterFullScreen() {
        $(".opbox").addClass("fullScreen");
        $(".s_model").addClass("fullScreen");
        $(".fullScreenBtn").html("退出全屏");
        $(".backBtn").hide();
        $(".changeTemplate").hide();
    }
    function ExitFullScreen() {
        $(".opbox").removeClass("fullScreen");
        $(".s_model").removeClass("fullScreen");
        $(".fullScreenBtn").html("全屏显示");
        $(".backBtn").show();
        $(".changeTemplate").show();
    }
    $(".fullScreenBtn").click(function () {
        imi.FullScreen.ToggleFullScreen(function() {EnterFullScreen();},function() {ExitFullScreen();});
    });
    imi.FullScreen.EnterFullScreenWithF11(function(){EnterFullScreen()});
    imi.FullScreen.ExitFullScreen(function(){ExitFullScreen()});
})();