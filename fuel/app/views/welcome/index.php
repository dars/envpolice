<!DOCTYPE HTML>
<html>
<head>
<title>環警隊 - 財產管理</title>
<meta charset="utf-8">
<?php echo Asset::css(array('mos-style.css','style.css'));?>
</head>

<body>
<div id="header">
	<div class="inHeaderLogin"></div>
</div>

<div id="loginForm">
	<div class="headLoginForm">
	管理者登入
	</div>
	<div class="fieldLogin">
		<?php echo Form::open();?>
		<?php echo Form::label('帳號：')?>
		<?php echo Form::input('username','',array('class'=>'login'));?>
		<?php echo Form::label('密碼：')?>
		<?php echo Form::password('username','',array('class'=>'login'));?>
		<?php echo Form::submit(array('value'=>'登入','class'=>'button','name'=>'sub_btn'));?>
		<?php echo Form::close();?>
	</div>
</div>
</body>
</html>