!(function ($, window, undefined) {
    var Count = function (ele, options) {
        this.settings = $.extend({}, Count.defaults, options);
        this.$ele = $(ele);
        this.init();
    };
    Count.defaults = {
        limitnum: 'null',
        addAction: 'null',
        minusAction: 'null',
    }
    Count.prototype = {
        constructor: Count,
        init: function () {
            this.$ele.find('.minus_btn').on('click', $.proxy(this.minus, this));
            this.$ele.find('.add_btn').on('click', $.proxy(this.add, this));
        },
        minus: function () {
            var $count = this.$ele.find('.count'),
                $add_btn = this.$ele.find('.add_btn'),
                $minus_btn = this.$ele.find('.minus_btn'),
                num = $count.val();
            if (Number(num) > 1) {
                var curNum = Number($count.val()) - 1;
                $count.attr('value', curNum);
                $count.val(curNum);
                $add_btn.removeClass('disabled');
                if ($.isFunction(this.settings.minusAction)) {
                    this.settings.minusAction(curNum);
                }
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
                var curNum = Number($count.val()) + 1;
                $count.attr('value', curNum);
                $count.val(curNum);
                $minus_btn.removeClass('disabled');
                if ($.isFunction(this.settings.addAction)) {
                    this.settings.addAction(curNum);
                }
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