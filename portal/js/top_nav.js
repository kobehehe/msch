/**
 * top_nav.js最上方导航

 调用方式：
 首页：$('body').top_nav();
 其他页：
 $('body').top_nav({
		active_no:1
	});
 */

(function($){
	// if(typeof($.fn.top_nav) != 'undefined') {return false;} // prevent dmultiple scripts inits
	
	$.fn.top_nav = function(options) {
		var defaults = {
			active_no: 0,//0是首页	
		};
		var options = $.extend(defaults, options);

		var nav_list = [{
			name:"首页", 
			page_url:"index_front",
		},{
			name:"灵感库", 
			page_url:"library",
		},{
			name:"精选库", 
			page_url:"sales_list&pre_type=6",
			
		},{
			name:"共享库房",
			page_url:"",
			display:"none"//如果要显示，看这里看这里,要显示，去掉这个变量
		},{
			name:"创意集市",
			page_url:"", 
			sub_page_url:[{
				name:"效果图",
				page_url:"", 
			},{
				name:"案例商城",
				page_url:"", 
			},{
				name:"道具商城",
				page_url:"", 
			},]
		}];

		var my_list = [{
			name:"升级VIP", 
			page_url:"buyVIP",
			my_class:"my-ms"
		},{
			name:"我的美思", 
			page_url:"index&CI_Type=order",
			my_class:"manager"
		},{
			name:"美思供应商", 
			page_url:"",	
			// my_class:"ms-supplier"
			my_class:"manager"
		},{
			name:"退出",
			page_url:"",
			my_class:"logout"
		}];
		var api_url = "/portal/index.php?r=background/";

		if($.cookie('vip') == 'true' || $.cookie('vip') == true){//是vip不显示升级vip
			my_list[0].display ="none";
		}
		if($.cookie('is_supplier')!='true' || $.cookie('is_supplier') != true){//不是美思供应商不显示
			my_list[2].display ="none";
		}

		xuanran();


		function xuanran(){
			var html = '<div class="header">';
			html += '<div class="w">';
			html += '	<a href="/portal/index.php?r=background/index_front" class="logo" title="美思策划"></a>';
			html += '	<div class="nav cl">';
			html += '		<ul class="cl">';
			$.each(nav_list, function(index, value) {
    			var is_active = '';    			
    			if(options.active_no == index){
    				is_active = 'on';
    			}
    			if(value.display==undefined){//如果没有display变量，即显示
	    			if(value.sub_page_url==undefined){
	    				html += '<li><a href="'+ api_url+  value.page_url +'" class="' + is_active + '">'+ value.name +'</a></li>';
	    			}else{//创意集市
						html += '<li class="chuangyijishi-li">';
						html += '<a href="'+ api_url+ value.page_url +'" class="' + is_active + '">'+ value.name +'<i></i></a>';
						html += '<div class="chuangyijishi-more-link">';

						$.each(value.sub_page_url, function(ind, val) {
							html += '<a href="'+ api_url+ val.page_url+'">'+val.name+'</a>';
						});
						html += '</div></li>';
	    			}  
    			}     				          
    		});
			html += '</ul></div>';

			// 头像部分呈现内容
			html += '<div class="account">';
			var userid = $.cookie("userid");
			if(userid != null && userid != undefined && userid != '' && userid != 'null' && userid != 'undefined'){
				html += '	<a href="javascript:;" class="person">';
				html += '			<img src="images/member.png" alt="" class="pic" />';
				html += '			<span class="pic"></span>';
				html += '			<span class="name">'+$.cookie('staff_name')+'</span>';
				html += '			<i></i>';
				html += '		</a>';
				html += '		<div class="member-navs">';

				$.each(my_list, function(index, value) {
					var jump_url = api_url+ value.page_url;
					if(index == 3){ // 如果是退出
						jump_url = "javascript:;";
					}
					if(value.display==undefined ){//如果没有display变量，即显示
						html += '<a href="'+ jump_url +'" class="'+value.my_class+'"><i></i>'+value.name+'</a>';
					}
				});
				html += '		</div>';
				html += '	</div>';
				html += '</div>';
				html += '</div>';
			}else{
				html += '<a href="javascript:;" class="person login" >';
				html += '	<span class="name login">登录</span>';
				html += '</a>';
			}

			$('body').prepend(html);


			//渲染头像
			if($.cookie('avatar') != null && $.cookie('avatar') != 'null' && $.cookie('avatar') != '' && $.cookie('avatar') != undefined && $.cookie('avatar') != 'undefined'){
				$(".person img").attr("src", $.cookie('avatar'));	
			};

			//退出操作
			$('.logout').on("click", function(){
				$.cookie("userid",null,{path:"/"});
				localStorage.setItem('order_id', '');
				localStorage.setItem('order_name', '');
				localStorage.setItem('order_date', '');
				location.href = 'index.php?r=background/login_cat';
			});

			//登录操作
			$('.login').on("click", function(){
				location.href = 'index.php?r=background/login_cat';
			});

			//判断是否为VIP
			// if($.cookie('vip')== 'true' || $.cookie('vip') == true){
			// 	$('.ms-supplier').on("click",function(){
			// 		window.open('index.php?r=background/index&CI_Type=order');			
			// 	});
			// }else{
			// 	//设卡
			// };
		}

	}
	
})(jQuery);
