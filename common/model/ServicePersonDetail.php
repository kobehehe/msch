<?php

class ServicePersonDetail extends InitActiveRecord
{
	public function tableName()
	{
		return 'service_person_detail';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('service_person_id',$this->service_person_id);
		$criteria->compare('city',$this->city);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('height',$this->height);
		$criteria->compare('hosting_style',$this->hosting_style);
		$criteria->compare('appearance_style',$this->appearance_style);
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
