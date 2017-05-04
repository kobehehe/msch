var maApp = angular.module('maApp', ['ui.router', 'ngAnimate']);
maApp.config(function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise("/home");
    $stateProvider
        .state('home', {
            url: "/home",
            templateUrl: "tpls/home.html",
//          link
        })

        .state('goods', {
            url: "/goods",
            templateUrl: "tpls/goods.html"
        })
//      .state('goods.list1', {
//          url: "/list1",
//          templateUrl: "tpls/goods.list1.html",
//          controller: function($scope) {
//              $scope.things = ["A", "Set", "Of", "Things"];
//          }
//      })
        .state('goods.list2', {
            url: "/list2",
            templateUrl: "tpls/goods.list2.html",
            controller: function($scope) {
                $scope.things = ["A", "Set", "Of", "Things"];
            }
        })
        .state('goods.list3', {
            url: "/list3",
            templateUrl: "tpls/goods.list3.html",
            controller: function($scope) {
                $scope.things = ["A", "Set", "Of", "Things"];
            }
        })
        .state('goodsAdd', {
            url: "/goodsAdd",
            templateUrl: "tpls/goodsAdd.html",
            controller: function($scope) {
                $scope.things = ["A", "Set", "Of", "Things"];
            }
        })
        .state('goodsAddCase', {
            url: "/goodsAddCase",
            templateUrl: "tpls/goodsAddCase.html",
            controller: function($scope) {
                $scope.things = ["A", "Set", "Of", "Things"];
            }
        })
        .state('goodsAddMake', {
            url: "/goodsAddMake",
            templateUrl: "tpls/goodsAddMake.html",
            controller: function($scope) {
                $scope.things = ["A", "Set", "Of", "Things"];
            }
        })
        .state('basicInfo', {
            url: "/basicInfo",
            templateUrl: "tpls/basicInfo.html"
        })
        .state('basicInfo.list2', {
            url: "/list2",
            templateUrl: "tpls/basicInfo.list2.html",
            controller: function($scope) {
                $scope.things = ["A", "Set", "Of", "Things"];
            }
        })
});









