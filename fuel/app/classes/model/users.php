<?php
class Model_Users extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'username',
		'password',
		'name',
		'email',
		'group',
		'location_id',
		'phone',
		'status',
		'profile_fields',
		'cell_phone',
		'last_login',
		'login_hash',
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
		'location' => array(
			'key_from' => 'location_id',
			'model_to' => 'Model_Locations',
			'key_to' => 'id'
		),
	);

	public static function get_user_name($id){
		$model = Model_Users::find($id);
		return $model->name;
	}

	public static function get_user_id($name){
		$model = Model_Users::find()->where('name',$name)->get_one();
		if($model){
			return $model->id;
		}else{
			return 0;
		}
	}
}