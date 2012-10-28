<?php
class Controller_Admin_Sys extends Controller_Admin
{
	public function before()
	{
		$this->template = 'tmpl';
		//$this->current_user = Auth::check()? Model_User::find_by_username(Auth::get_screen_name()):null;
		parent::before();
	}
	public function action_index(){
		Session::delete('chks');
		if(Input::get('from_user')){
			$result = Model_Assets::find()->where('user_id',Input::get('from_user'));
			$config = array(
				'pagination_url' => Uri::create('admin/sys/index'),
				'total_items' => $result->count(),
				'per_page' => 50,
				'uri_segment' => 4,
				'template' => array(
					'previous_inactive_start' => '<span class="disabled"><a href="#">',
					'previous_inactive_end' => '</a></span>',
					'next_inactive_start' => '</span class="disabled"><a href="#">',
			        'next_inactive_end' => '</a></span>',
			        'active_start' => '<span class="active"><a href="#">',
			        'active_end' => '</a></span>',
				)
			);
			Pagination::set_config($config);
			$data['result'] = $result->limit(Pagination::$per_page)->offset(Pagination::$offset)->get();
			$data['pagination'] = Pagination::create_links();
			$data['offset'] = Pagination::$offset;
			$data['sys_chks'] = is_array(Session::get('sys_chks'))?Session::get('sys_chks'):array();
		}
		$users = Model_Users::find('all');
		$data['users'] = array();
		$data['users'][0] = '請選擇';
		foreach($users as $u)
		{
			$data['users'][$u->id] = $u->name;
		}
		$model = Model_Assetsbackup::find()->get_one();
		$data['backup_date'] = $model->backup_at;
		$this->template->title = "系統管理";
		$view = View::forge('sys/index');
		$view->set('data',$data,false);
		$this->template->content = $view;
	}
	public function action_get_assets($id){
		$result = Model_Assets::find()->where('user_id','=',$id);
		$config = array(
			'pagination_url' => Uri::create('admin/sys/index'),
			'total_items' => $result->count(),
			'per_page' => 50,
			'uri_segment' => 4,
			'template' => array(
				'previous_inactive_start' => '<span class="disabled"><a href="#">',
        		'previous_inactive_end' => '</a></span>',
        		'next_inactive_start' => '</span class="disabled"><a href="#">',
		        'next_inactive_end' => '</a></span>',
		        'active_start' => '<span class="active"><a href="#">',
		        'active_end' => '</a></span>',
				)

		);
		Pagination::set_config($config);

		$model = $result->limit(Pagination::$per_page)->offset(Pagination::$offset)->get();
		$result = "<table class='table table-striped table-bordered table-condensed'>";
		$result.= "<tr>";
		$result.= "<th><input type='checkbox' id='chk_page_btn'></th>";
		$result.= "<th>項目</th><th>總號</th><th>分類編號</th><th>品名</th><th>金額</th><th>到期日</th></tr>";
		$index = 1;
		if($model){
			foreach($model as $t){
				$result.= "<tr>";
				$result.= "<td><input type='checkbox' id='chk_".$t->id."' class='chk_btn'></td>";
				$result.= "<td>".(Pagination::$offset+$index)."</td>";
				$result.= "<td>".$t->total_no."</td>";
				$result.= "<td>".$t->sub_no."</td>";
				$result.= "<td>".$t->name."</td>";
				$result.= "<td>".$t->amount."</td>";
				$result.= "<td>".$t->expire_date."</td>";
				$result.= "</tr>";
				$index++;
			}
		}
		$result.= "</table>";
		$result.= Pagination::create_links();
		return $result;
	}
	public function action_chg_user(){
		$sys_chks = Session::get('sys_chks');
		var_dump($sys_chks);
		if(is_array($sys_chks)){
			if(Input::method() == 'POST'){
				$model = Model_Assets::find()->where('user_id','=',Input::post('from_user'))->where('id','in',$sys_chks)->get();
				foreach($model as $t){
					$t->user_id = Input::post('to_user');
					$t->save();
				}
				Session::set_flash('notice',array('type'=>'success','msg'=>'資料已儲存'));
				Response::redirect('admin/sys');
			}
			Session::set('sys_chks',array());
		}else{
			Session::set_flash('notice',array('type'=>'error','msg'=>'資料設定異常'));
				Response::redirect('admin/sys');
		}
	}
	public function action_import_assets(){
		$this->template = false;
		$config = array(
			'path'=> DOCROOT.'temp',
			'randomize' => true,
			'ext_whitelist' => array('xls')
		);
		Upload::process($config);
		if(Upload::is_valid())
		{
			Upload::save();
			$file = Upload::get_files(0);
			Autoloader::add_class('PHPExcel_IOFactory',APPPATH.'vendor/PHPExcel/IOFactory.php');
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$xls = $reader->load($file['saved_to'].$file['saved_as']);
			$sheet = $xls->getSheet(0);
			$total_row = $sheet->getHighestRow();
			for($row=3;$row<=$total_row;$row++){
				$model = Model_Assets::forge();
				$model->total_no = trim($sheet->getCellByColumnAndRow(1,$row)->getValue());
				$model->sub_no = trim($sheet->getCellByColumnAndRow(2,$row)->getValue());
				$model->name = trim($sheet->getCellByColumnAndRow(3,$row)->getValue());
				$model->qty = (int)trim($sheet->getCellByColumnAndRow(4,$row)->getValue());
				$model->buy_date = trim($sheet->getCellByColumnAndRow(5,$row)->getValue());
				$model->years = (int)trim($sheet->getCellByColumnAndRow(6,$row)->getValue());
				$model->amount = (int)trim($sheet->getCellByColumnAndRow(7,$row)->getValue());
				$model->location_id = Model_Locations::get_location_id(trim($sheet->getCellByColumnAndRow(8,$row)->getValue()));
				$model->user_id = Model_Users::get_user_id(trim($sheet->getCellByColumnAndRow(9,$row)->getValue()));
				$model->expire_date = trim($sheet->getCellByColumnAndRow(10,$row)->getValue());
				$model->expiration_time = trim($sheet->getCellByColumnAndRow(11,$row)->getValue());
				$model->status = 1;
				$model->save();
			}
			Session::set_flash('notice',array('type'=>'success','msg'=>'資料已匯入完成'));
			Response::redirect('admin/sys');
		}
		else
		{
			echo '{"error":"no file was uploaded"}';
		}
	}

