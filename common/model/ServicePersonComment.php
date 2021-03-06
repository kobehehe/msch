<?php

class ServicePersonComment extends InitActiveRecord
{
	public function tableName()
	{
		return 'service_person_comment';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id,true);
		$criteria->compare('service_person_id',$this->service_person_id,true);
		$criteria->compare('comment',$this->comment,true);
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
