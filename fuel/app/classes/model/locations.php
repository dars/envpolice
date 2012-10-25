<?php
class Model_Locations extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'name'
	);

	public static function get_location_id($name){
		$model = Model_Locations::find()->where('name',$name)->get_one();
		if($model){
			return $model->id;
		}else{
			return 0;
		}
	}
}