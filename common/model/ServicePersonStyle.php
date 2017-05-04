<?php

class ServicePersonStyle extends InitActiveRecord
{
	public function tableName()
	{
		return 'service_person_style';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('service_type',$this->service_type,true);
		$criteria->compare('name',$this->name);
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
