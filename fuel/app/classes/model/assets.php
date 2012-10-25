<?php
class Model_Assets extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'user_id',
		'total_no',
		'sub_no',
		'name',
		'location_id',
		'buy_date',
		'expire_date',
		'expiration_time',
		'amount',
		'qty',
		'years',
		'note',
		'status',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => true,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => true,
		),
	);

	protected static $_belongs_to = array(
		'user' => array(
			'key_from' => 'user_id',
			'model_to' => 'Model_Users',
			'key_to' => 'id'
		),
		'location' => array(
			'key_from' => 'location_id',
			'model_to' => 'Model_Locations',
			'key_to' => 'id'
		)
	);

	public static function get_expire_day($date){
		$str = '';
		if($date != ''){
			$tmp_date = explode("/",$date);
			$sec = mktime(0,0,0,$tmp_date[1],$tmp_date[2],($tmp_date[0]+1911));
			$due_sec = mktime(0,0,0,date('m'),date('d'),date('Y')) - $sec;
			if($due_sec > 0){
				$due_days = ($due_sec/86400);
				$max_year = floor($due_days/365);
				$max_month = floor(($due_days-(365*$max_year))/30);
				$max_day = $due_days-(365*$max_year)-(30*$max_month);
	
				if($max_year > 0){
					$str.= $max_year.'年'; 
				}
				if($max_month > 0){
					$str.= $max_month.'月'; 
				}
				if($max_day > 0){
					$str.= $max_day.'天'; 
				}
			}
		}	
		return $str;
	}
}