	public function action_backup_assets(){
		$model = Model_Assetsbackup::find()->delete();
		$model = Model_Assets::find()->get();
		$time = date('Y-m-d');
		foreach($model as $t){
			$model2 = Model_Assetsbackup::forge();
			$model2->id = $t->id;
			$model2->user_id = $t->user_id;
			$model2->total_no = $t->total_no;
			$model2->sub_no = $t->sub_no;
			$model2->name = $t->name;
			$model2->location_id = $t->location_id;
			$model2->buy_date = $t->buy_date;
			$model2->expire_date = $t->expire_date;
			$model2->expiration_time = $t->expiration_time;
			$model2->amount = $t->amount;
			$model2->qty = $t->qty;
			$model2->years = $t->years;
			$model2->note = $t->note;
			$model2->status = $t->status;
			$model2->created_at = $t->created_at;
			$model2->updated_at = $t->updated_at;
			$model2->backup_at = $time;
			$model2->save();
		}
		Session::set_flash('notice',array('type'=>'success','msg'=>'資料已備份完成'));
		Response::redirect('admin/sys');
	}

	public function action_restore_assets(){
		$model = Model_Assets::find()->delete();
		$model = Model_Assetsbackup::find()->get();
		foreach($model as $t){
			$model2 = Model_Assets::forge();
			$model2->id = $t->id;
			$model2->user_id = $t->user_id;
			$model2->total_no = $t->total_no;
			$model2->sub_no = $t->sub_no;
			$model2->name = $t->name;
			$model2->location_id = $t->location_id;
			$model2->buy_date = $t->buy_date;
			$model2->expire_date = $t->expire_date;
			$model2->expiration_time = $t->expiration_time;
			$model2->amount = $t->amount;
			$model2->qty = $t->qty;
			$model2->years = $t->years;
			$model2->note = $t->note;
			$model2->status = $t->status;
			$model2->created_at = $t->created_at;
			$model2->updated_at = $t->updated_at;
			$model2->save();
		}
		Session::set_flash('notice',array('type'=>'success','msg'=>'資料已還原完成'));
		Response::redirect('admin/sys');
	}

	public function action_chk_session(){
		$this->template = null;
		if(Input::method() == 'POST')
		{
			$chks = Session::get('sys_chks');
			$ids = explode(',',Input::post('ids'));

			if(!is_array($chks)){
				$chks = array();
			}
			
			if(Input::post('status') == 1)
			{
				foreach($ids as $t)
				{
					if(array_search($t,$chks) === false)
					{
						array_push($chks,$t); 
					}
				}
			}
			else
			{
				foreach($ids as $t)
				{
					$index = array_search($t,$chks);
					unset($chks[$index]);
				}
			}
			$chks = array_unique($chks);
			sort($chks);
			Session::set('sys_chks',$chks);
		}
	}

	public function action_chk_all_session(){
		$this->template = null;
		if(Input::method() == 'POST')
		{
			$chks = array();
			if(Input::post('status') == 1)
			{
				$result = Model_Assets::find()->where('user_id',Input::get('user_id'));
				$res = $result->get();
				foreach($res as $t){
					array_push($chks,$t->id);
				}
			}
			$chks = array_unique($chks);
			sort($chks);
			Session::set('sys_chks',$chks);
		}
	}
}