<?php
class Controller_Admin_Inventory extends Controller_admin
{
	public function before()
	{
		$this->template = 'tmpl';
		//$this->current_user = Auth::check()? Model_User::find_by_username(Auth::get_screen_name()):null;
		parent::before();
	}
	public function action_index()
	{
		$view = View::forge('inventory/index');
		$data = array();
		$this->template->title = '使用者財產-財產清冊';
		$result = Model_Assets::find()->order_by('created_at','desc');
		$config = array(
			'pagination_url' => Uri::create('admin/inventory/index'),
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
		$view->set('data',$data,false);
		$this->template->content = $view;
	}
}