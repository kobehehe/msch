$(function () {
    /*初始化*/
    var liHeight = $('.filter_list .item').height();
    for (var i = 0; i < $('.filter_list .item').length; i++) {
        var listHeight = $('.filter_list .item').eq(i).find('.list')[0].offsetHeight;
        if (listHeight - 5 > liHeight) {
            $('.filter_list .item').eq(i).find('.morebtn').show();
        }
    }
    /*banner滚动*/
    new Swiper('.banner', {
        slidesPerView: '1',
        pagination: '.pagination',
        loop:true,
        autoplay:3000
    });

    /*更多展开*/
    $(".filter_list").on('click', '.morebtn', function () {
        var $this = $(this);
        if ($this.hasClass('up')) {
            $this.parents('li').css('height', liHeight);
            $this.removeClass('up');
        } else {
            $this.parents('li').css('height', $this.parents('li').find('.list')[0].offsetHeight);
            $this.addClass('up');
        }
    })

    /*列表选中*/
    $('.order_tpl_box').on('mouseenter','.order_tpl_item',function(){
        $(this).find('.info_box').animate({height:'110px'});
        $(this).find('.info_box').find('.date,.tx_box .btn,.tx_box .nick').show(150);
        $(this).find('.info_box').find('.price_box strong,.price_box del,.tx_box .num').hide();
        $(this).addClass('active');
    }).on('mouseleave','.order_tpl_item',function(){
        $(this).find('.info_box').animate({height:'80px'});
        $(this).find('.info_box').find('.date,.tx_box .btn,.tx_box .nick').hide();
        $(this).find('.info_box').find('.price_box strong,.price_box del,.tx_box .num').show(150);
        $(this).removeClass('active');
    })
})


