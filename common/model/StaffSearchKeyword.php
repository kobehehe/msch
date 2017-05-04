<?php

class StaffSearchKeyword extends InitActiveRecord
{

	public function tableName()
	{
		return 'staff_search_keyword';
	}

	public function search()
	{

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('keyword',$this->keyword);
		$criteria->compare('show',$this->show);
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
