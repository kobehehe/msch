<?php

class StaffCid extends InitActiveRecord
{
	public function tableName()
	{
		return 'staff_cid';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('cid',$this->cid);
		$criteria->compare('DeviceToken',$this->DeviceToken);
		$criteria->compare('appid',$this->appid);
		$criteria->compare('appkey',$this->appkey);
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
