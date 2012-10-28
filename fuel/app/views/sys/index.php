<script type="text/javascript">
var user_id;
$(function(){
	<?php if(Input::get('from_user')):?>
		var page_links = $('.pagination a');
		$.each(page_links, function(i, v){
			$(v).attr('href',$(v).attr('href')+'?from_user=<?php echo Input::get('from_user')?>');
		});
	<?php endif;?>
	$('#form_from_user').change(function(){
		location.href = '<?php echo Uri::create('admin/sys/index')?>?from_user='+$(this).val();
		return;
		if($(this).val() != 0){
			user_id = $(this).val();
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
	$('#backup_btn').click(function(){
		flag = confirm('您確定要備份目前的財產資料嗎？');
		if(flag){
			location.href = '<?php echo Uri::create('admin/sys/backup_assets') ?>';
		}
	});

	$('#restore_btn').click(function(){
		flag = confirm('您確定要還原之前備份的財產資料嗎？');
		if(flag){
			location.href = '<?php echo Uri::create('admin/sys/restore_assets') ?>';
		}
	});

	// 單一checkbox
	$('.chk_btn').live('click',function(){
		var ids = new Array();
		ids.push($(this).attr('id').split('_')[2]);
		var chk = $(this).attr('checked')?'1':'0';
		send_chk_query(ids,chk);
	});

	// 選擇全部
	$('#sele_all').live('click',function(){
		var objs = $('.chk_btn');
		$.each(objs,function(i, v){
			$(v).attr('checked',true);
		});
		send_chk_all(1);
	});

	// 取消選擇全部
	$('#cancel_sele_all').live('click',function(){
		var objs = $('.chk_btn');
		$.each(objs,function(i, v){
			$(v).attr('checked',false);
		});
		send_chk_all(0);
	});

	function send_chk_query(ids,chk){
		$.ajax({
			url:'<?php echo Uri::create('admin/sys/chk_session')?>',
			type:'post',
			data:'ids='+ids.join(',')+'&status='+chk
		});
	}
	function send_chk_all(chk){
		$.ajax({
			url:'<?php echo Uri::create('admin/sys/chk_all_session?user_id=')?>'+user_id,
			type:'post',
			data:'status='+chk
		});
	}
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
	<?php echo Form::button('backup_btn','備份財產',array('class'=>'btn btn-success','type'=>'button','id'=>'backup_btn')) ?>
	<?php if(isset($data['backup_date'])):?>
	<?php echo Form::button('restore_btn','還原財產&nbsp;('.$data['backup_date'].')',array('class'=>'btn btn-info','type'=>'button','id'=>'restore_btn')) ?>
	<?php endif;?>
</div>

<div class="well">
	<?php echo Form::open(array('action'=>Uri::create('admin/sys/chg_user')))?>
	<?php echo Form::label('財產轉移') ?>
	<?php echo Form::select('from_user',Input::get('from_user',''),$data['users']) ?>
	<?php echo Form::select('to_user',Input::get('to_user',''),$data['users']) ?>
	<?php echo Form::submit('sub_btn','轉移',array('class'=>'btn btn-danger'))?>
	<?php echo Form::close() ?>
	<div id="res_block">
		<?php
			if(isset($data['result'])){
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
					echo '<td>'.Form::checkbox('chk_'.$t->id,'',(array_search($t->id,$data['sys_chks'])===false)?null:true,array('class'=>'chk_btn')).'</td>';
					echo '<td>'.($data['offset']+$sn).'.</td>';
					echo '<td>'.Html::anchor(Uri::create('admin/inventory/view/'.$t->id),$t->total_no).'</td>';
					echo '<td>'.$t->sub_no.'</td>';
					echo '<td>'.$t->name.'</td>';
					echo '<td class="money">$'.number_format($t->amount,2).'</td>';
					echo '<td><nobr>'.Html::anchor(Uri::create('admin/users/view/'.$t->user_id),$t->user->name).'</nobr></td>';
					echo '<td>'.$t->expire_date.'</td>';
					echo '</tr>';
					$sn++;
				}
				echo '</tbody>';
				echo "</table>";
				echo $data['pagination'];
			}
			?>
	</div>
</div>