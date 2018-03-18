<?php
  include_once("header.php");
?>

<title><?=$site?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<noscript>
  对不起, 您的浏览器不支持或禁用了JavaScript, 不能正常使用, 请打开相应开关或更换浏览器
</noscript>

<script type="text/javascript" src="js/bootstrap-3.0.3.min.js"></script>	  
<script type="text/javascript" src="js/jquery-1.9.0.min.js"   ></script>

<link href="css/bootstrap-3.0.3.min.css" rel="stylesheet">
<link href="css/index.css"               rel="stylesheet">	  

</head>

<?php $closed = true; ?>
<body>
    <?php
        if ($closed == false) {
    ?>
        <p>系统已关闭。</p>
    <?php
        }else{
    ?>

	<div class="container">
		<form method="post" class="form-signin" action="login.php">
	    <p class="form-signin-heading"><?=$site?></p>
                </br><p style='padding:0px'>用户名：</p>
		<label for="username" class="sr-only">用户名</label>
		<input type="text"     id="username" name="username" class="form-control" placeholder="username@dicp.ac.cn" required autofocus>
                </br><p style='padding:0px;'>密码：</p>
		<label for="password" class="sr-only">密码</label>
		<input type="password" id="password" name="password" class="form-control" placeholder="password"   required>
		<input class="btn btn-lg btn-primary btn-block" type="submit" value="登录">
	    </form>
	</div>

    <?php
    }
    ?>
</body>
</html>
