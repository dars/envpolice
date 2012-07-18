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
	$('#del_btn').click(function(){
		$('#myModal').modal('show')
	});
});
</script>
<div>
	<?php echo Form::open(array('class'=>'well form-inline')) ?>
	<table>
		<tr>
			<td><?php echo Form::label('總號：') ?></td>
			<td><?php echo Form::input('total_no','',array('class'=>'span2')) ?></td>
			<td><?php echo Form::label('分類編號：') ?></td>
			<td><?php echo Form::input('sub_no','',array('class'=>'span2')) ?></td>
			<td><?php echo Form::label('品名：') ?></td>
			<td><?php echo Form::input('name','',array('class'=>'span2'))?></td>
		</tr>
		<tr>
			<td><?php echo Form::label('備註：') ?></td>
			<td><?php echo Form::input('note','',array('class'=>'span2'))?></td>
			<td><?php echo Form::label('放置地點：') ?></td>
			<td><?php echo Form::select('location_id','',$data['locations'],array('class'=>'span2'))?></td>
			<td><?php echo Form::label('保管人：') ?></td>
			<td><?php echo Form::select('user_id','',$data['users'],array('class'=>'span2'))?></td>
		</tr>
		<tr>
			<td><?php echo Form::label('採購日期：') ?></td>
			<td><?php echo Form::input('buy_date','',array('class'=>'span2'))?></td>
			<td><?php echo Form::label('逾期時間：') ?></td>
			<td><?php echo Form::input('expiration_time','',array('class'=>'span2'))?></td>
			<td colsapn=2>&nbsp;</td>
		</tr>
		<tr>
			<td colspan=6 style="text-align:right">
				<?php echo Form::reset('reset_btn','清除',array('class'=>'btn'))?>&nbsp;
				<?php echo Form::submit('sub_btn','搜尋',array('class'=>'btn btn-info'))?>
			</td>
		</tr>
	</table>
	<?php echo Form::close() ?>
</div>
<?php if(Input::get('status') && Input::get('status') == 'deleted'):?>
<h3>財產清冊 - 已報廢財產</h3>
<?php else:?>
<h3>財產清冊 - 使用中財產</h3>
<?php endif;?>
<div class="btn-group" style="float:left">
	<button class="btn"><i class="icon-ok"></i>選擇此頁</button>
	<button class="btn"><i class="icon-repeat"></i>取消此頁</button>
	<button class="btn btn-success"><i class="icon-print icon-white"></i>列印</button>
	<button class="btn btn-warning"><i class="icon-share icon-white"></i>匯出</button>
</div>
<div class="btn-group" style="float:right">
	<button class="btn btn-info" id="add_btn"><i class="icon-plus icon-white"></i>新增</button>
	<button class="btn btn-inverse" id="edit_btn"><i class="icon-edit icon-white"></i>修改</button>
	<button class="btn btn-danger" id="del_btn"><i class="icon-remove icon-white"></i>刪除</button>
</div>
<br><br>
<?php
if($data['result']){
	echo "<table class='table table-striped table-bordered table-condensed'>";
	echo "<thead><tr>";
	echo "<th><input type='checkbox'></th>";
	echo "<th>項目</th>";
	echo "<th>總號</th>";
	echo "<th>分類編號</th>";
	echo "<th>品名</th>";
	echo "<th>金額</th>";
	echo "<th>保管人</th>";
	echo "<th>到期日</th>";
	echo "</tr></thead>";

	$sn = 1;
	echo '<tbody>';
	foreach($data['result'] as $t)
	{
		echo '<tr>';
		echo '<td><input type="checkbox"></td>';
		echo '<td>'.($data['offset']+$sn).'.</td>';
		echo '<td>'.Html::anchor(Uri::create('admin/inventory/view/'.$t->id),$t->total_no).'</td>';
		echo '<td>'.$t->sub_no.'</td>';
		echo '<td>'.$t->name.'</td>';
		echo '<td class="money">$'.number_format($t->amount,2).'</td>';
		echo '<td>'.$t->user->name.'</td>';
		echo '<td>'.$t->expire_date.'</td>';
		echo '</tr>';
		$sn++;
	}
	echo '</tbody>';
	echo "</table>";
	echo $data['pagination'];
}
?>

<div class="modal fade hide" id="myModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>訊息</h3>
	</div>
	<div class="modal-body">
		<p>您確定要刪除你所選擇的資料嗎？</p>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">取消</a>
		<a href="#" class="btn btn-danger">確定</a>
	</div>
</div>