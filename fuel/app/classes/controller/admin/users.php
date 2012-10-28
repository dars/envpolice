<?php
class Controller_Admin_Users extends Controller_Admin
{
	public $group = array(1=>'中隊',50=>'全隊',100=>'系統管理');
	public $status = array(1=>'有效',0=>'停權');
	public function before()
	{
		$this->template = 'tmpl';
		//$this->current_user = Auth::check()? Model_User::find_by_username(Auth::get_screen_name()):null;
		parent::before();
	}

	public function action_index()
	{
		Session::delete('chks');
		Session::delete('sys_chks');
		$model = Model_Users::find()->where('status',1)->get();
		$this->template->title = '使用者列表-帳號管理';
		$this->template->content = View::forge('users/index',array('model'=>$model,'group'=>$this->group,'status'=>$this->status));
	}

	public function action_view($id)
	{
		$model = Model_Users::find($id);
		if($model)
		{
			$view = View::forge('users/view');
			$view->set('model',$model,false);
			$view->set('group',$this->group);
			$view->set('status',$this->status);
			$this->template->title = '檢視使用者-帳號管理';
			$this->template->content = $view;
		}
		else
		{
			Session::set_flash('notice', array('type'=>'error','msg'=>'Sorry, 錯誤的需求參數'));
			Response::redirect('admin/users');
		}
	}

	public function action_create(){
		if(Input::method() == 'POST')
		{
			$model = Model_Users::forge();
			$model->username = Input::post('username');
			$model->password = Auth::instance()->hash_password(Input::post('password'));
			$model->email = Input::post('email');
			$model->name = Input::post('name');
			$model->location_id = Input::post('location_id');
			$model->group = Input::post('group');
			$model->status = 1;
			$model->save();
			Session::set_flash('notice',array('type'=>'success','msg'=>'資料已儲存'));
			Response::redirect('admin/users');
		}
		$view = View::forge('users/create');
		$data = array();

		$locations = Model_Locations::find('all');
		$data['locations'] = array();
		foreach($locations as $l)
		{
			$data['locations'][$l->id] = $l->name;
		}
		$data['group'] = array(1=>'中隊',50=>'全隊',100=>'系統管理者');
		$view->data = $data;
		$view->form = View::forge('users/_form',$data);
		$this->template->title = '新增 - 使用者資料';
		$this->template->content = $view;
	}
	public function action_edit($id){
		if(Input::method() == 'POST')
		{
			$model = Model_Users::find(Input::post('id'));
			$model->username = Input::post('username');
			if(Input::post('password') != ''){
				$model->password = Auth::instance()->hash_password(Input::post('password'));
			}
			$model->email = Input::post('email');
			$model->name = Input::post('name');
			$model->location_id = Input::post('location_id');
			$model->group = Input::post('group');
			$model->status = Input::post('status');
			$model->save();
			Session::set_flash('notice',array('type'=>'success','msg'=>'資料已儲存'));
			Response::redirect('admin/users/view/'.Input::post('id'));
		}
		$view = View::forge('users/edit');
		$data = array();

		$locations = Model_Locations::find('all');
		$data['locations'] = array();
		foreach($locations as $l)
		{
			$data['locations'][$l->id] = $l->name;
		}
		$data['group'] = array(1=>'中隊',50=>'全隊',100=>'系統管理者');
		$data['status'] = array(1=>'有效',0=>'暫停');
		$data['model'] = Model_Users::find($id);
		$view->data = $data;
		$view->form = View::forge('users/_form',$data);
		$this->template->title = '修改 - 使用者資料';
		$this->template->content = $view;
	}

	public function action_delete($id)
	{
		$model = Model_Assets::find()->where('user_id','=',$id)->get();
		if($model){
			Session::set_flash('notice',array('type'=>'error','msg'=>'此帳號還有財產資料，請先做財產轉移'));
		}else{
			Session::set_flash('notice',array('type'=>'success','msg'=>'資料已刪除'));
			Model_Users::find($id)->delete();
		}
		Response::redirect('admin/users');
	}
}