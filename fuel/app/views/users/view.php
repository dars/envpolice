<h3>帳號檢視 - 帳號管理</h3>
<div class="well">
	<table class='table table-striped table-bordered table-condensed assets-info'>
		<tr>
			<th>帳號</th>
			<td><?php echo $model->username ?></td>
		</tr>
		<tr>
			<th>權限</th>
			<td><?php echo $group[$model->group] ?></td>
		</tr>
		<tr>
			<th>狀態</th>
			<td><?php echo $status[$model->status] ?></td>
		</tr>
		<tr>
			<th>姓名</th>
			<td><?php echo $model->name ?></td>
		</tr>
		<tr>
			<th>所屬單位</th>
			<td><?php echo $model->location->name ?></td>
		</tr>
		<tr>
			<th>電話</th>
			<td><?php echo $model->phone ?></td>
		</tr>
		<tr>
			<th>電子信箱</th>
			<td><?php echo Html::mail_to($model->email,$model->email) ?></td>
		</tr>
		<tr>
			<th>最後修改</th>
			<td><?php echo $model->updated_at ?></td>
		</tr>
	</table>
</div>
<?php echo Html::anchor('javascript:history.back()','返回上一頁');?>