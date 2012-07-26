<?php
class Controller_Admin_Inventory extends Controller_admin
{
	public function before()
	{
		$this->template = 'tmpl';
		//$this->current_user = Auth::check()? Model_User::find_by_username(Auth::get_screen_name()):null;
		// 顯示結果頁面套用不同template
		if(@Uri::segment(3) == 'print_assets'){
			$this->template = 'tmpl2';
		}
	
		parent::before();
	}
	public function action_index()
	{
		$view = View::forge('inventory/index');
		$data = array();
		$this->template->title = '使用中財產-財產清冊';
		$result = Model_Assets::find();
		if(Input::get('status'))
		{
			if(Input::get('status') == 'deleted')
			{
				$result->where('status',0);
			}
		}
		else
		{
			$result->where('status',1);
		}
		$result->order_by('created_at','desc');
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
		
		$users = Model_Users::find('all');
		$data['users'] = array();
		foreach($users as $u)
		{
			$data['users'][$u->id] = $u->name;
		}

		$locations = Model_Locations::find('all');
		$data['locations'] = array();
		foreach($locations as $l)
		{
			$data['locations'][$l->id] = $l->name;
		}


		$data['result'] = $result->limit(Pagination::$per_page)->offset(Pagination::$offset)->get();
		$data['pagination'] = Pagination::create_links();
		$data['offset'] = Pagination::$offset;
		$data['chks'] = Session::get('chks');
		if(!is_array($data['chks']))
		{
			$data['chks'] = array();
		}
		$view->set('data',$data,false);
		$this->template->content = $view;
	}
	public function action_view($id)
	{
		$model = Model_Assets::find($id);
		if($model)
		{
			$view = View::forge('inventory/view');
			$view->set('model',$model,false);
			$this->template->title = ' - 財產資料';
			$this->template->content = $view;
		}
		else
		{
			Session::set_flash('notice', array('type'=>'error','msg'=>'Sorry, 錯誤的需求參數'));
			Response::redirect('admin/inventory');
		}
	}

	public function action_create(){
		if(Input::method() == 'POST')
		{
			$times = Input::post('times');
			$total_no = Input::post('total_no');
			do{
				$model = new Model_Assets;
				$model->user_id = Input::post('user_id');
				$model->total_no = $total_no;
				$model->sub_no = Input::post('sub_no');
				$model->name = Input::post('name');
				$model->location_id = Input::post('location_id');
				$model->buy_date = Input::post('buy_date');
				$model->amount = Input::post('amount');
				$model->qty = Input::post('qty');
				$model->years = Input::post('years');
				$model->note = Input::post('note');
				$model->save();
				$times--;
				$total_no++;
			}while($times>=0);
			Session::set_flash('notice',array('type'=>'success','msg'=>'資料已儲存'));
			Response::redirect('admin/inventory');
		}
		$view = View::forge('inventory/create');
		$data = array();
		$users = Model_Users::find('all');
		$data['users'] = array();
		foreach($users as $u)
		{
			$data['users'][$u->id] = $u->name;
		}

		$locations = Model_Locations::find('all');
		$data['locations'] = array();
		foreach($locations as $l)
		{
			$data['locations'][$l->id] = $l->name;
		}
		$view->data = $data;
		$view->form = View::forge('inventory/_form',$data);
		$this->template->title = '新增 - 財產資料';
		$this->template->content = $view;
	}

	public function action_edit($id){
		if(Input::method() == 'POST')
		{
			$model = Model_Assets::find(Input::post('id'));
			$model->user_id = Input::post('user_id');
			$model->total_no = Input::post('total_no');
			$model->sub_no = Input::post('sub_no');
			$model->name = Input::post('name');
			$model->location_id = Input::post('location_id');
			$model->buy_date = Input::post('buy_date');
			$model->amount = Input::post('amount');
			$model->qty = Input::post('qty');
			$model->years = Input::post('years');
			$model->note = Input::post('note');
			if($model->save())
			{
				Session::set_flash('notice',array('type'=>'success','msg'=>'資料已儲存'));
				Response::redirect('admin/inventory');
			}
			else
			{
				Session::set_flash('notice',array('type'=>'error','msg'=>'資料儲存異常'));
			}
		}
		$view = View::forge('inventory/edit');
		$data = array();
		$users = Model_Users::find('all');
		$data['users'] = array();
		foreach($users as $u)
		{
			$data['users'][$u->id] = $u->name;
		}

		$locations = Model_Locations::find('all');
		$data['locations'] = array();
		foreach($locations as $l)
		{
			$data['locations'][$l->id] = $l->name;
		}
		$data['model'] = Model_Assets::find($id);
		$view->data = $data;
		$view->form = View::forge('inventory/_form',$data);
		$this->template->title = '編輯 - 財產資料';
		$this->template->content = $view;
	}

