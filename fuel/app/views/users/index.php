<script type="text/javascript">
var id;
$(function(){
	$('.edit_btns').click(function(){
		location.href = "<?php echo Uri::create('admin/users/edit')?>/"+$(this).attr('id').split('_')[1];
	});
	$('.dele_btns').click(function(){
		id = $(this).attr('id').split('_')[1];
		$('#myModal').modal('show');
	});
	$('#del_sub_btn').click(function(){
		location.href = "<?php echo Uri::create('admin/users/delete')?>/"+id;
	});
});
</script>
<h3>使用者列表 - 帳號管理</h3>
<table class='table table-striped table-bordered table-condensed'>
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>帳號</th>
			<th>姓名</th>
			<th>所屬單位</th>
			<th>電子信箱</th>
			<th>權限</th>
			<th>狀態</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$index = 1;
			//Auth::instance('simplegroup')->get_name(100);
			foreach($model as $t)
			{
				echo '<tr>';
				echo '<td>'.$index.'.</td>';
				echo '<td>'.Html::anchor('admin/users/view/'.$t->id,$t->username).'</td>';
				echo '<td>'.$t->name.'</td>';
				echo '<td>'.$t->location->name.'</td>';
				echo '<td>'.Html::mail_to($t->email,$t->email).'</td>';
				echo '<td>'.$group[$t->group].'</td>';
				echo '<td>'.$status[$t->status].'</td>';
				echo '<td>';
				echo '<button class="btn btn-mini edit_btns" id="edit_'.$t->id.'"><i class="icon-pencil"></i></button>&nbsp;';
				echo '<button class="btn btn-danger btn-mini dele_btns" id="dele_'.$t->id.'"><i class="icon-remove icon-white"></i></button>';
				echo '</td>';
				echo '</tr>';
				$index++;
			}
		?>
	</tbody>
</table>

<div class="modal fade hide" id="myModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>訊息</h3>
	</div>
	<div class="modal-body">
		<p>您確定要刪除你所選擇的資料嗎？</p>
	</div>
	<div class="modal-footer">
		<a href="javascript:void(0)" class="btn" data-dismiss="modal">取消</a>
		<a href="javascript:void(0)" id="del_sub_btn" class="btn btn-danger">確定</a>
	</div>
</div>