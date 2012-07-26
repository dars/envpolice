<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $title ?></title>
<meta charset="utf-8">
<?php echo Asset::css(array('mos-style.css','jquery-ui-1.8.21.css','style.css'));?>
<?php echo Asset::js(array('jquery-1.7.2.min.js','bootstrap.min.js','jquery-ui-1.8.21.min.js'));?>
<script type="text/javascript">
$(function(){
	window.print();
})
</script>
</head>

<body>
	<?php echo $content ?>
</body>
</html>