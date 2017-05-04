<?php

class ServiceProvince extends InitActiveRecord
{
	public function tableName()
	{
		return 'service_province';
	}
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('provice_name',$this->provice_name);
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
