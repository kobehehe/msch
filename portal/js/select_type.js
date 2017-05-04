/**
 * 下拉分类
 * @Date 2016-12-28
 * @autor 
 **/
var select = {
    $current:$(".category_box .current"),
    $option:$(".category_box .options_box"),
    init: function () {
        /*下拉*/
        select.$current.on('click', select.down);

        /*选中*/
        select.$option.find(".list").on('click', 'li', function () {
            select.sel($(this));
        });

        /*输入 创建分类*/
        select.$option.find(".add_category input").on('keyup', select.input);
        
        /*添加分类*/
        select.$option.find(".add_content").on('click','.creat_c',select.addCatgory);
    },
    down: function () {
        $("body").append("<div class='sel_mask'></div>");
        select.$option.show();
        select.$option.find(".add_content").hide();
        select.$option.find(".list").show();
        select.$option.find(".add_category input").val('');
        /*点击任意区域取消*/
        $(".sel_mask").on('click', select.downHide);
    },
    downHide: function () {
        $(".sel_mask").remove();
        select.$option.hide();
    },
    sel: function (ele) {
        var _selText = ele.html();
        var _tabId = ele.attr('tab-id');
        $(".sel_mask").remove();
        select.$option.hide();
        select.$current.find(".selected").html(_selText);
        select.$current.find(".selected").attr('tab-id', _tabId);
    },
    input: function () {
        var _inVal = select.$option.find(".add_category input").val();
        if (_inVal.length < 1) {
            select.$option.find(".list").show();
            select.$option.find(".add_content").hide();
        } else {
            select.$option.find(".add_content").show();
            select.$option.find(".list").hide();
            select.$option.find(".creat_c span").html(_inVal);
        }
    },
    addCatgory:function(){
        var _newCategory=select.$option.find(".creat_c span").html();
        if(_newCategory){
            select.$option.find(".list").show();
            select.$option.find(".add_content").hide();
            select.$option.find(".list").prepend('<li tab-id="0">'+_newCategory+'</li>');
            select.$option.find(".add_category input").val('');
        }
    }
}
$(function () {
    /*下拉框初始化调用*/
    select.init();
})