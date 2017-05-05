<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
	<title>我的订单</title>
	<link rel="stylesheet" type="text/css" href="css/base4.css" />
	<link rel="stylesheet" type="text/css" href="css/layout.css" />
	<link rel="stylesheet" type="text/css" href="css/jcDate.css" />
	<link rel="stylesheet" type="text/css" href="css/colorpicker.css" />
	<link rel="stylesheet" type="text/css" href="css/swiper.min.css" />
	<link rel="stylesheet" type="text/css" href="css/angular-datepicker.css" />
	<link rel="stylesheet" type="text/css" href="css/price_list.css" />
</head>

<body ng-app="ms" ng-controller="priceList" overflow="auto">
	<div class="bread_nav" style="width: 1160px;margin-left: auto;margin-right: auto;"></div>
	<!--header end -->
	<!--头部-->
	<!-- <div class="upload_top">
		<div class="upload_wapper clearfix">
			<h1 class="logo left"><img src="images/logo.jpg" alt="" /></h1>
			<span class="nick right">best</span>
		</div>
	</div> -->
	<!--头部导航-->
	<!-- <div class="navbox">
		<div class="navbar wapper clearfix">
			<h1 class="logo left">
				<a href="javascript:;"><img src="images/logo.jpg" alt="" /></a>
			</h1>
			<ul class="nav_list left clearfix">
				<li class="item active">
					<a href="javascript:;">我的订单</a>
				</li>
				<li class="item">
					<a href="javascript:;">门店介绍</a>
				</li>
				<li class="item">
					<a href="javascript:;">宴会套餐</a>
				</li>
				<li class="item">
					<a href="javascript:;">婚礼套餐</a>
				</li>
				<li class="item">
					<a href="javascript:;">经典案例</a>
				</li>
				<li class="item">
					<a href="javascript:;">我的收藏</a>
				</li>
				<li class="item hasdown show_class" style="z-index:999999999">
					<a class="clearfix" href="javascript:;">
						<span class="left">婚礼策划</span>
						<span class="left icon"><img src="images/icon_down.png" alt="" /></span>
					</a>

					<div class="subnav_c">
						<div class="subnav_box clearfix">
							<div class="right mainbox">
								<ul class="main_list"></ul>
							</div>
							<div class="right two_box">
								<ul class="two_list"></ul>
								<div class="ad_box">
									<a href="javascript:;" class="">
										<img src="images/ad.jpg" alt="" />
									</a>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
			<div class="account_box right clearfix">
				<span class="left">用户名</span>
				<span class="left icon"><img src="images/icon_down.png" alt="" /></span>
				<ul class="account_list">
					<li class="acc_item">
						<a href="javascript:;" class="">
							<img src="images/icon_add.png" alt="" />
							<span>个人中心</span>
						</a>
					</li>
					<li class="acc_item">
						<a href="javascript:;" class="">
							<img src="images/icon_add.png" alt="" />
							<span>我的收藏</span>
						</a>
					</li>
					<li class="acc_item">
						<a href="javascript:;" class="">
							<img src="images/icon_add.png" alt="" />
							<span>我的地址</span>
						</a>
					</li>
					<li class="acc_item">
						<a href="javascript:;" class="">
							<img src="images/icon_add.png" alt="" />
							<span>安全登录</span>
						</a>
					</li>
					<li class="acc_item">
						<a href="javascript:;" class="">
							<img src="images/icon_add.png" alt="" />
							<span>退出登录</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div> -->
	<div class="crumbs_nav_box">
		<div class="wapper">
			<!--面包屑导航-->
			<!-- <div class="crumbs_nav clearfix">
				<a class="left" id="back">[全部订单]</a>
				<span class="arr left">&gt;</span>
				<p class="left">{{data.result.order_data.order_name}}</p>
			</div> -->
		</div>
	</div>
	<!--悬浮窗-->
	<div class="xfc_box">
		<div class="wapper">
			<div class="fixed_tag">
				<img src="images/xfk.png" alt="" id="example" style="right:10rem"/>
			</div>
		</div>
	</div>
	<div class="xfc_box">
		<div class="wapper">
			<div class="fixed_tag">
				<img src="images/xfk.png" alt="" id="print" style="right:1rem"/>
			</div>
		</div>
	</div>

	<!--内容区-->
	<div class="main_container_wrap">
		<div class="wapper main_container clearfix">

			<!--左侧导航-->
			<div class="sidebar_scroll" id="sidebar_scroll">
				<div class="sidebar_c">
					<ul class="sidebar_list">
                        <li class="sidebar_item" data-id="jbxx" ng-class="{true:'active'}[sidebarActive==-1]" ng-click="ChangeSidebar(-1)">
                            <div class="namebox clearfix">
                                <p class="name left">基本信息</p>
                            </div>
                            <span class="dot"></span>
                        </li>
                        <li class="sidebar_item" data-id="area1" ng-class="{true:'active'}[sidebarActive==1]" ng-click="ChangeSidebar(1)">
                            <div class="namebox clearfix">
                                <p class="name left">主题创意</p>
                            </div>
                            <span class="dot"></span>
                        </li>
                        <li class="sidebar_item" data-id="area8" ng-class="{true:'active'}[sidebarActive==-2]" ng-click="ChangeSidebar(-2)">
                            <div class="namebox clearfix">
                                <p class="name left">效果图</p>
                            </div>
                            <span class="dot"></span>
                        </li>
                        <li class="sidebar_item" data-id="bjd" ng-class="{true:'active'}[sidebarActive==-3]" ng-click="ChangeSidebar(-3)">
                            <div class="namebox clearfix">
                                <p class="name left">订单总价</p>
                            </div>
                            <span class="dot"></span>
                        </li>
						<li class="sidebar_item" data-id="area{{i.id}}" data-areaId="{{i.id}}" ng-repeat="i in data.area_data" ng-hide="i.id==1||i.id==2||(i.id==14 && taocanShow==false)" ng-class="{true:'active'}[sidebarActive==i.id]" ng-click="ChangeSidebar(i.id)">
							<div class="namebox clearfix">
								<p class="name left">{{i.name}}</p>
							</div>
							<span class="dot"></span>
						</li>
					</ul>
				</div>
			</div>
			
			<!--右侧主体-->
			<div class="main_scroll" id="main_scroll">
				<div class="main_c">
					<!--基本信息-->
					<div class="base_module price_module" id="jbxx">
						<div class="titlebox clearfix">
							<h2 class="left">基本信息</h2>
							<a href="javascript:;" class="right t_btn editbtn" ng-click="OpenBasicPop()">[编辑]</a>
						</div>
						<div class="base_m_c">
							<div class="base_box clearfix">
								<div class="left">
									<h4>基本信息</h4>
									<ul class="base_list">
										<li class="item">
											<span>订单日期：</span><span class="c">
												{{basicInfo.order_date}}
											</span>
										</li>
										<li class="item">
											<span>订单地址：</span><span class="c">{{basicInfo.order_place}}</span>
										</li>
									</ul>
								</div>
								<div class="left">
									<h4 style='visibility:hidden;height:14px'></h4>
									<ul class="base_list">
										<li class="item">
											<span>来宾人数：</span><span class="c">{{basicInfo.guest_amount}}</span>
										</li>
										<li class="item">
											<span>备注：</span><span class="c">{{basicInfo.remark}}</span>
										</li>
									</ul>
								</div>
							</div>
							<div class="base_box clearfix wedding_info">
								<div class="left">
									<h4>新郎信息</h4>
									<ul class="base_list">
										<li class="item">
											<span>姓名：</span><span class="c">{{basicInfo.groom_name}}</span>
										</li>
										<li class="item">
											<span>联系电话：</span><span class="c">{{basicInfo.groom_phone}}</span>
										</li>
										<!--<li class="item">
											<span>QQ：</span><span class="c">{{basicInfo.groom_qq}}</span>
										</li>
										<li class="item">
											<span>微信：</span><span class="c">{{basicInfo.groom_wechat}}</span>
										</li>-->
									</ul>
								</div>
								<div class="left">
									<h4>新娘信息</h4>
									<ul class="base_list">
										<li class="item">
											<span>姓名：</span><span class="c">{{basicInfo.bride_name}}</span>
										</li>
										<li class="item">
											<span>联系电话：</span><span class="c">{{basicInfo.bride_phone}}</span>
										</li>
										<!--<li class="item">
											<span>QQ：</span><span class="c">{{basicInfo.bride_qq}}</span>
										</li>
										<li class="item">
											<span>微信：</span><span class="c">{{basicInfo.bride_wechat}}</span>
										</li>-->
									</ul>
								</div>
							</div>
							<div class="base_box clearfix meeting_info" style="display:none">
								<div class="left">
									<h4>公司信息</h4>
									<ul class="base_list">
										<li class="item">
											<span>公司名称：</span><span class="c">{{basicInfo.company_name}}</span>
										</li>
									</ul>
								</div>
							</div>
							<div class="base_box clearfix">
								<div class="left">
									<h4>联系人信息</h4>
									<ul class="base_list">
										<li class="item">
											<span>姓名：</span><span class="c">{{basicInfo.contact_name}}</span>
										</li>
										<li class="item">
											<span>联系电话：</span><span class="c">{{basicInfo.contact_phone}}</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!--主题创意-->
					<div class="theme_module te_module price_module" id="area1">
						<div class="titlebox clearfix" id="area2">
							<h2 class="left">主题创意</h2>
						</div>
						<div class="theme_m_c te_m_c clearfix">
							<div class="left">
								<h3>主题文字</h3>
								<div class="themebox tebox textTheme" ng-show="curTextTheme==null" ng-click="OpenTextThemePop()">
									<img src="images/icon_add2.png" alt="" class="dimg" />
								</div>
								<div class="themebox tebox textTheme" ng-show="curTextTheme!=null">
									<h4 class="name">{{curTextTheme.words}}</h4>
									<div class="con">
										<p class="desc">{{curTextTheme.remark}}</p>
									</div>
									<div class="btnbox" style='margin-left:3rem'>
										<button ng-click="DeleteSelectedTextTheme(curTextThemeId,curTextTheme)">删除</button>
										<button class="editbtn" ng-click="EditTextTheme(curTextTheme)">编辑</button>
									</div>
								</div>
							</div>
							<div class="left">
								<h3>主题颜色</h3>
								<div class="themebox tebox colorTheme" ng-show="curColorTheme==null" ng-click="OpenColorThemePop()">
									<img src="images/icon_add2.png" alt="" class="dimg" />
								</div>

								<div class="themebox tebox textTheme" ng-show="curColorTheme!=null">
									<h4 class="name">{{curColorTheme.name}}</h4>
									<div class="con">
										<div class="color">
											<span style="background: {{curColorTheme.main_color}}"></span>
											<span style="background: {{curColorTheme.second_color}}"></span>
											<span style="background: {{curColorTheme.third_color}}"></span>
										</div>
										<p class="desc">{{curColorTheme.remark}}</p>
									</div>
									<div class="btnbox" style='margin-left:3rem'>
										<button ng-click="DeleteSelectedColorTheme(curColorThemeId,curColorTheme)">删除</button>
										<button class="editbtn" ng-click="EditColorTheme(curColorTheme)">编辑</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--效果图-->
					<div class="effect_module te_module price_module" id="area8">
						<div class="titlebox clearfix">
							<h2 class="left">效果图</h2>
						</div>
						<div class="effect_m_c te_m_c clearfix">
							<div class="left">
								<h3>仪式区</h3>
								<div class="effectbox tebox">
									<img ng-show="curYsqList.length<1" ng-click="OpenYsqPop()" src="images/icon_add2.png" alt="" class="dimg" />
									<div class="effect_swiper swiper-container con" ng-show="curYsqList.length>=1">
										<ks-swiper-container class="swiper-wrapper" swiper="swiper" show-nav-buttons="true" on-ready="onReadySwiper(swiper)">
											<ks-swiper-slide class="swiper-slide" ng-repeat="i in curYsqList">
												<div class="imgbox">
													<img src="{{i.img_url}}" alt="" />
												</div>
												<div class="infobox">
													<p class="desc">{{i.description}}</p>
												</div>
												<div class="btnbox">
													<button ng-click="DeleteSelectedYsq(i)">删除</button>
													<button class="editbtn" ng-click="EditYsq(i)">编辑</button>
												</div>
											</ks-swiper-slide>
										</ks-swiper-container>
									</div>
									<a href="javascript:;" class="moreadd" ng-show="curYsqList.length>=1" ng-click="OpenYsqPop()">+添加更多效果图</a>
								</div>
							</div>
							<div class="left">
								<h3>迎宾区</h3>
								<div class="effectbox tebox">
									<img ng-show="curYbqList.length<1" ng-click="OpenYbqPop()" src="images/icon_add2.png" alt="" class="dimg" />
									<div class="effect_swiper swiper-container con" ng-show="curYbqList.length>=1">
										<ks-swiper-container class="swiper-wrapper" show-nav-buttons="true" on-ready="onReadySwiper(swiper)">
											<ks-swiper-slide class="swiper-slide" ng-repeat="i in curYbqList">
												<div class="imgbox">
													<img src="{{i.img_url}}" alt="" />
												</div>
												<div class="infobox">
													<p class="desc">{{i.description}}</p>
												</div>
												<div class="btnbox">
													<button ng-click="DeleteSelectedYbq(i)">删除</button>
													<button class="editbtn" ng-click="EditYbq(i)">编辑</button>
												</div>
											</ks-swiper-slide>
										</ks-swiper-container>
									</div>
									<a href="javascript:;" class="moreadd" ng-show="curYbqList.length>=1" ng-click="OpenYbqPop()">+添加更多效果图</a>
								</div>
							</div>
						</div>
					</div>
					<!--报价单-->
					<div class="print_module price_module" id="bjd">
						<!--打印标题-->
						<div class="titlebox">
							<div class="title clearfix">
								<h2 class="left">订单总价</h2>
								<a href="javascript:;" class="right print_btn t_btn">[打印报价单]</a>
							</div>
						</div>
						<!--表格概况-->
						<div class="print_top" id="jxxx">
							<div class="print_title_box clearfix" id="print_title">
								<!--info-->
								<div class="left base_info_box">
									<div class="top">
										<h3>{{data.result.order_data.order_name}}</h3>
										<p class="address">
											地址：<span>{{data.result.order_data.order_place}}</span>
										</p>
										<p class="date">
											日期：<span>{{data.result.order_data.order_date}}</span>
										</p>
									</div>
									<div class="set_price_table_box">
										<table class="set_price" cellpadding="0" cellspacing="0">
											<tr id="m_jb">
												<td>基本信息</td>
											</tr>
											<tr id="m_jb01">
												<td><img class="op" src="images/print_zk.png" alt="折扣" /></td>
												<td ng-click="OpenDiscountPop()">{{data.result.order_data.discount.other_discount}}折</td>
											</tr>
											<tr id="m_jb02">
												<td><img class="op" src="images/print_ml.png" alt="" /></td>
												<td colspan="4" id="cuPrice">
													<span class="cuPriceStic active">{{data.result.order_data.cut_price}}</span>
													<span class="cuPriceDny ">
														<input type="text" class="cupText" ng-model="cutPrice"/>
														<input type="button" class="yellowBtn" value="确定" ng-click="UpdateCutPrice()"/>
													</span>
												</td>
											</tr>
											<tr>
												<td colspan="5">
													<div class="foot clearfix">
														<div class="left">
															<p>
																婚宴统筹:
																<br /><img src="images/print_hlyh.png" alt="" />
															</p>
															<p>
																<span class="name">{{data.result.order_data.planner_name}} </span>
																<span class="tel">{{data.result.order_data.planner_phone}}</span>
															</p>
														</div>
														<div class="left">
															<p>
																婚礼策划:
																<br /><img src="images/print_hlch.png" alt="" />
															</p>
															<p>
																<span class="name">{{data.result.order_data.designer_name}} </span>
																<span class="tel">{{data.result.order_data.designer_phone}}</span>
															</p>
														</div>
													</div>
												</td>
											</tr>
										</table>
									</div>
								</div>
								<!--echart-->
								<div class="chart_box left">
									<div id="main" style="width: 300px;height:300px;"></div>
								</div>
							</div>
						</div>
						<!--表格主体-->
						<div>
							<div id="print_area">
								<div class="option_table_box" id="area{{i.area_id}}" ng-repeat="i in data.result.area_product" ng-hide="i.area_id==14 && i.product_list.length<1">
									<div class="titlebox">
										<div class="title clearfix">
											<h2 class="left">{{i.area_name}}</h2>
											<a href="javascript:;" class="right print_btn t_btn share_store" data-area="{{i.area_id}}" data-areaname="{{i.area_name}}" data-scroll="area{{i.area_id}}">[添加商品]</a>
										</div>
									</div>
									<div class="option_table">
										<table data-id="area{{i.area_id}}" data-areaId="{{i.area_id}}" width="100%;" cellpadding="0" cellspacing="0">
											<thead>
												<tr>
													<td>
														<!-- <img class="t_img" src="images/print_t{{i.area_id}}.png" alt="" /> -->
													</td>
													<td>参考图</td>
													<td>数量</td>
													<td>单位</td>
													<td>价格</td>
													<td>备注</td>
													<td>总价</td>
													<td>操作</td>
												</tr>
											</thead>
											<tbody>
												<tr id="area{{i.area_id}}pro{{j.product_id}}" ng-repeat="j in i.product_list">
													<td>
														<div>{{j.product_name}}</div>
													</td>
													<td><img class="list_img" src="{{j.ref_pic_url}}" data-sm="{{sm}}" data-md="{{j.md}}"/></td>
													<td data-flag="num">
														<div>{{j.amount}}</div>
													</td>
													<td>
														<div>{{j.unit}}</div>
													</td>
													<td data-flag="price">
														<div>{{j.price}}</div>
													</td>
													<td data-flag="remark">
														<div class="list_remark">{{j.remark}}</div>
													</td>
													<td>
														<div>{{j.amount*j.price}}</div>
													</td>
													<td class="option_item">
														<div class="clearfix">
															<span class="left editbtn" ng-click="TableEdit(j)">编辑</span>
															<span class="left sep">|</span>
															<span class="left delbtn" ng-click="TableDelete(i.area_id,j.product_id)">删除</span>
														</div>
													</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td class="total green">总计</td>
													<td colspan="3">
														<p>原总价</p>{{i.area_total}}
													</td>
													<td colspan="5">
														<p>最终价</p>{{j.discount_total}}
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
							<div id="print_non_area"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--打印弹框-->
	<div class="msgbox print_msgbox">
		<div class="mask"></div>
		<div class="mascontent print_msg">
			<div class="titlebox clearfix">
				<div class="left clearfix">
					<h3 class="left">邮箱</h3>
					<button class="left add_mail">新增邮箱</button>
				</div>
				<div class="right">
					<p class="">操作</p>
				</div>
			</div>
			<ul class="mail_list">
				<li class="clearfix" ng-repeat="i in data.result.email_list">
					<div class="left">
						<p class="name">{{i.email}}</p>
					</div>
					<div class="left">
						<div>
							<span class="left send">发送报价单</span>
							<span class="left sep">|</span>
							<span class="left del" data-id="{{i.id}}">删除</span>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<!--新增弹框-->
	<div class="msgbox add_msgbox" id="local_upload">
		<div class="mask"></div>
		<div class="mascontent add_msg clearfix">
			<span class="close"></span>
			<div class="left " id="image-list">
				<div class="imgbox" id="addImg">
					<img src="images/icon_add2.png" alt="" ng-model="pic" />
				</div>
			</div>
			<ul class="left add_option_list">
				<li class="clearfix name">
					<label class="left" for="">名称：</label>
					<input id="in_name" class="in left" type="text" ng-model="name" />
					<select class="left" ng-model="type" ng-options="i.id as i.name for i in subAreaList">
						<option value="">---  请选择  ---</option>
					</select>
				</li>
				<li class="clearfix">
					<label class="left" for="">单价：</label>
					<input id="in_price" class="in left" type="text" ng-model="price" />
				</li>
				<li class="clearfix">
					<label class="left" for="">单位：</label>
					<input id="in_unit" class="in left" type="text" ng-model="unit" />
				</li>
				<li class="clearfix" >
					<label class="left" for="">单位成本：</label>
					<input id="in_cost" class="in left" type="text" ng-model="cost" />
				</li>
				<li class="clearfix">
					<label class="left" for="">库存：</label>
					<input id="in_inventory" class="in left" type="text" ng-model="inventory" />
				</li>
				<li class="clearfix">
					<label class="left" for="">数量：</label>
					<input id="in_num" class="in left" type="text" ng-model="amount" />
				</li>
				<li class="clearfix">
					<label class="left" for="">描述：</label>
					<input id="in_desc" class="in left" type="text" ng-model="description" />
				</li>
				<li class="clearfix">
					<label class="left" for="">备注：</label>
					<input id="in_bz" class="in left" type="text" ng-model="remark" />
				</li>
				<li class="clearfix">
					<button class="sure_btn" id="addNewGoods">确定</button>
				</li>
			</ul>
		</div>
	</div>
	<!--表格项编辑弹框-->
	<div class="msgbox edit_msgbox">
		<div class="mask"></div>
		<div class="mascontent edit_msg clearfix">
			<span class="close"></span>
			<!--<div class="left ">
				<div class="imgbox">
					<img src="images/icon_add2.png" alt="" />
				</div>
			</div>-->
			<ul class="left add_option_list">
				<li class="clearfix">
					<label class="left" for="">单价：</label>
					<input data-flag="price" class="in left" type="text" ng-model="price" />
				</li>
				<li class="clearfix">
					<label class="left" for="">数量：</label>
					<input data-flag="num" class="in left" type="text" ng-model="amount" />
				</li>
				<li class="clearfix">
					<label class="left" for="">备注：</label>
					<input data-flag="remark" class="in left" type="text" ng-model="remark" />
				</li>
				<li class="clearfix" style="visibility:hidden;">
					<label class="left" for="">单位成本：</label>
					<input data-flag="num" class="in left" type="text" ng-model="cost" />
				</li>
				<li class="clearfix">
					<button class="sure_btn" ng-click="PopConfirm($event)">确定</button>
				</li>
			</ul>
		</div>
	</div>
	<!--基本信息编辑弹框-->
	<div class="msgbox base_msgbox none" id="baseInfo">
		<div class="mask"></div>
		<div class="mascontent base_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">基本信息</h3>
				<span class="close right"></span>
			</div>
			<div class="m_c m_scroll">
				<div>
					<div class="base_t">
						<h5>请输入订单基本信息</h5>
						<p class="desc">请输入至少新郎和新娘的姓名，完成后，点击确定按钮或返回</p>
					</div>
					<div class="base_form top clearfix">
						<ul class="left">
							<li>
								<p class="name">订单日期</p>
								<div class="in_box">
									<datepicker date-format="yyyy-MM-dd">
										<input type="text" id="order_date" ng-model="basicInfo.order_date" ng-class="{false:'form_error'}[basicValidate.order_date]" />
									</datepicker>
								</div>
							</li>
							<li class="clearfix s_li">
								<div class="left" style="display:none">
									<p class="name">订单类型</p>
									<select ng-model="orderType" ng-options="item.id as item.name for item in orderTypeList" ng-class="{false:'form_error'}[basicValidate.order_type]">
										<option value="">-- 请选择 --</option>
									</select>
								</div>
								<div class="left" style="margin-top:1rem">
									<p class="name">门店</p>
									<select ng-model="hotel" ng-options="item.id as item.name for item in hotelList" ng-class="{false:'form_error'}[basicValidate.staff_hotel_id]">
										<option value="">-- 请选择 --</option>
									</select>
								</div>
								<div class="left" style="margin-top:1rem">
									<p class="name">订单地址</p>
									<input type="text" style="width:130%" ng-model="basicInfo.order_place" ng-class="{false:'form_error'}[basicValidate.order_place]" />
								</div>
							</li>
						</ul>
						<ul class="left">
							<li>
								<p class="name">来宾人数</p>
								<input type="text" ng-model="basicInfo.guest_amount" />
							</li>
							<li class="clearfix">
								<p class="name">备注</p>
								<input type="text" ng-model="basicInfo.remark" />
							</li>
						</ul>
					</div>
					<div class="base_form top clearfix wedding_info">
						<ul class="left">
							<li>
								<p class="name">新郎姓名</p>
								<input type="text" ng-model="basicInfo.groom_name" ng-class="{false:'form_error'}[basicValidate.groom_name]" />
							</li>
							<li class="clearfix">
								<p class="name">新郎电话</p>
								<input type="text" ng-model="basicInfo.groom_phone" />
							</li>
							<!--<li class="clearfix">
								<p class="name">QQ</p>
								<input type="text" ng-model="basicInfo.groom_qq"/>
							</li>
							<li class="clearfix">
								<p class="name">微信</p>
								<input type="text" ng-model="basicInfo.groom_wechat"/>
							</li>-->
						</ul>
						<ul class="left">
							<li>
								<p class="name">新娘姓名</p>
								<input type="text" ng-model="basicInfo.bride_name" ng-class="{false:'form_error'}[basicValidate.bride_name]" />
							</li>
							<li class="clearfix">
								<p class="name">新娘电话</p>
								<input type="text" ng-model="basicInfo.bride_phone" />
							</li>
							<!--<li class="clearfix">
								<p class="name">QQ</p>
								<input type="text" ng-model="basicInfo.bride_qq" />
							</li>
							<li class="clearfix">
								<p class="name">微信</p>
								<input type="text" ng-model="basicInfo.bride_wechat" />
							</li>-->
						</ul>
					</div>
					<div class="base_form top clearfix meeting_info" style="display:none">
						<ul class="left">
							<li>
								<p class="name">公司名称</p>
								<input type="text" ng-model="basicInfo.company_name" ng-class="{false:'form_error'}[basicValidate.company_name]" />
							</li>
						</ul>
					</div>
					<div class="base_form top clearfix" style="margin-top:2.5rem">
						<ul class="left">
							<li>
								<p class="name">联系人姓名</p>
								<input type="text" ng-model="basicInfo.contact_name" />
							</li>
						</ul>
						<ul class="left">
							<li class="clearfix">
								<p class="name">联系人电话</p>
								<input type="text" ng-model="basicInfo.contact_phone" />
							</li>
						</ul>
					</div>
				<div class="btn_box">
					<button class="surebtn confirm" ng-click="PopConfirm($event)">确定</button>
					<button class="close">返回</button>
				</div>
			</div>
			</div>
		</div>
	</div>
	<!--选择文字主题弹窗-->
	<div class="msgbox theme_msgbox theme_word_msgbox none">
		<div class="mask"></div>
		<div class="mascontent theme_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">选择文字主题</h3>
				<span class="close right"></span>
			</div>
			<div class="m_c m_scroll">
				<div>
					<ul class="theme_list clearfix">
						<li class="theme_item" ng-click="AddTextTheme()">
							<div class="theme_add_box">
								<p><img src="images/icon_add2.png" alt="" />添加</p>
								<p>主题文字</p>
							</div>
						</li>
						<li class="theme_item" ng-repeat="i in textThemeList">
							<h4 class="name">主题：<span>{{i.words}}</span></h4>
							<p class="desc">{{i.remark}}</p>
							<div class="selbtn" ng-class="{true:'checked'}[i.selected==true]" ng-click="SelectTextTheme(i,$event)"><span>选择此主题</span></div>
							<div class="btnbox" ng-show="i.selected==false">
								<button class="editbtn" ng-click="EditTextTheme(i)">编辑</button>
								<button class="delbtn" ng-click="DeleteTextTheme(i)">删除</button>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!--编辑文字主题弹窗-->
	<div class="msgbox theme_add_msgbox text_theme_add_msgbox none">
		<div class="mask"></div>
		<div class="mascontent theme_add_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">添加文字主题</h3>
				<span class="close right"></span>
			</div>
			<div class="m_c">
				<!--<div class="hbox">
					<h4>添加主题文字</h4>
					<p class="desc">完成时点击“确定”按钮保存</p>
				</div>-->

				<div class="optionbox">
					<div class="inbox">
						<p class="name">主题名称</p>
						<input type="text" class="name_inp" ng-model="textWords" ng-class="{false:'form_error'}[textThemeValidate.textWords]" />
					</div>
					<div class="inbox">
						<p class="name">主题描述</p>
						<input type="text" class="desc_inp" ng-model="textRemark" ng-class="{false:'form_error'}[textThemeValidate.textRemark]" />
					</div>
				</div>
			</div>
			<div class="bbox">
				<button class="surebtn" ng-click="PopConfirm($event)">确定</button>
			</div>
		</div>
	</div>
	<!--选择颜色主题弹窗-->
	<div class="msgbox theme_msgbox theme_color_msgbox none">
		<div class="mask"></div>
		<div class="mascontent theme_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">选择颜色主题</h3>
				<span class="close right"></span>
			</div>
			<div class="m_c m_scroll">
				<div>
					<ul class="theme_list clearfix">
						<li class="theme_item" ng-click="AddColorTheme()">
							<div class="theme_add_box">
								<p><img src="images/icon_add2.png" alt="" />添加</p>
								<p>主题颜色</p>
							</div>
						</li>
						<li class="theme_item" ng-repeat="i in colorThemeList">
							<div class="namebox clearfix">
								<h4 class="name left">{{i.name}}</h4>
								<div class="color left">
									<span style="background: {{i.main_color}}"></span>
									<span style="background: {{i.second_color}}"></span>
									<span style="background: {{i.third_color}}"></span>
								</div>
							</div>
							<p class="desc">{{i.remark}}</p>
							<div class="selbtn" ng-class="{true:'checked'}[i.selected==true]" ng-click="SelectColorTheme(i,$event)"><span>选择此主题</span></div>
							<div class="btnbox" ng-show="i.selected==false">
								<button class="editbtn" ng-click="EditColorTheme(i)">编辑</button>
								<button class="delbtn" ng-click="DeleteColorTheme(i.id)">删除</button>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!--编辑颜色主题弹窗-->
	<div class="msgbox theme_add_msgbox color_theme_add_msgbox none">
		<div class="mask"></div>
		<div class="mascontent theme_add_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">添加颜色主题</h3>
				<span class="close right"></span>
			</div>
			<div class="m_c clearfix">
				<div class="left">
					<!--<div class="hbox">
						<h4>添加颜色文字</h4>
						<p class="desc">完成时点击“确定”按钮保存</p>
					</div>-->

					<div class="optionbox">
						<div class="inbox">
							<p class="name">主题颜色</p>
							<input type="text" ng-model="colorName" ng-class="{false:'form_error'}[colorThemeValidate.colorName]" />
						</div>
						<div class="inbox">
							<p class="name">配色描述</p>
							<input type="text" ng-model="colorRemark" ng-class="{false:'form_error'}[colorThemeValidate.colorRemark]" />
						</div>
					</div>
				</div>
				<ul class="color_sel_list left">
					<li class="clearfix">
						<p class="left">主色:</p>
						<button colorpicker type="button" colorpicker-position="top" ng-model="mainColor" ng-style="{'background':mainColor}"
								ng-class="{false:'form_error'}[colorThemeValidate.mainColor]"></button>
					</li>
					<li class="clearfix">
						<p class="left">副色:</p>
						<button colorpicker type="button" colorpicker-position="top" ng-model="secondColor" ng-style="{'background':secondColor}"
								ng-class="{false:'form_error'}[colorThemeValidate.secondColor]"></button>
					</li>
					<li class="clearfix">
						<p class="left">配色:</p>
						<button colorpicker type="button" colorpicker-position="top" ng-model="thirdColor" ng-style="{'background':thirdColor}"
								ng-class="{false:'form_error'}[colorThemeValidate.thirdColor]"></button>
					</li>
				</ul>

			</div>
			<div class="bbox">
				<button class="surebtn" ng-click="PopConfirm($event)">确定</button>
			</div>
		</div>
	</div>
	<!--选择仪式区效果图弹窗-->
	<div class="msgbox theme_msgbox effect_msgbox none ysq_msgbox">
		<div class="mask"></div>
		<div class="mascontent theme_msg effect_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">选择选择效果图</h3>
				<span class="close right effect_msg_close">确定</span>
			</div>
			<div class="m_c m_scroll">
				<div>
					<ul class="theme_list clearfix">
						<li class="theme_item" ng-click="AddYsq()">
							<div class="theme_add_box">
								<p><img src="images/icon_add2.png" alt="" />上传效果图</p>
								<!-- <p></p> -->
							</div>
						</li>
						<li class="theme_item" ng-repeat="i in ysqList">
							<div class="imgbox clearfix">
								<img src="{{i.img_url}}" alt="" />
							</div>
							<p class="desc">{{i.description}}</p>
							<div class="selbtn" ng-class="{true:'checked'}[i.selected==true]" ng-click="SelectYsq(i)"><span>选择此效果图</span></div>
							<div class="btnbox" ng-show="i.selected==false">
								<button class="editbtn" ng-click="EditYsq(i)">编辑</button>
								<button class="delbtn" ng-click="DeleteYsq(i)">删除</button>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!--新增仪式区效果图弹窗-->
	<div class="msgbox add_effect_msgbox none ysq_add_msgbox">
		<div class="mask"></div>
		<div class="mascontent  add_effect_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">新增效果图</h3>
				<span class="close right"></span>
			</div>
			<div class="m_c" id="image-list2">
				<div class="imgbox" id="addImg2" ng-class="{false:'form_error'}[ysqValidate.ysqImg]">
					<img src="images/icon_add2.png" alt="" class="dimg" />
				</div>
				<div class="inbox">
					<p class="name">效果图描述（必填）</p>
					<input type="text" class="dinput" ng-model="ysqDesc" ng-class="{false:'form_error'}[ysqValidate.ysqDesc]" />
				</div>
				<button class="dbtn_yellow" id="addNewGoods2">提交</button>
			</div>
		</div>
	</div>
	<!--编辑仪式区效果图弹窗-->
	<div class="msgbox add_effect_msgbox none ysq_edit_msgbox">
		<div class="mask"></div>
		<div class="mascontent  add_effect_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">新增效果图</h3>
				<span class="close right"></span>
			</div>
			<div class="m_c">
				<div class="imgbox">
					<img src="{{ysqImg}}" alt="" class="dimg" />
				</div>
				<div class="inbox">
					<p class="name">效果图描述（必填）</p>
					<input type="text" class="dinput" ng-model="ysqDesc" ng-class="{false:'form_error'}[ysqValidate.ysqDesc]" />
				</div>
				<button class="dbtn_yellow" ng-click="PopConfirm($event)">提交</button>
			</div>
		</div>
	</div>
	<!--选择迎宾区效果图弹窗-->
	<div class="msgbox theme_msgbox effect_msgbox none ybq_msgbox">
		<div class="mask"></div>
		<div class="mascontent theme_msg effect_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">选择选择效果图</h3>
				<span class="close right effect_msg_close">确定</span>
			</div>
			<div class="m_c m_scroll">
				<div>
					<ul class="theme_list clearfix">
						<li class="theme_item" ng-click="AddYbq()">
							<div class="theme_add_box">
								<p><img src="images/icon_add2.png" alt="" />上传</p>
								<p>效 果 图</p>
							</div>
						</li>
						<li class="theme_item" ng-repeat="i in ybqList">
							<div class="imgbox clearfix">
								<img src="{{i.img_url}}" alt="" />
							</div>
							<p class="desc">{{i.description}}</p>
							<div class="selbtn" ng-class="{true:'checked'}[i.selected==true]" ng-click="SelectYbq(i)"><span>选择此效果图</span></div>
							<div class="btnbox">
								<button class="editbtn" ng-click="EditYbq(i)">编辑</button>
								<button class="delbtn" ng-click="DeleteYbq(i)">删除</button>
							</div>
						</li>
					</ul>
				</div>

			</div>
		</div>
	</div>
	<!--新增迎宾区效果图弹窗-->
	<div class="msgbox add_effect_msgbox none ybq_add_msgbox">
		<div class="mask"></div>
		<div class="mascontent  add_effect_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">新增效果图</h3>
				<span class="close right"></span>
			</div>
			<div class="m_c" id="image-list3">
				<div class="imgbox" id="addImg3" ng-class="{false:'form_error'}[ybqValidate.ybqImg]">
					<img src="{{ybqImg}}" alt="" class="dimg" />
				</div>
				<div class="inbox">
					<p class="name">效果图描述（必填）</p>
					<input type="text" class="dinput" ng-model="ybqDesc" ng-class="{false:'form_error'}[ybqValidate.ybqDesc]" />
				</div>
				<button class="dbtn_yellow" id="addNewGoods3">提交</button>
			</div>
		</div>
	</div>
	<!--编辑迎宾区效果图弹窗-->
	<div class="msgbox add_effect_msgbox none ybq_edit_msgbox">
		<div class="mask"></div>
		<div class="mascontent  add_effect_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">新增效果图</h3>
				<span class="close right"></span>
			</div>
			<div class="m_c">
				<div class="imgbox">
					<img src="{{ybqImg}}" alt="" class="dimg" />
				</div>
				<div class="inbox">
					<p class="name">效果图描述（必填）</p>
					<input type="text" class="dinput" ng-model="ybqDesc" ng-class="{false:'form_error'}[ybqValidate.ybqDesc]" />
				</div>
				<button class="dbtn_yellow" ng-click="PopConfirm($event)">提交</button>
			</div>
		</div>
	</div>
	<!--折扣弹窗-->
	<div class="msgbox discount_msgbox none">
		<div class="mask"></div>
		<div class="mascontent  discount_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">修改折扣</h3>
				<span class="close right"></span>
			</div>
			<div class="m_c">
				<div class="optionbox">
					<div class="inbox clearfix">
						<p class="name left">折扣: </p>
						<input type="text" class="num_inp left" ng-model="discountData.other_discount"/>
					</div>
					<div class="inbox clearfix">
						<p class="name left">范围: </p>
						<div class="discount_list left">
							<div class="discount_item clearfix">
								<span ng-repeat="i in discountData.discount_range" ng-class="{true:'active'}[i.discount==true]" ng-click="DiscountCheck(i)">{{i.name}}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="bbox">
				<button class="surebtn" ng-click="DiscountConfirm($event)">确定</button>
			</div>
		</div>
	</div>
	<!--复制到弹框-->
	<div class="msgbox copy_msgbox none">
		<div class="mask"></div>
		<div class="mascontent  copy_msg">
			<div class="msg_titlebox clearfix">
				<h3 class="left">选择要复制的订单</h3>
				<div>
					<input class="order_search_txt" type="text" placeholder="搜索订单名称" style="margin-left: 13rem;height: 2rem;width:12rem"></input>
	    			<input class="order_search_btn" type="button" value="搜索"></input>
	    		</div>
				<!-- <span class="close right"></span> -->
			</div>
			<div class="m_c" style="height:200px;">
				<div class="tab_nav clearfix">
					<span ng-click="ChangeCopyType(1)" ng-class="{true:'active'}[copyType==1]">婚礼</span>
					<span ng-click="ChangeCopyType(2)" ng-class="{true:'active'}[copyType==2]">会议</span>
				</div>
				<div class="tab_con" style="overflow: scroll;height: 14rem;">
					<ul class="list" ng-show="copyList.length>0">
						<li class="item" ng-repeat="i in copyList" ng-class="{true:'active'}[copyId==i.id]" ng-click="SelectCopy(i)">
							<span>{{i.order_date}}</span>
							<span style="margin-left:9px;">{{i.name}}</span>
						</li>
					</ul>
					<p ng-show="copyList.length<=0&&copyType==1">无可复制到的婚礼订单</p>
					<p ng-show="copyList.length<=0&&copyType==2">无可复制到的会议订单</p>
				</div>
			</div>
			<div class="bbox">
				<button class="surebtn" ng-click="CopyConfirm($event)">确定</button>
			</div>
		</div>
	</div>
	<!--悬浮 复制按钮-->
	<div class="copy_btn_box">
		<span class="dispatch_btn">派单</span>
		<span class="copy_btn" ng-click="OpenCopyPop()">复制</span>
	</div>
	<div style="display: none;">
		<input type="radio" name="myradio" value="local_name" /> 上传文件名字保持本地文件名字
		<input type="radio" name="myradio" value="random_name" checked=true /> 上传文件名字是随机文件名
		<br /> 上传到指定目录:
		<input type="text" id='dirname' value="" />
	</div>
	<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript" src="js/iscroll.js"></script>
	<script type="text/javascript" src="js/jQuery-jcDate.js"></script>
	<script type="text/javascript" src="js/iCore.min.js"></script>
	<script type="text/javascript" src="js/swiper.js"></script>
	<script type="text/javascript" src="js/angular.js"></script>
	<script type="text/javascript" src="js/angular-swiper.js"></script>
	<script type="text/javascript" src="js/angular-datepicker.js"></script>
	<script type="text/javascript" src="js/bootstrap-colorpicker-module.js"></script>
	<script type="text/javascript" src="js/echarts.min.js"></script>
	<script type="text/javascript" src="js/swiper-3.4.1.jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.bomb.js"></script>
	<script type="text/javascript" src="js/crypto1/crypto/crypto.js"></script>
	<script type="text/javascript" src="js/crypto1/hmac/hmac.js"></script>
	<script type="text/javascript" src="js/crypto1/sha1/sha1.js"></script>
	<script type="text/javascript" src="js/base64.js"></script>
	<script type="text/javascript" src="js/plupload-2.1.2/js/plupload.full.min.js"></script>
	<script type="text/javascript" src="js/bread_nav.js"></script>
	<script type="text/javascript" src="js/top_nav.js"></script>
	<script type="text/javascript" src="js/price_list.js"></script>
	<script type="text/javascript">
		//去掉主题文字、主题色彩、效果图的加号
		$(".addbtn_box").each(function(i, val){
			if($(this).attr("areaid") == 1 || $(this).attr("areaid") == 2 || $(this).attr("areaid") == 8){
				$(this).remove();
			};
		});

        
        

        $(".my_collection").live('click', function(){
        	var area_id = '';
            var subarea_id = $(this).parent().parent().parent().parent().attr('subarea-id');
            location.href = '<?php echo $this->createUrl("background/my_collection");?>&token=<?php echo $_GET['token']?>&order_id=<?php echo $_GET['order_id']?>&subarea_id=' + subarea_id + '&area_id=' + area_id;
        });

        $("#example").on("click", function(){
        	var post_data = {
        		order_id : <?php echo $_GET['order_id']?>
        	};
        	console.log(post_data);
        	$.post("<?php echo $this->createUrl('dailyReport/get_template_id');?>",JSON.stringify(post_data),function(data){
        		var json_data = JSON.parse(data);
        		if(json_data.template_id == 0){
        			window.open('<?php echo $this->createUrl("background/examples");?>&token=<?php echo $_GET['token']?>&order_id=<?php echo $_GET['order_id']?>');		
        			//location.href='<?php echo $this->createUrl("background/examples");?>&token=<?php echo $_GET['token']?>&order_id=<?php echo $_GET['order_id']?>';
				}else{
        			window.open('<?php echo $this->createUrl("background/example'+json_data.template_id+'");?>&token=<?php echo $_GET['token']?>&order_id=<?php echo $_GET['order_id']?>');
        			//location.href='<?php echo $this->createUrl("background/examples'+json_data.tempalte_id+'");?>&token=<?php echo $_GET['token']?>&order_id=<?php echo $_GET['order_id']?>';
				}
			});
        });
		$("#print").on("click", function(){
			window.open('/portal/index.php?r=background/bill&order_id=<?php echo $_GET["order_id"]?>&token=<?php echo $_GET["token"]?>');
		});

        //返回
        $("#back").on("click", function(){
        	location.href = '<?php echo $this->createUrl("background/index");?>&CI_Type=order';
        });
		
	</script>
	
</body>

</html>