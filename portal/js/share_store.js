var loadOff = true;
angular.module("ms", [])
    .controller('shareStore',
    [
        "$scope", "$http", "$compile", "$timeout", function ($scope, $http, $compile, $timeout) {
            $scope.orderId = "";
            $scope.token = "";

            $scope.addGoods = false;
            $scope.RedirectMyCollection = function () {
                localStorage.setItem("ShopCarList", JSON.stringify($scope.ShopCarList));
                var url = $("#hidMyCollection").val() +
                    "&token=" +
                    $("#hidToken").val() +
                    "&order_id=" +
                    $("#hidOrderId").val() +
                    "&subarea_id=" +
                    $scope.selectedSubAreaId +
                    "&area_id=" + imi.GetQueryString("area_id");
                location.href = url;
            };

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
            $scope.AddGoods = function () {
                bombBox({
                    entrance: $('#local_upload'),
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

            //分页
            $scope.pages = [];
            $scope.curPage = 1;
            $scope.pageLength = 10;
            $scope.pageSize = 8;
            $scope.pageCount = 0;
            $scope.rowTotalCount = 999;
            $scope.isLoadMore = false;

            //
            $scope.areaId = null;
            //城市筛选
            $scope.selectedAreaId = "";
            $scope.selectedArea = null;
            $scope.areaList = [];

            //供应商筛选
            $scope.selectedServicePeronId = "";
            $scope.selectedServicePeron = null;
            $scope.servicePeronList = [];

            //区域筛选
            $scope.selectedSubAreaId = "";
            $scope.selectedSubArea = null;
            $scope.subAreaList = [];

            //价格筛选
            $scope.lowPrice = "";
            $scope.lowPriceInt = "";
            $scope.highPrice = "";
            $scope.highPriceInt = "";

            //商品列表
            $scope.productList = [];

            //购物车
            $scope.ShopCarList = [];

            //大图
            $scope.bigImg = null;

            //分页方法
            $scope.GetPagingData = function (i) {
                $scope.curPage = i;
                $scope.GetData();
            }
            $scope.GetPrePage = function () {
                if ($scope.curPage > 1) {
                    $scope.curPage -= 1;
                    $scope.GetData();
                }
            }
            $scope.GetNextPage = function () {
                if ($scope.curPage < $scope.pageCount) {
                    $scope.curPage += 1;
                    $scope.GetData();
                }
            }

            //操作后，调接口刷新商品列表，重新分页
            $scope.GetData = function () {
                $http
                    .get("/portal/index.php?r=background/Share_store&order_id=" +
                    $scope.orderId +
                    "&token=" +
                    $scope.token +
                    "&area_id=" +
                    $scope.areaId +
                    "&service_person_list=" +
                    $scope.selectedServicePeronId +
                    "&subarea_id=" +
                    $scope.selectedSubAreaId +
                    "&low_price=" +
                    $scope.lowPrice +
                    "&high_price=" +
                    $scope.highPrice +
                    "&page=" +
                    $scope.curPage)
                    .success(function (data) {
                        $scope.productList = [];
                        var shopCar = localStorage.getItem("shopCar");
                        if (shopCar != null) {
                            $scope.ShopCarList = JSON.parse(shopCar);
                        }
                        angular.forEach(data.product.product_list,
                            function (i, x) {
                                i.amount = 0;
                                if ($scope.ShopCarList.length > 0) {
                                    angular.forEach($scope.ShopCarList,
                                        function (n, m) {
                                            if (n.service_product_id == i.service_product_id) {
                                                i.amount = n.amount;
                                                return;
                                            }
                                        });
                                }
                                $scope.productList.push(i);
                            });
                        $scope.pageCount = data.product.total_page;
                        $scope.pages = imi.GetPagingIndexes($scope.curPage, $scope.pageCount, $scope.pageLength);
                    });
            }

            $scope.AppendPagingData = function () {
                if ($scope.curPage > $scope.pageCount) {
                    loadOff = false;
                    $scope.isLoadMore = false;
                    return;
                }
                $http
                    .get("/portal/index.php?r=background/Share_store&order_id=" +
                    $scope.orderId +
                    "&token=" +
                    $scope.token +
                    "&area_id=" +
                    $scope.areaId +
                    "&service_person_list=" +
                    $scope.selectedServicePeronId +
                    "&subarea_id=" +
                    $scope.selectedSubAreaId +
                    "&low_price=" +
                    $scope.lowPrice +
                    "&high_price=" +
                    $scope.highPrice +
                    "&page=" +
                    $scope.curPage)
                    .success(function (data) {
                        var shopCar = localStorage.getItem("shopCar");
                        if (shopCar != null) {
                            $scope.ShopCarList = JSON.parse(shopCar);
                        }
                        angular.forEach(data.product.product_list,
                            function (i, x) {
                                i.amount = 0;
                                if ($scope.ShopCarList.length > 0) {
                                    angular.forEach($scope.ShopCarList,
                                        function (n, m) {
                                            if (n.service_product_id == i.service_product_id) {
                                                i.amount = n.amount;
                                                return;
                                            }
                                        });
                                }
                                $scope.productList.push(i);
                            });
                        loadOff = true;
                        $scope.isLoadMore = false;
                    });
            }

            $scope.Init = function () {
                $scope.orderId = imi.GetQueryString("order_id") != null ? imi.GetQueryString("order_id") : "";
                $scope.token = imi.GetQueryString("token") != null ? imi.GetQueryString("token") : "";
                $scope.areaId = imi.GetQueryString("area_id") != null ? imi.GetQueryString("area_id") : "";
                $scope.selectedAreaId = imi.GetQueryString("city_id") != null ? imi.GetQueryString("city_id") : "";
                $scope.selectedServicePeronId = imi.GetQueryString("service_person_list") != null ? imi.GetQueryString("service_person_list") : "";
                $scope.selectedSubAreaId = imi.GetQueryString("subarea_id") != null ? imi.GetQueryString("subarea_id") : "";
                $scope.lowPrice = imi.GetQueryString("low_price") != null ? imi.GetQueryString("low_price") : "";
                $scope.highPrice = imi.GetQueryString("high_price") != null ? imi.GetQueryString("high_price") : "";
                $scope.curPage = imi.GetQueryString("page") != null ? parseInt(imi.GetQueryString("page")) : 1;

                $scope.lowPriceInt = $scope.lowPrice;
                $scope.highPriceInt = $scope.highPrice;

                //渲染购物车
                var car = localStorage.getItem("ShopCarList");
                if(car != '' && car != null && car != undefined && car != 'null' && car != 'undefined'){
                    var json_car = JSON.parse(car);
                    $.each(json_car, function(e, i){
                        $scope.ShopCarList.push(e);
                    });
                };


                /**********产品编辑事件**********/
                //1、渲染、显示弹框
                $(".edit_btn").live('click', function(){
                    var data_id = $(this).parents('li.clearfix').find('.title').attr('data-id');
                    var ele = $scope.productList[data_id];
                    $("#product_edit").find("img").attr('src', ele.ref_pic_url);
                    $("#e_name").val(ele.name);
                    $("#e_price").val(ele.price);
                    $("#e_unit").val(ele.unit);
                    $("#e_cost").val(ele.cost);
                    $("#e_inventory").val(ele.inventory);
                    $("#e_desc").val(ele.description);
                    $("#editGoods").attr('service_product_id', ele.service_product_id).attr('data-id', ele.data_id);
                    $("#delGoods").attr('service_product_id', ele.service_product_id).attr('data-id', ele.data_id);

                    $("#product_edit").show();
                });
                //2、关闭弹框
                $(".mask,.close").live('click', function(){
                    $("#product_edit").hide();
                });
                //3、删除产品
                $("#delGoods").live("click", function(){
                    $.post("/portal/index.php?r=background/Service_product_del",
                        JSON.stringify({
                            service_product_id: $(this).attr("service_product_id")
                        }),
                        function (data) {
                            location.reload();
                        })
                        .error(function () {
                            alert("网络错误，请稍后重试！");
                        }
                    );
                });
                //4、修改产品
                $("#editGoods").live("click", function(){
                    $.post("/portal/index.php?r=background/edit_service_product",
                        JSON.stringify({
                            service_product_id: $(this).attr("service_product_id"),
                            name: $("#e_name").val(),
                            price: $("#e_price").val(),
                            unit: $("#e_unit").val(),
                            cost: $("#e_cost").val(),
                            inventory: $("#e_inventory").val(),
                            description: $("#e_desc").val()
                        }),
                        function (data) {
                            location.reload();
                        })
                        .error(function () {
                            alert("网络错误，请稍后重试！");
                        }
                    );
                });


                //面包屑导航
                $('body').bread_nav({
                    father_class: 'bread_nav',
                    nav_name_list: [{
                        nav_name: '首页',
                        nav_link: 'index.php?r=background/index_front'
                    }, {
                        nav_name: localStorage.getItem('bread_order_name'),
                        nav_link: 'index.php?r=background/price_list&order_id=' + imi.GetQueryString("order_id") + '&token=' + imi.GetQueryString("token")
                    }, {
                        nav_name: localStorage.getItem('bread_area_name'),
                        nav_link: ''
                    }]
                });


                $http
                    .get("/portal/index.php?r=background/Share_store&order_id=" +
                    $scope.orderId +
                    "&token=" +
                    $scope.token +
                    "&area_id=" +
                    $scope.areaId +
                    "&service_person_list=" +
                    $scope.selectedServicePeronId +
                    "&subarea_id=" +
                    $scope.selectedSubAreaId +
                    "&low_price=" +
                    $scope.lowPrice +
                    "&high_price=" +
                    $scope.highPrice +
                    "&page=" +
                    $scope.curPage)
                    .success(function (data) {

                        console.log(data);
                        $scope.areaList = data.city;
                        $scope.servicePeronList = data.city[0].service_person_list;
                        $scope.subAreaList = data.subarea;
                        if ($scope.selectedAreaId == "") {
                            $scope.selectedAreaId = data.city[0].id;
                        }
                        if ($scope.selectedServicePeronId == "") {
                            $scope.selectedServicePeronId = data.city[0].service_person_list[0].id;
                        }
                        if ($scope.selectedSubAreaId == "") {
                            $scope.selectedSubAreaId = data.city[0].service_person_list[0].subarea[0].id;
                        }
                        //$scope.selectedAreaId = 1;
                        angular.forEach(data.city,
                            function (i) {
                                if (i.id == $scope.selectedAreaId) {
                                    $scope.selectedArea = i;
                                    angular.forEach(i.service_person_list,
                                        function (j) {
                                            if (j.id == $scope.selectedServicePeronId) {
                                                $scope.selectedServicePeron = j;
                                                angular.forEach(j.subarea,
                                                    function (k) {
                                                        if (k.id == $scope.selectedSubAreaId) {
                                                            $scope.selectedSubArea = k;
                                                            return;
                                                        }
                                                    });
                                                return;
                                            }
                                        });
                                    return;
                                }
                            });

                        var shopCar = localStorage.getItem("shopCar");
                        if (shopCar != null) {
                            $scope.ShopCarList = JSON.parse(shopCar);
                        }
                        angular.forEach(data.product.product_list,
                            function (i, x) {
                                i.amount = 0;
                                if ($scope.ShopCarList.length > 0) {
                                    angular.forEach($scope.ShopCarList,
                                        function (n, m) {
                                            if (n.service_product_id == i.service_product_id) {
                                                i.amount = n.amount;
                                                return;
                                            }
                                        });
                                }
                                $scope.productList.push(i);
                            });
                        $scope.pageCount = data.product.total_page;
                        $scope.pages = imi.GetPagingIndexes($scope.curPage, $scope.pageCount, $scope.pageLength);
                        $scope.FilterInit();
                    });
            }
            $scope.Init();

            //城市筛选
            $scope.AreaSearch = function (item) {
                if ($scope.selectedAreaId == item.id) {
                    $scope.selectedAreaId = "";
                    $scope.selectedArea = null;

                    $scope.selectedServicePeronId = "";
                    $scope.selectedServicePeron = null;
                    $scope.servicePeronList = [];

                    $scope.selectedSubAreaId = "";
                    $scope.selectedSubArea = null;
                    $scope.subAreaList = [];
                } else {
                    $scope.selectedAreaId = item.id;
                    $scope.selectedArea = item;

                    $scope.selectedServicePeronId = item.service_person_list[0].id;
                    $scope.selectedServicePeron = item.service_person_list[0];
                    $scope.servicePeronList = item.service_person_list;

                    $scope.selectedSubAreaId = item.service_person_list[0].subarea[0].id;
                    $scope.selectedSubArea = item.service_person_list[0].subarea[0];
                    $scope.subAreaList = item.service_person_list[0].subarea;

                    $scope.FilterInit();
                }
                $scope.curPage = 1;
                loadOff = true;
                $scope.GetData();
            }

            //供应商筛选
            $scope.PersonSearch = function (item) {
                if ($scope.selectedServicePeronId == item.id) {
                    $scope.selectedServicePeronId = "";
                    $scope.selectedServicePeron = null;

                    $scope.selectedSubAreaId = "";
                    $scope.selectedSubArea = null;
                    $scope.subAreaList = [];
                } else {
                    $scope.selectedServicePeronId = item.id;
                    $scope.selectedServicePeron = item;

                    $scope.selectedSubAreaId = item.subarea[0].id;
                    $scope.selectedSubArea = item.subarea[0];
                    $scope.subAreaList = item.subarea;

                    $scope.FilterInit();
                }
                $scope.curPage = 1;
                loadOff = true;
                $scope.GetData();
            }

            //区域筛选
            $scope.SubAreaSearch = function (item) {
                if ($scope.selectedSubAreaId == item.id) {
                    $scope.selectedSubAreaId = "";
                    $scope.selectedSubArea = null;
                } else {
                    $scope.selectedSubAreaId = item.id;
                    $scope.selectedSubArea = item;
                }
                $scope.curPage = 1;
                loadOff = true;
                $scope.GetData();
            }

            //价格筛选
            $scope.PriceSearch = function () {
                $scope.lowPrice = $scope.lowPriceInt;
                $scope.highPrice = $scope.highPriceInt;
                $scope.curPage = 1;
                loadOff = true;
                $scope.GetData();
            }

            //价格筛选
            $('.self_price')
                .mouseover(function () {
                    $(this).addClass('active').find('.surebtn').show();
                })
                .mouseout(function () {
                    $(this).removeClass('active').find('.surebtn').hide();
                });

            //加入购物车
            $scope.AddToShopCar = function (item, e) {
                item.amount = 1;
                item.remark = "";
                /*加入订单 飞入效果*/
                var cartOffset = $(".fixed_cart").offset();
                var img = $(e.target).parents('li').find('img').attr('src');
                var flyer = $('<img class="u-flyer" src="' + img + '">');
                flyer.fly({
                    start: {
                        left: e.pageX,
                        top: e.pageY
                    },
                    end: {
                        left: cartOffset.left + 10,
                        top: cartOffset.top + 10,
                        width: 0,
                        height: 0
                    },
                    onEnd: function () {
                        $scope.$apply(function () {
                            $scope.ShopCarList.push(item);
                            localStorage.setItem("shopCar", JSON.stringify($scope.ShopCarList));
                        });
                        this.destory();
                    }
                });
            }

            //清空购物车
            $scope.RemoveShopCar = function () {
                localStorage.removeItem("shopCar");
                $scope.ShopCarList = [];
                angular.forEach($scope.productList,
                    function (i, x) {
                        i.amount = 0;
                    });
            }

            //减少数量
            $scope.CountLower = function (item) {
                if (item.amount > 1) {
                    item.amount = parseInt(item.amount) - 1;
                    angular.forEach($scope.productList,
                        function (i, x) {
                            if (i.service_product_id == item.service_product_id) {
                                i.amount = item.amount;
                                return;
                            }
                        });
                    angular.forEach($scope.ShopCarList,
                        function (i, x) {
                            if (i.service_product_id == item.service_product_id) {
                                i.amount = item.amount;
                                return;
                            }
                        });
                } else {
                    angular.forEach($scope.productList,
                        function (i, x) {
                            if (i.service_product_id == item.service_product_id) {
                                i.amount = 0;
                                return;
                            }
                        });
                    angular.forEach($scope.ShopCarList,
                        function (i, x) {
                            if (i.service_product_id == item.service_product_id) {
                                $scope.ShopCarList.splice(x, 1);
                                return;
                            }
                        });
                }
                localStorage.setItem("shopCar", JSON.stringify($scope.ShopCarList));
            }

            //增加数量
            $scope.CountUper = function (item) {
                if (item.amount < 10000) {
                    item.amount = parseInt(item.amount) + 1;
                    $scope.AsyCount(item);
                }
            }

            //同步数量
            $scope.AsyCount = function (item) {
                angular.forEach($scope.productList,
                    function (i, x) {
                        if (i.service_product_id == item.service_product_id) {
                            i.amount = item.amount;
                            return;
                        }
                    });
                angular.forEach($scope.ShopCarList,
                    function (i, x) {
                        if (i.service_product_id == item.service_product_id) {
                            i.amount = item.amount;
                            return;
                        }
                    });
                localStorage.setItem("shopCar", JSON.stringify($scope.ShopCarList));
            }

            //结算
            $scope.Batch = function () {
                var proList = "";
                angular.forEach($scope.ShopCarList,
                    function (i, x) {
                        if (x > 0) {
                            proList += ",";
                        }
                        proList += i.service_product_id + "|" + i.price + "|" + i.amount + "|" + i.remark;
                    });
                $.post("/portal/index.php?r=background/Batch_product_insert",
                    JSON.stringify({
                        token: $scope.token,
                        order_id: $scope.orderId,
                        product_list: proList
                    }),
                    function (data) {
                        localStorage.removeItem("shopCar");
                        localStorage.removeItem("ShopCarList");
                        alert("操作成功！");
                        location.href = "/portal/index.php?r=background/price_list&order_id=" + $scope.orderId + "&token=" + $scope.token + "&scroll_id=" + imi.GetQueryString("scroll_id");
                    })
                    .error(function () {
                        alert("网络错误，请稍后重试！");
                    });
            }

            //图片放大
            $scope.OpenBigImg = function (url) {
                $scope.bigImg = url;
                bombBox({
                    entrance: $('.imgmsgbox')
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

            //上传 
            var fileNames = [];
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
                            //console.log($scope.pic);
                            $.post("/portal/index.php?r=dailyReport/Local_upload_product",
                                JSON.stringify({
                                    token: $scope.token,
                                    order_id: $scope.orderId,
                                    subarea_id: $scope.selectedSubAreaId,
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
                                    //console.log(data)
                                    data = JSON.parse(data);
                                    $scope.$apply(function () {
                                        $scope.productList.unshift({
                                            service_product_id: data.id,
                                            name: $scope.name,
                                            price: $scope.price,
                                            cost: $scope.cost,
                                            unit: $scope.unit,
                                            description: $scope.description,
                                            ref_pic_url: $scope.pic,
                                            inventory: $scope.inventory,
                                            remark: $scope.remark,
                                            amount: 1
                                        });
                                        $("#local_upload").hide();
                                        $timeout(function() {
                                                var cartOffset = $(".fixed_cart").offset();
                                                var flyer = $('<img class="u-flyer" src="' + $scope.pic + '">');
                                                flyer.fly({
                                                    start: {
                                                        left: $('.goods_list li:eq(0)').find('.add_cart_btn_count')
                                                            .offset()
                                                            .left,
                                                        top: $('.goods_list li:eq(0)').find('.add_cart_btn_count')
                                                            .offset()
                                                            .top
                                                    },
                                                    end: {
                                                        left: cartOffset.left + 10,
                                                        top: cartOffset.top + 10,
                                                        width: 0,
                                                        height: 0
                                                    },
                                                    onEnd: function() {
                                                        $scope.$apply(function() {
                                                            $scope.ShopCarList.push($scope.productList[0]);
                                                            localStorage
                                                                .setItem("shopCar", JSON.stringify($scope.ShopCarList));
                                                        });
                                                        this.destory();
                                                    }
                                                });
                                            },
                                            700);

                                        //angular.forEach($scope.data.result.area_product,
                                        //    function (i) {
                                        //        if (i.area_id == $scope.addAreaId) {
                                        //            i.product_list.push({
                                        //                product_id: data.id,
                                        //                ref_pic_url: $scope.pic,
                                        //                product_name: $scope.name,
                                        //                subareaid: $scope.type,
                                        //                price: $scope.price,
                                        //                unit: $scope.unit,
                                        //                cost: $scope.cost,
                                        //                inventory: $scope.inventory,
                                        //                amount: $scope.amount,
                                        //                description: $scope.description,
                                        //                remark: $scope.remark
                                        //            });
                                        //            $("#local_upload").hide();
                                        //            return;
                                        //        }
                                        //    });
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
        }
    ]).directive("scroll",
    [
        "$window", "$document",
        function ($window, $document) {
            return function (scope, element) {
                angular.element($window)
                    .bind("scroll",
                    function () {
                        var pageYOffset = $window.pageYOffset;
                        var clientHeight = $document[0].documentElement.clientHeight;
                        var offsetHeight = $document[0].body.scrollHeight;
                        //console.log(pageYOffset + clientHeight > offsetHeight * 0.9);
                        //当滚动到90%的时候去加载
                        if (pageYOffset + clientHeight > offsetHeight * 0.9 && loadOff) {
                            loadOff = false;
                            scope.curPage = scope.curPage + 1;
                            scope.isLoadMore = true;
                            scope.AppendPagingData();
                        }
                    });
            };
        }
    ]);