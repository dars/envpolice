<script type="text/javascript">
var type = 'minus';
$(function() {
	$( "#form_buy_date").datepicker({
		'showAnim':'slideDown',
		onSelect: function(dateText, inst) {
      		dateText = (dateText.substr(0,4)-1911)+dateText.substr(4)
      		$(this).val(dateText);
    	}
	});

	// 取消搜尋
	$('#reset_btn').click(function(){
		var url = "<?php echo Uri::create('admin/inventory?clear=true')?>";
		if($('#form_status').val() == 'deleted'){
			url += "&status=deleted";
		}
		location.href = url;
	});

	// 新增
	$('#add_btn').click(function(){
		location.href = '<?php echo Uri::create('admin/inventory/create')?>';
	});

	// 報廢
	$('#minus_btn').click(function(){
		var objs = $('.chk_btn:checked');
		if(objs.length > 0){
			type = 'minus';
			$('#type_str').html('報廢');
			$('#myModal').modal('show');
		}
	});

	// 刪除
	$('#del_btn').click(function(){
		var objs = $('.chk_btn:checked');
		type = 'delete';
		if(objs.length > 0){
			$('#type_str').html('刪除');
			$('#myModal').modal('show');
		}
	});

	$('#del_sub_btn').click(function(){
		var objs = $('.chk_btn:checked');
		var ids = new Array();
		$.each(objs,function(i, v){
			ids.push($(v).attr('id').split('_')[2]);
		});
		$.ajax({
			url:'<?php echo Uri::create('admin/inventory/')?>'+type,
			type:'post',
			data:'ids='+ids.join(','),
			dateType:'json',
			success:function(){
				location.reload();
			}
		});
	});

	// 單一checkbox
	$('.chk_btn').click(function(){
		var ids = new Array();
		ids.push($(this).attr('id').split('_')[2]);
		var chk = $(this).attr('checked')?'1':'0';
		send_chk_query(ids,chk);
	});

	// 取消本頁
	$('#dele_page').click(function(){
		var objs = $('.chk_btn');
		var ids = new Array();
		$.each(objs,function(i, v){
			$(v).attr('checked',false);
			ids.push($(v).attr('id').split('_')[2]);
		});
		send_chk_query(ids,0);
	});

	// 選取本頁
	$('#chk_page_btn').click(function(){
		var objs = $('.chk_btn');
		var ids = new Array();
		if($(this).attr('checked')){
			$.each(objs,function(i, v){
				$(v).attr('checked',true);
				ids.push($(v).attr('id').split('_')[2]);
			});
			send_chk_query(ids,1);
		}else{
			$.each(objs,function(i, v){
				$(v).attr('checked',false);
				ids.push($(v).attr('id').split('_')[2]);
			});
			send_chk_query(ids,0);
		}
	});

	// 選擇全部
	$('#sele_all').click(function(){
		var objs = $('.chk_btn');
		$.each(objs,function(i, v){
			$(v).attr('checked',true);
		});
		send_chk_all(1);
	});

	// 取消選擇全部
	$('#cancel_sele_all').click(function(){
		var objs = $('.chk_btn');
		$.each(objs,function(i, v){
			$(v).attr('checked',false);
		});
		send_chk_all(0);
	});

	// 列印
	$('#print_btn').click(function(){
		window.open("<?php echo Uri::create('admin/inventory/print_assets')?>");
	});

	// 匯出
	$('#parse_xls').click(function(){
		location.href = "<?php echo Uri::create('admin/inventory/parse_xls')?>";
	});

	function send_chk_query(ids,chk){
		$.ajax({
			url:'<?php echo Uri::create('admin/inventory/chk_session')?>',
			type:'post',
			data:'ids='+ids.join(',')+'&status='+chk
		});
	}

	function send_chk_all(chk){
		$.ajax({
			url:'<?php echo Uri::create('admin/inventory/chk_all_session')?>',
			type:'post',
			data:'status='+chk
		});
	}
	
});
</script>
<div>
	<?php echo Form::open(array('action'=>Uri::create('admin/inventory/index'),'class'=>'well form-inline','method'=>'get')) ?>
	<table>
		<tr>
			<?php $condition = Session::get('condition'); ?>
			<td><?php echo Form::label('總號：') ?></td>
			<td><?php echo Form::input('total_no',isset($condition['total_no'])?$condition['total_no']:'',array('class'=>'span2')) ?></td>
			<td><?php echo Form::label('分類編號：') ?></td>
			<td><?php echo Form::input('sub_no',isset($condition['sub_no'])?$condition['sub_no']:'',array('class'=>'span2')) ?></td>
			<td><?php echo Form::label('品名：') ?></td>
			<td><?php echo Form::input('name',isset($condition['name'])?$condition['name']:'',array('class'=>'span2'))?></td>
		</tr>
		<tr>
			<td><?php echo Form::label('備註：') ?></td>
			<td><?php echo Form::input('note',isset($condition['note'])?$condition['note']:'',array('class'=>'span2'))?></td>
			<td><?php echo Form::label('放置地點：') ?></td>
			<td><?php echo Form::select('location_id',isset($condition['location_id'])?$condition['location_id']:'',$data['locations'],array('class'=>'span2'))?></td>
			<td><?php echo Form::label('保管人：') ?></td>
			<td><?php echo Form::select('user_id',isset($condition['user_id'])?$condition['user_id']:'',$data['users'],array('class'=>'span2'))?></td>
		</tr>
		<tr>
			<td><?php echo Form::label('金額：') ?></td>
			<td><?php echo Form::select('amount',isset($condition['amount'])?$condition['amount']:'',array(''=>'請選擇','10000up'=>'10000以上','10000down'=>'10000以下'),array('class'=>'span2'))?></td>
			<td><?php echo Form::label('採購日期：') ?></td>
			<td><?php echo Form::input('buy_date',isset($condition['buy_date'])?$condition['buy_date']:'',array('class'=>'span2'))?></td>
			<td><?php echo Form::label('逾期時間：') ?></td>
			<td><?php echo Form::select('expiration_time',isset($condition['expiration_time'])?$condition['expiration_time']:'',array(''=>'請選擇','0'=>'未逾期','*'=>'已逾期'),array('class'=>'span2'))?></td>
		</tr>
		<tr>
			<td colspan=6 style="text-align:right">
				<?php echo Form::hidden('status',Input::get('status',''));?>
				<?php echo Form::button('reset_btn','清除',array('class'=>'btn','type'=>'button','id'=>'reset_btn'))?>&nbsp;
				<?php echo Form::submit('sub_btn','搜尋',array('class'=>'btn btn-info'))?>
			</td>
		</tr>
	</table>
	<?php echo Form::close() ?>
