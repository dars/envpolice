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
		$model = Model_Users::find('all');
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
}