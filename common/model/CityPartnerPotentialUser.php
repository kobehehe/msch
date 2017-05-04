<?php

class CityPartnerPotentialUser extends InitActiveRecord
{
	public function tableName()
	{
		return 'city_partner_potential_user';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('partner_id',$this->partner_id);
		$criteria->compare('user_staff_id',$this->user_staff_id);
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
