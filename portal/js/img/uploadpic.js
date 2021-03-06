/**
 * Author:ZhengYan
 * Last EditDate: 2016-05-31
 *
 */

/**
 * 主函数 只调用它
 * @parameter: setwidth:图片的宽度;  quality: 图片压缩质量; boxid: 图片展示区 id
 */
function picload(event,setwidth,quality,boxid,fieldname) {			
	var n = event.target.files.length;	        
	var file;
	for (var i = 0; i < n; i++) {
		file=event.target.files[i];
		
		if (window.createObjectURL!=undefined) {
			var blob = window.createObjectURL(file) ; 
		} else if (window.URL!=undefined) {
			var blob = window.URL.createObjectURL(file) ;
		} else if (window.webkitURL!=undefined) {
			var blob = window.webkitURL.createObjectURL(file);
		} 
		
		picpress(blob,setwidth,quality,boxid,fieldname);
	};						        
}

/**
 * 图片压缩主函数
 */
function picpress(blob,width,quality,boxid,fieldname){
	var img = new Image();
	img.src = blob;

	img.onload = function(){
		var that = this;
		//生成比例
		var w = that.width,
			h = that.height,
			scale = w / h;
		w = width || w;
		h = w / scale;

		//生成canvas
		var canvas = document.createElement('canvas');
		var ctx = canvas.getContext('2d');
		$(canvas).attr({
			width: w,
			height: h
		});
		ctx.drawImage(that, 0, 0, w, h);

		/**
		 * 生成base64
		 * 兼容修复移动设备需要引入mobileBUGFix.js
		 */
		var base64 = canvas.toDataURL('image/jpeg', quality || 0.9);

		// 修复IOS
		if (navigator.userAgent.match(/iphone/i)) {
			var mpImg = new MegaPixImage(img);
			mpImg.render(canvas, {
				maxWidth: w,
				maxHeight: h,
				quality: quality || 0.9
			});
			base64 = canvas.toDataURL('image/jpeg', quality || 0.9);
		}

		// 修复android
		if (navigator.userAgent.match(/Android/i)) {
			var encoder = new JPEGEncoder();
			base64 = encoder.encode(ctx.getImageData(0, 0, w, h), quality * 100 || 90);
		}

		// 生成结果
		result = {
			base64: base64,
			clearBase64: base64.substr(base64.indexOf(',') + 1)
		};
		
		var new_img ='<li><div class="MobImgUpl_imgBox"><div class="MobImgUpl_img"><img src="'+result.base64+'"></div></div>';
			new_img+='<i class="iconfont" onclick="delpic(this,\''+boxid+'\')">&#xe609;</i>';
			new_img+='<input type="hidden" name="'+fieldname+'" value="'+result.base64+'">';
			new_img+='</li>';
		
		$('#'+boxid).before(new_img);
		
		$('#'+boxid).find('span').hide();
	};
	
}

/**
 * 图片删除函数
 */
function delpic(obj,id){
	$(obj).parent().remove();
	var li_num= $('#'+id).children('li').length;
	if(!li_num){
		$('#'+id).find('span').show();
	}
}