</div>
<h3>
	<?php if(Input::get('status') && Input::get('status') == 'deleted'):?>
		財產清冊 - 已報廢財產
	<?php else:?>
		財產清冊 - 使用中財產
	<?php endif;?>
	<span class="total_result">共<?php echo $data['total_result']?>筆資料</span>
</h3>
<div class="btn-group" style="float:left">
	<button class="btn" id="sele_all"><i class="icon-ok"></i>選擇全部</button>
	<button class="btn" id="cancel_sele_all"><i class="icon-remove"></i>取消全部</button>
	<button class="btn btn-success" id="print_btn"><i class="icon-print icon-white"></i>列印</button>
	<button class="btn btn-warning" id="parse_xls"><i class="icon-share icon-white"></i>匯出</button>
</div>
<?php if(Auth::member(100)):?>
<div class="btn-group" style="float:right">
	<?php if(Input::get('status') != 'deleted'):?>
		<button class="btn btn-info" id="add_btn"><i class="icon-plus icon-white"></i>新增</button>
		<button class="btn btn-inverse" id="minus_btn"><i class="icon-minus-sign icon-white"></i>報廢</button>
	<?php endif;?>
	<button class="btn btn-danger" id="del_btn"><i class="icon-remove icon-white"></i>刪除</button>
</div>
<?php endif; ?>
<br><br>
<?php
if($data['result']){
	echo "<table class='table table-striped table-bordered table-condensed'>";
	echo "<thead><tr>";
	echo "<th><input type='checkbox' id='chk_page_btn'></th>";
	echo "<th><nobr>項目</nobr></th>";
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
		echo '<td>'.Form::checkbox('chk_'.$t->id,'',(array_search($t->id,$data['chks'])===false)?null:true,array('class'=>'chk_btn')).'</td>';
		echo '<td>'.($data['offset']+$sn).'.</td>';
		echo '<td>'.Html::anchor(Uri::create('admin/inventory/view/'.$t->id),$t->total_no).'</td>';
		echo '<td>'.$t->sub_no.'</td>';
		echo '<td>'.$t->name.'</td>';
		echo '<td class="money">$'.number_format($t->amount,2).'</td>';
		echo '<td><nobr>';
		if($t->user_id != '0'){
			echo Html::anchor(Uri::create('admin/users/view/'.$t->user_id),$t->user->name);
		}else{
			echo '-';
		}
		echo '</nobr></td>';
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
		<p>您確定要<span id="type_str">刪除</span>你所選擇的資料嗎？</p>
	</div>
	<div class="modal-footer">
		<a href="javascript:void(0)" class="btn" data-dismiss="modal">取消</a>
		<a href="javascript:void(0)" id="del_sub_btn" class="btn btn-danger">確定</a>
	</div>
</div>