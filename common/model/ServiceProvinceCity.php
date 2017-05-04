<?php
class ServiceProvinceCity extends InitActiveRecord
{
	public function tableName()
	{
		return 'service_province_city';
	}

	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('province_id',$this->province_id);
		$criteria->compare('city_name',$this->city_name);
		$criteria->compare('update_time',$this->update_time);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
