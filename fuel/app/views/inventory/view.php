<h3>財產檢視</h3>
<div class="well">
	<table class='table table-striped table-bordered table-condensed assets-info'>
		<tr>
			<th>總號：</th>
			<td><?php echo $model->total_no?></td>
			<th>分類編號：</th>
			<td><?php echo $model->sub_no?></td>
		</tr>
		<tr>
			<th>品名：</th>
			<td colspan=3><?php echo $model->name?></td>
		</tr>
		<tr>
			<th>數量：</th>
			<td><?php echo $model->qty ?></td>
			<th>採購日：</th>
			<td><?php echo $model->buy_date ?></td>
		</tr>
		<tr>
			<th>年限：</th>
			<td><?php echo $model->years ?></td>
			<th>金額：</th>
			<td>$<?php echo $model->amount ?></td>
		</tr>
		<tr>
			<th>保管人：</th>
			<td><?php echo ($model->user_id != 0)?$model->user->name:'' ?></td>
			<th>放置地點：</th>
			<td><?php echo $model->location->name ?></td>
		</tr>
		<tr>
			<th>到期日：</th>
			<td><?php echo $model->expire_date ?></td>
			<th>逾期時間：</th>
			<td><?php echo Model_Assets::get_expire_day($model->expire_date) ?></td>
		</tr>
		<tr>
			<th>備註：</th>
			<td colspan=3><?php echo $model->note ?></td>
		</tr>
		<tr>
			<th>最後修改：</th>
			<td colspan=3><?php echo $model->updated_at ?></td>
		</tr>
	</table>
	<?php if(Auth::member(100)):?>
		<a class="btn btn-inverse" href='<?php echo Uri::create('admin/inventory/edit/'.$model->id)?>'><i class="icon-edit icon-white"></i>修改</a>	
	<?php endif; ?>
</div>

<?php echo Html::anchor('javascript:history.back()','返回上一頁');?>