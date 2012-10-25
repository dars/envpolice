<?php echo Form::open(array('class'=>'well')) ?>
<?php echo Form::label('帳號')?>
<?php echo Form::input('username',Input::post('username',isset($model)?$model->username:''))?>
<?php echo Form::label('密碼')?>
<?php echo Form::password('password',Input::post('password',''))?>
<?php echo isset($model)?'<span class="remark">&nbsp;不修改請留空</span>':'' ?>
<?php echo Form::label('名稱')?>
<?php echo Form::input('name',Input::post('name',isset($model)?$model->name:''))?>
<?php echo Form::label('email')?>
<?php echo Form::input('email',Input::post('email',isset($model)?$model->email:''))?>
<?php echo Form::label('所屬單位')?>
<?php echo Form::select('location_id',Input::post('location_id',isset($model)?$model->location_id:''),$locations)?>
<?php echo Form::label('權限')?>
<?php echo Form::select('group',Input::post('group',isset($model)?$model->group:''),$group)?>
<?php if(isset($model)):?>
	<?php echo Form::label('狀態')?>
	<?php echo Form::select('status',Input::post('status',isset($model)?$model->status:1),$status)?>
<?php endif;?>
<br>
<?php echo Form::hidden('id',Input::post('id',isset($model)?$model->id:''))?>
<?php echo Form::submit('sub_btn','送出',array('class'=>'btn btn-primary'));?>
<?php echo Form::close() ?>