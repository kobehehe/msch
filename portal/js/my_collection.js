angular.module("ms", ["wu.masonry"])
    .controller('myCollection',
    [
        "$scope", "$http", "$compile", "$timeout", function ($scope, $http, $compile, $timeout) {
            $scope.orderId = "";
            $scope.token = "";
            $scope.area_id = "";

            //分页
            $scope.curPage = 1;

            //筛选1
            $scope.selectedTypeId = "";
            $scope.selectedType = null;
            $scope.typeList = [];

            //筛选2
            $scope.selectedSubTypeId = "";
            $scope.selectedSubType = null;
            $scope.subTypeList = [];

            //商品列表
            $scope.productList = [];

            //购物车
            $scope.ShopCarList = [];

            //弹窗
            $scope.PopConfirm = null;

            //表单
            $scope.local_URL = null;
            $scope.typ = null;
            $scope.name = null;
            $scope.unit = null;
            $scope.price = null;
            $scope.cost = null;
            $scope.amount = null;
            $scope.description = null;
            $scope.remark = null;
            $scope.typList = [];

            //操作后，调接口刷新商品列表，重新分页
            $scope.GetData = function () {
                $http
                    .get("http://crm.cike360.com/portal/index.php?r=background/My_folders&token=" +
                    $scope.token +
                    "&area_id=" +
                    $scope.area_id +
                    "&folder_id=" +
                    $scope.selectedSubTypeId +
                    "&page=" +
                    $scope.curPage +
                    "&subarea_id=" +
                    $scope.subarea_id)
                    .success(function (data) {
                        $scope.productList = [];
                        var shopCar = localStorage.getItem("shopCar2");
                        if (shopCar != null) {
                            $scope.ShopCarList = JSON.parse(shopCar);
                        }
                        angular.forEach(data.folder_img,
                            function (i, x) {
                                i.name = null;
                                i.typ = null;
                                i.unit = null;
                                i.price = null;
                                i.cost = null;
                                i.amount = null;
                                i.description = null;
                                i.remark = null;
                                if ($scope.ShopCarList.length > 0) {
                                    angular.forEach($scope.ShopCarList,
                                        function (n, m) {
                                            if (n.Img_ID == i.Img_ID) {
                                                i.name = n.name;
                                                i.typ = n.typ;
                                                i.unit = n.unit;
                                                i.price = n.price;
                                                i.cost = n.cost;
                                                i.amount = n.amount;
                                                i.description = n.description;
                                                i.remark = n.remark;
                                                return;
                                            }
                                        });
                                }
                                $scope.productList.push(i);
                            });
                    });
            }

            //追加数据操作
            $scope.AppendData = function () {
                $http
                    .get("http://crm.cike360.com/portal/index.php?r=background/My_folders&token=" +
                    $scope.token +
                    "&area_id=" +
                    $scope.area_id +
                    "&folder_id=" +
                    $scope.selectedSubTypeId +
                    "&page=" +
                    $scope.curPage +
                    "&subarea_id=" +
                    $scope.subarea_id)
                    .success(function (data) {
                        var shopCar = localStorage.getItem("shopCar2");
                        if (shopCar != null) {
                            $scope.ShopCarList = JSON.parse(shopCar);
                        }
                        angular.forEach(data.folder_img,
                            function (i, x) {
                                i.name = null;
                                i.typ = null;
                                i.unit = null;
                                i.price = null;
                                i.cost = null;
                                i.amount = null;
                                i.description = null;
                                i.remark = null;
                                if ($scope.ShopCarList.length > 0) {
                                    angular.forEach($scope.ShopCarList,
                                        function (n, m) {
                                            if (n.Img_ID == i.Img_ID) {
                                                i.name = n.name;
                                                i.typ = n.typ;
                                                i.unit = n.unit;
                                                i.price = n.price;
                                                i.cost = n.cost;
                                                i.amount = n.amount;
                                                i.description = n.description;
                                                i.remark = n.remark;
                                                return;
                                            }
                                        });
                                }
                                $scope.productList.push(i);
                            });
                    });
            }

            //初始化
            $scope.Init = function () {
                $scope.orderId = imi.GetQueryString("order_id") != null ? imi.GetQueryString("order_id") : "";
                $scope.token = imi.GetQueryString("token") != null ? imi.GetQueryString("token") : "";
                $scope.area_id = imi.GetQueryString("area_id") != null ? imi.GetQueryString("area_id") : "";
                $scope.subarea_id = imi.GetQueryString("subarea_id") != null ? imi.GetQueryString("subarea_id") : "";
                $scope.selectedSubTypeId = imi.GetQueryString("folder_id") != null
                    ? imi.GetQueryString("folder_id")
                    : "";
                $scope.curPage = imi.GetQueryString("page") != null ? imi.GetQueryString("page") : 1;
                $('body').bread_nav({
                    father_class: 'bread_nav',
                    nav_name_list:[{
                        nav_name: '首页',
                        nav_link: 'index.php?r=background/index_front'
                    },{
                        nav_name: localStorage.getItem('bread_order_name'),
                        nav_link: 'index.php?r=background/price_list&token='+$scope.token+'&order_id='+$scope.orderId
                    },{
                        nav_name: localStorage.getItem('bread_area_name'),
                        nav_link: 'index.php?r=background/share_store_view&token='+$scope.token+'&order_id='+$scope.orderId+'&area_id='+imi.GetQueryString("area_id")+'&service_person_list=&low_price=&high_price=&subarea_id='+imi.GetQueryString("subarea_id")+'&page=1&scroll_id=area'+imi.GetQueryString("area_id")
                    },{
                        nav_name: '我的收藏',
                        nav_link: ''
                    }]
                });
                $http
                    .get("http://crm.cike360.com/portal/index.php?r=background/My_folders&token=" +
                    $scope.token +
                    "&area_id=" +
                    $scope.area_id +
                    "&folder_id=" +
                    $scope.selectedSubTypeId +
                    "&page=" +
                    $scope.curPage +
                    "&subarea_id=" +
                    $scope.subarea_id)
                    .success(function (data) {
                        //console.log(JSON.stringify(data));
                        $scope.selectedTypeId = data.folder_type[0].id;
                        $scope.selectedType = data.folder_type[0];
                        $scope.typeList = data.folder_type;

                        //var ass = [{ id: 100, name: "12123" }, { id: 1001, name: "12123" }, { id: 10031, name: "12123" }, { id: 10301, name: "12123" }, { id: 13001, name: "12123" }, { id: 10041, name: "12123" }, { id: 10401, name: "12123" }, { id: 14001, name: "12123" }, { id: 10051, name: "12123" }, { id: 10501, name: "12123" }, { id: 105061, name: "12123" }, { id: 105601, name: "121hhj23" }, { id: 160501, name: "dd" }, { id: 170501, name: "121ss23" }, { id: 105701, name: "asd" }];
                        //angular.forEach(ass, function (i) {
                        //    $scope.typeList.push(i);
                        //})

                        $scope.selectedSubTypeId = data.folder_type[0].folder_list[0].id;
                        $scope.selectedSubType = data.folder_type[0].folder_list[0];
                        $scope.subTypeList = data.folder_type[0].folder_list;

                        $scope.typList = data.subarea;
                        $scope.typ = $scope.typList[0].id;;


                        var shopCar = localStorage.getItem("shopCar2");
                        if (shopCar != null) {
                            $scope.ShopCarList = JSON.parse(shopCar);
                        }
                        angular.forEach(data.folder_img,
                            function (i, x) {
                                i.name = null;
                                i.typ = null;
                                i.unit = null;
                                i.price = null;
                                i.cost = null;
                                i.amount = null;
                                i.description = null;
                                i.remark = null;
                                if ($scope.ShopCarList.length > 0) {
                                    angular.forEach($scope.ShopCarList,
                                        function (n, m) {
                                            if (n.Img_ID == i.Img_ID) {
                                                i.name = n.name;
                                                i.typ = n.typ;
                                                i.unit = n.unit;
                                                i.price = n.price;
                                                i.cost = n.cost;
                                                i.amount = n.amount;
                                                i.description = n.description;
                                                i.remark = n.remark;
                                                return;
                                            }
                                        });
                                }
                                $scope.productList.push(i);
                            });

                        $timeout(function () {
                            $scope.FilterInit();
                        },
                            300);
                    });
            }
            $scope.Init();

            //筛选1
            $scope.TypeSearch = function (item) {
                if ($scope.selectedTypeId == item.id) {
                    $scope.selectedTypeId = "";
                    $scope.selectedType = null;

                    $scope.selectedSubTypeId = "";
                    $scope.selectedSubType = null;
                    $scope.subTypeList = [];
                } else {
                    $scope.selectedTypeId = item.id;
                    $scope.selectedType = item;

                    $scope.subTypeList = item.folder_list;
                    $scope.selectedSubTypeId = item.folder_list[0].id;
                    $scope.selectedSubType = item.folder_list[0];
                }
                $scope.curPage = 1;
                $scope.GetData();
            }

            //筛选2
            $scope.SubTypeSearch = function (item) {
                if ($scope.selectedSubTypeId == item.id) {
                    angular.forEach($scope.typeList,
                        function (i, x) {
                            if (i.id == $scope.selectedTypeId) {
                                $scope.selectedSubTypeId = i.folder_list[0].id;
                                $scope.selectedSubType = i.folder_list[0];
                                return;
                            }
                        });
                } else {
                    $scope.selectedSubTypeId = item.id;
                    $scope.selectedSubType = item;
                }
                $scope.curPage = 1;
                $scope.GetData();
            }

            //加入购物车
            $scope.AddToShopCar = function (item, e) {

                bombBox({
                    entrance: $('.add_msgbox'),
                    initFun: function () {
                        $scope.local_URL = item.local_URL;
                        $scope.name = item.name;
                        // $scope.typ = item.typ;
                        $scope.unit = item.unit;
                        $scope.price = item.price;
                        $scope.cost = item.cost;
                        $scope.amount = item.amount;
                        $scope.description = item.description;
                        $scope.remark = item.remark;
                    }
                });

                $scope.PopConfirm = function ($event) {
                    if ($scope.name == "" || $scope.name == null) {
                        alert("您尚未填写名称！");
                        return;
                    }
                    if ($scope.typ == "" || $scope.typ == null) {
                        alert("您尚未选择区域！");
                        return;
                    }
                    if ($scope.unit == "" || $scope.unit == null) {
                        alert("您尚未填写单位！");
                        return;
                    }
                    if ($scope.price == "" || $scope.price == null) {
                        alert("您尚未填写单价！");
                        return;
                    }
                    if ($scope.cost == "" || $scope.cost == null) {
                        alert("您尚未填写单位成本！");
                        return;
                    }
                    if ($scope.amount == "" || $scope.amount == null) {
                        alert("您尚未填写数量！");
                        return;
                    }
                    $($event.target).parents(".msgbox").hide();
                    var cartOffset = $(".fixed_cart").offset();
                    var img = $(e.target).parents('.waterfall_item').find('img').attr('src');
                    var flyer = $('<img class="u-flyer" src="' + img + '">');
                    flyer.fly({
                        start: {
                            left: $(e.target).parents('.waterfall_item').find('.addbtn').offset().left -
                            $('body').scrollLeft(),
                            top: $(e.target).parents('.waterfall_item').find('.addbtn').offset().top -
                            $('body').scrollTop()
                        },
                        end: {
                            left: cartOffset.left + 10,
                            top: cartOffset.top + 10,
                            width: 0,
                            height: 0
                        },
                        onEnd: function () {
                            $scope.$apply(function () {
                                item.local_URL = $scope.local_URL;
                                item.name = $scope.name;
                                item.typ = $scope.typ;
                                item.unit = $scope.unit;
                                item.price = $scope.price;
                                item.cost = $scope.cost;
                                item.amount = $scope.amount;
                                item.description = $scope.description;
                                item.remark = $scope.remark;
                                $scope.ShopCarList.push(item);
                                localStorage.setItem("shopCar2", JSON.stringify($scope.ShopCarList));
                            });
                            this.destory();
                        }
                    });
                }
            }

            //清空购物车
            $scope.RemoveShopCar = function () {
                localStorage.removeItem("shopCar2");
                $scope.ShopCarList = [];
                angular.forEach($scope.productList,
                    function (i, x) {
                        i.name = null;
                        i.typ = null;
                        i.unit = null;
                        i.price = null;
                        i.cost = null;
                        i.amount = null;
                        i.description = null;
                        i.remark = null;
                    });
            }

            //减少数量
            $scope.CountLower = function (item) {
                if (item.amount > 1) {
                    item.amount = parseInt(item.amount) - 1;
                    angular.forEach($scope.productList,
                        function (i, x) {
                            if (i.Img_ID == item.Img_ID) {
                                i.amount = item.amount;
                                return;
                            }
                        });
                    angular.forEach($scope.ShopCarList,
                        function (i, x) {
                            if (i.Img_ID == item.Img_ID) {
                                i.amount = item.amount;
                                return;
                            }
                        });
                } else {
                    angular.forEach($scope.productList,
                        function (i, x) {
                            if (i.Img_ID == item.Img_ID) {
                                i.name = null;
                                i.typ = null;
                                i.unit = null;
                                i.price = null;
                                i.cost = null;
                                i.amount = null;
                                i.description = null;
                                i.remark = null;
                                return;
                            }
                        });
                    angular.forEach($scope.ShopCarList,
                        function (i, x) {
                            if (i.Img_ID == item.Img_ID) {
                                $scope.ShopCarList.splice(x, 1);
                                return;
                            }
                        });
                }
                localStorage.setItem("shopCar2", JSON.stringify($scope.ShopCarList));
            }

            //增加数量
            $scope.CountUper = function (item) {
                if (item.amount < 10000) {
                    item.amount = parseInt(item.amount) + 1;
                    angular.forEach($scope.productList,
                        function (i, x) {
                            if (i.Img_ID == item.Img_ID) {
                                i.amount = item.amount;
                                return;
                            }
                        });
                    angular.forEach($scope.ShopCarList,
                        function (i, x) {
                            if (i.Img_ID == item.Img_ID) {
                                i.amount = item.amount;
                                return;
                            }
                        });
                }
                localStorage.setItem("shopCar2", JSON.stringify($scope.ShopCarList));
            }

            //同步数量
            $scope.AsyCount = function (item) {
                angular.forEach($scope.productList,
                    function (i, x) {
                        if (i.Img_ID == item.Img_ID) {
                            i.amount = item.amount;
                            return;
                        }
                    });
                angular.forEach($scope.ShopCarList,
                    function (i, x) {
                        if (i.Img_ID == item.Img_ID) {
                            i.amount = item.amount;
                            return;
                        }
                    });
                localStorage.setItem("shopCar2", JSON.stringify($scope.ShopCarList));
            }

            //结算
            $scope.Batch = function () {
                var proList = "";
                angular.forEach($scope.ShopCarList,
                    function (i, x) {
                        if (x > 0) {
                            proList += ",";
                        }
                        proList += i.Img_ID +
                            "|folder|" +
                            i.typ +
                            "|" +
                            i.name +
                            "|" +
                            i.price +
                            "|" +
                            i.cost +
                            "|" +
                            i.unit +
                            "|" +
                            i.amount +
                            "|" +
                            i.description +
                            "|" +
                            i.remark;
                    });
                $.post("/portal/index.php?r=background/Batch_new_product_insert",
                    JSON.stringify({
                        token: $scope.token,
                        order_id: $scope.orderId,
                        product_list: proList
                    }),
                    function (data) {
                        localStorage.removeItem("shopCar2");
                        alert("操作成功！");
                        location.href = 'index.php?r=background/share_store_view&token='+$scope.token+'&order_id='+$scope.orderId+'&area_id='+imi.GetQueryString("area_id")+'&service_person_list=&low_price=&high_price=&subarea_id='+imi.GetQueryString("subarea_id")+'&page=1&scroll_id=area'+imi.GetQueryString("area_id");
                    })
                    .error(function () {
                        alert("网络错误，请稍后重试！");
                    });
            }

            //筛选初始化
            $scope.FilterInit = function () {
                var liHeight = $('.filter_list li').height();
                for (var i = 0; i < $('.filter_list li').length; i++) {
                    var listHeight = $('.filter_list li').eq(i).find('.list')[0].offsetHeight;
                    if (listHeight - 5 > liHeight) {
                        $('.filter_list li').eq(i).find('.morebtn').show();
                    }
                }

                $(".filter_list")
                    .on('click',
                    '.morebtn',
                    function () {
                        var $this = $(this);
                        if ($this.hasClass('up')) {
                            $this.parents('li').css('height', liHeight);
                            $this.removeClass('up');
                        } else {
                            $this.parents('li').css('height', $this.parents('li').find('.list')[0].offsetHeight);
                            $this.addClass('up');
                        }
                    });
            }

            //下拉追加
            var loader = new Loadmore($('.waterfall_box')[0], {
                loadMore: function (page, done) {
                    setTimeout(function () {
                        $scope.curPage += 1;
                        $scope.AppendData();
                        //没数据测试用
                        //for (var i = 0; i < 10; i++) {
                        //    $scope.productList.push({
                        //        Img_ID: 1000,
                        //        local_URL: "http://inspitation-img-store.oss-cn-beijing.aliyuncs.com/inspiration/t2jjb0ddxm1oldp5.sm.jpg"
                        //    }, {
                        //        Img_ID: 1000,
                        //        local_URL: "http://inspitation-img-store.oss-cn-beijing.aliyuncs.com/inspiration/ijge3in3h5mlyf9t.sm.jpg"
                        //    }, {
                        //        Img_ID: 1000,
                        //        local_URL: "http://inspitation-img-store.oss-cn-beijing.aliyuncs.com/inspiration/e3ny64ybh5vvsupd.sm.jpg"
                        //    });
                        //}

                        done();

                    }, 500);

                },
                bottomBuffer: 0 //预加载临界值
            });
        }
    ])
