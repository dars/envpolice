<script>
$(function() {
	$( "#form_buy_date").datepicker({
		'showAnim':'slideDown',
		onSelect: function(dateText, inst) {
			console.log(dateText);
      		dateText = (dateText.substr(0,4)-1911)+dateText.substr(4)
      		$(this).val(dateText);
    	}
	});
});
</script>

<?php echo Form::open(array('class'=>'well')) ?>

<?php echo Form::label('總號：');?>
<?php echo Form::input('total_no',Input::post('total_no',isset($model)?$model->total_no:''),array('class'=>'span2'))?>
<?php echo Form::label('分類編號：');?>
<?php echo Form::input('sub_no',Input::post('sub_no',isset($model)?$model->sub_no:''),array('class'=>'span2'))?>
<?php echo Form::label('品名：');?>
<?php echo Form::input('name',Input::post('name',isset($model)?$model->name:''),array('class'=>'span4'))?>
<?php echo Form::label('採購日期：');?>
<?php echo Form::input('buy_date',Input::post('buy_date',isset($model)?$model->buy_date:''),array('class'=>'span2'))?>

<?php echo Form::label('保管人：');?>
<?php echo Form::select('user_id',Input::post('user_id',isset($model)?$model->user_id:''),$users)?>
<?php echo Form::label('放置地點：');?>
<?php echo Form::select('location_id',Input::post('location_id',isset($model)?$model->location_id:''),$locations)?>

<?php echo Form::label('年限：');?>
<?php echo Form::input('years',Input::post('years',isset($model)?$model->years:''),array('class'=>'span1'))?>
<?php echo Form::label('數量：');?>
<?php echo Form::input('qty',Input::post('qty',isset($model)?$model->qty:''),array('class'=>'span1'))?>
<?php echo Form::label('金額：');?>
<?php echo Form::input('amount',Input::post('amount',isset($model)?$model->amount:''),array('class'=>'span1'))?>
<?php echo Form::label('備註：');?>
<?php echo Form::input('note',Input::post('note',isset($model)?$model->note:''),array('class'=>'span6'))?>
<?php echo Form::hidden('id',Input::post('id',isset($model)?$model->id:''))?>
<?php if(!isset($model)):?>
<?php echo Form::label('複製次數：','times',array('class'=>'remark'));?>
<?php echo Form::input('times',Input::post('times',0),array('class'=>'span1 remark'))?>
<span class="remark">&nbsp;依總號自動產生相同內容的財產資料</span>
<?php endif;?>
<br>
<?php echo Form::submit('sub_btn','儲存',array('class'=>'btn btn-primary'));?>
<?php echo Form::close() ?>