<?php

class StaffAttendance extends InitActiveRecord
{
	public function tableName()
	{
		return 'staff_attendance';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('place',$this->place);
		$criteria->compare('sign_date',$this->sign_date);
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