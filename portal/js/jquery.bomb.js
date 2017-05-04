/*
 * Bombbox 弹出框 v1.0.0
 * @Date:   2017/2/14
 */
;
(function($, window, undefined) {
    var Bombbox = function(options) {
        this.settings = $.extend({}, Bombbox.defaults, options);
        this.init();
        var _this=this;
        $('.msgbox .mask,.msgbox .close').on('click',function(event){
            if(event.target==this){
               _this.settings.entrance.hide();
            }
            $(this).unbind('click');
        });
    }

    Bombbox.prototype = {
        init: function() {
            this.show();
            if ($.isFunction(this.settings.confirm)) {
                this.confirm();
            }
            if ($.isFunction(this.settings.close)) {
                this.close();
            }
        },
        show: function() {
            var _this=this;
            var bombWidth=this.settings.entrance.find('.mascontent').width();
            var winWidth = document.body.clientWidth;
            var left = (winWidth - bombWidth) / 2 + 'px';
            this.settings.entrance.find('.mascontent').css('left',left);
            this.settings.entrance.show();
            if ($.isFunction(this.settings.initFun)) {
                _this.settings.initFun()
            }
        },
        confirm: function() {
            var _this = this;
            this.settings.entrance.find('.confirm').click(function() {
                var confirmCallback = _this.settings.confirm();
                if (confirmCallback == undefined || confirmCallback) {
                    _this.close();
                }
                 $(this).unbind('click');
            })
        },
        close: function () {
            var _this = this;
            this.settings.entrance.find('.close')
                .click(function() {
                    var closeCallback = _this.settings.close();
                    if (closeCallback == undefined || closeCallback) {
                        $(this).parents("msgbox").hide();
                    } else {
                        $(this).parents("msgbox").hide();
                    }
                    $(this).unbind('click');
                });
        },
    }

    Bombbox.defaults = {
        entrance:'',
        initFun:'',
        confirm: null,
        close:null
    }
    var bombBox = function(options) {
        return new Bombbox(options);
    }
    window.bombBox = $.bombBox = bombBox;
})(jQuery, window);