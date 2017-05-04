/**
 * bread_nav.js面包屑导航
 */

(function($){
	// if(typeof($.fn.bread_nav) != 'undefined') {return false;} // prevent dmultiple scripts inits
	
	$.fn.bread_nav = function(options) {
		var defaults = {
			father_class: 'bread_nav',
			font_size: '14px',			
			nav_name_list:[{
				nav_name: '首页',
				nav_link: 'index_front',
			}]
		};
		var options = $.extend(defaults, options);
		xuanran();

		function xuanran(){
			var html = '<div style="font-size:14px ;margin: 20px 0 10px 0;height: 20px;line-height: 20px;">';
			var nav_num = options.nav_name_list.length;
			$.each(options.nav_name_list,function(i,val){
				var color = '#444';
				if(i==nav_num-1) color = '#feb300;';
				html += '<a href="' + val.nav_link + '" style="color:'+color+';display:inline-block;">' + val.nav_name + '</a>';
				if( i != nav_num-1){
					html += '<i  style="display:inline-block;width: 12px;height: 15px;position: relative;top: 1px;background: url(images/sprite.png) no-repeat -8px -74px;margin: 0 10px;"></i>';
				}	
			});
			html += '</div>';
			$("."+options.father_class).prepend(html);
		}
	}
	
})(jQuery);
