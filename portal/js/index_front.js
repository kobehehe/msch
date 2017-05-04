$(function(){
	var userid = $.cookie('userid');

	//已登录
	if(userid != null && userid != undefined && userid != '' && userid != 'null' && userid != 'undefined'){
		console.log(userid);
		var post_data = {
			token : 102
		};
		
		/*美思宝藏*/
		$(".concave").addClass("kort");
		$.get("/portal/index.php?r=background/index_front_data&token=" + userid,function(data){
            // $.cookie('temp_resource_id',null);
            var json_data = JSON.parse(data);
            console.log(json_data);
            //1、渲染订单
			var ppt_html = template('tpl-order', {
			    "list": json_data.order_doing,
			    'type': 2
			});
			$("#order").html(ppt_html);            

			//2、渲染套系
			console.log(json_data.order_model);
			var ppt_html = template('tpl-model', {
			    "list": json_data.order_model
			});
			$("#model").html('<div class="imgbox kort no_bg" id="model_more"><img src="images/f_imgmore.png" width="334" height="240"></div>'+
			ppt_html+'<div class="imgbox no_bg"><img src="images/f_img01.png" width="334" height="240"></div>');  
			Kort();

			//3、渲染姓名／头像
			console.log($.cookie('staff_name'))
			$(".user_name").html($.cookie('staff_name'));

			if($.cookie('avatar') != null && $.cookie('avatar') != 'null' && $.cookie('avatar') != '' && $.cookie('avatar') != undefined && $.cookie('avatar') != 'undefined'){
				$(".tx_box img").attr("src", $.cookie('avatar'));	
			};

			//4、判断是否为VIP
			if($.cookie('isVIP') == 'true'){
				$(".yh_icon").removeClass("hide").addClass("hide");
				$(".m_icon").removeClass("hide");
			}else{
				//设卡

			};

			//5、判断是否为供应商
			if($.cookie('is_supplier') == 'true' || $.cookie('is_supplier') == true){
				$('.sz_icon').removeClass('hide');
			};

			//6、判断是否已选择订单
			var cur_order = localStorage.getItem('order_id');
			if(cur_order != '' && cur_order != null && cur_order != undefined){
				var order_name = localStorage.getItem("order_name");
				var order_date = localStorage.getItem('order_date');
				var order_id = cur_order;
				$("#logo_info").removeClass("hide").addClass("hide");
				$("#order_info").removeClass("hide");
				$("#order_info .name").html(order_name);
				$("#order_info .date").html(order_date);

				$("#n_status").removeClass("hide").addClass("hide");
				$("#y_status").removeClass("hide").attr("order-id", order_id);

				$("#example,#print").attr("order-id", order_id);
			};
			

			/*页面初始化*/
			window.onresize = window.onload = function () {
				var cHeight = document.documentElement.clientHeight;
				var topHeight = document.getElementById('container').offsetHeight;
				var imgHeight = document.getElementById('shatan').offsetHeight;
				document.getElementById('bot').style.height = (cHeight - topHeight - imgHeight) + 'px';
			}


			/*登录下拉框*/
			$(".login_box").addClass('has_more');
			$('.no_login').removeClass('hide').addClass('hide');
			$('.logined').removeClass('hide');

			/*订单弹框下拉*/
			$('.order_btn').click(function () {
				$('.mask').show();
				$('.masgbox').slideDown(250);
			})
			$('.mask').click(function () {
				$('.mask').hide();
				$('.masgbox').slideUp(250);
			})
			$('.masgbox').click(function (e) {
				if (e.target == this) {
					$('.mask').hide();
					$('.masgbox').slideUp(250);
				}
			});
			/*订单下拉框搜索*/
            $(".masgbox .search_btn").live("click", function(){
              var txt=$(".masgbox .search_box input").val();
              if($.trim(txt)!=""){        
                $(".masgbox .tab_content li").hide().filter(":contains('"+txt+"')").show();
              }else{
                $(".masgbox .tab_content li").show();
              }
            });
            /*订单下拉框搜索输入框的回车操作*/  
            $('.masgbox .search_box input').bind('keypress',function(event){  
                if(event.keyCode == "13") 
                    $('.masgbox .search_btn').click();  
            });

			/*导航跳转*/
			$(".nav_box li,.nav_down_list li").on("click", function(){
				var action = $(this).attr("action");
				open_window(action);
			});


			/*猫跳转*/
			$(".PathItem").on("click", function(){
				var pre_type = $(this).attr("pre-type");
				jump(pre_type);		
			});

			/*订单点击事件*/
			$("#order li").live("click", function(){
				var order_id = $(this).attr("order-id");
				var order_name = $(this).attr("order-name");
				var order_date = $(this).attr("order-date");
				var action = $(this).parent().attr('action');

				$("#logo_info").removeClass("hide").addClass("hide");
				$("#order_info").removeClass("hide");
				$("#order_info .name").html(order_name);
				$("#order_info .date").html(order_date);

				$("#n_status").removeClass("hide").addClass("hide");
				$("#y_status").removeClass("hide").attr("order-id", order_id);

				$("#example,#print").attr("order-id", order_id);

				$('.mask').hide();
				$('.masgbox').slideUp(250);

				localStorage.setItem('order_id', order_id);
				localStorage.setItem('order_name', order_name);
				localStorage.setItem('order_date', order_date);

				if(action == 'bill'){
					window.open('index.php?r=background/bill&order_id=' + order_id + '&token=' + $.cookie("userid"));
					$(this).parent().attr('action', '');
				};
				if(action == 'example'){
					var post_data = {'order_id': order_id};
					$.post("index.php?r=dailyReport/Get_template_id", JSON.stringify(post_data) ,function(data){
		                window.open('index.php?r=background/example'+JSON.parse(data).template_id+'&order_id=' + order_id + '&token=' + $.cookie("userid"));
						$(this).parent().attr('action', '');
		            });
				};
				if(action == 'price_list'){
					location.href = 'index.php?r=background/price_list&order_id=' + order_id + '&token=' + $.cookie("userid");
					$(this).parent().attr('action', '');
				};
			});

			/*订单下拉框中的订单详情*/
			$(".wjdj_btn").live("click", function(){
				location.href = 'index.php?r=background/price_list&order_id=' + $(this).parent().attr('order-id') + '&token=' + $.cookie("userid");
			});

			/*订单删除事件*/
			$(".del_order_btn").live("click", function(event){
				var _this = $(this).parent();
				var order_id = _this.attr("order-id");
				var order_name = _this.attr("order-name");
				layer.confirm('确认要删除['+order_name+']么？',function(){
					$.ajax({
						type : 'post',
						dataType : "JSON",
						url : '/portal/index.php?r=resource/delOrder',
						data : JSON.stringify({
							order_id:order_id
						}),
						success : function(res){
							if(res.result == "success"){
								layer.msg('删除成功',{time:1000},function(){
									_this.remove();
									if(order_id == localStorage.getItem('order_id')){
										localStorage.setItem('order_id', '');
										localStorage.setItem('order_name', '');
										localStorage.setItem('order_date', '');
										location.reload();
									}
								});
							}else{
								layer.msg('网络问题，删除失败',{time:1000});
							}							
						},
						error:function(){
							layer.msg('网络问题，删除失败',{time:1000});
						}
					});
				});
				event.stopPropagation();
			});

			/*策划案&报价单*/
			$("#print").on("click", function(){
				if($(this).attr('order-id') == ''){
					$('.mask').show();
					$('.masgbox').slideDown(250);
					$("#cat03").css("z-index", 1).attr("data-flag", 0);
					$("#order").attr('action', 'bill');
				}else{
					location.href = 'index.php?r=background/bill&order_id=' + $(this).attr('order-id') + '&token=' + $.cookie("userid");
				};
			});

			$("#example").on("click", function(){
				if($(this).attr('order-id') == ''){
					$('.mask').show();
					$('.masgbox').slideDown(250);
					$("#cat03").css("z-index", 1).attr("data-flag", 0);
					$("#order").attr('action', 'example');
				}else{
					var order_id = $(this).attr('order-id');
					var post_data = {'order_id': order_id};
					$.post("index.php?r=dailyReport/Get_template_id", JSON.stringify(post_data) ,function(data){
		                window.open('index.php?r=background/example'+JSON.parse(data).template_id+'&order_id=' + order_id + '&token=' + $.cookie("userid"));
						$(this).parent().attr('action', '');
		            });
				};
			});

			/*订单详情*/
			$("#y_status").on("click", function(){
				if($(this).attr('order-id') == ''){
					$('.mask').show();
					$('.masgbox').slideDown(250);
					$("#order").attr('action', 'price_list');
				}else{
					location.href = 'index.php?r=background/price_list&order_id=' + $(this).attr('order-id') + '&token=' + $.cookie("userid");
				};
			})

			/*导航下拉*/
			$('.has_more').mouseenter(function () {
				console.log("000")
				if ($(this).children('.logined')) {
					$(this).children('.logined').addClass('active');
				}
				$(this).children('.nav_down_list').slideDown(200);
			}).mouseleave(function () {
				if ($(this).children('.logined')) {
					$(this).children('.logined').removeClass('active');
				};
				$(this).children('.nav_down_list').slideUp(200);
			});

			/*套系跳转*/
			$(".model").on("click", function(){
				var model_id = $(this).attr('model-id');
				var model_order = $(this).attr('order-id');
				var post_data = {'order_id': model_order};

				$.post("index.php?r=dailyReport/Get_template_id", JSON.stringify(post_data) ,function(data){
	                window.open('index.php?r=background/example'+JSON.parse(data).template_id+'&type=model&model-id=' + model_id + '&order_id=' + model_order + '&token=' + $.cookie("userid"));
	            });
			});

			/*退出*/
			$('.tc_icon').on("click", function(){
				$.cookie("userid",null,{path:"/"});
				localStorage.setItem('order_id', '');
				localStorage.setItem('order_name', '');
				localStorage.setItem('order_date', '');
				location.reload();
			});

			/*新增订单*/
			$('.new_order_btn').on("click", function(){
				location.href = 'index.php?r=background/new_order';
			});

			/*婚礼／会议切换*/
			$(".tab_nav span").on("click", function(){
				if(!$(this).hasClass("active")){
					$(".tab_nav span").removeClass('active');
					$(this).addClass('active');

					var ppt_html = template('tpl-order', {
					    "list": json_data.order_doing,
					    'type': $(this).attr('type')
					});
					$("#order").html(ppt_html);   
					$(".masgbox .search_box input").val('');//将搜索清空
				};
			})
        });
	}else{
		$(".order_btn,#print,#example,.PathItem,.nav_box li,.no_login,.concave").on("click", function(){
			location.href = 'index.php?r=background/login_cat';
		});

		/*导航下拉*/
		$('.has_more').mouseenter(function () {
			console.log("000")
			if ($(this).children('.logined')) {
				$(this).children('.logined').addClass('active');
			}
			$(this).children('.nav_down_list').slideDown(200);
		}).mouseleave(function () {
			if ($(this).children('.logined')) {
				$(this).children('.logined').removeClass('active');
			}
			$(this).children('.nav_down_list').slideUp(200);
		})
	}

	function open_window(action)
	{
		if(action == 'exit'){

		};
		if(action == 'library'){  //暂时只有  library   
			location.href = ('index.php?r=background/' + action);	
		};
		if(action == 'background'){  //暂时只有  library   
			window.open('index.php?r=background/index&CI_Type=101');	
		};
		if(action == 'supplier'){  //暂时只有  library   
			window.open('index.php?r=background/index_supplier')
		};
	}

			

	function jump(pre_type)
	{
		if(pre_type != ''){
			location.href = 'index.php?r=background/sales_list&pre_type=' + pre_type;	
		};
	}
})






