<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Material Login </title>
	<!-- <link rel="stylesheet" type="text/css" href="css/normalize.css" /> -->
	<link rel="stylesheet" href="/Public/css/default.css" type="text/css">
	<link rel="stylesheet" href="/Public/css/materialize.min.css">
	<link rel="stylesheet" href="/Public/css/grid.css">
	<style type="text/css">
	html,body {
	    height: 100%;
	}
	html {
	    display: table;
	    margin: auto;
	}
	body {
	    display: table-cell;
	    vertical-align: middle;
	}
	.margin {
	  margin: 0 !important;
	}
	</style>
	<!--[if IE]>
		<script src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>
	<![endif]-->
	<script>
		function login(){
			$(".login-form").submit();
		}
		function submit(){
			document.getElementById("msg").innerText = '';
			$(".msg").hide();
			var username = document.getElementById("username").value;
			var password = document.getElementById("password").value;
			var callback = document.getElementById("callback").value;
			var appId = document.getElementById("appId").value;
			$.post("/index.php/Home/Server/login",{username,password,callback,appId},function(data){
				if(data.status != 1){
					var info = data.info;
					document.getElementById("msg").innerText = info;
					$(".msg").show();
				}
			});
		}
	</script>
</head>
<body class="red">
  <div id="login-page" class="row">
	<div class="col s12 z-depth-6 card-panel">
	  <form class="login-form" action="<?php echo U('Home/Server/login');?>" method="post">
	    <div class="row">
          <div class="input-field col s12 center">
            <img src="/Public/img/logo.png" alt="" class="responsive-img valign profile-image-login index-icon">
            <p class="center login-form-text">Material Design 用户登录系统</p>
          
          </div>
	    </div>
	    <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-social-person-outline prefix"></i>
            <input class="validate" name="username" id="username" type="text">
            <label for="username" class="">用户名/邮箱/手机号</label> <!-- data-error="wrong" data-success="right" -->
          </div>
	    </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-action-lock-outline prefix"></i>
            <input name="password" id="password" type="password">
            <label for="password">密码</label>
          </div>
        </div>
        <div class="row msg">          
          <div class="input-field col s12 m12 l12  login-text">
              <span id="msg"></span>
          </div>
        </div>
        <!-- <div class="row">          
          <div class="input-field col s12 m12 l12  login-text">
              <input type="checkbox" id="remember-me" />
              <label for="remember-me">记住我</label>
          </div>
        </div> -->
        <div class="row">
          <div class="input-field col s12">
            <a href="javascript:void(0);" onclick="login();" class="btn waves-effect waves-light col s12">登　录</a>
          </div>
        </div>
        <div class="rows">
          <div class="input-field col s6 m6 l6">
            <p class="margin medium-small"><a href="<?php echo U('Home/Server/register');?>">现在注册!</a></p>
          </div>
          <div class="input-field col s6 m6 l6">
            <p class="margin right-align medium-small"><a href="<?php echo U('Home/Server/forgot_password');?>">忘记密码?</a></p>
          </div>          
        </div>
        <div class="row group">
			 <div class="tagline"><span>第三方登录</span></div><!-- .tagline -->
		</div>
		<div class="row group">
		  <div class="col span_12_of_12 center">
			<a href="#"><img class="third-icon" src="/Public/img/weixin.png"></a>
			<a href="#"><img class="third-icon" src="/Public/img/68-sina-weibo.png" ></a>
			<a href="#"><img class="third-icon" src="/Public/img/taobao.png" ></a>
			<a href="#"><img class="third-icon" src="/Public/img/qq.png" ></a>
			<a href="#"><img class="third-icon" src="/Public/img/didi.png" ></a>
			<a href="#"><img class="third-icon" src="/Public/img/baidu.png" ></a>
			<a href="#"><img class="third-icon" src="/Public/img/dianping.png" ></a>
		  </div>
		</div>
		<input type="hidden" name="callback" id="callback" value="<?php echo ($callback); ?>">
		<input type="hidden" name="appId" id="appId" value="<?php echo ($appId); ?>">
	  </form>
	</div>
  </div>
  <script type="text/javascript" src="/Public/js/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="/Public/js/materialize.min.js"></script>
</body>
</html>