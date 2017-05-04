<?php

class SupplierOrder extends InitActiveRecord
{
	public function tableName()
	{
		return 'supplier_order';
	}
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('supplier_id',$this->supplier_id,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('applicant_id',$this->applicant_id,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('exp_num',$this->exp_num,true);
		$criteria->compare('exp_name',$this->exp_name,true);
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
