!(function ($, window, undefined) {
    var Count = function (ele, options) {
        this.settings = $.extend({}, Count.defaults, options);
        this.$ele = $(ele);
        this.init();
    };
    Count.defaults = {
        limitnum: 'null'
    }
    Count.prototype = {
        constructor: Count,
        init: function () {
            this.$ele.find('.minus_btn').live('click', $.proxy(this.minus, this));
            this.$ele.find('.add_btn').live('click', $.proxy(this.add, this));
        },
        minus: function () {
            var $count = this.$ele.find('.count'),
                $add_btn = this.$ele.find('.add_btn'),
                $minus_btn = this.$ele.find('.minus_btn'),
                num = $count.val();
            if (Number(num) > 1) {
                //$count.val(Number(num) - 1);
                $count.attr('value',Number(num) - 1)
                $add_btn.removeClass('disabled');
                if (Number(num) - 1 == 1) {
                    $minus_btn.addClass('disabled');
                }
            }
        },
        add: function () {
            var $count = this.$ele.find('.count'),
                $add_btn = this.$ele.find('.add_btn'),
                $minus_btn = this.$ele.find('.minus_btn'),
                num = $count.val(),
                limitnum = this.settings.limitnum;
            if (Number(num) < limitnum || limitnum == 'null') {
                //$count.val(Number($count.val()) + 1);
                $count.attr('value',Number(num) + 1)
                $minus_btn.removeClass('disabled');
                if (Number(num) == limitnum - 1) {
                    $add_btn.addClass('disabled');
                }
            }

        }
    }

    $.fn.count = function (options) {
        return this.each(function () {
            return new Count(this, options);
        })
    }
})(jQuery, window)
$(function () {
    /*导航显隐控制*/
    //场地布置
    $("#decoration_li").mouseover(function () {
        $("#decoration").show();
    }).mouseout(function () {
        $("#decoration").hide();
    });
    //人员统筹
    $("#person_li").mouseover(function () {
        $("#person").show();
    }).mouseout(function () {
        $("#person").hide();
    });
    //婚礼设备
    $("#lss_li").mouseover(function () {
        $("#lss").show();
    }).mouseout(function () {
        $("#lss").hide();
    });
    //婚礼商品
    $("#goods_li").mouseover(function () {
        $("#goods").show();
    }).mouseout(function () {
        $("#goods").hide();
    });

    /*数量增减控制*/
    $(".counter_box").count({
            limitnum: 1000
        })
        /*左侧固定*/
    var $right_fixed = $(".upload_set_c .right_area"),
        $left_fixed = $(".upload_set_c .left_area")
    var wapper = 960,
        w_width = document.documentElement.clientWidth,
        w_height = document.documentElement.clientHeight;
    console.log((w_width - wapper) / 2);
    var h_footer_fixed = $(".footer").outerHeight();
    $right_fixed.css({
        'right': (w_width - wapper) / 2 + 'px',
        'height': w_height - h_footer_fixed - 140 + 'px'
    });
    $left_fixed.css({
        'height': w_height - h_footer_fixed - 140 + 'px'
    });
    $(".upload_set_c .right_area>div:first").css({
        'height': w_height - h_footer_fixed - 190 + 'px'
    });

})