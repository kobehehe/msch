<?php

class CityPartnerCustomer extends InitActiveRecord
{
	public function tableName()
	{
		return 'city_partner_customer';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('city_partner_id',$this->city_partner_id,true);
		$criteria->compare('customer_company_id',$this->customer_company_id,true);
		$criteria->compare('customer_charge_type',$this->customer_charge_type,true);
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
