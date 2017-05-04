$(function(){
	$(".upgrade a.close").on("click",function(){
		$(".upgrade").fadeOut(300);
	})
	$("#select_date").on("change",function(){
		$(this).prev().html($(this).val());
	})
	//点击立即升级按钮的事件
	$(".lijishengji_btn").on("click",function(){










		$.ajax({
			type:"POST",
			url:"http://www.cike360.com/school/crm_web/portal/index.php?r=DailyReport/get_charge_type",
			data:{},
			success:function(data){
				var json=JSON.parse(data);
				if(json){
				 var weather=json.weatherinfo ;
				 var markup = "<li><b>${city_en}</b> (${date_y})</li>";
				 //jQuery.template( "tt1", markup );
				 $("#target2").html(template( "tt1", markup ));
				 //jQuery.tmpl( "tt1", weather ).appendTo( "#target2" ); 
				}
			},
			dataType:"JSONP",
			error:function(data){
				alert("系统异常："+JSON.stringify(data));
			}
		})	






		//$(".upgrade").fadeIn(300);
	})
	



})









/*


传回数据：
｛Year:[
		{id, 
		num, // 右侧框内 5个账号的“5”
		price, //最终价”4888”
		discount_data,//原价 5940
		Description,   //此处无用
		Recommend:true //将第一个为true的设为默认
		}
	],
	Month:[
		{id, 
		num, 
		price, 
		Description
		}
	]
}


*/









/*


<table id="table1"></table>
<script type="text/html" id="template1">
        <tr>
          <td>{{ID}}</td>
          <td>{{Name}}</td>
        </tr>
</script>
<script type="text/javascript">
    var users = {
            ID: 'think8848',
            Name: 'Joseph Chan',
        };
$('#table1').html(template("template1",users));
</script>





<div id="content"></div>

<script id="test" type="text/html">
<ul>
    {{each listBox as list}}
        <li>{{list.name}}</li>
    {{/each}}
</ul>
</script>

<script>
var data = [
  {"name":"vivian", "age":10}, 
  {"name":"john", "age":11}, 
  {"name":"eddy", "age":12}, 
  {"name":"angle", "age":13}
];
var dataList = {"listBox":data };

$("#content").html(template('test', dataList ));
</script>


*/


/*

{
	"year":[
			{"id":"10","num":"1","price":"999","discount_data":"1188","recommend":"0","description":null},
			{"id":"11","num":"3","price":"2588","discount_data":"3564","recommend":"0","description":null},
			{"id":"12","num":"5","price":"4588","discount_data":"5940","recommend":"1","description":"\u4e2d\u578b\u516c\u53f8\u6700\u4f73\u9009\u62e9"},
			{"id":"13","num":"10","price":"9888","discount_data":"11880","recommend":"0","description":null},
			{"id":"14","num":"20","price":"15880","discount_data":"23760","recommend":"0","description":"\u9002\u5408\u5927\u578b\u516c\u53f8"}
		],
	"month":[
		{"id":"5","num":"1","price":"0.01","discount_data":"99","recommend":"0","description":null},
		{"id":"6","num":"3","price":"0.01","discount_data":"288","recommend":"0","description":"\u9002\u5408\u5a5a\u793c\u5de5\u4f5c\u5ba4"},
		{"id":"7","num":"5","price":"488","discount_data":"488","recommend":"0","description":"\u4e2d\u578b\u516c\u53f8\u6700\u4f73\u9009\u62e9"},
		{"id":"8","num":"10","price":"988","discount_data":"988","recommend":"0","description":"\u9002\u5408\u4e2d\u5927\u578b\u516c\u53f8"},
		{"id":"9","num":"20","price":"1888","discount_data":"1888","recommend":"0","description":null}
	]
}

{"year":[{"id":"10","num":"1","price":"999","discount_data":"1188","recommend":"0","description":null},{"id":"11","num":"3","price":"2588","discount_data":"3564","recommend":"0","description":null},{"id":"12","num":"5","price":"4588","discount_data":"5940","recommend":"1","description":"\u4e2d\u578b\u516c\u53f8\u6700\u4f73\u9009\u62e9"},{"id":"13","num":"10","price":"9888","discount_data":"11880","recommend":"0","description":null},{"id":"14","num":"20","price":"15880","discount_data":"23760","recommend":"0","description":"\u9002\u5408\u5927\u578b\u516c\u53f8"}],"month":[{"id":"5","num":"1","price":"0.01","discount_data":"99","recommend":"0","description":null},{"id":"6","num":"3","price":"0.01","discount_data":"288","recommend":"0","description":"\u9002\u5408\u5a5a\u793c\u5de5\u4f5c\u5ba4"},{"id":"7","num":"5","price":"488","discount_data":"488","recommend":"0","description":"\u4e2d\u578b\u516c\u53f8\u6700\u4f73\u9009\u62e9"},{"id":"8","num":"10","price":"988","discount_data":"988","recommend":"0","description":"\u9002\u5408\u4e2d\u5927\u578b\u516c\u53f8"},{"id":"9","num":"20","price":"1888","discount_data":"1888","recommend":"0","description":null}]}


*/





