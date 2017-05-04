<?php
class OrderModelRecommend extends InitActiveRecord
{
	public function tableName()
	{
		return 'order_model_recommend';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
