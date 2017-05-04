angular.module("ms", [])
    .controller('regist',
    [
        "$scope", "$http", "$compile", "$timeout", "$interval", function ($scope, $http, $compile, $timeout, $interval) {
            $scope.mobile = null;
            $scope.code = null;
            $scope.error = null;
            $scope.time = 0;

            $scope.Regist = function () {
                if ($scope.mobile == null || $scope.mobile == "") {
                    $scope.error = "请输入11位手机号！";
                    return;
                }
                if (!(/^1\d{10}$/.test($scope.mobile))) {
                    $scope.error = "手机号格式不正确！";
                    return;
                }
                if ($scope.code == null || $scope.code == "") {
                    $scope.error = "请输入验证码！";
                    return;
                }
                $scope.error = null;

                var weixin_openid = imi.GetQueryString("weixin_openid") != null
                    ? imi.GetQueryString("weixin_openid")
                    : "";
                var weixin_unionid = imi.GetQueryString("weixin_unionid") != null
                    ? imi.GetQueryString("weixin_unionid")
                    : "";
                var weixin_name = imi.GetQueryString("weixin_name") != null
                    ? imi.GetQueryString("weixin_name")
                    : "";
                var weixin_avatar = imi.GetQueryString("weixin_avatar") != null
                    ? imi.GetQueryString("weixin_avatar")
                    : "";
                var weixin_country = imi.GetQueryString("weixin_country") != null
                    ? imi.GetQueryString("weixin_country")
                    : "";
                var weixin_province = imi.GetQueryString("weixin_province") != null
                    ? imi.GetQueryString("weixin_province")
                    : "";
                var weixin_city = imi.GetQueryString("weixin_city") != null
                    ? imi.GetQueryString("weixin_city")
                    : "";
                var weixin_gender = imi.GetQueryString("weixin_gender") != null
                    ? imi.GetQueryString("weixin_gender")
                    : "";


                $http.post("/portal/index.php?r=dailyReport/Wechat_regist",
                    {
                        telephone: $scope.mobile,
                        code: $scope.code,
                        weixin_openid: weixin_openid,
                        weixin_unionid: weixin_unionid,
                        weixin_name: weixin_name,
                        weixin_avatar: weixin_avatar,
                        weixin_country: weixin_country,
                        weixin_province: weixin_province,
                        weixin_city: weixin_city,
                        weixin_gender: weixin_gender
                    }).
                    success(function (data) {
                        console.log(data);
                        if (data.code_correct == false) {
                            $scope.error = "您输入的验证码错误！";
                            return;
                        }
                        if (data.unfinished == "selectcity" || data.unfinished == "hotel") {
                            $scope.error = "您尚未完成注册，请手机端登录完成！";
                            return;
                        }
                        $.cookie('$token', data.token, { expires: 7 });
                        $.cookie('$manage', data.manage, { expires: 7 });
                        $.cookie('$staff_name', data.name, { expires: 7 });
                        $.cookie('$isVIP', data.vip, { expires: 7 });
                        $.cookie('$avatar', data.avatar, { expires: 7 });
                        $.cookie('$is_supplier', data.is_supplier, { expires: 7 });
                        if (data.is_supplier == true) {
                            location.href = "/portal/index.php?r=background/supplier_management";
                        } else {
                            location.href = "/portal/index.php?r=background/index_front";
                        }
                    }).
                    error(function (err) {
                        $scope.error = "网络错误，请稍后重试！";
                    });
            }

            $scope.GetCode = function () {
                if ($scope.time == 0) {
                    if ($scope.mobile == null || $scope.mobile == "") {
                        $scope.error = "请输入11位手机号！";
                        return;
                    }
                    if (!(/^1\d{10}$/.test($scope.mobile))) {
                        $scope.error = "手机号格式不正确！";
                        return;
                    }
                    $http.get("/portal/index.php?r=dailyReport/sendcode&telephone=" + $scope.mobile).
                        success(function (data) {
                            if (data != 1) {
                                $scope.error = "验证码发送失败！";
                            } else {
                                $scope.time = 60;
                                $scope.timer = $interval(function () {
                                    if ($scope.time > 0) {
                                        $scope.time -= 1;
                                    } else {
                                        $interval.cancel($scope.timer);
                                    }
                                },
                                    1000);
                            }
                        }).
                        error(function (err) {
                            $scope.error = "网络错误，请稍后重试！";
                        });
                }
            }
        }
    ])
