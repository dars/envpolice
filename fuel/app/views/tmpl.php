<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $title ?></title>
<meta charset="utf-8">
<?php echo Asset::css(array('mos-style.css','style.css'));?>
<?php echo Asset::js(array('jquery-1.7.2.min.js'));?>
<script type="text/javascript">
$(function(){
	$('.alert').on('click','.close',function(){
		$(this).parent().fadeOut();
	});
});
</script>
</head>

<body>
<div id="header">
	<div class="inHeaderLogin">
		<div class="mosAdmin">
			Hallo, <?php echo Auth::get_screen_name()?><br>
			<a href="<?php echo Uri::create('admin/logout')?>">Logout</a>
		</div>
	</div>
</div>

<div id="wrapper">
	<div id="leftBar">
		<ul>
			<li><?php echo Html::anchor('inventory/index','財產清冊');?></a></li>
			<li><a href="tabel.html">財產管理</a></li>
			<li><a href="form.html">帳號管理</a></li>
			<li><a href="form.html">系統管理</a></li>
		</ul>
	</div>
	<div id="rightContent">
		<?php echo $flash_msg?>
		<?php echo $content ?>
	</div>
	<div class="clear"></div>
	<div id="footer">
		&copy;2012 訊揚科技有限公司 <a href="http://www.inforise.com.tw" target="_blank">Inforise Technology CO., ITD.</a>
	</div>
</div>
</body>
</html>