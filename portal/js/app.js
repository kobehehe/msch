var api_url = "http://www.cike360.com/school/crm_web/portal/index.php?r=resource/";
var api_url_root = "http://www.cike360.com/school/crm_web/portal/index.php?r=";//var api_url="http://123.56.115.136:8080/";
var file_url="http://file.cike360.com/";
Array.prototype.remove = function(dx) {
	if (isNaN(dx) || dx > this.length) {
		return false;
	}
	for (var i = 0, n = 0; i < this.length; i++) {
		if (this[i] != this[dx]) {
			this[n++] = this[i]
		}
	}
	this.length -= 1
}

///判断是否下载
function LocalDown(cid) {
	var downlist = JSON.parse(localStorage.getItem("$DOWN")) || {};
	if (downlist[cid] == null || downlist[cid] == "")
		return false;
	else
		return true;
}

function SetDown(cid) {
	console.log("SetDown:" + cid);
	var downlist = JSON.parse(localStorage.getItem("$DOWN")) || {};
	downlist[cid] = "1";
	localStorage.setItem("$DOWN", JSON.stringify(downlist));
}

function createReplaceImgName(furl, type) {
	return furl.substr(0, furl.lastIndexOf(".")) + "_" + type + furl.substr(furl.lastIndexOf("."));
}

(function($, owner) {
	/**
	 * 获取当前状态
	 **/
	owner.getState = function() {
		//var stateText = localStorage.getItem('$state') || "{}";
		//return JSON.parse(stateText);
		return localStorage.getItem('$token') || "";
	};

	/**
	 * 设置当前状态
	 **/
	owner.setState = function(state) {
		state = state || "";
		localStorage.setItem('$state', state);
//		var settings = owner.getSettings();
//		settings.gestures = '';
//		owner.setSettings(settings);
	};
	
	owner.setOrderId = function(order_id){
		localStorage.setItem('$cur_orderId', order_id);
	};
	
	owner.getOrderId = function(){
		localStorage.getItem('$cur_orderId');
	};

}(mui, window.app = {}));


function ShowWaiting(_show,father_class){
	if(_show==false){
		$(".waiting").remove();
	}
	else{
		var str ="<div class=\"waiting\"><img src=\"style/images/waiting.gif\" /></div>";
		if(father_class==null)
			$("body").append(str);
		else{
			$('.'+father_class).append(str);
		}
	}
}

function ShowAmaWating(_show,father_class){
	if(_show==false){
		$(".start").remove();
	}
	else{
		var str =  '<div class="start" >';
        		str += 	'<img style="position: absolute;top: 0;z-index: 200;animation: one ease-in-out 2s forwards;-webkit-animation: one ease-in-out 2s forwards;" src="style/images/img_0158.jpg" alt="">';
        		str += 	'<img style="position: absolute;top: 0;z-index: 199;opacity: 0;animation: two ease-in-out 2s forwards;-webkit-animation: two ease-in-out 2s forwards;" src="style/images/WENZI.png" alt="">';
    			str += '</div>';
		if(father_class==null)
			$("body").append(str);
		else{
			$('.'+father_class).prepend(str);
		}
	}
}

function ShowIcon(_show,mengcat_name,bg_width,father_class,close){
	$(" .mengcat").remove();
	if(_show == true){
		if(bg_width == ''|| bg_width == undefined) bg_width = '100%';
		var str ="<div class=\"mengcat\" style='width:"+bg_width+"'><img src='style/images/"+mengcat_name+"'></div>";
		if(father_class==null|| father_class == ''){
			$("body").append(str);
			$('.title_box').css('z-index','999999999').css('position','relative');
		}
		else{
			$('.'+father_class).append(str);
		}
	}
	if(close == true){
		$('.mengcat').click(function(){$(this).remove()});
	}
}
