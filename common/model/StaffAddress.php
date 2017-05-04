<?php

class StaffAddress extends InitActiveRecord
{
	public function tableName()
	{
		return 'staff_address';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('link_name',$this->link_name);
		$criteria->compare('link_telephone',$this->link_telephone);
		$criteria->compare('address',$this->address);
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
