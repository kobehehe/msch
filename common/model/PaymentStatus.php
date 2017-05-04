<?php

class PaymentStatus extends InitActiveRecord
{
	public function tableName()
	{
		return 'payment_status';
	}
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('trade_no',$this->trade_no);
		$criteria->compare('telephone',$this->telephone);
		$criteria->compare('price',$this->price);
		$criteria->compare('status',$this->status);
		$criteria->compare('charge_type',$this->charge_type);
		$criteria->compare('company_charge_type',$this->company_charge_type);
		$criteria->compare('remark',$this->remark);
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