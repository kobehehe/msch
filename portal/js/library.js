$(function () {
	
	var library_data;
	var totalPages;
	
	var web_id = 1;
	if(localStorage.getItem("$cur_web_id") != "" && localStorage.getItem("$cur_web_id") != null){
		web_id = localStorage.getItem("$cur_web_id");
		$(".top_area li.main_nav_item").removeClass("active");
        $("[web-id='"+web_id+"']").addClass('active');
	};
	
    /*主tab*/
    $('.top_area').on('click', '.main_nav_item', function () {
        var idx = $(this).index();
        $('.nav_content').eq(idx).show().siblings().hide();
        $(this).addClass('active').siblings().removeClass('active');
//      var html_main = template('tpl-main', {
//	        "case_list": library_data[idx]['case_list']
//	    , });
//	    $("#main_list").html(html_main);
		localStorage.setItem("$cur_web_id",$(this).attr("web-id"));
		location.reload();
   });

    /*折叠*/
    $('.fold_btn').click(function () {
        $(this).toggleClass('up');
        $('.sub_nav_box').toggleClass('none');
    })

    /*副tab*/
    $('.top_area').on('click', '.sub_nav_item', function () {
            var idx = $(this).index();
            $('.sub_nav_content').eq(idx).show().siblings().hide();
            $(this).addClass('active').siblings().removeClass('active');
        })
    
    /*选择筛选分类*/
    $('.top_area').on('click', '.list_item', function () {
        var idx = $(this).index();
        $(this).addClass('active').siblings().removeClass('active');
        $('.sub_nav_box').addClass('none');
    		$('.fold_btn ').removeClass('up');
    		
    		var tab_id =[];
    		var tab_name ="";
    		$(".sub_nav_list li.active").each(function(){
    			if($(this).attr("tab-id") != 0){
    				tab_name += $(this).html() + ","
    				tab_id.push($(this).attr("tab-id"));	
    			};
    		});
    		console.log(tab_name);
    		if(tab_name != ""){
    			tab_name = tab_name.substring(0,tab_name.length-1);	
    			localStorage.setItem("$tabName",tab_name);
    		};
    		
    		
    		console.log(tab_id);
    		
    		if(tab_id.length == 0){

    			$(".m_img_box ").removeClass("hid");
    			$("#tab_title").html("分类");
    			localStorage.setItem("$tabId","");
    			localStorage.setItem("$tabName","");
    			location.reload();
    		}else{

    			localStorage.setItem("$tabId",JSON.stringify({'tab':tab_id}));

    			location.reload();
    		};
    })
    
    /*点击遮罩隐藏*/
    $('.top_area').on('click','.sub_nav_container',function(e){
        if (e.target == this) {
            $('.sub_nav_box').addClass('none');
            $('.fold_btn').removeClass('up');
        }
    })
    
    /*点击案例跳转*/
    $('li.m_img_box').live("click",function(){

    		localStorage.setItem("$cur_lib_case_id",$(this).attr("case-id"));
    		location.href = "library_detail.html";
    })

    /*分页*/
    var pageCount;
    
    var loader = new Loadmore($('.nav_content .list')[0], {
        loadMore: function (page, done) {
            pageCount = page - 1;
            if(pageCount > 1){
            		if (pageCount > totalPages) {
	                loader.destroy();
	            } else {
	            	if(localStorage.getItem("$tabId") != "" && localStorage.getItem("$tabId") != null){
						var tabId = JSON.parse(localStorage.getItem("$tabId"));
						var post_data = {
							'web_id' : web_id,
							'tab' : tabId.tab,
							'page' : pageCount,
						};
	
				    	//读<筛选订单>页接口
						mui.ajax(api_url + 'LibraryTabCase' , {
							dataType: 'JSON',
							type: 'post',
							data: JSON.stringify(post_data),
							timeout: 6000,
							success: function(data) {
								var jsonData = JSON.parse(data);
								pagingXuanran(jsonData['case']);
							}, 
							error: function(xhr, type, errorThrown) {
								console.log(type);
								//ShowIcon(true,'without-wifi.png');
							}
						});
						done();
				    }else{
				    	//读<全部案例>页接口
						mui.ajax(api_url + 'LibraryCasePaging&web_id=' + web_id + '&page=' + pageCount , {
							dataType: 'JSON',
							type: 'GET',
							timeout: 6000,
							success: function(data) {
								var jsonData = JSON.parse(data);
								console.log(jsonData);
								pagingXuanran(jsonData);
							}, 
							error: function(xhr, type, errorThrown) {
								console.log(type);
							}
						});
						done();
				    };
	            };
            }else{
            		pageCount++;
            		done();
            }
        }
    });
    
    function pagingXuanran(data){
		var html = "";
		$.each(data, function(idx,val) {
			html += '<li class="m_img_box" case-id="'+val.id+'"><img src="style/images/d_img.jpg" data-lazyload="'+val.cover_img+'"><div class="info"><p class="title">'+val.company+'</p><p class="end">'+val.name+'</p></div></li>'           			
		});
        $('.nav_content .list').append(html);
    }

})