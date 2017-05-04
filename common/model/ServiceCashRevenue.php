<?php

class ServiceCashRevenue extends InitActiveRecord
{
	public function tableName()
	{
		return 'service_cash_revenue';
	}

	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('price',$this->price);
		$criteria->compare('payer_staff_id',$this->payer_staff_id);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('order_type',$this->order_type);
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
