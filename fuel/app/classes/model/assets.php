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
		'amount',
		'qty',
		'years',
		'note',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
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
}