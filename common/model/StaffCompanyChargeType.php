<?php

class StaffCompanyChargeType extends InitActiveRecord
{
	public function tableName()
	{
		return 'staff_company_charge_type';
	}
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('type_name',$this->type_name);
		$criteria->compare('y_or_m',$this->y_or_m);
		$criteria->compare('recommend',$this->recommend);
		$criteria->compare('charge_price',$this->charge_price);
		$criteria->compare('discount_data',$this->discount_data);
		$criteria->compare('staff_amount',$this->staff_amount);
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
