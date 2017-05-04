var obj={
	pre_type:null,
	pageNum:1,
	total_page:1,
	userid:$.cookie('userid'),
	pageSize:9, //一页显示多少条数据
	u:"/portal/index.php?r=background/",  
	pre_type_4_type:false //判断pre_type=4时，传回的结果中type是否有缓存到本地，默认为false;
};

//从当前url列表中，通过参数求等号后面的值
function getURI(key){
	var url=window.location.search;
	if(url.indexOf("?")!=-1){
		var rep=url.substring(1);
		if(rep.indexOf("&")==-1){//只有1个参数
			if(rep.indexOf("=")==11){
				return encodeURIComponent(rep.substring(12));
			}else{
				var arr=rep.split("=");
				if(arr[0]==key){
					return arr[1];
				}
			}
		}else if(rep.indexOf("&")>0){//有多个参数
			var arr=rep.split("&");
			for(var i=0;i<arr.length;i++){
				var spl=arr[i].split("=");
				if(spl[0]==key){
					return spl[1];
				}
			}
		}
	}else if(url.indexOf("?")==-1){
		return "";
	}
}

//读取cookie
function getCookie(c_name) {
	var c_start,c_end;
	if(document.cookie.length > 0) {
		c_start = document.cookie.indexOf(c_name + "=");
		if(c_start != -1) {
			c_start = c_start + c_name.length + 1;
			c_end = document.cookie.indexOf(";", c_start);
			if(c_end == -1)
				c_end = document.cookie.length;
			return unescape(document.cookie.substring(c_start, c_end));
		}
	}
	return "";
}

function pre_type(){
	if(getURI("pre_type")){
		obj.pre_type=parseInt(getURI("pre_type"));
		switch(obj.pre_type){
			case 1:$(".nav-menu,div.article .selector").hide(); $(".crumb-wrap span").html("门店介绍"); $(".prod-list").removeClass("person-list");$("div.article").addClass("on"); break;
			case 2:$(".nav-menu,div.article .selector").hide(); $(".crumb-wrap span").html("经典案例"); $(".prod-list").removeClass("person-list");$("div.article").addClass("on"); break;
			case 3:$(".nav-menu").hide(); $(".crumb-wrap span").html("我的收藏"); $(".prod-list").removeClass("person-list");$("div.article").addClass("on"); break;
			case 4:$(".crumb-wrap span").html("服务人员"); $(".prod-list").addClass("person-list");$(".article .selector").hide();$("div.article").removeClass("on"); break;
			default:break;
		}
	}else{
		alert("获取参数pre_type失败, 系统将终止执行后续任务");
	}
}

//Image onload
function loadImage(img){
	var w=parseInt($(img).width());
	var h=parseInt($(img).height());
	if(parseInt(w/h)>parseInt(180/140)){
		$(img).attr({
			"width":"auto",
			"height":"140"
		});
	}else{
		$(img).attr({
			"width":"180",
			"height":"auto"
		});
	}
}

