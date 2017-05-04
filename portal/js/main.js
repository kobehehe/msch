//获取参数
function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}

//$(document).ready(function() {
//  $('.table').DataTable( {
//	    "language": {
//	        "sProcessing": "处理中...",
//	        "sLengthMenu": "显示 _MENU_ 项结果",
//	        "sZeroRecords": "没有匹配结果",
//	        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
//	        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
//	        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
//	        "sInfoPostFix": "",
//	        "sSearch": "搜索:",
//	        "sUrl": "",
//	        "sEmptyTable": "表中数据为空",
//	        "sLoadingRecords": "载入中...",
//	        "sInfoThousands": ",",
//	        "oPaginate": {
//	            "sFirst": "首页",
//	            "sPrevious": "上页",
//	            "sNext": "下页",
//	            "sLast": "末页"
//	        },
//	        "oAria": {
//	            "sSortAscending": ": 以升序排列此列",
//	            "sSortDescending": ": 以降序排列此列"
//	        }
//	    }
//  });
//} );

$(function(){
	

	

//充值按钮样式
//	var balance = $("#balanceMoney").text();
//	if(balance>=10){
//		$("#balanceDraw").removeClass("Dbtn_default");
//		$("#balanceDraw").addClass("Dbtn_warning")
//	}else{
//		$("#balanceDraw").removeClass("Dbtn_warning");
//		$("#balanceDraw").addClass("Dbtn_default")
//	}

//样式为空时出现的判断
//	var empty = $(".emptyChange").find("tr").length;
//	if(empty==0){
////		$(".emptyChange").addClass("hidden");
//		$(".emptyImg").removeClass("hidden");
////		$("#pageContent").addClass("hidden");
//		
//	}else{
////		$(".emptyChange").removeClass("hidden");
//		$(".emptyImg").addClass("hidden");
////		$("#pageContent").removeClass("hidden");
//	}
	
//点击添加样式
	$(".clickActiveOn ul li a").click(function(){
		$(this).parent().addClass("on");
		$(this).parent().siblings().removeClass("on");
	})
	$(".clickActive ul li a").click(function(){
		$(this).parent().addClass("active");
		$(this).parent().siblings().removeClass("active");
	})
	$(".selectList_box ul li a").click(function(){
		$(this).parent().addClass("on");
		$(this).parent().siblings().removeClass("on");
	})
	
//select框
	$(".selectMoreClick").click(function(){
		$(this).siblings(".selectMore_list").toggle(300);
		$(this).parents("li").siblings().find(".selectMore_list").hide(300);
	})
	

	
//var uplAll = window.location.hash
//if(uplAll = '#/goods/list3'){
//	$("#goodsNav_2").addClass("active");
//	alert(uplAll)
//}else if(uplAll = '#/goods/list2'){
//	$("#goodsNav_1").addClass("active");
//	alert(uplAll)
//}else if(uplAll = '#/goods'){
//	$("#goodsNav_0").addClass("active");
//	alert(uplAll)
//}
//alert(uplAll)

$(".moreList").click(function(){
	var i = $(this).parents(".selectList_box").height();
	if(i==50){
		$(this).parents(".selectList_box").css("height","atuo");
		$(this).text("展开");
	}else{
		$(this).parents(".selectList_box").css("height","50px");
		$(this).text("收起")
	}
});


//$(".moreList").click(function(){
//	alert(111)
////	$(this).parents(".selectList_box").height(auto);
//})





});

//多图上传点击展开收起
var flag=0;
function callback(){
	$("#z_photo").css("height","220px");
	var z_photoHeight=$("#z_photo").find("section").length;
//	var MobImgUplLi=$(".MobImgUpl_box").find("li").length;
}
$(".z_photo_open").click(function(){
	if(!flag){
		//展开
		flag=1;
		$("#z_photo").css("height","auto");
		$(".z_photo_open").html("<i class='iconfont icon-arrow'></i><span>收起</span>");
	}else{
		flag=0;
		$("#z_photo").css("height","220px");
		$(".z_photo_open").htnl("<i class='iconfont icon-arrowDown'></i><span>展开</span>");
	}
})


//$(function(){
//	var i=0;
//	$(".moreList").click(function(){
//		
//		i++;
//		if(i%2!=0){
//			$(".moreList").parents(".selectList_box").animate({"height":"650px"},600);
//		}else{
//			$(".moreList").parents(".selectList_box").animate({"height":"100px"},600);
//		}
//	});
//})
