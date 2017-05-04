(function () {
    var m_h, sub_h, ad_h;
    $(".account_box").mouseover(function () {
        $(".account_list").show();
    }).mouseout(function () {
        $(".account_list").hide();
    })
    $(".nav_list .hasdown").mouseenter(function () {
        $.ajax({
            url: "http://crm.cike360.com/portal/index.php?r=background/Design_list"
            , type: "get"
            , async: true
            , cache: false
            , datatype: "json"
            , success: function (data) {
                data = eval("(" + data + ")");
                var html = "";
                for (var i = 0; i < data.length; i++) {
                    if (i == 0) {
                        html += "<li class=\"main_item clearfix active\" data-id=\"" +
                            data[i].id +
                            "\">" +
                            "<a href=\"" +
                            data[i].href +
                            "\" class=\"right\">" +
                            data[i].name +
                            "</a>" +
                            "<span class=\"left\"><</span>" +
                            "</li>";
                    } else {
                        html += "<li class=\"main_item clearfix\" data-id=\"" +
                            data[i].id +
                            "\">" +
                            "<a href=\"" +
                            data[i].href +
                            "\" class=\"right\">" +
                            data[i].name +
                            "</a>" +
                            "<span class=\"left\"><</span>" +
                            "</li>";
                    }

                    if (i == 0) {
                        var nhtml = "";
                        for (var j = 0; j < data[i].data.length; j++) {
                            nhtml += "<li class=\"two_item clearfix\">" +
                                        "<a href=\"" + data[i].data[j].href + "\" class=\"right two_text\">" + data[i].data[j].name + "</a>";
                            if (data[i].data[j].data.length > 0) {
                                nhtml += "<ul class=\"thr_list right clearfix\">";
                                for (var k = 0; k < data[i].data[j].data.length; k++) {
                                    nhtml += "<li class=\"thr_item\">";
                                    nhtml += "<a href=\"" + data[i].data[j].data[k].href + "\" class=\"\">" + data[i].data[j].data[k].name + "</a>";
                                    nhtml += "</li>";
                                }
                                nhtml += "</ul>";
                            }
                            nhtml += "</li>";
                        }
                        $(".subnav_c .two_list").html(nhtml);
                    }
                }
                $(".subnav_c .main_list").html(html);
                $(".subnav_c").addClass("show");
                m_h = $(".subnav_c .mainbox")[0].offsetHeight;
                sizeHeight();
            }
            , error: function (data) {
                alert("导航信息获取失败!");
            }
        });
    })
        .mouseleave(function () {
            $(".subnav_c").removeClass("show");
        })
    function sizeHeight() {
        $(".subnav_c .two_box").css('height', 0)
        sub_h = $(".subnav_c .two_list")[0].offsetHeight;
        ad_h = $(".subnav_c .ad_box")[0].offsetHeight;
        if ((sub_h + ad_h) > m_h) {
            $(".subnav_c .two_box").css('height', sub_h + ad_h)
        } else {
            $(".subnav_c .two_box").css('height', m_h)
        }
    }

    $(".subnav_c").on('mouseover', '.main_item', function () {
        var id = $(this).attr("data-id");
        $(this).addClass('active').siblings().removeClass('active');
        $.ajax({
            url: "http://crm.cike360.com/portal/index.php?r=background/Design_list"
            , type: "get"
            , async: true
            , cache: false
            , datatype: "json"
            , success: function (data) {
                data = eval("(" + data + ")");
                for (var i = 0; i < data.length; i++) {
                    if (data[i].id == id) {
                        var nhtml = "";
                        for (var j = 0; j < data[i].data.length; j++) {
                            nhtml += "<li class=\"two_item clearfix\">" +
                                        "<a href=\"" + data[i].data[j].href + "\" class=\"right two_text\">" + data[i].data[j].name + "</a>";
                            if (data[i].data[j].data.length > 0) {
                                nhtml += "<ul class=\"thr_list right clearfix\">";
                                for (var k = 0; k < data[i].data[j].data.length; k++) {
                                    nhtml += "<li class=\"thr_item\">";
                                    nhtml += "<a href=\"" + data[i].data[j].data[k].href + "\" class=\"\">" + data[i].data[j].data[k].name + "</a>";
                                    nhtml += "</li>";
                                }
                                nhtml += "</ul>";
                            }
                            nhtml += "</li>";
                        }
                        $(".subnav_c .two_list").html(nhtml);

                        return;
                    }
                }
            }
            , error: function (data) {
                alert("导航信息获取失败!");
            }

        });
        sizeHeight();
    })
})()