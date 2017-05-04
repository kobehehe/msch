<?php

class ServiceAdData extends InitActiveRecord
{

	public function tableName()
	{
		return 'service_ad_data';
	}

	public function search()
	{

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('service_person_id',$this->service_person_id);
		$criteria->compare('data_type',$this->data_type,true);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('sort',$this->sort,true);
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
