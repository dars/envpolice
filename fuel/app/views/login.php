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
	<div class="inHeaderLogin"></div>
</div>

<div id="container">
	<?php echo $flash_msg?>
	<?php echo $content ?>
</div>
</body>
</html>