//分页方法
	function printPages(Data){//Data：返回的result值
		
		var str="";
		var fristpage="";//首页
		var prepage="";//上一页
		var nextpage="";//下一页
		var lastpage="";//末页
		if(Data.pagerSize==1) return str;//pageCount：为总页数，如果总页数为1， 说明只有1页
		if(Data.pageNum==1){//pageNo：为当前页码，如果当前页码数为1，则没有首页和上一页
			fristpage="";
			prepage="";
		}else{//如果当前页不是1
			var prePageNo=Math.max(Data.pageNum-1,1);//动态计算上一页
			fristpage="<a href=\"javascript:;\" class=\"1\">\u9996\u9875</a>";//给第一页赋值
			prepage="<a href=\"javascript:;\" class=\""+prePageNo+"\">\u4e0a\u4e00\u9875</a>";//给上一页赋值
		}	
		if(Data.pagerSize==Data.pageNum){//如果总页数（也就是最后一页的页码数）等于当前页码数，则没有下一页和末页
			nextpage="";
			lastpage="";
		}else{//如果当前页码数不是最后一页的页码数
			var nextPageNo=Math.min(Data.pageNum+1,Data.pagerSize);//则可以动态计算下一页
			nextpage="<a href=\"javascript:;\" class=\""+nextPageNo+"\">\u4e0b\u4e00\u9875</a>";//并给下一页赋值
			lastpage="<a href=\"javascript:;\" class=\""+Data.pagerSize+"\">\u672b\u9875</a>";//给末页赋值
		}
		
	//	
		if(Data.pagerSize<=7){//如果总页数小于7页
			var startPage=1;//从第一页开始
			var endPage=Data.pagerSize;//计算末页的页码数就是总页数
			var beforeStr="";//页码前
			var afterStr="";//和页码后，都不需要用...
		}else if(Data.pagerSize>7){//如果总页数大于7页
			if(Data.pageNum < 6){//如果当前页码数小于6
				var startPage=1;//从第一页开始
				var endPage=6;//末页的页码数就是6（因为总页数是大于7的）
				var beforeStr="";//页码前不需要用...，因为前面没有再多内容了
				var afterStr="<em>...</em>";//末页后面用...表示
			}else if(Data.pageNum >= 6 && Data.pageNum < Data.pagerSize-2){//如果当前页码数是6或者是6后面的页码，也就是在中间页，小于最后2页
				var startPage=Data.pageNum-2;//则首页就是当前页码数减2，让原来的页码排列的前2个页码去掉，
				var endPage=Data.pageNum+2;//后面再补2个页码，这样，保持中间一直是5个，而且当前页码一直在中间位置
				var beforeStr="<em>...</em>";//页码前面用...表示，表示往前也可以翻
				var afterStr="<em>...</em>";//后面用...表示，表示往后也可以翻，前后都有数据
			}else{ //最后，如果页码翻到最后，后面没有页码了，
				var startPage=Data.pagerSize-5;//则排列在第一位的页码是总页码数（也就是最后一页的页码数减去5）
				var endPage=Data.pagerSize;//末页就是最后的页码数，也是总页码数
				var beforeStr="<em>...</em>";//页码前面用...表示，意思是说前面有内容，可以往前翻
				var afterStr="";//后面没有内容了，置空（不用...）
			}
		}
		
		for(var i=startPage; i < endPage+1;i++){//把页码循环出来
			if(Data.pageNum==i){//如果当前页码就是 i
				str+="<span>"+i+"</span>";//则当前页码用span标签包起来，没有点击事件
			}else{//其它页码，用a标签包起来，可以点击翻页
				str+="<a href=\"javascript:;\" class=\""+i+"\">"+i+"</a>";
			}
		}
		str=fristpage+prepage+beforeStr+str+afterStr+nextpage+lastpage;	
		return str;
	}