	public function action_delete(){
		$this->template = false;
		$ids = explode(',',Input::post('ids'));
		foreach($ids as $v){
			$model = Model_Assets::find($v);
			if($model){
				$model->status = 0;
				$model->save();
			}
		}
		Session::set('chks',array());
		Session::set_flash('notice',array('type'=>'success','msg'=>'資料已轉入報廢清單'));
	}

	public function action_chk_session(){
		$this->template = null;
		if(Input::method() == 'POST')
		{
			if(Input::post('chk_all') == 1)
			{
				$chks = array();
				if(Input::post('status') == 1)
				{
					$result = Model_Assets::find();
					$result->order_by('created_at','desc');
					foreach($result as $t){
						echo 1;
						echo $t->id;
						array_push($chks,$t->id);
					}
				}
			}
			else
			{
				$chks = Session::get('chks');
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
			}
			$chks = array_unique($chks);
			sort($chks);
			Session::set('chks',$chks);
		}
	}

	public function action_print_assets()
	{
		//Request::active()->uri->segments[1];
		$chks = Session::get('chks');
		$objs = Model_Assets::find()->where('id','IN',$chks)->get();
		$this->template->title = '環 保 警 察 隊 財 產 清 冊';
		$this->template->content = View::forge('inventory/print',array('data'=>$objs));
	}

	public function action_parse_xls()
	{
		$this->template = false;
		$chks = Session::get('chks');
		$objs = Model_Assets::find()->where('id','IN',$chks)->get();

		Autoloader::add_class('PHPExcel',APPPATH.'vendor/PHPExcel.php');
		$title = '環 保 警 察 隊 財 產 清 冊';
		$xls_obj = new PHPExcel();
		//設定標題
		$xls_obj->getProperties()->setCategory($title);
		$xls_obj->setActiveSheetIndex(0);
		$xls_obj->getActiveSheet()->setTitle('財產清冊');
		$xls_obj->getActiveSheet()->mergeCells('A1:L1');
		$xls_obj->getActiveSheet()->getRowDimension('1')->setRowHeight(26);
		$xls_obj->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_DARKGREEN);
		$xls_obj->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$xls_obj->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
		$xls_obj->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$xls_obj->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$xls_obj->getActiveSheet()->setCellValue('A1', $title);

		$xls_obj->getActiveSheet()->getRowDimension('2')->setRowHeight(18);
		$xls_obj->getActiveSheet()->setCellValue('A2', '項次');
		$xls_obj->getActiveSheet()->setCellValue('B2', '總號');
		$xls_obj->getActiveSheet()->setCellValue('C2', '分類號碼');
		$xls_obj->getActiveSheet()->setCellValue('D2', '品名');
		$xls_obj->getActiveSheet()->setCellValue('E2', '數量');
		$xls_obj->getActiveSheet()->setCellValue('F2', '購置日');
		$xls_obj->getActiveSheet()->setCellValue('G2', '年限');
		$xls_obj->getActiveSheet()->setCellValue('H2', '金額');
		$xls_obj->getActiveSheet()->setCellValue('I2', '放置地點');
		$xls_obj->getActiveSheet()->setCellValue('J2', '保管人');
		$xls_obj->getActiveSheet()->setCellValue('K2', '到期日');
		$xls_obj->getActiveSheet()->setCellValue('L2', '逾期時間');

