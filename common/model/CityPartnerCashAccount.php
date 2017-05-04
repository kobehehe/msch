<?php

class CityPartnerCashAccount extends InitActiveRecord
{
	public function tableName()
	{
		return 'city_partner_cash_account';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('data',$this->data);
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
