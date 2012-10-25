<div id="loginForm">
	<div class="headLoginForm">
	管理者登入
	</div>
	<div class="fieldLogin">
		<?php echo Form::open();?>
		<?php echo Form::label('帳號：')?>
		<?php echo Form::input('username');?>
		<?php echo Form::label('密碼：')?>
		<?php echo Form::password('username');?>
		<?php echo Form::submit(array('value'=>'登入','class'=>'button','name'=>'sub_btn'));?>
		<?php echo Form::close();?>
	</div>
</div>