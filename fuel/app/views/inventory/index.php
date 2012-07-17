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
			<td><?php echo Form::input('location_id','',array('class'=>'span2'))?></td>
			<td><?php echo Form::label('保管人：') ?></td>
			<td><?php echo Form::input('member_id','',array('class'=>'span2'))?></td>
		</tr>
		<tr>
			<td><?php echo Form::label('採購日期：') ?></td>
			<td><?php echo Form::input('buy_date','',array('class'=>'span2'))?></td>
			<td><?php echo Form::label('逾期時間：') ?></td>
			<td><?php echo Form::input('expire_date','',array('class'=>'span2'))?></td>
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
<div class="btn-group">
	<button class="btn"><i class="icon-ok"></i>選擇此頁</button>
	<button class="btn"><i class="icon-remove"></i>取消此頁</button>
	<button class="btn btn-inverse"><i class="icon-print icon-white"></i>列印</button>
	<button class="btn btn-warning"><i class="icon-share icon-white"></i>匯出</button>
	<button class="btn btn-danger"><i class="icon-edit icon-white"></i>修改</button>
</div>
<br>
<?php
if($data['result']){
	echo "<table class='table table-striped table-bordered table-condensed'>";
	echo "<tr>";
	echo "<th><input type='checkbox'></th>";
	echo "<th>項目</th>";
	echo "<th>總號</th>";
	echo "<th>分類編號</th>";
	echo "<th>品名</th>";
	echo "<th>金額</th>";
	echo "<th>保管人</th>";
	echo "<th>到期日</th>";
	echo "</tr>";

	$sn = 1;
	foreach($data['result'] as $t)
	{
		echo '<tr>';
		echo '<td><input type="checkbox"></td>';
		echo '<td>'.($data['offset']+$sn).'.</td>';
		echo '<td>'.Html::anchor(Uri::create('admin/inventory/view/'.$t->total_no),$t->total_no).'</td>';
		echo '<td>'.$t->sub_no.'</td>';
		echo '<td>'.$t->name.'</td>';
		echo '<td class="money">$'.number_format($t->amount,2).'</td>';
		echo '<td>'.$t->user->name.'</td>';
		echo '<td>'.$t->expire_date.'</td>';
		echo '</tr>';
		$sn++;
	}
	echo "</table>";
	echo $data['pagination'];
}
?>