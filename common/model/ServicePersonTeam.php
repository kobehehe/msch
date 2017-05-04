<?php

class ServicePersonTeam extends InitActiveRecord
{

	public function tableName()
	{
		return 'service_person_team';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('corpid',$this->corpid);
		$criteria->compare('corpsecret',$this->corpsecret);
		$criteria->compare('name',$this->name);
		$criteria->compare('service_type',$this->service_type);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('icon',$this->icon);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