		$start_row = 3;
		$index = 1;
		foreach($objs as $t):
			$xls_obj->getActiveSheet()->setCellValue('A'.$start_row, $index);
			$xls_obj->getActiveSheet()->setCellValue('B'.$start_row, $t->total_no);
			$xls_obj->getActiveSheet()->setCellValue('C'.$start_row, $t->sub_no);
			$xls_obj->getActiveSheet()->setCellValue('D'.$start_row, $t->name);
			$xls_obj->getActiveSheet()->setCellValue('E'.$start_row, $t->qty);
			$xls_obj->getActiveSheet()->setCellValue('F'.$start_row, $t->buy_date);
			$xls_obj->getActiveSheet()->setCellValue('G'.$start_row, $t->years);
			$xls_obj->getActiveSheet()->setCellValue('H'.$start_row, $t->amount);
			$xls_obj->getActiveSheet()->setCellValue('I'.$start_row, $t->location->name);
			$xls_obj->getActiveSheet()->setCellValue('J'.$start_row, $t->user->name);
			$xls_obj->getActiveSheet()->setCellValue('K'.$start_row, $t->expire_date);
			$xls_obj->getActiveSheet()->setCellValue('L'.$start_row, $t->expiration_time);
			$index++;
			$start_row++;
		endforeach;

		//設定格式到A2~L2欄位
		$xls_obj->getActiveSheet()->getStyle('A2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
		$xls_obj->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$xls_obj->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$xls_obj->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$xls_obj->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
		$xls_obj->getActiveSheet()->getStyle('A2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$xls_obj->getActiveSheet()->getStyle('A2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$xls_obj->getActiveSheet()->getStyle('A2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$xls_obj->getActiveSheet()->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$xls_obj->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);
		$xls_obj->getActiveSheet()->duplicateStyle( $xls_obj->getActiveSheet()->getStyle('A2'), 'B2:L2' );
		$xls_obj->getActiveSheet()->getStyle('L2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$xls_obj->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$xls_obj->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$xls_obj->getActiveSheet()->getColumnDimension('C')->setWidth(11);
		$xls_obj->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$xls_obj->getActiveSheet()->getColumnDimension('E')->setWidth(5);
		$xls_obj->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$xls_obj->getActiveSheet()->getColumnDimension('G')->setWidth(5);
		$xls_obj->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$xls_obj->getActiveSheet()->getColumnDimension('I')->setWidth(11);
		$xls_obj->getActiveSheet()->getColumnDimension('J')->setWidth(8);
		$xls_obj->getActiveSheet()->getColumnDimension('k')->setWidth(10);
		$xls_obj->getActiveSheet()->getColumnDimension('L')->setWidth(10);
		
		$n = $index+2;

		$xls_obj->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$xls_obj->getActiveSheet()->getStyle('A3')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$xls_obj->getActiveSheet()->getStyle('A3')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$xls_obj->getActiveSheet()->duplicateStyle( $xls_obj->getActiveSheet()->getStyle('A3'), 'A4:A'.$n );
		$xls_obj->getActiveSheet()->duplicateStyle( $xls_obj->getActiveSheet()->getStyle('A3'), 'E3:G'.$n );
		$xls_obj->getActiveSheet()->duplicateStyle( $xls_obj->getActiveSheet()->getStyle('A3'), 'I3:L'.$n );
		$xls_obj->getActiveSheet()->getStyle('L3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$xls_obj->getActiveSheet()->duplicateStyle( $xls_obj->getActiveSheet()->getStyle('L3'), 'L4:L'.$n );
		$xls_obj->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$xls_obj->getActiveSheet()->getStyle('B3')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$xls_obj->getActiveSheet()->getStyle('B3')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$xls_obj->getActiveSheet()->duplicateStyle( $xls_obj->getActiveSheet()->getStyle('B3'), 'B3:D'.$n );
		$xls_obj->getActiveSheet()->getStyle('D3')->getFont()->setSize(8);
		$xls_obj->getActiveSheet()->duplicateStyle( $xls_obj->getActiveSheet()->getStyle('D3'), 'D4:D'.$n );
		$xls_obj->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$xls_obj->getActiveSheet()->getStyle('H3')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$xls_obj->getActiveSheet()->getStyle('H3')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$xls_obj->getActiveSheet()->duplicateStyle( $xls_obj->getActiveSheet()->getStyle('H3'), 'H4:H'.$n);
		$xls_obj->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$xls_obj->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(2, 2);
		$xls_obj->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$xls_obj->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		Autoloader::add_class('PHPExcel_Writer_Excel5',APPPATH.'vendor/PHPExcel/Writer/Excel5.php');
		$objWriter = new PHPExcel_Writer_Excel5($xls_obj);
		$file = "asset_".date('Ymd').".xls";
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$file.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($xls_obj, 'Excel5');
		$objWriter->save('php://output');
	}
}