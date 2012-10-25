<h2>環 保 警 察 隊 財 產 清 冊</h2><br>
<table class='table table-striped table-bordered table-condensed'>
	<thead>
		<tr>
			<th>項次</th>
			<th>總號</th>
			<th>分類號碼</th>
			<th>品名</th>
			<th>數量</th>
			<th>購置日</th>
			<th>年限</th>
			<th>金額</th>
			<th>放置地點</th>
			<th>保管人</th>
			<th>到期日</th>
			<th>逾期時間</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$index = 1;
			foreach($data as $t):
				echo "<tr>";
				echo "<td>".$index."</td>";
				echo "<td>".$t->total_no."</td>";
				echo "<td>".$t->sub_no."</td>";
				echo "<td>".$t->name."</td>";
				echo "<td>".$t->qty."</td>";
				echo "<td>".$t->buy_date."</td>";
				echo "<td>".$t->years."</td>";
				echo "<td>".$t->amount."</td>";
				echo "<td>".$t->location->name."</td>";
				echo "<td>".$t->user->name."</td>";
				echo "<td>".$t->expire_date."</td>";
				echo "<td>".$t->expiration_time."</td>";
				echo "</tr>";
				$index++;
			endforeach;
		?>
	</tbody>
</table>