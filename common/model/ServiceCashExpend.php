<?php

class ServiceCashExpend extends InitActiveRecord
{
	public function tableName()
	{
		return 'service_cash_expend';
	}

	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('price',$this->price);
		$criteria->compare('expend_account',$this->expend_account);
		$criteria->compare('apply_id',$this->apply_id);
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
