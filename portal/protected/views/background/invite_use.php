<!DOCTYPE html>
<html>

	<head>
		<title>邀请试用</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/base_invite_use.css">
		<link rel="stylesheet" type="text/css" href="css/nav_invite_use.css">

		<style>
			html,
			body {
				position: relative;
				height: 100%;
			}
			
			.swiper-container {
				width: 100%;
				height: 100%;
			}
			
			.swiper-slide {
				position: relative;
				display: -webkit-box;
				display: -ms-flexbox;
				display: -webkit-flex;
				display: flex;
				-webkit-box-pack: center;
				-ms-flex-pack: center;
				-webkit-justify-content: center;
				justify-content: center;
				-webkit-box-align: center;
				-ms-flex-align: center;
				-webkit-align-items: center;
				align-items: center;
			}
			
			.swiper-slide img {
				width: 100%;
				height: 100%;
				object-fit: cover;
				-webkit-object-fit: cover;
			}
			
			.swiper-slide.index .btn_box {
				position: absolute;
				bottom: 20%;
				left: 0;
				width: 100%;
				text-align: center;
			}
			
			.swiper-slide.index .btn_box a {
				display: inline-block;
				width: 50%;
				height: 4rem;
				line-height: 4rem;
				padding: 0 1rem;
				margin-bottom: 1.5rem;
				background: #ad21be;
				color: #fff;
				font-size: 2rem;
				font-weight: bold;
			}
			
			.swiper-slide.index .btn_box a.andriod {
				background: #533fb2;
			}
		</style>
	</head>

	<body>
		<div class="swiper swiper-container">
			<ul class="swiper-wrapper">
				<li class="swiper-slide index">
					<img src="images/use01.jpg" alt="">
					<div class="btn_box">
						<a class="andriod" href="http://a.app.qq.com/o/simple.jsp?pkgname=com.hhyf.wxapi">Android版下载</a><br/>
						<a class="ios" href="http://a.app.qq.com/o/simple.jsp?pkgname=com.hhyf.wxapi">Ios版下载</a>
					</div>
				</li>
				<li class="swiper-slide">
					<img src="images/use02.jpg" alt="">
				</li>
				<li class="swiper-slide">
					<img src="images/use03.jpg" alt="">
				</li>
				<li class="swiper-slide">
					<img src="images/use04.jpg" alt="">
				</li>
				<li class="swiper-slide">
					<img src="images/use05.jpg" alt="">
				</li>
				<li class="swiper-slide">
					<img src="images/use06.jpg" alt="">
				</li>
			</ul>
		</div>

		<script src="js/zepto.min.js"></script>
		<script src="js/zepto.swipe.js"></script>
		<script>
			new Swiper('.swiper', {
				direction: 'vertical',
				autoplay: false,
				loop: false,
			});
		</script>
	</body>

</html>