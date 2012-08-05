<script type="text/javascript">
$(function(){
	$('#form_from_user').change(function(){
		if($(this).val() != 0){
			$.ajax({
				url:'<?php echo Uri::create('admin/sys/get_assets')?>/'+$(this).val(),
				success:function(data){
					// $('#form_sub_btn').attr('disabled',0);
					$('#res_block').html(data);
				}
			});
		}else{
			$('#res_block').html('');
		}
		// $('#form_sub_btn').attr('disabled',1);
	});
});
</script>
<div class="well">
	<?php echo Form::open(array('action'=>Uri::create('admin/sys/import_assets'),'enctype'=>'multipart/form-data'))?>
	<?php echo Form::label('匯入財產') ?>
	<?php echo Form::file('assets') ?>
	<?php echo Form::submit('sub_btn','上傳',array('class'=>'btn btn-warning'))?>
	<?php echo Form::close() ?>
</div>

<div class="well">
	<?php echo Form::open(array('action'=>Uri::create('admin/sys/chg_user')))?>
	<?php echo Form::label('財產轉移') ?>
	<?php echo Form::select('from_user','',$users) ?>
	<?php echo Form::select('to_user','',$users) ?>
	<?php echo Form::submit('sub_btn','轉移',array('class'=>'btn btn-danger'))?>
	<?php echo Form::close() ?>
	<div id="res_block"></div>
</div>