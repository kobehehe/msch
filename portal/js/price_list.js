angular.module('ms', ['ksSwiper', 'colorpicker.module', '720kb.datepicker'])
    .controller('priceList',
    [
        "$scope", "$http", "$compile", "$timeout", function ($scope, $http, $compile, $timeout) {
            $scope.orderId = imi.GetQueryString("order_id");
            $scope.token = imi.GetQueryString("token");
            $scope.order_type = '';
            $scope.data = null;
            $scope.taocanShow = true;

            $scope.sidebarActive = -1;

            $scope.ChangeSidebar = function (i) {
                $scope.sidebarActive = i;
            }

            //基本信息
            $scope.basicInfo = null;
            $scope.orderTypeList = [
                {
                    id: "1",
                    name: "会议"
                }, {
                    id: "2",
                    name: "婚礼"
                }
            ];
            $scope.orderType = null;
            $scope.hotelList = [];
            $scope.hotel = null;
            $scope.basicValidate = {
                total: true,
                order_date: true,
                order_type: true,
                staff_hotel_id: true,
                order_place: true,
                groom_name: true,
                bride_name: true
            }

            //文字主题
            $scope.curTextThemeId = null;
            $scope.curTextTheme = null;
            $scope.textThemeList = [];
            $scope.textWords = null;
            $scope.textRemark = null;
            $scope.textThemeValidate = {
                total: true,
                textWords: true,
                textRemark: true
            }

            //颜色主题
            $scope.curColorThemeId = null;
            $scope.curColorTheme = null;
            $scope.colorThemeList = [];
            $scope.colorName = null;
            $scope.colorRemark = null;
            $scope.mainColor = null;
            $scope.secondColor = null;
            $scope.thirdColor = null;
            $scope.colorThemeValidate = {
                total: true,
                colorName: true,
                colorRemark: true,
                mainColor: true,
                secondColor: true,
                thirdColor: true
            }

            //仪式区效果图
            $scope.curYsqList = [];
            $scope.curYsqList = [];
            $scope.ysqList = [];
            $scope.ysqImg = null;
            $scope.ysqDesc = null;
            $scope.ysqValidate = {
                total: true,
                ysqImg: true,
                ysqDesc: true
            }

            //迎宾区效果图
            $scope.curYbqList = [];
            $scope.ybqList = [];
            $scope.ybqImg = null;
            $scope.ybqDesc = null;
            $scope.ybqValidate = {
                total: true,
                ybqImg: true,
                ybqDesc: true
            }

            //新增弹窗
            $scope.pic = null;
            $scope.name = null;
            $scope.type = null;
            $scope.price = null;
            $scope.unit = null;
            $scope.cost = null;
            $scope.inventory = null;
            $scope.amount = null;
            $scope.description = null;
            $scope.remark = null;
            $scope.addAreaId = null;
            $scope.addSubareaId = null;
            $scope.subAreaList = [];

            $scope.cutPrice = null;

            $scope.PopConfirm = null;//弹窗确定操作

            $scope.onReadySwiper = function (swiper) {
                imi.SwiperOnReady(swiper);
            };

            //页面初始化
            $scope.Init = function () {
                // document.cookie = "userid=" + 100;
                
                $.post("/portal/index.php?r=background/Price_list_data",
                    JSON.stringify({
                        order_id: $scope.orderId,
                        token: $scope.token
                    }),
                    function (data) {
                        console.log(data);
                        data = JSON.parse(data);
                        $scope.order_type = data.result.order_data.order_type;
                        $('body').bread_nav({
                            father_class: 'bread_nav',
                            nav_name_list:[{
                                nav_name: '首页',
                                nav_link: 'index.php?r=background/index_front'
                            },{
                                nav_name: data.result.order_data.order_name,
                                nav_link: ''
                            }]
                        });
                        $('body').top_nav({
                            active_no:10
                        });

                        //会议：基本信息渲染
                        if($scope.order_type == 1){
                            $(".wedding_info").css('display', 'none');
                            $(".meeting_info").css('display', 'block');

                            $(".base_t .desc").css("display", 'none');
                        };

                        $("#print_area .share_store").live('click', function(){
                            var scroll_id = $(this).attr('data-scroll');
                            var subarea_id = '';
                            var area_id=$(this).attr('data-area');
                            var area_name=$(this).attr('data-areaname');
                            localStorage.setItem('bread_order_name', data.result.order_data.order_name);
                            localStorage.setItem('bread_area_name', area_name);
                            location.href = '/portal/index.php?r=background/share_store_view&token='+imi.GetQueryString("token")+'&order_id='+imi.GetQueryString("order_id")+'&area_id=' + area_id + '&service_person_list=&low_price=&high_price=&subarea_id='+ subarea_id +'&page=1&scroll_id='+scroll_id;                            
                        });
                        

                        $scope.$apply(function () {

                            $scope.data = data;
                            $scope.cutPrice = $scope.data.result.order_data.cut_price;
                            angular.forEach(data.result.area_product,
                                function (i) {
                                    if (i.area_id == 14) {
                                        if (i.product_list.length < 1) {
                                            $scope.taocanShow = false;
                                        }
                                        return;
                                    }
                                });

                            $scope.basicInfo = data.result.order_data;
                            $scope.hotelList = data.hotel_list;

                            $scope.textThemeList = data.words_list;
                            angular.forEach($scope.textThemeList,
                                function (i, x) {
                                    if (i.selected == true) {
                                        $scope.curTextTheme = i;
                                        return;
                                    }
                                });
                            angular.forEach(data.result.order_show,
                                function (i) {
                                    if (i.area_id == 1) {
                                        angular.forEach(i.subarea,
                                            function (n) {
                                                if (n.id == 1) {
                                                    if (n.data.length > 0) {
                                                        $scope.curTextThemeId = n.data[0].show_id;
                                                    }
                                                    return;
                                                }
                                            });
                                    }
                                });

                            $scope.colorThemeList = data.color_list;
                            angular.forEach($scope.colorThemeList,
                                function (i, x) {
                                    if (i.selected == true) {
                                        $scope.curColorTheme = i;
                                        return;
                                    }
                                });
                            angular.forEach(data.result.order_show,
                                function (i) {
                                    if (i.area_id == 2) {
                                        angular.forEach(i.subarea,
                                            function (n) {
                                                if (n.id == 3) {
                                                    if (n.data.length > 0) {
                                                        $scope.curColorThemeId = n.data[0].show_id;
                                                    }
                                                    return;
                                                }
                                            });
                                    }
                                });

                            angular.forEach(data.show_img,
                                function (i, x) {
                                    if (i.subarea == 4) {
                                        $scope.ysqList.push(i);
                                        if (i.selected == true) {
                                            $scope.curYsqList.push(i);
                                        }
                                    } else if (i.subarea == 15) {
                                        $scope.ybqList.push(i);
                                        if (i.selected == true) {
                                            $scope.curYbqList.push(i);
                                        }
                                    }
                                });
                            angular.forEach($scope.curYsqList,
                                function (i) {
                                    angular.forEach(data.result.order_show,
                                        function (it) {
                                            if (it.area_id == 5) {
                                                angular.forEach(it.show_img_list,
                                                    function (nt) {
                                                        if (nt.img_id == i.order_show_img_id) {
                                                            i.show_id = nt.show_id;
                                                            return;
                                                        }
                                                    });
                                            }
                                        });
                                });

                            angular.forEach($scope.curYbqList,
                                function (i) {
                                    angular.forEach(data.result.order_show,
                                        function (it) {
                                            if (it.area_id == 4) {
                                                angular.forEach(it.show_img_list,
                                                    function (nt) {
                                                        if (nt.img_id == i.order_show_img_id) {
                                                            i.show_id = nt.show_id;
                                                            return;
                                                        }
                                                    });
                                            }
                                        });
                                });

                            echart(data.result.order_total);

                            /*打印弹框*/
                            var printMsg = {
                                init: function () {
                                    printMsg.initPosition();
                                    $('.print_btn').on('click', printMsg.show)
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
                                    console.log(JSON.stringify({
                                            email: _newMail,
                                            order_id: $scope.orderId,
                                            staff_id: $scope.token
                                        }));
                                    $http.post("/portal/index.php?r=resource/new_email_print",
                                        JSON.stringify({
                                            email: _newMail,
                                            order_id: $scope.orderId,
                                            staff_id: $scope.token
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
                                    $http.post("/portal/index.php?r=dailyReport/del_email",
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
                                    $http.post("/portal/index.php?r=resource/old_email_print",
                                        JSON.stringify({
                                            email: _Mail,
                                            order_id: $scope.orderId,
                                            staff_id: $scope.token
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
                            /*侧边导航*/
                            var sideBar = {
                                init: function () {
                                    $('.sidebar_list').on('click', '.expand', sideBar.expand2);
                                    $('.sidebar_list').on('click', '.name', sideBar.expand);
                                    $('.sidebar_list').on('click', '.side_subbar_list li', sideBar.scroll);
                                    $('.sidebar_list')
                                        .on('mouseover', '.sidebar_item .addbtn_box', sideBar.addHover)
                                        .on('mouseout', '.addbtn_box', sideBar.addout);
                                    $('#print_area')
                                        .on('mouseover', '.addbtn_box', sideBar.addHover)
                                        .on('mouseout', '.addbtn_box', sideBar.addout);
                                    $('.add_msgbox').on('click', '.mask', sideBar.uploadClose);
                                    $('.add_msgbox').on('click', '.close', sideBar.uploadClose);
                                },
                                expand: function () {
                                    if ($(this).prev().hasClass('up')) {
                                        $(this).prev().removeClass('up');
                                        $(this).parents('li').find('.side_subbar_list').hide();
                                    } else {
                                        $(this).prev().addClass('up');
                                        $(this).parents('li').find('.side_subbar_list').show();
                                    }
                                },
                                expand2: function () {
                                    if ($(this).hasClass('up')) {
                                        $(this).removeClass('up');
                                        $(this).parents('li').find('.side_subbar_list').hide();
                                    } else {
                                        $(this).addClass('up');
                                        $(this).parents('li').find('.side_subbar_list').show();
                                    }
                                },
                                scroll: function () {
                                    var _dataid = $(this).data('id');
                                    // var _offsetTop = $("#" + _dataid).offset().top - 60;
                                    // $(window).scrollTop(_offsetTop);
                                    $('li').removeClass('active');
                                    $(this).addClass('active');
                                },
                                addHover: function () {
                                    $(this).find('.hovercon_box').show();
                                },
                                addout: function () {
                                    $(this).find('.hovercon_box').hide();
                                },
                                uploadClose: function () {
                                    $('.add_msgbox').hide();
                                }
                            }
                            /*表格操作*/
                            var tableOption = {
                                init: function () {
                                    winWidth = window.screen.availWidth,
                                    winHeight = window.screen.availHeight;  
                                    wScale=winWidth/winHeight;
                                    $('.edit_msgbox').on('click', '.mask', tableOption.close);
                                    $('.edit_msgbox').on('click', '.close', tableOption.close);
                                    $('#print_area').on('click', '.list_img', tableOption.imgMsg);
                                    $('body').on('click','.imgmsgbox',tableOption.imgClose);
                                },
                                close: function () {
                                    $('.edit_msgbox').hide();
                                },
                                imgMsg: function () {
                                    var _src_md = $(this).attr('data-md');                                
                                    var _src_sm = $(this).attr('data-sm');                                
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
                            /*效果图滑动调用*/
                            //var swip = new Swiper($(".effect_swiper"), {
                            //    slidesPerView: 'auto',
                            //    initialSlide: 0,
                            //    autoPlay: false,
                            //    loop: false,
                            //    nextButton: '.swiper-button-next',
                            //    prevButton: '.swiper-button-prev',
                            //});
                            /*打印弹框初始化*/
                            printMsg.init();
                            /*侧边导航初始化*/
                            sideBar.init();
                            /*表格操作初始化*/
                            tableOption.init();

                            $timeout(function () {
                                sidebarScroll = new IScroll('#sidebar_scroll', {
                                    scrollbars: false,
                                    mouseWheel: true,
                                });
                                mainScroll = new IScroll('#main_scroll', {
                                    scrollbars: false,
                                    mouseWheel: true,
                                });
                                
                                var scroll_id = imi.GetQueryString("scroll_id");                                    
                                if (scroll_id != null) {
                                        $scope.sidebarActive = scroll_id.substring(4);
                                    sidebarScroll.scrollToElement($('.sidebar_item[data-id="' + scroll_id + '"]')[0], 400);
                                    mainScroll.scrollToElement($("#" + scroll_id)[0], 400);
                                }

                                //$(".copy_btn_box").css("top", "1267px");
                            },
                                500);
                        });
                    })
                    .error(function () {
                        alert("网络出现问题，请稍后重试！");
                    });
            }
            $scope.Init();

            $scope.UpdateCutPrice = function () {
                $http.post("/portal/index.php?r=resource/update_cut_price",
                    {
                        order_id: $scope.orderId,
                        cut_price: $scope.cutPrice
                    }).
                    success(function (data) {
                        location.href = "/portal/index.php?r=background/price_list&order_id=" +
                            $scope.orderId +
                            "&token=" +
                            $scope.token +
                            "&scroll_id=jxxx";
                        //$scope.data.result.order_data.cut_price=$scope.cutPrice ;
                    }).
                    error(function (err) {
                        alert("网络错误，请稍后重试！");
                    });
            }

            //基本信息
            //编辑
            $scope.OpenBasicPop = function () {
                $scope.orderType = $scope.basicInfo.order_type;
                $scope.hotel = $scope.basicInfo.staff_hotel_id;
                bombBox({
                    entrance: $('.base_msgbox')
                });
                //提交
                $scope.PopConfirm = function ($event) {
                    $scope.basicInfo.order_type = $scope.orderType;
                    $scope.basicInfo.staff_hotel_id = $scope.hotel;
                    //console.log($scope.basicInfo.order_type)

                    if ($scope.BasicValidate()) {
                        $.post("/portal/index.php?r=background/Edit_order_info",
                            JSON.stringify({
                                orderplace: $scope.basicInfo.order_place,
                                orderdate: $scope.basicInfo.order_date,
                                order_type: $scope.basicInfo.order_type,
                                guest_amount: $scope.basicInfo.guest_amount,
                                hotelid: $scope.basicInfo.staff_hotel_id,
                                groomname: $scope.basicInfo.groom_name,
                                groomtelephone: $scope.basicInfo.groom_phone,
                                bridename: $scope.basicInfo.bride_name,
                                bridetelephone: $scope.basicInfo.bride_phone,
                                company_name: $scope.basicInfo.company_name,
                                company_id: $scope.basicInfo.company_id,
                                contact_id: $scope.basicInfo.contact_id,
                                contact_name: $scope.basicInfo.contact_name,
                                contact_phone: $scope.basicInfo.contact_phone,
                                remark: $scope.basicInfo.remark,
                                orderId: $scope.orderId
                            }),
                            function (data) {
                                $($event.target).parents(".msgbox").hide();
                            })
                            .error(function () {
                                alert("网络出现问题，请稍后重试！");
                            }
                        );
                    };
                };
            };
            //基本信息验证
            $scope.BasicValidate = function () {
                if ($.trim($scope.basicInfo.order_date) == "") {
                    $scope.basicValidate.order_data = false;
                } else {
                    $scope.basicValidate.order_data = true;
                }
                if ($.trim($scope.orderType) == "" || $scope.orderType == null) {
                    $scope.basicValidate.order_type = false;
                } else {
                    $scope.basicValidate.order_type = true;
                }
                if ($.trim($scope.hotel) == "" || $scope.hotel == null) {
                    $scope.basicValidate.staff_hotel_id = false;
                } else {
                    $scope.basicValidate.staff_hotel_id = true;
                }
                if ($.trim($scope.basicInfo.order_place) == "") {
                    $scope.basicValidate.order_place = false;
                } else {
                    $scope.basicValidate.order_place = true;
                }
                if ($.trim($scope.basicInfo.groom_name) == "") {
                    $scope.basicValidate.groom_name = false;
                } else {
                    $scope.basicValidate.groom_name = true;
                }
                if ($.trim($scope.basicInfo.bride_name) == "") {
                    $scope.basicValidate.bride_name = false;
                } else {
                    $scope.basicValidate.bride_name = true;
                }
                if ($.trim($scope.basicInfo.company_name) == "") {
                    $scope.basicValidate.company_name = false;
                } else {
                    $scope.basicValidate.company_name = true;
                }
                if ($.trim($scope.basicInfo.contact_phone) == "") {
                    $scope.basicValidate.contact_phone = false;
                } else {
                    $scope.basicValidate.contact_phone = true;
                }
                if ($.trim($scope.basicInfo.contact_name) == "") {
                    $scope.basicValidate.contact_name = false;
                } else {
                    $scope.basicValidate.contact_name = true;
                }

                if($scope.order_type == 2){
                    $scope.basicValidate.total = $scope.basicValidate.order_data && ($scope.basicValidate.groom_name || $scope.basicValidate.bride_name);    
                }else{
                    $scope.basicValidate.total = $scope.basicValidate.order_data && $scope.basicValidate.company_name;
                };
                
                return $scope.basicValidate.total;
            }

            //文字主题
            $scope.OpenTextThemePop = function () {
                bombBox({
                    entrance: $('.theme_word_msgbox')
                });
            }
            //添加文字主题
            $scope.AddTextTheme = function () {
                bombBox({
                    title: '添加主题文字',
                    entrance: $('.text_theme_add_msgbox'),
                    initFun: function () {
                        $scope.textWords = "";
                        $scope.textRemark = "";
                    }
                });

                $scope.PopConfirm = function ($event) {
                    if ($scope.TextThemeValidate()) {
                        $.post("/portal/index.php?r=background/Insert_word_theme",
                            JSON.stringify({
                                words: $scope.textWords,
                                remark: $scope.textRemark,
                                token: $scope.token
                            }),
                            function (data) {
                                data = JSON.parse(data);
                                if (data.id > 0) {
                                    $scope.$apply(function () {
                                        $scope.textThemeList.push({
                                            id: data.id,
                                            words: $scope.textWords,
                                            remark: $scope.textRemark,
                                            selected: false
                                        });
                                        $($event.target).parents(".msgbox").hide();
                                    });
                                } else {
                                    alert("操作失败！");
                                }
                            })
                            .error(function () {
                                alert("网络出现问题，请稍后重试！");
                            });
                    }
                }
            }
            //编辑文字主题
            $scope.EditTextTheme = function (item) {
                bombBox({
                    title: '编辑文字主题',
                    entrance: $('.text_theme_add_msgbox'),
                    initFun: function () {
                        $scope.textWords = item.words;
                        $scope.textRemark = item.remark;
                    }
                });
                $scope.PopConfirm = function ($event) {
                    if ($scope.TextThemeValidate()) {
                        $.post("/portal/index.php?r=background/edit_word_theme",
                            JSON.stringify({
                                words: $scope.textWords,
                                remark: $scope.textRemark,
                                word_id: item.id
                            }),
                            function (data) {
                                data = JSON.parse(data);
                                if (data.result == 1 || data.result == 0) {
                                    $scope.$apply(function () {
                                        angular.forEach($scope.textThemeList,
                                            function (i, t) {
                                                if (i.id == item.id) {
                                                    i.words = $scope.textWords;
                                                    i.remark = $scope.textRemark;
                                                    return;
                                                }
                                            });
                                        if ($scope.curTextTheme.id == item.id) {
                                            $scope.curTextTheme.words = $scope.textWords;
                                            $scope.curTextTheme.remark = $scope.textRemark;
                                        }
                                    });
                                    $($event.target).parents(".msgbox").hide();
                                } else {
                                    alert("操作失败！");
                                }
                            })
                            .error(function () {
                                alert("网络出现问题，请稍后重试！");
                            });
                    }
                }
            }
            //删除文字主题
            $scope.DeleteTextTheme = function (item) {
                $.post("/portal/index.php?r=background/del_word_theme",
                    JSON.stringify({
                        word_id: item.id
                    }),
                    function (data) {
                        data = JSON.parse(data);
                        if (data.result == 1) {
                            $scope.$apply(function () {
                                var index = $scope.textThemeList.indexOf(item);
                                if (index >= 0) {
                                    $scope.textThemeList.splice(index, 1);
                                }
                                if ($scope.curTextTheme.id == item.id) {
                                    $scope.curTextTheme = null;
                                }
                            });
                        } else {
                            alert("操作失败！");
                        }
                    })
                    .error(function () {
                        alert("网络出现问题，请稍后重试！");
                    });
            }
            //删除已选文字主题
            $scope.DeleteSelectedTextTheme = function (id, item) {
                $.post("/portal/index.php?r=background/del_word_theme_order_show",
                    JSON.stringify({
                        show_id: id
                    }),
                    function (data) {
                        data = JSON.parse(data);
                        if (data.result == 0) {
                            alert("操作失败！");
                        } else {
                            $scope.$apply(function () {
                                angular.forEach($scope.textThemeList,
                                    function (i, x) {
                                        if (i.id == item.id) {
                                            i.selected = false;
                                            return;
                                        }
                                    });
                                $scope.curTextTheme = null;
                            });
                        }
                    })
                    .error(function () {
                        alert("网络出现问题，请稍后重试！");
                    });
            }
            //选择文字主题
            $scope.SelectTextTheme = function (item, $event) {
                if ($scope.curTextTheme != null && $scope.curTextTheme.id == item.id) {
                    $.post("/portal/index.php?r=background/del_word_theme_order_show",
                        JSON.stringify({
                            show_id: $scope.curTextThemeId
                        }),
                        function (data) {
                            data = JSON.parse(data);
                            if (data.result == 0) {
                                alert("操作失败！");
                            } else {
                                $scope.$apply(function () {
                                    angular.forEach($scope.textThemeList,
                                        function (i, x) {
                                            if (i.id == item.id) {
                                                i.selected = false;
                                                return;
                                            }
                                        });
                                    $scope.curTextTheme = null;
                                });
                            }
                        })
                        .error(function () {
                            alert("网络出现问题，请稍后重试！");
                        });
                } else {
                    $.post("/portal/index.php?r=background/select_word_theme",
                        JSON.stringify({
                            order_id: $scope.orderId,
                            word_theme_id: item.id
                        }),
                        function (data) {
                            data = JSON.parse(data);
                            if (data.result > 0) {
                                $scope.$apply(function () {
                                    angular.forEach($scope.textThemeList,
                                        function (i, x) {
                                            if (i.id == item.id) {
                                                i.selected = !i.selected;
                                            } else {
                                                i.selected = false;
                                            }
                                        });

                                    $scope.curTextTheme = item;
                                    $scope.curTextThemeId = data.result;
                                });
                            } else {
                                alert("操作失败！");
                            }
                        })
                        .error(function () {
                            alert("网络出现问题，请稍后重试！");
                        });
                }
                $($event.target).parents(".msgbox").hide();
            }
            //验证
            $scope.TextThemeValidate = function () {
                if ($.trim($scope.textWords) == "") {
                    $scope.textThemeValidate.textWords = false;
                } else {
                    $scope.textThemeValidate.textWords = true;
                }
                if ($.trim($scope.textRemark) == "") {
                    $scope.textThemeValidate.textRemark = false;
                } else {
                    $scope.textThemeValidate.textRemark = true;
                }
                $scope.textThemeValidate.total = $scope.textThemeValidate.textWords &&
                    $scope.textThemeValidate.textRemark;
                return $scope.textThemeValidate.total;
            }

            //颜色主题
            $scope.OpenColorThemePop = function () {
                bombBox({
                    entrance: $('.theme_color_msgbox')
                });
            }
            //添加颜色主题
            $scope.AddColorTheme = function () {

                bombBox({
                    title: '添加主题颜色',
                    entrance: $('.color_theme_add_msgbox'),
                    initFun: function () {
                        $scope.colorName = null;
                        $scope.colorRemark = null;
                        $scope.mainColor = null;
                        $scope.secondColor = null;
                        $scope.thirdColor = null;
                    }
                });
                $scope.PopConfirm = function ($event) {
                    if ($scope.ColorThemeValidate()) {
                        $.post("/portal/index.php?r=background/insert_idea_color",
                            JSON.stringify({
                                name: $scope.colorName,
                                remark: $scope.colorRemark,
                                token: $scope.token,
                                main_color: $scope.mainColor,
                                second_color: $scope.secondColor,
                                third_color: $scope.thirdColor
                            }),
                            function (data) {
                                data = JSON.parse(data);
                                if (data.id > 0) {
                                    $scope.$apply(function () {
                                        $scope.colorThemeList.push({
                                            id: data.id,
                                            name: $scope.colorName,
                                            remark: $scope.colorRemark,
                                            main_color: $scope.mainColor,
                                            second_color: $scope.secondColor,
                                            third_color: $scope.thirdColor,
                                            selected: false
                                        });
                                    });
                                    $($event.target).parents(".msgbox").hide();
                                } else {
                                    alert("操作失败！");
                                }
                            })
                            .error(function () {
                                alert("网络错误，请稍后重试！");
                            });
                    }
                }
            }
            //编辑颜色主题
            $scope.EditColorTheme = function (item) {
                $("body").attr("onmousewheel", "return false;");
                bombBox({
                    title: '编辑主题颜色',
                    entrance: $('.color_theme_add_msgbox'),
                    initFun: function () {
                        $scope.colorName = item.name;
                        $scope.colorRemark = item.remark;
                        $scope.mainColor = item.main_color;
                        $scope.secondColor = item.second_color;
                        $scope.thirdColor = item.third_color;
                    },
                    close: function () {
                        $("body").attr("onmousewheel", "return true;");
                    }
                });
                $scope.PopConfirm = function ($event) {
                    $("body").attr("onmousewheel", "return true;");
                    if ($scope.ColorThemeValidate()) {
                        $.post("/portal/index.php?r=background/edit_idea_color",
                            JSON.stringify({
                                name: $scope.colorName,
                                remark: $scope.colorRemark,
                                color_id: item.id,
                                main_color: $scope.mainColor,
                                second_color: $scope.secondColor,
                                third_color: $scope.thirdColor
                            }),
                            function (data) {
                                data = JSON.parse(data);
                                if (data.result == 1 || data.result == 0) {
                                    $scope.$apply(function () {
                                        angular.forEach($scope.colorThemeList,
                                            function (i, x) {
                                                if (i.id == item.id) {
                                                    i.name = $scope.colorName;
                                                    i.remark = $scope.colorRemark;
                                                    i.main_color = $scope.mainColor;
                                                    i.second_color = $scope.secondColor;
                                                    i.third_color = $scope.thirdColor;
                                                }
                                            });
                                        if ($scope.curColorTheme.id == item.id) {
                                            $scope.curColorTheme.name = $scope.colorName;
                                            $scope.curColorTheme.remark = $scope.colorRemark;
                                            $scope.curColorTheme.main_color = $scope.mainColor;
                                            $scope.curColorTheme.second_color = $scope.secondColor;
                                            $scope.curColorTheme.third_color = $scope.thirdColor;
                                        }
                                    });
                                    $($event.target).parents(".msgbox").hide();
                                } else {
                                    alert("操作失败！");
                                }
                            })
                            .error(function () {
                                alert("网络错误，请稍后重试！");
                            });
                    }
                }
            }
            //删除颜色主题
            $scope.DeleteColorTheme = function (id) {
                $.post("/portal/index.php?r=background/del_idea_color",
                    JSON.stringify({
                        color_id: id
                    }),
                    function (data) {
                        data = JSON.parse(data);
                        if (data.result == 1) {
                            $scope.$apply(function () {
                                angular.forEach($scope.colorThemeList,
                                    function (i, x) {
                                        if (i.id == id) {
                                            $scope.colorThemeList.splice(x, 1);
                                            return;
                                        }
                                    });
                                if ($scope.curColorTheme.id == id) {
                                    $scope.curColorTheme = null;
                                }
                            });
                        } else {
                            alert("操作失败！");
                        }
                    })
                    .error(function () {
                        alert("网络错误，请稍后重试！");
                    });
            }
            //删除已选颜色主题
            $scope.DeleteSelectedColorTheme = function (id, item) {
                $.post("/portal/index.php?r=background/del_idea_color_order_show",
                    JSON.stringify({
                        show_id: id
                    }),
                    function (data) {
                        data = JSON.parse(data);
                        if (data.result == 1) {
                            $scope.$apply(function () {
                                angular.forEach($scope.colorThemeList,
                                    function (i, x) {
                                        if (i.id == item.id) {
                                            i.selected = false;
                                            return;
                                        }
                                    });
                                $scope.curColorTheme = null;
                            });
                        } else {
                            alert("操作失败！");
                        }
                    })
                    .error(function () {
                        alert("网络错误，请稍后重试！");
                    });
            }
            //选择颜色主题
            $scope.SelectColorTheme = function (item, $event) {
                if ($scope.curColorTheme != null && $scope.curColorTheme.id == item.id) {
                    $.post("/portal/index.php?r=background/del_idea_color_order_show",
                        JSON.stringify({
                            show_id: id
                        }),
                        function (data) {
                            data = JSON.parse(data);
                            if (data.result == 1) {
                                $scope.$apply(function () {
                                    angular.forEach($scope.colorThemeList,
                                        function (i, x) {
                                            if (i.id == item.id) {
                                                i.selected = false;
                                                return;
                                            }
                                        });
                                    $scope.curColorTheme = null;
                                });
                            } else {
                                alert("操作失败！");
                            }
                        })
                        .error(function () {
                            alert("网络错误，请稍后重试！");
                        });
                } else {
                    $.post("/portal/index.php?r=background/Select_theme_color",
                        JSON.stringify({
                            order_id: $scope.orderId,
                            theme_color_id: item.id
                        }),
                        function (data) {
                            data = JSON.parse(data);
                            if (data.result > 0) {
                                $scope.$apply(function () {
                                    angular.forEach($scope.colorThemeList,
                                        function (i, x) {
                                            if (i.id == item.id) {
                                                i.selected = !i.selected;
                                            } else {
                                                i.selected = false;
                                            }
                                        });

                                    $scope.curColorTheme = item;
                                    $scope.curColorThemeId = data.result;
                                });
                            } else {
                                alert("操作失败！");
                            }
                        })
                        .error(function () {
                            alert("网络错误，请稍后重试！");
                        });
                }
                $($event.target).parents(".msgbox").hide();
            }
            //验证
            $scope.ColorThemeValidate = function () {
                if ($.trim($scope.colorName) == "") {
                    $scope.colorThemeValidate.colorName = false;
                } else {
                    $scope.colorThemeValidate.colorName = true;
                }
                if ($.trim($scope.colorRemark) == "") {
                    $scope.colorThemeValidate.colorRemark = false;
                } else {
                    $scope.colorThemeValidate.colorRemark = true;
                }
                if ($.trim($scope.mainColor) == "") {
                    $scope.colorThemeValidate.mainColor = false;
                } else {
                    $scope.colorThemeValidate.mainColor = true;
                }
                if ($.trim($scope.secondColor) == "") {
                    $scope.colorThemeValidate.secondColor = false;
                } else {
                    $scope.colorThemeValidate.secondColor = true;
                }
                if ($.trim($scope.thirdColor) == "") {
                    $scope.colorThemeValidate.thirdColor = false;
                } else {
                    $scope.colorThemeValidate.thirdColor = true;
                }
                $scope.colorThemeValidate.total = $scope.colorThemeValidate.colorName &&
                    $scope.colorThemeValidate.colorRemark &&
                    $scope.colorThemeValidate.mainColor &&
                    $scope.colorThemeValidate.secondColor &&
                    $scope.colorThemeValidate.thirdColor;
                return $scope.colorThemeValidate.total;
            }

            $scope.discountData = null;
            $scope.intDiscount = null;
            //折扣
            $scope.OpenDiscountPop = function () {
                $http
                    .get("/portal/index.php?r=DailyReport/get_other_discount&order_id=" + $scope.orderId)
                    .success(function (data) {
                        $scope.discountData = data;
                        $scope.intDiscount = $scope.discountData.other_discount;
                    })
                    .error(function (data, status, headers, config) {
                        alert("网络错误，请稍后重试！");
                    });

                bombBox({
                    entrance: $('.discount_msgbox')
                });
            }

            $scope.DiscountCheck = function (i) {
                if (i.discount == true) {
                    $scope.discountData.range = $scope.ArrayRemove($scope.discountData.range, i.discount_range);
                    i.discount = false;
                } else {
                    if ($scope.discountData.range.length > 0) {
                        $scope.discountData.range = $scope.discountData.range + "," + i.discount_range;
                    } else {
                        $scope.discountData.range = i.discount_range;
                    }

                    i.discount = true;
                }
            }

            $scope.DiscountConfirm = function ($event) {
                if (!/^[0-9]*$/.test($scope.discountData.other_discount)) {
                    alert("折扣只能为数字!");
                    $scope.discountData.other_discount = $scope.intDiscount;
                    return;
                }
                if ($scope.discountData.other_discount < 0 || $scope.discountData.other_discount > 10) {
                    alert("折扣的范围应在0～10之间！");
                    $scope.discountData.other_discount = $scope.intDiscount;
                    return;
                }
                $http.post("/portal/index.php?r=resource/update_other_discount",
                    {
                        order_id: $scope.orderId,
                        other_discount: $scope.discountData.other_discount,
                        discount_range: $scope.discountData.range
                    }).
                    success(function (data) {
                        location.href = "/portal/index.php?r=background/price_list&order_id=" +
                            $scope.orderId +
                            "&token=" +
                            $scope.token +
                            "&scroll_id=jxxx";
                        //$scope.data.result.order_data.discount.other_discount = $scope.discountData.other_discount;
                        //$($event.target).parents(".msgbox").hide();
                    }).
                    error(function (err) {
                        alert("网络错误，请稍后重试！");
                    });
            }

            $scope.ArrayRemove = function (ori, tra) {
                var oriArray = ori.split(",");
                var traArray = tra.split(",");
                angular.forEach(traArray,
                    function (i) {
                        var index = oriArray.indexOf(i);
                        if (index >= 0) {
                            oriArray.splice(index, 1);
                        }
                    });
                return oriArray.join(",");
            }

            $scope.copyData = null;
            $scope.copyList = [];
            $scope.copyType = 1;
            $scope.copyId = null;
            $scope.copyName = null;
            //复制
            $scope.OpenCopyPop = function () {
                $http.post("/portal/index.php?r=background/get_all_order_list",
                    JSON.stringify({
                        token: $scope.token,
                    })).
                    success(function (data) {
                        $scope.copyData = data;
                        angular.forEach($scope.copyData.wedding,
                            function (i, j) {
                                if (i.id == $scope.orderId || i.is_mine == false) {
                                    $scope.copyData.wedding.splice(j, 1);
                                }
                            });
                        angular.forEach($scope.copyData.meeting,
                            function (i, j) {
                                if (i.id == $scope.orderId || i.is_mine == false) {
                                    $scope.copyData.wedding.splice(j, 1);
                                }
                            });

                        $scope.copyType = 1;
                        $scope.copyList = $scope.copyData.wedding;
                    }).
                    error(function (err) {
                        alert("网络错误，请稍后重试！");
                    });
                bombBox({
                    entrance: $('.copy_msgbox')
                });
            }

            $scope.ChangeCopyType = function (i) {
                $scope.copyType = i;
                if ($scope.copyType == 1) {
                    $scope.copyList = $scope.copyData.wedding;
                    $(".copy_msgbox .order_search_txt").val('');
                } else {
                    $scope.copyList = $scope.copyData.meeting;
                    $(".copy_msgbox .order_search_txt").val('');
                }
            }

            $scope.SelectCopy = function (i) {
                $scope.copyId = i.id;
                $scope.copyName = i.name;
            }
            //复制订单功能
            $scope.CopyConfirm = function ($event) {
                if ($scope.copyId == null) {
                    alert("您尚未选择订单！");
                    return;
                };
                if (confirm("确定要将［" + $scope.copyName + "］订单中内容复制到当前订单吗？")) {
                    $http.post("/portal/index.php?r=DailyReport/copy_order",
                            JSON.stringify({
                                order_id: $scope.orderId,//下面的那个
                                copy_order_id: $scope.copyId,//选的那个
                                token: $scope.token
                            })
                        ).
                        success(function (data) {
                            location.reload();
                        }).
                        error(function (err) {
                            alert("网络错误，请稍后重试！");
                        });
                }
            }
            //复制弹框－订单搜索功能
            $(".copy_msgbox .order_search_btn").click(function(){
              var txt=$(".copy_msgbox .order_search_txt").val();
              if($.trim(txt)!=""){        
                $(".copy_msgbox .list li").hide().filter(":contains('"+txt+"')").show();
              }else{
                $(".copy_msgbox .list li").show();
              }
            });

            /*复制弹框－订单搜索输入框的回车操作*/  
            $('.copy_msgbox .order_search_txt').bind('keypress',function(event){  
                if(event.keyCode == "13") 
                    $('.copy_msgbox .order_search_btn').click();  
            });

            //仪式区效果图
            $scope.OpenYsqPop = function () {
                bombBox({
                    entrance: $('.ysq_msgbox')
                });
            }
            //添加效果图 
            $scope.AddYsq = function () {
                bombBox({
                    title: '新增效果图',
                    entrance: $('.ysq_add_msgbox'),
                    initFun: function () {
                        $scope.ysqImg = null;
                        $scope.ysqDesc = null;
                        $(".ysq_add_msgbox img").attr("src", "images/icon_add2.png");
                    }
                });
            }
            //编辑效果图
            $scope.EditYsq = function (item) {
                bombBox({
                    title: '编辑效果图',
                    entrance: $('.ysq_edit_msgbox'),
                    initFun: function () {
                        $scope.ysqImg = item.img_url;
                        $scope.ysqDesc = item.description;

                    }
                });

                $scope.PopConfirm = function ($event) {
                    if ($scope.YsqValidate()) {
                        $.post("/portal/index.php?r=background/Edit_order_show_img",
                            JSON.stringify({
                                show_img_id: item.order_show_img_id,
                                //img_url: $scope.ysqImg,
                                description: $scope.ysqDesc
                            }),
                            function (data) {
                                data = JSON.parse(data);
                                if (data.result == 1) {
                                    $scope.$apply(function () {
                                        angular.forEach($scope.ysqList,
                                            function (i, t) {
                                                if (i.order_show_img_id == item.order_show_img_id) {
                                                    i.img_url = $scope.ysqImg;
                                                    i.description = $scope.ysqDesc;
                                                }
                                            });
                                        angular.forEach($scope.curYsqList,
                                            function (i, t) {
                                                if (i.order_show_img_id == item.order_show_img_id) {
                                                    i.img_url = $scope.ysqImg;
                                                    i.description = $scope.ysqDesc;
                                                }
                                            });
                                    });
                                    $($event.target).parents(".msgbox").hide();
                                } else {
                                    alert("操作失败！");
                                }
                            })
                            .error(function () {
                                alert("网络错误，请稍后重试！");
                            });
                    }
                }
            }
            //删除效果图
            $scope.DeleteYsq = function (item) {
                $.post("/portal/index.php?r=background/Del_order_show_img",
                    JSON.stringify({
                        show_img_id: item.order_show_img_id
                    }),
                    function (data) {
                        data = JSON.parse(data);
                        if (data.result == 1) {
                            $scope.$apply(function () {
                                angular.forEach($scope.ysqList,
                                    function (i, t) {
                                        if (i.order_show_img_id == item.order_show_img_id) {
                                            $scope.ysqList.splice(t, 1);
                                        }
                                    });
                                angular.forEach($scope.curYsqList,
                                    function (i, t) {
                                        if (i.order_show_img_id == item.order_show_img_id) {
                                            $scope.curYsqList.splice(t, 1);
                                        }
                                    });
                            });
                        } else {
                            alert("操作失败！");
                        }
                    })
                    .error(function () {
                        alert("网络错误，请稍后重试！");
                    });
            }
            //删除已选效果图
            $scope.DeleteSelectedYsq = function (item) {
                $.post("/portal/index.php?r=background/del_img_order_show",
                    JSON.stringify({
                        order_id: $scope.orderId,
                        show_img_id: item.show_id
                    }),
                    function (data) {
                        data = JSON.parse(data);
                        if (data.result == 1) {
                            $scope.$apply(function () {
                                angular.forEach($scope.ysqList,
                                    function (i, t) {
                                        if (i.order_show_img_id == item.order_show_img_id) {
                                            i.selected = !i.selected;
                                            return;
                                        }
                                    });
                                angular.forEach($scope.curYsqList,
                                    function (i, t) {
                                        if (i.order_show_img_id == item.order_show_img_id) {
                                            $scope.curYsqList.splice(t, 1);
                                            return;
                                        }
                                    });
                            });
                        } else {
                            alert("操作失败！");
                        }
                    })
                    .error(function () {
                        alert("网络错误，请稍后重试！");
                    });
            }
            //选择效果图 
            $scope.SelectYsq = function (item) {
                if (item.selected) {
                    //取消选择
                    $.post("/portal/index.php?r=background/del_img_order_show",
                        JSON.stringify({
                            order_id: $scope.orderId,
                            show_img_id: item.show_id
                        }),
                        function (data) {
                            data = JSON.parse(data);
                            if (data.result == 1) {
                                $scope.$apply(function () {
                                    angular.forEach($scope.ysqList,
                                        function (i, t) {
                                            if (i.order_show_img_id == item.order_show_img_id) {
                                                i.selected = !i.selected;
                                                return;
                                            }
                                        });
                                    angular.forEach($scope.curYsqList,
                                        function (i, t) {
                                            if (i.order_show_img_id == item.order_show_img_id) {
                                                $scope.curYsqList.splice(t, 1);
                                                return;
                                            }
                                        });
                                });
                            } else {
                                alert("操作失败！");
                            }
                        })
                        .error(function () {
                            alert("网络错误，请稍后重试！");
                        });
                } else {
                    //选择
                    $.post("/portal/index.php?r=background/add_show_img_to_order",
                        JSON.stringify({
                            token: $scope.token,
                            img_id: item.order_show_img_id,
                            service_product_id: item.service_product_id,
                            order_id: $scope.orderId
                        }),
                        function (data) {
                            data = JSON.parse(data);
                            if (data.result > 0) {
                                $scope.$apply(function () {
                                    angular.forEach($scope.ysqList,
                                        function (i, t) {
                                            if (i.order_show_img_id == item.order_show_img_id) {
                                                i.selected = !i.selected;
                                                return;
                                            }
                                        });

                                    item.show_id = data.result;
                                    $scope.curYsqList.push(item);
                                });
                            } else {
                                alert("操作失败！");
                            }
                        })
                        .error(function () {
                            alert("网络错误，请稍后重试！");
                        });
                }
            }
            //验证
            $scope.YsqValidate = function () {
                if ($.trim($scope.ysqImg) == "") {
                    $scope.ysqValidate.ysqImg = false;
                } else {
                    $scope.ysqValidate.ysqImg = true;
                }
                if ($.trim($scope.ysqDesc) == "") {
                    $scope.ysqValidate.ysqDesc = false;
                } else {
                    $scope.ysqValidate.ysqDesc = true;
                }
                $scope.ysqValidate.total = $scope.ysqValidate.ysqImg &&
                    $scope.ysqValidate.ysqDesc;
                return $scope.ysqValidate.total;
            }

            $("#cuPrice").hover(function () {
                $(".cuPriceStic").toggleClass("active");
                $(".cuPriceDny").toggleClass("active");
            });

            //迎宾区效果图
            $scope.OpenYbqPop = function () {
                bombBox({
                    entrance: $('.ybq_msgbox')
                });
            }
            //添加效果图
            $scope.AddYbq = function () {
                bombBox({
                    title: '新增效果图',
                    entrance: $('.ybq_add_msgbox'),
                    initFun: function () {
                        $scope.ybqImg = null;
                        $scope.ybqDesc = null;
                        $(".ybq_add_msgbox img").attr("src", "images/icon_add2.png");
                    }
                });
            }
            //编辑效果图，此处执行操作
            $scope.EditYbq = function (item) {
                bombBox({
                    title: '编辑效果图',
                    entrance: $('.ybq_edit_msgbox'),
                    initFun: function () {
                        $scope.ybqImg = item.img_url;
                        $scope.ybqDesc = item.description;
                    }
                });

                $scope.PopConfirm = function ($event) {
                    if ($scope.YbqValidate()) {
                        $.post("/portal/index.php?r=background/Edit_order_show_img",
                            JSON.stringify({
                                show_img_id: item.order_show_img_id,
                                //img_url: $scope.ybqImg,
                                description: $scope.ybqDesc
                            }),
                            function (data) {
                                data = JSON.parse(data);
                                if (data.result == 1) {
                                    $scope.$apply(function () {
                                        angular.forEach($scope.ybqList,
                                            function (i, t) {
                                                if (i.order_show_img_id == item.order_show_img_id) {
                                                    i.img_url = $scope.ybqImg;
                                                    i.description = $scope.ybqDesc;
                                                }
                                            });
                                        angular.forEach($scope.curYbqList,
                                            function (i, t) {
                                                if (i.order_show_img_id == item.order_show_img_id) {
                                                    i.img_url = $scope.ybqImg;
                                                    i.description = $scope.ybqDesc;
                                                }
                                            });
                                    });
                                    $($event.target).parents(".msgbox").hide();
                                } else {
                                    alert("操作失败！");
                                }
                            })
                            .error(function () {
                                alert("网络错误，请稍后重试！");
                            });
                    }
                }
            }
            //删除效果图
            $scope.DeleteYbq = function (item) {
                $.post("/portal/index.php?r=background/Del_order_show_img",
                    JSON.stringify({
                        show_img_id: item.order_show_img_id
                    }),
                    function (data) {
                        data = JSON.parse(data);
                        if (data.result == 1) {
                            $scope.$apply(function () {
                                angular.forEach($scope.ybqList,
                                    function (i, t) {
                                        if (i.order_show_img_id == item.order_show_img_id) {
                                            $scope.ybqList.splice(t, 1);
                                        }
                                    });
                                angular.forEach($scope.curYbqList,
                                    function (i, t) {
                                        if (i.order_show_img_id == item.order_show_img_id) {
                                            $scope.curYbqList.splice(t, 1);
                                        }
                                    });
                            });
                        } else {
                            alert("操作失败！");
                        }
                    })
                    .error(function () {
                        alert("网络错误，请稍后重试！");
                    });
            }
            //删除已选效果图
            $scope.DeleteSelectedYbq = function (item) {
                $.post("/portal/index.php?r=background/del_img_order_show",
                    JSON.stringify({
                        order_id: $scope.orderId,
                        show_img_id: item.show_id
                    }),
                    function (data) {
                        data = JSON.parse(data);
                        if (data.result == 1) {
                            $scope.$apply(function () {
                                angular.forEach($scope.ybqList,
                                    function (i, t) {
                                        if (i.order_show_img_id == item.order_show_img_id) {
                                            i.selected = !i.selected;
                                            return;
                                        }
                                    });
                                angular.forEach($scope.curYbqList,
                                    function (i, t) {
                                        if (i.order_show_img_id == item.order_show_img_id) {
                                            $scope.curYbqList.splice(t, 1);
                                            return;
                                        }
                                    });
                            });
                        } else {
                            alert("操作失败！");
                        }
                    })
                    .error(function () {
                        alert("网络错误，请稍后重试！");
                    });
            }
            //选择效果图
            $scope.SelectYbq = function (item) {
                if (item.selected) {
                    //取消选择
                    $.post("/portal/index.php?r=background/del_img_order_show",
                        JSON.stringify({
                            order_id: $scope.orderId,
                            show_img_id: item.show_id
                        }),
                        function (data) {
                            data = JSON.parse(data);
                            if (data.result == 1) {
                                $scope.$apply(function () {
                                    angular.forEach($scope.ybqList,
                                        function (i, t) {
                                            if (i.order_show_img_id == item.order_show_img_id) {
                                                i.selected = !i.selected;
                                                return;
                                            }
                                        });
                                    angular.forEach($scope.curYbqList,
                                        function (i, t) {
                                            if (i.order_show_img_id == item.order_show_img_id) {
                                                $scope.curYbqList.splice(t, 1);
                                                return;
                                            }
                                        });
                                });
                            } else {
                                alert("操作失败！");
                            }
                        })
                        .error(function () {
                            alert("网络错误，请稍后重试！");
                        });
                } else {
                    //选择
                    $.post("/portal/index.php?r=background/add_show_img_to_order",
                        JSON.stringify({
                            token: $scope.token,
                            img_id: item.order_show_img_id,
                            service_product_id: item.service_product_id,
                            order_id: $scope.orderId
                        }),
                        function (data) {
                            data = JSON.parse(data);
                            if (data.result > 0) {
                                $scope.$apply(function () {
                                    angular.forEach($scope.ybqList,
                                        function (i, t) {
                                            if (i.order_show_img_id == item.order_show_img_id) {
                                                i.selected = !i.selected;
                                                return;
                                            }
                                        });

                                    item.show_id = data.result;
                                    $scope.curYbqList.push(item);
                                });
                            } else {
                                alert("操作失败！");
                            }
                        })
                        .error(function () {
                            alert("网络错误，请稍后重试！");
                        });
                }
            }
            //验证
            $scope.YbqValidate = function () {
                if ($.trim($scope.ybqImg) == "") {
                    $scope.ybqValidate.ybqImg = false;
                } else {
                    $scope.ybqValidate.ybqImg = true;
                }
                if ($.trim($scope.ybqDesc) == "") {
                    $scope.ybqValidate.ybqDesc = false;
                } else {
                    $scope.ybqValidate.ybqDesc = true;
                }
                $scope.ybqValidate.total = $scope.ybqValidate.ybqImg &&
                    $scope.ybqValidate.ybqDesc;
                return $scope.ybqValidate.total;
            }

            //echart
            function echart(orderTotal) {
                var allTotal = orderTotal.total.price.toFixed(0);
                var pieName = null;
                //var pieName = ['婚宴'];
                var outData = [];
                var inData = [];
                //计算婚宴
                var feast_total = 0;
                $.each(orderTotal.feast, function (index, value) {
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
                $.each(orderTotal.areap, function (index, value) {
                    //pieName.push(value.name);
                    outData.push({
                        value: value.zhehou_total,
                        name: value.name
                    });
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
                inData.push({
                    value: 0,
                    name: '服务人员',
                    itemStyle: {
                        normal: {
                            color: '#2094e6'
                        }
                    }
                });
                inData.push({
                    value: 16300,
                    name: '场地布置',
                    itemStyle: {
                        normal: {
                            color: '#ecd70f'
                        }
                    }
                });
                inData.push({
                    value: 0,
                    name: '灯光音响视频',
                    itemStyle: {
                        normal: {
                            color: '#0fec63'
                        }
                    }
                });

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
                    },
                    {
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
                                        fontSize: '15',
                                        fontWeight: 'bold',
                                        color: '#333',
                                    },
                                    formatter: function (data) {
                                        return data.name + '\n' + data.value;
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
                        },]
                    },
                    {
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
                    }
                    ]
                };
                myChart.setOption(option);
            }

            var fileNames = [];
            var fileNames2 = [];
            var fileNames3 = [];

            //表格添加
            $scope.TableAdd = function (id) {
                $scope.addSubareaId = id;
                angular.forEach($scope.data.area_data,
                    function (i) {
                        angular.forEach(i.subarea,
                            function (j) {
                                if (j.id == id) {
                                    $scope.addAreaId = i.id;
                                    $scope.subAreaList = [{ 'id': parseInt(j.id), 'name': j.name }];
                                    return;
                                };
                            });
                    });
                bombBox({
                    entrance: $('.add_msgbox'),
                    initFun: function () {
                        $scope.pic = null;
                        $scope.name = null;
                        $scope.type = null;
                        $scope.price = null;
                        $scope.unit = null;
                        $scope.cost = null;
                        $scope.inventory = null;
                        $scope.amount = null;
                        $scope.description = null;
                        $scope.remark = null;
                        $(".add_msgbox img").attr("src", "images/icon_add2.png");
                    }
                });
            }
            //表格编辑 
            $scope.TableEdit = function (item) {
                bombBox({
                    //title: '编辑效果图',
                    entrance: $('.edit_msgbox'),
                    initFun: function () {
                        $scope.price = item.price;
                        $scope.amount = item.amount;
                        $scope.cost = item.cost;
                        $scope.remark = item.remark;
                    }
                });

                $scope.PopConfirm = function ($event) {
                    $.post("/portal/index.php?r=background/update_op",
                        JSON.stringify({
                            op_id: item.product_id,
                            actual_price: $scope.price,
                            amount: $scope.amount,
                            actual_unit_cost: $scope.cost,
                            remark: $scope.remark
                        }),
                        function (data) {
                            if (data == "") {
                                $scope.$apply(function () {
                                    item.price = $scope.price;
                                    item.amount = $scope.amount;
                                    item.cost = $scope.cost;
                                    item.remark = $scope.remark;
                                    $($event.target).parents(".msgbox").hide();
                                });
                            }
                        })
                        .error(function () {
                            alert("网络错误，请稍后重试！");
                        });
                }
            }
            //表格删除
            $scope.TableDelete = function (id, nid) {
                $.post("/portal/index.php?r=background/del_op",
                    JSON.stringify({
                        op_id: nid
                    }),
                    function (data) {
                        data = JSON.parse(data);
                        if (data.result == 1) {
                            $scope.$apply(function () {
                                angular.forEach($scope.data.result.area_product,
                                    function (i, t) {
                                        if (i.area_id == id) {
                                            angular.forEach(i.product_list,
                                                function (j, x) {
                                                    if (j.product_id == nid) {
                                                        i.product_list.splice(x, 1);
                                                        return;
                                                    }
                                                });
                                        }
                                    });
                            });
                        } else {
                            alert("操作失败！");
                        }
                    })
                    .error(function () {
                        alert("网络错误，请稍后重试！");
                    });
            }

            //上传 
            accessid = 'LTAIjPXqmetaG8iO';
            accesskey = 'yHaKQMZBQCmA4TyUdNLY3roAo5fRtZ';
            host = 'http://inspitation-img-store.oss-cn-beijing.aliyuncs.com';

            g_dirname = '';//设置存储目录，为空则为根目录
            g_object_name = '';
            g_object_name_type = '';
            now = timestamp = Date.parse(new Date()) / 1000;

            var policyText = {
                "expiration": "2020-01-01T12:00:00.000Z", //设置该Policy的失效时间，超过这个失效时间之后，就没有办法通过这个policy上传文件了
                "conditions": [
                    ["content-length-range", 0, 1048576000] // 设置上传文件的大小限制
                ]
            };

            var policyBase64 = Base64.encode(JSON.stringify(policyText));
            message = policyBase64;
            var bytes = Crypto.HMAC(Crypto.SHA1, message, accesskey, { asBytes: true });
            var signature = Crypto.util.bytesToBase64(bytes);

            function check_object_radio() {
                var tt = document.getElementsByName('myradio');
                for (var i = 0; i < tt.length; i++) {
                    if (tt[i].checked) {
                        g_object_name_type = tt[i].value;
                        break;
                    }
                }
            }

            function get_dirname() {
                dir = document.getElementById("dirname").value;
                if (dir != '' && dir.indexOf('/') != dir.length - 1) {
                    dir = dir + '/';
                }
                //alert(dir)
                g_dirname = dir;
            }

            function random_string(len) {
                len = len || 32;
                var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
                var maxPos = chars.length;
                var pwd = '';
                for (var i = 0; i < len; i++) {
                    pwd += chars.charAt(Math.floor(Math.random() * maxPos));
                }
                return pwd;
            }

            function get_suffix(filename) {
                var pos = filename.lastIndexOf('.');
                var suffix = '';
                if (pos != -1) {
                    suffix = filename.substring(pos);
                }
                return suffix;
            }

            function calculate_object_name(filename) {
                if (g_object_name_type == 'local_name') {
                    g_object_name += "${filename}";
                }
                else if (g_object_name_type == 'random_name') {
                    suffix = get_suffix(filename);
                    g_object_name = g_dirname + random_string(10) + suffix;
                }

                //$scope.$apply(function () {
                //    $scope.pic = host +"/"+ g_object_name;
                //});
                //console.log($scope.pic)
                return '';
            }

            function get_uploaded_object_name(filename) {
                if (g_object_name_type == 'local_name') {
                    tmp_name = g_object_name;
                    tmp_name = tmp_name.replace("${filename}", filename);
                    return tmp_name;
                }
                else if (g_object_name_type == 'random_name') {
                    return g_object_name;
                }
            }

            function previewImage(file, callback) {
                if (!file || !/image\//.test(file.type)) return;
                if (file.type == 'image/gif') {
                    var fr = new mOxie.FileReader();
                    fr.onload = function () {
                        callback(fr.result);
                        fr.destroy();
                        fr = null;
                    }
                    fr.readAsDataURL(file.getSource());
                } else {
                    var preloader = new mOxie.Image();
                    preloader.onload = function () {
                        //preloader.downsize(550, 400);//先压缩一下要预览的图片,宽300，高300
                        var imgsrc = preloader.type == 'image/jpeg' ? preloader.getAsDataURL('image/jpeg', 80) : preloader.getAsDataURL();
                        callback && callback(imgsrc);
                        preloader.destroy();
                        preloader = null;
                    };
                    preloader.load(file.getSource());
                }
            }

            //产品新增
            function set_upload_param(up, filename, ret) {
                if ($(".add_msgbox img").attr("src") == "images/icon_add2.png") {
                    return;
                }
                g_object_name = g_dirname;
                if (filename != '') {
                    suffix = get_suffix(filename);
                    calculate_object_name(filename);
                }
                new_multipart_params = {
                    'key': g_object_name,
                    'policy': policyBase64,
                    'OSSAccessKeyId': accessid,
                    'success_action_status': '200', //让服务端返回200,不然，默认会返回204
                    'signature': signature,
                };
                fileNames.push({
                    id: up.files[up.files.length - 1].id,
                    name: g_object_name
                })
                up.setOption({
                    'url': host,
                    'multipart_params': new_multipart_params
                });

                up.start();
            }

            var uploader = new plupload.Uploader({
                runtimes: 'html5,flash,silverlight,html4',
                browse_button: 'addImg',
                multi_selection: false,
                container: document.getElementById('image-list'),
                flash_swf_url: 'lib/plupload-2.1.2/js/Moxie.swf',
                silverlight_xap_url: 'lib/plupload-2.1.2/js/Moxie.xap',
                url: 'http://oss.aliyuncs.com',

                init: {
                    PostInit: function () {
                        document.getElementById('addNewGoods').onclick = function () {
                            set_upload_param(uploader, '', false);
                            // return false;
                        };
                    },

                    FilesAdded: function (up, files) {
                        for (var i = 0, len = files.length; i < len; i++) {
                            !function (i) {
                                previewImage(files[i],
                                    function (imgsrc) {
                                        //var html =
                                        //    '<div style="height: 4rem;width: 4rem;">' +
                                        //        '<img src="' +
                                        //        imgsrc +
                                        //        '" style="width: 4rem;height: 4rem;margin-left: .1rem;border-radius: .5rem;" />' +
                                        //        '<i class="img-del img-del' + files[i].id + ' glyphicon glyphicon-remove" data-val="' +
                                        //        files[i].id +
                                        //        '"><img src="style/images/close.jpg" alt=""></i>' +
                                        //        '</div>';
                                        //$('#image-list').append(html);
                                        $("#addImg img").attr("src", imgsrc);
                                    })
                            }(i);
                        }
                        //plupload.each(files, function(file) {
                        //	document.getElementById('ossfile').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ')<b></b>'
                        //	+'<div class="progress"><div class="progress-bar" style="width: 0%"></div></div>'
                        //	+'</div>';
                        //});
                    },

                    BeforeUpload: function (up, file) {
                        check_object_radio();
                        get_dirname();
                        set_upload_param(up, file.name, true);
                    },

                    UploadProgress: function (up, file) {
                        //var d = document.getElementById(file.id);
                        //d.getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                        //var prog = d.getElementsByTagName('div')[0];
                        //var progBar = prog.getElementsByTagName('div')[0]
                        //progBar.style.width= 2*file.percent+'px';
                        //progBar.setAttribute('aria-valuenow', file.percent);
                    },

                    FileUploaded: function (up, file, info) {
                        if (info.status == 200) {
                            $(".img-del" + file.id).css("display", "none");
                            //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = 'upload to oss success, object name:' + get_uploaded_object_name(file.name);
                        }
                        else {
                            //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = info.response;
                        }
                    },

                    UploadComplete: function (up, files, event) {
                        $scope.$apply(function () {
                            $scope.pic = host + "/" + fileNames[fileNames.length - 1].name;
                            console.log($scope.pic);
                            $.post("/portal/index.php?r=dailyReport/Local_upload_product",
                                JSON.stringify({
                                    token: $scope.token,
                                    order_id: $scope.orderId,
                                    subarea_id: $scope.type,
                                    name: $scope.name,
                                    price: $scope.price,
                                    cost: $scope.cost,
                                    total_inventory: $scope.inventory,
                                    unit: $scope.unit,
                                    amount: $scope.amount,
                                    remark: $scope.remark,
                                    description: $scope.description,
                                    img_list: $scope.pic
                                }),
                                function (data) {
                                    data = JSON.parse(data);
                                    $scope.$apply(function () {
                                        angular.forEach($scope.data.result.area_product,
                                            function (i) {
                                                if (i.area_id == $scope.addAreaId) {
                                                    i.product_list.push({
                                                        product_id: data.id,
                                                        ref_pic_url: $scope.pic,
                                                        product_name: $scope.name,
                                                        subareaid: $scope.type,
                                                        price: $scope.price,
                                                        unit: $scope.unit,
                                                        cost: $scope.cost,
                                                        inventory: $scope.inventory,
                                                        amount: $scope.amount,
                                                        description: $scope.description,
                                                        remark: $scope.remark
                                                    });
                                                    $("#local_upload").hide();
                                                    return;
                                                }
                                            });
                                    });
                                })
                                .error(function () {
                                    alert("网络错误，请稍后重试！");
                                });
                        });
                    },

                    Error: function (up, err) {
                        alert(err.response);
                        document.getElementById('console').appendChild(document.createTextNode("\nError xml:" + err.response));
                    }
                }
            });

            uploader.init();

            //仪式区效果图新增
            function set_upload_param2(up, filename, ret) {
                if ($(".ysq_add_msgbox img").attr("src") == "images/icon_add2.png") {
                    return;
                }
                g_object_name = g_dirname;
                if (filename != '') {
                    suffix = get_suffix(filename);
                    calculate_object_name(filename);
                }
                new_multipart_params = {
                    'key': g_object_name,
                    'policy': policyBase64,
                    'OSSAccessKeyId': accessid,
                    'success_action_status': '200', //让服务端返回200,不然，默认会返回204
                    'signature': signature,
                };
                if (up.files.length < 1) {
                    $("#addImg2").addClass("form_error");
                }
                fileNames2.push({
                    id: up.files[up.files.length - 1].id,
                    name: g_object_name
                });
                up.setOption({
                    'url': host,
                    'multipart_params': new_multipart_params
                });

                up.start();
            }

            var uploader2 = new plupload.Uploader({
                runtimes: 'html5,flash,silverlight,html4',
                browse_button: 'addImg2',
                multi_selection: false,
                container: document.getElementById('image-list2'),
                flash_swf_url: 'lib/plupload-2.1.2/js/Moxie.swf',
                silverlight_xap_url: 'lib/plupload-2.1.2/js/Moxie.xap',
                url: 'http://oss.aliyuncs.com',

                init: {
                    PostInit: function () {
                        document.getElementById('addNewGoods2').onclick = function () {
                            set_upload_param2(uploader2, '', false);
                            // return false;
                        };
                    },

                    FilesAdded: function (up, files) {
                        for (var i = 0, len = files.length; i < len; i++) {
                            !function (i) {
                                previewImage(files[i],
                                    function (imgsrc) {
                                        //var html =
                                        //    '<div style="height: 4rem;width: 4rem;">' +
                                        //        '<img src="' +
                                        //        imgsrc +
                                        //        '" style="width: 4rem;height: 4rem;margin-left: .1rem;border-radius: .5rem;" />' +
                                        //        '<i class="img-del img-del' + files[i].id + ' glyphicon glyphicon-remove" data-val="' +
                                        //        files[i].id +
                                        //        '"><img src="style/images/close.jpg" alt=""></i>' +
                                        //        '</div>';
                                        //$('#image-list').append(html);
                                        $("#addImg2 img").attr("src", imgsrc);
                                    })
                            }(i);
                        }
                        //plupload.each(files, function(file) {
                        //	document.getElementById('ossfile').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ')<b></b>'
                        //	+'<div class="progress"><div class="progress-bar" style="width: 0%"></div></div>'
                        //	+'</div>';
                        //});
                    },

                    BeforeUpload: function (up, file) {
                        check_object_radio();
                        get_dirname();
                        set_upload_param(up, file.name, true);
                    },

                    UploadProgress: function (up, file) {
                        //var d = document.getElementById(file.id);
                        //d.getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                        //var prog = d.getElementsByTagName('div')[0];
                        //var progBar = prog.getElementsByTagName('div')[0]
                        //progBar.style.width= 2*file.percent+'px';
                        //progBar.setAttribute('aria-valuenow', file.percent);
                    },

                    FileUploaded: function (up, file, info) {
                        if (info.status == 200) {
                            $(".img-del" + file.id).css("display", "none");
                            //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = 'upload to oss success, object name:' + get_uploaded_object_name(file.name);
                        }
                        else {
                            //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = info.response;
                        }
                    },

                    UploadComplete: function (up, files) {
                        $scope.$apply(function () {
                            $scope.ysqImg = host + "/" + fileNames[fileNames.length - 1].name;

                            if ($scope.YsqValidate()) {
                                $.post("/portal/index.php?r=background/Insert_order_show_img",
                                    JSON.stringify({
                                        subarea_id: 4,
                                        img_url: $scope.ysqImg,
                                        staff_id: $scope.token,
                                        description: $scope.ysqDesc
                                    }),
                                    function (data) {
                                        data = JSON.parse(data);
                                        if (data.id > 0) {
                                            $scope.$apply(function () {
                                                $scope.ysqList.push({
                                                    order_show_img_id: data.id,
                                                    img_url: $scope.ysqImg,
                                                    description: $scope.ysqDesc,
                                                    subarea_id: 4,
                                                    selected: false
                                                });
                                            });
                                            $(".ysq_add_msgbox").hide();
                                        } else {
                                            alert("操作失败！");
                                        }
                                    })
                                    .error(function () {
                                        alert("网络错误，请稍后重试！");
                                    });
                            }
                        });
                    },

                    Error: function (up, err) {
                        alert(err.response);
                        document.getElementById('console').appendChild(document.createTextNode("\nError xml:" + err.response));
                    }
                }
            });

            uploader2.init();

            //迎宾区效果图新增
            function set_upload_param3(up, filename, ret) {
                if ($(".ybq_add_msgbox img").attr("src") == "images/icon_add2.png") {
                    return;
                }
                g_object_name = g_dirname;
                if (filename != '') {
                    suffix = get_suffix(filename);
                    calculate_object_name(filename);
                }
                new_multipart_params = {
                    'key': g_object_name,
                    'policy': policyBase64,
                    'OSSAccessKeyId': accessid,
                    'success_action_status': '200', //让服务端返回200,不然，默认会返回204
                    'signature': signature,
                };
                if (up.files.length < 1) {
                    $("#addImg3").addClass("form_error");
                }
                fileNames3.push({
                    id: up.files[up.files.length - 1].id,
                    name: g_object_name
                });
                up.setOption({
                    'url': host,
                    'multipart_params': new_multipart_params
                });

                up.start();
            }

            var uploader3 = new plupload.Uploader({
                runtimes: 'html5,flash,silverlight,html4',
                browse_button: 'addImg3',
                multi_selection: false,
                container: document.getElementById('image-list3'),
                flash_swf_url: 'lib/plupload-2.1.2/js/Moxie.swf',
                silverlight_xap_url: 'lib/plupload-2.1.2/js/Moxie.xap',
                url: 'http://oss.aliyuncs.com',

                init: {
                    PostInit: function () {
                        document.getElementById('addNewGoods3').onclick = function () {
                            set_upload_param2(uploader3, '', false);
                            // return false;
                        };
                    },

                    FilesAdded: function (up, files) {
                        for (var i = 0, len = files.length; i < len; i++) {
                            !function (i) {
                                previewImage(files[i],
                                    function (imgsrc) {
                                        //var html =
                                        //    '<div style="height: 4rem;width: 4rem;">' +
                                        //        '<img src="' +
                                        //        imgsrc +
                                        //        '" style="width: 4rem;height: 4rem;margin-left: .1rem;border-radius: .5rem;" />' +
                                        //        '<i class="img-del img-del' + files[i].id + ' glyphicon glyphicon-remove" data-val="' +
                                        //        files[i].id +
                                        //        '"><img src="style/images/close.jpg" alt=""></i>' +
                                        //        '</div>';
                                        //$('#image-list').append(html);
                                        $("#addImg3 img").attr("src", imgsrc);
                                    })
                            }(i);
                        }
                        //plupload.each(files, function(file) {
                        //	document.getElementById('ossfile').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ')<b></b>'
                        //	+'<div class="progress"><div class="progress-bar" style="width: 0%"></div></div>'
                        //	+'</div>';
                        //});
                    },

                    BeforeUpload: function (up, file) {
                        check_object_radio();
                        get_dirname();
                        set_upload_param(up, file.name, true);
                    },

                    UploadProgress: function (up, file) {
                        //var d = document.getElementById(file.id);
                        //d.getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                        //var prog = d.getElementsByTagName('div')[0];
                        //var progBar = prog.getElementsByTagName('div')[0]
                        //progBar.style.width= 2*file.percent+'px';
                        //progBar.setAttribute('aria-valuenow', file.percent);
                    },

                    FileUploaded: function (up, file, info) {
                        if (info.status == 200) {
                            $(".img-del" + file.id).css("display", "none");
                            //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = 'upload to oss success, object name:' + get_uploaded_object_name(file.name);
                        }
                        else {
                            //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = info.response;
                        }
                    },

                    UploadComplete: function (up, files) {
                        $scope.$apply(function () {
                            $scope.ybqImg = host + "/" + fileNames[fileNames.length - 1].name;

                            if ($scope.YbqValidate()) {
                                $.post("/portal/index.php?r=background/Insert_order_show_img",
                                    JSON.stringify({
                                        subarea_id: 15,
                                        img_url: $scope.ybqImg,
                                        staff_id: $scope.token,
                                        description: $scope.ybqDesc
                                    }),
                                    function (data) {
                                        data = JSON.parse(data);
                                        if (data.id > 0) {
                                            $scope.$apply(function () {
                                                $scope.ybqList.push({
                                                    order_show_img_id: data.id,
                                                    img_url: $scope.ybqImg,
                                                    description: $scope.ybqDesc,
                                                    subarea_id: 15,
                                                    selected: false
                                                });
                                            });
                                            $(".ybq_add_msgbox").hide();

                                        } else {
                                            alert("操作失败！");
                                        }
                                    })
                                    .error(function () {
                                        alert("网络错误，请稍后重试！");
                                    });
                            }
                        });
                    },

                    Error: function (up, err) {
                        alert(err.response);
                        document.getElementById('console').appendChild(document.createTextNode("\nError xml:" + err.response));
                    }
                }
            });

            uploader3.init();
        }
    ])
    .filter("inArray",
    function () {
        return function (array, id) {
            angular.forEach(array,
                function (i, t) {
                    if (i.id == id) {
                        return true;
                    }
                });
            return false;
        }
    });

$("#sidebar_scroll").on('click', '.sidebar_item', function () {
    var _dataid = $(this).data('id');
    mainScroll.scrollToElement($("#" + _dataid)[0], 400);
})

$("#sidebar_scroll").on('click', '.nli', function (e) {
    var _dataid = $(this).data('id');
    console.log(_dataid)
    mainScroll.scrollToElement($("#" + _dataid)[0], 400);
});