$(function(){
	//接收参数pre_type判断页面显示内容
	var page_name;
	pre_type();
	getList();
	$('body').top_nav({
		active_no:10
	});
	$('body').bread_nav({
		father_class: 'bread_nav',
		nav_name_list:[{
			nav_name: '首页',
			nav_link: 'index.php?r=background/index_front'
		},{
			nav_name: page_name,
			nav_link: ''
		}]
	});
	function getList(typeID,preType){
		var url="",oTypeID;
		if(obj.pre_type){
			if(preType==3 || preType==4){
				oTypeID=typeID;
			}else{
				oTypeID="";
			};
			
			switch(obj.pre_type){
				case 1:
					url=obj.u+"get_case_list&CI_Type=16&page="+obj.pageNum+"&staff_id="+obj.userid; 
					page_name = '门店介绍';
					//obj.pageSize=9;
					break;
				case 2:
					url=obj.u+"get_case_list&CI_Type=2&page="+obj.pageNum+"&staff_id="+obj.userid;
					page_name = '经典案例';
					//obj.pageSize=9;
					break;
				case 3:
					url=obj.u+"get_my_collection&type_id="+oTypeID+"&page="+obj.pageNum+"&token="+obj.userid;
					page_name = '我的收藏';
					//obj.pageSize=9; 
					break;
				case 4:
					page_name = '服务人员';
					if(!oTypeID){
						oTypeID=3;
					}
					url=obj.u+"get_service_person&type_id="+oTypeID+"&page="+obj.pageNum+"&staff_id="+obj.userid; 
					//obj.pageSize=3;
					setTimeout(function(){
						$(".nav-menu a:eq(0)").addClass("on");
					},300);
					break;
			};
			
		}else{
			return false;
		}
		$.ajax({
			type:"POST",
			url:url,
			data:{},
			success:function(data){
				console.log(data);
				var list;
				switch(obj.pre_type){
					case 1:list=data.list_data; break;
					case 2:list=data.list_data; break;
					case 3:list=data.folder_data; break;
					case 4:list=data.person; break;
				}
				if((data.total_page==1 && list.length>0) || (data.total_page>1 && (list.length>0 || list.length==0))){//有数据
					$("div.article .main").show();
					$("div.article div.none").hide();
					showList(data); //显示数据
					$(".selector").find('dd').find('a').eq(0).addClass('on');
					if(data.total_page<2){ //若总页数大于1
						$(".pages").hide(); //否则不显示页码
					}else{
						$(".pages").show();//则显示页码
					}
				}else{//无数据
					$("div.article .main,.pages").hide(); //隐藏排版代码和页码
					$("div.article div.none").show(); //显示无内容提示
				}
			},	
			dataType:"JSON",
			error:function(data){
				alert("查询过程中系统异常："+JSON.stringify(data));
			}
		})
	}
	//显示  查询出的内容列表
	function showList(data){
		var html=msg=len=title=img_url=link="";	
		switch(obj.pre_type){
			case 1:msg=data.list_data; break;
			case 2:msg=data.list_data; break;
			case 3:msg=data.folder_data; break;
			case 4:msg=data.person; break;
		}
		len=msg.length;
		if(obj.pre_type==4){
			for(var i=0;i<len;i++){
				title=msg[i].name||"";img_url=msg[i].img||"";
				link=obj.u+'upload_case_detail&service_person_id='+msg[i].service_person_id+'&token='+obj.userid+'&pre_page=sales_list';
				var price_list=orderList="";
				if(msg[i].price.price_list.length>0){
					var pl=msg[i].price.price_list;
					for(var j=0;j<pl.length;j++){
						price_list += '<a href="javascript:;" id="'+pl[j].id+'" name="'+pl[j].price+'">'+pl[j].name+'</a>';
					}
				}else{
					price_list="";
				}
				if(data.order_doing.length>0){
					var dod=data.order_doing;
					for(var k=0;k<dod.length;k++){
						orderList += '<a href="javascript:;" id="'+dod[k].id+'">'+dod[k].order_date+' '+dod[k].name+dod[k].designer_name+'</a>';
					}
				}else{
					orderList="";
				}

				html += '<li><a href="'+link+'" class="prod-pic"><img src="'+img_url+'" onload="loadImage(this)" alt=""></a>'+
							'<div class="info">'+
								'<div class="prod-top">'+
									'<a href="'+link+'" class="tit">'+title+'</a>'+
									'<a class="price">'+(msg[i].price.min_price||0)+'</a>'+
								'</div>'+
								'<p class="intro">'+(msg[i].description||"")+'</p>'+
								'<a href="javascript:;" class="view">加入订单</a>'+
							'</div>'+
							'<div class="address-list">'+
								'<div class="selector-order">'+
									'<span>选择订单</span>'+
									'<div class="select">'+
										'<span>请选择一个订单</span>'+
										'<p>'+orderList+'</p>'+
									'</div>'+
								'</div>'+
								'<span class="address-head">请选择报价</span>'+
								'<div class="address-body">'+price_list+'</div>'+
								'<div class="address-foot"><a href="javascript:;" class="confirm" subarea="'+msg[i].subarea_id+'" data-type_id="'+msg[i].type_id+'" data-service_person_id="'+msg[i].service_person_id+'">确认</a></div>'+
								'<a href="javascript:;">×</a></div></li>';
			}
			if(len%2!=0){
				html+='<li></li>';
			}
			$("div.article .main ul").html(html);
			if(!obj.pre_type_4_type){
				if(data.type.length>0){
					obj.pre_type_4_type=data.type;
					dataType=data.type,dataTypeStr='';
					for(var i=0;i<dataType.length;i++){
						dataTypeStr += '<a href="javascript:;" name="'+dataType[i].id+'">'+dataType[i].name+'</a>';
					}
					$(".nav-menu").html(dataTypeStr);
				}else{//没有分类
					$(".nav-menu").html("");
				};
			};
		}else{//pre_type==3、2、1  
			for(var i=0;i<len;i++){
				if(obj.pre_type==3){
					title=msg[i].name||"";
					img_url=msg[i].img_url||"";
					link=obj.u+'upload_collection&folder_id='+msg[i].id+'&type=folder&token='+obj.userid+'&pre_page=sales_list';
				}else if(obj.pre_type==1 || obj.pre_type==2){
					title=msg[i].CI_Name||"";
					img_url=msg[i].CI_Pic||"";
					if(obj.pre_type==1){
						link=obj.u+'upload_case_detail&ci_id='+msg[i].CI_ID+'&type=hotel_introduction&token='+obj.userid+'&pre_page=sales_list';
					}else{//pre_type=2
						link=obj.u+'upload_case_detail&ci_id='+msg[i].CI_ID+'&type=case&token='+obj.userid+'&pre_page=sales_list';
					}
				}

				html += '<li>'+
							'<a href="'+link+'" class="prod-pic"><img src="'+img_url+'" onload="loadImage(this)" alt=""></a>'+
							'<div class="info">'+
								'<a href="'+link+'" class="tit">'+title+'</a>'+
								'<p class="intro">'+(msg[i].CI_Remarks||"")+'</p>'+
								'<span class="time" >'+(msg[i].CI_Date||"")+'</span>'+
								'<a href="'+link+'" class="view">查看详情</a>'+
							'</div>'+
						'</li>';
			}
			if((len+1)%3==0){
				html+='<li></li>';
			}else if((len+2)%3==0){
				html+='<li></li><li></li>';
			};
			$("div.article .main ul").html(html);
			if(obj.pre_type==3){
				if(data.type.length>0){
					var more='<a href="javascript:;" class="more"></a>',n=0;
					dataType=data.type,dataTypeStr='<dl><dt><a href="javascript:;" name="">分类</a></dt><dd>';
					for(var i=0;i<dataType.length;i++){
						dataTypeStr += '<a href="javascript:;" name="'+dataType[i].id+'">'+dataType[i].name+'</a>';
					};
					dataTypeStr += '</dd></dl>';
					$("div.article .selector").html(dataTypeStr).show();
					$("div.article .selector dl dd a").each(function(){
						n+=parseInt($(this).width());
					});
					if(n>950){
						$("div.article .selector dl").append(more);
					};
				}else{//没有分类
					$("div.article .selector").hide();
				};
			};
		};
		//分页
		var pageStr=printPages({
			pagerSize:data.total_page,//总页数
			pageNum:obj.pageNum,//当前页码
			pageSize:obj.pageSize//每页多少条数据
		});
		$(".pages").html(pageStr);	
	};


	//加入订单 按钮事件
	$(document).on("click",".person-list li .info a.view",function(){
		$(".address-list").hide();//,.selector-order div.select p
		$(".address-body a,.selector-order div.select p a").removeClass("on");
		$(".selector-order div.select span").html("请选择一个订单");
		$(".address-foot").removeClass("on");
		$(this).parent().parent().find(".address-list").show();
		if(getCookie("order_id")){
			$(this).parent().parent().find(".address-list").find(".selector-order").hide();
		}else{
			$(this).parent().next(".address-list").children(".address-head,.address-body").hide();
			$(this).parent().parent().find(".address-list").find(".selector-order").show();
		}
	})

	//鼠标放在选择订单上，显示订单列表
	$(document).on("mouseenter",".selector-order div.select",function(){
		$(this).children("p").show();
		$(this).parent().parent().find(".address-head,.address-body,.address-foot").hide();
	}).on("mouseleave",".selector-order div.select",function(){
		//$(this).parent().parent().find(".address-head,.address-body").show();
		$(this).children("p").hide();
	})

	//关闭选择报价弹窗
	$(document).on("click",".address-list>a",function(){
		$(this).parent().find(".selector-order").hide();
		$(this).parent().find(".selector-order .select").removeClass("on");
		$(this).parent().find(".selector-order .select>span").html("请选择一个订单");
		$(this).parent().hide();
		$(this).parent().parent().parent().parent().find(".address-body a").removeClass("on");
		$(this).parent().parent().parent().parent().find(".address-foot").removeClass("on");
		$(this).parent().parent().find(".info").find("a.price").addClass("on");
	})
	//选择报价
	$(document).on("click",".address-body a",function(){
		//if($(this).hasClass("on")){
			//$(this).removeClass("on");
		//}else{
			$(this).addClass("on").siblings().removeClass("on");
			$(this).parent().parent().parent().find(".info").find(".prod-top").find(".price").html($(this).attr("name"));
			$(this).attr("id")
			$(this).parent().next().find("a").attr("data-priceID",$(this).attr("id"));
			console.log(1);
			$(this).parent().parent().parent().find(".info").find("a.price").addClass("on");
			console.log(2);
			if(getCookie("order_id")){
				$(this).parent().next(".address-foot").addClass("on");
			}else{
				if($(this).parent().parent().find(".selector-order").find(".select>span").html()!="请选择一个订单"){
					$(this).parent().next(".address-foot").addClass("on");
				}else{
					$(this).parent().next(".address-foot").removeClass("on");
				}
			}
		//}
	})
	//选择订单
	$(document).on("click",".selector-order div.select p a",function(){
		$(this).parent().prev("span").html($(this).html());
		$(this).parent().parent().addClass("on");
		$(this).parent().hide();
		$(this).addClass("on").siblings().removeClass("on");
		$(this).parent().parent().parent().parent().find(".address-head,.address-body,.address-foot").show();
		if($(this).parent("p").parent(".select").parent(".selector-order").parent(".address-list").find(".address-body a.on").length>0){
			$(this).parent().parent().parent().parent().find(".address-foot").addClass("on");
		}else{
			$(this).parent("p").parent(".select").parent(".selector-order").parent(".address-list").find(".address-foot").removeClass("on");
		}
	})
	//报价弹窗中的确定按钮 事件
	$(document).on("click",".address-foot a.confirm",function(){
		var param={
			order_id:getCookie("order_id")||$(this).parent().parent().find(".selector-order").find(".select p a.on").attr("id"),
			product_id:$(this).attr("data-priceID"),
			sort:1,
			type:"person",
			price:$(this).parent().prev().children("a.on").attr("name"),
			amount:1,
			subarea_id:$(this).attr("subarea")
		};
		if(!param.order_id){
			alert("获取订单ID失败");return false;
		}
		if(!param.product_id){
			alert("获取报价ID失败");return false;
		}
		if(!param.price){
			alert("获取报价失败");return false;
		}
		if(!param.subarea_id){
			alert("获取type_id失败");return false;
		}
		//var url="http://crm.cike360.com/portal/index.php?r=resource/InsertProduct&order_id="+param.order_id+"&product_id="+param.product_id+"&sort=1&type=person&price="+param.price+"&amount=1&subarea_id="+param.subarea_id;
		//obj.u+"InsertProduct&order_id="+param.order_id+"&product_id="+param.product_id+"&sort="+param.sort+"&type="+param.type+"&price="+param.price+"&amount="+param.amount+"&subarea_id="+param.subarea_id,
		alert(JSON.stringify(param));
		$.ajax({
			type:"POST",
			url:"/portal/index.php?r=resource/InsertProduct",
			data:JSON.stringify(param),
			dataType:"JSON",
			success:function(data){
				alert('添加成功！');
				$(".address-list").hide();
			},
			error:function(data){
				alert("网络连接失败，请重试");
			}
		})
	})

	//翻页功能
	$(document).on("click",".pages a",function(){
		obj.pageNum=$(this).attr("class");
		var param="";
		if(obj.pre_type==3){
			if($("div.article .selector dl dd a.on").length==0){
				param=$("div.article .selector dl dt a").attr("name");
			}else if($("div.article .selector dl dd a.on").length>0){
				param=$("div.article .selector dl dd a.on").attr("name");
			}
		}else if(obj.pre_type==4){
			param=$(".nav-menu a.on").attr("name");
		}
		getList(param,obj.pre_type);
	})
	//分类，查看更多
	$(document).on("click","div.article .selector dl>a.more",function(){
		if($(this).hasClass("on")){
			$(this).removeClass("on");
			$(this).parent().find("dd").css("height","25px");
		}else{
			$(this).addClass("on");
			$(this).parent().find("dd").css("height","auto");
		}
	})
	//pre_type=3 点击分类按钮事件
	$(document).on("click","div.article .selector dl dd a",function(){
		var idx_name = $(this).attr("name");
		if($(this).hasClass("on")){
			return false;
		}
		$(this).addClass("on").siblings().removeClass("on");
		var url="";
		if(obj.pre_type==3){
			url=obj.u+"get_my_collection&type_id="+$(this).attr("name")+"&page=1&token="+obj.userid;//"+obj.pageNum+"
		}
		$.ajax({
			type:"POST",
			url:url,
			data:{},
			success:function(data){
				if(obj.pre_type==3){
					if((data.total_page==1 && data.folder_data.length>0) || (data.total_page>1 && (data.folder_data.length==0 || data.folder_data.length>0))){
						$(".article .none").hide();
						$(".article .main").show();
						showList(data);
						$("[name='"+idx_name+"']").removeClass('on').addClass('on');
					}else{
						$(".prod-list").html("");
						$(".article .main").hide();
						$(".article .none").show();
						$(".pages").html("").hide();
						$("[name='"+idx_name+"']").removeClass('on').addClass('on');
					}
				}
			},
			dataType:"JSON",
			error:function(data){
				alert("查询过程中系统异常："+JSON.stringify(data));
			}
		})
	})
	//pre_type=4 点击左侧导航事件
	$(document).on("click",".nav-menu a",function(){
		if($(this).hasClass("on")){
			return false;
		}
		//obj.pageNum==1;
		$(this).addClass("on").siblings().removeClass("on");
		var url="";
		if(obj.pre_type==4){
			url=url=obj.u+"get_service_person&type_id="+$(this).attr("name")+"&page=1&staff_id="+obj.userid;//"+obj.pageNum+"
		};
		$.ajax({
			type:"POST",
			url:url,
			data:{},
			success:function(data){
				if((data.total_page==1 && data.person.length>0) || (data.total_page>1 && (data.person.length==0 || data.person.length>0))){
					$(".article .none").hide();
					$(".article .main").show();
					showList(data);
				}else{
					$(".prod-list").html("");
					$(".article .main").hide();
					$(".article .none").show();
					$(".pages").html("").hide();
				}
			},
			dataType:"JSON",
			error:function(data){
				alert("查询过程中系统异常："+JSON.stringify(data));
			}
		})
	})

	//点击logo返回
	$(".logo").on("click", function(){
		location.href = '<?php echo $this->createUrl("background/index_front");?>';
	});




























})