/*小猫效果*/
var angle = Math.PI / ((4 - 1) * 2);
var Radius = 200; //小图出来的半径
var Offset = 10; //小图出来后的偏移量
var OutSpeed = 80; //小图出现的速度
var OutIncr = 1; //小图出来的旋转
var OffsetSpeed = 1; //小图出来的旋转速度
var InSpeed = 1; //小图进去的速度
var InIncr = 10; //小图进去的旋转
function PathRun(ele, Path, PathStatus) {
	var PathMenu = ele;
	var PathItems = PathMenu.children('.PathItem').slice(0, 4);
	//var PathStatus = PathMenu.attr('data-flag');
	if (PathStatus == 0) {
		var Count = PathItems.size();
		PathMenu.css('z-index', '10');
		PathItems.show();
		// $('.mask').fadeIn(100);
		PathItems.each(function (SP) {
			var ID = $(this).index();
			if (Path == 1) {
				if (ID == 1) {
					var X = Radius;
					var Y = 100;
					var X1 = X + Offset;
					var Y1 = Y;
				} else if (ID == Count) {
					var X = 100;
					var Y = 0;
					var X1 = X;
					var Y1 = Y + Offset;
				}
			} else if (Path == 2) {
				if (ID == 1) {
					var X = 0;
					var Y = 0;
					var X1 = X + Offset;
					var Y1 = Y;
				} else if (ID == Count) {
					var X = 170;
					var Y = 0;
					var X1 = X;
					var Y1 = Y + Offset;
				}
			} else if (Path == 3) {
				if (ID == 1) {
					var X = 0;
					var Y = 180;
					var X1 = X + Offset;
					var Y1 = Y;
				} else if (ID == Count) {
					var X = 0;
					var Y = 0;
					var X1 = X;
					var Y1 = Y + Offset;
				}
			}
			$(this).children().children().animate({
				rotate: 360
			}, 600);
			$(this).animate({
				left: X1,
				bottom: Y1
			}, OutSpeed + SP * OutIncr, function () {
				$(this).animate({
					left: X,
					bottom: Y
				}, OffsetSpeed);
			});
		});
		PathMenu.attr('data-flag', '1');
	} else if (PathStatus == 1) {
		PathItems.each(function (SP) {
			X1 = parseInt($(this).css('left'));
			Y1 = parseInt($(this).css('bottom'));
			$(this).children().children().animate({
				rotate: 0
			}, 600);
			if (Path == 1) {
				$(this).animate({
					left: X1,
					bottom: Y1
				}, OffsetSpeed, function () {
					$(this).animate({
						left: 0,
						bottom: 100
					}, InSpeed + SP * InIncr);
				});
			} else if (Path == 2) {
				$(this).animate({
					left: X1,
					bottom: Y1
				}, OffsetSpeed, function () {
					$(this).animate({
						left: 40,
						bottom: 110
					}, InSpeed + SP * InIncr);
				});
			} else {
				$(this).animate({
					left: X1,
					bottom: Y1
				}, OffsetSpeed, function () {
					$(this).animate({
						left: 140,
						bottom: 70
					}, InSpeed + SP * InIncr);
				});
			}
		});
		PathMenu.attr('data-flag', '0');
		setTimeout(function () {
			PathItems.hide();
			// $('.mask').hide();
			// $('.mask').fadeOut();
			PathMenu.css('z-index', '1');
		}, 10)
	}
}