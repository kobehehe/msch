/**
 * Created by lordc on 2017/2/12.
 */
$(function () {
    /*echart渲染*/
    echart();
    /*打印弹框初始化*/
    printMsg.init();
    /*侧边导航初始化*/
    sideBar.init();
    /*表格操作初始化*/
    tableOption.init();
})

function echart() {
    //var orderTotal = '{"feast":[],"other":[],"areap":[{"id":"3","name":"\u5ba4\u5916\u5e03\u7f6e","zheqian_total":0,"zhehou_total":0,"cost":0},{"id":"4","name":"\u7b7e\u5230\u533a\u5e03\u7f6e","zheqian_total":3300,"zhehou_total":3300,"cost":0},{"id":"5","name":"\u4eea\u5f0f\u533a\u5e03\u7f6e","zheqian_total":13000,"zhehou_total":13000,"cost":0},{"id":"6","name":"\u670d\u52a1\u4eba\u5458","zheqian_total":0,"zhehou_total":0,"cost":0},{"id":"7","name":"\u821e\u7f8e\u8bbe\u5907","zheqian_total":0,"zhehou_total":0,"cost":0},{"id":"9","name":"\u5a5a\u54c1","zheqian_total":0,"zhehou_total":0,"cost":0},{"id":"10","name":"\u5a5a\u793c\u8f66\u8f86","zheqian_total":0,"zhehou_total":0,"cost":0},{"id":"13","name":"\u6446\u4ef6\u9053\u5177","zheqian_total":0,"zhehou_total":0,"cost":0}],"non_area":{"zhehou_total":0,"cost":0},"total":{"price":16300,"cost":0,"profit":16300}}';
    var orderTotal=$("#orderTotaJson").html();
    console.log(orderTotal);
    // orderTotal = JSON.parse(orderTotal);
    orderTotal = [];
    var allTotal = orderTotal.total.price.toFixed(0);
    var pieName = null;
    //var pieName = ['婚宴'];
    var outData = [];
    var inData = [];
    //计算婚宴
    var feast_total = 0;
    $.each(orderTotal.feast, function (index, value) {
        feast_total += parseFloat(value.zhehou_total);
    });
    //计算未分配
    var non_area_total = orderTotal.non_area.zhehou_total;
    //构造外圈呈现内容
    outData.push({
        value: feast_total,
        name: '婚宴',
        itemStyle: {
            normal: {
                color: '#dc69aa'
            }
        }
    });
    $.each(orderTotal.areap, function (index, value) {
        //pieName.push(value.name);
        outData.push({
            value: value.zhehou_total,
            name: value.name
        });
    });
    //pieName.push("待分配产品", '服务人员', '场地布置', '灯光音响视频');
    outData.push({
        value: non_area_total,
        name: '待分配产品',
        itemStyle: {
            normal: {
                color: '#8d98b3'
            }
        }
    });

    //构造内圈呈现内容
    inData.push({
        value: feast_total,
        name: '婚宴',
        itemStyle: {
            normal: {
                color: '#dc69aa'
            }
        }
    });
    inData.push({
        value: 0,
        name: '服务人员',
        itemStyle: {
            normal: {
                color: '#2094e6'
            }
        }
    });
    inData.push({
        value: 16300,
        name: '场地布置',
        itemStyle: {
            normal: {
                color: '#ecd70f'
            }
        }
    });
    inData.push({
        value: 0,
        name: '灯光音响视频',
        itemStyle: {
            normal: {
                color: '#0fec63'
            }
        }
    });

    var myChart = echarts.init(document.getElementById('main'));
    option = {
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: 'horizontal',
            y: 'bottom',
            x: '20',
            itemWidth: 15,
            textStyle: {
                fontSize: 10,
                color: '#999',
            },
            data: pieName
        },
        series: [{
            name: '类别',
            type: 'pie',
            selectedMode: 'single',
            radius: ['30%', '50%'],
            itemStyle: {
                normal: {
                    label: {
                        show: false,
                        normal: {
                            position: 'inner'
                        }
                    },
                    labelLine: {
                        show: false
                    }
                }
            },
            data: inData
        },
            {
                name: '总价',
                type: 'pie',
                selectedMode: '',
                radius: ['0', '32%'],
                itemStyle: {
                    normal: {
                        label: {
                            show: true,
                            position: 'center',
                            textStyle: {
                                fontSize: '15',
                                fontWeight: 'bold',
                                color: '#333',
                            },
                            formatter: function (data) {
                                return data.name + '\n' + data.value;
                            },
                        },
                        labelLine: {
                            show: false
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data: [{
                    value: '￥' + allTotal,
                    name: '总价TOTAL',
                    itemStyle: {
                        normal: {
                            color: '#fff'
                        }
                    }
                }, ]
            },
            {
                name: '区域',
                type: 'pie',
                radius: ['54%', '73%'],
                itemStyle: {
                    normal: {
                        label: {
                            show: false
                        },
                        labelLine: {
                            show: false
                        }
                    }
                },
                data: outData
            }
        ]
    };
    myChart.setOption(option);
}
/*打印弹框*/
var printMsg = {
    init: function () {
        printMsg.initPosition();
        $('.print_btn').on('click', printMsg.show)
        $('.print_msgbox').on('click', '.mask', printMsg.close)
        $('.print_msgbox').on('click', '.add_mail', printMsg.addSureaddMail)
        $('.print_msgbox').on('click', '.sure_btn', printMsg.addSure)
        $('.print_msgbox').on('click', '.del', printMsg.del)
        $('.print_msgbox').on('click', '.send', printMsg.sendMail)
    },
    initPosition: function () {
        var winWidth = document.body.clientWidth;
        var left = (winWidth - 700) / 2 + 'PX';
        $('.print_msgbox .print_msg').css('left', left);
    },
    show: function () {
        $('.print_msgbox').show();
    },
    close: function () {
        $('.print_msgbox').hide();
    },
    addSureaddMail: function () {
        $('.mail_list').prepend('<li class="clearfix new_item"><div class="left"><input type="text"></div><div class="left"><div><button class = "sure_btn" > 确定 </button> </div></div> </li>')
    },
    addSure:function(){
        var _newMail=$(this).parents('.new_item').find('input').val();
        if($.trim(_newMail)!=''){
            $(this).parents('.new_item').find('input').after('<p class="name">'+_newMail+'</p></div>');
            $(this).parents('.new_item').find('input').remove();
            $(this).after('<span class="left send">发送报价单</span><span class="left sep">|</span><span class="left del">删除</span>');
            $(this).remove();
        }
        /*此处ajax 新增邮箱*/
    },
    del:function(){
        $(this).parents('li').remove();
        /*此处ajax 删除邮箱*/
    },
    sendMail:function(){
        /*发送报价单相关操作*/
    }
};
/*侧边导航*/
var sideBar={
    init:function(){
        $('.sidebar_list').on('click', '.name', sideBar.expand)
        $('.side_subbar_list').on('click', 'li', sideBar.scroll)
        $('.sidebar_item').on('mouseover', '.addbtn_box', sideBar.addHover).on('mouseout', '.addbtn_box', sideBar.addout)
        $('#print_area').on('mouseover', '.addbtn_box', sideBar.addHover).on('mouseout', '.addbtn_box', sideBar.addout)
        $('.sidebar_list').on('click', '.upload_item', sideBar.upload)
        $('#print_area').on('click', '.upload_item', sideBar.upload)
        $('.add_msgbox').on('click', '.sure_btn', sideBar.uploadSure)
        $('.add_msgbox').on('click', '.mask', sideBar.uploadClose)
        $('.add_msgbox').on('click', '.close', sideBar.uploadClose)
        $('#areaSelect').on("click",sideBar.pushArea)
    },
    expand:function(){
        if($(this).prev().hasClass('up')){
            $(this).prev().removeClass('up');
            $(this).parents('li').find('.side_subbar_list').hide();
        }else{
            $(this).prev().addClass('up');
            $(this).parents('li').find('.side_subbar_list').show();
        }
    },
    scroll:function(){
        var _dataid=$(this).data('id');
        var _offsetTop=$("#"+_dataid).offset().top-60;
        $(window).scrollTop(_offsetTop);
        $('li').removeClass('active');
        $(this).addClass('active');
    },
    addHover:function(){
        $(this).find('.hovercon_box').show();
    },
    addout:function(){
        $(this).find('.hovercon_box').hide();
    },
    upload:function(){
        var winWidth = document.body.clientWidth;
        var left = (winWidth - 700) / 2 + 'px';
        var dataid;
        if($(this).parents(".addbtn_box").hasClass('intable')){
            dataid=$(this).parents('table').data('id');
        }else{
            dataid=$(this).parents('.sidebar_item').data('id');
        }

        var dataareaid;
        if($(this).parents(".addbtn_box").hasClass('intable')){
            dataareaid=$(this).parents('table').attr('data-areaId');
        }else{
            dataareaid=$(this).parents('.sidebar_item').attr('data-areaId');
        }
        console.log(dataid)
        $('.add_msgbox .add_msg').css('left', left);
        $('.add_msgbox').attr('data-id',dataid);
        $('.add_msgbox').attr('data-areaId',dataareaid);
        $('.add_msgbox').show();
    },
    uploadSure:function(){
        var _inname=$("#in_name").val(),
            _inunit=$("#in_unit").val(),
            _inprice=$("#in_price").val(),
            _innum=$("#in_num").val(),
            _indesc=$("#in_desc").val(),
            _inbz=$("#in_bz").val(),
            _sel=$("select").find("option:selected").text();
        var  dataid=$('.add_msgbox').attr('data-id');
        var html='';
        var lastid=$('.option_table').find('#'+dataid).find('tbody tr:last').attr('id');
        var newid=lastid.substring(0,lastid.length-1)+(Number(lastid.substr(lastid.length-1))+1);
        console.log(dataid)
        html='<tr id="'+newid+'"><td><div>'+_inname+'</div></td>'
            +'<td><img class="list_img" src="http://file.cike360.com/upload/set/set38888/a水牌 (12)_xs.jpg"></td>'
            +'<td><div>'+_inprice+'</div></td>'
            +'<td><div>'+_inunit+'</div></td>'
            +'<td><div>'+_innum+'</div></td>'
            +'<td><div class="list_remark">'+_inbz+'</div></td>'
            +'<td><div>'+_inprice*_innum+'</div></td>'
            +'<td class="option_item">'
            +'<div class="clearfix">'
            +'<span class="left editbtn">编辑</span>'
            +'<span class="left sep">|</span>'
            +'<span class="left delbtn">删除</span>'
            +'</div></td></tr>'
        $('.option_table').find('#'+dataid).find('tbody').append(html);
        /*此处ajax 删除邮箱*/
        $(this).parents('.add_msgbox').hide();
    },
    uploadClose:function(){
        $('.add_msgbox').hide();
    },
    pushArea:function () {
        var areaId=$(this).parents(".add_msgbox").attr("data-areaId");
        $.ajax({
            url: "http://crm.cike360.com/portal/index.php?r=background/Get_area_data",
            type: "get",
            async: true,
            cache:false ,
            datatype: "json",
            success: function (data)
            {
                for(var i=0;i<data.length;i++){
                    if(data[i].id==areaId){
                        var html="";
                        for(var j=0;j<data.subarea.length;i++){
                            html+="<option value=\""+data.subarea[j].id+"\">"+data.subarea[j].name+"</option>";
                        }
                        $('#areaSelect').html(html);
                        return;
                    }
                }
            },
            error: function (data)
            {
                alert("地址获取失败!");
            }
        });

    }
}
/*表格操作*/
var tableOption={
    init:function(){
        $('#print_area').on('click', '.option_item .delbtn', tableOption.del)
        $('#print_area').on('click', '.option_item .editbtn', tableOption.edit)
        $('.edit_msgbox').on('click', '.sure_btn', tableOption.sureBtn)
        $('.edit_msgbox').on('click', '.mask', tableOption.close)
        $('.edit_msgbox').on('click', '.close', tableOption.close)
        $('#print_area').on('click', '.list_img', tableOption.imgMsg)
        $('body').on('click', '.imgmsgbox .mask', tableOption.imgClose)
    },
    del:function(){
        $(this).parents('tr').remove();
        /*此处ajax 删除邮箱*/
    },
    edit:function(){
        var winWidth = document.body.clientWidth;
        var left = (winWidth - 700) / 2 + 'px';
        $('.edit_msgbox .edit_msg').css('left', left);
        $('.edit_msgbox').show();
        var dataid=$(this).parents('tr').attr('id');
        $('.edit_msgbox').attr('data-id',dataid);
    },
    sureBtn:function(){
        var _inprice=$(".edit_msgbox [data-flag=price]").val(),
            _innum=$(".edit_msgbox [data-flag=num]").val(),
            _inbz=$(".edit_msgbox [data-flag=remark]").val();
        var  dataid=$(this).parents('.edit_msgbox').attr('data-id');
        console.log(dataid);
        $('.option_table').find('#'+dataid).find('[data-flag=price] div').html(_inprice);
        $('.option_table').find('#'+dataid).find('[data-flag=num] div').html(_innum);
        $('.option_table').find('#'+dataid).find('[data-flag=remark] div').html(_inbz);
        /*此处ajax 删除邮箱*/

        $(this).parents('.edit_msgbox').hide();

    },
    close:function(){
        $('.edit_msgbox').hide();
    },
    imgMsg:function(){
        var _src=$(this).attr('src');
        var winWidth = document.body.clientWidth;
        var left = (winWidth - 500) / 2 + 'px';
        var html='<div class="msgbox imgmsgbox">'
            +'<div class="mask"></div>'
            +'<div class="imgbox" style="left:'+left+'"><img src='+_src+'></div>'
            +'</div>'
        $("body").append(html);
    },
    imgClose:function(){
        $('.imgmsgbox').hide();
    }
}