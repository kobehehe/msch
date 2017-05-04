<?php

class ServicePerson extends InitActiveRecord
{
	public function tableName()
	{
		return 'service_person';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('name',$this->name);
		$criteria->compare('show',$this->show);
		$criteria->compare('recommend',$this->recommend);
		$criteria->compare('style_id',$this->style_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('telephone',$this->telephone);
		$criteria->compare('weixin_id',$this->weixin_id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('staff_id',$this->staff_id,true);
		$criteria->compare('service_type',$this->service_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
