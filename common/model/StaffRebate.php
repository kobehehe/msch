<?php

class StaffRebate extends InitActiveRecord
{
	public function tableName()
	{
		return 'staff_rebate';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('rebate',$this->rebate,true);
		$criteria->compare('supplier_order_id',$this->supplier_order_id,true);
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
