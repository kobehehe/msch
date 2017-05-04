<?php

class OrderMeeting extends InitActiveRecord
{
	public function tableName()
	{
		return 'order_meeting';
	}

	public function search()
	{
		
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('company_linkman_id',$this->company_linkman_id);
		$criteria->compare('layout_id',$this->layout_id);
		$criteria->compare('remark',$this->remark);
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
