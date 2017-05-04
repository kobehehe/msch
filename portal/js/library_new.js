 $(function () {
 	var library_data;
 	var totalPages;
 	var curPage = 1;
 	var web_id = 1;
 	var sear = false;
 	if (localStorage.getItem("$cur_web_id") != "" && localStorage.getItem("$cur_web_id") != null) {
 		web_id = localStorage.getItem("$cur_web_id");
 		$(".top_area li.main_nav_item").removeClass("active");
 		$("[web-id='" + web_id + "']").addClass('active');
 	};

 	if (localStorage.getItem("$tabId") != "" && localStorage.getItem("$tabId") != null) {
 		//筛选状态
 		$.ajax({
 			url: "<?php echo $this->createUrl('background/Img_filter');?>&page=" + curPage + "&tab_list=" + localStorage.getItem("$tab_list") + '&token=<?php echo $_COOKIE['
 			userid ']?>",
 			type: "get",
 			async: true,
 			cache: false,
 			datatype: "json",
 			success: function (data1) {
 				firstXuanran(data1);
 			},
 			error: function (data) {
 				alert("网络访问出现问题!");
 			}
 		});
 	} else {
 		//正常浏览
 		$.ajax({
 			url: "<?php echo $this->createUrl('DailyReport/imgStore');?>&web_id=0&page=1&token=<?php echo $_COOKIE['userid'];?>",
 			type: "get",
 			async: true,
 			cache: false,
 			datatype: "json",
 			success: function (data) {
 				console.log('first:' + data);
 				firstXuanran(data);
 			},
 			error: function (data) {
 				alert("网络访问出现问题!");
 			}
 		});
 	}

 	var imgLength;
 	var imgIndex;
 	var mySwiper;

 	var winWidth = window.screen.availWidth,
 		winHeight = window.screen.availHeight;
 	var wScale = winWidth / winHeight;

 	//面包屑导航
 	$.fn.bread_nav = function (options) {
 		var defaults = {
 			father_class: 'bread_nav',
 			font_size: '14px',
 			nav_name_list: [{
 				nav_name: '首页',
 				nav_link: 'index_front',
 			}]
 		};
 		var options = $.extend(defaults, options);
 		xuanran();

 		function xuanran() {
 			var html = '<div style="font-size:14px ;margin: 20px 0 10px 0;height: 20px;line-height: 20px;">';
 			var nav_num = options.nav_name_list.length;
 			$.each(options.nav_name_list, function (i, val) {
 				var color = '#444';
 				if (i == nav_num - 1) color = '#feb300;';
 				html += '<a href="' + val.nav_link + '" style="color:' + color + ';display:inline-block;">' + val.nav_name + '</a>';
 				if (i != nav_num - 1) {
 					html += '<i  style="display:inline-block;width: 12px;height: 15px;position: relative;top: 1px;background: url(images/sprite.png) no-repeat -8px -74px;margin: 0 10px;"></i>';
 				}
 			});
 			html += '</div>';
 			$("." + options.father_class).prepend(html);
 		}
 	}
 	$('body').bread_nav({
 		father_class: 'bread_nav',
 		nav_name_list: [{
 			nav_name: '首页',
 			nav_link: 'index.php?r=background/index_front'
 		}, {
 			nav_name: '灵感库',
 			nav_link: ''
 		}]
 	});

 	$('body').top_nav({
 		active_no: 1
 	});

 	$("#main_list").on("click", ".m_img_box", function (e) {
 		$(".img_show").addClass("show");
 		imgLength = $("#main_list .m_img_box").length;
 		imgIndex = $(this).attr("img-index");
 		console.log(imgIndex);
 		var i = new Image();
 		i.src = $(this).attr("data-url");
 		var rw = i.width;
 		var rh = i.height;
 		if (rw / rh > wScale) {
 			$(".img_show .swiper-wrapper").html('<div class="swiper-slide flexbox center slider' + imgIndex + '"><img src="' +
 				$(this).attr("data-url") +
 				'" style="width:100%;height:auto;"/></div>');
 		} else {
 			$(".img_show .swiper-wrapper").html('<div class="swiper-slide flexbox center slider' + imgIndex + '"><img src="' +
 				$(this).attr("data-url") +
 				'" /></div>');
 		};

 		mySwiper = new Swiper('.swiper-container', {
 			centeredSlides: true,
 		});

 		for (var i = imgIndex - 1; i > 0 && i >= imgIndex - 2; i--) {
 			mySwiper.prependSlide(htmlStr(i));
 		};
 		for (var i = parseInt(imgIndex) + 1; i <= imgLength && i <= parseInt(imgIndex) + 2; i++) {
 			mySwiper.appendSlide(htmlStr(i));
 		};
 	});
 	/*上一张  大图*/
 	$(".swiper-prev-button").click(function () {
 		if ($(".swiper-wrapper .slider" + (parseInt(imgIndex) + 2)).length > 0 && (parseInt(imgIndex) + 2) > 5) {
 			$(".swiper-wrapper .slider" + (parseInt(imgIndex) + 2)).remove();
 		}
 		var i = imgIndex - 3;
 		if (i > 0) {
 			if ($(".swiper-wrapper .slider" + i).length < 1) {
 				mySwiper.prependSlide(htmlStr(i));
 			}
 		}
 		if (imgIndex > 1) {
 			imgIndex -= 1;
 		}
 		mySwiper.slidePrev();
 	});
 	/*上一张  大图*/
 	$(".swiper-next-button").click(function () {
 		if ($(".swiper-wrapper .slider" + (parseInt(imgIndex) - 2)).length > 0 && (parseInt(imgIndex) - 2) < imgLength - 4) {
 			$(".swiper-wrapper .slider" + (parseInt(imgIndex) - 2)).remove();
 		} else {
 			mySwiper.slideNext();
 		}
 		var i = parseInt(imgIndex) + 3;
 		if (i <= imgLength) {
 			if ($(".swiper-wrapper .slider" + i).length < 1) {
 				mySwiper.appendSlide(htmlStr(i));
 			}
 		}
 		if (imgIndex < imgLength) {
 			imgIndex = parseInt(imgIndex) + 1;
 		}
 	});

 	function htmlStr(i) {
 		var ii = new Image();
 		ii.src = $("#main_list .m_img_box[img-index=\"" + i + "\"]").attr("data-url");
 		var rw = ii.width;
 		var rh = ii.height;
 		if (rw / rh > wScale) {
 			var html = '<div class="swiper-slide flexbox center  slider' + i + '"><img src="' +
 				$("#main_list .m_img_box[img-index=\"" + i + "\"]").attr("data-url") +
 				'" style="width:100%;height:auto;"/></div>'
 		} else {
 			var html = '<div class="swiper-slide flexbox center  slider' + i + '"><img src="' +
 				$("#main_list .m_img_box[img-index=\"" + i + "\"]").attr("data-url") +
 				'" /></div>'
 		}
 		return html;
 	}

 	$(".img_show .content").click(function (e) {
 		if ($(e.target).hasClass("swiper-prev-button") || $(e.target).hasClass("swiper-next-button") || $(e.target).hasClass("asasa")) {
 			return;
 		}
 		$(".img_show").removeClass("show");
 	});

 	function firstXuanran(data) {
 		//console.log(data)
 		library_data = JSON.parse(data)
 		//console.log(library_data.tab.color);
 		totalPages = library_data.page;
 		var html_sdo2 = template('tpl_tabnav', {
 			"list": library_data['web'],
 		});
 		$("#tab_nav").html(html_sdo2);

 		var html_sdo3 = template('tpl_tab', {
 			"tab": library_data['tab_data'],
 		});
 		$("#sub_nav_container").html(html_sdo3);
 		//console.log('data:'+data);
 		var html_main = template('tpl-main', {
 			"case_list": library_data['img']
 		});
 		$("#main_list").html(html_main);

 		if (library_data['img'].length <= 0) {
 			$(".search_none").show();
 		}

 		if (localStorage.getItem("$tabId") != "" && localStorage.getItem("$tabId") != null) {
 			var tabId = JSON.parse(localStorage.getItem("$tabId"));
 			$(tabId.tab).each(function (idx, val) {
 				$("[tab-id = '" + val + "']").addClass("active");
 			});
 		};
 		$("#sub_nav_container .list span.active").each(function () {
 			if ($(this).attr("tab-id") != 0) {
 				$(this).siblings().removeClass("active");
 			};
 		});

 		if (localStorage.getItem("$cur_web_id") != "" && localStorage.getItem("$cur_web_id") != null) {
 			$(".top_area li.main_nav_item").removeClass("active");
 			$("[web-id='" + web_id + "']").addClass('active');
 		};

 		if (localStorage.getItem("colorfi") != "" && localStorage.getItem("colorfi") != null) {
 			$("#colorfi").html("<p data-id='" + localStorage.getItem("colorfiid") + "'>" + localStorage.getItem("colorfi") + "</p><span>X</span>").show();
 		};
 		if (localStorage.getItem("stylefi") != "" && localStorage.getItem("stylefi") != null) {
 			$("#stylefi").html("<p data-id='" + localStorage.getItem("stylefiid") + "'>" + localStorage.getItem("stylefi") + "</p><span>X</span>").show();
 		};
 		if (localStorage.getItem("changjingfi") != "" && localStorage.getItem("changjingfi") != null) {
 			$("#changjingfi").html("<p data-id='" + localStorage.getItem("changjingfiid") + "'>" + localStorage.getItem("changjingfi") + "</p><span>X</span>").show();
 		};
 		if (localStorage.getItem("danpinfi") != "" && localStorage.getItem("danpinfi") != null) {
 			$("#danpinfi").html("<p data-id='" + localStorage.getItem("danpinfiid") + "'>" + localStorage.getItem("danpinfi") + "</p><span>X</span>").show();
 		};

 		/*筛选列表初始化*/
 		var liHeight = $('.filter_list li').height() - 21;
 		console.log(liHeight)
 		for (var i = 0; i < $('.filter_list li').length; i++) {
 			var listHeight = $('.filter_list li').eq(i).find('.list')[0].offsetHeight;
 			if (listHeight - 5 > liHeight) {
 				$('.filter_list li').eq(i).find('.morebtn').show();
 			}
 		}
 		/*更多展开*/
 		$(".filter_list").on('click', '.morebtn', function () {
 			var $this = $(this);
 			if ($this.hasClass('up')) {
 				console.log(liHeight)
 				$this.parents('li').css('height', liHeight);
 				$this.removeClass('up').html('更多');
 			} else {
 				$this.parents('li').css('height', $this.parents('li').find('.list')[0].offsetHeight);
 				$this.addClass('up').html('收起');
 			}
 		})
 	}

 	/*主tab*/
 	$('.top_area').on('click', '.main_nav_item', function () {
 		var idx = $(this).index();
 		$('.nav_content').eq(idx).show().siblings().hide();
 		$(this).addClass('active').siblings().removeClass('active');
 		//      var html_main = template('tpl-main', {
 		//          "case_list": library_data[idx]['case_list']
 		//      , });
 		//      $("#main_list").html(html_main);
 		localStorage.setItem("$cur_web_id", $(this).attr("web-id"));
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

 		var tab_id = [];
 		var tab_list = '';
 		var tab_name = "";
 		$(".sub_nav_list li.active").each(function () {
 			if ($(this).attr("tab-id") != 0) {
 				tab_name += $(this).html() + ","
 				tab_id.push($(this).attr("tab-id"));
 				tab_list = $(this).attr("tab-id") + ",";
 			};
 		});
 		console.log(tab_name);
 		if (tab_name != "") {
 			tab_name = tab_name.substring(0, tab_name.length - 1);
 			localStorage.setItem("$tabName", tab_name);
 		};


 		console.log(tab_id);

 		if (tab_id.length == 0 || $(this).attr("tab-id") == 0) {
 			$(".m_img_box ").removeClass("hid");
 			$("#tab_title").html("分类");
 			localStorage.setItem("$tabId", "");
 			localStorage.setItem("$tabName", "");
 			localStorage.setItem("$tab_list", '');
 			location.reload();
 		} else {
 			localStorage.setItem("$tab_list", tab_list.substring(0, tab_list.length - 1));
 			localStorage.setItem("$tabId", JSON.stringify({
 				'tab': tab_id
 			}));
 			location.reload();
 		};
 	})

 	$("#sub_nav_container").on("click", ".list span", function () {
 		sear = false;
 		$(".search_none").hide();
 		$(this).addClass('active').siblings().removeClass('active');
 		if ($(this).parents(".list").hasClass("color")) {
 			if ($(this).attr("tab-id") == 0) {
 				$("#colorfi").hide();
 				localStorage.setItem("colorfi", "");
 				localStorage.setItem("colorfiid", "");
 			} else {
 				$("#colorfi").html("<p data-id='" + $(this).attr("tab-id") + "'>" + $(this).html() + "</p><span>X</span>").show();
 				localStorage.setItem("colorfi", $(this).html());
 				localStorage.setItem("colorfiid", $(this).attr("tab-id"));
 			}
 		}
 		if ($(this).parents(".list").hasClass("style")) {
 			if ($(this).attr("tab-id") == 0) {
 				$("#stylefi").hide();
 				localStorage.setItem("stylefi", "");
 				localStorage.setItem("stylefiid", "");
 			} else {
 				$("#stylefi").html("<p data-id='" + $(this).attr("tab-id") + "'>" + $(this).html() + "</p><span>X</span>").show();
 				localStorage.setItem("stylefi", $(this).html());
 				localStorage.setItem("stylefiid", $(this).attr("tab-id"));
 			}
 		}
 		if ($(this).parents(".list").hasClass("changjing")) {
 			if ($(this).attr("tab-id") == 0) {
 				$("#changjingfi").hide();
 				localStorage.setItem("changjingfi", "");
 				localStorage.setItem("changjingfiid", "");
 			} else {
 				$("#changjingfi").html("<p data-id='" + $(this).attr("tab-id") + "'>" + $(this).html() + "</p><span>X</span>").show();
 				localStorage.setItem("changjingfi", $(this).html());
 				localStorage.setItem("changjingfiid", $(this).attr("tab-id"));
 			}
 		}
 		if ($(this).parents(".list").hasClass("danpin")) {
 			if ($(this).attr("tab-id") == 0) {
 				$("#danpinfi").hide();
 				localStorage.setItem("danpinfi", "");
 				localStorage.setItem("danpinfiid", "");
 			} else {
 				$("#danpinfi").html("<p data-id='" + $(this).attr("tab-id") + "'>" + $(this).html() + "</p><span>X</span>").show();
 				localStorage.setItem("danpinfi", $(this).html());
 				localStorage.setItem("danpinfiid", $(this).attr("tab-id"));
 			}
 		}

 		var tab_id = [];
 		var tab_list = '';
 		var tab_name = "";
 		$("#sub_nav_container .list span.active").each(function () {
 			if ($(this).attr("tab-id") != 0) {
 				tab_name += $(this).html() + ","
 				tab_id.push($(this).attr("tab-id"));
 				tab_list = $(this).attr("tab-id") + ",";
 			};
 		});
 		if (tab_name != "") {
 			tab_name = tab_name.substring(0, tab_name.length - 1);
 		};
 		if (tab_list != "") {
 			tab_list = tab_list.substring(0, tab_list.length - 1);
 		};
 		localStorage.setItem("$tabId", tab_id.length > 0 ? JSON.stringify({
 			'tab': tab_id
 		}) : "");
 		localStorage.setItem("$tabName", tab_name);
 		localStorage.setItem("$tab_list", tab_list);
 		curPage = 1;
 		if (tab_id.length > 0) {
 			$.ajax({
 				url: "<?php echo $this->createUrl('background/Img_filter');?>&page=" + curPage + "&tab_list=" + tab_list + '&token=<?php echo $_COOKIE['
 				userid ']?>',
 				type: "get",
 				async: true,
 				cache: false,
 				datatype: "json",
 				success: function (data) {
 					library_data = JSON.parse(data);
 					totalPages = library_data.page;
 					var html_main = template('tpl-main', {
 						"case_list": library_data['img']
 					});
 					$("#main_list").html(html_main);
 					if (library_data['img'].length <= 0) {
 						$(".search_none").show();
 					}
 				},
 				error: function (data) {
 					alert("网络访问出现问题!");
 				}
 			});
 		} else {
 			$.ajax({
 				url: "<?php echo $this->createUrl('DailyReport/imgStore');?>&web_id=0&page=1&token=<?php echo $_COOKIE['userid'];?>",
 				type: "get",
 				async: true,
 				cache: false,
 				datatype: "json",
 				success: function (data) {
 					library_data = JSON.parse(data);
 					totalPages = library_data.page;
 					var html_main = template('tpl-main', {
 						"case_list": library_data['img']
 					});
 					$("#main_list").html(html_main);
 					if (library_data['img'].length <= 0) {
 						$(".search_none").show();
 					}
 				},
 				error: function (data) {
 					alert("网络访问出现问题!");
 				}
 			});
 		}
 	})

 	$("#colorfi").click(function () {
 		sear = false;
 		$(".search_none").hide();
 		$("#colorfi").hide();
 		$("#sub_nav_container .list.color span[tab-id=\"0\"]").addClass("active").siblings().removeClass("active");
 		var id = $("#colorfi p").attr("data-id");
 		var tabId = JSON.parse(localStorage.getItem("$tabId")).tab;
 		tabId.splice(tabId.indexOf(id), 1)
 		var tab_list = tabId.length > 0 ? tabId.join(",") : "";
 		localStorage.setItem("$tabId", tabId.length > 0 ? JSON.stringify({
 			'tab': tabId
 		}) : "");
 		localStorage.setItem("$tab_list", tabId.length > 0 ? tabId.join(",") : "");
 		localStorage.setItem("colorfi", "");
 		curPage = 1;
 		if (tabId.length > 0) {
 			$.ajax({
 				url: "<?php echo $this->createUrl('background/Img_filter');?>&page=" + curPage + "&tab_list=" + tab_list + '&token=<?php echo $_COOKIE['
 				userid ']?>',
 				type: "get",
 				async: true,
 				cache: false,
 				datatype: "json",
 				success: function (data) {
 					console.log()
 					library_data = JSON.parse(data);
 					totalPages = library_data.page;
 					var html_main = template('tpl-main', {
 						"case_list": library_data['img']
 					});
 					$("#main_list").html(html_main);
 					if (library_data['img'].length <= 0) {
 						$(".search_none").show();
 					}
 				},
 				error: function (data) {
 					alert("网络访问出现问题!");
 				}
 			});
 		} else {
 			$.ajax({
 				url: "<?php echo $this->createUrl('DailyReport/imgStore');?>&web_id=0&page=1&token=<?php echo $_COOKIE['userid'];?>",
 				type: "get",
 				async: true,
 				cache: false,
 				datatype: "json",
 				success: function (data) {
 					library_data = JSON.parse(data);
 					totalPages = library_data.page;
 					var html_main = template('tpl-main', {
 						"case_list": library_data['img']
 					});
 					$("#main_list").html(html_main);
 					if (library_data['img'].length <= 0) {
 						$(".search_none").show();
 					}
 				},
 				error: function (data) {
 					alert("网络访问出现问题!");
 				}
 			});
 		}
 	})
 	$("#stylefi").click(function () {
 		sear = false;
 		$(".search_none").hide();
 		$("#stylefi").hide();
 		$("#sub_nav_container .list.style span[tab-id=\"0\"]").addClass("active").siblings().removeClass("active");
 		var id = $("#stylefi p").attr("data-id");
 		var tabId = JSON.parse(localStorage.getItem("$tabId")).tab;
 		tabId.splice(tabId.indexOf(id), 1)
 		var tab_list = tabId.length > 0 ? tabId.join(",") : "";
 		localStorage.setItem("$tabId", tabId.length > 0 ? JSON.stringify({
 			'tab': tabId
 		}) : "");
 		localStorage.setItem("$tab_list", tabId.length > 0 ? tabId.join(",") : "");
 		localStorage.setItem("stylefi", "");
 		curPage = 1;
 		if (tabId.length > 0) {
 			$.ajax({
 				url: "<?php echo $this->createUrl('background/Img_filter');?>&page=" + curPage + "&tab_list=" + tab_list + '&token=<?php echo $_COOKIE['
 				userid ']?>',
 				type: "get",
 				async: true,
 				cache: false,
 				datatype: "json",
 				success: function (data) {
 					library_data = JSON.parse(data);
 					totalPages = library_data.page;
 					var html_main = template('tpl-main', {
 						"case_list": library_data['img']
 					});
 					$("#main_list").html(html_main);
 					if (library_data['img'].length <= 0) {
 						$(".search_none").show();
 					}
 				},
 				error: function (data) {
 					alert("网络访问出现问题!");
 				}
 			});
 		} else {
 			$.ajax({
 				url: "<?php echo $this->createUrl('DailyReport/imgStore');?>&web_id=0&page=1&token=<?php echo $_COOKIE['userid'];?>",
 				type: "get",
 				async: true,
 				cache: false,
 				datatype: "json",
 				success: function (data) {
 					library_data = JSON.parse(data);
 					totalPages = library_data.page;
 					var html_main = template('tpl-main', {
 						"case_list": library_data['img']
 					});
 					$("#main_list").html(html_main);
 					if (library_data['img'].length <= 0) {
 						$(".search_none").show();
 					}
 				},
 				error: function (data) {
 					alert("网络访问出现问题!");
 				}
 			});
 		}
 	})
 	$("#changjingfi").click(function () {
 		sear = false;
 		$(".search_none").hide();
 		$("#changjingfi").hide();
 		$("#sub_nav_container .list.changjing span[tab-id=\"0\"]").addClass("active").siblings().removeClass("active");
 		var id = $("#changjingfi p").attr("data-id");
 		var tabId = JSON.parse(localStorage.getItem("$tabId")).tab;
 		tabId.splice(tabId.indexOf(id), 1)
 		var tab_list = tabId.length > 0 ? tabId.join(",") : "";
 		localStorage.setItem("$tabId", tabId.length > 0 ? JSON.stringify({
 			'tab': tabId
 		}) : "");
 		localStorage.setItem("$tab_list", tabId.length > 0 ? tabId.join(",") : "");
 		localStorage.setItem("changjingfi", "");
 		curPage = 1;
 		if (tabId.length > 0) {
 			$.ajax({
 				url: "<?php echo $this->createUrl('background/Img_filter');?>&page=" + curPage + "&tab_list=" + tab_list + '&token=<?php echo $_COOKIE['
 				userid ']?>',
 				type: "get",
 				async: true,
 				cache: false,
 				datatype: "json",
 				success: function (data) {
 					library_data = JSON.parse(data);
 					totalPages = library_data.page;
 					var html_main = template('tpl-main', {
 						"case_list": library_data['img']
 					});
 					$("#main_list").html(html_main);
 					if (library_data['img'].length <= 0) {
 						$(".search_none").show();
 					}
 				},
 				error: function (data) {
 					alert("网络访问出现问题!");
 				}
 			});
 		} else {
 			$.ajax({
 				url: "<?php echo $this->createUrl('DailyReport/imgStore');?>&web_id=0&page=1&token=<?php echo $_COOKIE['userid'];?>",
 				type: "get",
 				async: true,
 				cache: false,
 				datatype: "json",
 				success: function (data) {
 					library_data = JSON.parse(data);
 					totalPages = library_data.page;
 					var html_main = template('tpl-main', {
 						"case_list": library_data['img']
 					});
 					$("#main_list").html(html_main);
 					if (library_data['img'].length <= 0) {
 						$(".search_none").show();
 					}
 				},
 				error: function (data) {
 					alert("网络访问出现问题!");
 				}
 			});
 		}
 	})
 	$("#danpinfi").click(function () {
 		sear = false;
 		$(".search_none").hide();
 		$("#danpinfi").hide();
 		$("#sub_nav_container .list.danpin span[tab-id=\"0\"]").addClass("active").siblings().removeClass("active");
 		var id = $("#danpinfi p").attr("data-id");
 		var tabId = JSON.parse(localStorage.getItem("$tabId")).tab;
 		tabId.splice(tabId.indexOf(id), 1)
 		var tab_list = tabId.length > 0 ? tabId.join(",") : "";
 		localStorage.setItem("$tabId", tabId.length > 0 ? JSON.stringify({
 			'tab': tabId
 		}) : "");
 		localStorage.setItem("$tab_list", tabId.length > 0 ? tabId.join(",") : "");
 		localStorage.setItem("danpinfi", "");
 		curPage = 1;
 		if (tabId.length > 0) {
 			$.ajax({
 				url: "<?php echo $this->createUrl('background/Img_filter');?>&page=" + curPage + "&tab_list=" + tab_list + '&token=<?php echo $_COOKIE['
 				userid ']?>',
 				type: "get",
 				async: true,
 				cache: false,
 				datatype: "json",
 				success: function (data) {
 					library_data = JSON.parse(data);
 					totalPages = library_data.page;
 					var html_main = template('tpl-main', {
 						"case_list": library_data['img']
 					});
 					$("#main_list").html(html_main);
 					if (library_data['img'].length <= 0) {
 						$(".search_none").show();
 					}
 				},
 				error: function (data) {
 					alert("网络访问出现问题!");
 				}
 			});
 		} else {
 			$.ajax({
 				url: "<?php echo $this->createUrl('DailyReport/imgStore');?>&web_id=0&page=1&token=<?php echo $_COOKIE['userid'];?>",
 				type: "get",
 				async: true,
 				cache: false,
 				datatype: "json",
 				success: function (data) {
 					library_data = JSON.parse(data);
 					totalPages = library_data.page;
 					var html_main = template('tpl-main', {
 						"case_list": library_data['img']
 					});
 					$("#main_list").html(html_main);
 					if (library_data['img'].length <= 0) {
 						$(".search_none").show();
 					}
 				},
 				error: function (data) {
 					alert("网络访问出现问题!");
 				}
 			});
 		}
 	})

 	$("#keybu").click(function () {
 		sear = true;
 		$(".search_none").hide();
 		var keyword = $("#keyin").val();
 		localStorage.setItem("$tabId", "");
 		localStorage.setItem("$tabName", "");
 		localStorage.setItem("$tab_list", "");
 		$("#sub_nav_container .list span[tab-id=\"0\"]").addClass("active").siblings().removeClass("active");
 		$(".seled_item").hide();
 		curPage = 1;
 		$.ajax({
 			url: "<?php echo $this->createUrl('background/library_search');?>&page=" + curPage + "&key_words=" + keyword,
 			type: "get",
 			async: true,
 			cache: false,
 			datatype: "json",
 			success: function (data) {
 				library_data = JSON.parse(data);
 				var html_main = template('tpl-main', {
 					"case_list": library_data
 				});
 				$("#main_list").html(html_main);
 				if (data == null || data == "" || data == "[]") {
 					$(".search_none").show();
 				}
 			},
 			error: function (data) {
 				alert("网络访问出现问题!");
 			}
 		});
 	})

 	/*点击遮罩隐藏*/
 	$('.top_area').on('click', '.sub_nav_container', function (e) {
 		if (e.target == this) {
 			$('.sub_nav_box').addClass('none');
 			$('.fold_btn').removeClass('up');
 		}
 	})

 	/*分页*/
 	var pageCount;

 	var loader = new Loadmore($('.nav_content .list')[0], {
 		loadMore: function (curPage, done) {
 			console.log("page" + curPage)
 			if (sear == false) {
 				console.log("sear:" + sear);
 				pageCount = curPage - 1;
 				if (pageCount > 1) {
 					if (pageCount > totalPages) {
 						loader.destroy();
 					} else {
 						if (localStorage.getItem("$tabId") != "" && localStorage.getItem("$tabId") != null) {
 							var tabId = JSON.parse(localStorage.getItem("$tabId"));

 							$.ajax({
 								url: "<?php echo $this->createUrl('DailyReport/imgStore');?>&web_id=0&page=" + pageCount + "&token=<?php echo $_COOKIE['userid'];?>",
 								type: "get",
 								async: true,
 								cache: false,
 								datatype: "json",
 								success: function (data) {
 									var jsonData = JSON.parse(data);
 									console.log(jsonData);
 									pagingXuanran(jsonData, pageCount);
 								},
 								error: function (data) {
 									alert("网络访问出现问题!");
 								}
 							});
 							done();
 						} else {
 							//读<全部案例>页接口
 							$.ajax({
 								url: "<?php echo $this->createUrl('DailyReport/imgStore');?>&web_id=0&page=1&token=<?php echo $_COOKIE['userid'];?>",
 								type: "get",
 								async: true,
 								cache: false,
 								datatype: "json",
 								success: function (data) {
 									var jsonData = JSON.parse(data);
 									console.log(jsonData);
 									pagingXuanran(jsonData, pageCount);
 								},
 								error: function (data) {
 									alert("网络访问出现问题!");
 								}
 							});
 							done();
 						};
 					};
 				} else {
 					pageCount++;
 					done();
 				}
 			} else {
 				console.log("sear:" + sear);
 				var keyword = $("#keyin").val();
 				$.ajax({
 					url: "<?php echo $this->createUrl('background/library_search');?>&page=" + curPage + "&key_words=" + keyword,
 					type: "get",
 					async: true,
 					cache: false,
 					datatype: "json",
 					success: function (data) {
 						library_data = JSON.parse(data);
 						var html_main = template('tpl-main', {
 							"case_list": library_data
 						});
 						$("#main_list").append(html_main);
 						if (data == null || data == "" || data == "[]") {
 							$(".search_none").show();
 						}
 					},
 					error: function (data) {
 						alert("网络访问出现问题!");
 					}
 				});
 				done();
 			}

 		}
 	});

 	function pagingXuanran(data, page) {
 		var html = "";
 		$.each(data.img, function (idx, val) {
 			html += '<li class="m_img_box" img-index="' + ((page - 1) * 60 + idx) + '" case-id="' + val.id + '" data-url="' + val.cover_img_lg + '"><img src="images/d_img.jpg" data-lazyload="' + val.cover_img + '"></li>'
 		});
 		$('.nav_content .list').append(html);
 		if (data == null || data.img == null || data.img.length <= 0) {
 			$(".search_none").show();
 		}
 	}
 })