<?php
class Controller_Admin extends Controller_Template
{
	public $template = 'login';
	public function before()
	{	
		parent::before();
		$this->template->flash_msg = View::forge('flash_msg');
		if(Request::active()->action != 'logout'){
			if(!Auth::check() && Request::active()->action != 'login')
			{
				Response::redirect('admin/login');
			}
		}
	}
	public function action_login()
	{
		if(Input::method() == 'POST')
		{
			$val = Validation::forge();
			$val->add('username','User name')
				->add_rule('required');
			$val->add('password','Password')
				->add_rule('required');
			if($val->run())
			{
				$auth = Auth::instance();
				if(Auth::check() || $auth->login(Input::post('username'), Input::post('password')))
				{
					Session::set_flash('notice', array('type'=>'success','msg'=>'您好, 您已正確登入.'));
					Response::redirect('admin/inventory');
				}
				else
				{
					Session::set_flash('notice', array('type'=>'error','msg'=>'Sorry, 您的登入資訊不正確'));
					Response::redirect('admin/login');
				}
			}
			else
			{
				Session::set_flash('notice', array('type'=>'error','msg'=>'Sorry, 請填入正確的登入資訊'));
				Response::redirect('admin/login');
			}
		}
		$this->template->title = '環警隊 - 帳號登入';
		$this->template->content = View::forge('admin/login');
	}
	public function action_logout()
	{
		Auth::logout();
		Response::redirect('admin/login');
	}
	public function action_hashPwd(){
		echo Auth::instance()->hash_password(Input::get('pwd'));
	}
}