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
				echo '<button class="btn btn-mini"><i class="icon-pencil"></i></button>&nbsp;';
				echo '<button class="btn btn-danger btn-mini"><i class="icon-remove icon-white"></i></button>';
				echo '</td>';
				echo '</tr>';
				$index++;
			}
		?>
	</tbody>
</table>