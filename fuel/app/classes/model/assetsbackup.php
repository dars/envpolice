<?php
class Model_Assetsbackup extends \Orm\Model
{
	protected static $_table_name = 'assets_backup';
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
		'backup_at',
	);
}