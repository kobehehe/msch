﻿angular.module("ms", [])
    .controller('login',
    [
        "$scope", "$http", "$compile", "$timeout", function ($scope, $http, $compile, $timeout) {
            $scope.mobile = null;
            $scope.password = null;
            $scope.error = null;

            $scope.Login = function () {
                if ($scope.mobile == null || $scope.mobile == "") {
                    $scope.error = "请输入11位手机号！";
                    return;
                }
                if (!(/^1\d{10}$/.test($scope.mobile))) {
                    $scope.error = "手机号格式不正确！";
                    return;
                }
                if ($scope.password == null || $scope.password == "") {
                    $scope.error = "请输入密码！";
                    return;
                }
                $scope.error = null;

                $http.post("/portal/index.php?r=dailyReport/login",
                    {
                        phone: $scope.mobile,
                        password: $scope.password
                    }).
                    success(function (data) {
                        //console.log(data);
                        if (data == "failed") {
                            $scope.error = "您输入的手机号或密码错误！";
                            return;
                        }
                        if (data.unfinished == "selectcity" || data.unfinished == "hotel") {
                            $scope.error = "您尚未完成注册，请手机端登录完成！";
                            return;
                        }
                        $.cookie('token', data.token, { expires: 7 }); 
                        $.cookie('manage', data.manage, { expires: 7 }); 
                        $.cookie('staff_name', data.name, { expires: 7 }); 
                        $.cookie('isVIP', data.vip, { expires: 7 }); 
                        $.cookie('avatar', data.avatar, { expires: 7 }); 
                        $.cookie('is_supplier', data.is_supplier, { expires: 7 });

                        location.href = "/portal/index.php?r=background/index_front";
                    }).
                    error(function (err) {
                        $scope.error = "网络错误，请稍后重试！";
                    });
            }
        }
    ]);

$(function(){
    /*监听输入框的回车操作*/  
    $('.password_input').bind('keypress',function(event){  
        if(event.keyCode == "13") 
            $('.login_btn').click();  
    });  
})
