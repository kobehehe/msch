﻿(function(n){var t=n(".item-list"),u=["SICN","Unix"],i=document.URL,r=!1;n.extend(n.fn,{lazyload:function(){function o(){return window.innerHeight||screen.availHeight}function c(n){var r=o(),t=window.pageYOffset-r/2,u=s(),i=n.offset().top;return t=t<0?0:t,i>=0&&i>=t&&i<=u}function s(){return window.pageYOffset+o()*1.2}function l(n){url=n.data("lazyload");n.attr("src",url).removeAttr("data-lazyload");r==!0&&u.length==2&&t.imagesLoaded(function(){t.masonry({itemSelector:".item",columnWidth:220,gutterWidth:15,isAnimated:!0})})}var h=this,f=0,e=(new Date).getTime();n(window).on("scroll.lazyload",function(){var a=(new Date).getTime(),y=f,v=h,w=parseInt(Math.random()*10)%2==0,b=v.length,o,t,p,u;if(!(a-e<100))for(f++,e=a,o=n("[data-lazyload]"),r=i.indexOf("upload_case_detail")>=0||i.indexOf("upload_collection")>=0,t=0;t<o.length;t++){if(y+1!=f)break;if(p=v[t],u=o.eq(t),u.is("img")){if(u.offset().top>s())break;c(u)&&l(u)}}});setTimeout(function(){n(window).trigger("scroll")},100)}});n(function(){n("[data-lazyload]").lazyload()})})(jQuery);