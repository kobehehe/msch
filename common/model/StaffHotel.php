<?php

class StaffHotel extends InitActiveRecord
{
	public function tableName()
	{
		return 'staff_hotel';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('name',$this->name);
		$criteria->compare('sign_time',$this->sign_time);
		$criteria->compare('sign_out_time',$this->sign_out_time);
		$criteria->compare('sign_place',$this->sign_place);
		$criteria->compare('place',$this->place);
		$criteria->compare('department_id',$this->department_id);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('fxiaoke_AppID',$this->fxiaoke_AppID);
		$criteria->compare('fxiaoke_APPSecret',$this->fxiaoke_APPSecret);
		$criteria->compare('permanentCode',$this->permanentCode);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
