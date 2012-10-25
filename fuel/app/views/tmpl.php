<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $title ?></title>
<meta charset="utf-8">
<?php echo Asset::css(array('mos-style.css','jquery-ui-1.8.21.css','style.css'));?>
<?php echo Asset::js(array('jquery-1.7.2.min.js','bootstrap.min.js','jquery-ui-1.8.21.min.js'));?>
<script type="text/javascript">
jQuery(function($){
	$.datepicker.regional['zh-TW'] = {
		closeText: '關閉',
		prevText: '&#x3c;上月',
		nextText: '下月&#x3e;',
		currentText: '今天',
		monthNames: ['一月','二月','三月','四月','五月','六月',
		'七月','八月','九月','十月','十一月','十二月'],
		monthNamesShort: ['一','二','三','四','五','六',
		'七','八','九','十','十一','十二'],
		dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
		dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
		dayNamesMin: ['日','一','二','三','四','五','六'],
		weekHeader: '周',
		dateFormat: 'yy/mm/dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: '年'};
	$.datepicker.setDefaults($.datepicker.regional['zh-TW']);
});
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
			Hello, <?php echo Auth::get_user_name()?><br>
			<a href="<?php echo Uri::create('admin/logout')?>">Logout</a>
		</div>
	</div>
</div>

<div id="wrapper">
	<div id="leftBar">
		<ul>
			<li><?php echo Html::anchor('admin/inventory','財產清冊');?></a></li>
			<li><?php echo Html::anchor('admin/inventory?status=deleted','已報廢財產');?></li>
			<li><?php echo Html::anchor('admin/users','帳號管理');?></li>
			<li><?php echo Html::anchor('admin/sys','系統管理');?></li>
		</ul>
	</div>
	<div id="rightContent">
		<?php echo $flash_msg?>
		<?php echo $content ?>
	</div>
	<div class="clear"></div>
	<div id="footer">
		&copy;2012 環保警察隊 資訊室
	</div>
</div>
</body>
</html>